<template>
  <MainLayout>
    <div class="page">
      <div class="user-permission-table-outer">
        <div class="user-permission-table-card">
          <div class="user-permission-table-header-bar">
            <button class="btn btn-primary">
              <img
                src="@/assets/images/Add.svg"
                alt="Add"
                class="user-permission-add-btn-icon"
              />
              Add New
            </button>
          </div>
          <div class="user-permission-table-container">
            <table class="user-permission-table">
              <TableHeader
                :columns="[
                  { label: 'Name', key: 'name' },
                  { label: 'Email', key: 'email' },
                  { label: 'Roles', key: 'role' },
                  { label: 'Actions', key: 'actions' },
                ]"
                @sort="sortBy"
              />
              <tbody>
                <tr
                  v-for="user in paginatedUsers"
                  :key="user.email"
                >
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.role }}</td>
                  <td class="actions">
                    <button
                      class="icon-btn"
                      title="Edit"
                    >
                      <img
                        src="@/assets/images/EditBlack.svg"
                        alt="Edit"
                      />
                    </button>
                    <button
                      class="icon-btn"
                      title="Delete"
                    >
                      <img
                        src="@/assets/images/Delete icon.svg"
                        alt="Delete"
                      />
                    </button>
                    <button class="impersonate-btn">
                      <img
                        src="@/assets/images/Impersonate.svg"
                        alt="Impersonate"
                        class="impersonate-icon"
                      />
                      Impersonate
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <Pagination
          :pageSize="pageSize"
          :pageSizes="pageSizes"
          :showPageDropdown="showPageDropdown"
          :currentPage="currentPage"
          :totalPages="totalPages"
          @togglePageDropdown="showPageDropdown = !showPageDropdown"
          @selectPageSize="selectPageSize"
          @goToPage="goToPage"
        />
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../../layout/MainLayout.vue';
import Pagination from '../../layout/Pagination.vue';
import TableHeader from '@/components/Common/Common_UI/TableHeader.vue';
export default {
  name: 'UserPermission',
  components: { MainLayout, Pagination, TableHeader },
  data() {
    return {
      users: [
        { name: 'Aaliyah Moses', email: 'aaliyah@dolphin.org', role: 'Admin' },
        { name: 'Clarence Reed', email: 'clarence@dolphin.org', role: 'Admin' },
        {
          name: 'Patricia Smith',
          email: 'patricia@dolphin.org',
          role: 'Admin',
        },
        { name: 'Mary Bracker', email: 'mary@dolphin.org', role: 'Support' },
        {
          name: 'Beatrice Bonds',
          email: 'beatrice@dolphin.org',
          role: 'Support',
        },
        { name: 'Kia Okiria', email: 'kia@whyn.org', role: 'Sales' },
        { name: 'Daisy Doe', email: 'daisy@dolphin.org', role: 'Sales' },
        { name: 'John Smith', email: 'john@dolphin.org', role: 'Sales' },
        { name: 'David Dallas', email: 'david@dolphin.org', role: 'Admin' },
        { name: 'Bella Moses', email: 'bella@dolphin.org', role: 'Sales' },
      ],
      currentPage: 1,
      pageSize: 10,
      pageSizes: [10, 25, 100],
      showPageDropdown: false,
      sortKey: '',
      sortAsc: true,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.users.length / this.pageSize) || 1;
    },
    paginatedUsers() {
      let users = [...this.users];
      if (this.sortKey) {
        users.sort((a, b) => {
          const aVal = a[this.sortKey] || '';
          const bVal = b[this.sortKey] || '';
          if (aVal < bVal) return this.sortAsc ? -1 : 1;
          if (aVal > bVal) return this.sortAsc ? 1 : -1;
          return 0;
        });
      }
      const start = (this.currentPage - 1) * this.pageSize;
      return users.slice(start, start + this.pageSize);
    },
  },
  methods: {
    goToPage(page) {
      if (page < 1 || page > this.totalPages) return;
      this.currentPage = page;
    },
    selectPageSize(size) {
      this.pageSize = size;
      this.currentPage = 1;
      this.showPageDropdown = false;
    },
    sortBy(key) {
      if (this.sortKey === key) {
        this.sortAsc = !this.sortAsc;
      } else {
        this.sortKey = key;
        this.sortAsc = true;
      }
    },
  },
};
</script>

