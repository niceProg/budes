package supply

import (
	"context"
	"errors"

	"github.com/jackc/pgx/v5"
	"github.com/jackc/pgx/v5/pgxpool"
)

// Kesalahan domain.
var (
	ErrNotFound      = errors.New("listing tidak ditemukan")
	ErrOrderNotFound = errors.New("pesanan tidak ditemukan")
	ErrNotPosted     = errors.New("listing tidak tersedia untuk dipesan (harus POSTED)")
	ErrOverOrder     = errors.New("jumlah melebihi stok tersedia")
	ErrForbidden     = errors.New("tidak berhak atas aksi ini")
	ErrBadTransition = errors.New("transisi status tidak valid")
)

const listingCols = `id::text, koperasi_id::text, warga_id::text, komoditas_id::text, item_name, satuan,
	qty_available, qty_sold, price_per_item::float8, listing_status`

// Repository operasi supply_listings & orders.
type Repository struct{ pool *pgxpool.Pool }

// NewRepository membuat repository supply.
func NewRepository(pool *pgxpool.Pool) *Repository { return &Repository{pool: pool} }

func scanListing(row pgx.Row) (*Listing, error) {
	var l Listing
	err := row.Scan(&l.ID, &l.KoperasiID, &l.WargaID, &l.KomoditasID, &l.ItemName, &l.Satuan,
		&l.QtyAvailable, &l.QtySold, &l.PricePerItem, &l.ListingStatus)
	if err != nil {
		return nil, err
	}
	return &l, nil
}

// CreateListing menyimpan listing baru (status DRAFT) & kembalikan id.
func (r *Repository) CreateListing(ctx context.Context, wargaID string, in CreateListingInput) (string, error) {
	var id string
	err := r.pool.QueryRow(ctx, `
		INSERT INTO supply_listings (koperasi_id, warga_id, komoditas_id, item_name, satuan,
			qty_available, price_per_item, user_input)
		VALUES ($1,$2,$3,$4,$5,$6,$7,$8) RETURNING id::text`,
		in.KoperasiID, wargaID, in.KomoditasID, in.ItemName, in.Satuan,
		in.QtyAvailable, in.PricePerItem, wargaID).Scan(&id)
	return id, err
}

// List mengembalikan listing status tertentu (default etalase POSTED), berpaginasi.
func (r *Repository) List(ctx context.Context, statuses []string, limit, offset int) ([]Listing, error) {
	rows, err := r.pool.Query(ctx, `SELECT `+listingCols+` FROM supply_listings
		WHERE listing_status = ANY($1) ORDER BY tanggal_input DESC LIMIT $2 OFFSET $3`, statuses, limit, offset)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	return collectListings(rows)
}

// ByWarga mengembalikan listing milik warga.
func (r *Repository) ByWarga(ctx context.Context, wargaID string) ([]Listing, error) {
	rows, err := r.pool.Query(ctx, `SELECT `+listingCols+` FROM supply_listings
		WHERE warga_id=$1 ORDER BY tanggal_input DESC`, wargaID)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	return collectListings(rows)
}

func collectListings(rows pgx.Rows) ([]Listing, error) {
	out := []Listing{}
	for rows.Next() {
		l, err := scanListing(rows)
		if err != nil {
			return nil, err
		}
		out = append(out, *l)
	}
	return out, rows.Err()
}

// GetListing mengambil satu listing.
func (r *Repository) GetListing(ctx context.Context, id string) (*Listing, error) {
	l, err := scanListing(r.pool.QueryRow(ctx, `SELECT `+listingCols+` FROM supply_listings WHERE id=$1`, id))
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrNotFound
	}
	return l, err
}

var listingTransitions = map[string][]string{
	"DRAFT":    {"POSTED", "CLOSED"},
	"POSTED":   {"CLOSED", "SOLD_OUT"},
	"SOLD_OUT": {"CLOSED", "POSTED"},
}

// SetListingStatus mengubah status listing (pemilik/admin).
func (r *Repository) SetListingStatus(ctx context.Context, id, actorID, actorRole, newStatus string) error {
	var owner, cur string
	err := r.pool.QueryRow(ctx, `SELECT warga_id::text, listing_status FROM supply_listings WHERE id=$1`, id).Scan(&owner, &cur)
	if errors.Is(err, pgx.ErrNoRows) {
		return ErrNotFound
	}
	if err != nil {
		return err
	}
	if actorRole != "ADMIN_KOPERASI" && actorID != owner {
		return ErrForbidden
	}
	if !contains(listingTransitions[cur], newStatus) {
		return ErrBadTransition
	}
	_, err = r.pool.Exec(ctx, `UPDATE supply_listings SET listing_status=$2, user_update=$3 WHERE id=$1`, id, newStatus, actorID)
	return err
}

