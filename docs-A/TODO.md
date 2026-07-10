# TODO — Bursa Desa (Budes) Web App

> Menurunkan `docs-A/mission.md` + `docs-A/PRD.md` menjadi checklist yang bisa dieksekusi.
>
> **Konsep inti:** KDMP sebagai **hub aktif** yang menjual **kepastian pasokan B2B**. **Dua alur** — **A) Demand (fokus utama):** pembeli usaha pasang kebutuhan → DP 30% + biaya layanan 8% ditarik di depan via payment gateway → warga menyanggupi (gotong royong) → setor → serah (lunasi sisa tunai/transfer); **B) Supply (aktif):** warga titip surplus → sistem mendorong ke pembeli cocok via Matching → pembeli pesan. **Matching = inti.** Warga punya **skor reputasi**; gagal setor → penalti reputasi + gotong-royong ulang. **Janji PROSES, bukan STOK.**

## Stack
- **Frontend:** Nuxt.js (Vue 3) + Tailwind / Nuxt UI · Pinia · SSR halaman publik B2B
- **Backend:** Go — REST API · JWT · WebSocket · 2 connection pool (App DB rw + Reference DB ro) · adapter payment gateway · Matching (inti) · skor reputasi
- **Payment gateway:** integrasi pihak ketiga (tahan DP + tarik biaya layanan 8% di depan) — provider `[TBD — riset]`
- **Database:** PostgreSQL dua instance (App DB writable + Reference DB read-only `budes_ref_db` @ `localhost:5433`)
- **Deployment:** Docker + Docker Compose

## Legenda
- `[ ]` belum · `[~]` proses · `[x]` selesai
- 🔴 wajib MVP · 🟡 penting · 🟢 nice-to-have · `[high]`/`[medium]` mengikuti prioritas modul di PRD §3

## Prinsip pengerjaan
1. **Target B2B, bukan eceran:** pembeli daftar atas nama **usaha/toko**. UI untuk **koordinator belanja usaha**.
2. **Alur B aktif:** surplus **didorong** ke pembeli via Matching; empty state = "sedang ditawarkan", bukan pasif.
3. **Janji PROSES, bukan STOK:** data KDMP = penyaran arah/targeting (ditandai "potensi"), bukan penjamin. Kegagalan → gotong-royong ulang + penalti reputasi.
4. **Biaya enforceable:** DP + biaya layanan 8% ditarik di depan via gateway; sisa barang boleh tunai/transfer.
5. **Try-before-register** & **mock-first** (UI dengan data tiruan → migrasi DB → endpoint API → sambungkan).
6. **Grounded pada KDMP:** koperasi/komoditas/warga di-seed dari dataset KDMP (read-only) + simpan `*_ref`. **Matching sebagai inti.**

---

## Fase 0 — Fondasi & DevOps 🔴
- [ ] Struktur monorepo: `/frontend` (Nuxt), `/backend` (Go), `/deploy` (docker) + Git + `.gitignore`
- [x] Reference DB KDMP di-clone ke lokal (Docker `budes_ref_db`, `localhost:5433`, 27 tabel, read-only)
- [ ] `docker-compose.yml`: service `web` (Nuxt), `api` (Go), `app-db`, `ref-db` + volume & network
- [ ] Env config bersama (`.env`): `APP_DATABASE_URL`, `REF_DATABASE_URL`, `JWT_SECRET`, `API_BASE_URL`, `PAYMENT_GATEWAY_*`
- [x] Backend Go: skeleton (router `net/http`, config loader env, 2 koneksi Postgres appDB rw + refDB ro via pgxpool, health check)
- [x] Backend Go: pool refDB dibatasi read-only (`SET default_transaction_read_only=on`)
- [ ] Backend Go: tooling migrasi (golang-migrate/goose) **hanya App DB** + layer repository-service-handler per modul
- [ ] Frontend Nuxt: skeleton (Tailwind/Nuxt UI, Pinia, layout dasar, wrapper `$fetch` + interceptor JWT)
- [ ] Konvensi API: format response JSON, error, penamaan `snake_case`, UUID sebagai PK
- [x] CORS + middleware dasar (logging, recover) di backend
- [ ] Riset & pilih **provider payment gateway** (Midtrans/Xendit/dll) + biaya per transaksi `[TBD — riset]`

