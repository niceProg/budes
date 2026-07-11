package payment

import (
	"context"
	"errors"

	"github.com/jackc/pgx/v5"
	"github.com/jackc/pgx/v5/pgxpool"
)

// Kesalahan domain.
var (
	ErrNotFound  = errors.New("data tidak ditemukan")
	ErrForbidden = errors.New("bukan milik Anda")
	ErrBadState  = errors.New("status tidak memenuhi syarat untuk dibayar")
)

// Payment merepresentasikan baris tabel payments.
type Payment struct {
	ID        string
	Kind      string // DP | DEMAND_TXN | SUPPLY_TXN
	RefID     string
	Amount    float64
	Status    string
	createdBy string
}

// Repository operasi tabel payments + query pendukung.
type Repository struct{ pool *pgxpool.Pool }

// NewRepository membuat repository payment.
func NewRepository(pool *pgxpool.Pool) *Repository { return &Repository{pool: pool} }

// UserContact mengambil nama/email/telepon pengaju bayar (untuk payload Mayar).
func (r *Repository) UserContact(ctx context.Context, userID string) (name, email string, phone string, err error) {
	var ph *string
	err = r.pool.QueryRow(ctx, `SELECT name, email, phone FROM users WHERE id=$1`, userID).Scan(&name, &email, &ph)
	if ph != nil {
		phone = *ph
	}
	if phone == "" {
		phone = "08000000000" // Mayar mewajibkan mobile
	}
	return
}

// DemandForDP memvalidasi & mengambil info DP demand milik pembeli (harus DRAFT/UNPAID).
func (r *Repository) DemandForDP(ctx context.Context, demandID, buyerID string) (amount float64, item string, err error) {
	var owner, demandStatus, dpStatus string
	var dpAmount *float64
	e := r.pool.QueryRow(ctx, `SELECT buyer_id::text, demand_status, dp_status, dp_amount, item_name
		FROM demands WHERE id=$1`, demandID).Scan(&owner, &demandStatus, &dpStatus, &dpAmount, &item)
	if errors.Is(e, pgx.ErrNoRows) {
		return 0, "", ErrNotFound
	}
	if e != nil {
		return 0, "", e
	}
	if owner != buyerID {
		return 0, "", ErrForbidden
	}
	if dpStatus != "UNPAID" || demandStatus != "DRAFT" {
		return 0, "", ErrBadState
	}
	if dpAmount != nil {
		amount = *dpAmount
	}
	return amount, item, nil
}

// TxnForPay memvalidasi & mengambil info transaksi milik pembeli (harus UNPAID).
func (r *Repository) TxnForPay(ctx context.Context, kind, txnID, buyerID string) (amount float64, item string, err error) {
	var q string
	if kind == "DEMAND_TXN" {
		q = `SELECT d.buyer_id::text, dt.gross_amount, dt.payment_status, COALESCE(d.item_name,'')
			FROM demand_transactions dt JOIN demand_pledges p ON p.id=dt.demand_pledge_id
			JOIN demands d ON d.id=p.demand_id WHERE dt.id=$1`
	} else {
		q = `SELECT o.buyer_id::text, st.gross_amount, st.payment_status, COALESCE(l.item_name,'')
			FROM supply_transactions st JOIN orders o ON o.id=st.order_id
			LEFT JOIN supply_listings l ON l.id=o.listing_id WHERE st.id=$1`
	}
	var owner, payStatus string
	e := r.pool.QueryRow(ctx, q, txnID).Scan(&owner, &amount, &payStatus, &item)
	if errors.Is(e, pgx.ErrNoRows) {
		return 0, "", ErrNotFound
	}
	if e != nil {
		return 0, "", e
	}
	if owner != buyerID {
		return 0, "", ErrForbidden
	}
	if payStatus == "PAID" || payStatus == "SETTLED" {
		return 0, "", ErrBadState
	}
	return amount, item, nil
}

// Create menyimpan baris payment PENDING.
func (r *Repository) Create(ctx context.Context, kind, refID string, amount float64, txnID, linkID, link, email, createdBy string) (string, error) {
	var id string
	err := r.pool.QueryRow(ctx, `
		INSERT INTO payments (kind, ref_id, amount, mayar_transaction_id, mayar_link_id, link, customer_email, created_by)
		VALUES ($1,$2,$3,$4,$5,$6,$7,$8) RETURNING id::text`,
		kind, refID, amount, txnID, linkID, link, email, createdBy).Scan(&id)
	return id, err
}

// MarkPaidByMayar mencari payment PENDING via id transaksi/link Mayar, tandai PAID (idempoten).
// Kembalikan payment yang cocok untuk efek-samping; nil bila tak ada (sudah diproses / tak cocok).
func (r *Repository) MarkPaidByMayar(ctx context.Context, mayarID, email string, amount float64) (*Payment, error) {
	var p Payment
	err := r.pool.QueryRow(ctx, `
		UPDATE payments SET status='PAID', paid_at=now(), tanggal_update=now()
		WHERE id = (
			SELECT id FROM payments
			WHERE status='PENDING' AND (
				($1 <> '' AND (mayar_transaction_id=$1 OR mayar_link_id=$1))
				OR ($1 = '' AND $2 <> '' AND customer_email=$2 AND amount=$3)
			)
			ORDER BY tanggal_input DESC LIMIT 1)
		RETURNING id::text, kind, ref_id::text, amount, status, COALESCE(created_by::text,'')`,
		mayarID, email, amount).Scan(&p.ID, &p.Kind, &p.RefID, &p.Amount, &p.Status, &p.createdBy)
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, nil // tak ada yang cocok (mungkin sudah PAID)
	}
	if err != nil {
		return nil, err
	}
	return &p, nil
}
