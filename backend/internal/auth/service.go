package auth

import (
	"context"
	"errors"
	"strings"

	"golang.org/x/crypto/bcrypt"
)

var validRoles = map[string]bool{"BUYER": true, "WARGA": true, "ADMIN_KOPERASI": true}

// RegisterInput adalah data pendaftaran pengguna.
type RegisterInput struct {
	Name       string  `json:"name"`
	Email      string  `json:"email"`
	Password   string  `json:"password"`
	Phone      *string `json:"phone"`
	Role       string  `json:"role"`
	KoperasiID *string `json:"koperasi_id"`
	AnggotaRef *string `json:"anggota_ref"`
}

// Service berisi logika bisnis auth.
type Service struct {
	repo *Repository
	jwt  *Manager
}

// NewService membuat service auth.
func NewService(repo *Repository, jwt *Manager) *Service { return &Service{repo: repo, jwt: jwt} }

// Register memvalidasi, hash password, menyimpan user, dan menerbitkan token.
func (s *Service) Register(ctx context.Context, in RegisterInput) (*User, string, error) {
	in.Email = strings.ToLower(strings.TrimSpace(in.Email))
	if strings.TrimSpace(in.Name) == "" || in.Email == "" || len(in.Password) < 6 {
		return nil, "", errors.New("name & email wajib; password minimal 6 karakter")
	}
	if !validRoles[in.Role] {
		return nil, "", errors.New("role harus BUYER, WARGA, atau ADMIN_KOPERASI")
	}
	exists, err := s.repo.EmailExists(ctx, in.Email)
	if err != nil {
		return nil, "", err
	}
	if exists {
		return nil, "", errors.New("email sudah terdaftar")
	}

	hash, err := bcrypt.GenerateFromPassword([]byte(in.Password), bcrypt.DefaultCost)
	if err != nil {
		return nil, "", err
	}
	u := User{
		Name: strings.TrimSpace(in.Name), Email: in.Email, Phone: in.Phone,
		Role: in.Role, KoperasiID: in.KoperasiID, AnggotaRef: in.AnggotaRef,
	}
	id, err := s.repo.Create(ctx, u, string(hash))
	if err != nil {
		return nil, "", err
	}
	u.ID = id
	u.VerificationStatus = "UNVERIFIED"
	token, err := s.jwt.Issue(id, u.Role)
	return &u, token, err
}

// Login memverifikasi kredensial & menerbitkan token.
func (s *Service) Login(ctx context.Context, email, password string) (*User, string, error) {
	u, err := s.repo.ByEmail(ctx, strings.ToLower(strings.TrimSpace(email)))
	if err != nil {
		return nil, "", errors.New("email atau kata sandi tidak sesuai")
	}
	if bcrypt.CompareHashAndPassword([]byte(u.passwordHash), []byte(password)) != nil {
		return nil, "", errors.New("email atau kata sandi tidak sesuai")
	}
	token, err := s.jwt.Issue(u.ID, u.Role)
	return u, token, err
}
