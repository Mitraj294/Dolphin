<template>
  <div class="org-table-outer">
    <div class="org-table-card">
      <div class="org-table-search-bar">
        <input
          class="org-search"
          placeholder="Search Organization Name"
          v-model="search"
        />
      </div>
      <div class="org-table-container">
        <table class="org-table">
          <thead>
            <tr>
              <th class="rounded-th-left">
                <span class="org-th-content">
                  Organizations Name
                  <button
                    class="org-th-sort-btn"
                    type="button"
                    @click="sortBy('name')"
                  >
                    <img
                      src="@/assets/images/up-down.svg"
                      class="org-th-sort"
                      alt="Sort"
                    />
                  </button>
                </span>
              </th>
              <th>
                <span class="org-th-content">Size</span>
              </th>
              <th>
                <span class="org-th-content">Admin Name</span>
              </th>
              <th>
                <span class="org-th-content">
                  Contract Start
                  <button
                    class="org-th-sort-btn"
                    type="button"
                    @click="sortBy('contractStart')"
                  >
                    <img
                      src="@/assets/images/up-down.svg"
                      class="org-th-sort"
                      alt="Sort"
                    />
                  </button>
                </span>
              </th>
              <th>
                <span class="org-th-content">
                  Contract End
                  <button
                    class="org-th-sort-btn"
                    type="button"
                    @click="sortBy('contractEnd')"
                  >
                    <img
                      src="@/assets/images/up-down.svg"
                      class="org-th-sort"
                      alt="Sort"
                    />
                  </button>
                </span>
              </th>
              <th>
                <span class="org-th-content">Last Login</span>
              </th>
              <th class="rounded-th-right">
                <span class="org-th-content">Action</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="org in paginatedOrganizations"
              :key="org.name"
            >
              <td class="org-td-content">{{ org.name }}</td>
              <td class="org-td-content">{{ org.size }}</td>
              <td class="org-td-content">{{ org.admin }}</td>
              <td class="org-td-content">{{ org.contractStart }}</td>
              <td class="org-td-content">{{ org.contractEnd }}</td>
              <td class="org-td-content">{{ org.lastLogin }}</td>
              <td class="org-td-content">
                <button
                  class="btn-view-detail"
                  @click="goToDetail(org)"
                >
                  <img
                    src="@/assets/images/Detail.svg"
                    alt="View"
                    class="btn-view-detail-icon"
                  />
                  View Detail
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <Pagination
      :withPagination="true"
      :pageSize="pageSize"
      :pageSizes="[10, 25, 100]"
      :showPageDropdown="showPageDropdown"
      :currentPage="currentPage"
      :totalPages="totalPages"
      :paginationPages="paginationPages"
      @togglePageDropdown="showPageDropdown = !showPageDropdown"
      @selectPageSize="selectPageSize"
      @goToPage="goToPage"
    />
  </div>
</template>

