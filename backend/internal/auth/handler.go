package auth

import (
	"net/http"

	"budes/internal/httpx"
)

// Handler mengekspos endpoint auth.
type Handler struct {
	svc  *Service
	repo *Repository
}

// NewHandler membuat handler auth.
func NewHandler(svc *Service, repo *Repository) *Handler { return &Handler{svc: svc, repo: repo} }

// Register: POST /api/auth/register
func (h *Handler) Register(w http.ResponseWriter, r *http.Request) {
	var in RegisterInput
	if !httpx.Decode(w, r, &in) {
		return
	}
	u, token, err := h.svc.Register(r.Context(), in)
	if err != nil {
		httpx.Error(w, http.StatusBadRequest, err.Error())
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": u, "token": token})
}

// Login: POST /api/auth/login
func (h *Handler) Login(w http.ResponseWriter, r *http.Request) {
	var in struct {
		Email    string `json:"email"`
		Password string `json:"password"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	u, token, err := h.svc.Login(r.Context(), in.Email, in.Password)
	if err != nil {
		httpx.Error(w, http.StatusUnauthorized, err.Error())
		return
	}
	httpx.JSON(w, http.StatusOK, map[string]any{"data": u, "token": token})
}

// Me: GET /api/me (butuh auth)
func (h *Handler) Me(w http.ResponseWriter, r *http.Request) {
	u, err := h.repo.ByID(r.Context(), UserID(r.Context()))
	if err != nil {
		httpx.Error(w, http.StatusNotFound, "user tidak ditemukan")
		return
	}
	httpx.OK(w, u)
}

// Logout: POST /api/auth/logout (stateless — token dibuang di sisi klien)
func (h *Handler) Logout(w http.ResponseWriter, _ *http.Request) {
	httpx.OK(w, "logout berhasil")
}