// CreateOrder memesan dari listing (anti over-order, update qty_sold + status listing).
func (r *Repository) CreateOrder(ctx context.Context, listingID, buyerID string, qty int) (*Order, error) {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return nil, err
	}
	defer tx.Rollback(ctx)

	var avail, sold int
	var status, itemName string
	var price float64
	err = tx.QueryRow(ctx, `SELECT qty_available, qty_sold, price_per_item::float8, listing_status, item_name
		FROM supply_listings WHERE id=$1 FOR UPDATE`, listingID).Scan(&avail, &sold, &price, &status, &itemName)
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}
	if status != "POSTED" {
		return nil, ErrNotPosted
	}
	if qty > avail-sold {
		return nil, ErrOverOrder
	}
	total := float64(qty) * price
	var oid string
	if err := tx.QueryRow(ctx, `
		INSERT INTO orders (listing_id, buyer_id, qty_ordered, price_per_item, total_amount, user_input)
		VALUES ($1,$2,$3,$4,$5,$6) RETURNING id::text`,
		listingID, buyerID, qty, price, total, buyerID).Scan(&oid); err != nil {
		return nil, err
	}
	newSold := sold + qty
	newStatus := "POSTED"
	if newSold >= avail {
		newStatus = "SOLD_OUT"
	}
	if _, err := tx.Exec(ctx, `UPDATE supply_listings SET qty_sold=$2, listing_status=$3 WHERE id=$1`,
		listingID, newSold, newStatus); err != nil {
		return nil, err
	}
	if err := tx.Commit(ctx); err != nil {
		return nil, err
	}
	return &Order{ID: oid, ListingID: listingID, BuyerID: buyerID, ItemName: itemName,
		QtyOrdered: qty, PricePerItem: price, TotalAmount: total, OrderStatus: "PENDING"}, nil
}

// OrdersByBuyer mengembalikan pesanan milik pembeli.
func (r *Repository) OrdersByBuyer(ctx context.Context, buyerID string) ([]Order, error) {
	rows, err := r.pool.Query(ctx, `
		SELECT o.id::text, o.listing_id::text, o.buyer_id::text, COALESCE(l.item_name,''),
			o.qty_ordered, o.price_per_item::float8, o.total_amount::float8, o.order_status
		FROM orders o LEFT JOIN supply_listings l ON l.id=o.listing_id
		WHERE o.buyer_id=$1 ORDER BY o.tanggal_input DESC`, buyerID)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []Order{}
	for rows.Next() {
		var o Order
		if err := rows.Scan(&o.ID, &o.ListingID, &o.BuyerID, &o.ItemName,
			&o.QtyOrdered, &o.PricePerItem, &o.TotalAmount, &o.OrderStatus); err != nil {
			return nil, err
		}
		out = append(out, o)
	}
	return out, rows.Err()
}

// GetOrder mengambil satu pesanan.
func (r *Repository) GetOrder(ctx context.Context, id string) (*Order, error) {
	var o Order
	err := r.pool.QueryRow(ctx, `
		SELECT o.id::text, o.listing_id::text, o.buyer_id::text, COALESCE(l.item_name,''),
			o.qty_ordered, o.price_per_item::float8, o.total_amount::float8, o.order_status
		FROM orders o LEFT JOIN supply_listings l ON l.id=o.listing_id WHERE o.id=$1`, id).
		Scan(&o.ID, &o.ListingID, &o.BuyerID, &o.ItemName, &o.QtyOrdered, &o.PricePerItem, &o.TotalAmount, &o.OrderStatus)
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrOrderNotFound
	}
	return &o, err
}

// orderTransitions untuk PUT generik. HANDED_OVER hanya via endpoint verifikasi (Modul D).
var orderTransitions = map[string][]string{
	"PENDING":   {"CONFIRMED", "CANCELLED"},
	"CONFIRMED": {"CANCELLED"},
}

// SetOrderStatus mengubah status pesanan; CANCELLED mengembalikan stok listing.
func (r *Repository) SetOrderStatus(ctx context.Context, id, actorID, actorRole, newStatus string) error {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return err
	}
	defer tx.Rollback(ctx)

	var listingID, buyerID, cur string
	var qty int
	err = tx.QueryRow(ctx, `SELECT listing_id::text, buyer_id::text, qty_ordered, order_status
		FROM orders WHERE id=$1 FOR UPDATE`, id).Scan(&listingID, &buyerID, &qty, &cur)
	if errors.Is(err, pgx.ErrNoRows) {
		return ErrOrderNotFound
	}
	if err != nil {
		return err
	}
	if actorRole != "ADMIN_KOPERASI" && actorID != buyerID {
		return ErrForbidden
	}
	if !contains(orderTransitions[cur], newStatus) {
		return ErrBadTransition
	}
	if _, err := tx.Exec(ctx, `UPDATE orders SET order_status=$2, user_update=$3 WHERE id=$1`, id, newStatus, actorID); err != nil {
		return err
	}
	if newStatus == "CANCELLED" {
		if _, err := tx.Exec(ctx, `UPDATE supply_listings
			SET qty_sold = GREATEST(qty_sold-$2,0),
			    listing_status = CASE WHEN listing_status='SOLD_OUT' THEN 'POSTED' ELSE listing_status END
			WHERE id=$1`, listingID, qty); err != nil {
			return err
		}
	}
	return tx.Commit(ctx)
}

func contains(list []string, v string) bool {
	for _, s := range list {
		if s == v {
			return true
		}
	}
	return false
}
