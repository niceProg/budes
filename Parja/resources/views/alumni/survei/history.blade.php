@extends('parja::alumni.layouts.app')

@section('title', 'Riwayat Survei')
@section('page-title', 'Riwayat Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h5 class="mb-1 fw-bold text-maroon">Riwayat Pengisian Survei</h5>
        <p class="mb-0 text-muted">Daftar survei yang pernah Anda ikuti beserta statusnya.</p>
    </div>
    <a href="{{ route('alumni.survei.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="ri-arrow-left-line"></i> Kembali ke Data Survei
    </a>
</div>

@if($riwayat->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="ri-file-list-3-line text-muted" style="font-size:2.4rem;"></i>
            <h6 class="mt-3 mb-1">Belum Ada Riwayat</h6>
            <p class="text-muted mb-0">Anda belum memiliki riwayat pengisian survei.</p>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Survei</th>
                        <th>Status</th>
                        <th>Selesai</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $item)
                        @php
                            $status = $item->status_partisipasi;
                            $statusLabel = $status === 'selesai' ? 'Selesai' : ($status === 'sedang_mengisi' ? 'Sedang Mengisi' : 'Terdaftar');
                            $statusClass = $status === 'selesai' ? 'bg-success-subtle text-success' : ($status === 'sedang_mengisi' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary');
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->survei->judul }}</div>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($item->survei->deskripsi ?: '-', 90) }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y H:i') : '-' }}
                            </td>
                            <td class="text-end">
                                @if($status === 'selesai')
                                    <a href="{{ route('alumni.survei.result', $item->survei->id) }}" class="btn btn-sm btn-maroon">
                                        <i class="ri-bar-chart-box-line"></i> Lihat Hasil
                                    </a>
                                @else
                                    <a href="{{ route('alumni.survei.show', $item->survei->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-edit-2-line"></i> Lanjut Isi
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
