<template>
    <div class="px-4 py-2">

        <div class="flex flex-col items-center text-center mt-16">
            <!-- Logo -->
            <ion-icon name="map-outline" class="text-blue-600 text-6xl mb-4"></ion-icon>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Profile</h1>
            <p class="text-gray-500 mb-8">Edit your profile</p>
        </div>
        <div class="mb-4">
            <img v-if="form.cover_photo" :alt="form.name + ' ' + form.surname"
                :src="coverPreview?.toString() || (STORAGE_URL + '/' + form.cover_photo)"
                class="w-full rounded-xl shadow mb-2" />
            <label for="form-cover" class="custom-file-upload">Choose a cover</label>
            <input id="form-cover" type="file" accept="image/*" @change="onCoverPicked($event)" />
        </div>
        <div class="mb-4">
            <ion-avatar class="fixed-avatar mb-2" v-if="form.avatar">
                <img :alt="form.name + ' ' + form.surname"
                    :src="avatarPreview?.toString() || (STORAGE_URL + '/' + form.avatar)" />
            </ion-avatar>
            <label for="form-avatar" class="custom-file-upload">Choose an avatar</label>
            <input id="form-avatar" type="file" accept="image/*" @change="onAvatarPicked($event)" />
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Status</label>
            <div class="input-wrapper">
                <ion-select interface="action-sheet" v-model="form.availability_id" placeholder="Select your status">
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
                <ion-input v-model="form.name" type="text" placeholder="Insert your name" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Surname</label>
            <div class="input-wrapper">
                <ion-input v-model="form.surname" type="text" placeholder="Insert your surname" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Birthdate</label>
            <ion-datetime-button datetime="datetime">
            </ion-datetime-button>
            <ion-modal :keep-contents-mounted="true">
                <ion-datetime id="datetime" presentation="date" :value="bdFormatted" v-model="form.birthdate"
                    :format-options="dtFormatOptions"></ion-datetime>
            </ion-modal>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">City</label>
            <div class="input-wrapper">
                <ion-input v-model="form.city" type="text" placeholder="Insert your city" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Country</label>
            <div class="input-wrapper">
                <ion-input v-model="form.country" type="text" placeholder="Insert your country" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Languages</label>
            <div class="input-wrapper">
                <ion-input v-model="form.languages" type="text" placeholder="Insert your languages" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Relationship status</label>
            <div class="input-wrapper">
                <ion-select interface="action-sheet" v-model="form.relationship_status"
                    placeholder="Relationship status">
                    <ion-select-option v-for="status in relationshipStatus" :key="status.id" :value="status.val">
                        {{ status.val }}
                    </ion-select-option>
                </ion-select>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Sexual Orientation</label>
            <div class="input-wrapper">
                <ion-select interface="action-sheet" v-model="form.sexual_orientation" placeholder="Sexual Orientation">
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
                <ion-textarea :rows="3" v-model="form.bio" class="modern-textarea shadow-sm"
                    placeholder="Tell something about yourself" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Interests</label>
            <div class="input-wrapper">
                <ion-textarea :rows="3" v-model="form.interests" class="modern-textarea shadow-sm"
                    placeholder="Tell something about your interests" />
            </div>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-4">
            <ion-button @click="saveProfile()" expand="block" class="font-semibold">
                SAVE
            </ion-button>
            <ion-button color="light" expand="block" @click="router.push('/home')">
                Cancel
            </ion-button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {
    IonDatetime,
    IonDatetimeButton,
    IonModal,
    IonInput,
    IonTextarea,
    IonSelect,
    IonSelectOption,
    IonButton,
    IonAvatar,
    IonIcon
} from '@ionic/vue'
import axios from 'axios'
import dayjs from 'dayjs';
import router from '@/router';
import { onMounted, ref } from 'vue'
import { showLoading, hideLoading } from '@/composables/uxUtils';
import { Availability, User, UserLocation, Profile } from '@/types/defTypes';
import { relationshipStatus, sexualOrientation, getAvailabilities } from '@/composables/usedLists';
import { API_URL, STORAGE_URL, formatOptions } from '@/composables/envVars';
import { usePresenceStore } from "@/stores/presences";

const props = defineProps<{
    bearerToken: string;
    user: User;
    userLocation: UserLocation | null;
    profile: Profile;
}>();

const presenceStore = usePresenceStore();

const bearerToken = props.bearerToken;
// const user = props.user;
// const userLocation = props.userLocation;
const profile = props.profile;
const availabilities = ref<Availability[]>([]);
const bdFormatted = dayjs(profile.birthdate).format('YYYY-MM-DD');
const dtFormatOptions: any = formatOptions;

const form = ref({
    availability_id: profile.availability_id,
    avatar: profile.avatar,
    avatar_image: null,
    bio: profile.bio,
    birthdate: bdFormatted,
    city: profile.city,
    country: profile.country,
    cover_photo: profile.cover_photo,
    cover_photo_image: null,
    interests: profile.interests,
    languages: profile.languages,
    name: profile.name,
    relationship_status: profile.relationship_status,
    sexual_orientation: profile.sexual_orientation,
    surname: profile.surname
})


const avatarFile = ref<File | null>(null);
const coverFile = ref<File | null>(null);
const avatarPreview = ref<string | ArrayBuffer | null>(null);
const coverPreview = ref<string | ArrayBuffer | null>(null);
// let aI:any = null;
// let cI:any = null;

// function pickAvatar() {
//     (aI as unknown as HTMLInputElement).click();
// }
// function pickCover() {
//     (cI as unknown as HTMLInputElement).click();
// }

function onAvatarPicked(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0] ?? null;
    if (file) {
        avatarFile.value = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
}

function onCoverPicked(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0] ?? null;
    if (file) {
        coverFile.value = file;
        coverPreview.value = URL.createObjectURL(file);
    }
}

onMounted(() => {
    showLoading();
    getAvailabilities()
    .then(response => {
        availabilities.value = response.data.data || [];
    }).catch(error => {
        presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Error fetching availabilities']);
        console.error('Error:', error);
    }).finally(() => {
        hideLoading();
    });
});

const saveProfile = async () => {
    showLoading();
    const fd = new FormData();

    Object.entries(form.value).forEach(([k, v]) => {
        if (v !== null && v !== undefined) {
            fd.append(k, v as string);
        }
    });

    if (avatarFile.value) {
        fd.append('avatar_image', avatarFile.value);
    }
    if (coverFile.value) {
        fd.append('cover_photo_image', coverFile.value);
    }

    await axios.post(`${API_URL}/profile/${profile.id}`,
    fd,
    {
        headers: {
            'Authorization': `Bearer ${bearerToken}`,
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(response => {
        Object.assign(profile, response.data.data);
        localStorage.setItem('profile', JSON.stringify(profile));
    }).catch(error => {
        presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Profile update error']);
        console.error('Error:', error);
    }).finally(() => {
        hideLoading();
    });
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
