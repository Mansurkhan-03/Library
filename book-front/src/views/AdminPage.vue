<script setup>
import {reactive, ref} from "vue";
import FormInput from "@/components/UI/FormInput.vue";
import FormButton from "@/components/UI/FormButton.vue";
import PaginationComponent from "@/components/PaginationComponent.vue";
import {useGetUsersStore} from "@/stores/user/getUsers.js";
import {useI18n} from "vue-i18n";
import {useDeleteUserStore} from "@/stores/user/deleteUser.js";
import UserEditModalComponent from "@/components/UserEditModalComponent.vue";
import {useChangeUserStore} from "@/stores/user/changeUser.js";
import {number, object, string} from "yup";
import {useField, useForm} from "vee-validate";

const usersStore = useGetUsersStore();
const deleteUserStore = useDeleteUserStore();
const changeUserStore = useChangeUserStore();
usersStore.fetchUsers('?page=1');

const schema = object({
    username: string().required(),
    password: string().required().min(6),
    age: number().integer().required().moreThan(18).lessThan(50),
    phone: string().required().matches(/^\+?\d+$/).min(9).max(13)
})

const { errors, handleSubmit } = useForm({ validationSchema: schema });

const { value: username } = useField('username');
const { value: password } = useField('password');
const { value: age } = useField('age');
const { value: phone } = useField('phone');

const queryFilters = reactive({
    query: '?page=1',
    email: null,
    phone: null,
    before: null,
    after: null,
})

const currentPage = ref(1);
const isOpenForEdit = ref(false);
const currentId = ref(null);

const search = () => {
    queryFilters.query = `?page=1`;

    if (queryFilters.email) {
        queryFilters.query += `&email=${queryFilters.email}`;
    }

    if (queryFilters.phone) {
        queryFilters.query += `&phone=${queryFilters.phone}`;
    }

    if (queryFilters.before) {
        queryFilters.query += `&createdAt[before]=${queryFilters.before}`;
    }

    if (queryFilters.after) {
        queryFilters.query += `&createdAt[after]=${queryFilters.after}`;
    }

    if (queryFilters.email || queryFilters.phone || queryFilters.before || queryFilters.after) {
        usersStore.fetchUsers(queryFilters.query);
        currentPage.value = 1;
    }
}

const clear = () => {
    queryFilters.email = null;
    queryFilters.phone = null;
    queryFilters.before = null;
    queryFilters.after = null;

    usersStore.fetchUsers('?page=1');
    currentPage.value = 1;
}

const changePage = (page) => {
    currentPage.value = page;
    usersStore.fetchUsers(queryFilters.query.replace(/page=\d+/, `page=${page}`));
}

const nextLastPage = (incrementOrDecrement) => {
    currentPage.value += incrementOrDecrement;
    if (!currentPage.value) {
        currentPage.value = 1;
    } else if(currentPage.value > usersStore.get.pageCount) {
        currentPage.value--;
    } else {
        usersStore.fetchUsers(queryFilters.query.replace(/page=\d+/, `page=${currentPage.value}`));

    }
}

const openUserModal = id => {
    isOpenForEdit.value = true;
    currentId.value = id;
}

const deleteUser = async (id) => {
    await deleteUserStore.deleteUser(id);
    await usersStore.fetchUsers(queryFilters.query.replace(/page=\d+/, `page=${currentPage.value}`));
}

const changeUser = handleSubmit( async values => {
    await changeUserStore.change(currentId.value, { ...values, age: parseInt(values.age) });
    console.log(queryFilters.query);
    await usersStore.fetchUsers(queryFilters.query.replace(/page=\d+/, `page=${currentPage.value}`));
    username.value = "";
    password.value = "";
    age.value = null;
    phone.value = "";
    isOpenForEdit.value = false;
});

const { t } = useI18n();
</script>

