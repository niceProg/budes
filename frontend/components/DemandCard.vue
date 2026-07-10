<script setup lang="ts">
import { decorateDemand, type Demand } from '~/utils/decorate'
const props = defineProps<{ d: Demand }>()
const v = computed(() => decorateDemand(props.d))
</script>

<template>
  <NuxtLink :to="`/permintaan/${d.id}`" class="card card-hover block cursor-pointer p-[18px]">
    <div class="mb-2 flex items-start justify-between gap-2.5">
      <div class="text-[16.5px] font-extrabold leading-tight">{{ v.item_name }}</div>
      <StatusBadge :badge="v.badge" class="shrink-0" />
    </div>
    <div class="mb-2 text-[13px] text-sand-700">{{ v.qtyLine }}</div>

    <ProgressBar :pct="v.pct" />
    <div class="mb-3 mt-1.5 flex justify-between text-xs text-sand-700">
      <span>{{ v.pct }}% tersanggupi</span>
      <span>{{ v.hariTxt }}</span>
    </div>

    <div class="flex items-center justify-between border-t border-dashed border-sand-400 pt-3">
      <div>
        <div class="text-[11px] font-semibold text-sand-600">Harga target</div>
        <div class="text-[15px] font-extrabold text-clay-600">
          {{ v.hargaTxt }}<span class="text-[11.5px] font-semibold text-sand-600"> /{{ v.satuan }}</span>
        </div>
      </div>
      <span
        v-if="v.dpPaid"
        class="badge border border-success-200 bg-success-50 text-success-600"
      >✓ DP Terbayar</span>
    </div>
  </NuxtLink>
</template>
