// Package auth menangani registrasi, login (JWT), dan proteksi endpoint.
package auth

import (
	"errors"
	"time"

	"github.com/golang-jwt/jwt/v5"
)

// Claims adalah klaim JWT Budes.
type Claims struct {
	Role string `json:"role"`
	jwt.RegisteredClaims
}

// Manager menerbitkan & memverifikasi token JWT (HS256).
type Manager struct {
	secret []byte
	ttl    time.Duration
}

// NewManager membuat manager JWT dengan masa berlaku 24 jam.
func NewManager(secret string) *Manager {
	return &Manager{secret: []byte(secret), ttl: 24 * time.Hour}
}

// Issue menerbitkan token untuk user id + role.
func (m *Manager) Issue(userID, role string) (string, error) {
	now := time.Now()
	claims := Claims{
		Role: role,
		RegisteredClaims: jwt.RegisteredClaims{
			Subject:   userID,
			IssuedAt:  jwt.NewNumericDate(now),
			ExpiresAt: jwt.NewNumericDate(now.Add(m.ttl)),
		},
	}
	return jwt.NewWithClaims(jwt.SigningMethodHS256, claims).SignedString(m.secret)
}

// Parse memverifikasi token & mengembalikan klaimnya.
func (m *Manager) Parse(token string) (*Claims, error) {
	var c Claims
	t, err := jwt.ParseWithClaims(token, &c, func(t *jwt.Token) (any, error) {
		if _, ok := t.Method.(*jwt.SigningMethodHMAC); !ok {
			return nil, errors.New("metode tanda tangan tidak sesuai")
		}
		return m.secret, nil
	})
	if err != nil || !t.Valid {
		return nil, errors.New("token tidak valid")
	}
	return &c, nil
}
