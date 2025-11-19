<template>
  <ion-content class="flex items-center justify-center bg-white">

    <div class="px-4 py-2">
        <div
        class="text-center transition-opacity duration-700"
        :class="{ 'opacity-0': fadingOut }"
        >
            <div class="flex flex-col items-center text-center mt-16">
                <!-- Logo -->
                <ion-icon name="map-outline" class="text-blue-600 text-6xl mb-4"></ion-icon>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Notifications</h1>
                <p class="text-gray-500 mb-8">Read your messages</p>
            </div>

            <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                Logging out...
            </h2>

            <p class="text-gray-600 text-lg">
                You will be redirected to login in
                <strong class="text-primary">{{ s }}</strong>
                seconds.
            </p>
        </div>
    </div>

  </ion-content>
</template>


<script setup lang="ts">
import router from '@/router';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { IonContent, IonIcon } from '@ionic/vue';
import { User } from '@/types/defTypes';
import { API_URL } from '@/composables/envVars';
import { usePresenceStore } from '@/stores/presences';

const presenceStore = usePresenceStore();
const s = ref(3);
const fadingOut = ref(false);

const props = defineProps<{
  bearerToken: string;
  user: User;
//   userLocation: UserLocation | null;
//   profile: Profile;
}>();

const bearerToken = props.bearerToken;
// const userLocation = props.userLocation;
const user = props.user;
// const profile = props.profile;

onMounted(() => {
  logout();
});

const logout = async () => {
    try {
        await axios.post(`${API_URL}/logout`, {},
        {
            headers: {
                'Authorization': `Bearer ${bearerToken}`
            }
        });
    } catch (e) {
        console.error('Error:', e);
    } finally {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('userLocation');
        localStorage.removeItem('profile');
        localStorage.removeItem('filters');

        delete axios.defaults.headers.common['Authorization'];

        presenceStore.stopPresence(user.id);



        const interval = setInterval(() => {
            if (s.value > 1) {
                s.value--;
            } else {
                clearInterval(interval);
                // Avvia fade-out
                fadingOut.value = true;

                // Attendi fine animazione (700ms)
                setTimeout(() => {
                    router.replace('/login');
                }, 700);
            }
        }, 1000);
    }
};

</script>
