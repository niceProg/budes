<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f5; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="padding: 20px 24px; border-bottom: 1px solid #e5e7eb;">
                <h2 style="margin: 0; font-size: 20px; color: #111827;">Reset Password Akun {{ config('app.name') }}</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 24px; font-size: 14px; color: #374151;">
                <p>Yth. {{ $payload['nama'] }},</p>
                <p>Kami menerima permintaan untuk mengatur ulang password akun {{ config('app.name') }} Anda.</p>
                <p>Silakan klik tombol di bawah ini untuk mengubah password:</p>
                <p style="text-align: center; margin: 24px 0;">
                    <a href="{{ $payload['reset_url'] }}" 
                       style="display: inline-block; padding: 10px 18px; background-color: #b08d48; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;">
                        Atur Ulang Password
                    </a>
                </p>
                <p>Link ini hanya berlaku selama <strong>30 menit</strong> sejak email ini dikirim dan hanya dapat digunakan satu kali.</p>
                <p>Jika Anda tidak merasa melakukan permintaan reset password, abaikan email ini. Password Anda akan tetap aman.</p>
                <p style="margin-top: 24px;">Salam, <br> <strong>Tim {{ config('app.name') }} Setjen DPR RI</strong></p>
            </td>
        </tr>
    </table>
</body>
</html>

