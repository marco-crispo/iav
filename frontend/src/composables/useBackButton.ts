// src/composables/useBackButton.ts
import { App } from "@capacitor/app";
import { useRouter } from "vue-router";

let lastBackPress = 0;

export function useBackButton() {
  const router = useRouter();

  const registerBackButton = () => {
    App.addListener("backButton", async () => {
      const route = router.currentRoute.value;

      if (route.name === "home" || route.path === "/home") {
        const now = Date.now();

        if (now - lastBackPress < 1500) {
          App.exitApp();
        } else {
          lastBackPress = now;
          router.back();
        }
      } else {
          router.back();
      }
    });
  };

  const removeBackButtonListener = () => {
    App.removeAllListeners();
  };

  return { registerBackButton, removeBackButtonListener };
}
