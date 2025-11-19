<template>
    <base-page>
        <notifications-set :bearerToken="bearerToken" :user="user" :userLocation="userLocation" :profile="profile"></notifications-set>
    </base-page>
</template>

<script setup lang="ts">
import { authCheck } from '@/composables/authCheck';
import NotificationsSet from '@/components/notificationsSet.vue';
import BasePage from '@/components/basePage.vue';


const { bearerToken, user, userLocation, profile } = authCheck();

</script>
