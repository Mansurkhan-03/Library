import {defineStore} from "pinia";
import {authorizedClient} from "@/api/axios.js";

export const useClickLikeStore = defineStore('clickLike', () => {


    const fetchLike = async (id, username) => {
        try {
            return await authorizedClient.patch(`/books/change_likes?id=${id}&username=` + username, {});
        } catch (err) {
            console.error(err);
            throw err;
        }
    }

    return {
        fetchLike,
    }
})