# Plan Integrasi Frontend (Nuxt) ↔ Backend (Go)

Status awal: **frontend mock-first** (Pinia, data tiruan) & **backend Go sudah lengkap**
(REST + JWT + dua-DB). Integrasi ini = mengganti lapisan data tiruan dengan panggilan API asli.
**Tidak perlu menulis endpoint baru** — cukup satu penyesuaian kecil di sisi backend (lihat §6).

---

## 1. Temuan — endpoint backend sudah menutupi semua kebutuhan frontend

Pemetaan aksi store frontend → endpoint Go (dari `internal/server/server.go`):

| Aksi frontend (`stores/app.ts`) | Method + Path | Peran |
|---|---|---|
| `submitLogin` / demo login | `POST /api/auth/login` | publik |
| `submitReg` | `POST /api/auth/register` | publik |
| `doLogout` | `POST /api/auth/logout` | login |
| restore sesi (`user`) | `GET /api/me` | login |
| daftar permintaan (Pasar) | `GET /api/demands` | publik |
| detail permintaan | `GET /api/demands/{id}` | publik |
| `submitBuat` | `POST /api/demands` | BUYER |
| `confirmDp` | `POST /api/demands/{id}/dp` | BUYER |
| `cancelDemand` | `POST /api/demands/{id}/cancel` | BUYER |
| `submitPledge` | `POST /api/demands/{id}/pledges` | WARGA |
| kandidat koperasi | `GET /api/demands/{id}/kandidat` | publik |
| `myPledges` (Aktivitasku) | `GET /api/pledges` | login |
| `cancelPledge` | `PUT /api/pledges/{id}` | login |
| daftar etalase | `GET /api/listings` | publik |
| detail listing | `GET /api/listings/{id}` | publik |
| `submitTitip` | `POST /api/listings` | WARGA / ADMIN |
| `myListings` | `GET /api/my/listings` | login |
| `submitOrder` | `POST /api/orders` | BUYER |
| `myOrders` | `GET /api/orders` | BUYER |
| `verifyOrder` | `POST /api/orders/{id}/verifikasi` | BUYER |
| batalkan pesanan | `PUT /api/orders/{id}` | login |
| transaksi (Dashboard) | `GET /api/transactions` | login |
| `advanceTxn` (tandai bayar/selesai) | `PUT /api/transactions/{kind}/{id}` | ADMIN |
| stat komisi (Dashboard) | `GET /api/pembukuan` | ADMIN |
| ajukan KYC | `POST /api/verifikasi` | login |
| daftar KYC | `GET /api/verifikasi` | ADMIN |
| `kycAct` (verif/tolak) | `PUT /api/verifikasi/{id}` | ADMIN |
| lookup koperasi/anggota (form daftar) | `GET /api/ref/koperasi`, `/api/ref/anggota` | publik |

CORS sudah mengizinkan `*` + header `Authorization` (`server.go` `cors`). Aturan bisnis cocok:
`DPPercent` (uang muka) & `KoperasiFeePercent = 5.0` sudah ada di backend.

---

## 2. Selisih bentuk data (perlu lapisan *adapter*)

DTO backend memakai `snake_case` dan nama berbeda dari model tiruan frontend. Contoh `Demand`:

| Backend (JSON) | Frontend saat ini | Catatan |
|---|---|---|
| `total_qty` | `total` | |
| `fulfilled_qty` | `fulfilled` | |
| `target_price_per_item` | `harga` | nullable |
| `demand_status` | `status` | enum sama: DRAFT/OPEN/PARTIAL/FULFILLED/CANCELLED ✓ |
| `dp_status` | `dp` (`PAID`/`UNPAID`) | verifikasi nilai enum |
| `dp_payment_method` | `method` | |
| `deadline` (RFC3339) | `deadline` (`YYYY-MM-DD`) | perlu potong tanggal |
| `pledges[].warga_name` / `qty_pledged` / `qty_delivered` / `pledge_status` | `pledges[].name` / `q` / `d` / `st` | |

