// Pemetaan status → { label, cls } (kelas Tailwind). Diambil dari desain clay.
export interface Badge {
  label: string
  cls: string
}

type Map = Record<string, Badge>

const OK = 'bg-success-100 text-success-600' // hijau lembut
const DONE = 'bg-[#e0f1ef] text-success-800' // teal (selesai)
const WARN = 'bg-warning-50 text-warning-700' // amber
const DANGER = 'bg-rose-100 text-rose-700' // merah
const MUTE = 'bg-navy-250 text-navy-700' // netral/pasif

export const DEMAND_BADGE: Map = {
  DRAFT: { label: 'Draf', cls: MUTE },
  OPEN: { label: 'Dibuka', cls: OK },
  PARTIAL: { label: 'Sebagian', cls: WARN },
  FULFILLED: { label: 'Terpenuhi', cls: DONE },
  CANCELLED: { label: 'Dibatalkan', cls: DANGER },
}

export const PLEDGE_BADGE: Map = {
  PLEDGED: { label: 'Menyanggupi', cls: WARN },
  CONFIRMED: { label: 'Dikonfirmasi', cls: OK },
  DELIVERED: { label: 'Diserahkan', cls: DONE },
  CANCELLED: { label: 'Dibatalkan', cls: DANGER },
}

export const LISTING_BADGE: Map = {
  ACTIVE: { label: 'Aktif', cls: OK },
  SOLD_OUT: { label: 'Habis', cls: MUTE },
  INACTIVE: { label: 'Nonaktif', cls: MUTE },
}

export const ORDER_BADGE: Map = {
  BARU: { label: 'Menunggu', cls: WARN },
  CONFIRMED: { label: 'Dikonfirmasi', cls: OK },
  DONE: { label: 'Selesai', cls: DONE },
  CANCELLED: { label: 'Dibatalkan', cls: DANGER },
}

export const PAY_BADGE: Map = {
  UNPAID: { label: 'Belum Dibayar', cls: WARN },
  PAID: { label: 'Dibayar', cls: OK },
  SETTLED: { label: 'Selesai', cls: DONE },
}

export const ROLE_LABEL: Record<string, string> = {
  BUYER: 'Pembeli',
  WARGA: 'Warga Desa',
  ADMIN_KOPERASI: 'Admin Koperasi',
}

export function badge(map: Map, key: string, fallback = 'OPEN'): Badge {
  return map[key] || map[fallback] || { label: key, cls: MUTE }
}
