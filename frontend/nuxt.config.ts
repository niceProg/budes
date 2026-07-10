// Konfigurasi Nuxt — Budes (Bursa Desa). Frontend clay/terakota, mock-first.
export default defineNuxtConfig({
  compatibilityDate: '2025-01-01',
  devtools: { enabled: false },
  modules: ['@nuxtjs/tailwindcss', '@pinia/nuxt'],
  css: ['~/assets/css/main.css'],
  runtimeConfig: {
    public: {
      // Kosong = pakai data tiruan (mock-first). Isi dengan URL backend Go
      // (mis. http://localhost:8080) saat siap menyambung API asli.
      apiBase: '',
    },
  },
  app: {
    head: {
      title: 'Bursa Desa — Pasar Gotong Royong Desa',
      htmlAttrs: { lang: 'id' },
      link: [
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
        },
      ],
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        {
          name: 'description',
          content:
            'Bursa Desa — pembeli memposting kebutuhan, warga menyanggupi dari hasil panen, koperasi desa (KDMP) menjadi penghubungnya.',
        },
      ],
    },
  },
})
