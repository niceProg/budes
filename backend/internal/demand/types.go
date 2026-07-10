// Package demand menangani alur A (pra-pesan): demands + uang muka (DP) + pledges.
package demand

import "time"

// Demand merepresentasikan baris tabel demands.
type Demand struct {
	ID                 string     `json:"id"`
	BuyerID            string     `json:"buyer_id"`
	KoperasiID         *string    `json:"koperasi_id"`
	KomoditasID        *string    `json:"komoditas_id"`
	ItemName           string     `json:"item_name"`
	Satuan             *string    `json:"satuan"`
	TotalQty           int        `json:"total_qty"`
	FulfilledQty       int        `json:"fulfilled_qty"`
	TargetPricePerItem *float64   `json:"target_price_per_item"`
	TotalPrice         *float64   `json:"total_price"`
	DPPercent          float64    `json:"dp_percent"`
	DPAmount           *float64   `json:"dp_amount"`
	RemainingAmount    *float64   `json:"remaining_amount"`
	DPPaymentMethod    *string    `json:"dp_payment_method"`
	DPStatus           string     `json:"dp_status"`
	DPPaidAt           *time.Time `json:"dp_paid_at"`
	Deadline           *time.Time `json:"deadline"`
	DemandStatus       string     `json:"demand_status"`
	Pledges            []Pledge   `json:"pledges,omitempty"`
}

// Pledge merepresentasikan sanggupan warga atas sebuah demand.
type Pledge struct {
	ID           string   `json:"id"`
	DemandID     string   `json:"demand_id"`
	WargaID      string   `json:"warga_id"`
	WargaName    string   `json:"warga_name,omitempty"`
	QtyPledged   int      `json:"qty_pledged"`
	QtyDelivered int      `json:"qty_delivered"`
	PricePerItem *float64 `json:"price_per_item"`
	PledgeStatus string   `json:"pledge_status"`
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
