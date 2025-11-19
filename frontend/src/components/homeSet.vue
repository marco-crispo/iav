<template>
    <div id="map" class="map-container"></div>
</template>

<script setup lang="ts">
import { onMounted, onUpdated, onUnmounted, ref, createApp } from 'vue';
import { API_URL } from '@/composables/envVars';
import { User, UserLocation, Profile, Filters } from '@/types/defTypes';
import L, { Map as LeafletMap } from 'leaflet';
import { getAbly } from '@/composables/ablyClient';
import axios from 'axios';
import { getPersonalCurrentPosition, getDistanceKm } from '@/composables/coordsUtils';
import userMarker from './userMarker.vue';
import { showLoading, hideLoading } from '@/composables/uxUtils';
import { usePresenceStore } from "@/stores/presences";
import { useLocationStore } from "@/stores/locations";

const props = defineProps<{
    bearerToken: string;
    user: User;
    userLocation: UserLocation | null;
    profile: Profile;
    filters: Filters;
}>();

const presenceStore = usePresenceStore();
const locationStore = useLocationStore();

const bearerToken = props.bearerToken;
const currentUser = props.user;
const markers: L.MarkerClusterGroup = L.markerClusterGroup();
const myPosition = ref<{ lat: number, lng: number } | null>(null);
const markerInstance = ref<any>(null);

let map: LeafletMap | null = null;
let currentUserLocation = props.userLocation;

onMounted(async () => {
    const ably = getAbly(bearerToken);

    ably.connection.once('connected', async () => {


        // Initialize map and other setup
        map = L.map('map');
        map.setView([45.4642, 9.1900], 19); // Default to Milan coordinates

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        setTimeout(async () =>
        {
            map?.invalidateSize();
            setCoords(); // Set initial coordinates
            await presenceStore.initPresence(bearerToken, currentUser, markerInstance);
            locationStore.setupAppLifecycleTracking(currentUser.id);
            await locationStore.startWatchingLocation(currentUser.id);

        }, 1500);
    });

    // map.on("popupopen", (event) =>{
    //     const popupPixelCoords = map!.project(event.popup.getLatLng() as L.LatLng); // get the pixel coordinates of the popup's geographical location
    //     const popupHeight = event.popup.getElement()!.clientHeight;
    //     popupPixelCoords.y -= popupHeight+100; // move the popup vertical axis location down (distance: half of popup height)
    //     map!.panTo(map!.unproject(popupPixelCoords), { animate: true }); // pan to calculated location
    // });
});

onUpdated(() => {
    setTimeout(async () =>
    {
        map?.invalidateSize();
        setCoords(); // Set initial coordinates
        await presenceStore.initPresence(bearerToken, currentUser, markerInstance);
        locationStore.setupAppLifecycleTracking(currentUser.id);
        await locationStore.startWatchingLocation(currentUser.id);
    }, 1500);
});

onUnmounted(async () => {
    console.log('Unmounting homeSet component, cleaning up Ably subscriptions and presence...');
    await locationStore.stopWatchingLocation();
});

const setCoords = async () => {
    try {
        showLoading();

        myPosition.value = await getPersonalCurrentPosition();

        const posUrl = currentUserLocation ? `${API_URL}/position/${currentUserLocation.id}` : `${API_URL}/position`;
        await axios.post(posUrl, {
            user_id: currentUser.id,
            latitude: myPosition.value.lat,
            longitude: myPosition.value.lng,
        },
        {
            headers: {
                'Authorization': `Bearer ${bearerToken}`
            }
        })
        .then((response) => {
            currentUserLocation = response.data.data;
            localStorage.setItem('userLocation', JSON.stringify(currentUserLocation));
            console.log('Position updated:', currentUserLocation);
            updateUsersPosition();
        })
        .catch((error) => {
            presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Position update error']);
            console.error('Error:', error);
        })
        .finally(() => {
            hideLoading();
        });

    } catch (err) {
        console.error('Errore aggiornamento posizione:', err);
        hideLoading();
    }
}

const updateUsersPosition = async () => {
    try {
        if (!myPosition.value) {
            myPosition.value = await getPersonalCurrentPosition();
        }
        await axios.get(`${API_URL}/position/nearby`, {
            params: {
                latitude: myPosition.value.lat,
                longitude: myPosition.value.lng,
                filters: props.filters
            },
            headers: {
                'Authorization': `Bearer ${bearerToken}`
            }
        }).then((response) => {
            response.data.data = response.data.data || [];
            console.log('Nearby users:', response.data.data);
            const data = response.data.data;
            // markers.clearLayers();
            data.forEach((element: { id: number; user_id: number; latitude: number; longitude: number; online: number; status: string; user: User }) => {
                element.status = element.user.profile?.availability.status || 'I feel lonely';
                // element.online = onlineUsers.value.find(u => u.id === element.user_id) ? 1 : 0;
                updateOrCreateMarker(element);
            });
            if (map) map.locate({ setView: true, maxZoom: 19 });

        }).catch((error) => {
            presenceStore.addToast(error.response?.data?.errors ? error.response.data.errors : ['Login error']);
            console.error('Error:', error);
        })
        .finally(() => {
            //
        });
    } catch (error) {
        console.error('Error fetching user positions:', error);
    }
};

const updateOrCreateMarker = async (data: { id: number; user_id: number; latitude: number; longitude: number, online: number, status: string, user: User }) => {
    if (!myPosition.value) return;

    const distance = getDistanceKm(myPosition.value.lat, myPosition.value.lng, data.latitude, data.longitude);
    const matchesStatus = props.filters.status === '' || data.status === props.filters.status;
    if (distance <= props.filters.radiusKm && (matchesStatus || currentUserLocation?.id === data.id)) {
        markerInstance.value?.removeMarker(data.user_id);

        const div = document.createElement('div');
        markerInstance.value = createApp(userMarker, { data, map, bearerToken, markers }).mount(div);
    }
};

</script>

<style scoped>
.map-container {
    min-height: 600px;
    height: 95vh;
}
</style>
