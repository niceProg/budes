{{--
    Layer 3 popup modal: muncul setelah admin approve L2 verifikasi.
    Mengarahkan alumni ke halaman profile-edit untuk melengkapi data
    profile detail (pekerjaan, pendidikan, dst).

    WAJIB / blocking behavior (anti-bypass):
    - TIDAK ada tombol "Nanti saja" dan TIDAK ada tombol X — satu-satunya aksi
      adalah "Lengkapi Sekarang".
    - Tidak bisa ditutup via klik backdrop atau tombol ESC (static backdrop).
    - Tidak dirender di halaman form profil (alumni.profile.edit) supaya form
      tetap bisa diisi — penegakan utama tetap di EnsureAlumniAccess middleware
      yang memblokir akses fitur lain sampai profile_completed = 1.

    Variable: $shouldShowProfileCompletionModal (bool) — di-share dari
    EnsureAlumniAccess middleware.
--}}
@if (! empty($shouldShowProfileCompletionModal) && ! request()->routeIs('alumni.profile.edit'))
<div class="modal fade" id="profileCompletionModal" tabindex="-1" aria-labelledby="profileCompletionModalLabel"
     data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:18px;border:1px solid rgba(65,23,75,0.16);overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#41174b 0%,#bf0050 100%);color:#fff;border:0;">
                <h5 class="modal-title fw-bold" id="profileCompletionModalLabel">
                    <i class="ri-check-double-line me-1"></i> Akun Anda Sudah Disetujui
                </h5>
                {{-- Tombol X sengaja DIHILANGKAN: profil wajib dilengkapi, tidak boleh ditutup. --}}
            </div>
            <div class="modal-body p-4">
                <p class="mb-3" style="line-height:1.6;">
                    Selamat! Tim Parja telah memverifikasi keanggotaan alumni Anda. Sebelum dapat
                    mengakses portal Parja Alumni, Anda <strong>wajib melengkapi profile detail</strong>
                    terlebih dahulu.
                </p>
                <div class="p-3 rounded mb-2" style="background:#fffdf2;border:1px solid #f2e7c3;">
                    <strong class="d-block mb-2" style="color:#41174b;font-size:0.92rem;">Yang wajib dilengkapi:</strong>
                    <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#695a72;line-height:1.7;">
                        <li>Pendidikan saat ini (tingkat, jurusan, kampus)</li>
                        <li>Domisili terakhir</li>
                        <li>Pekerjaan, organisasi, prestasi, foto &amp; media sosial (opsional)</li>
                    </ul>
                </div>
                <p class="small text-muted mb-0">
                    Akses fitur alumni (feed, chat, direktori) terkunci sampai profil dilengkapi.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid rgba(65,23,75,0.12);">
                {{-- "Nanti saja" sengaja DIHILANGKAN. Hanya "Lengkapi Sekarang" yang aktif. --}}
                <a href="{{ route('alumni.profile.edit') }}" class="btn fw-semibold w-100"
                   style="background:linear-gradient(135deg,#41174b,#bf0050);color:#fff;border:0;">
                    <i class="ri-edit-2-line"></i> Lengkapi Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    // Auto-show modal terkunci kalau profil belum lengkap. Tidak ada dismiss /
    // sessionStorage flag — wajib lewat "Lengkapi Sekarang".
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var modalEl = document.getElementById('profileCompletionModal');
        if (modalEl) {
            var modal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
            setTimeout(function () { modal.show(); }, 400);
        }
    }
})();
</script>
@endif
