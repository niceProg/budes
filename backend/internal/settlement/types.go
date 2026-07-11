// Package settlement menangani serah-terima (Modul D) + pencatatan transaksi
// offline & komisi koperasi (Modul E) + sengketa, untuk kedua alur.
package settlement

// KoperasiFeePercent = komisi koperasi per transaksi (persen).
const KoperasiFeePercent = 5.0

// Txn adalah baris transaksi (demand/supply) yang diseragamkan (bentuk JSON = kontrak frontend).
type Txn struct {
	ID            string  `json:"id"`
	Kind          string  `json:"kind"`  // demand | supply (lowercase)
	Item          string  `json:"item"`  // item_name
	Pihak         string  `json:"pihak"` // "{warga} → {buyer}"
	Gross         float64 `json:"gross"` // gross_amount
	Pay           string  `json:"pay"`   // payment_status
	RefID         string  `json:"ref_id,omitempty"`
	KoperasiFee   float64 `json:"koperasi_fee,omitempty"`
	NetAmount     float64 `json:"net_amount,omitempty"`
	PaymentMethod *string `json:"payment_method,omitempty"`
	BuyerID       string  `json:"buyer_id,omitempty"`
	WargaID       string  `json:"warga_id,omitempty"`
}

// TopItem = komoditas etalase terlaris (unit terjual).
type TopItem struct {
	ItemName string  `json:"item_name"`
	Satuan   *string `json:"satuan"`
	Sold     int     `json:"sold"`
	Revenue  float64 `json:"revenue"`
}

// Insights = ringkasan demand & supply untuk dashboard admin.
type Insights struct {
	DemandTotal    int            `json:"demand_total"`
	DemandByStatus map[string]int `json:"demand_by_status"`
	DemandValue    float64        `json:"demand_value"`
	ListingTotal   int            `json:"listing_total"`
	LarisCount     int            `json:"laris_count"`   // jumlah item pernah terjual
	SupplySold     int            `json:"supply_sold"`   // total unit terjual
	SupplyRevenue  float64        `json:"supply_revenue"`
	TopSelling     []TopItem      `json:"top_selling"`
}

// Pembukuan meringkas pemasukan komisi koperasi.
type Pembukuan struct {
	TotalKomisi     float64 `json:"total_komisi"`
	TotalGross      float64 `json:"total_gross"`
	JumlahTransaksi int     `json:"jumlah_transaksi"`
	KomisiDemand    float64 `json:"komisi_demand"`
	KomisiSupply    float64 `json:"komisi_supply"`
}
