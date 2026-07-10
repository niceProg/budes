package demand

import (
	"context"
	"errors"

	"github.com/jackc/pgx/v5"
	"github.com/jackc/pgx/v5/pgxpool"
)

// Kesalahan domain.
var (
	ErrNotFound       = errors.New("demand tidak ditemukan")
	ErrDemandNotOpen  = errors.New("demand tidak menerima sanggupan (harus OPEN/PARTIAL)")
	ErrOverPledge     = errors.New("jumlah melebihi sisa kebutuhan")
	ErrPledgeNotFound = errors.New("sanggupan tidak ditemukan")
	ErrForbidden      = errors.New("tidak berhak atas aksi ini")
	ErrBadTransition  = errors.New("transisi status tidak valid")
)

const demandCols = `id::text, buyer_id::text, koperasi_id::text, komoditas_id::text, item_name, satuan,
	total_qty, fulfilled_qty, target_price_per_item::float8, total_price::float8, dp_percent::float8,
	dp_amount::float8, remaining_amount::float8, dp_payment_method, dp_status, dp_paid_at, deadline, demand_status`

// Repository operasi tabel demands & demand_pledges.
type Repository struct{ pool *pgxpool.Pool }

// NewRepository membuat repository demand.
func NewRepository(pool *pgxpool.Pool) *Repository { return &Repository{pool: pool} }

func scanDemand(row pgx.Row) (*Demand, error) {
	var d Demand
	err := row.Scan(&d.ID, &d.BuyerID, &d.KoperasiID, &d.KomoditasID, &d.ItemName, &d.Satuan,
		&d.TotalQty, &d.FulfilledQty, &d.TargetPricePerItem, &d.TotalPrice, &d.DPPercent,
		&d.DPAmount, &d.RemainingAmount, &d.DPPaymentMethod, &d.DPStatus, &d.DPPaidAt,
		&d.Deadline, &d.DemandStatus)
	if err != nil {
		return nil, err
	}
	return &d, nil
}

// Create menyimpan demand baru (status DRAFT) & kembalikan id.
func (r *Repository) Create(ctx context.Context, buyerID string, in CreateInput, totalPrice, dpAmount, remaining float64) (string, error) {
	var id string
	err := r.pool.QueryRow(ctx, `
		INSERT INTO demands (buyer_id, koperasi_id, komoditas_id, item_name, satuan, total_qty,
			target_price_per_item, total_price, dp_amount, remaining_amount, deadline)
		VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11) RETURNING id::text`,
		buyerID, in.KoperasiID, in.KomoditasID, in.ItemName, in.Satuan, in.TotalQty,
		in.TargetPricePerItem, totalPrice, dpAmount, remaining, in.Deadline).Scan(&id)
	return id, err
}

// MarkDPPaid menandai DP terbayar & mendorong demand DRAFT → OPEN. Kembalikan rows terpengaruh.
func (r *Repository) MarkDPPaid(ctx context.Context, id, buyerID, method string) (int64, error) {
	ct, err := r.pool.Exec(ctx, `
		UPDATE demands SET dp_status='PAID', dp_payment_method=$3, dp_paid_at=now(),
			demand_status='OPEN', user_update=$4
		WHERE id=$1 AND buyer_id=$2 AND dp_status='UNPAID' AND demand_status='DRAFT'`,
		id, buyerID, method, buyerID)
	if err != nil {
		return 0, err
	}
	return ct.RowsAffected(), nil
}

// List mengembalikan demand dengan status tertentu (untuk etalase publik), berpaginasi.
func (r *Repository) List(ctx context.Context, statuses []string, limit, offset int) ([]Demand, error) {
	rows, err := r.pool.Query(ctx, `SELECT `+demandCols+` FROM demands
		WHERE demand_status = ANY($1) ORDER BY tanggal_input DESC LIMIT $2 OFFSET $3`, statuses, limit, offset)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []Demand{}
	for rows.Next() {
		d, err := scanDemand(rows)
		if err != nil {
			return nil, err
		}
		out = append(out, *d)
	}
	return out, rows.Err()
}

// ByBuyer mengembalikan semua demand milik pembeli (semua status).
func (r *Repository) ByBuyer(ctx context.Context, buyerID string) ([]Demand, error) {
	rows, err := r.pool.Query(ctx, `SELECT `+demandCols+` FROM demands
		WHERE buyer_id=$1 ORDER BY tanggal_input DESC`, buyerID)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []Demand{}
	for rows.Next() {
		d, err := scanDemand(rows)
		if err != nil {
			return nil, err
		}
		out = append(out, *d)
	}
	return out, rows.Err()
}