Sama untuk `listings` (`avail`/`sold` vs field backend), `orders`, dan `transactions`
(`gross_amount`/`koperasi_fee`/`payment_status`/`kind` = DEMAND\|SUPPLY, huruf besar).

**Keputusan**: buat modul `utils/api-adapters.ts` yang memetakan DTO backend → tipe internal
(`Demand`/`Listing` di `utils/decorate.ts`). Dengan begitu komponen & fungsi `decorate*`
**tidak berubah** — hanya sumber datanya yang berpindah.

---

## 3. Arsitektur integrasi (sisi frontend)

1. **Lapisan API client** — `composables/useApi.ts`:
   - Bungkus `$fetch.create({ baseURL: useRuntimeConfig().public.apiBase })`.
   - `onRequest`: sisipkan `Authorization: Bearer <token>` bila ada.
   - `onResponseError`: 401 → hapus sesi + arahkan ke `/masuk`; 409 → lempar pesan (UI sudah
     menyiapkan teks "melebihi sisa/stok"); 5xx → toast error generik.
2. **Sesi & JWT** — simpan token via `useCookie('budes_token')` (SSR-friendly, persist refresh):
   - Login/Register → simpan `token`, panggil `GET /api/me` → isi `user` di store.
   - `plugins/auth.client.ts` atau `app:created`: bila ada cookie token → `GET /api/me` untuk
     restore sesi saat reload.
   - Logout → `POST /api/auth/logout` + hapus cookie + reset store.
3. **Route guard** — `middleware/auth.ts` (protect `/buat`, `/titip`, `/aktivitas`, `/dashboard`)
   & `middleware/role.ts` (mis. dashboard = ADMIN_KOPERASI). Menggantikan cek `onMounted` ad-hoc.
4. **Pengambilan data**:
   - Halaman publik (Pasar, Etalase, detail) → `useAsyncData`/`useFetch` (boleh SSR untuk SEO).
   - Mutasi (pledge/order/buat/titip/verify) → panggil `useApi` di action store, lalu `refresh()`
     data terkait (atau update optimistik + rollback bila gagal).
5. **State**: `stores/app.ts` tetap sebagai pusat state, tetapi field mock (`demands`, `listings`,
   dst.) diisi dari server; aksi lokal diganti panggilan API. `utils/mockdb`/data seed di store
   dihapus di fase akhir.

---

## 4. Rencana berfase

### Fase 0 — Prasyarat & pembuktian koneksi (½ hari)
- Jalankan DB & backend: `cd deploy && docker compose up -d`, lalu `cd backend && cp .env.example .env && go run ./cmd/api` (`:8080`). Seed bila perlu (`go run ./cmd/seed`).
- Set `frontend/.env` → `NUXT_PUBLIC_API_BASE=http://localhost:8080`.
- Pembuktian: `curl :8080/health`, `curl :8080/api/demands` tampil JSON.
- **Verifikasi bentuk respons** `login`/`register`/`me` (token & user) — satu-satunya bagian yang
  belum saya baca detailnya (lihat §7).

### Fase 1 — Fondasi: API client + Auth + Guard (1 hari)
- `composables/useApi.ts` (+ interceptor token & error).
- Cookie token + restore via `/api/me`; wire `submitLogin`/`submitReg`/`doLogout` ke API.
  Pertahankan tombol "akun demo" (kirim kredensial demo asli, atau tandai demo-only).
- `middleware/auth.ts` & `middleware/role.ts`; ganti guard `onMounted` di `buat/titip/aktivitas/dashboard`.
- **Uji**: login demo → sesi nyata; reload tetap login; akses `/dashboard` non-admin ditolak.

### Fase 2 — Baca data publik + adapter (1 hari)
- `utils/api-adapters.ts` (DTO→internal) untuk demand, listing, kandidat.
- Ganti sumber: Pasar (`GET /api/demands`), detail (`/api/demands/{id}` + `/kandidat`),
  Etalase (`/api/listings`, `/api/listings/{id}`) via `useAsyncData`.
- Komponen `DemandCard/Row/ListingCard` & `decorate*` tetap; hanya data berubah.

