// Package settings menyimpan pengaturan aplikasi key-value (mis. komisi koperasi)
// yang dapat diedit ADMIN_KOPERASI dari dashboard.
package settings

import (
	"context"
	"errors"
	"strconv"

	"github.com/jackc/pgx/v5"
	"github.com/jackc/pgx/v5/pgxpool"
)

const (
	feeKey = "koperasi_fee_percent"
	// DefaultFeePercent dipakai bila pengaturan belum ada.
	DefaultFeePercent = 5.0
)

// Repository operasi tabel settings.
type Repository struct{ pool *pgxpool.Pool }

// NewRepository membuat repository settings.
func NewRepository(pool *pgxpool.Pool) *Repository { return &Repository{pool: pool} }

// GetFeePercent membaca komisi koperasi (persen). Fallback DefaultFeePercent.
func (r *Repository) GetFeePercent(ctx context.Context) (float64, error) {
	var v string
	err := r.pool.QueryRow(ctx, `SELECT value FROM settings WHERE key=$1`, feeKey).Scan(&v)
	if errors.Is(err, pgx.ErrNoRows) {
		return DefaultFeePercent, nil
	}
	if err != nil {
		return 0, err
	}
	f, perr := strconv.ParseFloat(v, 64)
	if perr != nil {
		return DefaultFeePercent, nil
	}
	return f, nil
}

// SetFeePercent menyetel komisi koperasi (upsert).
func (r *Repository) SetFeePercent(ctx context.Context, pct float64, actorID string) error {
	_, err := r.pool.Exec(ctx, `
		INSERT INTO settings (key, value, user_update) VALUES ($1,$2,$3)
		ON CONFLICT (key) DO UPDATE SET value=EXCLUDED.value, updated_at=now(), user_update=EXCLUDED.user_update`,
		feeKey, strconv.FormatFloat(pct, 'f', -1, 64), actorID)
	return err
}

// PriceCap = batas harga komoditas (anti mark-up).
type PriceCap struct {
	ID        string  `json:"id"`
	Komoditas string  `json:"komoditas"`
	Satuan    string  `json:"satuan"`
	MaxJual   float64 `json:"max_jual"`
	MaxBeli   float64 `json:"max_beli"`
}

// ListPriceCaps mengembalikan seluruh batas harga.
func (r *Repository) ListPriceCaps(ctx context.Context) ([]PriceCap, error) {
	rows, err := r.pool.Query(ctx, `SELECT id::text, komoditas, satuan, max_jual::float8, max_beli::float8
		FROM price_caps ORDER BY komoditas`)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []PriceCap{}
	for rows.Next() {
		var p PriceCap
		if err := rows.Scan(&p.ID, &p.Komoditas, &p.Satuan, &p.MaxJual, &p.MaxBeli); err != nil {
			return nil, err
		}
		out = append(out, p)
	}
	return out, rows.Err()
}

// ReplacePriceCaps mengganti seluruh batas harga (bulk) dalam satu transaksi.
func (r *Repository) ReplacePriceCaps(ctx context.Context, caps []PriceCap, actorID string) error {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return err
	}
	defer tx.Rollback(ctx)
	if _, err := tx.Exec(ctx, `DELETE FROM price_caps`); err != nil {
		return err
	}
	for _, c := range caps {
		if c.Komoditas == "" {
			continue
		}
		sat := c.Satuan
		if sat == "" {
			sat = "kg"
		}
		if _, err := tx.Exec(ctx, `INSERT INTO price_caps (komoditas, satuan, max_jual, max_beli, user_input)
			VALUES ($1,$2,$3,$4,$5)`, c.Komoditas, sat, c.MaxJual, c.MaxBeli, actorID); err != nil {
			return err
		}
	}
	return tx.Commit(ctx)
}
