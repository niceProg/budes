<script setup lang="ts">
import { useApp } from '~/stores/app'
const app = useApp()

// Hanya untuk user login non-admin (admin punya panel tinjauan sendiri).
onMounted(() => {
  if (!app.isLoggedIn) navigateTo('/masuk')
  else if (app.isAdmin) navigateTo('/verifikasi')
})

const verified = computed(() => app.user?.ver === 'VERIFIED')
</script>

<template>
  <div class="mx-auto max-w-[560px] px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Verifikasi Identitas</h2>
    <p class="mb-5 text-[13.5px] text-sand-700">
      Ajukan verifikasi KTP agar akunmu tepercaya di Bursa Desa. Koperasi (KDMP) akan meninjau pengajuanmu.
    </p>

    <!-- status saat ini -->
    <div
      class="mb-5 flex items-center gap-3 rounded-xl border p-4"
      :class="verified ? 'border-success-200 bg-success-50' : 'border-warning-200 bg-warning-50'"
    >
      <span class="text-2xl">{{ verified ? '✅' : '⏳' }}</span>
      <div>
        <div class="text-[14px] font-extrabold" :class="verified ? 'text-success-700' : 'text-warning-800'">
          {{ verified ? 'Identitas terverifikasi' : 'Belum terverifikasi' }}
        </div>
        <div class="text-[12.5px] text-sand-700">
          {{ verified ? 'Akunmu sudah tepercaya.' : 'Kirim pengajuan di bawah untuk ditinjau koperasi.' }}
        </div>
      </div>
    </div>

    <section class="card p-6">
      <div class="mb-3.5">
        <label class="field-label">NIK (16 digit)</label>
        <input
          v-model="app.kycForm.nik"
          inputmode="numeric"
          maxlength="16"
          placeholder="3402xxxxxxxxxxxx"
          class="field-input font-mono"
        />
      </div>
      <div class="mb-3.5">
        <label class="field-label">Berkas foto KTP (nama file)</label>
        <input v-model="app.kycForm.ktpFile" type="text" placeholder="ktp-nama.jpg" class="field-input" />
        <p class="mt-1 text-[11.5px] text-sand-600">Format JPG/PNG/JPEG. (Unggah berkas nyata menyusul.)</p>
      </div>
      <div class="mb-4">
        <label class="field-label">Dokumen pendukung (opsional)</label>
        <input v-model="app.kycForm.doc" type="text" placeholder="mis. Kartu Keluarga / Surat domisili" class="field-input" />
      </div>

      <div v-if="app.kycErr" class="mb-3 text-[12.5px] font-bold text-clay-800">{{ app.kycErr }}</div>

      <button
        class="btn-primary btn-block py-3.5"
        :disabled="app.busy"
        @click="app.submitKyc()"
      >{{ app.busy ? 'Mengirim…' : 'Kirim Pengajuan Verifikasi' }}</button>
    </section>
  </div>
</template>
