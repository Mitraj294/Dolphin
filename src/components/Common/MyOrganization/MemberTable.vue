<template>
  <div class="my-org-table-container">
    <table class="my-org-table">
      <thead>
        <tr>
          <th class="rounded-th-left group-name-th">Group Name</th>
          <th class="actions-th">Actions</th>
          <th class="rounded-th-right empty-th"></th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="group in groups"
          :key="group.id"
        >
          <td class="group-name-td">{{ group.name }}</td>
          <td>
            <button
              class="icon-btn view-btn"
              @click="viewGroup(group)"
            >
              <img
                src="@/assets/images/Notes.svg"
                alt="View"
                class="view-icon"
              />
              <span class="view-label">View</span>
            </button>
          </td>
          <td></td>
        </tr>
        <tr v-if="groups.length === 0">
          <td
            colspan="3"
            class="empty-row"
          >
            No groups found.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'MemberTable',
  props: {
    groups: {
      type: Array,
      default: () => [],
    },
  },
  methods: {
    viewGroup(group) {
      this.$emit('view-group', group);
    },
  },
};
</script>

<style scoped>
.my-org-table-container {
  width: 100%;
  overflow-x: auto;
  box-sizing: border-box;
  padding: 0 24px 24px 24px;
  background: #fff;
  border-bottom-left-radius: 24px;
  border-bottom-right-radius: 24px;
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.my-org-table-container::-webkit-scrollbar {
  display: none;
}
.my-org-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  margin-bottom: 8px;
  background: transparent;
  margin-left: 0;
  margin-right: 0;
  table-layout: fixed;
  border: none;
  margin-top: 0;
  min-width: 700px; /* 340+340+20 */
}
.my-org-table th,
.my-org-table td {
  padding: 12px 8px;
  text-align: left;
  font-size: 14px;
  border-bottom: 1px solid #f0f0f0;
  background: #fff;
  font-family: 'Inter', Arial, sans-serif;
  font-weight: 400;
  line-height: 18px;
  letter-spacing: 0.01em;
}
.my-org-table th.group-name-th,
.my-org-table td.group-name-td {
  width: 340px;
  min-width: 340px;
  max-width: 340px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  padding-left: 20px;
}
.my-org-table th.actions-th,
.my-org-table td:nth-child(2) {
  width: 340px;
  min-width: 340px;
  max-width: 340px;
  text-align: left;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.my-org-table th.empty-th,
.my-org-table td:nth-child(3) {
  width: auto;
  min-width: 20px;
  max-width: 9999px;
  padding: 0;
}
.my-org-table th.group-name-th {
  padding-left: 20px;
}
.my-org-table td.group-name-td {
  padding-left: 20px;
}
.my-org-table th.empty-th {
  border-top-right-radius: 24px;
  border-bottom-right-radius: 24px;
  background: #f8f8f8;
}
.my-org-table td {
  color: #222;
  background: #fff;
}
.my-org-table .empty-row {
  text-align: center;
  color: #888;
  font-style: italic;
  background: #fff;
}
.icon-btn.view-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  font-size: 15px;
  color: #222;
}
.icon-btn.view-btn:hover .view-label {
  text-decoration: underline;
}
.view-icon {
  width: 18px;
  height: 18px;
  display: inline-block;
  vertical-align: middle;
}
.view-label {
  color: #222;
  text-decoration: underline;
  font-weight: 500;
  font-size: 15px;
  cursor: pointer;
}

/* Responsive: shrink empty-th first, then shrink fixed columns equally */
@media (max-width: 800px) {
  .my-org-table {
    min-width: 700px;
  }
  .my-org-table th.empty-th,
  .my-org-table td:nth-child(3) {
    min-width: 20px;
    width: 20px;
    max-width: 20px;
  }
}
@media (max-width: 700px) {
  .my-org-table {
    min-width: 640px;
  }
  .my-org-table th.group-name-th,
  .my-org-table td.group-name-td,
  .my-org-table th.actions-th,
  .my-org-table td:nth-child(2) {
    width: 320px;
    min-width: 320px;
    max-width: 320px;
  }
}
@media (max-width: 640px) {
  .my-org-table {
    min-width: 600px;
  }
  .my-org-table th.group-name-th,
  .my-org-table td.group-name-td,
  .my-org-table th.actions-th,
  .my-org-table td:nth-child(2) {
    width: 300px;
    min-width: 300px;
    max-width: 300px;
  }
}
@media (max-width: 600px) {
  .my-org-table {
    min-width: 540px;
  }
  .my-org-table th.group-name-th,
  .my-org-table td.group-name-td,
  .my-org-table th.actions-th,
  .my-org-table td:nth-child(2) {
    width: 260px;
    min-width: 260px;
    max-width: 260px;
  }
}
@media (max-width: 540px) {
  .my-org-table {
    min-width: 480px;
  }
  .my-org-table th.group-name-th,
  .my-org-table td.group-name-td,
  .my-org-table th.actions-th,
  .my-org-table td:nth-child(2) {
    width: 220px;
    min-width: 220px;
    max-width: 220px;
  }
}
@media (max-width: 480px) {
  .my-org-table {
    min-width: 420px;
  }
  .my-org-table th.group-name-th,
  .my-org-table td.group-name-td,
  .my-org-table th.actions-th,
  .my-org-table td:nth-child(2) {
    width: 180px;
    min-width: 180px;
    max-width: 180px;
  }
}
@media (max-width: 420px) {
  .my-org-table {
    min-width: 340px;
  }
  .my-org-table th.group-name-th,
  .my-org-table td.group-name-td,
  .my-org-table th.actions-th,
  .my-org-table td:nth-child(2) {
    width: 160px;
    min-width: 160px;
    max-width: 160px;
  }
}
@media (max-width: 340px) {
  .my-org-table {
    min-width: 200px;
  }
  .my-org-table th.group-name-th,
  .my-org-table td.group-name-td,
  .my-org-table th.actions-th,
  .my-org-table td:nth-child(2) {
    width: 90px;
    min-width: 90px;
    max-width: 90px;
  }
}
@media (max-width: 1400px) {
  .my-org-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
  }
  .my-org-table th,
  .my-org-table td {
    font-size: 12px;
    padding: 8px 4px;
  }
  .my-org-table th.empty-th {
    border-top-right-radius: 14px;
    border-bottom-right-radius: 14px;
  }
  .my-org-table th.group-name-th,
  .my-org-table td.group-name-td {
    padding-left: 16px;
  }
}
@media (max-width: 900px) {
  .my-org-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  .my-org-table th,
  .my-org-table td {
    font-size: 11px;
    padding: 6px 2px;
  }
  .my-org-table th.empty-th {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  .my-org-table th.group-name-th,
  .my-org-table td.group-name-td {
    padding-left: 8px;
  }
}
.rounded-th-left {
  border-top-left-radius: 24px;
  border-bottom-left-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
}
.my-org-table th {
  background: #f8f8f8;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: auto;
  border-bottom: 1.5px solid #ebebeb;
}
.rounded-th-right {
  border-top-right-radius: 24px;
  border-bottom-right-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
}
</style>
