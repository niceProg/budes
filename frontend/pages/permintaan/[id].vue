<script setup lang="ts">
import { useApp } from '~/stores/app'
import { decorateDemand, initial } from '~/utils/decorate'
import { PLEDGE_BADGE, badge } from '~/utils/badges'

const app = useApp()
const route = useRoute()

// Ambil detail (termasuk daftar penyanggup) dari API saat halaman dibuka.
onMounted(() => app.loadDemand(route.params.id as string))

const raw = computed(() => app.demands.find((d) => d.id === route.params.id) || app.demands[0])
const det = computed(() => decorateDemand(raw.value))
const pledges = computed(() =>
  raw.value.pledges.map((p) => ({
    name: p.name,
    initial: initial(p.name),
    line: `${fmtN(p.q)} ${raw.value.satuan} disanggupi · ${fmtN(p.d)} ${raw.value.satuan} diserahkan`,
    badge: badge(PLEDGE_BADGE, p.st, 'PLEDGED'),
  })),
)
</script>

<template>
  <div class="mx-auto max-w-page px-5 pb-16 pt-[22px]">
    <NuxtLink to="/" class="mb-4 inline-block text-[13.5px] font-bold text-clay-600 hover:underline">← Jelajah Pasar</NuxtLink>

    <div class="grid items-start gap-[18px] lg:grid-cols-[1.7fr_1fr]">
      <!-- kolom kiri -->
      <div class="flex min-w-0 flex-col gap-[18px]">
        <div class="card p-[26px]">
          <div class="mb-1.5 flex flex-wrap items-center gap-2.5">
            <h1 class="text-[26px] font-extrabold leading-tight">{{ det.item_name }}</h1>
            <StatusBadge :badge="det.badge" />
            <span v-if="det.dpPaid" class="badge border border-success-200 bg-success-50 text-success-600">✓ DP Terbayar</span>
          </div>
          <div class="mb-4 text-sm text-sand-700">{{ det.qtyLine }} · tenggat {{ det.tglTxt }} ({{ det.hariTxt }})</div>
          <div class="mb-1.5 flex justify-between text-[13px]">
            <span class="font-extrabold text-success-600">{{ det.pct }}% tersanggupi</span>
            <span class="text-sand-700">sisa {{ det.sisaKuotaTxt }}</span>
          </div>
          <ProgressBar :pct="det.pct" height="h-3" />
        </div>

        <div class="card p-[26px]">
          <h3 class="mb-3.5 text-base font-extrabold">Penyanggup ({{ det.pledgeCount }})</h3>
          <div v-if="pledges.length" class="flex flex-col gap-2.5">
            <div v-for="(p, i) in pledges" :key="i" class="flex items-center gap-3 rounded-xl border border-sand-200 px-3.5 py-[11px]">
              <div class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-gold-50 text-[13px] font-extrabold text-gold-700">{{ p.initial }}</div>
              <div class="min-w-0 flex-1">
                <div class="text-[13.5px] font-bold">{{ p.name }}</div>
                <div class="text-xs text-sand-700">{{ p.line }}</div>
              </div>
              <StatusBadge :badge="p.badge" class="shrink-0" />
            </div>
          </div>
          <p v-else-if="!app.isAdmin" class="text-[13.5px] text-sand-700">
            Belum ada penyanggup. Jadilah yang pertama bergotong royong memenuhi kebutuhan ini.
          </p>
        </div>
      </div>

      <!-- kolom kanan -->
      <div class="flex min-w-0 flex-col gap-[18px]">
        <div class="card p-6">
          <h3 class="mb-3.5 text-[15px] font-extrabold">Rincian Pembayaran</h3>
          <div class="flex justify-between border-b border-dashed border-sand-400 py-2 text-[13.5px]">
            <span class="text-sand-700">Nilai total</span><span class="font-bold">{{ det.totalTxt }}</span>
          </div>
          <div class="flex justify-between border-b border-dashed border-sand-400 py-2 text-[13.5px]">
            <span class="text-sand-700">Uang muka (30%)</span><span class="font-extrabold text-clay-600">{{ det.dpTxt }}</span>
          </div>
          <div class="flex justify-between border-b border-dashed border-sand-400 py-2 text-[13.5px]">
            <span class="text-sand-700">Sisa pembayaran</span><span class="font-bold">{{ det.sisaTxt }}</span>
          </div>
          <div class="flex justify-between py-2 text-[13.5px]">
            <span class="text-sand-700">Status DP</span><span class="font-bold">{{ det.dpStatusLabel }}</span>
          </div>
        </div>

        <div v-if="!app.isBuyer && !app.isAdmin" class="card p-6">
          <h3 class="mb-1.5 text-base font-extrabold">Punya hasil panen ini?</h3>
          <template v-if="det.status === 'OPEN' || det.status === 'PARTIAL'">
            <p class="mb-4 text-[13px] leading-relaxed text-sand-700">
              Sanggupi sebagian atau seluruhnya — sisa kebutuhan {{ det.sisaKuotaTxt }}.
            </p>
            <button class="btn-primary btn-block py-3.5 text-[14.5px]" @click="app.ctaPledge(det.id)">Sanggupi Permintaan</button>
          </template>
          <p v-else-if="det.status === 'DRAFT'" class="text-[13px] leading-relaxed text-sand-700">
            ⏳ Menunggu pembayaran DP dari pembeli. Bisa disanggupi setelah permintaan aktif.
          </p>
          <p v-else class="text-[13px] leading-relaxed text-sand-700">Permintaan ini sudah tidak menerima sanggupan.</p>
        </div>
      </div>
    </div>
  </div>
</template>
