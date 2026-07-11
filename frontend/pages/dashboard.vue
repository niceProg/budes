<script setup lang="ts">
import { useApp } from '~/stores/app'
import { PAY_BADGE, badge } from '~/utils/badges'

const app = useApp()
onMounted(() => {
  if (!app.isAdmin) return navigateTo('/')
  // Muat ulang data admin agar dashboard tak pernah kosong (mis. sesi lama / hydrate terlewat).
  app.hydratePembukuan()
  app.hydrateInsights()
  app.refreshUser()
})

const NEXT: Record<string, [string, string]> = {
  UNPAID: ['PAID', 'Tandai Dibayar'],
  PAID: ['SETTLED', 'Tandai Selesai'],
}

const feeRate = computed(() => app.commissionPct / 100)

const txnList = computed(() =>
  app.txns.map((t) => {
    const isDemand = t.kind === 'demand'
    const next = NEXT[t.pay]
    return {
      id: t.id,
      item: t.item,
      subLine: `${fmtRp(t.gross)} · ${t.pihak}`,
      kindLabel: isDemand ? 'Permintaan' : 'Etalase',
      kindCls: isDemand ? 'bg-gold-50 text-gold-700' : 'bg-grape-100 text-grape-700',
      feeTxt: fmtRp(t.gross * feeRate.value),
      pay: badge(PAY_BADGE, t.pay, 'UNPAID'),
      nextPay: next ? next[0] : null,
      actionLabel: next ? next[1] : '',
    }
  }),
)
const totGross = computed(() => app.txns.reduce((a, t) => a + t.gross, 0))
const totFee = computed(() => totGross.value * feeRate.value)
const unpaidCount = computed(() => app.txns.filter((t) => t.pay === 'UNPAID').length)

// Angka akumulatif dari pembukuan backend (real); fallback ke hitung dari txns.
const komisiTotal = computed(() => app.pembukuan?.total_komisi ?? totFee.value)
const grossTotal = computed(() => app.pembukuan?.total_gross ?? totGross.value)
const txnCount = computed(() => app.pembukuan?.jumlah_transaksi ?? app.txns.length)

const ins = computed(() => app.insights)
const wargaNet = computed(() => Math.max(0, grossTotal.value - komisiTotal.value))
</script>

