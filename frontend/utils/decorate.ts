// Mengubah data mentah (demand/listing) menjadi field siap-tampil.
import { fmtN, fmtRp, fmtTgl, hari } from '../composables/useFormat'
import { DEMAND_BADGE, LISTING_BADGE, badge, type Badge } from './badges'

export interface Pledge {
  name: string
  q: number
  d: number
  st: string
}
export interface Kandidat {
  name: string
  note: string
}
export interface Demand {
  id: string
  owner: string
  item_name: string
  satuan: string
  total: number
  fulfilled: number
  harga: number
  deadline: string | null
  status: string
  dp: string
  method: string | null
  pledges: Pledge[]
  kandidat: Kandidat[]
}
export interface Listing {
  id: string
  owner: string
  seller: string
  item_name: string
  satuan: string
  avail: number
  sold: number
  harga: number
  status: string
  tanggal: string
}

export interface DemandView extends Demand {
  pct: number
  totalPrice: number
  qtyLine: string
  hargaTxt: string
  hargaNum: string
  totalTxt: string
  dpTxt: string
  sisaTxt: string
  tglTxt: string
  hariTxt: string
  sisaKuota: number
  sisaKuotaTxt: string
  badge: Badge
  dpPaid: boolean
  dpStatusShort: string
  dpStatusLabel: string
  pledgeCount: number
}

export interface ListingView extends Listing {
  stok: number
  stokLine: string
  stokTxt: string
  terjualTxt: string
  hargaTxt: string
  tglTxt: string
  badge: Badge
}

export function decorateDemand(d: Demand): DemandView {
  const totalPrice = d.total * d.harga
  const pct = d.total ? Math.min(100, Math.round((d.fulfilled / d.total) * 100)) : 0
  const sisaKuota = Math.max(0, d.total - d.fulfilled)
  return {
    ...d,
    pct,
    totalPrice,
    qtyLine: `${fmtN(d.fulfilled)} / ${fmtN(d.total)} ${d.satuan} tersanggupi`,
    hargaTxt: fmtRp(d.harga),
    hargaNum: String(d.harga),
    totalTxt: fmtRp(totalPrice),
    dpTxt: fmtRp(totalPrice * 0.3),
    sisaTxt: fmtRp(totalPrice * 0.7),
    tglTxt: fmtTgl(d.deadline),
    hariTxt: hari(d.deadline),
    sisaKuota,
    sisaKuotaTxt: `${fmtN(sisaKuota)} ${d.satuan}`,
    badge: badge(DEMAND_BADGE, d.status),
    dpPaid: d.dp === 'PAID',
    dpStatusShort: d.dp === 'PAID' ? 'terbayar' : 'belum dibayar',
    dpStatusLabel:
      d.dp === 'PAID'
        ? `Terbayar (${d.method === 'CASH' ? 'Tunai' : 'Transfer'})`
        : 'Belum dibayar',
    pledgeCount: d.pledges.length,
  }
}

export function decorateListing(l: Listing): ListingView {
  const stok = Math.max(0, l.avail - l.sold)
  const st = stok === 0 ? 'SOLD_OUT' : l.status
  return {
    ...l,
    stok,
    stokLine: `Stok tersedia: ${fmtN(stok)} ${l.satuan}`,
    stokTxt: `${fmtN(stok)} ${l.satuan}`,
    terjualTxt: `${fmtN(l.sold)} ${l.satuan}`,
    hargaTxt: fmtRp(l.harga),
    tglTxt: fmtTgl(l.tanggal),
    badge: badge(LISTING_BADGE, st),
  }
}

export function initial(name: string): string {
  return name ? name.trim()[0].toUpperCase() : ''
}
