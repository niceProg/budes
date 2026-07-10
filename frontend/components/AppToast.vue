<script setup lang="ts">
import { useApp } from '~/stores/app'
const app = useApp()
</script>

<template>
  <Transition name="toast">
    <div
      v-if="app.toast"
      class="fixed bottom-6 left-1/2 z-[60] max-w-[calc(100vw-48px)] -translate-x-1/2 rounded-xl bg-sand-900 px-[22px] py-[13px] text-[13.5px] font-semibold text-white shadow-lg"
    >
      {{ app.toast }}
    </div>
  </Transition>
</template>

<style scoped>
.toast-enter-active { animation: toastIn 0.25s ease; }
.toast-leave-active { transition: opacity 0.2s ease; }
.toast-leave-to { opacity: 0; }
@keyframes toastIn {
  from { opacity: 0; transform: translate(-50%, 12px); }
  to { opacity: 1; transform: translate(-50%, 0); }
}
</style>
