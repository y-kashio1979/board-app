<script setup>
import BaseModal from './BaseModal.vue';
</script>

<template>
    <BaseModal>
        <template #body>
            <slot />

            <!-- 円形アニメーション -->
            <div class="flex items-center justify-center mt-item">
                <div class="h-20 w-20 animate-spin rounded-full border-8 border-gray-200 border-t-primary"></div>
            </div>
        </template>

        <template #btn> </template>
    </BaseModal>
</template>

<style scoped></style>
