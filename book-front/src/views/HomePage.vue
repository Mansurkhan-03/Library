<script setup>
import BooksComponent from "@/components/BooksComponent.vue";
import {useI18n} from "vue-i18n";

const { t } = useI18n();
</script>

<template>
    <div>
        <h1 class="text-3xl mb-2 font-medium">{{ t('books') }}</h1>
        <BooksComponent />
    </div>
</template>
