<script setup lang="ts">
import { useApp } from '~/stores/app'
import { PAY_BADGE, badge } from '~/utils/badges'

const app = useApp()
onMounted(() => {
  if (!app.isAdmin) navigateTo('/')
})

const NEXT: Record<string, [string, string]> = {
  UNPAID: ['PAID', 'Tandai Dibayar'],
  PAID: ['SETTLED', 'Tandai Selesai'],
}

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
      feeTxt: fmtRp(t.gross * 0.05),
      pay: badge(PAY_BADGE, t.pay, 'UNPAID'),
      nextPay: next ? next[0] : null,
      actionLabel: next ? next[1] : '',
    }
  }),
)
const totGross = computed(() => app.txns.reduce((a, t) => a + t.gross, 0))
const totFee = computed(() => totGross.value * 0.05)
const unpaidCount = computed(() => app.txns.filter((t) => t.pay === 'UNPAID').length)
</script>

<template>
  <div class="mx-auto max-w-page px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Dashboard Koperasi</h2>
    <p class="mb-5 text-[13.5px] text-sand-700">KDMP Desa Sukamaju — ringkasan pembukuan & transaksi koperasi.</p>

    <!-- stat tiles -->
    <div class="mb-5 grid gap-3.5" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))">
      <div class="card p-5">
        <div class="mb-1 text-xs font-bold text-sand-600">Total Komisi (5%)</div>
        <div class="text-[23px] font-extrabold text-clay-600">{{ fmtRp(totFee) }}</div>
      </div>
      <div class="card p-5">
        <div class="mb-1 text-xs font-bold text-sand-600">Total Nilai Transaksi</div>
        <div class="text-[23px] font-extrabold">{{ fmtRp(totGross) }}</div>
      </div>
      <div class="card p-5">
        <div class="mb-1 text-xs font-bold text-sand-600">Menunggu Pembayaran</div>
        <div class="text-[23px] font-extrabold text-warning-700">{{ unpaidCount }}</div>
      </div>
    </div>

    <!-- transaksi -->
    <section class="card mb-4 p-[22px]">
      <h3 class="mb-3.5 text-[15.5px] font-extrabold">Transaksi</h3>
      <div class="overflow-x-auto">
        <div class="min-w-[560px]">
          <div class="grid gap-2.5 border-b border-sand-200 px-3 py-2 text-[11px] font-extrabold uppercase tracking-[0.04em] text-sand-600" style="grid-template-columns: 100px 1.8fr 1fr 190px">
            <span>Alur</span><span>Komoditas</span><span>Komisi 5%</span><span>Status</span>
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
