<script setup lang="ts">
import { useApp } from '~/stores/app'
const app = useApp()

// Guard: hanya pembeli yang boleh membuat permintaan.
onMounted(() => {
  if (!app.isBuyer) navigateTo('/')
})

const total = computed(() => (parseFloat(app.buat.qty) || 0) * (parseFloat(app.buat.harga) || 0))
const draft = computed(() => app.demands.find((d) => d.id === app.buatDraft))
const draftDp = computed(() => (draft.value ? fmtRp(draft.value.total * draft.value.harga * 0.3) : ''))
</script>

<template>
  <div class="mx-auto max-w-[900px] px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Buat Permintaan</h2>
    <p class="mb-[18px] text-[13.5px] text-sand-700">Posting kebutuhan komoditasmu — warga desa akan menyanggupinya.</p>

    <div class="mb-5 flex items-center gap-2 text-[12.5px] font-bold">
      <span class="rounded-full px-3.5 py-[5px]" :class="app.buatStep === 1 ? 'bg-clay-600 text-white' : 'bg-sand-200 text-sand-600'">1 · Rincian</span>
      <span class="text-sand-500">—</span>
      <span class="rounded-full px-3.5 py-[5px]" :class="app.buatStep === 2 ? 'bg-clay-600 text-white' : 'bg-sand-200 text-sand-600'">2 · Uang Muka</span>
    </div>

    <!-- STEP 1 -->
    <div v-if="app.buatStep === 1" class="grid items-start gap-[18px] lg:grid-cols-[1.6fr_1fr]">
      <div class="card min-w-0 p-6">
        <div class="mb-3">
          <label class="field-label">Nama komoditas</label>
          <input v-model="app.buat.item" type="text" placeholder="cth. Beras Medium IR64" class="field-input" />
        </div>
        <div class="mb-3 flex flex-wrap gap-2.5">
          <div class="min-w-[110px] flex-1">
            <label class="field-label">Jumlah</label>
            <input v-model="app.buat.qty" type="number" placeholder="500" class="field-input" />
          </div>
          <div class="min-w-[110px] flex-1">
            <label class="field-label">Satuan</label>
            <input v-model="app.buat.satuan" type="text" placeholder="kg" class="field-input" />
          </div>
        </div>
        <div class="mb-4 flex flex-wrap gap-2.5">
          <div class="min-w-[140px] flex-[1.3]">
            <label class="field-label">Harga target / satuan (Rp)</label>
            <input v-model="app.buat.harga" type="number" placeholder="12500" class="field-input" />
          </div>
          <div class="min-w-[140px] flex-1">
            <label class="field-label">Tenggat <span class="font-medium text-sand-600">(ops.)</span></label>
            <input v-model="app.buat.deadline" type="date" class="field-input" />
          </div>
        </div>
        <div v-if="app.buatErr" class="mb-3 text-[12.5px] font-bold text-clay-800">{{ app.buatErr }}</div>
        <button class="btn-primary px-6 py-3" @click="app.submitBuat()">Lanjut ke Uang Muka →</button>
      </div>

      <div class="card min-w-0 p-6">
        <h3 class="mb-3.5 text-[15px] font-extrabold">Perkiraan Biaya</h3>
        <div class="flex justify-between border-b border-dashed border-sand-400 py-2 text-[13.5px]"><span class="text-sand-700">Nilai total</span><span class="font-bold">{{ fmtRp(total) }}</span></div>
        <div class="flex justify-between border-b border-dashed border-sand-400 py-2 text-[13.5px]"><span class="text-sand-700">Uang muka (30%)</span><span class="font-extrabold text-clay-600">{{ fmtRp(total * 0.3) }}</span></div>
        <div class="flex justify-between py-2 text-[13.5px]"><span class="text-sand-700">Sisa pembayaran</span><span class="font-bold">{{ fmtRp(total * 0.7) }}</span></div>
        <p class="mt-3.5 text-[11.5px] leading-relaxed text-sand-600">Perkiraan lokal — angka final dihitung oleh server saat permintaan dibuat.</p>
      </div>
    </div>

    <!-- STEP 2 -->
    <div v-else class="card max-w-[560px] p-[26px]">
      <h3 class="mb-1 text-[17px] font-extrabold">Bayar Uang Muka</h3>
      <p class="mb-4 text-[13px] leading-relaxed text-sand-700">
        Permintaan <strong>{{ draft?.item_name }}</strong> tersimpan sebagai <strong>Draf</strong>.
        Bayar DP <strong class="text-clay-600">{{ draftDp }}</strong> agar tampil publik di Jelajah Pasar.
      </p>
      <div class="mb-4 flex flex-wrap gap-2.5">
        <button
          class="min-w-[150px] flex-1 cursor-pointer rounded-xl border-2 p-3.5 text-left transition"
          :class="app.dpMethod === 'TRANSFER' ? 'border-clay-600 bg-clay-50' : 'border-sand-350 bg-white'"
          @click="app.dpMethod = 'TRANSFER'"
        >
          <div class="mb-0.5 text-sm font-extrabold">Transfer / Online</div>
          <div class="text-xs text-sand-700">Bayar aman via Mayar</div>
        </button>
        <button
          class="min-w-[150px] flex-1 cursor-pointer rounded-xl border-2 p-3.5 text-left transition"
          :class="app.dpMethod === 'CASH' ? 'border-clay-600 bg-clay-50' : 'border-sand-350 bg-white'"
          @click="app.dpMethod = 'CASH'"
        >
          <div class="mb-0.5 text-sm font-extrabold">Tunai</div>
          <div class="text-xs text-sand-700">Setor langsung di koperasi</div>
        </button>
      </div>
      <p class="mb-4 text-xs leading-relaxed text-sand-600">
        {{ app.dpMethod === 'TRANSFER'
          ? 'Kamu akan diarahkan ke halaman pembayaran Mayar. Status DP terupdate otomatis setelah lunas.'
          : 'Pembayaran tunai dilakukan di luar aplikasi. Konfirmasi di bawah setelah DP disetor.' }}
      </p>
      <div class="flex flex-wrap gap-2.5">
        <button
          class="btn-primary min-w-[170px] flex-1 py-3.5"
          :disabled="app.busy"
          @click="app.dpMethod === 'TRANSFER' ? app.payDpMayar() : app.confirmDp()"
        >{{ app.busy ? 'Memproses…' : (app.dpMethod === 'TRANSFER' ? 'Bayar via Mayar' : 'Konfirmasi DP Terbayar') }}</button>
        <button class="btn-soft px-[18px] py-3.5 text-[13.5px]" @click="app.saveDraft()">Nanti saja</button>
      </div>
    </div>
  </div>
</template>
