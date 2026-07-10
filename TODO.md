# TODO — Bursa Desa (Budes) Web App

> **Konsep inti:** Koperasi Desa (KDMP) sebagai **hub/perantara** dengan **dua alur** — **A) Demand (pra-pesan):** pembeli bayar **uang muka (DP ~30%)** → warga menyanggupi (gotong royong) → setor ke koperasi → serah ke pembeli (lunasi sisa); **B) Supply (titip-jual):** warga menitipkan komoditas → koperasi posting listing → pembeli memesan. **Transaksi offline** (tunai/transfer) — sistem **mencatat** + hitung **komisi koperasi**, tanpa escrow/dompet penuh. Pengguna bisa **verifikasi identitas (KYC)** yang ditinjau ADMIN_KOPERASI.
>
> **Sumber kebenaran:** `PRD.md` + `erd_marketplace_koperasi.mermaid` + `features/*.md`. Dokumen ini menurunkannya jadi checklist yang bisa dieksekusi.

## Stack (per PRD §5 & §7)
- **Frontend:** Nuxt.js (Vue 3) + Tailwind CSS / Nuxt UI · Pinia (state) · SSR untuk halaman publik (SEO, try-before-register)
- **Backend:** Go (Golang) — REST API (router: chi/gin/echo atau `net/http`) · JWT auth · WebSocket untuk real-time · **2 connection pool** (App DB read-write + Reference DB read-only)
- **Database:** PostgreSQL — **dua instance** (arsitektur dua-DB, PRD §5):
  - **App DB** (writable) — transaksional: koperasi, users, komoditas, demands, demand_pledges, supply_listings, orders, demand_transactions, supply_transactions, disputes
  - **Reference DB** (read-only) — cermin lokal dataset KDMP (27 tabel; sumber **seed** + lookup + matching). Sudah di-clone ke `budes_ref_db` (Docker, `localhost:5433`), lihat `deploy/`
- **Deployment:** Docker + Docker Compose (Nuxt + Go + App DB + Reference DB)

## Legenda
- `[ ]` belum · `[~]` proses · `[x]` selesai
- 🔴 wajib MVP · 🟡 penting · 🟢 nice-to-have
- Prioritas modul mengikuti PRD §3: `[high]` / `[medium]`

## Prinsip pengerjaan (dari features)
1. **Try-before-register:** halaman publik (Jelajah Pasar untuk Demand, Etalase Listing untuk Supply) bisa dilihat tanpa login. Login/daftar diminta *just-in-time* saat aksi konkret (Sanggupi / Pasang / Pesan / Titipkan).
2. **Mock-first:** tiap modul dibangun UI dengan data tiruan dulu → lalu migrasi DB → lalu endpoint API → lalu sambungkan.
3. **Frontend & backend terpisah:** komunikasi via JSON REST + JWT; status real-time via WebSocket.
4. **Grounded pada data nyata:** koperasi, komoditas unggulan, dan warga **di-seed** dari **dataset KDMP (read-only)** dan menyimpan referensi balik (`koperasi_ref`, `komoditas_ref`, `anggota_ref`). Matching memakai sinyal komoditas & inventaris nyata.
5. **Transaksi offline:** tidak ada escrow/dompet; sistem hanya mencatat transaksi (gross/fee/net + status `UNPAID/PAID/SETTLED`).

---

