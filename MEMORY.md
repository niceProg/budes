# MEMORY — Bursa Desa (Budes)

> **Dokumen acuan tunggal** untuk pengerjaan backend & frontend. Sintesis dari `mission.md` + `PRD.md` + `TODO.md`, disinkronkan dengan kondisi kode aktual (`backend/`). Bila ada konflik, urutan sumber kebenaran: **kode aktual → PRD.md → erd_marketplace_koperasi.mermaid → TODO.md → mission.md**.
>
> Update terakhir sinkronisasi kode: 2026-07-10.

---

## 1. Elevator Pitch (kenapa Budes berbeda)

**Koperasi Desa (KDMP) sebagai hub/perantara digital** antara warga desa dan pasar. Bukan menambah etalase pasif — koperasi jadi perantara aktif yang menciptakan kepastian. Dua alur transaksi saling melengkapi:

- **Alur A — Pra-pesan / Demand (marketplace terbalik):** pembeli mengumumkan kebutuhan lebih dulu → koperasi menyebarkan ke warga → warga menyanggupi (penuh/sebagian, **gotong royong**) → setor ke koperasi → serah ke pembeli. Petani baru berproduksi saat permintaan sudah pasti → menekan risiko gagal serap & harga jatuh.
- **Alur B — Titip-jual / Supply:** warga menitipkan komoditas yang **sudah ada** → koperasi posting sebagai listing → pembeli tinggal memesan. Panen siap tak menganggur menunggu tengkulak.

**Prinsip pembayaran:** transaksi terjadi **nyata di lapangan** (tunai/transfer). Sistem hanya **mencatat & merapikan** (gross → komisi koperasi → net ke warga) + status pembayaran. **TIDAK ada escrow, dompet digital, atau rekening bersama.** Menyesuaikan realitas transaksi desa yang mayoritas tunai.

**Uang muka (DP) pra-pesan:** pembeli setor DP (default **30%**) sebagai komitmen sebelum warga berproduksi; sisanya dilunasi saat serah-terima. Batal sepihak → DP hangus (`FORFEITED`) sebagai kompensasi; pesanan gagal → DP dikembalikan (`REFUNDED`).

**Grounded pada data nyata KDMP** (bukan katalog karangan): 1.026 koperasi desa, 74.269 anggota, 8.191 potensi komoditas unggulan, ~13.974 produk gerai — terpetakan ke hierarki wilayah provinsi→kab/kota→kecamatan→desa. Data koperasi/komoditas/warga di-seed & tetap tertaut ke dataset resmi via kolom `*_ref`.

**Masalah yang dipecahkan:** petani berproduksi "buta" tanpa kepastian pembeli & bergantung tengkulak; UMKM kuliner sulit dapat pasokan segar stabil; koperasi desa stagnan sebagai penampung pasif. Budes mempertemukan dua kebutuhan yang saling melengkapi ini lewat koperasi.

---

## 2. Peran Pengguna (3 role)

| Role | Deskripsi | Aksi utama |
|---|---|---|
| **BUYER** | Pembeli, bisa dari luar desa (UMKM kuliner, katering, restoran) | Pasang kebutuhan (A), bayar DP, pesan listing (B), konfirmasi terima, lunasi |
| **WARGA** | Produsen anggota koperasi (petani/peternak) | Sanggupi demand (A), titip komoditas/listing (B), setor ke koperasi |
| **ADMIN_KOPERASI** | Pengurus koperasi (hub) | Kelola transaksi kedua alur, tinjau KYC, update status pembayaran, pembukuan/komisi |

**Verifikasi identitas (KYC):** BUYER & WARGA ajukan NIK + foto KTP + dokumen pendukung opsional. ADMIN_KOPERASI meninjau. `verification_status`: `UNVERIFIED → PENDING → VERIFIED/REJECTED`.

**Try-before-register:** halaman publik (Jelajah Pasar untuk Demand, Etalase Listing untuk Supply) bisa dilihat **tanpa login**. Login/daftar diminta **just-in-time** saat aksi konkret (Sanggupi / Pasang / Pesan / Titipkan).

---

## 3. Alur Bisnis Detail

