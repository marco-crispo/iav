<template>
    <base-page>
        <verifyemail-set :bearerToken="bearerToken" :user="user" :userLocation="userLocation" :profile="profile"></verifyemail-set>
    </base-page>
</template>

<script setup lang="ts">
import { authCheck } from '@/composables/authCheck';
import verifyemailSet from '@/components/verifyemailSet.vue';
import BasePage from '@/components/basePage.vue';

const { bearerToken, user, userLocation, profile } = authCheck();
</script>
