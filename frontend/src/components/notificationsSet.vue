<template>
    <div class="px-4 py-2">

        <div class="flex flex-col items-center text-center mt-16">
            <!-- Logo -->
            <ion-icon name="map-outline" class="text-blue-600 text-6xl mb-4"></ion-icon>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Notifications</h1>
            <p class="text-gray-500 mb-8">Read your messages</p>
        </div>
        <ion-accordion-group expand="inset">
            <ion-accordion v-for="conversation in conversations" :key="conversation.user.id"
                :value="conversation.user.id.toString()">
                <ion-item slot="header" button lines="none" class="rounded-xl shadow-sm my-2">
                    <ion-avatar slot="start" class="fixed-avatar w-12 h-12 shadow mr-4">
                        <img alt="" :src="STORAGE_URL + '/' + conversation.user.avatar" />
                    </ion-avatar>
                    <ion-label class="font-semibold text-gray-800">
                        {{ conversation.user.name }}
                        <p class="text-sm text-gray-500">
                            {{ conversation.messages.length }} messages
                        </p>
                    </ion-label>
                    <ion-badge slot="end">
                        {{ conversation.messages.length }}
                    </ion-badge>
                </ion-item>
                <div slot="content" class="p-3 space-y-4">
                    <div v-for="(msg, index) in conversation.messages" :key="index" class="flex"
                        :class="msg.fromMe ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[70%] px-3 py-2 rounded-xl text-sm shadow-sm" :class="msg.fromMe
                            ? 'bg-orange-400 text-white rounded-br-none'
                            : 'bg-gray-400 text-white rounded-bl-none'
                            ">
                            {{ msg.content }}
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 pt-2">
                        <div class="input-wrapper">
                            <ion-input v-model="conversation.newMessage" placeholder="Scrivi un messaggio..."
                                fill="outline" class="flex-1 modern-input" />
                        </div>

                        <ion-button @click="sendMessage(conversation.user.id)" shape="round">
                            SEND
                        </ion-button>
                    </div>

                </div>
            </ion-accordion>
        </ion-accordion-group>
    </div>
</template>

<script setup lang="ts">
import {IonIcon,
        IonAccordion,
        IonAccordionGroup,
        IonItem,
        IonLabel,
        IonInput,
        IonButton,
        IonAvatar,
        IonBadge,
} from '@ionic/vue';

import { onMounted, ref } from 'vue';
import { Conversation, User, UserLocation, Profile } from '@/types/defTypes';
import { API_URL, STORAGE_URL } from '@/composables/envVars';
import axios from 'axios';
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

const conversations = ref<Conversation[]>([]);

onMounted (async () => {
    await axios.get(`${API_URL}/my-messages`,
        { headers: { Authorization: `Bearer ${bearerToken}` }
    })
    .then(response => {
        conversations.value = response.data.data.map((conv: any) => ({
            user: conv.user,
            messages: conv.messages,
            newMessage: '',
        }));
    })
    .catch(error => {
        presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Login error']);
        console.error('Error fetching conversations:', error);
    });
});

const sendMessage = async (userId: number) => {
    const conversation = conversations.value.find(c => c.user.id === userId);
    if (conversation && conversation.newMessage.trim() !== "") {
        conversation.messages.push({
            content: conversation.newMessage,
            fromMe: true,
            timestamp: new Date().toISOString(),
        });

        await axios.post(
            `${API_URL}/messages`,
            { receiver_id: userId, content: conversation.newMessage },
            { headers: { Authorization: `Bearer ${bearerToken}` } }
        ).then(() => {
            console.log('Message sent successfully');
            conversation.newMessage = "";
        })
        .catch((error) => {
            presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Message send error']);
            console.error('Error sending message:', error);
        });

  }
};

</script>

<style scoped>
</style>
