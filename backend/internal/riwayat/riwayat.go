// Package riwayat menyajikan ringkasan aktivitas & transaksi seorang pengguna.
package riwayat

import (
	"net/http"

	"budes/internal/auth"
	"budes/internal/demand"
	"budes/internal/httpx"
	"budes/internal/settlement"
	"budes/internal/supply"
)

// Handler menggabungkan aktivitas lintas modul untuk pengguna login.
type Handler struct {
	demands *demand.Repository
	supply  *supply.Repository
	settle  *settlement.Repository
}

// NewHandler membuat handler riwayat.
func NewHandler(d *demand.Repository, s *supply.Repository, st *settlement.Repository) *Handler {
	return &Handler{demands: d, supply: s, settle: st}
}

// Me: GET /api/me/riwayat (auth) — kebutuhan, sanggupan, titipan, pesanan, transaksi.
func (h *Handler) Me(w http.ResponseWriter, r *http.Request) {
	ctx := r.Context()
	uid := auth.UserID(ctx)

	demands, err := h.demands.ByBuyer(ctx, uid)
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	pledges, err := h.demands.PledgesByWarga(ctx, uid)
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	listings, err := h.supply.ByWarga(ctx, uid)
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	orders, err := h.supply.OrdersByBuyer(ctx, uid)
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	txns, err := h.settle.List(ctx, uid, auth.RoleFrom(ctx))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}

	httpx.OK(w, map[string]any{
		"demands":      demands,  // sebagai pembeli (alur A)
		"pledges":      pledges,  // sebagai warga (alur A)
		"listings":     listings, // sebagai warga (alur B)
		"orders":       orders,   // sebagai pembeli (alur B)
		"transactions": txns,     // transaksi yang melibatkan saya
	})
}
