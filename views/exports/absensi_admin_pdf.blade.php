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
                <th>Tanggal</th>
                <th>Nama Peserta</th>
                <th>Kategori</th>
                <th>Satuan Kerja</th>
                <th>Jenis Kehadiran</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $presensi)
                @php
                    $tanggal = $presensi->tanggal
                        ? \Carbon\Carbon::parse($presensi->tanggal)->locale('id')->translatedFormat('d F Y')
                        : ($presensi->waktu_masuk ? \Carbon\Carbon::parse($presensi->waktu_masuk)->locale('id')->translatedFormat('d F Y') : '-');
                    
                    $waktuMasuk = $presensi->waktu_masuk 
                        ? \Carbon\Carbon::parse($presensi->waktu_masuk)->format('H:i')
                        : '-';
                    
                    $waktuKeluar = $presensi->waktu_keluar 
                        ? \Carbon\Carbon::parse($presensi->waktu_keluar)->format('H:i')
                        : '-';
                    
                    $peserta = $presensi->peserta;
                    $kategori = $peserta && $peserta->lamaran ? $peserta->lamaran->kategori : '-';
                    $satker = $peserta && $peserta->satker 
                        ? ($peserta->satker->nama ?? $peserta->satker->kode ?? '-')
                        : '-';
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $tanggal }}</td>
                    <td>{{ $peserta->nama ?? '-' }}</td>
                    <td>{{ $kategori }}</td>
                    <td>{{ $satker }}</td>
                    <td>{{ $presensi->jenis_kehadiran ?? '-' }}</td>
                    <td>{{ $waktuMasuk }}</td>
                    <td>{{ $waktuKeluar }}</td>
                    <td>{{ $presensi->keterangan ? strip_tags($presensi->keterangan) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
