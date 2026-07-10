import { defineStore } from 'pinia'
import { fmtN, fmtRp } from '~/composables/useFormat'
import { ROLE_LABEL } from '~/utils/badges'
import { initial, type Demand, type Listing } from '~/utils/decorate'

export type Role = 'BUYER' | 'WARGA' | 'ADMIN_KOPERASI'
export interface User {
  id: string
  name: string
  role: Role
  ver: 'VERIFIED' | 'PENDING'
}
interface PledgeRow {
  id: string
  owner: string
  demandId: string
  item: string
  qty: number
  satuan: string
  status: string
}
interface OrderRow {
  id: string
  owner: string
  listingId: string
  item: string
  qty: number
  satuan: string
  harga: number
  status: string
}
interface Txn {
  id: string
  kind: 'demand' | 'supply'
  item: string
  pihak: string
  gross: number
  pay: string
}
interface Kyc {
  id: string
  name: string
  role: 'WARGA' | 'BUYER'
  status: 'PENDING' | 'VERIFIED' | 'REJECTED'
  nik: string
  doc: string
  ktpFile: string
}
interface PriceCap {
  id: string
  komoditas: string
  satuan: string
  maxJual: number
  maxBeli: number
}
type Pending =
  | { type: 'pledge'; id: string }
  | { type: 'order'; id: string }
  | { type: 'buat' }
  | null

const USERS: Record<string, User> = {
  budi: { id: 'budi', name: 'Budi Santoso', role: 'BUYER', ver: 'VERIFIED' },
  wati: { id: 'wati', name: 'Wati Suharti', role: 'WARGA', ver: 'VERIFIED' },
  darto: { id: 'darto', name: 'Pak Darto', role: 'ADMIN_KOPERASI', ver: 'VERIFIED' },
}

export const KOPERASI = [
  'KDMP Desa Sukamaju',
  'KDMP Desa Mekarsari',
  'KDMP Kelurahan Tanjung Harapan',
]

let toastTimer: ReturnType<typeof setTimeout> | undefined

function routeFor(screen: string, id?: string): string {
  switch (screen) {
    case 'pasar': return '/'
    case 'etalase': return '/etalase'
    case 'demand': return `/permintaan/${id}`
    case 'listing': return `/etalase/${id}`
    case 'login': return '/masuk'
    case 'buat': return '/buat'
    case 'titip': return '/titip'
    case 'saya': return '/aktivitas'
    case 'dash': return '/dashboard'
    default: return '/'
  }
}

