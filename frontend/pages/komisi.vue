<script setup lang="ts">
import { useApp } from '~/stores/app'
import { fmtRp } from '~/composables/useFormat'
const app = useApp()
onMounted(() => {
  if (!app.isAdmin) navigateTo('/')
})

// nilai lokal yang di-edit; baru dipakai global saat "Simpan"
const draft = ref(app.commissionPct)
watch(() => app.commissionPct, (v) => (draft.value = v))
const preset = [3, 5, 7.5, 10]

const totGross = computed(() => app.txns.reduce((a, t) => a + t.gross, 0))
const rate = computed(() => Math.min(100, Math.max(0, Number(draft.value) || 0)) / 100)
const previewFee = computed(() => totGross.value * rate.value)
const netToWarga = computed(() => totGross.value - previewFee.value)
const changed = computed(() => Number(draft.value) !== app.commissionPct)

async function simpan() {
  await app.saveCommission(Number(draft.value))
  draft.value = app.commissionPct
}
</script>

<template>
  <div class="mx-auto max-w-page px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Pengaturan Komisi</h2>
    <p class="mb-5 text-[13.5px] text-sand-700">
      Atur persentase komisi koperasi yang dipotong otomatis dari <b>setiap transaksi</b> (alur permintaan &amp; etalase).
      Nilai ini disepakati koperasi bersama anggota — sisanya (net) menjadi hak warga penjual.
    </p>

    <div class="flex flex-wrap gap-4">
      <!-- kartu pengaturan -->
      <section class="card min-w-[300px] flex-[1.2] p-[22px]">
        <h3 class="mb-4 text-[15.5px] font-extrabold">Persentase Komisi per Transaksi</h3>

        <div class="mb-4 flex items-end gap-3">
          <div class="flex items-center gap-2 rounded-xl border-[1.5px] border-sand-300 px-3.5 py-2.5 focus-within:border-clay-600">
            <input
              v-model.number="draft"
              type="number"
              min="0"
              max="100"
              step="0.5"
              class="w-24 border-none bg-transparent text-[28px] font-extrabold text-clay-600 outline-none"
            />
            <span class="text-[22px] font-extrabold text-sand-500">%</span>
          </div>
          <div class="pb-1.5 text-[12.5px] text-sand-600">
            Saat ini aktif: <b class="text-sand-800">{{ app.commissionPct }}%</b>
          </div>
        </div>

        <!-- preset cepat -->
        <div class="mb-5">
          <div class="mb-2 text-[11px] font-bold uppercase tracking-wide text-sand-600">Pilihan cepat</div>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="p in preset"
              :key="p"
              class="cursor-pointer rounded-full border px-4 py-1.5 text-[12.5px] font-bold transition"
              :class="Number(draft) === p
                ? 'border-clay-600 bg-clay-600 text-white'
                : 'border-sand-350 bg-white text-sand-700 hover:border-clay-600 hover:text-clay-600'"
              @click="draft = p"
            >{{ p }}%</button>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <button
            class="btn-primary px-5 py-2.5 text-[13.5px]"
            :disabled="!changed || app.busy"
            :class="{ 'cursor-not-allowed opacity-50': !changed || app.busy }"
            @click="simpan"
          >{{ app.busy ? 'Menyimpan…' : 'Simpan Pengaturan' }}</button>
          <button
            v-if="changed"
            class="cursor-pointer text-[12.5px] font-bold text-sand-600 hover:text-sand-800"
            @click="draft = app.commissionPct"
          >Batalkan perubahan</button>
        </div>
      </section>

      <!-- kartu simulasi -->
      <section class="card min-w-[260px] flex-1 p-[22px]">
        <h3 class="mb-1 text-[15.5px] font-extrabold">Simulasi Dampak</h3>
        <p class="mb-4 text-[12px] text-sand-600">Berdasarkan total nilai transaksi berjalan.</p>

        <div class="flex justify-between border-b border-dashed border-sand-400 py-2.5 text-[13.5px]">
          <span class="text-sand-700">Total nilai transaksi</span>
          <span class="font-bold">{{ fmtRp(totGross) }}</span>
        </div>
        <div class="flex justify-between border-b border-dashed border-sand-400 py-2.5 text-[13.5px]">
          <span class="text-sand-700">Komisi koperasi ({{ Number(draft) || 0 }}%)</span>
          <span class="font-extrabold text-clay-600">{{ fmtRp(previewFee) }}</span>
        </div>
        <div class="flex justify-between py-2.5 text-[13.5px]">
          <span class="text-sand-700">Net untuk warga</span>
          <span class="font-bold text-success-700">{{ fmtRp(netToWarga) }}</span>
        </div>
      </section>
    </div>
  </div>
</template>