## Fase 0 — Fondasi Proyek & DevOps 🔴
- [ ] Struktur monorepo: `/frontend` (Nuxt), `/backend` (Go), `/deploy` (docker) + Git + `.gitignore`
- [x] **Reference DB KDMP di-clone ke lokal** (Docker `budes_ref_db`, `localhost:5433`, 27 tabel, read-only) — `deploy/docker-compose.yml` + `deploy/dumps/`
- [ ] `docker-compose.yml`: service `web` (Nuxt), `api` (Go), `app-db` (App DB writable), `ref-db` (Reference DB read-only) + volume & network
- [ ] Env config bersama (`.env`): **`APP_DATABASE_URL`** (writable) + **`REF_DATABASE_URL`** (read-only), `JWT_SECRET`, `API_BASE_URL`, dll
- [x] Backend Go: skeleton (router `net/http`, config loader env, **2 koneksi Postgres: appDB rw + refDB ro** via pgxpool, health check `GET /health` cek keduanya) — `backend/`
- [x] Backend Go: pool refDB dibatasi read-only (`SET default_transaction_read_only=on` di AfterConnect) — cegah tulis tak sengaja
- [~] Backend Go: layer **repository-service-handler per modul** ✅ (auth, demand) + JWT middleware; tooling migrasi formal (golang-migrate) belum — migrasi diterapkan via `psql`
- [ ] Frontend Nuxt: skeleton (Tailwind/Nuxt UI, Pinia, layout dasar, wrapper `$fetch` + interceptor JWT)
- [ ] Konvensi API: format response JSON, error, penamaan `snake_case`, UUID sebagai PK
- [x] CORS + middleware dasar (logging, recover) di backend

## Fase 1 — Skema Database & Migrasi 🔴
> Migrasi **hanya untuk App DB** (writable). Reference DB KDMP tidak dimigrasi — di-clone (Fase 0) & dipakai untuk seed. Basis PRD §6 + ERD `erd_marketplace_koperasi.mermaid`.
> ✅ Migrasi `000001_init_schema` kini berisi **skema hub-koperasi dua-alur (11 tabel, termasuk `verifications` + DP)** dan **sudah diterapkan** ke App DB (:5434).

**App DB (writable) — migrasi (skema baru)** — semua `[x]` di `backend/migrations/000001_init_schema.up.sql`
- [x] `koperasi` (id, nama, desa, wilayah, kontak, status, **`koperasi_ref`**)
- [x] `users` (koperasi_id nullable, name, phone, email, password_hash, role `BUYER/WARGA/ADMIN_KOPERASI`, status, **`anggota_ref`**, **`verification_status`**, **`verified_at`**)
- [x] `verifications` (user_id, nik, id_card_file, support_doc_file, `status` PENDING/VERIFIED/REJECTED, reviewed_by, review_note, reviewed_at)
- [x] `komoditas` (id, nama, kategori, satuan, **`komoditas_ref`**)
- [x] `demands` (+ `demand_status` **DRAFT**/OPEN/PARTIAL/CLOSED/EXPIRED, **DP:** total_price, dp_percent, dp_amount, remaining_amount, dp_payment_method, `dp_status` UNPAID/PAID/FORFEITED/REFUNDED, dp_paid_at)
- [x] `demand_pledges` (qty_pledged, qty_delivered, price_per_item, `pledge_status` PENDING/ACCEPTED/DELIVERED_TO_KOPERASI/HANDED_TO_BUYER/CANCELLED)
- [x] `supply_listings` (qty_available, qty_sold, price_per_item, `listing_status` DRAFT/POSTED/SOLD_OUT/CLOSED)
- [x] `orders` (qty_ordered, price_per_item snapshot, total_amount, `order_status` PENDING/CONFIRMED/HANDED_OVER/CANCELLED)
- [x] `demand_transactions` / `supply_transactions` (gross_amount, koperasi_fee, net_amount, payment_method, payment_status UNPAID/PAID/SETTLED, paid_at)
- [x] `disputes` (demand_pledge_id/order_id nullable, source_type DEMAND/SUPPLY, reported_by, reason, dispute_status, resolution, resolved_at)
- [x] Kolom audit `user_input`/`tanggal_input`/`user_update`/`tanggal_update` + index status/FK utama + trigger tanggal_update