## Fase 1 — Skema Database & Migrasi 🔴
> Migrasi **hanya App DB** (writable). Reference DB KDMP di-clone (Fase 0), tidak dimigrasi. Basis PRD §6.
- [ ] `koperasi` (id, nama, desa, wilayah, kontak, status, `koperasi_ref`)
- [ ] `users` (koperasi_id nullable, name, `business_name`, phone, email, password_hash, role BUYER/WARGA/ADMIN_KOPERASI, status, `verification_status` UNVERIFIED/PENDING/VERIFIED/REJECTED, `verified_at`, `anggota_ref`, `reputation_score`, `fulfilled_count`, `failed_count`)
- [ ] `verifications` (user_id, nik, id_card_file, support_doc_file, status PENDING/VERIFIED/REJECTED, reviewed_by, review_note, reviewed_at)
- [ ] `komoditas` (id, nama, kategori, satuan, `komoditas_ref`)
- [ ] `demands` (buyer_id, koperasi_id, komoditas_id, item_name, satuan, total_qty, fulfilled_qty, target_price_per_item, deadline, `demand_status` DRAFT/OPEN/PARTIAL/CLOSED/EXPIRED, total_price, `service_fee_percent` def 8, `service_fee_amount`, dp_percent def 30, dp_amount, remaining_amount, `gateway_ref`, `dp_status` UNPAID/HELD/RELEASED/FORFEITED/REFUNDED, dp_paid_at)
- [ ] `demand_pledges` (demand_id, warga_id, qty_pledged, qty_delivered, price_per_item, `pledge_status` PENDING/ACCEPTED/DELIVERED_TO_KOPERASI/HANDED_TO_BUYER/FAILED/CANCELLED)
- [ ] `supply_listings` (koperasi_id, warga_id, komoditas_id, item_name, satuan, qty_available, qty_sold, price_per_item, `listing_status` DRAFT/POSTED/SOLD_OUT/CLOSED)
- [ ] `orders` (listing_id, buyer_id, qty_ordered, price_per_item snapshot, total_amount, `service_fee_amount`, `gateway_ref`, `order_status` PENDING/CONFIRMED/HANDED_OVER/CANCELLED)
- [ ] `demand_transactions` (demand_pledge_id, gross_amount, `service_fee`, koperasi_fee, net_amount, payment_method CASH/TRANSFER, payment_status UNPAID/PAID/SETTLED, paid_at)
- [ ] `supply_transactions` (order_id, gross_amount, `service_fee`, koperasi_fee, net_amount, payment_method, payment_status, paid_at)
- [ ] `disputes` (demand_pledge_id nullable, order_id nullable, source_type DEMAND/SUPPLY, reported_by, reason, dispute_status OPEN/REVIEW/RESOLVED, resolution, resolved_at)
- [ ] Kolom audit `user_input`/`tanggal_input`/`user_update`/`tanggal_update` + index status/FK utama

**Reference DB (read-only) — layer baca & seed**
- [x] Reference read-layer (repository read-only); query koperasi, anggota, inventaris/produk × wilayah (join tervalidasi)
- [ ] Seed App DB dari KDMP: `koperasi` ← `profil_koperasi`×`referensi_koperasi_wilayah`×`referensi_wilayah`; `komoditas` ← `referensi_komoditas_desa`; `users(WARGA)` ← `anggota_koperasi` (simpan `*_ref`)
- [ ] Validasi soft-ref: cek `koperasi_ref`/`anggota_ref`/`komoditas_ref` benar-benar ada di Reference DB saat penautan (+ cache resolve)
- [ ] Script re-sync Reference DB: `pg_dump` remote → `pg_restore` ke `budes_ref_db`

---

## Modul A — Akun, Profil & Reputasi `[medium]`
> Prasyarat aksi menulis (Pasang/Sanggupi/Pesan/Titip), walau publik-browse tak butuh login.

**Frontend**
- [ ] Layout & routing modul akun + state auth (Pinia)
- [ ] Halaman Daftar: BUYER **atas nama usaha/toko (`business_name`)** / WARGA / ADMIN_KOPERASI + validasi
- [ ] Peran WARGA/ADMIN: tautkan identitas KDMP riil — cari & pilih koperasi (`koperasi_ref`) / anggota (`anggota_ref`) 🟡
- [ ] Halaman Login + error · Logout
- [ ] Header: nama & peran + badge `verification_status` + badge `reputation_score` (warga)
- [ ] Halaman Verifikasi Identitas (KYC): unggah NIK + foto KTP + dokumen pendukung 🟡
- [ ] Halaman Riwayatku (aktivitas + rekap transaksi & biaya layanan)
- [ ] (Admin) Halaman Tinjau Verifikasi — daftar PENDING, aksi VERIFIED/REJECTED + catatan 🟡

