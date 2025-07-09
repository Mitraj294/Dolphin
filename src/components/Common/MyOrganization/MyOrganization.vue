<template>
  <MainLayout>
    <div class="page">
      <div class="my-org-table-outer">
        <div class="my-org-table-card">
          <OrgActionButtons
            @show-add-group="showAddGroupModal = true"
            @show-add-member="showAddMemberModal = true"
          />
          <MemberTable
            :groups="paginatedGroups"
            @view-group="viewGroup"
          />
        </div>
        <Pagination
          :pageSize="pageSize"
          :pageSizes="pageSizes"
          :showPageDropdown="showPageDropdown"
          :currentPage="currentPage"
          :totalPages="totalPages"
          @togglePageDropdown="togglePageDropdown"
          @selectPageSize="selectPageSize"
          @goToPage="goToPage"
        />
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../../layout/MainLayout.vue';
import MemberTable from './MemberTable.vue';
import OrgActionButtons from './OrgActionButtons.vue';
import Pagination from '../../layout/Pagination.vue';

export default {
  name: 'MyOrganization',
  components: { MainLayout, MemberTable, OrgActionButtons, Pagination },
  data() {
    return {
      groups: [
        { id: 1, name: 'Flexi-Finders' },
        { id: 2, name: 'Interim Solutions' },
        { id: 3, name: 'Talent on Demand' },
        { id: 4, name: 'QuickStaff' },
        { id: 1, name: 'Flexi-Finders' },
        { id: 2, name: 'Interim Solutions' },
        { id: 3, name: 'Talent on Demand' },
        { id: 4, name: 'QuickStaff' },
        { id: 5, name: 'Rapid Recruiters' },
        { id: 6, name: 'Elite Staffing' },
        { id: 7, name: 'ProHire Solutions' },
        { id: 8, name: 'Talent Bridge' },
        { id: 9, name: 'NextGen Talent' },
        { id: 10, name: 'Dynamic Workforce' },
        { id: 11, name: 'Global Talent Hub' },
        { id: 12, name: 'Premier Placements' },
      ],
      pageSize: 10,
      pageSizes: [10, 25, 100],
      currentPage: 1,
      showPageDropdown: false,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.groups.length / this.pageSize) || 1;
    },
    paginatedGroups() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.groups.slice(start, start + this.pageSize);
    },
  },
  methods: {
    togglePageDropdown() {
      this.showPageDropdown = !this.showPageDropdown;
    },
    selectPageSize(size) {
      this.pageSize = size;
      this.currentPage = 1;
      this.showPageDropdown = false;
    },
    goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    viewGroup(group) {
      alert(`Viewing group: ${group.name}`);
    },
  },
};
</script>

<style scoped>
.my-org-table-outer {
  width: 100%;
  max-width: 1400px;
  min-width: auto;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
  background: none !important;
  padding: 0;
}
.my-org-table-card {
  width: 100%;
  max-width: 1400px;
  min-width: auto;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 0 auto;
  box-sizing: border-box;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
}
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
  min-width: auto;
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  margin-bottom: 8px;
  background: transparent;
  margin-left: 0;
  margin-right: 0;
  table-layout: auto;
  border: none;
  margin-top: 0;
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
.my-org-table th {
  background: #f8f8f8;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: auto;
  border-bottom: 1.5px solid #ebebeb;
}
.rounded-th-left {
  border-top-left-radius: 24px;
  border-bottom-left-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
}
.rounded-th-right {
  border-top-right-radius: 24px;
  border-bottom-right-radius: 24px;
  overflow: hidden;
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
@media (max-width: 1400px) {
  .my-org-table-outer {
    margin: 12px;
    max-width: 100%;
  }
  .my-org-table-card {
    max-width: 100%;
    border-radius: 14px;
  }
  .my-org-table-header {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
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
  .rounded-th-left {
    border-top-left-radius: 14px;
    border-bottom-left-radius: 14px;
  }
  .rounded-th-right {
    border-top-right-radius: 14px;
    border-bottom-right-radius: 14px;
  }
}
@media (max-width: 900px) {
  .my-org-table-outer {
    margin: 4px;
    max-width: 100%;
  }
  .my-org-table-card {
    border-radius: 10px;
  }
  .my-org-table-header {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
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
  .rounded-th-left {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
  }
  .rounded-th-right {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>
