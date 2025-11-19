<template>
    <div ref="popupContent"></div>
</template>

<script setup lang="ts">
import { onMounted, ref, createApp, toRaw } from 'vue'
import L, { Map as LeafletMap } from 'leaflet'
import 'leaflet.markercluster';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import userPopup from './userPopup.vue'
import { User, Profile } from '@/types/defTypes';
import {stateIcons, stateBwIcons} from '@/composables/stateIcons'; // Import your state icons
import { usePresenceStore } from "@/stores/presences";

const presenceStore = usePresenceStore();

const props = defineProps<{
    data: { id: number; user_id: number; latitude: number; longitude: number, online: number,status: string, user: User };
    map: LeafletMap | null;
    bearerToken: string;
    markers: L.MarkerClusterGroup;
}>()

const popupContent = ref<HTMLDivElement | null>(null)
const data = props.data;
const bearerToken = props.bearerToken;
// const map = props.map;
// const markers = props.markers;
const { user_id, latitude, longitude, online, status, user } = data;
const marker: Map<number, L.Marker> = new Map();

// Remove marker on map
const removeMarker = (uid: number) => {
    const mrk = presenceStore.getMarker(uid);
    if (mrk) {
        console.log('Removing marker for user ID:', uid);
        props.markers.removeLayer(marker.get(uid)!);
        marker.delete(uid);
    }
};
// Set a new icon for the marker
const setMarkerIcon = (uid: number, status: string, online: number) => {
    console.log('Setting marker icon for user ID:', uid, status);
    const mrk = presenceStore.getMarker(uid);
    if (mrk) {
        const icon = online === 1 ? (stateIcons[status] || stateIcons['I feel lonely']) : (stateBwIcons[status] || stateBwIcons['I feel lonely']);
        mrk.setIcon(icon);
        // props.markers.addLayer(marker.get(user_id)!);
        props.map!.addLayer(props.markers);

    }
};
// Update an existing marker
const updateMarkerPosition = (uid: number, lat: number, lng: number) => {
    const mrk = presenceStore.getMarker(uid);
    if (mrk) {
        // props.markers.removeLayer(marker.get(uid)!);
        console.log('Updating marker position for user ID:', uid, lat, lng);
        mrk.setLatLng([lat, lng]);
        // props.markers.addLayer(marker.get(user_id)!);
        props.map!.addLayer(props.markers);
    }
};

const updateProfileOnPopup = (uid: number, profile: Profile) => {
    const mrk = presenceStore.getMarker(uid);
    if (mrk) {
        const container = document.createElement("div");
        mrk.getPopup()?.setContent(container);
        // Mounting of the popup component
        createApp(userPopup, {
            profile: profile,
            bearerToken: bearerToken
        }).mount(container);
    }
};

defineExpose({
    removeMarker,
    setMarkerIcon,
    updateMarkerPosition,
    updateProfileOnPopup
});

onMounted(() => {
    const icon = online === 1 ? (stateIcons[status] || stateIcons['I feel lonely']) : (stateBwIcons[status] || stateBwIcons['I feel lonely']);

    if (presenceStore.getMarker(user_id)) {

        console.log('Marker already exists for user ID:', user_id);
        updateMarkerPosition(user_id, latitude, longitude);

    } else {

        marker.set(user_id, L.marker([latitude, longitude], { icon }));
        props.markers.addLayer(marker.get(user_id)!);
        props.map!.addLayer(toRaw(props.markers));
        presenceStore.addMarker(user_id, marker.get(user_id)!);

        // container placeholder
        const container = document.createElement("div");
        marker.get(user_id)!.bindPopup(container, {
            maxWidth: 300,
            minWidth: 250,
            className: 'user-popup',
        });
        // Mounting of the popup component
        createApp(userPopup, {
            profile: user.profile,
            bearerToken: bearerToken
        }).mount(container);


    }
});
</script>
