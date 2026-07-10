<script>
    // Mobile Menu Toggle
    (function initMobileMenu() {
        const btnOpen = document.getElementById('btnOpenMobileMenu');
        const btnClose = document.getElementById('btnCloseMobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        const menu = document.getElementById('mobileMenu');
        if (!btnOpen || !overlay || !menu) return;

        function open() {
            document.body.classList.add('sidebar-open');
            document.body.style.overflow = 'hidden';
        }
        function close() {
            document.body.classList.remove('sidebar-open');
            document.body.style.overflow = 'auto';
        }

        btnOpen.addEventListener('click', open);
        if (btnClose) btnClose.addEventListener('click', close);
        overlay.addEventListener('click', close);

        menu.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => close());
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') close();
        });
    })();

    // Back to Top Button
    document.addEventListener('DOMContentLoaded', function () {
        const backToTopButton = document.getElementById('backToTop');
        if (!backToTopButton) return;

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        window.scrollToTop = scrollToTop;

        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });

        backToTopButton.addEventListener('click', scrollToTop);
    });
</script>
