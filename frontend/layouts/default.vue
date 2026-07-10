<script setup lang="ts">
import { useApp } from '~/stores/app'
const app = useApp()
const route = useRoute()
const isActive = (p: string) => (p === '/' ? route.path === '/' : route.path.startsWith(p))
</script>

<template>
  <!-- Admin koperasi: layout sidebar (row). Lainnya: header + footer (column). -->
  <div v-if="app.isAdmin" class="flex min-h-screen">
    <aside
      class="sticky top-0 z-40 flex h-screen w-[228px] shrink-0 flex-col gap-1 border-r border-sand-400 bg-white p-[14px] px-[14px] py-5"
    >
      <div class="flex items-center gap-2.5 px-2 pb-[18px] pt-0.5">
        <div class="flex h-[34px] w-[34px] items-center justify-center rounded-[9px] bg-clay-600 text-sm font-extrabold text-white">BD</div>
        <div>
          <div class="text-[15px] font-extrabold leading-tight">Bursa Desa</div>
          <div class="eyebrow text-sand-600">Panel Koperasi</div>
        </div>
      </div>
      <NuxtLink to="/dashboard" class="sb" :class="isActive('/dashboard') ? 'sb-on' : 'sb-off'">Dashboard</NuxtLink>
      <NuxtLink to="/" class="sb" :class="isActive('/') ? 'sb-on' : 'sb-off'">Jelajah Pasar</NuxtLink>
      <NuxtLink to="/etalase" class="sb" :class="isActive('/etalase') ? 'sb-on' : 'sb-off'">Etalase</NuxtLink>
      <div class="flex-1" />
      <div class="mt-3.5 flex items-center gap-2.5 border-t border-sand-400 pt-3.5">
        <div class="flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-full bg-clay-600 text-xs font-extrabold text-white">{{ app.userInitial }}</div>
        <div class="min-w-0">
          <div class="truncate text-[12.5px] font-bold leading-tight">{{ app.user?.name }}</div>
          <div class="text-[10px] font-semibold text-sand-600">{{ app.userRoleLabel }}</div>
        </div>
      </div>
      <button class="btn-outline mt-2.5 w-full py-2.5 text-[12.5px]" @click="app.doLogout()">Keluar</button>
    </aside>

    <main class="min-w-0 flex-1">
      <slot />
    </main>
  </div>

  <div v-else class="flex min-h-screen flex-col">
    <header class="sticky top-0 z-40 border-b border-sand-400 bg-sand-50 shadow-[0_1px_5px_rgba(80,10,12,0.04)]">
      <div class="mx-auto flex min-h-[60px] max-w-page flex-wrap items-center gap-x-3.5 gap-y-1 px-5">
        <NuxtLink to="/" class="mr-2 flex items-center gap-2.5 py-2.5">
          <div class="flex h-[34px] w-[34px] items-center justify-center rounded-[9px] bg-clay-600 text-sm font-extrabold text-white">BD</div>
          <div>
            <div class="text-base font-extrabold leading-tight">Bursa Desa</div>
            <div class="eyebrow text-sand-600">Koperasi Desa Merah Putih</div>
          </div>
        </NuxtLink>

        <nav class="flex flex-wrap items-stretch">
          <NuxtLink to="/" class="nav" :class="isActive('/') ? 'nav-on' : 'nav-off'">Jelajah Pasar</NuxtLink>
          <NuxtLink to="/etalase" class="nav" :class="isActive('/etalase') ? 'nav-on' : 'nav-off'">Etalase</NuxtLink>
          <button v-if="app.isBuyer" class="nav nav-off" :class="{ 'nav-on': isActive('/buat') }" @click="app.ctaBuat()">Buat Permintaan</button>
          <NuxtLink v-if="app.isWarga" to="/titip" class="nav" :class="isActive('/titip') ? 'nav-on' : 'nav-off'">Titip Komoditas</NuxtLink>
          <NuxtLink v-if="app.isLoggedIn" to="/aktivitas" class="nav" :class="isActive('/aktivitas') ? 'nav-on' : 'nav-off'">Aktivitasku</NuxtLink>
        </nav>

        <div class="flex-1" />

        <div v-if="app.isLoggedIn" class="flex items-center gap-2 py-2">
          <div class="flex items-center gap-2 rounded-full border border-sand-400 bg-sand-150 py-1 pl-[5px] pr-3">
            <div class="flex h-[26px] w-[26px] items-center justify-center rounded-full bg-clay-600 text-xs font-extrabold text-white">{{ app.userInitial }}</div>
            <div>
              <div class="text-[12.5px] font-bold leading-tight">{{ app.user?.name }}</div>
              <div class="text-[10px] font-semibold text-sand-600">{{ app.userRoleLabel }} · {{ app.userVerLabel }}</div>
            </div>
          </div>
          <button class="btn-outline px-3 py-[7px] text-xs" @click="app.doLogout()">Keluar</button>
        </div>
        <div v-else class="flex gap-2 py-2">
          <NuxtLink to="/masuk" class="btn-outline px-4 py-2 text-[13px]" @click="app.tab = 'masuk'">Masuk</NuxtLink>
          <NuxtLink to="/masuk" class="btn-primary px-4 py-2 text-[13px]" @click="app.tab = 'daftar'">Daftar</NuxtLink>
        </div>
      </div>
    </header>

    <main class="min-w-0 flex-1">
      <slot />
    </main>

    <footer class="border-t border-sand-400 bg-[#f3eae1] text-sand-700">
      <div class="mx-auto flex max-w-page flex-wrap items-center justify-between gap-3.5 px-5 py-[26px]">
        <div>
          <div class="text-[15px] font-extrabold text-sand-900">Bursa Desa</div>
          <div class="text-[12.5px]">Pasar gotong royong Koperasi Desa Merah Putih.</div>
        </div>
        <div class="text-xs">© 2026 Bursa Desa · Komisi koperasi 5% per transaksi · Prototipe demo</div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.nav {
  @apply cursor-pointer border-b-[3px] border-t-[3px] border-transparent px-3 py-[19px] text-[13.5px] font-bold text-sand-800 transition hover:bg-sand-150;
}
.nav-on { @apply border-b-clay-600; }
.nav-off { @apply border-b-transparent; }
.sb {
  @apply cursor-pointer rounded-[10px] px-3 py-[11px] text-left text-[13.5px] font-bold transition hover:bg-sand-150;
}
.sb-on { @apply bg-[#f6e8e4] text-clay-700; }
.sb-off { @apply text-sand-800; }
</style>
