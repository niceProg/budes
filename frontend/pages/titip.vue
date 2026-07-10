<script setup lang="ts">
import { useApp } from '~/stores/app'
const app = useApp()

onMounted(() => {
  if (!app.isWarga) navigateTo('/')
})
</script>

<template>
  <div class="mx-auto max-w-[560px] px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Titipkan Komoditas</h2>
    <p class="mb-[18px] text-[13.5px] text-sand-700">Pajang hasil panenmu di etalase koperasi — pembeli memesan langsung.</p>

    <div class="card p-6">
      <div class="mb-3">
        <label class="field-label">Nama komoditas</label>
        <input v-model="app.titip.item" type="text" placeholder="cth. Madu Hutan Asli" class="field-input" />
      </div>
      <div class="mb-3 flex flex-wrap gap-2.5">
        <div class="min-w-[110px] flex-1">
          <label class="field-label">Stok tersedia</label>
          <input v-model="app.titip.qty" type="number" placeholder="100" class="field-input" />
        </div>
        <div class="min-w-[110px] flex-1">
          <label class="field-label">Satuan</label>
          <input v-model="app.titip.satuan" type="text" placeholder="kg" class="field-input" />
        </div>
      </div>
      <div class="mb-4">
        <label class="field-label">Harga / satuan (Rp)</label>
        <input v-model="app.titip.harga" type="number" placeholder="16000" class="field-input" />
      </div>
      <div v-if="app.titipErr" class="mb-3 text-[12.5px] font-bold text-clay-800">{{ app.titipErr }}</div>
      <button class="btn-primary btn-block py-3.5" @click="app.submitTitip()">Titipkan ke Etalase</button>
      <p class="mt-3.5 text-[11.5px] leading-relaxed text-sand-600">Koperasi mengambil komisi 5% dari setiap transaksi yang terjadi.</p>
    </div>
  </div>
</template>
