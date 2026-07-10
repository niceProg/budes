// Package reference adalah read-layer read-only atas Reference DB KDMP (PRD §6b).
// Semua query hanya SELECT; tidak pernah menulis.
package reference

import (
	"context"

	"github.com/jackc/pgx/v5/pgxpool"
)

// Repository membaca data sisi-suplai dari Reference DB.
type Repository struct {
	pool *pgxpool.Pool
}

// NewRepository membuat read-layer di atas pool Reference DB.
func NewRepository(pool *pgxpool.Pool) *Repository {
	return &Repository{pool: pool}
}

// Koperasi = hasil lookup koperasi untuk penautan identitas / penjamin.
type Koperasi struct {
	KoperasiRef  string `json:"koperasi_ref"`
	NamaKoperasi string `json:"nama_koperasi"`
	Provinsi     string `json:"provinsi"`
	KabKota      string `json:"kab_kota"`
}

// Anggota = hasil lookup anggota koperasi (identitas produsen riil).
type Anggota struct {
	AnggotaRef  string `json:"anggota_ref"`
	KoperasiRef string `json:"koperasi_ref"`
	Nama        string `json:"nama"`
}

// PotensiDesa = sinyal kapasitas produksi komoditas unggulan desa (pra-pesan, Modul G).
type PotensiDesa struct {
	KomoditasRef  string `json:"komoditas_ref"`
	NamaKomoditas string `json:"nama_komoditas"`
	Provinsi      string `json:"provinsi"`
	KabKota       string `json:"kab_kota"`
	Kecamatan     string `json:"kecamatan"`
	Desa          string `json:"desa"`
	Volume        string `json:"volume"`
	NilaiPotensi  int64  `json:"nilai_potensi_desa"`
}

// Kandidat = koperasi kandidat pemenuh sebuah kebutuhan (Modul G).
type Kandidat struct {
	KoperasiRef  string  `json:"koperasi_ref"`
	NamaKoperasi string  `json:"nama_koperasi"`
	Provinsi     string  `json:"provinsi"`
	KabKota      string  `json:"kab_kota"`
	Kecamatan    string  `json:"kecamatan"`
	NamaProduk   string  `json:"nama_produk"`
	Stok         float64 `json:"stok"`
	Score        float64 `json:"score"`
}

// AnggotaRefExists mengecek apakah anggota_ref benar-benar ada di dataset KDMP.
func (r *Repository) AnggotaRefExists(ctx context.Context, ref string) (bool, error) {
	var ok bool
	err := r.pool.QueryRow(ctx, `SELECT EXISTS(SELECT 1 FROM anggota_koperasi WHERE anggota_ref=$1)`, ref).Scan(&ok)
	return ok, err
}

// KoperasiRefExists mengecek apakah koperasi_ref ada di dataset KDMP.
func (r *Repository) KoperasiRefExists(ctx context.Context, ref string) (bool, error) {
	var ok bool
	err := r.pool.QueryRow(ctx, `SELECT EXISTS(SELECT 1 FROM referensi_koperasi_wilayah WHERE koperasi_ref=$1)`, ref).Scan(&ok)
	return ok, err
}

// SearchKoperasi mencari koperasi berdasarkan nama (case-insensitive).
func (r *Repository) SearchKoperasi(ctx context.Context, q string, limit int) ([]Koperasi, error) {
	const sql = `
		SELECT p.koperasi_ref, COALESCE(p.nama_koperasi,''),
		       COALESCE(w.provinsi,''), COALESCE(w.kab_kota,'')
		FROM profil_koperasi p
		LEFT JOIN referensi_koperasi_wilayah kw ON kw.koperasi_ref = p.koperasi_ref
		LEFT JOIN referensi_wilayah w           ON w.kode_wilayah  = kw.kode_wilayah
		WHERE p.nama_koperasi ILIKE '%' || $1 || '%'
		ORDER BY p.nama_koperasi
		LIMIT $2`
	rows, err := r.pool.Query(ctx, sql, q, limit)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []Koperasi{}
	for rows.Next() {
		var k Koperasi
		if err := rows.Scan(&k.KoperasiRef, &k.NamaKoperasi, &k.Provinsi, &k.KabKota); err != nil {
			return nil, err
		}
		out = append(out, k)
	}
	return out, rows.Err()
}

