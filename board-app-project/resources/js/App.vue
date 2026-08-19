<template>
    <Header />
    <router-view />
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from './AuthStore';
import Header from './components/Header.vue';

const authStore = useAuthStore();

onMounted(async () => {
    await authStore.fetchUser();
});
</script>