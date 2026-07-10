@extends('layouts.app')

@section('title', 'Dashboard | Pegawai - SMART Setjen DPR RI')
@section('content')
<!-- Import Google Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">


<style>
    :root {
        --primary-navy: #82858e;
        --accent-gold: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%);
        --gold-solid: #b08d48;
        --glass-bg: rgba(255, 255, 255, 0.9);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

        /* === SWEETALERT CUSTOM THEME === */
    .swal2-popup {
        font-family: 'Plus Jakarta Sans', sans-serif;
        border-radius: 20px !important;
        padding: 2rem !important;
        border: 1px solid var(--border-color);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .swal2-title {
        font-size: 1.25rem !important;
        font-weight: 800 !important;
        color: var(--primary-dark) !important;
    }

    .swal2-html-container {
        margin-top: 1.2rem !important;
    }

    /* Input */
    .swal2-input {
        border-radius: 10px !important;
        border: 1px solid var(--border-color) !important;
        padding: 10px 12px !important;
        font-size: 0.9rem !important;
        background: #fcfdfe !important;
    }

    .swal2-input:focus {
        border-color: var(--accent-gold) !important;
        box-shadow: 0 0 0 3px rgba(176,141,72,.15) !important;
    }

    /* Buttons */
    .swal2-confirm {
        background: var(--primary-dark) !important;
        color: #fff !important;
        border-radius: 10px !important;
        padding: 10px 18px !important;
        font-weight: 700 !important;
    }

    .swal2-confirm:hover {
        background: #000 !important;
    }

    .swal2-cancel {
        background: transparent !important;
        color: var(--text-muted) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 10px !important;
        padding: 10px 18px !important;
    }

    /* Error text */
    .swal2-validation-message {
        background: #fffbeb !important;
        color: #92400e !important;
        border-radius: 10px !important;
        font-size: 0.8rem !important;
    }

    .swal-input-group {
        position: relative;
        margin-bottom: 12px;
    }

    .swal-input-group .swal2-input {
        margin: 0;
        padding-right: 38px;
    }

    .swal-eye {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.1rem;
        color: var(--text-muted);
        transition: 0.2s;
    }

    .swal-eye:hover {
        color: var(--accent-gold);
    }

    .main-dashboard {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8fafc;
        min-height: 100vh;
        padding-bottom: 50px;
        position: relative;
    }

    /* Background Pattern Batik */
    .main-dashboard::before {
        content: '';
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}'); 
        background-size: 500px;
        opacity: 0.03;
        z-index: 0;
        pointer-events: none;
    }

    /* Welcome Header Section */
    .welcome-banner {
        background: var(--primary-navy);
        border-radius: 30px;
        padding: 40px;
        position: relative;
        overflow: hidden;
        margin-bottom: 40px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);
        z-index: 1;
    }

    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 250px; height: 250px;
        background: var(--accent-gold);
        border-radius: 50%;
        opacity: 0.1;
    }

    .welcome-text h1 {
        font-weight: 800;
        font-size: 2.2rem;
        color: white;
        margin-bottom: 8px;
    }

    .welcome-text p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
        font-weight: 500;
    }

    .welcome-name {
        color: #d4af37;
        font-weight: 700;
        border-bottom: 2px solid #d4af37;
    }

    /* Stats Grid Customization */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
        margin-bottom: 50px;
        position: relative;
        z-index: 1;
    }

    .stat-card-modern {
        background: white;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .stat-card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.08);
        border-color: var(--gold-solid);
    }
    a.stat-card-modern { color: inherit; }

    .icon-box {
        width: 65px;
        height: 65px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    /* Colors for specific cards */
    .bg-light-gold { background: #fef9c3; color: #a16207; }
    .bg-light-green { background: #dcfce7; color: #15803d; }
    .bg-light-blue { background: #dbeafe; color: #1d4ed8; }
    .bg-light-orange { background: #ffedd5; color: #c2410c; }
    .bg-light-red { background: #fee2e2; color: #b91c1c; }

    .stat-info .value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--primary-navy);
        line-height: 1;
    }

    .stat-info .label {
        font-size: 0.9rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .cursor-pointer { cursor: pointer; }

    /* Quick Action Section */
    .action-section {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        padding: 40px;
        border: 1px solid white;
        position: relative;
        z-index: 1;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .section-header .line {
        flex-grow: 1;
        height: 2px;
        background: #e2e8f0;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .action-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        color: var(--primary-navy);
        border: 1px solid transparent;
        transition: var(--transition);
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .action-card:hover {
        border-color: var(--gold-solid);
        background: var(--primary-navy);
        color: white;
        transform: scale(1.02);
    }

    .action-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .action-content i {
        font-size: 1.5rem;
    }

    .action-card i.arrow {
        opacity: 0;
        transform: translateX(-10px);
        transition: var(--transition);
    }

    .action-card:hover i.arrow {
        opacity: 1;
        transform: translateX(0);
    }

    @media (max-width: 768px) {
        .welcome-banner { 
            padding: 30px 20px; 
            border-radius: 20px;
        }
        .welcome-text h1 { 
            font-size: 1.6rem; 
        }
        .welcome-text p {
            font-size: 0.95rem;
        }
        .stats-container {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .stat-card-modern {
            padding: 20px;
        }
        .icon-box {
            width: 55px;
            height: 55px;
            font-size: 1.5rem;
        }
        .stat-info .value {
            font-size: 1.5rem;
        }
        .action-grid {
            grid-template-columns: 1fr;
        }
        .action-section {
            padding: 30px 20px;
        }
    }

    @media (max-width: 576px) {
        .main-dashboard {
            padding: 1rem 0.5rem;
        }
        .welcome-banner {
            padding: 20px 15px;
            margin-bottom: 30px;
        }
        .welcome-text h1 {
            font-size: 1.3rem;
        }
        .welcome-name {
            font-size: 1rem;
        }
        .stats-container {
            margin-bottom: 30px;
        }
        .stat-card-modern {
            flex-direction: column;
            text-align: center;
            padding: 15px;
        }
        .icon-box {
            width: 50px;
            height: 50px;
            font-size: 1.3rem;
        }
        .stat-info .value {
            font-size: 1.3rem;
        }
        .stat-info .label {
            font-size: 0.8rem;
        }
        .action-section {
            padding: 20px 15px;
            border-radius: 20px;
        }
        .action-card {
            padding: 15px;
        }
    }
</style>

<div class="main-dashboard p-4">
    <div class="container-fluid">
        <!-- Header Banner -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Selamat Datang Kembali 👋</h1>
                <p>Portal {{ config('app.name') }} | <span class="welcome-name">{{ $roleAs['nama'] ?? ($pegawai->nama ?? 'Pegawai') }}</span></p>
                <p>Hari ini: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                @if(isset($roleAs['roles_internal']) && !empty($roleAs['roles_internal']))
                <div class="mt-3 d-flex flex-wrap gap-2">
                    @foreach($roleAs['roles_internal'] as $role)
                    <span class="badge bg-warning text-dark">
                        <i class="ri-user-star-line"></i> 
                        {{ $role['nama'] ?? $role->nama ?? '' }}
                    </span>
                    @endforeach
                    @if(isset($satker) && $satker)
                    <span class="badge bg-info text-white">
                        <i class="ri-building-line"></i> 
                        Satuan Kerja: {{ $satker->kode ?? ($satker->nama ?? '-') }}
                    </span>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Stats Cards untuk Pegawai - Semua role tampil sesuai role yang dimiliki -->
        <div class="stats-container">
            @if(!empty($isAtasan) && isset($satker) && $satker)
            <!-- Atasan -->
            <div class="stat-card-modern">
                <div class="icon-box bg-light-blue">
                    <i class="ri-group-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $statPesertaAktif ?? 0 }}</div>
                    <div class="label">Peserta yang Aktif</div>
                </div>
            </div>

            <div class="stat-card-modern">
                <div class="icon-box bg-light-gold">
                    <i class="ri-file-list-3-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $statTotalLamaran ?? 0 }}</div>
                    <div class="label">Total Lamaran</div>
                </div>
            </div>

            <div class="stat-card-modern">
                <div class="icon-box bg-light-green">
                    <i class="ri-calendar-check-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $statPresensiHariIni ?? 0 }}</div>
                    <div class="label">Presensi Hari Ini</div>
                </div>
            </div>

            <div class="stat-card-modern">
                <div class="icon-box bg-light-green">
                    <i class="ri-checkbox-circle-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $statPesertaDiterima ?? 0 }}</div>
                    <div class="label">Peserta Diterima</div>
                </div>
            </div>

            <div class="stat-card-modern">
                <div class="icon-box bg-light-red">
                    <i class="ri-close-circle-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $statPesertaDitolak ?? 0 }}</div>
                    <div class="label">Peserta Ditolak</div>
                </div>
            </div>
            @endif

            @if(!empty($isVerifikator))
            <!-- Verifikator -->
            <a href="{{ route('verifikator.biodata.index') }}" class="stat-card-modern text-decoration-none cursor-pointer" title="Ke halaman Biodata Peserta">
                <div class="icon-box bg-light-orange">
                    <i class="ri-file-warning-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ ($pesertaBelumVerifikasi ?? collect())->count() }}</div>
                    <div class="label">Peserta Belum Diverifikasi</div>
                </div>
            </a>
            @endif

            @if(!empty($isMentor) && empty($isAtasan) && isset($satker) && $satker)
            <!-- Mentor (tidak ditampilkan jika sudah atasan, agar tidak double) -->
            <div class="stat-card-modern">
                <div class="icon-box bg-light-blue">
                    <i class="ri-group-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $statMentorPesertaAktif ?? 0 }}</div>
                    <div class="label">Peserta Aktif</div>
                </div>
            </div>

            <div class="stat-card-modern">
                <div class="icon-box bg-light-green">
                    <i class="ri-calendar-check-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $statMentorPresensiHariIni ?? 0 }}</div>
                    <div class="label">Presensi Hari Ini</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Greeting berdasarkan jam
        const hour = new Date().getHours();
        const welcomeH1 = document.querySelector('.welcome-text h1');
        if (welcomeH1) {
            let greeting = "Selamat Datang Kembali 👋";
            if (hour < 12) greeting = "Selamat Pagi ☀️";
            else if (hour < 15) greeting = "Selamat Siang 🌤️";
            else if (hour < 18) greeting = "Selamat Sore 🌅";
            else greeting = "Selamat Malam 🌙";
            welcomeH1.innerHTML = greeting;
        }
    });

    document.getElementById('btnChangePassword').addEventListener('click', function () {
        Swal.fire({
            title: 'Ganti Password',
            html: `
                <div class="swal-input-group">
                    <input type="password" id="current_password" class="swal2-input" placeholder="Password Lama">
                    <i class="ri-eye-off-line swal-eye" data-target="current_password"></i>
                </div>

                <div class="swal-input-group">
                    <input type="password" id="new_password" class="swal2-input" placeholder="Password Baru">
                    <i class="ri-eye-off-line swal-eye" data-target="new_password"></i>
                </div>

                <div class="swal-input-group">
                    <input type="password" id="new_password_confirmation" class="swal2-input" placeholder="Konfirmasi Password Baru">
                    <i class="ri-eye-off-line swal-eye" data-target="new_password_confirmation"></i>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Perbarui Password',
            cancelButtonText: 'Batal',
            focusConfirm: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),

            didOpen: () => {
                document.querySelectorAll('.swal-eye').forEach(icon => {
                    icon.addEventListener('click', () => {
                        const input = document.getElementById(icon.dataset.target);
                        if (!input) return;

                        const isPassword = input.type === 'password';
                        input.type = isPassword ? 'text' : 'password';

                        icon.classList.toggle('ri-eye-line', isPassword);
                        icon.classList.toggle('ri-eye-off-line', !isPassword);
                    });
                });
            },

            preConfirm: async () => {
                const current_password = document.getElementById('current_password').value;
                const new_password = document.getElementById('new_password').value;
                const new_password_confirmation = document.getElementById('new_password_confirmation').value;

                if (!current_password || !new_password || !new_password_confirmation) {
                    Swal.showValidationMessage('Semua field wajib diisi');
                    return false;
                }

                if (new_password !== new_password_confirmation) {
                    Swal.showValidationMessage('Konfirmasi password tidak cocok');
                    return false;
                }

                try {
                    const res = await fetch("{{ route('admin.change-password') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            current_password,
                            new_password,
                            new_password_confirmation
                        })
                    });

                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal mengganti password');
                    return data;
                } catch (error) {
                    Swal.showValidationMessage(error.message);
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: result.value.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Warning peserta belum diverifikasi — tampil sekali per sesi (saat pertama buka dashboard setelah login)
    @if(!empty($showPesertaBelumVerifikasiPopup))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Peserta Belum Diverifikasi',
            html: 'Ada <strong>{{ ($pesertaBelumVerifikasi ?? collect())->count() }}</strong> peserta yang belum melengkapi Nota Dinas (Nodin).<br><br>Silakan verifikasi di halaman <strong>Biodata Peserta</strong>.',
            confirmButtonText: 'Ke Biodata Peserta',
            confirmButtonColor: '#b45309',
            showCloseButton: true,
            allowOutsideClick: true
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = "{{ route('verifikator.biodata.index') }}";
            }
        });
    });
    @endif
</script>
@endsection