// Get mengambil satu demand beserta daftar pledges-nya.
func (r *Repository) Get(ctx context.Context, id string) (*Demand, error) {
	d, err := scanDemand(r.pool.QueryRow(ctx, `SELECT `+demandCols+` FROM demands WHERE id=$1`, id))
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}
	pledges, err := r.PledgesByDemand(ctx, id)
	if err != nil {
		return nil, err
	}
	d.Pledges = pledges
	return d, nil
}

// PledgesByDemand mengambil sanggupan sebuah demand (+ nama warga).
func (r *Repository) PledgesByDemand(ctx context.Context, demandID string) ([]Pledge, error) {
	rows, err := r.pool.Query(ctx, `
		SELECT p.id::text, p.demand_id::text, p.warga_id::text, COALESCE(u.name,''),
			p.qty_pledged, p.qty_delivered, p.price_per_item::float8, p.pledge_status
		FROM demand_pledges p LEFT JOIN users u ON u.id = p.warga_id
		WHERE p.demand_id=$1 ORDER BY p.tanggal_input`, demandID)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	return scanPledges(rows)
}

// PledgesByWarga mengambil sanggupan milik seorang warga.
func (r *Repository) PledgesByWarga(ctx context.Context, wargaID string) ([]Pledge, error) {
	rows, err := r.pool.Query(ctx, `
		SELECT p.id::text, p.demand_id::text, p.warga_id::text, '',
			p.qty_pledged, p.qty_delivered, p.price_per_item::float8, p.pledge_status
		FROM demand_pledges p WHERE p.warga_id=$1 ORDER BY p.tanggal_input DESC`, wargaID)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	return scanPledges(rows)
}

func scanPledges(rows pgx.Rows) ([]Pledge, error) {
	out := []Pledge{}
	for rows.Next() {
		var p Pledge
		if err := rows.Scan(&p.ID, &p.DemandID, &p.WargaID, &p.WargaName,
			&p.QtyPledged, &p.QtyDelivered, &p.PricePerItem, &p.PledgeStatus); err != nil {
			return nil, err
		}
		out = append(out, p)
	}
	return out, rows.Err()
}

// CreatePledge menyanggupi demand secara aman (anti over-pledge, update fulfilled + status).
func (r *Repository) CreatePledge(ctx context.Context, demandID, wargaID string, qty int, price *float64) (*Pledge, error) {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return nil, err
	}
	defer tx.Rollback(ctx)

	var total, fulfilled int
	var status string
	err = tx.QueryRow(ctx, `SELECT total_qty, fulfilled_qty, demand_status FROM demands WHERE id=$1 FOR UPDATE`, demandID).
		Scan(&total, &fulfilled, &status)
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}
	if status != "OPEN" && status != "PARTIAL" {
		return nil, ErrDemandNotOpen
	}
	if qty > total-fulfilled {
		return nil, ErrOverPledge
	}

	var pid string
	if err := tx.QueryRow(ctx, `
		INSERT INTO demand_pledges (demand_id, warga_id, qty_pledged, price_per_item, user_input)
		VALUES ($1,$2,$3,$4,$5) RETURNING id::text`, demandID, wargaID, qty, price, wargaID).Scan(&pid); err != nil {
		return nil, err
	}

	newFulfilled := fulfilled + qty
	newStatus := "PARTIAL"
	if newFulfilled >= total {
		newStatus = "FULFILLED"
	}
	if _, err := tx.Exec(ctx, `UPDATE demands SET fulfilled_qty=$2, demand_status=$3 WHERE id=$1`,
		demandID, newFulfilled, newStatus); err != nil {
		return nil, err
	}
	if err := tx.Commit(ctx); err != nil {
		return nil, err
	}
	return &Pledge{ID: pid, DemandID: demandID, WargaID: wargaID, QtyPledged: qty, PricePerItem: price, PledgeStatus: "PLEDGED"}, nil
}

// forwardTransitions untuk PUT generik. DELIVERED hanya via endpoint verifikasi (Modul D).
var forwardTransitions = map[string][]string{
	"PLEDGED":   {"CONFIRMED", "CANCELLED"},
	"CONFIRMED": {"CANCELLED"},
}

