@php
    $appName = config('app.name', 'SMART');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode Verifikasi Login {{ $appName }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f4f4f5; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;">
        <tr>
            <td style="padding:20px 24px;border-bottom:1px solid #e5e7eb;">
                <h2 style="margin:0;color:#111827;font-size:18px;">Verifikasi Login {{ $appName }}</h2>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 24px;color:#374151;font-size:14px;line-height:1.6;">
                <p style="margin-top:0;">Halo <strong>{{ $nama }}</strong>,</p>
                <p>Kami menerima permintaan untuk masuk ke akun Anda. Silakan masukkan kode berikut pada halaman verifikasi:</p>
                <p style="text-align:center;margin:24px 0;">
                    <span style="display:inline-block;padding:12px 24px;font-size:22px;letter-spacing:8px;font-weight:bold;background:#111827;color:#f9fafb;border-radius:10px;">
                        {{ $code }}
                    </span>
                </p>
                <p>Kode ini berlaku selama <strong>10 menit</strong> dan hanya bisa dicoba maksimal <strong>10 kali</strong>.</p>
                <p>Jika Anda tidak merasa melakukan login, Anda dapat mengabaikan email ini.</p>
                <p style="margin-bottom:0;">Salam hangat,<br><strong>Tim {{ $appName }}</strong></p>
            </td>
        </tr>
    </table>
</body>
</html>

