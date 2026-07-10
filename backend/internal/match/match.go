// Package match menyajikan kandidat pemenuh sebuah demand (Modul G) — grounded
// pada Reference DB KDMP: stok gerai nyata + potensi komoditas unggulan desa.
package match

import (
	"net/http"

	"budes/internal/demand"
	"budes/internal/httpx"
	"budes/internal/reference"
)

// Handler menggabungkan lookup demand (App DB) dengan matching KDMP (Reference DB).
type Handler struct {
	demands *demand.Repository
	ref     *reference.Repository
}

// NewHandler membuat handler match.
func NewHandler(demands *demand.Repository, ref *reference.Repository) *Handler {
	return &Handler{demands: demands, ref: ref}
}

// ByDemand: GET /api/demands/{id}/kandidat (publik)
// Membaca item dari demand → mencari kandidat stok gerai + potensi desa.
func (h *Handler) ByDemand(w http.ResponseWriter, r *http.Request) {
	d, err := h.demands.Get(r.Context(), r.PathValue("id"))
	if err != nil {
		httpx.Error(w, http.StatusNotFound, "demand tidak ditemukan")
		return
	}
	provinsi := r.URL.Query().Get("provinsi") // opsional
	stok, err := h.ref.MatchKandidat(r.Context(), d.ItemName, provinsi, 10)
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	potensi, err := h.ref.MatchPotensiDesa(r.Context(), d.ItemName, provinsi, 10)
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, map[string]any{
		"demand_id":    d.ID,
		"item":         d.ItemName,
		"stok_gerai":   stok,    // kandidat dari inventaris gerai nyata
		"potensi_desa": potensi, // kandidat dari komoditas unggulan desa (pra-pesan)
	})
}
