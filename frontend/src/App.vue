<template>
    <ion-app>
        <ion-router-outlet></ion-router-outlet>
    </ion-app>
</template>

<script setup lang="ts">
import {
    IonApp,
    IonRouterOutlet
} from '@ionic/vue';
import { onMounted, onBeforeUnmount } from "vue";
import { usePresenceStore } from "@/stores/presences";
import { authCheck } from '@/composables/authCheck';
import { requestAllPermissions } from '@/composables/usePermissions';
import { useBackButton } from "@/composables/useBackButton";


const presenceStore = usePresenceStore();
const { user } = authCheck();
const { registerBackButton, removeBackButtonListener } = useBackButton();

onMounted(async () => {
    await requestAllPermissions();
    registerBackButton();
});
onBeforeUnmount(() => {
    presenceStore.stopPresence(user.id);
    removeBackButtonListener();
});

window.addEventListener("beforeunload", () => {
    presenceStore.stopPresence(user.id);
    removeBackButtonListener();
});
</script>

<style scoped>
ion-app {
    --ion-background-color: #eeeeee;
    background-color: #ffffff;
}
</style>
