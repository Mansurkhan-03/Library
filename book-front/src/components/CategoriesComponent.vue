<script setup>

import {useGetCategoryStore} from "@/stores/category/getCategories.js";

const categoryStore = useGetCategoryStore();

categoryStore.fetchAll();
</script>

<template>
    <aside class="py-5 bg-my-gray  md:w-72 text-white flex-none h-fit md:sticky md:top-[100px]">
        <ul>
            <li class="py-2 px-5 cursor-pointer hover:bg-my-blue-gray-light hover:text-gray-800">
                <RouterLink
                    :to="{ name: 'by-like', params: { id: 0 } }"
                    active-class="text-teal-600"
                >
                    Favorite books
                </RouterLink>
            </li>
            <li v-if="categoryStore.isLoading" class="py-2 px-5 cursor-pointer hover:bg-my-blue-gray-light hover:text-gray-800">Yuklanyapti...</li>
            <li v-else-if="!categoryStore.get.total" class="py-2 px-5 cursor-pointer hover:bg-my-blue-gray-light hover:text-gray-800">Ma'lumot topilmadi.</li>
            <li
                v-else
                v-for="category of categoryStore.get.all"
                :key="category.id"
                class="py-2 px-5 cursor-pointer hover:bg-my-blue-gray-light hover:text-gray-800"
            >
                <RouterLink
                    :to="{ name: 'by-category', params: { id: category.id } }"
                    active-class="text-teal-600"
                >
                    {{ category.name }}
                </RouterLink>
            </li>
        </ul>
    </aside>
</template>
