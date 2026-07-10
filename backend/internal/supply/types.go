// Package supply menangani alur B (titip-jual): supply_listings + orders.
package supply

import "time"

// Listing merepresentasikan baris supply_listings.
type Listing struct {
	ID            string     `json:"id"`
	KoperasiID    *string    `json:"koperasi_id"`
	WargaID       string     `json:"warga_id"`
	KomoditasID   *string    `json:"komoditas_id"`
	ItemName      string     `json:"item_name"`
	Satuan        *string    `json:"satuan"`
	QtyAvailable  int        `json:"qty_available"`
	QtySold       int        `json:"qty_sold"`
	PricePerItem  float64    `json:"price_per_item"`
	ListingStatus string     `json:"listing_status"`
	CreatedAt     *time.Time `json:"tanggal_input,omitempty"`
}

// Order merepresentasikan baris orders.
type Order struct {
	ID           string  `json:"id"`
	ListingID    string  `json:"listing_id"`
	BuyerID      string  `json:"buyer_id"`
	ItemName     string  `json:"item_name,omitempty"`
	QtyOrdered   int     `json:"qty_ordered"`
	PricePerItem float64 `json:"price_per_item"`
	TotalAmount  float64 `json:"total_amount"`
	OrderStatus  string  `json:"order_status"`
}

// CreateListingInput payload titip-jual.
type CreateListingInput struct {
	KoperasiID   *string `json:"koperasi_id"`
	KomoditasID  *string `json:"komoditas_id"`
	ItemName     string  `json:"item_name"`
	Satuan       *string `json:"satuan"`
	QtyAvailable int     `json:"qty_available"`
	PricePerItem float64 `json:"price_per_item"`
}
