import { loadingController } from '@ionic/vue';
let loading: HTMLIonLoadingElement | null = null;

const showLoading = async () => {
    loading = await loadingController.create({
        message: 'Loading data and content...',
        duration: 3000,
    });

    loading.present();
};

const hideLoading = async () => {
    loading?.dismiss();
};

export { showLoading, hideLoading };
