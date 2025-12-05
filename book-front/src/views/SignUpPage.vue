<script setup>
import FormInput from "@/components/UI/FormInput.vue";
import FormButton from "@/components/UI/FormButton.vue";
import {useCreateUserStore} from "@/stores/user/createUser.js";
import {useRouter} from "vue-router";
import * as yup from "yup";
import {useField, useForm} from "vee-validate";

const createUserStore = useCreateUserStore();
const router = useRouter();

const schema = yup.object({
    username: yup.string().required('username kiriting!'),
    password: yup.string().required('Parol kiriting!').min(6),
    email: yup.string().email("Email noto'g'ri kiritilgan").required('Email kiriting!'),
    age: yup.number().integer().required('Yosh kiriting!').moreThan(18).lessThan(50),
    phone: yup.string().matches(/\d/).min(9).max(12),
    gender: yup.string().required('Jinsni tanlang!')
});

const { errors, handleSubmit } = useForm({ validationSchema: schema });

const { value: username } = useField('username');
const { value: password } = useField('password');
const { value: email } = useField('email');
const { value: age } = useField('age');
const { value: phone } = useField('phone');
const { value: gender } = useField('gender');

const create = handleSubmit(async (values) => {
    await createUserStore.create({ ...values, age: parseInt(values.age) });
    await router.push({ name: 'login' });
})

</script>

<template>
    <div class="min-h-dvh grid place-content-center">
        <form @submit.prevent="create" class="flex flex-col max-w-100 w-full sm:min-w-100 gap-5 bg-my-gray p-10 rounded-2xl text-white">
            <h2 class="text-center text-2xl font-medium">Registratsiya</h2>

            <FormInput :error-message="!!errors.username" v-model="username" label-name="Username" placeholder="Foydalanuvchi nomini kiriting..." />
            <FormInput :error-message="!!errors.password" v-model="password" input-type="password" label-name="Parol" placeholder="Parol kiriting..." />
            <FormInput :error-message="!!errors.email" v-model="email" input-type="email" label-name="Email" placeholder="Email kiriting..." />
            <FormInput :error-message="!!errors.age" v-model="age" label-name="Yosh" placeholder="Yoshni kiriting..." />
            <FormInput :error-message="!!errors.phone" v-model="phone" label-name="Telefon raqam" placeholder="Raqamingizni kiriting..." />

            <div class="flex justify-self-end-safe gap-5">
                <label for="male"><input v-model="gender" type="radio" name="gender" value="male" id="male" class="mr-1" />Male</label>
                <label for="female"><input v-model="gender" type="radio" name="gender" value="female" id="female" class="mr-1" />Female</label>
            </div>

            <FormButton :is-loading="createUserStore.isLoading" :disabled="createUserStore.isLoading">Ro'yxatdan o'tish</FormButton>
        </form>
    </div>
</template>