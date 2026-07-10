# Bursa Desa (Budes)

## Apa yang sebenarnya kami jual

Budes bukan etalase. Budes menjual satu hal: **kepastian pasokan pedesaan yang tidak bisa dirakit sendiri oleh pembeli.** Seorang pemilik warung makan, katering, atau usaha kuliner tidak sanggup — sendirian — mengoordinasi delapan peternak di tiga desa, menjamin salah satunya tidak kabur, dan menuntut ganti bila barang jelek. Itulah yang kami rakit menjadi satu pasokan yang terjamin. Yang kami jual adalah **kepastian**, bukan sekadar kemudahan; kemudahan menguap begitu pembeli hafal nomor HP petani, kepastian tidak.

Kepastian itu kami bangun di atas **Koperasi Desa (KDMP) sebagai hub/perantara aktif** — bukan penampung pasif — yang menjalankan dua alur yang saling melengkapi.

## Dua alur

**Alur A — Pra-pesan / Demand (fokus utama).** Pembeli usaha mengumumkan kebutuhan ("butuh 500 ekor ayam potong per minggu", "50 kg cabai merah lusa"), koperasi menyebarkannya secara tertarget ke warga yang relevan, dan warga **menyanggupi secara gotong royong** — satu pesanan besar dipenuhi beberapa warga sekaligus (petani A 200 ekor, B 200, C 100). Petani baru berproduksi/memanen ketika permintaan sudah pasti, sehingga risiko gagal serap dan harga jatuh ditekan. Inilah jantung Budes.

**Alur B — Titip-jual / Supply (saluran surplus).** Tidak semua panen bisa diserap permintaan lokal — desa dengan surplus padi besar mustahil dibeli habis oleh warganya sendiri. Maka warga yang sudah punya komoditas menitipkannya ke koperasi. Tapi Alur B bukan rak pajangan pasif: sistem **secara aktif mendorong** surplus itu keluar — menawarkannya ke jaringan pembeli yang cocok lewat mesin pencocokan yang sama dengan Alur A. Pembeli tetap **berhak memilih** (menelusuri, membandingkan, menolak) — barang riil bukan saham, pembeli harus punya pilihan. Bila surplus belum terserap, sistem terus menawarkannya, karena membiarkan panen membusuk di rak adalah persis masalah "penampung pasif" yang kami datang untuk hancurkan.

## Peta pasokan desa: inilah keunggulan kami

Perbedaan Budes bukan pada UI marketplace — itu bisa dikloning siapa pun dalam sehari. Perbedaan kami adalah **kami bisa menjawab, sampai level desa, siapa bisa memasok apa di seluruh pedesaan Indonesia** — bersandar pada program resmi KDMP.

Sisi-suplai kami ditanamkan pada dataset resmi Koperasi Desa Merah Putih (KDMP): **1.026 koperasi desa nyata, 74.269 warga anggota, 8.191 potensi komoditas unggulan desa** (teratas: Padi, Jagung, Ayam, Sapi, Cabai, Perikanan), **~14.000 produk gerai, 1.942 gerai** — terpetakan ke hierarki provinsi hingga desa. Data koperasi, komoditas, dan warga di-seed dari dataset ini dan tetap tertaut ke sumber resminya.

Selama ini pasokan pertanian desa **tidak terlihat secara informasi** — dan justru ketidakterlihatan inilah yang memberi tengkulak kuasa menentukan harga dan membuat pembeli tak bisa mencari langsung ke desa. Budes membuat pasokan desa **terlihat dan bisa dijangkau untuk pertama kalinya.** Itu asetnya. Marketplace hanyalah salah satu cara memperlihatkannya. Karena itu **mesin Matching adalah inti produk, bukan fitur pelengkap.**

## Kejujuran soal data: kami menjanjikan PROSES, bukan STOK

Dataset KDMP memberi tahu apa yang sebuah desa **bisa** hasilkan (potensi), bukan apa yang **ada** di desa itu hari ini (stok). Angkanya statis, sebagian bahkan berskala ekstrem dan perlu normalisasi. Karena itu peran data KDMP adalah **mesin penyaran arah** — "mulai ketuk desa-desa ini" — bukan mesin janji.

Tiga lapisan yang kami pisahkan tegas:

| Lapisan | Sumber | Boleh dijanjikan ke pembeli? |
|---|---|---|
| **Potensi** — desa ini *bisa* menghasilkan X | Dataset KDMP | ❌ Tidak — hanya sinyal untuk menyasar broadcast |
| **Kapasitas** — warga ini *sanggup* produksi X sebelum tenggat | Pledge warga | 🟡 Setengah — komitmen, belum barang |
| **Ketersediaan** — ada X, siap diserahkan | Konfirmasi warga + rekam jejak siklus | ✅ Ya |

Karena Budes bukan gudang melainkan gotong royong, kami tidak menjamin **barang**, kami menjamin **proses**: bila satu warga gagal setor, sistem/koperasi memicu **gotong royong ulang** untuk menambal, dan warga yang lalai kena **penalti reputasi** (bukan denda uang yang tak bisa ditegakkan, melainkan **turunnya akses ke order berikutnya**). Ekspektasi ke pembeli dibuat jujur: terpenuhi dalam tenggat sejauh sanggupan nyata, sisanya di siklus berikut — bukan janji kepastian palsu.

## Bagaimana roda ini dibiayai (tanpa mengeksploitasi desa)

