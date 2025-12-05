import {defineStore} from "pinia";
import {authorizedClient} from "@/api/axios.js";

export const useAddCategoryStore = defineStore('addCategory', () => {
    const add = async (data) => {
        try {
            return await authorizedClient.post(`/categories`, data);
        }catch (err) {
            console.error(err);
            throw err;
        }
    }

    return {
        add,
    }
})
