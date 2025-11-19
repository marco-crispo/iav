import router from '@/router';
import { Profile, User, UserLocation } from '@/types/defTypes';
import { API_URL } from './envVars';
import axios from 'axios';

const authCheck = (): { bearerToken: string | ''; user: User; userLocation: UserLocation | null; profile: Profile; } => {
    // Check if the user is authenticated
    const bearerToken: string = localStorage.getItem('token') || '';
    if (!bearerToken) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('userLocation');
        localStorage.removeItem('profile');
        if (router.currentRoute.value.path !== '/register') {
            router.push('/login'); // Redirect to login page if not authenticated
        }
        // router.push('/login'); // Redirect to login page if not authenticated
        return { bearerToken: '', user: {} as User, userLocation: null, profile: {} as Profile };
    }

    // Retrieve user information from local storage
    const user: User = JSON.parse(localStorage.getItem('user') || 'null');
    const userLocation: UserLocation | null = JSON.parse(localStorage.getItem('userLocation') || 'null');
    const profile: Profile = JSON.parse(localStorage.getItem('profile') || 'null');

    return { bearerToken, user, userLocation, profile };
}

const isEmailVerified = async (bearerToken : string) => {

    try {
        const { data } = await axios.get(`${API_URL}/is-email-verified`, {
            headers: { Authorization: `Bearer ${bearerToken}` }
        });

        return data.success as boolean;
    } catch (err) {
        return false;
    }
}

export { authCheck, isEmailVerified };
