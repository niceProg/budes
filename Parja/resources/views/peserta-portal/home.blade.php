<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Peserta — Parlemen Remaja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('template/dist/lib/remixicon/fonts/remixicon.css') }}">
</head>

<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                    <i class="ri-group-2-fill text-white text-sm"></i>
                </div>
                <h1 class="text-lg font-bold text-gray-900">Portal Peserta</h1>
            </div>
            <form method="POST" action="{{ route('alumni.logout') }}">
                @csrf
                <button type="submit"
                    class="border border-red-200 text-red-600 hover:bg-red-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-1">
                    <i class="ri-logout-box-r-line"></i><span>Keluar</span>
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-gradient-to-br from-cyan-600 to-blue-700 rounded-2xl p-8 text-white mb-6 shadow-lg">
            <p class="text-cyan-100 text-sm mb-1">Selamat datang di Parlemen Remaja DPR RI</p>
            <h2 class="text-2xl font-bold">Halo, {{ $user->name ?? $user->email }} 👋</h2>
            <span class="inline-flex items-center mt-3 px-3 py-1 rounded-full text-xs font-medium bg-white/15">
                <i class="ri-shield-check-line mr-1"></i> Peserta Aktif (parja:peserta)
            </span>
        </div>

        <div class="bg-white rounded-2xl shadow border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Data Peserta</h3>
            @if ($peserta)
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <dt class="text-xs font-medium text-gray-500 mb-1">Nama</dt>
                        <dd class="font-semibold text-gray-900">{{ $peserta->nama ?: '—' }}</dd>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <dt class="text-xs font-medium text-gray-500 mb-1">NISN</dt>
                        <dd class="font-semibold text-gray-900">{{ $peserta->nisn ?: '—' }}</dd>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <dt class="text-xs font-medium text-gray-500 mb-1">Asal Sekolah</dt>
                        <dd class="font-semibold text-gray-900">{{ $peserta->asal_sekolah ?: '—' }}</dd>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <dt class="text-xs font-medium text-gray-500 mb-1">Dapil</dt>
                        <dd class="font-semibold text-gray-900">{{ $peserta->nama_dapil ?: '—' }}</dd>
                    </div>
                </dl>
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
                    <i class="ri-information-line mr-1"></i>
                    Data pendaftaran belum tertaut ke akun ini. Hubungi panitia bila ini keliru.
                </div>
            @endif

            <div class="mt-6 border-t border-gray-100 pt-4 text-sm text-gray-500">
                <i class="ri-time-line mr-1"></i> Fitur peserta lainnya akan hadir di sini.
            </div>
        </div>
    </main>
</body>

</html>