<style scoped>
/* --- Layout and spacing to match Leads.vue/OrganizationTable.vue --- */
.user-permission-table-outer {
  width: 100%;
  max-width: 1400px;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
}
.user-permission-table-card {
  width: 100%;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  overflow: visible;
  margin: 0 auto;
  box-sizing: border-box;
  min-width: 0;
  max-width: 1400px;
}
.user-permission-table-header-bar {
  padding: 24px 24px 24px 24px;
  background: #fff;
  border-top-left-radius: 24px;
  border-top-right-radius: 24px;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-end;
}
@media (max-width: 1400px) {
  .user-permission-table-header-bar {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
}
@media (max-width: 900px) {
  .user-permission-table-header-bar {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
}
.user-permission-table-container {
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
.user-permission-table-container::-webkit-scrollbar {
  display: none;
}
.user-permission-table {
  min-width: 800px;
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
.user-permission-table th,
.user-permission-table td {
  padding: 18px 12px;
  text-align: left;
  font-size: 15px;
  border-bottom: 1.5px solid #f0f0f0;
  background: #fff;
  font-family: 'Inter', Arial, sans-serif;
  font-weight: 400;
  line-height: 22px;
}
.user-permission-table td:first-child {
  padding-left: 20px !important;
}
.user-permission-table th {
  background: #f8f8f8;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: 100px;
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
.actions {
  display: flex;
  align-items: center;
  gap: 8px;
}
.icon-btn {
  background: none;
  border: none;
  margin-right: 8px;
  cursor: pointer;
  padding: 4px;
  border-radius: 6px;
  transition: background 0.18s;
  display: flex;
  align-items: center;
}
.icon-btn img {
  width: 18px;
  height: 18px;
  display: block;
}
.icon-btn:hover,
.icon-btn:focus {
  background: #f0f0f0;
}
.impersonate-btn {
  background: #f5f5f5;
  border: none;
  border-radius: 999px;
  padding: 10px 24px 10px 14px;
  font-size: 1.08rem;
  color: #222;
  font-weight: 400;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  box-shadow: none;
  transition: background 0.18s, color 0.18s, font-size 0.18s, padding 0.18s;
}
.impersonate-btn:hover,
.impersonate-btn:focus {
  background: #e6f0fa;
  color: #0164a5;
  outline: none;
}
.impersonate-icon {
  width: 18px;
  height: 18px;
  margin-right: 4px;
  display: block;
}
@media (max-width: 1400px) {
  .user-permission-table-outer {
    margin: 12px;
    max-width: 100%;
  }
  .user-permission-table-card {
    border-radius: 14px;
    max-width: 100%;
  }
  .user-permission-table-header-bar {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .user-permission-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
  }
  .user-permission-table th,
  .user-permission-table td {
    font-size: 12px;
    padding: 8px 4px;
  }
  .user-permission-table th {
    height: 40px;
    line-height: 40px;
    padding: 0 4px;
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
  .user-permission-table-outer {
    margin: 4px;
    max-width: 100%;
  }
  .user-permission-table-card {
    border-radius: 10px;
  }
  .user-permission-table-header-bar {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .user-permission-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  .user-permission-table th,
  .user-permission-table td {
    font-size: 11px;
    padding: 6px 2px;
  }
  .user-permission-table th {
    height: 36px;
    line-height: 36px;
    padding: 0 2px;
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

.user-permission-add-btn-icon {
  width: 18px;
  height: 18px;
  margin-right: 6px;
  display: inline-block;
  vertical-align: middle;
}
.page {
  padding: 0 32px 32px 32px;
  display: flex;
  background-color: #fff;
  justify-content: center;
  box-sizing: border-box;
}
@media (max-width: 1400px) {
  .page {
    padding: 16px;
  }
}
@media (max-width: 900px) {
  .page {
    padding: 4px;
  }
}
</style>
