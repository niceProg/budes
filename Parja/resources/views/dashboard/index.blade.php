@extends('parja::layouts.app')

@section('title', 'Dashboard Parja')

@php
    $totalWebsite = array_sum($ringkasanMenu['website']);
    $totalSkoring = array_sum($ringkasanMenu['skoring']);
    $totalPeserta = (int) ($ringkasanMenu['peserta']['total'] ?? 0);
    $totalPenilaianSelesai = (int) ($ringkasanMenu['penilaian']['selesai_semua'] ?? 0);
    $progressPenilaian = $totalPeserta > 0 ? round(($totalPenilaianSelesai / $totalPeserta) * 100, 1) : 0;
@endphp

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Parja</li>
        </ol>
        <h4 class="main-title mb-0">Dashboard Parja</h4>
    </div>
    <div class="text-muted fs-sm">Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="badge bg-primary-subtle text-primary">Peserta</span>
                    <i class="ri-group-line text-primary fs-5"></i>
                </div>
                <h3 class="mt-3 mb-1">{{ number_format($totalPeserta) }}</h3>
                <p class="text-muted mb-0 fs-sm">Total peserta aktif</p>
                @if ($totalPeserta === 0)
                    <div class="mt-2 small text-warning">0 karena belum ada data aktif</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="badge bg-success-subtle text-success">Survei</span>
                    <i class="ri-survey-line text-success fs-5"></i>
                </div>
                <h3 class="mt-3 mb-1">{{ number_format($surveiStatus['terbit']) }}</h3>
                <p class="text-muted mb-0 fs-sm">Survei berstatus terbit</p>
                @if ((int) $surveiStatus['terbit'] === 0)
                    <div class="mt-2 small text-warning">0 karena belum ada data aktif</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="badge bg-info-subtle text-info">Penilaian</span>
                    <i class="ri-file-chart-line text-info fs-5"></i>
                </div>
                <h3 class="mt-3 mb-1">{{ number_format($totalPenilaianSelesai) }}</h3>
                <p class="text-muted mb-0 fs-sm">Peserta sudah dinilai lengkap</p>
                @if ($totalPenilaianSelesai === 0)
                    <div class="mt-2 small text-warning">0 karena belum ada data aktif</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="badge bg-warning-subtle text-warning">Website</span>
                    <i class="ri-global-line text-warning fs-5"></i>
                </div>
                <h3 class="mt-3 mb-1">{{ number_format($totalWebsite) }}</h3>
                <p class="text-muted mb-0 fs-sm">Total konten website aktif</p>
                @if ($totalWebsite === 0)
                    <div class="mt-2 small text-warning">0 karena belum ada data aktif</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="mb-1">Sebaran Peserta per Dapil (Top 7)</h6>
                <p class="text-muted fs-sm mb-0">Sumber data menu Data Peserta</p>
            </div>
            <div class="card-body">
                <canvas id="dapilChart" height="115"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="mb-1">Status Publikasi Survei</h6>
                <p class="text-muted fs-sm mb-0">Draft vs Terbit vs Selesai</p>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="surveiStatusChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="mb-1">Progress Penilaian Peserta</h6>
                <p class="text-muted fs-sm mb-0">Target penilaian lengkap: {{ $progressPenilaian }}%</p>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="penilaianProgressChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="mb-1">Partisipasi Survei</h6>
                <p class="text-muted fs-sm mb-0">Berdasarkan status peserta survei</p>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="partisipasiChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="mb-1">Ringkasan Menu Parja</h6>
                <p class="text-muted fs-sm mb-0">Total data aktif per kelompok menu</p>
            </div>
            <div class="card-body">
                <canvas id="menuSummaryChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="mb-1">Rata-rata Nilai Peserta</h6>
                <p class="text-muted fs-sm mb-0">Sumber menu Data Penilaian CV, Esai, dan Video</p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-3 rounded border bg-light">
                            <div class="text-muted fs-sm">Nilai CV</div>
                            <div class="fs-4 fw-semibold">{{ number_format($avgNilai['cv'], 2) }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-3 rounded border bg-light">
                            <div class="text-muted fs-sm">Nilai Esai</div>
                            <div class="fs-4 fw-semibold">{{ number_format($avgNilai['esai'], 2) }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-3 rounded border bg-light">
                            <div class="text-muted fs-sm">Nilai Video</div>
                            <div class="fs-4 fw-semibold">{{ number_format($avgNilai['video'], 2) }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-3 rounded border bg-light">
                            <div class="text-muted fs-sm">Nilai Total</div>
                            <div class="fs-4 fw-semibold">{{ number_format($avgNilai['total'], 2) }}</div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row g-3 fs-sm">
                    <div class="col-md-4">
                        <div class="text-muted">CV Sudah Dinilai</div>
                        <div class="fw-semibold">{{ number_format($ringkasanMenu['penilaian']['cv_done']) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted">Esai Sudah Dinilai</div>
                        <div class="fw-semibold">{{ number_format($ringkasanMenu['penilaian']['esai_done']) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted">Video Sudah Dinilai</div>
                        <div class="fw-semibold">{{ number_format($ringkasanMenu['penilaian']['video_done']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="mb-1">Highlight Operasional</h6>
                <p class="text-muted fs-sm mb-0">Snapshot dari menu Website dan Skoring</p>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Poster Terbit</span>
                    <span class="fw-semibold">{{ number_format($websitePublished['poster_terbit']) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Publikasi Terbit</span>
                    <span class="fw-semibold">{{ number_format($websitePublished['publikasi_terbit']) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Total Master Skoring</span>
                    <span class="fw-semibold">{{ number_format($totalSkoring) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Peserta Lolos</span>
                    <span class="fw-semibold">{{ number_format($ringkasanMenu['peserta']['lolos']) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Peserta Lolos Seleksi</span>
                    <span class="fw-semibold">{{ number_format($ringkasanMenu['peserta']['lolos_seleksi']) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('template/dist/lib/chart.js/chart.min.js') }}"></script>
<script>
    const chartPalette = {
        blue: '#3b82f6',
        green: '#22c55e',
        amber: '#f59e0b',
        rose: '#f43f5e',
        slate: '#64748b',
        teal: '#14b8a6',
        indigo: '#6366f1',
        cyan: '#06b6d4'
    };

    const dapilLabels = @json($dapilRank->pluck('dapil')->values());
    const dapilTotals = @json($dapilRank->pluck('total')->values());

    const surveiStatusValues = [
        {{ (int) $surveiStatus['draft'] }},
        {{ (int) $surveiStatus['terbit'] }},
        {{ (int) $surveiStatus['selesai'] }}
    ];

    const partisipasiValues = [
        {{ (int) $surveiPartisipasi['terdaftar'] }},
        {{ (int) $surveiPartisipasi['sedang_mengisi'] }},
        {{ (int) $surveiPartisipasi['selesai'] }},
        {{ (int) $surveiPartisipasi['batal'] }}
    ];

    const menuSummaryValues = [
        {{ (int) $ringkasanMenu['survei']['total_survei'] }},
        {{ (int) $totalWebsite }},
        {{ (int) $totalSkoring }},
        {{ (int) $ringkasanMenu['peserta']['total'] }},
        {{ (int) $ringkasanMenu['penilaian']['selesai_semua'] }}
    ];

    new Chart(document.getElementById('dapilChart'), {
        type: 'bar',
        data: {
            labels: dapilLabels,
            datasets: [{
                label: 'Jumlah Peserta',
                data: dapilTotals,
                borderRadius: 6,
                backgroundColor: 'rgba(59, 130, 246, 0.75)',
                borderColor: chartPalette.blue,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    new Chart(document.getElementById('surveiStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Draft', 'Terbit', 'Selesai'],
            datasets: [{
                data: surveiStatusValues,
                backgroundColor: [chartPalette.slate, chartPalette.green, chartPalette.blue],
                borderWidth: 1
            }]
        },
        options: {
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('penilaianProgressChart'), {
        type: 'pie',
        data: {
            labels: ['Selesai Lengkap', 'Belum Lengkap'],
            datasets: [{
                data: [
                    {{ (int) $ringkasanMenu['penilaian']['selesai_semua'] }},
                    {{ (int) $ringkasanMenu['penilaian']['belum_lengkap'] }}
                ],
                backgroundColor: [chartPalette.green, chartPalette.amber],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('partisipasiChart'), {
        type: 'doughnut',
        data: {
            labels: ['Terdaftar', 'Sedang Mengisi', 'Selesai', 'Batal'],
            datasets: [{
                data: partisipasiValues,
                backgroundColor: [chartPalette.blue, chartPalette.cyan, chartPalette.green, chartPalette.rose],
                borderWidth: 1
            }]
        },
        options: {
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('menuSummaryChart'), {
        type: 'bar',
        data: {
            labels: ['Survei', 'Website', 'Skoring', 'Peserta', 'Penilaian Lengkap'],
            datasets: [{
                data: menuSummaryValues,
                borderRadius: 6,
                backgroundColor: [
                    chartPalette.indigo,
                    chartPalette.amber,
                    chartPalette.teal,
                    chartPalette.blue,
                    chartPalette.green
                ]
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
