# Bursa Desa — Frontend (Nuxt 3)

Konversi desain `Bursa-desa-design.html` menjadi aplikasi Nuxt. Tema **clay/terakota**
(Tailwind, token `clay`/`sand`), **mock-first** (data tiruan di Pinia — jalan tanpa backend).

## Menjalankan
```bash
cd frontend
npm install
npm run dev        # http://localhost:3000
```
Sambungkan ke backend Go nanti dengan mengisi `NUXT_PUBLIC_API_BASE` (mis. `http://localhost:8080`).

## Struktur
```
pages/               rute (file-based)
  index.vue          Jelajah Pasar (hero + permintaan)
  permintaan/[id]    Detail permintaan (pledge, rincian DP, kandidat)
  etalase/           Etalase + detail listing
  masuk.vue          Auth (login/daftar + akun demo)
  buat.vue           Buat permintaan (2 langkah: rincian → DP)
  titip.vue          Titip komoditas (warga)
  aktivitas.vue      Aktivitasku (per-peran)
  dashboard.vue      Dashboard koperasi (admin, layout sidebar)
stores/app.ts        state + logika bisnis (port dari prototipe DCLogic)
components/          kartu, badge, progress, modal, toast
composables/         format id-ID (Rupiah, tanggal)
utils/               badge maps + decorator tampilan
layouts/default.vue  header+footer (umum) / sidebar (admin) per-peran
assets/css/main.css  layer komponen Tailwind (btn, card, chip, input…)
```

## Akun demo (di halaman Masuk)
- **Budi Santoso** — Pembeli (BUYER)
- **Wati Suharti** — Warga Desa (WARGA)
- **Pak Darto** — Admin Koperasi (ADMIN_KOPERASI)

Atau login dengan email apa pun yang memuat `budi`/`wati`/`darto`/`koperasi`.

## Aturan bisnis (mock)
- Uang muka (DP) permintaan = **30%** nilai total; sisa 70% saat serah-terima.
- Komisi koperasi = **5%** per transaksi.
- Status: demand (DRAFT→OPEN→PARTIAL→FULFILLED/CANCELLED), pledge, order, pembayaran.
</content>
