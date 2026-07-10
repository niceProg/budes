package auth

import (
	"context"
	"net/http"
	"strings"

	"budes/internal/httpx"
)

type ctxKey string

const (
	ctxUserID ctxKey = "uid"
	ctxRole   ctxKey = "role"
)

// Middleware memverifikasi Bearer JWT & menaruh uid/role di context request.
func (m *Manager) Middleware(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		tok := bearer(r)
		if tok == "" {
			httpx.Error(w, http.StatusUnauthorized, "token wajib (Authorization: Bearer ...)")
			return
		}
		c, err := m.Parse(tok)
		if err != nil {
			httpx.Error(w, http.StatusUnauthorized, "token tidak valid atau kedaluwarsa")
			return
		}
		ctx := context.WithValue(r.Context(), ctxUserID, c.Subject)
		ctx = context.WithValue(ctx, ctxRole, c.Role)
		next.ServeHTTP(w, r.WithContext(ctx))
	})
}

// RequireRole membungkus handler agar hanya peran tertentu yang diizinkan.
func RequireRole(roles ...string) func(http.Handler) http.Handler {
	allowed := make(map[string]bool, len(roles))
	for _, r := range roles {
		allowed[r] = true
	}
	return func(next http.Handler) http.Handler {
		return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
			if !allowed[RoleFrom(r.Context())] {
				httpx.Error(w, http.StatusForbidden, "peran tidak diizinkan untuk aksi ini")
				return
			}
			next.ServeHTTP(w, r)
		})
	}
}

func bearer(r *http.Request) string {
	h := r.Header.Get("Authorization")
	if after, ok := strings.CutPrefix(h, "Bearer "); ok {
		return strings.TrimSpace(after)
	}
	return ""
}

// UserID mengambil id user dari context (kosong bila tak ada).
func UserID(ctx context.Context) string {
	v, _ := ctx.Value(ctxUserID).(string)
	return v
}

// RoleFrom mengambil peran dari context.
func RoleFrom(ctx context.Context) string {
	v, _ := ctx.Value(ctxRole).(string)
	return v
}
