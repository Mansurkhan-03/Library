<script setup>
import FormInput from "@/components/UI/FormInput.vue";
import FormButton from "@/components/UI/FormButton.vue";

const isOpen = defineModel('isOpen');
const username = defineModel('username');
const password = defineModel('password');
const age = defineModel('age');
const phone = defineModel('phone');

defineProps({
    errorUsername: {
        type: Boolean,
        default: false
    },errorPassword: {
        type: Boolean,
        default: false
    },errorAge: {
        type: Boolean,
        default: false
    },errorPhone: {
        type: Boolean,
        default: false
    },
})

defineEmits(["on-accept"]);

</script>

<template>
    <div v-if="isOpen" @click.self="isOpen = false" class="fixed inset-0 z-50 bg-black/30 grid place-content-center">
        <div class="p-10 rounded bg-white flex flex-col gap-5 sm:w-150">
            <h3 class="text-2xl font-bold">Userni tahrirlash</h3>

            <FormInput :error-message="errorUsername" label-name="Username" v-model="username" placeholder=""/>
            <FormInput :error-message="errorPassword" label-name="Parol" v-model="password" placeholder=""/>
            <FormInput :error-message="errorAge" label-name="Yosh" v-model="age" placeholder=""/>
            <FormInput :error-message="errorPhone" label-name="Telefon" v-model="phone" placeholder=""/>

            <div class="flex gap-5 justify-end">
                <FormButton @click="$emit('on-accept')">O'zgartirish</FormButton>
                <FormButton @click="isOpen = false">Bekor qilish</FormButton>
            </div>
        </div>
    </div>
</template>