<template>
    <div class="container mx-auto flex flex-col bg-my-gray p-5">
        <form @submit.prevent="search" class="flex items-end gap-5 text-white">
            <FormInput v-model="queryFilters.email" :label-name="t('email')" :placeholder="t('phEmail')"/>
            <FormInput v-model="queryFilters.phone" :label-name="t('phone')" :placeholder="t('phPhone')"/>
            <FormInput v-model="queryFilters.before" :label-name="t('before')" input-type="date"/>
            <FormInput v-model="queryFilters.after" :label-name="t('after')" input-type="date"/>

            <FormButton class="ml-auto">{{ t('search') }}</FormButton>
            <FormButton @click="clear" class="bg-red-500">{{ t('clear') }}</FormButton>
        </form>

        <div class="border p-2 w-full my-5">
            <table class="table-auto w-full">
                <thead class="bg-my-blue-gray">
                    <tr>
                        <th class="text-left pl-5 py-2">#</th>
                        <th class="text-left">username</th>
                        <th class="text-left">{{ t('email') }}</th>
                        <th class="text-left">{{ t('age') }}</th>
                        <th class="text-left">{{ t('phone') }}</th>
                        <th class="text-left">{{ t('gender') }}</th>
                        <th class="text-left">{{ t('registrationTime') }}</th>
                        <th class="text-right">{{ t('update') }}</th>
                        <th class="text-right pr-5">{{ t('delete') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-my-beige text-black">
                    <tr
                        v-for="user of usersStore.get.all"
                        :key="user.id"
                        class="not-last:border-b"
                    >
                        <td class="text-left pl-5 py-2">{{ user.id }}</td>
                        <td class="text-left">{{ user.username }}</td>
                        <td class="text-left">{{ user.email }}</td>
                        <td class="text-left">{{ user.age }}</td>
                        <td class="text-left">{{ user.phone }}</td>
                        <td class="text-left">{{ user.gender }}</td>
                        <td class="text-left">{{ new Intl.DateTimeFormat().format(new Date(user.createdAt)) }}</td>
                        <td class="pr-4">
                            <div class="flex justify-end">
                                <button @click="openUserModal(user.id)">
                                    <svg height="24px" id="Layer_1" viewBox="0 0 48 48" width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M44.929,14.391c-0.046,0.099-0.102,0.194-0.183,0.276L16.84,42.572  c-0.109,0.188-0.26,0.352-0.475,0.434l-13.852,3.88c-0.029,0.014-0.062,0.016-0.094,0.026l-0.047,0.014  c-0.008,0.003-0.017,0.001-0.024,0.004c-0.094,0.025-0.187,0.046-0.286,0.045c-0.098,0.003-0.189-0.015-0.282-0.041  c-0.021-0.006-0.04-0.002-0.061-0.009c-0.008-0.003-0.013-0.01-0.021-0.013c-0.088-0.033-0.164-0.083-0.24-0.141  c-0.039-0.028-0.08-0.053-0.113-0.086s-0.058-0.074-0.086-0.113c-0.058-0.075-0.107-0.152-0.141-0.24  c-0.004-0.008-0.01-0.013-0.013-0.021c-0.007-0.02-0.003-0.04-0.009-0.061c-0.025-0.092-0.043-0.184-0.041-0.281  c0-0.1,0.02-0.193,0.045-0.287c0.004-0.008,0.001-0.016,0.004-0.023l0.014-0.049c0.011-0.03,0.013-0.063,0.026-0.093l3.88-13.852  c0.082-0.216,0.246-0.364,0.434-0.475l27.479-27.48c0.04-0.045,0.087-0.083,0.128-0.127l0.299-0.299  c0.015-0.015,0.034-0.02,0.05-0.034C34.858,1.87,36.796,1,38.953,1C43.397,1,47,4.603,47,9.047  C47,11.108,46.205,12.969,44.929,14.391z M41.15,15.5l-3.619-3.619L13.891,35.522c0.004,0.008,0.014,0.011,0.018,0.019l2.373,4.827  L41.15,15.5z M3.559,44.473l2.785-0.779l-2.006-2.005L3.559,44.473z M4.943,39.53l3.558,3.559l6.12-1.715  c0,0-2.586-5.372-2.59-5.374l-5.374-2.59L4.943,39.53z M12.49,34.124c0.008,0.004,0.011,0.013,0.019,0.018L36.15,10.5l-3.619-3.619  L7.663,31.749L12.49,34.124z M38.922,3c-1.782,0-3.372,0.776-4.489,1.994l-0.007-0.007L33.912,5.5l8.619,8.619l0.527-0.528  l-0.006-0.006c1.209-1.116,1.979-2.701,1.979-4.476C45.031,5.735,42.296,3,38.922,3z" fill-rule="evenodd"/></svg>
                                </button>
                            </div>
                        </td>
                        <td class="pr-5">
                            <div class="flex justify-end">
                                <button @click="deleteUser(user.id)">
                                    <svg height="24px" viewBox="0 0 48 48" width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><g id="Expanded"><g><g><path d="M41,48H7V7h34V48z M9,46h30V9H9V46z"/></g><g><path d="M35,9H13V1h22V9z M15,7h18V3H15V7z"/></g><g><path d="M16,41c-0.553,0-1-0.447-1-1V15c0-0.553,0.447-1,1-1s1,0.447,1,1v25C17,40.553,16.553,41,16,41z"/></g><g><path d="M24,41c-0.553,0-1-0.447-1-1V15c0-0.553,0.447-1,1-1s1,0.447,1,1v25C25,40.553,24.553,41,24,41z"/></g><g><path d="M32,41c-0.553,0-1-0.447-1-1V15c0-0.553,0.447-1,1-1s1,0.447,1,1v25C33,40.553,32.553,41,32,41z"/></g><g><rect height="2" width="48" y="7"/></g></g></g></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div class="flex items-center justify-between">
            <p class="text-white">Jami: {{ usersStore.get.totalItems}}</p>
            <PaginationComponent
                :pagination-count="usersStore.get.pageCount"
                @on-previous-and-next-page="nextLastPage"
                @onSetPage="changePage"
            />
        </div>

        <UserEditModalComponent
            @on-accept="changeUser"
            v-model:username="username"
            v-model:password="password"
            v-model:age="age"
            v-model:phone="phone"
            v-model:is-open="isOpenForEdit"
            :error-username="!!errors.username"
            :error-password="!!errors.password"
            :error-age="!!errors.age"
            :error-phone="!!errors.phone"
        />
    </div>
</template>
