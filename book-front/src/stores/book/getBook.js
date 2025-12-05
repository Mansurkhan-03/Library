import {defineStore} from "pinia";
import {computed, reactive} from "vue";
import {authorizedClient} from "@/api/axios.js";

export const useGetBookStore = defineStore('getBook', () => {
    const state = reactive({
        book: {},
        isLoading: false
    })

    const fetchBook = async (id) => {
        try {
            state.isLoading = true;
            const { data } = await authorizedClient.get('/books/' + id);

            state.book = data;
        } catch (err) {
            console.error(err);
            throw err;
        } finally {
            state.isLoading = false;
        }
    }

    return {
        fetchBook,
        getBook: computed(() => state.book),
        isLoading: computed(() => state.isLoading)
    }
})
