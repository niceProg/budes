// Package seed mengisi App DB (writable) dari dataset KDMP (Reference DB, read-only).
// Idempoten: TRUNCATE tabel target lalu isi ulang. Menyimpan *_ref untuk telusur balik.
package seed

import (
	"context"
	"fmt"

	"budes/internal/db"

	"github.com/jackc/pgx/v5"
	"golang.org/x/crypto/bcrypt"
)

const (
	demoWargaLimit = 10         // seed 10 warga demo yang bisa login
	demoPassword   = "budes123" // password default warga demo
)

// Result meringkas jumlah baris yang ter-seed.
type Result struct{ Koperasi, Komoditas, Warga int }

// Run menjalankan seluruh proses seed dari KDMP → App DB.
func Run(ctx context.Context, pools *db.Pools) (Result, error) {
	var res Result
	if _, err := pools.App.Exec(ctx, `TRUNCATE users, komoditas, koperasi RESTART IDENTITY CASCADE`); err != nil {
		return res, fmt.Errorf("truncate: %w", err)
	}

	kopMap, n, err := seedKoperasi(ctx, pools)
	if err != nil {
		return res, fmt.Errorf("koperasi: %w", err)
	}
	res.Koperasi = n

	if res.Komoditas, err = seedKomoditas(ctx, pools); err != nil {
		return res, fmt.Errorf("komoditas: %w", err)
	}
	if res.Warga, err = seedWarga(ctx, pools, kopMap); err != nil {
		return res, fmt.Errorf("warga: %w", err)
	}
	return res, nil
}

// seedKoperasi menyalin profil_koperasi × wilayah → koperasi, lalu kembalikan map koperasi_ref→id.
func seedKoperasi(ctx context.Context, pools *db.Pools) (map[string]string, int, error) {
	const q = `
		SELECT DISTINCT ON (kw.koperasi_ref)
		       kw.koperasi_ref,
		       COALESCE(NULLIF(p.nama_koperasi, ''), 'Koperasi ' || kw.koperasi_ref),
		       COALESCE(w.desa_kelurahan, ''),
		       btrim(COALESCE(w.provinsi, '') || ' / ' || COALESCE(w.kab_kota, '') || ' / ' || COALESCE(w.kecamatan, ''), ' /')
		FROM referensi_koperasi_wilayah kw
		LEFT JOIN profil_koperasi  p ON p.koperasi_ref = kw.koperasi_ref
		LEFT JOIN referensi_wilayah w ON w.kode_wilayah = kw.kode_wilayah
		WHERE kw.koperasi_ref IS NOT NULL
		ORDER BY kw.koperasi_ref`
	rows, err := pools.Ref.Query(ctx, q)
	if err != nil {
		return nil, 0, err
	}
	var data [][]any
	for rows.Next() {
		var ref, nama, desa, wil string
		if err := rows.Scan(&ref, &nama, &desa, &wil); err != nil {
			rows.Close()
			return nil, 0, err
		}
		data = append(data, []any{ref, nama, desa, wil})
	}
	rows.Close()
	if err := rows.Err(); err != nil {
		return nil, 0, err
	}

	n, err := pools.App.CopyFrom(ctx, pgx.Identifier{"koperasi"},
		[]string{"koperasi_ref", "nama", "desa", "wilayah"}, pgx.CopyFromRows(data))
	if err != nil {
		return nil, 0, err
	}

	m := make(map[string]string, len(data))
	mrows, err := pools.App.Query(ctx, `SELECT koperasi_ref, id::text FROM koperasi WHERE koperasi_ref IS NOT NULL`)
	if err != nil {
		return nil, int(n), err
	}
	defer mrows.Close()
	for mrows.Next() {
		var ref, id string
		if err := mrows.Scan(&ref, &id); err != nil {
			return nil, int(n), err
		}
		m[ref] = id
	}
	return m, int(n), mrows.Err()
}

// seedKomoditas menyalin referensi_komoditas_desa → komoditas (kategori/satuan diderivasi).
func seedKomoditas(ctx context.Context, pools *db.Pools) (int, error) {
	const q = `
		SELECT komoditas_ref, nama_komoditas
		FROM referensi_komoditas_desa
		WHERE nama_komoditas IS NOT NULL AND btrim(nama_komoditas) <> ''`
	rows, err := pools.Ref.Query(ctx, q)
	if err != nil {
		return 0, err
	}
	var data [][]any
	for rows.Next() {
		var ref, nama string
		if err := rows.Scan(&ref, &nama); err != nil {
			rows.Close()
			return 0, err
		}
		kat, sat := deriveKategori(nama)
		data = append(data, []any{ref, nama, kat, sat})
	}
	rows.Close()
	if err := rows.Err(); err != nil {
		return 0, err
	}
	n, err := pools.App.CopyFrom(ctx, pgx.Identifier{"komoditas"},
		[]string{"komoditas_ref", "nama", "kategori", "satuan"}, pgx.CopyFromRows(data))
	return int(n), err
}

// seedWarga membuat sejumlah kecil user peran WARGA (bisa login) dari anggota_koperasi.
func seedWarga(ctx context.Context, pools *db.Pools, kopMap map[string]string) (int, error) {
	const q = `
		SELECT anggota_ref, koperasi_ref, nama
		FROM anggota_koperasi
		WHERE nama IS NOT NULL AND koperasi_ref IS NOT NULL
		ORDER BY anggota_ref
		LIMIT $1`
	rows, err := pools.Ref.Query(ctx, q, demoWargaLimit)
	if err != nil {
		return 0, err
	}
	type warga struct{ ref, kopRef, nama string }
	var list []warga
	for rows.Next() {
		var w warga
		if err := rows.Scan(&w.ref, &w.kopRef, &w.nama); err != nil {
			rows.Close()
			return 0, err
		}
		list = append(list, w)
	}
	rows.Close()
	if err := rows.Err(); err != nil {
		return 0, err
	}

	hash, err := bcrypt.GenerateFromPassword([]byte(demoPassword), bcrypt.DefaultCost)
	if err != nil {
		return 0, err
	}

	count := 0
	for i, w := range list {
		var kopID any
		if id, ok := kopMap[w.kopRef]; ok {
			kopID = id
		}
		email := fmt.Sprintf("warga%d@budes.desa", i+1)
		if _, err := pools.App.Exec(ctx, `
			INSERT INTO users (koperasi_id, anggota_ref, name, email, password_hash, role)
			VALUES ($1, $2, $3, $4, $5, 'WARGA')`,
			kopID, w.ref, w.nama, email, string(hash)); err != nil {
			return count, err
		}
		count++
	}
	return count, nil
}
