# TODO — Bursa Desa (Budes) Web App

> **Konsep inti:** Marketplace terbalik. Pembeli mengumumkan kebutuhan → produsen desa menyanggupi (penuh/sebagian, gotong royong) → KDMP (koperasi) sebagai infrastruktur & penjamin lewat **escrow** + **komisi otomatis**.
>
> **Sumber kebenaran:** `PRD.md` + `features/*.md`. Dokumen ini menurunkannya jadi checklist yang bisa dieksekusi.

## Stack (per PRD §5 & §7)
- **Frontend:** Nuxt.js (Vue 3) + Tailwind CSS / Nuxt UI · Pinia (state) · SSR untuk halaman publik (SEO, try-before-register)
- **Backend:** Go (Golang) — REST API (router: chi/gin/echo) · JWT auth · WebSocket untuk real-time
- **Database:** PostgreSQL (ACID — wajib untuk escrow/dompet)
- **Deployment:** Docker + Docker Compose (Nuxt + Go + Postgres)

## Legenda
- `[ ]` belum · `[~]` proses · `[x]` selesai
- 🔴 wajib MVP · 🟡 penting · 🟢 nice-to-have
- Prioritas modul mengikuti PRD §3: `[high]` / `[medium]`

## Prinsip pengerjaan (dari features)
1. **Try-before-register:** halaman publik (Jelajah Pasar, detail permintaan) bisa dilihat tanpa login. Login/daftar diminta *just-in-time* saat aksi konkret (Sanggupi / Pasang Kebutuhan). → **Aktivasi kunjungan pertama = lihat permintaan nyata + tombol Sanggupi.**
2. **Mock-first:** tiap modul dibangun UI dengan data tiruan dulu → lalu migrasi DB → lalu endpoint API → lalu sambungkan.
3. **Frontend & backend terpisah:** komunikasi via JSON REST + JWT; status real-time via WebSocket.

---

## Fase 0 — Fondasi Proyek & DevOps 🔴
- [ ] Struktur monorepo: `/frontend` (Nuxt), `/backend` (Go), `/deploy` (docker) + Git + `.gitignore`
- [ ] `docker-compose.yml`: service `web` (Nuxt), `api` (Go), `db` (PostgreSQL) + volume & network
- [ ] Env config bersama (`.env`): `DATABASE_URL`, `JWT_SECRET`, `API_BASE_URL`, dll
- [ ] Backend Go: skeleton (router, config loader, koneksi Postgres, health check `GET /health`)
- [ ] Backend Go: tooling migrasi (golang-migrate / goose) + layer repository-service-handler
- [ ] Frontend Nuxt: skeleton (Tailwind/Nuxt UI, Pinia, layout dasar, wrapper `$fetch` + interceptor JWT)
- [ ] Konvensi API: format response JSON, error, penamaan `snake_case`, UUID sebagai PK
- [ ] CORS + middleware dasar (logging, recover) di backend

## Fase 1 — Skema Database & Migrasi 🔴
> Basis PRD §6 (users, demands, fulfillments, transactions) + tabel yang dibutuhkan features. Lihat bagian **Skema Database** di bawah.
- [ ] Migrasi `users` (role: BUYER/PRODUCER/KOPERASI, password_hash)
- [ ] Migrasi `wallets` (saldo per user) — atau kolom `wallet_balance` di users (versi ringkas PRD)
- [ ] Migrasi `demands` (kebutuhan)
- [ ] Migrasi `fulfillments` (sanggupan; termasuk `qty_received` untuk terima sebagian)
- [ ] Migrasi `transactions`/`escrow` (dana ditahan, komisi, status)
- [ ] Migrasi `disputes` (sengketa dari Lapor Masalah)
- [ ] Seed data tiruan (users tiap peran, beberapa demands & fulfillments)

---

## Modul A — Akun & Profil `[medium]` → `features/05-akun-profil.md`
> Dikerjakan lebih awal karena jadi prasyarat aksi menulis (Pasang/Sanggupi), walau publik-browse tak butuh login.

**Frontend**
- [ ] Layout & routing modul akun + state auth (Pinia, mock)
- [ ] Halaman Daftar (nama, email, password, pilih peran) + validasi
- [ ] Halaman Login + error "Email atau kata sandi tidak sesuai"
- [ ] Tombol Logout (hapus sesi) di setiap halaman
- [ ] Tampilkan nama & peran di header setelah login
- [ ] Halaman **Riwayatku** (aktivitas + keuangan: ditahan/dicairkan/komisi)

**Backend (Go)**
- [ ] `POST /api/auth/register` (hash password bcrypt) 🔴
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
- [ ] **Broadcast Kebutuhan**: notifikasi ke produsen saat ada kebutuhan baru
- [ ] **Update Status Pesanan**: notif saat disanggupi / dikirim / dana cair
- [ ] **Peringatan Tenggat**: pengingat 24 jam sebelum batas kirim 🟡
- [ ] Notifikasi in-app (bell) + fallback polling bila WS gagal

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

## Skema Database (selaras PRD §6 + kebutuhan features)
> PRD §6 menyebut 4 tabel inti. Features 03 & 04 menambah kebutuhan (kiriman, wallets, escrow terpisah, sengketa). Rekomendasi: pertahankan tabel eksplisit di bawah (lebih auditable). Semua PK `UUID`.

| Tabel | Kolom inti | Catatan |
|---|---|---|
| `users` | id, name, email, password_hash, role(BUYER/PRODUCER/KOPERASI) | PRD §6 |
| `wallets` | id, user_id, balance | features 04; alt: `wallet_balance` di `users` (versi ringkas PRD) |
| `demands` | id, buyer_id, item_name, total_qty, fulfilled_qty, price_per_item, deadline, status(OPEN/PARTIAL/CLOSED) | PRD §6 |
| `fulfillments` | id, demand_id, producer_id, qty_pledged, qty_received, status(PENDING/SHIPPED/RECEIVED/DISPUTED) | PRD §6 + `qty_received` untuk terima sebagian |
| `transactions` | id, fulfillment_id, amount, koperasi_fee, net_amount, status(ON_HOLD/RELEASED/REFUNDED) | PRD §6 = escrow ledger |
| `disputes` | id, fulfillment_id, reported_by, reason, status, resolution | dari Lapor Masalah (features 03) |

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
