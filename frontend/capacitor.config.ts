import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
    appId: 'com.iav.app',
    appName: 'I Am Available',
    webDir: '../public/app',
    bundledWebRuntime: false,
    server: {
        // 👇 in sviluppo, usa il server Vite
        url: process.env.NODE_ENV === 'development'
            ? 'http://localhost:5173'
            : undefined,
        cleartext: true, // necessario per debug su Android
    },
    android: {
        path: 'android', // dove viene generato il progetto Android
    },
    ios: {
        path: 'ios', // dove viene generato il progetto iOS
    },
    plugins: {
        Geolocation: {
            permissions: ['location', 'background'],
        },
        LocalNotifications: {
            smallIcon: 'ic_stat_icon_config_sample',
            iconColor: '#488AFF',
            sound: 'beep.wav'
        }
    }
};

export default config;
