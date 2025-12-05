import {defineStore} from "pinia";
import {computed, reactive} from "vue";
import {authorizedClient} from "@/api/axios.js";

export const useGetUserStore = defineStore('getUsers', () => {
    const state = reactive({
        user: {},
    })

    const fetchUser = async () => {
        try {
            const { data } = await authorizedClient.get('/users/about-me');

            state.user = data;
        } catch (err) {
            console.error(err);
            throw err;
        }
    }

    return {
        fetchUser,
        getUser: computed(() => state.user),
    }
})
