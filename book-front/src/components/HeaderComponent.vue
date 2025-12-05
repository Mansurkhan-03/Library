<script setup>

import {useAuthorizationStore} from "@/stores/user/authorization.js";
import {useRouter} from "vue-router";
import {useI18n} from "vue-i18n";

const authorizationStore = useAuthorizationStore();
const router = useRouter();

const logout = () => {
    localStorage.removeItem('token');
    router.push({name: 'login'});
}

const { t, locale } = useI18n();

const isAdmin = JSON.parse(atob(localStorage.getItem('token').split('.')[1])).roles.includes('ROLE_ADMIN');

</script>

<template>
    <header class="flex justify-between items-center bg-my-gray px-10 z-10 h-20 sticky top-0 text-white mb-5 shadow-md">
        <a href="#" class="text-3xl font-semibold">Lo<span class="text-my-blue-gray">go</span></a>
        <nav class="hidden lg:block mx-auto">
            <ul class="flex gap-10">
                <li class="border-b-1 border-transparent cursor-pointer hover:border-white transition-all">
                    <RouterLink to="/" active-class="text-teal-600">{{ t('books') }}</RouterLink>
                </li>
                <li class="border-b-1 border-transparent cursor-pointer hover:border-white transition-all">
                    <RouterLink :to="{ name: 'add-book' }" active-class="text-teal-600">{{ t('addBook') }}</RouterLink>
                </li>
                <li class="border-b-1 border-transparent cursor-pointer hover:border-white transition-all">
                    <RouterLink :to="{ name: 'categories' }" active-class="text-teal-600">{{ t('category') }}</RouterLink>
                </li>
                <li v-if="isAdmin" class="border-b-1 border-transparent cursor-pointer hover:border-white transition-all">
                    <RouterLink :to="{ name: 'statistics' }" active-class="text-teal-600">{{ t('statistics') }}</RouterLink>
                </li>
            </ul>
        </nav>
        <div class="ml-auto mr-4 border rounded px-2 py-1">
            <select @change="locale = $event.target.value" :value="locale" class="form-select ">
                <option class="bg-my-gray" value="uz">UZ</option>
                <option class="bg-my-gray" value="ru">RU</option>
                <option class="bg-my-gray" value="en">ENG</option>
            </select>
        </div>

        <div>
            <div>
                <button @click="logout" class=" lg:inline px-8 py-2 bg-my-blue-gray rounded-lg hover:bg-white hover:text-my-blue-gray cursor-pointer transition-all mr-5">
                    {{ t('LogOut') }}
                </button>
            </div>
        </div>
    </header>
</template>

<style scoped>

</style>