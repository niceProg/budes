<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background: #f6f8fa; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 30px 40px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">

        <h2 style="font-size: 26px; color: #0d47a1; margin-bottom: 10px;">
            Hai {{ $payload['nama'] }}! 👋
        </h2>

        <p style="font-size: 18px; color: #333; line-height: 1.6;">
            Selamat! 🎉 Akun <strong>Pembimbing</strong> Anda telah dibuat di sistem {{ config('app.name') }} DPR RI.
            @if(isset($payload['peserta_nama']))
                Anda telah ditetapkan sebagai pembimbing untuk peserta: <strong>{{ $payload['peserta_nama'] }}</strong>.
            @endif
        </p>

        <p style="font-size: 18px; color: #333; line-height: 1.6;">
            Berikut adalah informasi login Anda sebagai pembimbing:
        </p>

        <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 15px 0;">

            <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 18px;">
                <tr>
                    <td style="font-weight: bold; width: 120px;">Email</td>
                    <td style="text-align: left;">: {{ $payload['email'] }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding-top: 8px;">Password</td>
                    <td style="text-align: left; padding-top: 8px;">: {{ $payload['password_plain'] }}</td>
                </tr>
            </table>

        </div>

        <p style="font-size: 18px; margin-top: 20px;">
            Anda dapat login ke sistem melalui tautan berikut:<br>
            <a href="{{ $payload['login_url'] }}"
               style="display: inline-block; margin-top: 12px; background: #0d47a1; color: white; text-decoration: none; padding: 12px 20px; border-radius: 6px; font-size: 18px;">
                Klik untuk Login
            </a>
        </p>

        <p style="font-size: 16px; margin-top: 25px; color: #444;">
            Sebagai pembimbing, Anda dapat melihat dan memantau perkembangan peserta yang Anda bimbing melalui dashboard pembimbing.
        </p>

        <p style="font-size: 16px; margin-top: 15px; color: #444;">
            Demi keamanan, kami sangat menyarankan Anda untuk <strong>mengganti password</strong> setelah login pertama.
        </p>

        <hr style="margin: 30px 0; border: none; border-bottom: 1px solid #ddd;">

        <p style="font-size: 15px; color: #666; text-align: center;">
            Terima kasih atas kontribusi Anda dalam membimbing peserta magang DPR RI! ✨
        </p>

    </div>

</body>
</html>
