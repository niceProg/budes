<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Dinas ke Universitas/Instansi - {{ $peserta->nama ?? 'Peserta' }}</title>
    <style>
        @page { size: A4; margin: 24px 32px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #000; font-size: 11pt; }

        /* ===== Kop Surat ===== */
        .kop { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .kop td { vertical-align: middle; }
        .kop .logo-cell { width: 110px; text-align: center; }
        .kop .logo-cell img { height: 90px; }
        .kop .text-cell { text-align: center; }
        .kop .title-1 { font-weight: 800; font-size: 16pt; margin: 0; letter-spacing: 0.2px; }
        .kop .title-2 { font-weight: 800; font-size: 14pt; margin: 2px 0 0 0; letter-spacing: 0.2px; }
        .kop .addr { font-size: 9pt; margin: 3px 0 0 0; }
        .kop .addr a { color: #0000ee; text-decoration: underline; }
        .kop-line { height: 2.5px; background: #000; margin: 4px 0 16px; }

        /* ===== Meta (Nomor/Sifat/...) ===== */
        .meta { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .meta td { padding: 1px 4px; vertical-align: top; font-size: 11pt; }
        .meta td.label { width: 78px; }
        .meta td.sep { width: 10px; }
        .meta td.date { text-align: right; white-space: nowrap; }

        /* ===== Tujuan (Yth.) ===== */
        .tujuan { margin: 4px 0 12px; font-size: 11pt; line-height: 1.4; font-family: Arial, Helvetica, sans-serif; }
        .tujuan .yth { font-weight: 800; }
        .tujuan .nama-tujuan { font-weight: 800; }
        .tujuan .kota { font-weight: 800; }

        /* ===== Isi ===== */
        .content { font-size: 11pt; line-height: 1.6; text-align: justify; }
        .text-indent { text-indent: 50px; }
        .meta-peserta { width: 100%; border-collapse: collapse; margin: 6px 0 6px 50px; }
        .meta-peserta td { padding: 2px 4px; vertical-align: top; font-size: 11pt; }
        .meta-peserta td.label { width: 150px; }

        /* ===== TTE / Tanda Tangan Elektronik ===== */
        .tte-layout {
            margin-top: 24px;
            margin-left: auto;
            margin-right: 20px;
            width: 62%;
            border-collapse: collapse;
        }
        .tte-layout td { vertical-align: middle; }
        .tte-wrap { border: 1px solid #000; }
        .tte-table { width: 100%; border-collapse: collapse; }
        .tte-table td { vertical-align: top; }
        .tte-qr { width: 80px; height: 80px; margin-right: 8px; display: block; }
        .tte-key { width: 82px; height: 82px; margin: 5px 6px; display: block; }
        .tte-text { font-size: 7.8pt; line-height: 1.25; font-weight: 400; padding: 8px 0 0 2px; }
        .tte-name { font-size: 8.2pt; font-weight: 800; padding: 10px 0 0 0; }

        /* ===== Tembusan ===== */
        .tembusan { margin-top: 26px; font-size: 10.5pt; line-height: 1.4; }
        .tembusan-title { margin-bottom: 2px; }
        .tembusan ol { margin: 0; padding-left: 22px; }
        .tembusan-line { height: 1px; background: #000; margin-top: 6px; }
    </style>
</head>
<body>
    {{-- ===================== KOP SURAT ===================== --}}
    <table class="kop">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('theme/admin-dashbyte/dist/assets/img/logo-setjen.png') }}" alt="DPR RI">
            </td>
            <td class="text-cell">
                <div class="title-1">SEKRETARIAT JENDERAL</div>
                <div class="title-2">DEWAN PERWAKILAN RAKYAT REPUBLIK INDONESIA</div>
                <div class="addr">JLN. JENDERAL GATOT SUBROTO JAKARTA KODE POS 10270</div>
                <div class="addr">TELP (021) 5715 349 FAX (021) 5715 423 / 5715 925, WEBSITE : <a href="https://www.dpr.go.id">www.dpr.go.id</a></div>
            </td>
        </tr>
    </table>
    <div class="kop-line"></div>

    {{-- ===================== META ===================== --}}
    <table class="meta">
        <tr>
            <td class="label">Nomor</td>
            <td class="sep">:</td>
            <td>{{ $nomor_nodin }}</td>
            <td class="date">{{ $today }}</td>
        </tr>
        <tr>
            <td class="label">Sifat</td>
            <td class="sep">:</td>
            <td colspan="2">{{ $sifat }}</td>
        </tr>
        <tr>
            <td class="label">Derajat</td>
            <td class="sep">:</td>
            <td colspan="2">{{ $derajat }}</td>
        </tr>
        <tr>
            <td class="label">Lampiran</td>
            <td class="sep">:</td>
            <td colspan="2">{{ $lampiran }}</td>
        </tr>
        <tr>
            <td class="label">Perihal</td>
            <td class="sep">:</td>
            <td colspan="2">{{ $perihal }}</td>
        </tr>
    </table>

    {{-- ===================== TUJUAN ===================== --}}
    <div class="tujuan">
        <div class="yth">Yth.</div>
        <div class="nama-tujuan">{{ $jabatan_penerima }}</div>
        <div class="nama-tujuan">{{ $instansi }}</div>
        @if(!empty($alamat_instansi))
            <div class="kota">{{ $alamat_instansi }}</div>
        @endif
    </div>

    {{-- ===================== ISI ===================== --}}
    <div class="content text-indent">
        Menindaklanjuti surat Saudara nomor {{ $nomor_surat }}, tanggal {{ $tanggal_surat }},
        perihal {{ $perihal_surat }}, dengan ini kami beritahukan bahwa Sekretariat Jenderal DPR RI dapat
        menerima {{ $orang_nama_label }} Saudara, atas nama:
    </div>

    <table class="meta-peserta">
        <tr><td class="label">Nama</td><td>: {{ $nama }}</td></tr>
        <tr><td class="label">{{ $nim_label }}</td><td>: {{ $nim }}</td></tr>
        <tr><td class="label">Program Studi</td><td>: {{ $prodi }}</td></tr>
    </table>

    <div class="content text-indent">
        untuk melakukan kegiatan {{ $kategori }} di {{ $unit_nama }}
        Sekretariat Jenderal DPR RI dengan melaksanakan Tata Tertib {{ $kategori }} yang berlaku
        mulai tanggal {{ $tgl_mulai }} sampai dengan {{ $tgl_selesai }}.
    </div>

    <div class="content text-indent">
        Apabila dalam pelaksanaan penerimaan peserta {{ $kategori }} ini terdapat hal-hal yang tidak sesuai
        dengan ketentuan yang berlaku, Saudara dapat melaporkan melalui tautan {{ $lapor_link }}.
    </div>

    <div class="content text-indent">
        Atas perhatian Saudara, kami mengucapkan terima kasih.
    </div>

    {{-- ===================== TTE ===================== --}}
    <table class="tte-layout">
        <tr>
            <td style="width:88px;">
                {{-- <img class="tte-qr" src="{{ $signature_qr_url }}" alt="QR Verifikasi"> --}}
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
                                    Ditandatangani secara elektronik oleh:<br>
                                    {{ $signer_title }}
                                </div>
                                <div class="tte-name">{{ $signer_name }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===================== TEMBUSAN ===================== --}}
    <div class="tembusan">
        <div class="tembusan-title">Tembusan:</div>
        <ol>
            <li>Sekretaris Jenderal DPR RI;</li>
            <li>Kepala {{ $parent_satker_nama }} Setjen DPR RI;</li>
            <li>Kepala {{ $unit_nama }} Setjen DPR RI.</li>
        </ol>
        <div class="tembusan-line"></div>
    </div>
</body>
</html>
