<template>
    <base-page>
        <profile-set :bearerToken="bearerToken" :user="user" :userLocation="userLocation" :profile="profile"></profile-set>
    </base-page>
</template>

<script setup lang="ts">
import { authCheck } from '@/composables/authCheck';
import ProfileSet from '@/components/profileSet.vue';
import BasePage from '@/components/basePage.vue';

const { bearerToken, user, userLocation, profile } = authCheck();
</script>
