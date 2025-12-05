import {defineStore} from "pinia";
import {authorizedClient} from "@/api/axios.js";

export const useDeleteCategoryStore = defineStore('deleteCategory', () => {
    const deleteCategory = async (id) => {
        try {
            return await authorizedClient.delete(`/categories/${id}`);
        } catch (err) {
            console.error(err);
            throw err;
        }
    }

    return {
        deleteCategory
    }
})
