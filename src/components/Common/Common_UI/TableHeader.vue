<template>
  <thead>
    <tr>
      <th
        v-for="(col, idx) in columns"
        :key="col.key || idx"
        :class="[
          idx === 0 ? 'rounded-th-left' : '',
          idx === columns.length - 1 ? 'rounded-th-right' : '',
        ]"
      >
        <span class="org-th-content">
          {{ col.label }}
          <button
            v-if="col.sortable === true"
            class="org-th-sort-btn"
            type="button"
            @click="$emit('sort', col.key)"
          >
            <img
              src="@/assets/images/up-down.svg"
              class="org-th-sort"
              alt="Sort"
            />
          </button>
        </span>
      </th>
    </tr>
  </thead>
</template>

<script>
export default {
  name: 'TableHeader',
  props: {
    columns: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style scoped>
.org-th-content {
  display: block;
  text-align: left;
  font-size: 14px;
  font-weight: 600;
  color: #888;
}
.org-th-sort-btn {
  background: none;
  border: none;
  display: inline-flex;
  align-items: center;
  vertical-align: middle;
  cursor: pointer;
  height: 1em;
  line-height: 1;
}
.org-th-sort {
  width: 1em;
  height: 1em;
  min-width: 16px;
  min-height: 16px;
  max-width: 18px;
  max-height: 18px;
  margin-left: 2px;
  opacity: 0.7;
  transition: opacity 0.15s;
}
.org-th-sort-btn:hover .org-th-sort,
.org-th-sort-btn:focus .org-th-sort {
  opacity: 1;
}
.rounded-th-left {
  border-top-left-radius: 24px;
  border-bottom-left-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
  padding-left: 20px !important;
}
.rounded-th-right {
  border-top-right-radius: 24px;
  border-bottom-right-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
}

th {
  background: #f8f8f8;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: 100px;
  border-bottom: 1.5px solid #ebebeb;
  padding: 10px 12px; /* decreased from 18px 12px */
  font-family: 'Inter', Arial, sans-serif;
  font-size: 15px;
  line-height: 20px; /* slightly reduced */
}
@media (max-width: 1400px) {
  th {
    font-size: 12px;
    padding: 6px 4px; /* decreased from 8px 4px */
  }
  .rounded-th-left {
    border-top-left-radius: 14px;
    border-bottom-left-radius: 14px;
    padding-left: 20px !important;
  }
  .rounded-th-right {
    border-top-right-radius: 14px;
    border-bottom-right-radius: 14px;
  }
}
@media (max-width: 900px) {
  th {
    font-size: 11px;
    padding: 4px 2px; /* decreased from 6px 2px */
  }
  .rounded-th-left {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    padding-left: 20px !important;
  }
  .rounded-th-right {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>
