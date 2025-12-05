import {defineStore} from "pinia";
import {computed, reactive} from "vue";
import {authorizedClient} from "@/api/axios.js";

export const useGetUsersStore = defineStore('getUsers', () => {
    const state = reactive({
        users: {
            all: [],
            totalItems: 0,
            pageCount: 0,
        },
    })

    const fetchUsers = async (url = '') => {
        try {
            const { data } = await authorizedClient.get('/users' + url);

            state.users.all = data.member;
            state.users.totalItems = data.totalItems;
            state.users.pageCount = Math.ceil(data.totalItems / 5);
        } catch (err) {
            console.error(err);
            throw err;
        }
    }

    return {
        fetchUsers,
        get: computed(() => state.users),
    }
})