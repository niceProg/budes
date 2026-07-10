package auth

import (
	"context"
	"errors"

	"github.com/jackc/pgx/v5"
	"github.com/jackc/pgx/v5/pgxpool"
)

// ErrNotFound dikembalikan saat user tidak ada.
var ErrNotFound = errors.New("user tidak ditemukan")

// User merepresentasikan baris tabel users. passwordHash tak diserialisasi.
type User struct {
	ID                 string  `json:"id"`
	KoperasiID         *string `json:"koperasi_id"`
	AnggotaRef         *string `json:"anggota_ref"`
	Name               string  `json:"name"`
	Phone              *string `json:"phone"`
	Email              string  `json:"email"`
	Role               string  `json:"role"`
	VerificationStatus string  `json:"verification_status"`
	passwordHash       string
}

// Repository operasi tabel users di App DB.
type Repository struct{ pool *pgxpool.Pool }

// NewRepository membuat repository users.
func NewRepository(pool *pgxpool.Pool) *Repository { return &Repository{pool: pool} }

// Create menyimpan user baru & mengembalikan id.
func (r *Repository) Create(ctx context.Context, u User, passwordHash string) (string, error) {
	var id string
	err := r.pool.QueryRow(ctx, `
		INSERT INTO users (koperasi_id, anggota_ref, name, phone, email, password_hash, role)
		VALUES ($1,$2,$3,$4,$5,$6,$7) RETURNING id::text`,
		u.KoperasiID, u.AnggotaRef, u.Name, u.Phone, u.Email, passwordHash, u.Role).Scan(&id)
	return id, err
}

// ByEmail mencari user berdasarkan email (menyertakan hash untuk login).
func (r *Repository) ByEmail(ctx context.Context, email string) (*User, error) {
	return r.scanOne(ctx, "WHERE email = $1", email)
}

// ByID mengambil user berdasarkan id.
func (r *Repository) ByID(ctx context.Context, id string) (*User, error) {
	return r.scanOne(ctx, "WHERE id = $1", id)
}

// EmailExists mengecek keberadaan email.
func (r *Repository) EmailExists(ctx context.Context, email string) (bool, error) {
	var exists bool
	err := r.pool.QueryRow(ctx, `SELECT EXISTS(SELECT 1 FROM users WHERE email=$1)`, email).Scan(&exists)
	return exists, err
}

func (r *Repository) scanOne(ctx context.Context, where string, arg any) (*User, error) {
	var u User
	err := r.pool.QueryRow(ctx, `
		SELECT id::text, koperasi_id::text, anggota_ref, name, phone, email, password_hash, role, verification_status
		FROM users `+where, arg).Scan(
		&u.ID, &u.KoperasiID, &u.AnggotaRef, &u.Name, &u.Phone, &u.Email, &u.passwordHash, &u.Role, &u.VerificationStatus)
	if errors.Is(err, pgx.ErrNoRows) {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}
	return &u, nil
}
