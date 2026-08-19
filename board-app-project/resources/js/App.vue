<template>
    <Header />
    <router-view />
    <Footer />
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from './AuthStore';
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';

const authStore = useAuthStore();

onMounted(async () => {
    await authStore.fetchUser();
});
</script>