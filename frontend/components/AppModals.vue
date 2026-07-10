<script setup lang="ts">
import { useApp } from '~/stores/app'
import { decorateDemand, decorateListing } from '~/utils/decorate'

const app = useApp()
const route = useRoute()

// Konteks modal diturunkan dari halaman detail yang sedang dibuka.
const demand = computed(() => {
  const d = app.demands.find((x) => x.id === route.params.id) || app.demands[0]
  return decorateDemand(d)
})
const listing = computed(() => {
  const l = app.listings.find((x) => x.id === route.params.id) || app.listings[0]
  return decorateListing(l)
})

const authMsg = computed(() => {
  const p = app.pending
  if (p?.type === 'pledge') return 'Untuk menyanggupi permintaan, masuk sebagai Warga Desa.'
  if (p?.type === 'order') return 'Untuk memesan komoditas, masuk sebagai Pembeli.'
  if (p?.type === 'buat') return 'Untuk membuat permintaan, masuk sebagai Pembeli.'
  return 'Untuk melanjutkan aksi ini, silakan masuk.'
})

const orderSubtotal = computed(() => fmtRp((parseFloat(app.orderQty) || 0) * listing.value.harga))

function goMasuk(tab: 'masuk' | 'daftar') {
  app.tab = tab
  app.modal = null
  navigateTo('/masuk')
}
</script>

<template>
  <Teleport to="body">
    <!-- Backdrop -->
    <div
      v-if="app.modal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-[rgba(43,26,23,0.5)] p-5"
      @click.self="app.closeModal()"
    >
      <!-- Modal: auth-gate -->
      <div v-if="app.modal === 'auth'" class="w-full max-w-[380px] animate-fadeUp rounded-2xl bg-white p-[26px]">
        <div class="mb-1.5 text-lg font-extrabold">Masuk dulu, ya</div>
        <p class="mb-[18px] text-[13.5px] leading-relaxed text-sand-700">
          {{ authMsg }} Setelah masuk, aksimu dilanjutkan otomatis.
        </p>
        <div class="flex flex-col gap-2">
          <button class="btn-primary btn-block" @click="goMasuk('masuk')">Masuk</button>
          <button class="btn-soft btn-block" @click="goMasuk('daftar')">Daftar Baru</button>
          <button class="btn-ghost btn-block" @click="app.closeModal()">Batal</button>
        </div>
      </div>

      <!-- Modal: sanggupi (pledge) -->
      <div v-else-if="app.modal === 'pledge'" class="w-full max-w-[400px] animate-fadeUp rounded-2xl bg-white p-[26px]">
        <div class="mb-1 text-lg font-extrabold">Sanggupi — {{ demand.item_name }}</div>
        <p class="mb-4 text-[13px] text-sand-700">
          Sisa kebutuhan: <strong>{{ demand.sisaKuotaTxt }}</strong>
        </p>
        <div class="mb-3">
          <label class="field-label">Jumlah yang disanggupi ({{ demand.satuan }})</label>
          <input v-model="app.pledgeQty" type="number" placeholder="0" class="field-input" />
        </div>
        <div class="mb-3.5">
          <label class="field-label">
            Harga penawaran / {{ demand.satuan }}
            <span class="font-medium text-sand-600">(ops. — default harga target)</span>
          </label>
          <input v-model="app.pledgePrice" type="number" :placeholder="demand.hargaNum" class="field-input" />
        </div>
        <div v-if="app.modErr" class="mb-3 rounded-[9px] bg-clay-100 px-3 py-2.5 text-[12.5px] font-bold text-clay-800">
          {{ app.modErr }}
        </div>
        <div class="flex gap-2.5">
          <button class="btn-primary flex-1" @click="app.submitPledge(demand.id)">Kirim Kesanggupan</button>
          <button class="btn-ghost" @click="app.closeModal()">Batal</button>
        </div>
      </div>

      <!-- Modal: pesan (order) -->
      <div v-else-if="app.modal === 'order'" class="w-full max-w-[400px] animate-fadeUp rounded-2xl bg-white p-[26px]">
        <div class="mb-1 text-lg font-extrabold">Pesan — {{ listing.item_name }}</div>
        <p class="mb-4 text-[13px] text-sand-700">
          Stok tersedia: <strong>{{ listing.stokTxt }}</strong> · {{ listing.hargaTxt }}/{{ listing.satuan }}
        </p>
        <div class="mb-3.5">
          <label class="field-label">Jumlah pesanan ({{ listing.satuan }})</label>
          <input v-model="app.orderQty" type="number" placeholder="0" class="field-input" />
        </div>
        <div class="mb-3.5 flex justify-between rounded-[10px] bg-sand-100 px-3.5 py-[11px] text-[13.5px]">
          <span class="text-sand-700">Perkiraan subtotal</span>
          <span class="font-extrabold text-clay-600">{{ orderSubtotal }}</span>
        </div>
        <div v-if="app.modErr" class="mb-3 rounded-[9px] bg-clay-100 px-3 py-2.5 text-[12.5px] font-bold text-clay-800">
          {{ app.modErr }}
        </div>
        <div class="flex gap-2.5">
          <button class="btn-primary flex-1" @click="app.submitOrder(listing.id)">Buat Pesanan</button>
          <button class="btn-ghost" @click="app.closeModal()">Batal</button>
        </div>
      </div>
    </div>
  </Teleport>
</template>
