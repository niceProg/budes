<script setup lang="ts">
import { useApp } from '~/stores/app'
import { decorateListing } from '~/utils/decorate'
const app = useApp()
// Warga hanya melihat titipan miliknya sendiri; peran lain melihat semua etalase.
const rows = computed(() => {
  const src = app.isWarga ? app.listings.filter((l) => l.owner === app.user?.id) : app.listings
  return src.map(decorateListing)
})
</script>

<template>
  <div class="mx-auto max-w-page px-5 pb-16 pt-7">
    <div class="mb-[18px]">
      <h2 class="mb-1 text-[22px] font-extrabold">{{ app.isWarga ? 'Titipan Saya' : 'Etalase Koperasi' }}</h2>
      <p class="text-[13.5px] text-sand-700">
        {{ app.isWarga ? 'Komoditas yang Anda titipkan ke koperasi desa.' : 'Komoditas titipan warga yang siap dipesan langsung.' }}
      </p>
    </div>

    <div class="card overflow-x-auto p-0">
      <table class="w-full min-w-[720px] border-collapse text-[13.5px]">
        <thead>
          <tr class="border-b border-sand-300 text-left text-[11.5px] uppercase tracking-wide text-sand-600">
            <th class="px-4 py-3 font-bold">No</th>
            <th class="px-4 py-3 font-bold">Komoditas</th>
            <th class="px-4 py-3 font-bold">Penitip</th>
            <th class="px-4 py-3 text-right font-bold">Harga</th>
            <th class="px-4 py-3 font-bold">Status</th>
            <th class="px-4 py-3 text-center font-bold">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(l, i) in rows"
            :key="l.id"
            class="border-b border-sand-200 transition last:border-0 hover:bg-sand-100/60"
          >
            <td class="px-4 py-3 text-sand-600">{{ i + 1 }}</td>
            <td class="px-4 py-3 font-bold">{{ l.item_name }}</td>
            <td class="px-4 py-3 text-sand-700">{{ l.seller }}</td>
            <td class="px-4 py-3 text-right font-bold text-clay-600">{{ l.hargaTxt }}<span class="text-[11px] font-semibold text-sand-600"> /{{ l.satuan }}</span></td>
            <td class="px-4 py-3"><StatusBadge :badge="l.badge" /></td>
            <td class="px-4 py-3">
              <div class="flex items-center justify-center gap-1.5">
                <button class="act act-view" @click="app.viewListing(l.id)">Lihat</button>
              </div>
            </td>
          </tr>
          <tr v-if="!rows.length">
            <td colspan="6" class="px-4 py-12 text-center text-[13.5px] text-sand-600">
              Belum ada komoditas di etalase.
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
.act-edit {
  @apply bg-clay-100 text-clay-700 hover:bg-clay-200;
}
.act-del {
  @apply bg-rose-100 text-rose-700 hover:bg-rose-200;
}
</style>
