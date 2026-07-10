# TODO — Bursa Desa (Budes) Web App

> **Konsep inti:** Marketplace terbalik. Pembeli mengumumkan kebutuhan → produsen desa menyanggupi (penuh/sebagian, gotong royong) → KDMP (koperasi) sebagai infrastruktur & penjamin lewat **escrow** + **komisi otomatis**.
>
> **Sumber kebenaran:** `PRD.md` + `features/*.md`. Dokumen ini menurunkannya jadi checklist yang bisa dieksekusi.

## Stack (per PRD §5 & §7)
- **Frontend:** Nuxt.js (Vue 3) + Tailwind CSS / Nuxt UI · Pinia (state) · SSR untuk halaman publik (SEO, try-before-register)
- **Backend:** Go (Golang) — REST API (router: chi/gin/echo) · JWT auth · WebSocket untuk real-time · **2 connection pool** (App DB read-write + Reference DB read-only)
- **Database:** PostgreSQL — **dua instance** (arsitektur dua-DB, PRD §5):
  - **App DB** (writable) — transaksional: users, demands, fulfillments, transactions, disputes, wallets (ACID — wajib untuk escrow/dompet)
  - **Reference DB** (read-only) — cermin lokal dataset KDMP (27 tabel; sumber sisi-suplai & matching). Sudah di-clone ke `budes_ref_db` (Docker, `localhost:5433`), lihat `deploy/`
- **Deployment:** Docker + Docker Compose (Nuxt + Go + App DB + Reference DB)

## Legenda
- `[ ]` belum · `[~]` proses · `[x]` selesai
- 🔴 wajib MVP · 🟡 penting · 🟢 nice-to-have
- Prioritas modul mengikuti PRD §3: `[high]` / `[medium]`

## Prinsip pengerjaan (dari features)
1. **Try-before-register:** halaman publik (Jelajah Pasar, detail permintaan) bisa dilihat tanpa login. Login/daftar diminta *just-in-time* saat aksi konkret (Sanggupi / Pasang Kebutuhan). → **Aktivasi kunjungan pertama = lihat permintaan nyata + tombol Sanggupi.**
2. **Mock-first:** tiap modul dibangun UI dengan data tiruan dulu → lalu migrasi DB → lalu endpoint API → lalu sambungkan.
3. **Frontend & backend terpisah:** komunikasi via JSON REST + JWT; status real-time via WebSocket.
4. **Grounded pada data nyata:** sisi-suplai (produsen, kapasitas produksi desa, stok gerai, koperasi penjamin) dibaca dari **Reference DB KDMP (read-only)**, bukan dikarang. Seed & matching memakai data nyata; keterkaitan disimpan sebagai *soft ref* (`koperasi_ref`, `anggota_ref`, `komoditas_ref`, `kode_wilayah`) — bukan FK lintas-DB.

---

## Fase 0 — Fondasi Proyek & DevOps 🔴
- [ ] Struktur monorepo: `/frontend` (Nuxt), `/backend` (Go), `/deploy` (docker) + Git + `.gitignore`
- [x] **Reference DB KDMP di-clone ke lokal** (Docker `budes_ref_db`, `localhost:5433`, 27 tabel / 547.869 baris, read-only) — `deploy/docker-compose.yml` + `deploy/dumps/`
- [ ] `docker-compose.yml`: service `web` (Nuxt), `api` (Go), `app-db` (App DB writable), `ref-db` (Reference DB read-only) + volume & network
- [ ] Env config bersama (`.env`): **`APP_DATABASE_URL`** (writable) + **`REF_DATABASE_URL`** (read-only), `JWT_SECRET`, `API_BASE_URL`, dll
- [x] Backend Go: skeleton (router `net/http`, config loader env, **2 koneksi Postgres: appDB rw + refDB ro** via pgxpool, health check `GET /health` cek keduanya) — `backend/`
- [x] Backend Go: pool refDB dibatasi read-only (`SET default_transaction_read_only=on` di AfterConnect) — cegah tulis tak sengaja
- [ ] Backend Go: tooling migrasi (golang-migrate / goose) **hanya untuk App DB** (Reference DB tidak dimigrasi — di-seed dari dump) + layer repository-service-handler
- [ ] Frontend Nuxt: skeleton (Tailwind/Nuxt UI, Pinia, layout dasar, wrapper `$fetch` + interceptor JWT)
- [ ] Konvensi API: format response JSON, error, penamaan `snake_case`, UUID sebagai PK
- [ ] CORS + middleware dasar (logging, recover) di backend

