<template>
  <div
    class="dropdown-radio"
    :style="widthStyle + 'margin-right:0;'"
  >
    <button
      class="dropdown-radio-btn"
      :class="{ open }"
      type="button"
      tabindex="0"
      :style="widthStyle"
      @click="toggleDropdown"
    >
      <span>{{ displayLabel }}</span>
      <img
        class="dropdown-radio-arrow"
        :src="
          open
            ? require('@/assets/images/VectorUp.svg')
            : require('@/assets/images/VectorDown.svg')
        "
        :alt="open ? 'Close' : 'Open'"
      />
    </button>
    <div
      v-if="open"
      class="dropdown-radio-list"
      :style="widthStyle"
    >
      <!-- Quarter/Year Mode -->
      <template v-if="mode === 'quarter'">
        <div
          v-if="yearOpen"
          style="
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 8px;
            padding: 0;
            width: 100%;
          "
        >
          <button
            v-for="y in yearOptions"
            :key="y"
            class="dropdown-radio-option"
            style="font-size: 1rem; width: 100%"
            @click="selectYear(y)"
          >
            <span>{{ y }}</span>
          </button>
        </div>
        <button
          v-for="opt in quarterOptions"
          :key="opt.value"
          class="dropdown-radio-option"
          type="button"
          style="width: 100%"
          @click="selectQuarter(opt.value)"
        >
          <span>{{ opt.label }}</span>
          <span
            class="dropdown-radio-circle"
            :class="{ selected: internalQuarter === opt.value }"
          ></span>
        </button>
      </template>
      <!-- Radio Mode -->
      <template v-else>
        <button
          v-for="opt in options"
          :key="opt.value"
          class="dropdown-radio-option"
          type="button"
          @click="selectOption(opt.value)"
        >
          <span>{{ opt.label }}</span>
          <span
            class="dropdown-radio-circle"
            :class="{ selected: internalValue === opt.value }"
          ></span>
        </button>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UniversalDropdown',
  props: {
    mode: { type: String, default: 'radio' }, // 'radio' or 'quarter'
    options: { type: Array, default: () => [] }, // for radio mode
    modelValue: { type: String, default: '' }, // for radio mode
    quarter: String, // for quarter mode
    year: Number, // for quarter mode
    dropdownWidth: {
      type: Number,
      default: 240, // Increased from 180 to 240
    },
  },
  emits: ['update:modelValue', 'update:quarter', 'update:year'],
  data() {
    return {
      open: false,
      yearOpen: false,
      internalValue: this.modelValue || this.options[0]?.value || '',
      internalYear: this.year || new Date().getFullYear(),
      internalQuarter:
        this.quarter || `Q1-${this.year || new Date().getFullYear()}`,
      yearOptions: Array.from(
        { length: 10 },
        (_, i) => new Date().getFullYear() - i
      ),
    };
  },
  computed: {
    quarterOptions() {
      return [1, 2, 3, 4].map((q) => ({
        label: `Q${q}-${this.internalYear}`,
        value: `Q${q}-${this.internalYear}`,
      }));
    },
    widthStyle() {
      return `width: ${this.dropdownWidth}px; min-width: ${this.dropdownWidth}px; max-width: ${this.dropdownWidth}px;`;
    },
    displayLabel() {
      if (this.mode === 'quarter') {
        return this.internalQuarter;
      } else {
        const found = this.options.find((o) => o.value === this.internalValue);
        return found ? found.label : this.options[0]?.label || '';
      }
    },
  },
  watch: {
    // Radio mode
    modelValue(val) {
      if (this.mode === 'radio') this.internalValue = val;
    },
    internalValue(val) {
      if (this.mode === 'radio') this.$emit('update:modelValue', val);
    },
    // Quarter mode
    year(val) {
      if (this.mode === 'quarter') this.internalYear = val;
    },
    quarter(val) {
      if (this.mode === 'quarter') this.internalQuarter = val;
    },
    internalYear(val) {
      if (this.mode === 'quarter') {
        this.$emit('update:year', val);
        if (!this.internalQuarter.endsWith(`-${val}`)) {
          this.internalQuarter = `Q1-${val}`;
          this.$emit('update:quarter', this.internalQuarter);
        }
      }
    },
    internalQuarter(val) {
      if (this.mode === 'quarter') this.$emit('update:quarter', val);
    },
  },
  methods: {
    toggleDropdown() {
      this.open = !this.open;
    },
    selectOption(val) {
      this.internalValue = val;
      this.open = false;
    },
    selectQuarter(val) {
      this.internalQuarter = val;
      this.open = false;
    },
    selectYear(val) {
      this.internalYear = val;
      this.yearOpen = false;
    },
    handleClickOutside(e) {
      if (this.open && !this.$el.contains(e.target)) {
        this.open = false;
        this.yearOpen = false;
      }
    },
  },
  mounted() {
    document.addEventListener('mousedown', this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('mousedown', this.handleClickOutside);
  },
};
</script>

<style scoped>
.dropdown-radio {
  position: relative;
  display: inline-block;
  cursor: pointer;
  user-select: none;
}
.dropdown-radio-btn {
  background: #f0f0f0;
  border: 1px solid #e6e6e6;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 15px;
  color: #222;
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  transition: border 0.2s;
}
.dropdown-radio-btn.open {
  border-color: #0164a5;
}
.dropdown-radio-arrow {
  width: 18px;
  height: 18px;
}
.dropdown-radio-list {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: #fff;
  border: 1px solid #e6e6e6;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  z-index: 10;
  margin-top: 4px;
}
.dropdown-radio-option {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 14px;
  font-size: 15px;
  color: #222;
  background: transparent;
  border: none;
  cursor: pointer;
  width: 100%;
  text-align: left;
}
.dropdown-radio-option:hover {
  background: #f0f0f0;
}
.dropdown-radio-circle {
  width: 12px;
  height: 12px;
  border: 2px solid #0164a5;
  border-radius: 50%;
  position: relative;
  flex-shrink: 0;
}
.dropdown-radio-circle.selected {
  background: #0164a5;
}
</style>
