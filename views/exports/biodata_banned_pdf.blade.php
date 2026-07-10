<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        .header { margin-bottom: 14px; }
        .title { font-size: 16px; font-weight: 700; margin: 0; }
        .subtitle { font-size: 12px; color: #6b7280; margin: 4px 0 0 0; }
        .meta { font-size: 10px; color: #6b7280; margin-top: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; vertical-align: top; }
        th { background: #f9fafb; text-align: left; font-weight: 700; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">{{ $title }}</p>
        <p class="subtitle">{{ $subtitle }}</p>
        <div class="meta">Generated: {{ $generatedAt->locale('id')->translatedFormat('d F Y H:i:s') }} | Total: {{ $items->count() }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Gender</th>
                <th>Tempat Tanggal Lahir</th>
                <th>Universitas/Sekolah</th>
                <th>Satuan Kerja</th>
                <th>No HP</th>
                <th>Jenjang</th>
                <th>Kategori</th>
                <th>Nilai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $p)
                @php
                    $lamaran = $p->lamaran;
                    $gender = $lamaran->gender ?? null;
                    $genderText = $gender === 'L' ? 'Laki-laki' : ($gender === 'P' ? 'Perempuan' : '-');
                    $tempatLahir = $lamaran->tempat_lahir ?? '';
                    $tanggalLahir = $lamaran->tanggal_lahir ? $lamaran->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '';
                    $tempatTanggalLahir = trim($tempatLahir . ($tempatLahir && $tanggalLahir ? ', ' : '') . $tanggalLahir);
                    if (empty($tempatTanggalLahir)) $tempatTanggalLahir = '-';
                    $satker = $p->satker ? ($p->satker->nama ?? $p->satker->kode ?? '-') : '-';
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->nik ?? $lamaran->nik ?? '-' }}</td>
                    <td>{{ $genderText }}</td>
                    <td>{{ $tempatTanggalLahir }}</td>
                    <td>{{ $lamaran->instansi ?? '-' }}</td>
                    <td>{{ $satker }}</td>
                    <td>{{ $lamaran->kontak ?? '-' }}</td>
                    <td>{{ optional($lamaran->pendidikan)->strata ?? '-' }}</td>
                    <td>{{ $p->kategori ?? '-' }}</td>
                    <td>{{ $p->nilai ?? '0.00' }}</td>
                    <td>BANNED</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

