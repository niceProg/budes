<script setup lang="ts">
import { useApp } from '~/stores/app'
const app = useApp()
const route = useRoute()
const isActive = (p: string) => (p === '/' ? route.path === '/' : route.path.startsWith(p))

const menuOpen = ref(false)
watch(() => route.fullPath, () => (menuOpen.value = false))

// Link navigasi non-admin (dinamis per peran).
const navLinks = computed(() => {
  const l: { to: string; label: string }[] = [{ to: '/', label: 'Jelajah Pasar' }]
  if (app.isWarga) l.push({ to: '/permintaan', label: 'Permintaan' })
  l.push({ to: '/etalase', label: 'Etalase' })
  if (app.isWarga) l.push({ to: '/titip', label: 'Titip Komoditas' })
  if (app.isLoggedIn) l.push({ to: '/aktivitas', label: 'Aktivitasku' })
  if (app.isLoggedIn && !app.isAdmin) l.push({ to: '/verifikasi-identitas', label: 'Verifikasi' })
  return l
})
const adminLinks = [
  { to: '/dashboard', label: 'Dashboard' },
  { to: '/', label: 'Jelajah Pasar' },
  { to: '/etalase', label: 'Etalase' },
  { to: '/harga', label: 'Pengaturan Harga' },
  { to: '/komisi', label: 'Pengaturan Komisi' },
  { to: '/verifikasi', label: 'Verifikasi Identitas' },
]
</script>

