package settlement

import (
	"context"
	"errors"
	"math"
	"strconv"

	"github.com/jackc/pgx/v5"
	"github.com/jackc/pgx/v5/pgxpool"
)

// Kesalahan domain.
var (
	ErrNotFound  = errors.New("data tidak ditemukan")
	ErrForbidden = errors.New("tidak berhak atas aksi ini")
	ErrBadState  = errors.New("status tidak memenuhi syarat untuk aksi ini")
)

// Repository menutup transaksi + sengketa di App DB.
type Repository struct{ pool *pgxpool.Pool }

// NewRepository membuat repository settlement.
func NewRepository(pool *pgxpool.Pool) *Repository { return &Repository{pool: pool} }

func fee(gross, pct float64) (koperasiFee, net float64) {
	koperasiFee = math.Round(gross*pct) / 100
	return koperasiFee, gross - koperasiFee
}

// feePercent membaca komisi koperasi terkini dari tabel settings (fallback KoperasiFeePercent).
func (r *Repository) feePercent(ctx context.Context) float64 {
	var v string
	if err := r.pool.QueryRow(ctx, `SELECT value FROM settings WHERE key='koperasi_fee_percent'`).Scan(&v); err == nil {
		if f, e := strconv.ParseFloat(v, 64); e == nil {
			return f
		}
	}
	return KoperasiFeePercent
}

// VerifyPledge (alur A): pembeli konfirmasi terima → DELIVERED + catat demand_transactions.
func (r *Repository) VerifyPledge(ctx context.Context, pledgeID, buyerID string, qtyReceived *int) (*Txn, error) {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return nil, err
	}
	defer tx.Rollback(ctx)

	var wargaID, owner, status, itemName string
	var qtyPledged int
	var pPrice, dPrice *float64
	err = tx.QueryRow(ctx, `
		SELECT p.warga_id::text, p.qty_pledged, p.pledge_status, p.price_per_item::float8,
			d.buyer_id::text, d.target_price_per_item::float8, COALESCE(d.item_name,'')
		FROM demand_pledges p JOIN demands d ON d.id=p.demand_id
		WHERE p.id=$1 FOR UPDATE OF p`, pledgeID).Scan(&wargaID, &qtyPledged, &status, &pPrice, &owner, &dPrice, &itemName)
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}
	if owner != buyerID {
		return nil, ErrForbidden
	}
	if status != "CONFIRMED" {
		return nil, ErrBadState
	}
	qty := qtyPledged
	if qtyReceived != nil {
		qty = *qtyReceived
	}
	if qty <= 0 || qty > qtyPledged {
		return nil, errors.New("qty_received harus 1..")
	}
	price := valueOr(pPrice, dPrice)
	gross := float64(qty) * price
	kfee, net := fee(gross, r.feePercent(ctx))

	if _, err := tx.Exec(ctx, `UPDATE demand_pledges SET pledge_status='DELIVERED', qty_delivered=$2, user_update=$3 WHERE id=$1`,
		pledgeID, qty, buyerID); err != nil {
		return nil, err
	}
	var id string
	if err := tx.QueryRow(ctx, `
		INSERT INTO demand_transactions (demand_pledge_id, gross_amount, koperasi_fee, net_amount)
		VALUES ($1,$2,$3,$4) RETURNING id::text`, pledgeID, gross, kfee, net).Scan(&id); err != nil {
		return nil, err
	}
	if err := tx.Commit(ctx); err != nil {
		return nil, err
	}
	return &Txn{ID: id, Kind: "demand", Item: itemName, RefID: pledgeID, Gross: gross, KoperasiFee: kfee, NetAmount: net, Pay: "UNPAID", WargaID: wargaID, BuyerID: buyerID}, nil
}

// VerifyOrder (alur B): pembeli konfirmasi terima → DONE + catat supply_transactions.
func (r *Repository) VerifyOrder(ctx context.Context, orderID, buyerID string) (*Txn, error) {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return nil, err
	}
	defer tx.Rollback(ctx)

	var owner, status, itemName string
	var total float64
	err = tx.QueryRow(ctx, `
		SELECT o.buyer_id::text, o.order_status, o.total_amount::float8, COALESCE(l.item_name,'')
		FROM orders o LEFT JOIN supply_listings l ON l.id=o.listing_id
		WHERE o.id=$1 FOR UPDATE OF o`, orderID).Scan(&owner, &status, &total, &itemName)
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}
	if owner != buyerID {
		return nil, ErrForbidden
	}
	if status != "CONFIRMED" {
		return nil, ErrBadState
	}
	kfee, net := fee(total, r.feePercent(ctx))
	if _, err := tx.Exec(ctx, `UPDATE orders SET order_status='DONE', user_update=$2 WHERE id=$1`, orderID, buyerID); err != nil {
		return nil, err
	}
	var id string
	if err := tx.QueryRow(ctx, `
		INSERT INTO supply_transactions (order_id, gross_amount, koperasi_fee, net_amount)
		VALUES ($1,$2,$3,$4) RETURNING id::text`, orderID, total, kfee, net).Scan(&id); err != nil {
		return nil, err
	}
	if err := tx.Commit(ctx); err != nil {
		return nil, err
	}
	return &Txn{ID: id, Kind: "supply", Item: itemName, RefID: orderID, Gross: total, KoperasiFee: kfee, NetAmount: net, Pay: "UNPAID", BuyerID: buyerID}, nil
}