KDMP adalah **instrumen ekonomi publik** — rodanya menggerakkan uang dan kepastian ke desa, bukan mengambil margin. Tapi nirlaba tidak berarti tanpa hitungan: roda tanpa energi berhenti, dan yang rugi bukan investor melainkan warga yang terlanjur percaya lalu ditinggalkan saat layanannya mati. Karena itu ada **biaya layanan tipis yang menghidupi roda**, dirancang agar disintermediasi tidak mematikannya:

- **Biaya layanan 8%, ditanggung PEMBELI** — setimpal dengan kepastian pasokan yang tidak bisa ia rakit sendiri.
- **Ditarik di depan lewat payment gateway saat DP** — sehingga walau 70% pembayaran barang tetap tunai/transfer di lapangan, potongan layanan sudah tertangkap secara digital sebelum barang bergerak. Ini yang membuat modelnya hidup, bukan sekadar mencatat uang yang lewat.
- **Uang muka (DP ~30%) ditahan di payment gateway**, bukan tunai offline. DP hangus (`FORFEITED`) bila pembeli batal sepihak, dikembalikan (`REFUNDED`) bila pemenuhan gagal. Sisa 70% dilunasi saat serah-terima.
- Koperasi **tidak menalangi modal** — gateway menahan DP + biaya layanan, sehingga KDMP tidak mengeluarkan uang lebih dulu.

## Masalah yang kami selesaikan

Petani/peternak desa menghadapi ketidakpastian pasar dan ketergantungan tengkulak: berproduksi lalu menunggu pembeli, hasil sering tidak terserap, harga ditekan, atau membusuk. Di sisi lain, usaha kuliner kesulitan pasokan segar yang stabil, pasti jumlahnya, dan wajar harganya. Dua kebutuhan yang saling melengkapi ini jarang bertemu efisien, sementara koperasi desa stagnan sebagai penampung pasif. Budes menempatkan KDMP sebagai perantara aktif yang membuat pasokan desa terlihat, produksi terukur pada permintaan nyata, panen surplus terdorong ke pasar, pendapatan warga lebih pasti, dan roda ekonomi desa berputar dari setiap transaksi yang difasilitasi.

## Manfaat bagi tiap pihak

**Bagi petani / warga (produsen):**
- Produksi mengikuti **permintaan yang sudah pasti** — bukan lagi "produksi buta" lalu menunggu pembeli.
- Panen surplus tetap mendapat jalur pasar karena **aktif ditawarkan** koperasi, tidak dibiarkan membusuk di rak.
- Pendapatan lebih pasti dan lepas dari tekanan harga tengkulak.
- Order besar bisa dipenuhi **gotong royong** sesuai kapasitas masing-masing — petani kecil pun kebagian.
- Rekam jejak baik menaikkan **skor reputasi** → prioritas dapat order & harga lebih baik.

**Bagi pembeli usaha (katering, warung makan, dapur MBG):**
- Pasokan segar **terjamin jumlah dan harganya** tanpa perlu keliling desa mengumpulkan satu per satu.
- Mendapat **kepastian yang tak bisa dirakit sendiri** — koordinasi banyak petani + jaminan pemenuhan bila ada yang gagal.
- **Uang muka terlindungi** di payment gateway: hangus hanya bila membatalkan sepihak, dikembalikan bila pemenuhan gagal.
- Ekspektasi jujur (janji proses, bukan stok palsu) → lebih sedikit kejutan gagal kirim.

**Bagi koperasi desa (KDMP):**
- Naik peran dari **penampung pasif → hub aktif** penggerak ekonomi desa.
- Memperoleh penghasilan dari **biaya layanan** tiap transaksi untuk menghidupi operasional (tanpa menalangi modal, karena DP ditahan gateway).
- Beban administrasi lebih rapi: pencatatan transaksi, komisi, dan pembukuan otomatis.

**Bagi ekosistem desa secara luas:**
- Pasokan pedesaan menjadi **terlihat dan bisa dijangkau** untuk pertama kalinya — memutus kuasa penentuan harga tengkulak.
- Permintaan dan pasokan yang selama ini jarang bertemu efisien kini **saling menemukan** lewat koperasi.

## Cakupan MVP

Modul akun tiga peran (**Pembeli** — mendaftar atas nama usaha/toko, bukan pribadi, agar tidak mematikan warung eceran; **Warga**; **Admin Koperasi**) dengan verifikasi identitas (KYC) dan **skor reputasi warga**; alur pra-pesan gotong royong dengan DP + biaya layanan lewat gateway; alur titip-jual yang aktif mendorong surplus; konfirmasi serah-terima dua arah; pencatatan transaksi + biaya layanan; penanganan sengketa per bagian; dan **mesin Matching grounded sebagai inti**. Antarmuka dibangun untuk **koordinator belanja usaha B2B** — bukan etalase konsumen eceran.

**Arah lanjutan (bukan MVP): demand berulang / langganan** — kebutuhan besar dipecah jadi siklus berulang (mingguan/bulanan), sehingga petani berproduksi menyongsong ritme yang pasti dan kepastian tumbuh dari keteraturan, bukan dari data statis.

## Pertanyaan terbuka (menunggu riset)

Unit economics belum ditetapkan dan **tidak boleh dikarang** — butuh data lapangan: order B2B rata-rata (Rp), jumlah transaksi/bulan/koperasi yang realistis, apakah biaya layanan 8% menutup biaya operasional (server + gateway + waktu pengurus koperasi), serta biaya & durasi meng-onboard koperasi desa pertama sampai transaksi perdananya jalan. Jawaban atas pertanyaan-pertanyaan ini menentukan apakah roda benar-benar berputar sendiri.