// SearchAnggota mencari anggota koperasi berdasarkan nama (case-insensitive).
func (r *Repository) SearchAnggota(ctx context.Context, q string, limit int) ([]Anggota, error) {
	const sql = `
		SELECT anggota_ref, koperasi_ref, COALESCE(nama,'')
		FROM anggota_koperasi
		WHERE nama ILIKE '%' || $1 || '%'
		ORDER BY nama
		LIMIT $2`
	rows, err := r.pool.Query(ctx, sql, q, limit)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []Anggota{}
	for rows.Next() {
		var a Anggota
		if err := rows.Scan(&a.AnggotaRef, &a.KoperasiRef, &a.Nama); err != nil {
			return nil, err
		}
		out = append(out, a)
	}
	return out, rows.Err()
}

// MatchKandidat mencocokkan kebutuhan (item + wilayah opsional) ke koperasi
// yang punya stok gerai relevan. Join: inventaris_produk → koperasi → wilayah.
// provinsi kosong = tidak difilter wilayah. Skor sederhana = besar stok.
func (r *Repository) MatchKandidat(ctx context.Context, item, provinsi string, limit int) ([]Kandidat, error) {
	const sql = `
		SELECT i.koperasi_ref, COALESCE(p.nama_koperasi,''),
		       COALESCE(w.provinsi,''), COALESCE(w.kab_kota,''), COALESCE(w.kecamatan,''),
		       COALESCE(i.nama_produk,''), i.stok
		FROM inventaris_produk i
		JOIN referensi_koperasi_wilayah kw ON kw.koperasi_ref = i.koperasi_ref
		JOIN referensi_wilayah w           ON w.kode_wilayah  = kw.kode_wilayah
		LEFT JOIN profil_koperasi p        ON p.koperasi_ref  = i.koperasi_ref
		WHERE i.nama_produk ILIKE '%' || $1 || '%'
		  AND i.stok > 0
		  AND ($2 = '' OR w.provinsi = $2)
		ORDER BY i.stok DESC
		LIMIT $3`
	rows, err := r.pool.Query(ctx, sql, item, provinsi, limit)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []Kandidat{}
	for rows.Next() {
		var k Kandidat
		if err := rows.Scan(&k.KoperasiRef, &k.NamaKoperasi, &k.Provinsi,
			&k.KabKota, &k.Kecamatan, &k.NamaProduk, &k.Stok); err != nil {
			return nil, err
		}
		k.Score = k.Stok
		out = append(out, k)
	}
	return out, rows.Err()
}

// MatchPotensiDesa mencari desa berpotensi memproduksi sebuah komoditas (pra-pesan),
// dari referensi_komoditas_desa × wilayah. provinsi kosong = tak difilter.
// Diurutkan berdasarkan nilai potensi ekonomi desa.
func (r *Repository) MatchPotensiDesa(ctx context.Context, item, provinsi string, limit int) ([]PotensiDesa, error) {
	const sql = `
		SELECT k.komoditas_ref, COALESCE(k.nama_komoditas,''),
		       COALESCE(w.provinsi,''), COALESCE(w.kab_kota,''), COALESCE(w.kecamatan,''),
		       COALESCE(w.desa_kelurahan,''), COALESCE(k.volume,''), COALESCE(k.nilai_potensi_desa,0)
		FROM referensi_komoditas_desa k
		LEFT JOIN referensi_wilayah w ON w.kode_wilayah = k.kode_wilayah
		WHERE k.nama_komoditas ILIKE '%' || $1 || '%'
		  AND ($2 = '' OR w.provinsi = $2)
		ORDER BY k.nilai_potensi_desa DESC NULLS LAST
		LIMIT $3`
	rows, err := r.pool.Query(ctx, sql, item, provinsi, limit)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	out := []PotensiDesa{}
	for rows.Next() {
		var p PotensiDesa
		if err := rows.Scan(&p.KomoditasRef, &p.NamaKomoditas, &p.Provinsi, &p.KabKota,
			&p.Kecamatan, &p.Desa, &p.Volume, &p.NilaiPotensi); err != nil {
			return nil, err
		}
		out = append(out, p)
	}
	return out, rows.Err()
}
