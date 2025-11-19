<template>
    <base-page>
        <logout-set :bearerToken="bearerToken" :user="user" :userLocation="userLocation" :profile="profile"></logout-set>
    </base-page>
</template>

<script setup lang="ts">
import { authCheck } from '@/composables/authCheck';
import LogoutSet from '@/components/logoutSet.vue';
import BasePage from '@/components/basePage.vue';


const { bearerToken, user, userLocation, profile } = authCheck();

</script>
