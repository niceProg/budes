<script setup lang="ts">
import { useApp } from '~/stores/app'
import { decorateListing } from '~/utils/decorate'

const app = useApp()
const route = useRoute()
const raw = computed(() => app.listings.find((l) => l.id === route.params.id) || app.listings[0])
const lst = computed(() => decorateListing(raw.value))
</script>

<template>
  <div class="mx-auto max-w-[980px] px-5 pb-16 pt-[22px]">
    <NuxtLink to="/etalase" class="mb-4 inline-block text-[13.5px] font-bold text-clay-600 hover:underline">← Etalase</NuxtLink>

    <div class="flex flex-wrap items-start gap-[18px]">
      <div class="card min-w-[300px] flex-[1.6] p-[26px]">
        <div class="mb-4 flex flex-wrap items-center gap-2.5">
          <h1 class="text-[26px] font-extrabold leading-tight">{{ lst.item_name }}</h1>
          <StatusBadge :badge="lst.badge" />
        </div>
        <div class="grid gap-3.5" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr))">
          <div class="rounded-xl border border-sand-200 p-[13px]">
            <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Stok tersedia</div>
            <div class="text-[15px] font-extrabold">{{ lst.stokTxt }}</div>
          </div>
          <div class="rounded-xl border border-sand-200 p-[13px]">
            <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Sudah terjual</div>
            <div class="text-[15px] font-extrabold">{{ lst.terjualTxt }}</div>
          </div>
          <div class="rounded-xl border border-sand-200 p-[13px]">
            <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Penitip</div>
            <div class="text-[15px] font-extrabold">{{ lst.seller }}</div>
          </div>
          <div class="rounded-xl border border-sand-200 p-[13px]">
            <div class="mb-0.5 text-[11px] font-semibold text-sand-600">Masuk etalase</div>
            <div class="text-[15px] font-extrabold">{{ lst.tglTxt }}</div>
          </div>
        </div>
      </div>

      <div class="card min-w-[270px] flex-1 p-6">
        <div class="mb-0.5 text-xs font-semibold text-sand-600">Harga per {{ lst.satuan }}</div>
        <div class="mb-3.5 text-[28px] font-extrabold text-clay-600">{{ lst.hargaTxt }}</div>
        <p class="mb-4 text-[12.5px] leading-relaxed text-sand-700">
          Harga dikunci saat pesanan dibuat. Serah-terima dan pembayaran difasilitasi koperasi desa (komisi 5%).
        </p>
        <button class="btn-primary btn-block py-3.5 text-[14.5px]" @click="app.ctaOrder(lst.id)">Pesan Sekarang</button>
      </div>
    </div>
  </div>
</template>