### Fase 3 — Mutasi inti (1–2 hari)
- Wire action: `submitBuat`→create, `confirmDp`→dp, `cancelDemand`→cancel,
  `submitPledge`→pledges, `submitOrder`→orders, `submitTitip`→listings.
- Pola: kirim → `refresh()` daftar/detail terkait → toast sukses; tangani 409 (pesan kuota/stok).
- Pindahkan konteks modal (id demand/listing) dari route ke argumen action (sudah sebagian).

### Fase 4 — Aktivitasku & Dashboard koperasi (1 hari)
- `myPledges`/`myOrders`/`myListings` dari endpoint `my/*`.
- Dashboard: `GET /api/transactions` + `GET /api/pembukuan` (stat tiles), `advanceTxn`→
  `PUT /api/transactions/{kind}/{id}`, KYC via `/api/verifikasi` (list/submit/review).

### Fase 5 — Pembersihan & ketahanan (½ hari)
- Hapus data mock & mutasi lokal di `stores/app.ts`.
- State loading/error per-halaman; retry; skeleton opsional.
- 401 global → logout+redirect; pesan error dari body `{ "error": ... }` backend.

### Fase 6 — Konfigurasi & rilis (½ hari)
- Env per-lingkungan (`NUXT_PUBLIC_API_BASE` dev/staging/prod).
- Perketat CORS backend ke origin frontend untuk produksi (saat ini `*`).
- Build (`nuxt build`) + uji preview terhadap backend.

**Perkiraan total: ~5–6 hari kerja** (1 dev), bisa paralel bila dibagi (auth vs data).

---

## 5. Contoh potongan kunci

`composables/useApi.ts` (inti):
```ts
export function useApi() {
  const cfg = useRuntimeConfig()
  const token = useCookie<string | null>('budes_token')
  const api = $fetch.create({
    baseURL: cfg.public.apiBase,
    onRequest({ options }) {
      if (token.value) options.headers = { ...options.headers, Authorization: `Bearer ${token.value}` }
    },
    onResponseError({ response }) {
      if (response.status === 401) { token.value = null; navigateTo('/masuk') }
    },
  })
  return { api, token }
}
```

Contoh mutasi di store:
```ts
async submitPledge(demandId: string) {
  const { api } = useApi()
  try {
    await api(`/api/demands/${demandId}/pledges`, {
      method: 'POST',
      body: { qty_pledged: Number(this.pledgeQty), price_per_item: this.pledgePrice || null },
    })
    await this.refreshDemand(demandId)  // GET ulang detail
    this.closeModal(); this.showToast('Kesanggupan terkirim!')
  } catch (e: any) {
    this.modErr = e?.data?.error ?? 'Gagal mengirim kesanggupan.'
  }
}
```

---

## 6. Perubahan kecil yang mungkin diperlukan di backend

- **Konfirmasi bentuk respons auth** (`token` + `user`) — bila belum mengembalikan `user`,
  cukup andalkan `GET /api/me`.
- **CORS produksi**: ganti `*` → origin spesifik (dan atur `Allow-Credentials` hanya bila pindah
  ke cookie httpOnly; dengan skema Bearer-header ini tak wajib).
- Pastikan endpoint list mendukung yang dibutuhkan UI (mis. filter status demand) — bila tidak,
  filter dilakukan di klien seperti sekarang.

---

## 7. Keputusan yang perlu Anda tentukan sebelum eksekusi

1. **Penyimpanan token**: cookie (rekomendasi — SSR + persist) vs `localStorage` (SPA murni).
2. **Mode render**: pertahankan **SSR** (SEO "lihat tanpa daftar") atau jadikan **SPA**
   (`ssr: false`) agar lebih sederhana untuk auth. Rekomendasi: SSR untuk halaman publik saja.
3. **Akun demo**: tetap ada di UI (butuh kredensial demo asli di DB via seed) atau dihapus.
4. Ada perubahan kontrak API yang diinginkan (mis. field, pagination), atau ikuti apa adanya?

Setelah keputusan §7 ditetapkan, Fase 0–1 bisa langsung dieksekusi.
