<script setup lang="ts">
import { useApp } from '~/stores/app'
const app = useApp()

// Hanya untuk user login non-admin. Segarkan status terbaru saat dibuka.
onMounted(() => {
  if (!app.isLoggedIn) return navigateTo('/masuk')
  if (app.isAdmin) return navigateTo('/verifikasi')
  app.refreshMe()
})

const status = computed(() => app.user?.ver || 'UNVERIFIED')
// Form hanya bisa diisi bila BELUM pernah mengajukan (UNVERIFIED).
const canSubmit = computed(() => status.value === 'UNVERIFIED')

const tone = computed(() =>
  status.value === 'VERIFIED'
    ? { icon: '✅', box: 'border-success-200 bg-success-50', text: 'text-success-700', title: 'Identitas terverifikasi', sub: 'Akunmu sudah tepercaya — bisa bertransaksi penuh.' }
    : status.value === 'PENDING'
      ? { icon: '⏳', box: 'border-warning-200 bg-warning-50', text: 'text-warning-800', title: 'Menunggu tinjauan koperasi', sub: 'Pengajuanmu sudah dikirim & sedang ditinjau admin. Mohon tunggu.' }
      : status.value === 'REJECTED'
        ? { icon: '❌', box: 'border-rose-200 bg-rose-50', text: 'text-rose-700', title: 'Pengajuan ditolak', sub: 'Pengajuanmu ditolak koperasi. Silakan hubungi admin.' }
        : { icon: '🪪', box: 'border-clay-200 bg-clay-50', text: 'text-clay-700', title: 'Belum terverifikasi', sub: 'Kirim pengajuan di bawah agar bisa bertransaksi.' },
)

const ktpUploaded = computed(() => !!app.kycForm.ktpFile)
function onFile(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (file) app.uploadKtp(file)
}
</script>

<template>
  <div class="mx-auto max-w-[560px] px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Verifikasi Identitas</h2>
    <p class="mb-5 text-[13.5px] text-sand-700">
      Verifikasi KTP wajib sebelum bisa bertransaksi (membuat permintaan, menyanggupi, memesan, menitipkan). Koperasi (KDMP) meninjau pengajuanmu.
    </p>

    <div class="mb-5 flex items-center gap-3 rounded-xl border p-4" :class="tone.box">
      <span class="text-2xl">{{ tone.icon }}</span>
      <div>
        <div class="text-[14px] font-extrabold" :class="tone.text">{{ tone.title }}</div>
        <div class="text-[12.5px] text-sand-700">{{ tone.sub }}</div>
      </div>
    </div>

    <!-- form pengajuan (hanya bila belum pernah mengajukan) -->
    <section v-if="canSubmit" class="card p-6">
      <div class="mb-3.5">
        <label class="field-label">NIK (16 digit)</label>
        <input v-model="app.kycForm.nik" inputmode="numeric" maxlength="16" placeholder="3402xxxxxxxxxxxx" class="field-input font-mono" />
      </div>
      <div class="mb-4">
        <label class="field-label">Foto KTP (JPG / JPEG / PNG)</label>
        <input
          type="file"
          accept="image/png,image/jpeg,.jpg,.jpeg,.png"
          class="block w-full cursor-pointer rounded-xl border-[1.5px] border-sand-300 text-[13px] text-sand-700 file:mr-3 file:cursor-pointer file:border-0 file:bg-clay-600 file:px-4 file:py-2.5 file:text-[12.5px] file:font-bold file:text-white hover:file:bg-clay-700"
          @change="onFile"
        />
        <p v-if="ktpUploaded" class="mt-1.5 text-[12px] font-bold text-success-700">✓ Foto KTP terunggah</p>
        <p v-else class="mt-1.5 text-[11.5px] text-sand-600">Maks 5 MB. Pastikan foto jelas & terbaca.</p>
      </div>

      <div v-if="app.kycErr" class="mb-3 text-[12.5px] font-bold text-clay-800">{{ app.kycErr }}</div>

      <button class="btn-primary btn-block py-3.5" :disabled="app.busy" @click="app.submitKyc()">
        {{ app.busy ? 'Mengirim…' : 'Kirim Pengajuan Verifikasi' }}
      </button>
    </section>

    <div v-else class="card p-6 text-center text-[13px] text-sand-700">
      {{ status === 'PENDING' ? 'Pengajuanmu sedang ditinjau — kamu tak perlu mengirim ulang.' : 'Status verifikasimu sudah final. Kamu tak perlu mengirim pengajuan lagi.' }}
    </div>
  </div>
</template>
