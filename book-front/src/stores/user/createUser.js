import {defineStore} from "pinia";
import {computed, ref} from "vue";
import {authorizedClient} from "@/api/axios.js";

export const useCreateUserStore = defineStore("createUser", () => {
    const isLoading = ref(false);

    const create = async (data) => {
        try {
            isLoading.value = true;
            return await authorizedClient.post('/users/my', data);
        } catch (err) {
            console.log(err);
            throw err;
        } finally {
            isLoading.value = false;
        }
    }

    return {
        create,
        isLoading: computed(() => isLoading.value),
    }
})