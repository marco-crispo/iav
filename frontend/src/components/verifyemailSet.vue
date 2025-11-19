<template>
    <base-page>
        <div class="px-4 py-2">
            <div class="flex flex-col items-center text-center mt-16">
                <!-- Logo -->
                <ion-icon name="map-outline" class="text-blue-600 text-6xl mb-4"></ion-icon>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Verify your email</h1>
                <p class="text-gray-500 mb-8">A verification email was sent to your address <strong>{{ user?.email }}</strong></p>
                <p class="text-gray-500 mb-8">Check your inbox and click the link to confirm.</p>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <ion-button expand="block" @click="resendVerificationEmail" :disabled="loading" class="font-semibold">
                    RESEND VERIFICATION EMAIL
                </ion-button>
                <ion-button color="light" expand="block" @click="router.push('/home')">
                    Back to home
                </ion-button>
            </div>
        </div>
    </base-page>
</template>


<script lang="ts" setup>
import { ref } from 'vue'
import { IonButton } from '@ionic/vue'
import basePage from './basePage.vue';
import axios from 'axios'
import router from '@/router';
import { User, UserLocation, Profile } from '@/types/defTypes';
import { API_URL } from '@/composables/envVars';
import { usePresenceStore } from "@/stores/presences";


const props = defineProps<{
    bearerToken: string;
    user: User;
    userLocation: UserLocation | null;
    profile: Profile;
}>();

const presenceStore = usePresenceStore();

const bearerToken = props.bearerToken;
const user = props.user;

const loading = ref(false)

const resendVerificationEmail = async () => {
    loading.value = true

    await axios.post(`${API_URL}/email/resend`, {}, {
        headers: { Authorization: `Bearer ${bearerToken}` }
    }).then((response) => {
        const data = response.data;
        presenceStore.addToast(data.message ? [data.message] : ['Verification email resent']);
    }).catch((error) => {
        presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Error resending email']);
    }).finally(() => {
        loading.value = false
    });
}
</script>

<style>
  ion-toast.custom-toast {
    --background: #f4f4fa;
    --box-shadow: 3px 3px 10px 0 rgba(0, 0, 0, 0.2);
    --color: #4b4a50;
  }

  ion-toast.custom-toast::part(message) {
    font-style: italic;
  }

  ion-toast.custom-toast::part(button) {
    border-left: 1px solid #d2d2d2;
    color: #030207;
    font-size: 15px;
  }
</style>
