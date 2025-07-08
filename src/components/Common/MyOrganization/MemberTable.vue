<template>
  <div class="member-table-outer">
    <div class="member-table-card">
      <div class="member-table-header">
        <h2 class="member-table-title">Members</h2>
        <!-- Add button or actions if needed -->
      </div>
      <div class="member-table-header-spacer"></div>
      <div class="member-table-container">
        <table class="member-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(member, idx) in paginatedMembers" :key="member.id">
              <td>{{ member.name }}</td>
              <td>{{ member.email }}</td>
              <td>{{ member.role }}</td>
              <td>{{ member.status }}</td>
              <td>
                <!-- Example action button -->
                <button class="member-action-btn" @click="viewMember(member)">View</button>
              </td>
            </tr>
            <tr v-if="paginatedMembers.length === 0">
              <td colspan="5" class="no-data">No members found.</td>
            </tr>
          </tbody>
        </table>
      </div>
      <Pagination
        :pageSize="pageSize"
        :pageSizes="[10, 25, 100]"
        :showPageDropdown="showPageDropdown"
        :currentPage="currentPage"
        :totalPages="totalPages"
        @goToPage="goToPage"
        @selectPageSize="selectPageSize"
        @togglePageDropdown="showPageDropdown = !showPageDropdown"
      />
    </div>
  </div>
</template>

<script>
// import Pagination from '@/components/layout/Pagination.vue'
// import MemberListing from '@/components/Common/MemberListing.vue'
export default {
  // name: 'MemberTable',
  // components: { Pagination, MemberListing },
  props: {
    members: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      currentPage: 1,
      pageSize: 10,
      showPageDropdown: false
    }
  },
  computed: {
    totalPages() {
      return Math.ceil(this.members.length / this.pageSize) || 1;
    },
    paginatedMembers() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.members.slice(start, start + this.pageSize);
    }
  },
  methods: {
    goToPage(page) {
      this.currentPage = page;
    },
    selectPageSize(size) {
      this.pageSize = size;
      this.currentPage = 1;
    },
    viewMember(member) {
      // Implement view logic
      this.$emit('view-member', member);
    }
  }
}
</script>

<style scoped>
.member-table-outer {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 64px;
  margin-bottom: 64px;
}
.member-table-card {
  width: 100%;
  max-width: 1400px;
  background: #fff;
  border-radius: 18px;
  border: 1px solid #EBEBEB;
  box-sizing: border-box;
  overflow: visible;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 0 auto;
}
.member-table-header {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 24px 46px 0 24px;
  background: #fff;
  border-top-left-radius: 18px;
  border-top-right-radius: 18px;
  min-height: 64px;
  box-sizing: border-box;
}
.member-table-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #222;
  margin: 0;
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
  border-bottom-left-radius: 18px;
  border-bottom-right-radius: 18px;
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
.member-table th, .member-table td {
  padding: 12px 8px;
  text-align: left;
  font-size: 14px;
  border-bottom: 1px solid #f0f0f0;
  background: #fff;
}
.member-table th {
  background: #F8F8F8;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: 100px;
  border-bottom: 1.5px solid #EBEBEB;
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
@media (max-width: 1400px) {
  .member-table-outer {
    margin: 12px;
  }
  .member-table-card {
    max-width: 100%;
    border-radius: 10px;
  }
  .member-table-header {
    padding: 8px 42px 0 8px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .member-table th, .member-table td {
    font-size: 12px;
    padding: 8px 4px;
  }
}
@media (max-width: 900px) {
  .member-table-outer {
    margin: 4px;
  }
  .member-table-card {
    border-radius: 6px;
  }
  .member-table-header {
    padding: 8px 40px 0 4px;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
  }
  .member-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 6px;
    border-bottom-right-radius: 6px;
  }
  .member-table th, .member-table td {
    font-size: 11px;
    padding: 6px 2px;
  }
}
</style>
