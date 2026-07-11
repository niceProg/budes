<script setup lang="ts">
import { useApp } from '~/stores/app'
import { decorateDemand } from '~/utils/decorate'

const app = useApp()

const FILTERS: Record<string, string[] | null> = {
  AKTIF: ['DRAFT', 'OPEN', 'PARTIAL'],
  OPEN: ['OPEN'],
  PARTIAL: ['PARTIAL'],
  SELESAI: ['FULFILLED'],
  SEMUA: ['DRAFT', 'OPEN', 'PARTIAL', 'FULFILLED'], // tanpa CANCELLED
}
onMounted(() => app.hydratePublic()) // segarkan data agar sinkron lintas peran
const chips = [
  { key: 'AKTIF', label: 'Aktif' },
  { key: 'OPEN', label: 'Dibuka' },
  { key: 'PARTIAL', label: 'Sebagian' },
  { key: 'SELESAI', label: 'Selesai' },
  { key: 'SEMUA', label: 'Semua' },
] as const

const pasarList = computed(() => {
  const allow = FILTERS[app.filter]
  return app.demands.filter((d) => !allow || allow.includes(d.status))
})
const sorot = computed(() => {
  const list = pasarList.value
  if (!list.length) return null
  const raw = list.reduce((a, b) => (a.total * a.harga >= b.total * b.harga ? a : b))
  return decorateDemand(raw)
})
const gridList = computed(() => pasarList.value.filter((d) => d.id !== sorot.value?.id))

// Permintaan milik pembeli yang DP-nya belum dibayar (masih DRAFT).
const unpaidDpDemands = computed(() =>
  app.isBuyer
    ? app.demands.filter((d) => d.owner === (app.user?.id ?? '') && d.status === 'DRAFT' && d.dp !== 'PAID')
    : [],
)

// Angka nyata dari backend (GET /api/stats); '—' selama belum termuat.
const stats = computed(() => {
  const s = app.stats
  return [
    { n: s ? String(s.demands) : '—', l: 'Permintaan' },
    { n: s ? String(s.warga) : '—', l: 'Warga Terdaftar' },
    { n: s ? String(s.listings) : '—', l: 'Komoditas Etalase' },
  ]
})
const tahapan = [
  { ikon: '📋', judul: 'Pembeli Memposting Kebutuhan', teks: 'Pembeli usaha mengumumkan komoditas, jumlah, dan harga target — lalu membayar uang muka 30%.' },
  { ikon: '🤝', judul: 'Warga Menyanggupi', teks: 'Warga desa menyanggupi sesuai kapasitas panen. Satu kebutuhan besar dirakit gotong royong.' },
  { ikon: '🚜', judul: 'Setor ke Koperasi', teks: 'Hasil panen disetor ke koperasi desa (KDMP) sebagai hub — dikumpulkan dan diperiksa.' },
  { ikon: '✅', judul: 'Serah-Terima ke Pembeli', teks: 'Koperasi menyerahkan pasokan ke pembeli. Sisa dilunasi, komisi 5% tercatat.' },
]
const pilar = [
  { ikon: '🧩', judul: 'Merakit yang tak bisa Anda rakit sendiri', teks: 'Satu pesanan besar dipenuhi banyak petani sekaligus — koordinasi lintas desa yang kami jamin, bukan sekadar daftar kontak.' },
  { ikon: '🛡️', judul: 'Kepastian proses, bukan janji stok', teks: 'Bila satu petani gagal setor, sistem memicu gotong-royong ulang dan menurunkan reputasinya. Anda beli kepastian pemenuhan.' },
  { ikon: '🔒', judul: 'Uang muka terjamin', teks: 'DP 30% + biaya layanan ditahan di depan. Batal sepihak → hangus; gagal dipenuhi → dikembalikan. Transparan.' },
]
</script>

