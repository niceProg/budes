<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc;padding:20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="padding:20px 24px;background:#0f172a;color:#ffffff;">
                            <h2 style="margin:0;font-size:20px;line-height:1.3;">Pengumuman SIAP-MAGANG</h2>
                            <p style="margin:8px 0 0;font-size:13px;opacity:.9;">Setjen DPR RI</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 14px;font-size:14px;">Halo <strong>{{ $recipientName ?: 'Peserta' }}</strong>,</p>
                            <div style="font-size:14px;line-height:1.65;color:#334155;">
                                {!! clean($announcementHtml) !!}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:12px;color:#64748b;">
                            Email ini dikirim otomatis melalui sistem SIAP-MAGANG.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
