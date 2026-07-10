# PRD — Project Requirements Document

## 1. Overview

**Masalah yang Diselesaikan**  
Saat ini, terjadi ketidakcocokan antara pasokan desa dan permintaan pasar riil. Di satu sisi, produsen (seperti peternak atau petani) berproduksi secara "buta" tanpa kepastian pembeli, menanggung risiko kerugian besar. Di sisi lain, pembeli (seperti pemilik usaha kuliner) kesulitan mendapatkan pasokan bahan baku yang konsisten dalam harga maupun kualitas. Di tengah mereka, rantai tengkulak mengambil porsi keuntungan yang besar.

**Tujuan Aplikasi**  
Aplikasi ini adalah sebuah platform "marketplace terbalik" khusus ekosistem desa. Sistem membalikkan alur tradisional: **pembeli yang mengumumkan kebutuhan, dan produsen desa yang menyanggupi**. Aplikasi ini bertujuan mempertemukan produsen dan pembeli secara langsung "tanpa cari-cari". Koperasi Desa (KDMP) hadir sebagai infrastruktur dan penjamin lewat sistem pembayaran *escrow* (rekening bersama). Dengan ini, produksi desa berjalan berdasarkan pesanan pasti, pembeli mendapat pasokan terjamin, dan koperasi mendapatkan penghasilan dari biaya layanan (komisi).

**Landasan Data (Grounded pada Dataset KDMP)**  
Berbeda dari marketplace yang mengarang katalog, sisi-suplai Budes **ditanamkan pada dataset resmi Koperasi Desa Merah Putih (KDMP/KDKMP)** yang disediakan panitia hackathon: 1.026 koperasi riil, 74.269 anggota, ~14.000 produk & inventaris gerai, 8.191 potensi komoditas desa, beserta hierarki wilayah. Dataset ini **read-only** (peserta hanya punya hak `SELECT`), sehingga menjadi **sumber kebenaran sisi-suplai** — siapa produsennya, apa yang bisa diproduksi tiap desa, stok gerai, dan koperasi mana yang menjamin. Layer transaksional Budes (kebutuhan, sanggupan, escrow) hidup di database aplikasi terpisah yang bisa ditulis. Konsekuensinya: **arsitektur dua-database** (lihat §5) dan matching kebutuhan berbasis data nyata, bukan tiruan.

## 2. Requirements

*   **Fokus pada Niat Pengguna Dulu (Try-before-Register):** Pengunjung harus dapat melihat daftar kebutuhan (permintaan pembeli) secara transparan sebelum diwajibkan mendaftar. Pendaftaran baru diminta ketika mereka melakukan aksi konkret (seperti menekan tombol "Sanggupi" atau "Pasang Kebutuhan").
*   **Pemenuhan Fleksibel (Gotong Royong):** Satu permintaan besar dari pembeli (misal: 500 ekor ayam) harus bisa disanggupi oleh banyak produsen kecil secara bersama-sama sesuai kapasitas masing-masing (misal: 5 orang masing-masing menyanggupi 100 ayam).
*   **Keamanan Finansial (Escrow Sederhana):** Dana pembeli ditahan oleh sistem terlebih dahulu. Pencairan ke produsen hanya terjadi *setelah* pembeli mengonfirmasi barang diterima dengan baik.
*   **Sistem Komisi Koperasi:** Sistem harus secara otomatis memotong komisi sekian persen untuk koperasi dari setiap transaksi yang berhasil diselesaikan, yang tercatat rapi di pembukuan.
*   **Penyelesaian Sengketa Sebagian:** Jika dalam pesanan gotong royong ada satu produsen yang gagal kirim, hanya dana milik produsen tersebut yang ditahan/dikembalikan, sedangkan produsen yang berhasil tetap dibayar.
*   **Grounded pada Data Nyata (Dataset KDMP):** Sisi-suplai (produsen, kapasitas produksi desa, stok gerai, koperasi penjamin) dibaca dari dataset KDMP read-only, bukan data karangan. Registrasi produsen dapat ditautkan ke identitas riil (`anggota_ref`/`koperasi_ref`), dan matching/broadcast kebutuhan memakai sinyal komoditas & inventaris nyata per wilayah.

## 3. Core Features

