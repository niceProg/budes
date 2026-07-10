package demand

import (
	"errors"
	"net/http"

	"budes/internal/auth"
	"budes/internal/httpx"
)

// Handler mengekspos endpoint alur Demand.
type Handler struct {
	svc  *Service
	repo *Repository
}

// NewHandler membuat handler demand.
func NewHandler(svc *Service, repo *Repository) *Handler { return &Handler{svc: svc, repo: repo} }

// List: GET /api/demands?status=OPEN,PARTIAL (publik)
func (h *Handler) List(w http.ResponseWriter, r *http.Request) {
	statuses := []string{"OPEN", "PARTIAL"}
	if s := r.URL.Query().Get("status"); s != "" {
		statuses = splitCSV(s)
	}
	res, err := h.repo.List(r.Context(), statuses)
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// Detail: GET /api/demands/{id} (publik)
func (h *Handler) Detail(w http.ResponseWriter, r *http.Request) {
	d, err := h.repo.Get(r.Context(), r.PathValue("id"))
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.OK(w, d)
}

// Create: POST /api/demands (BUYER)
func (h *Handler) Create(w http.ResponseWriter, r *http.Request) {
	var in CreateInput
	if !httpx.Decode(w, r, &in) {
		return
	}
	d, err := h.svc.Create(r.Context(), auth.UserID(r.Context()), in)
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": d})
}

// PayDP: POST /api/demands/{id}/dp (BUYER)
func (h *Handler) PayDP(w http.ResponseWriter, r *http.Request) {
	var in struct {
		DPPaymentMethod string `json:"dp_payment_method"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	d, err := h.svc.PayDP(r.Context(), r.PathValue("id"), auth.UserID(r.Context()), in.DPPaymentMethod)
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.OK(w, d)
}

// Cancel: POST /api/demands/{id}/cancel (BUYER) — DP hangus (FORFEITED)
func (h *Handler) Cancel(w http.ResponseWriter, r *http.Request) {
	if err := h.repo.Cancel(r.Context(), r.PathValue("id"), auth.UserID(r.Context())); err != nil {
		writeErr(w, err)
		return
	}
	httpx.OK(w, "demand dibatalkan")
}

// CreatePledge: POST /api/demands/{id}/pledges (WARGA)
func (h *Handler) CreatePledge(w http.ResponseWriter, r *http.Request) {
	var in struct {
		QtyPledged   int      `json:"qty_pledged"`
		PricePerItem *float64 `json:"price_per_item"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	p, err := h.svc.Pledge(r.Context(), r.PathValue("id"), auth.UserID(r.Context()), in.QtyPledged, in.PricePerItem)
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": p})
}

// MyPledges: GET /api/pledges (WARGA login)
func (h *Handler) MyPledges(w http.ResponseWriter, r *http.Request) {
	res, err := h.repo.PledgesByWarga(r.Context(), auth.UserID(r.Context()))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// UpdatePledge: PUT /api/pledges/{id} (WARGA pemilik / ADMIN_KOPERASI)
func (h *Handler) UpdatePledge(w http.ResponseWriter, r *http.Request) {
	var in struct {
		Status string `json:"status"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	err := h.repo.UpdatePledgeStatus(r.Context(), r.PathValue("id"),
		auth.UserID(r.Context()), auth.RoleFrom(r.Context()), in.Status)
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.OK(w, "status sanggupan diperbarui")
}

// writeErr memetakan error domain ke kode HTTP.
func writeErr(w http.ResponseWriter, err error) {
	switch {
	case errors.Is(err, ErrNotFound), errors.Is(err, ErrPledgeNotFound):
		httpx.Error(w, http.StatusNotFound, err.Error())
	case errors.Is(err, ErrForbidden):
		httpx.Error(w, http.StatusForbidden, err.Error())
	case errors.Is(err, ErrDemandNotOpen), errors.Is(err, ErrOverPledge), errors.Is(err, ErrBadTransition):
		httpx.Error(w, http.StatusConflict, err.Error())
	default:
		httpx.Error(w, http.StatusBadRequest, err.Error())
	}
}

func splitCSV(s string) []string {
	out := []string{}
	cur := ""
	for _, c := range s {
		if c == ',' {
			if cur != "" {
				out = append(out, cur)
			}
			cur = ""
			continue
		}
		cur += string(c)
	}
	if cur != "" {
		out = append(out, cur)
	}
	return out
}
