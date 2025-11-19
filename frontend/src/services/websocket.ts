import axios from "axios";
import { API_URL } from '@/composables/envVars';


type WebSocketEvent = {
    event: string;
    data: any;
};

type MessageHandler = (data: WebSocketEvent) => void;

class WebSocketClient {
    private socket: WebSocket | null = null;
    private handlers: MessageHandler[] = [];

    async connect(url: string) {

        const { data } = await axios.post(`${API_URL}/ws/token`, {}, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`
            }
        });

        this.socket = new WebSocket(`${url}?token=${data.ws_token}`);
        // this.socket = new WebSocket(url);

        this.socket.onopen = () => {
            console.log('[WebSocket] Connected');
        };

        this.socket.onmessage = (message) => {
            try {
                const payload: WebSocketEvent = JSON.parse(message.data);
                this.handlers.forEach((handler) => handler(payload));
            } catch (error) {
                console.error('[WebSocket] Invalid message', error);
            }
        };

        this.socket.onclose = () => {
            console.warn('[WebSocket] Disconnected');
            setTimeout(() => this.connect(url), 3000); // tentativo riconnessione
        };

        this.socket.onerror = (error) => {
            console.error('[WebSocket] Error:', error);
        };
    }

    onMessage(handler: MessageHandler) {
        this.handlers.push(handler);
    }

    disconnect() {
        this.socket?.close();
        this.socket = null;
        this.handlers = [];
    }
}

export const wsClient = new WebSocketClient();
