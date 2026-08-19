<script setup>
import BaseModal from "./BaseModal.vue";
</script>
<template>
    <BaseModal>
        <template #body>
            <slot></slot>
        </template>
    </BaseModal>
</template>

<style scoped></style>
