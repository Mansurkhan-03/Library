<script setup>
import {useRoute} from "vue-router";
import {useGetBookStore} from "@/stores/book/getBook.js";
import FormButton from "@/components/UI/FormButton.vue";
import {useAddCommentStore} from "@/stores/book/addComment.js";
import {reactive} from "vue";
import {useGetUserStore} from "@/stores/user/getCurrentUser.js";

const bookStore = useGetBookStore();
const route = useRoute();
const addCommentStore = useAddCommentStore();
const getUserStore = useGetUserStore();

getUserStore.fetchUser();

const user = getUserStore.getUser;
const data = reactive({
    author: 'api/users/' + user.id,
    text: '',
    book: ''
})

bookStore.fetchBook(route.params.bookId);

const addComment = async () => {
    data.book = 'api/books/' + route.params.bookId;
    await addCommentStore.add(data);
    data.text = '';
    await bookStore.fetchBook(route.params.bookId);
}
</script>

<template>
    <div class="bg-my-gray grow gap-5 p-5 text-white">
        <h1 class="text-4xl font-semibold mb-10">{{ bookStore.getBook.name }}</h1>
        <p>{{ bookStore.getBook.text }}</p>
    </div>

    <div class="flex flex-col bg-my-gray grow gap-5 p-5 text-white">
        <h2 class="text-2xl font-medium">Comments</h2>
        <div v-for="comment of bookStore.getBook.comment">
            <div class="flex items-center gap-1">
                <svg height="24px" width="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title/><circle cx="12" cy="8" fill="#FFFFFF" r="4"/><path d="M20,19v1a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V19a6,6,0,0,1,6-6h4A6,6,0,0,1,20,19Z" fill="#FFFFFF"/></svg>
<!--                <svg height="16px" width="16px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M272 304h-96C78.8 304 0 382.8 0 480c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32C448 382.8 369.2 304 272 304zM48.99 464C56.89 400.9 110.8 352 176 352h96c65.16 0 119.1 48.95 127 112H48.99zM224 256c70.69 0 128-57.31 128-128c0-70.69-57.31-128-128-128S96 57.31 96 128C96 198.7 153.3 256 224 256zM224 48c44.11 0 80 35.89 80 80c0 44.11-35.89 80-80 80S144 172.1 144 128C144 83.89 179.9 48 224 48z"/></svg>-->
                <h3 class="font-medium font-serif">{{ comment.author.username }}</h3>
            </div>
            <p class="pl-5">{{ comment.text }}</p>
        </div>
        <div class="flex flex-col mt-auto gap-5">
            <textarea v-model="data.text" class="rounded h-30 bg-white text-black " placeholder="Add comment..."></textarea>
            <FormButton @click.prevent="addComment" >Add comment</FormButton>
        </div>
    </div>
</template>

<style scoped>

</style>