**Reference DB (read-only) — layer baca & seed**
- [x] Reference read-layer (repository read-only) — `backend/internal/reference/`; query koperasi, anggota, inventaris/produk × wilayah (join tervalidasi)
- [x] **Seed App DB dari KDMP** — `backend/cmd/seed` + `internal/seed`: `koperasi` (1.026) ← profil×wilayah; `komoditas` (8.191) ← referensi_komoditas_desa (kategori/satuan diderivasi); `users(WARGA)` (10 demo, bisa login) ← anggota_koperasi (simpan `*_ref`)
- [ ] Validasi soft-ref: cek `koperasi_ref`/`anggota_ref`/`komoditas_ref` benar-benar ada di Reference DB saat penautan (+ cache resolve)
- [ ] Script re-sync Reference DB: `pg_dump` remote → `pg_restore` ke `budes_ref_db` (dump ada di `deploy/dumps/`)

---

## Modul A — Akun & Profil `[medium]` → `features/05-akun-profil.md`
> Prasyarat aksi menulis (Pasang/Sanggupi/Pesan/Titip), walau publik-browse tak butuh login.

**Frontend**
- [ ] Layout & routing modul akun + state auth (Pinia, mock)
- [ ] Halaman Daftar (nama, phone, email, password, pilih peran BUYER/WARGA/ADMIN_KOPERASI) + validasi
- [ ] Peran WARGA/ADMIN_KOPERASI: **tautkan identitas KDMP riil** — cari & pilih koperasi (`koperasi_ref`) / anggota (`anggota_ref`) dari Reference DB 🟡
- [ ] Halaman Login + error "Email atau kata sandi tidak sesuai" · Logout
- [ ] Tampilkan nama & peran + badge `verification_status` di header setelah login
- [ ] Halaman **Verifikasi Identitas** (KYC): unggah NIK + foto KTP + dokumen pendukung 🟡
- [ ] Halaman **Riwayatku** (aktivitas + rekap transaksi & komisi)
- [ ] (Admin) Halaman **Tinjau Verifikasi** — daftar `PENDING`, aksi VERIFIED/REJECTED + catatan 🟡

**Backend (Go)** — paket `internal/auth` (jwt, middleware, repo, service, handler)
- [~] `POST /api/auth/register` (hash bcrypt ✅; **validasi `koperasi_ref`/`anggota_ref` ke Reference DB belum**) 🔴
- [x] `GET /api/ref/koperasi?q=` & `GET /api/ref/anggota?q=` (lookup read-only KDMP untuk penautan identitas) 🟡
- [x] `POST /api/auth/login` → terbitkan JWT (HS256) 🔴
- [x] Middleware autentikasi JWT + otorisasi peran (`RequireRole`) 🔴
- [x] `POST /api/auth/logout` (stateless) 🟡 · [x] `GET /api/me` · [ ] `GET /api/me/riwayat`
- [ ] `POST /api/verifikasi` (ajukan KYC) · `GET /api/verifikasi` (daftar; admin) · `PUT /api/verifikasi/:id` (ADMIN_KOPERASI: VERIFIED/REJECTED → set `users.verification_status`) 🟡

## Modul B — Alur A: Pasang Kebutuhan (Demand) `[high]` → `features/01-pasang-kebutuhan.md` 🔴 · **Backend ✅ · Frontend ⬜**
**Frontend**
- [ ] Halaman **Jelajah Pasar** (publik) — daftar demand (hanya yang `OPEN`/DP terbayar) + indikator progress
- [ ] Halaman **Buat Postingan Baru** (pilih komoditas, jumlah, satuan, target harga, tenggat) + tampil hitungan `total_price`/`dp_amount` (30%)/`remaining_amount`
- [ ] Langkah **Bayar Uang Muka (DP)** — pilih metode (CASH/TRANSFER), tandai DP terbayar → demand `DRAFT → OPEN`
- [ ] Halaman **Detail Permintaan** — status DP + progress bar % tersanggupi + daftar penyanggup
- [ ] Tombol **Sanggupi** (just-in-time auth)

