<script setup lang="ts">
import { useApp } from '~/stores/app'
const app = useApp()
onMounted(() => {
  if (!app.isAdmin) navigateTo('/')
})
</script>

<template>
  <div class="mx-auto max-w-page px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Pengaturan Harga Komoditas</h2>
    <p class="mb-5 text-[13.5px] text-sand-700">
      Batas harga mencegah permainan / mark-up: warga tak boleh menjual di atas <b>maks. harga jual</b>,
      pembeli tak boleh membeli di atas <b>maks. harga beli</b>.
    </p>

    <section class="card p-[22px]">
      <div class="mb-3.5 flex justify-end">
        <button class="btn-soft rounded-lg px-3 py-1.5 text-[12px]" @click="app.addPriceCap()">+ Tambah Komoditas</button>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] border-collapse text-[13px]">
          <thead>
            <tr class="border-b border-sand-300 text-left text-[11px] uppercase tracking-wide text-sand-600">
              <th class="px-2 py-2 font-bold">Komoditas</th>
              <th class="px-2 py-2 font-bold">Satuan</th>
              <th class="px-2 py-2 font-bold">Maks. Harga Jual (Warga)</th>
              <th class="px-2 py-2 font-bold">Maks. Harga Beli (Pembeli)</th>
              <th class="px-2 py-2"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in app.priceCaps" :key="c.id" class="border-b border-sand-150">
              <td class="px-2 py-2"><input v-model="c.komoditas" type="text" placeholder="Nama komoditas" class="field-input py-1.5" /></td>
              <td class="px-2 py-2"><input v-model="c.satuan" type="text" class="field-input w-20 py-1.5" /></td>
              <td class="px-2 py-2">
                <div class="flex items-center gap-1.5">
                  <span class="text-[12px] font-semibold text-sand-600">Rp</span>
                  <input v-model.number="c.maxJual" type="number" min="0" class="field-input py-1.5" />
                </div>
              </td>
              <td class="px-2 py-2">
                <div class="flex items-center gap-1.5">
                  <span class="text-[12px] font-semibold text-sand-600">Rp</span>
                  <input v-model.number="c.maxBeli" type="number" min="0" class="field-input py-1.5" />
                </div>
              </td>
              <td class="px-2 py-2 text-right">
                <button class="cursor-pointer rounded-lg bg-rose-100 px-2.5 py-1.5 text-[12px] font-bold text-rose-700 transition hover:bg-rose-200" @click="app.removePriceCap(c.id)">Hapus</button>
              </td>
            </tr>
            <tr v-if="!app.priceCaps.length">
              <td colspan="5" class="px-2 py-8 text-center text-sand-600">Belum ada batas harga. Tambah komoditas.</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-3.5 flex justify-end">
        <button class="btn-primary px-5 py-2 text-[13px]" @click="app.savePriceCaps()">Simpan Pengaturan</button>
      </div>
    </section>
  </div>
</template>