**Backend (Go)**
- [ ] `POST /api/auth/register` (hash bcrypt; validasi `koperasi_ref`/`anggota_ref`; `business_name` wajib bila BUYER) 🔴
- [x] `GET /api/ref/koperasi?q=` & `GET /api/ref/anggota?q=` (lookup read-only KDMP) 🟡
- [ ] `POST /api/auth/login` → terbitkan JWT 🔴
- [ ] Middleware autentikasi JWT + otorisasi peran 🔴
- [ ] `POST /api/auth/logout` 🟡 · `GET /api/me` · `GET /api/me/riwayat`
- [ ] `POST /api/verifikasi` (ajukan KYC) · `GET /api/verifikasi` (admin) · `PUT /api/verifikasi/:id` (VERIFIED/REJECTED) 🟡
- [ ] **Skor reputasi**: hitung dari `fulfilled_count`/`failed_count`; expose di profil & saat pledge; update saat serah-terima/gagal 🟡

## Modul B — Alur A: Pasang Kebutuhan (Demand) `[high]` 🔴
**Frontend**
- [ ] Halaman Jelajah Pasar (publik, B2B) — daftar demand (`OPEN`/DP terbayar) + indikator progress
- [ ] Halaman Buat Postingan Baru + tampil hitungan `total_price` / `service_fee` (8%) / `dp_amount` (30%) / `remaining_amount`
- [ ] Langkah **Bayar via Payment Gateway** — DP + biaya layanan; tandai `dp_status = HELD` → demand `DRAFT → OPEN`
- [ ] Halaman Detail Permintaan — status DP + progress bar % tersanggupi + daftar penyanggup + reputasi warga
- [ ] Tombol Sanggupi (just-in-time auth)

**Backend (Go)**
- [ ] `POST /api/demands` (auth buyer; hitung total_price/service_fee/dp_amount/remaining; mulai `DRAFT`)
- [ ] `POST /api/demands/:id/pay` (buat transaksi gateway DP + biaya layanan; webhook → `dp_status = HELD`) → demand `OPEN`
- [ ] `GET /api/demands` (publik; default hanya `OPEN`+) · `GET /api/demands/:id` (detail + agregasi pledge)
- [ ] `POST /api/demands/:id/pledges` (warga menyanggupi; validasi ≤ sisa; update `fulfilled_qty`)
- [ ] `GET /api/pledges` (milik warga) · `PUT /api/pledges/:id` (ACCEPTED → DELIVERED_TO_KOPERASI → HANDED_TO_BUYER)
- [ ] Logika status demand `DRAFT → OPEN → PARTIAL → CLOSED/EXPIRED` + anti over-pledge
- [ ] Logika DP: `FORFEITED` (buyer batal) · `REFUNDED` (demand gagal) · `RELEASED` (via gateway)
- [ ] Pemulihan kegagalan: pledge `FAILED` → turunkan reputasi warga + picu gotong-royong ulang (re-open sisa qty)

## Modul C — Alur B: Titip-Jual (Supply, aktif) `[high]` 🔴
**Frontend**
- [ ] Halaman Etalase Listing (publik) — komoditas surplus siap jual
- [ ] Halaman Titipkan Komoditas (warga/koperasi buat listing)
- [ ] Halaman Detail Listing + tombol Pesan (just-in-time auth)
- [ ] Halaman Pesananku (buyer) & Titipanku (warga) — empty state "sedang ditawarkan ke pembeli", bukan pasif

**Backend (Go)**
- [ ] `POST /api/listings` · `GET /api/listings` (publik) · `GET /api/listings/:id`
- [ ] `PUT /api/listings/:id` (status DRAFT/POSTED/SOLD_OUT/CLOSED, rekalkulasi `qty_available`/`qty_sold`)
- [ ] `POST /api/orders` (buyer pesan; snapshot harga, hitung total + biaya layanan 8% via gateway) · `GET /api/orders` · `GET /api/orders/:id`
- [ ] `PUT /api/orders/:id` (PENDING → CONFIRMED → HANDED_OVER/CANCELLED) + anti over-order
- [ ] Dorongan aktif: saat listing POSTED, panggil Matching untuk menawarkan ke pembeli cocok (Modul G)