Berdasarkan kerangka fitur (Roadmap) Fase 1, pilar utama platform ini meliputi:

*   **Pasang Kebutuhan** [high] — Permintaan kebutuhan dari pihak pembeli.
    *   *Buat Postingan Baru*: Form bagi pembeli menulis spesifikasi barang, jumlah, dan tenggat waktu.
    *   *Jelajah Pasar*: Etalase yang menampilkan semua permintaan aktif dari pembeli, dapat dilihat publik (sebelum login).
    *   *Lihat Detail Permintaan*: Halaman lengkap yang menunjukkan progres berapa persen kebutuhan yang sudah disanggupi dan siapa saja yang menyanggupi.
*   **Sanggupi Pesanan** [high] — Respon dari produsen desa.
    *   *Ajukan Sanggupan*: Tombol untuk warga mendaftar/memasukkan jumlah barang yang sanggup mereka penuhi dari sebuah permintaan.
    *   *Pantau Sanggupanku*: Dashboard ringkas untuk warga memantau daftar sanggupan mereka dan status operasionalnya.
*   **Konfirmasi Terima** [high] — Penyelesaian logistik dan garansi.
    *   *Daftar Kiriman*: Daftar barang yang telah dikirim oleh produsen namun menunggu pengecekan fisik oleh pembeli.
    *   *Verifikasi Penerimaan*: Tombol konfirmasi 2 arah; pembeli menandai barang diterima (keseluruhan/sebagian).
    *   *Lapor Masalah*: Opsi menahan transaksi jika barang rusak, tidak sesuai, atau belum tiba.
*   **Escrow Dana** [high] — Pengelolaan arus uang yang aman.
    *   *Lihat Status Dana*: Visualisasi jernih apakah dana di tahap Ditahan (Tahap Proses), Dicairkan (Selesai), atau Disengketakan (Masalah).
    *   *Pencairan Otomatis*: Sistem transfer/pemindahan saldo mutlak kepada produsen *real-time* usai pembeli klik verifikasi terima.
    *   *Dompet & Komisi*: Saldo digital warga dan kalkulasi pemotongan admin secara otomatis untuk dimasukkan ke pembukuan koperasi per transaksi.
*   **Akun & Profil** [medium] — Identitas pengguna aplikasi.
    *   *Daftar Akun*: Modul pendaftaran untuk 3 peran utama: Pembeli, Produsen, dan Pengurus Koperasi.
    *   *Login & Logout*: Tata cara autentikasi aman standar ekosistem.
    *   *Lihat Riwayatku*: Catatan (audit trail) semua tindakan pasang, sanggup, rekap komisi, dan dana mutasi.

*   **Sistem Notifikasi** [medium] — Pengingat real-time untuk kelancaran transaksi.
    *   *Broadcast Kebutuhan*: Notifikasi ke semua produsen desa saat ada pembeli memposting kebutuhan baru.
    *   *Update Status Pesanan*: Notifikasi otomatis saat barang telah disanggupi, dikirim, atau dana telah cair ke dompet.
    *   *Peringatan Tenggat*: Pengingat otomatis bagi produsen 24 jam sebelum batas waktu pengiriman berakhir.

## 4. User Flow

Berikut adalah perjalanan langkah demi langkah para pengguna dari masuk ke web hingga transaksi berhasil:

1.  **Eksplorasi Awal (Semua Pengguna)**
    *   Pengunjung membuka web dan langsung melihat halaman "Jelajah Pasar".
    *   Pengunjung melihat daftar "Dibutuhkan: 500 Ayam, 50kg Cabai, dll".
2.  **Flow Pembeli (Membuat Kepastian)**
    *   Pembeli klik "Buat Postingan Baru" terkait kebutuhannya.
    *   Sistem meminta pembeli untuk Login/Daftar & melakukan deposit/transfer dana pesanan total ke sistem (Escrow).
    *   Sistem menahan dana; Permintaan tampil secara publik dengan stempel "Dana Terjamin".
3.  **Flow Produsen Desa (Menangkap Permintaan)**
    *   Petani/peternak melihat permintaan di Jelajah Pasar, lalu menekan "Ajukan Sanggupan" (misal: "Saya sanggup 100 ekor").
    *   Diminta Login/Daftar jika belum.
    *   Menyiapkan dan mengirim barang sebelum tenggat waktu tiba. Melalui aplikasi, status diubah menjadi "Dikirim".
