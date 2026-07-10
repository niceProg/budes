package verification

import (
	"errors"
	"net/http"

	"budes/internal/auth"
	"budes/internal/httpx"
	"budes/internal/notify"
)

// Handler mengekspos endpoint verifikasi KYC.
type Handler struct {
	repo     *Repository
	notifier *notify.Notifier
}

// NewHandler membuat handler verification.
func NewHandler(repo *Repository, notifier *notify.Notifier) *Handler {
	return &Handler{repo: repo, notifier: notifier}
}

// Submit: POST /api/verifikasi (auth)
func (h *Handler) Submit(w http.ResponseWriter, r *http.Request) {
	var in struct {
		NIK            *string `json:"nik"`
		IDCardFile     *string `json:"id_card_file"`
		SupportDocFile *string `json:"support_doc_file"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	id, err := h.repo.Submit(r.Context(), auth.UserID(r.Context()), in.NIK, in.IDCardFile, in.SupportDocFile)
	if err != nil {
		httpx.Error(w, http.StatusBadRequest, err.Error())
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": map[string]string{"id": id, "status": "PENDING"}})
}

// List: GET /api/verifikasi?status=PENDING (ADMIN_KOPERASI)
func (h *Handler) List(w http.ResponseWriter, r *http.Request) {
	res, err := h.repo.List(r.Context(), r.URL.Query().Get("status"))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// Review: PUT /api/verifikasi/{id} (ADMIN_KOPERASI)
func (h *Handler) Review(w http.ResponseWriter, r *http.Request) {
	var in struct {
		Status     string  `json:"status"`
		ReviewNote *string `json:"review_note"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	if in.Status != "VERIFIED" && in.Status != "REJECTED" {
		httpx.Error(w, http.StatusBadRequest, "status harus VERIFIED atau REJECTED")
		return
	}
	phone, err := h.repo.Review(r.Context(), r.PathValue("id"), auth.UserID(r.Context()), in.Status, in.ReviewNote)
	if errors.Is(err, ErrNotFound) {
		httpx.Error(w, http.StatusNotFound, err.Error())
		return
	}
	if err != nil {
		httpx.Error(w, http.StatusBadRequest, err.Error())
		return
	}
	if phone != nil {
		pesan := "✅ Verifikasi identitas Anda *DISETUJUI*. Selamat datang di Bursa Desa!"
		if in.Status == "REJECTED" {
			pesan = "❌ Verifikasi identitas Anda *DITOLAK*. Silakan ajukan ulang dengan dokumen yang benar."
		}
		h.notifier.NotifyPhone(*phone, pesan)
	}
	httpx.OK(w, "verifikasi "+in.Status)
}
