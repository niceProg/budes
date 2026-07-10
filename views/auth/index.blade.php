<?php
ob_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name') }} | Sekretariat Jenderal DPR RI</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
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
            background-color: #f8fafc;
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.7;
        }

        /* --- FIXED BACKGROUND IMPROVEMENT --- */
        .bg-pattern {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            /* Pastikan path image benar atau gunakan placeholder */
            background-image: url('theme/admin-dashbyte/dist/assets/img/batiknew.png');
            background-size: cover;
            opacity: 0.05; /* Sangat tipis agar elegan */
            z-index: -1;
        }

        /* Header, nav, mobile menu, dropdown, footer, back-to-top, skin-toggle, &
           dark-mode chrome dipindah ke partial: auth.partials.public-chrome-head */

        /* --- HERO SECTION --- */
        .hero {
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: var(--primary-dark);
            position: relative;
            color: white;
            padding: 0 10%;
            margin-top: 60px; /* Offset for fixed header */
        }

        .hero-img {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            opacity: 0.4;
        }

        .hero-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(to bottom, rgba(15,23,42,0.4), rgba(15,23,42,0.9));
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        /* --- CARDS & GRID --- */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .smart-card {
            background: var(--white);
            border-radius: 24px;
            padding: 3rem;
            margin-top: -80px;
            position: relative;
            z-index: 10;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            text-align: center;
            border: 1px solid rgba(255,255,255,0.7);
        }

        .section-title {
            text-align: center;
            margin: 5rem 0 2.5rem;
        }

        .section-title h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
            position: relative;
            display: inline-block;
        }

        .section-title p {
            color: var(--text-muted);
            margin-top: 10px;
            font-size: 1.15rem;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px; left: 50%;
            transform: translateX(-50%);
            width: 60px; height: 4px;
            background: var(--accent-gold);
            border-radius: 2px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .modern-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            transition: var(--transition);
            border: 1px solid #edf2f7;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .modern-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px rgba(0,0,0,0.05);
            border-color: var(--gold-solid);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--gold-solid);
            margin-bottom: 1.2rem;
            transition: var(--transition);
        }

        .modern-card:hover .card-icon {
            background: var(--accent-gold);
            color: white;
        }

        /* --- PROGRAMS (TABS) --- */
        .tab-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 12px 30px;
            background: white;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-muted);
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
            transition: var(--transition);
        }

        .tab-btn:hover {
            color: var(--primary-dark);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .tab-btn.active {
            background: var(--primary-dark);
            color: white;
            border-color: var(--primary-dark);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.5s ease forwards; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .program-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .program-card {
            background: white;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            border: 1px solid #edf2f7;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .program-card::after {
            content: '\f105'; /* FontAwesome right arrow */
            font-family: "Font Awesome 6 Free"; font-weight: 900;
            position: absolute; bottom: 2.5rem; right: 2rem;
            font-size: 1.2rem; color: var(--gold-solid);
            opacity: 0; transform: translateX(-10px);
            transition: var(--transition);
        }

        .program-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(176, 141, 72, 0.1);
            border-color: var(--gold-solid);
        }

        .program-card:hover::after {
            opacity: 1; transform: translateX(0);
        }

        .pc-icon {
            width: 55px; height: 55px;
            background: rgba(176, 141, 72, 0.1);
            color: var(--gold-solid);
            border-radius: 14px;
            display: flex; justify-content: center; align-items: center;
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .program-card:hover .pc-icon {
            background: var(--accent-gold);
            color: white;
        }

        .program-card h3 { font-size: 1.3rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 0.8rem; }
        .program-card p { font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0; padding-right: 20px;}

        /* --- LOWONGAN SECTION (Gaya MAGENTA) --- */
        .lowongan-stats {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.1rem;
            color: var(--text-muted);
        }

        .lowongan-stat-count {
            font-weight: 700;
            color: var(--primary-dark);
        }

        .lowongan-stat-divider {
            margin: 0 0.5rem;
            color: var(--gold-solid);
        }

        .lowongan-search-wrapper {
            max-width: 900px;
            margin: 0 auto 2.5rem;
        }

        .lowongan-search-form {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .lowongan-search-main {
            display: flex;
            overflow: hidden;
            align-items: center;
            background: white;
            border-radius: 999px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(148, 163, 184, 0.4);
            padding: 0.35rem 0.4rem 0.35rem 0.9rem;
            gap: 0.5rem;
        }

        .lowongan-search-icon {
            color: var(--gold-solid);
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lowongan-search-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 0.95rem;
            padding: 0.55rem 0.75rem;
            background: transparent;
            color: var(--text-main);
            width: 50%;
        }

        .lowongan-search-input::placeholder {
            color: #94a3b8;
        }

        .lowongan-search-button {
            border: none;
            height: 100%;
            border-radius: 999px;
            padding: 0.55rem 1.5rem;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            background: var(--accent-gold);
            color: #fff;
            white-space: nowrap;
            box-shadow: 0 8px 18px rgba(176, 141, 72, 0.35);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .lowongan-search-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(176, 141, 72, 0.45);
        }

        .lowongan-search-button i {
            font-size: 0.85rem;
        }

        .lowongan-search-clear {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f1f5f9;
            color: var(--text-muted);
            text-decoration: none;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .lowongan-search-clear:hover {
            background: #e2e8f0;
            color: var(--primary-dark);
            transform: scale(1.1);
        }

        /* Mobile: pil pencarian tidak muat 1 baris (input + tombol + select per_page).
           Jadikan kotak membulat: input baris 1, tombol+select+clear baris 2. */
        @media (max-width: 576px) {
            .lowongan-search-main {
                flex-wrap: wrap;
                border-radius: 18px;
                padding: 0.6rem;
                gap: 0.5rem;
            }
            .lowongan-search-icon { display: none; }
            .lowongan-search-input {
                flex: 1 1 100%;
                width: auto;
                min-width: 0;
                padding: 0.55rem 0.5rem;
            }
            .lowongan-search-button {
                flex: 1 1 auto;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }
            #lowongan-per-page {
                flex: 1 1 auto;
                width: auto !important;
            }
            .lowongan-search-clear { flex: 0 0 auto; }
        }

        .lowongan-search-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .chip {
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
        }

        .chip-active {
            border-color: var(--gold-solid);
            background: rgba(176, 141, 72, 0.1);
            color: var(--gold-solid);
        }

        .lowongan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .lowongan-grid-magenta {
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        }

        .lowongan-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: var(--transition);
            border: 1px solid #edf2f7;
            display: flex;
            flex-direction: column;
        }

        .lowongan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 48px rgba(176, 141, 72, 0.18), 0 4px 12px rgba(0,0,0,0.06);
            border-color: rgba(176, 141, 72, 0.4);
        }

        /* Kartu lowongan - tampilan lebih kaya */
        .lowongan-card-magenta {
            position: relative;
            background: linear-gradient(180deg, #ffffff 0%, #fefefe 100%);
        }

        .lowongan-card-magenta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--gold-solid) 0%, #d4af37 50%, var(--gold-solid) 100%);
            opacity: 0;
            transition: var(--transition);
        }

        .lowongan-card-magenta:hover::before {
            opacity: 1;
        }

        .lowongan-card-magenta .lowongan-card-header {
            padding: 1.5rem 1.5rem 1rem;
            text-align: left;
            background: linear-gradient(180deg, rgba(253, 250, 233, 0.5) 0%, transparent 100%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.6rem;
        }

        .lowongan-logo-wrap {
            width: 72px;
            height: 72px;
            margin: 0;
            border-radius: 14px;
            overflow: hidden;
            background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .lowongan-logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px;
        }

        .lowongan-logo-placeholder,
        .lowongan-logo-wrap.lowongan-logo-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(176, 141, 72, 0.12) 0%, rgba(142, 109, 47, 0.2) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--gold-solid);
        }

        .lowongan-company {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 700;
            margin: 0;
            line-height: 1.4;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .lowongan-company::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--gold-solid);
            border-radius: 50%;
            opacity: 0.7;
        }

        .lowongan-card-magenta .lowongan-card-body {
            padding: 1.35rem 1.5rem;
            flex: 1;
        }

        .lowongan-title {
            font-size: 1.12rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 1rem;
            line-height: 1.4;
            letter-spacing: -0.2px;
        }

        .lowongan-meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 0.9rem;
        }

        .lowongan-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 600;
            padding: 6px 12px;
            background: rgba(248, 250, 252, 0.9);
            border-radius: 10px;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .lowongan-meta-item i {
            font-size: 0.9rem;
            color: var(--gold-solid);
        }

        .lowongan-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .lowongan-meta-divider {
            margin: 0 0.35rem;
            color: var(--gold-solid);
        }

        .lowongan-date-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .lowongan-date-group span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lowongan-date-group span i {
            color: var(--gold-solid);
            font-size: 0.75rem;
            width: 16px;
            text-align: center;
        }

        .lowongan-date-group strong {
            color: var(--primary-dark);
            font-weight: 700;
        }

        .lowongan-header {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .lowongan-image {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .lowongan-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .lowongan-card:hover .lowongan-image img {
            transform: scale(1.1);
        }

        .lowongan-image-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(176, 141, 72, 0.1) 0%, rgba(142, 109, 47, 0.2) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--gold-solid);
        }

        .lowongan-badge {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-status.aktif {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .lowongan-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .lowongan-body h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .lowongan-unit,
        .lowongan-posisi,
        .lowongan-deadline {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lowongan-unit i,
        .lowongan-posisi i,
        .lowongan-deadline i {
            color: var(--gold-solid);
            width: 16px;
        }

        .lowongan-detail {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: var(--text-main);
            line-height: 1.6;
            flex: 1;
        }

        .lowongan-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #edf2f7;
            background: #f8fafc;
        }

        .lowongan-card-magenta .lowongan-card-footer {
            padding: 1rem 1.5rem 1.25rem;
            border-top: 1px solid rgba(226, 232, 240, 0.9);
            background: linear-gradient(180deg, #fafbfc 0%, #f8fafc 100%);
        }

        .btn-lowongan {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.65rem 1.25rem;
            background: linear-gradient(135deg, transparent 0%, transparent 100%);
            border: 1.5px solid var(--gold-solid);
            color: var(--gold-solid);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.82rem;
            transition: var(--transition);
            justify-content: center;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .btn-lowongan::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--gold-solid) 0%, #c9a227 100%);
            opacity: 0;
            transition: var(--transition);
            z-index: 0;
        }

        .btn-lowongan span, .btn-lowongan i {
            position: relative;
            z-index: 1;
        }

        .btn-lowongan i {
            font-size: 0.7rem;
            transition: transform 0.25s ease;
        }

        .btn-lowongan:hover {
            color: #fff;
            border-color: transparent;
        }

        .btn-lowongan:hover::before {
            opacity: 1;
        }

        .btn-lowongan:hover i {
            transform: translateX(4px);
        }

        .lowongan-empty {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 20px;
            border: 2px dashed #e2e8f0;
        }

        .lowongan-empty i {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .lowongan-empty p {
            font-size: 1.1rem;
            color: var(--text-muted);
        }

        /* --- CUSTOM PAGINATION --- */
        .lowongan-pagination-wrapper {
            margin: 3rem auto 4rem;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .lowongan-pagination {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 0 12px;
            border-radius: 12px;
            background: white;
            border: 1.5px solid #e2e8f0;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .pagination-btn:hover:not(.pagination-btn-disabled):not(.pagination-btn-active) {
            background: rgba(176, 141, 72, 0.1);
            border-color: var(--gold-solid);
            color: var(--gold-solid);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(176, 141, 72, 0.2);
        }

        .pagination-btn-active {
            background: var(--accent-gold);
            border-color: var(--gold-solid);
            color: white;
            box-shadow: 0 4px 15px rgba(176, 141, 72, 0.3);
        }

        .pagination-btn-disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background: #f1f5f9;
        }

        .pagination-info {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 600;
            text-align: center;
        }

        @media (max-width: 768px) {
            .lowongan-pagination {
                gap: 0.35rem;
            }
            .pagination-btn {
                min-width: 38px;
                height: 38px;
                padding: 0 10px;
                font-size: 0.85rem;
            }
            .pagination-info {
                font-size: 0.85rem;
            }
        }

        /* --- MODAL IMPROVEMENTS --- */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 3rem;
            width: 90%;
            max-width: 700px;
            border-radius: 24px;
            position: relative;
            animation: slideUp 0.4s ease-out;
            display: flex;
            flex-direction: column;
            max-height: 85vh;
            border: 1px solid rgba(176, 141, 72, 0.35);
            text-align: justify;
        }

        .modal-scrollable {
            flex: 1;
            overflow-y: auto;
            padding-right: 15px;
            margin-right: -15px;
        }

        .modal-scrollable::-webkit-scrollbar {
            width: 8px;
        }

        .modal-scrollable::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .modal-scrollable::-webkit-scrollbar-thumb {
            background: var(--gold-solid);
            border-radius: 10px;
        }

        .modal-scrollable::-webkit-scrollbar-thumb:hover {
            background: #8e6d2f;
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .close {
            position: absolute;
            right: 25px; top: 20px;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted);
        }

        .modal h3 {
            color: var(--primary-dark);
            margin: 1.5rem 0 1rem;
            font-size: 1.2rem;
            border-left: 4px solid var(--gold-solid);
            padding-left: 15px;
        }

        .modal p {
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .section-tag {
            display: inline-block;
            padding: 0.5rem 1.2rem;
            background: var(--accent-gold);
            color: white;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
        }

        /* Di dalam .section-title, badge harus di baris sendiri & center di atas judul
           (judul memakai display:inline-block sehingga tanpa ini badge sejajar judul). */
        .section-title .section-tag {
            display: block;
            width: max-content;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 1rem;
        }

        /* Text Center Utility */
        .text-center {
            text-align: center;
        }

        /* === RESPONSIVE === */
        @media (max-width: 991px) {
            /* Chrome responsive (header/nav/logo/buttons) → partial: auth.partials.public-chrome-head */
            .hero {
                height: 60vh;
                padding: 0 5%;
                margin-top: 70px;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .smart-card {
                padding: 2rem 1.5rem;
                margin-top: -60px;
            }

            .grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.25rem;
            }

            .modern-card {
                padding: 1.5rem;
            }

            .tab-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .program-grid {
                grid-template-columns: 1fr;
            }

            .lowongan-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .lowongan-card {
                border-radius: 16px;
            }

            .lowongan-body,
            .lowongan-card-magenta .lowongan-card-body {
                padding: 1.25rem;
            }

            .lowongan-body h3,
            .lowongan-title {
                font-size: 1.1rem;
            }

            .modal-content {
                width: 95%;
                padding: 2rem 1.5rem;
                margin: 10% auto;
            }
        }

        @media (max-width: 768px) {
            /* Chrome responsive (header/nav/logo/buttons) → partial: auth.partials.public-chrome-head */
            .hero {
                height: 50vh;
                padding: 0 3%;
            }

            .hero-content h1 {
                font-size: 1.5rem;
                line-height: 1.3;
            }

            .hero-content p {
                font-size: 0.9rem;
            }

            .smart-card {
                padding: 1.5rem 1rem;
                margin-top: -50px;
                border-radius: 20px;
            }

            .section-title {
                margin: 3rem 0 2rem;
            }

            .section-title h2 {
                font-size: 1.5rem;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .modern-card {
                padding: 1.25rem;
            }

            .card-icon {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .modal-content {
                width: 98%;
                padding: 1.5rem 1rem;
                margin: 5% auto;
                max-height: 90vh;
            }

            .modal h3 {
                font-size: 1.1rem;
            }

            .modal p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .hero {
                height: 45vh;
            }

            .hero-content h1 {
                font-size: 1.3rem;
            }

            .smart-card {
                padding: 1.25rem 0.75rem;
            }

            .section-title h2 {
                font-size: 1.3rem;
            }

            .modern-card h3 {
                font-size: 1.1rem;
            }

            .modern-card p {
                font-size: 0.85rem;
            }
        }

        /* Tahapan Pelaksanaan Program Magang - Alur 1-2-3-4, card untuk teks saja */
        .tahapan-section {
            margin-top: 3rem;
            padding: 2.5rem 0;
            text-align: center;
        }
        .tahapan-section .section-tag {
            display: inline-block;
            padding: 0.5rem 1.2rem;
            background: var(--accent-gold);
            color: white;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }
        .tahapan-section h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 2.5rem;
        }
        .tahapan-flow {
            display: flex;
            align-items: stretch; /* supaya semua item punya tinggi yang sama */
            justify-content: center;
            gap: 2rem;
            position: relative;
            padding: 0 0.5rem;
            flex-wrap: wrap;
        }
        @media (max-width: 991px) {
            .tahapan-flow {
                flex-direction: column;
                align-items: center;
                gap: 2.5rem;
                padding: 0;
            }
        }
        .tahapan-item {
            width: 240px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }
        @media (max-width: 991px) {
            .tahapan-item {
                width: 100%;
                max-width: 320px;
            }
        }
        .tahapan-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 32px;
            left: calc(50% + 32px);
            width: calc(100% + 2rem - 64px);
            height: 3px;
            background: var(--gold-solid);
            z-index: 0;
        }
        @media (max-width: 991px) {
            .tahapan-item:not(:last-child)::after {
                top: auto;
                bottom: -1.25rem;
                left: 50%;
                width: 3px;
                height: calc(2.5rem + 1.25rem);
                background: var(--gold-solid);
                transform: translateX(-50%);
            }
        }
        .tahapan-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--accent-gold);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(176, 141, 72, 0.35);
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .tahapan-circle i {
            font-size: 1.5rem;
        }
        .tahapan-item:hover .tahapan-circle {
            transform: scale(1.1);
            box-shadow: 0 8px 28px rgba(176, 141, 72, 0.45);
        }
        .tahapan-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem 1.25rem;
            text-align: center;
            border: 1px solid rgba(176, 141, 72, 0.18);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            position: relative;
            z-index: 1;
            width: 100%;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            flex: 1; /* isi penuh tinggi parent supaya semua card rata */
            box-sizing: border-box;
        }
        .tahapan-item:hover .tahapan-card {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(176, 141, 72, 0.12);
            border-color: rgba(176, 141, 72, 0.35);
        }
        .tahapan-card h4 {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 1.25rem;
            line-height: 1.35;
        }
        .tahapan-card p {
            font-size: 0.85rem;
            color: #1e293b;
            margin: 0;
            line-height: 1.6;
        }

        /* === Dark mode: beranda === */
        html[data-skin="dark"] body { background: #020617; color: #ffffff; }
        html[data-skin="dark"] .section-title h2,
        html[data-skin="dark"] .section-title p { color: #ffffff !important; }
        html[data-skin="dark"] .tahapan-section h3 { color: #ffffff !important; }
        html[data-skin="dark"] .tahapan-card h4 { color: #ffffff !important; }
        html[data-skin="dark"] .smart-card,
        html[data-skin="dark"] .modern-card {
            background: #0f172a !important;
            border-color: rgba(251, 191, 36, 0.45) !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .modern-card:hover { border-color: #fbbf24 !important; box-shadow: 0 20px 40px rgba(0,0,0,0.6); }
        html[data-skin="dark"] .card-icon { background: #1e293b !important; color: #fbbf24 !important; }
        html[data-skin="dark"] .modern-card:hover .card-icon { background: #fbbf24 !important; color: #020617 !important; }
        html[data-skin="dark"] .modal-content {
            background: #0f172a !important;
            border-color: rgba(251, 191, 36, 0.45) !important;
        }
        html[data-skin="dark"] .modal h3 {
            color: #ffffff !important;
            border-left-color: #fbbf24 !important;
        }
        html[data-skin="dark"] .modal p {
            color: #e5e7eb !important;
        }
        html[data-skin="dark"] .close {
            color: #9ca3af !important;
        }
        html[data-skin="dark"] .tab-btn { background: #1e293b !important; border-color: #374151; color: #ffffff !important; }
        html[data-skin="dark"] .tab-btn.active { background: #0f172a !important; border-color: #fbbf24; color: #fbbf24 !important; }
        html[data-skin="dark"] .lowongan-stat-count{ color: #ffffff !important; }
        html[data-skin="dark"] .lowongan-card,
        html[data-skin="dark"] .lowongan-item,
        html[data-skin="dark"] [class*="card"] {
            background: #0f172a !important;
            border-color: rgba(251, 191, 36, 0.35) !important;
        }
        html[data-skin="dark"] .lowongan-card h3,
        html[data-skin="dark"] .lowongan-card p,
        html[data-skin="dark"] .lowongan-item *,
        html[data-skin="dark"] h2, html[data-skin="dark"] h3, html[data-skin="dark"] p { color: #ffffff !important; }
        html[data-skin="dark"] .text-muted,
        html[data-skin="dark"] .text-secondary { color: #9ca3af !important; }
        /* Loading saat pindah halaman (landing Gerbang Kompetensi & seluruh index auth) */
        .auth-page-loading-overlay {
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
        .auth-page-loading-overlay.show {
            display: flex;
            opacity: 1;
        }
        .auth-page-loading-card {
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
            animation: auth-landing-loader-pop 220ms ease-out forwards;
        }
        @keyframes auth-landing-loader-pop {
            to { transform: translateY(0) scale(1); }
        }
        .auth-page-loading-mark {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(176, 141, 72, 0.12);
            border: 1px solid rgba(176, 141, 72, 0.28);
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }
        .auth-page-loading-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2px;
        }
        .auth-page-loading-dpr-logo {
            width: 46px;
            height: auto;
            flex-shrink: 0;
        }
        .auth-page-loading-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
            align-items: flex-start;
        }
        .auth-page-loading-brand-title {
            font-weight: 1000;
            letter-spacing: -0.4px;
            font-size: 1.25rem;
            color: #0f172a;
        }
        .auth-page-loading-brand-sub {
            font-size: 0.92rem;
            color: #64748b;
            font-weight: 700;
            margin-top: 2px;
        }
        .auth-page-loading-message {
            font-weight: 900;
            letter-spacing: -0.3px;
            font-size: 1.35rem;
            color: #0f172a;
        }
        .auth-page-loading-submessage {
            font-size: 0.98rem;
            color: #475569;
            margin-top: 2px;
        }
        .auth-page-loading-spinner {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            border: 3px solid rgba(176, 141, 72, 0.22);
            border-top-color: #b08d48;
            animation: auth-landing-loader-spin 0.75s linear infinite;
        }
        @keyframes auth-landing-loader-spin { to { transform: rotate(360deg); } }
        html[data-skin="dark"] .auth-page-loading-overlay {
            background:
                radial-gradient(1200px 500px at 20% 10%, rgba(251, 191, 36, 0.10), transparent 55%),
                radial-gradient(900px 450px at 85% 30%, rgba(99, 102, 241, 0.12), transparent 55%),
                rgba(2, 6, 23, 0.72);
        }
        html[data-skin="dark"] .auth-page-loading-card {
            background: rgba(15, 23, 42, 0.82);
            border-color: rgba(55, 65, 81, 0.95);
            box-shadow: 0 30px 90px rgba(0,0,0,0.55);
        }
        html[data-skin="dark"] .auth-page-loading-mark {
            background: rgba(251, 191, 36, 0.10);
            border-color: rgba(251, 191, 36, 0.26);
        }
        html[data-skin="dark"] .auth-page-loading-spinner {
            border-color: rgba(251, 191, 36, 0.18);
            border-top-color: #fbbf24;
        }
        html[data-skin="dark"] .auth-page-loading-message { color: #ffffff; }
        html[data-skin="dark"] .auth-page-loading-submessage { color: #cbd5e1; }
        html[data-skin="dark"] .auth-page-loading-brand-title { color: #ffffff; }
        html[data-skin="dark"] .auth-page-loading-brand-sub { color: #cbd5e1; }

    </style>
    {{-- Chrome header/footer (CSS + skin toggle script) bersama dengan halaman FAQ --}}
    @include('auth.partials.public-chrome-head')
</head>
<body>

<div class="bg-pattern"></div>

<div class="auth-page-loading-overlay" id="authLandingPageLoader" aria-hidden="true">
    <div class="auth-page-loading-card" role="status" aria-live="polite" aria-label="Tolong menunggu sesaat">
        <div class="auth-page-loading-brand">
            <img class="auth-page-loading-dpr-logo" src="{{ asset('theme/admin-dashbyte/dist/assets/img/logo.png') }}" alt="DPR RI">
            <div class="auth-page-loading-brand-text">
                <div class="auth-page-loading-brand-title">SMART</div>
                <div class="auth-page-loading-brand-sub">Sistem Magang Administratif, Responsif, dan Terintegrasi</div>
            </div>
        </div>
        <div class="auth-page-loading-message">Tolong menunggu sesaat</div>
        <div class="auth-page-loading-submessage" aria-hidden="true">Sedang memuat halaman...</div>
        <div class="auth-page-loading-mark" aria-hidden="true">
            <div class="auth-page-loading-spinner"></div>
        </div>
    </div>
</div>

@include('auth.partials.public-header')

@php $onlyLowongan = $onlyLowongan ?? false; @endphp

@unless($onlyLowongan)
<section class="hero">
    <img src="theme/admin-dashbyte/dist/assets/img/home3.jpg" class="hero-img" alt="Gedung DPR RI">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Gerbang Kompetensi<br>Masa Depan Legislatif</h1>
        <p>Platform terintegrasi untuk Magang, Praktik Kerja Lapangan, dan Penelitian di Sekretariat Jenderal DPR RI.</p>
    </div>
</section>
@endunless

<div class="container"@if($onlyLowongan ?? false) style="padding-top: 100px;"@endif>
    @unless($onlyLowongan)
    <div class="smart-card">
        <span class="section-tag">Tentang Platform</span>
        <p style="font-size: 1.1rem; color: var(--text-main); max-width: 900px; margin: 0 auto;">
            <strong>{{ config('app.name') }}</strong> adalah ekosistem digital dari PUSBANGKOM <b>Setjen</b> DPR RI. Kami hadir untuk memfasilitasi mahasiswa dan pelajar dalam mengembangkan kompetensi, mengelola administrasi secara transparan, serta berkolaborasi langsung dalam lingkungan kerja legislatif Indonesia.
        </p>
    </div>

    <div class="tahapan-section">
        <span class="section-tag">Alur Magang</span>
        <h3>Tahapan Pelaksanaan Program Magang</h3>
        <div class="tahapan-flow">
            <div class="tahapan-item">
                <div class="tahapan-circle"><i class="fa-solid fa-file-signature" aria-hidden="true"></i></div>
                <div class="tahapan-card">
                    <h4>Pendaftaran Calon Peserta Magang</h4>
                    <p>Calon peserta mendaftar secara online melalui formulir pendaftaran dan mengunggah berkas persyaratan.</p>
                </div>
            </div>
            <div class="tahapan-item">
                <div class="tahapan-circle"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></div>
                <div class="tahapan-card">
                    <h4>Seleksi Calon Peserta Magang</h4>
                    <p>Tim PUSBANGKOM melakukan verifikasi berkas seluruh calon peserta.</p>
                </div>
            </div>
            <div class="tahapan-item">
                <div class="tahapan-circle"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></div>
                <div class="tahapan-card">
                    <h4>Penetapan Peserta Magang</h4>
                    <p>Calon peserta yang lolos seleksi ditetapkan sebagai peserta magang dan memperoleh informasi penempatan pada unit kerja.</p>
                </div>
            </div>
            <div class="tahapan-item">
                <div class="tahapan-circle"><i class="fa-solid fa-briefcase" aria-hidden="true"></i></div>
                <div class="tahapan-card">
                    <h4>Pelaksanaan Program Magang</h4>
                    <p>Peserta memulai program magang di unit kerja yang telah ditetapkan sesuai dengan jadwal yang berlaku.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="section-title">
        <h2>Program Pilihan</h2>
        <p>Pilih kategori yang sesuai dengan kebutuhan akademik dan kualifikasi Anda</p>
    </div>

    <div class="tab-controls">
        <button class="tab-btn active" onclick="openTab('pkl')">Praktik Kerja Lapangan</button>
        <button class="tab-btn" onclick="openTab('magang')">Program Magang</button>
        <button class="tab-btn" onclick="openTab('penelitian')">Riset & Penelitian</button>
    </div>

    <!-- TAB 1: PKL -->
    <div id="pkl" class="tab-content active">
        <div class="program-grid">
            <div class="program-card" onclick="openModal('modal1')">
                <div class="pc-icon"><i class="fa-solid fa-file-signature"></i></div>
                <h3>Pendaftaran</h3>
                <p>Program Praktik Kerja Lapangan ditujukan bagi siswa/i SMA/SMK sederajat.</p>
            </div>
            <div class="program-card" onclick="openModal('modal2')">
                <div class="pc-icon"><i class="fa-solid fa-briefcase"></i></div>
                <h3>Pelaksanaan</h3>
                <p>Informasi detail unit penempatan dan tata tertib selama bertugas.</p>
            </div>
            <div class="program-card" onclick="openModal('modal3')">
                <div class="pc-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <h3>Penyelesaian</h3>
                <p>Prosedur penilaian, pengumpulan laporan akhir, dan pengambilan sertifikat.</p>
            </div>
        </div>
    </div>

    <!-- TAB 2: Magang -->
    <div id="magang" class="tab-content">
        <div class="program-grid">
            <div class="program-card" onclick="openModal('modal4')">
                <div class="pc-icon"><i class="fa-solid fa-user-graduate"></i></div>
                <h3>Pendaftaran</h3>
                <p>Program Magang ini ditujukan bagi mahasiswa/i aktif di Perguruan Tinggi/Universitas/Akademi/Politeknik sederajat.</p>
            </div>
            <div class="program-card" onclick="openModal('modal5')">
                <div class="pc-icon"><i class="fa-solid fa-laptop-file"></i></div>
                <h3>Pelaksanaan</h3>
                <p>Mekanisme kerja di bagian/bidang sesuai dengan kompetensi akademik.</p>
            </div>
            <div class="program-card" onclick="openModal('modal6')">
                <div class="pc-icon"><i class="fa-solid fa-award"></i></div>
                <h3>Penyelesaian</h3>
                <p>Prosedur penilaian, pengumpulan laporan akhir, dan pengambilan sertifikat.</p>
            </div>
        </div>
    </div>

    <!-- TAB 3: Penelitian -->
    <div id="penelitian" class="tab-content">
        <div class="program-grid">
            <div class="program-card" onclick="openModal('modal7')">
                <div class="pc-icon"><i class="fa-solid fa-microscope"></i></div>
                <h3>Pendaftaran</h3>
                <p>Prosedur registrasi online dan persiapan berkas fisik persyaratan penelitian.</p>
            </div>
            <div class="program-card" onclick="openModal('modal8')">
                <div class="pc-icon"><i class="fa-solid fa-chart-pie"></i></div>
                <h3>Pelaksanaan</h3>
                <p>Informasi detail unit penempatan dan tata tertib selama melakukan penelitian.</p>
            </div>
            <div class="program-card" onclick="openModal('modal9')">
                <div class="pc-icon"><i class="fa-solid fa-book-journal-whills"></i></div>
                <h3>Penyelesaian</h3>
                <p>Prosedur penilaian, pengumpulan laporan akhir, dan konfirmasi pengiriman hasil penelitian.</p>
            </div>
        </div>
    </div>
    @endunless

    <!-- Section Lowongan - Gaya MAGENTA -->
    @if($onlyLowongan)
    <div class="section-title" style="margin-top: 2.5rem;">
        <h2>Lowongan Khusus</h2>
        <p>Telusuri seluruh lowongan magang/PKL yang tersedia. Gunakan pencarian untuk menemukan posisi yang sesuai.</p>
    </div>
    @else
    <div class="section-title" style="margin-top: 6rem;">
        <h2>Temukan Karier yang Paling Sesuai untuk Kamu</h2>
        <p>{{ config('app.name') }} menawarkan berbagai peluang yang sesuai dengan keterampilan dan ambisi Kamu. Daftar hari ini dan ambil langkah berikutnya menuju karier Impian. Masa depan Kamu dimulai di sini.</p>
    </div>
    @endif

    @if (session('error'))
        <div style="max-width: 900px; margin: 0 auto 18px; background: #fff1f2; border: 1px solid #fecdd3; color: #9f1239; padding: 12px 16px; border-radius: 14px; font-weight: 700;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span style="margin-left: 8px;">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Searchbar ala MAGENTAKU: Temukan Karier --}}
    <div class="lowongan-search-wrapper" id="lowongan-section">
        <form action="{{ $onlyLowongan ? route('lowongan.khusus') : route('Halaman awal').'#lowongan-section' }}" method="GET" class="lowongan-search-form" id="lowongan-search-form">
            <div class="lowongan-search-main">
                <span class="lowongan-search-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input
                    type="text"
                    name="q"
                    id="lowongan-search-input"
                    class="lowongan-search-input"
                    placeholder="Cari lowongan magang di DPR RI"
                    value="{{ $query ?? '' }}"
                    autocomplete="off"
                >
                <button type="submit" class="lowongan-search-button">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Cari</span>
                </button>
                @if($onlyLowongan)
                <select name="per_page" id="lowongan-per-page" onchange="this.form.submit()" title="Jumlah lowongan per halaman"
                        style="flex-shrink:0; border:none; background:#f1f5f9; border-radius:12px; padding:0 14px; height:48px; font-weight:700; color:#334155; cursor:pointer;">
                    @foreach([6, 9, 12, 24] as $sz)
                        <option value="{{ $sz }}" {{ ($perPage ?? 9) == $sz ? 'selected' : '' }}>{{ $sz }} / halaman</option>
                    @endforeach
                </select>
                @endif
                <a href="{{ $onlyLowongan ? route('lowongan.khusus') : route('Halaman awal').'#lowongan-section' }}" class="lowongan-search-clear" id="lowongan-search-clear" title="Hapus pencarian" style="display: {{ $query ? 'flex' : 'none' }};">
                    <i class="fa-solid fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Container untuk hasil search (akan di-update via AJAX) --}}
    <div id="lowongan-results-container">
        @include('auth.partials.lowongan-list', ['lowongan' => $lowongan, 'totalPelamarAktif' => $totalPelamarAktif, 'query' => $query, 'ajaxPagination' => ! $onlyLowongan])
    </div>

    {{-- Tombol lihat semua lowongan (hanya di beranda, bukan di halaman khusus) --}}
    @if(! $onlyLowongan && $lowongan && $lowongan->total() > 0)
    <style>
        .lowongan-more { text-align: center; margin-top: 1.5rem; }
        .lowongan-more a { display: inline-flex; align-items: center; gap: 8px; padding: 0.8rem 1.8rem; border-radius: 12px; background: var(--accent-gold); color: #fff; text-decoration: none; font-weight: 700; transition: all 0.3s ease; }
        .lowongan-more a:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(176,141,72,0.3); }
    </style>
    <div class="lowongan-more">
        <a href="{{ route('lowongan.khusus') }}">Lihat Semua Lowongan <i class="ri-arrow-right-line"></i></a>
    </div>
    @endif

    @if(isset($faqs) && $faqs->count())
    <style>
        .home-faq-section { max-width: 900px; margin: 6rem auto 2rem; width: 92%; }
        .home-faq-item { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; margin-bottom: 1rem; overflow: hidden; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(15,23,42,0.03); }
        .home-faq-item.open { border-color: var(--gold-solid); box-shadow: 0 8px 20px rgba(176,141,72,0.12); }
        .home-faq-q { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 1.15rem 1.4rem; cursor: pointer; font-weight: 700; color: var(--primary-dark); font-size: 1rem; user-select: none; }
        .home-faq-q .icon { flex-shrink: 0; width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(176,141,72,0.12); color: var(--gold-solid); transition: all 0.3s ease; }
        .home-faq-item.open .home-faq-q .icon { transform: rotate(180deg); background: var(--gold-solid); color: #fff; }
        .home-faq-a { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; padding: 0 1.4rem; }
        .home-faq-a .inner { padding: 0 0 1.2rem; color: #475569; }
        .home-faq-more { text-align: center; margin-top: 1.5rem; }
        .home-faq-more a { display: inline-flex; align-items: center; gap: 8px; padding: 0.8rem 1.8rem; border-radius: 12px; background: var(--accent-gold); color: #fff; text-decoration: none; font-weight: 700; transition: all 0.3s ease; }
        .home-faq-more a:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(176,141,72,0.3); }
        html[data-skin="dark"] .home-faq-item { background: #0f172a !important; border-color: #1f2937 !important; }
        html[data-skin="dark"] .home-faq-q { color: #fff !important; }
        html[data-skin="dark"] .home-faq-a .inner { color: #cbd5e1 !important; }
    </style>
    <div class="section-title" style="margin-top: 6rem;">
        <span class="section-tag">Pusat Bantuan</span>
        <h2>Pertanyaan yang Sering Diajukan</h2>
        <p>Jawaban atas pertanyaan umum seputar program magang di DPR RI.</p>
    </div>
    <div class="home-faq-section">
        @foreach($faqs as $faq)
            <div class="home-faq-item">
                <div class="home-faq-q" onclick="toggleHomeFaq(this)">
                    <span>{{ $faq->pertanyaan }}</span>
                    <span class="icon"><i class="ri-arrow-down-s-line"></i></span>
                </div>
                <div class="home-faq-a">
                    <div class="inner">{!! nl2br(e($faq->jawaban)) !!}</div>
                </div>
            </div>
        @endforeach
        <div class="home-faq-more">
            <a href="{{ route('faq') }}">Lihat Semua FAQ <i class="ri-arrow-right-line"></i></a>
        </div>
    </div>
    <script>
        function toggleHomeFaq(el) {
            const item = el.closest('.home-faq-item');
            const answer = item.querySelector('.home-faq-a');
            if (item.classList.contains('open')) {
                item.classList.remove('open');
                answer.style.maxHeight = null;
            } else {
                item.classList.add('open');
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        }
    </script>
    @endif
</div>

<div id="modal1" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal1')">&times;</span>
        <div class="modal-scrollable">
            <h2 style="color: var(--gold-solid)">Prosedur Pendaftaran PKL</h2>
            <h3>A. Pendaftaran Online</h3>
            <ol style="margin-left: 60px;">
                <li>Lakukan registrasi akun pada halaman pendaftaran dan lengkapi data profil sekolah.</li>
            </ol>
            <h3>B. Berkas Fisik (Hardcopy)</h3>
            <ol style="margin-left: 60px;">
                <li>Surat Pengantar Sekolah (Ditujukan ke: Kapusbangkom SDM Legislatif)</li>
                <li>Curriculum Vitae (Daftar Riwayat Hidup)</li>
                <li>Pas Foto 2x3 latar merah (2 lembar)</li>
                <li>Fotokopi Kartu Pelajar (2 lembar) </li>
                <li>Surat Rekomendasi (opsional)</li>
            </ol>
        </div>
    </div>
</div>

<div id="modal2" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal2')">&times;</span>
        <div class="modal-scrollable">
            <h2 style="color: var(--gold-solid)">Pelaksanaan Praktik Kerja Lapangan</h2>
            <h3>A. Informasi Detail Tentang Pelaksanaan Praktik Kerja Lapangan</h3>
            <ol style="margin-left: 60px;">
                <li>Peserta Mengambil Dokumen PKL</li>
                <li>Peserta Melaksanakan PKL di Bagian/Bidang/Unit Kerja</li>
                <li>Setelah Selesai PKL, peserta mengikuti mekanisme sesuai ketentuan yang berlaku</li>
            </ol>
            <h3>B. Tata Tertib Pelaksanaan Praktik Kerja Lapangan</h3>
            <ol style="margin-left: 60px;">
                <li>Menjaga nilai-nilai <b>Setjen</b> DPR RI yakni BERAKHLAK (Berorientasi Pelayanan Akuntabel Kompeten Harmonis Loyal Adaptif Kolaboratif)</li>
                <li>Bersikap sopan dan santun selama di lingkungan <b>Setjen</b> DPR RI</li>
                <li>Menggunakan pakaian seragam sekolah/jas almamater </li>
                <li>Berpakaian rapi dan sopan</li>
                <li>Rambut rapi, tidak panjang bagi laki-laki</li>
                <li>Wajib Mengisi Presensi Kehadiran dan Catatan Harian Kegiatan PKL</li>
                <li>Datang dan pulang tepat waktu sesuai dengan jam kantor pukul 08.00 s.d 15.00 WIB (Menyesuaikan dengan Bagian/Bidang/Unit Kerja)</li>
            </ol>
        </div>
    </div>
</div>

<div id="modal3" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal3')">&times;</span>
        <div class="modal-scrollable">
            <h2 style="color: var(--gold-solid)">Penilaian Praktik Kerja Lapangan</h2>
            <h3>Informasi Detail Tentang Pasca Pelaksanaan Praktik Kerja Lapangan</h3>
            <ol style="margin-left: 60px;">
                <li>Silahkan meminta nilai dari pembimbing di Bagian/Bidang/Unit Kerja secara online </li>
                <li>Meminta Nota Dinas Keterangan Selesai PKL dari Bidang/Bagian/Unit Kerja Anda untuk dikirimkan kepada Pusbangkom SDM Legislatif <b>Setjen</b> DPR RI</li>
                <li>Mengupload Laporan Akhir secara online </li>
                <li>Mengisi Form Testimonial</li>
            </ol>
        </div>
    </div>
</div>

<div id="modal4" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal4')">&times;</span>
        <div class="modal-scrollable">
            <h2 style="color: var(--gold-solid)">Prosedur Pendaftaran Magang</h2>
            <h3>A. Melakukan Pendaftaran pada Halaman Registrasi</h3>
            <h3>B. Mempersiapkan Berkas Fisik Persyaratan Magang untuk diserahkan ke <b>Setjen</b> DPR RI</h3>
            <ol style="margin-left: 60px;">
                <li>Surat Pengantar dari Perguruan Tinggi/Universitas yang ditujukan kepada: Kepala Pusbangkom SDM Legislatif <b>Setjen</b> DPR RI</li>
                <li>Kartu Rencana Studi dan Kartu Hasil Studi/Transkrip Nilai</li>
                <li>Curriculum Vitae (Daftar Riwayat Hidup)</li>
                <li>Pas foto terbaru dengan ukuran 2x3 (2 lembar)</li>
                <li>Fotokopi KTM (2 lembar) </li>
                <li>Motivation Letter yang ditulis tangan (berisi kelebihan diri, kekurangan diri, kemampuan, motivasi mendaftar, dan harapan)</li>
                <li>Proposal Magang berisi latar belakang, tujuan, dan rencana kegiatan magang (opsional)</li>
                <li>Surat Rekomendasi (opsional)</li>
            </ol>
        </div>
    </div>
</div>

<div id="modal5" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal5')">&times;</span>
        <div class="modal-scrollable">
            <h2 style="color: var(--gold-solid)">Pelaksanaan Magang</h2>
            <h3>A. Informasi Detail Tentang Pelaksanaan Magang</h3>
            <ol style="margin-left: 60px;">
                <li>Peserta Melaksanakan Magang di Bagian/Bidang/Unit Kerja</li>
                <li>Setelah Selesai Magang mengikuti mekanisme sesuai ketentuan yang berlaku</li>
            </ol>
            <h3>B. Tata Tertib Pelaksanaan Magang</h3>
            <ol style="margin-left: 60px;">

                <li>Menjaga nilai-nilai <b>Setjen</b> DPR RI yakni BERAKHLAK (Berorientasi Pelayanan Akuntabel Kompeten Harmonis Loyal Adaptif Kolaboratif)</li>
                <li>Bersikap sopan dan santun selama di lingkungan <b>Setjen</b> DPR RI</li>
                <li>Berpakaian rapi dan sopan</li>
                <li>Tidak memakai celana jeans, gunakan celana bahan</li>
                <li>Tidak memakai kaos, gunakan kemeja</li>
                <li>Rambut rapi, tidak panjang bagi laki-laki</li>
                <li>Wajib Mengisi Presensi Kehadiran dan Catatan Harian Kegiatan Magang</li>
                <li>Datang dan pulang tepat waktu sesuai dengan jam kantor pukul 08.00 s.d 15.00 WIB (Menyesuaikan dengan Bagian/Bidang/Unit Kerja)</li>
            </ol>
        </div>
    </div>
</div>

<div id="modal6" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal6')">&times;</span>
        <div class="modal-scrollable">
            <h2 style="color: var(--gold-solid)">Pasca Pelaksanaan Magang</h2>
            <h3>Informasi Detail Tentang Pasca Pelaksanaan Magang</h3>
            <ol style="margin-left: 60px;">
                <li>Silahkan meminta nilai dari pembimbing di Bagian/Bidang/Unit Kerja secara online </li>
                <li>Meminta Nota Dinas Keterangan Selesai PKL dari Bidang/Bagian/Unit Kerja Anda untuk dikirimkan kepada Pusbangkom SDM Legislatif <b>Setjen</b> DPR RI</li>
                <li>Mengupload Laporan Akhir secara online </li>
                <li>Mengisi Form Testimonial</li>
            </ol>
        </div>
    </div>
</div>

<div id="modal7" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal7')">&times;</span>
        <div class="modal-scrollable">
            <h2 style="color: var(--gold-solid)">Prosedur Pendaftaran Penelitian</h2>
            <h3>A. Melakukan Pendaftaran pada Halaman Registrasi</h3>
            <h3>B. Mempersiapkan Berkas Fisik Persyaratan Penelitian untuk diserahkan ke <b>Setjen</b> DPR RI</h3>
            <ol style="margin-left: 60px;">
                <li>Surat Pengantar dari perguruan tinggi yang ditunjukan kepada: Kepala Pusat Pengembangan Kompetensi SDM Legislatif (PUSBANGKOM). <b>Setjen</b> DPR RI</li>
                <li>Curriculum Vitae (Daftar Riwayat Hidup)</li>
                <li>Pas Foto berlatar merah dengan ukuran 2x3 sebanyak 2 lembar</li>
                <li>Fotokopi kartu Pelajar/ Surat Keterangan dari Sekolah/ dan Surat Rekomendasi</li>
                <li>Proposal Penelitian dan Daftar Wawancara/kuesioner Penelitian</li>
            </ol>
        </div>
    </div>
</div>

<div id="modal8" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal8')">&times;</span>
        <div class="modal-scrollable">
            <h2 style="color: var(--gold-solid)">Pelaksanaan Penelitian</h2>
            <h3>A. Informasi Detail Tentang Pelaksanaan Penelitian</h3>
            <ol style="margin-left: 60px;">
                <li>Peserta Mengambil Dokumen Penelitian</li>
                <li>Peserta Melaksanakan Penelitian di Bagian/Bidang/Unit Penempatan</li>
                <li>Setelah Selesai Penelitian mengikuti mekanisme sesuai alur</li>
            </ol>
            <h3>B. Tata Tertib Pelaksanaan Penelitian</h3>
            <ol style="margin-left: 60px;">
                <li>Menjaga nilai-nilai <b>Setjen</b> RI yakni BERAKHLAK (Berorientasi Pelayanan Akuntabel Kompeten Harmonis Loyal Adaptif Kolaboratif)</li>
                <li>Bersikap sopan dan santun selama di lingkungan <b>Setjen</b> DPR RI</li>
                <li>Berpakaian rapi dan sopan</li>
                <li>Tidak memakai celana jeans, melainkan celana bahan</li>
                <li>Tidak memakai kaos, melainkan kemeja</li>
                <li>Rambut rapi, tidak panjang bagi laki-laki</li>
                <li>Datang tepat pada waktunya sesuai dengan jam kantor pukul 08.00 WIB</li>
                <li>Pulang tepat pada waktunya sesuai dengan jam kantor pukul 15.00 WIB (Menyesuaikan dengan Bagian/Bidang/Unit)</li>
            </ol>
        </div>
    </div>
</div>

<div id="modal9" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal9')">&times;</span>
        <div class="modal-scrollable">
            <h2 style="color: var(--gold-solid)">Pasca Pelaksanaan Penelitian</h2>
            <h3>Informasi Detail Tentang Pasca Pelaksanaan Penelitian</h3>
            <ol style="margin-left: 60px;">
                <li>Silahkan Meminta Nilai dari Pembimbing di Bagian/Bidang/Satuan Kerja secara online dengan mengirimkan link <b>https://bit.ly/NilaiPKL</b> kepada pembimbing saudara</li>
                <li>Meminta Nota Dinas Selesai dari Bidang/Bagian/Satuan Kerja saudara untuk dikirimkan secara langsung ke Ruangan Pusbangkom SDM Legislatif <b>Setjen</b> DPR RI.
                Gd. <b>Setjen</b> Lt.4 Ruangan 413</li>
                <li>Mengisi Form Testimonial</li>
                <li>Mengisi Form Pengiriman Laporan Akhir pada link, Kemudian Konfirmasi ke email : <b>magangpsf.dpr@gmail.com</b>  Bahwa telah mengirimkan Laporan Penelitian</li>
            </ol>
        </div>
    </div>
</div>


<script>
    // System Tabs
    function openTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }

    function openModal(id) {
        document.getElementById(id).style.display = "block";
        document.body.style.overflow = "hidden"; // Prevent scroll
    }

    function closeModal(id) {
        document.getElementById(id).style.display = "none";
        document.body.style.overflow = "auto";
    }

    // Close on outside click
    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = "none";
            document.body.style.overflow = "auto";
        }
    }

    // Header scroll effect
    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.style.padding = "0.5rem 6%";
            header.style.background = "rgba(255, 255, 255, 0.95)";
        } else {
            header.style.padding = "0.8rem 6%";
            header.style.background = "rgba(255, 255, 255, 0.85)";
        }
    });

    // Mobile menu & back-to-top di-handle oleh partial: auth.partials.public-chrome-scripts
    // (di-include di luar <script> ini, dekat akhir body)

    // Inisialisasi search lowongan (live, server-side via AJAX) setelah DOM siap.
    // Di halaman "Lowongan Khusus" ($onlyLowongan) AJAX dimatikan -> pakai GET biasa
    // (search + per_page + pagination berbasis URL).
    document.addEventListener('DOMContentLoaded', function () {
        @unless($onlyLowongan ?? false)
        initLowonganSearch();
        @else
        initLowonganKhususLiveSearch();
        @endunless
    });

    function initLowonganSearch() {
        const form = document.getElementById('lowongan-search-form');
        const input = document.getElementById('lowongan-search-input');
        const clearBtn = document.getElementById('lowongan-search-clear');
        const container = document.getElementById('lowongan-results-container');
        if (!form || !input || !container) {
            return;
        }

        let debounceTimer = null;
        let currentController = null;

        function attachPaginationHandlers() {
            container.querySelectorAll('.pagination-btn-ajax').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var page = parseInt(this.dataset.page || '1', 10);
                    loadLowongan(page);
                });
            });
        }

        function updateClearButton(q) {
            if (!clearBtn) return;
            clearBtn.style.display = q && q.trim() !== '' ? 'flex' : 'none';
        }

        function updateUrl(q, page) {
            if (!window.history || !window.history.replaceState) return;
            // URL dibersihkan: tanpa ?q=..., hanya anchor ke section lowongan
            var baseUrl = "{{ route('Halaman awal') }}";
            var newUrl = baseUrl + '#lowongan-section';
            window.history.replaceState({}, '', newUrl);
        }

        function loadLowongan(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            var q = input.value || '';
            updateClearButton(q);

            var params = new URLSearchParams();
            if (q && q.trim() !== '') {
                params.append('q', q.trim());
            }
            params.append('page', page);

            var url = "{{ route('api.search-lowongan') }}" + '?' + params.toString();

            if (currentController) {
                currentController.abort();
            }
            currentController = new AbortController();

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                signal: currentController.signal
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Gagal memuat data lowongan');
                    }
                    return response.json();
                })
                .then(function (data) {
                    if (!data || !data.success) return;
                    container.innerHTML = data.html;
                    attachPaginationHandlers();
                    updateUrl(q, page);
                })
                .catch(function (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }
                });
        }

        // Live search hanya untuk desktop (lebar >= 992px)
        if (window.innerWidth >= 992) {
            input.addEventListener('input', function () {
                var value = this.value;
                updateClearButton(value);

                if (debounceTimer) {
                    clearTimeout(debounceTimer);
                }

                debounceTimer = setTimeout(function () {
                    loadLowongan(1);
                }, 800);
            });
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            loadLowongan(1);
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                input.value = '';
                updateClearButton('');
                loadLowongan(1);
            });
        }

        // Handler paginasi awal
        attachPaginationHandlers();
    }

    /**
     * Live-search untuk halaman "Lowongan Khusus".
     * - Mengetik (desktop) -> AJAX memperbarui hasil (tanpa reload, hormati per_page).
     * - Tombol "Cari" / Enter & dropdown per_page tetap memakai GET biasa (URL-based)
     *   sehingga tetap berfungsi walau JS gagal/AJAX bermasalah.
     */
    function initLowonganKhususLiveSearch() {
        var input = document.getElementById('lowongan-search-input');
        var clearBtn = document.getElementById('lowongan-search-clear');
        var container = document.getElementById('lowongan-results-container');
        var perPageSelect = document.getElementById('lowongan-per-page');
        if (!input || !container) return;

        var debounceTimer = null;
        var currentController = null;

        function perPageVal() { return perPageSelect ? perPageSelect.value : ''; }

        function attachPagination() {
            container.querySelectorAll('.pagination-btn-ajax').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    load(parseInt(this.dataset.page || '1', 10));
                });
            });
        }

        function syncUrl(q, page) {
            if (!window.history || !window.history.replaceState) return;
            var params = new URLSearchParams();
            if (q && q.trim() !== '') params.set('q', q.trim());
            if (perPageVal()) params.set('per_page', perPageVal());
            if (page && page > 1) params.set('page', page);
            var qs = params.toString();
            window.history.replaceState({}, '', "{{ route('lowongan.khusus') }}" + (qs ? ('?' + qs) : ''));
        }

        function load(page) {
            page = page || 1;
            var q = input.value || '';
            if (clearBtn) clearBtn.style.display = (q && q.trim() !== '') ? 'flex' : 'none';

            var params = new URLSearchParams();
            if (q && q.trim() !== '') params.append('q', q.trim());
            if (perPageVal()) params.append('per_page', perPageVal());
            params.append('page', page);

            if (currentController) currentController.abort();
            currentController = new AbortController();

            fetch("{{ route('api.search-lowongan') }}" + '?' + params.toString(), {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                signal: currentController.signal
            })
                .then(function (r) { if (!r.ok) throw new Error('Gagal memuat'); return r.json(); })
                .then(function (data) {
                    if (!data || !data.success) return;
                    container.innerHTML = data.html;
                    attachPagination();
                    syncUrl(q, page);
                })
                .catch(function (err) { /* abaikan: tombol Cari (GET) tetap berfungsi sebagai fallback */ });
        }

        // Live-search hanya untuk desktop (lebar >= 992px); mobile pakai tombol Cari
        if (window.innerWidth >= 992) {
            input.addEventListener('input', function () {
                if (debounceTimer) clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () { load(1); }, 700);
            });
        }

        attachPagination();
    }

    /**
     * Loading fullscreen saat pindah halaman dari landing (hero Gerbang Kompetensi, konten, header, footer).
     * Tidak dipakai untuk: anchor/hash saja di halaman yang sama, link eksternal, mailto/tel, pagination AJAX (#).
     */
    (function initAuthLandingPageLoader() {
        var overlay = document.getElementById('authLandingPageLoader');
        if (!overlay) return;

        function showLoader() {
            overlay.style.display = 'flex';
            overlay.style.opacity = '1';
            overlay.classList.add('show');
            overlay.setAttribute('aria-hidden', 'false');
            void overlay.offsetHeight;
        }

        function hideLoader() {
            overlay.classList.remove('show');
            overlay.style.display = '';
            overlay.style.opacity = '';
            overlay.setAttribute('aria-hidden', 'true');
        }

        window.addEventListener('pageshow', hideLoader);
        window.addEventListener('load', hideLoader);

        function isFullPageLeave(link, e) {
            if (!link || link.tagName !== 'A') return false;
            if (link.hasAttribute('data-no-page-loader')) return false;
            if (link.target === '_blank' || link.hasAttribute('download')) return false;
            if (link.getAttribute('data-bs-toggle')) return false;
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return false;
            var href = link.getAttribute('href');
            if (href === null || href === '') return false;
            var h = href.trim();
            if (h.indexOf('javascript:') === 0) return false;
            if (h.indexOf('mailto:') === 0 || h.indexOf('tel:') === 0) return false;
            try {
                var u = new URL(href, window.location.href);
                if (u.origin !== window.location.origin) return false;
                var cur = new URL(window.location.href);
                if (u.pathname === cur.pathname && u.search === cur.search) {
                    return false;
                }
                return true;
            } catch (err) {
                return false;
            }
        }

        document.addEventListener('click', function (e) {
            var link = e.target.closest('a[href]');
            if (!isFullPageLeave(link, e)) return;
            showLoader();
        }, true);

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form || !(form instanceof HTMLFormElement)) return;
            if (form.target === '_blank') return;
            showLoader();
        }, true);

        window.addEventListener('beforeunload', function () {
            showLoader();
        });
    })();
</script>

@include('auth.partials.public-footer')

@include('auth.partials.public-chrome-scripts')

</body>
</html>

<?php
echo ob_get_clean();
?>
