<template>
    <ion-card class="user-popup overflow-hidden rounded-2xl">

        <!-- COVER IMAGE -->
        <img :alt="profile.name + ' ' + profile.surname" :src="STORAGE_URL + '/' + profile.cover_photo"
            class="h-44 w-full object-cover" />

        <!-- HEADER -->
        <ion-card-header class="p-4">
            <ion-card-subtitle class="text-primary text-sm font-medium">
                {{ profile.availability.status }}
            </ion-card-subtitle>

            <ion-card-title class="text-xl font-bold mt-1">
                {{ profile.name }} {{ profile.surname }}
            </ion-card-title>
        </ion-card-header>

        <!-- CONTENT -->
        <ion-card-content class="px-4 pb-5 text-base">

            <ion-grid class="gap-2">

                <ion-row class="items-center">
                    <ion-col size="1" class="flex justify-center mr-2">
                        <i class="fa-solid fa-cake-candles text-lg"></i>
                    </ion-col>
                    <ion-col>
                        <ion-note>{{ getAge(new Date(profile.birthdate)) }}</ion-note>
                    </ion-col>
                </ion-row>

                <ion-row class="items-center">
                    <ion-col size="1" class="flex justify-center mr-2">
                        <i class="fa-solid fa-language text-lg"></i>
                    </ion-col>
                    <ion-col>
                        <ion-note>{{ profile.languages }}</ion-note>
                    </ion-col>
                </ion-row>

                <ion-row class="items-center">
                    <ion-col size="1" class="flex justify-center mr-2">
                        <i class="fa-solid fa-city text-lg"></i>
                    </ion-col>
                    <ion-col>
                        <ion-note>{{ profile.city }}</ion-note>
                    </ion-col>
                </ion-row>

                <ion-row class="items-center">
                    <ion-col size="1" class="flex justify-center mr-2">
                        <i class="fa-solid fa-globe text-lg"></i>
                    </ion-col>
                    <ion-col>
                        <ion-note>{{ profile.country }}</ion-note>
                    </ion-col>
                </ion-row>

                <ion-row class="items-center">
                    <ion-col size="1" class="flex justify-center mr-2">
                        <i class="fa-solid fa-venus-mars text-lg"></i>
                    </ion-col>
                    <ion-col>
                        <ion-note>{{ profile.sexual_orientation }}</ion-note>
                    </ion-col>
                </ion-row>

                <ion-row class="items-center">
                    <ion-col size="1" class="flex justify-center mr-2">
                        <i class="fa-solid fa-heart-crack text-lg"></i>
                    </ion-col>
                    <ion-col>
                        <ion-note>{{ profile.relationship_status }}</ion-note>
                    </ion-col>
                </ion-row>

                <ion-row class="items-center">
                    <ion-col size="1" class="flex justify-center mr-2">
                        <i class="fa-solid fa-question text-lg"></i>
                    </ion-col>
                    <ion-col>
                        <ion-note>{{ profile.interests }}</ion-note>
                    </ion-col>
                </ion-row>

            </ion-grid>

            <!-- CTA BUTTON -->
            <ion-button expand="block" class="mt-5 text-base font-medium" @click="openMessagePopup">
                Contact me!
            </ion-button>

            <ion-modal :is-open="isMessageModalOpen" @didDismiss="isMessageModalOpen = false">
                <div class="message-modal">
                    <h2 class="modal-title">
                        Send a message to {{ profile.name }} {{ profile.surname }}
                    </h2>

                    <div class="input-wrapper">
                        <ion-textarea v-model="messageContent" placeholder="Write your message..."
                            class="message-textarea" />
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <ion-button color="medium" @click="isMessageModalOpen = false">Cancel</ion-button>
                        <ion-button color="primary" :disabled="!messageContent" @click="sendMessage">Send</ion-button>
                    </div>
                </div>
            </ion-modal>


        </ion-card-content>
    </ion-card>
</template>

<script lang="ts" setup>
import {
    IonCard,
    IonCardContent,
    IonCardHeader,
    IonCardSubtitle,
    IonCardTitle,
    IonGrid,
    IonRow,
    IonCol,
    IonNote,
    IonButton,
    IonModal,
    IonTextarea
} from '@ionic/vue';
import { ref } from 'vue';
import axios from 'axios';
import { Profile } from '@/types/defTypes';
import { API_URL, STORAGE_URL } from '@/composables/envVars';

const props = defineProps<{
    profile: Profile;
    bearerToken: string;
}>();

const profile = props.profile;
const bearerToken = props.bearerToken;
const isMessageModalOpen = ref(false);
const messageContent = ref('');

const getAge = (dob: Date) => {
    const diff_ms = Date.now() - dob.getTime();
    const age_dt = new Date(diff_ms);
    return Math.abs(age_dt.getUTCFullYear() - 1970);
};

function openMessagePopup() {
  isMessageModalOpen.value = true;
}

async function sendMessage() {
    await axios.post(
        `${API_URL}/messages`,
        { receiver_id: profile.user_id, content: messageContent.value },
        { headers: { Authorization: `Bearer ${bearerToken}` } }
    ).then(() => {
        console.log('Message sent successfully');
        messageContent.value = '';
        isMessageModalOpen.value = false;
    }).catch((error) => {
        console.error('Error sending message:', error);
    });
}
</script>

<style scoped>
.user-popup {
    border-radius: 24px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.15);
}
ion-note {
    font-size: 15px;
}
.message-modal {
    padding: 20px;
    color: #222;
    /* colore di base leggibile */
}

/* Titolo ben leggibile */
.modal-title {
    color: #111;
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 14px;
}

/* Textarea moderno e leggibile */
.message-textarea {
    --background: rgba(255, 255, 255, 0.25);
    --color: #111;
    /* testo scuro */
    --placeholder-color: #555;
    /* placeholder leggibile */

    --padding-start: 12px;
    --padding-end: 12px;
    --padding-top: 12px;
    --padding-bottom: 12px;

    border-radius: 14px;
    backdrop-filter: blur(10px);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}
</style>