// Dispute mencatat sengketa untuk pledge (DEMAND) atau order (SUPPLY).
func (r *Repository) Dispute(ctx context.Context, kind, refID, reporterID, reason string) (string, error) {
	var id string
	var err error
	if kind == "DEMAND" {
		err = r.pool.QueryRow(ctx, `INSERT INTO disputes (demand_pledge_id, source_type, reported_by, reason)
			VALUES ($1,'DEMAND',$2,$3) RETURNING id::text`, refID, reporterID, reason).Scan(&id)
	} else {
		err = r.pool.QueryRow(ctx, `INSERT INTO disputes (order_id, source_type, reported_by, reason)
			VALUES ($1,'SUPPLY',$2,$3) RETURNING id::text`, refID, reporterID, reason).Scan(&id)
	}
	return id, err
}

// Dispute (baris) untuk daftar/kelola sengketa.
type DisputeRow struct {
	ID             string  `json:"id"`
	DemandPledgeID *string `json:"demand_pledge_id"`
	OrderID        *string `json:"order_id"`
	SourceType     string  `json:"source_type"`
	ReportedBy     string  `json:"reported_by"`
	Reason         string  `json:"reason"`
	DisputeStatus  string  `json:"dispute_status"`
	Resolution     *string `json:"resolution"`
}

// ListDisputes mengembalikan sengketa (filter status opsional).
func (r *Repository) ListDisputes(ctx context.Context, status string) ([]DisputeRow, error) {
	rows, err := r.pool.Query(ctx, `
		SELECT id::text, demand_pledge_id::text, order_id::text, source_type,
			reported_by::text, reason, dispute_status, resolution
		FROM disputes WHERE ($1='' OR dispute_status=$1) ORDER BY tanggal_input DESC`, status)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []DisputeRow{}
	for rows.Next() {
		var d DisputeRow
		if err := rows.Scan(&d.ID, &d.DemandPledgeID, &d.OrderID, &d.SourceType,
			&d.ReportedBy, &d.Reason, &d.DisputeStatus, &d.Resolution); err != nil {
			return nil, err
		}
		out = append(out, d)
	}
	return out, rows.Err()
}

// ResolveDispute memperbarui status sengketa (REVIEW/RESOLVED) + resolusi.
func (r *Repository) ResolveDispute(ctx context.Context, id, reviewerID, status string, resolution *string) (int64, error) {
	ct, err := r.pool.Exec(ctx, `UPDATE disputes
		SET dispute_status=$2, resolution=$3, resolved_at = CASE WHEN $2='RESOLVED' THEN now() ELSE resolved_at END, user_update=$4
		WHERE id=$1`, id, status, resolution, reviewerID)
	if err != nil {
		return 0, err
	}
	return ct.RowsAffected(), nil
}

// UpdatePayment memperbarui status pembayaran sebuah transaksi (PAID mengisi paid_at).
func (r *Repository) UpdatePayment(ctx context.Context, kind, id, status string) (int64, error) {
	table := "demand_transactions"
	if kind == "supply" {
		table = "supply_transactions"
	}
	q := `UPDATE ` + table + ` SET payment_status=$2 WHERE id=$1`
	if status == "PAID" {
		q = `UPDATE ` + table + ` SET payment_status=$2, paid_at=now() WHERE id=$1`
	}
	ct, err := r.pool.Exec(ctx, q, id, status)
	if err != nil {
		return 0, err
	}
	return ct.RowsAffected(), nil
}