// UpdatePledgeStatus memvalidasi transisi & otorisasi, lalu memperbarui status.
// CANCELLED mengembalikan kuota ke demand (kurangi fulfilled_qty).
func (r *Repository) UpdatePledgeStatus(ctx context.Context, pledgeID, actorID, actorRole, newStatus string) error {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return err
	}
	defer tx.Rollback(ctx)

	var demandID, wargaID, cur string
	var qty int
	err = tx.QueryRow(ctx, `SELECT demand_id::text, warga_id::text, qty_pledged, pledge_status
		FROM demand_pledges WHERE id=$1 FOR UPDATE`, pledgeID).Scan(&demandID, &wargaID, &qty, &cur)
	if errors.Is(err, pgx.ErrNoRows) {
		return ErrPledgeNotFound
	}
	if err != nil {
		return err
	}
	if actorRole != "ADMIN_KOPERASI" && actorID != wargaID {
		return ErrForbidden
	}
	if !allowed(forwardTransitions[cur], newStatus) {
		return ErrBadTransition
	}
	if _, err := tx.Exec(ctx, `UPDATE demand_pledges SET pledge_status=$2, user_update=$3 WHERE id=$1`,
		pledgeID, newStatus, actorID); err != nil {
		return err
	}
	if newStatus == "CANCELLED" {
		if err := releaseQty(ctx, tx, demandID, qty); err != nil {
			return err
		}
	}
	return tx.Commit(ctx)
}

// releaseQty mengurangi fulfilled_qty & menghitung ulang status demand setelah pembatalan.
func releaseQty(ctx context.Context, tx pgx.Tx, demandID string, qty int) error {
	var total, fulfilled int
	var dpStatus string
	if err := tx.QueryRow(ctx, `SELECT total_qty, fulfilled_qty, dp_status FROM demands WHERE id=$1 FOR UPDATE`, demandID).
		Scan(&total, &fulfilled, &dpStatus); err != nil {
		return err
	}
	fulfilled -= qty
	if fulfilled < 0 {
		fulfilled = 0
	}
	status := "PARTIAL"
	if fulfilled == 0 {
		status = "OPEN"
	} else if fulfilled >= total {
		status = "FULFILLED"
	}
	_, err := tx.Exec(ctx, `UPDATE demands SET fulfilled_qty=$2, demand_status=$3 WHERE id=$1`, demandID, fulfilled, status)
	return err
}

// Cancel membatalkan demand oleh pembeli: DP PAID → FORFEITED, status → CANCELLED.
func (r *Repository) Cancel(ctx context.Context, id, buyerID string) error {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return err
	}
	defer tx.Rollback(ctx)

	var owner, status, dpStatus string
	err = tx.QueryRow(ctx, `SELECT buyer_id::text, demand_status, dp_status FROM demands WHERE id=$1 FOR UPDATE`, id).
		Scan(&owner, &status, &dpStatus)
	if errors.Is(err, pgx.ErrNoRows) {
		return ErrNotFound
	}
	if err != nil {
		return err
	}
	if owner != buyerID {
		return ErrForbidden
	}
	if status == "FULFILLED" || status == "CANCELLED" {
		return ErrBadTransition
	}
	newDP := dpStatus
	if dpStatus == "PAID" {
		newDP = "FORFEITED"
	}
	_, err = tx.Exec(ctx, `UPDATE demands SET demand_status='CANCELLED', dp_status=$2, user_update=$3 WHERE id=$1`, id, newDP, buyerID)
	if err != nil {
		return err
	}
	return tx.Commit(ctx)
}

// ExpireDemand (ADMIN_KOPERASI): akhiri demand yang gagal → CANCELLED + DP REFUNDED (bila sudah PAID).
func (r *Repository) ExpireDemand(ctx context.Context, id string) (int64, error) {
	ct, err := r.pool.Exec(ctx, `UPDATE demands
		SET demand_status='CANCELLED',
		    dp_status = CASE WHEN dp_status='PAID' THEN 'REFUNDED' ELSE dp_status END
		WHERE id=$1 AND demand_status IN ('DRAFT','OPEN','PARTIAL')`, id)
	if err != nil {
		return 0, err
	}
	return ct.RowsAffected(), nil
}

func allowed(list []string, v string) bool {
	for _, s := range list {
		if s == v {
			return true
		}
	}
	return false
}
