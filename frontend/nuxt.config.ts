// Konfigurasi Nuxt — Budes (Bursa Desa). Frontend clay/terakota, mock-first.
export default defineNuxtConfig({
  compatibilityDate: '2025-01-01',
  devtools: { enabled: false },
  // Pertahankan konteks Nuxt setelah await di SSR — agar useCookie/useRuntimeConfig
  // di dalam action store (hydrateUser dsb) tetap membaca cookie auth saat render server.
  experimental: { asyncContext: true },
  modules: ['@nuxtjs/tailwindcss', '@pinia/nuxt'],
  css: ['~/assets/css/main.css'],
  runtimeConfig: {
    // Base URL API untuk render sisi-server (SSR) — internal cepat, mis. http://api:8080.
    // Kosong = pakai apiBase publik. Set via env NUXT_API_BASE_SERVER.
    apiBaseServer: '',
    public: {
      // Base URL API untuk klien (browser). Kosong = pakai data tiruan (mock-first).
      // Set via env NUXT_PUBLIC_API_BASE (mis. https://api-budes.yum-dev.com).
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
          href: 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap',
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
