<script setup lang="ts">
import { useApp, KOPERASI } from '~/stores/app'
const app = useApp()
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
            <label class="field-label">Mendaftar sebagai</label>
            <select v-model="app.reg.role" class="field-input">
              <option value="BUYER">Pembeli (BUYER)</option>
              <option value="WARGA">Warga Desa (WARGA)</option>
              <option value="ADMIN_KOPERASI">Admin Koperasi (ADMIN_KOPERASI)</option>
            </select>
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
