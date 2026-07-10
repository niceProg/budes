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
