<script setup lang="ts">
import { useApp, KOPERASI } from '~/stores/app'
const app = useApp()

// Opsi peran untuk kartu pilih peran (ikon = path SVG gaya lucide).
const roleOpts = [
  {
    value: 'BUYER',
    label: 'Pembeli',
    desc: 'Memasang kebutuhan & memesan',
    icon: '<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>'
  },
  {
    value: 'WARGA',
    label: 'Warga Desa',
    desc: 'Menyanggupi & menitipkan komoditas',
    icon: '<path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8z"/><path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.1-4 1-4.9 2z"/>'
  },
  {
    value: 'ADMIN_KOPERASI',
    label: 'Admin Koperasi',
    desc: 'Mengelola koperasi & transaksi',
    icon: '<path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01"/><path d="M9 12v.01"/><path d="M9 15v.01"/><path d="M9 18v.01"/>'
  }
]
</script>

<template>
  <div class="mx-auto max-w-[460px] px-5 pb-16 pt-9">
    <div v-if="app.pending" class="mb-4 rounded-xl border border-warning-100 bg-warning-50 px-4 py-3 text-[13px] font-semibold text-warning-700">
      Masuk dulu untuk melanjutkan — setelah masuk, aksimu dilanjutkan otomatis.
    </div>

    <div class="card mb-4 overflow-hidden">
      <div class="flex">
        <button
          class="flex-1 py-3.5 text-sm font-extrabold transition"
          :class="app.tab === 'masuk' ? 'bg-white text-gold-700' : 'bg-navy-100 text-navy-600'"
          @click="app.tab = 'masuk'; app.authErr = ''"
        >Masuk</button>
        <button
          class="flex-1 py-3.5 text-sm font-extrabold transition"
          :class="app.tab === 'daftar' ? 'bg-white text-gold-700' : 'bg-navy-100 text-navy-600'"
          @click="app.tab = 'daftar'; app.authErr = ''"
        >Daftar</button>
      </div>

      <div class="p-6">
        <!-- MASUK -->
        <template v-if="app.tab === 'masuk'">
          <div class="mb-3.5">
            <label class="field-label">Email</label>
            <input v-model="app.loginEmail" type="email" placeholder="nama@email.id" class="field-input" @keyup.enter="app.submitLogin()" />
          </div>
          <div class="mb-4">
            <label class="field-label">Kata sandi</label>
            <input v-model="app.loginPass" type="password" placeholder="••••••" class="field-input" @keyup.enter="app.submitLogin()" />
          </div>
          <div v-if="app.authErr" class="mb-3 text-[12.5px] font-bold text-clay-800">{{ app.authErr }}</div>
          <button class="btn-primary btn-block py-3.5" @click="app.submitLogin()">Masuk</button>
        </template>

        <!-- DAFTAR -->
        <template v-else>
          <div class="mb-3">
            <label class="field-label">Nama lengkap</label>
            <input v-model="app.reg.name" type="text" placeholder="Nama sesuai KTP" class="field-input" />
          </div>
          <div class="mb-3 flex flex-wrap gap-2.5">
            <div class="min-w-[150px] flex-[1.4]">
              <label class="field-label">Email</label>
              <input v-model="app.reg.email" type="email" placeholder="nama@email.id" class="field-input" />
            </div>
            <div class="min-w-[120px] flex-1">
              <label class="field-label">Telepon <span class="font-medium text-sand-600">(ops.)</span></label>
              <input v-model="app.reg.phone" type="tel" placeholder="08…" class="field-input" />
            </div>
          </div>
          <div class="mb-3">
            <label class="field-label">Kata sandi <span class="font-medium text-sand-600">(min. 6 karakter)</span></label>
            <input v-model="app.reg.pass" type="password" placeholder="••••••" class="field-input" />
          </div>
          <div class="mb-3">
            <label class="field-label">Saya adalah…</label>
            <div class="flex flex-col gap-2">
              <button
                v-for="r in roleOpts"
                :key="r.value"
                type="button"
                class="flex items-center gap-3 rounded-xl border-2 p-3 text-left transition"
                :class="app.reg.role === r.value
                  ? 'border-pink-600 bg-pink-50'
                  : 'border-gray-200 bg-white hover:border-gray-350'"
                @click="app.reg.role = r.value"
              >
                <span
                  class="grid h-10 w-10 shrink-0 place-items-center rounded-full"
                  :class="app.reg.role === r.value ? 'bg-white text-pink-600' : 'bg-gray-100 text-gray-700'"
                >
                  <svg
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"
                    v-html="r.icon"
                  />
                </span>
                <span class="min-w-0">
                  <span class="block text-sm font-extrabold text-gray-800">{{ r.label }}</span>
                  <span class="block text-xs text-gray-600">{{ r.desc }}</span>
                </span>
                <svg
                  v-if="app.reg.role === r.value"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round"
                  class="ml-auto h-5 w-5 shrink-0 text-pink-600"
                >
                  <circle cx="12" cy="12" r="10" /><path d="m9 12 2 2 4-4" />
                </svg>
              </button>
            </div>
          </div>
          <div v-if="app.reg.role !== 'BUYER'" class="mb-3 rounded-xl border border-sand-200 bg-sand-100 p-3.5">
            <div class="mb-2.5 text-xs font-extrabold text-clay-700">Penautan KDMP</div>
            <div class="mb-2.5">
              <label class="field-label">Koperasi desa</label>
              <select v-model="app.reg.koperasi" class="field-input">
                <option v-for="k in KOPERASI" :key="k" :value="k">{{ k }}</option>
              </select>
            </div>
            <div>
              <label class="field-label">No. anggota <span class="font-medium text-sand-600">(ops.)</span></label>
              <input v-model="app.reg.anggota" type="text" placeholder="cth. SKM-0231" class="field-input" />
            </div>
          </div>
          <div v-if="app.authErr" class="mb-3 text-[12.5px] font-bold text-clay-800">{{ app.authErr }}</div>
          <button class="btn-primary btn-block py-3.5" @click="app.submitReg()">Daftar &amp; Masuk</button>
        </template>
      </div>
    </div>

    <!-- akun demo -->
    <div class="card p-5">
      <div class="mb-3 text-xs font-extrabold uppercase tracking-[0.04em] text-sand-600">Coba cepat — akun demo</div>
      <div class="flex flex-col gap-2">
        <button class="demo" @click="app.demo('budi')"><span class="font-bold">Budi Santoso</span><span class="text-xs text-sand-700">Pembeli (BUYER)</span></button>
        <button class="demo" @click="app.demo('wati')"><span class="font-bold">Wati Suharti</span><span class="text-xs text-sand-700">Warga Desa (WARGA)</span></button>
        <button class="demo" @click="app.demo('darto')"><span class="font-bold">Pak Darto</span><span class="text-xs text-sand-700">Admin Koperasi</span></button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.demo {
  @apply flex cursor-pointer items-center justify-between rounded-[11px] border border-sand-200 bg-sand-100 px-3.5 py-[11px] text-left text-[13px] transition hover:border-clay-600;
}
</style>
