package seed

import "testing"

func TestDeriveKategori(t *testing.T) {
	cases := []struct {
		nama, wantKat, wantSat string
	}{
		{"Padi", "Pertanian", "kg"},
		{"Jagung", "Pertanian", "kg"},
		{"Ayam Petelur", "Peternakan", "ekor"},
		{"Sapi Perah", "Peternakan", "ekor"},
		{"Ikan Lele", "Perikanan", "kg"},
		{"Perikanan Tambak", "Perikanan", "kg"},
		{"Kopi Robusta", "Perkebunan", "kg"},
		{"Tambang Batu Gamping", "Pertambangan", "ton"},
		{"Produksi Kayu Bangunan", "Kehutanan", "m³"},
		{"Kerajinan Anyaman", "Perdagangan/UMKM", "buah"},
		{"Sesuatu Yang Tak Dikenal", "Lainnya", "unit"},
	}
	for _, c := range cases {
		kat, sat := deriveKategori(c.nama)
		if kat != c.wantKat || sat != c.wantSat {
			t.Errorf("deriveKategori(%q) = (%q,%q), mau (%q,%q)", c.nama, kat, sat, c.wantKat, c.wantSat)
		}
	}
}