### Alur A — Pra-pesan (Demand)
1. Pembeli **Buat Postingan Baru**: pilih komoditas, jumlah, satuan, target harga/item, tenggat. Demand tersimpan `DRAFT`; sistem hitung `total_price`, `dp_amount` (30%), `remaining_amount`.
2. Pembeli **bayar uang muka** (offline, tunai/transfer); koperasi catat `dp_status = PAID`, `dp_paid_at`. Demand `DRAFT → OPEN` & tampil publik (berstempel "DP Terbayar").
3. Koperasi/sistem menyebar ke warga relevan (**matching grounded**). Warga tekan **Sanggupi** dengan jumlah yang sanggup dipenuhi — banyak warga bisa mengisi bersama (gotong royong) sampai `fulfilled_qty` = `total_qty`.
4. Warga menyiapkan & **setor ke koperasi** sebelum tenggat (`DELIVERED_TO_KOPERASI`). Koperasi **serah ke pembeli** (`HANDED_TO_BUYER`).
5. Pembeli **lunasi `remaining_amount`** & konfirmasi penerimaan. Sistem catat `demand_transactions` **per warga** (gross → koperasi_fee → net). Batal → DP `FORFEITED`; bermasalah → `disputes` (hanya bagian terkait).

**Sengketa per bagian:** pada pesanan gotong royong, bila 1 warga gagal setor, hanya pledge warga itu masuk sengketa; bagian warga lain tetap diproses.

### Alur B — Titip-jual (Supply)
1. Warga **titip komoditas** yang sudah ada; koperasi posting `supply_listings` (qty tersedia + harga).
2. Pembeli telusuri etalase, buat `orders` (qty, snapshot harga) → koperasi `CONFIRMED`.
3. Serah-terima di koperasi/gerai (`HANDED_OVER`); pembeli konfirmasi.
4. Sistem catat `supply_transactions` (gross, komisi, net) + status pembayaran. Sengketa bila perlu.

### Komisi
Sistem hitung otomatis komisi koperasi — **default 5%** (`koperasi_fee`) dari setiap transaksi. Masuk pembukuan koperasi (`GET /api/pembukuan`).

---

## 4. Arsitektur (Dua-Database)

Dataset KDMP bersifat **read-only** (`SELECT` saja), maka Budes memisah penyimpanan jadi **dua Postgres**:

- **App DB (writable)** — skema mandiri Budes, satu-satunya sumber tulis. Port lokal **5434**, DB `budes_app`. 11 tabel (lihat §6).
- **Reference DB (read-only)** — cermin lokal dataset KDMP (27 tabel), DB `hackathon_2026`, port lokal **5433**. Untuk **(a) seed** awal & **(b) lookup live** saat registrasi/penautan identitas & matching. Diakses via connection pool khusus SELECT (`SET default_transaction_read_only=on`).

Kolom `*_ref` (`koperasi_ref`, `anggota_ref`, `komoditas_ref`) = **soft reference** — nilai biasa penanda asal-usul KDMP, **bukan FK lintas-database**.

```
Frontend (Nuxt3/Pinia)  <-- JSON REST + JWT / WebSocket -->  Backend (Go)
                                                              |-- Auth & Role
                                                              |-- Demand & Pledge (A)
                                                              |-- Supply & Order (B)
                                                              |-- Transaksi & Komisi (catat offline)
                                                              |-- Reference Data (read-only)
                                                              |-- Matching & Broadcast
                                        App DB (rw, :5434) <--/       \--> Ref DB (ro, :5433, KDMP)
```

---

## 5. Tech Stack

- **Frontend:** Nuxt.js (Vue 3) + Tailwind CSS / Nuxt UI · **Pinia** (state) · **SSR** untuk halaman publik (SEO, try-before-register) · wrapper `$fetch` + interceptor JWT.
- **Backend:** Go (Golang) — REST API pakai **`net/http`** (Go 1.22+ pattern routing, mis. `GET /api/demands/{id}`) · **JWT HS256** · WebSocket untuk real-time (belum dibangun) · **pgxpool** (2 pool: App rw + Ref ro) · bcrypt untuk password.
- **Database:** PostgreSQL — **dua instance** (App DB writable + Reference DB read-only). Tanpa escrow/dompet.
- **Deployment:** Docker + Docker Compose (Nuxt + Go + App DB + Reference DB).

**Konvensi API:** response JSON, penamaan `snake_case`, **UUID sebagai PK** semua tabel App DB.

---

## 6. Skema App DB (writable) — 11 tabel

