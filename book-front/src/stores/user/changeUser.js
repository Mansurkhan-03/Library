import {defineStore} from "pinia";
import {authorizedClient} from "@/api/axios.js";

export const useChangeUserStore = defineStore('changeUser', () => {
    const change = async (id, data) => {
        try {
            return await authorizedClient.patch(`/users/update?id=${id}`, data);
        } catch (err) {
            console.error(err);
            throw err;
        }
    }

    return {
        change,
    }
})