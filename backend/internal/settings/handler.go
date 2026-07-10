package settings

import (
	"net/http"

	"budes/internal/auth"
	"budes/internal/httpx"
)

// Handler mengekspos endpoint pengaturan.
type Handler struct{ repo *Repository }

// NewHandler membuat handler settings.
func NewHandler(repo *Repository) *Handler { return &Handler{repo: repo} }

// GetKomisi: GET /api/pengaturan/komisi (auth)
func (h *Handler) GetKomisi(w http.ResponseWriter, r *http.Request) {
	pct, err := h.repo.GetFeePercent(r.Context())
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, map[string]any{"komisi_persen": pct})
}

// SetKomisi: PUT /api/pengaturan/komisi (ADMIN_KOPERASI)
func (h *Handler) SetKomisi(w http.ResponseWriter, r *http.Request) {
	var in struct {
		KomisiPersen float64 `json:"komisi_persen"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	if in.KomisiPersen < 0 || in.KomisiPersen > 100 {
		httpx.Error(w, http.StatusBadRequest, "komisi_persen harus 0..100")
		return
	}
	if err := h.repo.SetFeePercent(r.Context(), in.KomisiPersen, auth.UserID(r.Context())); err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, map[string]any{"komisi_persen": in.KomisiPersen})
}