Semua PK `UUID`. Kolom audit `user_input`/`tanggal_input`/`user_update`/`tanggal_update` ada di tabel transaksional. Migrasi tunggal: `backend/migrations/000001_init_schema.up.sql` (sudah diterapkan ke :5434).

| Tabel | Kolom inti | Soft-ref | Alur |
|---|---|---|---|
| `koperasi` | id, nama, desa, wilayah, kontak, status (1=aktif/9=hapus) | `koperasi_ref` | hub |
| `users` | id, koperasi_id (null bila buyer eksternal), name, phone, email, password_hash, role, status, `verification_status`, verified_at | `anggota_ref` | — |
| `verifications` | id, user_id, nik, id_card_file, support_doc_file, status(PENDING/VERIFIED/REJECTED), reviewed_by, review_note, reviewed_at | — | KYC |
| `komoditas` | id, nama, kategori, satuan (kg/ikat/karung) | `komoditas_ref` | A+B |
| `demands` | id, buyer_id, koperasi_id, komoditas_id, item_name, satuan, total_qty, fulfilled_qty, target_price_per_item, deadline, `demand_status`; **DP:** total_price, dp_percent(30), dp_amount, remaining_amount, dp_payment_method, `dp_status`, dp_paid_at | via komoditas | A |
| `demand_pledges` | id, demand_id, warga_id, qty_pledged, qty_delivered, price_per_item, `pledge_status` | — | A |
| `supply_listings` | id, koperasi_id, warga_id, komoditas_id, item_name, satuan, qty_available, qty_sold, price_per_item, `listing_status` | via komoditas | B |
| `orders` | id, listing_id, buyer_id, qty_ordered, price_per_item(snapshot), total_amount, `order_status` | — | B |
| `demand_transactions` | id, demand_pledge_id, gross_amount, koperasi_fee, net_amount, payment_method, `payment_status`, paid_at | — | A |
| `supply_transactions` | id, order_id, gross_amount, koperasi_fee, net_amount, payment_method, `payment_status`, paid_at | — | B |
| `disputes` | id, demand_pledge_id, order_id, source_type(DEMAND/SUPPLY), reported_by, reason, `dispute_status`, resolution, resolved_at | — | A+B |

### Enum status (WAJIB konsisten FE↔BE)
- `role`: `BUYER` · `WARGA` · `ADMIN_KOPERASI`
- `verification_status` (users): `UNVERIFIED` · `PENDING` · `VERIFIED` · `REJECTED`
- `demand_status`: `DRAFT` · `OPEN` · `PARTIAL` · `CLOSED` · `EXPIRED`
- `dp_status`: `UNPAID` · `PAID` · `FORFEITED` · `REFUNDED`
- `pledge_status`: `PENDING` · `ACCEPTED` · `DELIVERED_TO_KOPERASI` · `HANDED_TO_BUYER` · `CANCELLED`
- `listing_status`: `DRAFT` · `POSTED` · `SOLD_OUT` · `CLOSED`
- `order_status`: `PENDING` · `CONFIRMED` · `HANDED_OVER` · `CANCELLED`
- `payment_status` (transactions): `UNPAID` · `PAID` · `SETTLED`
- `payment_method` / `dp_payment_method`: `CASH` · `TRANSFER`
- `dispute_status`: `OPEN` · `REVIEW` · `RESOLVED`

---

## 6b. Reference DB (read-only, KDMP) — seed mapping

Cermin lokal 27 tabel `hackathon_2026` (di-clone ke `budes_ref_db`, `localhost:5433`). Untuk seed App DB + lookup + matching.

| Tabel App DB | Di-seed dari KDMP | Kolom kunci |
|---|---|---|
| `koperasi` (1.026) | `profil_koperasi` × `referensi_koperasi_wilayah` × `referensi_wilayah` | `koperasi_ref`, `nama_koperasi`, `provinsi`, `kab_kota`, `kecamatan`, `desa_kelurahan` |
| `users`(WARGA) (74.269 sumber; 10 demo di-seed) | `anggota_koperasi` | `anggota_ref`, `koperasi_ref`, `nama` |
| `komoditas` (8.191) | `referensi_komoditas_desa` | `komoditas_ref`, `nama_komoditas`, `volume`, `nilai_potensi_desa` |
| (matching) stok gerai (13.974) | `produk_koperasi` + `inventaris_produk` | `produk_sample_id`, `koperasi_ref`, `stok` |
| (distribusi) titik gerai (1.942) | `gerai_koperasi` + `referensi_gerai_koperasi` | `gerai_ref`, `koperasi_ref` |

