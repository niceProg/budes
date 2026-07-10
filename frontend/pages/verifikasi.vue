<script setup lang="ts">
import { useApp } from '~/stores/app'
import { KYC_BADGE, badge } from '~/utils/badges'
const app = useApp()
onMounted(() => {
  if (!app.isAdmin) navigateTo('/')
})

const filter = ref<'ALL' | 'WARGA' | 'BUYER'>('ALL')
const chips = [
  { key: 'ALL', label: 'Semua' },
  { key: 'WARGA', label: 'Warga' },
  { key: 'BUYER', label: 'Pembeli' },
] as const

const rows = computed(() =>
  app.kyc.filter((k) => filter.value === 'ALL' || k.role === filter.value),
)
const maskNik = (n: string) => (n.length > 6 ? `${n.slice(0, 4)}**********${n.slice(-2)}` : n)
</script>

<template>
  <div class="mx-auto max-w-page px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Verifikasi Identitas (KYC)</h2>
    <p class="mb-4 text-[13.5px] text-sand-700">Tinjau pengajuan verifikasi anggota — warga & pembeli.</p>

    <!-- filter peran -->
    <div class="mb-4 flex flex-wrap gap-2">
      <button
        v-for="c in chips"
        :key="c.key"
        class="cursor-pointer rounded-full border px-4 py-1.5 text-[12.5px] font-bold transition"
        :class="filter === c.key
          ? 'border-clay-600 bg-clay-600 text-white'
          : 'border-sand-350 bg-white text-sand-700 hover:border-clay-600 hover:text-clay-600'"
        @click="filter = c.key"
      >
        {{ c.label }}
      </button>
    </div>

    <section class="card p-[22px]">
      <div v-if="rows.length" class="overflow-x-auto">
        <table class="w-full min-w-[600px] border-collapse text-[13.5px]">
          <thead>
            <tr class="border-b border-sand-300 text-left text-[11px] uppercase tracking-wide text-sand-600">
              <th class="px-3 py-2.5 font-bold">No</th>
              <th class="px-3 py-2.5 font-bold">Nama</th>
              <th class="px-3 py-2.5 font-bold">Peran</th>
              <th class="px-3 py-2.5 font-bold">NIK</th>
              <th class="px-3 py-2.5 font-bold">Status</th>
              <th class="px-3 py-2.5 text-center font-bold">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(k, i) in rows" :key="k.id" class="border-b border-sand-150 hover:bg-sand-100/60">
              <td class="px-3 py-2.5 text-sand-600">{{ i + 1 }}</td>
              <td class="px-3 py-2.5 font-bold">{{ k.name }}</td>
              <td class="px-3 py-2.5">
                <span
                  class="rounded-full px-2.5 py-0.5 text-[11px] font-bold"
                  :class="k.role === 'WARGA' ? 'bg-success-100 text-success-700' : 'bg-grape-100 text-grape-700'"
                >{{ k.role === 'WARGA' ? 'Warga' : 'Pembeli' }}</span>
              </td>
              <td class="px-3 py-2.5 font-mono text-sand-700">{{ maskNik(k.nik) }}</td>
              <td class="px-3 py-2.5"><StatusBadge :badge="badge(KYC_BADGE, k.status)" /></td>
              <td class="px-3 py-2.5">
                <div class="flex justify-center">
                  <button
                    class="cursor-pointer rounded-lg bg-sand-150 px-3 py-1.5 text-[12px] font-bold text-sand-800 transition hover:bg-sand-200"
                    @click="app.viewKyc(k.id)"
                  >Lihat</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <p v-else class="text-[13px] text-sand-700">Tidak ada pengajuan pada filter ini.</p>
    </section>
  </div>
</template>
