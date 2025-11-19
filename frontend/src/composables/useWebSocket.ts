import { onMounted, onBeforeUnmount } from 'vue';
import { wsClient } from '@/services/websocket';
import { WS_URL } from './envVars';

export default function (handler: (event: { event: string; data: any }) => void) {

  onMounted(() => {
    wsClient.connect(WS_URL);
    wsClient.onMessage(handler);
  });

  onBeforeUnmount(() => {
    wsClient.disconnect();
  });
}