4.  **Flow Serah Terima & Pencairan**
    *   Pembeli menerima barang secara fisik.
    *   Pembeli buka aplikasi, masuk ke "Daftar Kiriman", lalu klik "Verifikasi Penerimaan" khusus untuk 100 ekor dari/petani tersebut.
    *   *Trigger Sistem:* Dana 100 ekor otomatis cair (dikurangi komisi KDMP) menuju "Dompet" produsen.
    *   Petani bisa menarik uang ke rekening pribadi.

## 5. Architecture

Platform ini dibangun menggunakan arsitektur modern perpaduan sistem berbasis modul web frontend yang reaktif berkomunikasi dengan API backend (microservices-oriented), yang dirangkum menjadi kontainer untuk memudahkan deployment ke cloud.

**Arsitektur Dua-Database (wajib).** Karena dataset KDMP hackathon read-only (`SELECT` saja), Budes memisahkan penyimpanan menjadi dua Postgres:

*   **Reference DB (read-only)** — cermin lokal dataset KDMP (27 tabel: koperasi, anggota, produk, inventaris, komoditas desa, wilayah, dll). Menjadi sumber **sisi-suplai** & bahan matching. Backend mengaksesnya lewat *connection pool khusus SELECT* melalui **Reference Data Service** (layer repository read-only).
*   **App DB (writable)** — database milik Budes untuk layer **transaksional**: `users`, `demands`, `fulfillments`, `transactions`, `disputes`, `wallets`. Tidak ada FK lintas-database; keterkaitan ke KDMP disimpan sebagai *soft reference* (`koperasi_ref`, `anggota_ref`, `komoditas_ref`, `kode_wilayah`).

Keduanya berjalan sebagai service Postgres terpisah dalam Docker Compose yang sama.

```mermaid
flowchart TD
    User([Pengguna: Pembeli / Produsen / KDMP])

    subgraph Frontend [Nuxt.js - Client Apps]
        UI[UI / UX Interface]
        State[State Management / Store]
    end

    subgraph Backend [Golang - RESTful API]
        AuthService[Auth & Role Service]
        MarketService[Demand & Bid Service]
        EscrowService[Escrow & Wallet Service]
        RefService[Reference Data Service - read-only]
        MatchService[Matching & Broadcast Service]
    end

    AppDB[(App DB - writable<br/>demands, fulfillments,<br/>transactions, disputes)]
    RefDB[(Reference DB - read-only<br/>dataset KDMP:<br/>koperasi, anggota, komoditas,<br/>produk, inventaris, wilayah)]

    User <-->|HTTP/HTTPS| UI
    UI <--> State
    State <-->|JSON Data| AuthService
    State <-->|JSON Data| MarketService
    State <-->|JSON Data| EscrowService
    State <-->|JSON Data| MatchService

    AuthService <--> AppDB
    MarketService <--> AppDB
    EscrowService <--> AppDB
    MatchService --> MarketService
    MatchService -.->|SELECT| RefDB
    RefService -.->|SELECT only| RefDB
    MarketService -.->|SELECT via RefService| RefDB

    %% Keterangan Deployment
    subgraph Docker
        Frontend
        Backend
        AppDB
        RefDB
    end
```

## 6. Database Schema

Skema berikut adalah **App DB (writable)** milik Budes — mendukung marketplace gotong-royong & escrow. Kolom `*_ref` / `*_sample_id` adalah **soft reference** ke Reference DB KDMP (§6b): disimpan sebagai nilai biasa (bukan FK lintas-DB), divalidasi/di-*resolve* di aplikasi lewat Reference Data Service.

*   **users**: Menyimpan data seluruh pengguna (Buyer, Producer, Koperasi).
    *   `id` (UUID) - Primary Key
    *   `name` (String) - Nama pengguna
    *   `email` (String, unique) - Login
    *   `password_hash` (String) - bcrypt
    *   `role` (Enum) - `BUYER`, `PRODUCER`, `KOPERASI`
    *   `wallet_balance` (Decimal) - Saldo internal pengguna
    *   `koperasi_ref` (Text, nullable) - *soft ref* → KDMP `referensi_koperasi_wilayah.koperasi_ref` (untuk produsen/pengurus yang menaut koperasi riil)
    *   `anggota_ref` (Text, nullable) - *soft ref* → KDMP `anggota_koperasi.anggota_ref` (produsen yang klaim identitas anggota riil)
