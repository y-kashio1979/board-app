<script setup>
import BaseModal from './BaseModal.vue';
</script>

<template>
    <BaseModal>
        <template #body>
            <slot />
        </template>

        <template #btn> </template>
    </BaseModal>
</template>

<style scoped></style>
