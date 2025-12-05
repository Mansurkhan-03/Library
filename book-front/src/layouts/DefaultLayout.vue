<script setup>

import HeaderComponent from "@/components/HeaderComponent.vue";
import CategoriesComponent from "@/components/CategoriesComponent.vue";
import FooterComponent from "@/components/FooterComponent.vue";
</script>

<template>
    <HeaderComponent />

    <section class="flex flex-col md:flex-row gap-5">
        <CategoriesComponent />

        <RouterView/>
    </section>

    <FooterComponent />
</template>
