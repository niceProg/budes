// Package health menyediakan health check yang mengecek kedua database.
package health

import (
	"net/http"

	"budes/internal/db"
	"budes/internal/httpx"
)

// Handler mengecek kesehatan App DB & Reference DB.
type Handler struct {
	pools *db.Pools
}

// New membuat health handler.
func New(pools *db.Pools) *Handler {
	return &Handler{pools: pools}
}

// Check: GET /health — status 200 bila kedua DB merespons ping, 503 bila tidak.
func (h *Handler) Check(w http.ResponseWriter, r *http.Request) {
	ctx := r.Context()
	appOK := h.pools.App.Ping(ctx) == nil
	refOK := h.pools.Ref.Ping(ctx) == nil

	status := http.StatusOK
	if !appOK || !refOK {
		status = http.StatusServiceUnavailable
	}
	httpx.JSON(w, status, map[string]any{
		"status": map[string]bool{"app_db": appOK, "ref_db": refOK},
	})
}