export const useApp = defineStore('app', {
  state: () => ({
    user: null as User | null,
    filter: 'AKTIF' as 'AKTIF' | 'OPEN' | 'PARTIAL' | 'SEMUA',
    demands: [
      { id: 'd1', owner: 'budi', item_name: 'Beras Medium IR64', satuan: 'kg', total: 500, fulfilled: 320, harga: 12500, deadline: '2026-07-25', status: 'PARTIAL', dp: 'PAID', method: 'TRANSFER',
        pledges: [{ name: 'Wati Suharti', q: 120, d: 120, st: 'DELIVERED' }, { name: 'Slamet Riyadi', q: 200, d: 80, st: 'CONFIRMED' }],
        kandidat: [{ name: 'KDMP Desa Mekarsari', note: 'Panen padi diperkirakan Agustus' }, { name: 'KDMP Desa Sukamaju', note: 'Stok gabah ± 2 ton' }] },
      { id: 'd2', owner: 'lain', item_name: 'Jagung Pipil Kering', satuan: 'kg', total: 1000, fulfilled: 150, harga: 5200, deadline: '2026-08-02', status: 'PARTIAL', dp: 'PAID', method: 'CASH',
        pledges: [{ name: 'Karyo', q: 150, d: 0, st: 'PLEDGED' }],
        kandidat: [{ name: 'KDMP Kelurahan Tanjung Harapan', note: 'Sentra jagung, 3 kelompok tani' }] },
      { id: 'd3', owner: 'budi', item_name: 'Cabai Merah Keriting', satuan: 'kg', total: 200, fulfilled: 0, harga: 38000, deadline: '2026-07-18', status: 'OPEN', dp: 'PAID', method: 'TRANSFER', pledges: [], kandidat: [] },
      { id: 'd4', owner: 'lain', item_name: 'Gula Aren Cetak', satuan: 'kg', total: 300, fulfilled: 300, harga: 21000, deadline: '2026-07-12', status: 'FULFILLED', dp: 'PAID', method: 'TRANSFER',
        pledges: [{ name: 'Siti Aminah', q: 180, d: 180, st: 'DELIVERED' }, { name: 'Wagimin', q: 120, d: 120, st: 'DELIVERED' }], kandidat: [] },
      { id: 'd5', owner: 'budi', item_name: 'Kopi Robusta Petik Merah', satuan: 'kg', total: 150, fulfilled: 40, harga: 65000, deadline: '2026-08-15', status: 'PARTIAL', dp: 'PAID', method: 'TRANSFER',
        pledges: [{ name: 'Wati Suharti', q: 40, d: 0, st: 'CONFIRMED' }],
        kandidat: [{ name: 'KDMP Desa Mekarsari', note: 'Kebun kopi rakyat 12 ha' }] },
      { id: 'd6', owner: 'lain', item_name: 'Kelapa Butir', satuan: 'butir', total: 2000, fulfilled: 0, harga: 3500, deadline: '2026-07-30', status: 'OPEN', dp: 'PAID', method: 'CASH', pledges: [], kandidat: [] },
      { id: 'd7', owner: 'lain', item_name: 'Minyak Kelapa Murni', satuan: 'liter', total: 100, fulfilled: 0, harga: 45000, deadline: '2026-08-20', status: 'DRAFT', dp: 'UNPAID', method: null, pledges: [], kandidat: [] },
    ] as Demand[],
    listings: [
      { id: 'l1', owner: 'wati', seller: 'Wati Suharti', item_name: 'Beras Organik Mentik Wangi', satuan: 'kg', avail: 250, sold: 90, harga: 16000, status: 'ACTIVE', tanggal: '2026-07-01' },
      { id: 'l2', owner: 'lain', seller: 'Karyo', item_name: 'Madu Hutan Asli', satuan: 'botol', avail: 60, sold: 12, harga: 55000, status: 'ACTIVE', tanggal: '2026-06-28' },
      { id: 'l3', owner: 'lain', seller: 'Siti Aminah', item_name: 'Pisang Kepok', satuan: 'sisir', avail: 120, sold: 40, harga: 9000, status: 'ACTIVE', tanggal: '2026-07-05' },
      { id: 'l4', owner: 'darto', seller: 'KDMP Desa Sukamaju', item_name: 'Gabah Kering Giling', satuan: 'kg', avail: 800, sold: 0, harga: 6800, status: 'ACTIVE', tanggal: '2026-07-08' },
      { id: 'l5', owner: 'wati', seller: 'Wati Suharti', item_name: 'Keripik Singkong Balado', satuan: 'bungkus', avail: 200, sold: 185, harga: 12000, status: 'ACTIVE', tanggal: '2026-06-20' },
    ] as Listing[],
    pledges: [
      { id: 'p1', owner: 'wati', demandId: 'd1', item: 'Beras Medium IR64', qty: 120, satuan: 'kg', status: 'DELIVERED' },
      { id: 'p2', owner: 'wati', demandId: 'd5', item: 'Kopi Robusta Petik Merah', qty: 40, satuan: 'kg', status: 'CONFIRMED' },
    ] as PledgeRow[],
    orders: [
      { id: 'o1', owner: 'budi', listingId: 'l2', item: 'Madu Hutan Asli', qty: 12, satuan: 'botol', harga: 55000, status: 'CONFIRMED' },
    ] as OrderRow[],
    txns: [
      { id: 't1', kind: 'demand', item: 'Gula Aren Cetak', pihak: 'Siti Aminah → Toko Manis Jaya', gross: 6300000, pay: 'SETTLED' },
      { id: 't2', kind: 'demand', item: 'Beras Medium IR64', pihak: 'Wati Suharti → Budi Santoso', gross: 1500000, pay: 'PAID' },
      { id: 't3', kind: 'supply', item: 'Madu Hutan Asli', pihak: 'Karyo → Budi Santoso', gross: 660000, pay: 'UNPAID' },
      { id: 't4', kind: 'supply', item: 'Beras Organik Mentik Wangi', pihak: 'Wati Suharti → CV Sehat Pangan', gross: 1440000, pay: 'PAID' },
      { id: 't5', kind: 'demand', item: 'Kopi Robusta Petik Merah', pihak: 'Wati Suharti → Budi Santoso', gross: 2600000, pay: 'UNPAID' },
    ] as Txn[],
    kyc: [
      { id: 'k1', name: 'Slamet Riyadi', role: 'WARGA', status: 'PENDING', nik: '3402011203920012', doc: 'Surat keterangan domisili desa', ktpFile: 'ktp-slamet-riyadi.jpg' },
      { id: 'k2', name: 'Siti Aminah', role: 'WARGA', status: 'VERIFIED', nik: '3402014507870087', doc: 'Kartu Keluarga', ktpFile: 'ktp-siti-aminah.png' },
      { id: 'k3', name: 'Budi Santoso', role: 'BUYER', status: 'PENDING', nik: '3174052208900055', doc: 'NPWP usaha kuliner', ktpFile: 'ktp-budi-santoso.jpeg' },
      { id: 'k4', name: 'Toko Manis Jaya', role: 'BUYER', status: 'REJECTED', nik: '3273068811930041', doc: 'SIUP', ktpFile: 'ktp-toko-manis.jpg' },
    ] as Kyc[],
    priceCaps: [
      { id: 'pc1', komoditas: 'Beras', satuan: 'kg', maxJual: 13000, maxBeli: 15000 },
      { id: 'pc2', komoditas: 'Jagung Pipil', satuan: 'kg', maxJual: 5500, maxBeli: 6500 },
      { id: 'pc3', komoditas: 'Cabai Merah', satuan: 'kg', maxJual: 40000, maxBeli: 45000 },
      { id: 'pc4', komoditas: 'Kopi Robusta', satuan: 'kg', maxJual: 68000, maxBeli: 75000 },
      { id: 'pc5', komoditas: 'Kelapa', satuan: 'butir', maxJual: 3800, maxBeli: 4500 },
    ] as PriceCap[],

    // UI transient
    modal: null as null | 'auth' | 'pledge' | 'order' | 'listingView' | 'listingEdit' | 'kycView',
    modErr: '',
    pledgeQty: '',
    pledgePrice: '',
    orderQty: '',
    activeListingId: null as string | null,
    activeKycId: null as string | null,
    listEdit: { avail: '', harga: '', status: 'ACTIVE' },
    listEditErr: '',
    pending: null as Pending,
    tab: 'masuk' as 'masuk' | 'daftar',
    loginEmail: '',
    loginPass: '',
    authErr: '',
    reg: { name: '', email: '', pass: '', phone: '', role: 'WARGA' as Role, koperasi: KOPERASI[0], anggota: '' },
    buat: { item: '', satuan: 'kg', qty: '', harga: '', deadline: '' },
    buatStep: 1 as 1 | 2,
    buatDraft: null as string | null,
    buatErr: '',
    dpMethod: 'TRANSFER' as 'TRANSFER' | 'CASH',
    titip: { item: '', satuan: 'kg', qty: '', harga: '' },
    titipErr: '',
    toast: '',
  }),

  getters: {
    cu: (s): User | null => s.user,
    isLoggedIn: (s) => !!s.user,
    isGuest: (s) => !s.user,
    isBuyer: (s) => s.user?.role === 'BUYER',
    isWarga: (s) => s.user?.role === 'WARGA',
    isAdmin: (s) => s.user?.role === 'ADMIN_KOPERASI',
    userInitial: (s) => (s.user ? initial(s.user.name) : ''),
    userRoleLabel: (s) => (s.user ? ROLE_LABEL[s.user.role] : ''),
    userVerLabel: (s) =>
      s.user ? (s.user.ver === 'VERIFIED' ? 'Terverifikasi ✓' : 'Menunggu verifikasi') : '',
  },

  actions: {
    showToast(msg: string) {
      clearTimeout(toastTimer)
      this.toast = msg
      toastTimer = setTimeout(() => (this.toast = ''), 3000)
    },
    closeModal() {
      this.modal = null
      this.modErr = ''
      this.activeListingId = null
      this.activeKycId = null
      this.listEditErr = ''
    },

    // ---- etalase: lihat / edit / hapus listing ----
    viewListing(id: string) {
      this.activeListingId = id
      this.modal = 'listingView'
    },
    startEditListing(id: string) {
      const l = this.listings.find((x) => x.id === id)
      if (!l) return
      this.activeListingId = id
      this.listEdit = { avail: String(l.avail), harga: String(l.harga), status: l.status }
      this.listEditErr = ''
      this.modal = 'listingEdit'
    },
    saveListing() {
      const l = this.listings.find((x) => x.id === this.activeListingId)
      if (!l) return
      const avail = parseFloat(this.listEdit.avail)
      const harga = parseFloat(this.listEdit.harga)
      if (!(avail >= 0) || !(harga > 0)) {
        this.listEditErr = 'Stok harus ≥ 0 dan harga harus lebih dari 0.'
        return
      }
      if (avail < l.sold) {
        this.listEditErr = `Stok tidak boleh kurang dari yang sudah terjual (${l.sold}).`
        return
      }
      l.avail = avail
      l.harga = harga
      l.status = this.listEdit.status
      this.closeModal()
      this.showToast('Listing etalase diperbarui.')
    },
    deleteListing(id: string) {
      const l = this.listings.find((x) => x.id === id)
      if (!l) return
      if (import.meta.client && !window.confirm(`Hapus "${l.item_name}" dari etalase?`)) return
      this.listings = this.listings.filter((x) => x.id !== id)
      this.showToast('Listing dihapus dari etalase.')
    },

    // ---- auth ----
    login(user: User) {
      this.user = user
      this.authErr = ''
      this.loginEmail = ''
      this.loginPass = ''
      this.resume(user)
    },
    resume(user: User) {
      const p = this.pending
      this.modal = null
      if (!p) {
        navigateTo(routeFor(user.role === 'ADMIN_KOPERASI' ? 'dash' : 'pasar'))
        this.showToast(`Selamat datang, ${user.name}!`)
        return
      }
      this.pending = null
      if (p.type === 'pledge') {
        if (user.role === 'WARGA') {
          navigateTo(routeFor('demand', p.id))
          this.modal = 'pledge'
          this.pledgeQty = ''
          this.pledgePrice = ''
        } else {
          navigateTo(routeFor('demand', p.id))
          this.showToast('Menyanggupi khusus peran Warga Desa.')
        }
      } else if (p.type === 'order') {
        if (user.role === 'BUYER') {
          navigateTo(routeFor('listing', p.id))
          this.modal = 'order'
          this.orderQty = ''
        } else {
          navigateTo(routeFor('listing', p.id))
          this.showToast('Memesan khusus peran Pembeli.')
        }
      } else if (p.type === 'buat') {
        if (user.role === 'BUYER') {
          this.buatStep = 1
          navigateTo(routeFor('buat'))
        } else {
          navigateTo(routeFor('pasar'))
          this.showToast('Membuat permintaan khusus peran Pembeli.')
        }
      } else {
        navigateTo(routeFor('pasar'))
      }
    },
    doLogout() {
      this.user = null
      navigateTo(routeFor('pasar'))
      this.showToast('Kamu telah keluar. Sampai jumpa!')
    },
    submitLogin() {
      const e = this.loginEmail.toLowerCase()
      if (!e || !this.loginPass) {
        this.authErr = 'Isi email dan kata sandi dulu, ya.'
        return
      }
      let user: User
      if (e.includes('budi')) user = USERS.budi
      else if (e.includes('wati')) user = USERS.wati
      else if (e.includes('darto') || e.includes('koperasi')) user = USERS.darto
      else user = { id: 'baru', name: e.split('@')[0], role: 'BUYER', ver: 'PENDING' }
      this.login(user)
    },
    submitReg() {
      const r = this.reg
      if (!r.name || !r.email) {
        this.authErr = 'Nama dan email wajib diisi.'
        return
      }
      if ((r.pass || '').length < 6) {
        this.authErr = 'Kata sandi minimal 6 karakter.'
        return
      }
      const user: User = { id: 'baru', name: r.name, role: r.role, ver: 'PENDING' }
      this.showToast('Akun dibuat! Status verifikasi: Menunggu.')
      this.login(user)
    },
    demo(kind: 'budi' | 'wati' | 'darto') {
      this.login(USERS[kind])
    },

    // ---- gerbang aksi (auth-gate) ----
    ctaPledge(demandId: string) {
      const u = this.user
      if (!u) {
        this.pending = { type: 'pledge', id: demandId }
        this.modal = 'auth'
        return
      }
      if (u.role !== 'WARGA') {
        this.showToast('Menyanggupi khusus peran Warga Desa.')
        return
      }
      this.modal = 'pledge'
      this.modErr = ''
      this.pledgeQty = ''
      this.pledgePrice = ''
    },
    ctaOrder(listingId: string) {
      const u = this.user
      if (!u) {
        this.pending = { type: 'order', id: listingId }
        this.modal = 'auth'
        return
      }
      if (u.role !== 'BUYER') {
        this.showToast('Memesan khusus peran Pembeli.')
        return
      }
      this.modal = 'order'
      this.modErr = ''
      this.orderQty = ''
    },
    ctaBuat() {
      const u = this.user
      if (!u) {
        this.pending = { type: 'buat' }
        this.modal = 'auth'
        return
      }
      if (u.role !== 'BUYER') {
        this.showToast('Membuat permintaan khusus peran Pembeli.')
        return
      }
      this.buatStep = 1
      this.buatErr = ''
      navigateTo(routeFor('buat'))
    },

    // ---- pledge / order ----
    submitPledge(demandId: string) {
      const d = this.demands.find((x) => x.id === demandId)
      if (!d) return
      const qty = parseFloat(this.pledgeQty) || 0
      if (qty <= 0) {
        this.modErr = 'Isi jumlah yang disanggupi.'
        return
      }
      const sisa = d.total - d.fulfilled
      if (qty > sisa) {
        this.modErr = `Melebihi sisa kebutuhan (${fmtN(sisa)} ${d.satuan}). Server akan menolak permintaan ini (409).`
        return
      }
      const u = this.user!
      const nf = d.fulfilled + qty
      d.fulfilled = nf
      d.status = nf >= d.total ? 'FULFILLED' : 'PARTIAL'
      d.pledges.push({ name: u.name, q: qty, d: 0, st: 'PLEDGED' })
      this.pledges.push({ id: 'p' + Date.now(), owner: u.id, demandId: d.id, item: d.item_name, qty, satuan: d.satuan, status: 'PLEDGED' })
      this.closeModal()
      this.showToast('Kesanggupan terkirim — terima kasih sudah bergotong royong!')
    },
    submitOrder(listingId: string) {
      const l = this.listings.find((x) => x.id === listingId)
      if (!l) return
      const qty = parseFloat(this.orderQty) || 0
      if (qty <= 0) {
        this.modErr = 'Isi jumlah pesanan.'
        return
      }
      const stok = l.avail - l.sold
      if (qty > stok) {
        this.modErr = `Melebihi stok tersedia (${fmtN(stok)} ${l.satuan}). Server akan menolak pesanan ini (409).`
        return
      }
      const u = this.user!
      l.sold += qty
      this.orders.push({ id: 'o' + Date.now(), owner: u.id, listingId: l.id, item: l.item_name, qty, satuan: l.satuan, harga: l.harga, status: 'BARU' })
      this.closeModal()
      this.showToast(`Pesanan dibuat! Harga dikunci di ${fmtRp(l.harga)}/${l.satuan}.`)
    },

    // ---- buat permintaan ----
    submitBuat() {
      const b = this.buat
      const qty = parseFloat(b.qty) || 0
      const harga = parseFloat(b.harga) || 0
      if (!b.item.trim()) {
        this.buatErr = 'Nama komoditas wajib diisi.'
        return
      }
      if (qty <= 0 || harga <= 0) {
        this.buatErr = 'Jumlah dan harga target harus lebih dari 0.'
        return
      }
      const u = this.user!
      const id = 'd' + Date.now()
      this.demands.push({
        id, owner: u.id, item_name: b.item.trim(), satuan: b.satuan.trim() || 'kg',
        total: qty, fulfilled: 0, harga, deadline: b.deadline || null,
        status: 'DRAFT', dp: 'UNPAID', method: null, pledges: [], kandidat: [],
      })
      this.buatDraft = id
      this.buatStep = 2
      this.buatErr = ''
    },
    confirmDp() {
      const draftId = this.buatDraft
      const d = this.demands.find((x) => x.id === draftId)
      if (d) {
        d.status = 'OPEN'
        d.dp = 'PAID'
        d.method = this.dpMethod
      }
      this.buat = { item: '', satuan: 'kg', qty: '', harga: '', deadline: '' }
      this.buatStep = 1
      navigateTo(routeFor('demand', draftId!))
      this.showToast('DP dikonfirmasi — permintaanmu kini tampil publik!')
    },
    saveDraft() {
      navigateTo(routeFor('saya'))
      this.showToast('Disimpan sebagai draf — bayar DP kapan saja dari Aktivitasku.')
    },

    // ---- titip komoditas ----
    submitTitip() {
      const t = this.titip
      const qty = parseFloat(t.qty) || 0
      const harga = parseFloat(t.harga) || 0
      if (!t.item.trim()) {
        this.titipErr = 'Nama komoditas wajib diisi.'
        return
      }
      if (qty <= 0 || harga <= 0) {
        this.titipErr = 'Stok dan harga harus lebih dari 0.'
        return
      }
      const u = this.user!
      this.listings.push({
        id: 'l' + Date.now(), owner: u.id, seller: u.name, item_name: t.item.trim(),
        satuan: t.satuan.trim() || 'kg', avail: qty, sold: 0, harga, status: 'ACTIVE',
        tanggal: new Date().toISOString().slice(0, 10),
      })
      this.titip = { item: '', satuan: 'kg', qty: '', harga: '' }
      this.titipErr = ''
      navigateTo(routeFor('etalase'))
      this.showToast('Komoditasmu kini tampil di etalase koperasi!')
    },

    // ---- pembatalan / verifikasi ----
    cancelDemand(id: string) {
      const d = this.demands.find((x) => x.id === id)
      if (d) d.status = 'CANCELLED'
      this.showToast('Permintaan dibatalkan. Sesuai ketentuan, DP hangus (FORFEITED).')
    },
    cancelPledge(id: string) {
      const p = this.pledges.find((x) => x.id === id)
      if (p) {
        p.status = 'CANCELLED'
        const d = this.demands.find((x) => x.id === p.demandId)
        if (d) {
          d.fulfilled = Math.max(0, d.fulfilled - p.qty)
          const uname = this.user?.name ?? ''
          const idx = d.pledges.findIndex((pl) => pl.name === uname && pl.q === p.qty && pl.st === 'PLEDGED')
          if (idx >= 0) d.pledges.splice(idx, 1)
        }
      }
      this.showToast('Kesanggupan dibatalkan.')
    },
    setOrder(id: string, status: string, msg: string) {
      const o = this.orders.find((x) => x.id === id)
      if (o) o.status = status
      this.showToast(msg)
    },
    verifyOrder(id: string) {
      const o = this.orders.find((x) => x.id === id)
      if (o) {
        o.status = 'DONE'
        this.txns.push({ id: 't' + Date.now(), kind: 'supply', item: o.item, pihak: `— → ${this.user?.name ?? ''}`, gross: o.qty * o.harga, pay: 'UNPAID' })
      }
      this.showToast('Serah-terima tercatat. Komisi koperasi 5% masuk pembukuan.')
    },
    advanceTxn(id: string, pay: string) {
      const t = this.txns.find((x) => x.id === id)
      if (t) t.pay = pay
      this.showToast(pay === 'PAID' ? 'Transaksi ditandai Dibayar.' : 'Transaksi ditandai Selesai.')
    },
    kycAct(id: string, ok: boolean) {
      // Perbarui status (tidak dihapus) — bisa di-toggle terdaftar ⇄ ditolak.
      const k = this.kyc.find((x) => x.id === id)
      if (k) k.status = ok ? 'VERIFIED' : 'REJECTED'
      if (this.modal === 'kycView') this.closeModal()
      this.showToast(ok ? 'Anggota diverifikasi — status: Terdaftar ✓' : 'Anggota ditolak — status: Ditolak.')
    },
    viewKyc(id: string) {
      this.activeKycId = id
      this.modal = 'kycView'
    },

    // ---- pengaturan batas harga komoditas ----
    savePriceCaps() {
      this.showToast('Batas harga komoditas diperbarui.')
    },
    addPriceCap() {
      this.priceCaps.push({ id: 'pc' + Date.now(), komoditas: '', satuan: 'kg', maxJual: 0, maxBeli: 0 })
    },
    removePriceCap(id: string) {
      this.priceCaps = this.priceCaps.filter((x) => x.id !== id)
    },
  },
})
