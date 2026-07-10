{{-- Header & footer "chrome" styles + skin (dark/light) toggle script.
     Dipakai bersama oleh halaman publik (beranda, FAQ) agar header/footer identik.
     Asumsi: :root variables (--primary-dark, --accent-gold, --gold-solid, dll) sudah
     didefinisikan di halaman pemanggil. --}}
<style>
    /* --- MODERN NAVBAR --- */
    header {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 0.8rem 6%;
        padding-right: 1.5%!important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        transition: var(--transition);
        gap: 12px;
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

    nav {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .nav-links {
        display: flex;
        gap: 1.5rem;
        margin-right: 1rem;
    }

    .nav-links a {
        text-decoration: none;
        color: var(--text-main);
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition);
    }

    .nav-links a:hover {
        color: var(--gold-solid);
    }

    /* Mobile Menu Button (Hamburger) */
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
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .mobile-menu-btn:hover {
        transform: translateY(-1px);
        border-color: var(--gold-solid);
        box-shadow: 0 10px 20px rgba(142, 109, 47, 0.12);
        background: white;
    }

    .mobile-menu-btn:active {
        transform: translateY(0);
    }

    /* Sidebar Overlay */
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

    /* Mobile Menu */
    .mobile-menu {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 86vw;
        max-width: 340px;
        z-index: 2000;
        background: white;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 0 24px 24px 0;
        transform: translateX(-110%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        padding-top: calc(80px + 20px);
    }

    body.sidebar-open .mobile-menu {
        transform: translateX(0);
    }

    .mobile-menu-header {
        padding: 1.2rem 1.5rem 0.75rem;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .mobile-menu-header h4 {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin: 0;
        letter-spacing: 0.5px;
    }

    .mobile-menu-content {
        padding: 1.5rem 2rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .mobile-nav-links {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .mobile-nav-links a {
        text-decoration: none;
        color: var(--text-main);
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.85rem 1.2rem;
        border-radius: 12px;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid transparent;
    }

    .mobile-nav-links a:hover,
    .mobile-nav-links a:active {
        background: linear-gradient(135deg, rgba(176, 141, 72, 0.08) 0%, rgba(176, 141, 72, 0.04) 100%);
        color: var(--gold-solid);
        border-color: rgba(176, 141, 72, 0.2);
        transform: translateX(4px);
    }

    .mobile-nav-links a i {
        font-size: 1rem;
        width: 20px;
        text-align: center;
    }

    .mobile-auth-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 0.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid #f1f5f9;
    }

    .mobile-auth-buttons .btn-action {
        width: 100%;
        text-align: center;
        justify-content: center;
    }

    /* Buttons */
    .auth-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-action {
        padding: 0.6rem 1.4rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.8rem;
        transition: var(--transition);
    }

    .btn-outline {
        border: 1.5px solid var(--gold-solid);
        color: var(--gold-solid);
    }

    .btn-fill {
        background: var(--accent-gold);
        color: white;
        box-shadow: 0 4px 15px rgba(142, 109, 47, 0.2);
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    /* Dropdown Simple */
    .dropdown { position: relative; display: inline-block; }
    .dropdown-content {
        display: none;
        position: absolute;
        background: rgba(255, 255, 255, 0.9);
        min-width: 180px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
        border-radius: 14px;
        padding: 10px 0;
        z-index: 1;
        top: 100%;
        border: 1px solid rgba(176, 141, 72, 0.35);
        backdrop-filter: blur(10px);
    }
    .dropdown:hover .dropdown-content { display: block; }
    .dropdown-content a {
        display: block;
        padding: 10px 20px;
        color: var(--text-main);
        text-decoration: none;
        font-size: 0.85rem;
        border-radius: 999px;
        margin: 2px 10px;
        border: 1px solid transparent;
        transition: var(--transition);
    }
    .dropdown-content a:hover,
    .dropdown-content a:focus,
    .dropdown-content a:active {
        background: transparent;
        color: var(--gold-solid);
        border-color: rgba(176, 141, 72, 0.8);
    }

    /* Footer */
    .site-footer {
        border-top: 8px solid var(--gold-solid);
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        color: rgba(255,255,255,0.9);
        width: 100%;
    }
    .site-footer-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2.5rem 2rem;
    }
    .footer-top {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(255,255,255,0.12);
    }
    @media (max-width: 768px) {
        .footer-top { grid-template-columns: 1fr; }
    }
    .footer-brand h3 {
        font-size: 1.25rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.4rem;
    }
    .footer-brand p {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.75);
        line-height: 1.6;
    }
    .footer-brand .gold-text {
        color: var(--gold-solid);
        font-weight: 600;
    }
    .footer-right {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    @media (max-width: 600px) {
        .footer-right { grid-template-columns: 1fr; }
    }
    .footer-links h4,
    .footer-contact h4 {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--gold-solid);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
    }
    .footer-links ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .footer-links li { margin-bottom: 0.5rem; }
    .footer-links a {
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.2s;
    }
    .footer-links a:hover { color: var(--gold-solid); }
    .footer-contact p {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.8);
        line-height: 1.7;
        margin: 0 0 0.5rem 0;
    }
    .footer-contact a {
        color: var(--gold-solid);
        text-decoration: none;
    }
    .footer-contact a:hover { text-decoration: underline; }
    .footer-contact .contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: rgba(255,255,255,0.8);
    }
    .footer-contact .contact-item i {
        width: 18px;
        text-align: center;
        color: var(--gold-solid);
    }
    .footer-bottom {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding-top: 1.5rem;
    }
    .footer-copy {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.6);
    }
    .footer-social {
        margin-top: 1.5rem;
    }
    .footer-social h4 {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--gold-solid);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
    }
    .footer-social-links {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .footer-social-links a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.9);
        text-decoration: none;
        font-size: 1.1rem;
        transition: all 0.2s;
    }
    .footer-social-links a:hover {
        background: var(--gold-solid);
        color: white;
        transform: translateY(-2px);
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

    /* Toggle dark/light (inline, sebelah tombol Masuk) */
    .skin-toggle {
        position: static;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        padding: 0;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.7);
        background: rgba(255, 255, 255, 0.9);
        color: var(--gold-solid);
        font-size: 1.1rem;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
        margin-left: 4px;
        visibility: hidden;
    }
    html[data-skin-init="ready"] .skin-toggle { visibility: visible; }
    .skin-toggle i {
        font-size: 1.1rem;
    }
    .skin-toggle:hover {
        border-color: var(--gold-solid);
        box-shadow: 0 8px 20px rgba(148, 163, 184, 0.4);
        transform: translateY(-1px);
    }
    @media (max-width: 768px) {
        .skin-toggle {
            width: 38px;
            height: 38px;
            font-size: 1rem;
        }
    }

    /* === RESPONSIVE (chrome) === */
    @media (max-width: 991px) {
        header {
            padding: 0.6rem 4%;
            flex-wrap: nowrap;
        }
        .logo-container {
            flex: 1;
            min-width: 0;
        }
        .logo-container img {
            height: 35px;
        }
        .logo-text {
            font-size: 1rem;
        }
        .logo-text span {
            font-size: 0.6rem;
        }
        .nav-links {
            display: none;
        }
        .mobile-menu-btn {
            display: inline-flex;
            margin-right: 8px;
        }
        .auth-buttons {
            display: none;
        }
        .btn-action {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 768px) {
        header {
            padding: 0.5rem 3%;
        }
        .logo-container {
            gap: 0;
        }
        .logo-container img {
            height: 30px;
        }
        .logo-text {
            font-size: 0.85rem;
        }
        .logo-text span {
            font-size: 0.55rem;
        }
        .auth-buttons {
            display: none;
        }
        .mobile-menu-btn {
            display: inline-flex;
        }
    }

    /* === Dark mode (chrome) === */
    html[data-skin="dark"] body { background: #020617; color: #ffffff; }
    html[data-skin="dark"] .bg-pattern { opacity: 0.03; }
    html[data-skin="dark"] header {
        background: rgba(15, 23, 42, 0.95) !important;
        border-color: #1f2937;
    }
    html[data-skin="dark"] .logo-text,
    html[data-skin="dark"] .logo-text span { color: #fbbf24 !important; }
    html[data-skin="dark"] .nav-links a { color: #ffffff !important; }
    html[data-skin="dark"] .nav-links a:hover { color: #fbbf24 !important; }
    html[data-skin="dark"] .mobile-menu { background: #0f172a !important; border-color: rgba(251, 191, 36, 0.45); }
    html[data-skin="dark"] .mobile-menu-header h4,
    html[data-skin="dark"] .mobile-nav-links a { color: #ffffff !important; }
    html[data-skin="dark"] .mobile-menu-header { border-color: #1f2937 !important; }
    html[data-skin="dark"] .mobile-auth-buttons { border-color: #1f2937 !important; }
    html[data-skin="dark"] .mobile-menu-btn { background: #1e293b !important; border-color: #374151; color: #ffffff !important; }
    html[data-skin="dark"] .btn-outline { border-color: #fbbf24; color: #fbbf24; }
    html[data-skin="dark"] .skin-toggle {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.8);
        color: #e5e7eb;
    }
    html[data-skin="dark"] .skin-toggle:hover {
        border-color: #fbbf24;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.8);
    }
    html[data-skin="dark"] .dropdown-content {
        background: rgba(15, 23, 42, 0.97) !important;
        border-color: rgba(251, 191, 36, 0.45);
    }
    html[data-skin="dark"] .dropdown-content a {
        color: #ffffff !important;
        border-radius: 999px;
        border: 1px solid transparent;
        margin: 2px 10px;
    }
    html[data-skin="dark"] .dropdown-content a:hover,
    html[data-skin="dark"] .dropdown-content a:focus,
    html[data-skin="dark"] .dropdown-content a:active {
        background: transparent !important;
        color: #fbbf24 !important;
        border-color: rgba(251, 191, 36, 0.9);
    }
</style>
<script>
    function updateSkinToggleIcons(mode) {
        var next = mode === 'dark' ? 'dark' : 'light';
        document.querySelectorAll('.skin-toggle').forEach(function (btn) {
            btn.innerHTML = next === 'dark'
                ? '<i class="ri-moon-fill"></i>'
                : '<i class="ri-sun-fill"></i>';
        });
    }

    (function(){
        // Set theme ASAP (biar style dark/light langsung ke-apply, hindari flash)
        try {
            document.documentElement.setAttribute('data-skin-init', 'pending');
            var m = localStorage.getItem('skin-mode');
            var mode = (m === 'dark') ? 'dark' : 'light';
            if (mode === 'dark') document.documentElement.setAttribute('data-skin', 'dark');
            else document.documentElement.setAttribute('data-skin', '');

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function () {
                    updateSkinToggleIcons(mode);
                    document.documentElement.setAttribute('data-skin-init', 'ready');
                });
            } else {
                updateSkinToggleIcons(mode);
                document.documentElement.setAttribute('data-skin-init', 'ready');
            }
        } catch(e) {}
    })();

    function toggleSkinMode() {
        try {
            var current = document.documentElement.getAttribute('data-skin') || 'light';
            var next = current === 'dark' ? 'light' : 'dark';
            if (next === 'dark') {
                document.documentElement.setAttribute('data-skin', 'dark');
                localStorage.setItem('skin-mode', 'dark');
            } else {
                document.documentElement.setAttribute('data-skin', '');
                localStorage.removeItem('skin-mode');
            }
            updateSkinToggleIcons(next);
        } catch(e) {}
    }
</script>
