import {defineStore} from "pinia";
import {computed, reactive} from "vue";
import {authorizedClient} from "@/api/axios.js";

export const useGetBooksStore = defineStore('getBooks', () => {
    const state = reactive({
        books: {
            all: [],
            totalItem: 0,
            pageCount: 0,
        },
        isLoading: false
    })

    const fetchAll = async (url = '') => {
        try {
            state.isLoading = true;
            const { data } = await authorizedClient.get('/books' + url);

            state.books.all = data.member;
            state.books.total = data.totalItems;
            state.books.pageCount = Math.ceil(data.totalItems / 8);
        } catch (err) {
            console.error(err);
            throw err;
        } finally {
            state.isLoading = false
        }
    }

    return {
        fetchAll,
        get: computed(() => state.books),
        isLoading: computed(() => state.isLoading)
    }
})
