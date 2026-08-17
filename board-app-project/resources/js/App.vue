<template>
    <router-view />
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from './AuthStore';

const authStore = useAuthStore();

onMounted(async () => {
    await authStore.fetchUser();
});
</script>