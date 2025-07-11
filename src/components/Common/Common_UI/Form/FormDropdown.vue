<template>
  <FormBox :error="error">
    <span
      v-if="icon"
      class="form-input-icon"
      ><i :class="icon"></i
    ></span>
    <select
      v-bind="$attrs"
      v-model="inputValue"
      :disabled="disabled"
      class="form-dropdown"
      :class="{ 'with-icon': icon }"
      @change="$emit('update:modelValue', inputValue)"
    >
      <slot />
    </select>
    <span class="form-dropdown-chevron"
      ><i class="fas fa-chevron-down"></i
    ></span>
  </FormBox>
</template>

<script>
import FormBox from './FormBox.vue';
export default {
  name: 'FormDropdown',
  components: { FormBox },
  props: {
    modelValue: [String, Number],
    icon: { type: String, default: '' },
    error: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
  },
  computed: {
    inputValue: {
      get() {
        return this.modelValue;
      },
      set(val) {
        this.$emit('update:modelValue', val);
      },
    },
  },
};
</script>

<style scoped>
.form-dropdown {
  border: none;
  background: transparent;
  outline: none;
  font-size: 16px;
  color: #222;
  width: 100%;
  height: 44px;
  padding: 0;
  font-family: inherit;
  appearance: none;
  cursor: pointer;
}
.form-dropdown.with-icon {
  padding-left: 36px;
}
.form-dropdown {
  padding-right: 36px;
}
.form-dropdown:disabled {
  background: #f0f0f0;
  color: #aaa;
}
.form-input-icon {
  margin-right: 10px;
  margin-left: 12px;
  color: #888;
  font-size: 16px;
  display: flex;
  align-items: center;
  position: absolute;
  left: 0;
  height: 100%;
}
.form-dropdown-chevron {
  margin-left: auto;
  color: #888;
  font-size: 16px;
  display: flex;
  align-items: center;
  pointer-events: none;
  position: absolute;
  right: 10px;
  height: 100%;
}
</style>
