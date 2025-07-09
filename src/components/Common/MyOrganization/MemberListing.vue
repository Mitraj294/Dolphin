<template>
  <MainLayout>
    <div class="page">
      <div class="member-table-outer">
        <div class="member-table-card">
          <div class="member-table-header">
            <div class="member-table-search-bar">
              <span class="member-search-icon">
                <svg
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="#888"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <circle
                    cx="11"
                    cy="11"
                    r="8"
                  />
                  <line
                    x1="21"
                    y1="21"
                    x2="16.65"
                    y2="16.65"
                  />
                </svg>
              </span>
              <input
                class="member-search"
                v-model="searchQuery"
                placeholder="Search Member..."
                @input="onSearch"
              />
            </div>
          </div>
          <div class="member-table-header-spacer"></div>
          <div class="member-table-container">
            <table class="member-table">
              <thead>
                <tr>
                  <th class="rounded-th-left">Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Role</th>
                  <th class="rounded-th-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(member, idx) in paginatedMembers"
                  :key="member.id"
                >
                  <td>{{ member.name }}</td>
                  <td>{{ member.email }}</td>
                  <td>{{ member.phone }}</td>
                  <td>{{ member.role }}</td>
                  <td>
                    <button
                      class="member-action-btn"
                      @click="viewMember(member)"
                    >
                      <span style="margin-right: 4px">
                        <img
                          src="@/assets/images/Notes.svg"
                          alt="View"
                          width="16"
                          height="16"
                        />
                      </span>
                      View
                    </button>
                  </td>
                </tr>
                <tr v-if="paginatedMembers.length === 0">
                  <td
                    colspan="5"
                    class="no-data"
                  >
                    No members found.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <Pagination
          :pageSize="pageSize"
          :pageSizes="[10, 25, 100]"
          :showPageDropdown="showPageDropdown"
          :currentPage="currentPage"
          :totalPages="totalPages"
          :paginationPages="paginationPages"
          @goToPage="goToPage"
          @selectPageSize="selectPageSize"
          @togglePageDropdown="showPageDropdown = !showPageDropdown"
        />
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
import Pagination from '@/components/layout/Pagination.vue';
export default {
  name: 'MemberListing',
  components: { MainLayout, Pagination },
  data() {
    return {
      currentPage: 1,
      pageSize: 10,
      searchQuery: '',
      showPageDropdown: false,
      members: [
        {
          id: 1,
          name: 'Emily Carter',
          email: 'emily@dolphin.org',
          phone: '+91 98765 43210',
          role: 'Manager',
          status: 'Active',
        },
        {
          id: 2,
          name: 'James Parker',
          email: 'james@dolphin.org',
          phone: '+91 91234 56789',
          role: 'CEO',
          status: 'Active',
        },
        {
          id: 3,
          name: 'Sophia Mitchell',
          email: 'sophia@dolphin.org',
          phone: '+91 99887 76655',
          role: 'Owner',
          status: 'Active',
        },
        {
          id: 4,
          name: 'Mason Walker',
          email: 'mason@dolphin.org',
          phone: '+91 90011 22334',
          role: 'Support',
          status: 'Active',
        },
        {
          id: 5,
          name: 'Olivia Bennett',
          email: 'olivia@dolphin.org',
          phone: '+91 88990 11223',
          role: 'Manager',
          status: 'Active',
        },
        {
          id: 6,
          name: 'Benjamin Hayes',
          email: 'benjamin@dolphin.org',
          phone: '+91 77665 54433',
          role: 'CEO',
          status: 'Active',
        },
        {
          id: 7,
          name: 'Ava Richardson',
          email: 'ava@dolphin.org',
          phone: '+91 95555 12345',
          role: 'Owner',
          status: 'Active',
        },
        {
          id: 8,
          name: 'Henry Cooper',
          email: 'henry@dolphin.org',
          phone: '+91 96666 77788',
          role: 'Support',
          status: 'Active',
        },
        {
          id: 9,
          name: 'Isabella Thompson',
          email: 'isabella@dolphin.org',
          phone: '+91 98888 99900',
          role: 'Support',
          status: 'Active',
        },
        {
          id: 10,
          name: 'Daniel Morgan',
          email: 'daniel@dolphin.org',
          phone: '+91 91122 33445',
          role: 'CEO',
          status: 'Active',
        },
      ],
      filteredMembers: [],
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.filteredMembers.length / this.pageSize) || 1;
    },
    paginatedMembers() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.filteredMembers.slice(start, start + this.pageSize);
    },
    paginationPages() {
      const total = this.totalPages;
      if (total <= 7) {
        return Array.from({ length: total }, (_, i) => i + 1);
      } else {
        const pages = [1];
        if (this.currentPage > 4) pages.push('...');
        for (
          let i = Math.max(2, this.currentPage - 1);
          i <= Math.min(total - 1, this.currentPage + 1);
          i++
        ) {
          pages.push(i);
        }
        if (this.currentPage < total - 3) pages.push('...');
        pages.push(total);
        return pages;
      }
    },
  },
  methods: {
    onSearch() {
      const q = this.searchQuery.trim().toLowerCase();
      if (!q) {
        this.filteredMembers = this.members;
      } else {
        this.filteredMembers = this.members.filter(
          (m) =>
            m.name.toLowerCase().includes(q) ||
            m.email.toLowerCase().includes(q) ||
            m.phone.replace(/\s+/g, '').includes(q.replace(/\s+/g, '')) ||
            m.role.toLowerCase().includes(q)
        );
      }
      this.currentPage = 1;
    },
    goToPage(page) {
      if (page === '...' || page < 1 || page > this.totalPages) return;
      this.currentPage = page;
    },
    selectPageSize(size) {
      this.pageSize = size;
      this.currentPage = 1;
      this.showPageDropdown = false;
    },
    viewMember(member) {
      // Implement view logic
      this.$emit('view-member', member);
    },
  },
  mounted() {
    this.filteredMembers = this.members;
  },
};
</script>