## Fase 1 — Skema Database & Migrasi 🔴
> Migrasi **hanya untuk App DB** (writable). Reference DB KDMP tidak dimigrasi — sudah di-clone (Fase 0). Basis PRD §6 + soft-ref ke KDMP (§6b). Lihat bagian **Skema Database** di bawah.

**App DB (writable) — migrasi**
- [ ] Migrasi `users` (role: BUYER/PRODUCER/KOPERASI, password_hash, **+ `koperasi_ref`/`anggota_ref` nullable soft-ref**)
- [ ] Migrasi `wallets` (saldo per user) — atau kolom `wallet_balance` di users (versi ringkas PRD)
- [ ] Migrasi `demands` (kebutuhan; **+ `deadline`, `kode_wilayah`, `komoditas_ref` soft-ref**)
- [ ] Migrasi `fulfillments` (sanggupan; `qty_received` untuk terima sebagian; **+ `koperasi_ref`, `produk_sample_id` soft-ref**)
- [ ] Migrasi `transactions`/`escrow` (dana ditahan, komisi, `net_amount`, status)
- [ ] Migrasi `disputes` (sengketa dari Lapor Masalah)

**Reference DB (read-only) — layer baca & seed**
- [x] Reference read-layer (repository read-only) — `backend/internal/reference/`; query koperasi, anggota, inventaris/produk × wilayah (join tervalidasi). Komoditas & tabel lain menyusul sesuai kebutuhan.
- [ ] Validasi soft-ref: cek `koperasi_ref`/`anggota_ref`/`komoditas_ref`/`kode_wilayah` benar-benar ada di Reference DB sebelum tulis ke App DB (+ cache resolve)
- [ ] **Seed diturunkan dari data nyata** (bukan tiruan): produsen contoh ditaut ke `anggota_ref`/`koperasi_ref` riil; demands contoh memakai komoditas/produk & wilayah nyata; user `KOPERASI` dipetakan ke `koperasi_ref` riil
- [ ] Script re-sync Reference DB: `pg_dump` remote → `pg_restore` ke `budes_ref_db` (dump ada di `deploy/dumps/`)

---

## Modul A — Akun & Profil `[medium]` → `features/05-akun-profil.md`
> Dikerjakan lebih awal karena jadi prasyarat aksi menulis (Pasang/Sanggupi), walau publik-browse tak butuh login.

**Frontend**
- [ ] Layout & routing modul akun + state auth (Pinia, mock)
- [ ] Halaman Daftar (nama, email, password, pilih peran) + validasi
- [ ] Saat peran PRODUCER/KOPERASI: opsi **tautkan identitas KDMP riil** — cari & pilih koperasi (`koperasi_ref`) / anggota (`anggota_ref`) dari Reference DB 🟡
- [ ] Halaman Login + error "Email atau kata sandi tidak sesuai"
- [ ] Tombol Logout (hapus sesi) di setiap halaman
- [ ] Tampilkan nama & peran di header setelah login
- [ ] Halaman **Riwayatku** (aktivitas + keuangan: ditahan/dicairkan/komisi)

