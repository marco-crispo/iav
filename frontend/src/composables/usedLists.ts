import axios from "axios";
import { API_URL } from "./envVars";

const relationshipStatus: Array<{ id: number; val: string }> = [
    { id: 1, val: 'Single' },
    { id: 2, val: 'In a relationship' },
    { id: 3, val: 'In a open relationship' },
    { id: 4, val: 'In more than one relationship' },
    { id: 5, val: 'It\'s complicated' },
    { id: 6, val: 'Married' },
    { id: 7, val: 'Widowed' },
    { id: 8, val: 'Divorced' },
    { id: 9, val: 'Separated' },
    { id: 10, val: 'dontwannasay' },
];

const sexualOrientation: Array<{ id: number; val: string }> = [
    { id: 1, val: 'Lesbian' },
    { id: 2, val: 'Gay' },
    { id: 3, val: 'Bisexual' },
    { id: 4, val: 'Transgender' },
    { id: 5, val: 'Queer' },
    { id: 6, val: 'Intersex' },
    { id: 7, val: 'Asexual' },
    { id: 8, val: '+' },
    { id: 9, val: 'dontwannasay' },
    { id: 10, val: 'Hetero' },
];


const getAvailabilities = async () => {
    return await axios.get(`${API_URL}/availability`, {
        params: {},
        headers: {
            // 'Authorization': `Bearer ${bearerToken}`
        }
    });
}

export { relationshipStatus, sexualOrientation, getAvailabilities };
