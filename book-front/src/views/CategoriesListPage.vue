<script setup>

import FormButton from "@/components/UI/FormButton.vue";
import PaginationComponent from "@/components/PaginationComponent.vue";
import ModalComponent from "@/components/ModalComponent.vue";
import { useGetCategoryStore } from "@/stores/category/getCategories";
import { useChangeCategoryStore } from "@/stores/category/changeCategory";
import { useDeleteCategoryStore } from "@/stores/category/deleteCategory";
import {useAddCategoryStore} from "@/stores/category/addCategory.js";
import {ref} from "vue";
import {useI18n} from "vue-i18n";
import {object, string} from "yup";
import {useField, useForm} from "vee-validate";

const getCategoryStore = useGetCategoryStore();
const changeCategoryStore = useChangeCategoryStore();
const deleteCategoryStore = useDeleteCategoryStore();
const addCategoryStore = useAddCategoryStore();
const isOpenForEdit = ref(false);
const isOpenForAdd = ref(false);
const currentId = ref(null);
const schema = object({
    category: string().required()
})

const { errors, handleSubmit } = useForm({ validationSchema: schema });
const { value: category } = useField('category');

getCategoryStore.fetchAll();

const openEditModal = id => {
    isOpenForEdit.value = true;
    currentId.value = id;
}

const editCategory = handleSubmit( async values => {
    await changeCategoryStore.change(currentId.value, { name: values.category });
    category.value = '';
    currentId.value = null;
    isOpenForEdit.value = false;
    await getCategoryStore.fetchAll();
});

const deleteCategory = async id => {
    await deleteCategoryStore.deleteCategory(id);
    await getCategoryStore.fetchAll();
}

const addCategory = handleSubmit( async values => {
    await addCategoryStore.add({ name: values.category });
    category.value = '';
    isOpenForAdd.value = false;
    await getCategoryStore.fetchAll();
});

const { t } = useI18n();
</script>

<template>
    <div class="grow mr-5">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-3xl mb-2 font-medium">{{ t('books') }}</h2>
            <FormButton @click="isOpenForAdd = true">{{ t('add') }}</FormButton>
        </div>

        <div class="border p-2 w-full mb-5">
            <table class="table-auto w-full">
                <thead class="bg-my-blue-gray">
                <tr>
                    <th class="text-left pl-5 py-2">Id</th>
                    <th class="text-left">{{ t('name') }}</th>
                    <th class="text-right pr-5">{{ t('actions') }}</th>
                </tr>
                </thead>
                <tbody class="bg-my-beige">
                <tr v-for="category of getCategoryStore.get.all" :key="category.id" class="not-last:border-b">
                    <td class="text-left pl-5">{{ category.id }}</td>
                    <td class="text-left">{{ category.name }}</td>
                    <td class="text-right pr-5">
                        <button @click="openEditModal(category.id)" class="px-3 py-1 hover:op-70 transition-all bg-amber-500 text-white rounded-full my-2 mr-4 cursor-pointer">{{ t('update') }}</button>
                        <button @click="deleteCategory(category.id)" class="px-3 py-1 hover:op-70 transition-all bg-red-500 text-white rounded-full my-2 mr-4 cursor-pointer">{{ t('delete') }}</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <PaginationComponent :pagination-count="6" />
        <ModalComponent
            @on-accept="editCategory"
            :error="!!errors.category"
            :label-name-input="t('categoryName')"
            :placeholder-input="t('phCategoryName')"
            :modal-title="t('updateCategory')"
            :cancel-button-text="t('cancel')"
            :accept-button-text="t('update')"
            v-model:is-open="isOpenForEdit"
            v-model="category"
        />
        <ModalComponent
            @on-accept="addCategory"
            :error="!!errors.category"
            :label-name-input="t('categoryName')"
            :placeholder-input="t('phCategoryName')"
            :modal-title="t('addCategory')"
            :cancel-button-text="t('cancel')"
            :accept-button-text="t('add')"
            v-model:is-open="isOpenForAdd"
            v-model="category"
        />
    </div>
</template>