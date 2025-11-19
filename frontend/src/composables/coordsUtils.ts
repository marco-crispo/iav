import { Capacitor } from '@capacitor/core';
import { Geolocation } from '@capacitor/geolocation';

const getDistanceKm = (lat1: number, lon1: number, lat2: number, lon2: number): number => {
    const R = 6371; // raggio della Terra in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) *
        Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

const getPersonalCurrentPosition = (): Promise<{ lat: number; lng: number }> => {
    return new Promise((resolve, reject) => {
        try {
            // Se siamo su un dispositivo nativo (Capacitor)
            if (Capacitor.isNativePlatform()) {
                Geolocation.getCurrentPosition({ enableHighAccuracy: true }).then(position => {
                    resolve({
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    });
                }).catch(err => reject(err));
            } else {
                // Se siamo su browser
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve({
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        });
                });
            }
      } catch (error) {
            reject(error);
        }
    });
}

export { getDistanceKm, getPersonalCurrentPosition };
