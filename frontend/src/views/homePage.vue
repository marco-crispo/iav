<template>
    <base-page>
        <home-set :bearerToken="bearerToken" :user="user" :userLocation="userLocation" :profile="profile" :filters="filters"></home-set>
    </base-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { authCheck } from '@/composables/authCheck';
import homeSet from '@/components/homeSet.vue';
import { Filters } from '@/types/defTypes';
import BasePage from '@/components/basePage.vue';
import { DEFAULT_RADIUS_KM, AGE_LOWER, AGE_UPPER } from '@/composables/envVars';

const { bearerToken, user, userLocation, profile } = authCheck();
const filtersStorage = localStorage.getItem('filters') ? JSON.parse(localStorage.getItem('filters')!) : null;

const filters = ref<Filters>({
    online: filtersStorage?.online || 0,
    radiusKm: filtersStorage?.radiusKm || DEFAULT_RADIUS_KM,
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
</script>