<script>
import Pagination from '@/components/layout/Pagination.vue';
export default {
  name: 'OrganizationTable',
  components: {
    Pagination,
  },
  data() {
    return {
      search: '',
      showPageDropdown: false,
      pageSize: 10,
      currentPage: 1,
      sortKey: '',
      sortAsc: true,
      organizations: [
        {
          name: 'Alpha Group',
          size: 'Large',
          admin: 'Alice Adams',
          contractStart: 'Feb 1, 2024',
          contractEnd: 'Feb 1, 2025',
          lastLogin: 'Feb 2, 2025 at 9:00 am',
        },
        {
          name: 'Beta Solutions',
          size: 'Small',
          admin: 'Bob Brown',
          contractStart: 'Mar 10, 2024',
          contractEnd: 'Mar 10, 2025',
          lastLogin: 'Mar 11, 2025 at 10:30 am',
        },
        {
          name: 'Crest Corp',
          size: 'Large',
          admin: 'Cathy Clark',
          contractStart: 'Apr 5, 2024',
          contractEnd: 'Apr 5, 2025',
          lastLogin: 'Apr 6, 2025 at 11:15 am',
        },
        {
          name: 'Delta Dynamics',
          size: 'Small',
          admin: 'David Duke',
          contractStart: 'May 12, 2024',
          contractEnd: 'May 12, 2025',
          lastLogin: 'May 13, 2025 at 8:45 am',
        },
        {
          name: 'Echo Enterprises',
          size: 'Large',
          admin: 'Eve Evans',
          contractStart: 'Jun 18, 2024',
          contractEnd: 'Jun 18, 2025',
          lastLogin: 'Jun 19, 2025 at 10:20 am',
        },
        {
          name: 'Flexi-Finders',
          size: 'Large',
          admin: 'Aaliyah Moss',
          contractStart: 'Jun 18, 2024',
          contractEnd: 'Jun-18-2025',
          lastLogin: 'Feb 18, 2025 at 10:20 am',
        },
        {
          name: 'Gamma Group',
          size: 'Small',
          admin: 'Gina Green',
          contractStart: 'Jul 7, 2024',
          contractEnd: 'Jul 7, 2025',
          lastLogin: 'Jul 8, 2025 at 2:00 pm',
        },
        {
          name: 'Helix Holdings',
          size: 'Large',
          admin: 'Hank Hill',
          contractStart: 'Aug 15, 2024',
          contractEnd: 'Aug 15, 2025',
          lastLogin: 'Aug 16, 2025 at 3:30 pm',
        },
        {
          name: 'Interim Solutions',
          size: 'Small',
          admin: 'Clarence Reed',
          contractStart: 'Jan 1, 2024',
          contractEnd: 'Dec 31, 2024',
          lastLogin: 'Jan 4, 2025 at 4:40 pm',
        },
        {
          name: 'Jupiter Jobs',
          size: 'Large',
          admin: 'Julia James',
          contractStart: 'Sep 20, 2024',
          contractEnd: 'Sep 20, 2025',
          lastLogin: 'Sep 21, 2025 at 5:10 pm',
        },
        {
          name: 'Kappa Konsulting',
          size: 'Small',
          admin: 'Karl King',
          contractStart: 'Oct 2, 2024',
          contractEnd: 'Oct 2, 2025',
          lastLogin: 'Oct 3, 2025 at 6:00 pm',
        },
        {
          name: 'Lambda Labs',
          size: 'Large',
          admin: 'Laura Lane',
          contractStart: 'Nov 11, 2024',
          contractEnd: 'Nov 11, 2025',
          lastLogin: 'Nov 12, 2025 at 7:20 pm',
        },
        {
          name: 'Matrix Media',
          size: 'Small',
          admin: 'Mona Moore',
          contractStart: 'Dec 25, 2024',
          contractEnd: 'Dec 25, 2025',
          lastLogin: 'Dec 26, 2025 at 8:00 am',
        },
        {
          name: 'Nova Networks',
          size: 'Large',
          admin: 'Nina North',
          contractStart: 'Jan 3, 2025',
          contractEnd: 'Jan 3, 2026',
          lastLogin: 'Jan 4, 2026 at 9:30 am',
        },
        {
          name: 'Omega Org',
          size: 'Small',
          admin: 'Oscar Owen',
          contractStart: 'Feb 14, 2025',
          contractEnd: 'Feb 14, 2026',
          lastLogin: 'Feb 15, 2026 at 10:10 am',
        },
        {
          name: 'Prime Partners',
          size: 'Large',
          admin: 'Paula Price',
          contractStart: 'Mar 8, 2025',
          contractEnd: 'Mar 8, 2026',
          lastLogin: 'Mar 9, 2026 at 11:50 am',
        },
        {
          name: 'QuickStaff',
          size: 'Large',
          admin: 'Mary Brucker',
          contractStart: 'Jan 1, 2025',
          contractEnd: 'Dec 31, 2025',
          lastLogin: 'Nov 4, 2024 at 12:10 pm',
        },
        {
          name: 'Rocket Resources',
          size: 'Small',
          admin: 'Rita Ray',
          contractStart: 'Apr 17, 2025',
          contractEnd: 'Apr 17, 2026',
          lastLogin: 'Apr 18, 2026 at 12:30 pm',
        },
        {
          name: 'Sigma Systems',
          size: 'Large',
          admin: 'Sam Smith',
          contractStart: 'May 22, 2025',
          contractEnd: 'May 22, 2026',
          lastLogin: 'May 23, 2026 at 1:40 pm',
        },
        {
          name: 'Titan Tech',
          size: 'Small',
          admin: 'Tina Turner',
          contractStart: 'Jun 30, 2025',
          contractEnd: 'Jun 30, 2026',
          lastLogin: 'Jul 1, 2026 at 2:20 pm',
        },
        {
          name: 'Umbrella United',
          size: 'Large',
          admin: 'Uma Underwood',
          contractStart: 'Jul 19, 2025',
          contractEnd: 'Jul 19, 2026',
          lastLogin: 'Jul 20, 2026 at 3:00 pm',
        },
        {
          name: 'Vega Ventures',
          size: 'Small',
          admin: 'Victor Voss',
          contractStart: 'Aug 8, 2025',
          contractEnd: 'Aug 8, 2026',
          lastLogin: 'Aug 9, 2026 at 4:10 pm',
        },
        {
          name: 'WaveWorks',
          size: 'Large',
          admin: 'Wendy White',
          contractStart: 'Sep 14, 2025',
          contractEnd: 'Sep 14, 2026',
          lastLogin: 'Sep 15, 2026 at 5:30 pm',
        },
        {
          name: 'Xeno Xperts',
          size: 'Small',
          admin: 'Xander Xu',
          contractStart: 'Oct 27, 2025',
          contractEnd: 'Oct 27, 2026',
          lastLogin: 'Oct 28, 2026 at 6:40 pm',
        },
        {
          name: 'Yield Yard',
          size: 'Large',
          admin: 'Yara Young',
          contractStart: 'Nov 5, 2025',
          contractEnd: 'Nov 5, 2026',
          lastLogin: 'Nov 6, 2026 at 7:50 pm',
        },
        {
          name: 'Zenith Zone',
          size: 'Small',
          admin: 'Zane Zeller',
          contractStart: 'Dec 12, 2025',
          contractEnd: 'Dec 12, 2026',
          lastLogin: 'Dec 13, 2026 at 8:00 am',
        },
        {
          name: 'Atlas Associates',
          size: 'Large',
          admin: 'Ava Allen',
          contractStart: 'Jan 15, 2024',
          contractEnd: 'Jan 15, 2025',
          lastLogin: 'Jan 16, 2025 at 9:10 am',
        },
        {
          name: 'Bright Bridge',
          size: 'Small',
          admin: 'Ben Brooks',
          contractStart: 'Feb 22, 2024',
          contractEnd: 'Feb 22, 2025',
          lastLogin: 'Feb 23, 2025 at 10:20 am',
        },
        {
          name: 'Clever Crew',
          size: 'Large',
          admin: 'Cara Carter',
          contractStart: 'Mar 29, 2024',
          contractEnd: 'Mar 29, 2025',
          lastLogin: 'Mar 30, 2025 at 11:30 am',
        },
        {
          name: 'Dynamic Devs',
          size: 'Small',
          admin: 'Derek Dean',
          contractStart: 'Apr 6, 2024',
          contractEnd: 'Apr 6, 2025',
          lastLogin: 'Apr 7, 2025 at 12:40 pm',
        },
        {
          name: 'Elite Experts',
          size: 'Large',
          admin: 'Ella East',
          contractStart: 'May 13, 2024',
          contractEnd: 'May 13, 2025',
          lastLogin: 'May 14, 2025 at 1:50 pm',
        },
        {
          name: 'Alpha Group',
          size: 'Large',
          admin: 'Alice Adams',
          contractStart: 'Feb 1, 2024',
          contractEnd: 'Feb 1, 2025',
          lastLogin: 'Feb 2, 2025 at 9:00 am',
        },
        {
          name: 'Beta Solutions',
          size: 'Small',
          admin: 'Bob Brown',
          contractStart: 'Mar 10, 2024',
          contractEnd: 'Mar 10, 2025',
          lastLogin: 'Mar 11, 2025 at 10:30 am',
        },
        {
          name: 'Crest Corp',
          size: 'Large',
          admin: 'Cathy Clark',
          contractStart: 'Apr 5, 2024',
          contractEnd: 'Apr 5, 2025',
          lastLogin: 'Apr 6, 2025 at 11:15 am',
        },
        {
          name: 'Delta Dynamics',
          size: 'Small',
          admin: 'David Duke',
          contractStart: 'May 12, 2024',
          contractEnd: 'May 12, 2025',
          lastLogin: 'May 13, 2025 at 8:45 am',
        },
        {
          name: 'Echo Enterprises',
          size: 'Large',
          admin: 'Eve Evans',
          contractStart: 'Jun 18, 2024',
          contractEnd: 'Jun 18, 2025',
          lastLogin: 'Jun 19, 2025 at 10:20 am',
        },
        {
          name: 'Flexi-Finders',
          size: 'Large',
          admin: 'Aaliyah Moss',
          contractStart: 'Jun 18, 2024',
          contractEnd: 'Jun-18-2025',
          lastLogin: 'Feb 18, 2025 at 10:20 am',
        },
        {
          name: 'Gamma Group',
          size: 'Small',
          admin: 'Gina Green',
          contractStart: 'Jul 7, 2024',
          contractEnd: 'Jul 7, 2025',
          lastLogin: 'Jul 8, 2025 at 2:00 pm',
        },
        {
          name: 'Helix Holdings',
          size: 'Large',
          admin: 'Hank Hill',
          contractStart: 'Aug 15, 2024',
          contractEnd: 'Aug 15, 2025',
          lastLogin: 'Aug 16, 2025 at 3:30 pm',
        },
        {
          name: 'Interim Solutions',
          size: 'Small',
          admin: 'Clarence Reed',
          contractStart: 'Jan 1, 2024',
          contractEnd: 'Dec 31, 2024',
          lastLogin: 'Jan 4, 2025 at 4:40 pm',
        },
        {
          name: 'Jupiter Jobs',
          size: 'Large',
          admin: 'Julia James',
          contractStart: 'Sep 20, 2024',
          contractEnd: 'Sep 20, 2025',
          lastLogin: 'Sep 21, 2025 at 5:10 pm',
        },
        {
          name: 'Kappa Konsulting',
          size: 'Small',
          admin: 'Karl King',
          contractStart: 'Oct 2, 2024',
          contractEnd: 'Oct 2, 2025',
          lastLogin: 'Oct 3, 2025 at 6:00 pm',
        },
        {
          name: 'Lambda Labs',
          size: 'Large',
          admin: 'Laura Lane',
          contractStart: 'Nov 11, 2024',
          contractEnd: 'Nov 11, 2025',
          lastLogin: 'Nov 12, 2025 at 7:20 pm',
        },
        {
          name: 'Matrix Media',
          size: 'Small',
          admin: 'Mona Moore',
          contractStart: 'Dec 25, 2024',
          contractEnd: 'Dec 25, 2025',
          lastLogin: 'Dec 26, 2025 at 8:00 am',
        },
        {
          name: 'Nova Networks',
          size: 'Large',
          admin: 'Nina North',
          contractStart: 'Jan 3, 2025',
          contractEnd: 'Jan 3, 2026',
          lastLogin: 'Jan 4, 2026 at 9:30 am',
        },
        {
          name: 'Omega Org',
          size: 'Small',
          admin: 'Oscar Owen',
          contractStart: 'Feb 14, 2025',
          contractEnd: 'Feb 14, 2026',
          lastLogin: 'Feb 15, 2026 at 10:10 am',
        },
        {
          name: 'Prime Partners',
          size: 'Large',
          admin: 'Paula Price',
          contractStart: 'Mar 8, 2025',
          contractEnd: 'Mar 8, 2026',
          lastLogin: 'Mar 9, 2026 at 11:50 am',
        },
        {
          name: 'QuickStaff',
          size: 'Large',
          admin: 'Mary Brucker',
          contractStart: 'Jan 1, 2025',
          contractEnd: 'Dec 31, 2025',
          lastLogin: 'Nov 4, 2024 at 12:10 pm',
        },
        {
          name: 'Rocket Resources',
          size: 'Small',
          admin: 'Rita Ray',
          contractStart: 'Apr 17, 2025',
          contractEnd: 'Apr 17, 2026',
          lastLogin: 'Apr 18, 2026 at 12:30 pm',
        },
        {
          name: 'Sigma Systems',
          size: 'Large',
          admin: 'Sam Smith',
          contractStart: 'May 22, 2025',
          contractEnd: 'May 22, 2026',
          lastLogin: 'May 23, 2026 at 1:40 pm',
        },
        {
          name: 'Titan Tech',
          size: 'Small',
          admin: 'Tina Turner',
          contractStart: 'Jun 30, 2025',
          contractEnd: 'Jun 30, 2026',
          lastLogin: 'Jul 1, 2026 at 2:20 pm',
        },
        {
          name: 'Umbrella United',
          size: 'Large',
          admin: 'Uma Underwood',
          contractStart: 'Jul 19, 2025',
          contractEnd: 'Jul 19, 2026',
          lastLogin: 'Jul 20, 2026 at 3:00 pm',
        },
        {
          name: 'Vega Ventures',
          size: 'Small',
          admin: 'Victor Voss',
          contractStart: 'Aug 8, 2025',
          contractEnd: 'Aug 8, 2026',
          lastLogin: 'Aug 9, 2026 at 4:10 pm',
        },
        {
          name: 'WaveWorks',
          size: 'Large',
          admin: 'Wendy White',
          contractStart: 'Sep 14, 2025',
          contractEnd: 'Sep 14, 2026',
          lastLogin: 'Sep 15, 2026 at 5:30 pm',
        },
        {
          name: 'Xeno Xperts',
          size: 'Small',
          admin: 'Xander Xu',
          contractStart: 'Oct 27, 2025',
          contractEnd: 'Oct 27, 2026',
          lastLogin: 'Oct 28, 2026 at 6:40 pm',
        },
        {
          name: 'Yield Yard',
          size: 'Large',
          admin: 'Yara Young',
          contractStart: 'Nov 5, 2025',
          contractEnd: 'Nov 5, 2026',
          lastLogin: 'Nov 6, 2026 at 7:50 pm',
        },
        {
          name: 'Zenith Zone',
          size: 'Small',
          admin: 'Zane Zeller',
          contractStart: 'Dec 12, 2025',
          contractEnd: 'Dec 12, 2026',
          lastLogin: 'Dec 13, 2026 at 8:00 am',
        },
        {
          name: 'Atlas Associates',
          size: 'Large',
          admin: 'Ava Allen',
          contractStart: 'Jan 15, 2024',
          contractEnd: 'Jan 15, 2025',
          lastLogin: 'Jan 16, 2025 at 9:10 am',
        },
        {
          name: 'Bright Bridge',
          size: 'Small',
          admin: 'Ben Brooks',
          contractStart: 'Feb 22, 2024',
          contractEnd: 'Feb 22, 2025',
          lastLogin: 'Feb 23, 2025 at 10:20 am',
        },
        {
          name: 'Clever Crew',
          size: 'Large',
          admin: 'Cara Carter',
          contractStart: 'Mar 29, 2024',
          contractEnd: 'Mar 29, 2025',
          lastLogin: 'Mar 30, 2025 at 11:30 am',
        },
        {
          name: 'Dynamic Devs',
          size: 'Small',
          admin: 'Derek Dean',
          contractStart: 'Apr 6, 2024',
          contractEnd: 'Apr 6, 2025',
          lastLogin: 'Apr 7, 2025 at 12:40 pm',
        },
        {
          name: 'Elite Experts',
          size: 'Large',
          admin: 'Ella East',
          contractStart: 'May 13, 2024',
          contractEnd: 'May 13, 2025',
          lastLogin: 'May 14, 2025 at 1:50 pm',
        },
      ],
    };
  },
  computed: {
    filteredOrganizations() {
      let orgs = this.organizations;
      if (this.search) {
        orgs = orgs.filter((org) =>
          org.name.toLowerCase().includes(this.search.toLowerCase())
        );
      }
      if (this.sortKey) {
        orgs = orgs.slice().sort((a, b) => {
          let aVal = a[this.sortKey];
          let bVal = b[this.sortKey];
          // For contractStart and contractEnd, sort as dates
          if (
            this.sortKey === 'contractStart' ||
            this.sortKey === 'contractEnd'
          ) {
            // Try to parse as Date, fallback to string compare
            const aDate = new Date(aVal);
            const bDate = new Date(bVal);
            if (!isNaN(aDate) && !isNaN(bDate)) {
              return this.sortAsc ? aDate - bDate : bDate - aDate;
            }
          }
          // Default: string compare
          if (aVal < bVal) return this.sortAsc ? -1 : 1;
          if (aVal > bVal) return this.sortAsc ? 1 : -1;
          return 0;
        });
      }
      return orgs;
    },
    totalPages() {
      return Math.ceil(this.filteredOrganizations.length / this.pageSize) || 1;
    },
    paginatedOrganizations() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.filteredOrganizations.slice(start, start + this.pageSize);
    },
    paginationPages() {
      const pages = [];
      if (10 <= 7) {
        for (let i = 1; i <= 10; i++) pages.push(i);
      } else {
        pages.push(1, 2, 3, '...', 8, 9, 10);
      }
      return pages;
    },
  },
  methods: {
    goToDetail(org) {
      this.$router.push({
        name: 'OrganizationDetail',
        params: { orgName: org.name },
      });
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
/* --- Layout and spacing to match reference page --- */
.org-table-outer {
  width: 100%;
  max-width: 1400px;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
}

.org-table-card {
  width: 100%;
  background: #fff;
  border-radius: 24px; /* was 18px */
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  overflow: visible;
  margin: 0 auto;
  box-sizing: border-box;
}

.org-table-search-bar {
  padding: 24px 46px 0 24px;
  background: #fff;
  border-top-left-radius: 24px; /* was 18px */
  border-top-right-radius: 24px; /* was 18px */
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
}

.org-search {
  width: 260px;
  padding: 8px 24px 8px 32px;
  border-radius: 12px;
  border: none;
  background: #f8f8f8;
  font-size: 14px;
  margin: 0 0 16px 0;
  outline: none;
  background-image: url('data:image/svg+xml;utf8,<svg fill="gray" height="14" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99c.41.41 1.09.41 1.5 0s.41-1.09 0-1.5l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>');
  background-repeat: no-repeat;
  background-position: 8px center;
  margin-left: 0;
  margin-right: auto;
}

.org-table-container {
  width: 100%;
  overflow-x: auto;
  box-sizing: border-box;
  padding: 0 24px 24px 24px;
  background: #fff;
  border-bottom-left-radius: 24px; /* was 18px */
  border-bottom-right-radius: 24px; /* was 18px */
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.org-table-container::-webkit-scrollbar {
  display: none;
}

.org-table {
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

.org-table th,
.org-table td {
  padding: 12px 8px;
  text-align: left;
  font-size: 14px;
  border-bottom: 1px solid #f0f0f0;
  background: #fff;
}

.org-table th {
  background: #f8f8f8;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: 100px;
  border-bottom: 1.5px solid #ebebeb;
}

.rounded-th-left {
  border-top-left-radius: 24px; /* was 18px */
  border-bottom-left-radius: 24px; /* was 18px */
  overflow: hidden;
  background: #f8f8f8;
}
.rounded-th-right {
  border-top-right-radius: 24px; /* was 18px */
  border-bottom-right-radius: 24px; /* was 18px */
  overflow: hidden;
  background: #f8f8f8;
}

.org-td-content,
.org-table th,
.org-th-content,
.org-table td {
  font-family: 'Inter', Arial, sans-serif;
  font-weight: 400;
  font-size: 13px;
  line-height: 18px;
  letter-spacing: 0.01em;
}

.org-td-content {
  color: #222;
  padding-left: 0;
  background: #fff;
}

/* --- Pagination and footer spacing --- */
.org-footer-row {
  width: 100%;
  max-width: 1200px;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  margin-top: 18px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 0;
  padding-right: 0;
  box-sizing: border-box;
}

/* Responsive: shrink margin and font on small screens */
@media (max-width: 1400px) {
  .org-table-outer {
    margin: 12px;
    max-width: 100%;
  }
  .org-table-card {
    border-radius: 14px; /* was 10px */
  }
  .org-table-search-bar {
    padding: 8px 8px 0 8px;
    border-top-left-radius: 14px; /* was 10px */
    border-top-right-radius: 14px; /* was 10px */
  }
  .org-search {
    font-size: 13px;
    padding: 8px 16px 8px 32px;
    max-width: 320px;
    border-radius: 12px;
    margin: 0 0 16px 0;
  }
  .org-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px; /* was 10px */
    border-bottom-right-radius: 14px; /* was 10px */
  }
  .org-table th,
  .org-table td {
    font-size: 12px;
    padding: 8px 4px;
  }
}

@media (max-width: 900px) {
  .org-table-outer {
    margin: 4px;
    max-width: 100%;
  }
  .org-table-card {
    border-radius: 10px; /* was 6px */
  }
  .org-table-search-bar {
    padding: 8px 4px 0 4px;
    border-top-left-radius: 10px; /* was 6px */
    border-top-right-radius: 10px; /* was 6px */
  }
  .org-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px; /* was 6px */
    border-bottom-right-radius: 10px; /* was 6px */
  }
  .org-table th,
  .org-table td {
    font-size: 11px;
    padding: 6px 2px;
  }
  .org-search {
    font-size: 11px;
    padding: 6px 10px 6px 28px;
    max-width: 180px;
    border-radius: 8px;
  }
}

/* --- Sort button and icon --- */
.org-th-sort-btn {
  background: none;
  border: none;
  padding: 0 0 0 4px;
  margin: 0;
  display: inline-flex;
  align-items: center;
  vertical-align: middle;
  box-shadow: none;
  outline: none;
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
  margin-right: 0;
  vertical-align: middle;
  display: inline-block;
  border: none;
  background: none;
  box-shadow: none;
  filter: none;
  opacity: 0.7;
  transition: opacity 0.15s;
}
.org-th-sort-btn:hover .org-th-sort,
.org-th-sort-btn:focus .org-th-sort {
  opacity: 1;
}
.org-th-content {
  display: inline-flex;
  align-items: center;
  gap: 2px;
  font-size: 14px;
  font-weight: 600;
  color: #888;
}
</style>
