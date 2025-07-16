<template>
  <MainLayout>
    <div class="page">
      <div class="table-outer">
        <div class="table-card">
          <div class="table-header-bar">
            <button class="btn btn-primary">
              <img
                src="@/assets/images/Add.svg"
                alt="Add"
                class="user-permission-add-btn-icon"
              />
              Add New
            </button>
          </div>
          <div class="table-container">
            <table class="table">
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
                  <td class="actions actions-scroll">
                    <div class="actions-row">
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
                      <button class="btn-view">
                        <img
                          src="@/assets/images/Impersonate.svg"
                          alt="Impersonate"
                          class="btn-view-icon"
                        />
                        Impersonate
                      </button>
                    </div>
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
.actions-scroll {
  max-width: 220px;
  overflow-x: auto;
}
.actions-row {
  display: flex;
  flex-direction: row;
  gap: 8px;
  min-width: 220px;
}
.icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  padding: 0;
  margin: 0;
  background: #fff;
  border: none; /* Remove border */
  border-radius: 8px;
  cursor: pointer;
  transition: border 0.2s, box-shadow 0.2s;
}
.icon-btn img {
  width: 18px;
  height: 18px;
  display: block;
}
.icon-btn:hover {
  border: 1.5px solid #a1a1a1;
  box-shadow: 0 2px 8px rgba(33, 150, 243, 0.08);
}

/* Impersonate button style */
.impersonate-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 0 12px;
  height: 32px;
  font-size: 14px;
  color: #222;
  cursor: pointer;
  font-weight: 500;
  transition: border 0.2s, box-shadow 0.2s;
}
.impersonate-btn img.impersonate-icon {
  width: 18px;
  height: 18px;
  display: block;
}
.impersonate-btn:hover {
  border: 1.5px solid #0074c2;
  box-shadow: 0 2px 8px rgba(33, 150, 243, 0.08);
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
