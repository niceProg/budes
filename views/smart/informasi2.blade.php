<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Tiket Pendaftaran Magang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.ico') }}" type="image/x-icon">
    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
            --gold-solid: #b08d48;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            background-color: #f8fafc;
            color: var(--text-main);
            line-height: 1.7;
            overflow-x: hidden;
        }

        /* Background Motif Batik */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
            background-size: 400px;
            opacity: 0.05;
            z-index: -1;
            pointer-events: none;
        }

        header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 0.8rem 6%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            transition: var(--transition);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-container img {
            height: 45px;
            width: auto;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--primary-dark);
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .logo-text span {
            display: block;
            font-size: 0.7rem;
            color: var(--gold-solid);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .container {
            background-color: white;
            padding: 40px;
            width: 80%;
            max-width: 800px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            margin: 100px auto 30px;
        }

        h2 {
            font-size: 24px;
            color: #131313;
            text-align: center;
            margin-bottom: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .ticket-header {
            margin-bottom: 24px;
            text-align: center;
        }

        .ticket-eyebrow {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .ticket-divider {
            width: min(420px, 90%);
            height: 4px;
            margin: 0 auto;
            border-radius: 999px;
            background: linear-gradient(90deg, #d1d5db 0%, #b08d48 50%, #d1d5db 100%);
            box-shadow: 0 3px 8px rgba(176, 141, 72, 0.2);
        }

        .ticket-meta {
            background: linear-gradient(180deg, #fafafa 0%, #ffffff 100%);
            border: 1px solid #ececec;
            border-radius: 12px;
            padding: 18px 16px 8px;
            margin-bottom: 16px;
        }

        .form-inline {
            display: flex;
            align-items: baseline;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e5e7eb;
        }

        .ticket-meta .form-inline:last-child {
            border-bottom: none;
            margin-bottom: 6px;
        }

        .form-inline label {
            font-weight: 600;
            font-size: 16px;
            color: #4A4A4A;
            flex: 0 0 170px;
            position: relative;
            padding-right: 1rem;
            flex-shrink: 0;
        }

        .form-inline label::after {
            content: ':';
            position: absolute;
            right: 0;
            top: 0;
        }

        .form-inline .value {
            padding: 0;
            font-size: 16px;
            color: #0f172a;
            font-weight: 600;
        }

        .meta-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .meta-label i {
            color: var(--gold-solid);
            font-size: 0.9rem;
            width: 16px;
            text-align: center;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            min-height: 32px;
            padding: 6px 12px;
            font-size: 0.92rem;
            font-weight: 700;
            line-height: 1.2;
            border: 1px solid transparent;
        }

        .status-pill.is-process {
            background: #fef9c3;
            color: #854d0e;
            border-color: #fde68a;
        }

        .status-pill.is-reject {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fecaca;
        }

        .status-pill.is-approved {
            background: #dcfce7;
            color: #166534;
            border-color: #bbf7d0;
        }

        .job-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            min-height: 32px;
            padding: 6px 12px;
            font-size: 0.92rem;
            font-weight: 700;
            border: 1px solid transparent;
            line-height: 1.2;
        }

        .job-pill i {
            font-size: 0.78rem;
        }

        .job-pill--special {
            background: #fff7ed;
            color: #92400e;
            border-color: #fed7aa;
        }

        .job-pill--general {
            background: #f1f5f9;
            color: #334155;
            border-color: #cbd5e1;
        }

        .large-form-group {
            border: 1px solid #dcdcdc;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            background-color: #fefefe;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        .upload-section {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .upload-section h3 {
            margin-right: 10px;
            width: 250px;
            color: #050505;
            font-size: 16px;
            font-weight: 600;
            position: relative;
            padding-right: 1rem;
        }

        .profile-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .profile-label i {
            color: var(--gold-solid);
            font-size: 0.88rem;
            width: 16px;
            text-align: center;
        }

        .upload-section h3::after {
            content: ':';
            position: absolute;
            right: 0;
            top: 0;
        }

        .upload-section .value {
            flex-grow: 1;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
        }

        .container .value a {
            color: var(--gold-solid);
            text-decoration: underline;
            text-underline-offset: 2px;
            transition: var(--transition);
        }

        .container .value a:hover {
            color: #8e6d2f;
            text-decoration-thickness: 2px;
        }

        .container .value a:focus-visible {
            outline: 2px solid var(--gold-solid);
            outline-offset: 2px;
            border-radius: 4px;
            color: #8e6d2f;
            text-decoration-thickness: 2px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.6rem 1.2rem;
            background: white;
            border: 1.5px solid var(--gold-solid);
            color: var(--gold-solid);
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--transition);
            margin-top: 15px;
            margin-right: 10px;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background: var(--accent-gold);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(142, 109, 47, 0.2);
        }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--accent-gold);
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            text-align: center;
            text-decoration: none;
            border-radius: 10px;
            margin-top: 15px;
            margin-right: 10px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(142, 109, 47, 0.2);
            border: none;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(142, 109, 47, 0.3);
        }

        .info-note {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--gold-solid);
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 6px;
            margin-bottom: 10px;
            color: #334155;
            text-align: justify;
            text-justify: inter-word;
        }

        .info-note p {
            margin-top: 6px;
            margin-bottom: 0;
            text-align: justify;
            text-justify: inter-word;
        }

        .action-group {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .action-group .btn-download,
        .action-group .btn-back {
            margin: 0;
            min-height: 48px;
            padding: 0 24px;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes softRise {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ticket-header {
            animation: softRise 0.4s ease both;
        }

        .ticket-meta {
            animation: softRise 0.48s ease both;
            animation-delay: 0.06s;
        }

        .large-form-group {
            animation: softRise 0.54s ease both;
            animation-delay: 0.12s;
        }

        .info-note {
            animation: softRise 0.58s ease both;
            animation-delay: 0.16s;
        }

        .action-group {
            animation: softRise 0.62s ease both;
            animation-delay: 0.2s;
        }

        @media (prefers-reduced-motion: reduce) {
            .ticket-header,
            .ticket-meta,
            .large-form-group,
            .info-note,
            .action-group {
                animation: none;
            }
        }



        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-content h3 {
            color: var(--text-main);
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .modal-content p {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .modal-content .warning-icon {
            font-size: 3rem;
            color: #f59e0b;
            margin-bottom: 1rem;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .modal-btn-cancel {
            background: #e2e8f0;
            color: var(--text-main);
        }

        .modal-btn-cancel:hover {
            background: #cbd5e1;
        }

        .modal-btn-confirm {
            background: var(--accent-gold);
            color: white;
        }

        .modal-btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(142, 109, 47, 0.3);
        }

        /* Loading Modal untuk Download PDF */
        .loading-modal {
            display: none;
            position: fixed;
            z-index: 3000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }

        .loading-modal.show {
            display: flex;
        }

        .loading-modal-content {
            background: white;
            padding: 2rem 1.75rem;
            border-radius: 20px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(17, 0, 0, 0.18);
            animation: slideUp 0.25s ease-out;
        }

        .loading-spinner {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            border: 5px solid rgba(176, 141, 72, 0.25);
            border-top-color: #b08d48;
            margin: 0 auto 14px;
            animation: spin 0.9s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-title {
            color: var(--primary-dark);
            font-weight: 800;
            font-size: 1.05rem;
            margin-bottom: 0.5rem;
        }

        .loading-subtitle {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
        }

        /* === MOBILE === label lebar tetap (250px) menyisakan ruang sempit untuk nilai;
           pada layar kecil tumpuk label di atas nilai dan kecilkan padding kartu. */
        @media (max-width: 576px) {
            .container {
                width: 100%;
                padding: 24px 18px;
                margin: 90px auto 20px;
            }
            .upload-section {
                flex-direction: column;
                align-items: stretch;
            }
            .upload-section h3 {
                width: auto;
                margin-right: 0;
                margin-bottom: 6px;
            }
            .form-inline {
                flex-wrap: wrap;
                border-bottom: none;
                padding-bottom: 4px;
            }
            .form-inline label {
                flex: 0 0 100%;
                margin-bottom: 2px;
                padding-right: 0;
            }
            .form-inline label::after {
                content: '';
            }
            .form-inline .value {
                word-break: break-word;
            }
            .ticket-meta {
                padding: 14px 12px 6px;
            }
            .info-note,
            .info-note p {
                text-align: left;
                text-justify: auto;
            }
        }
    </style>

    {{-- Header & footer publik bersama dengan beranda/FAQ (CSS + skin toggle) --}}
    @include('auth.partials.public-chrome-head')
</head>
<body>
    @include('partials.global_page_loader')
    <div class="bg-pattern"></div>

    @include('auth.partials.public-header')

    <div class="container">
        <div class="ticket-header">
            <p class="ticket-eyebrow">Informasi Resmi</p>
            <h2>Tiket Pendaftaran</h2>
            <div class="ticket-divider" aria-hidden="true"></div>
        </div>

        <div class="ticket-meta">
            <div class="form-inline">
                <label><span class="meta-label"><i class="fa-solid fa-ticket-simple" aria-hidden="true"></i>No. Tiket</span></label>
                <span class="value">{{ isset($data) ? $data->no_pendaftaran : 'Data tidak tersedia' }}</span>
            </div>

            <div class="form-inline">
                <label><span class="meta-label"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i>Tanggal dibuat</span></label>
                <span class="value">{{ isset($data) && $data->created_at ? $data->created_at->copy()->locale('id')->translatedFormat('d F Y') . ' pukul ' . $data->created_at->copy()->format('H:i') . ' WIB' : '-' }}</span>
            </div>

            <div class="form-inline">
                <label><span class="meta-label"><i class="fa-solid fa-circle-info" aria-hidden="true"></i>Status</span></label>
                <div class="value status-pill {{ $data->status == 1 ? 'is-process' : ($data->status == 9 ? 'is-reject' : 'is-approved') }}">
                    {{ ($data->status == 1 ? 'Berkas sedang diproses' : ($data->status == 9 ? 'Berkas lamaran ditolak' : 'Berkas telah disetujui. Silakan login.')) }}
                </div>
            </div>
        </div>

        <div class="large-form-group">
            <div class="upload-section">
                <h3><span class="profile-label"><i class="fa-solid fa-user" aria-hidden="true"></i>Nama</span></h3>
                <div class="value" style="color:black;">{{ $data->nama }}</div>
            </div>

            <div class="upload-section">
                <h3><span class="profile-label"><i class="fa-solid fa-envelope" aria-hidden="true"></i>E-mail</span></h3>
                <div class="value" style="color:black;">{{ $data->email }}</div>
            </div>

            <div class="upload-section">
                <h3><span class="profile-label"><i class="fa-solid fa-venus-mars" aria-hidden="true"></i>Jenis Kelamin</span></h3>
                <div class="value" style="color:black;">
                    {{ $data->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}
                </div>
            </div>

            <div class="upload-section">
                <h3><span class="profile-label"><i class="fa-solid fa-briefcase" aria-hidden="true"></i>Lowongan</span></h3>
                <div class="value" style="color:black;">
                    @if(!empty($data->id_lowongan))
                        <span class="job-pill job-pill--special">
                            <i class="fa-solid fa-star" aria-hidden="true"></i>
                            {{ $data->lowongan->title ?? 'Lowongan Khusus' }}
                        </span>
                    @else
                        <span class="job-pill job-pill--general">
                            <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
                            Umum
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="value info-note">
            Mohon simpan nomor tiket Anda untuk memantau status pendaftaran secara berkala pada halaman
            <a href="{{ route('status') }}"><strong>Cek Status</strong></a>.
            <p>Perkembangan status pendaftaran juga akan diinformasikan secara otomatis melalui email Anda.</p>
            <p>Jika file tiket SMART Anda tidak terunduh otomatis, maka silakan unduh secara manual melalui tombol <strong>Unduh PDF</strong> di bawah ini.</p>
        </div>

        <div class="action-group">
            <a href="#" class="btn-download" id="btnDownloadPdf">Unduh PDF</a>
            <a href="#" class="btn-back" id="btnBack"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <!-- Loading Modal untuk Download PDF -->
    <div class="loading-modal" id="loadingModal" aria-hidden="true">
        <div class="loading-modal-content">
            <div class="loading-spinner" aria-hidden="true"></div>
            <p class="loading-title">Mempersiapkan PDF...</p>
            <p class="loading-subtitle">Mohon tunggu. Jangan tutup atau muat ulang halaman ini.</p>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-content">
            <div class="warning-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3>Sebelum Keluar</h3>
            <p>
                Pastikan Anda sudah melakukan hal berikut sebelum meninggalkan halaman ini:
            </p>
            <ul style="text-align: left; margin: 1rem 0; padding-left: 1.5rem; color: var(--text-muted);">
                <li>Mengunduh file PDF pendaftaran</li>
                <li>Mencatat nomor pendaftaran: <strong style="color: var(--text-main);">{{ $data->no_pendaftaran }}</strong></li>
            </ul>
            <p style="margin-top: 1rem;">
                Apakah Anda yakin ingin meninggalkan halaman ini?
            </p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-cancel" id="btnCancel">Batal</button>
                <button class="modal-btn modal-btn-confirm" id="btnConfirm">Ya, keluar</button>
            </div>
        </div>
    </div>

    @include('auth.partials.public-footer')

    @php
        $safePdfNama = \Illuminate\Support\Str::of((string) ($data->nama ?? ''))
            ->ascii()
            ->replaceMatches('/[^A-Za-z0-9 ]+/', '')
            ->squish()
            ->trim()
            ->value();

        if ($safePdfNama === '') {
            $safePdfNama = 'Pelamar';
        }

        $pdfFilename = 'SMART - Tiket Pendaftaran Magang DPR - ' . $safePdfNama . '-' . ($data->no_pendaftaran ?? '-') . '.pdf';
    @endphp

    <script>
        const informasiId = {{ $data->id }};
        const btnBack = document.getElementById('btnBack');
        const btnDownloadPdf = document.getElementById('btnDownloadPdf');
        const loadingModal = document.getElementById('loadingModal');
        const confirmModal = document.getElementById('confirmModal');
        const btnCancel = document.getElementById('btnCancel');
        const btnConfirm = document.getElementById('btnConfirm');
        const homeUrl = '{{ route("Halaman awal") }}';
        const downloadUrl = '{{ route("download.pdf", ["id" => $data->id]) }}';
        const pdfFilename = @json($pdfFilename);

        // Flag to track if user is navigating away (not refreshing)
        let isNavigatingAway = false;

        // Fungsi untuk menjalankan download PDF (dipakai oleh tombol dan auto-download)
        function doDownloadPdf() {
            if (loadingModal) {
                loadingModal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            fetch(downloadUrl, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/pdf')) {
                        return response.blob();
                    }
                    if (contentType && contentType.includes('text/html')) {
                        throw new Error('Akses ditolak atau terjadi kesalahan');
                    }
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = pdfFilename;
                    document.body.appendChild(a);
                    a.click();
                    setTimeout(() => {
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                    }, 100);
                    setTimeout(() => {
                        if (loadingModal) {
                            loadingModal.classList.remove('show');
                            document.body.style.overflow = '';
                        }
                    }, 500);
                })
                .catch(error => {
                    console.error('Error downloading PDF:', error);
                    if (loadingModal) {
                        loadingModal.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal mengunduh PDF',
                            text: 'Unduhan PDF gagal. Silakan coba lagi.'
                        });
                    } else {
                    alert('Unduhan PDF gagal. Silakan coba lagi.');
                    }
                });
        }

        // Auto-download PDF saat halaman informasi dibuka
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', doDownloadPdf);
        } else {
            doDownloadPdf();
        }

        // Handle tombol download PDF (untuk download ulang)
        btnDownloadPdf.addEventListener('click', function(e) {
            e.preventDefault();
            doDownloadPdf();
        });

        // Handle tombol kembali
        btnBack.addEventListener('click', function(e) {
            e.preventDefault();
            confirmModal.classList.add('show');
        });

        // Handle tombol batal
        btnCancel.addEventListener('click', function() {
            confirmModal.classList.remove('show');
        });

        // Handle tombol konfirmasi keluar
        btnConfirm.addEventListener('click', function() {
            isNavigatingAway = true;
            // Clear session flag
            fetch(`/informasi/${informasiId}/clear-session`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                // Redirect to home
                window.location.href = homeUrl;
            }).catch(() => {
                // Even if request fails, still redirect
                window.location.href = homeUrl;
            });
        });

        // Handle klik di luar modal
        confirmModal.addEventListener('click', function(e) {
            if (e.target === confirmModal) {
                confirmModal.classList.remove('show');
            }
        });

        // NOTE:
        // Session akses informasi/PDF sengaja TIDAK di-clear otomatis di event unload/popstate.
        // Alasan: proses download PDF (dan tombol back setelah membuka PDF) bisa memicu event tersebut
        // dan membuat session hilang, lalu user kena 403 saat download ulang.
        // Session hanya di-clear saat user klik "Ya, Keluar" di modal konfirmasi.
    </script>

    @include('auth.partials.public-chrome-scripts')
</body>
</html>
