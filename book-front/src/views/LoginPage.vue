<script setup>
import FormInput from "@/components/UI/FormInput.vue";
import FormButton from "@/components/UI/FormButton.vue";
import {useAuthorizationStore} from "@/stores/user/authorization.js";
import {useRouter} from "vue-router";
import {object, string} from "yup";
import {useField, useForm} from "vee-validate";

const authStore = useAuthorizationStore();
const router = useRouter();
const schema = object({
    username: string().required('Username kiriting!'),
    password: string().required('Parol kiriting!').min(6)
})

const { errors, handleSubmit } = useForm({ validationSchema: schema });
const { value: username } = useField('username');
const { value: password } = useField('password');

const auth = handleSubmit(async (values) => {
    await authStore.auth({
        username: values.username,
        password: values.password,
    });
    await router.push({ name: 'home' })
    location.reload();
});

</script>

<template>
    <div class="min-h-dvh grid place-content-center">
        <form @submit.prevent="auth" class="flex flex-col max-w-100 w-full sm:min-w-100 gap-5 bg-my-gray p-10 rounded-2xl text-white">
            <h2 class="text-center text-2xl font-medium">Kirish</h2>

            <FormInput :error-message="!!errors.username" v-model="username" label-name="Username" placeholder="username kiriting..." />
            <FormInput :error-message="!!errors.password" v-model="password" input-type="password" label-name="Parol" placeholder="parol kiriting..." />
            <div class="flex justify-between">
                <label for="remember-me">
                    <input type="checkbox" value="remember-me" id="remember-me" class="mr-1" />
                    Remember me
                </label>
                <RouterLink :to="{ name: 'forgot-password' }">Forgot password?</RouterLink>
            </div>

            <FormButton :isloading="authStore.isLoading" :disabled="authStore.isLoading">Kirish</FormButton>

            <div class="flex justify-center">
                <p>
                    Don't have an account?
                    <RouterLink :to="{ name: 'signup' }">Register</RouterLink>
                </p>
            </div>
        </form>
    </div>
</template>
