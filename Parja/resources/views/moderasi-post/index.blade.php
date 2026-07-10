@extends('parja::layouts.app')

@section('title', 'Moderasi Post Alumni')
@section('page-title', 'Moderasi Post Alumni')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item active" aria-current="page">Moderasi Post</li>
        </ol>
        <h4 class="main-title mb-0">Moderasi Post Alumni</h4>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Tab Filter --}}
<div class="card mb-3">
    <div class="card-body p-2">
        <ul class="nav nav-pills gap-1">
            @foreach (['semua' => 'Semua', 'pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'req-public' => 'Req. Public'] as $key => $label)
                @php
                    $isActive = $tab === $key;
                    $url      = route('parja.moderasi-post.index', ['tab' => $key]);
                    $count    = match ($key) {
                        'pending'    => \Modules\Parja\App\Models\AlumniPostModel::pending()->count(),
                        'disetujui'  => \Modules\Parja\App\Models\AlumniPostModel::approved()->count(),
                        'ditolak'    => \Modules\Parja\App\Models\AlumniPostModel::rejected()->count(),
                        'req-public' => \Modules\Parja\App\Models\AlumniPostModel::where('privasi_request', 'public')->count(),
                        default      => \Modules\Parja\App\Models\AlumniPostModel::count(),
                    };
                @endphp
                <li class="nav-item">
                    <a href="{{ $url }}"
                       class="nav-link py-1 px-3 {{ $isActive ? 'active' : '' }}"
                       @if ($isActive) aria-current="page" @endif>
                        {{ $label }}
                        <span class="badge rounded-pill ms-1 {{ $isActive ? 'bg-white text-dark' : 'bg-secondary' }}">
                            {{ $count }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('parja.moderasi-post.index') }}" class="mb-3">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Cari nama alumni atau isi post..."
                       value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit"><i class="ri-search-line me-1"></i>Cari</button>
                @if(!empty($search ?? ''))
                    <a href="{{ route('parja.moderasi-post.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary" title="Reset"><i class="ri-close-line"></i></a>
                @endif
            </div>
        </form>
        @if ($posts->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="ri-inbox-line fs-1 d-block mb-2"></i>
                Tidak ada post untuk ditampilkan.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="p-2" style="width:4%">#</th>
                            <th class="p-2">Alumni</th>
                            <th class="p-2" style="width:10%">Tipe</th>
                            <th class="p-2">Preview Konten</th>
                            <th class="p-2 text-center" style="width:8%">Foto</th>
                            <th class="p-2" style="width:13%">Dikirim</th>
                            <th class="p-2 text-center" style="width:12%">Status</th>
                            <th class="p-2 text-center" style="width:10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $i => $post)
                            <tr>
                                <td class="p-2 text-muted small">{{ $posts->firstItem() + $i }}</td>
                                <td class="p-2">
                                    <span class="fw-semibold d-block">{{ $post->alumni?->nama ?? '-' }}</span>
                                    <span class="text-muted small">{{ $post->alumni?->dapil ?? '-' }}</span>
                                </td>
                                <td class="p-2">
                                    <span class="badge {{ $post->tipe === 'artikel' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $post->tipe === 'artikel' ? 'Artikel' : 'Freepost' }}
                                    </span>
                                </td>
                                <td class="p-2">
                                    @if ($post->tipe === 'artikel' && $post->judul)
                                        <strong class="d-block small">{{ Str::limit($post->judul, 40) }}</strong>
                                    @endif
                                    <span class="text-muted small">{{ Str::limit($post->konten, 80) }}</span>
                                </td>
                                <td class="p-2 text-center">
                                    @if (!empty($post->foto_paths))
                                        <span class="badge bg-info text-dark">{{ count($post->foto_paths) }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="p-2">
                                    <span class="small">{{ $post->created_at->format('d M Y') }}</span><br>
                                    <span class="text-muted small">{{ $post->created_at->format('H:i') }}</span>
                                </td>
                                <td class="p-2 text-center">
                                    @if ($post->status === 0)
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($post->status === 1)
                                        <span class="badge bg-success">Terbit</span>
                                        @if ($post->privasi_request === 'public')
                                            <span class="badge bg-primary mt-1 d-block">Req. Public</span>
                                        @endif
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="p-2 text-center">
                                    <a href="{{ route('parja.moderasi-post.show', $post->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="ri-eye-line"></i> Detail
                                    </a>
                                    @if (rbac_can_delete_parja())
                                        <form action="{{ route('parja.moderasi-post.destroy', $post->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus post ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
