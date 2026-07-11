package settlement

import (
	"errors"
	"fmt"
	"net/http"
	"strings"

	"budes/internal/auth"
	"budes/internal/httpx"
	"budes/internal/notify"
)

// Handler mengekspos endpoint serah-terima, transaksi, sengketa.
type Handler struct {
	repo     *Repository
	notifier *notify.Notifier
}

// NewHandler membuat handler settlement.
func NewHandler(repo *Repository, notifier *notify.Notifier) *Handler {
	return &Handler{repo: repo, notifier: notifier}
}

func (h *Handler) broadcastCair(t *Txn) {
	h.notifier.Broadcast(fmt.Sprintf(
		"✅ *Transaksi Selesai (%s)*\nBarang: %s\nDana cair ke warga: Rp%.0f\nKomisi koperasi: Rp%.0f",
		t.Kind, t.Item, t.NetAmount, t.KoperasiFee))
}

// VerifyPledge: POST /api/pledges/{id}/verifikasi (BUYER)
func (h *Handler) VerifyPledge(w http.ResponseWriter, r *http.Request) {
	var in struct {
		QtyReceived *int `json:"qty_received"`
	}
	_ = httpx.Decode(w, r, &in) // body opsional; abaikan error decode kosong
	t, err := h.repo.VerifyPledge(r.Context(), r.PathValue("id"), auth.UserID(r.Context()), in.QtyReceived)
	if err != nil {
		writeErr(w, err)
		return
	}
	h.broadcastCair(t)
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": t})
}

// VerifyOrder: POST /api/orders/{id}/verifikasi (BUYER)
func (h *Handler) VerifyOrder(w http.ResponseWriter, r *http.Request) {
	t, err := h.repo.VerifyOrder(r.Context(), r.PathValue("id"), auth.UserID(r.Context()))
	if err != nil {
		writeErr(w, err)
		return
	}
	h.broadcastCair(t)
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": t})
}

// LaporPledge: POST /api/pledges/{id}/lapor (auth)
func (h *Handler) LaporPledge(w http.ResponseWriter, r *http.Request) { h.lapor(w, r, "DEMAND") }

// LaporOrder: POST /api/orders/{id}/lapor (auth)
func (h *Handler) LaporOrder(w http.ResponseWriter, r *http.Request) { h.lapor(w, r, "SUPPLY") }

func (h *Handler) lapor(w http.ResponseWriter, r *http.Request, kind string) {
	var in struct {
		Reason string `json:"reason"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	if strings.TrimSpace(in.Reason) == "" {
		httpx.Error(w, http.StatusBadRequest, "reason wajib diisi")
		return
	}
	id, err := h.repo.Dispute(r.Context(), kind, r.PathValue("id"), auth.UserID(r.Context()), in.Reason)
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": map[string]string{"dispute_id": id, "source_type": kind}})
}

// List: GET /api/transactions (auth)
func (h *Handler) List(w http.ResponseWriter, r *http.Request) {
	res, err := h.repo.List(r.Context(), auth.UserID(r.Context()), auth.RoleFrom(r.Context()))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// UpdatePayment: PUT /api/transactions/{kind}/{id} (ADMIN_KOPERASI)
func (h *Handler) UpdatePayment(w http.ResponseWriter, r *http.Request) {
	kind := r.PathValue("kind")
	if kind != "demand" && kind != "supply" {
		httpx.Error(w, http.StatusBadRequest, "kind harus 'demand' atau 'supply'")
		return
	}
	var in struct {
		PaymentStatus string `json:"payment_status"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	if in.PaymentStatus != "UNPAID" && in.PaymentStatus != "PAID" && in.PaymentStatus != "SETTLED" {
		httpx.Error(w, http.StatusBadRequest, "payment_status harus UNPAID/PAID/SETTLED")
		return
	}
	n, err := h.repo.UpdatePayment(r.Context(), kind, r.PathValue("id"), in.PaymentStatus)
	if err != nil {
		httpx.Error(w, http.StatusBadRequest, err.Error())
		return
	}
	if n == 0 {
		httpx.Error(w, http.StatusNotFound, "transaksi tidak ditemukan")
		return
	}
	httpx.OK(w, "status pembayaran diperbarui")
}

// ListDisputes: GET /api/disputes?status=OPEN (ADMIN_KOPERASI)
func (h *Handler) ListDisputes(w http.ResponseWriter, r *http.Request) {
	res, err := h.repo.ListDisputes(r.Context(), r.URL.Query().Get("status"))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// ResolveDispute: PUT /api/disputes/{id} (ADMIN_KOPERASI)
func (h *Handler) ResolveDispute(w http.ResponseWriter, r *http.Request) {
	var in struct {
		Status     string  `json:"status"`
		Resolution *string `json:"resolution"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	if in.Status != "REVIEW" && in.Status != "RESOLVED" {
		httpx.Error(w, http.StatusBadRequest, "status harus REVIEW atau RESOLVED")
		return
	}
	n, err := h.repo.ResolveDispute(r.Context(), r.PathValue("id"), auth.UserID(r.Context()), in.Status, in.Resolution)
	if err != nil {
		httpx.Error(w, http.StatusBadRequest, err.Error())
		return
	}
	if n == 0 {
		httpx.Error(w, http.StatusNotFound, "sengketa tidak ditemukan")
		return
	}
	httpx.OK(w, "sengketa "+in.Status)
}

// Pembukuan: GET /api/pembukuan (ADMIN_KOPERASI)
func (h *Handler) Pembukuan(w http.ResponseWriter, r *http.Request) {
	p, err := h.repo.Pembukuan(r.Context())
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, p)
}

// Stats: GET /api/stats (publik) — angka ringkas untuk landing.
func (h *Handler) Stats(w http.ResponseWriter, r *http.Request) {
	res, err := h.repo.PublicStats(r.Context())
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// Insights: GET /api/insights (ADMIN_KOPERASI) — ringkasan demand & supply.
func (h *Handler) Insights(w http.ResponseWriter, r *http.Request) {
	res, err := h.repo.Insights(r.Context())
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

func writeErr(w http.ResponseWriter, err error) {
	switch {
	case errors.Is(err, ErrNotFound):
		httpx.Error(w, http.StatusNotFound, err.Error())
	case errors.Is(err, ErrForbidden):
		httpx.Error(w, http.StatusForbidden, err.Error())
	case errors.Is(err, ErrBadState):
		httpx.Error(w, http.StatusConflict, err.Error())
	default:
		httpx.Error(w, http.StatusBadRequest, err.Error())
	}
}
