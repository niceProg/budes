// Package supply menangani alur B (titip-jual): supply_listings + orders.
// JSON DTO diselaraskan dengan kontrak frontend.
package supply

// Listing merepresentasikan baris supply_listings (bentuk JSON = kontrak frontend).
type Listing struct {
	ID          string  `json:"id"`
	Owner       string  `json:"owner"`  // warga_id
	Seller      string  `json:"seller"` // nama warga
	KoperasiID  *string `json:"koperasi_id,omitempty"`
	KomoditasID *string `json:"komoditas_id,omitempty"`
	ItemName    string  `json:"item_name"`
	Satuan      *string `json:"satuan"`
	Avail       int     `json:"avail"` // qty_available
	Sold        int     `json:"sold"`  // qty_sold
	Harga       float64 `json:"harga"` // price_per_item
	Status      string  `json:"status"`
	Tanggal     *string `json:"tanggal"` // YYYY-MM-DD (tanggal_input)
}

// Order merepresentasikan baris orders (bentuk JSON = kontrak frontend).
type Order struct {
	ID        string  `json:"id"`
	Owner     string  `json:"owner"`     // buyer_id
	ListingID string  `json:"listingId"` // camelCase (kontrak frontend)
	Item      string  `json:"item"`      // item_name listing
	Satuan    *string `json:"satuan"`
	Qty       int     `json:"qty"`   // qty_ordered
	Harga     float64 `json:"harga"` // price_per_item
	Total     float64 `json:"total_amount,omitempty"`
	Status    string  `json:"status"`
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
