import {defineStore} from "pinia";
import {authorizedClient} from "@/api/axios.js";

export const useAddCommentStore = defineStore('addComment', () => {
    const add = async (data) => {
        try {
            return await authorizedClient.post(`/comments`, data);
        } catch (err) {
            console.error(err);
            throw err;
        }
    }

    return {
        add,
    }
})