**Backend (Go)**
- [ ] `POST /api/auth/register` (hash password bcrypt; **validasi `koperasi_ref`/`anggota_ref` ke Reference DB bila diisi**) 🔴
- [x] `GET /api/ref/koperasi?q=` & `GET /api/ref/anggota?q=` (lookup read-only ke Reference DB untuk penautan identitas) 🟡
- [ ] `POST /api/auth/login` → terbitkan JWT 🔴
- [ ] Middleware autentikasi JWT (lindungi endpoint) 🔴
- [ ] `POST /api/auth/logout` (invalidate sesi/token) 🟡
- [ ] `GET /api/me` (profil pengguna login)
- [ ] `GET /api/me/riwayat` (audit aktivitas + mutasi dana)

## Modul B — Pasang Kebutuhan `[high]` → `features/01-pasang-kebutuhan.md` 🔴
**Frontend**
- [ ] Halaman **Jelajah Pasar** (publik, tanpa login) — daftar permintaan + indikator status/progress
- [ ] Halaman **Buat Postingan Baru** (nama barang, jumlah, harga/item, tenggat) + validasi lokal
- [ ] Halaman **Detail Permintaan** — progress bar % tersanggupi + daftar penyanggup
- [ ] Tombol **Sanggupi** di detail (just-in-time auth kalau belum login)

**Backend (Go)**
- [ ] `POST /api/kebutuhan` (buat postingan; butuh auth pembeli)
- [ ] `GET /api/kebutuhan` (daftar publik)
- [ ] `GET /api/kebutuhan/:id` (detail + agregasi jumlah tersanggupi)
- [ ] Logika status demand `OPEN → PARTIAL → CLOSED` otomatis

## Modul C — Sanggupi Pesanan `[high]` → `features/02-sanggupi-pesanan.md` 🔴
**Frontend**
- [ ] Form **Ajukan Sanggupan** di detail permintaan (validasi ≤ sisa kebutuhan) + notifikasi sukses/gagal
- [ ] Halaman **Pantau Sanggupanku** (status: Menunggu/Dikirim/Diterima/Bermasalah)
- [ ] Halaman detail sanggupan
- [ ] Update status real-time tanpa reload

**Backend (Go)**
- [ ] `POST /api/sanggupan` (ajukan; validasi jumlah, update `fulfilled_qty`)
- [ ] `GET /api/sanggupan` (daftar milik produsen login)
- [ ] `GET /api/sanggupan/:id` (detail)
- [ ] `PUT /api/sanggupan/:id` (perbarui status, mis. → SHIPPED)
- [ ] Logika bisnis: cegah over-pledge + rekalkulasi sisa kebutuhan

## Modul D — Konfirmasi Terima `[high]` → `features/03-konfirmasi-terima.md` 🔴
**Frontend**
- [ ] Halaman **Daftar Kiriman** (hanya status "Dikirim", menunggu cek)
- [ ] Aksi **Verifikasi Terima** — penuh & sebagian (isi jumlah diterima, mis. 80/100)
- [ ] Aksi **Lapor Masalah** (form alasan) → status "Disengketakan"
- [ ] Tampilan kiriman selesai (dikonfirmasi/dilaporkan) terpisah
- [ ] Daftar diperbarui langsung tanpa reload

**Backend (Go)**
- [ ] `GET /api/kiriman` (daftar menunggu konfirmasi untuk pembeli)
- [ ] `POST /api/kiriman/:id/verifikasi` → set RECEIVED + **trigger pencairan escrow** (penuh/sebagian)
- [ ] `POST /api/kiriman/:id/lapor` → set DISPUTED + tahan dana + notifikasi produsen & koperasi

## Modul E — Escrow Dana `[high]` → `features/04-escrow-dana.md` 🔴
**Frontend**
- [ ] Ringkasan escrow di detail permintaan (total dana ditahan + status)
- [ ] Status dana per sanggupan di Pantau Sanggupan (Ditahan/Dicairkan/Disengketakan)
- [ ] **Dashboard Koperasi** — pantau semua transaksi escrow
- [ ] Halaman **Dompet** produsen (saldo + riwayat transaksi: kotor, komisi, bersih)

