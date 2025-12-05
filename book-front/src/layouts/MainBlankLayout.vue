<script setup>

import HeaderComponent from "@/components/HeaderComponent.vue";
import FooterComponent from "@/components/FooterComponent.vue";
</script>

<template>
    <section class="flex flex-col min-h-dvh">
        <HeaderComponent />

        <RouterView/>

        <FooterComponent class="mt-auto" />
    </section>
</template>
