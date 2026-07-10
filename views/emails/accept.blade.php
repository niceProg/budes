<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background: #f6f8fa; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 30px 40px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">

        <!-- Header dengan Logo -->
        <div style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e3f2fd;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="text-align: center;">
                        @if($smartLogo)
                        <img src="{{ $message->embed($smartLogo) }}" alt="Logo {{ config('app.name') }}" style="height: 55px; vertical-align: middle; margin-right: 15px;">
                        @endif
                        @if($dprLogo)
                        <img src="{{ $message->embed($dprLogo) }}" alt="DPR RI" style="height: 55px; vertical-align: middle;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; padding-top: 10px;">
                        <div style="font-size: 20px; font-weight: bold; color: #0d47a1; line-height: 1.3;">
                            {{ config('app.name') }}<br>
                            <span style="font-size: 14px; font-weight: normal; color: #666;">Setjen DPR RI</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <h2 style="font-size: 26px; color: #0d47a1; margin-bottom: 10px;">
            Hai {{ $payload['nama'] }}! 👋
        </h2>

        <p style="font-size: 18px; color: #333; line-height: 1.6;">
            Selamat! 🎉 Anda resmi terdaftar sebagai <strong>Peserta Magang DPR RI</strong>.
            Kami sangat senang menyambut Anda menjadi bagian dari perjalanan pembelajaran dan pengalaman baru ini.
        </p>

        <!-- Data Peserta Magang -->
        <h3 style="font-size: 20px; color: #0d47a1; margin-top: 30px; margin-bottom: 15px;">
            📋 Data Peserta Magang
        </h3>

        <table width="100%" cellpadding="8" cellspacing="0" style="font-size: 16px; border-collapse: collapse; background: #f8f9fa; border-radius: 8px;">
            <tr style="background: #e3f2fd;">
                <td style="font-weight: bold; padding: 10px; border-bottom: 1px solid #ddd;">No. Pendaftaran</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $payload['no_pendaftaran'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; padding: 10px; border-bottom: 1px solid #ddd;">Nama</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $payload['nama'] }}</td>
            </tr>
            <tr style="background: #e3f2fd;">
                <td style="font-weight: bold; padding: 10px; border-bottom: 1px solid #ddd;">NIK</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $payload['nik'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; padding: 10px; border-bottom: 1px solid #ddd;">Universitas/Sekolah</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $payload['instansi'] ?? '-' }}</td>
            </tr>
            <tr style="background: #e3f2fd;">
                <td style="font-weight: bold; padding: 10px; border-bottom: 1px solid #ddd;">Jenis</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ ucfirst($payload['kategori'] ?? '-') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; padding: 10px; border-bottom: 1px solid #ddd;">Periode</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $payload['tanggal_mulai'] ?? '-' }} s/d {{ $payload['tanggal_selesai'] ?? '-' }}</td>
            </tr>
            <tr style="background: #e3f2fd;">
                <td style="font-weight: bold; padding: 10px;">Satuan Kerja Penempatan</td>
                <td style="padding: 10px;">{{ $payload['satker'] ?? 'Belum ditentukan' }}</td>
            </tr>
        </table>

        <!-- Informasi Login -->
        <h3 style="font-size: 20px; color: #0d47a1; margin-top: 30px; margin-bottom: 15px;">
            🔐 Informasi Login
        </h3>

        <p style="font-size: 18px; color: #333; line-height: 1.6;">
            Akun magang Anda telah aktif. Berikut adalah informasi login Anda:
        </p>

        <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 15px 0;">

            <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 18px;">
                <tr>
                    <td style="font-weight: bold; width: 120px;">Email</td>
                    <td style="text-align: left;">: {{ $payload['email'] }}</td>
                </tr>
                @if(!empty($payload['password_plain']))
                    <tr>
                        <td style="font-weight: bold; padding-top: 8px; vertical-align: top;">Password</td>
                        <td style="text-align: left; padding-top: 8px;">
                            <div style="background: #fff3cd; padding: 12px; border-radius: 6px; border: 2px solid #ffc107; margin-top: 5px;">
                                <div style="text-align: center; margin-bottom: 8px;">
                                    <span style="font-size: 13px; color: #856404; font-weight: bold;">🔒 PASSWORD ANDA</span>
                                </div>
                                <div style="text-align: center; background: #ffffff; padding: 12px 20px; border-radius: 4px; border: 2px dashed #0d47a1;">
                                    <span style="font-family: 'Courier New', monospace; font-size: 20px; font-weight: bold; letter-spacing: 2px; color: #0d47a1; user-select: all;">{{ $payload['password_plain'] }}</span>
                                </div>
                                <div style="text-align: center; margin-top: 8px;">
                                    <small style="color: #856404; font-size: 12px; font-style: italic;">
                                        � Klik 3x pada password untuk select semua, lalu tekan Ctrl+C untuk copy
                                    </small>
                                </div>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td style="font-weight: bold; padding-top: 8px;">Password</td>
                        <td style="text-align: left; padding-top: 8px;">: Gunakan password yang Anda gunakan sebelumnya.</td>
                    </tr>
                @endif
            </table>

        </div>

        <p style="font-size: 18px; margin-top: 20px;">
            Anda dapat login ke sistem melalui tautan berikut:<br>
            <a href="{{ $payload['login_url'] }}"
               style="display: inline-block; margin-top: 12px; background: #0d47a1; color: white; text-decoration: none; padding: 12px 20px; border-radius: 6px; font-size: 18px;">
                Klik untuk Login
            </a>
        </p>

        <hr style="margin: 30px 0; border: none; border-bottom: 1px solid #ddd;">

        <!-- Informasi Kontak -->
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin: 20px 0;">
            <p style="font-size: 16px; color: #856404; margin: 0; line-height: 1.6;">
                <strong>⚠️ Informasi Penting:</strong><br>
                Demi keamanan, kami sangat menyarankan Anda untuk <strong>mengganti password</strong> setelah login pertama.
            </p>
        </div>

        <p style="font-size: 15px; color: #666; text-align: center; margin-top: 25px;">
            Selamat belajar dan semoga pengalaman magang Anda di DPR RI menjadi perjalanan yang berharga! ✨
        </p>

        <!-- Footer dengan Logo DPR RI -->
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e3f2fd;">
            @if($footerLogo)
            <img src="{{ $message->embed($footerLogo) }}" alt="Logo DPR RI" style="max-width: 100%; width: 100%; height: auto;">
            @endif
        </div>

    </div>

</body>
</html>
