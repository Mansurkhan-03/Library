<script setup>
defineProps({
    labelName: {
        type: String,
        required: true
    },
    inputType: {
        type: String,
        default: 'text'
    },
    placeholder: {
        type: String,
        default: 'Type here...'
    },
    errorMessage: {
        type: Boolean,
        default: false
    }
})

const model = defineModel();
</script>

<template>
    <label class="flex flex-col">
        <div>
            <span class="italic">{{ labelName }}</span>
            <span v-if="errorMessage" class="text-red-500 pl-2">*</span>
        </div>

        <input
            class="border px-2.5 py-2 rounded bg-white placeholder:text-my-gray text-black"
            :type="inputType"
            :placeholder
            v-model="model"
        />
    </label>
</template>