**Backend (Go)** — paket `internal/demand` (types, repo, service, handler); ✅ smoke-tested end-to-end
- [x] `POST /api/demands` (auth BUYER; hitung total_price/dp_amount 30%/remaining_amount; mulai `DRAFT`)
- [x] `POST /api/demands/:id/dp` (catat `dp_status=PAID`, `dp_paid_at`, metode) → demand `OPEN`
- [x] `GET /api/demands` (publik; default `OPEN`+`PARTIAL`, filter `?status=`) · `GET /api/demands/:id` (detail + pledges)
- [x] `POST /api/demands/:id/pledges` (auth WARGA; anti over-pledge via tx `FOR UPDATE`; update `fulfilled_qty`)
- [x] `GET /api/pledges` (milik warga login) · `PUT /api/pledges/:id` (transisi status tervalidasi; CANCELLED kembalikan kuota)
- [x] Logika status demand `DRAFT → OPEN → PARTIAL → CLOSED` + anti over-pledge
- [~] Logika DP: `FORFEITED` via `POST /api/demands/:id/cancel` (buyer) ✅ · **`REFUNDED` (expire koperasi) belum**

## Modul C — Alur B: Titip-Jual (Supply) `[high]` → `features/02-titip-jual.md` 🔴
**Frontend**
- [ ] Halaman **Etalase Listing** (publik) — komoditas warga yang siap dijual
- [ ] Halaman **Titipkan Komoditas** (warga/koperasi buat listing: komoditas, qty, harga)
- [ ] Halaman **Detail Listing** + tombol **Pesan** (just-in-time auth)
- [ ] Halaman **Pesananku** (buyer) & **Titipanku** (warga)

**Backend (Go)**
- [ ] `POST /api/listings` (buat; warga/admin) · `GET /api/listings` (publik) · `GET /api/listings/:id`
- [ ] `PUT /api/listings/:id` (status DRAFT/POSTED/SOLD_OUT/CLOSED, rekalkulasi `qty_available`/`qty_sold`)
- [ ] `POST /api/orders` (buyer pesan; snapshot harga, hitung total) · `GET /api/orders` · `GET /api/orders/:id`
- [ ] `PUT /api/orders/:id` (status PENDING → CONFIRMED → HANDED_OVER/CANCELLED) + anti over-order

## Modul D — Konfirmasi Serah-Terima `[high]` → `features/03-konfirmasi-terima.md` 🔴
**Frontend**
- [ ] Halaman **Daftar Serah-Terima** (pledge DELIVERED_TO_KOPERASI / order CONFIRMED yang menunggu)
- [ ] Aksi **Verifikasi Terima** — penuh & sebagian
- [ ] Aksi **Lapor Masalah** (form alasan) → sengketa
- [ ] Daftar diperbarui langsung tanpa reload

**Backend (Go)**
- [ ] `POST /api/pledges/:id/verifikasi` (alur A) → HANDED_TO_BUYER + trigger catat `demand_transactions`
- [ ] `POST /api/orders/:id/verifikasi` (alur B) → HANDED_OVER + trigger catat `supply_transactions`
- [ ] `POST /api/(pledges|orders)/:id/lapor` → buat `disputes` (source_type sesuai alur)

## Modul E — Pencatatan Transaksi Offline & Komisi `[high]` → `features/04-transaksi-komisi.md` 🔴
> Menggantikan modul escrow. Tidak ada dana ditahan; sistem mencatat transaksi nyata + komisi.
**Frontend**
- [ ] Status pembayaran per pledge/order (UNPAID/PAID/SETTLED) di Pantau/Pesananku
- [ ] **Dashboard Koperasi** — pantau semua transaksi kedua alur + total komisi
- [ ] Ringkasan pembukuan koperasi (rekap komisi per periode)

