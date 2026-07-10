<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Remix Icon: dipakai oleh tombol toggle dark mode (ri-sun-fill / ri-moon-fill) di header bersama --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.ico') }}" type="image/x-icon">
    <!-- reCAPTCHA v2 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>{{ config('app.name') }} - Form Pendaftaran</title>
    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-gold: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%);
            --gold-solid: #b08d48;
            --gold-light: #fdfae9;
            --text-main: #334155;
            --text-muted: #64748b;
            --white: #ffffff;
            --bg-body: #f8fafc;
            --border-color: #e2e8f0;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --danger: #ef4444;
            --success: #10b981;
            --header-height: 80px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }

        body {
          font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
        }

        /* ============================
           Detail Lowongan (di atas form)
           ============================ */
        .lowongan-detail-card {
            background: white;
            border: 1px solid rgba(176, 141, 72, 0.35);
            border-radius: 20px;
            padding: 22px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
            margin-bottom: 22px;
        }

        .lowongan-detail-grid {
            display: grid;
            grid-template-columns: 160px 1fr 240px;
            gap: 22px;
            align-items: start;
        }

        .lowongan-logo-box {
            width: 160px;
            height: 160px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lowongan-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .lowongan-logo-placeholder {
            font-size: 3rem;
            color: var(--gold-solid);
            opacity: 0.6;
        }

        .lowongan-detail-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin: 0 0 10px 0;
            line-height: 1.25;
        }

        .lowongan-meta {
            display: grid;
            gap: 8px;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .lowongan-meta i {
            color: var(--gold-solid);
            width: 18px;
        }

        .lowongan-badges {
            display: grid;
            gap: 12px;
        }

        .lowongan-badge {
            background: var(--gold-light);
            border: 1px solid rgba(176, 141, 72, 0.2);
            padding: 14px 14px;
            border-radius: 16px;
        }

        .lowongan-badge .lbl {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--gold-solid);
            margin-bottom: 6px;
        }

        .lowongan-badge .val {
            display: block;
            font-weight: 800;
            color: var(--primary-dark);
            font-size: 0.95rem;
        }

        .lowongan-detail-divider {
            height: 4px;
            border-radius: 8px;
            background: var(--gold-solid);
            border: none;
            margin: 18px 0 18px 0;
        }

        .lowongan-detail-desc-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin: 0 0 10px 0;
        }

        .lowongan-detail-desc {
            color: var(--text-main);
            line-height: 1.8;
            font-size: 0.98rem;
        }

        .lowongan-detail-desc ul,
        .lowongan-detail-desc ol {
            padding-left: 20px;
        }

        @media (max-width: 992px) {
            .lowongan-detail-grid {
                grid-template-columns: 1fr;
            }
            .lowongan-logo-box {
                margin: 0 auto;
            }
            .lowongan-badges {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .lowongan-badges {
                grid-template-columns: 1fr;
            }
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
            opacity: 0.03;
            z-index: -1;
            pointer-events: none;
        }

        header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            height: var(--header-height);
            padding: 0 6%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0px;
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
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .logo-text span {
            display: block;
            font-size: 0.7rem;
            color: var(--gold-solid);
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .page-wrapper {
            max-width: 1300px;
            margin: calc(var(--header-height) + 40px) auto 60px;
            padding: 0 25px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 40px;
        }

        /* Mobile: sidebar jadi menu samping (off-canvas) */
        .mobile-menu-btn {
            display: none;
            border: 1px solid rgba(176, 141, 72, 0.35);
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary-dark);
            width: 44px;
            height: 44px;
            border-radius: 14px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .mobile-menu-btn:hover {
            transform: translateY(-1px);
            border-color: var(--gold-solid);
            box-shadow: 0 10px 20px rgba(142, 109, 47, 0.12);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(3px);
            z-index: 1999;
            display: none;
        }

        body.sidebar-open .sidebar-overlay {
            display: block;
        }

        @media (max-width: 992px) {
            header {
                padding: 0.8rem 4%;
                justify-content: space-between;
                gap: 12px;
            }

            .mobile-menu-btn { display: inline-flex; }

            .page-wrapper {
                grid-template-columns: 1fr;
                gap: 18px;
                padding: 0 16px;
            }

            /* Di mobile: sidebar langkah ditampilkan inline (stacked) di atas form,
               tidak lagi off-canvas. Menu situs (hamburger) ditangani header publik. */
            .sidebar-nav {
                position: static;
                width: auto;
                max-width: none;
                height: auto;
                transform: none;
                padding-top: 30px;
            }

            .sidebar-nav .btn-return-home {
                margin-bottom: 16px;
            }

            /* Biar konten tidak ketutup header */
            .form-content { padding-top: 0; }
        }

        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px;
            background: #f8fafc;
            color: var(--text-main);
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
            transition: var(--transition);
            border: 1px solid #e2e8f0;
        }

        .btn-back:hover {
            background: white;
            border-color: var(--gold-solid);
            color: var(--gold-solid);
            transform: translateY(-2px);
        }

        .container {
            width: 100%;
            margin: 0;
            display: block;
            padding: 0;
            align-items: start;
        }


        .sidebar-nav {
            height: fit-content;
            background: white;
            padding: 30px 20px;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            border: 1px solid rgba(176, 141, 72, 0.35);
        }

        /* Sticky HANYA di desktop. Di mobile (<=992px) sidebar harus mengalir statis
           di atas form (lihat media query di atas); kalau sticky diset di rule dasar ini,
           ia menang karena sumbernya lebih akhir daripada override mobile, sehingga
           sidebar 'Alur Form' menimpa kartu form di tampilan mobile. */
        @media (min-width: 993px) {
            .sidebar-nav {
                position: sticky;
                top: calc(var(--header-height) + 40px);
            }
        }

        .nav-card {
            background: transparent;
            padding: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .nav-card h4 {
            margin-bottom: 20px;
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 800;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--gold-solid);
            position: relative;
        }

        .nav-card h4::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--gold-solid);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px;
            text-decoration: none;
            color: var(--text-muted);
            border-radius: 16px;
            margin-bottom: 8px;
            transition: var(--transition);
            font-weight: 700;
            cursor: pointer;
            position: relative;
        }

        /* Hapus garis vertikal di kiri */
        .nav-item::before {
            display: none;
        }

        .step-num {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f1f5f9;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            transition: var(--transition);
            flex-shrink: 0;
            border: 2px solid #e2e8f0;
            position: relative;
        }

        /* Garis alur penghubung antar step */
        .nav-item:not(:last-child) .step-num::after {
            content: '';
            position: absolute;
            bottom: -24px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: #e2e8f0;
            transition: var(--transition);
        }

        /* Garis alur menjadi emas jika step sebelumnya aktif */
        .nav-item.active ~ .nav-item .step-num::after,
        .nav-item.active .step-num::after {
            background: var(--gold-solid);
        }

        .step-text {
            flex: 1;
            font-size: 0.9rem;
        }

        .nav-item:hover {
            background: rgba(176, 141, 72, 0.05);
        }

        .nav-item:hover .step-num {
            background: rgba(176, 141, 72, 0.1);
            border-color: rgba(176, 141, 72, 0.3);
            transform: scale(1.05);
        }

        .nav-item.active {
            background: var(--gold-light);
            color: var(--gold-solid);
        }

        .nav-item.active .step-num {
            background: var(--gold-solid);
            color: white;
            border-color: var(--gold-solid);
            box-shadow: 0 0 0 4px rgba(176, 141, 72, 0.2);
        }

        /* Bulatan di sekitar angka aktif */
        .nav-item.active .step-num::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px solid var(--gold-solid);
            opacity: 0.4;
            animation: pulse-ring 2s ease-out infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(1);
                opacity: 0.4;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.2;
            }
            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

         .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-header i {
            color: var(--gold-solid);
            font-size: 1.4rem;
        }

        .grid-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 8px;
            color: var(--primary-dark);
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            transition: var(--transition);
        }

        input:focus {
            outline: none;
            border-color:
            var(--gold-solid);
            background: #fff; }

        .error-msg {
            color: #d9534f;
            font-size: 0.75rem;
            margin-top: 5px;
            display: block;
            font-weight: 600;
         }

        /* File Upload Styling */
        .upload-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 10px;
        }

        /* Upload box style */
        .upload-box {
            position: relative;
            border: 2.5px dashed #cbd5e1;
            padding: 25px 15px;
            border-radius: 25px;
            text-align: center;
            transition: var(--transition);
            background: #f8fafc;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .upload-box:hover {
            border-color: var(--gold-solid);
            background: #fffbeb;
            transform: translateY(-3px);
        }

        .upload-box i.main-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #94a3b8;
            transition: var(--transition);
            display: block;
        }

        .upload-box.has-file {
            border: 2.5px solid var(--success);
            background: #f0fdf4;
        }

        .upload-box.has-file i.main-icon {
            display: none;
        }

        .success-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--success);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            z-index: 5;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        .has-file .success-badge {
            display: flex;
        }

        .upload-box .upload-text {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--primary-dark);
        }

        .upload-box .file-info {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .upload-box input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            z-index: 10;
            cursor: pointer;
        }

        /* Preview Container */
        .preview-container {
            display: none;
            width: 100%;
            height: 100%;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .has-file .preview-container {
            display: flex;
        }

        .preview-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 15px;
            border: 3px solid white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .preview-pdf {
            width: 90%;
            height: 200px;
            border-radius: 10px;
            border: none;
            pointer-events: none;
        }

        .badge-type {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-top: 8px;
        }
        .badge-pdf { background: #fee2e2; color: #dc2626; }
        .badge-img { background: #e0f2fe; color: #0284c7; }

        .section-subtitle { color: var(--text-muted); font-size: 0.95rem; margin-top: 6px; margin-bottom: 12px; text-align: left; }

        /* Tautan template / contoh berkas pendukung (opsional, diatur superadmin) */
        .template-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: -2px 0 8px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--gold-solid);
            text-decoration: none;
            transition: var(--transition);
        }
        .template-link:hover { color: #8e6d2f; text-decoration: underline; }
        .template-link i { font-size: 0.85rem; }
        html[data-skin="dark"] .template-link { color: #fbbf24 !important; }
        html[data-skin="dark"] .template-link:hover { color: #fcd34d !important; }

        /* Submit button wrapper - matches grid-form layout */
        .btn-submit-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }
        .btn-submit-wrapper .btn-submit {
            grid-column: span 2;
            width: 100%;
            margin: 0;
        }

        @media (max-width: 992px) {
            .btn-submit-wrapper { grid-template-columns: 1fr; }
            .btn-submit-wrapper .btn-submit { grid-column: span 1; }
        }

        .persyaratan-title {
            background-color: #ffffff!important;
        }

        /* Disclaimer Perlindungan Data Pribadi */
        .disclaimer-ppdp { margin-top: 2rem; margin-bottom: 1rem; }
        .disclaimer-box {
            background: var(--gold-light, #fdfae9);
            border: 1px solid rgba(176, 141, 72, 0.25);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
        }
        .disclaimer-title { font-size: 0.95rem; color: var(--primary-dark, #0f172a); margin: 0 0 8px 0; }
        .disclaimer-text { font-size: 0.875rem; color: var(--text-main, #334155); line-height: 1.6; margin: 0 0 1rem 0; text-align: justify; text-justify: inter-word; }
        .disclaimer-checkbox {
            display: flex; align-items: flex-start; gap: 10px; cursor: pointer;
            font-size: 0.9rem; font-weight: 600; color: var(--primary-dark, #0f172a);
        }
        .disclaimer-checkbox input[type="checkbox"] { width: 20px; height: 20px; margin-top: 2px; flex-shrink: 0; cursor: pointer; }

        .btn-submit {
            background: var(--accent-gold);
            color: white;
            padding: 20px;
            width: 100%;
            border: none;
            border-radius: 14px;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 10px 25px rgba(142, 109, 47, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(142, 109, 47, 0.3);
        }
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .btn-submit:disabled:hover {
            transform: none;
            box-shadow: 0 10px 25px rgba(142, 109, 47, 0.2);
        }

        @media (max-width: 992px) {
            .container { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .grid-form { grid-template-columns: 1fr; }
        }

        .form-section {
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            margin-bottom: 25px;
            border: 1px solid rgba(176, 141, 72, 0.35);
        }

        .main-container {
            max-width: 920px;
            margin: 120px auto 40px;
            padding: 0 12px;
        }

        .form-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.04);
            margin-bottom: 16px;
            width: 100%;
            border: 1px solid #e2e8f0;
        }

        .form-card h2 {
            text-align: center;
            margin-bottom: 40px;
            color: var(--primary-dark);
            font-weight: 800;
            border-bottom: 3px solid var(--gold-solid);
            padding-bottom: 15px;
            position: relative;
        }

        .section-title {
            display: flex;
            align-items: center;
            margin: 40px 0 25px;
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 1.1rem;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(176, 141, 72, 0.45);
        }

        .section-title i {
            margin-right: 12px;
            color: var(--gold-solid);
            font-size: 1.2rem;
        }

        .section-title h3 {
            margin: 0;
            font-weight: 700;
            color: var(--primary-dark);
        }


        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9em;
        }

        .label-required::after {
            content: " *";
            color: #dc2626;
            font-weight: 700;
            font-size: 0.9em;
        }

        .field-invalid {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;
        }
        .field-invalid:focus {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.18) !important;
        }

        /* Teks error (client & server) pakai bintang */
        .client-error-message,
        .server-error-message {
            display: block;
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        .client-error-message::before,
        .server-error-message::before {
            content: "* ";
            font-weight: 800;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--gold-solid);
            box-shadow: 0 0 0 3px rgba(176, 141, 72, 0.1);
        }

        /* Requirements Section */
        .section {
            margin: 30px 0;
            padding: 25px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid var(--gold-solid);
            border-top: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            display: block;
            position: relative;
        }

        .section::before {
            content: '';
            position: absolute;
            top: -30px;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-solid), transparent);
            display: block;
        }

        .section:first-of-type::before {
            display: none;
        }

        .section h1 {
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--gold-solid);
            position: relative;
        }

        .section h1::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: var(--gold-solid);
        }

        .section ul {
            list-style: none;
            padding: 0;
        }

        .section ul li {
            padding: 12px 0;
            padding-left: 25px;
            position: relative;
            color: var(--text-main);
            border-bottom: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .section ul li:last-child {
            border-bottom: none;
        }

        .section ul li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--gold-solid);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .section ul li:hover {
            padding-left: 30px;
            background: rgba(176, 141, 72, 0.05);
            margin: 0 -15px;
            padding-left: 35px;
            padding-right: 15px;
            border-radius: 6px;
        }

        /* Modal Konfirmasi */
        .confirm-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }

        .confirm-modal.show {
            display: flex;
        }

        .confirm-modal-content {
            background: white;
            padding: 2.5rem;
            border-radius: 24px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(17, 0, 0, 0.15);
            animation: slideUp 0.4s ease-out;
            position: relative;
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

        .confirm-icon {
            width: 70px;
            height: 70px;
            background: var(--accent-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .confirm-modal-content h3 {
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }

        .confirm-modal-content p {
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .confirm-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        /* Loading setelah konfirmasi submit (untuk koneksi lambat) */
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
            margin: 0 0 6px 0;
        }

        .loading-subtitle {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
            line-height: 1.45;
        }

        .btn-confirm {
            padding: 12px 30px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-confirm-yes {
            background: var(--accent-gold);
            color: white;
            box-shadow: 0 4px 15px rgba(142, 109, 47, 0.2);
        }

        .btn-confirm-yes:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(142, 109, 47, 0.3);
        }

        .btn-confirm-no {
            background: #f1f5f9;
            color: var(--text-main);
            border: 1.5px solid #e2e8f0;
        }

        .btn-confirm-no:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .btn-submit {
            background: var(--accent-gold);
            color: white;
            padding: 20px;
            width: 100%;
            border: none;
            border-radius: 14px;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 10px 25px rgba(142, 109, 47, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 50px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(142, 109, 47, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--accent-gold);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(142, 109, 47, 0.3);
            z-index: 999;
            transition: var(--transition);
        }

        .back-to-top.show {
            display: flex;
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(142, 109, 47, 0.4);
        }

        .highlight-note {
            background-color: #fff3f3;
            border-left: 4px solid #d9534f;
            color: #d9534f;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        label {
            color: var(--text-main);
            font-weight: 600;
        }

        select option {
            padding: 10px;
        }

        .scrollable-info {
            max-height: 300px;
            overflow-y: auto;
        }

        @media (max-width: 600px) {
            .grid-form { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
        }

        /* Button Return Home */
        .btn-return-home {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 30px;
            padding: 15px;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            border-top: 1px solid var(--border-color);
            transition: var(--transition);
            border-radius: 10px;
        }

        .btn-return-home i {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .btn-return-home:hover {
            color: var(--gold-solid);
            background: rgba(176, 141, 72, 0.05);
        }

        .btn-return-home:hover i {
            transform: translateX(-5px);
        }

        /* === Dark mode: register konsisten === */
        html[data-skin="dark"] body { background: #020617 !important; color: #ffffff !important; }
        html[data-skin="dark"] .bg-pattern { opacity: 0.03 !important; }
        html[data-skin="dark"] header {
            background: rgba(15, 23, 42, 0.95) !important;
            border-color: #1f2937 !important;
        }
        html[data-skin="dark"] .logo-text { color: #ffffff !important; }
        html[data-skin="dark"] .logo-text span { color: #fbbf24 !important; }
        html[data-skin="dark"] .mobile-menu-btn { background: #1e293b !important; border-color: #374151 !important; color: #ffffff !important; }
        html[data-skin="dark"] .sidebar-overlay { background: rgba(2, 6, 23, 0.8) !important; }
        html[data-skin="dark"] .sidebar-nav,
        html[data-skin="dark"] .nav-card,
        html[data-skin="dark"] .lowongan-detail-card,
        html[data-skin="dark"] .form-section,
        html[data-skin="dark"] .form-card,
        html[data-skin="dark"] .main-container .section,
        html[data-skin="dark"] .page-wrapper {
            background: #0f172a !important;
            border-color: rgba(251, 191, 36, 0.45) !important;
        }
        html[data-skin="dark"] .lowongan-logo-box { background: #1e293b !important; border-color: #374151 !important; }
        html[data-skin="dark"] .lowongan-badge { background: #1e293b !important; border-color: #374151 !important; }
        html[data-skin="dark"] .lowongan-detail-title,
        html[data-skin="dark"] .lowongan-detail-desc-title,
        html[data-skin="dark"] .lowongan-badge .val,
        html[data-skin="dark"] h1, html[data-skin="dark"] h2, html[data-skin="dark"] h3, html[data-skin="dark"] h4 ,
        html[data-skin="dark"] .lowongan-detail-desc { color: #ffffff !important; }
        html[data-skin="dark"] .form-group label,
        html[data-skin="dark"] .section-header h3,
        html[data-skin="dark"] .section-title,
        html[data-skin="dark"] .section-title h3,
        html[data-skin="dark"] .disclaimer-title,
        html[data-skin="dark"] .disclaimer-checkbox,
        html[data-skin="dark"] .form-card h2 { color: #ffffff !important; }
        html[data-skin="dark"] .lowongan-meta,
        html[data-skin="dark"] .lowongan-badge .lbl,
        html[data-skin="dark"] .form-group .hint,
        html[data-skin="dark"] .section-subtitle,
        html[data-skin="dark"] .disclaimer-text,
        html[data-skin="dark"] .nav-card h4 { color: #9ca3af !important; }
        html[data-skin="dark"] .section-header,
        html[data-skin="dark"] .section-title { border-color: #374151 !important; }
        html[data-skin="dark"] input,
        html[data-skin="dark"] select,
        html[data-skin="dark"] textarea {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] input:focus,
        html[data-skin="dark"] select:focus,
        html[data-skin="dark"] textarea:focus { background: #0f172a !important; }
        html[data-skin="dark"] input::placeholder,
        html[data-skin="dark"] textarea::placeholder { color: #9ca3af !important; }
        html[data-skin="dark"] select option { background: #1e293b !important; color: #ffffff !important; }

        /* Dark mode: buat icon kalender di input[type=date] jadi putih */
        html[data-skin="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(1.4);
        }
        html[data-skin="dark"] input[type="date"]::-moz-calendar-picker-indicator {
            filter: invert(1) brightness(1.4);
        }
        html[data-skin="dark"] .upload-box {
            background: #1e293b !important;
            border-color: #374151 !important;
        }
        html[data-skin="dark"] .upload-box:hover {
            background: #0f172a !important;
            border-color: #fbbf24 !important;
        }
        html[data-skin="dark"] .upload-box.has-file {
            background: #052e16 !important;
            border-color: #22c55e !important;
        }
        html[data-skin="dark"] .upload-box .upload-text,
        html[data-skin="dark"] .upload-box i.main-icon { color: #fbbf24 !important; }
        html[data-skin="dark"] .upload-box .file-info { color: #9ca3af !important; }
        html[data-skin="dark"] .preview-img { border-color: #374151 !important; }
        html[data-skin="dark"] .badge-pdf { background: #450a0a !important; color: #f87171 !important; }
        html[data-skin="dark"] .badge-img { background: #1e3a5f !important; color: #93c5fd !important; }
        html[data-skin="dark"] .disclaimer-box {
            background: #1e293b !important;
            border-color: #374151 !important;
        }
        html[data-skin="dark"] .section { background: #0f172a !important; border-color: rgba(251, 191, 36, 0.35) !important; }
        html[data-skin="dark"] .section h1 { color: #ffffff !important; border-color: #374151 !important; }
        html[data-skin="dark"] .section ul li { color: #ffffff !important; border-color: #1f2937 !important; }
        html[data-skin="dark"] .section ul li:hover { background: rgba(251, 191, 36, 0.1) !important; }
        html[data-skin="dark"] .step-num {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .nav-item .step-num::after { background: #374151 !important; }
        html[data-skin="dark"] .nav-item.active .step-num::after { background: #fbbf24 !important; }
        html[data-skin="dark"] .nav-item { color: #ffffff !important; }
        html[data-skin="dark"] .nav-item:hover { background: rgba(251, 191, 36, 0.1) !important; color: #fbbf24 !important; }
        html[data-skin="dark"] .nav-item.active {
            background: rgba(251, 191, 36, 0.15) !important;
            color: #fbbf24 !important;
        }
        html[data-skin="dark"] .nav-item.active .step-num {
            background: #fbbf24 !important;
            color: #020617 !important;
            border-color: #fbbf24 !important;
        }
        html[data-skin="dark"] .btn-return-home { color: #9ca3af !important; border-color: #1f2937 !important; }
        html[data-skin="dark"] .btn-return-home:hover { color: #fbbf24 !important; background: rgba(251, 191, 36, 0.1) !important; }
        html[data-skin="dark"] .btn-back {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .btn-back:hover {
            background: #0f172a !important;
            border-color: #fbbf24 !important;
            color: #fbbf24 !important;
        }
        html[data-skin="dark"] .confirm-modal-content,
        html[data-skin="dark"] .loading-modal-content {
            background: #0f172a !important;
            border-color: #1f2937 !important;
        }
        html[data-skin="dark"] .confirm-modal-content h3,
        html[data-skin="dark"] .loading-title { color: #ffffff !important; }
        html[data-skin="dark"] .confirm-modal-content p,
        html[data-skin="dark"] .loading-subtitle { color: #9ca3af !important; }
        html[data-skin="dark"] .btn-confirm-no {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .btn-confirm-no:hover {
            background: #0f172a !important;
            border-color: #fbbf24 !important;
        }
        html[data-skin="dark"] .scrollable-info { background: #1e293b !important; }
        html[data-skin="dark"] .client-error-message,
        html[data-skin="dark"] .server-error-message { color: #f87171 !important; }
        html[data-skin="dark"] .persyaratan-title {  background-color: var(--primary-dark) !important;}
    </style>

    {{-- Header & footer publik bersama dengan beranda/FAQ (CSS + skin toggle) --}}
    @include('auth.partials.public-chrome-head')
</head>

<body>
    @include('partials.global_page_loader')
    <div class="bg-pattern"></div>

    @include('auth.partials.public-header')

 <div class="page-wrapper">
        <aside class="sidebar-nav" id="registerSidebar">
            <div class="nav-card">
                <h4>Detail Lowongan</h4>
                <a href="#detailLowongan" class="nav-item active">
                    <span class="step-num">1</span>
                    <span class="step-text">Detail Lowongan</span>
                </a>
            </div>

            <div class="nav-card">
                <h4>Alur Form</h4>
                <a href="#section1" class="nav-item">
                    <span class="step-num">1</span>
                    <span class="step-text">Data Diri</span>
                </a>
                <a href="#section2" class="nav-item">
                    <span class="step-num">2</span>
                    <span class="step-text">Data Pendidikan</span>
                </a>
                <a href="#section3" class="nav-item">
                    <span class="step-num">3</span>
                    <span class="step-text">Berkas Pendukung</span>
                </a>
            </div>

            <a href="{{ route('Halaman awal') }}" class="btn-return-home">
                <i class="fas fa-house-chimney"></i>
                <span>Beranda Utama</span>
            </a>
        </aside>



    <main class="form-content">
        {{-- Detail Lowongan (Dipisah dari Form Card) --}}
        <div class="section-container" id="detailLowongan" style="margin-bottom: 25px;">
            <div class="section-title">
                <i class="fas fa-briefcase"></i> <h3>Detail Lowongan</h3>
            </div>

            <div class="lowongan-detail-card">
                <div class="lowongan-detail-grid">
                    <div class="lowongan-logo-box">
                        @if(isset($lowonganSelected) && !empty($lowonganSelected->foto))
                            <img src="{{ file_url('lowongan/' . $lowonganSelected->foto) }}" alt="{{ $lowonganSelected->title }}"
                                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div class=&quot;lowongan-logo-placeholder&quot;><i class=&quot;fas fa-briefcase&quot;></i></div>';">
                        @else
                            <div class="lowongan-logo-placeholder">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        @endif
                    </div>

                    <div>
                        @php
                            $judulLowongan = isset($lowonganSelected) && !empty($lowonganSelected->title)
                                ? $lowonganSelected->title
                                : 'Lowongan Umum';
                            $kebutuhanText = isset($lowonganSelected) && !is_null($lowonganSelected->jumlah_posisi)
                                ? $lowonganSelected->jumlah_posisi . ' Peserta'
                                : '-';
                            $deadlineText = isset($lowonganSelected) && $lowonganSelected->deadline
                                ? $lowonganSelected->deadline->locale('id')->translatedFormat('d F Y')
                                : '-';
                            $publishedText = isset($lowonganSelected) && $lowonganSelected->created_at
                                ? $lowonganSelected->created_at->locale('id')->translatedFormat('d F Y')
                                : '-';
                            $detailLowonganHtml = isset($lowonganSelected) && !empty($lowonganSelected->detail)
                                ? $lowonganSelected->detail
                                : '<em>Detail lowongan tidak tersedia.</em>';
                        @endphp

                        <h3 class="lowongan-detail-title">{{ $judulLowongan }}</h3>
                        <div class="lowongan-meta">
                            <div><i class="fas fa-users"></i> Kuota: {{ $kebutuhanText }}</div>
                            @if(isset($lowonganSelected))
                                <div><i class="fas fa-user-check"></i> Pelamar aktif: {{ $lowonganSelected->pelamar_proses_count ?? 0 }}</div>
                            @endif
                        </div>
                        @if(isset($lowonganSelected))
                            @php $lowJenisList = $lowonganSelected->jenisList(); @endphp
                            <div style="display:flex; gap:6px; flex-wrap:wrap; margin-top:10px;">
                                <span style="font-size:0.78rem; color:var(--text-muted); align-self:center;">Untuk:</span>
                                @foreach($lowJenisList as $jenis)
                                    @if($jenis === 'Magang')
                                        <span style="display:inline-flex; align-items:center; gap:4px; font-size:0.74rem; font-weight:800; padding:3px 10px; border-radius:999px; background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe;"><i class="fas fa-graduation-cap"></i> Magang (Mahasiswa)</span>
                                    @else
                                        <span style="display:inline-flex; align-items:center; gap:4px; font-size:0.74rem; font-weight:800; padding:3px 10px; border-radius:999px; background:#ecfdf5; color:#047857; border:1px solid #a7f3d0;"><i class="fas fa-school"></i> PKL (Siswa SMK)</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="lowongan-badges">
                        <div class="lowongan-badge">
                            <span class="lbl">Batas Lamaran</span>
                            <span class="val">{{ $deadlineText }}</span>
                        </div>
                        <div class="lowongan-badge" style="background:#f1f5f9;border-color:#e2e8f0;">
                            <span class="lbl" style="color: var(--text-muted);">Dipublikasikan</span>
                            <span class="val">{{ $publishedText }}</span>
                        </div>
                    </div>
                </div>

                <hr class="lowongan-detail-divider">

                <div class="lowongan-detail-desc-title">Deskripsi & Kualifikasi</div>
                <div class="lowongan-detail-desc">
                    {!! clean($detailLowonganHtml) !!}
                </div>
            </div>
        </div>

            <div class="form-card">
            <div class="container">
                <div class="form-header">
                    <h2>
                        Pendaftaran Lowongan
                        @if(isset($lowonganSelected))
                            <span style="display:block; font-size:0.95rem; color: var(--text-muted); font-weight: 700; margin-top: 8px;">
                                {{ $lowonganSelected->title }}
                            </span>
                        @endif
                    </h2>
                </div>

            @if (session('error'))
                <div class="highlight-note">
                    <i class="fas fa-exclamation-circle"></i> <strong>{{ session('error') }}</strong>
                </div>
            @endif

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    function setInvalidState(el, isValid) {
                        if (!el) return;
                        el.classList.toggle('field-invalid', !isValid);

                        // Cari / buat elemen pesan error tepat di dalam form-group
                        var group = el.closest('.form-group');
                        if (!group) return;
                        var msg = group.querySelector('.client-error-message');
                        if (!msg) {
                            msg = document.createElement('small');
                            msg.className = 'client-error-message';
                            msg.style.display = 'none';
                            msg.style.color = '#dc2626';
                            msg.style.fontSize = '0.85rem';
                            msg.style.marginTop = '5px';
                            group.appendChild(msg);
                        }

                        if (isValid) {
                            msg.style.display = 'none';
                            msg.textContent = '';
                        } else {
                            var message = el.validationMessage || 'Field ini wajib diisi.';

                            switch (el.id) {
                                case 'nik':
                                    if (!el.value) {
                                        message = 'NIK wajib diisi.';
                                    } else if (!/^[0-9]{16}$/.test(el.value)) {
                                        message = 'NIK harus terdiri dari 16 digit angka.';
                                    }
                                    break;
                                case 'nim_sn':
                                    if (!el.value) {
                                        message = 'NIM/NISN wajib diisi.';
                                    } else if (!/^[0-9]{8,16}$/.test(el.value)) {
                                        message = 'NIM/NISN harus terdiri dari 8 sampai 16 digit angka.';
                                    }
                                    break;
                                case 'nama':
                                    message = 'Nama lengkap wajib diisi.';
                                    break;
                                case 'email':
                                    message = !el.value
                                        ? 'Email wajib diisi.'
                                        : 'Masukkan alamat email yang valid.';
                                    break;
                                case 'kontak':
                                    if (!el.value) {
                                        message = 'Kontak wajib diisi.';
                                    } else if (el.value.length < 10) {
                                        message = 'Kontak minimal 10 digit angka.';
                                    } else {
                                        message = 'Kontak hanya boleh berisi angka (10–14 digit).';
                                    }
                                    break;
                                case 'gender':
                                    message = 'Silakan pilih jenis kelamin.';
                                    break;
                                case 'id_agama':
                                    message = 'Silakan pilih agama.';
                                    break;
                                case 'tempat_lahir':
                                    message = 'Tempat lahir wajib diisi.';
                                    break;
                                case 'tanggal_lahir':
                                    message = 'Tanggal lahir wajib diisi.';
                                    break;
                                case 'minat':
                                    message = 'Minat magang wajib diisi.';
                                    break;
                                case 'nama_kontak_darurat':
                                    message = 'Nama kontak darurat wajib diisi.';
                                    break;
                                case 'hubungan_kontak_darurat':
                                    message = 'Hubungan kontak darurat wajib diisi.';
                                    break;
                                case 'nomor_kontak_darurat':
                                    if (!el.value) {
                                        message = 'Nomor kontak darurat wajib diisi.';
                                    } else if (el.value.length < 10) {
                                        message = 'Nomor kontak darurat minimal 10 digit angka.';
                                    } else {
                                        message = 'Nomor kontak darurat hanya boleh berisi angka (10–14 digit).';
                                    }
                                    break;
                                case 'kategoriMagang':
                                    message = 'Silakan pilih kategori magang.';
                                    break;
                                case 'id_pendidikan':
                                    message = 'Silakan pilih jenjang pendidikan.';
                                    break;
                                case 'fakultas':
                                    message = 'Fakultas wajib diisi untuk kategori selain PKL.';
                                    break;
                                case 'jurusan':
                                    message = 'Jurusan wajib diisi.';
                                    break;
                                case 'tanggal_mulai':
                                    message = 'Tanggal mulai magang wajib diisi.';
                                    break;
                                case 'tanggal_selesai':
                                    message = 'Tanggal selesai magang wajib diisi.';
                                    break;
                                case 'instansiInput':
                                    message = 'Asal institusi / universitas wajib diisi.';
                                    break;
                                case 'alamat_instansi':
                                    message = 'Alamat institusi wajib diisi.';
                                    break;
                                case 'nama_guru':
                                    message = 'Nama dosen/guru/penanggung jawab wajib diisi.';
                                    break;
                                case 'email_guru':
                                    message = !el.value
                                        ? 'Email dosen/guru/penanggung jawab wajib diisi.'
                                        : 'Masukkan email dosen/guru/penanggung jawab yang valid.';
                                    break;
                                case 'kontak_guru':
                                    if (!el.value) {
                                        message = 'Kontak dosen/guru/penanggung jawab wajib diisi.';
                                    } else if (el.value.length < 10) {
                                        message = 'Kontak dosen/guru minimal 10 digit angka.';
                                    } else {
                                        message = 'Kontak dosen/guru hanya boleh berisi angka (10–14 digit).';
                                    }
                                    break;
                                case 'pas_foto':
                                    message = 'Pas foto wajib diunggah.';
                                    break;
                                case 'cv':
                                    message = 'CV wajib diunggah.';
                                    break;
                                case 'surat':
                                    message = 'Surat pengantar instansi wajib diunggah.';
                                    break;
                                case 'ktm':
                                    message = 'KTM / kartu pelajar wajib diunggah.';
                                    break;
                                case 'motivation':
                                    message = 'Motivation letter wajib diunggah.';
                                    break;
                            }

                            msg.textContent = message;
                            msg.style.display = 'block';
                        }
                    }

                    function validateField(el) {
                        if (!el) return true;
                        // Jangan validasi field yang disabled (mis: tanggal_selesai sebelum tanggal_mulai dipilih)
                        if (el.disabled) {
                            setInvalidState(el, true);
                            return true;
                        }
                        const isValid = el.checkValidity();
                        setInvalidState(el, isValid);
                        return isValid;
                    }

                    // Validasi saat pindah field (blur) → kalau salah jadi merah
                    const form = document.getElementById('registrationForm');
                    if (form) {
                        const fields = form.querySelectorAll('input, select, textarea');
                        fields.forEach(function(el) {
                            if (!el || el.type === 'hidden' || el.type === 'file') return;

                            el.addEventListener('blur', function() {
                                this.dataset.touched = '1';
                                validateField(this);
                            });

                            // Kalau user sudah pernah "touched", setiap input/change akan update merahnya
                            el.addEventListener('input', function() {
                                if (this.dataset.touched) validateField(this);
                            });
                            el.addEventListener('change', function() {
                                if (this.dataset.touched) validateField(this);
                            });
                        });
                    }

                    // ===== Validasi seluruh form di sisi klien (sebelum submit ke backend) =====
                    // Tandai box berkas (upload-box) sebagai invalid + tampilkan pesannya.
                    function setFileBoxInvalid(field, invalid) {
                        var box = document.getElementById('box-' + field);
                        if (!box) return;
                        box.style.border = invalid ? '2px solid #dc2626' : '';
                        var group = box.closest('.form-group');
                        if (!group) return;
                        var msg = group.querySelector('.client-error-message');
                        if (!msg) {
                            msg = document.createElement('small');
                            msg.className = 'client-error-message';
                            msg.style.color = '#dc2626';
                            msg.style.fontSize = '0.85rem';
                            msg.style.marginTop = '5px';
                            group.appendChild(msg);
                        }
                        var labels = {
                            pas_foto: 'Pas foto wajib diunggah.',
                            cv: 'CV wajib diunggah.',
                            surat: 'Surat pengantar instansi wajib diunggah.',
                            ktm: 'KTM / kartu pelajar wajib diunggah.',
                            motivation: 'Motivation letter wajib diunggah.'
                        };
                        if (invalid) {
                            msg.textContent = labels[field] || 'Berkas wajib diunggah.';
                            msg.style.display = 'block';
                        } else {
                            msg.style.display = 'none';
                            msg.textContent = '';
                        }
                    }
                    // Diekspos agar handleUpload() (scope global) bisa menghapus error saat file dipilih.
                    window.clearFileBoxError = function(field) { setFileBoxInvalid(field, false); };

                    // Validasi semua field. Mengembalikan elemen invalid pertama (null jika semua valid).
                    window.validateRegistrationForm = function() {
                        var firstInvalid = null;
                        var f = document.getElementById('registrationForm');
                        if (!f) return null;

                        // 1) Field standar (text/email/date/select/textarea) memakai HTML5 checkValidity
                        f.querySelectorAll('input, select, textarea').forEach(function(el) {
                            if (!el) return;
                            var t = (el.type || '').toLowerCase();
                            // checkbox (accept_ppdp) divalidasi terpisah; hidden/file/tombol dilewati
                            if (t === 'hidden' || t === 'file' || t === 'submit' || t === 'button' || t === 'reset' || t === 'checkbox') return;
                            if (el.name === 'g-recaptcha-response') return;
                            if (el.disabled) return; // mis. tanggal_selesai sebelum tanggal_mulai dipilih
                            el.dataset.touched = '1';
                            if (!validateField(el) && !firstInvalid) firstInvalid = el;
                        });

                        // 2) Berkas wajib (surat_rekomendasi opsional -> tidak dicek).
                        //    Valid bila ada file baru ATAU temp file dari percobaan sebelumnya.
                        ['pas_foto', 'cv', 'surat', 'ktm', 'motivation'].forEach(function(field) {
                            var input = document.getElementById(field);
                            var box = document.getElementById('box-' + field);
                            if (!input || !box) return;
                            var hasNew = input.files && input.files.length > 0;
                            var existing = f.querySelector('input[name="existing_' + field + '"]');
                            var hasTemp = (existing && existing.value) || box.classList.contains('has-file');
                            var ok = !!(hasNew || hasTemp);
                            setFileBoxInvalid(field, !ok);
                            if (!ok && !firstInvalid) firstInvalid = box;
                        });

                        return firstInvalid;
                    };

                    // Batasi tanggal lahir maksimal 10 tahun yang lalu
                    (function enforceMinAge() {
                        var dob = document.getElementById('tanggal_lahir');
                        if (!dob) return;
                        try {
                            var today = new Date();
                            today.setFullYear(today.getFullYear() - 10); // minimal usia 10 tahun
                            var max = today.toISOString().split('T')[0];
                            dob.max = max;
                        } catch (e) {}
                    })();

                    // NIK & kontak: hanya angka, blok huruf real-time
                    ['nik', 'nim_sn', 'kontak', 'kontak_guru'].forEach(function(id) {
                        var el = document.getElementById(id);
                        if (el) {
                            var maxLen = id === 'nik' ? 16 : (id === 'nim_sn' ? 16 : 14);
                            el.addEventListener('input', function() {
                                var v = this.value.replace(/[^0-9]/g, '');

                                // Untuk kontak & kontak_guru: paksa selalu diawali "08"
                                if ((id === 'kontak' || id === 'kontak_guru') && v.length > 0) {
                                    if (v.length === 1) {
                                        // Kalau user ketik 0 atau selain itu, paksa jadi "0"
                                        v = '0';
                                    } else {
                                        if (!v.startsWith('08')) {
                                            // Paksa prefix 08, sisanya mengikuti input user
                                            v = '08' + v.slice(2);
                                        }
                                    }
                                }

                                this.value = v.slice(0, maxLen);

                                // NIK & kontak: realtime validasi (NIK 16 digit, kontak 10–14 digit)
                                this.dataset.touched = '1';
                                validateField(this);
                            });
                            el.addEventListener('keypress', function(e) {
                                if (e.key && e.key.length === 1 && !/[0-9]/.test(e.key)) {
                                    e.preventDefault();
                                }
                            });
                            el.addEventListener('paste', function(e) {
                                e.preventDefault();
                                var pasted = (e.clipboardData || window.clipboardData).getData('text');
                                var digits = (pasted || '').replace(/[^0-9]/g, '');
                                var cur = this.value;
                                var start = this.selectionStart || 0;
                                var end = this.selectionEnd || 0;
                                var newVal = (cur.slice(0, start) + digits + cur.slice(end));

                                // Terapkan aturan prefix 08 juga untuk paste pada kontak
                                if ((id === 'kontak' || id === 'kontak_guru') && newVal.length > 0) {
                                    if (newVal.length === 1) {
                                        newVal = '0';
                                    } else {
                                        if (!newVal.startsWith('08')) {
                                            newVal = '08' + newVal.slice(2);
                                        }
                                    }
                                }

                                this.value = newVal.slice(0, maxLen);

                                this.dataset.touched = '1';
                                validateField(this);
                            });
                        }
                    });
                });
            </script>
            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Auto focus ke field pertama yang error
                        @php
                            $firstErrorKey = $errors->keys()[0];
                            $fieldMap = [
                                'nik' => 'nik',
                                'nim_sn' => 'nim_sn',
                                'nama' => 'nama',
                                'email' => 'email',
                                'kontak' => 'kontak',
                                'gender' => 'gender',
                                'id_agama' => 'id_agama',
                                'tempat_lahir' => 'tempat_lahir',
                                'tanggal_lahir' => 'tanggal_lahir',
                                'minat' => 'minat',
                                'catatan_khusus' => 'catatan_khusus',
                                'nama_kontak_darurat' => 'nama_kontak_darurat',
                                'hubungan_kontak_darurat' => 'hubungan_kontak_darurat',
                                'nomor_kontak_darurat' => 'nomor_kontak_darurat',
                                'kategori' => 'kategoriMagang',
                                'kategori_lainnya' => 'kategori_lainnya',
                                'fakultas' => 'fakultas',
                                'jurusan' => 'jurusan',
                                'nomor_surat_pengantar' => 'nomor_surat_pengantar',
                                'jabatan_penandatangan_surat' => 'jabatan_penandatangan_surat',
                                'id_pendidikan' => 'id_pendidikan',
                                'tanggal_mulai' => 'tanggal_mulai',
                                'tanggal_selesai' => 'tanggal_selesai',
                                'instansi' => 'instansiInput',
                                'alamat_instansi' => 'alamat_instansi',
                                'nama_guru' => 'nama_guru',
                                'email_guru' => 'email_guru',
                                'kontak_guru' => 'kontak_guru',
                                'pas_foto' => 'pas_foto',
                                'cv' => 'cv',
                                'surat' => 'surat',
                                'ktm' => 'ktm',
                                'surat_rekomendasi' => 'surat_rekomendasi',
                                'motivation' => 'motivation',
                                'accept_ppdp' => 'accept_ppdp',
                            ];
                            $fieldId = $fieldMap[$firstErrorKey] ?? $firstErrorKey;
                        @endphp
                        const errorField = document.getElementById('{{ $fieldId }}');
                        if (errorField) {
                            setTimeout(() => {
                                errorField.focus();
                                errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }, 300);
                        }
                    });
                </script>
            @endif

            <form id="registrationForm" method="POST" enctype="multipart/form-data" action="{{ route('Register Form2') }}">
                @csrf
                @if(isset($lowonganSelected))
                    <input type="hidden" name="id_lowongan" value="{{ $lowonganSelected->id }}">
                @endif

                <div class="section-container" id="section1">
                <div class="section-title">
                    <i class="fas fa-user"></i> <h3>1. Data Diri</h3>
                </div>
                <div class="grid-form">
                    <div class="form-group">
                        <label for="nik" class="label-required">Nomor Induk Kependudukan (NIK) :</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik', $formData['nik'] ?? '') }}" placeholder="Masukkan Nomor Induk Kependudukan" maxlength="16" inputmode="numeric" pattern="[0-9]{16}" required>
                        @error('nik')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nim_sn" class="label-required">NIM / NISN :</label>
                        <input type="text" name="nim_sn" id="nim_sn" value="{{ old('nim_sn', $formData['nim_sn'] ?? '') }}" placeholder="Masukkan NIM (Mahasiswa) atau NISN (Siswa)" maxlength="16" minlength="8" inputmode="numeric" pattern="[0-9]{8,16}" required>
                        @error('nim_sn')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="label-required">Nama Lengkap :</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $formData['nama'] ?? '') }}" placeholder="Masukkan Nama Lengkap" required>
                        @error('nama')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="label-required">E-Mail :</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $formData['email'] ?? '') }}" placeholder="Masukkan Email" required>
                        @error('email')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="kontak" class="label-required">Kontak (No. HP / WhatsApp) :</label>
                        <input type="text" name="kontak" id="kontak" value="{{ old('kontak', $formData['kontak'] ?? '') }}" placeholder="Harus diawali 08, min. 10 digit (No. HP / WhatsApp)" maxlength="14" inputmode="numeric" pattern="08[0-9]{8,12}" minlength="10" required>
                        @error('kontak')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="label-required">Jenis Kelamin :</label>
                        <select name="gender" id="gender" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('gender', $formData['gender'] ?? '') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('gender', $formData['gender'] ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="label-required">Agama :</label>
                        <select name="id_agama" id="id_agama" required>
                            <option value="">-- Pilih --</option>
                            @foreach($agama as $agama)
                                <option value="{{ $agama->id }}" {{ old('id_agama', $formData['id_agama'] ?? '') == $agama->id ? 'selected' : '' }}>{{ $agama->agama }}</option>
                            @endforeach
                        </select>
                        @error('id_agama')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tempat_lahir" class="label-required">Tempat Lahir :</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir"
                            value="{{ old('tempat_lahir', $formData['tempat_lahir'] ?? (!empty($smart) ? $smart->tempat_lahir : '')) }}" placeholder="Masukkan Tempat Lahir" required>
                        @error('tempat_lahir')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir" class="label-required">Tanggal Lahir :</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $formData['tanggal_lahir'] ?? '') }}" onclick="this.showPicker()" required>
                        @error('tanggal_lahir')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="position: relative;">
                        <label for="minat" class="label-required">Minat :</label>
                        <input type="text"
                               name="minat"
                               id="minat"
                               value="{{ old('minat', $formData['minat'] ?? '') }}"
                               placeholder="Ketik untuk mencari(minat magang)"
                               autocomplete="off"
                               required>
                        <div id="minatSuggestions" class="instansi-suggestions" style="display:none;"></div>
                        <small style="display:block;margin-top:6px;color:#64748b;font-size:0.85rem;">
                            Ketik minimal 1 huruf untuk melihat saran. Jika tidak ada, Anda tetap bisa mengetik minat sendiri.
                        </small>
                        @error('minat')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="label-required">Nama Kontak Darurat :</label>
                        <input type="text" name="nama_kontak_darurat" id="nama_kontak_darurat" value="{{ old('nama_kontak_darurat', $formData['nama_kontak_darurat'] ?? '') }}" placeholder="Masukkan nama kontak darurat" required>
                        @error('nama_kontak_darurat')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="label-required">Hubungan Kontak Darurat :</label>
                        <input type="text" name="hubungan_kontak_darurat" id="hubungan_kontak_darurat" value="{{ old('hubungan_kontak_darurat', $formData['hubungan_kontak_darurat'] ?? '') }}" placeholder="Contoh: Orang Tua, Saudara, dll" required>
                        @error('hubungan_kontak_darurat')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="label-required">Nomor Kontak Darurat :</label>
                        <input type="tel" name="nomor_kontak_darurat" id="nomor_kontak_darurat" value="{{ old('nomor_kontak_darurat', $formData['nomor_kontak_darurat'] ?? '') }}" placeholder="Contoh: 08123456789" pattern="08[0-9]{8,12}" required>
                        @error('nomor_kontak_darurat')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="catatan_khusus">Catatan Khusus <span class="text-muted"></span> :</label>
                        <textarea name="catatan_khusus" id="catatan_khusus" rows="3" placeholder="Catatan tambahan jika ada (misal: kebutuhan khusus, jadwal, dll)" class="w-100" style="padding: 12px 16px; border: 1.5px solid #e0e0e0; border-radius: 10px; font-family: inherit; resize: none;">{{ old('catatan_khusus') }}</textarea>
                        @error('catatan_khusus')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>



                <div class="section-container" id="section2">
                <div class="section-title">
                    <i class="fas fa-graduation-cap"></i> <h3>2. Data Pendidikan</h3>
                </div>
                <div class="grid-form">
                    @php
                        // Kunci Kategori & Jenjang Pendidikan sesuai jenis pendidikan lowongan.
                        $hasLowongan = isset($lowonganSelected) && $lowonganSelected;
                        $lowJenis = $hasLowongan ? $lowonganSelected->jenisList() : [];
                        $lockMagangOnly = $hasLowongan && in_array('Magang', $lowJenis, true) && !in_array('PKL', $lowJenis, true);
                        $lockPklOnly = $hasLowongan && in_array('PKL', $lowJenis, true) && !in_array('Magang', $lowJenis, true);
                        $jenisBoth = $hasLowongan && in_array('Magang', $lowJenis, true) && in_array('PKL', $lowJenis, true);
                        $lockedKategori = $lockMagangOnly ? 'Magang' : ($lockPklOnly ? 'PKL' : null);
                    @endphp
                    <div class="form-group">
                        <label class="label-required">Kategori Magang :</label>
                        @if($lockedKategori)
                            <select id="kategoriMagang" onchange="updateRequirements(); toggleKategoriLainnya(); toggleFakultasField();" disabled style="background-color:#e9ecef; cursor:not-allowed;">
                                <option value="{{ $lockedKategori }}" selected>{{ $lockedKategori === 'PKL' ? 'PKL (Siswa SMK/Sederajat)' : 'Magang (Mahasiswa)' }}</option>
                            </select>
                            <input type="hidden" name="kategori" value="{{ $lockedKategori }}">
                            <small style="display:block;margin-top:6px;color:var(--text-muted);font-size:0.85rem;"><i class="fas fa-lock"></i> Ditentukan oleh lowongan.</small>
                        @else
                            <select id="kategoriMagang" name="kategori" onchange="updateRequirements(); toggleKategoriLainnya(); toggleFakultasField();" required>
                                <option value="">-- Pilih Kategori --</option>
                                @if(!$hasLowongan || in_array('PKL', $lowJenis, true))
                                    <option value="PKL" {{ old('kategori', $formData['kategori'] ?? '') == 'PKL' ? 'selected' : '' }}>PKL (Siswa SMK/Sederajat)</option>
                                @endif
                                @if(!$hasLowongan || in_array('Magang', $lowJenis, true))
                                    <option value="Magang" {{ old('kategori', $formData['kategori'] ?? '') == 'Magang' ? 'selected' : '' }}>Magang (Mahasiswa)</option>
                                @endif
                                @unless($hasLowongan)
                                    <option value="Penelitian" {{ old('kategori', $formData['kategori'] ?? '') == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                                    <option value="Lainnya" {{ old('kategori', $formData['kategori'] ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya (Masukkan Kategori)</option>
                                @endunless
                            </select>
                            @if($jenisBoth)
                                <small style="display:block;margin-top:6px;color:var(--text-muted);font-size:0.85rem;"><i class="fas fa-info-circle"></i> Lowongan ini menerima Magang &amp; PKL. Pilih sesuai jenjang Anda.</small>
                            @endif
                            @error('kategori')
                                <span class="server-error-message">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>

                    <div class="form-group full-width" id="wrapKategoriLainnya" style="display: {{ old('kategori') == 'Lainnya' ? 'block' : 'none' }};">
                        <label for="kategori_lainnya">Kategori (Masukkan Kategori) :</label>
                        <input type="text" name="kategori_lainnya" id="kategori_lainnya" value="{{ old('kategori_lainnya') }}" placeholder="Contoh: Pelatihan, Kunjungan, dll" maxlength="100" style="max-width: 100%;">
                        @error('kategori_lainnya')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="label-required">Jenjang Pendidikan :</label>
                        <select name="id_pendidikan" id="id_pendidikan" required>
                            <option value="">-- Pilih --</option>
                            @foreach($pendidikan as $pendidikan)
                                    <option value="{{ $pendidikan->id }}" data-strata="{{ $pendidikan->strata }}" {{ old('id_pendidikan', $formData['id_pendidikan'] ?? '') == $pendidikan->id ? 'selected' : '' }}>{{ $pendidikan->pendidikan }} ({{ $pendidikan->strata }})</option>
                                @endforeach
                        </select>
                        @if($hasLowongan)
                            <small style="display:block;margin-top:6px;color:var(--text-muted);font-size:0.85rem;"><i class="fas fa-info-circle"></i> Pilihan jenjang disesuaikan dengan kriteria lowongan.</small>
                        @endif
                        @error('id_pendidikan')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($hasLowongan)
                    <script>
                    (function() {
                        // Batasi pilihan Jenjang Pendidikan sesuai Kategori (Magang=non-SMK, PKL=SMK).
                        var REG_LOCKED_KATEGORI = @json($lockedKategori);

                        function strataMatch(kategori, strata) {
                            if (kategori === 'PKL') return strata === 'SMK';
                            if (kategori === 'Magang') return strata !== 'SMK';
                            return true; // belum pilih kategori -> tampilkan semua
                        }
                        function effectiveKategori() {
                            if (REG_LOCKED_KATEGORI) return REG_LOCKED_KATEGORI;
                            var el = document.getElementById('kategoriMagang');
                            return el ? (el.value || '') : '';
                        }
                        function filterPendidikan() {
                            var sel = document.getElementById('id_pendidikan');
                            if (!sel) return;
                            var kategori = effectiveKategori();
                            var resetNeeded = false;
                            Array.prototype.forEach.call(sel.options, function(opt) {
                                if (!opt.value) return;
                                var strata = opt.getAttribute('data-strata') || '';
                                var ok = strataMatch(kategori, strata);
                                opt.hidden = !ok;
                                opt.disabled = !ok;
                                if (!ok && opt.selected) resetNeeded = true;
                            });
                            if (resetNeeded) sel.value = '';
                        }
                        function init() {
                            filterPendidikan();
                            var katEl = document.getElementById('kategoriMagang');
                            if (katEl && !REG_LOCKED_KATEGORI) {
                                katEl.addEventListener('change', filterPendidikan);
                            }
                            // Jalankan ulang setelah proses restore old/session selesai
                            setTimeout(filterPendidikan, 600);
                        }
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', init);
                        } else {
                            init();
                        }
                    })();
                    </script>
                    @endif
                    <div class="form-group">
                        <label for="tanggal_mulai" class="label-required">Tanggal Mulai Magang :</label>
                        @if(isset($lowonganSelected) && !empty($lowonganSelected->tanggal_mulai))
                            <input type="date" id="tanggal_mulai" value="{{ \Carbon\Carbon::parse($lowonganSelected->tanggal_mulai)->format('Y-m-d') }}" readonly disabled style="background-color:#e9ecef; cursor:not-allowed;">
                            <input type="hidden" name="tanggal_mulai" value="{{ \Carbon\Carbon::parse($lowonganSelected->tanggal_mulai)->format('Y-m-d') }}">
                            <small style="color:var(--text-muted)"><i class="fas fa-lock"></i> Ditentukan oleh lowongan.</small>
                        @else
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $formData['tanggal_mulai'] ?? '') }}" onclick="this.showPicker()" required>
                            {{-- <small style="color:var(--text-muted)">* Minimal 2 minggu dari hari ini</small> --}}
                        @endif
                        @error('tanggal_mulai')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai" class="label-required">Tanggal Selesai Magang :</label>
                        @if(isset($lowonganSelected) && !empty($lowonganSelected->tanggal_selesai))
                            <input type="date" id="tanggal_selesai" value="{{ \Carbon\Carbon::parse($lowonganSelected->tanggal_selesai)->format('Y-m-d') }}" readonly disabled style="background-color:#e9ecef; cursor:not-allowed;">
                            <input type="hidden" name="tanggal_selesai" value="{{ \Carbon\Carbon::parse($lowonganSelected->tanggal_selesai)->format('Y-m-d') }}">
                            <small style="color:var(--text-muted)"><i class="fas fa-lock"></i> Ditentukan oleh lowongan.</small>
                        @else
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $formData['tanggal_selesai'] ?? '') }}" onclick="this.showPicker()" required disabled>
                            {{-- <small style="color:var(--text-muted)">* Minimal durasi 1 bulan</small> --}}
                        @endif
                        @error('tanggal_selesai')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width" style="position: relative;">
                        <label class="label-required">Asal Institusi / Universitas :</label>
                        <input
                            type="text"
                            id="instansiInput"
                            name="instansi"
                            value="{{ old('instansi', $formData['instansi'] ?? '') }}"
                            placeholder="Ketik untuk mencari (Universitas / Sekolah)"
                            autocomplete="off"
                            required
                        >
                        <div id="instansiSuggestions" class="instansi-suggestions" style="display:none;"></div>
                        <small style="display:block;margin-top:6px;color:#64748b;font-size:0.85rem;">
                            Ketik minimal 2 huruf untuk melihat saran. Jika sekolah/universitas tidak ditemukan, Anda dapat mengetik sendiri.
                        </small>
                        @error('instansi')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" id="wrapFakultas" style="display: {{ old('kategori', $formData['kategori'] ?? '') != 'PKL' && old('kategori', $formData['kategori'] ?? '') != '' ? 'block' : 'none' }};">
                        <label for="fakultas" id="labelFakultas" class="{{ old('kategori', $formData['kategori'] ?? '') != 'PKL' && old('kategori', $formData['kategori'] ?? '') != '' ? 'label-required' : '' }}">Fakultas :</label>
                        <input type="text" name="fakultas" id="fakultas" value="{{ old('fakultas', $formData['fakultas'] ?? '') }}" placeholder="Masukkan Fakultas" {{ old('kategori', $formData['kategori'] ?? '') != 'PKL' && old('kategori', $formData['kategori'] ?? '') != '' ? 'required' : '' }}>
                        @error('fakultas')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jurusan" class="label-required">Jurusan :</label>
                        <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan', $formData['jurusan'] ?? '') }}" placeholder="Masukkan Jurusan" required>
                        @error('jurusan')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="alamat_instansi" class="label-required">Alamat Institusi :</label>
                        <input type="text" name="alamat_instansi" id="alamat_instansi" value="{{ old('alamat_instansi', $formData['alamat_instansi'] ?? '') }}" placeholder="Masukkan Alamat Institusi" required>
                        @error('alamat_instansi')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_guru" class="label-required">Nama Dosen/Guru/Penanggung Jawab :</label>
                        <input type="text" name="nama_guru" id="nama_guru" value="{{ old('nama_guru', $formData['nama_guru'] ?? '') }}" placeholder="Masukkan Nama Dosen/Guru/Penanggung Jawab" required>
                        @error('nama_guru')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email_guru" class="label-required">E -Mail Dosen/Guru/Penanggung Jawab :</label>
                        <input type="email" name="email_guru" id="email_guru" value="{{ old('email_guru', $formData['email_guru'] ?? '') }}" placeholder="Masukkan Email Dosen/Guru/Penanggung Jawab" required>
                        @error('email_guru')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="kontak_guru" class="label-required">Kontak Dosen/Guru/Penanggung Jawab (No. HP/WA) :</label>
                        <input type="text" name="kontak_guru" id="kontak_guru" value="{{ old('kontak_guru', $formData['kontak_guru'] ?? '') }}" placeholder="Harus diawali 08, min. 10 digit (No. HP / WhatsApp)" maxlength="14" inputmode="numeric" pattern="08[0-9]{8,12}" minlength="10" required>
                        @error('kontak_guru')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nomor_surat_pengantar" class="label-required">Nomor Surat Pengantar :</label>
                        <div style="display:flex;align-items:center;gap:8px;position:relative;">
                            <input type="text"
                                   name="nomor_surat_pengantar"
                                   id="nomor_surat_pengantar"
                                   value="{{ old('nomor_surat_pengantar', $formData['nomor_surat_pengantar'] ?? '') }}"
                                   placeholder="Contoh: 123/UNIV/III/2026"
                                   maxlength="100"
                                   required
                                   oninvalid="this.setCustomValidity('Nomor surat pengantar wajib diisi.')"
                                   oninput="this.setCustomValidity('')">
                            <span
                                id="info-nomor-surat-trigger"
                                onclick="event.stopImmediatePropagation(); (function(){var p=document.getElementById('nomor-surat-info-popover'); var t=document.getElementById('info-nomor-surat-trigger'); if(!p||!t) return; if(!p.dataset.bound){ document.addEventListener('click', function(ev){ if(!p.contains(ev.target) && ev.target!==t){ p.style.display='none'; }}); p.dataset.bound='1'; } p.style.display = (p.style.display==='none'||p.style.display==='') ? 'block' : 'none'; })();"
                                style="flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:999px;border:1px solid #93c5fd;font-size:11px;font-weight:700;color:#2563eb;cursor:pointer;background:#eff6ff;"
                            >i</span>

                            <div
                                id="nomor-surat-info-popover"
                                style="display:none;position:absolute;top:calc(100% + 10px);right:0;width:min(340px, calc(100vw - 40px));max-width:340px;background:#ffffff;border:1px solid rgba(226,232,240,1);border-radius:12px;box-shadow:0 10px 25px rgba(15,23,42,0.12);padding:14px 14px;z-index:60;">
                                <div style="display:flex;gap:10px;align-items:flex-start;">
                                    <div style="width:24px;height:24px;border-radius:999px;background:#fffbeb;border:1px solid #fef3c7;display:flex;align-items:center;justify-content:center;color:#b45309;font-weight:900;flex-shrink:0;line-height:1;font-size:14px;">!</div>
                                    <div style="flex:1;">
                                        <div style="font-weight:800;color:#111827;margin-bottom:6px;">Cara menemukan Nomor Surat Pengantar</div>
                                        <ul style="margin:0;padding-left:18px;color:#374151;line-height:1.5;font-size:0.85rem;">
                                            <li>Lihat bagian atas surat pengantar (awal halaman).</li>
                                            <li>Cari tulisan <strong>Nomor:</strong>.</li>
                                            <li>Salin format nomor persis seperti yang tertulis, misalnya <strong>123/UNIV/III/2026</strong>.</li>
                                            <li>Masukkan ke kolom <strong>Nomor Surat Pengantar</strong>.</li>
                                        </ul>
                                        <div style="margin-top:10px;color:#4b5563;font-size:0.8rem;">
                                            Contoh potongan:
                                            <div style="margin-top:6px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:10px;font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;color:#111827;line-height:1.35;">
                                                Nomor: 123/UNIV/III/2026<br>
                                                Perihal: ...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('nomor_surat_pengantar')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jabatan_penandatangan_surat" class="label-required">Jabatan Penandatangan Surat Pengantar :</label>
                        <input type="text"
                               name="jabatan_penandatangan_surat"
                               id="jabatan_penandatangan_surat"
                               value="{{ old('jabatan_penandatangan_surat', $formData['jabatan_penandatangan_surat'] ?? '') }}"
                               placeholder="Kepala Sekolah, Dekan, Asisten Dosen"
                               maxlength="100"
                               required
                               oninvalid="this.setCustomValidity('Jabatan penandatangan surat pengantar wajib diisi.')"
                               oninput="this.setCustomValidity('')">
                        @error('jabatan_penandatangan_surat')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                </div>

                <div class="section-container" id="section3">
                <div class="section-title">
                    <i class="fas fa-cloud-upload-alt"></i> <h3>3. Berkas Pendukung</h3>
                </div>
                 <div id="databerkas">
                        <!-- Hidden inputs untuk menyimpan referensi file yang sudah di-upload -->
                        @if(session('uploadedFiles'))
                            @foreach(session('uploadedFiles') as $field => $path)
                                <input type="hidden" name="existing_{{ $field }}" value="{{ $path }}">
                            @endforeach
                        @endif

                        <div class="upload-grid">
                            <!-- PAS FOTO -->
                            <div class="form-group">
                                <label for="pas_foto" class="label-required">Pas Foto (3x4, Latar Merah)</label>
                                <div class="upload-box" id="box-pas_foto">
                                    <div class="success-badge"><i class="fas fa-check"></i></div>
                                    <i class="fas fa-image main-icon"></i>
                                    <span class="upload-text">Unggah Pas Foto</span>
                                    <span class="file-info">Format: JPG/PNG </span>
                                    <span class="file-info">(Min: 200&nbsp;&nbsp;KB, Max: 1&nbsp;&nbsp;MB)</span>
                                    <div class="preview-container">
                                        <img src="" class="preview-img" id="img-preview-pas_foto">
                                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--success)">Foto Berhasil Dimuat</span>
                                    </div>
                                    <input type="file" name="pas_foto" id="pas_foto" accept="image/jpeg,image/png,image/jpg" onchange="handleUpload(this, 'image')">
                                </div>
                                @error('pas_foto')
                                    <span class="server-error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- CV -->
                           <div class="form-group">
                                <label for="cv" class="label-required">Curriculum Vitae (CV)</label>
                                @if(!empty($templateLinks['cv']))
                                    <a href="{{ $templateLinks['cv'] }}" target="_blank" rel="noopener noreferrer" class="template-link">
                                        <i class="fas fa-download"></i> Template CV
                                    </a>
                                @endif
                                <div class="upload-box" id="box-cv">
                                    <div class="success-badge"><i class="fas fa-check"></i></div>
                                    <i class="fas fa-file-pdf main-icon"></i>
                                    <span class="upload-text">Unggah CV</span>
                                    <span class="file-info">Format: PDF </span>
                                    <span class="file-info">(Min: 200&nbsp;&nbsp;KB, Max: 1&nbsp;&nbsp;MB)</span>
                                    <div class="preview-container">
                                        <embed src="" class="preview-pdf" id="pdf-preview-cv">
                                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--success)">File Berhasil Dimuat</span>
                                    </div>
                                    <input type="file" name="cv" id="cv" accept="application/pdf" onchange="handleUpload(this, 'pdf')">
                                </div>
                                @error('cv')
                                    <span class="server-error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- SURAT PENGANTAR -->
                            <div class="form-group">
                                <div style="display:flex;align-items:center;gap:8px;position:relative;margin-bottom:8px;">
                                    <label for="surat" class="label-required" style="margin-bottom:0;">Surat Pengantar Institusi</label>
                                    <span
                                        id="info-surat-trigger"
                                        onclick="event.stopImmediatePropagation(); (function(){var p=document.getElementById('surat-info-popover'); var t=document.getElementById('info-surat-trigger'); if(!p||!t) return; if(!p.dataset.bound){ document.addEventListener('click', function(ev){ if(!p.contains(ev.target) && ev.target!==t){ p.style.display='none'; }}); p.dataset.bound='1'; } p.style.display = (p.style.display==='none'||p.style.display==='') ? 'block' : 'none'; })();"
                                        style="flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:999px;border:1px solid #93c5fd;font-size:11px;font-weight:700;color:#2563eb;cursor:pointer;background:#eff6ff;"
                                    >i</span>

                                    <div
                                        id="surat-info-popover"
                                        style="display:none;position:absolute;top:calc(100% + 10px);left:0;width:min(340px, calc(100vw - 40px));max-width:340px;background:#ffffff;border:1px solid rgba(226,232,240,1);border-radius:12px;box-shadow:0 10px 25px rgba(15,23,42,0.12);padding:14px 14px;z-index:60;">
                                        <div style="display:flex;gap:10px;align-items:flex-start;">
                                            <div style="width:24px;height:24px;border-radius:999px;background:#fffbeb;border:1px solid #fef3c7;display:flex;align-items:center;justify-content:center;color:#b45309;font-weight:900;flex-shrink:0;line-height:1;font-size:14px;">!</div>
                                            <div style="flex:1;">
                                                <div style="font-weight:800;color:#111827;margin-bottom:6px;">Surat Pengantar Institusi</div>
                                                <p style="margin:0;padding:0;color:#374151;line-height:1.5;font-size:0.85rem;">Surat Pengantar Institusi ditujukan kepada <strong>Kepala Pusat Pengembangan Kompetensi SDM Legislatif (PUSBANGKOM) Setjen DPR RI</strong>.</p>
                                                <p style="margin:6px 0 0;padding:6px 8px;background:#fef2f2;border-left:3px solid #ef4444;border-radius:4px;color:#b91c1c;line-height:1.5;font-size:0.82rem;font-weight:600;">⚠️ Pastikan nama penerima pada surat <u>sama persis</u> seperti di atas. Kesalahan penulisan nama/jabatan dapat menyebabkan berkas ditolak.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($templateLinks['surat_pengantar']))
                                    <a href="{{ $templateLinks['surat_pengantar'] }}" target="_blank" rel="noopener noreferrer" class="template-link">
                                        <i class="fas fa-download"></i> Contoh Surat Pengantar Institusi
                                    </a>
                                @endif
                                <div class="upload-box" id="box-surat">
                                    <div class="success-badge"><i class="fas fa-check"></i></div>
                                    <i class="fas fa-file-signature main-icon"></i>
                                    <span class="upload-text">Unggah Surat Pengantar</span>
                                    <span class="file-info">Format: PDF </span>
                                    <span class="file-info">(Min: 200&nbsp;&nbsp;KB, Max: 1&nbsp;&nbsp;MB)</span>
                                    <div class="preview-container">
                                        <embed src="" class="preview-pdf" id="pdf-preview-surat">
                                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--success)">File Berhasil Dimuat</span>
                                    </div>
                                    <input type="file" name="surat" id="surat" accept="application/pdf" onchange="handleUpload(this, 'pdf')">
                                </div>
                                @error('surat')
                                    <span class="server-error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- KTM -->
                            <div class="form-group">
                                <label for="ktm" class="label-required">KTM / Kartu Pelajar</label>
                                <div class="upload-box" id="box-ktm">
                                    <div class="success-badge"><i class="fas fa-check"></i></div>
                                    <i class="fas fa-id-card main-icon"></i>
                                    <span class="upload-text">Unggah KTM</span>
                                    <span class="file-info">Format: PDF </span>
                                    <span class="file-info">(Min: 200&nbsp;&nbsp;KB, Max: 1&nbsp;&nbsp;MB)</span>
                                    <div class="preview-container">
                                        <img src="" class="preview-img" id="img-preview-ktm" style="display:none;">
                                        <embed src="" class="preview-pdf" id="pdf-preview-ktm" style="display:none;">
                                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--success)">File Berhasil Dimuat</span>
                                    </div>
                                    <input type="file" name="ktm" id="ktm" accept="application/pdf" onchange="handleUpload(this, 'pdf')">
                                </div>
                                @error('ktm')
                                    <span class="server-error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Surat Rekomendasi (Opsional) -->
                            <div class="form-group">
                                <label for="surat_rekomendasi">Surat Rekomendasi <span class="text-muted"></span></label>
                                @if(!empty($templateLinks['surat_rekomendasi']))
                                    <a href="{{ $templateLinks['surat_rekomendasi'] }}" target="_blank" rel="noopener noreferrer" class="template-link">
                                        <i class="fas fa-download"></i> Contoh Surat Rekomendasi
                                    </a>
                                @endif
                                <div class="upload-box" id="box-surat_rekomendasi">
                                    <div class="success-badge"><i class="fas fa-check"></i></div>
                                    <i class="fas fa-address-card main-icon"></i>
                                    <span class="upload-text">Unggah Surat Rekomendasi</span>
                                    <span class="file-info">Format: PDF </span>
                                    <span class="file-info">(Min: 200&nbsp;&nbsp;KB, Max: 1&nbsp;&nbsp;MB)</span>
                                    <div class="preview-container">
                                        <img src="" class="preview-img" id="img-preview-surat_rekomendasi" style="display:none;">
                                        <embed src="" class="preview-pdf" id="pdf-preview-surat_rekomendasi" style="display:none;">
                                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--success)">File Berhasil Dimuat</span>
                                    </div>
                                    <input type="file" name="surat_rekomendasi" id="surat_rekomendasi" accept="application/pdf" onchange="handleUpload(this, 'pdf')">
                                </div>
                                @error('surat_rekomendasi')
                                    <span class="server-error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- MOTIVATION LETTER -->
                            <div class="form-group">
                                <div style="display:flex;align-items:center;gap:8px;position:relative;margin-bottom:8px;">
                                    <label for="motivation" class="label-required" style="margin-bottom:0;">Proposal Magang / Motivation Letter</label>
                                    <span
                                        id="info-motivation-trigger"
                                        onclick="event.stopImmediatePropagation(); (function(){var p=document.getElementById('motivation-info-popover'); var t=document.getElementById('info-motivation-trigger'); if(!p||!t) return; if(!p.dataset.bound){ document.addEventListener('click', function(ev){ if(!p.contains(ev.target) && ev.target!==t){ p.style.display='none'; }}); p.dataset.bound='1'; } p.style.display = (p.style.display==='none'||p.style.display==='') ? 'block' : 'none'; })();"
                                        style="flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:999px;border:1px solid #93c5fd;font-size:11px;font-weight:700;color:#2563eb;cursor:pointer;background:#eff6ff;"
                                    >i</span>

                                    <div
                                        id="motivation-info-popover"
                                        style="display:none;position:absolute;top:calc(100% + 10px);left:0;width:min(340px, calc(100vw - 40px));max-width:340px;background:#ffffff;border:1px solid rgba(226,232,240,1);border-radius:12px;box-shadow:0 10px 25px rgba(15,23,42,0.12);padding:14px 14px;z-index:60;">
                                        <div style="display:flex;gap:10px;align-items:flex-start;">
                                            <div style="width:24px;height:24px;border-radius:999px;background:#fffbeb;border:1px solid #fef3c7;display:flex;align-items:center;justify-content:center;color:#b45309;font-weight:900;flex-shrink:0;line-height:1;font-size:14px;">!</div>
                                            <div style="flex:1;">
                                                <div style="font-weight:800;color:#111827;margin-bottom:6px;">Proposal Magang / Motivation Letter</div>
                                                <ul style="margin:0;padding-left:18px;color:#374151;line-height:1.5;font-size:0.85rem;">
                                                    <li><strong>Proposal Magang</strong> wajib khusus lamaran yang menuju Pustekinfo, yang berisikan tujuan yang ingin dicapai atau dikembangkan di Pustekinfo.</li>
                                                    <li><strong>Motivation Letter</strong> bagi lamaran umum, wajib tulisan tangan yang di-scan lalu di-upload.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($templateLinks['proposal']))
                                    <a href="{{ $templateLinks['proposal'] }}" target="_blank" rel="noopener noreferrer" class="template-link">
                                        <i class="fas fa-download"></i> Contoh Proposal Magang / Motivation Letter
                                    </a>
                                @endif
                                <div class="upload-box" id="box-motivation">
                                    <div class="success-badge"><i class="fas fa-check"></i></div>
                                    <i class="fas fa-pen-nib main-icon"></i>
                                    <span class="upload-text">Unggah Motivation Letter</span>
                                    <span class="file-info">Format: PDF </span>
                                    <span class="file-info">(Min: 200&nbsp;&nbsp;KB, Max: 1&nbsp;&nbsp;MB)</span>
                                    <div class="preview-container">
                                        <embed src="" class="preview-pdf" id="pdf-preview-motivation">
                                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--success)">File Berhasil Dimuat</span>
                                    </div>
                                    <input type="file" name="motivation" id="motivation" accept="application/pdf" onchange="handleUpload(this, 'pdf')">
                                </div>
                                @error('motivation')
                                    <span class="server-error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    </div>

                    <!-- Divider untuk pemisah persyaratan -->
                    <div style="border-top: 2px solid var(--gold-solid); margin: 40px 0 30px; position: relative;">
                        <div  class="persyaratan-title" style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); padding: 0 20px; color: var(--gold-solid); font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">
                            <i class="fas fa-list-check" style="margin-right: 8px;"></i> Persyaratan
                        </div>
                    </div>

                    <!--Section PKL-->
                    <div class="section" id="section-pkl" style="display: none;">
                        <h1>Persyaratan Praktik Kerja Lapangan</h1>
                        <div class="scrollable-info">
                        <ul>
                            <li>Surat pengantar dari Sekolah yang ditujukan kepada: Kepala Pusat Pengembangan Kompetensi SDM Legislatif (PUSBANGKOM) Setjen DPR RI (berisi tentang tujuan PKL dan pemilihan topik PKL).</li>
                            <li>Curriculum Vitae (Daftar Riwayat Hidup).</li>
                            <li>Pas Foto berlatar merah dengan ukuran 2x3 sebanyak 2 lembar.</li>
                            <li>Fotokopi Kartu Pelajar dan Surat Rekomendasi.</li>
                            <li>Motivation Letter yang ditulis tangan (berisi kelebihan diri, kekurangan diri, kemampuan, motivasi mendaftar, dan harapan).</li>
                        </ul>
                        </div>
                    </div>

                    <!--Section Magang-->
                    <div class="section" id="section-magang" style="display: none;">
                        <h1>Persyaratan Magang</h1>
                        <div class="scrollable-info">
                        <ul>
                            <li>Surat pengantar dari perguruan tinggi yang ditujukan kepada: Kepala Pusat Pengembangan Kompetensi SDM Legislatif (PUSBANGKOM) Setjen DPR RI (berisi tentang tujuan magang dan pemilihan topik magang).</li>
                            <li>Kartu Rencana Studi dan Kartu Hasil Studi/Transkrip Nilai.</li>
                            <li>Curriculum Vitae (Daftar Riwayat Hidup).</li>
                            <li>Pas Foto berlatar merah dengan ukuran 2x3 sebanyak 2 lembar.</li>
                            <li>Fotokopi KTM dan Surat Rekomendasi.</li>
                            <li>Motivation Letter yang ditulis tangan (berisi kelebihan diri, kekurangan diri, kemampuan, motivasi mendaftar, dan harapan).</li>
                        </ul>
                        </div>
                    </div>

                    <!--Section Penelitian-->
                    <div class="section" id="section-penelitian" style="display: none;">
                        <h1>Persyaratan Penelitian</h1>
                        <div class="scrollable-info">
                        <ul>
                            <li>Surat pengantar dari Perguruan Tinggi/Surat Permohonan yang ditujukan kepada: Kepala Pusat Pengembangan Kompetensi SDM Legislatif (PUSBANGKOM) Setjen DPR RI.</li>
                            <li>Curriculum Vitae (Daftar Riwayat Hidup).</li>
                            <li>Pas Foto berlatar merah dengan ukuran 2x3 sebanyak 2 lembar.</li>
                            <li>Fotokopi KTM dan Surat Rekomendasi.</li>
                            <li>Proposal Penelitian dan Daftar Pertanyaan Wawancara/Kuesioner Penelitian.</li>
                            <li>Proposal Magang (berisi kelebihan diri, kekurangan diri, kemampuan, motivasi mendaftar, dan tujuan yang dicapai).</li>
                        </ul>
                        </div>
                    </div>
                    </div>

                </div>


                </div>

                <!-- Disclaimer Perlindungan Data Pribadi -->
                <div class="disclaimer-ppdp">
                    <div class="disclaimer-box">
                        <p class="disclaimer-title"><strong>Disclaimer</strong></p>
                        <p class="disclaimer-text">Data pribadi yang Anda isikan dalam formulir pendaftaran ini akan digunakan hanya untuk keperluan magang, serta dikelola sesuai dengan ketentuan peraturan perundang-undangan yang berlaku mengenai perlindungan data pribadi. DPR RI berkomitmen menjaga kerahasiaan dan keamanan data Anda.</p>
                        <label class="disclaimer-checkbox">
                            <input type="checkbox" name="accept_ppdp" id="accept_ppdp" value="1" {{ old('accept_ppdp') ? 'checked' : '' }}>
                            <span>Saya telah membaca dan menyetujui pernyataan di atas</span>
                        </label>
                        @error('accept_ppdp')
                            <span class="server-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- reCAPTCHA -->
                <div style="margin-top: 1.5rem; margin-bottom: 1rem;">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" id="recaptchaContainer"></div>
                    @error('g-recaptcha-response')
                        <span class="server-error-message" style="display:block; margin-top:8px;">{{ $message }}</span>
                    @enderror
                </div>

                 <div class="btn-submit-wrapper">
                    <button type="button" class="btn-submit" id="btnSubmitPendaftaran" onclick="showConfirmModal()" {{ old('accept_ppdp') ? '' : 'disabled' }}>
                        <i class="fas fa-paper-plane"></i> SUBMIT PENDAFTARAN
                    </button>
                </div>


                </div>


            </form>
            </div>
        </div>
    </main>
</div>

    <!-- Modal Konfirmasi Submit -->
    <div class="confirm-modal" id="confirmModal">
        <div class="confirm-modal-content">
            <div class="confirm-icon">
                <i class="fas fa-question"></i>
            </div>
            <h3>Konfirmasi Pendaftaran</h3>
           <p style="color: black;">
            <div class="confirm-buttons">
                <button type="button" class="btn-confirm btn-confirm-no" onclick="hideConfirmModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn-confirm btn-confirm-yes" onclick="submitForm()">
                    <i class="fas fa-check"></i> Ya, Saya Yakin
                </button>
            </div>
        </div>
    </div>

    <!-- Loading setelah klik "Ya, Saya Yakin" -->
    <div class="loading-modal" id="loadingModal" aria-hidden="true">
        <div class="loading-modal-content">
            <div class="loading-spinner" aria-hidden="true"></div>
            <p class="loading-title">Memproses Pendaftaran...</p>
            <p class="loading-subtitle">Mohon tunggu. Jangan tutup atau refresh halaman.</p>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Handle file input - simpan ke sessionStorage agar tidak hilang saat validasi gagal
        const fileFields = ['pas_foto', 'cv', 'surat', 'ktm', 'surat_rekomendasi', 'motivation'];
        const STORAGE_PREFIX = 'reg_file_';
        const STORAGE_TIMESTAMP = 'reg_file_timestamp';
        const STORAGE_MAX_AGE = 24 * 60 * 60 * 1000; // 24 jam dalam milliseconds

        // Clear old storage jika lebih dari 24 jam
        function clearOldStorage() {
            const timestamp = sessionStorage.getItem(STORAGE_TIMESTAMP);
            if (timestamp) {
                const age = Date.now() - parseInt(timestamp);
                if (age > STORAGE_MAX_AGE) {
                    fileFields.forEach(field => {
                        sessionStorage.removeItem(`${STORAGE_PREFIX}${field}`);
                    });
                    sessionStorage.removeItem(STORAGE_TIMESTAMP);
                }
            }
        }

        // Set timestamp saat pertama kali menyimpan
        function setStorageTimestamp() {
            if (!sessionStorage.getItem(STORAGE_TIMESTAMP)) {
                sessionStorage.setItem(STORAGE_TIMESTAMP, Date.now().toString());
            }
        }

        // Fungsi untuk menyimpan file ke sessionStorage sebagai base64
        function saveFileToStorage(field, file) {
            setStorageTimestamp();
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileData = {
                    name: file.name,
                    type: file.type,
                    size: file.size,
                    data: e.target.result, // base64
                    timestamp: Date.now()
                };
                sessionStorage.setItem(`${STORAGE_PREFIX}${field}`, JSON.stringify(fileData));
            };
            reader.readAsDataURL(file);
        }

        // Fungsi untuk restore file dari sessionStorage ke input
        function restoreFileToInput(field) {
            const stored = sessionStorage.getItem(`${STORAGE_PREFIX}${field}`);
            if (!stored) return false;

            try {
                const fileData = JSON.parse(stored);

                // Check jika file lebih dari 24 jam, skip restore
                if (fileData.timestamp) {
                    const age = Date.now() - fileData.timestamp;
                    if (age > STORAGE_MAX_AGE) {
                        sessionStorage.removeItem(`${STORAGE_PREFIX}${field}`);
                        return false;
                    }
                }

                const fileInput = document.getElementById(field);
                if (!fileInput) {
                    console.warn(`File input ${field} not found, will retry...`);
                    return false;
                }

                // Convert base64 ke Blob
                const byteString = atob(fileData.data.split(',')[1]);
                const mimeString = fileData.data.split(',')[0].split(':')[1].split(';')[0];
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                const blob = new Blob([ab], { type: mimeString });
                const file = new File([blob], fileData.name, { type: fileData.type });

                // Set file ke input menggunakan DataTransfer
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                // Verify file sudah ter-set
                if (fileInput.files.length === 0) {
                    console.warn(`Failed to set file to input ${field}`);
                    return false;
                }

                // Trigger change event untuk update UI (tapi jangan trigger handleUpload karena sudah ada file)
                // Gunakan custom event atau langsung update UI
                const box = document.getElementById('box-' + field);
                if (box) {
                    box.classList.add('has-file');
                    const mainIcon = box.querySelector('.main-icon');
                    const previewContainer = box.querySelector('.preview-container');

                    if (mainIcon) mainIcon.style.display = 'none';
                    if (previewContainer) {
                        previewContainer.style.display = 'flex';

                        if (field === 'pas_foto') {
                            const imgPreview = previewContainer.querySelector('.preview-img');
                            if (imgPreview) {
                                imgPreview.src = fileData.data; // Use base64 data directly
                                imgPreview.style.display = 'block';
                            }
                        } else {
                            const pdfPreview = previewContainer.querySelector('.preview-pdf');
                            if (pdfPreview) {
                                pdfPreview.src = fileData.data; // Use base64 data directly
                                pdfPreview.style.display = 'block';
                            }
                        }
                    }
                }

                return true;
            } catch (e) {
                console.error('Error restoring file:', e);
                sessionStorage.removeItem(`${STORAGE_PREFIX}${field}`);
                return false;
            }
        }

        // Update visual saat file dipilih
        function handleUpload(input, type) {
            const box = input.closest('.upload-box');
            const mainIcon = box.querySelector('.main-icon');
            const previewContainer = box.querySelector('.preview-container');
            const successBadge = box.querySelector('.success-badge');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxSize = 1024 * 1024; // 1 MB
                const minFileSize = 200 * 1024; // 200 KB
                if (file.size < minFileSize) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'File terlalu kecil',
                            html:
                                '<div style="text-align:left;line-height:1.6;">' +
                                '<div>Ukuran minimal file adalah <strong>200 KB</strong>.</div>' +
                                '<div>Ukuran file Anda: <strong>' + (file.size / 1024).toFixed(0) + ' KB</strong>.</div>' +
                                '<div style="margin-top:8px;color:#64748b;font-size:13px;">Silakan pilih file lain dengan ukuran yang sesuai.</div>' +
                                '</div>',
                            confirmButtonText: 'Pilih Ulang File',
                            confirmButtonColor: '#b08d48'
                        });
                    } else {
                        alert('Ukuran file minimal 200 KB. File ini: ' + (file.size / 1024).toFixed(0) + ' KB.');
                    }
                    input.value = '';
                    return;
                }
                if (file.size > maxSize) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'File terlalu besar',
                            html:
                                '<div style="text-align:left;line-height:1.6;">' +
                                '<div>Ukuran maksimal file adalah <strong>1 MB</strong>.</div>' +
                                '<div>Ukuran file Anda: <strong>' + (file.size / 1024 / 1024).toFixed(2) + ' MB</strong>.</div>' +
                                '<div style="margin-top:8px;color:#64748b;font-size:13px;">Silakan kompres atau pilih file lain.</div>' +
                                '</div>',
                            confirmButtonText: 'Pilih Ulang File',
                            confirmButtonColor: '#b08d48'
                        });
                    } else {
                        alert('Ukuran file maksimal 1 MB. File ini: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB.');
                    }
                    input.value = '';
                    return;
                }
                box.classList.add('has-file');
                box.style.border = '';
                if (typeof window.clearFileBoxError === 'function') window.clearFileBoxError(input.name);
                mainIcon.style.display = 'none';
                previewContainer.style.display = 'flex';

                if (type === 'image' || type === 'mixed') {
                    // Check if it's an image
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const imgPreview = previewContainer.querySelector('.preview-img');
                            const pdfPreview = previewContainer.querySelector('.preview-pdf');
                            if (imgPreview) {
                                imgPreview.src = e.target.result;
                                imgPreview.style.display = 'block';
                            }
                            if (pdfPreview) pdfPreview.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    } else if (type === 'mixed' && file.type === 'application/pdf') {
                        // PDF in mixed mode
                        const pdfPreview = previewContainer.querySelector('.preview-pdf');
                        const imgPreview = previewContainer.querySelector('.preview-img');
                        if (pdfPreview) {
                            pdfPreview.src = URL.createObjectURL(file);
                            pdfPreview.style.display = 'block';
                        }
                        if (imgPreview) imgPreview.style.display = 'none';
                    }
                } else if (type === 'pdf') {
                    // PDF file
                    const pdfPreview = previewContainer.querySelector('.preview-pdf');
                    if (pdfPreview) {
                        pdfPreview.src = URL.createObjectURL(file);
                        pdfPreview.style.display = 'block';
                    }
                }

                // Simpan file ke sessionStorage
                saveFileToStorage(input.name, file);
            } else {
                box.classList.remove('has-file');
                mainIcon.style.display = 'block';
                previewContainer.style.display = 'none';
                // Hapus dari sessionStorage jika file dihapus
                sessionStorage.removeItem(`${STORAGE_PREFIX}${input.name}`);
            }
        }

        // Clear old storage saat halaman load
        clearOldStorage();

        // Restore semua file saat halaman load (termasuk setelah validasi gagal)
        // Restore dilakukan setelah DOM ready untuk memastikan semua element sudah ada
        function restoreAllFiles() {
            const failedFields = [];
            fileFields.forEach(field => {
                const success = restoreFileToInput(field);
                if (!success) {
                    failedFields.push(field);
                }
            });

            // Retry untuk field yang gagal (mungkin element belum ready)
            if (failedFields.length > 0) {
                setTimeout(function() {
                    failedFields.forEach(field => {
                        restoreFileToInput(field);
                    });
                }, 300);
            }
        }

        // Restore saat DOM ready dengan multiple attempts
        function initRestore() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    // Delay untuk memastikan semua element sudah render
                    setTimeout(restoreAllFiles, 100);
                    // Retry sekali lagi setelah delay lebih lama
                    setTimeout(restoreAllFiles, 500);
                });
            } else {
                // DOM sudah ready, restore dengan delay
                setTimeout(restoreAllFiles, 100);
                // Retry sekali lagi setelah delay lebih lama
                setTimeout(restoreAllFiles, 500);
            }
        }

        initRestore();

        // Simpan file ke sessionStorage saat dipilih
        fileFields.forEach(field => {
            const fileInput = document.getElementById(field);
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        const file = this.files[0];
                        saveFileToStorage(field, file);
                    } else {
                        // Jika file dihapus, hapus dari sessionStorage
                        sessionStorage.removeItem(`${STORAGE_PREFIX}${field}`);
                    }
                });
            }
        });

        // Function untuk clear semua sessionStorage file
        function clearAllSessionStorageFiles() {
            fileFields.forEach(field => {
                sessionStorage.removeItem(`${STORAGE_PREFIX}${field}`);
            });
            sessionStorage.removeItem(STORAGE_TIMESTAMP);
        }

        // JANGAN clear sessionStorage saat submit
        // Biarkan file tetap ada di sessionStorage agar bisa di-restore jika validasi gagal
        // File akan di-clear hanya saat:
        // 1. User navigate away dari halaman registrasi (via clearRegistrationData)
        // 2. User tutup tab/browser (via beforeunload/pagehide)
        // 3. Submit berhasil dan redirect ke halaman lain (akan trigger beforeunload)

        // Reset semua upload box dan input file (dipakai saat user Back ke halaman ini)
        function resetAllUploadBoxes() {
            document.querySelectorAll('#registrationForm input[type="file"]').forEach(function(input) {
                input.value = '';
            });
            document.querySelectorAll('#registrationForm .upload-box').forEach(function(box) {
                box.classList.remove('has-file');
                var mainIcon = box.querySelector('.main-icon');
                var previewContainer = box.querySelector('.preview-container');
                if (mainIcon) mainIcon.style.display = '';
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                    var img = previewContainer.querySelector('.preview-img');
                    var embed = previewContainer.querySelector('.preview-pdf');
                    if (img) { img.src = ''; img.style.display = 'none'; }
                    if (embed) { embed.src = ''; embed.style.display = 'none'; }
                }
            });
        }

        // Saat halaman ditampilkan lagi dari cache (tombol Back), kosongkan file dan preview
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                resetAllUploadBoxes();
            }
        });

        function updateRequirements() {
            const kategori = document.getElementById('kategoriMagang').value;
            document.getElementById('section-pkl').style.display = 'none';
            document.getElementById('section-magang').style.display = 'none';
            document.getElementById('section-penelitian').style.display = 'none';
            if (kategori === 'PKL') {
                document.getElementById('section-pkl').style.display = 'block';
            } else if (kategori === 'Magang') {
                document.getElementById('section-magang').style.display = 'block';
            } else if (kategori === 'Penelitian') {
                document.getElementById('section-penelitian').style.display = 'block';
            }
        }

        function toggleKategoriLainnya() {
            const sel = document.getElementById('kategoriMagang');
            const wrap = document.getElementById('wrapKategoriLainnya');
            const input = document.getElementById('kategori_lainnya');
            if (!wrap || !input) return;
            if (sel && sel.value === 'Lainnya') {
                wrap.style.display = 'block';
                input.setAttribute('required', 'required');
            } else {
                wrap.style.display = 'none';
                input.removeAttribute('required');
                input.value = '';
            }
        }

        function toggleFakultasField() {
            const sel = document.getElementById('kategoriMagang');
            const wrap = document.getElementById('wrapFakultas');
            const input = document.getElementById('fakultas');
            const label = document.getElementById('labelFakultas');
            if (!sel || !wrap || !input) return;

            const isNonPkl = sel.value !== '' && sel.value !== 'PKL';
            wrap.style.display = isNonPkl ? 'block' : 'none';
            input.required = isNonPkl;

            if (label) {
                label.classList.toggle('label-required', isNonPkl);
            }

            if (!isNonPkl) {
                input.value = '';
            }
        }

        // Date Validation
        function initDateValidation() {
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalSelesai = document.getElementById('tanggal_selesai');

            // Skip jika tanggal dikunci oleh lowongan
            if (!tanggalMulai || !tanggalSelesai || tanggalMulai.readOnly || tanggalSelesai.readOnly) return;

            // Set minimum date for tanggal_mulai to 14 days from today
            // const minStartDate = new Date();
            // minStartDate.setDate(minStartDate.getDate());
            // tanggalMulai.min = minStartDate.toISOString().split('T')[0];

            // When tanggal_mulai changes, update tanggal_selesai minimum
            tanggalMulai.addEventListener('change', function() {
                if (this.value) {
                    const startDate = new Date(this.value);
                    const endDate = new Date(startDate);
                    endDate.setMonth(endDate.getMonth());
                    tanggalSelesai.min = endDate.toISOString().split('T')[0];
                    tanggalSelesai.value = ''; // Reset end date when start date changes
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const inputMulai = document.getElementById('tanggal_mulai');
            const inputSelesai = document.getElementById('tanggal_selesai');
            const dateHint = document.getElementById('date-hint');

            // Skip jika tanggal dikunci oleh lowongan
            if (!inputMulai || !inputSelesai || inputMulai.readOnly || inputSelesai.readOnly) return;

            // Fungsi untuk mengatur status input tanggal selesai
            function checkDateStatus() {
                if (inputMulai.value) {
                    // Aktifkan jika tanggal mulai ada isinya (termasuk hasil dari old())
                    inputSelesai.disabled = false;
                    inputSelesai.min = inputMulai.value;
                    if (dateHint) dateHint.style.display = 'none';
                } else {
                    // Tetap disable jika kosong
                    inputSelesai.disabled = true;
                    if (dateHint) dateHint.style.display = 'block';
                }
            }

            // Jalankan fungsi saat halaman pertama kali dimuat (untuk menangani error/reload)
            checkDateStatus();

        // Jalankan fungsi setiap kali tanggal mulai diubah manual oleh user
        inputMulai.addEventListener('change', function() {
            checkDateStatus();

            // Reset tanggal selesai jika ternyata lebih kecil dari tanggal mulai yang baru diubah
            if (inputSelesai.value && inputSelesai.value < inputMulai.value) {
                inputSelesai.value = '';
            }
        });
    });


        // Tombol submit: enable hanya ketika disclaimer dicentang
        (function() {
            var btn = document.getElementById('btnSubmitPendaftaran');
            var cb = document.getElementById('accept_ppdp');
            if (!btn || !cb) return;
            function toggleSubmit() {
                btn.disabled = !cb.checked;
                btn.style.opacity = cb.checked ? '1' : '0.6';
                btn.style.cursor = cb.checked ? 'pointer' : 'not-allowed';
            }
            cb.addEventListener('change', toggleSubmit);
            toggleSubmit(); // set state awal
        })();

        // Modal Konfirmasi (wajib ceklis disclaimer dulu)
        function showConfirmModal() {
            // 0) Validasi SELURUH isian di frontend dulu agar tidak perlu reload halaman dari backend
            if (typeof window.validateRegistrationForm === 'function') {
                var firstInvalid = window.validateRegistrationForm();
                if (firstInvalid) {
                    try {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        if (typeof firstInvalid.focus === 'function') {
                            setTimeout(function() { firstInvalid.focus({ preventScroll: true }); }, 350);
                        }
                    } catch (e) {}

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Formulir Belum Lengkap',
                            text: 'Masih ada isian yang belum lengkap atau belum sesuai. Mohon periksa kembali bagian yang ditandai merah.',
                            confirmButtonColor: '#b08d48',
                            confirmButtonText: 'Periksa Kembali'
                        });
                    } else {
                        alert('Masih ada isian yang belum lengkap atau belum sesuai. Mohon periksa kembali.');
                    }
                    return;
                }
            }

            var cb = document.getElementById('accept_ppdp');
            if (!cb || !cb.checked) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Anda harus menyetujui disclaimer perlindungan data pribadi terlebih dahulu sebelum mengirim formulir.',
                        confirmButtonColor: '#b08d48'
                    });
                } else {
                    alert('Anda harus menyetujui disclaimer perlindungan data pribadi terlebih dahulu sebelum mengirim formulir.');
                }
                return;
            }

            // Cek reCAPTCHA
            if (typeof grecaptcha !== 'undefined') {
                var recaptchaResponse = grecaptcha.getResponse();
                if (!recaptchaResponse) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Verifikasi Diperlukan',
                            text: 'Silakan lengkapi verifikasi reCAPTCHA terlebih dahulu.',
                            confirmButtonColor: '#b08d48',
                            confirmButtonText: 'Mengerti'
                        });
                    } else {
                        alert('Silakan lengkapi verifikasi reCAPTCHA terlebih dahulu.');
                    }
                    return;
                }
            }

            document.getElementById('confirmModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function hideConfirmModal() {
            document.getElementById('confirmModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function submitForm() {
            // Tutup modal dulu
            hideConfirmModal();

            // Tampilkan loading agar user tahu proses berjalan (berguna saat koneksi lambat)
            const loadingEl = document.getElementById('loadingModal');
            if (loadingEl) {
                loadingEl.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            // Disable tombol untuk mencegah double submit
            const yesBtn = document.querySelector('#confirmModal .btn-confirm-yes');
            if (yesBtn) {
                yesBtn.disabled = true;
                yesBtn.style.opacity = '0.8';
                yesBtn.style.cursor = 'not-allowed';
            }
            const submitBtn = document.querySelector('.btn-submit');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.8';
                submitBtn.style.cursor = 'not-allowed';
            }

            // Beri jeda singkat supaya overlay sempat ter-render sebelum submit
            setTimeout(() => {
            // Pastikan reCAPTCHA response ikut terkirim bersama form
            // Google reCAPTCHA v2 bisa me-render textarea di luar <form>, jadi kita copy nilainya
            const form = document.getElementById('registrationForm');
            if (typeof grecaptcha !== 'undefined') {
                const recaptchaResponse = grecaptcha.getResponse();
                let hiddenInput = form.querySelector('input[name="g-recaptcha-response"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'g-recaptcha-response';
                    form.appendChild(hiddenInput);
                }
                hiddenInput.value = recaptchaResponse;
            }
            // Submit form secara normal (bukan AJAX) karena lebih reliable untuk file upload
            // File akan tetap tersimpan di temp jika validasi gagal
            form.submit();
            }, 50);
        }

        // Tutup modal ketika klik di luar
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideConfirmModal();
            }
        });

        // Tutup modal dengan ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideConfirmModal();
            }
        });

        // Initialize date validation
        initDateValidation();

        // Mobile sidebar (off-canvas)
        (function initMobileSidebar() {
            const btn = document.getElementById('btnOpenSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const sidebar = document.getElementById('registerSidebar');
            if (!btn || !overlay || !sidebar) return;

            function open() { document.body.classList.add('sidebar-open'); }
            function close() { document.body.classList.remove('sidebar-open'); }

            btn.addEventListener('click', open);
            overlay.addEventListener('click', close);

            // Kalau user klik item menu, sidebar ditutup
            sidebar.querySelectorAll('a.nav-item').forEach(a => {
                a.addEventListener('click', () => close());
            });

            // ESC untuk tutup
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') close();
            });
        })();

        // Highlight nav items on scroll (FIXED VERSION)
        window.addEventListener('scroll', () => {
            let current = '';
            const sections = document.querySelectorAll('.section-container');
            const navItems = document.querySelectorAll('.nav-item');

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionMiddle = sectionTop + (section.clientHeight / 2);

                // Jika viewport top lebih besar dari section middle, set sebagai current
                if (window.pageYOffset + 200 >= sectionMiddle) {
                    current = section.getAttribute('id');
                }
            });

            navItems.forEach(item => {
                item.classList.remove('active');
                const href = item.getAttribute('href')?.substring(1); // Ambil ID dari #section1 dll
                if (href === current) {
                    item.classList.add('active');
                }
            });
        });

        // Click handler untuk nav items (IMPROVED) - LANGSUNG NYALA
         document.querySelectorAll('.nav-item').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            // Ambil target section
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                // Scroll ke section dengan offset header
                const offset = 130; // Sesuaikan dengan tinggi header Anda
                const elementPosition = targetElement.offsetTop;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth"
                });

                // Hapus class active dari semua nav-item dan tambah ke yang diklik
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // ScrollSpy: Mengubah warna navbar secara otomatis saat user scroll manual
    window.addEventListener('scroll', () => {
        let current = '';
        const sections = document.querySelectorAll('.section-container');
        const navItems = document.querySelectorAll('.nav-item');

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            // Jika posisi scroll sudah melewati batas atas section (dengan toleransi offset)
            if (pageYOffset >= (sectionTop - 160)) {
                current = section.getAttribute('id');
            }
        });

        navItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === `#${current}`) {
                item.classList.add('active');
            }
        });
    });

        // Back to Top Button
        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }

            // Header scroll effect
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.style.padding = "0.5rem 6%";
                header.style.background = "rgba(255, 255, 255, 0.95)";
            } else {
                header.style.padding = "0.8rem 6%";
                header.style.background = "rgba(255, 255, 255, 0.85)";
            }
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // =========================
        // Autocomplete Instansi (DB lokal)
        // =========================
        const instansiInput = document.getElementById('instansiInput');
        const instansiBox = document.getElementById('instansiSuggestions');
        const kategoriMagangEl = document.getElementById('kategoriMagang');

        function getInstansiSearchParams() {
            const kategori = (kategoriMagangEl?.value || '').toUpperCase();
            // PKL = siswa SMA/SMK -> cari sekolah (SMA dan SMK)
            if (kategori === 'PKL') {
                return { type: 'school', grade: '' }; // Empty grade akan menampilkan SMA dan SMK
            }
            // Magang/Penelitian -> cari universitas
            return { type: 'university', grade: '' };
        }

        function renderInstansiSuggestions(items) {
            if (!instansiBox) return;
            if (!items || items.length === 0) {
                instansiBox.style.display = 'none';
                instansiBox.innerHTML = '';
                return;
            }

            instansiBox.innerHTML = items.map((it, idx) => {
                const location = it.location ? `<div style="font-size:12px;color:#64748b;margin-top:2px;">${it.location}</div>` : '';
                const grade = it.type === 'school' && it.grade ? `<span style="font-size:12px;color:#8e6d2f;font-weight:700;margin-left:6px;">${it.grade}</span>` : '';
                return `
                    <div class="instansi-item" data-name="${(it.name || '').replace(/"/g, '&quot;')}">
                        <div style="font-weight:600;color:#0f172a;">${it.name}${grade}</div>
                        ${location}
                    </div>
                `;
            }).join('');

            instansiBox.style.display = 'block';
        }

        let instansiTimer = null;
        async function fetchInstansiSuggestions(q) {
            const params = getInstansiSearchParams();
            const url = new URL("{{ route('search.instansi') }}", window.location.origin);
            url.searchParams.set('q', q);
            url.searchParams.set('type', params.type);
            if (params.grade) url.searchParams.set('grade', params.grade);

            const res = await fetch(url.toString(), { method: 'GET' });
            if (!res.ok) return [];
            const json = await res.json();
            return json?.data || [];
        }

        function hideInstansiSuggestions() {
            if (!instansiBox) return;
            instansiBox.style.display = 'none';
            instansiBox.innerHTML = '';
        }

        if (instansiInput && instansiBox) {
            // Basic styling injected via JS (biar gak ganggu CSS panjang di atas)
            const style = document.createElement('style');
            style.innerHTML = `
                .instansi-suggestions{
                    position:absolute;
                    left:0; right:0;
                    top:72px;
                    background:#fff;
                    border:1px solid #e2e8f0;
                    border-radius:10px;
                    box-shadow:0 10px 25px rgba(0,0,0,0.08);
                    max-height:260px;
                    overflow:auto;
                    z-index:999;
                }
                .instansi-item{
                    padding:10px 12px;
                    cursor:pointer;
                    border-bottom:1px solid #f1f5f9;
                }
                .instansi-item:hover{
                    background:#f8fafc;
                }
                .instansi-item:last-child{
                    border-bottom:none;
                }
            `;
            document.head.appendChild(style);

            instansiInput.addEventListener('input', function () {
                const q = (this.value || '').trim();
                if (q.length < 2) {
                    hideInstansiSuggestions();
                    return;
                }
                clearTimeout(instansiTimer);
                instansiTimer = setTimeout(async () => {
                    const items = await fetchInstansiSuggestions(q);
                    renderInstansiSuggestions(items);
                }, 250);
            });

            instansiInput.addEventListener('focus', function () {
                const q = (this.value || '').trim();
                if (q.length >= 2) {
                    clearTimeout(instansiTimer);
                    instansiTimer = setTimeout(async () => {
                        const items = await fetchInstansiSuggestions(q);
                        renderInstansiSuggestions(items);
                    }, 150);
                }
            });

            document.addEventListener('click', function (e) {
                const target = e.target;
                const item = target?.closest?.('.instansi-item');
                if (item) {
                    instansiInput.value = item.getAttribute('data-name') || '';
                    hideInstansiSuggestions();
                    return;
                }
                if (target !== instansiInput && !instansiBox.contains(target)) {
                    hideInstansiSuggestions();
                }
            });

            // Kalau kategori berubah, refresh suggestion
            if (kategoriMagangEl) {
                kategoriMagangEl.addEventListener('change', function () {
                    const q = (instansiInput.value || '').trim();
                    hideInstansiSuggestions();
                    if (q.length >= 2) {
                        clearTimeout(instansiTimer);
                        instansiTimer = setTimeout(async () => {
                            const items = await fetchInstansiSuggestions(q);
                            renderInstansiSuggestions(items);
                        }, 150);
                    }
                });
            }
        }

        // =========================
        // Autocomplete Minat (opsi dari DB minat)
        // =========================
        const minatInput = document.getElementById('minat');
        const minatBox = document.getElementById('minatSuggestions');
        const minatOptions = @json(($minatOptions ?? collect())->values());

        function hideMinatSuggestions() {
            if (!minatBox) return;
            minatBox.style.display = 'none';
            minatBox.innerHTML = '';
        }

        function renderMinatSuggestions(items) {
            if (!minatBox) return;
            if (!items || items.length === 0) {
                hideMinatSuggestions();
                return;
            }

            minatBox.innerHTML = items.map((name) => {
                const label = String(name || '');
                const safeName = encodeURIComponent(label);
                return `
                    <div class="instansi-item minat-item" data-name="${safeName}">
                        <div style="font-weight:600;color:#0f172a;">${label}</div>
                    </div>
                `;
            }).join('');
            minatBox.style.display = 'block';
        }

        function filterMinatSuggestions(keyword) {
            const q = (keyword || '').trim().toLowerCase();
            if (!q) return [];

            return (Array.isArray(minatOptions) ? minatOptions : [])
                .map((v) => String(v || '').trim())
                .filter((v) => v !== '' && v.toLowerCase().includes(q))
                .slice(0, 20);
        }

        if (minatInput && minatBox) {
            minatInput.addEventListener('input', function () {
                const q = (this.value || '').trim();
                if (q.length < 1) {
                    hideMinatSuggestions();
                    return;
                }
                renderMinatSuggestions(filterMinatSuggestions(q));
            });

            minatInput.addEventListener('focus', function () {
                const q = (this.value || '').trim();
                if (q.length >= 1) {
                    renderMinatSuggestions(filterMinatSuggestions(q));
                }
            });

            document.addEventListener('click', function (e) {
                const target = e.target;
                const item = target?.closest?.('.minat-item');
                if (item) {
                    minatInput.value = decodeURIComponent(item.getAttribute('data-name') || '');
                    hideMinatSuggestions();
                    return;
                }
                if (target !== minatInput && !minatBox.contains(target)) {
                    hideMinatSuggestions();
                }
            });
        }



        // ============================================
        // AUTO-SAVE & RESTORE FORM DATA
        // ============================================
        (function() {
            const SAVE_DELAY = 500; // Debounce 500ms
            const form = document.getElementById('registrationForm');
            if (!form) return;

            let saveTimer = null;
            const clearDataUrl = '{{ route("register.clear-temp-files") }}';
            const csrfToken = '{{ csrf_token() }}';
            let isFormSubmitting = false;
            let isPageRefresh = false;

            // Detect page refresh
            if (window.performance && window.performance.getEntriesByType) {
                const navEntries = window.performance.getEntriesByType('navigation');
                if (navEntries.length > 0 && navEntries[0].type === 'reload') {
                    isPageRefresh = true;
                }
            }

            // Restore form data dari session
            // old() hanya berlaku 1 request, jadi saat refresh manual perlu restore dari session
            const formDataFromSession = @json($formData ?? []);
            const uploadedFilesFromSession = @json(session('uploadedFiles', []));
            const uploadedFileUrls = @json(
                collect(session('uploadedFiles', []))->mapWithKeys(function ($path, $field) {
                    return [$field => file_url($path)];
                })->toArray()
            );

            // Function untuk restore form data
            function restoreFormData() {
                if (formDataFromSession && Object.keys(formDataFromSession).length > 0) {
                    Object.keys(formDataFromSession).forEach(field => {
                        const input = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
                        if (!input) return;

                        const sessionValue = formDataFromSession[field];
                        // Skip jika value null, undefined, atau empty string (kecuali untuk select yang mungkin perlu set ke empty)
                        if (sessionValue === null || sessionValue === undefined || sessionValue === '') {
                            // Untuk select, tetap set jika value kosong di session
                            if (input.tagName !== 'SELECT') return;
        }

                        // Untuk SELECT - selalu restore dari session
                        if (input.tagName === 'SELECT') {
                            const sessionValueStr = String(sessionValue || '');
                            if (input.value !== sessionValueStr) {
                                input.value = sessionValueStr;
                                // Trigger change untuk select yang punya event listener (seperti kategoriMagang)
                                setTimeout(() => {
                                    input.dispatchEvent(new Event('change', { bubbles: true }));
                                }, 100);
                            }
                        }
                        // Untuk checkbox/radio
                        else if (input.type === 'checkbox' || input.type === 'radio') {
                            input.checked = String(sessionValue) == input.value;
                        }
                        // Untuk input text, email, date, dll
                        else {
                            const currentValue = input.value || '';
                            const sessionValueStr = String(sessionValue || '');
                            // Restore jika kosong atau jika value berbeda
                            if (!currentValue || currentValue !== sessionValueStr) {
                                input.value = sessionValueStr;
                            }
                        }
                    });
                }
        }

            // Function untuk restore file preview dari temp_uploads
            function restoreFilePreview() {
                if (!uploadedFilesFromSession || Object.keys(uploadedFilesFromSession).length === 0) {
                    return false;
                }

                let allRestored = true;
                Object.entries(uploadedFilesFromSession).forEach(([field, path]) => {
                    const box = document.getElementById('box-' + field);
                    if (!box) {
                        allRestored = false;
                        return;
                    }

                    const filePath = uploadedFileUrls[field] || '';
                    box.classList.add('has-file');
                    const mainIcon = box.querySelector('.main-icon');
                    const previewContainer = box.querySelector('.preview-container');

                    if (mainIcon) mainIcon.style.display = 'none';
                    if (previewContainer) {
                        previewContainer.style.display = 'flex';

                        if (field === 'pas_foto') {
                            const imgPreview = previewContainer.querySelector('.preview-img');
                            if (imgPreview) {
                                imgPreview.src = filePath;
                                imgPreview.style.display = 'block';
                            }
                        } else {
                            const pdfPreview = previewContainer.querySelector('.preview-pdf');
                            if (pdfPreview) {
                                pdfPreview.src = filePath;
                                pdfPreview.style.display = 'block';
                    }
                }
                    }

                    // PENTING: Buat atau update hidden input untuk existing file
                    let hiddenInput = document.getElementById('existing_' + field);
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'existing_' + field;
                        hiddenInput.id = 'existing_' + field;
                        form.appendChild(hiddenInput);
                    }
                    hiddenInput.value = path;
                });

                return allRestored;
            }

            // Function untuk restore semua data (dengan retry mechanism)
            function performRestore() {
                restoreFormData();

                const success = restoreFilePreview();
                return success;
            }

            // Restore dengan multiple attempts untuk memastikan semua element sudah ready
            function initRestore() {
                let attempts = 0;
                const maxAttempts = 10;

                function tryRestore() {
                    attempts++;

                    // Check if all required elements exist
                    const formExists = !!document.getElementById('registrationForm');
                    let allBoxesExist = true;

                    if (uploadedFilesFromSession && Object.keys(uploadedFilesFromSession).length > 0) {
                        allBoxesExist = Object.keys(uploadedFilesFromSession).every(function(field) {
                            return !!document.getElementById('box-' + field);
                        });
                    }

                    if (formExists && allBoxesExist) {
                        const success = performRestore();
                        if (!success && attempts < maxAttempts) {
                            // Some elements might still be loading, retry
                            setTimeout(tryRestore, 100);
                        }
                    } else if (attempts < maxAttempts) {
                        // Elements not ready yet, retry
                        setTimeout(tryRestore, 100);
                    } else {
                        // Max attempts reached, try anyway
                        performRestore();
                    }
                }

                // Start restore immediately
                tryRestore();
            }

            // Restore saat DOM ready dengan multiple fallbacks
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    initRestore();
                });
            } else {
                // DOM sudah ready, start immediately
                setTimeout(function() {
                    initRestore();
                }, 50);
        }

            // Fallback: juga coba restore setelah window load (untuk memastikan semua resource loaded)
            window.addEventListener('load', function() {
                setTimeout(performRestore, 50);
            });

            // Auto-save form data ke session saat user mengetik
            function saveFormData() {
                if (isFormSubmitting) return;

                const formData = new FormData(form);
                const data = {};

                // Convert FormData to object (exclude files)
                for (let [key, value] of formData.entries()) {
                    if (!key.startsWith('existing_') && !['pas_foto', 'cv', 'surat', 'ktm', 'surat_rekomendasi', 'motivation'].includes(key)) {
                        data[key] = value;
                    }
                }

                // Save via AJAX
                fetch('{{ route("register.save-form-data") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                }).catch(err => console.error('Error saving form data:', err));
            }

            // Debounced save
            function debouncedSave() {
                clearTimeout(saveTimer);
                saveTimer = setTimeout(saveFormData, SAVE_DELAY);
            }

            // Listen to form input changes
            form.addEventListener('input', debouncedSave);
            form.addEventListener('change', debouncedSave);

            // Track form submit
            form.addEventListener('submit', function() {
                isFormSubmitting = true;
            });

            // Clear data hanya saat keluar dari route register (bukan refresh/submit)
            function clearRegistrationData() {
                if (!isFormSubmitting) {
                    // Clear client-side file cache (sessionStorage) supaya saat balik ke /register file tidak ikut kebawa
                    // Tetap aman untuk kasus validasi gagal, karena validasi gagal tidak keluar dari route /register
                    try { clearAllSessionStorageFiles(); } catch (e) {}

                    // Clear server-side session data
                    if (navigator.sendBeacon) {
                        const formData = new FormData();
                        formData.append('_token', csrfToken);
                        navigator.sendBeacon(clearDataUrl, formData);
                    } else {
                        try {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', clearDataUrl, false);
                            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                            xhr.send('_token=' + encodeURIComponent(csrfToken));
                        } catch (e) {}
                    }
                }
            }

            // Clear saat klik link ke halaman lain (hanya jika bukan route register)
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.href) {
                    try {
                        const targetUrl = new URL(link.href, window.location.origin);
                        const currentPath = window.location.pathname;
                        // Hanya clear jika navigate ke halaman selain /register
                        if (targetUrl.pathname !== currentPath && !targetUrl.pathname.includes('/register')) {
                            clearRegistrationData();
                    }
                    } catch (e) {}
            }
            }, true);

            // Clear saat beforeunload (hanya jika bukan refresh/submit)
            window.addEventListener('beforeunload', function(e) {
                if (!isFormSubmitting && !isPageRefresh) {
                    // Saat unload dari /register (mis. klik menu lain / Back), bersihkan cache file
                    clearRegistrationData();
                }
            });

            // Clear saat pagehide (hanya jika bukan refresh/submit)
            window.addEventListener('pagehide', function(e) {
                if (!isFormSubmitting && !isPageRefresh) {
                    clearRegistrationData();
                }
            });

            // Monitor URL changes (untuk handle user yang langsung ganti URL di address bar)
            let currentUrl = window.location.href;
            const urlCheckInterval = setInterval(function() {
                if (window.location.href !== currentUrl) {
                    const newPath = new URL(window.location.href).pathname;
                    // Jika keluar dari route register, clear data
                    if (!newPath.includes('/register')) {
                        clearRegistrationData();
                        clearInterval(urlCheckInterval);
                    }
                    currentUrl = window.location.href;
                }
            }, 100);
        })();

        // Jika validasi backend gagal karena pas foto < 200 KB, tampilkan popup juga.
        @if($errors->has('pas_foto') && str_contains((string) $errors->first('pas_foto'), 'minimal 200 KB'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'File terlalu kecil',
                html: '<div style="text-align:left;line-height:1.6;"><div>{{ addslashes($errors->first('pas_foto')) }}</div><div style="margin-top:8px;color:#64748b;font-size:13px;">Silakan pilih file lain dengan ukuran minimal 200 KB.</div></div>',
                confirmButtonText: 'Pilih Ulang File',
                confirmButtonColor: '#b08d48'
            });
        }
        @endif
        @if($errors->has('cv') && str_contains((string) $errors->first('cv'), 'minimal 200 KB'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'File terlalu kecil',
                html: '<div style="text-align:left;line-height:1.6;"><div>{{ addslashes($errors->first('cv')) }}</div><div style="margin-top:8px;color:#64748b;font-size:13px;">Silakan pilih file lain dengan ukuran minimal 200 KB.</div></div>',
                confirmButtonText: 'Pilih Ulang File',
                confirmButtonColor: '#b08d48'
            });
        }
        @endif
        @if($errors->has('surat') && str_contains((string) $errors->first('surat'), 'minimal 200 KB'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'File terlalu kecil',
                html: '<div style="text-align:left;line-height:1.6;"><div>{{ addslashes($errors->first('surat')) }}</div><div style="margin-top:8px;color:#64748b;font-size:13px;">Silakan pilih file lain dengan ukuran minimal 200 KB.</div></div>',
                confirmButtonText: 'Pilih Ulang File',
                confirmButtonColor: '#b08d48'
            });
        }
        @endif
        @if($errors->has('ktm') && str_contains((string) $errors->first('ktm'), 'minimal 200 KB'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'File terlalu kecil',
                html: '<div style="text-align:left;line-height:1.6;"><div>{{ addslashes($errors->first('ktm')) }}</div><div style="margin-top:8px;color:#64748b;font-size:13px;">Silakan pilih file lain dengan ukuran minimal 200 KB.</div></div>',
                confirmButtonText: 'Pilih Ulang File',
                confirmButtonColor: '#b08d48'
            });
        }
        @endif
        @if($errors->has('surat_rekomendasi') && str_contains((string) $errors->first('surat_rekomendasi'), 'minimal 200 KB'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'File terlalu kecil',
                html: '<div style="text-align:left;line-height:1.6;"><div>{{ addslashes($errors->first('surat_rekomendasi')) }}</div><div style="margin-top:8px;color:#64748b;font-size:13px;">Silakan pilih file lain dengan ukuran minimal 200 KB.</div></div>',
                confirmButtonText: 'Pilih Ulang File',
                confirmButtonColor: '#b08d48'
            });
        }
        @endif
        @if($errors->has('motivation') && str_contains((string) $errors->first('motivation'), 'minimal 200 KB'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'File terlalu kecil',
                html: '<div style="text-align:left;line-height:1.6;"><div>{{ addslashes($errors->first('motivation')) }}</div><div style="margin-top:8px;color:#64748b;font-size:13px;">Silakan pilih file lain dengan ukuran minimal 200 KB.</div></div>',
                confirmButtonText: 'Pilih Ulang File',
                confirmButtonColor: '#b08d48'
            });
        }
        @endif

        // Inisialisasi tampilan requirements berdasarkan kategori yang tersimpan (old)
        document.addEventListener('DOMContentLoaded', function() {
            resetAllUploadBoxes();
            updateRequirements();
            toggleKategoriLainnya();
            toggleFakultasField();
            // Set first nav active
            const navItemsInit = document.querySelectorAll('.nav-item');
            if (navItemsInit.length) {
                navItemsInit.forEach(i => i.classList.remove('active'));
                navItemsInit[0].classList.add('active');
            }
        });

        // Info Nomor Surat Pengantar (pakai modal seperti di biodata)
        document.addEventListener('DOMContentLoaded', function () {
            var trigger = document.getElementById('info-nomor-surat-trigger');
            if (!trigger) return;

            trigger.addEventListener('click', function () {
                var htmlInfo = '' +
                    '<p style="text-align:left;margin-bottom:8px;">' +
                    'Nomor Surat Pengantar diisi dengan <strong>nomor surat resmi</strong> yang tercetak pada surat pengantar dari sekolah/universitas Anda.' +
                    '</p>' +
                    '<p style="text-align:left;margin:0;">' +
                    'Biasanya nomor tersebut tertulis di bagian atas surat dengan format seperti:<br>' +
                    '<em>Nomor: 123/UNIV/III/2026</em>.<br>' +
                    'Salin dan masukkan angka/format nomor tersebut <strong>persis sama</strong> ke dalam kolom ini.' +
                    '</p>';

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Informasi Nomor Surat Pengantar',
                        html: htmlInfo,
                        icon: 'info',
                        confirmButtonColor: '#b08d48'
                    });
                } else {
                    alert('Isi dengan nomor surat resmi yang tertulis di bagian atas surat pengantar, misalnya: Nomor: 123/UNIV/III/2026.');
                }
            });
        });

        // Highlight nav items on scroll (matches provided template behavior)
        window.addEventListener('scroll', () => {
            let current = '';
            const sections = document.querySelectorAll('.section-container');
            const navItems = document.querySelectorAll('.nav-item');

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 150)) {
                    current = section.getAttribute('id');
                }
            });

            navItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === `#${current}`) {
                    item.classList.add('active');
                }
            });
        });
    </script>

    @include('auth.partials.public-footer')
    @include('auth.partials.public-chrome-scripts')
</body>
</html>
