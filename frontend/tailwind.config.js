/** @type {import('tailwindcss').Config} */

// Design system "Parlemen Remaja (Parja)" — magenta/pink gradient + violet + abu.
// Token lama di-remap: gold/clay → pink (primer), navy/sand → abu/gelap (netral),
// grape → violet (sekunder). Alias `pink`/`violet` disediakan untuk markup baru.
const pink = {
  50: '#fdeef4', 100: '#fbdce8', 200: '#f7b9d0', 300: '#f48bb2',
  400: '#ff4d85', 500: '#e83070', 600: '#bf0050', 700: '#a8003f',
  800: '#8e0035', 900: '#6b0028',
}
const gray = {
  50: '#ffffff', 100: '#f8f9fa', 150: '#f8f9fa', 200: '#f0f0f0',
  250: '#ececec', 300: '#e9ecef', 350: '#dee2e6', 400: '#ececec',
  500: '#adb5bd', 600: '#6c757d', 700: '#495057', 800: '#212529',
  900: '#1a1a2e',
}
const violet = {
  100: '#efe6f3', 200: '#d8c2e0', 500: '#7a3a8a', 600: '#41174b',
  700: '#310f39',
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
        pink,
        violet,
        gray,
        // Alias legacy → skema Parja
        gold: pink, // aksen/primer
        clay: pink,
        navy: gray, // netral & gelap
        sand: gray,
        grape: violet, // badge sekunder
        // Status semantik
        success: {
          50: '#eaf7ef', 100: '#d3ecdc', 200: '#a9d9bb',
          600: '#1a7a45', 700: '#146138', 800: '#0f4d2c',
        },
        warning: { 50: '#fff4e0', 100: '#ffe4b3', 700: '#a15c00' },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'Segoe UI', 'Roboto', 'sans-serif'],
        heading: ['Poppins', 'system-ui', 'sans-serif'],
      },
      maxWidth: {
        page: '1200px',
      },
      backgroundImage: {
        'gold-gradient': 'linear-gradient(135deg, #bf0050 0%, #ff4d85 100%)',
        'pink-gradient': 'linear-gradient(135deg, #bf0050 0%, #ff4d85 100%)',
        'pink-gradient-hover': 'linear-gradient(135deg, #a8003f 0%, #e83070 100%)',
        'pink-soft': 'linear-gradient(135deg, rgba(191,0,80,0.08) 0%, rgba(255,77,133,0.08) 100%)',
        'violet-gradient': 'linear-gradient(135deg, #41174b 0%, #7a3a8a 100%)',
        'navy-hero': 'linear-gradient(135deg, #bf0050 0%, #ff4d85 100%)',
      },
      boxShadow: {
        'pr-sm': '0 4px 20px rgba(191,0,80,0.12)',
        'pr-md': '0 8px 32px rgba(191,0,80,0.18)',
        soft: '0 2px 16px rgba(33,37,41,0.08)',
        lift: '0 8px 32px rgba(33,37,41,0.15)',
      },
      keyframes: {
        fadeUp: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
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
        fadeUp: 'fadeUp .6s ease forwards',
        fadeIn: 'fadeIn .5s ease forwards',
        toastIn: 'toastIn .25s ease',
      },
    },
  },
  plugins: [],
}
