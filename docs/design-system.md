# Design System — Budes (Bursa Desa) · tema "Clay" (terakota)

Panduan design system Budes. Dokumen ini menangkap **token, komponen, dan pola komposisi**
agar tampilan yang sama bisa diterapkan ulang di project lain — termasuk yang **bukan**
Nuxt/Vue (cukup Tailwind + HTML, atau React).

Basis teknis: **Tailwind CSS v3**. Palet warna diambil dari desain acuan resmi
`Bursa-desa-design.html`, **bukan** dari scaffold Nuxt (yang sempat memakai hijau sebagai
placeholder). Identitas warna yang benar adalah **clay/terakota hangat** — merah bata + netral
cokelat + latar krem. Hijau **hanya** dipakai sebagai warna status "sukses".

---

## 1. Prinsip desain

- **Hangat, bukan dingin.** Kanvas krem (`#f7efe7`), kartu putih hangat (`#fffdfb`), teks cokelat
  gelap. Tidak ada abu-abu biru (slate) dan tidak ada tema gelap.
- **Terakota sebagai identitas.** Warna utama = merah bata `#a93438` (`clay`), dipakai hemat untuk
  aksi utama, tautan, dan penanda aktif. Bukan hijau.
- **Netral hangat (cokelat/"sand").** Semua teks & border memakai skala cokelat hangat, bukan abu
  netral — inilah yang memberi kesan "clay/tanah".
- **Sudut membulat lembut.** `rounded-lg` → `rounded-xl` → `rounded-full`. Tidak ada sudut tajam
  pada elemen interaktif.
- **Kedalaman ringan.** Bayangan halus (`shadow-sm` → hover `shadow-md`) + border cokelat muda
  menegaskan tepi kartu.
- **Warna untuk makna.** Struktur pakai netral cokelat; warna aksen (terakota/hijau/amber/merah)
  hanya muncul untuk aksi atau status.
- **Bahasa Indonesia & format lokal (`id-ID`).** Rupiah tanpa desimal, tanggal ringkas.

---

## 2. Token warna

> Nilai bertanda ✓ diambil **persis** dari `Bursa-desa-design.html`. Nilai tanpa tanda adalah
> interpolasi untuk melengkapi skala (silakan sesuaikan).

### `clay` — primer (terakota / merah bata)
| Token | Hex | Sumber | Pemakaian |
|---|---|---|---|
| `clay-50`  | `#fbf1f0` | ✓ | latar chip/badge paling lembut |
| `clay-100` | `#fbe7e5` | ✓ | latar badge status merah/error lembut |
| `clay-200` | `#efc7c4` | ✓ | border hover, garis lembut |
| `clay-300` | `#e5c4c0` | ✓ | border putus-putus aksen |
| `clay-400` | `#d15a5e` |   | hover ring |
| `clay-500` | `#c14b4f` |   | focus ring, dot aktif |
| `clay-600` | `#a93438` | ✓ | **tombol primer, logo, angka penting, tautan** |
| `clay-700` | `#8e2a2f` | ✓ | hover tombol primer, teks aksen |
| `clay-800` | `#9a1b1f` | ✓ | teks merah pekat (deep) |
| `clay-900` | `#5e1518` |   | — |

### `sand` — netral hangat (cokelat) → pengganti "slate/gray"
| Token | Hex | Sumber | Pemakaian |
|---|---|---|---|
| `sand-50`  | `#fffdfb` | ✓ | **latar kartu** (putih hangat) |
| `sand-100` | `#fbf7f2` | ✓ | hover ghost, baris ringan |
| `sand-150` | `#f7efe7` | ✓ | **latar halaman (`body`)** |
| `sand-200` | `#f0e4dc` | ✓ | permukaan/inset hangat |
| `sand-300` | `#e4d5cb` | ✓ | pemisah tipis |
| `sand-400` | `#dcc5bb` | ✓ | **border kartu / input** |
| `sand-500` | `#c9afa3` | ✓ | teks paling redup / placeholder |
| `sand-600` | `#9c8378` | ✓ | teks tersier/keterangan |
| `sand-700` | `#7c6560` | ✓ | teks sekunder |
| `sand-800` | `#5c4a44` | ✓ | **warna teks body default** |
| `sand-900` | `#2b1a17` | ✓ | **judul/heading** (cokelat near-black) |

