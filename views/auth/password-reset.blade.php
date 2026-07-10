<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Atur Ulang Password | {{ config('app.name') }} DPR RI</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/assets/css/style.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.ico') }}" type="image/x-icon">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        html[data-skin="dark"] body {
            background-color: #020617;
            color: #e5e7eb;
        }
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(15,23,42,0.08);
            padding: 2rem 2.5rem;
            max-width: 480px;
            width: 100%;
        }
        html[data-skin="dark"] .auth-card {
            background: #0f172a;
            box-shadow: 0 15px 40px rgba(0,0,0,0.7);
            border: 1px solid #1f2937;
        }
        .auth-title {
            font-weight: 800;
            font-size: 1.5rem;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }
        html[data-skin="dark"] .auth-title {
            color: #e5e7eb;
        }
        .auth-subtitle {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }
        html[data-skin="dark"] .auth-subtitle {
            color: #9ca3af;
        }
        .btn-primary-gold {
            background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
            border: none;
            padding: 0.8rem;
            border-radius: 10px;
            font-weight: 700;
            color: #ffffff;
            width: 100%;
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .form-control {
            padding-right: 44px;
        }
        /* Dark-mode input theme: samakan dengan register2 */
        html[data-skin="dark"] .form-control {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .form-control:focus {
            background-color: #0f172a !important;
        }
        html[data-skin="dark"] .form-control::placeholder {
            color: #9ca3af !important;
        }
        .password-help {
            margin-top: 6px;
        }
        .password-help-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            margin-top: 2px;
            color: #dc2626;
            line-height: 1.2;
        }
        .password-help-item i {
            font-size: 1rem;
            line-height: 1;
            flex-shrink: 0;
            color: inherit;
        }
        .password-help-item .hint {
            color: #64748b;
            font-size: 0.74rem;
            margin-left: 4px;
            white-space: nowrap;
        }
        html[data-skin="dark"] .password-help-item {
            color: #f97316;
        }
        html[data-skin="dark"] .password-help-item .hint {
            color: #9ca3af;
        }
        .reset-eye {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.1rem;
            color: #9ca3af;
            background: #ffffff;
            border-radius: 999px;
            padding: 2px;
            z-index: 2;
        }
        html[data-skin="dark"] .reset-eye {
            background: #020617;
            color: #9ca3af;
        }
        .reset-eye:hover {
            color: #6b7280;
        }
    </style>
</head>
<body>
    @include('partials.global_page_loader')
<div class="auth-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">Atur Ulang Password</h1>
        <p class="auth-subtitle">Silakan buat password baru untuk akun Anda (admin, peserta, atau pembimbing).</p>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="{{ $email }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <div class="password-wrapper">
                <input type="password"
                       name="password"
                           id="reset_password"
                       class="form-control @error('password') is-invalid @enderror"
                       required
                           minlength="12"
                           placeholder="Minimal 12 karakter">
                    <i class="ri-eye-off-line reset-eye" data-target="reset_password"></i>
                </div>
                <div id="reset-password-help" class="form-text mt-1 password-help">
                    <div id="reset-pass-rule-length" class="password-help-item">
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Password minimal 12 karakter</span>
                    </div>
                    <div id="reset-pass-rule-lower" class="password-help-item">
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Harus mengandung huruf kecil</span>
                        <span class="hint">(contoh: a-z)</span>
                    </div>
                    <div id="reset-pass-rule-upper" class="password-help-item">
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Harus mengandung huruf besar</span>
                        <span class="hint">(contoh: A-Z)</span>
                    </div>
                    <div id="reset-pass-rule-digit" class="password-help-item">
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Harus mengandung angka</span>
                        <span class="hint">(contoh: 0-9)</span>
                    </div>
                    <div id="reset-pass-rule-symbol" class="password-help-item">
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Harus mengandung karakter khusus</span>
                        <span class="hint">(contoh: ! @ # $ % ^ &amp; * )</span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <div class="password-wrapper">
                <input type="password"
                       name="password_confirmation"
                           id="reset_password_confirmation"
                       class="form-control"
                       required
                           minlength="12"
                       placeholder="Ulangi password baru">
                    <i class="ri-eye-off-line reset-eye" data-target="reset_password_confirmation"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-gold">
                Simpan Password Baru
            </button>

            <p class="text-center mt-3 mb-0">
                <a href="{{ route('login') }}" class="small text-muted">Kembali ke halaman login</a>
            </p>
        </form>
    </div>
</div>
<script>
    (function setupResetPassword() {
        // Eye toggle handlers
        document.querySelectorAll('.reset-eye').forEach(function(icon) {
            const targetId = icon.getAttribute('data-target');
            icon.addEventListener('click', function () {
                const input = document.getElementById(targetId);
                if (!input) return;
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('ri-eye-line', isPassword);
                icon.classList.toggle('ri-eye-off-line', !isPassword);
            });
        });

        // Live rule-by-rule validation
        const pwdInput = document.getElementById('reset_password');
        const confirmInput = document.getElementById('reset_password_confirmation');
        if (!pwdInput) return;

        const ruleLength = document.getElementById('reset-pass-rule-length');
        const ruleLower  = document.getElementById('reset-pass-rule-lower');
        const ruleUpper  = document.getElementById('reset-pass-rule-upper');
        const ruleDigit  = document.getElementById('reset-pass-rule-digit');
        const ruleSymbol = document.getElementById('reset-pass-rule-symbol');

        function validateNew() {
            const val = pwdInput.value || '';
            const hasLength = val.length >= 12;
            const hasLower  = /[a-z]/.test(val);
            const hasUpper  = /[A-Z]/.test(val);
            const hasDigit  = /\d/.test(val);
            const hasSymbol = /[^A-Za-z0-9]/.test(val);

            const allValid = hasLength && hasLower && hasUpper && hasDigit && hasSymbol;

            if (ruleLength) ruleLength.style.color = hasLength ? '#16a34a' : '#dc2626';
            if (ruleLower)  ruleLower.style.color  = hasLower  ? '#16a34a' : '#dc2626';
            if (ruleUpper)  ruleUpper.style.color  = hasUpper  ? '#16a34a' : '#dc2626';
            if (ruleDigit)  ruleDigit.style.color  = hasDigit  ? '#16a34a' : '#dc2626';
            if (ruleSymbol) ruleSymbol.style.color = hasSymbol ? '#16a34a' : '#dc2626';

            if (val.length > 0 && !allValid) {
                pwdInput.classList.add('is-invalid');
            } else {
                pwdInput.classList.remove('is-invalid');
            }

            if (confirmInput && confirmInput.value.length > 0) {
                validateConfirm();
            }
        }

        function validateConfirm() {
            if (!confirmInput) return;
            const match = confirmInput.value === pwdInput.value;
            if (match || confirmInput.value.length === 0) {
                confirmInput.classList.remove('is-invalid');
            } else {
                confirmInput.classList.add('is-invalid');
            }
        }

        pwdInput.addEventListener('input', validateNew);
        if (confirmInput) {
            confirmInput.addEventListener('input', validateConfirm);
        }
    })();
</script>
</body>
</html>

