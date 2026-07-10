<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author" content="Themepixels">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.png') }}">

    <title>@yield('title', 'SMART Setjen DPR RI')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vendor CSS -->
    {{-- Local remixicon v2.5.0 removed: outdated @font-face overrides CDN v3.5.0, causing missing glyphs --}}
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/lib/apexcharts/apexcharts.css') }}">

    <!-- Text Editor CSS -->
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/lib/quill/quill.core.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/lib/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/lib/quill/quill.bubble.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/lib/jquery-timepicker/jquery.timepicker.min.css') }}">

    <script src="{{ asset('theme/admin-dashbyte/dist/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/admin-dashbyte/dist/lib/jquery-timepicker/jquery.timepicker.min.js') }}"></script>
    <script src="{{ asset('theme/admin-dashbyte/dist/lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }

        /* Background tekstur global (admin/dashboard) */
        .app-bg-pattern {
            position: fixed;
            inset: 0;
            background-color: #020617;
            background-image: url('{{ asset('theme/admin-dashbyte/dist/assets/img/batiknew.png') }}');
            background-size: 260px;
            background-repeat: repeat;
            opacity: 0.08;
            z-index: -1;
            pointer-events: none;
        }
        html[data-skin="dark"] .app-bg-pattern {
            background-color: #020617;
            opacity: 0.10; /* sedikit lebih terang supaya pola/foto tetap terlihat */
        }

        /* === Global Dark Mode admin: samakan nuansa dengan beranda === */
        html[data-skin="dark"] body {
            background-color: #020617 !important;
            color: #ffffff !important;
        }

        /* Konten utama (wrapper di dalam layout) */
        html[data-skin="dark"] .main-app,
        html[data-skin="dark"] .main-bg,
        html[data-skin="dark"] .content-wrapper {
            background-color: transparent !important;
            color: #ffffff !important;
        }

        /* Kartu-kartu umum (sedikit transparan agar background masih terlihat) */
        html[data-skin="dark"] .main-app .card,
        html[data-skin="dark"] .main-app .modern-card {
            background-color: rgba(15, 23, 42, 0.88);
            border-color: #1f2937;
            color: #ffffff;
            box-shadow: 0 14px 32px rgba(0,0,0,0.6);
        }

        /* Dropdown, modal, alert, tabel */
        html[data-skin="dark"] .dropdown-menu,
        html[data-skin="dark"] .modal-content,
        html[data-skin="dark"] .swal2-popup,
        html[data-skin="dark"] .table,
        html[data-skin="dark"] .table thead,
        html[data-skin="dark"] .table tbody,
        html[data-skin="dark"] .alert {
            background-color: #020617;
            color: #ffffff;
            border-color: #1f2937;
        }
        /* Pastikan semua teks modal putih di dark mode */
        html[data-skin="dark"] .modal-title,
        html[data-skin="dark"] .modal-header,
        html[data-skin="dark"] .modal-body,
        html[data-skin="dark"] .modal-footer,
        html[data-skin="dark"] .modal-header *,
        html[data-skin="dark"] .modal-body *,
        html[data-skin="dark"] .modal-footer * {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .modal-body .text-muted,
        html[data-skin="dark"] .modal-footer .text-muted {
            color: #cbd5e1 !important;
        }
        html[data-skin="dark"] .swal2-title,
        html[data-skin="dark"] .swal2-html-container,
        html[data-skin="dark"] .swal2-content {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .swal2-input,
        html[data-skin="dark"] .swal2-textarea,
        html[data-skin="dark"] .swal2-select {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .swal2-input::placeholder,
        html[data-skin="dark"] .swal2-textarea::placeholder {
            color: #9ca3af !important;
        }

        /* Teks judul di dalam kartu */
        html[data-skin="dark"] .main-app .card-header,
        html[data-skin="dark"] .main-app .main-title {
            color: #ffffff !important;
        }

        /* Input & select: background gelap, teks putih (default sama seperti register2) */
        html[data-skin="dark"] .main-app .form-control,
        html[data-skin="dark"] .main-app .form-select,
        html[data-skin="dark"] .main-app textarea {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .main-app .form-control:focus,
        html[data-skin="dark"] .main-app .form-select:focus,
        html[data-skin="dark"] .main-app textarea:focus {
            background-color: #0f172a !important;
        }
        html[data-skin="dark"] .main-app .form-control::placeholder,
        html[data-skin="dark"] .main-app .form-select::placeholder,
        html[data-skin="dark"] .main-app textarea::placeholder {
            color: #9ca3af;
        }

        /* === Dark mode: semua wrapper & panel gelap (seluruh halaman) === */
        html[data-skin="dark"] .main-container,
        html[data-skin="dark"] .main-dashboard,
        html[data-skin="dark"] .main-content,
        html[data-skin="dark"] .main-bg {
            background-color: #020617 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .welcome-card,
        html[data-skin="dark"] .welcome-banner,
        html[data-skin="dark"] .welcome-quote,
        html[data-skin="dark"] .stat-card-modern,
        html[data-skin="dark"] .banner-avatar,
        html[data-skin="dark"] .photo-popup-content {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .modal-header,
        html[data-skin="dark"] .modal-header.bg-white,
        html[data-skin="dark"] .modal-body,
        html[data-skin="dark"] .modal-footer {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .table td,
        html[data-skin="dark"] .table th,
        html[data-skin="dark"] .table-hover tbody tr:hover {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .dropdown-menu-body,
        html[data-skin="dark"] .dropdown-item {
            background-color: transparent !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .dropdown-item:hover {
            background-color: #1f2937 !important;
            color: #ffffff;
        }
        /* Utility & badge: gelap, teks tetap terbaca */
        html[data-skin="dark"] .bg-white,
        html[data-skin="dark"] .bg-light,
        html[data-skin="dark"] .bg-body,
        html[data-skin="dark"] [class*="bg-light-"] {
            background-color: #1e293b !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .card-body,
        html[data-skin="dark"] .list-group-item {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .form-control,
        html[data-skin="dark"] .form-select,
        html[data-skin="dark"] textarea.form-control {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        /* Global dark-mode form field theme (match register2) */
        html[data-skin="dark"] input,
        html[data-skin="dark"] select,
        html[data-skin="dark"] textarea {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] input:focus,
        html[data-skin="dark"] select:focus,
        html[data-skin="dark"] textarea:focus {
            background: #0f172a !important;
        }
        html[data-skin="dark"] input::placeholder,
        html[data-skin="dark"] textarea::placeholder {
            color: #9ca3af !important;
        }
        html[data-skin="dark"] select option {
            background: #1e293b !important;
            color: #ffffff !important;
        }
        /* Checkbox di dark mode: kotak jelas + icon ceklis */
        html[data-skin="dark"] .form-check-label {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .form-check-input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 1.1rem;
            height: 1.1rem;
            border-radius: 0.45rem;
            background-color: #0b1120 !important;
            border: 2px solid #4b5563 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
            transition: all 0.18s ease-out;
        }
        html[data-skin="dark"] .form-check-input::after {
            content: '\2713'; /* icon ceklis */
            font-size: 0.8rem;
            color: #0b1120;
            opacity: 0;
            transform: scale(0.6);
            transition: all 0.16s ease-out;
        }
        html[data-skin="dark"] .form-check-input:focus {
            border-color: rgba(251, 191, 36, 0.7) !important;
            box-shadow: 0 0 0 0.14rem rgba(251, 191, 36, 0.35) !important;
        }
        html[data-skin="dark"] .form-check-input:checked {
            background-color: #22c55e !important;
            border-color: #22c55e !important;
        }
        html[data-skin="dark"] .form-check-input:checked::after {
            opacity: 1;
            transform: scale(1);
            color: #022c22;
        }
        html[data-skin="dark"] .input-group-text {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .nav-tabs .nav-link:not(.active),
        html[data-skin="dark"] .nav-pills .nav-link:not(.active) {
            background-color: transparent !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .nav-tabs .nav-link.active,
        html[data-skin="dark"] .nav-pills .nav-link.active {
            background-color: #1e293b !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .pagination .page-link {
            background-color: #0f172a !important;
            border-color: #374151 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .breadcrumb {
            background-color: transparent !important;
            color: #ffffff;
        }
        html[data-skin="dark"] h1, html[data-skin="dark"] h2, html[data-skin="dark"] h3,
        html[data-skin="dark"] h4, html[data-skin="dark"] h5, html[data-skin="dark"] h6 {
            color: #ffffff !important;
        }
        html[data-skin="dark"] p, html[data-skin="dark"] span, html[data-skin="dark"] label,
        html[data-skin="dark"] small, html[data-skin="dark"] .form-label, html[data-skin="dark"] .page-title {
            color: #ffffff !important;
        }
        html[data-skin="dark"] a:not(.btn):not(.nav-link) {
            color: #fbbf24 !important;
        }
        html[data-skin="dark"] a:not(.btn):not(.nav-link):hover {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .text-dark,
        html[data-skin="dark"] .text-muted {
            color: #ffffff !important;
        }
        /* Override inline color: var(--text-main) agar keterangan/catatan/bukti putih di dark mode */
        html[data-skin="dark"] [style*="var(--text-main)"] {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .header-main {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }

        /* Popup informatif (selamat datang / pengumuman broadcast) diletakkan DI BAWAH
           header (header-main z-index 800) agar tombol menu (logo sandwich) tetap bisa
           ditekan di tampilan mobile. Tanpa ini, backdrop SweetAlert menutupi seluruh
           layar termasuk header, sehingga peserta tidak bisa membuka sidebar/absensi.
           Modal keamanan (ganti password wajib) sengaja TIDAK memakai kelas ini. */
        .swal2-container.swal-nav-safe {
            z-index: 700 !important;
        }
        html[data-skin="dark"] .offcanvas,
        html[data-skin="dark"] .page-header {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .dataTables_wrapper,
        html[data-skin="dark"] .table-responsive {
            background-color: transparent !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .participant-avatar,
        html[data-skin="dark"] .applicant-avatar {
            background-color: #1e293b !important;
            border-color: #374151 !important;
        }
        html[data-skin="dark"] .filter-dropdown .dropdown-menu,
        html[data-skin="dark"] .filter-dropdown .btn {
            background-color: #0f172a !important;
            border-color: #374151 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .score-badge,
        html[data-skin="dark"] .badge.bg-light {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff;
        }

        /* === Dark: halaman lamaran, biodata, absensi, pegawai, lowongan, agama, pendidikan, satker, roles-internal, peraturan-pkl === */
        /* Biarkan wrapper transparan supaya tekstur tetap terlihat */
        html[data-skin="dark"] .content-wrapper {
            background-color: transparent !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .page-title {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .card-toolbar {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .search-container .input-group {
            background-color: #1e293b !important;
            border-color: #374151;
        }
        html[data-skin="dark"] .search-container .input-group:focus-within {
            background-color: #1e293b !important;
            border-color: var(--gold-solid);
        }
        html[data-skin="dark"] .search-container input {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .custom-table thead th {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .custom-table tbody tr,
        html[data-skin="dark"] .custom-table tbody td {
            background-color: #0f172a !important;
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        html[data-skin="dark"] .custom-table tbody tr:hover {
            box-shadow: 0 12px 25px rgba(0,0,0,0.3);
        }
        html[data-skin="dark"] .custom-table tbody td:first-child {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .filter-section {
            background-color: rgba(15, 23, 42, 0.6) !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .filter-dropdown .dropdown-menu {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
        }
        html[data-skin="dark"] .filter-dropdown .form-check-label {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .filter-dropdown .form-check:hover {
            background: rgba(30, 41, 59, 0.8) !important;
        }
        html[data-skin="dark"] .filter-badge {
            background-color: #1e293b !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .footer-container {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .btn-action {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .btn-action:hover {
            background-color: #0f172a !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .date-badge {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .filter-box {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.9) 100%) !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .form-control-custom {
            background-color: #0f172a !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .applicant-name,
        html[data-skin="dark"] .participant-name {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .category-badge {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .status-pill,
        html[data-skin="dark"] .pill-process,
        html[data-skin="dark"] .pill-success,
        html[data-skin="dark"] .pill-danger,
        html[data-skin="dark"] .status-badge,
        html[data-skin="dark"] .status-belum-mulai,
        html[data-skin="dark"] .status-aktif,
        html[data-skin="dark"] .status-selesai,
        html[data-skin="dark"] .status-banned,
        html[data-skin="dark"] .pill-wfo,
        html[data-skin="dark"] .pill-wfh,
        html[data-skin="dark"] .pill-izin,
        html[data-skin="dark"] .pill-sakit {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff;
        }

        .pegawai-badge{
            font-weight: 600;
            font-size: 0.75rem;
        }

        html[data-skin="dark"] .pegawai-badge{
            background-color: var(--accent-gold) !important;
            border-color: #374151 !important;
            color: #ffffff;
        }

        html[data-skin="dark"] .btn-filter-reset {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #f87171;
        }
        html[data-skin="dark"] .btn-filter-reset:hover {
            color: #ffffff;
        }
        html[data-skin="dark"] .late-tag {
            background-color: #7f1d1d !important;
            color: #fca5a5;
        }
        html[data-skin="dark"] .table-container {
            background-color: transparent !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .modern-card {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] #quill-editor,
        html[data-skin="dark"] .ql-editor,
        html[data-skin="dark"] .ql-toolbar {
            background-color: #0f172a !important;
            border-color: #374151 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .ql-toolbar .ql-stroke {
            stroke: #64748b !important;
        }
        html[data-skin="dark"] .ql-toolbar .ql-fill {
            fill: #64748b !important;
        }
        html[data-skin="dark"] .px-4.py-3[style*="background"] {
            background-color: rgba(15, 23, 42, 0.6) !important;
        }
        html[data-skin="dark"] .dropdown-header {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .alert-success {
            background-color: #064e3b !important;
            border-color: #065f46 !important;
            color: #a7f3d0;
        }
        html[data-skin="dark"] .alert-danger {
            background-color: #7f1d1d !important;
            border-color: #991b1b !important;
            color: #fecaca;
        }
        html[data-skin="dark"] .alert-warning {
            background-color: #78350f !important;
            border-color: #92400e !important;
            color: #fef3c7;
        }
        html[data-skin="dark"] .alert-info {
            background-color: #1e3a5f !important;
            border-color: #1e40af !important;
            color: #bfdbfe;
        }
        html[data-skin="dark"] .list-group-item a {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .list-group-item a:hover {
            color: #fbbf24 !important;
        }
        html[data-skin="dark"] .badge-status,
        html[data-skin="dark"] .status-draft,
        html[data-skin="dark"] .status-aktif,
        html[data-skin="dark"] .status-tidak-aktif {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .btn-detail,
        html[data-skin="dark"] .btn-edit,
        html[data-skin="dark"] .btn-delete,
        html[data-skin="dark"] .btn-status,
        html[data-skin="dark"] .btn-action {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .btn-detail:hover,
        html[data-skin="dark"] .btn-edit:hover,
        html[data-skin="dark"] .btn-delete:hover,
        html[data-skin="dark"] .btn-status:hover {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .status-filter-btn:not(.active) {
            background-color: #1e293b !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .status-filter-btn.active {
            background-color: #1e293b !important;
            border-color: var(--gold-solid);
            color: #fbbf24;
        }
        html[data-skin="dark"] .status-change-btn.active {
            background-color: #1e3a5f !important;
            color: #93c5fd;
        }
        html[data-skin="dark"] .status-change-btn:not(.active):hover {
            background-color: #1e293b !important;
        }
        html[data-skin="dark"] .filter-dropdown .dropdown-menu::-webkit-scrollbar-track {
            background: #1e293b !important;
        }
        html[data-skin="dark"] .filter-dropdown .dropdown-menu::-webkit-scrollbar-thumb {
            background: #475569 !important;
        }
        html[data-skin="dark"] .info-box {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .detail-container {
            color: #ffffff;
        }
        html[data-skin="dark"] .breadcrumb-item a {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .breadcrumb-item a:hover {
            color: #fbbf24 !important;
        }

        /* === Dark: halaman detail (lamaran, biodata, agama, pendidikan, satker, roles-internal show) === */
        html[data-skin="dark"] .info-label,
        html[data-skin="dark"] .info-value,
        html[data-skin="dark"] .info-content,
        html[data-skin="dark"] .info-icon {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .info-box .info-label {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .info-box .info-value {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .info-item {
            background-color: transparent !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .info-item:hover {
            background-color: rgba(30, 41, 59, 0.6) !important;
        }
        html[data-skin="dark"] .info-group,
        html[data-skin="dark"] .detail-grid,
        html[data-skin="dark"] .info-container {
            color: #ffffff;
        }
        html[data-skin="dark"] .btn-back {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .btn-back:hover {
            background-color: #0f172a !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .id-badge {
            background-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .card-header-custom,
        html[data-skin="dark"] .card-body-custom {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .card-header-custom h6 {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .card-header-custom i {
            background-color: #1e293b !important;
            color: #fbbf24 !important;
        }
        html[data-skin="dark"] .profile-section {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff;
        }
        html[data-skin="dark"] .lowongan-summary-detail,
        html[data-skin="dark"] .lowongan-summary-detail-title,
        html[data-skin="dark"] .lowongan-summary-detail-content {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .modal-icon-wrap {
            background-color: #1e293b !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .modal-icon-wrap i {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .modal-body p,
        html[data-skin="dark"] .modal-body .form-label,
        html[data-skin="dark"] .modal-body label,
        html[data-skin="dark"] .modal-body .text-dark,
        html[data-skin="dark"] #assignRoleModal .modal-body,
        html[data-skin="dark"] #assignRoleModal .form-label,
        html[data-skin="dark"] #assignRoleModal p,
        html[data-skin="dark"] #confirmDeleteRoleModal .modal-body,
        html[data-skin="dark"] #validationRoleModal .modal-body,
        html[data-skin="dark"] #successRoleModal .modal-body {
            color: #ffffff !important;
        }
        html[data-skin="dark"] #assignRoleModal .modal-content,
        html[data-skin="dark"] #assignRoleModal .modal-header,
        html[data-skin="dark"] #assignRoleModal .modal-body,
        html[data-skin="dark"] #assignRoleModal .modal-footer,
        html[data-skin="dark"] #confirmDeleteRoleModal .modal-content,
        html[data-skin="dark"] #confirmDeleteRoleModal .modal-body,
        html[data-skin="dark"] #validationRoleModal .modal-content,
        html[data-skin="dark"] #validationRoleModal .modal-body,
        html[data-skin="dark"] #successRoleModal .modal-content,
        html[data-skin="dark"] #successRoleModal .modal-body {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] #assignRoleModal .form-select,
        html[data-skin="dark"] #assignRoleModal .form-control,
        html[data-skin="dark"] #assignRoleModal #modal-roles-container,
        html[data-skin="dark"] #assignRoleModal .form-check-label {
            background-color: #0f172a !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .card-header h6 {
            color: #ffffff !important;
        }
        html[data-skin="dark"] .card-header h6 i {
            color: #fbbf24 !important;
        }
        html[data-skin="dark"] #roleDetailModal .modal-content,
        html[data-skin="dark"] #roleDetailModal .modal-body,
        html[data-skin="dark"] #roleDetailModal .modal-footer {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] #roleDetailModal .modal-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            border-bottom-color: #1f2937 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] #roleDetailModal .modal-title { color: #ffffff !important; }
        html[data-skin="dark"] #roleDetailModal .modal-body .text-dark,
        html[data-skin="dark"] #roleDetailModal .modal-body strong,
        html[data-skin="dark"] #roleDetailModal .modal-body .form-label,
        html[data-skin="dark"] #roleDetailModal .modal-body .form-check-label,
        html[data-skin="dark"] #roleDetailModal .modal-body h6 { color: #ffffff !important; }
        html[data-skin="dark"] #roleDetailModal .modal-body .p-3.rounded {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] #roleDetailModal .modal-body .form-control,
        html[data-skin="dark"] #roleDetailModal .modal-body .form-select {
            background-color: #0f172a !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] #roleDetailModal .modal-body .role-detail-info-box,
        html[data-skin="dark"] #roleDetailModal .modal-body .role-detail-roles-box {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] #roleDetailModal .modal-body .role-detail-label { color: #d1d5db !important; }
        html[data-skin="dark"] #roleDetailModal .modal-footer .btn-secondary {
            background-color: #1e293b !important;
            border-color: #475569 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] #roleDetailModal .modal-footer .btn-primary {
            background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%) !important;
            border: none !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] #roleDetailModal .modal-footer {
            border-top-color: #1f2937 !important;
        }
        html[data-skin="dark"] .main-app p,
        html[data-skin="dark"] .main-app span,
        html[data-skin="dark"] .main-app label {
            color: #ffffff;
        }
        html[data-skin="dark"] .main-app .form-label {
            color: #ffffff !important;
        }

        .dataTable th.sorting::after,
        .dataTable th.sorting_asc::before,
        .dataTable th.sorting_asc::after,
        .dataTable th.sorting::before,
        .dataTable th.sorting_desc::after,
        .dataTable th.sorting_desc::before {
            content: none !important;
        }

        /* === BACKGROUND FIX (AMAN) === */
        .main-bg {
            position: relative;
            z-index: 1;
        }

        .main-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
            background-size: 400px;
            background-repeat: repeat;
            opacity: 0.05;
            z-index: -1;
            pointer-events: none;
        }

        /* Versi dark: pola batik digelapkan supaya tidak ada area putih mencolok */
        html[data-skin="dark"] .main-bg::before {
            opacity: 0.04;
            filter: grayscale(1) brightness(0.25);
        }

        /* === RESPONSIVE UTILITIES === */
        @media (max-width: 768px) {
            .main-app {
                padding: 1rem !important;
            }
        }

        @media (max-width: 576px) {
            .main-app {
                padding: 0.75rem !important;
            }
        }

        /* === GLOBAL SIZE CONTROL (Ukuran) – hanya konten utama, bukan sidebar === */
        html[data-size="small"] .main-app {
            font-size: 0.85rem;
            padding: 0.75rem !important;
        }

        html[data-size="medium"] .main-app {
            font-size: 1rem;
        }

        html[data-size="large"] .main-app {
            font-size: 1.15rem;
            padding: 1.75rem !important;
        }

        html[data-size="small"] .main-app .main-title {
            font-size: 1.1rem;
        }
        html[data-size="medium"] .main-app .main-title {
            font-size: 1.35rem;
        }
        html[data-size="large"] .main-app .main-title {
            font-size: 1.6rem;
        }

        /* Scale padding & components hanya di dalam main-app */
        html[data-size="small"] .main-app .card,
        html[data-size="small"] .main-app .modern-card {
            padding: 0.75rem 0.9rem;
        }
        html[data-size="medium"] .main-app .card,
        html[data-size="medium"] .main-app .modern-card {
            padding: 1.1rem 1.25rem;
        }
        html[data-size="large"] .main-app .card,
        html[data-size="large"] .main-app .modern-card {
            padding: 1.6rem 1.8rem;
        }

        html[data-size="small"] .main-app .form-control,
        html[data-size="small"] .main-app .form-select,
        html[data-size="small"] .main-app .btn {
            padding-top: 0.35rem;
            padding-bottom: 0.35rem;
        }
        html[data-size="large"] .main-app .form-control,
        html[data-size="large"] .main-app .form-select,
        html[data-size="large"] .main-app .btn {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        /* === SweetAlert2 popup styling for Satuan Kerja & Mentor === */
        .swal2-popup .swal-unit-mentor {
            text-align: left;
        }
        .swal-unit-mentor .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .swal-unit-mentor .form-select,
        .swal-unit-mentor .select2-container {
            width: 100% !important;
        }
        .swal-unit-mentor .select2-container .select2-selection--single {
            border-radius: 10px;
            min-height: 42px;
            display: flex;
            align-items: center;
            border-color: #e2e8f0;
        }
        .swal-unit-mentor .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 12px;
            font-size: 0.9rem;
        }
        .swal-unit-mentor .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }

        /* Page transition loader (modern) */
        .page-loading-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(1200px 500px at 20% 10%, rgba(176, 141, 72, 0.10), transparent 55%),
                radial-gradient(900px 450px at 85% 30%, rgba(99, 102, 241, 0.10), transparent 55%),
                rgba(248, 250, 252, 0.68);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 99999;
            opacity: 0;
            transition: opacity 160ms ease-in-out;
            pointer-events: all;
        }
        .page-loading-overlay.show {
            display: flex;
            opacity: 1;
        }

        .page-loading-card {
            width: min(520px, calc(100vw - 48px));
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: rgba(255, 255, 255, 0.84);
            box-shadow: 0 30px 90px rgba(15, 23, 42, 0.20);
            padding: 22px 22px 18px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            align-items: center;
            justify-content: center;
            text-align: center;
            transform: translateY(8px) scale(0.98);
            animation: page-loading-pop 220ms ease-out forwards;
        }
        @keyframes page-loading-pop {
            to { transform: translateY(0) scale(1); }
        }

        .page-loading-mark {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(176, 141, 72, 0.12);
            border: 1px solid rgba(176, 141, 72, 0.28);
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .page-loading-logo {
            width: 86px;
            height: auto;
            margin-bottom: 4px;
        }

        .page-loading-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2px;
        }
        .page-loading-dpr-logo {
            width: 46px;
            height: auto;
            flex-shrink: 0;
        }
        .page-loading-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
            align-items: flex-start;
        }
        .page-loading-brand-title {
            font-weight: 1000;
            letter-spacing: -0.4px;
            font-size: 1.25rem;
            color: #0f172a;
        }
        .page-loading-brand-sub {
            font-size: 0.92rem;
            color: #64748b;
            font-weight: 700;
            margin-top: 2px;
        }

        .page-loading-message {
            font-weight: 900;
            letter-spacing: -0.3px;
            font-size: 1.35rem;
            color: #0f172a;
        }

        .page-loading-submessage {
            font-size: 0.98rem;
            color: #475569;
            margin-top: 2px;
        }

        .page-loading-spinner {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            border: 3px solid rgba(176, 141, 72, 0.22);
            border-top-color: #b08d48;
            animation: page-loading-spin 0.75s linear infinite;
        }

        .page-loading-text {
            min-width: 0;
        }
        .page-loading-title {
            font-weight: 900;
            letter-spacing: -0.2px;
            color: #0f172a;
            line-height: 1.2;
        }
        .page-loading-subtitle {
            font-size: 0.88rem;
            color: #64748b;
            margin-top: 2px;
            line-height: 1.35;
        }
        .page-loading-dots::after {
            content: '';
            display: inline-block;
            width: 18px;
            text-align: left;
            margin-left: 2px;
            animation: page-loading-dots 1.1s steps(4, end) infinite;
        }

        .page-loading-bar {
            margin-top: 10px;
            height: 6px;
            border-radius: 999px;
            background: rgba(226, 232, 240, 0.8);
            overflow: hidden;
            position: relative;
        }
        .page-loading-bar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(176, 141, 72, 0.55), transparent);
            transform: translateX(-60%);
            animation: page-loading-sheen 1.05s ease-in-out infinite;
        }

        @keyframes page-loading-spin { to { transform: rotate(360deg); } }
        @keyframes page-loading-sheen {
            0% { transform: translateX(-70%); }
            100% { transform: translateX(70%); }
        }
        @keyframes page-loading-dots {
            0% { content: ''; }
            25% { content: '.'; }
            50% { content: '..'; }
            75% { content: '...'; }
            100% { content: ''; }
        }

        html[data-skin="dark"] .page-loading-overlay {
            background:
                radial-gradient(1200px 500px at 20% 10%, rgba(251, 191, 36, 0.10), transparent 55%),
                radial-gradient(900px 450px at 85% 30%, rgba(99, 102, 241, 0.12), transparent 55%),
                rgba(2, 6, 23, 0.72);
        }
        html[data-skin="dark"] .page-loading-card {
            background: rgba(15, 23, 42, 0.82);
            border-color: rgba(55, 65, 81, 0.95);
            box-shadow: 0 30px 90px rgba(0,0,0,0.55);
        }
        html[data-skin="dark"] .page-loading-mark {
            background: rgba(251, 191, 36, 0.10);
            border-color: rgba(251, 191, 36, 0.26);
        }
        html[data-skin="dark"] .page-loading-spinner {
            border-color: rgba(251, 191, 36, 0.18);
            border-top-color: #fbbf24;
        }
        html[data-skin="dark"] .page-loading-message { color: #ffffff; }
        html[data-skin="dark"] .page-loading-submessage { color: #cbd5e1; }
        html[data-skin="dark"] .page-loading-brand-title { color: #ffffff; }
        html[data-skin="dark"] .page-loading-brand-sub { color: #cbd5e1; }
        html[data-skin="dark"] .page-loading-bar { background: rgba(55, 65, 81, 0.7); }
        html[data-skin="dark"] .page-loading-bar::before {
            background: linear-gradient(90deg, transparent, rgba(251, 191, 36, 0.45), transparent);
        }
    </style>

    <script>
        (function () {
            try {
                var mode = localStorage.getItem('skin-mode');
                if (mode === 'dark') document.documentElement.setAttribute('data-skin', 'dark');
                else document.documentElement.setAttribute('data-skin', '');
            } catch (e) {}
        })();
    </script>

    {{-- Vite assets (dipakai untuk Tiptap/Materi dan future enhancements) --}}
    @vite(['Modules/Magang/resources/css/app.css', 'Modules/Magang/resources/js/app.js'])

    @livewireStyles
    @stack('styles')
</head>

<body id="konten">

    <div class="app-bg-pattern"></div>
    <div class="page-loading-overlay" id="pageLoadingOverlay" aria-hidden="true">
        <div class="page-loading-card" role="status" aria-live="polite" aria-label="Tolong menunggu sesaat">
            <div class="page-loading-brand">
                <img class="page-loading-dpr-logo" src="{{ asset('theme/admin-dashbyte/dist/assets/img/logo.png') }}" alt="DPR RI">
                <div class="page-loading-brand-text">
                    <div class="page-loading-brand-title">SMART</div>
                    <div class="page-loading-brand-sub">Sistem Magang Administratif, Responsif, dan Terintegrasi</div>
                </div>
            </div>
            <div class="page-loading-message">Tolong menunggu sesaat</div>
            <div class="page-loading-submessage" aria-hidden="true">Sedang memuat halaman...</div>
            <div class="page-loading-mark" aria-hidden="true">
                <div class="page-loading-spinner"></div>
            </div>
        </div>
    </div>

    @include('layouts.sidebar')
    @include('layouts.header')

    <!-- ✅ MAIN CONTENT -->
    <div class="main main-app p-3 p-lg-4 main-bg">
        @yield('content')
        @include('layouts.footer')
    </div>

    <!-- JS -->
    <script src="{{ asset('theme/admin-dashbyte/dist/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/admin-dashbyte/dist/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('theme/admin-dashbyte/dist/lib/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('theme/admin-dashbyte/dist/assets/js/script.js') }}"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $("table#tableGrid3").each(function () {
                $(this).DataTable({
                    searching: true,
                    paging: true,
                    ordering: true,
                    pageLength: 10,
                    responsive: true
                });

                $(this).addClass('table table-bordered table-striped table-hover');
                $(this).find('th, td').addClass('p-1');
            });

            // Toggle & close sidebar mobile sepenuhnya ditangani tema
            // (script.js: #menuSidebar -> body.sidebar-show, .main-backdrop -> close).
            // Handler kustom dihapus agar tidak desync dengan state tema.
        });

        // Global loading indicator saat pindah halaman
        (function initPageLoadingOverlay() {
            var overlay = document.getElementById('pageLoadingOverlay');
            if (!overlay) return;

            function showLoader() {
                // Paksa tampil agar sempat render sebelum pindah halaman
                overlay.style.display = 'flex';
                overlay.style.opacity = '1';
                overlay.classList.add('show');
                overlay.setAttribute('aria-hidden', 'false');
                // Force reflow
                void overlay.offsetHeight;
            }

            function hideLoader() {
                // Wajib reset inline style yang dipasang showLoader(). Jika hanya
                // class 'show' yang dilepas, inline display:flex/opacity:1 tetap
                // menempel sehingga overlay nyangkut — terutama saat halaman
                // dikembalikan dari bfcache browser (tombol Back).
                overlay.classList.remove('show');
                overlay.style.display = '';
                overlay.style.opacity = '';
                overlay.setAttribute('aria-hidden', 'true');
            }

            // pageshow menangani restore dari bfcache (Back/Forward); load untuk
            // navigasi biasa. Keduanya harus benar-benar menyembunyikan overlay.
            window.addEventListener('pageshow', hideLoader);
            window.addEventListener('load', hideLoader);

            document.addEventListener('click', function (e) {
                var link = e.target.closest('a[href]');
                if (!link) return;
                var href = link.getAttribute('href') || '';
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
                if (href.startsWith('mailto:') || href.startsWith('tel:')) return;
                if (link.target === '_blank' || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
                if (link.hasAttribute('download') || link.getAttribute('data-bs-toggle')) return;
                try {
                    var u = new URL(href, window.location.href);
                    if (u.origin !== window.location.origin) return;
                    var cur = new URL(window.location.href);
                    if (u.pathname === cur.pathname && u.search === cur.search) return;
                } catch (err) {
                    return;
                }
                showLoader();
            }, true);

            document.addEventListener('submit', function (e) {
                var form = e.target;
                if (!form || !(form instanceof HTMLFormElement)) return;
                if (form.target === '_blank') return;
                if (form.hasAttribute('data-no-global-loader')) return;
                if (e.defaultPrevented) return;
                showLoader();
            });

            window.addEventListener('beforeunload', function () {
                showLoader();
            });
        })();

        // Global guard: semua upload file minimal 200 KB
        (function initGlobalImageMinSizeGuard() {
            if (window.__globalImageMinSizeGuardInit) return;
            window.__globalImageMinSizeGuardInit = true;

            var minFileBytes = 200 * 1024; // 200 KB

            function notifyTooSmall(file) {
                var sizeKb = (file.size / 1024).toFixed(0);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Kecil',
                        html:
                            '<div style="text-align:left;line-height:1.6;">' +
                            '<div>Ukuran minimal file adalah <strong>200 KB</strong>.</div>' +
                            '<div>Ukuran file Anda: <strong>' + sizeKb + ' KB</strong>.</div>' +
                            '<div style="margin-top:8px;color:#64748b;font-size:13px;">Silakan pilih file lain dengan ukuran yang sesuai.</div>' +
                            '</div>',
                        confirmButtonText: 'Pilih Ulang',
                        confirmButtonColor: '#2563eb'
                    });
                } else {
                    alert('Ukuran file terlalu kecil. Ukuran minimal 200 KB. File ini: ' + sizeKb + ' KB.');
                }
            }

            document.addEventListener('change', function (e) {
                var input = e.target;
                if (!input || input.tagName !== 'INPUT' || input.type !== 'file') return;
                if (!input.files || !input.files.length) return;

                var firstFile = input.files[0];
                if (firstFile.size < minFileBytes) {
                    notifyTooSmall(firstFile);
                    input.value = '';
                }
            }, true);
        })();

        // Pengumuman broadcast peserta: badge, dropdown daftar, popup otomatis
        @if(auth()->user()?->roles === 'peserta')
        (function initPesertaBroadcastAnnouncementsUi() {
            if (window.__pesertaBroadcastAnnouncementsUiInit) return;
            window.__pesertaBroadcastAnnouncementsUiInit = true;

            const pendingUrl = "{{ route('peserta.announcements.pending') }}";
            const readUrl = "{{ route('peserta.announcements.read') }}";
            const unreadCountUrl = "{{ route('peserta.announcements.unread-count') }}";
            const listUrl = "{{ route('peserta.announcements.list') }}";

            window.__pesertaAnnouncementItemCache = {};

            function escHtml(s) {
                return String(s == null ? '' : s)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            async function fetchPending() {
                try {
                    const res = await fetch(pendingUrl, {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });
                    const data = await res.json().catch(() => null);
                    if (!res.ok || !data || !data.success) return null;
                    return data.data || null;
                } catch (e) {
                    return null;
                }
            }

            async function markRead(recipientId) {
                try {
                    await fetch(readUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ recipient_id: recipientId })
                    });
                } catch (e) {}
            }

            function setBadgeCount(n) {
                const el = document.getElementById('pesertaAnnouncementBadge');
                const btn = document.getElementById('pesertaAnnouncementNotifBtn');
                if (!el) return;
                if (n > 0) {
                    el.style.display = '';
                    el.textContent = n > 99 ? '99+' : String(n);
                    if (btn) btn.classList.add('peserta-announcement-has-unread');
                } else {
                    el.style.display = 'none';
                    if (btn) btn.classList.remove('peserta-announcement-has-unread');
                }
            }

            window.__pesertaAnnouncementRefreshBadge = async function () {
                try {
                    const res = await fetch(unreadCountUrl, {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });
                    const data = await res.json().catch(() => null);
                    if (!res.ok || !data || !data.success) return;
                    setBadgeCount(parseInt(data.unread_count, 10) || 0);
                } catch (e) {}
            };

            async function showAnnouncementPopup(pending) {
                if (typeof Swal === 'undefined' || !pending || !pending.recipient_id) return false;
                const result = await Swal.fire({
                    title: '',
                    html:
                        '<div class="peserta-broadcast-modal" style="text-align:left;">' +
                            '<div class="peserta-broadcast-modal__top">' +
                                '<div class="peserta-broadcast-modal__badge">' +
                                    '<i class="ri-notification-3-line" style="font-size:15px;"></i>' +
                                    '<span>Pengumuman Baru</span>' +
                                '</div>' +
                                '<span class="peserta-broadcast-modal__brand">SIAP-MAGANG</span>' +
                            '</div>' +
                            '<h3 class="peserta-broadcast-modal__title">' +
                                escHtml(pending.subject || 'Pengumuman') +
                            '</h3>' +
                            '<div class="peserta-broadcast-modal__body">' +
                                (pending.content_html || '') +
                            '</div>' +
                            '<p class="peserta-broadcast-modal__hint">' +
                                'Pengumuman tetap tampil di ikon notifikasi sampai Anda menekan tombol <strong>Saya sudah membaca</strong> di bawah.' +
                            '</p>' +
                        '</div>',
                    confirmButtonText: 'Saya sudah membaca',
                    confirmButtonColor: '#b08d48',
                    showCloseButton: true,
                    width: 'min(96vw, 1000px)',
                    customClass: {
                        container: 'swal-nav-safe',
                        popup: 'swal2-broadcast-popup',
                        closeButton: 'swal2-broadcast-close',
                        htmlContainer: 'swal2-broadcast-html-container'
                    }
                });
                // Hanya tandai dibaca jika peserta menekan tombol konfirmasi (bukan X, klik luar, atau Esc)
                if (result.isConfirmed) {
                    await markRead(pending.recipient_id);
                    if (window.__pesertaAnnouncementRefreshBadge) {
                        await window.__pesertaAnnouncementRefreshBadge();
                    }
                    return true;
                }
                return false;
            }

            async function loadAnnouncementDropdownList() {
                const container = document.getElementById('pesertaAnnouncementList');
                if (!container) return;
                container.innerHTML = '<div class="px-3 py-3 text-center text-muted small">Memuat…</div>';
                window.__pesertaAnnouncementItemCache = {};
                try {
                    const res = await fetch(listUrl + '?limit=15', {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });
                    const data = await res.json().catch(() => null);
                    if (!res.ok || !data || !data.success) {
                        container.innerHTML = '<div class="px-3 py-4 text-center text-danger small">Gagal memuat pengumuman.</div>';
                        return;
                    }
                    const items = data.items || [];
                    if (!items.length) {
                        container.innerHTML = '<div class="px-3 py-4 text-center text-muted small">Tidak ada pengumuman belum dibaca.</div>';
                        return;
                    }
                    items.forEach(function (it) {
                        window.__pesertaAnnouncementItemCache[it.recipient_id] = it;
                    });
                    container.innerHTML = items.map(function (it) {
                        return (
                            '<button type="button" class="dropdown-item peserta-announcement-item w-100" data-recipient-id="' +
                            String(it.recipient_id) +
                            '">' +
                            '<div class="peserta-announcement-item-subject">' + escHtml(it.subject || 'Pengumuman') + '</div>' +
                            '<div class="peserta-announcement-item-preview">' + escHtml(it.preview || '') + '</div>' +
                            '</button>'
                        );
                    }).join('');
                } catch (e) {
                    container.innerHTML = '<div class="px-3 py-4 text-center text-danger small">Gagal memuat pengumuman.</div>';
                }
            }

            document.addEventListener('DOMContentLoaded', async function () {
                if (window.__pesertaAnnouncementRefreshBadge) {
                    await window.__pesertaAnnouncementRefreshBadge();
                }

                const listEl = document.getElementById('pesertaAnnouncementList');
                const notifBtn = document.getElementById('pesertaAnnouncementNotifBtn');
                const ddRoot = document.querySelector('.dropdown-peserta-announcements');

                if (ddRoot && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                    ddRoot.addEventListener('shown.bs.dropdown', function () {
                        loadAnnouncementDropdownList();
                    });
                }

                if (listEl) {
                    listEl.addEventListener('click', async function (e) {
                        const row = e.target.closest('.peserta-announcement-item');
                        if (!row || typeof Swal === 'undefined') return;
                        e.preventDefault();
                        e.stopPropagation();
                        const id = parseInt(row.getAttribute('data-recipient-id'), 10);
                        const item = window.__pesertaAnnouncementItemCache[id];
                        if (!item) return;
                        if (notifBtn && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                            const inst = bootstrap.Dropdown.getInstance(notifBtn);
                            if (inst) inst.hide();
                        }
                        await showAnnouncementPopup({
                            recipient_id: item.recipient_id,
                            subject: item.subject,
                            content_html: item.content_html
                        });
                        await loadAnnouncementDropdownList();
                    });
                }

                if (typeof Swal === 'undefined') return;
                const pending = await fetchPending();
                if (!pending || !pending.recipient_id) return;
                await showAnnouncementPopup(pending);
            });
        })();
        @endif
    </script>

    @stack('script')

    @livewireScripts

</body>
</html>
