<template>
  <MainLayout>
    <div class="page">
      <div class="notifications-table-outer">
        <div class="notifications-table-card">
          <div class="notifications-table-header-bar">
            <button
              class="btn btn-primary"
              @click="showSendModal = true"
            >
              <img
                src="@/assets/images/SendNotification.svg"
                alt="Send"
                class="notifications-add-btn-icon"
              />
              Send Notification
            </button>
          </div>
          <div class="notifications-table-container">
            <table class="notifications-table">
              <TableHeader
                :columns="[
                  { label: 'Notification Title', key: 'title' },
                  { label: 'Date & Time', key: 'date' },
                  { label: 'Action', key: 'action' },
                ]"
                @sort="sortBy"
              />
              <tbody>
                <tr
                  v-for="(item, idx) in paginatedNotifications"
                  :key="idx"
                >
                  <td>{{ item.title }}</td>
                  <td>{{ item.date }}</td>
                  <td>
                    <button class="btn-view">
                      <img
                        src="@/assets/images/Detail.svg"
                        alt="View"
                        class="btn-view-icon"
                      />
                      View Detail
                    </button>
                  </td>
                </tr>
                <tr v-if="paginatedNotifications.length === 0">
                  <td
                    colspan="3"
                    class="no-data"
                  >
                    No notifications found.
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
        <div
          v-if="showSendModal"
          class="send-notification-modal-overlay"
          @click.self="showSendModal = false"
        >
          <div class="send-notification-modal">
            <button
              class="modal-close-btn"
              @click="showSendModal = false"
            >
              &times;
            </button>
            <div class="modal-title">Send Notifications</div>
            <div class="modal-desc">
              Lorem Ipsum is simply dummy text of the printing and typesetting
              industry.
            </div>
            <textarea
              class="modal-textarea"
              placeholder="Type here"
            ></textarea>
            <div class="modal-row">
              <div class="modal-field">
                <label>Select Organizations</label>
                <select>
                  <option>Select</option>
                </select>
              </div>
              <div class="modal-field">
                <label>Select Admin</label>
                <select>
                  <option>Select</option>
                </select>
              </div>
            </div>
            <div class="modal-row">
              <div class="modal-field">
                <label>Select Group</label>
                <select>
                  <option>Select</option>
                </select>
              </div>
              <div class="modal-field modal-field-schedule">
                <label>Schedule</label>
                <div class="modal-schedule-fields">
                  <input
                    type="text"
                    placeholder="MM/DD/YYYY"
                    class="modal-date-input"
                  />
                  <input
                    type="time"
                    class="modal-time-input"
                  />
                </div>
              </div>
            </div>
            <button class="btn btn-primary">Send Notification</button>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../../layout/MainLayout.vue';
import Pagination from '../../layout/Pagination.vue';
import TableHeader from '@/components/Common/Common_UI/TableHeader.vue';
export default {
  name: 'Notifications',
  components: { MainLayout, Pagination, TableHeader },
  data() {
    return {
      showPageDropdown: false,
      showSendModal: false,
      pageSize: 10,
      currentPage: 1,
      sortKey: '',
      sortAsc: true,
      notifications: [
        { title: 'Lorem Ipsum is simply', date: 'Jan 22, 2025 at 02:00 PM' },
        {
          title: 'Dummy text of the printing',
          date: 'Jan 22, 2025 at 02:00 PM',
        },
        {
          title: 'And typesetting industry.',
          date: 'Jan 12, 2025 at 01:15 PM',
        },
        {
          title: 'Lorem Ipsum text has been',
          date: 'Jan 10, 2025 at 01:00 PM',
        },
        { title: 'The industry standard', date: 'Jan 6, 2025 at 12:00 PM' },
        { title: 'Dummy text ever since', date: 'Dec 24, 2024 at 10:00 AM' },
        { title: 'When an unknown printer', date: 'Dec 24, 2024 at 10:00 AM' },
        { title: 'took a galley of type', date: 'Dec 24, 2024 at 10:00 AM' },
        { title: 'And scrambled it to make', date: 'Dec 24, 2024 at 10:00 AM' },
        { title: 'And scrambled it to make', date: 'Dec 15, 2024 at 4:40 PM' },
      ],
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.notifications.length / this.pageSize) || 1;
    },
    paginatedNotifications() {
      let notifications = [...this.notifications];
      if (this.sortKey) {
        notifications.sort((a, b) => {
          const aVal = a[this.sortKey] || '';
          const bVal = b[this.sortKey] || '';
          if (aVal < bVal) return this.sortAsc ? -1 : 1;
          if (aVal > bVal) return this.sortAsc ? 1 : -1;
          return 0;
        });
      }
      const start = (this.currentPage - 1) * this.pageSize;
      return notifications.slice(start, start + this.pageSize);
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
/* --- Layout and spacing to match Leads/OrganizationTable --- */
.notifications-table-outer {
  width: 100%;
  max-width: 1400px;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
  padding: 0 16px;
}
.notifications-table-card {
  width: 100%;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  overflow: visible;
  margin: 0 auto;
  box-sizing: border-box;
}
.notifications-table-header-bar {
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
  .notifications-table-header-bar {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
}
@media (max-width: 900px) {
  .notifications-table-header-bar {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
}
.notifications-table-container {
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
.notifications-table-container::-webkit-scrollbar {
  display: none;
}
.notifications-table {
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
.notifications-table th,
.notifications-table td {
  padding: 18px 12px;
  text-align: left;
  font-size: 15px;
  border-bottom: 1.5px solid #f0f0f0;
  background: #fff;
  font-family: 'Inter', Arial, sans-serif;
  font-weight: 400;
  line-height: 22px;
}
.notifications-table th {
  background: #f8f8f8;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: 100px;
  border-bottom: 1.5px solid #ebebeb;
}
.notifications-table th.rounded-th-left {
  border-top-left-radius: 24px;
  border-bottom-left-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
  padding-left: 20px !important;
}
.notifications-table td:first-child {
  padding-left: 20px !important;
}
.notifications-table th.rounded-th-right {
  border-top-right-radius: 24px;
  border-bottom-right-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
}
.notifications-table td {
  color: #222;
  background: #fff;
}
.no-data {
  text-align: center;
  color: #888;
  font-size: 16px;
  padding: 32px 0;
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

.notifications-add-btn-icon {
  width: 18px;
  height: 18px;
  margin-right: 6px;
  display: inline-block;
  vertical-align: middle;
}
</style>
