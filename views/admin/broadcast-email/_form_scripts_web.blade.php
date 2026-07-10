<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('show.bs.modal', function (event) {
            const modalEl = event.target;
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
        });

        const form = document.getElementById('broadcast-form');
        const submitBtn = document.getElementById('broadcast-submit-btn');

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Terbitkan pengumuman web?',
                    text: 'Pengumuman akan tampil untuk seluruh peserta magang di aplikasi (tanpa email).',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, terbitkan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#b08d48',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> <span>Memproses...</span>';
                        }
                        form.submit();
                    }
                });
            });
        }
    });
</script>
<style>
    @keyframes spin { from { transform: rotate(0deg);} to { transform: rotate(360deg);} }
</style>
