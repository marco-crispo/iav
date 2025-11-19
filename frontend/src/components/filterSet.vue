<template>
    <div class="px-4 py-2">
        <div class="flex flex-col items-center text-center mt-16">
            <!-- Logo -->
            <ion-icon name="map-outline" class="text-blue-600 text-6xl mb-4"></ion-icon>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Filters</h1>
            <p class="text-gray-500 mb-8">Set up your filters</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">User Status</label>
            <div class="input-wrapper">
                <ion-select interface="action-sheet" v-model="filters.online" placeholder="Select your status">
                    <ion-select-option value="" selected>All users</ion-select-option>
                    <ion-select-option value="0">Only offline users</ion-select-option>
                    <ion-select-option value="1">Only online users</ion-select-option>
                </ion-select>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Proximity limit ({{ filters.radiusKm }} Km)</label>
            <div class="input-wrapper">
                <ion-range :min="1" :max="defaultRadiusKm" :value="filters.radiusKm" :step="1"
                    v-model="filters.radiusKm" aria-label="Radius (km)" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Status</label>
            <div class="input-wrapper">
                <ion-select interface="action-sheet" v-model="filters.status" placeholder="Select your status">
                    <ion-select-option value="" selected>ALL</ion-select-option>
                    <ion-select-option v-for="availability in availabilities" :key="availability.id"
                        :value="availability.status">{{ availability.status }}</ion-select-option>
                </ion-select>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Name</label>
            <div class="input-wrapper">
                <ion-input v-model="filters.name" type="text" placeholder="Search for name" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Surname</label>
            <div class="input-wrapper">
                <ion-input v-model="filters.surname" type="text" placeholder="Search for surname" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">City</label>
            <div class="input-wrapper">
                <ion-input v-model="filters.city" type="text" placeholder="Search for city" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Country</label>
            <div class="input-wrapper">
                <ion-input v-model="filters.country" type="text" placeholder="Search for country" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Age range (from {{ (typeof filters.age === 'number' ?
                filters.age : filters.age.lower).toString() }} to {{ (typeof filters.age === 'number' ? filters.age :
                filters.age.upper).toString() }})</label>
            <div class="input-wrapper">
                <ion-range aria-label="Age Range" :dual-knobs="true" v-model="filters.age" :min="ageLower"
                    :max="ageUpper" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Search for bio</label>
            <div class="input-wrapper">
                <ion-input v-model="filters.bio" type="text" placeholder="Search for country" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Search for interests</label>
            <div class="input-wrapper">
                <ion-input v-model="filters.interests" type="text" placeholder="Search for country" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Search for relationship status</label>
            <div class="input-wrapper">
                <ion-select interface="action-sheet" v-model="filters.relationship_status"
                    placeholder="Select your status">
                    <ion-select-option value="" selected>ALL</ion-select-option>
                    <ion-select-option v-for="status in relationshipStatus" :key="status.id" :value="status.val">{{
                        status.val }}</ion-select-option>
                </ion-select>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Search for sexual orientation status</label>
            <div class="input-wrapper">
                <ion-select interface="action-sheet" v-model="filters.sexual_orientation"
                    placeholder="Select your status">
                    <ion-select-option value="" selected>ALL</ion-select-option>
                    <ion-select-option v-for="status in sexualOrientation" :key="status.id" :value="status.val">{{
                        status.val }}</ion-select-option>
                </ion-select>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">Search for language</label>
            <div class="input-wrapper">
                <ion-input v-model="filters.languages" type="text" placeholder="Search for country" />
            </div>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-4">
            <ion-button expand="block" class="font-semibold" @click="applyFilters()">
                APPLY FILTERS
            </ion-button>
            <ion-button color="light" expand="block" @click="resetFilters()">
                Reset all and go back home
            </ion-button>
        </div>
    </div>
</template>

<script setup lang="ts">
import router from '@/router';
import { IonRange, IonSelect, IonInput, IonSelectOption, IonButton, IonIcon } from '@ionic/vue';
import { onMounted, ref } from 'vue';
import { Filters } from '@/types/defTypes';
import { relationshipStatus, sexualOrientation, getAvailabilities } from '@/composables/usedLists';
import { Availability } from '@/types/defTypes';
import { showLoading, hideLoading } from '@/composables/uxUtils';
import { AGE_LOWER, AGE_UPPER, DEFAULT_RADIUS_KM } from '@/composables/envVars';
import { usePresenceStore } from "@/stores/presences";

const presenceStore = usePresenceStore();
// const pinRangeFormatter = (value: number) => `${value}`;
// const pinAgeFormatter = (value: number) => `${value}`;


const ageLower = AGE_LOWER;
const ageUpper = AGE_UPPER;
const defaultRadiusKm = DEFAULT_RADIUS_KM;
const availabilities = ref<Availability[]>([]);

const filtersStorage = localStorage.getItem('filters') ? JSON.parse(localStorage.getItem('filters')!) : null;

const filters = ref<Filters>({
    online: filtersStorage?.online || 0,
    radiusKm: filtersStorage?.radiusKm || defaultRadiusKm,
    status: filtersStorage?.status || '',
    name: filtersStorage?.name || '',
    surname: filtersStorage?.surname || '',
    city: filtersStorage?.city || '',
    country: filtersStorage?.country || '',
    age: { lower: filtersStorage?.age.lower || AGE_LOWER, upper: filtersStorage?.age.upper || AGE_UPPER },
    bio: filtersStorage?.bio || '',
    interests: filtersStorage?.interests || '',
    relationship_status: filtersStorage?.relationship_status || '',
    sexual_orientation: filtersStorage?.sexual_orientation || '',
    languages: filtersStorage?.languages || ''
});

onMounted(() => {
    showLoading();
    getAvailabilities()
    .then(response => {
        availabilities.value = response.data.data || [];
    }).catch((error) => {
        presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Error fetching availabilities']);
        console.error('Error:', error);
    }).finally(() => {
        hideLoading();
    });
});

const applyFilters = () => {

    if (filters.value.radiusKm < 1) {
        filters.value.radiusKm = 1; // Ensure minimum radius is 1 km
    }
    if (filters.value.radiusKm > defaultRadiusKm) {
        filters.value.radiusKm = defaultRadiusKm; // Ensure maximum radius is defaultRadiusKm km
    }

    localStorage.setItem('filters', JSON.stringify(filters.value));
    router.push('/home'); // Redirect to home after applying filters
};

const resetFilters = () => {
    filters.value = {
        online: 0,
        radiusKm: defaultRadiusKm,
        status: '',
        name: '',
        surname: '',
        city: '',
        country: '',
        age: { lower: ageLower, upper: ageUpper },
        bio: '',
        interests: '',
        relationship_status: '',
        sexual_orientation: '',
        languages: ''
    };
    localStorage.removeItem('filters');
    router.push('/home'); // Redirect to home after resetting filters
};
</script>

<style scoped>


</style>
