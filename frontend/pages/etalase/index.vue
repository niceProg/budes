<script setup lang="ts">
import { useApp } from '~/stores/app'
const app = useApp()
</script>

<template>
  <div class="mx-auto max-w-page px-5 pb-16 pt-7">
    <div class="mb-[18px]">
      <h2 class="mb-1 text-[22px] font-extrabold">Etalase Koperasi</h2>
      <p class="text-[13.5px] text-sand-700">Komoditas titipan warga yang siap dipesan langsung.</p>
    </div>
    <div class="grid gap-4" style="grid-template-columns: repeat(auto-fill, minmax(250px, 1fr))">
      <ListingCard v-for="l in app.listings" :key="l.id" :l="l" />
    </div>
  </div>
</template>
