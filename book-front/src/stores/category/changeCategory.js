import {defineStore} from "pinia";
import {authorizedClient} from "@/api/axios.js";

export const useChangeCategoryStore = defineStore('changeCategory', () => {
    const change = async (id, data) => {
        try {
            return await authorizedClient.patch(`/categories/${id}`, data);
        } catch (err) {
            console.error(err);
            throw err;
        }
    }

    return {
        change,
    }
})