**Catatan kualitas data:** kolom `satuan`/`unit` bervariasi ("Gal", "LITER", "50 Kg", "Ikat"); sebagian `volume`/`nilai_potensi_desa` berskala ekstrem → perlu normalisasi ringan saat seed & matching.

---

## 7. Backend — struktur & status aktual

Module Go `budes` (Go 1.26, pgx v5, JWT v5, bcrypt). Struktur `backend/`:

```
cmd/api/main.go          entrypoint API
cmd/seed/main.go         seeder App DB dari KDMP
internal/
  config/config.go       env loader (PORT, APP_DATABASE_URL, REF_DATABASE_URL, JWT_SECRET)
  db/db.go               2 pgxpool (App rw + Ref ro dgn read-only guard)
  server/server.go       router net/http + middleware (recover, logger, cors)
  httpx/response.go      helper response JSON
  health/               GET /health (cek kedua DB)
  auth/                 jwt, middleware (RequireRole), repo, service, handler
  demand/               types, repo, service, handler (alur A)
  supply/               types, repo, handler (alur B)
  settlement/           types, repo, handler (serah-terima + transaksi/komisi + sengketa)
  verification/         KYC submit/list/review
  reference/            read-only KDMP: lookup koperasi/anggota + match kandidat
  match/                matching grounded per demand
  seed/                 seed.go + kategori.go (derivasi kategori/satuan)
migrations/000001_init_schema.{up,down}.sql
.env.example · README.md
```

**Pola arsitektur backend:** `repository → service → handler` per modul + JWT middleware. Thin handler, logika di service/repo. Anti over-pledge/over-order via transaksi Postgres `FOR UPDATE`.

**Config default (dev lokal):**
- `PORT=8080`
- `APP_DATABASE_URL=postgres://budes:budes@localhost:5434/budes_app`
- `REF_DATABASE_URL=postgres://budes:budes@localhost:5433/hackathon_2026`
- `JWT_SECRET=budes-dev-secret-change-me` (⚠️ `.env.example` belum mencantumkan `JWT_SECRET` — tambahkan)

### Daftar endpoint (aktual dari `server/server.go`)

**Publik (tanpa auth):**
```
GET  /health
GET  /api/ref/koperasi?q=          lookup koperasi KDMP (penautan identitas)
GET  /api/ref/anggota?q=           lookup anggota KDMP
GET  /api/match/kandidat?item=&provinsi=   kandidat pemenuh dari stok gerai nyata
GET  /api/demands                  list demand (default OPEN+PARTIAL, filter ?status=)
GET  /api/demands/{id}             detail demand + pledges
GET  /api/demands/{id}/kandidat    kandidat grounded per demand
GET  /api/listings                 list supply listing publik
GET  /api/listings/{id}            detail listing
```

**Auth:**
```
POST /api/auth/register            (bcrypt; validasi *_ref ke Ref DB BELUM)
POST /api/auth/login               → JWT HS256
POST /api/auth/logout              (stateless)
GET  /api/me                       [auth] profil user login
```

**Demand — alur A** (Modul B):
```
POST /api/demands                  [BUYER] hitung total/dp 30%/remaining; mulai DRAFT
POST /api/demands/{id}/dp          [BUYER] catat dp_status=PAID → demand OPEN
POST /api/demands/{id}/cancel      [BUYER] DP FORFEITED
POST /api/demands/{id}/pledges     [WARGA] sanggupi (anti over-pledge)
GET  /api/pledges                  [auth] pledge milik warga login
PUT  /api/pledges/{id}             [auth] transisi status; CANCELLED kembalikan kuota
```

**Supply — alur B** (Modul C):
```
POST /api/listings                 [WARGA, ADMIN_KOPERASI] buat listing
PUT  /api/listings/{id}            [auth] set status (DRAFT/POSTED/SOLD_OUT/CLOSED)
GET  /api/my/listings              [auth] listing milik sendiri
POST /api/orders                   [BUYER] pesan (snapshot harga, anti over-order)
GET  /api/orders                   [BUYER] pesananku
GET  /api/orders/{id}              [auth] detail order
PUT  /api/orders/{id}              [auth] set status; CANCELLED kembalikan stok
```

