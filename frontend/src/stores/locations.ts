// stores/locationStore.ts
import { defineStore } from 'pinia';
import { ref } from 'vue';
import { Geolocation } from '@capacitor/geolocation';
import { Capacitor } from '@capacitor/core';
import { App } from '@capacitor/app';
import { usePresenceStore } from "@/stores/presences";

export const useLocationStore = defineStore('location', () => {
    const userLocation = ref<{ lat: number; lng: number } | null>(null);
    const watchId = ref<string | null>(null);
    const isTracking = ref(false);

    /**
     * Start tracking user location
     */
    const startWatchingLocation = async (userId: number) => {
        try {
            if (isTracking.value) return; // evita doppi watcher
            isTracking.value = true;

            const watch = await Geolocation.watchPosition(
                { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 },
                (position, err) => {
                    if (err) {
                        console.error('Location error:', err);
                        return;
                    }

                    if (position) {
                        const coords = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        userLocation.value = coords;

                        // Publish live location updates
                        const presenceStore = usePresenceStore();
                        presenceStore.publishLocation(userId, coords);
                    }
                }
            );

            watchId.value = watch;
            console.log('[Location] Started watching position');
        } catch (e) {
            console.error('[Location] Error starting watcher:', e);
        }
    }

    /**
     * Stop the tracking
     */
    const stopWatchingLocation = async () => {
        if (watchId.value) {
            await Geolocation.clearWatch({ id: watchId.value });
            watchId.value = null;
            console.log('[Location] Stopped watching position');
        }
        isTracking.value = false;
    }

    /**
     * Managing app state (foreground / background)
     * Stop the GPS in background, restart in foreground
     */
    const setupAppLifecycleTracking = (userId: number) => {
        if (!Capacitor.isNativePlatform()) return;

        App.addListener('pause', async () => {
            console.log('[App] Paused — stopping location tracking');
            await stopWatchingLocation();
        });

        App.addListener('resume', async () => {
            console.log('[App] Resumed — restarting location tracking');
            await startWatchingLocation(userId);
        });
    }

    return {
        userLocation,
        startWatchingLocation,
        stopWatchingLocation,
        setupAppLifecycleTracking,
        isTracking,
    };
});
