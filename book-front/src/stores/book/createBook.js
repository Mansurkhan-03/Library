import {defineStore} from "pinia";
import {authorizedClient} from "@/api/axios.js";
import {computed, ref} from "vue";

export const useCreateBookStore = defineStore('addCategory', () => {
    const isLoading = ref(false);

    const createBook = async (data) => {
        try {
            isLoading.value = true;

            return await authorizedClient.post(`/books`, data);
        }catch (err) {
            console.error(err);
            throw err;
        } finally {
            isLoading.value = false;
        }
    }

    return {
        createBook,
        isLoading: computed(() => isLoading.value),
    }
})
