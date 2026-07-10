<script setup lang="ts">
import type { Badge } from '~/utils/badges'
defineProps<{ badge: Badge }>()
</script>

<template>
  <span class="badge" :class="badge.cls">{{ badge.label }}</span>
</template>
