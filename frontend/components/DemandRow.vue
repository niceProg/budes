<script setup lang="ts">
import { decorateDemand, type Demand } from '~/utils/decorate'
const props = defineProps<{ d: Demand }>()
const v = computed(() => decorateDemand(props.d))
</script>

<template>
  <NuxtLink
    :to="`/permintaan/${d.id}`"
    class="card block cursor-pointer rounded-[13px] px-5 py-4 transition hover:border-sand-350 hover:shadow-[0_6px_18px_rgba(80,10,12,0.09)]"
  >
    <div class="flex flex-wrap items-center gap-x-[22px] gap-y-3.5">
      <div class="min-w-[190px] flex-[1.5]">
        <div class="mb-0.5 flex flex-wrap items-center gap-2">
          <span class="text-[15.5px] font-extrabold">{{ v.item_name }}</span>
          <StatusBadge :badge="v.badge" />
          <span v-if="v.dpPaid" class="text-[10.5px] font-bold text-success-600">✓ DP</span>
        </div>
        <div class="text-[12.5px] text-sand-700">{{ v.qtyLine }}</div>
      </div>
      <div class="min-w-[160px] flex-[1.2]">
        <ProgressBar :pct="v.pct" height="h-[7px]" />
        <div class="mt-1 text-[11.5px] text-sand-700">{{ v.pct }}% tersanggupi</div>
      </div>
      <div class="min-w-[110px]">
        <div class="text-[14.5px] font-extrabold text-clay-600">
          {{ v.hargaTxt }}<span class="text-[11px] font-semibold text-sand-600"> /{{ v.satuan }}</span>
        </div>
        <div class="text-[11.5px] text-sand-700">{{ v.hariTxt }}</div>
      </div>
      <div class="text-lg font-bold text-sand-500">›</div>
    </div>
  </NuxtLink>
</template>