## Modul D — Konfirmasi Serah-Terima `[high]` 🔴
**Frontend**
- [ ] Halaman Daftar Serah-Terima (pledge DELIVERED_TO_KOPERASI / order CONFIRMED yang menunggu)
- [ ] Aksi Verifikasi Terima — penuh & sebagian
- [ ] Aksi Lapor Masalah (form alasan) → sengketa
- [ ] Daftar diperbarui langsung tanpa reload

**Backend (Go)**
- [ ] `POST /api/pledges/:id/verifikasi` (alur A) → HANDED_TO_BUYER + trigger catat `demand_transactions` + naikkan reputasi
- [ ] `POST /api/orders/:id/verifikasi` (alur B) → HANDED_OVER + trigger catat `supply_transactions`
- [ ] `POST /api/(pledges|orders)/:id/lapor` → buat `disputes` (source_type sesuai alur)

## Modul E — Pencatatan Transaksi & Biaya Layanan `[high]` 🔴
> Model enforceable: DP + biaya layanan ditarik di depan via gateway; sisa barang offline.
**Frontend**
- [ ] Status pembayaran per pledge/order (UNPAID/PAID/SETTLED) di Pantau/Pesananku
- [ ] Dashboard Koperasi — pantau semua transaksi kedua alur + total biaya layanan
- [ ] Ringkasan pembukuan koperasi (rekap biaya layanan per periode)

**Backend (Go)**
- [ ] Saat verifikasi terima: buat `*_transactions` (gross, `service_fee` 8%, `koperasi_fee`, `net_amount`, method, status awal)
- [ ] `PUT /api/transactions/:id` (perbarui `payment_status` UNPAID → PAID → SETTLED, isi `paid_at`) · pelepasan DP dari gateway
- [ ] `GET /api/transactions` (filter per peran/koperasi/alur) · `GET /api/koperasi/:id/pembukuan` (rekap)
- [ ] Sengketa gotong royong: hanya transaksi pledge/order bermasalah yang tertahan; sisanya jalan
- [ ] Semua mutasi tercatat (audit)

## Modul F — Sistem Notifikasi & Real-time `[medium]`
- [ ] Setup WebSocket di backend Go (hub/broadcast per user & channel)
- [ ] Frontend: koneksi WS + update status pledge/order/transaksi langsung
- [ ] Broadcast Kebutuhan: notif ke warga saat ada demand baru — tertarget via Matching (Modul G)
- [ ] Update Status: notif saat disanggupi/dipesan/diserahkan/dibayar
- [ ] Peringatan Tenggat: pengingat 24 jam sebelum batas 🟡
- [ ] Notifikasi in-app (bell) + fallback polling bila WS gagal

## Modul G — Matching & Peta Pasokan Grounded (data KDMP) `[high]` 🔴 (inti)
> Mencocokkan kebutuhan (Demand) & surplus (Supply) ke kapasitas produksi/stok dari Reference DB. Output = kandidat/arah, **ditandai "potensi", bukan janji stok**.

**Backend (Go) — read-only atas Reference DB**
- [~] `GET /api/match/kandidat?item=&provinsi=` (kandidat dari stok gerai nyata, join inventaris→koperasi→wilayah). TODO: bungkus jadi `GET /api/demands/:id/kandidat` (baca demand → derive item/wilayah)
- [ ] Tambah sinyal komoditas unggulan desa (`referensi_komoditas_desa`) sebagai sumber kandidat pra-pesan
- [ ] Peta pasokan: endpoint "desa/koperasi mana bisa memasok X di wilayah Y"
- [~] Skoring: v1 = besar stok. TODO: kecocokan komoditas × ketersediaan × kedekatan wilayah × reputasi warga + normalisasi nama/satuan
- [ ] Broadcast tertarget (Demand) + dorongan surplus (Supply): tentukan set warga/koperasi relevan

**Frontend**
- [ ] Di Detail Permintaan: seksi "Desa/koperasi yang berpotensi memenuhi" (berlabel jelas 'potensi, bukan jaminan stok')
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
- [ ] Go: unit test logika biaya layanan 8%, anti over-pledge/over-order, sengketa parsial, transisi status, perhitungan reputasi, pemulihan gotong-royong ulang
- [ ] Go: integration test endpoint utama (auth, demands+pledges+gateway, listings+orders, verifikasi, transaksi)
- [ ] Frontend: test komponen & alur utama (Vitest)
- [ ] Lint/format: `golangci-lint` + `gofmt` (Go), ESLint + Prettier (Nuxt)

