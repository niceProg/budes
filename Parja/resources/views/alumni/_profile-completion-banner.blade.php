{{--
    Banner persistent "Lengkapi profile" — tampil di atas konten alumni dashboard
    kalau profile_completed != 1 (signal dari middleware). Tidak bisa di-dismiss,
    hanya hilang setelah user benar2 melengkapi profile.
--}}
@if (! empty($shouldShowProfileCompletionModal))
<div class="alert d-flex align-items-center gap-3 mb-3" role="alert"
     style="background:linear-gradient(135deg,#fff8e1 0%,#fffde7 100%);border:1px solid rgba(251,172,24,0.5);border-left:4px solid #fbac18;border-radius:12px;">
    <i class="ri-information-line" style="font-size:1.4rem;color:#c77d00;flex-shrink:0;"></i>
    <div class="flex-grow-1">
        <strong style="color:#41174b;display:block;font-size:0.95rem;">Profile Anda belum lengkap</strong>
        <span style="font-size:0.84rem;color:#695a72;">
            Lengkapi pekerjaan, pendidikan, foto profil, dan akun medsos supaya kenalan sesama alumni dan tampil di direktori.
        </span>
    </div>
    <a href="{{ route('alumni.profile.edit') }}" class="btn btn-sm fw-semibold flex-shrink-0"
       style="background:linear-gradient(135deg,#41174b,#bf0050);color:#fff;border:0;">
        <i class="ri-edit-2-line"></i> Lengkapi
    </a>
</div>
@endif