<template>
  <div>
    <!-- HERO (gaya Parja: gradien magenta + wave) — disembunyikan untuk admin -->
    <section v-if="!app.isAdmin" class="relative overflow-hidden bg-pink-gradient pt-8">
      <div class="pointer-events-none absolute -right-28 -top-28 h-[480px] w-[480px] rounded-full bg-white/[0.06]" />
      <div class="pointer-events-none absolute -left-24 bottom-16 h-[360px] w-[360px] rounded-full bg-white/[0.04]" />

      <div class="page relative z-[2]">
        <div class="grid items-center gap-8 py-10 lg:grid-cols-2 lg:gap-10 lg:py-16">
          <!-- teks -->
          <div class="animate-fadeUp">
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/15 px-4 py-1.5 font-heading text-[0.78rem] font-semibold tracking-wide text-white">
              ★ Pasar Gotong Royong Desa
            </div>
            <h1 class="mb-4 font-heading text-[clamp(2rem,5vw,3.2rem)] font-extrabold leading-[1.2] text-white [text-shadow:0_2px_20px_rgba(0,0,0,0.1)]">
              Dari desa, <span class="text-pink-100">untuk meja makan</span> Indonesia!
            </h1>
            <p class="mb-6 max-w-lg text-[clamp(0.94rem,1.8vw,1.1rem)] leading-relaxed text-white/90">
              Platform tempat pembeli usaha memposting kebutuhan, warga menyanggupi dari hasil panen,
              dan Koperasi Desa (KDMP) menjadi penghubungnya. Lihat dulu — daftar saat ingin bertransaksi.
            </p>
            <div class="mb-8 flex flex-wrap gap-3">
              <button v-if="app.isBuyer || !app.isLoggedIn" class="btn-light px-6 py-3 text-[0.95rem]" @click="app.ctaBuat()">+ Buat Permintaan</button>
              <NuxtLink to="/etalase" class="btn-outline-light px-6 py-3 text-[0.95rem]">Lihat Etalase</NuxtLink>
            </div>
            <div class="flex flex-wrap gap-8">
              <div v-for="s in stats" :key="s.l">
                <div class="font-heading text-2xl font-extrabold leading-none text-white">{{ s.n }}</div>
                <div class="mt-1 text-xs text-white/75">{{ s.l }}</div>
              </div>
            </div>
          </div>

          <!-- visual -->
          <div class="flex animate-fadeUp justify-center lg:justify-end">
            <div class="relative w-full max-w-[440px]">
              <!-- kartu utama -->
              <div class="relative overflow-hidden rounded-3xl border border-white/25 bg-white/[0.12] p-8 shadow-[0_20px_60px_rgba(0,0,0,0.25)] backdrop-blur">
                <div class="pointer-events-none absolute -right-12 -top-12 h-44 w-44 rounded-full bg-white/25 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-14 -left-10 h-44 w-44 rounded-full bg-amber-300/30 blur-3xl"></div>

                <!-- ring progres gotong royong -->
                <div class="relative mx-auto flex h-40 w-40 items-center justify-center">
                  <svg viewBox="0 0 120 120" class="h-full w-full -rotate-90">
                    <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="9" />
                    <circle cx="60" cy="60" r="52" fill="none" stroke="#fff" stroke-width="9" stroke-linecap="round" stroke-dasharray="327" class="ring" />
                  </svg>
                  <div class="absolute flex flex-col items-center">
                    <span class="bob text-5xl">🌾</span>
                    <span class="mt-0.5 font-heading text-base font-extrabold text-white">68%</span>
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-white/70">tersanggupi</span>
                  </div>
                </div>

                <p class="mt-5 text-center text-[13.5px] leading-relaxed text-white/85">
                  Pasokan desa yang terjamin,<br />dirakit gotong royong koperasi
                </p>

                <!-- chip warga menyanggupi (mengambang) -->
                <div class="chip-a absolute right-4 top-7 flex items-center gap-1.5 rounded-full bg-white/95 py-1 pl-1.5 pr-2.5 shadow-lg">
                  <span class="text-[13px]">🧑‍🌾</span><span class="text-[11px] font-extrabold text-gray-700">+120 kg</span>
                </div>
                <div class="chip-b absolute left-3 top-24 flex items-center gap-1.5 rounded-full bg-white/95 py-1 pl-1.5 pr-2.5 shadow-lg">
                  <span class="text-[13px]">🧑‍🌾</span><span class="text-[11px] font-extrabold text-gray-700">+80 kg</span>
                </div>
              </div>

              <!-- badge Pasar Aktif -->
              <div class="badge-float absolute -bottom-3.5 left-5 flex max-w-[240px] items-center gap-2.5 rounded-2xl bg-white p-2.5 pr-4 shadow-[0_8px_24px_rgba(0,0,0,0.15)]">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-pink-gradient text-white">🏆</div>
                <div>
                  <strong class="block font-heading text-[0.82rem] leading-tight text-gray-800">Pasar Aktif!</strong>
                  <span class="flex items-center gap-1 text-[0.7rem] text-gray-600">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>Koperasi Desa Merah Putih
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- wave -->
      <div class="pointer-events-none absolute -bottom-[2px] left-0 w-full leading-[0]">
        <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="block w-full">
          <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#ffffff" />
        </svg>
      </div>
    </section>

    <div class="page pb-20">
      <!-- ALUR / TAHAPAN — disembunyikan untuk admin -->
      <section v-if="!app.isAdmin" class="mt-14 text-center">
        <span class="section-badge">Alur Gotong Royong</span>
        <h2 class="section-heading mb-10">Bagaimana Bursa Desa Bekerja</h2>
        <div class="grid gap-6" style="grid-template-columns: repeat(auto-fit, minmax(230px, 1fr))">
          <div v-for="(t, i) in tahapan" :key="i" class="group flex flex-col items-center">
            <div class="step-circle mb-5 group-hover:scale-110">{{ t.ikon }}</div>
            <div class="step-card min-h-[180px] w-full group-hover:-translate-y-1">
              <div class="mb-2 font-heading text-[11px] font-bold uppercase tracking-widest text-pink-600">Langkah {{ i + 1 }}</div>
              <h4 class="mb-2 font-heading text-[15px] font-bold text-gray-800">{{ t.judul }}</h4>
              <p class="text-[13px] text-gray-600">{{ t.teks }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- PILAR — disembunyikan untuk admin -->
      <template v-if="!app.isAdmin">
        <div class="section-title mt-20">
          <span class="section-badge">Kenapa Bursa Desa</span>
          <h2>Bukan Sekadar Marketplace</h2>
          <p>Kami menempatkan koperasi desa sebagai hub aktif yang merakit pasokan menjadi kepastian.</p>
        </div>
        <div class="grid gap-6" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr))">
          <div v-for="(p, i) in pilar" :key="i" class="card card-hover group p-8">
            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-pink-soft text-2xl transition group-hover:bg-pink-gradient">
              {{ p.ikon }}
            </div>
            <h3 class="mb-2 font-heading text-[1.1rem] font-bold text-gray-800">{{ p.judul }}</h3>
            <p class="text-[0.9rem] text-gray-600">{{ p.teks }}</p>
          </div>
        </div>
      </template>

      <!-- Alert: DP belum dibayar (pembeli) -->
      <div v-if="unpaidDpDemands.length" class="mt-8 flex flex-wrap items-center gap-3 rounded-xl border border-warning-200 bg-warning-50 px-5 py-3.5">
        <span class="text-xl">⏳</span>
        <div class="min-w-0 flex-1">
          <div class="text-[13.5px] font-extrabold text-warning-800">{{ unpaidDpDemands.length }} permintaanmu menunggu pembayaran DP</div>
          <div class="text-[12.5px] text-warning-700">Bayar uang muka agar permintaan tampil publik & bisa disanggupi warga desa.</div>
        </div>
        <NuxtLink to="/aktivitas" class="btn-primary shrink-0 px-4 py-2 text-[12.5px]">Bayar DP →</NuxtLink>
      </div>

      <!-- PERMINTAAN PEMBELI -->
      <div class="section-title" :class="app.isAdmin ? 'mt-6' : 'mt-20'">
        <span class="section-badge">Pasar Aktif</span>
        <h2>Permintaan Pembeli</h2>
        <p>Kebutuhan yang bisa disanggupi warga lewat koperasi desa.</p>
      </div>

      <div class="mb-5 flex flex-wrap justify-center gap-2">
        <button
          v-for="c in chips"
          :key="c.key"
          class="chip cursor-pointer border px-4 py-2 text-[12.5px] transition"
          :class="app.filter === c.key
            ? 'border-pink-600 bg-pink-600 text-white'
            : 'border-gray-350 bg-white text-gray-600 hover:border-pink-600 hover:text-pink-600'"
          @click="app.filter = c.key"
        >
          {{ c.label }}
        </button>
      </div>

      <!-- sorotan -->
      <NuxtLink
        v-if="sorot"
        :to="`/permintaan/${sorot.id}`"
        class="mb-5 flex cursor-pointer flex-wrap items-center gap-5 rounded-2xl border border-gray-200 border-l-4 border-l-pink-600 bg-white p-5 shadow-soft transition hover:shadow-lift sm:gap-6 sm:p-7"
      >
        <div class="min-w-0 flex-[1.6]">
          <div class="mb-2 font-heading text-[11px] font-bold uppercase tracking-widest text-pink-600">Permintaan Sorotan</div>
          <div class="mb-2 flex flex-wrap items-center gap-2.5">
            <div class="font-heading text-2xl font-bold leading-tight text-gray-800">{{ sorot.item_name }}</div>
            <StatusBadge :badge="sorot.badge" />
          </div>
          <div class="mb-3.5 text-sm text-gray-600">{{ sorot.qtyLine }} · tenggat {{ sorot.tglTxt }} ({{ sorot.hariTxt }})</div>
          <div class="flex flex-wrap gap-[22px]">
            <div>
              <div class="text-[11px] font-semibold text-gray-500">Harga target</div>
              <div class="text-[19px] font-extrabold text-pink-600">{{ sorot.hargaTxt }}<span class="text-xs font-semibold text-gray-500"> /{{ sorot.satuan }}</span></div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500">Nilai total</div>
              <div class="text-[19px] font-extrabold text-gray-800">{{ sorot.totalTxt }}</div>
            </div>
          </div>
        </div>
        <div class="min-w-[220px] flex-1">
          <div class="mb-1.5 flex justify-between text-[12.5px] text-gray-600">
            <span class="font-bold text-gray-800">{{ sorot.pct }}% tersanggupi</span>
            <span>{{ sorot.hariTxt }}</span>
          </div>
          <ProgressBar :pct="sorot.pct" height="h-3" class="mb-4" />
          <span class="btn-primary btn-block py-3">{{ app.isAdmin ? 'Lihat Detail' : 'Lihat & Sanggupi' }}</span>
        </div>
      </NuxtLink>

      <!-- grid -->
      <div v-if="gridList.length" class="grid gap-4" style="grid-template-columns: repeat(auto-fill, minmax(268px, 1fr))">
        <DemandCard v-for="d in gridList" :key="d.id" :d="d" />
      </div>

      <!-- empty -->
      <div v-if="!pasarList.length" class="card rounded-2xl border border-dashed border-gray-350 px-6 py-12 text-center">
        <div class="mb-1.5 font-heading text-[17px] font-bold text-gray-800">Belum ada permintaan pada filter ini</div>
        <p class="mb-[18px] text-[13.5px] text-gray-600">Jadilah pembeli pertama yang memposting kebutuhan komoditas desa.</p>
        <button v-if="app.isBuyer || !app.isLoggedIn" class="btn-primary px-5 py-2.5" @click="app.ctaBuat()">+ Buat Permintaan</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes bob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
@keyframes floatChip { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
@keyframes drawRing { from { stroke-dashoffset: 327; } to { stroke-dashoffset: 105; } }

.bob { animation: bob 3s ease-in-out infinite; }
.ring { stroke-dashoffset: 327; animation: drawRing 1.8s cubic-bezier(0.2, 0.8, 0.2, 1) 0.3s forwards; }
.chip-a { animation: floatChip 3.6s ease-in-out infinite; }
.chip-b { animation: floatChip 3.6s ease-in-out 0.9s infinite; }
.badge-float { animation: bob 4.5s ease-in-out infinite; }

@media (prefers-reduced-motion: reduce) {
  .bob, .chip-a, .chip-b, .badge-float { animation: none; }
  .ring { stroke-dashoffset: 105; animation: none; }
}
</style>