*   **demands**: (Kebutuhan / Permintaan Pembeli)
    *   `id` (UUID) - Primary Key
    *   `buyer_id` (UUID) - Relasi ke `users`
    *   `item_name` (String) - Contoh: "Ayam Potong"
    *   `total_qty` (Int) - Jumlah total yg diminta (misal: 500)
    *   `fulfilled_qty` (Int) - Jumlah yg sudah disanggupi (misal: 100)
    *   `price_per_item` (Decimal) - Harga penawaran per item
    *   `deadline` (Timestamp) - Tenggat pemenuhan
    *   `status` (Enum) - `OPEN`, `PARTIAL`, `CLOSED`
    *   `kode_wilayah` (Text, nullable) - *soft ref* → KDMP `referensi_wilayah.kode_wilayah` (wilayah target, untuk matching/broadcast)
    *   `komoditas_ref` (Text, nullable) - *soft ref* → KDMP `referensi_komoditas_desa.komoditas_ref` (bila kebutuhan dipetakan ke komoditas)
*   **fulfillments**: (Sanggupan / Komitmen Produsen)
    *   `id` (UUID) - Primary Key
    *   `demand_id` (UUID) - Relasi ke `demands`
    *   `producer_id` (UUID) - Relasi ke `users`
    *   `qty_pledged` (Int) - Jumlah yg disanggupi
    *   `qty_received` (Int) - Jumlah yg dikonfirmasi diterima (untuk terima sebagian)
    *   `status` (Enum) - `PENDING`, `SHIPPED`, `RECEIVED`, `DISPUTED`
    *   `koperasi_ref` (Text, nullable) - *soft ref* → koperasi penjamin/pemfasilitasi sanggupan
    *   `produk_sample_id` (Text, nullable) - *soft ref* → KDMP `produk_koperasi.produk_sample_id` (bila sanggupan dipetakan ke produk gerai)
*   **transactions**: (Sistem pembukuan dana Escrow & Komisi)
    *   `id` (UUID) - Primary Key
    *   `fulfillment_id` (UUID) - Relasi ke `fulfillments`
    *   `amount` (Decimal) - Total dana yg ditahan
    *   `koperasi_fee` (Decimal) - Potongan untuk koperasi
    *   `net_amount` (Decimal) - Dana bersih ke produsen (amount − fee)
    *   `status` (Enum) - `ON_HOLD`, `RELEASED`, `REFUNDED`
*   **disputes**: (Sengketa dari "Lapor Masalah")
    *   `id` (UUID) - Primary Key
    *   `fulfillment_id` (UUID) - Relasi ke `fulfillments`
    *   `reported_by` (UUID) - Relasi ke `users`
    *   `reason` (Text) - Alasan pelaporan
    *   `status` (Enum) - `OPEN`, `RESOLVED`
    *   `resolution` (Text, nullable) - Hasil penyelesaian

```mermaid
erDiagram
    USERS {
        uuid id PK
        string name
        string email
        string role
        decimal wallet_balance
        text koperasi_ref "soft ref → KDMP"
        text anggota_ref "soft ref → KDMP"
    }
    DEMANDS {
        uuid id PK
        uuid buyer_id FK
        string item_name
        int total_qty
        int fulfilled_qty
        decimal price_per_item
        timestamp deadline
        string status
        text kode_wilayah "soft ref → KDMP"
        text komoditas_ref "soft ref → KDMP"
    }
    FULFILLMENTS {
        uuid id PK
        uuid demand_id FK
        uuid producer_id FK
        int qty_pledged
        int qty_received
        string status
        text koperasi_ref "soft ref → KDMP"
        text produk_sample_id "soft ref → KDMP"
    }
    TRANSACTIONS {
        uuid id PK
        uuid fulfillment_id FK
        decimal amount
        decimal koperasi_fee
        decimal net_amount
        string status
    }
    DISPUTES {
        uuid id PK
        uuid fulfillment_id FK
        uuid reported_by FK
        text reason
        string status
        text resolution
    }

    USERS ||--o{ DEMANDS : "buyer makes"
    USERS ||--o{ FULFILLMENTS : "producer pledges"
    DEMANDS ||--o{ FULFILLMENTS : "contains"
    FULFILLMENTS ||--o| TRANSACTIONS : "triggers escrow/payout"
    FULFILLMENTS ||--o{ DISPUTES : "may be disputed"
```

