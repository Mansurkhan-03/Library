import {defineStore} from "pinia";
import {computed, ref} from "vue";
import axios from "axios";
import {authorizedClient} from "@/api/axios.js";

export const useAddFileStore = defineStore('addFile', () => {
    const isLoading = ref(false);

    const addFile = async (data) => {
        const file = new FormData();
        file.append('file', data);

        try {
            isLoading.value = true;
            const { data } = await authorizedClient.post('/media_objects', file);
            return data;
        } catch (err) {
            console.error(err);
            throw err;
        } finally {
            isLoading.value = false;
        }
    }

    return {
        addFile,
        isLoading: computed(() => isLoading.value),
    }
})
