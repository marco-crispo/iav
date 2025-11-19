import { Capacitor } from '@capacitor/core';
import { Geolocation } from '@capacitor/geolocation';
import { Camera } from '@capacitor/camera';
import { PushNotifications } from '@capacitor/push-notifications';

export async function requestAllPermissions() {
    console.log('[Permissions] Checking...');

    try {
        if (Capacitor.isNativePlatform()) {
            // --- LOCATION ---
            const geoPerm = await Geolocation.checkPermissions();
            if (geoPerm.location !== 'granted') {
                console.log('[Permissions] Requesting location...');
                await Geolocation.requestPermissions();
            }

            // --- CAMERA ---
            const camPerm = await Camera.checkPermissions();
            if (camPerm.camera !== 'granted') {
                console.log('[Permissions] Requesting camera...');
                await Camera.requestPermissions();
            }

            // --- PUSH NOTIFICATIONS ---
            const pushPerm = await PushNotifications.checkPermissions();
            if (pushPerm.receive !== 'granted') {
                console.log('[Permissions] Requesting push notifications...');
                await PushNotifications.requestPermissions();
            }

            console.log('[Permissions] All checked successfully');
        }
    } catch (err) {
        console.error('[Permissions] Error requesting permissions:', err);
    }
}
