<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Dinas 1 - {{ $peserta->nama ?? 'Peserta' }}</title>
    <style>
        @page { size: A4; margin: 24px 28px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #000; }
        .header { text-align: center; padding-bottom: 8px; margin-bottom: 18px; }
        .header .logo { position: absolute; left: 28px; }
        .logo img { height: 85px; }
        .title-1 { font-weight: 800; font-size: 16pt; margin: 0; letter-spacing: 0.2px; }
        .title-2 { font-weight: 800; font-size: 13pt; margin: 2px 0 0 0; letter-spacing: 0.2px; }
        .line { height: 2px; background: #000; margin-top: 40px; }
        .title { text-align: center; font-weight: 800; margin: 8px 0 14px; }
        .section-title { text-align: center; font-weight: 800; text-decoration: underline; margin: 6px 0 4px; }
        .section-sub { text-align: center; margin: 0 0 10px; font-size: 11pt; }
        .meta { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .meta td { padding: 3px 4px; vertical-align: top; font-size: 11pt; }
        .meta td.label { width: 60px; }
        .meta-peserta { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .meta-peserta td { padding: 3px 4px; vertical-align: top; font-size: 11pt; }
        .meta-peserta td.label { width: 120px; }
        .content { font-size: 11pt; line-height: 1.5; text-align: justify; }
        .content-indent { margin-left: 50px; }
        .text-indent { text-indent: 50px; }
        .list { margin: 8px 0 0 18px; }
        .signature { margin-top: 28px; width: 100%; }
        .signature td { vertical-align: top; font-size: 11pt; }
        .right { text-align: right; }
        .center { text-align: center; }
        .small { font-size: 10pt; }
        .page-break { page-break-after: always; }
        .tte-layout {
            margin-top: 30px;
            margin-left: auto;
            margin-right: 38px;
            width: 55%;
            border-collapse: collapse;
        }
        .tte-layout td { vertical-align: middle; }
        .tte-wrap {
            border: 1px solid #000;
        }
        .tte-table { width: 100%; border-collapse: collapse; }
        .tte-table td { vertical-align: top; }
        .tte-qr {
            width: 72px;
            height: 72px;
            margin-right: 8px;
            display: block;
        }
        .tte-key {
            width: 82px;
            height: 82px;
            margin: 5px 6px;
            display: block;
        }
        .tte-text {
            font-size: 7.4pt;
            line-height: 1.2;
            font-weight: 400;
            padding: 6px 0 0 2px;
        }
        .tte-name {
            font-size: 7.9pt;
            font-weight: 800;
            padding: 10px 0 0 0;
        }

        /* Tata Tertib page */
        .tt-title { text-align: center; font-weight: 800; text-decoration: underline; margin: 8px 0 14px; }
        .tt-list { margin: 0 0 0 22px; font-size: 11pt; line-height: 1.6; }
    </style>
</head>
@php
    $nama = $peserta->nama ?? '-';
    $nim = $peserta->lamaran->nim_sn ?? $peserta->nim_sn ?? '-';
    $nimLabel = (strtoupper($peserta->lamaran->kategori ?? '') === 'PKL') ? 'NISN' : 'NIM';
    $prodi = $peserta->lamaran->jurusan ?? '-';
    $instansi = $peserta->lamaran->instansi ?? '-';
    $kategoriOriginal = $peserta->lamaran->kategori ?? '-';
    $kategori = $kategoriOriginal;
    if (strtoupper($kategoriOriginal) === 'LAINNYA' && !empty($peserta->lamaran->kategori_lainnya)) {
        $kategori = $peserta->lamaran->kategori_lainnya;
    }
    $kategori = (strtoupper($kategori) === 'PKL') ? 'PKL' : ucfirst(strtolower((string) $kategori));
    $nomorSurat = $peserta->lamaran->nomor_surat_pengantar ?? '-';
    $unitNama = ucwords(strtolower($peserta->satker->nama ?? 'Satuan Kerja'));
    $ythText = 'Kepala ' . $unitNama;
    $halText = (strtoupper($kategoriOriginal) === 'PKL') ? $kategori : ucfirst(strtolower($kategori));
    $pimpinanText = $peserta->lamaran->jabatan_penandatangan_surat ?? ((strtoupper($kategoriOriginal) === 'PKL') ? 'Kepala Sekolah' : 'Pimpinan');
    $tglMulai = $peserta->lamaran->tanggal_mulai
        ? \Carbon\Carbon::parse($peserta->lamaran->tanggal_mulai)->locale('id')->translatedFormat('j F Y')
        : '-';
    $tglSelesai = $peserta->lamaran->tanggal_selesai
        ? \Carbon\Carbon::parse($peserta->lamaran->tanggal_selesai)->locale('id')->translatedFormat('j F Y')
        : '-';
    $gender = strtoupper(trim((string) ($peserta->lamaran->gender ?? '')));
    $isFemale = ($gender === 'P' || str_contains($gender, 'PEREMPUAN') || str_contains($gender, 'WANITA'));
    $sebutan = (strtoupper($kategoriOriginal) === 'PKL')
        ? ($isFemale ? 'siswi' : 'siswa')
        : ($isFemale ? 'mahasiswi' : 'mahasiswa');
@endphp
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('theme/admin-dashbyte/dist/assets/img/logo-setjen-pdf.png') }}" alt="DPR RI">
        </div>
        <div>
            <div class="title-1">SEKRETARIAT JENDERAL</div>
            <div class="title-2">DEWAN PERWAKILAN RAKYAT REPUBLIK INDONESIA</div>
        </div>
        <div class="line"></div>
    </div>

    <div class="title">{{ strtoupper($satker_bidang_nama ?? 'BIDANG PENGEMBANGAN KOMPETENSI MANAJERIAL DAN SOSIAL KULTURAL') }}</div>
    <div class="section-title">NOTA DINAS</div>
    <div class="section-sub">Nomor: {{ $nomor_nodin }}</div>
    <table class="meta" style="margin-left:50px; margin-top:50px;">
        <tr>
            <td class="label">Yth.</td>
            <td>: {{ $ythText }}</td>
        </tr>
        <tr>
            <td class="label">Dari</td>
            <td>: {{ $dari_jabatan }}</td>
        </tr>
        <tr>
            <td class="label">Hal</td>
            <td>: {{ $halText }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td>: {{ $today }}</td>
        </tr>
    </table>

    <div class="content-container" style="margin-left:100px;">
        <div class="content text-indent">
            Dengan hormat, kami sampaikan surat dari {{ $pimpinanText }} {{ $instansi }},
            Nomor {{ $nomorSurat }}, tanggal {{ \Carbon\Carbon::parse($lamaran->created_at)->translatedFormat('d F Y') }},
            perihal Permohonan {{ $kategori }} di Sekretariat Jenderal DPR RI,
            atas nama:
        </div>

        <div class="content-indent">
            <table class="meta-peserta" style="margin-top:8px;">
                <tr><td class="label">Nama</td><td>: {{ $nama }}</td></tr>
                <tr><td class="label">{{ $nimLabel }}</td><td>: {{ $nim }}</td></tr>
                <tr><td class="label">Program Studi</td><td>: {{ $prodi }}</td></tr>
            </table>
        </div>

        <div class="content text-indent">
            Sehubungan dengan itu, kami mengharapkan bantuan Saudara dapat menerima
            {{ $sebutan }} tersebut untuk melakukan {{ $kategori }} di {{ $unitNama }} Setjen DPR RI
            dengan mengikuti Tata Tertib Pelaksanaan {{ $kategori }}. Kegiatan {{ $kategori }} dimulai pada
            tanggal {{ $tglMulai }} sampai dengan tanggal {{ $tglSelesai }}.
        </div>

        <div class="content text-indent" style="margin-top:8px;">
            Berikut ini kami lampirkan pernyataan persetujuan {{ $kategori }} dan tata tertib dimaksud.
        </div>
        <div class="content text-indent">
            Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
        </div>
    </div>

    <table class="tte-layout">
        <tr>
            <td style="width:78px;">
                {{-- <img class="tte-qr" src="{{ $signature_qr_url ?? '' }}" alt="QR Verifikasi"> --}}
            </td>
            <td>
                <div class="tte-wrap">
                    <table class="tte-table">
                        <tr>
                            <td style="width:94px;">
                                <img class="tte-key" src="{{ public_path('theme/admin-dashbyte/dist/assets/img/ttdkey.png') }}" alt="TTE Key">
                            </td>
                            <td>
                                <div class="tte-text">
                                    Ditandatangani Secara Elektronik Oleh:<br>
                                    {{ $signer_title }}
                                </div>
                                <div class="tte-name">{{ $signer_name ?? 'Warsiti Alfiah, S.IP., DESS' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <div class="header">
        <div class="logo">
            <img src="{{ public_path('theme/admin-dashbyte/dist/assets/img/logo-setjen-pdf.png') }}" alt="DPR RI">
        </div>
        <div>
            <div class="title-1">SEKRETARIAT JENDERAL</div>
            <div class="title-2">DEWAN PERWAKILAN RAKYAT REPUBLIK INDONESIA</div>
        </div>
        <div class="line"></div>
    </div>

    <div class="tt-title">TATA TERTIB PELAKSANAAN {{ strtoupper($kategori) }}</div>
    @php
        $peraturanHtml = \Modules\Magang\App\Models\SiteSetting::get('peraturan_pkl', '');
    @endphp
    @if(!empty($peraturanHtml))
        <div style="margin-left:50px; font-size:11pt; line-height:1.6;">
            {!! clean($peraturanHtml) !!}
        </div>
    @else
        <ol class="tt-list" style="margin-left:50px;">
            <li>Menjaga nilai-nilai Setjen RI yakni BERAKHLAK (Berorientasi Pelayanan Akuntabel Kompeten Harmonis Loyal Adaptif Kolaboratif);</li>
            <li>Bersikap sopan dan santun selama di lingkungan Setjen DPR RI;</li>
            <li>Berpakaian rapi dan sopan;</li>
            <li>Tidak memakai celana jeans, melainkan celana bahan;</li>
            <li>Tidak memakai kaos, melainkan kemeja;</li>
            <li>Rambut rapi, tidak panjang bagi laki-laki;</li>
            <li>Datang tepat pada waktunya sesuai dengan jam kantor pukul 08.00 WIB;</li>
            <li>Pulang tepat pada waktunya sesuai dengan jam kantor pukul 16.00 WIB (menyesuaikan dengan Bagian/Bidang/Unit);</li>
            <li>Selalu memakai Jas Almamater apabila keluar Ruangan, mengantar surat atau memasuki Ruangan Sidang/Rapat;</li>
            <li>Selalu memakai ID Card/Tanda Pengenal.</li>
        </ol>
    @endif
</body>
</html>
