<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Pendaftaran Magang DPR RI</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @page {
            margin: 18px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            font-size: 12.5px;
            line-height: 1.5;
        }

        .ticket-sheet {
            width: 100%;
            border: 1px solid #d8e1eb;
            border-radius: 12px;
            background: #ffffff;
            overflow: hidden;
        }

        .ticket-top-line {
            height: 8px;
            background: #b08d48;
        }

        .ticket-inner {
            padding: 26px 28px 24px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .header-left {
            width: 42%;
        }

        .header-right {
            width: 58%;
            text-align: right;
        }

        .pdf-logo-img {
            width: auto;
            vertical-align: middle;
        }

        .pdf-logo-img--smart {
            height: 46px;
        }

        .pdf-logo-img--dpr {
            height: 38px;
            margin-left: 8px;
        }

        .logo-text {
            margin-top: 7px;
        }

        .logo-text .app-name {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
        }

        .logo-text .app-sub {
            font-size: 9.5px;
            color: #b08d48;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            font-weight: bold;
        }

        .ticket-title {
            font-size: 23px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #0f172a;
        }

        .ticket-subtitle {
            margin-top: 6px;
            font-size: 10.5px;
            color: #64748b;
        }

        .header-divider {
            border-top: 2px solid #e2e8f0;
            margin: 10px 0 16px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .meta-table td {
            padding: 6px 0;
            vertical-align: top;
        }

        .meta-table .label {
            width: 170px;
            font-weight: bold;
            color: #334155;
        }

        .meta-table .separator {
            width: 16px;
            text-align: center;
            font-weight: bold;
            color: #334155;
        }

        .meta-table .value {
            font-weight: bold;
            color: #0f172a;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            margin-top: 14px;
            overflow: hidden;
        }

        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-weight: bold;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #475569;
        }

        .card-body {
            padding: 14px;
        }

        .profile-table {
            width: 100%;
            border-collapse: collapse;
        }

        .profile-table td {
            padding: 5px 0;
            vertical-align: middle;
        }

        .profile-table .heading {
            width: 170px;
            font-weight: bold;
            color: #1f2937;
        }

        .profile-table .separator {
            width: 16px;
            text-align: center;
            font-weight: bold;
            color: #475569;
        }

        .value-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 7px 10px;
            font-weight: bold;
            color: #0f172a;
        }

        .link-table {
            width: 100%;
            border-collapse: collapse;
        }

        .link-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .link-table .heading {
            width: 170px;
            font-weight: bold;
            color: #334155;
        }

        .link-table .separator {
            width: 16px;
            text-align: center;
            font-weight: bold;
            color: #475569;
        }

        .url-link {
            color: #0b5cab;
            text-decoration: underline;
            word-break: break-word;
        }

        .note {
            margin-top: 10px;
            font-size: 11px;
            color: #475569;
            padding: 8px 10px;
            border-radius: 6px;
            background: #fffbeb;
            border: 1px solid #fde68a;
        }

        .footer-note {
            margin-top: 14px;
            font-size: 10px;
            color: #64748b;
            text-align: right;
        }
    </style>
</head>
<body>
@php
    $logoSMART = public_path('theme/admin-dashbyte/dist/assets/img/SMART.png');
    $logoDPR = public_path('theme/admin-dashbyte/dist/assets/img/logo.png');
    $tanggalDibuat = $lamaran->created_at
        ? $lamaran->created_at->copy()->locale('id')->translatedFormat('d F Y') . ' ' . $lamaran->created_at->copy()->format('H:i') . ' WIB'
        : '-';
    $lowonganLabel = !empty($lamaran->id_lowongan) ? ($lamaran->lowongan->title ?? 'Lowongan Khusus') : 'Umum';
    $statusUrl = route('status');
    $websiteUrl = route('Halaman awal');
@endphp

<div class="ticket-sheet">
    <div class="ticket-top-line"></div>

    <div class="ticket-inner">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <img src="{{ $logoSMART }}" alt="Logo {{ config('app.name') }}" class="pdf-logo-img pdf-logo-img--smart">
                    <img src="{{ $logoDPR }}" alt="Logo DPR RI" class="pdf-logo-img pdf-logo-img--dpr">

                    <div class="logo-text">
                        <div class="app-name">{{ config('app.name') }}</div>
                        <div class="app-sub">Setjen DPR RI</div>
                    </div>
                </td>
                <td class="header-right">
                    <div class="ticket-title">TIKET PENDAFTARAN MAGANG DPR RI</div>
                    <div class="ticket-subtitle">Dokumen resmi pendaftaran Magang/PKL/Penelitian DPR RI</div>
                </td>
            </tr>
        </table>

        <div class="header-divider"></div>

        <table class="meta-table">
            <tr>
                <td class="label">No. Tiket</td>
                <td class="separator">:</td>
                <td class="value">{{ $lamaran->no_pendaftaran ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Dibuat</td>
                <td class="separator">:</td>
                <td class="value">{{ $tanggalDibuat }}</td>
            </tr>
        </table>

        <div class="card">
            <div class="card-header">Data Pendaftar</div>
            <div class="card-body">
                <table class="profile-table">
                    <tr>
                        <td class="heading">Nama</td>
                        <td class="separator">:</td>
                        <td><div class="value-box">{{ $lamaran->nama ?? '-' }}</div></td>
                    </tr>
                    <tr>
                        <td class="heading">Email</td>
                        <td class="separator">:</td>
                        <td><div class="value-box">{{ $lamaran->email ?? '-' }}</div></td>
                    </tr>
                    <tr>
                        <td class="heading">Jenis Kelamin</td>
                        <td class="separator">:</td>
                        <td><div class="value-box">{{ $lamaran->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}</div></td>
                    </tr>
                    <tr>
                        <td class="heading">Lowongan</td>
                        <td class="separator">:</td>
                        <td><div class="value-box">{{ $lowonganLabel }}</div></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Akses Online</div>
            <div class="card-body">
                <table class="link-table">
                    <tr>
                        <td class="heading">Cek Status</td>
                        <td class="separator">:</td>
                        <td><a href="{{ $statusUrl }}" class="url-link">{{ $statusUrl }}</a></td>
                    </tr>
                    <tr>
                        <td class="heading">Website SMART</td>
                        <td class="separator">:</td>
                        <td><a href="{{ $websiteUrl }}" class="url-link">{{ $websiteUrl }}</a></td>
                    </tr>
                </table>
                <p class="note">Simpan No. Tiket Anda untuk mengecek perkembangan status pendaftaran secara berkala melalui halaman Cek Status. Perkembangan status pendaftaran juga akan diinformasikan secara otomatis melalui email Anda.</p>
            </div>
        </div>

        <div class="footer-note">Dokumen ini dihasilkan otomatis oleh sistem {{ config('app.name') }}.</div>
        <div class="footer-note">@2026 Kevin - Pustekinfo Setjen DPR RI.</div>
    </div>
</div>
</body>
</html>
