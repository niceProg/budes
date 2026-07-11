// Saat pengguna kembali dari halaman bayar Mayar (?bayar=dp|txn), tampilkan info
// & segarkan data (webhook memproses status secara asinkron).
export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.hook('app:mounted', () => {
    const route = useRoute()
    if (!route.query.bayar) return
    const app = useApp()
    app.showToast('Pembayaran diproses — status akan terbarui otomatis setelah lunas.')
    setTimeout(() => {
      app.refreshUser()
      app.hydratePublic()
    }, 3000)
  })
})
