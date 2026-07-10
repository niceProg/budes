<script setup lang="ts">
withDefaults(defineProps<{ pct: number; height?: string }>(), { height: 'h-2' })
</script>

<template>
  <div class="progress" :class="height">
    <span :style="{ width: pct + '%' }" />
  </div>
</template>