<template>
  <div class="mx-auto max-w-page px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Dashboard Koperasi</h2>
    <p class="mb-5 text-[13.5px] text-sand-700">KDMP Desa Sukamaju — ringkasan pembukuan & transaksi koperasi.</p>

    <!-- stat tiles -->
    <div class="mb-5 grid gap-3.5" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))">
      <div class="card p-5">
        <div class="mb-1 text-xs font-bold text-sand-600">Total Komisi Koperasi</div>
        <div class="text-[23px] font-extrabold text-clay-600">{{ fmtRp(komisiTotal) }}</div>
      </div>
      <div class="card p-5">
        <div class="mb-1 text-xs font-bold text-sand-600">Total Nilai Transaksi</div>
        <div class="text-[23px] font-extrabold">{{ fmtRp(grossTotal) }}</div>
      </div>
      <div class="card p-5">
        <div class="mb-1 text-xs font-bold text-sand-600">Jumlah Transaksi</div>
        <div class="text-[23px] font-extrabold text-navy-800">{{ txnCount }}</div>
      </div>
      <div class="card p-5">
        <div class="mb-1 text-xs font-bold text-sand-600">Menunggu Pembayaran</div>
        <div class="text-[23px] font-extrabold text-warning-700">{{ unpaidCount }}</div>
      </div>
    </div>

    <!-- insight pasar -->
    <section v-if="ins" class="mb-4 grid gap-3.5" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr))">
      <!-- Permintaan -->
      <div class="card p-5">
        <div class="mb-3 flex items-center justify-between">
          <h3 class="text-[14px] font-extrabold">Permintaan (Demand)</h3>
          <span class="text-[22px] font-extrabold text-clay-600">{{ ins.demand_total }}</span>
        </div>
        <div class="flex flex-col gap-1.5 text-[12.5px]">
          <div class="flex justify-between"><span class="text-sand-600">Aktif (dibuka + sebagian)</span><span class="font-bold text-success-700">{{ (ins.demand_by_status.OPEN || 0) + (ins.demand_by_status.PARTIAL || 0) }}</span></div>
          <div class="flex justify-between"><span class="text-sand-600">Terpenuhi</span><span class="font-bold">{{ ins.demand_by_status.FULFILLED || 0 }}</span></div>
          <div class="flex justify-between"><span class="text-sand-600">Nilai total permintaan</span><span class="font-bold">{{ fmtRp(ins.demand_value) }}</span></div>
        </div>
      </div>

      <!-- Etalase / terlaris -->
      <div class="card p-5">
        <div class="mb-3 flex items-center justify-between">
          <h3 class="text-[14px] font-extrabold">Etalase (Supply)</h3>
          <span class="text-[22px] font-extrabold text-grape-700">{{ ins.listing_total }}</span>
        </div>
        <div class="mb-2 flex justify-between text-[12.5px]">
          <span class="text-sand-600">Item laris (pernah terjual)</span>
          <span class="font-bold text-success-700">{{ ins.laris_count }} item · {{ ins.supply_sold }} unit</span>
        </div>
        <div v-if="ins.top_selling.length" class="mt-2 border-t border-sand-200 pt-2">
          <div class="mb-1.5 text-[11px] font-bold uppercase tracking-wide text-sand-600">Terlaris</div>
          <div v-for="(t, i) in ins.top_selling" :key="i" class="flex items-center justify-between py-0.5 text-[12.5px]">
            <span class="font-bold">{{ i + 1 }}. {{ t.item_name }}</span>
            <span class="text-sand-700">{{ t.sold }} {{ t.satuan || '' }} · {{ fmtRp(t.revenue) }}</span>
          </div>
        </div>
        <p v-else class="text-[12px] text-sand-600">Belum ada penjualan.</p>
      </div>

      <!-- Bagi hasil -->
      <div class="card p-5">
        <h3 class="mb-3 text-[14px] font-extrabold">Bagi Hasil Transaksi</h3>
        <div class="mb-3 flex h-2.5 overflow-hidden rounded-full bg-sand-200">
          <div class="bg-clay-600" :style="{ width: (grossTotal ? (komisiTotal / grossTotal * 100) : 0) + '%' }"></div>
          <div class="bg-success-600" :style="{ width: (grossTotal ? (wargaNet / grossTotal * 100) : 0) + '%' }"></div>
        </div>
        <div class="flex flex-col gap-1.5 text-[12.5px]">
          <div class="flex justify-between">
            <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-clay-600"></span>Koperasi (komisi {{ app.commissionPct }}%)</span>
            <span class="font-extrabold text-clay-600">{{ fmtRp(komisiTotal) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-success-600"></span>Warga (net penjual)</span>
            <span class="font-extrabold text-success-700">{{ fmtRp(wargaNet) }}</span>
          </div>
          <div class="flex justify-between border-t border-sand-200 pt-1.5"><span class="text-sand-600">Total nilai transaksi</span><span class="font-bold">{{ fmtRp(grossTotal) }}</span></div>
        </div>
      </div>
    </section>

    <!-- transaksi -->
    <section class="card mb-4 p-[22px]">
      <h3 class="mb-3.5 text-[15.5px] font-extrabold">Transaksi</h3>
      <div class="overflow-x-auto">
        <div class="min-w-[560px]">
          <div class="grid gap-2.5 border-b border-sand-200 px-3 py-2 text-[11px] font-extrabold uppercase tracking-[0.04em] text-sand-600" style="grid-template-columns: 100px 1.8fr 1fr 190px">
            <span>Alur</span><span>Komoditas</span><span>Komisi {{ app.commissionPct }}%</span><span>Status</span>
          </div>
          <div
            v-for="t in txnList"
            :key="t.id"
            class="grid items-center gap-2.5 border-b border-sand-150 px-3 py-[11px] text-[13px]"
            style="grid-template-columns: 100px 1.8fr 1fr 190px"
          >
            <span class="badge justify-center text-center" :class="t.kindCls">{{ t.kindLabel }}</span>
            <div>
              <div class="font-bold">{{ t.item }}</div>
              <div class="text-[11px] text-sand-600">{{ t.subLine }}</div>
            </div>
            <span class="font-bold text-clay-600">{{ t.feeTxt }}</span>
            <div class="flex flex-wrap items-center gap-2">
              <StatusBadge :badge="t.pay" />
              <button
                v-if="t.nextPay"
                class="btn-soft whitespace-nowrap rounded-lg px-2.5 py-[5px] text-[11px]"
                @click="app.advanceTxn(t.id, t.nextPay)"
              >{{ t.actionLabel }}</button>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>
