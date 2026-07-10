@extends('parja::alumni.layouts.app')

@section('title', 'Ringkasan Hasil Survei')
@section('page-title', 'Hasil Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h5 class="mb-1 fw-bold text-maroon">Ringkasan Hasil Pribadi</h5>
        <p class="mb-0 text-muted">Jawaban yang Anda kirim untuk survei ini.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('alumni.survei.history') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-history-line"></i> Riwayat
        </a>
        <a href="{{ route('alumni.survei.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-arrow-left-line"></i> Data Survei
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <h6 class="mb-1 fw-bold">{{ $survei->judul }}</h6>
        <p class="text-muted mb-2">{{ $survei->deskripsi ?: '-' }}</p>
        <div class="d-flex flex-wrap gap-2">
            <span class="badge bg-success-subtle text-success">Status: Selesai</span>
            <span class="badge bg-info-subtle text-info">Pertanyaan terjawab: {{ count($summaryRows) }}</span>
            <span class="badge bg-primary-subtle text-primary">Total nilai: {{ $totalNilai }}</span>
            <span class="badge bg-secondary-subtle text-secondary">
                Tanggal kirim: {{ $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai)->translatedFormat('d M Y H:i') : '-' }}
            </span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 45%;">Pertanyaan</th>
                    <th style="width: 15%;">Tipe</th>
                    <th>Jawaban Anda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summaryRows as $row)
                    <tr>
                        <td class="fw-semibold">{{ $row['pertanyaan'] }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ ucfirst($row['tipe']) }}</span>
                        </td>
                        <td>{{ $row['jawaban'] ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Belum ada jawaban tersimpan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
