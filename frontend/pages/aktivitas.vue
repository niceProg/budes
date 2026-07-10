<script setup lang="ts">
import { useApp } from '~/stores/app'
import { decorateDemand, decorateListing } from '~/utils/decorate'
import { ORDER_BADGE, PLEDGE_BADGE, badge } from '~/utils/badges'

const app = useApp()
onMounted(() => {
  if (!app.isLoggedIn) navigateTo('/masuk')
})

const uid = computed(() => app.user?.id ?? '__none')

const myDemands = computed(() =>
  app.demands
    .filter((d) => d.owner === uid.value)
    .map((d) => ({ ...decorateDemand(d), canCancel: ['DRAFT', 'OPEN', 'PARTIAL'].includes(d.status) })),
)
const myOrders = computed(() =>
  app.orders
    .filter((o) => o.owner === uid.value)
    .map((o) => ({
      ...o,
      line: `${fmtN(o.qty)} ${o.satuan} · ${fmtRp(o.qty * o.harga)}`,
      badge: badge(ORDER_BADGE, o.status, 'BARU'),
      canCancel: o.status === 'BARU',
      canVerify: o.status === 'CONFIRMED',
    })),
)
const myPledges = computed(() =>
  app.pledges
    .filter((p) => p.owner === uid.value)
    .map((p) => ({
      ...p,
      line: `${fmtN(p.qty)} ${p.satuan}`,
      badge: badge(PLEDGE_BADGE, p.status, 'PLEDGED'),
      canCancel: p.status === 'PLEDGED',
    })),
)
const myListings = computed(() =>
  app.listings.filter((l) => l.owner === uid.value).map((l) => decorateListing(l)),
)
</script>

<template>
  <div class="mx-auto max-w-[900px] px-5 pb-16 pt-7">
    <h2 class="mb-1 text-[22px] font-extrabold">Aktivitasku</h2>
    <p class="mb-5 text-[13.5px] text-sand-700">Riwayat dan status semua aksimu di Bursa Desa.</p>

    <!-- PEMBELI -->
    <template v-if="app.isBuyer">
      <section class="card mb-4 p-[22px]">
        <h3 class="mb-3.5 text-[15.5px] font-extrabold">Permintaanku</h3>
        <div v-if="myDemands.length" class="flex flex-col gap-2.5">
          <div v-for="d in myDemands" :key="d.id" class="flex flex-wrap items-center gap-3 rounded-xl border border-sand-200 px-[15px] py-3">
            <NuxtLink :to="`/permintaan/${d.id}`" class="min-w-[170px] flex-1 cursor-pointer">
              <div class="text-[13.5px] font-bold">{{ d.item_name }}</div>
              <div class="text-xs text-sand-700">{{ d.qtyLine }} · DP {{ d.dpStatusShort }}</div>
            </NuxtLink>
            <StatusBadge :badge="d.badge" />
            <button v-if="d.canCancel" class="btn-cancel" @click="app.cancelDemand(d.id)">Batalkan</button>
          </div>
        </div>
        <p v-else class="text-[13px] text-sand-700">
          Belum ada permintaan. <button class="link" @click="app.ctaBuat()">Buat sekarang →</button>
        </p>
      </section>

      <section class="card mb-4 p-[22px]">
        <h3 class="mb-3.5 text-[15.5px] font-extrabold">Pesananku</h3>
        <div v-if="myOrders.length" class="flex flex-col gap-2.5">
          <div v-for="o in myOrders" :key="o.id" class="flex flex-wrap items-center gap-3 rounded-xl border border-sand-200 px-[15px] py-3">
            <div class="min-w-[170px] flex-1">
              <div class="text-[13.5px] font-bold">{{ o.item }}</div>
              <div class="text-xs text-sand-700">{{ o.line }}</div>
            </div>
            <StatusBadge :badge="o.badge" />
            <button v-if="o.canVerify" class="btn-success rounded-[9px] px-3 py-[7px] text-[11.5px]" @click="app.verifyOrder(o.id)">Konfirmasi Diterima</button>
            <button v-if="o.canCancel" class="btn-cancel" @click="app.setOrder(o.id, 'CANCELLED', 'Pesanan dibatalkan.')">Batalkan</button>
          </div>
        </div>
        <p v-else class="text-[13px] text-sand-700">
          Belum ada pesanan. <NuxtLink to="/etalase" class="link">Jelajahi etalase →</NuxtLink>
        </p>
      </section>
    </template>

    <!-- WARGA -->
    <template v-if="app.isWarga">
      <section class="card mb-4 p-[22px]">
        <h3 class="mb-3.5 text-[15.5px] font-extrabold">Kesanggupanku</h3>
        <div v-if="myPledges.length" class="flex flex-col gap-2.5">
          <div v-for="p in myPledges" :key="p.id" class="flex flex-wrap items-center gap-3 rounded-xl border border-sand-200 px-[15px] py-3">
            <div class="min-w-[170px] flex-1">
              <div class="text-[13.5px] font-bold">{{ p.item }}</div>
              <div class="text-xs text-sand-700">{{ p.line }}</div>
            </div>
            <StatusBadge :badge="p.badge" />
            <button v-if="p.canCancel" class="btn-cancel" @click="app.cancelPledge(p.id)">Batalkan</button>
          </div>
        </div>
        <p v-else class="text-[13px] text-sand-700">
          Belum ada kesanggupan. <NuxtLink to="/" class="link">Lihat permintaan pembeli →</NuxtLink>
        </p>
      </section>

      <section class="card mb-4 p-[22px]">
        <h3 class="mb-3.5 text-[15.5px] font-extrabold">Titipanku</h3>
        <div v-if="myListings.length" class="flex flex-col gap-2.5">
          <NuxtLink v-for="l in myListings" :key="l.id" :to="`/etalase/${l.id}`" class="flex flex-wrap items-center gap-3 rounded-xl border border-sand-200 px-[15px] py-3">
            <div class="min-w-[170px] flex-1">
              <div class="text-[13.5px] font-bold">{{ l.item_name }}</div>
              <div class="text-xs text-sand-700">{{ l.stokLine }} · {{ l.hargaTxt }}/{{ l.satuan }}</div>
            </div>
            <StatusBadge :badge="l.badge" />
          </NuxtLink>
        </div>
        <p v-else class="text-[13px] text-sand-700">
          Belum ada titipan. <NuxtLink to="/titip" class="link">Titipkan komoditas →</NuxtLink>
        </p>
      </section>
    </template>
  </div>
</template>

<style scoped>
.btn-cancel {
  @apply cursor-pointer rounded-[9px] border-[1.5px] border-clay-200 bg-white px-3 py-1.5 text-[11.5px] font-bold text-clay-800 transition hover:bg-clay-100;
}
.link { @apply cursor-pointer font-bold text-clay-600; }
</style>
