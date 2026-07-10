// Package verification menangani KYC: pengajuan identitas + tinjauan ADMIN_KOPERASI.
package verification

import (
	"context"
	"errors"

	"github.com/jackc/pgx/v5"
	"github.com/jackc/pgx/v5/pgxpool"
)

// ErrNotFound saat pengajuan tidak ada.
var ErrNotFound = errors.New("pengajuan verifikasi tidak ditemukan")

// Verification merepresentasikan baris verifications (+ nama pengaju).
type Verification struct {
	ID             string  `json:"id"`
	UserID         string  `json:"user_id"`
	UserName       string  `json:"user_name,omitempty"`
	NIK            *string `json:"nik"`
	IDCardFile     *string `json:"id_card_file"`
	SupportDocFile *string `json:"support_doc_file"`
	Status         string  `json:"status"`
	ReviewNote     *string `json:"review_note"`
}

// Repository operasi tabel verifications.
type Repository struct{ pool *pgxpool.Pool }

// NewRepository membuat repository verification.
func NewRepository(pool *pgxpool.Pool) *Repository { return &Repository{pool: pool} }

// Submit menyimpan pengajuan & menandai user PENDING.
func (r *Repository) Submit(ctx context.Context, userID string, nik, idCard, supportDoc *string) (string, error) {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return "", err
	}
	defer tx.Rollback(ctx)

	var id string
	if err := tx.QueryRow(ctx, `
		INSERT INTO verifications (user_id, nik, id_card_file, support_doc_file, user_input)
		VALUES ($1,$2,$3,$4,$5) RETURNING id::text`, userID, nik, idCard, supportDoc, userID).Scan(&id); err != nil {
		return "", err
	}
	if _, err := tx.Exec(ctx, `UPDATE users SET verification_status='PENDING' WHERE id=$1`, userID); err != nil {
		return "", err
	}
	return id, tx.Commit(ctx)
}

// List mengembalikan pengajuan (filter status opsional).
func (r *Repository) List(ctx context.Context, status string) ([]Verification, error) {
	rows, err := r.pool.Query(ctx, `
		SELECT v.id::text, v.user_id::text, COALESCE(u.name,''), v.nik, v.id_card_file,
			v.support_doc_file, v.status, v.review_note
		FROM verifications v LEFT JOIN users u ON u.id=v.user_id
		WHERE ($1='' OR v.status=$1) ORDER BY v.tanggal_input DESC`, status)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []Verification{}
	for rows.Next() {
		var v Verification
		if err := rows.Scan(&v.ID, &v.UserID, &v.UserName, &v.NIK, &v.IDCardFile,
			&v.SupportDocFile, &v.Status, &v.ReviewNote); err != nil {
			return nil, err
		}
		out = append(out, v)
	}
	return out, rows.Err()
}

// Review memutuskan VERIFIED/REJECTED & menyinkronkan users.verification_status.
// Mengembalikan nomor telepon pengaju (untuk notifikasi), bila ada.
func (r *Repository) Review(ctx context.Context, id, reviewerID, decision string, note *string) (*string, error) {
	tx, err := r.pool.Begin(ctx)
	if err != nil {
		return nil, err
	}
	defer tx.Rollback(ctx)

	var userID string
	var phone *string
	err = tx.QueryRow(ctx, `SELECT v.user_id::text, u.phone
		FROM verifications v JOIN users u ON u.id = v.user_id WHERE v.id=$1 FOR UPDATE OF v`, id).Scan(&userID, &phone)
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}
	if _, err := tx.Exec(ctx, `UPDATE verifications SET status=$2, reviewed_by=$3, review_note=$4, reviewed_at=now(), user_update=$5 WHERE id=$1`,
		id, decision, reviewerID, note, reviewerID); err != nil {
		return nil, err
	}
	if _, err := tx.Exec(ctx, `UPDATE users SET verification_status=$2,
		verified_at = CASE WHEN $2='VERIFIED' THEN now() ELSE verified_at END WHERE id=$1`, userID, decision); err != nil {
		return nil, err
	}
	return phone, tx.Commit(ctx)
}
