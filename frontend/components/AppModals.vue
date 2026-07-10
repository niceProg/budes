<script setup lang="ts">
import { useApp } from '~/stores/app'
import { decorateDemand, decorateListing } from '~/utils/decorate'
import { KYC_BADGE, badge } from '~/utils/badges'

const app = useApp()
const route = useRoute()

// Konteks modal diturunkan dari halaman detail yang sedang dibuka.
const demand = computed(() => {
  const d = app.demands.find((x) => x.id === route.params.id) || app.demands[0]
  return decorateDemand(d)
})
const listing = computed(() => {
  const l = app.listings.find((x) => x.id === (app.activeListingId || route.params.id)) || app.listings[0]
  return decorateListing(l)
})
const kyc = computed(() => app.kyc.find((k) => k.id === app.activeKycId) || null)
const ktpValid = computed(() => /\.(jpe?g|png)$/i.test(kyc.value?.ktpFile || ''))
const showNik = ref(false)
const nikDisplay = computed(() => {
  const n = kyc.value?.nik || ''
  if (showNik.value) return n
  return n.length > 6 ? `${n.slice(0, 4)}**********${n.slice(-2)}` : n
})
// Reset ke tersembunyi setiap kali membuka berkas anggota lain
watch(() => app.activeKycId, () => (showNik.value = false))
const kycBadge = computed(() => badge(KYC_BADGE, kyc.value?.status || 'PENDING'))

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

      <!-- Modal: lihat detail listing (popup) -->
      <div v-else-if="app.modal === 'listingView'" class="w-full max-w-[460px] animate-fadeUp rounded-2xl bg-white p-[26px]">
        <div class="mb-4 flex flex-wrap items-center gap-2.5">
          <div class="text-lg font-extrabold leading-tight">{{ listing.item_name }}</div>
          <StatusBadge :badge="listing.badge" />
        </div>
        <div class="grid grid-cols-2 gap-2.5">
          <div class="rounded-xl border border-sand-200 p-3">
            <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Stok tersedia</div>
            <div class="text-[15px] font-extrabold">{{ listing.stokTxt }}</div>
          </div>
          <div class="rounded-xl border border-sand-200 p-3">
            <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Sudah terjual</div>
            <div class="text-[15px] font-extrabold">{{ listing.terjualTxt }}</div>
          </div>
          <div class="rounded-xl border border-sand-200 p-3">
            <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Harga / {{ listing.satuan }}</div>
            <div class="text-[15px] font-extrabold text-clay-600">{{ listing.hargaTxt }}</div>
          </div>
          <div class="rounded-xl border border-sand-200 p-3">
            <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Penitip</div>
            <div class="text-[15px] font-extrabold">{{ listing.seller }}</div>
          </div>
          <div class="col-span-2 rounded-xl border border-sand-200 p-3">
            <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Masuk etalase</div>
            <div class="text-[15px] font-extrabold">{{ listing.tglTxt }}</div>
          </div>
        </div>
        <div class="mt-4 flex gap-2.5">
          <button v-if="app.isBuyer || !app.isLoggedIn" class="btn-primary flex-1" @click="app.ctaOrder(listing.id)">Pesan Sekarang</button>
          <button class="btn-ghost" :class="{ 'flex-1': !(app.isBuyer || !app.isLoggedIn) }" @click="app.closeModal()">Tutup</button>
        </div>
      </div>

      <!-- Modal: edit listing -->
      <div v-else-if="app.modal === 'listingEdit'" class="w-full max-w-[400px] animate-fadeUp rounded-2xl bg-white p-[26px]">
        <div class="mb-1 text-lg font-extrabold">Edit — {{ listing.item_name }}</div>
        <p class="mb-4 text-[13px] text-sand-700">Perbarui stok, harga, atau status listing.</p>
        <div class="mb-3">
          <label class="field-label">Stok tersedia ({{ listing.satuan }})</label>
          <input v-model="app.listEdit.avail" type="number" class="field-input" />
        </div>
        <div class="mb-3">
          <label class="field-label">Harga / {{ listing.satuan }}</label>
          <input v-model="app.listEdit.harga" type="number" class="field-input" />
        </div>
        <div class="mb-3.5">
          <label class="field-label">Status</label>
          <select v-model="app.listEdit.status" class="field-input">
            <option value="ACTIVE">Aktif</option>
            <option value="INACTIVE">Nonaktif</option>
          </select>
        </div>
        <div v-if="app.listEditErr" class="mb-3 rounded-[9px] bg-clay-100 px-3 py-2.5 text-[12.5px] font-bold text-clay-800">
          {{ app.listEditErr }}
        </div>
        <div class="flex gap-2.5">
          <button class="btn-primary flex-1" @click="app.saveListing()">Simpan Perubahan</button>
          <button class="btn-ghost" @click="app.closeModal()">Batal</button>
        </div>
      </div>

      <!-- Modal: lihat berkas verifikasi (KYC) -->
      <div v-else-if="app.modal === 'kycView' && kyc" class="w-full max-w-[440px] animate-fadeUp rounded-2xl bg-white p-[26px]">
        <div class="mb-1 flex items-center gap-2.5">
          <span class="text-lg font-extrabold">Berkas Verifikasi</span>
          <StatusBadge :badge="kycBadge" />
        </div>
        <p class="mb-4 text-[13px] text-sand-700">Tinjau identitas anggota — status bisa diubah kapan saja.</p>

        <div class="mb-2.5 rounded-xl border border-sand-200 p-3.5">
          <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Nama</div>
          <div class="text-[15px] font-extrabold">{{ kyc.name }}</div>
        </div>
        <div class="mb-2.5 rounded-xl border border-sand-200 p-3.5">
          <div class="mb-1 flex items-center justify-between">
            <span class="text-[11px] font-semibold text-sand-600">NIK</span>
            <button class="cursor-pointer text-[11px] font-bold text-clay-600 transition hover:underline" @click="showNik = !showNik">
              {{ showNik ? '🙈 Sembunyikan' : '👁️ Tampilkan' }}
            </button>
          </div>
          <div class="font-mono text-[15px] font-bold tracking-wide">{{ nikDisplay }}</div>
        </div>

        <!-- Foto KTP (wajib JPG / PNG / JPEG) -->
        <div class="mb-4">
          <div class="mb-1.5 flex items-center justify-between">
            <span class="text-[11px] font-semibold text-sand-600">Foto KTP</span>
            <span v-if="ktpValid" class="rounded-full bg-success-100 px-2 py-0.5 text-[10.5px] font-bold text-success-700">Format JPG/PNG/JPEG ✓</span>
            <span v-else class="rounded-full bg-rose-100 px-2 py-0.5 text-[10.5px] font-bold text-rose-700">Format tidak didukung</span>
          </div>
          <div class="flex flex-col items-center justify-center gap-1.5 rounded-xl border border-dashed border-sand-400 bg-sand-100 py-7 text-sand-600">
            <span class="text-4xl">🪪</span>
            <span class="text-[12.5px] font-bold text-sand-800">{{ kyc.ktpFile }}</span>
            <span class="text-[11px]">Pratinjau foto KTP anggota</span>
          </div>
        </div>

        <div class="flex gap-2.5">
          <button class="btn-success flex-1" @click="app.kycAct(kyc.id, true)">Verifikasi</button>
          <button
            class="cursor-pointer rounded-[10px] border-[1.5px] border-rose-200 bg-white px-4 font-bold text-rose-700 transition hover:bg-rose-50"
            @click="app.kycAct(kyc.id, false)"
          >Tolak</button>
          <button class="btn-ghost" @click="app.closeModal()">Tutup</button>
        </div>
      </div>
    </div>
  </Teleport>
</template>