**Backend (Go)**
- [ ] Saat verifikasi terima: buat `*_transactions` (gross, hitung `koperasi_fee` %, `net_amount`, method, status awal)
- [ ] `PUT /api/transactions/:id` (perbarui `payment_status` UNPAID → PAID → SETTLED, isi `paid_at`)
- [ ] `GET /api/transactions` (filter per peran/koperasi/alur) · `GET /api/koperasi/:id/pembukuan` (rekap komisi)
- [ ] Sengketa gotong royong: hanya transaksi pledge/order bermasalah yang tertahan status-nya; sisanya jalan
- [ ] Semua mutasi tercatat (audit) — komisi masuk pembukuan koperasi

## Modul F — Sistem Notifikasi & Real-time `[medium]`
- [ ] Setup **WebSocket** di backend Go (hub/broadcast per user & channel)
- [ ] Frontend: koneksi WS + update status pledge/order/transaksi langsung
- [ ] **Broadcast Kebutuhan**: notif ke warga saat ada demand baru — **tertarget via Matching grounded (Modul G)**
- [ ] **Update Status**: notif saat disanggupi/dipesan/diserahkan/dibayar
- [ ] **Peringatan Tenggat**: pengingat 24 jam sebelum batas 🟡
- [ ] Notifikasi in-app (bell) + fallback polling bila WS gagal

## Modul G — Matching & Broadcast Grounded (data KDMP) `[high]` 🟡
> Diferensiator: mencocokkan kebutuhan (Demand) ke **kapasitas produksi & stok nyata** dari Reference DB.

**Backend (Go) — read-only atas Reference DB**
- [~] `GET /api/match/kandidat?item=&provinsi=` **selesai** (kandidat dari stok gerai nyata, join inventaris→koperasi→wilayah). TODO: bungkus jadi `GET /api/demands/:id/kandidat` (baca demand dari App DB → derive item/wilayah)
- [ ] Tambah sinyal **komoditas unggulan desa** (`referensi_komoditas_desa`) sebagai sumber kandidat pra-pesan (bukan hanya stok gerai)
- [~] Skoring: v1 = besar stok. TODO: kecocokan komoditas × ketersediaan × kedekatan wilayah + normalisasi nama/satuan (data variatif)
- [ ] Broadcast tertarget: saat demand dibuat, tentukan set warga/koperasi relevan untuk notifikasi

**Frontend**
- [ ] Di Detail Permintaan: seksi "Desa/koperasi yang berpotensi memenuhi" (dari kandidat)
- [ ] Empty state matching: bila tak ada kandidat → CTA "Kabari saya kalau ada"

---

## Fase Lintas-Modul

### Responsive & UX 🔴
- [ ] Mobile-first, uji breakpoint (sm/md/lg/xl)
- [ ] Empty state jangan kosong: CTA "Kabari saya"
- [ ] Komponen reusable (card, badge status, progress bar, modal)
- [ ] Format Rupiah & tanggal lokal (id-ID), bahasa Indonesia konsisten
- [ ] SSR/meta untuk Jelajah Pasar & Etalase Listing (SEO sebelum login)

### Testing & Kualitas 🟡
- [ ] Go: unit test logika komisi, anti over-pledge/over-order, sengketa parsial, transisi status
- [ ] Go: integration test endpoint utama (auth, demands+pledges, listings+orders, verifikasi, transaksi)
- [ ] Frontend: test komponen & alur utama (Vitest)
- [ ] Lint/format: `golangci-lint` + `gofmt` (Go), ESLint + Prettier (Nuxt)

### Deployment 🟡
- [ ] `Dockerfile` untuk Nuxt (build SSR) & Go (multi-stage build)
- [ ] Docker Compose produksi + migrasi & seed otomatis saat start
- [ ] HTTPS/reverse proxy (Caddy/Nginx/Traefik), env produksi, backup DB
- [ ] CI sederhana: test + lint + build image saat push 🟢

---

## Skema Database (selaras PRD §6 + ERD `erd_marketplace_koperasi.mermaid`)
> **App DB (writable)** — semua PK `UUID`. Kolom `*_ref` = **soft reference** telusur ke Reference DB KDMP (di-seed, bukan FK lintas-DB).

