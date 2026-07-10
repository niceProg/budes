package settlement

import (
	"context"
	"errors"
	"math"

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

func fee(gross float64) (koperasiFee, net float64) {
	koperasiFee = math.Round(gross*KoperasiFeePercent) / 100
	return koperasiFee, gross - koperasiFee
}

// VerifyPledge (alur A): pembeli konfirmasi terima → HANDED_TO_BUYER + catat demand_transactions.
func (r *Repository) VerifyPledge(ctx context.Context, pledgeID, buyerID string, qtyReceived *int) (*Txn, error) {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return nil, err
	}
	defer tx.Rollback(ctx)

	var wargaID, owner, status string
	var qtyPledged int
	var pPrice, dPrice *float64
	err = tx.QueryRow(ctx, `
		SELECT p.warga_id::text, p.qty_pledged, p.pledge_status, p.price_per_item::float8,
			d.buyer_id::text, d.target_price_per_item::float8
		FROM demand_pledges p JOIN demands d ON d.id=p.demand_id
		WHERE p.id=$1 FOR UPDATE OF p`, pledgeID).Scan(&wargaID, &qtyPledged, &status, &pPrice, &owner, &dPrice)
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}
	if owner != buyerID {
		return nil, ErrForbidden
	}
	if status != "DELIVERED_TO_KOPERASI" {
		return nil, ErrBadState
	}
	qty := qtyPledged
	if qtyReceived != nil {
		qty = *qtyReceived
	}
	if qty <= 0 || qty > qtyPledged {
		return nil, errors.New("qty_received harus 1.." )
	}
	price := valueOr(pPrice, dPrice)
	gross := float64(qty) * price
	kfee, net := fee(gross)

	if _, err := tx.Exec(ctx, `UPDATE demand_pledges SET pledge_status='HANDED_TO_BUYER', qty_delivered=$2, user_update=$3 WHERE id=$1`,
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
	return &Txn{ID: id, Kind: "DEMAND", RefID: pledgeID, GrossAmount: gross, KoperasiFee: kfee, NetAmount: net, PaymentStatus: "UNPAID", WargaID: wargaID, BuyerID: buyerID}, nil
}

// VerifyOrder (alur B): pembeli konfirmasi terima → HANDED_OVER + catat supply_transactions.
func (r *Repository) VerifyOrder(ctx context.Context, orderID, buyerID string) (*Txn, error) {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return nil, err
	}
	defer tx.Rollback(ctx)

	var owner, status string
	var total float64
	err = tx.QueryRow(ctx, `SELECT buyer_id::text, order_status, total_amount::float8 FROM orders WHERE id=$1 FOR UPDATE`, orderID).
		Scan(&owner, &status, &total)
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
	kfee, net := fee(total)
	if _, err := tx.Exec(ctx, `UPDATE orders SET order_status='HANDED_OVER', user_update=$2 WHERE id=$1`, orderID, buyerID); err != nil {
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
	return &Txn{ID: id, Kind: "SUPPLY", RefID: orderID, GrossAmount: total, KoperasiFee: kfee, NetAmount: net, PaymentStatus: "UNPAID", BuyerID: buyerID}, nil
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
			SELECT dt.id::text id, 'DEMAND' kind, dt.demand_pledge_id::text ref_id,
				dt.gross_amount::float8 gross, dt.koperasi_fee::float8 kfee, dt.net_amount::float8 net,
				dt.payment_method method, dt.payment_status status, COALESCE(d.item_name,'') item_name,
				d.buyer_id::text buyer_id, p.warga_id::text warga_id, dt.tanggal_input ts
			FROM demand_transactions dt
			JOIN demand_pledges p ON p.id=dt.demand_pledge_id
			JOIN demands d ON d.id=p.demand_id
			UNION ALL
			SELECT st.id::text, 'SUPPLY', st.order_id::text,
				st.gross_amount::float8, st.koperasi_fee::float8, st.net_amount::float8,
				st.payment_method, st.payment_status, COALESCE(l.item_name,''),
				o.buyer_id::text, l.warga_id::text, st.tanggal_input
			FROM supply_transactions st
			JOIN orders o ON o.id=st.order_id
			JOIN supply_listings l ON l.id=o.listing_id
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
		if err := rows.Scan(&t.ID, &t.Kind, &t.RefID, &t.GrossAmount, &t.KoperasiFee, &t.NetAmount,
			&t.PaymentMethod, &t.PaymentStatus, &t.ItemName, &t.BuyerID, &t.WargaID, &ts); err != nil {
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

func valueOr(a, b *float64) float64 {
	if a != nil {
		return *a
	}
	if b != nil {
		return *b
	}
	return 0
}
