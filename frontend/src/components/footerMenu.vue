<template>
    <ion-footer id="footer" class="footer-modern animate-in">
        <div class="footer-content">
            <ion-button fill="clear" class="w-full" @click="openedMenu = openMenu($event, 'mainMenu')">
                <ion-icon :icon="menu" />
            </ion-button>
        </div>
    </ion-footer>

    <ion-popover v-for="menu in menus" :key="menu.id" :is-open="openedMenu === menu.id" :event="menu.event"
        @did-dismiss="openedMenu = null" class="animated-popover">
        <ion-list>
            <ion-item v-for="(item, idx) in menu.items" :key="idx" button @click="openedMenu = onMenuItem(menu.id, idx)"
                :href="item.route">
                <ion-icon slot="start" :icon="item.icon" class="px-2"></ion-icon>
                <ion-label>{{ idx }}</ion-label>
            </ion-item>
        </ion-list>
    </ion-popover>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import {
    IonFooter,
    IonButton,
    IonIcon,
    IonPopover,
    IonList,
    IonItem,
    IonLabel
} from '@ionic/vue';

import { menu } from 'ionicons/icons';
import { menus, openMenu, onMenuItem } from '@/composables/menu';

const openedMenu = ref<string | null>(null);
</script>

<style scoped>
.footer-modern {
    position: fixed;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 92%;
    background: #ffffffcc;
    /* semi transparent */
    backdrop-filter: blur(10px);

    border-radius: 18px;
    padding: 2px;

    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    z-index: 2000;

    display: flex;
    justify-content: center;
}

.footer-content {
    display: flex;
    gap: 20px;
}

/* IonButton minimal */
ion-button {
    --background: transparent;
    --box-shadow: none;
    --ripple-color: var(--ion-color-primary);
    font-size: 24px;
}

/* ===== Animation ===== */
.animate-in {
    animation: fadeSlideIn .85s ease-out forwards;
}

@keyframes fadeSlideIn {
    from {
        transform: translate(-50%, 30px);
        opacity: 0;
    }

    to {
        transform: translate(-50%, 0);
        opacity: 1;
    }
}


.animated-popover::part(backdrop) {
    background: rgba(0, 0, 0, 0.25);
    opacity: 0;
    animation: fadeBackdropIn .3s forwards;
}

/* Contenuto interno del popover */
.animated-popover::part(content) {
    width: 92% !important;
    margin: 0 auto;
    left: 50% !important;
    transform: translateX(-50%) translateY(10px);
    background: #ffffffee;
    backdrop-filter: blur(10px);
    border-radius: 18px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);

    animation: popoverSlideIn .35s ease-out forwards;
}

/* Animazione ENTRATA contenuto */
@keyframes popoverSlideIn {
    0% {
        opacity: 0;
        transform: translateX(-50%) translateY(30px);
    }

    100% {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

/* Animazione USCITA popover */
.animated-popover.popover-hidden::part(content) {
    animation: popoverSlideOut .3s ease-in forwards !important;
}

@keyframes popoverSlideOut {
    0% {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    100% {
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
    }
}

/* Backdrop fade-in */
@keyframes fadeBackdropIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

/* Backdrop fade-out */
.animated-popover.popover-hidden::part(backdrop) {
    animation: fadeBackdropOut .3s forwards !important;
}

@keyframes fadeBackdropOut {
    from {
        opacity: 1;
    }

    to {
        opacity: 0;
    }
}
</style>
