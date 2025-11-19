<template>
  <ion-page>
    <!-- SCROLLING CONTENT -->
    <ion-content class="page-content">
      <slot></slot>
    </ion-content>
    <!-- STICKY FOOTER -->
    <footer-menu v-if="router.currentRoute.value.path !== '/login' && router.currentRoute.value.path !== '/register'"></footer-menu>
    <!-- TOAST MESSAGE COMPONENT -->
    <ToastStack />
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent } from '@ionic/vue';
import ToastStack from '@/components/toastStack.vue';
import FooterMenu from '@/components/footerMenu.vue';
import { useRouter } from 'vue-router';

const router = useRouter();
</script>
<style scoped>
.page-content {
  --padding-bottom: 92px; /* Spazio per il footer */
}

.footer-fixed {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;

  z-index: 2000;
  background: white;
  border-top: 1px solid #ddd;
}
</style>
