<template>
    <BasePage>
        <div class="px-6 py-10">

            <div class="flex flex-col items-center text-center mt-16">
                <!-- Logo -->
                <ion-icon name="map-outline" class="text-blue-600 text-6xl mb-4"></ion-icon>

                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome</h1>
                <p class="text-gray-500 mb-8">Log in to continue</p>
            </div>

            <form @submit.prevent="login" class="space-y-6 mt-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
                    <div class="input-wrapper">
                        <ion-input name="email" v-model="email" type="email"
                            placeholder="yourmail@mail.com" required>
                        </ion-input>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="password">Password</label>
                    <div class="input-wrapper">
                        <ion-input name="password" v-model="password" type="password"
                            placeholder="••••••••" required>
                        </ion-input>
                    </div>
                </div>

                <ion-button type="submit"
                    class="btn-primary w-full">
                    <ion-spinner v-if="loading" name="crescent" />
                    <span v-else>Login</span>
                </ion-button>

                <p class="text-center text-sm text-gray-500">
                    You don't have an account?
                    <router-link to="/register" class="text-blue-600 font-semibold">
                        Register
                    </router-link>
                </p>
                <p class="text-center text-sm text-gray-500">
                    Forgot your password?
                    <router-link to="/forgotpassword" class="text-blue-600 font-semibold">
                        Reset Password
                    </router-link>
                </p>
            </form>
        </div>
    </BasePage>
</template>

<script setup lang="ts">
import {
    IonInput,
    IonButton,
    IonIcon,
    IonSpinner,
} from '@ionic/vue';

import { ref } from 'vue';
import axios from 'axios';
import router from '@/router';

import { Profile, User, UserLocation } from '@/types/defTypes';
import { API_URL } from '@/composables/envVars';
import { initAbly } from '@/composables/ablyClient';
import BasePage from '@/components/basePage.vue';
import { usePresenceStore } from "@/stores/presences";
const presenceStore = usePresenceStore();

const email = ref('');
const password = ref('');
const loading = ref(false);

let user: User;
let userLocation: UserLocation;
let profile: Profile ;
let bearerToken: string;

const login = async () => {
    loading.value = true;

    await axios.post(`${API_URL}/login`, {
        email: email.value,
        password: password.value
    }).then(response => {
        console.log(response.data.data);
        const data = response.data.data;
        bearerToken = data.token;
        console.log('TOKEN:', bearerToken);
        if (!bearerToken) {
            presenceStore.addToast(['Token non ricevuto']);
            return;
        }
        user = data.user;
        userLocation = data.location;
        profile = data.profile;
        localStorage.setItem('token', bearerToken);
        localStorage.setItem('user', JSON.stringify(user));
        localStorage.setItem('userLocation', JSON.stringify(userLocation));
        localStorage.setItem('profile', JSON.stringify(profile));
        const ably = initAbly(bearerToken);
        console.log('Ably initialized:', ably);

        axios.defaults.headers.common['Authorization'] = `Bearer ${bearerToken}`;
        if (data.message == 'Unverified email') {
            router.push('/verifyemail');
        } else {
            router.push('/home');
        }

    })
    .catch(error => {
        presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Logino error']);
        console.log('Error:', error);
    }).finally(() => {
        loading.value = false;
    });
};
</script>
<style scoped>
</style>
