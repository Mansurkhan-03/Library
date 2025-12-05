<script setup>
import {useI18n} from "vue-i18n";

const { t } = useI18n();
</script>

<template>
    <footer class="bg-my-gray px-10 h-40 flex justify-start items-center mt-5 text-white text-lg">
        <p>{{ t('copyright') }}&copy;</p>
        <p>2025</p>
    </footer>
</template>

<style scoped>

</style>