> Alternatif cepat tanpa token kustom: Tailwind `stone` mendekati skala ini (juga hangat), tapi
> sedikit lebih abu. Untuk fidelity penuh, pakai `sand` di atas.

### Semantik (status)
| Peran | Latar | Teks / solid | Sumber | Kapan |
|---|---|---|---|---|
| Sukses | `#f0f9f3` / `#e4f3ea` | `#1d7a46` / solid `#156137` | ✓ | terpenuhi, disanggupi, berhasil |
| Sukses (teal) | `#e0f1ef` | `#0f6b62` | ✓ | varian aman/terjamin |
| Peringatan | `#fdf0d9` | `#92400e` | ✓ | terisi sebagian, tenggat dekat, disclaimer |
| Error/bahaya | `#fbe7e5` (`clay-100`) | `#9a1b1f` (`clay-800`) | ✓ | gagal, hangus, ditolak |
| Netral/pasif | `sand-100` / `sand-300` | `sand-600` / `sand-500` | — | draf, menunggu, kedaluwarsa |

Border sukses lembut: `#bbdcc8` ✓. Coral error terang: `#ff8a80` ✓.

---

## 3. Tipografi

- **Font family:** system UI stack — `ui-sans-serif, system-ui, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif`.
  Tidak ada webfont; cepat & native.
- **Skala:**
  | Peran | Kelas |
  |---|---|
  | Hero H1 | `text-4xl font-bold leading-tight sm:text-5xl` |
  | Judul section (H2) | `text-2xl font-bold` |
  | Judul kartu (H3) | `text-lg`–`text-xl font-semibold` |
  | Body | `text-sm`–`text-lg text-sand-700` |
  | Eyebrow/kategori | `text-xs font-medium uppercase tracking-wide text-clay-600` |
  | Keterangan redup | `text-xs text-sand-500` |
- **Warna heading** selalu `text-sand-900`; **body** `text-sand-700`/`800`.

---

## 4. Layout & spacing

- **Kontainer utama:** `mx-auto max-w-6xl px-4` (header, main, footer memakai lebar sama).
- **Padding halaman:** `py-8` pada `<main>`.
- **Jarak antar-section:** `space-y-16`; grid antar-kartu `gap-5`.
- **Grid responsif baku:**
  - Daftar kartu: `grid gap-5 md:grid-cols-2 lg:grid-cols-3`
  - Dua kolom (hero/split): `grid gap-8 md:grid-cols-2`
  - Stat tiles: `grid grid-cols-2 gap-4`
- **Breakpoints:** mobile-first; `sm` (≥640), `md` (≥768, kolom & nav desktop), `lg` (≥1024, kolom ketiga).

### Radius & elevasi
| Elemen | Radius | Bayangan |
|---|---|---|
| Kartu / panel | `rounded-xl` | `shadow-sm` → hover `shadow-md` |
| Tombol / input | `rounded-lg` | — |
| Chip / badge / dot | `rounded-full` | — |
| Baris/inset kecil | `rounded-lg` | — |

---

## 5. Resep komponen (CSS `@layer components`)

Definisikan sekali di CSS global, pakai sebagai kelas semantik.

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  body {
    @apply bg-sand-150 text-sand-800 antialiased;
  }
}

