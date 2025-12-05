import {defineStore} from "pinia";
import {authorizedClient} from "@/api/axios.js";

export const useChangeUserPasswordStore = defineStore('changeUserPassword', () => {
    const change = async (url, data) => {
        try {
            return await authorizedClient.patch(`/users/update_password${url}`, data);
        } catch (err) {
            if (err.response.data.detail === "User not found") {
                alert("Foydalanuvchi topilmadi!")
            }
            console.error(err);
            throw err;
        }
    }

    return {
        change,
    }
})