## 6b. Reference Database (read-only — dataset KDMP)

Database referensi = **cermin lokal 27 tabel dataset hackathon** (di-clone dari remote KDMP ke Postgres Docker lokal; hanya diakses `SELECT`). Aplikasi **tidak pernah menulis** ke sini. Tabel yang relevan untuk Budes MVP:

| Tabel referensi | Dipakai untuk | Kolom kunci |
|---|---|---|
| `referensi_koperasi_wilayah` | Identitas koperasi ⇄ wilayah; anchor semua join | `koperasi_ref`, `kode_wilayah` |
| `profil_koperasi` | Nama/profil koperasi penjamin (escrow, komisi) | `koperasi_ref`, `nama_koperasi` |
| `akun_bank_koperasi` | Tampilan tujuan payout/komisi koperasi | `koperasi_ref`, `nama_bank`, `nama_rekening` |
| `anggota_koperasi` | Identitas produsen riil (klaim `anggota_ref`) | `anggota_ref`, `koperasi_ref`, `nama` |
| `referensi_komoditas_desa` | Sinyal kapasitas produksi desa (matching) | `komoditas_ref`, `kode_wilayah`, `nama_komoditas`, `volume` |
| `produk_koperasi` + `inventaris_produk` | Katalog & stok gerai (matching produk ritel) | `produk_sample_id`, `koperasi_ref`, `nama_produk`, `stok` |
| `gerai_koperasi` + `referensi_gerai_koperasi` | Titik gerai koperasi (distribusi/serah-terima) | `gerai_ref`, `koperasi_ref`, `jenis_gerai_ref` |
| `referensi_wilayah` | Hierarki prov/kab/kec/desa (broadcast tertarget) | `kode_wilayah`, `provinsi`, `kab_kota`, `kecamatan`, `desa_kelurahan` |

**Aturan integritas soft-ref:** karena tidak ada FK lintas-database, Reference Data Service memvalidasi keberadaan nilai `*_ref` saat penulisan ke App DB (mis. cek `koperasi_ref` benar-benar ada sebelum menyimpan `demand`/`user`). Nilai yang sudah di-*resolve* boleh di-cache untuk performa.

## 7. Tech Stack

Untuk menjamin performa tinggi, keamanan berstandar enterprise, dan kemudahan _maintenance_ (sesuai input prioritas), berikut rekomendasi teknologi:

*   **Frontend**: Nuxt.js (Framework Vue.js) dipadukan dengan Tailwind CSS (atau pustaka UI sepert Nuxt UI) untuk membuat tampilan yang super responsif, mulus, dan *SEO-friendly* bahkan sebelum pengguna login.
*   **Backend**: Go (Golang) — Bahasa pemograman yang sangat cepat (*high performance*), sangat cocok untuk mengeksekusi logika *escrow* berlapis, sistem komisi transaksi, dan API ringan antar pengguna di *real-time*.
*   **Database**: PostgreSQL — Relational database yang kokoh (ACID Compliance) sangat mutlak diperlukan ketika sedang menangani masalah dompet digital, escrow, dan saldo para pengguna. Dijalankan **dua instance** (lihat §5): **App DB** (writable, transaksional) dan **Reference DB** (read-only, cermin dataset KDMP). Backend Go memakai dua connection pool terpisah; pool Reference dibatasi SELECT.
*   **Deployment**: Docker — Membungkus seluruh aplikasi (Nuxt, Go, dan kedua PostgreSQL) dalam *container* via Docker Compose, sehingga saat dieksekusi di *server* manapun (VPS seperti DigitalOcean atau AWS), sistem dapat berjalan konsisten tanpa khawatir masalah konfigurasi lingkungan. Reference DB di-*seed* dari dump dataset KDMP saat provisioning.