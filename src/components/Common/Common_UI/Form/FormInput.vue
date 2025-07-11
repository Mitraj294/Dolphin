<template>
  <FormBox :error="error">
    <span
      v-if="icon"
      class="form-input-icon"
      ><i :class="icon"></i
    ></span>
    <input
      v-bind="$attrs"
      :type="type"
      :placeholder="placeholder"
      v-model="inputValue"
      :readonly="readonly"
      :disabled="disabled"
      class="form-input"
      :class="{ 'with-icon': icon }"
      @input="$emit('update:modelValue', inputValue)"
    />
  </FormBox>
</template>

<script>
import FormBox from './FormBox.vue';
export default {
  name: 'FormInput',
  components: { FormBox },
  props: {
    modelValue: [String, Number],
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
    icon: { type: String, default: '' },
    error: { type: Boolean, default: false },
    readonly: { type: Boolean, default: false },
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
.form-input {
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
.form-input.with-icon {
  padding-left: 36px;
}
.form-input:disabled {
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
</style>
