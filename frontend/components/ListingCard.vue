<script setup lang="ts">
import { decorateListing, type Listing } from '~/utils/decorate'
const props = defineProps<{ l: Listing }>()
const v = computed(() => decorateListing(props.l))
</script>

<template>
  <NuxtLink :to="`/etalase/${l.id}`" class="card card-hover block cursor-pointer p-[18px]">
    <div class="mb-1.5 flex items-start justify-between gap-2.5">
      <div class="text-base font-extrabold leading-tight">{{ v.item_name }}</div>
      <StatusBadge :badge="v.badge" class="shrink-0" />
    </div>
    <div class="mb-3.5 text-[12.5px] text-sand-700">{{ v.stokLine }}</div>
    <div class="mb-3.5 text-[19px] font-extrabold text-clay-600">
      {{ v.hargaTxt }}<span class="text-xs font-semibold text-sand-600"> /{{ v.satuan }}</span>
    </div>
    <div class="flex items-center justify-between border-t border-dashed border-sand-400 pt-[11px] text-[11.5px] text-sand-700">
      <span class="font-semibold">{{ v.seller }}</span>
      <span>{{ v.tglTxt }}</span>
    </div>
  </NuxtLink>
</template>
