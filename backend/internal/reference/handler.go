package reference

import (
	"net/http"
	"strconv"

	"budes/internal/httpx"
)

// Handler mengekspos read-layer Reference DB sebagai endpoint HTTP.
type Handler struct {
	repo *Repository
}

// NewHandler membuat handler di atas repository read-only.
func NewHandler(repo *Repository) *Handler {
	return &Handler{repo: repo}
}

// SearchKoperasi: GET /api/ref/koperasi?q=&limit=
func (h *Handler) SearchKoperasi(w http.ResponseWriter, r *http.Request) {
	q := r.URL.Query().Get("q")
	if q == "" {
		httpx.Error(w, http.StatusBadRequest, "parameter 'q' wajib diisi")
		return
	}
	res, err := h.repo.SearchKoperasi(r.Context(), q, clampLimit(r, 20))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// SearchAnggota: GET /api/ref/anggota?q=&limit=
func (h *Handler) SearchAnggota(w http.ResponseWriter, r *http.Request) {
	q := r.URL.Query().Get("q")
	if q == "" {
		httpx.Error(w, http.StatusBadRequest, "parameter 'q' wajib diisi")
		return
	}
	res, err := h.repo.SearchAnggota(r.Context(), q, clampLimit(r, 20))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// MatchKandidat: GET /api/match/kandidat?item=&provinsi=&limit=
// Modul G — kandidat pemenuh dari stok gerai nyata. (Nanti disambung ke demand :id.)
func (h *Handler) MatchKandidat(w http.ResponseWriter, r *http.Request) {
	item := r.URL.Query().Get("item")
	if item == "" {
		httpx.Error(w, http.StatusBadRequest, "parameter 'item' wajib diisi (mis. telur, beras)")
		return
	}
	provinsi := r.URL.Query().Get("provinsi")
	res, err := h.repo.MatchKandidat(r.Context(), item, provinsi, clampLimit(r, 10))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// clampLimit membaca ?limit= dengan default & batas atas 100.
func clampLimit(r *http.Request, def int) int {
	n, err := strconv.Atoi(r.URL.Query().Get("limit"))
	if err != nil || n <= 0 {
		return def
	}
	if n > 100 {
		return 100
	}
	return n
}
