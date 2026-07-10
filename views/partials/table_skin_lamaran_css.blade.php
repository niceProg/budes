<style>
    :root {
        --primary-dark: #0f172a;
        --accent-gold: #b08d48;
        --gold-light: #fdfaf3;
        --gold-gradient: linear-gradient(135deg, #c5a059 0%, #917234 100%);
        --text-main: #334155;
        --text-muted: #64748b;
        --glass-white: rgba(255, 255, 255, 0.98);
        --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    body, .content-wrapper {
        background-color: #f8fafc;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 600px;
        background-attachment: fixed;
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .content-wrapper { position: relative; z-index: 1; padding: 2.5rem 0; }
    
    .content-wrapper::before {
        content: ''; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.97) 0%, rgba(241, 245, 249, 0.95) 100%);
        z-index: -1;
    }

    /* Dark mode: biar ikut background gelap global dari layout */
    html[data-skin="dark"] body,
    html[data-skin="dark"] .content-wrapper {
        background-color: transparent !important;
        background-image: none !important;
    }
    html[data-skin="dark"] .content-wrapper::before {
        background: transparent !important;
    }

    .page-title-area { margin-bottom: 2.5rem; }
    .breadcrumb-item { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--accent-gold); }
    .page-title { font-weight: 800; font-size: 2.2rem; color: var(--primary-dark); letter-spacing: -1px; margin-top: 5px; }

    .modern-card {
        background: var(--glass-white);
        border-radius: 30px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .card-toolbar {
        padding: 1.8rem 2.2rem;
        background: white;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid #f1f5f9;
    }

    .search-container .input-group {
        background: #f1f5f9;
        border-radius: 15px;
        padding: 5px 15px;
        border: 1.5px solid transparent;
        transition: var(--transition);
    }

    .search-container .input-group:focus-within {
        background: white;
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 4px rgba(176, 141, 72, 0.1);
    }

    .search-container input {
        background: transparent !important;
        border: none !important;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary-dark);
    }

    .table-container { padding: 10px 20px; }
    .custom-table { border-collapse: separate; border-spacing: 0 12px; width: 100%; }
    .custom-table thead th {
        background: transparent;
        padding: 1rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        border: none;
    }

    .custom-table tbody tr {
        background: white;
        transition: var(--transition);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .custom-table tbody tr:hover {
        transform: translateY(-3px) scale(1.002);
        box-shadow: 0 12px 25px rgba(0,0,0,0.05);
        z-index: 2;
    }

    .custom-table tbody td {
        padding: 1.5rem 1rem;
        vertical-align: middle;
        border: none;
        background: white;
    }

    .custom-table tbody td:first-child { border-radius: 18px 0 0 18px; text-align: center; font-weight: 800; color: var(--text-muted); }
    .custom-table tbody td:last-child { border-radius: 0 18px 18px 0; }

    .applicant-avatar {
        width: 48px; height: 48px;
        background: var(--gold-gradient);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 800; font-size: 1rem;
        box-shadow: 0 8px 16px rgba(176, 141, 72, 0.25);
    }

    .applicant-name { font-weight: 700; font-size: 1.05rem; color: var(--primary-dark); letter-spacing: -0.3px; }

    .category-badge {
        font-size: 0.7rem; font-weight: 800;
        padding: 6px 14px; background: var(--gold-light);
        color: var(--accent-gold); border-radius: 10px;
        border: 1px solid rgba(176, 141, 72, 0.2);
    }

    .status-pill {
        padding: 8px 16px; border-radius: 12px;
        font-weight: 800; font-size: 0.75rem;
        display: inline-flex; align-items: center; gap: 8px;
        letter-spacing: 0.5px;
    }
    .pill-process { background: #eff6ff; color: #2563eb; }
    .pill-success { background: #ecfdf5; color: #059669; }
    .pill-danger { background: #fef2f2; color: #dc2626; }

    .btn-action {
        width: 45px; height: 45px;
        border-radius: 15px;
        display: inline-flex; align-items: center; justify-content: center;
        background: white; color: var(--primary-dark);
        transition: var(--transition);
        border: 1.5px solid #f1f5f9;
        text-decoration: none;
    }
    .btn-action:hover {
        background: var(--primary-dark);
        color: white;
        transform: rotate(10deg);
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
    }

    .date-badge {
        background: #f8fafc; border: 1px solid #e2e8f0;
        padding: 8px 12px; border-radius: 12px;
        font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 8px;
    }

    .footer-container {
        padding: 2rem;
        background: #fcfdfe;
        border-top: 1px solid #f1f5f9;
    }

    .pagination .page-link {
        border: none; padding: 10px 18px; margin: 0 3px;
        border-radius: 12px; font-weight: 700; color: var(--text-muted);
        background: #f1f5f9; transition: var(--transition);
    }
    .pagination .page-item.active .page-link {
        background: var(--gold-gradient);
        color: white;
        box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3);
    }
    .pagination .page-item.disabled .page-link { background: transparent; opacity: 0.4; }

    /* ========== FILTER STYLING - SERAGAM ========== */
    .filter-section {
        background: rgba(248, 250, 252, 0.5);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }

    /* Filter Dropdown Button */
    .filter-dropdown .btn {
        background: white;
        border: 1.5px solid #e2e8f0;
        color: var(--text-main);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-dropdown .btn:hover,
    .filter-dropdown .btn:focus,
    .filter-dropdown .btn.show {
        background: var(--gold-gradient);
        color: white;
        border-color: var(--accent-gold);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(176, 141, 72, 0.25);
    }

    .filter-dropdown .btn i {
        font-size: 1rem;
    }

    /* Filter Badge */
    .filter-badge {
        background: rgba(255, 255, 255, 0.95);
        color: var(--accent-gold);
        font-weight: 800;
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 8px;
        margin-left: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .filter-dropdown .btn:hover .filter-badge,
    .filter-dropdown .btn.show .filter-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Dropdown Menu */
    .filter-dropdown .dropdown-menu {
        min-width: 220px;
        max-height: 320px;
        overflow-y: auto;
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        padding: 0.75rem;
        margin-top: 0.5rem;
        background: white;
    }

    .filter-dropdown .dropdown-menu::-webkit-scrollbar {
        width: 6px;
    }

    .filter-dropdown .dropdown-menu::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .filter-dropdown .dropdown-menu::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .filter-dropdown .dropdown-menu::-webkit-scrollbar-thumb:hover {
        background: var(--accent-gold);
    }

    /* Form Check (Checkbox Items) */
    .filter-dropdown .form-check {
        padding: 0.65rem 1rem;
        margin: 0;
        border-radius: 10px;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .filter-dropdown .form-check:hover {
        background: linear-gradient(135deg, rgba(176, 141, 72, 0.08) 0%, rgba(176, 141, 72, 0.04) 100%);
        transform: translateX(4px);
    }

    .filter-dropdown .form-check-input {
        width: 1.1rem;
        height: 1.1rem;
        margin-top: 0;
        margin-right: 0.75rem;
        margin-bottom: 0;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
        align-self: center;
    }

    .filter-dropdown .form-check-input:checked {
        background-color: var(--accent-gold);
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(176, 141, 72, 0.15);
    }

    .filter-dropdown .form-check-input:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(176, 141, 72, 0.15);
    }

    .filter-dropdown .form-check-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-main);
        cursor: pointer;
        user-select: none;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        line-height: 1.5;
    }

    /* Filter Action Buttons */
    .btn-filter-apply {
        background: var(--gold-gradient);
        color: white;
        border: none;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(176, 141, 72, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-filter-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(176, 141, 72, 0.35);
        color: white;
    }

    .btn-filter-reset {
        background: white;
        color: #dc2626;
        border: 1.5px solid #fecaca;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-filter-reset:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
    }

    /* ========== DATE FILTER STYLING (KHUSUS ABSENSI) ========== */
    .filter-box {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.9) 100%);
        border-radius: 20px;
        padding: 1.5rem;
        border: 1.5px solid rgba(226, 232, 240, 0.8);
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .form-control-custom {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 0.7rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-main);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
    }

    .form-control-custom:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 4px rgba(176, 141, 72, 0.12);
        background: white;
        outline: none;
    }

    .form-label {
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        color: var(--accent-gold);
        font-size: 1rem;
    }

    /* Date Input Styling - Seperti Register */
    .form-control-custom[type="date"] {
        position: relative;
        padding: 12px 16px;
        padding-right: 2.5rem;
        width: 100%;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-main);
        background: white;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control-custom[type="date"]:hover {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(176, 141, 72, 0.08);
    }

    .form-control-custom[type="date"]:focus {
        outline: none;
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 4px rgba(176, 141, 72, 0.12);
        background: white;
    }

    .form-control-custom[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 1;
        margin-left: 8px;
        transition: all 0.2s ease;
        filter: brightness(0) saturate(100%) invert(27%) sepia(15%) saturate(2000%) hue-rotate(30deg) brightness(0.7);
    }

    .form-control-custom[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 0.8;
        transform: scale(1.1);
    }

    /* Firefox date input */
    .form-control-custom[type="date"]::-moz-calendar-picker-indicator {
        cursor: pointer;
        opacity: 1;
    }

    /* Date input value styling */
    .form-control-custom[type="date"]:not(:placeholder-shown) {
        color: var(--primary-dark);
        font-weight: 700;
    }

    /* Gold Button untuk Absensi */
    .btn-gold {
        background: var(--gold-gradient);
        color: white;
        border: none;
        font-weight: 700;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(176, 141, 72, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(176, 141, 72, 0.35);
        color: white;
    }

    .btn-gold.flex-fill {
        flex: 1;
        min-width: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-section {
            padding: 1rem;
        }

        .filter-dropdown .btn {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }

        .btn-filter-apply,
        .btn-filter-reset {
            font-size: 0.8rem;
            padding: 0.5rem 1.2rem;
        }
    }
</style>
