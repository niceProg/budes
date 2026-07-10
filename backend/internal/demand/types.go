// Package demand menangani alur A (pra-pesan): demands + uang muka (DP) + pledges.
// JSON DTO diselaraskan dengan kontrak frontend (total/harga/status/dp/owner/...).
package demand

import "time"

// Kandidat = desa/koperasi berpotensi memenuhi (embedded di detail; diisi handler match).
type Kandidat struct {
	Name string `json:"name"`
	Note string `json:"note"`
}

// Demand merepresentasikan baris tabel demands (bentuk JSON = kontrak frontend).
type Demand struct {
	ID                 string     `json:"id"`
	Owner              string     `json:"owner"` // buyer_id
	KoperasiID         *string    `json:"koperasi_id,omitempty"`
	KomoditasID        *string    `json:"komoditas_id,omitempty"`
	ItemName           string     `json:"item_name"`
	Satuan             *string    `json:"satuan"`
	Total              int        `json:"total"`     // total_qty
	Fulfilled          int        `json:"fulfilled"` // fulfilled_qty
	Harga              *float64   `json:"harga"`     // target_price_per_item
	TotalPrice         *float64   `json:"total_price,omitempty"`
	DPPercent          float64    `json:"dp_percent,omitempty"`
	DPAmount           *float64   `json:"dp_amount,omitempty"`
	RemainingAmount    *float64   `json:"remaining_amount,omitempty"`
	Method             *string    `json:"method"` // dp_payment_method
	DP                 string     `json:"dp"`     // dp_status
	DPPaidAt           *time.Time `json:"dp_paid_at,omitempty"`
	Deadline           *string    `json:"deadline"` // YYYY-MM-DD
	Status             string     `json:"status"`   // demand_status
	Pledges            []Pledge   `json:"pledges"`
	Kandidat           []Kandidat `json:"kandidat"`
}

// Pledge merepresentasikan sanggupan warga (bentuk JSON = kontrak frontend).
type Pledge struct {
	ID           string   `json:"id"`
	DemandID     string   `json:"demand_id"`
	WargaID      string   `json:"warga_id"`
	Name         string   `json:"name"` // warga_name
	Q            int      `json:"q"`    // qty_pledged
	D            int      `json:"d"`    // qty_delivered
	PricePerItem *float64 `json:"price_per_item,omitempty"`
	St           string   `json:"st"` // pledge_status
}

// PledgeRow adalah baris "sanggupan saya" (GET /api/pledges), bentuk JSON = kontrak frontend.
type PledgeRow struct {
	ID       string  `json:"id"`
	Owner    string  `json:"owner"`    // warga_id
	DemandID string  `json:"demandId"` // camelCase (kontrak frontend)
	Item     string  `json:"item"`     // demand item_name
	Qty      int     `json:"qty"`      // qty_pledged
	Satuan   *string `json:"satuan"`
	Status   string  `json:"status"` // pledge_status
}

// CreateInput adalah payload pembuatan demand.
type CreateInput struct {
	KoperasiID         *string    `json:"koperasi_id"`
	KomoditasID        *string    `json:"komoditas_id"`
	ItemName           string     `json:"item_name"`
	Satuan             *string    `json:"satuan"`
	TotalQty           int        `json:"total_qty"`
	TargetPricePerItem float64    `json:"target_price_per_item"`
	Deadline           *time.Time `json:"deadline"`
}
