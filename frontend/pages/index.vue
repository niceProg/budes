<script setup lang="ts">
import { useApp } from '~/stores/app'
import { decorateDemand } from '~/utils/decorate'

const app = useApp()

const FILTERS: Record<string, string[] | null> = {
  AKTIF: ['OPEN', 'PARTIAL'],
  OPEN: ['OPEN'],
  PARTIAL: ['PARTIAL'],
  SEMUA: null,
}
const chips = [
  { key: 'AKTIF', label: 'Aktif' },
  { key: 'OPEN', label: 'Dibuka' },
  { key: 'PARTIAL', label: 'Sebagian' },
  { key: 'SEMUA', label: 'Semua' },
] as const

const pasarList = computed(() => {
  const allow = FILTERS[app.filter]
  return app.demands.filter((d) => !allow || allow.includes(d.status))
})
// Varian "sorot": tonjolkan permintaan bernilai terbesar, sisanya di grid.
const sorot = computed(() => {
  const list = pasarList.value
  if (!list.length) return null
  const raw = list.reduce((a, b) => (a.total * a.harga >= b.total * b.harga ? a : b))
  return decorateDemand(raw)
})
const gridList = computed(() => pasarList.value.filter((d) => d.id !== sorot.value?.id))
</script>

<template>
  <div>
    <!-- HERO -->
    <section class="border-b border-sand-400 bg-sand-150">
      <div class="mx-auto max-w-page px-5 pb-[50px] pt-[46px]">
        <div class="mb-3.5 inline-block rounded-full border border-sand-350 bg-white px-3.5 py-1 text-[11.5px] font-bold uppercase tracking-[0.05em] text-clay-700">
          Pasar Gotong Royong Desa
        </div>
        <h1 class="mb-2.5 max-w-[640px] text-[clamp(26px,4.5vw,36px)] font-extrabold leading-[1.15]">
          Dari desa, untuk meja makan Indonesia
        </h1>
        <p class="mb-6 max-w-[560px] text-[15.5px] leading-relaxed text-sand-700">
          Pembeli memposting kebutuhan, warga menyanggupi dari hasil panen, dan koperasi desa
          menjadi penghubungnya. Silakan lihat-lihat dulu — daftar hanya saat ingin bertransaksi.
        </p>
        <div class="flex flex-wrap gap-2.5">
          <button class="btn-primary px-[22px] py-3" @click="app.ctaBuat()">+ Buat Permintaan</button>
          <NuxtLink to="/etalase" class="btn-outline px-[22px] py-3">Lihat Etalase</NuxtLink>
        </div>
      </div>
    </section>

    <div class="mx-auto max-w-page px-5 pb-16 pt-7">
      <!-- header + filter chips -->
      <div class="mb-[18px] flex flex-wrap items-end justify-between gap-3">
        <div>
          <h2 class="mb-1 text-[22px] font-extrabold">Permintaan Pembeli</h2>
          <p class="text-[13.5px] text-sand-700">Kebutuhan yang bisa disanggupi warga lewat koperasi desa.</p>
        </div>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="c in chips"
            :key="c.key"
            class="chip cursor-pointer border-[1.5px] px-4 py-[7px] text-[12.5px] transition"
            :class="app.filter === c.key
              ? 'border-sand-800 bg-sand-800 text-white'
              : 'border-sand-350 bg-white text-sand-700 hover:border-clay-600 hover:text-clay-600'"
            @click="app.filter = c.key"
          >
            {{ c.label }}
          </button>
        </div>
      </div>

      <!-- sorotan -->
      <NuxtLink
        v-if="sorot"
        :to="`/permintaan/${sorot.id}`"
        class="mb-[18px] flex cursor-pointer flex-wrap items-center gap-6 rounded-2xl border border-sand-400 border-l-[5px] border-l-clay-600 bg-white p-[26px] transition hover:shadow-[0_10px_26px_rgba(80,10,12,0.10)]"
      >
        <div class="min-w-[260px] flex-[1.6]">
          <div class="eyebrow mb-2 text-clay-600">Permintaan Sorotan</div>
          <div class="mb-2 flex flex-wrap items-center gap-2.5">
            <div class="text-2xl font-extrabold leading-tight">{{ sorot.item_name }}</div>
            <StatusBadge :badge="sorot.badge" />
          </div>
          <div class="mb-3.5 text-sm text-sand-700">{{ sorot.qtyLine }} · tenggat {{ sorot.tglTxt }} ({{ sorot.hariTxt }})</div>
          <div class="flex flex-wrap gap-[22px]">
            <div>
              <div class="text-[11px] font-semibold text-sand-600">Harga target</div>
              <div class="text-[19px] font-extrabold text-clay-600">{{ sorot.hargaTxt }}<span class="text-xs font-semibold text-sand-600"> /{{ sorot.satuan }}</span></div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-sand-600">Nilai total</div>
              <div class="text-[19px] font-extrabold">{{ sorot.totalTxt }}</div>
            </div>
          </div>
        </div>
        <div class="min-w-[220px] flex-1">
          <div class="mb-1.5 flex justify-between text-[12.5px] text-sand-700">
            <span class="font-bold text-sand-900">{{ sorot.pct }}% tersanggupi</span>
            <span>{{ sorot.hariTxt }}</span>
          </div>
          <ProgressBar :pct="sorot.pct" height="h-3" class="mb-4" />
          <span class="btn-primary btn-block py-3">Lihat &amp; Sanggupi</span>
        </div>
      </NuxtLink>

      <!-- grid -->
      <div v-if="gridList.length" class="grid gap-4" style="grid-template-columns: repeat(auto-fill, minmax(268px, 1fr))">
        <DemandCard v-for="d in gridList" :key="d.id" :d="d" />
      </div>

      <!-- empty -->
      <div v-if="!pasarList.length" class="card rounded-2xl border-[1.5px] border-dashed border-sand-350 px-6 py-12 text-center">
        <div class="mb-1.5 text-[17px] font-extrabold">Belum ada permintaan pada filter ini</div>
        <p class="mb-[18px] text-[13.5px] text-sand-700">Jadilah pembeli pertama yang memposting kebutuhan komoditas desa.</p>
        <button class="btn-primary px-5 py-2.5" @click="app.ctaBuat()">+ Buat Permintaan</button>
      </div>
    </div>
  </div>
</template>
