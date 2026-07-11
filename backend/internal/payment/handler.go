package payment

import (
	"context"
	"encoding/json"
	"errors"
	"fmt"
	"io"
	"log"
	"math"
	"net/http"
	"strings"

	"budes/internal/auth"
	"budes/internal/httpx"
	"budes/internal/notify"
)

// DemandPayer menandai DP demand lunas (dipenuhi oleh demand.Repository).
type DemandPayer interface {
	MarkDPPaid(ctx context.Context, id, buyerID, method string) (int64, error)
}

// TxnPayer memperbarui status bayar transaksi (dipenuhi oleh settlement.Repository).
type TxnPayer interface {
	UpdatePayment(ctx context.Context, kind, id, status string) (int64, error)
}

// Handler mengekspos endpoint bayar (DP & transaksi) + webhook Mayar.
type Handler struct {
	mayar    *Mayar
	repo     *Repository
	demands  DemandPayer
	txns     TxnPayer
	notifier *notify.Notifier
	frontend string // base URL frontend untuk redirect
}

// NewHandler membuat handler payment.
func NewHandler(mayar *Mayar, repo *Repository, demands DemandPayer, txns TxnPayer, notifier *notify.Notifier, frontend string) *Handler {
	return &Handler{mayar: mayar, repo: repo, demands: demands, txns: txns, notifier: notifier, frontend: strings.TrimRight(frontend, "/")}
}

func (h *Handler) create(ctx context.Context, kind, refID string, amount float64, item, redirect, userID string) (string, error) {
	name, email, phone, err := h.repo.UserContact(ctx, userID)
	if err != nil {
		return "", err
	}
	res, err := h.mayar.CreatePayment(ctx, CreateInput{
		Name: name, Email: email, Mobile: phone,
		Amount:      int64(math.Round(amount)),
		Description: item,
		RedirectURL: redirect,
	})
	if err != nil {
		return "", err
	}
	if _, err := h.repo.Create(ctx, kind, refID, amount, res.TransactionID, res.LinkID, res.Link, email, userID); err != nil {
		return "", err
	}
	return res.Link, nil
}

// PayDP: POST /api/demands/{id}/dp/pay (BUYER) — buat link bayar DP via Mayar.
func (h *Handler) PayDP(w http.ResponseWriter, r *http.Request) {
	if !h.mayar.Enabled() {
		httpx.Error(w, http.StatusServiceUnavailable, "gateway pembayaran nonaktif")
		return
	}
	id := r.PathValue("id")
	buyer := auth.UserID(r.Context())
	amount, item, err := h.repo.DemandForDP(r.Context(), id, buyer)
	if err != nil {
		payErr(w, err)
		return
	}
	redirect := fmt.Sprintf("%s/permintaan/%s?bayar=dp", h.frontend, id)
	link, err := h.create(r.Context(), "DP", id, amount, "Uang Muka (DP) — "+item, redirect, buyer)
	if err != nil {
		httpx.Error(w, http.StatusBadGateway, err.Error())
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": map[string]string{"link": link}})
}

// PayTxn: POST /api/transactions/{kind}/{id}/pay (BUYER) — buat link bayar pelunasan.
func (h *Handler) PayTxn(w http.ResponseWriter, r *http.Request) {
	if !h.mayar.Enabled() {
		httpx.Error(w, http.StatusServiceUnavailable, "gateway pembayaran nonaktif")
		return
	}
	kindParam := r.PathValue("kind") // 'demand' | 'supply'
	id := r.PathValue("id")
	buyer := auth.UserID(r.Context())
	kind := "DEMAND_TXN"
	if kindParam == "supply" {
		kind = "SUPPLY_TXN"
	}
	amount, item, err := h.repo.TxnForPay(r.Context(), kind, id, buyer)
	if err != nil {
		payErr(w, err)
		return
	}
	redirect := fmt.Sprintf("%s/aktivitas?bayar=txn", h.frontend)
	link, err := h.create(r.Context(), kind, id, amount, "Pelunasan — "+item, redirect, buyer)
	if err != nil {
		httpx.Error(w, http.StatusBadGateway, err.Error())
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": map[string]string{"link": link}})
}

// Webhook: POST /api/webhooks/mayar (publik) — terima notifikasi pembayaran Mayar.
func (h *Handler) Webhook(w http.ResponseWriter, r *http.Request) {
	raw, _ := io.ReadAll(io.LimitReader(r.Body, 1<<20))
	log.Printf("[mayar-webhook] %s", string(raw))

	var wh struct {
		Event string `json:"event"`
		Data  struct {
			ID            string  `json:"id"`
			TransactionID string  `json:"transactionId"`
			Status        any     `json:"status"`
			Amount        float64 `json:"amount"`
			CustomerEmail string  `json:"customerEmail"`
		} `json:"data"`
	}
	_ = json.Unmarshal(raw, &wh)

	// Selalu balas 200 agar Mayar tak mengulang; event uji/registrasi cukup di-ack.
	if wh.Event == "" || wh.Event == "testing" {
		httpx.OK(w, "ok")
		return
	}
	if !isPaid(wh.Event, wh.Data.Status) {
		httpx.OK(w, "ignored")
		return
	}
	mayarID := wh.Data.TransactionID
	if mayarID == "" {
		mayarID = wh.Data.ID
	}
	p, err := h.repo.MarkPaidByMayar(r.Context(), mayarID, wh.Data.CustomerEmail, wh.Data.Amount)
	if err != nil {
		log.Printf("[mayar-webhook] match error: %v", err)
		httpx.OK(w, "error-logged")
		return
	}
	if p == nil {
		httpx.OK(w, "no-match") // sudah diproses / tak dikenal
		return
	}
	h.applyPaid(r.Context(), p)
	httpx.OK(w, "processed")
}

// applyPaid menjalankan efek-samping sesuai jenis pembayaran.
func (h *Handler) applyPaid(ctx context.Context, p *Payment) {
	switch p.Kind {
	case "DP":
		if _, err := h.demands.MarkDPPaid(ctx, p.RefID, payerID(ctx, p), "TRANSFER"); err != nil {
			log.Printf("[mayar-webhook] MarkDPPaid: %v", err)
			return
		}
		h.notifier.Broadcast("💳 *DP diterima* — sebuah kebutuhan kini terbuka di Bursa Desa!")
	case "DEMAND_TXN":
		_, _ = h.txns.UpdatePayment(ctx, "demand", p.RefID, "PAID")
	case "SUPPLY_TXN":
		_, _ = h.txns.UpdatePayment(ctx, "supply", p.RefID, "PAID")
	}
}

// payerID: MarkDPPaid butuh buyer_id; ambil dari created_by payment.
func payerID(ctx context.Context, p *Payment) string { return p.createdBy }

func isPaid(event string, status any) bool {
	if strings.Contains(strings.ToLower(event), "payment.received") {
		return true
	}
	switch v := status.(type) {
	case bool:
		return v
	case string:
		s := strings.ToLower(v)
		return s == "true" || s == "paid" || s == "success" || s == "settled"
	}
	return false
}

func payErr(w http.ResponseWriter, err error) {
	switch {
	case errors.Is(err, ErrNotFound):
		httpx.Error(w, http.StatusNotFound, err.Error())
	case errors.Is(err, ErrForbidden):
		httpx.Error(w, http.StatusForbidden, err.Error())
	case errors.Is(err, ErrBadState):
		httpx.Error(w, http.StatusConflict, err.Error())
	default:
		httpx.Error(w, http.StatusInternalServerError, err.Error())
	}
}
