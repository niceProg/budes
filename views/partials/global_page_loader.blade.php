<style>
    .global-page-loading-overlay {
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
    .global-page-loading-overlay.show {
        display: flex;
        opacity: 1;
    }
    .global-page-loading-card {
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
        animation: global-page-loader-pop 220ms ease-out forwards;
    }
    @keyframes global-page-loader-pop {
        to { transform: translateY(0) scale(1); }
    }
    .global-page-loading-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 2px;
    }
    .global-page-loading-dpr-logo {
        width: 46px;
        height: auto;
        flex-shrink: 0;
    }
    .global-page-loading-brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
        align-items: flex-start;
    }
    .global-page-loading-brand-title {
        font-weight: 1000;
        letter-spacing: -0.4px;
        font-size: 1.25rem;
        color: #0f172a;
    }
    .global-page-loading-brand-sub {
        font-size: 0.92rem;
        color: #64748b;
        font-weight: 700;
        margin-top: 2px;
    }
    .global-page-loading-message {
        font-weight: 900;
        letter-spacing: -0.3px;
        font-size: 1.35rem;
        color: #0f172a;
    }
    .global-page-loading-submessage {
        font-size: 0.98rem;
        color: #475569;
        margin-top: 2px;
    }
    .global-page-loading-mark {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: rgba(176, 141, 72, 0.12);
        border: 1px solid rgba(176, 141, 72, 0.28);
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .global-page-loading-spinner {
        width: 22px;
        height: 22px;
        border-radius: 999px;
        border: 3px solid rgba(176, 141, 72, 0.22);
        border-top-color: #b08d48;
        animation: global-page-loader-spin 0.75s linear infinite;
    }
    @keyframes global-page-loader-spin { to { transform: rotate(360deg); } }
    html[data-skin="dark"] .global-page-loading-overlay {
        background:
            radial-gradient(1200px 500px at 20% 10%, rgba(251, 191, 36, 0.10), transparent 55%),
            radial-gradient(900px 450px at 85% 30%, rgba(99, 102, 241, 0.12), transparent 55%),
            rgba(2, 6, 23, 0.72);
    }
    html[data-skin="dark"] .global-page-loading-card {
        background: rgba(15, 23, 42, 0.82);
        border-color: rgba(55, 65, 81, 0.95);
        box-shadow: 0 30px 90px rgba(0, 0, 0, 0.55);
    }
    html[data-skin="dark"] .global-page-loading-message { color: #ffffff; }
    html[data-skin="dark"] .global-page-loading-submessage { color: #cbd5e1; }
    html[data-skin="dark"] .global-page-loading-brand-title { color: #ffffff; }
    html[data-skin="dark"] .global-page-loading-brand-sub { color: #cbd5e1; }
    html[data-skin="dark"] .global-page-loading-mark {
        background: rgba(251, 191, 36, 0.10);
        border-color: rgba(251, 191, 36, 0.26);
    }
    html[data-skin="dark"] .global-page-loading-spinner {
        border-color: rgba(251, 191, 36, 0.18);
        border-top-color: #fbbf24;
    }
</style>

<div class="global-page-loading-overlay" id="globalPageLoadingOverlay" aria-hidden="true">
    <div class="global-page-loading-card" role="status" aria-live="polite" aria-label="Tolong menunggu sesaat">
        <div class="global-page-loading-brand">
            <img class="global-page-loading-dpr-logo" src="{{ asset('theme/admin-dashbyte/dist/assets/img/logo.png') }}" alt="DPR RI">
            <div class="global-page-loading-brand-text">
                <div class="global-page-loading-brand-title">SMART</div>
                <div class="global-page-loading-brand-sub">Sistem Magang Administratif, Responsif, dan Terintegrasi</div>
            </div>
        </div>
        <div class="global-page-loading-message">Tolong menunggu sesaat</div>
        <div class="global-page-loading-submessage" aria-hidden="true">Sedang memuat halaman...</div>
        <div class="global-page-loading-mark" aria-hidden="true">
            <div class="global-page-loading-spinner"></div>
        </div>
    </div>
</div>

<script>
    (function initGlobalPageLoader() {
        var overlay = document.getElementById('globalPageLoadingOverlay');
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

        document.addEventListener('click', function (e) {
            var link = e.target.closest('a[href]');
            if (!link) return;
            if (link.hasAttribute('data-no-page-loader')) return;
            if (link.target === '_blank' || link.hasAttribute('download')) return;
            if (link.getAttribute('data-bs-toggle')) return;
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;

            var href = (link.getAttribute('href') || '').trim();
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
            if (href.startsWith('mailto:') || href.startsWith('tel:')) return;

            try {
                var u = new URL(href, window.location.href);
                if (u.origin !== window.location.origin) return;
                var cur = new URL(window.location.href);
                if (u.pathname === cur.pathname && u.search === cur.search) return;
                showLoader();
            } catch (err) {}
        }, true);

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form || !(form instanceof HTMLFormElement)) return;
            if (form.hasAttribute('data-no-page-loader')) return;
            if (form.target === '_blank') return;
            showLoader();
        }, true);

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
</script>
