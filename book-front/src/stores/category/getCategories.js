import {defineStore} from "pinia";
import {computed, reactive} from "vue";
import {authorizedClient} from "@/api/axios.js";

export const useGetCategoryStore = defineStore('CategoryStore', () => {
    const state = reactive({
        categories: {
            all: [],
            total: 0
        },
        isLoading: false
    })

    const fetchAll = async () => {
        try {
            state.isLoading = true;
            const { data } = await authorizedClient.get('/categories');

            state.categories.all = data.member;
            state.categories.total = data.totalItems;
        } catch (err) {
            console.error(err);
            throw err;
        } finally {
            state.isLoading = false
        }
    }

    return {
        fetchAll,
        get: computed(() => state.categories),
        isLoading: computed(() => state.isLoading)
    }
})