| Tabel | Kolom inti | Soft-ref KDMP | Alur |
|---|---|---|---|
| `koperasi` | id, nama, desa, wilayah, kontak, status | `koperasi_ref` | hub |
| `users` | id, koperasi_id, name, phone, email, password_hash, role(BUYER/WARGA/ADMIN_KOPERASI), status, verification_status, verified_at | `anggota_ref` | — |
| `verifications` | id, user_id, nik, id_card_file, support_doc_file, status(PENDING/VERIFIED/REJECTED), reviewed_by, review_note, reviewed_at | — | KYC |
| `komoditas` | id, nama, kategori, satuan | `komoditas_ref` | acuan A+B |
| `demands` | id, buyer_id, koperasi_id, komoditas_id, item_name, satuan, total_qty, fulfilled_qty, target_price_per_item, deadline, demand_status(DRAFT/OPEN/PARTIAL/CLOSED/EXPIRED), **DP:** total_price, dp_percent, dp_amount, remaining_amount, dp_payment_method, dp_status(UNPAID/PAID/FORFEITED/REFUNDED), dp_paid_at | via komoditas | A |
| `demand_pledges` | id, demand_id, warga_id, qty_pledged, qty_delivered, price_per_item, pledge_status | — | A |
| `supply_listings` | id, koperasi_id, warga_id, komoditas_id, item_name, satuan, qty_available, qty_sold, price_per_item, listing_status | via komoditas | B |
| `orders` | id, listing_id, buyer_id, qty_ordered, price_per_item, total_amount, order_status | — | B |
| `demand_transactions` | id, demand_pledge_id, gross_amount, koperasi_fee, net_amount, payment_method, payment_status, paid_at | — | A |
| `supply_transactions` | id, order_id, gross_amount, koperasi_fee, net_amount, payment_method, payment_status, paid_at | — | B |
| `disputes` | id, demand_pledge_id, order_id, source_type(DEMAND/SUPPLY), reported_by, reason, dispute_status, resolution, resolved_at | — | A+B |

**Reference DB (read-only, dataset KDMP)** — 27 tabel, tidak dimigrasi (di-clone). Tabel kunci untuk seed & matching: `profil_koperasi`, `referensi_koperasi_wilayah`, `referensi_wilayah`, `anggota_koperasi`, `referensi_komoditas_desa`, `produk_koperasi`, `inventaris_produk`, `gerai_koperasi`. Detail → PRD §6b.

---

## Catatan penyesuaian dari versi TODO sebelumnya
- **Model pivot:** escrow + dompet → **transaksi offline yang dicatat + komisi koperasi** (sesuai realitas transaksi tunai desa).
- **Koperasi = hub/perantara** (bukan sekadar penjamin), menjalankan **dua alur**: Demand (pra-pesan) + Supply (titip-jual).
- **Peran:** BUYER / PRODUCER / KOPERASI → **BUYER / WARGA / ADMIN_KOPERASI**.
- **Grounding:** dari soft-ref runtime → **seed dari KDMP** ke tabel mandiri App DB (tetap simpan `*_ref` untuk telusur balik).
- **Skema:** ~6 tabel escrow → **11 tabel** dua-alur (ERD `erd_marketplace_koperasi.mermaid`). Migrasi lama `000001` perlu diganti.
- **Uang muka (DP)** pada demand (default 30%, `dp_status` UNPAID/PAID/FORFEITED/REFUNDED) + status `DRAFT` — dari ERD Herick; memberi komitmen pra-pesan tanpa escrow penuh.
- **Verifikasi identitas (KYC)** — tabel `verifications` + `users.verification_status`, ditinjau ADMIN_KOPERASI (dari ERD Herick).
- Stack tetap: **Nuxt.js + Go REST API + PostgreSQL (dua-DB) + Docker**.
