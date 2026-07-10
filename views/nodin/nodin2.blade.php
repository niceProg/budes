<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Dinas 2 - {{ $peserta->nama ?? 'Peserta' }}</title>
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
        /* Kurangi indent agar teks tidak terlalu menjorong ke dalam */
        .text-indent { text-indent: 40px; }
        .content-indent { margin-left: 40px; }

        .list-table {
            width: 100%;
            border-collapse: collapse;
            margin: 22px auto 0;
        }
        .list-table th{
            border: 1px solid #000;
            padding: 7px 6px;
            font-size: 11pt;
            text-align: center;
            vertical-align: middle;
        }
        .list-table td {
            border: 1px solid #000;
            padding: 7px 6px;
            font-size: 11pt;
            text-align: justify;
            vertical-align: top;
        }

        .footer-note { text-align: center; margin-top: 26px; font-size: 11pt; }
        .tte-layout {
            margin-top: 30px;
            margin-left: auto;
            margin-right: 38px;
            width: 55%;
            border-collapse: collapse;
        }
        .tte-layout td { vertical-align: middle; }
        .tte-wrap { border: 1px solid #000; }
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
    </style>
</head>
@php
    $nama = $peserta->nama ?? '-';
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

    <div class="title">{{ $satker?->nama ? strtoupper($satker->nama) : 'SEKRETARIAT JENDERAL DPR RI' }}</div>

    <div class="section-title">NOTA DINAS</div>
    <div class="section-sub">Nomor : {{ $nomor_nodin }}</div>

    <table class="meta" style="margin-left:50px; margin-top:50px;">
        <tr>
            <td class="label">Yth.</td>
            <td>: {{ $kpd_jabatan }}</td>
        </tr>
        <tr>
            <td class="label">Dari</td>
            <td>: {{ $dari_jabatan }}</td>
        </tr>
        <tr>
            <td class="label">Hal</td>
            <td>: {{ $hal }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td>: {{ $today }}</td>
        </tr>
    </table>

    <div class="content-container" style="margin-left:100px; margin-top:30px;">
        <div class="content text-indent">
            Bersama ini kami sampaikan dengan hormat bahwa {{ $orang_nama_label }} dari
            {{ $instansi }} akan melaksanakan kegiatan {{ $kategori }} di {{ ucwords(strtolower($satker?->nama)) }} Setjen DPR RI
            dengan mengikuti Tata Tertib Pelaksanaan {{ $kategori }}, yang dimulai pada tanggal
            {{ \Carbon\Carbon::parse($peserta->lamaran->tanggal_mulai)->translatedFormat('d F Y') }} sampai dengan tanggal {{ \Carbon\Carbon::parse($peserta->lamaran->tanggal_selesai)->translatedFormat('d F Y') }}.
            Berikut kami sampaikan nama {{ $orang_nama_label }} yang akan melaksanakan kegiatan terkait :
        </div>

        <div class="content-indent">
            <table class="meta-peserta" style="margin-top:8px;">
                <tr><td class="label">Nama</td><td>: {{ $nama }}</td></tr>
                <tr><td class="label">{{ $nimLabel }}</td><td>: {{ $nim }}</td></tr>
                <tr><td class="label">Program Studi</td><td>: {{ $prodi }}</td></tr>
            </table>
        </div>
    </div>

    <div class="footer-note">
        Atas perhatian Saudara, kami ucapkan terima kasih.
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
                                    {{ ucwords(strtolower($signer_title)) }}
                                </div>
                                <div class="tte-name">{{ $signer_name }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