**Backend (Go)**
- [ ] Saat Pasang Kebutuhan/Order: **tahan dana** pembeli (`ON_HOLD`) — simulasi deposit untuk MVP
- [ ] `GET /api/escrow/status` (untuk pembeli/produsen/koperasi)
- [ ] Pencairan otomatis saat verifikasi terima: potong komisi → `net` ke dompet produsen, `fee` ke koperasi (`RELEASED`)
- [ ] Sengketa gotong royong: hanya dana sanggupan bermasalah yang ditahan/di-refund; sisanya tetap cair
- [ ] `GET /api/dompet` (saldo + riwayat) · `POST /api/dompet/tarik` (tarik ke rekening) 🟡
- [ ] Semua mutasi dana tercatat (audit) + pemasukan komisi masuk pembukuan koperasi

## Modul F — Sistem Notifikasi & Real-time `[medium]`
- [ ] Setup **WebSocket** di backend Go (hub/broadcast per user & channel)
- [ ] Frontend: koneksi WS + update status sanggupan/kiriman/dana secara langsung
- [ ] **Broadcast Kebutuhan**: notifikasi ke produsen saat ada kebutuhan baru — **tertarget via Matching grounded (Modul G)**, bukan broadcast buta
- [ ] **Update Status Pesanan**: notif saat disanggupi / dikirim / dana cair
- [ ] **Peringatan Tenggat**: pengingat 24 jam sebelum batas kirim 🟡
- [ ] Notifikasi in-app (bell) + fallback polling bila WS gagal

## Modul G — Matching & Broadcast Grounded (data KDMP) `[high]` 🟡
> Diferensiator utama: mencocokkan kebutuhan pembeli ke **kapasitas produksi & stok nyata** dari Reference DB. Menghidupkan "Broadcast Kebutuhan" (PRD §3) jadi cerdas & berbasis data.

**Backend (Go) — read-only atas Reference DB**
- [~] `GET /api/match/kandidat?item=&provinsi=` **selesai** (kandidat dari stok gerai nyata, join inventaris→koperasi→wilayah). TODO: bungkus jadi `GET /api/kebutuhan/:id/kandidat` (baca demand dari App DB → derive item/wilayah) + tambah sinyal komoditas desa
- [~] Skoring kandidat: v1 = besar stok. TODO: kecocokan komoditas/produk × ketersediaan stok/volume × kedekatan wilayah + normalisasi nama (data variatif)
- [ ] Broadcast tertarget: saat demand dibuat, tentukan set produsen/koperasi relevan (bukan semua) untuk notifikasi
- [ ] (opsional) Trust-score koperasi penjamin dari sinyal Reference DB (status registrasi, RAT, dll) 🟢

**Frontend**
- [ ] Di Detail Permintaan: seksi "Desa/koperasi yang berpotensi memenuhi" (dari kandidat) — memperkuat kesan pasokan terjamin
- [ ] Empty state matching: bila tak ada kandidat cocok → CTA "Kabari saya kalau ada"

---

## Fase Lintas-Modul

### Responsive & UX 🔴
- [ ] Mobile-first, uji breakpoint (sm/md/lg/xl)
- [ ] Empty state jangan kosong: kalau belum ada permintaan cocok → CTA "Kabari saya"
- [ ] Komponen reusable (card, badge status, progress bar, modal)
- [ ] Format Rupiah & tanggal lokal (id-ID), bahasa Indonesia konsisten
- [ ] SSR/meta untuk Jelajah Pasar (SEO sebelum login)

### Testing & Kualitas 🟡
- [ ] Go: unit test logika escrow, komisi, anti over-pledge, sengketa parsial
- [ ] Go: integration test endpoint utama (auth, kebutuhan, sanggupan, verifikasi)
- [ ] Frontend: test komponen & alur utama (Vitest)
- [ ] Lint/format: `golangci-lint` + `gofmt` (Go), ESLint + Prettier (Nuxt)

