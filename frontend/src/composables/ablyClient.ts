import * as Ably from "ably";
import { API_URL } from "./envVars";
let ably: Ably.Realtime;

const initAbly = (bearerToken: string) => {
    if (!ably) {
        try {
            ably = new Ably.Realtime({
                authUrl: `${API_URL}/ably-token`, // Laravel endpoint
                authHeaders: {
                    Authorization: `Bearer ${bearerToken}`,
                },
            });
        } catch (error) {
            const message = error instanceof Error ? error.message : String(error);
            throw new Error("Error initializing Ably client. " + message);
        }
    }
    return ably;
}

const getAbly = (bearerToken: string) => {
    if (!ably) {
        ably = initAbly(bearerToken);
    }
    return ably;
}

export { initAbly, getAbly };
