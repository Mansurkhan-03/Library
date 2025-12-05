<script setup>
import FormButton from "@/components/UI/FormButton.vue";
import FormInput from "@/components/UI/FormInput.vue";
import {reactive} from "vue";
import {useGetCategoryStore} from "@/stores/category/getCategories.js";
import {useAddFileStore} from "@/stores/mediaObject/addFile.js";
import {useCreateBookStore} from "@/stores/book/createBook.js";
import {useI18n} from "vue-i18n";
import {object, string} from "yup";
import {useField, useForm} from "vee-validate";

// const book = reactive({
//     name: '',
//     description: '',
//     text: '',
//     category: '',
//     image: ''
// })

const getCategoryStore = useGetCategoryStore();
const addFileStore = useAddFileStore();
const createBookStore = useCreateBookStore();
const schema = object({
    name: string().required('Name kiriting!'),
    description: string().required('Description kiriting!'),
    text: string().required('Text kiriting!'),
    category: string().required('Category kiriting!'),
    image: string().required('Image kiriting!')
})

const { errors, handleSubmit } = useForm({ validationSchema: schema });

const { value: name } = useField('name');
const { value: description } = useField('description');
const { value: text } = useField('text');
const { value: category } = useField('category');
const { value: image } = useField('image');

getCategoryStore.fetchAll();

const setFile = event => {
    book.image = event.target.files[0]
}

const addBook = handleSubmit( async values => {
    const res = await addFileStore.addFile(image);
    await createBookStore.createBook({...values, image: res["@id"]});
    name.value = '';
    description.value = '';
    text.value = '';
    category.value = '';
    image.value = '';
});

const { t } = useI18n();
</script>

<template>
    <div class=" inset-0 bg-black/30 grid place-content-center">
        <div class="p-10 rounded bg-white flex flex-col gap-5 sm:w-150">
            <h3 class="text-2xl font-bold">{{ t('addBook') }}</h3>

            <FormInput :error-message="!!errors.name" :label-name="t('bookName')" v-model="name" :placeholder="t('phBookName')"/>
            <FormInput :error-message="!!errors.description" :label-name="t('description')" v-model="description" :placeholder="t('phDescription')"/>

            <div class="flex flex-col sm:w-130">
                <div>
                    <span class="italic pb-0.5">{{ t('textBook') }}</span>
                    <span v-if="errors.text" class="text-red-500 pl-2">*</span>
                </div>
                <textarea class="border rounded h-30" v-model="text"></textarea>
            </div>

            <div class="flex flex-col sm:w-130">
                <div>
                    <span class="italic">{{ t('category') }}</span>
                    <span v-if="errors.category" class="text-red-500 pl-2">*</span>
                </div>
                <select class="border rounded py-2.5" v-model="category" >
                    <option value="" disabled selected>{{ t('select') }}</option>
                    <option v-for="category of getCategoryStore.get.all" :key="category.id" :value="category['@id']">{{ category.name }}</option>
                </select>
            </div>

            <FormInput :error-message="errors.image" @change="setFile" input-type="file" :label-name="t('bookImage')"/>


            <div class="flex gap-5 justify-end">
                <FormButton :isloading="createBookStore.isLoading" @click="addBook">{{ t('add') }}</FormButton>
            </div>
        </div>
    </div>
</template>