**Settlement — serah-terima + transaksi/komisi + sengketa** (Modul D+E):
```
POST /api/pledges/{id}/verifikasi  [BUYER] → HANDED_TO_BUYER + demand_transactions (qty parsial OK)
POST /api/orders/{id}/verifikasi   [BUYER] → HANDED_OVER + supply_transactions
POST /api/pledges/{id}/lapor       [auth] buat dispute (source DEMAND)
POST /api/orders/{id}/lapor        [auth] buat dispute (source SUPPLY)
GET  /api/transactions             [auth] per peran (admin lihat semua)
PUT  /api/transactions/{kind}/{id} [ADMIN_KOPERASI] payment_status UNPAID→PAID→SETTLED
GET  /api/pembukuan                [ADMIN_KOPERASI] rekap komisi demand+supply
```

**Verifikasi KYC** (Modul A):
```
POST /api/verifikasi               [auth] ajukan KYC
GET  /api/verifikasi               [ADMIN_KOPERASI] daftar PENDING
PUT  /api/verifikasi/{id}          [ADMIN_KOPERASI] VERIFIED/REJECTED → set users.verification_status
```

Middleware chain (luar→dalam): `recover → logger → cors`. CORS `Access-Control-Allow-Origin: *`.

---

## 8. Status pengerjaan (dari TODO)

### ✅ Selesai (Backend)
- Skeleton Go: router `net/http`, config loader, **2 pgxpool** (App rw + Ref ro read-only guard), health check.
- Migrasi `000001_init_schema` (11 tabel dua-alur + verifications + DP + audit + trigger) diterapkan ke :5434.
- Reference read-layer (`internal/reference`) + lookup koperasi/anggota.
- Seed App DB dari KDMP (`cmd/seed`): koperasi 1.026, komoditas 8.191, 10 WARGA demo (bisa login).
- **Auth:** register (bcrypt), login (JWT), logout, `GET /api/me`, middleware `RequireRole`.
- **Demand (alur A):** create/DP/list/detail/pledge/update — smoke-tested E2E; anti over-pledge; status DRAFT→OPEN→PARTIAL→CLOSED; DP FORFEITED via cancel.
- **Supply (alur B):** listings + orders — smoke-tested; anti over-order.
- **Settlement (D+E):** verifikasi terima → transaksi (komisi 5%), update payment status, pembukuan, lapor sengketa — smoke-tested.
- **KYC verification:** submit/list/review.
- **Matching (G):** `GET /api/match/kandidat`, `GET /api/demands/{id}/kandidat`, sinyal komoditas unggulan; skoring v1 (besar stok/nilai potensi).
- CORS + middleware (logging, recover).

### 🔴 Belum / Perlu Dikerjakan (Backend)
- **Validasi soft-ref** `koperasi_ref`/`anggota_ref`/`komoditas_ref` ke Reference DB saat register/penautan (+ cache resolve). **Register belum validasi.**
- DP `REFUNDED` (expire oleh koperasi) — belum.
- `GET /api/me/riwayat` (Riwayatku) — belum.
- Tooling migrasi formal (golang-migrate) — sekarang manual via `psql`.
- Script re-sync Reference DB (`pg_dump` → `pg_restore`).
- **WebSocket** (Modul F) hub/broadcast — belum ada.
- Broadcast tertarget saat demand dibuat (butuh Modul F).
- Skoring matching v2 (kecocokan komoditas × ketersediaan × kedekatan wilayah + normalisasi nama).
- Testing: unit (komisi, anti over-pledge/order, sengketa parsial, transisi status) + integration.

### ⬜ Belum Dikerjakan (Frontend — SEMUA)
> **Belum ada folder `/frontend`.** Seluruh UI perlu dibangun dari nol. Pendekatan **mock-first**: UI dgn data tiruan → sambung API.

**Fase 0 FE:** skeleton Nuxt (Tailwind/Nuxt UI, Pinia, layout, wrapper `$fetch` + interceptor JWT).

**Modul A — Akun & Profil:**
- Daftar (nama/phone/email/password/role) + validasi; Login + Logout; badge `verification_status` di header.
- Penautan identitas KDMP (cari koperasi/anggota dari Ref DB).
- Halaman Verifikasi Identitas (unggah NIK+KTP+dok); Riwayatku; (Admin) Tinjau Verifikasi.

