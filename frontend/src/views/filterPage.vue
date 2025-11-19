<template>
    <base-page>
        <filter-set
            :bearerToken="bearerToken"
            :user="user"
            :userLocation="userLocation"
            :profile="profile"
        ></filter-set>
    </base-page>
</template>

<script setup lang="ts">
import { authCheck } from '@/composables/authCheck';
import FilterSet from '@/components/filterSet.vue';
import BasePage from '@/components/basePage.vue';

const { bearerToken, user, userLocation, profile } = authCheck();
</script>