<template>
  <!-- ============ ADMIN KOPERASI: sidebar (drawer di mobile) ============ -->
  <div v-if="app.isAdmin" class="min-h-screen lg:flex">
    <!-- Topbar mobile -->
    <div class="sticky top-0 z-30 flex items-center justify-between border-b border-sand-400 bg-white px-4 py-3 lg:!hidden">
      <div class="flex items-center gap-2.5">
        <div class="flex h-8 w-8 items-center justify-center rounded-[9px] bg-pink-gradient text-[13px] font-extrabold text-white">BD</div>
        <div class="text-[14px] font-extrabold leading-tight">Bursa Desa <span class="ml-1 text-[10px] font-bold uppercase tracking-wider text-navy-600">Admin</span></div>
      </div>
      <button class="ham" aria-label="Buka menu" @click="menuOpen = true"><span></span><span></span><span></span></button>
    </div>

    <!-- Backdrop -->
    <div v-if="menuOpen" class="fixed inset-0 z-40 bg-black/40 lg:hidden" @click="menuOpen = false" />

    <!-- Sidebar / drawer -->
    <aside
      class="fixed inset-y-0 left-0 z-50 flex h-screen w-[250px] shrink-0 flex-col gap-1 border-r border-sand-400 bg-white p-[14px] py-5 transition-transform duration-300 lg:sticky lg:top-0 lg:z-40 lg:w-[228px] lg:translate-x-0"
      :class="menuOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div class="flex items-center justify-between px-2 pb-[18px] pt-0.5">
        <div class="flex items-center gap-2.5">
          <div class="flex h-[34px] w-[34px] items-center justify-center rounded-[10px] bg-pink-gradient text-sm font-extrabold text-white">BD</div>
          <div>
            <div class="text-[15px] font-extrabold leading-tight">Bursa Desa</div>
            <div class="text-[10px] font-bold uppercase tracking-wider text-navy-600">Panel Koperasi</div>
          </div>
        </div>
        <button class="rounded-lg p-1.5 text-sand-600 hover:bg-sand-150 lg:!hidden" aria-label="Tutup menu" @click="menuOpen = false">✕</button>
      </div>
      <NuxtLink v-for="l in adminLinks" :key="l.to" :to="l.to" class="sb" :class="isActive(l.to) ? 'sb-on' : 'sb-off'">{{ l.label }}</NuxtLink>
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

    <main class="min-w-0 flex-1"><slot /></main>
  </div>

  <!-- ============ NON-ADMIN: header + footer ============ -->
  <div v-else class="flex min-h-screen flex-col">
    <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/95 shadow-[0_2px_20px_rgba(0,0,0,0.05)] backdrop-blur">
      <div class="mx-auto flex min-h-[60px] max-w-page items-center gap-x-3 px-4 sm:px-6 lg:px-8">
        <NuxtLink to="/" class="mr-1 flex items-center gap-2.5 py-2.5">
          <div class="flex h-9 w-9 items-center justify-center rounded-[10px] bg-pink-gradient text-sm font-extrabold text-white sm:h-10 sm:w-10">BD</div>
          <div>
            <div class="font-heading text-[15px] font-bold leading-tight text-pink-600 sm:text-base">Bursa Desa</div>
            <div class="hidden text-[10px] font-medium text-gray-600 sm:block">Koperasi Desa Merah Putih</div>
          </div>
        </NuxtLink>

        <!-- Nav desktop -->
        <nav class="ml-2 hidden items-center gap-1 lg:flex">
          <NuxtLink v-for="l in navLinks" :key="l.to" :to="l.to" class="nav" :class="isActive(l.to) ? 'nav-on' : 'nav-off'">{{ l.label }}</NuxtLink>
        </nav>

        <div class="flex-1" />

        <!-- Aksi desktop -->
        <div v-if="app.isLoggedIn" class="hidden items-center gap-2 py-2 lg:flex">
          <div class="flex items-center gap-2 rounded-full border border-sand-400 bg-sand-150 py-1 pl-[5px] pr-3">
            <div class="flex h-[26px] w-[26px] items-center justify-center rounded-full bg-clay-600 text-xs font-extrabold text-white">{{ app.userInitial }}</div>
            <div>
              <div class="text-[12.5px] font-bold leading-tight">{{ app.user?.name }}</div>
              <div class="text-[10px] font-semibold text-sand-600">{{ app.userRoleLabel }} · {{ app.userVerLabel }}</div>
            </div>
          </div>
          <button class="btn-outline px-3 py-[7px] text-xs" @click="app.doLogout()">Keluar</button>
        </div>
        <div v-else class="hidden gap-2 py-2 lg:flex">
          <NuxtLink to="/masuk" class="btn-outline px-4 py-2 text-[13px]" @click="app.tab = 'masuk'">Masuk</NuxtLink>
          <NuxtLink to="/masuk" class="btn-primary px-4 py-2 text-[13px]" @click="app.tab = 'daftar'">Daftar</NuxtLink>
        </div>

        <!-- Hamburger (mobile) -->
        <button class="ham lg:!hidden" :aria-label="menuOpen ? 'Tutup menu' : 'Buka menu'" @click="menuOpen = !menuOpen">
          <span :class="{ 'ham-x1': menuOpen }"></span>
          <span :class="{ 'ham-hide': menuOpen }"></span>
          <span :class="{ 'ham-x2': menuOpen }"></span>
        </button>
      </div>

      <!-- Panel menu mobile -->
      <transition name="slide">
        <div v-if="menuOpen" class="border-t border-gray-200 bg-white px-4 py-3 lg:hidden">
          <nav class="flex flex-col gap-1">
            <NuxtLink v-for="l in navLinks" :key="l.to" :to="l.to" class="nav w-full" :class="isActive(l.to) ? 'nav-on' : 'nav-off'">{{ l.label }}</NuxtLink>
          </nav>
          <div class="mt-3 border-t border-gray-200 pt-3">
            <div v-if="app.isLoggedIn" class="flex items-center justify-between gap-2">
              <div class="flex min-w-0 items-center gap-2">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-clay-600 text-xs font-extrabold text-white">{{ app.userInitial }}</div>
                <div class="min-w-0">
                  <div class="truncate text-[13px] font-bold leading-tight">{{ app.user?.name }}</div>
                  <div class="text-[10px] font-semibold text-sand-600">{{ app.userRoleLabel }} · {{ app.userVerLabel }}</div>
                </div>
              </div>
              <button class="btn-outline shrink-0 px-3 py-2 text-xs" @click="app.doLogout()">Keluar</button>
            </div>
            <div v-else class="grid grid-cols-2 gap-2">
              <NuxtLink to="/masuk" class="btn-outline py-2.5 text-center text-[13px]" @click="app.tab = 'masuk'">Masuk</NuxtLink>
              <NuxtLink to="/masuk" class="btn-primary py-2.5 text-center text-[13px]" @click="app.tab = 'daftar'">Daftar</NuxtLink>
            </div>
          </div>
        </div>
      </transition>
    </header>

    <main class="min-w-0 flex-1"><slot /></main>

    <footer class="bg-navy-900 text-navy-350">
      <div class="mx-auto flex max-w-page flex-col items-start justify-between gap-3.5 px-4 py-8 sm:flex-row sm:items-center sm:px-6 lg:px-8">
        <div class="flex items-center gap-2.5">
          <div class="flex h-[34px] w-[34px] items-center justify-center rounded-[9px] bg-white/10 text-sm font-extrabold text-gold-400">BD</div>
          <div>
            <div class="text-[15px] font-extrabold text-white">Bursa Desa</div>
            <div class="text-[12.5px]">Pasar gotong royong Koperasi Desa Merah Putih.</div>
          </div>
        </div>
        <div class="text-xs text-navy-500">© 2026 Bursa Desa · Komisi koperasi {{ app.commissionPct }}% per transaksi · Prototipe demo</div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.nav {
  @apply cursor-pointer rounded-lg px-3.5 py-2 text-[0.875rem] font-medium text-gray-700 transition hover:bg-pink-50 hover:text-pink-600;
}
.nav-on { @apply bg-pink-50 text-pink-600; }
.nav-off { @apply text-gray-700; }
.sb {
  @apply cursor-pointer rounded-[10px] px-3 py-[11px] text-left text-[13.5px] font-semibold transition hover:bg-pink-50;
}
.sb-on { @apply bg-pink-50 text-pink-600; }
.sb-off { @apply text-gray-700; }

/* Tombol hamburger */
.ham {
  @apply relative flex h-10 w-10 shrink-0 cursor-pointer flex-col items-center justify-center gap-[5px] rounded-lg border border-gray-200 bg-white transition hover:bg-gray-50;
}
.ham span {
  @apply block h-[2px] w-[19px] rounded-full bg-gray-700 transition-all duration-300;
}
.ham .ham-x1 { transform: translateY(7px) rotate(45deg); }
.ham .ham-x2 { transform: translateY(-7px) rotate(-45deg); }
.ham .ham-hide { opacity: 0; }

.slide-enter-active,
.slide-leave-active { transition: opacity 0.2s ease, transform 0.2s ease; }
.slide-enter-from,
.slide-leave-to { opacity: 0; transform: translateY(-8px); }
</style>
