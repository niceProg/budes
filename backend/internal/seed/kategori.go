package seed

import "strings"

// kategoriRule memetakan kata kunci pada nama komoditas ke kategori & satuan default.
type kategoriRule struct {
	kategori string
	satuan   string
	keywords []string
}

// Urutan penting: rule lebih spesifik didahulukan (mis. tambang sebelum tani).
var kategoriRules = []kategoriRule{
	{"Pertambangan", "ton", []string{"tambang", "galian", "batu", "marmer", "gamping", "pasir", "ore", "nikel", "emas", "semen"}},
	{"Kehutanan", "m³", []string{"kayu", "hutan", "bambu", "rotan"}},
	{"Perkebunan", "kg", []string{"sawit", "karet", "kakao", "cengkeh", "lada", "tebu", "kopi", "teh"}},
	{"Peternakan", "ekor", []string{"ayam", "sapi", "kambing", "bebek", "itik", "domba", "kerbau", "unggas", "ternak", "telur", "puyuh", "kuda"}},
	{"Perikanan", "kg", []string{"ikan", "perikan", "lele", "nila", "udang", "tambak", "bandeng", "rumput laut", "kerang", "kepiting"}},
	{"Pertanian", "kg", []string{"padi", "jagung", "cabai", "cabe", "sayur", "palawija", "ubi", "singkong", "kacang", "bawang", "tomat", "beras", "kedelai", "pisang", "buah", "kelapa", "tani", "kebun", "tanam"}},
	{"Perdagangan/UMKM", "buah", []string{"kerajinan", "anyam", "tenun", "batik", "kios", "makanan", "minuman", "produksi", "olahan", "warung", "dagang"}},
}

// deriveKategori menebak kategori & satuan default dari nama komoditas KDMP
// (dataset KDMP tidak menyediakan kolom kategori/satuan yang bersih).
func deriveKategori(nama string) (kategori, satuan string) {
	n := strings.ToLower(nama)
	for _, r := range kategoriRules {
		for _, kw := range r.keywords {
			if strings.Contains(n, kw) {
				return r.kategori, r.satuan
			}
		}
	}
	return "Lainnya", "unit"
}
