<script setup>
import FormInput from "@/components/UI/FormInput.vue";
import FormButton from "@/components/UI/FormButton.vue";
import {useChangeUserPasswordStore} from "@/stores/user/changeUserPassword.js";
import {reactive} from "vue";
import {useRouter} from "vue-router";
import {object, string} from "yup";
import {useField, useForm} from "vee-validate";

const changeUser = useChangeUserPasswordStore();
const router = useRouter();
const schema = object({
    email: string().email("Email noto'g'ri kiritilgan").required('Email kiriting!'),
    password: string().required('Parol kiriting!').min(6),
});

const { errors, handleSubmit } = useForm({ validationSchema: schema });

const {value: email} = useField('email');
const {value: password} = useField('password');

const changePassword = handleSubmit(async () => {
    await changeUser.change('?email=' + email, { password: password });
    await router.push({name: 'login'});
})
</script>

<template>
    <div class="min-h-dvh grid place-content-center">
        <form @submit.prevent="changePassword" class="flex flex-col max-w-100 w-full sm:min-w-100 gap-5 bg-my-gray p-10 rounded-2xl text-white">
            <h2 class="text-center text-2xl font-medium">Parolni tiklash</h2>

            <FormInput :error-message="!!errors.email" v-model="email" input-type="email" label-name="Email" placeholder="email kiriting..." />
            <FormInput :error-message="!!errors.password" v-model="password" input-type="password" label-name="Yangi parol" placeholder="yangi parol kiriting..." />
            <FormButton>O'zgartirish</FormButton>
        </form>
    </div>
</template>

<style scoped>

</style>