### Deployment 🟡
- [ ] `Dockerfile` untuk Nuxt (build SSR) & Go (multi-stage build)
- [ ] Docker Compose produksi + migrasi & seed otomatis saat start
- [ ] Webhook payment gateway (endpoint aman + verifikasi signature)
- [ ] HTTPS/reverse proxy (Caddy/Nginx/Traefik), env produksi, backup DB
- [ ] CI sederhana: test + lint + build image saat push 🟢

---

## Arah Lanjutan (bukan MVP) — Demand Berulang / Langganan 🟢
> Penyelesai reliabilitas via ritme. **Urutan wajib:** buktikan satu demand tunggal end-to-end → aktifkan skor reputasi → baru bangun langganan.
- [ ] `demand_berulang` (total + irama mingguan/bulanan + mulai/akhir) → melahirkan `demand_cycle` per termin
- [ ] Tiap `demand_cycle` pakai mesin `demand_pledges` yang ada (tenggat & serah-terima sendiri)
- [ ] Deposit bergulir sekali di depan (via gateway) + pelunasan per siklus
- [ ] Petani berproduksi menyongsong ritme; kapasitas nyata terbukti tumbuh dari rekam siklus

---

## Pertanyaan Terbuka (`[TBD — riset]`, jangan dikarang)
- [ ] Order B2B rata-rata (Rp) & transaksi/bulan/koperasi realistis tahun 1
- [ ] Net setelah gateway (~2–3%) vs biaya server + gateway + waktu pengurus koperasi
- [ ] Biaya & durasi onboarding koperasi pertama (desa gaptek/offline) sampai transaksi perdana
- [ ] Provider payment gateway + biaya per transaksi
- [ ] Ambang jumlah koperasi aktif sebelum roda menutup biayanya sendiri

---

## Skema Database (selaras PRD §6)
> **App DB (writable)** — semua PK `UUID`. Kolom `*_ref` = soft reference telusur ke Reference DB KDMP (di-seed, bukan FK lintas-DB).

| Tabel | Kolom inti | Soft-ref KDMP | Alur |
|---|---|---|---|
| `koperasi` | id, nama, desa, wilayah, kontak, status | `koperasi_ref` | hub |
| `users` | id, koperasi_id, name, business_name, phone, email, password_hash, role, status, verification_status, verified_at, reputation_score, fulfilled_count, failed_count | `anggota_ref` | — |
| `verifications` | id, user_id, nik, id_card_file, support_doc_file, status, reviewed_by, review_note, reviewed_at | — | KYC |
| `komoditas` | id, nama, kategori, satuan | `komoditas_ref` | acuan A+B |
| `demands` | id, buyer_id, koperasi_id, komoditas_id, item_name, satuan, total_qty, fulfilled_qty, target_price_per_item, deadline, demand_status, total_price, service_fee_percent, service_fee_amount, dp_percent, dp_amount, remaining_amount, gateway_ref, dp_status, dp_paid_at | via komoditas | A |
| `demand_pledges` | id, demand_id, warga_id, qty_pledged, qty_delivered, price_per_item, pledge_status | — | A |
| `supply_listings` | id, koperasi_id, warga_id, komoditas_id, item_name, satuan, qty_available, qty_sold, price_per_item, listing_status | via komoditas | B |
| `orders` | id, listing_id, buyer_id, qty_ordered, price_per_item, total_amount, service_fee_amount, gateway_ref, order_status | — | B |
| `demand_transactions` | id, demand_pledge_id, gross_amount, service_fee, koperasi_fee, net_amount, payment_method, payment_status, paid_at | — | A |
| `supply_transactions` | id, order_id, gross_amount, service_fee, koperasi_fee, net_amount, payment_method, payment_status, paid_at | — | B |
| `disputes` | id, demand_pledge_id, order_id, source_type, reported_by, reason, dispute_status, resolution, resolved_at | — | A+B |

**Reference DB (read-only, dataset KDMP)** — 27 tabel, tidak dimigrasi (di-clone). Tabel kunci seed & matching: `profil_koperasi`, `referensi_koperasi_wilayah`, `referensi_wilayah`, `anggota_koperasi`, `referensi_komoditas_desa`, `produk_koperasi`, `inventaris_produk`, `gerai_koperasi`. Detail → PRD §6b.
