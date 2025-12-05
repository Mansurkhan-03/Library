<script setup>

defineProps({
    paginationCount: {
        type: Number,
        required: true
    }
})

defineEmits(["onSetPage", "onPreviousAndNextPage"]);
</script>

<template>
    <div class="col-span-12">
        <div class="flex gap-4 w-fit">
            <button
                @click="$emit('onPreviousAndNextPage', -1)"
                class="p-2 rounded-md bg-my-beige size-10 cursor-pointer hover:text-my-beige hover:bg-transparent hover:ring-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18l-6-6l6-6"/>
                </svg>
            </button>
            <button
                v-for="i in paginationCount"
                :key="i"
                @click="$emit('onSetPage', i)"
                class="p-2 rounded-md bg-my-beige size-10 cursor-pointer hover:text-my-beige hover:bg-transparent hover:ring-1"
            >{{ i }}</button>

            <button
                @click="$emit('onPreviousAndNextPage', 1)"
                class="p-2 rounded-md bg-my-beige size-10 cursor-pointer hover:text-my-beige hover:bg-transparent hover:ring-1"
            >
                <svg class="rotate-180" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18l-6-6l6-6"/>
                </svg>
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>