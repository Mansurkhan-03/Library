<script setup>
import PaginationComponent from "@/components/PaginationComponent.vue";
import {useGetBooksStore} from "@/stores/book/getBooks.js";
import {useRoute} from "vue-router";
import {ref, watch} from "vue";
import {useI18n} from "vue-i18n";
import {useClickLikeStore} from "@/stores/book/clickLike.js";

const getBooksStore = useGetBooksStore();
const clickLikeStore = useClickLikeStore();

getBooksStore.fetchAll('?page=1');

const baseUrl = import.meta.env.VITE_API_URL;

const route = useRoute();
const username = JSON.parse(atob(localStorage.getItem('token').split('.')[1])).username;
const currentPage = ref(1);
const query = ref('?page=1');
watch(
    () => route.params.id,
    (val) => {
        if (!val) {
            query.value = '?page=1';
            getBooksStore.fetchAll(query.value);
        } else if (Number(val) === 0) {
            query.value = '/by-like?page=1&username=' + username;
            getBooksStore.fetchAll(query.value);
        } else {
            query.value = '/by-category?page=1&categoryId=' + val;
            getBooksStore.fetchAll(query.value);
        }
    },
    {immediate: true}
);

const changePage = (page) => {
    currentPage.value = page;
    query.value = query.value.replace(/page=\d+/, `page=${page}`)
    getBooksStore.fetchAll(query.value);
}

const nextLastPage = (incrementOrDecrement) => {
    currentPage.value += incrementOrDecrement;
    if (!currentPage.value) {
        currentPage.value = 1;
    } else if (currentPage.value > getBooksStore.get.pageCount) {
        currentPage.value--;
    } else {
        getBooksStore.fetchAll(query.value.replace(/page=\d+/, `page=${currentPage.value}`));
    }
}


const like = async (id) => {
    await clickLikeStore.fetchLike(id, username);
    await getBooksStore.fetchAll(query.value);
}

const isLiked = (value) => {
    return value.some(val => val.username === username);
}

const {t} = useI18n();
</script>

<template>
    <div class="bg-my-gray grow gap-5 p-5 grid grid-cols-12">
        <div v-for="book of getBooksStore.get.all" :key="book.id" class="bg-my-beige p-5 col-span-6 lg:col-span-4 xl:col-span-3">
            <div class="h-60 overflow-hidden mb-2">
                <img :src="`${baseUrl}${book.image.contentUrl}`" alt="kitob rasmi" class="h-full w-full object-cover">
            </div>
            <h4 class="text-lg font-semibold">{{ book.name }}</h4>
            <p class="text-sm text-black/70">{{ book.description }}</p>

            <div class="flex gap-3">
                <button class="w-full text-white hover:bg-white hover:text-my-blue-gray transition-all cursor-pointer  rounded-lg text-clip bg-my-blue-gray mt-5">
                    <RouterLink :to="{ name: 'book-info', params: { bookId: book.id } }" class="w-full block py-2">
                        {{ t('read') }}
                    </RouterLink>
                </button>

                <button v-if="isLiked(book.likes)" class="flex items-end" @click="like(book.id)">
                    <svg height="40px" viewBox="0 0 512 512" width="40px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                        <g id="_x31_66_x2C__Heart_x2C__Love_x2C__Like_x2C__Twitter">
                            <g><path d="M365.4,59.628c60.56,0,109.6,49.03,109.6,109.47c0,109.47-109.6,171.8-219.06,281.271    C146.47,340.898,37,278.568,37,169.099c0-60.44,49.04-109.47,109.47-109.47c54.73,0,82.1,27.37,109.47,82.1    C283.3,86.999,310.67,59.628,365.4,59.628z" style="fill:#FF7979;"/></g></g>
                        <g id="Layer_1"/>
                    </svg>
                    <span>{{ book.likes.length }}</span>
                </button>
                <button v-else class="flex items-end" @click="like(book.id)">
                    <svg height="40px" viewBox="0 0 512 512" width="40px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                        <g id="_x31_66_x2C__Heart_x2C__Love_x2C__Like_x2C__Twitter">
                            <g><path d="M365.4,59.628c60.56,0,109.6,49.03,109.6,109.47c0,109.47-109.6,171.8-219.06,281.271    C146.47,340.898,37,278.568,37,169.099c0-60.44,49.04-109.47,109.47-109.47c54.73,0,82.1,27.37,109.47,82.1    C283.3,86.999,310.67,59.628,365.4,59.628z" style="fill:#FFFFFF;"/></g>
                        </g>
                        <g id="Layer_1"/>
                    </svg>
                    <span>{{ book.likes.length }}</span>
                </button>
            </div>
        </div>
    </div>
    <PaginationComponent
        :pagination-count="getBooksStore.get.pageCount"
        class="col-span-6 lg:col-span-4 xl:col-span-3"
        @onSetPage="changePage"
        @on-previous-and-next-page="nextLastPage"
    />
</template>

<style scoped>

</style>