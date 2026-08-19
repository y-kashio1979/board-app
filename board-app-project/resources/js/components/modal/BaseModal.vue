<script setup>
</script>

<template>
  <div class="modal-overlay">
    <div class="modal-content">
      <div>
        <slot name="body" />
      </div>

      <div class="button-area">
        <slot name="btn" />
      </div>
    </div>
  </div>
</template>

<style scoped>
/* TODO: モーダルのスタイルの決定 */
.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 50;

  display: flex;
  justify-content: center;
  align-items: center;

  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  width: 100%;
  max-width: 448px; /* max-w-md */

  padding: 24px;
  text-align: center;

  background-color: #fff;
  border-radius: 12px;

  box-shadow:
    0 20px 25px -5px rgb(0 0 0 / 0.1),
    0 8px 10px -6px rgb(0 0 0 / 0.1);
}

.button-area {
  display: flex;
  justify-content: center;
  gap: 12px;

  margin-top: 24px;
}
</style>