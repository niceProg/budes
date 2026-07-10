// Helper format lokal (id-ID) — dipakai lintas halaman & store.
export function fmtN(n: number | null | undefined): string {
  return (n || 0).toLocaleString('id-ID')
}

export function fmtRp(n: number | null | undefined): string {
  return 'Rp' + Math.round(n || 0).toLocaleString('id-ID')
}

export function fmtTgl(iso: string | null | undefined): string {
  if (!iso) return '—'
  return new Date(iso + 'T00:00:00').toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  })
}

export function hari(iso: string | null | undefined): string {
  if (!iso) return 'tanpa tenggat'
  const diff = Math.ceil((new Date(iso + 'T00:00:00').getTime() - Date.now()) / 864e5)
  if (diff < 0) return 'lewat tenggat'
  if (diff === 0) return 'hari ini'
  return diff + ' hari lagi'
}

// Composable pembungkus agar bisa dipakai lewat auto-import gaya Nuxt.
export function useFormat() {
  return { fmtN, fmtRp, fmtTgl, hari }
}
