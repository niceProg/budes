<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat Magang</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        html, body { height: 100%; margin: 0; padding: 0; }
        body { font-family: DejaVu Serif, "Times New Roman", serif; color: #1f2937; margin: 0; padding: 0; }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        /* background template */
        img.bg {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            display: block; /* hindari whitespace default img */
        }

        .overlay {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            padding: 40px 0;
        }

        .title-small {
            text-align: center;
            font-size: 24px;
            font-weight: 800;
            margin-top: 18px;
            letter-spacing: 1px;
            color: #000;
        }
        .title-big {
            text-align: center;
            font-size: 50px;
            font-weight: 900;
            margin-top: 0;
            color: #000;
        }
        /* PKL: judul lebih panjang, font diperkecil agar muat */
        .title-big.title-big--pkl {
            font-size: 34px;
            line-height: 1.15;
        }
        .nomor {
            text-align: center;
            font-size: 17px;
            color: #000;
        }

        .content {
            margin-top: 176px;
            padding-top: 22px;
            text-align: center;
            line-height: 1.4;
            color: #000; /* semua teks utama hitam */
        }
        .content .intro {
            text-align: center;
            font-size: 16px;
            line-height: 1.4;
            width: 100%;
            margin-top: 6px;
        }

        .name {
            margin-top: 10px;
            font-weight: 900;
            font-size: 24px;
            color: #000;
        }

        .nim-line {
            font-weight: 800;
            font-size: 18.5px;
            color: #000;
        }
        .instansi-line {
            font-size: 18px;
            color: #000;
        }

        .finish-title {
            margin-top: 15px;
            font-weight: 900;
            font-size: 24px;
            letter-spacing: 0.5px;
            line-height: 1.25;
            color: #000;
            width: 100%;
        }

        .finish-line {
            font-weight: 900;
            font-size: 24px;
            letter-spacing: 0.5px;
            line-height: 1.25;
            color: #000;
            width: 100%;
        }

        .finish-sub {
            font-weight: 900;
            font-size: 24px;
            letter-spacing: 0.5px;
            line-height: 1.25;
            color: #000;
            width: 100%;
        }

        .date-line {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 150px;
            text-align: right;
            padding-right: 60px;
            font-size: 17px;
            color: #000;
        }
        /* TTE mengikuti style nodin1, posisi di bawah date-line (kanan bawah) */
        .tte-layout {
            position: absolute;
            right: 60px;
            bottom: 140px;
            width: 35%;
            border-collapse: collapse;
        }
        .tte-layout td { vertical-align: middle; }
        .tte-wrap { border: 1px solid #000; background: #fff; }
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
            color: #000;
        }
        .tte-name {
            font-size: 7.9pt;
            font-weight: 800;
            padding: 10px 0 0 0;
            color: #000;
        }
    </style>
</head>
@php
    $background = public_path('theme/admin-dashbyte/dist/assets/img/template-sertifikat.png');
@endphp
<body>
    <div class="page">
        <img class="bg" src="{{ $background }}" alt="Template Sertifikat">
        <div class="overlay">
            <div class="title-small">SEKRETARIAT JENDERAL DPR RI</div>
            @if(!empty($is_pkl))
                <div class="title-big title-big--pkl">SERTIFIKAT PRAKTIK KERJA LAPANGAN</div>
            @else
                <div class="title-big">SERTIFIKAT MAGANG</div>
            @endif
            <div class="nomor">Nomor: {{ $nomor }}</div>

            <div class="content">
                <div class="intro">Pusat Pengembangan Kompetensi SDM Legislatif Sekretariat Jenderal DPR RI, dengan ini menyatakan bahwa</div>
                <div class="name">{{ $nama }}</div>
                <div class="nim-line">{{ $nim_label ?? 'NIM' }} : {{ $nim }}</div>
                <div class="instansi-line">{{ $instansi }}</div>

                @if(!empty($is_pkl))
                    <div class="finish-title finish-title--pkl">TELAH MENYELESAIKAN PRAKTIK KERJA LAPANGAN</div>
                @else
                    <div class="finish-title">TELAH MENYELESAIKAN MAGANG</div>
                @endif
                <div class="finish-line">
                    Pada tanggal {{ $start }} s.d. {{ $end }}
                </div>
                <div class="finish-sub">
                    @php
                        $unit = $satker ?? null;
                    @endphp
                    @if($unit)
                        Di Bidang {{ $unit }}<br>Sekretariat Jenderal DPR RI
                    @else
                        Sekretariat Jenderal DPR RI
                    @endif
                </div>
            </div>
        </div>

        <div class="date-line">Jakarta, {{ $now }}</div>
        <table class="tte-layout">
            <tr>
                <td style="width:78px;">
                    <img class="tte-qr" src="{{ $signature_qr_url ?? '' }}" alt="QR Verifikasi">
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
                                        {{ $signer_title ?? 'Kepala Pengembangan Kompetensi Manajerial Dan Sosial Kultural' }}
                                    </div>
                                    <div class="tte-name">{{ $signer_name ?? 'Warsiti Alfiah, S.IP., DESS' }}</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

