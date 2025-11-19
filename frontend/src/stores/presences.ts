// stores/presence.ts
import { defineStore } from "pinia";
import { ref, type Ref } from "vue";
import * as Ably from "ably";
import { getAbly } from "@/composables/ablyClient";
import type { RealtimeChannel, Message, PresenceMessage } from "ably";
import axios from "axios";
import { API_URL } from "@/composables/envVars";
import * as L from "leaflet";
import { ToastMessage } from '@/types/defTypes';

export const usePresenceStore = defineStore("presence", () => {
    const onlineUsers = ref<{ id: number; name: string }[]>([]);
    const toasts = ref<ToastMessage[]>([]);

    let ably: Ably.Realtime | null = null;
    let presenceChannel: RealtimeChannel | null = null;
    let toastId = 0;

    const markers = ref<Map<number, L.Marker<any>>>(new Map());

    /**
     * Initialize the presence and realtime events
     */
    async function initPresence(bearerToken: string, user: { id: number; name: string }, markerInstance: Ref<any>) {
        ably = getAbly(bearerToken);
        presenceChannel = ably.channels.get("public:presence:user-location");

        const userLocationChannel = ably.channels.get('public:users.location');
        const userStatusChannel = ably.channels.get('public:users.status');
        const userProfileChannel = ably.channels.get('public:users.profile');
        const userLogoutChannel = ably.channels.get('public:users.logout');
        const userMessagesChannel = ably.channels.get('private:messages.sent.' + user.id);

        // Listen for joins/leaves
        presenceChannel.presence.subscribe("enter", async (member: PresenceMessage) => {
            if (!onlineUsers.value.some((u) => u.id === member.data.id)) {
                onlineUsers.value.push(member.data);
            }
            await axios.post(`${API_URL}/online/enter`, { user_id: member.data.id }, {
                headers: { Authorization: `Bearer ${bearerToken}` },
            }).then((response) => {
                addToast(`User ${member.data.name} is online.`);
                console.log(response.data.message, member.data.id);
            })
            .catch((error) => {
                addToast(`Error notifying presence of user ${member.data.name}.`);
                console.error('Error notifying presence enter:', error);
            });
        });

        presenceChannel.presence.subscribe("leave", async (member: PresenceMessage) => {
            onlineUsers.value = onlineUsers.value.filter((u) => u.id !== member.data.id);
            // await axios.post(`${API_URL}/online/leave`, { user_id: member.data.id }, {
            //     headers: { Authorization: `Bearer ${bearerToken}` },
            // });
        });

        // Announce my presence
        await presenceChannel.attach();
        await presenceChannel.presence.enter({ id: user.id, name: user.name });

        // Recover already present users
        const members = await presenceChannel.presence.get();
        onlineUsers.value = members.map((m: PresenceMessage) => m.data);

        userLocationChannel
        .subscribe('UserLocationUpdated', (event: any) => {
            console.log('[Location]', event);
            event.data.online = 1;
            markerInstance.value?.updateMarkerPosition(event.data.user_id, event.data.latitude, event.data.longitude);
            markerInstance.value?.setMarkerIcon(event.data.user_id, event.data.status, event.data.online);
        });

        userStatusChannel
        .subscribe('UserStatusUpdated', (event: any) => {
            console.log('[Status]', event);
            markerInstance.value?.setMarkerIcon(event.data.user_id, event.data.status.status);
        });

        userProfileChannel
        .subscribe('UserProfileUpdated', (event: any) => {
            console.log('[Profile]', event);
            markerInstance.value?.updateProfileOnPopup(event.data.user_id, event.data.profile);
        });

        userLogoutChannel
        .subscribe('UserLogout', (event: any) => {
            console.log('[Logout]', event);
            // onlineUsers.value = onlineUsers.value.filter((id:number) => id !== event.data.user_id);
            markerInstance.value?.removeMarker(event.data.user_id);
        });

        userMessagesChannel
        .subscribe('NewMessageSent', (event: any) => {
            console.log('[Message Sent]', event);
            addToast(`${event.data.sender_name}: ${event.data.content}`);
        });

        // Listen for location updates
        presenceChannel.subscribe("user:location", (message: Message) => {
            const { userId, lat, lng } = message.data;
            markerInstance.value?.updateMarkerPosition(userId, lat, lng);
        });
    }

    /**
    * Publish the user's location on the Ably channel
    */
    const publishLocation = async (userId: number, coords: { lat: number; lng: number }) => {
        if (!presenceChannel) return;
        try {
            await presenceChannel.publish("user:location", {
                userId,
                lat: coords.lat,
                lng: coords.lng,
            });
        } catch (err) {
            console.error("Errore invio posizione:", err);
        }
    }

    /**
    * Disconnect and cleanup
    */
    const stopPresence = async (userId: number) => {
        if (!ably || !presenceChannel) return;

        try {
            // Leave the presence channel
            await presenceChannel.presence.leave();

            // Unsubscribe all listeners
            presenceChannel.presence.unsubscribe();
            presenceChannel.unsubscribe();
            ably.channels.get('public:users.location').unsubscribe();
            ably.channels.get('public:users.status').unsubscribe();
            ably.channels.get('public:users.profile').unsubscribe();
            ably.channels.get('public:users.logout').unsubscribe();
            ably.channels.get('private:messages.sent.' + userId).unsubscribe();
            ably.channels.get('presence:user-location').presence.unsubscribe();

            // Clear local data
            onlineUsers.value = [];
            markers.value.forEach(m => m.remove());
            markers.value.clear();

            // Close the Ably connection
            ably.close();
            ably = null;
            presenceChannel = null;

            console.log("[Presence stopped] all listeners removed, Ably closed");
        } catch (err) {
            console.error("Error while stopping presence:", err);
        }
    }

    const addMarker = (userId: number, marker: L.Marker) => {
        markers.value.set(userId, marker);
    }

    const getMarker = (userId: number): L.Marker<any> | undefined => {
        return markers.value.get(userId) as L.Marker<any> | undefined;
    }



    const addToast = (text: string | string[]) => {
        if (typeof text === 'string' && !Array.isArray(text)) {
            text = [text];
        } else if (text === '') {
            return;
        }
        text.forEach(elem => {
            const id = ++toastId;
            toasts.value.push({
                id,
                text: elem,
                show: false
            });
            const duration = 2500;
            setTimeout(() => startLeaveAnimation(id), duration);
        });

    }

    function startLeaveAnimation(id: number) {
        const toast = toasts.value.find(t => t.id === id);
        if (!toast) return;

        toast.show = true;

        // Match con durata .animate-slide-out (0.6s)
        setTimeout(() => {
            removeToast(id);
        }, 600);
    }

    function removeToast(id: number) {
        toasts.value = toasts.value.filter(t => t.id !== id);
    }

    return {
        onlineUsers,
        toasts,
        initPresence,
        stopPresence,
        addMarker,
        getMarker,
        addToast,
        removeToast,
        publishLocation,
    };
});
