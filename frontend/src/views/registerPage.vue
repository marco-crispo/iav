<template>
    <base-page>
        <div class="px-4 py-2">

            <div class="flex flex-col items-center text-center mt-16">
                <!-- Logo -->
                <ion-icon name="map-outline" class="text-blue-600 text-6xl mb-4"></ion-icon>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome</h1>
                <p class="text-gray-500 mb-8">Register to continue</p>
            </div>

            <form @submit.prevent="register">
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Email</label>
                    <div class="input-wrapper">
                        <ion-input v-model="form.email" type="email"
                            placeholder="Insert your email" />
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Password</label>
                    <div class="input-wrapper">
                        <ion-input v-model="form.password" type="password"
                            placeholder="Insert your password" />
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Confirm password</label>
                    <div class="input-wrapper">
                        <ion-input v-model="form.password_confirmation" type="password"
                            placeholder="Confirm your password" />
                    </div>
                </div>

                <div class="mb-4">
                    <img v-if="form.cover_photo_image" :src="coverPreview?.toString()"
                        class="w-full rounded-xl shadow mb-2" />
                    <label for="form-cover" class="custom-file-upload">Choose a cover</label>
                    <input id="form-cover" type="file" accept="image/*" @change="onCoverPicked($event)" />
                </div>
                <div class="mb-4">
                    <ion-avatar class="fixed-avatar mb-2" v-if="form.avatar_image">
                        <img :src="avatarPreview?.toString()" class="rounded-full" />
                    </ion-avatar>
                    <label for="form-avatar" class="custom-file-upload">Choose an avatar</label>
                    <input id="form-avatar" type="file" accept="image/*" @change="onAvatarPicked($event)" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Status</label>
                    <div class="input-wrapper">
                        <ion-select interface="action-sheet" v-model="form.availability_id"
                            placeholder="Select your status" >
                            <ion-select-option v-for="availability in availabilities" :key="availability.id"
                                :value="availability.id">
                                {{ availability.status }}
                            </ion-select-option>
                        </ion-select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Name</label>
                    <div class="input-wrapper">
                        <ion-input v-model="form.name" type="text"
                            placeholder="Insert your name" />
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Surname</label>
                    <div class="input-wrapper">
                        <ion-input v-model="form.surname" type="text"
                            placeholder="Insert your surname" />
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Birthdate</label>
                    <ion-datetime-button datetime="datetime" >
                    </ion-datetime-button>
                    <ion-modal :keep-contents-mounted="true">
                        <ion-datetime id="datetime" presentation="date" :value="bdFormatted" v-model="form.birthdate"
                            :format-options="dtFormatOptions" ></ion-datetime>
                    </ion-modal>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">City</label>
                    <div class="input-wrapper">
                        <ion-input v-model="form.city" type="text"
                            placeholder="Insert your city" />
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Country</label>
                    <div class="input-wrapper">
                        <ion-input v-model="form.country" type="text"
                            placeholder="Insert your country" />
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Languages</label>
                    <div class="input-wrapper">
                        <ion-input v-model="form.languages" type="text"
                            placeholder="Insert your languages" />
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Relationship status</label>
                    <div class="input-wrapper">
                        <ion-select interface="action-sheet" v-model="form.relationship_status"
                            placeholder="Relationship status" >
                            <ion-select-option v-for="status in relationshipStatus" :key="status.id"
                                :value="status.val">
                                {{ status.val }}
                            </ion-select-option>
                        </ion-select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Sexual Orientation</label>
                    <div class="input-wrapper">
                        <ion-select interface="action-sheet" v-model="form.sexual_orientation"
                            placeholder="Sexual Orientation" >
                            <ion-select-option v-for="orientation in sexualOrientation" :key="orientation.id"
                                :value="orientation.val">
                                {{ orientation.val }}
                            </ion-select-option>
                        </ion-select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Bio</label>
                    <div class="input-wrapper">
                        <ion-textarea :rows="3" v-model="form.bio"
                            placeholder="Tell something about yourself" />
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-1">Interests</label>
                    <div class="input-wrapper">
                        <ion-textarea :rows="3" v-model="form.interests"
                            placeholder="Tell something about your interests" />
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4">
                    <ion-button type="submit" expand="block" class="font-semibold">
                        I’M DONE
                    </ion-button>
                    <ion-button color="light" expand="block" @click="router.push('/login')">
                        Cancel
                    </ion-button>
                </div>
            </form>
        </div>
    </base-page>
</template>

<script setup lang="ts">
import {
  IonInput, IonButton, IonTextarea,
  IonSelect, IonSelectOption, IonDatetime, IonDatetimeButton,
  IonModal, IonAvatar, IonIcon
} from '@ionic/vue';
import axios from 'axios'
import { onMounted, ref } from 'vue'
import { showLoading, hideLoading } from '@/composables/uxUtils';
import { Availability } from '@/types/defTypes';
import { relationshipStatus, sexualOrientation, getAvailabilities } from '@/composables/usedLists';
import { API_URL, formatOptions } from '@/composables/envVars';
import router from '@/router';
import dayjs from 'dayjs';

import { usePresenceStore } from "@/stores/presences";
import BasePage from '@/components/basePage.vue';

const presenceStore = usePresenceStore();

const availabilities = ref<Availability[]>([]);
const bdFormatted = dayjs().format('YYYY-MM-DD');
const avatarPreview = ref<string | ArrayBuffer | null>(null);
const coverPreview = ref<string | ArrayBuffer | null>(null);
const dtFormatOptions: any = formatOptions;

const form = ref({
  email: '',
  password: '',
  password_confirmation: '',
  availability_id: null as number | null,
  name: '',
  surname: '',
  status: '',
  bio: '',
  birthdate: bdFormatted,
  city: '',
  country: '',
  interests: '',
  languages: '',
  sexual_orientation: '',
  relationship_status: '',
  avatar_image: null as File | null,
  cover_photo_image: null as File | null
});

onMounted(() => {
    showLoading();
    getAvailabilities()
        .then(response => {
            availabilities.value = response.data.data || [];
        }).catch(error => {
            presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Error fetching availabilities']);
            console.log('Error:', error);
        }).finally(() => {
            hideLoading();
        });
});

function onAvatarPicked(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0] ?? null;
    if (file) {
        form.value.avatar_image = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
}

function onCoverPicked(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0] ?? null;
    if (file) {
        form.value.cover_photo_image = file;
        coverPreview.value = URL.createObjectURL(file);
    }
}

const register = async () => {
    try {
        showLoading();
        const formData = new FormData();
        Object.entries(form.value).forEach(([key, val]) => {
            if (val !== null && val !== '') {
                formData.append(key, val as any);
                console.log(key, val);
            }
        });
        await axios.post(`${API_URL}/register`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        }).then(async response => {
            console.log('Success register:', response.data);
            const data = response.data.data;
            const user = data.user;
            formData.delete('email');
            formData.delete('password');
            formData.delete('password_confirmation');

            formData.append('user_id', user.id);
            await axios.post(`${API_URL}/profile`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            }).then(response => {
                console.log('Success profile:', response.data);
                router.push('/login');
            }).catch(error => {
                presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Error creating profile']);
                console.log('Error:', error);
            }).finally(() => {
                hideLoading();
            });
            presenceStore.addToast(data.message || 'Registration successful');
        }).catch(error => {
            presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Registration error']);
            console.log('Error:', error);
        }).finally(() => {
            hideLoading();
        });
    } catch (err: any) {
        console.log(err);
        presenceStore.addToast(['Errore nella registrazione']);
    }
}
</script>
<style scoped>
input[type="file"] {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
</style>
