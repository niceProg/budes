# Budes Backend (Go)

REST API dengan arsitektur **dua-DB** (PRD §5): App DB (writable) + Reference DB (read-only, dataset KDMP).

## Struktur
```
cmd/api/main.go            entrypoint (graceful shutdown)
internal/config            loader env
internal/db                dua pgxpool (app rw + ref ro)
internal/httpx             helper response JSON
internal/health            GET /health (cek kedua DB)
internal/reference         read-layer read-only atas Reference DB KDMP
internal/server            router + middleware (log, recover, CORS)
migrations                 skema App DB (golang-migrate style)
```

## Menjalankan
Prasyarat: kedua Postgres jalan (`cd ../deploy && docker compose up -d`).
```bash
cp .env.example .env        # sesuaikan bila perlu
go run ./cmd/api            # default :8080
```

## Endpoint
| Method | Path | Keterangan |
|---|---|---|
| GET | `/health` | Status App DB & Reference DB |
| GET | `/api/ref/koperasi?q=&limit=` | Lookup koperasi (penautan identitas / penjamin) |
| GET | `/api/ref/anggota?q=&limit=` | Lookup anggota (identitas produsen riil) |
| GET | `/api/match/kandidat?item=&provinsi=&limit=` | Modul G — kandidat pemenuh dari stok gerai nyata |

Contoh:
```bash
curl "http://localhost:8080/api/match/kandidat?item=telur&provinsi=JAWA%20BARAT&limit=5"
```

## Migrasi App DB
File di `migrations/` (format golang-migrate). Untuk dev cepat bisa langsung:
```bash
psql "$APP_DATABASE_URL" -f migrations/000001_init_schema.up.sql
```
