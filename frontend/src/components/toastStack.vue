<template>
  <div class="toast-container w-full p-4">
    <div
      v-for="toast in toasts"
      :key="toast.id"
      class="toast"
      :class="toast.show ? 'animate-slide-out' : 'animate-slide-in'"
    >
      <div v-html="toast.text"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ToastMessage } from '@/types/defTypes';
import { Ref } from 'vue';
import { usePresenceStore } from "@/stores/presences";
import { storeToRefs } from "pinia";
const presenceStore = usePresenceStore();
const { toasts }: { toasts: Ref<ToastMessage[]> } = storeToRefs(presenceStore);
</script>

<style>
@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateX(120%);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}


@keyframes slide-out {
    from {
        transform: translateX(0);
        opacity: 1;
    }

    to {
        transform: translateX(120%);
        opacity: 0;
    }
}

.animate-slide-in {
    animation: slide-in 0.6s ease-out forwards;
}

.animate-slide-out {
    animation: slide-out 0.6s ease-in forwards;
}

.toast-container {
    z-index: 9999;
    max-width: 100%;
    position: fixed;
    top: 20px;
    right: 0px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.toast {
    background: #e87d04;
    color: #fff;
    padding: 14px 16px;
    border: 1px solid #3d3d3d;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
    font-size: 14px;
}
</style>
