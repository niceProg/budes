// Muat data awal dari API saat render pertama (SSR + klien). Aman untuk mode mock
// (boot() langsung keluar bila apiBase kosong).
export default defineNuxtPlugin(async () => {
  const app = useApp()
  if (!app.booted) await app.boot()
})
