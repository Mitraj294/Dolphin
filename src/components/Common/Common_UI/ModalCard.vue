<template>
  <div class="modal-card">
    <button
      class="modal-close"
      @click="$emit('close')"
    >
      &times;
    </button>
    <h2 class="modal-title">
      <slot name="title">{{ title }}</slot>
    </h2>
    <form
      class="modal-form"
      @submit.prevent="$emit('submit')"
    >
      <slot />
      <div class="modal-form-actions">
        <slot name="actions">
          <button
            v-if="actionLabel"
            type="submit"
            class="modal-save-btn"
          >
            {{ actionLabel }}
          </button>
        </slot>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'ModalCard',
  props: {
    title: {
      type: String,
      default: '',
    },
    actionLabel: {
      type: String,
      default: '',
    },
  },
};
</script>

<style scoped>
.modal-card {
  background: #fff;
  border-radius: 22px;
  box-shadow: 0 4px 32px 0 rgba(33, 150, 243, 0.1);
  padding: 40px 48px 32px 48px;
  min-width: 320px;
  max-width: 700px;
  width: 100%;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.modal-close {
  position: absolute;
  top: 24px;
  right: 32px;
  background: none;
  border: none;
  font-size: 32px;
  color: #888;
  cursor: pointer;
  z-index: 10;
}
.modal-title {
  font-size: 26px;
  font-weight: 600;
  margin-bottom: 32px;
  color: #222;
}
.modal-form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 18px;
}
.modal-form-row {
  display: flex;
  gap: 24px;
  width: 100%;
}
.modal-form-group,
.modal-form-group.custom-dropdown {
  flex: 1 1 0;
  min-width: 0;
  background: #f6f6f6;
  border-radius: 9px;
  padding: 0 16px;
  height: 48px;
  display: flex;
  align-items: center;
  margin-bottom: 0;
  box-sizing: border-box;
}
.modal-form-group.custom-dropdown {
  flex-direction: row;
  align-items: center;
  gap: 0;
  min-height: 48px;
  width: 100%;
}
.modal-icon {
  margin-right: 10px;
  display: flex;
  align-items: center;
}
.modal-form-group input,
.modal-form-group select {
  border: none;
  background: transparent;
  outline: none;
  font-size: 16px;
  color: #222;
  width: 100%;
  height: 44px;
  padding: 0;
  font-family: inherit;
}
.modal-form-group select {
  appearance: none;
  cursor: pointer;
}
.modal-form-actions {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  margin-top: 18px;
}
.modal-save-btn {
  border-radius: 22px;
  background: #0164a5;
  color: #fff;
  font-size: 17px;
  font-weight: 500;
  padding: 10px 32px;
  border: none;
  cursor: pointer;
  transition: background 0.2s;
}
.modal-save-btn:hover {
  background: #005fa3;
}
@media (max-width: 900px) {
  .modal-form-group.custom-dropdown {
    padding: 8px 6px 12px 6px;
    min-height: 60px;
    border-radius: 14px;
  }
  .modal-card {
    padding: 18px 8px;
    border-radius: 14px;
  }
}
@media (max-width: 600px) {
  .modal-card {
    padding: 12px 2vw;
    border-radius: 10px;
    min-width: 0;
    max-width: 98vw;
  }
}
</style>