### Deployment 🟡
- [ ] `Dockerfile` untuk Nuxt (build SSR) & Go (multi-stage build)
- [ ] Docker Compose produksi + migrasi otomatis saat start
- [ ] HTTPS/reverse proxy (Caddy/Nginx/Traefik), env produksi, backup DB
- [ ] CI sederhana: test + lint + build image saat push 🟢

---

## Skema Database (selaras PRD §6 + §6b + kebutuhan features)
> **App DB (writable)** — tabel di bawah. Semua PK `UUID`. Kolom `*_ref`/`*_sample_id` = **soft reference** ke Reference DB KDMP (bukan FK lintas-DB; divalidasi & di-resolve di aplikasi).

| Tabel | Kolom inti | Soft-ref ke KDMP | Catatan |
|---|---|---|---|
| `users` | id, name, email, password_hash, role(BUYER/PRODUCER/KOPERASI), wallet_balance | `koperasi_ref`, `anggota_ref` | PRD §6 |
| `wallets` | id, user_id, balance | — | features 04; alt: `wallet_balance` di `users` |
| `demands` | id, buyer_id, item_name, total_qty, fulfilled_qty, price_per_item, deadline, status(OPEN/PARTIAL/CLOSED) | `kode_wilayah`, `komoditas_ref` | PRD §6 |
| `fulfillments` | id, demand_id, producer_id, qty_pledged, qty_received, status(PENDING/SHIPPED/RECEIVED/DISPUTED) | `koperasi_ref`, `produk_sample_id` | + `qty_received` untuk terima sebagian |
| `transactions` | id, fulfillment_id, amount, koperasi_fee, net_amount, status(ON_HOLD/RELEASED/REFUNDED) | — | PRD §6 = escrow ledger |
| `disputes` | id, fulfillment_id, reported_by, reason, status, resolution | — | dari Lapor Masalah (features 03) |

**Reference DB (read-only, dataset KDMP)** — 27 tabel, tidak dimigrasi (di-clone). Tabel kunci yang dipakai: `referensi_koperasi_wilayah`, `profil_koperasi`, `akun_bank_koperasi`, `anggota_koperasi`, `referensi_komoditas_desa`, `produk_koperasi`, `inventaris_produk`, `gerai_koperasi`, `referensi_wilayah`. Detail pemetaan → PRD §6b.

**Status "kiriman"** (features 03): tidak butuh tabel terpisah — cukup query `fulfillments` dengan `status = SHIPPED`. Buat tabel `shipments` hanya bila perlu data logistik (resi, waktu kirim) 🟢.

---

## ⚠️ Perlu keputusan: `erd_marketplace_permintaan.mermaid` sudah usang
File ERD lama (14 entitas, Bahasa Indonesia, desain Laravel) **tidak lagi cocok** dengan PRD terbaru (skema English/UUID, lebih ramping, stack Go+Nuxt). Opsi:
1. **Ganti** ERD lama dengan diagram baru sesuai tabel di atas (rekomendasi).
2. **Arsipkan** (`_archive/`) sebagai referensi model kaya di masa depan.

## Catatan penyesuaian dari versi TODO sebelumnya
- Stack diganti total: **Laravel 12 + Livewire → Nuxt.js + Go REST API + PostgreSQL + Docker**.
- Auth: Breeze/Fortify (sesi) → **JWT + middleware Go**.
- Real-time: Livewire `wire:poll` → **WebSocket (Go)**.
- Skema: 14 entitas detail → **~6 tabel** selaras PRD (auditability tetap dijaga lewat `transactions` + `disputes`).
- Struktur pengerjaan kini **per-modul (mock → DB → API)** mengikuti `features/`.
