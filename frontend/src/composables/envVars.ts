const API_URL = import.meta.env.VITE_API_URL;
const SOCKET_URL = import.meta.env.VITE_SOCKET_URL;
const WS_URL = import.meta.env.VITE_WS_URL;
const STORAGE_URL = import.meta.env.VITE_STORAGE_URL;
const AGE_LOWER = import.meta.env.VITE_AGE_LOWER_VAL;
const AGE_UPPER = import.meta.env.VITE_AGE_UPPER_VAL;
const DEFAULT_RADIUS_KM = import.meta.env.VITE_DEFAULT_RADIUS_KM;

const formatOptions = {
    date: {
        weekday: 'short', // allowed: "short" | "long" | "narrow"
        month: '2-digit',    // allowed: "numeric" | "2-digit" | "long" | "short" | "narrow"
        day: '2-digit',   // allowed: "numeric" | "2-digit"
        year: 'numeric' // allowed: "numeric" | "2-digit"
    },
    time: {
        hour: '2-digit', // allowed: "numeric" | "2-digit"
        minute: '2-digit', // allowed: "numeric" | "2-digit"
        second: '2-digit' // allowed: "numeric" | "2-digit"
    }
};

export {
    API_URL,
    SOCKET_URL,
    WS_URL,
    STORAGE_URL,
    DEFAULT_RADIUS_KM,
    AGE_LOWER,
    AGE_UPPER,
    formatOptions
};