// List mengembalikan transaksi relevan untuk actor (admin melihat semua).
func (r *Repository) List(ctx context.Context, actorID, actorRole string) ([]Txn, error) {
	rows, err := r.pool.Query(ctx, `
		SELECT * FROM (
			SELECT dt.id::text id, 'demand' kind, dt.demand_pledge_id::text ref_id,
				dt.gross_amount::float8 gross, dt.koperasi_fee::float8 kfee, dt.net_amount::float8 net,
				dt.payment_method method, dt.payment_status status, COALESCE(d.item_name,'') item_name,
				(COALESCE(wu.name,'Warga') || ' → ' || COALESCE(bu.name,'Pembeli')) pihak,
				d.buyer_id::text buyer_id, p.warga_id::text warga_id, dt.tanggal_input ts
			FROM demand_transactions dt
			JOIN demand_pledges p ON p.id=dt.demand_pledge_id
			JOIN demands d ON d.id=p.demand_id
			LEFT JOIN users bu ON bu.id=d.buyer_id
			LEFT JOIN users wu ON wu.id=p.warga_id
			UNION ALL
			SELECT st.id::text, 'supply', st.order_id::text,
				st.gross_amount::float8, st.koperasi_fee::float8, st.net_amount::float8,
				st.payment_method, st.payment_status, COALESCE(l.item_name,''),
				(COALESCE(wu.name,'Warga') || ' → ' || COALESCE(bu.name,'Pembeli')),
				o.buyer_id::text, l.warga_id::text, st.tanggal_input
			FROM supply_transactions st
			JOIN orders o ON o.id=st.order_id
			JOIN supply_listings l ON l.id=o.listing_id
			LEFT JOIN users bu ON bu.id=o.buyer_id
			LEFT JOIN users wu ON wu.id=l.warga_id
		) t
		WHERE $2='ADMIN_KOPERASI' OR t.buyer_id=$1 OR t.warga_id=$1
		ORDER BY t.ts DESC`, actorID, actorRole)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []Txn{}
	for rows.Next() {
		var t Txn
		var ts any
		if err := rows.Scan(&t.ID, &t.Kind, &t.RefID, &t.Gross, &t.KoperasiFee, &t.NetAmount,
			&t.PaymentMethod, &t.Pay, &t.Item, &t.Pihak, &t.BuyerID, &t.WargaID, &ts); err != nil {
			return nil, err
		}
		out = append(out, t)
	}
	return out, rows.Err()
}

// Pembukuan menghitung rekap komisi koperasi.
func (r *Repository) Pembukuan(ctx context.Context) (*Pembukuan, error) {
	var p Pembukuan
	err := r.pool.QueryRow(ctx, `
		SELECT
			COALESCE((SELECT SUM(koperasi_fee) FROM demand_transactions),0)::float8,
			COALESCE((SELECT SUM(koperasi_fee) FROM supply_transactions),0)::float8,
			COALESCE((SELECT SUM(gross_amount) FROM demand_transactions),0)::float8 +
			COALESCE((SELECT SUM(gross_amount) FROM supply_transactions),0)::float8,
			(SELECT COUNT(*) FROM demand_transactions) + (SELECT COUNT(*) FROM supply_transactions)`).
		Scan(&p.KomisiDemand, &p.KomisiSupply, &p.TotalGross, &p.JumlahTransaksi)
	if err != nil {
		return nil, err
	}
	p.TotalKomisi = p.KomisiDemand + p.KomisiSupply
	return &p, nil
}

// Insights menghitung ringkasan demand & supply untuk dashboard admin.
func (r *Repository) Insights(ctx context.Context) (*Insights, error) {
	ins := &Insights{DemandByStatus: map[string]int{}, TopSelling: []TopItem{}}

	rows, err := r.pool.Query(ctx, `SELECT demand_status, count(*),
		COALESCE(SUM(total_qty*target_price_per_item),0)::float8
		FROM demands GROUP BY demand_status`)
	if err != nil {
		return nil, err
	}
	for rows.Next() {
		var st string
		var c int
		var v float64
		if err := rows.Scan(&st, &c, &v); err != nil {
			rows.Close()
			return nil, err
		}
		ins.DemandByStatus[st] = c
		ins.DemandTotal += c
		ins.DemandValue += v
	}
	rows.Close()
	if err := rows.Err(); err != nil {
		return nil, err
	}

	if err := r.pool.QueryRow(ctx, `SELECT count(*), COALESCE(SUM(qty_sold),0),
		COALESCE(SUM(qty_sold*price_per_item),0)::float8, COUNT(*) FILTER (WHERE qty_sold>0)
		FROM supply_listings`).Scan(&ins.ListingTotal, &ins.SupplySold, &ins.SupplyRevenue, &ins.LarisCount); err != nil {
		return nil, err
	}

	trows, err := r.pool.Query(ctx, `SELECT item_name, satuan, qty_sold, (qty_sold*price_per_item)::float8
		FROM supply_listings WHERE qty_sold>0 ORDER BY qty_sold DESC LIMIT 5`)
	if err != nil {
		return nil, err
	}
	defer trows.Close()
	for trows.Next() {
		var t TopItem
		if err := trows.Scan(&t.ItemName, &t.Satuan, &t.Sold, &t.Revenue); err != nil {
			return nil, err
		}
		ins.TopSelling = append(ins.TopSelling, t)
	}
	return ins, trows.Err()
}

func valueOr(a, b *float64) float64 {
	if a != nil {
		return *a
	}
	if b != nil {
		return *b
	}
	return 0
}