@layer components {
  /* Tombol — dasar + varian */
  .btn {
    @apply inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5
           text-sm font-semibold transition
           focus:outline-none focus:ring-2 focus:ring-offset-2
           disabled:opacity-50 disabled:cursor-not-allowed;
  }
  .btn-primary { @apply btn bg-clay-600 text-white hover:bg-clay-700 focus:ring-clay-500; }
  .btn-outline { @apply btn border border-sand-400 bg-sand-50 text-sand-800 hover:bg-sand-100 focus:ring-sand-500; }
  .btn-ghost   { @apply btn text-sand-700 hover:bg-sand-100; }

  /* Kartu */
  .card { @apply rounded-xl border border-sand-400/60 bg-sand-50 shadow-sm; }

  /* Form */
  .input { @apply w-full rounded-lg border border-sand-400 px-3 py-2.5 text-sm outline-none
                  focus:border-clay-500 focus:ring-1 focus:ring-clay-500; }
  .label { @apply mb-1.5 block text-sm font-medium text-sand-800; }

  /* Chip / badge (warna diisi via kelas tambahan) */
  .chip { @apply inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium; }
}
```

### Anatomi
- **Tombol:** tinggi konsisten (`px-4 py-2.5 text-sm font-semibold`), selalu ada `focus:ring`.
  `primary` = aksi utama (terakota), `outline` = sekunder, `ghost` = tersier/nav.
- **Kartu (`.card`):** kontainer serba-guna, latar putih hangat. Padding saat dipakai (`p-5`/`p-6`/`p-8`).
  Interaktif: tambahkan `transition hover:shadow-md hover:border-clay-300`.
- **Chip:** bentuk pil; warna makna ditambah terpisah. Chip filter aktif:
  `bg-clay-600 text-white border-clay-600`; non-aktif `bg-sand-50 text-sand-700 border-sand-300`.
- **Input & label:** label di atas, input full-width, focus terakota (`clay-500`).

---

## 6. Pemetaan status → warna (badge)

Pola `StatusBadge`: peta `status → { label, kelas }`, render sebagai `.chip`. Ganti daftar
status sesuai domain, pertahankan aturan warna.

```ts
const map: Record<string, { label: string; cls: string }> = {
  OPEN:     { label: 'Terbuka',         cls: 'bg-clay-50 text-clay-700' },
  PARTIAL:  { label: 'Terisi sebagian', cls: 'bg-[#fdf0d9] text-[#92400e]' }, // amber
  CLOSED:   { label: 'Terpenuhi',       cls: 'bg-[#156137] text-white' },     // sukses solid
  ACCEPTED: { label: 'Disanggupi',      cls: 'bg-[#e4f3ea] text-[#1d7a46]' }, // sukses lembut
  EXPIRED:  { label: 'Kedaluwarsa',     cls: 'bg-sand-300 text-sand-500' },
  FAILED:   { label: 'Gagal',           cls: 'bg-clay-100 text-clay-800' },   // error
  PENDING:  { label: 'Menunggu',        cls: 'bg-sand-100 text-sand-600' },
}
// fallback: { label: status, cls: 'bg-sand-100 text-sand-600' }
```

**Aturan warna status:** terakota = aksi/primer & error; hijau = sukses/aman; amber = perlu
perhatian; cokelat (sand) = pasif/mati. (Bila skala hijau/amber didaftarkan sebagai token
`success`/`warning`, ganti nilai `bg-[#…]` di atas dengan kelas token yang rapi.)

---

## 7. Pola komposisi (siap salin)

### Header sticky
```html
<header class="sticky top-0 z-20 border-b border-sand-300 bg-sand-50/90 backdrop-blur">
  <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
    <a href="/" class="flex items-center gap-2">
      <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-clay-600 text-sm font-bold text-white">B</span>
      <span class="text-lg font-bold text-sand-900">Budes</span>
    </a>
    <!-- nav aktif -> bg-clay-50 text-clay-700; lain -> text-sand-700 hover:bg-sand-100 -->
  </div>
</header>
```
- **Logo mark:** kotak `h-8 w-8 rounded-lg bg-clay-600` berisi inisial putih tebal.
- **Nav link aktif:** `bg-clay-50 text-clay-700`; non-aktif `text-sand-700 hover:bg-sand-100`.

### Eyebrow + judul
```html
<div class="text-xs font-medium uppercase tracking-wide text-clay-600">Kategori</div>
<h3 class="text-lg font-semibold text-sand-900">Judul utama</h3>
```

### Pill "eyebrow" (badge di atas hero)
```html
<div class="inline-flex items-center gap-2 rounded-full bg-clay-50 px-3 py-1 text-xs font-medium text-clay-700">
  Untuk pembeli usaha
</div>
```

### Stat tile
```html
<div class="rounded-xl bg-clay-50 p-5 text-center">
  <div class="text-2xl font-bold text-clay-700">1.234</div>
  <div class="mt-1 text-xs text-sand-600">label</div>
</div>
```

### Progress bar
```html
<div class="h-2 w-full overflow-hidden rounded-full bg-sand-200">
  <div class="h-full rounded-full bg-clay-500 transition-all" style="width: 62%"></div>
</div>
```

### Penanda "aktif" (dot berdenyut)
```html
<span class="inline-block h-1.5 w-1.5 animate-pulse rounded-full bg-clay-500"></span>
```

### Callout peringatan (amber)
```html
<p class="rounded-lg bg-[#fdf0d9] px-4 py-3 text-sm text-[#92400e]">
  <strong>Catatan:</strong> teks disclaimer / peringatan.
</p>
```

### Empty state
```html
<div class="card p-10 text-center text-sand-600">
  Belum ada data. <a href="#" class="text-clay-600 underline">Tambah pertama</a>
</div>
```

---

## 8. Konfigurasi Tailwind (token)

`tailwind.config.js` — daftarkan skala `clay`, `sand`, dan (opsional) `success`/`warning`.

```js
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './components/**/*.{vue,js,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './app.vue',
    // Framework lain: './src/**/*.{tsx,jsx,html}'
  ],
  theme: {
    extend: {
      colors: {
        // Primer — terakota / merah bata (identitas Budes)
        clay: {
          50:  '#fbf1f0', 100: '#fbe7e5', 200: '#efc7c4', 300: '#e5c4c0',
          400: '#d15a5e', 500: '#c14b4f', 600: '#a93438', 700: '#8e2a2f',
          800: '#9a1b1f', 900: '#5e1518',
        },
        // Netral hangat — cokelat/tanah (pengganti slate/gray)
        sand: {
          50:  '#fffdfb', 100: '#fbf7f2', 150: '#f7efe7', 200: '#f0e4dc',
          300: '#e4d5cb', 400: '#dcc5bb', 500: '#c9afa3', 600: '#9c8378',
          700: '#7c6560', 800: '#5c4a44', 900: '#2b1a17',
        },
        // Status sukses (hijau) — HANYA untuk state berhasil, bukan primer
        success: {
          50: '#f0f9f3', 100: '#e4f3ea', 200: '#bbdcc8',
          600: '#1d7a46', 700: '#156137', 800: '#0f6b62',
        },
        // Status peringatan (amber)
        warning: { 50: '#fdf0d9', 100: '#efd9a9', 700: '#92400e' },
      },
      fontFamily: {
        sans: ['ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
```

> Dengan token ini, kelas `bg-[#fdf0d9]`/`text-[#92400e]` di §6–§7 boleh diganti menjadi
> `bg-warning-50`/`text-warning-700`, dan hijau menjadi `bg-success-100`/`text-success-600` dst.
>
> **Ganti identitas merek** cukup dengan mengedit skala `clay` — seluruh komponen ikut berubah.

---

## 9. Format & lokal

Konsisten `Intl` dengan locale `id-ID`:

```ts
new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n) // Rupiah
new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(s))   // "10 Jul 2026"
n.toLocaleString('id-ID') // angka besar
```

---

## 10. Cara menerapkan ke project lain (checklist)

1. Pasang Tailwind v3. Salin blok `theme.extend.colors` (`clay` + `sand` + `success`/`warning`)
   dan `fontFamily` dari §8.
2. Buat CSS global, tempel `@layer base` + `@layer components` dari §5. Sesuaikan glob `content`.
3. Pakai kelas semantik (`.btn-primary`, `.card`, `.chip`, `.input`, `.label`) alih-alih menulis
   ulang utilitas panjang.
4. Untuk status/badge, tiru pola peta `status → {label, cls}` (§6) sesuai domain.
5. Ikuti komposisi: kontainer `max-w-6xl px-4`, kartu `rounded-xl border-sand-400 bg-sand-50
   shadow-sm`, heading `sand-900`, body `sand-700`, aksen terakota `clay-600` hemat.
6. **Jangan** memakai hijau sebagai warna primer — hijau hanya status sukses. Identitas = terakota
   `clay` + netral hangat `sand`.

---

### Sumber ekstraksi
- **Palet warna:** `Bursa-desa-design.html` (desain acuan resmi, tema clay/terakota).
- **Struktur komponen & pola:** frontend Nuxt 4 Budes — `assets/css/main.css`,
  `layouts/default.vue`, `components/{StatusBadge,ListingCard,DemandCard,ProgressBar}.vue`,
  `composables/useFormat.ts`, `pages/index.vue`, `pages/jelajah/index.vue`.
  *(Scaffold Nuxt memakai hijau sebagai placeholder — palet final mengikuti desain acuan.)*
</content>
</invoke>