**Modul B — Demand (alur A):**
- Jelajah Pasar (publik, demand OPEN + progress); Buat Postingan (hitung total/dp/remaining); Bayar DP; Detail Permintaan (progress bar + penyanggup); tombol Sanggupi (JIT auth).

**Modul C — Supply (alur B):**
- Etalase Listing (publik); Titipkan Komoditas; Detail Listing + Pesan (JIT auth); Pesananku (buyer) & Titipanku (warga).

**Modul D — Konfirmasi Serah-Terima:**
- Daftar Serah-Terima; Verifikasi Terima (penuh/sebagian); Lapor Masalah → sengketa; live update tanpa reload.

**Modul E — Transaksi & Komisi:**
- Status pembayaran per pledge/order; Dashboard Koperasi (semua transaksi + total komisi); ringkasan pembukuan per periode.

**Modul F — Notifikasi/Real-time:** koneksi WS + update status langsung; notif in-app (bell) + fallback polling.

**Modul G — Matching (FE):** seksi "Desa/koperasi yang berpotensi memenuhi" di Detail Permintaan; empty state → CTA "Kabari saya kalau ada".

### Lintas-modul
- **Responsive:** mobile-first, breakpoint sm/md/lg/xl; empty state selalu ada CTA; komponen reusable (card, badge status, progress bar, modal); **format Rupiah & tanggal id-ID**; bahasa Indonesia konsisten; SSR/meta untuk halaman publik (SEO).
- **Testing:** golangci-lint + gofmt (Go); ESLint + Prettier + Vitest (Nuxt).
- **Deployment:** Dockerfile Nuxt (SSR) & Go (multi-stage); Compose produksi + migrasi/seed otomatis; HTTPS reverse proxy; CI sederhana.

---

## 9. Prinsip pengerjaan (WAJIB dipatuhi)

1. **Try-before-register** — halaman publik dapat dilihat tanpa login; auth diminta just-in-time saat aksi menulis.
2. **Mock-first** — bangun UI dgn data tiruan dulu → sambung endpoint yang sudah ada.
3. **Frontend & backend terpisah** — komunikasi JSON REST + JWT; real-time via WebSocket.
4. **Grounded pada data nyata** — koperasi/komoditas/warga di-seed dari KDMP read-only; simpan `*_ref`; matching pakai sinyal komoditas & inventaris nyata.
5. **Transaksi offline** — tidak ada escrow/dompet; sistem hanya mencatat (gross/fee/net + status `UNPAID/PAID/SETTLED`).
6. **Konsistensi enum** — nilai status di FE harus persis sama dengan enum §6 (backend memvalidasi transisi).
7. **UUID PK · snake_case JSON · komisi default 5% · DP default 30%.**

---

## 10. Menjalankan lokal (blocker & langkah)

**Toolchain saat ini:** Docker + Node + Git tersedia. ⚠️ **Go 1.26 belum terinstal**; **psql belum ada**. Pertimbangkan jalankan backend via Docker.

**Alur dev (per `backend/README.md`):**
1. `docker-compose up` (dari `deploy/`) — sediakan App DB (:5434) + Reference DB (:5433).
2. Siapkan `.env` — App/Ref DSN + `JWT_SECRET`.
3. Terapkan migrasi `000001_init_schema` ke App DB.
4. Seed: `go run ./cmd/seed`.
5. Jalankan API: `go run ./cmd/api` (port 8080).

**File deploy:** `deploy/docker-compose.yml` + `deploy/sync-ref-db.sh`. Reference DB sudah di-clone ke `budes_ref_db` (:5433).

---

## 11. Sumber kebenaran & referensi file
- `PRD.md` — spesifikasi lengkap (Overview, Requirements, Features, User Flow, Architecture, DB Schema, Stack).
- `erd_marketplace_koperasi.mermaid` — ERD lengkap App DB.
- `mission.md` — narasi visi & masalah.
- `TODO.md` — checklist eksekusi per fase/modul.
- `backend/README.md` — cara jalan backend lokal.
- `backend/migrations/000001_init_schema.up.sql` — sumber skema App DB definitif.
- `backend/internal/server/server.go` — daftar route definitif.
