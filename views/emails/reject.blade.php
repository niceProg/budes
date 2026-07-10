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

        <h2 style="font-size: 24px; color: #0d47a1; margin-bottom: 15px;">
            Dear {{ $payload['nama'] }},
        </h2>

        <p style="font-size: 18px; color: #333; line-height: 1.7;">
            Terima kasih atas ketertarikan Anda untuk melamar program Magang DPR RI.
            Kami sangat menghargai waktu, perhatian, serta usaha yang telah Anda berikan dalam proses pendaftaran.
        </p>

        <p style="font-size: 18px; color: #333; line-height: 1.7; margin-top: 20px;">
            Setelah melalui proses peninjauan, dengan penuh hormat kami informasikan bahwa
            lamaran Anda <strong>belum dapat kami lanjutkan pada kesempatan ini</strong>.
        </p>

        @if(!empty($payload['catatan_penolakan']))
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 15px 20px; margin-top: 20px;">
            <p style="font-size: 14px; font-weight: bold; color: #991b1b; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                📝 Catatan dari Tim Peninjau:
            </p>
            <p style="font-size: 16px; color: #7f1d1d; line-height: 1.7; margin: 0; white-space: pre-line;">{{ $payload['catatan_penolakan'] }}</p>
        </div>
        @endif

        <p style="font-size: 18px; color: #333; line-height: 1.7; margin-top: 20px;">
            Namun demikian, kami mendorong Anda untuk tidak berkecil hati.
            Silakan mencoba kembali pada periode rekrutmen berikutnya sesuai minat dan kompetensi Anda.
        </p>

        <p style="font-size: 18px; color: #333; line-height: 1.7; margin-top: 20px;">
            Terima kasih atas antusiasme dan minat Anda untuk menjadi bagian dari program Magang DPR RI.
            Semoga kesempatan baik lainnya selalu menyertai langkah Anda ke depan.
        </p>

        <br><br>

        <p style="font-size: 18px; color: #333; margin-top: 10px;">
            Best Regards,<br>
            <span style="font-size: 17px; color: #555;">Tim Rekrutmen Magang DPR RI</span>
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
