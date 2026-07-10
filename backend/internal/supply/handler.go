package supply

import (
	"errors"
	"fmt"
	"net/http"
	"strings"

	"budes/internal/auth"
	"budes/internal/httpx"
	"budes/internal/notify"
)

// Handler mengekspos endpoint alur Supply.
type Handler struct {
	repo     *Repository
	notifier *notify.Notifier
}

// NewHandler membuat handler supply.
func NewHandler(repo *Repository, notifier *notify.Notifier) *Handler {
	return &Handler{repo: repo, notifier: notifier}
}

// CreateListing: POST /api/listings (WARGA/ADMIN_KOPERASI)
func (h *Handler) CreateListing(w http.ResponseWriter, r *http.Request) {
	var in CreateListingInput
	if !httpx.Decode(w, r, &in) {
		return
	}
	in.ItemName = strings.TrimSpace(in.ItemName)
	if in.ItemName == "" || in.QtyAvailable <= 0 || in.PricePerItem <= 0 {
		httpx.Error(w, http.StatusBadRequest, "item_name, qty_available > 0, price_per_item > 0 wajib")
		return
	}
	id, err := h.repo.CreateListing(r.Context(), auth.UserID(r.Context()), in)
	if err != nil {
		writeErr(w, err)
		return
	}
	l, err := h.repo.GetListing(r.Context(), id)
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": l})
}

// List: GET /api/listings?status=ACTIVE (publik)
func (h *Handler) List(w http.ResponseWriter, r *http.Request) {
	statuses := []string{"ACTIVE"}
	if s := r.URL.Query().Get("status"); s != "" {
		statuses = strings.Split(s, ",")
	}
	limit, offset := httpx.Paginate(r)
	res, err := h.repo.List(r.Context(), statuses, limit, offset)
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// Detail: GET /api/listings/{id} (publik)
func (h *Handler) Detail(w http.ResponseWriter, r *http.Request) {
	l, err := h.repo.GetListing(r.Context(), r.PathValue("id"))
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.OK(w, l)
}

// MyListings: GET /api/my/listings (WARGA login)
func (h *Handler) MyListings(w http.ResponseWriter, r *http.Request) {
	res, err := h.repo.ByWarga(r.Context(), auth.UserID(r.Context()))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// SetListingStatus: PUT /api/listings/{id} (pemilik/admin)
func (h *Handler) SetListingStatus(w http.ResponseWriter, r *http.Request) {
	var in struct {
		Status string `json:"status"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	err := h.repo.SetListingStatus(r.Context(), r.PathValue("id"),
		auth.UserID(r.Context()), auth.RoleFrom(r.Context()), in.Status)
	if err != nil {
		writeErr(w, err)
		return
	}
	if in.Status == "ACTIVE" {
		if l, e := h.repo.GetListing(r.Context(), r.PathValue("id")); e == nil {
			satuan := ""
			if l.Satuan != nil {
				satuan = " " + *l.Satuan
			}
			h.notifier.Broadcast(fmt.Sprintf("🌾 *Komoditas Baru di Bursa Desa*\n%s — %d%s tersedia @Rp%.0f/item\n\nBeli sekarang di Budes!",
				l.ItemName, l.Avail-l.Sold, satuan, l.Harga))
		}
	}
	httpx.OK(w, "status listing diperbarui")
}

// CreateOrder: POST /api/orders (BUYER)
func (h *Handler) CreateOrder(w http.ResponseWriter, r *http.Request) {
	var in struct {
		ListingID  string `json:"listing_id"`
		QtyOrdered int    `json:"qty_ordered"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	if in.ListingID == "" || in.QtyOrdered <= 0 {
		httpx.Error(w, http.StatusBadRequest, "listing_id & qty_ordered > 0 wajib")
		return
	}
	o, err := h.repo.CreateOrder(r.Context(), in.ListingID, auth.UserID(r.Context()), in.QtyOrdered)
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.JSON(w, http.StatusCreated, map[string]any{"data": o})
}

// MyOrders: GET /api/orders (BUYER login)
func (h *Handler) MyOrders(w http.ResponseWriter, r *http.Request) {
	res, err := h.repo.OrdersByBuyer(r.Context(), auth.UserID(r.Context()))
	if err != nil {
		httpx.Error(w, http.StatusInternalServerError, err.Error())
		return
	}
	httpx.OK(w, res)
}

// OrderDetail: GET /api/orders/{id}
func (h *Handler) OrderDetail(w http.ResponseWriter, r *http.Request) {
	o, err := h.repo.GetOrder(r.Context(), r.PathValue("id"))
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.OK(w, o)
}

// SetOrderStatus: PUT /api/orders/{id} (buyer/admin)
func (h *Handler) SetOrderStatus(w http.ResponseWriter, r *http.Request) {
	var in struct {
		Status string `json:"status"`
	}
	if !httpx.Decode(w, r, &in) {
		return
	}
	err := h.repo.SetOrderStatus(r.Context(), r.PathValue("id"),
		auth.UserID(r.Context()), auth.RoleFrom(r.Context()), in.Status)
	if err != nil {
		writeErr(w, err)
		return
	}
	httpx.OK(w, "status pesanan diperbarui")
}

func writeErr(w http.ResponseWriter, err error) {
	switch {
	case errors.Is(err, ErrNotFound), errors.Is(err, ErrOrderNotFound):
		httpx.Error(w, http.StatusNotFound, err.Error())
	case errors.Is(err, ErrForbidden):
		httpx.Error(w, http.StatusForbidden, err.Error())
	case errors.Is(err, ErrNotPosted), errors.Is(err, ErrOverOrder), errors.Is(err, ErrBadTransition):
		httpx.Error(w, http.StatusConflict, err.Error())
	default:
		httpx.Error(w, http.StatusBadRequest, err.Error())
	}
}
