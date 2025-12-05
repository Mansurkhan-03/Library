import {defineStore} from "pinia";
import axios from "axios";
import {computed, ref} from "vue";

export const useAuthorizationStore = defineStore('authorization', () => {
    const isLoading = ref(false);

    const auth = async authData => {
        try {
            isLoading.value = true;
            const response = await axios.post('http://localhost:9999/api/users/auth', authData);

            localStorage.setItem('token', response.data.token);
        }catch (err) {
            console.error(err);
            throw err;
        } finally {
            isLoading.value = false;
        }
    }

    return {
        auth,
        isLoading: computed(() => isLoading.value),
    };
})