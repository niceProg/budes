<script setup lang="ts">
import { useApp } from '~/stores/app'
import { decorateDemand } from '~/utils/decorate'
import { fmtN } from '~/composables/useFormat'
const app = useApp()
// Hanya tampilkan permintaan yang masih aktif dijual: Dibuka (OPEN) & Sebagian (PARTIAL).
// Draf, Terpenuhi, dan Dibatalkan tidak ditampilkan.
const rows = computed(() =>
  app.demands.filter((d) => d.status === 'OPEN' || d.status === 'PARTIAL').map(decorateDemand),
)
</script>

<template>
  <div class="mx-auto max-w-page px-5 pb-16 pt-7">
    <div class="mb-[18px]">
      <h2 class="mb-1 text-[22px] font-extrabold">Permintaan Pembeli</h2>
      <p class="text-[13.5px] text-sand-700">Semua kebutuhan yang diposting pembeli — pilih untuk menyanggupi.</p>
    </div>

    <div class="card overflow-x-auto p-0">
      <table class="w-full min-w-[760px] border-collapse text-[13.5px]">
        <thead>
          <tr class="border-b border-sand-300 text-left text-[11.5px] uppercase tracking-wide text-sand-600">
            <th class="px-4 py-3 font-bold">No</th>
            <th class="px-4 py-3 font-bold">Komoditas</th>
            <th class="px-4 py-3 text-right font-bold">Kebutuhan</th>
            <th class="px-4 py-3 text-right font-bold">Harga Target</th>
            <th class="px-4 py-3 text-right font-bold">Tersanggupi</th>
            <th class="px-4 py-3 font-bold">Status</th>
            <th class="px-4 py-3 text-center font-bold">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(d, i) in rows"
            :key="d.id"
            class="border-b border-sand-200 transition last:border-0 hover:bg-sand-100/60"
          >
            <td class="px-4 py-3 text-sand-600">{{ i + 1 }}</td>
            <td class="px-4 py-3 font-bold">{{ d.item_name }}</td>
            <td class="px-4 py-3 text-right">{{ fmtN(d.total) }} {{ d.satuan }}</td>
            <td class="px-4 py-3 text-right font-bold text-clay-600">{{ d.hargaTxt }}<span class="text-[11px] font-semibold text-sand-600"> /{{ d.satuan }}</span></td>
            <td class="px-4 py-3 text-right text-sand-700">{{ d.pct }}%</td>
            <td class="px-4 py-3"><StatusBadge :badge="d.badge" /></td>
            <td class="px-4 py-3">
              <div class="flex justify-center">
                <NuxtLink :to="`/permintaan/${d.id}`" class="act act-view">Lihat</NuxtLink>
              </div>
            </td>
          </tr>
          <tr v-if="!rows.length">
            <td colspan="7" class="px-4 py-12 text-center text-[13.5px] text-sand-600">
              Belum ada permintaan dari pembeli.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.act {
  @apply cursor-pointer rounded-lg px-2.5 py-1.5 text-[12px] font-bold transition;
}
.act-view {
  @apply bg-sand-150 text-sand-800 hover:bg-sand-200;
}
</style>