<style scoped>
.member-table-outer {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
}
.member-table-card {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #ebebeb;
  box-sizing: border-box;
  overflow: visible;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
}
.member-table-header {
  width: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  padding: 24px 46px 0 24px;
  background: #fff;
  border-top-left-radius: 24px;
  border-top-right-radius: 24px;
  min-height: 64px;
  box-sizing: border-box;
}
.member-table-search-bar {
  display: flex;
  align-items: center;
  background: #f8f8f8;
  border-radius: 24px;
  padding: 0 16px;
  width: 260px;
  height: 36px;
  box-sizing: border-box;
  border: none;
  box-shadow: none;
}
.member-search-icon {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 6px;
  opacity: 0.7;
}
.member-search {
  border: none;
  outline: none;
  background: transparent;
  font-size: 15px;
  width: 100%;
  color: #222;
  height: 36px;
  line-height: 36px;
  padding: 0;
}
.member-search::placeholder {
  color: #aaa;
  font-size: 15px;
}
.member-table-header-spacer {
  height: 18px;
  width: 100%;
  background: transparent;
  display: block;
}
.member-table-container {
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
.member-table-container::-webkit-scrollbar {
  display: none;
}
.member-table {
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
.member-table th,
.member-table td {
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
.member-table th {
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
.member-table td {
  color: #222;
  background: #fff;
}
.member-action-btn {
  background: #fff;
  border: 1.5px solid #e0e0e0;
  border-radius: 24px;
  padding: 4px 12px;
  font-size: 14px;
  color: #222;
  cursor: pointer;
  transition: border 0.2s;
  font-weight: 500;
  gap: 4px;
  display: flex;
  align-items: center;
}
.member-action-btn:hover {
  border: 1.5px solid #0074c2;
}
.no-data {
  text-align: center;
  color: #888;
  font-size: 16px;
  padding: 32px 0;
}
/* Responsive styles to match base pages */
@media (max-width: 1400px) {
  .member-table-outer {
    margin: 12px;
    max-width: 100%;
  }
  .member-table-card {
    max-width: 100%;
    border-radius: 14px;
  }
  .member-table-header {
    padding: 8px 8px 0 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .member-table-search-bar {
    padding: 0 8px;
    border-radius: 14px;
    width: 220px;
  }
  .member-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
  }
  .member-table th,
  .member-table td {
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
  .member-search {
    font-size: 13px;
  }
}
@media (max-width: 900px) {
  .member-table-outer {
    margin: 4px;
    max-width: 100%;
  }
  .member-table-card {
    border-radius: 10px;
  }
  .member-table-header {
    padding: 8px 4px 0 4px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .member-table-search-bar {
    padding: 0 4px;
    border-radius: 10px;
    width: 100%;
    max-width: 100%;
  }
  .member-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  .member-table th,
  .member-table td {
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
  .member-search {
    font-size: 11px;
  }
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
