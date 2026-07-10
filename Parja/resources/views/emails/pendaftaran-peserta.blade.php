<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Pendaftaran Parlemen Remaja DPR RI</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:Arial, 'Segoe UI', sans-serif; color:#241a2b;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7; padding:24px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0"
          style="width:600px; max-width:92%; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 8px 28px rgba(65,23,75,0.10);">

          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(135deg,#41174B 0%,#bf0050 100%); padding:28px 32px; text-align:center;">
              <img src="{{ asset('parja-landing/images/icons/logo-parja2.png') }}" alt="Parlemen Remaja DPR RI"
                style="height:52px; max-width:100%;">
              <p style="margin:14px 0 0; color:#ffffff; font-size:13px; letter-spacing:0.5px;">PARLEMEN REMAJA DPR RI</p>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:32px;">
              <h1 style="margin:0 0 6px; font-size:20px; color:#41174B;">Pendaftaran Berhasil Diterima! 🎉</h1>
              <p style="margin:0 0 18px; font-size:14px; line-height:1.7; color:#4a4150;">
                Halo <strong>{{ $nama }}</strong>,<br>
                Terima kasih telah mendaftar sebagai calon peserta <strong>Parlemen Remaja DPR RI Angkatan 2026</strong>.
                Data pendaftaranmu sudah kami terima dan tercatat di sistem panitia.
              </p>

              <!-- Ringkasan data -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                style="background:#f8eef5; border-radius:12px; padding:6px 0; margin:0 0 22px;">
                <tr><td style="padding:14px 20px 4px; font-size:12px; font-weight:bold; color:#bf0050; letter-spacing:0.6px;">RINGKASAN PENDAFTARAN</td></tr>
                @php
                  $rows = [
                    'Nama Lengkap' => $nama,
                    'Asal Sekolah' => $asalSekolah,
                    'Provinsi' => $namaProvinsi,
                    'Kabupaten/Kota' => $namaKabupaten,
                    'Dapil' => $namaDapil,
                    'Tanggal Daftar' => $tanggal,
                  ];
                @endphp
                @foreach ($rows as $label => $value)
                  <tr>
                    <td style="padding:5px 20px; font-size:13px; color:#695a72; width:42%;">{{ $label }}</td>
                    <td style="padding:5px 20px; font-size:13px; color:#241a2b; font-weight:bold;">{{ $value ?: '-' }}</td>
                  </tr>
                @endforeach
                <tr><td colspan="2" style="padding:8px;"></td></tr>
              </table>

              <p style="margin:0 0 8px; font-size:14px; font-weight:bold; color:#41174B;">Langkah selanjutnya</p>
              <p style="margin:0 0 18px; font-size:14px; line-height:1.7; color:#4a4150;">
                Tim panitia akan melakukan verifikasi berkas dan seleksi. Informasi tahap berikutnya
                (seleksi, pengumuman, dan jadwal) akan dikirimkan ke email ini. Mohon pantau kotak masuk
                (dan folder spam) secara berkala.
              </p>

              <p style="margin:0; font-size:13px; line-height:1.7; color:#695a72;">
                Jika ada pertanyaan, hubungi kami di
                <a href="mailto:parlemenremaja@dpr.go.id" style="color:#bf0050; text-decoration:none;">parlemenremaja@dpr.go.id</a>.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#41174B; padding:18px 32px; text-align:center;">
              <p style="margin:0; font-size:11px; color:rgba(255,255,255,0.7); line-height:1.6;">
                Email ini dikirim otomatis oleh sistem Parlemen Remaja DPR RI. Mohon tidak membalas email ini.<br>
                &copy; {{ date('Y') }} Sekretariat Jenderal DPR RI.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
