/** @type {import('tailwindcss').Config} */

// Design system "Navy + Gold" (diadaptasi dari landing /views — DPR RI/SMART).
// Token lama clay/sand di-remap: clay → emas (aksen), sand → navy/slate (netral).
// Alias `gold` & `navy` disediakan untuk markup baru agar lebih terbaca.
const gold = {
  50: '#faf6ec', 100: '#f3e9cf', 200: '#e6d3a0', 300: '#d4b878',
  400: '#c5a059', 500: '#b89551', 600: '#b08d48', 700: '#8e6d2f',
  800: '#6f5426', 900: '#4f3c1b',
}
const navy = {
  50: '#ffffff', 100: '#f8fafc', 150: '#f1f5f9', 200: '#edf2f7',
  250: '#e2e8f0', 300: '#e2e8f0', 350: '#cbd5e1', 400: '#e6ebf2',
  500: '#94a3b8', 600: '#64748b', 700: '#475569', 800: '#1e293b',
  900: '#0f172a',
}

export default {
  content: [
    './components/**/*.{vue,js,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './app.vue',
  ],
  theme: {
    extend: {
      colors: {
        gold,
        navy,
        clay: gold, // alias legacy → emas (aksen utama)
        sand: navy, // alias legacy → navy/slate (netral)
        // Status semantik
        success: {
          50: '#f0f9f3', 100: '#e4f3ea', 200: '#bbdcc8',
          600: '#1d7a46', 700: '#156137', 800: '#0f6b62',
        },
        warning: { 50: '#fdf0d9', 100: '#efd9a9', 700: '#92400e' },
        grape: { 100: '#ede7f3', 700: '#5b3e8c' },
      },
      fontFamily: {
        sans: ["'Plus Jakarta Sans'", 'system-ui', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
      maxWidth: {
        page: '1200px',
      },
      backgroundImage: {
        'gold-gradient': 'linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%)',
        'navy-hero': 'linear-gradient(160deg, #1e293b 0%, #0f172a 70%)',
      },
      boxShadow: {
        soft: '0 20px 40px rgba(15,23,42,0.08)',
        lift: '0 20px 40px rgba(176,141,72,0.12)',
      },
      keyframes: {
        fadeUp: {
          '0%': { opacity: '0', transform: 'translateY(8px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        fadeIn: {
          '0%': { opacity: '0', transform: 'translateY(10px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        toastIn: {
          '0%': { opacity: '0', transform: 'translate(-50%, 12px)' },
          '100%': { opacity: '1', transform: 'translate(-50%, 0)' },
        },
      },
      animation: {
        fadeUp: 'fadeUp .2s ease',
        fadeIn: 'fadeIn .5s ease forwards',
        toastIn: 'toastIn .25s ease',
      },
    },
  },
  plugins: [],
}
