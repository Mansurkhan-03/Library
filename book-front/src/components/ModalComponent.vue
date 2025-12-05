<script setup>
import FormInput from "@/components/UI/FormInput.vue";
import FormButton from "@/components/UI/FormButton.vue";

const isOpen = defineModel('isOpen');
const categoryText = defineModel();

defineProps({
    acceptButtonText: {
        type: String,
        required: true
    },
    cancelButtonText: {
        type: String,
        required: true
    },
    modalTitle: {
        type: String,
        required: true
    },
    labelNameInput: {
        type: String,
        default: 'Name'
    },
    placeholderInput: {
        type: String,
        default: 'type here...'
    },
    error: {
        type: Boolean,
        default: false
    },
})

defineEmits(["on-accept"]);

</script>

<template>
    <div v-if="isOpen" @click.self="isOpen = false" class="fixed inset-0 z-50 bg-black/30 grid place-content-center">
        <div class="p-10 rounded bg-white flex flex-col gap-5 sm:w-150">
            <h3 class="text-2xl font-bold">{{ modalTitle }}</h3>

            <FormInput :error-message="error" :label-name="labelNameInput" v-model="categoryText" :placeholder="placeholderInput"/>

            <div class="flex gap-5 justify-end">
                <FormButton @click="$emit('on-accept')">{{ acceptButtonText }}</FormButton>
                <FormButton @click="isOpen = false; categoryText = ''">{{ cancelButtonText }}</FormButton>
            </div>
        </div>
    </div>
</template>