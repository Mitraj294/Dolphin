<template>
  <MainLayout>
    <div class="page">
      <div class="notifications-table-outer">
        <div class="notifications-table-card">
          <div class="notifications-table-header">
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
          <div class="notifications-table-header-spacer"></div>
          <div class="notifications-table-container">
            <table class="notifications-table">
              <thead>
                <tr>
                  <th class="rounded-th-left">Notification Title</th>
                  <th>Date &amp; Time</th>
                  <th class="rounded-th-right">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, idx) in paginatedNotifications"
                  :key="idx"
                >
                  <td>{{ item.title }}</td>
                  <td>{{ item.date }}</td>
                  <td>
                    <button class="view-detail-btn">
                      <img
                        src="@/assets/images/Detail.svg"
                        alt="Detail"
                        class="view-detail-icon"
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
            <button class="btn btn-primary modal-send-btn">
              Send Notification
            </button>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../../layout/MainLayout.vue';
import Pagination from '../../layout/Pagination.vue';

export default {
  name: 'Notifications',
  components: { MainLayout, Pagination },
  data() {
    return {
      showPageDropdown: false,
      showSendModal: false,
      pageSize: 10,
      currentPage: 1,
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
      const start = (this.currentPage - 1) * this.pageSize;
      return this.notifications.slice(start, start + this.pageSize);
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
  padding: 0 16px; /* Add horizontal padding to match OrganizationTable layout */
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
.notifications-table-header {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 24px 46px 0 24px;
  background: #fff;
  border-top-left-radius: 24px;
  border-top-right-radius: 24px;
  min-height: 64px;
  box-sizing: border-box;
}
.notifications-table-header-spacer {
  height: 18px;
  width: 100%;
  background: transparent;
  display: block;
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
  padding-left: 32px !important;
}
.notifications-table td:first-child {
  padding-left: 32px !important;
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
.view-detail-btn {
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
.view-detail-btn:hover {
  border: 1.5px solid #0074c2;
}
.view-detail-icon {
  width: 16px;
  height: 16px;
  display: inline-block;
  vertical-align: middle;
  margin-right: 4px;
}
.no-data {
  text-align: center;
  color: #888;
  font-size: 16px;
  padding: 32px 0;
}
.notifications-add-btn {
  border-radius: 29.01px;
  background: #0164a5;
  color: #fff;
  font-family: 'Helvetica Neue LT Std', Helvetica, Arial, sans-serif;
  font-weight: 500;
  font-size: 15px;
  padding: 8px 24px 8px 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-right: 0;
  margin-top: 0;
  box-shadow: none;
  border: none;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
  white-space: nowrap;
  min-width: 0;
  max-width: none;
  overflow: visible;
}
.notifications-add-btn:hover {
  background: #005fa3;
  color: #fff;
}
.notifications-add-btn-icon {
  width: 18px;
  height: 18px;
  margin-right: 6px;
  display: inline-block;
  vertical-align: middle;
}
/* Responsive styles to match base pages */
@media (max-width: 1400px) {
  .notifications-table-outer {
    margin: 12px;
    max-width: 100%;
    padding: 0 4px; /* Match responsive horizontal padding */
  }
  .notifications-table-card {
    border-radius: 14px;
  }
  .notifications-table-header {
    padding: 8px 8px 0 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .notifications-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
  }
  .notifications-table th,
  .notifications-table td {
    font-size: 12px;
    padding: 8px 4px;
  }
  .notifications-table th.rounded-th-left {
    border-top-left-radius: 14px;
    border-bottom-left-radius: 14px;
    padding-left: 18px !important;
  }
  .notifications-table td:first-child {
    padding-left: 18px !important;
  }
  .notifications-table th.rounded-th-right {
    border-top-right-radius: 14px;
    border-bottom-right-radius: 14px;
  }
}
@media (max-width: 900px) {
  .notifications-table-outer {
    margin: 4px;
    max-width: 100%;
    padding: 0 2px; /* Even smaller horizontal padding on small screens */
  }
  .notifications-table-card {
    border-radius: 10px;
  }
  .notifications-table-header {
    padding: 8px 4px 0 4px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .notifications-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  .notifications-table th,
  .notifications-table td {
    font-size: 11px;
    padding: 6px 2px;
  }
  .notifications-table th.rounded-th-left {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    padding-left: 10px !important;
  }
  .notifications-table td:first-child {
    padding-left: 10px !important;
  }
  .notifications-table th.rounded-th-right {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
/* --- Modal Styles (unchanged, but keep for completeness) --- */
.send-notification-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.12);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}
.send-notification-modal {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 8px 32px 0 rgba(33, 150, 243, 0.1);
  padding: 32px 36px 28px 36px;
  min-width: 340px;
  max-width: 540px;
  width: 100%;
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 18px;
}
.modal-close-btn {
  position: absolute;
  top: 18px;
  right: 18px;
  background: none;
  border: none;
  font-size: 2rem;
  color: #888;
  cursor: pointer;
  z-index: 1;
}
.modal-title {
  font-size: 1.35rem;
  font-weight: 600;
  margin-bottom: 0;
  color: #222;
}
.modal-desc {
  font-size: 1rem;
  color: #444;
  margin-bottom: 0;
}
.modal-textarea {
  width: 100%;
  min-height: 80px;
  border-radius: 8px;
  border: 1.5px solid #e0e0e0;
  padding: 12px;
  font-size: 1rem;
  resize: vertical;
  margin-bottom: 0;
}
.modal-row {
  display: flex;
  gap: 18px;
  margin-bottom: 0;
}
.modal-field {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.modal-field label {
  font-size: 0.98rem;
  color: #222;
  margin-bottom: 2px;
}
.modal-field select,
.modal-date-input,
.modal-time-input {
  width: 100%;
  border-radius: 8px;
  border: 1.5px solid #e0e0e0;
  padding: 8px 12px;
  font-size: 1rem;
  background: #fff;
  color: #222;
}
.modal-field-schedule .modal-schedule-fields {
  display: flex;
  gap: 8px;
}
.modal-send-btn {
  margin-top: 10px;
  align-self: flex-end;
  background: #0164a5;
  color: #fff;
  border: none;
  border-radius: 999px;
  padding: 10px 32px;
  font-size: 1.08rem;
  font-weight: 500;
  cursor: pointer;
  box-shadow: none;
  transition: background 0.18s;
}
.modal-send-btn:hover {
  background: #005b8e;
}
@media (max-width: 700px) {
  .send-notification-modal {
    padding: 18px 8px 16px 8px;
    min-width: 0;
    max-width: 98vw;
    border-radius: 12px;
  }
  .modal-row {
    flex-direction: column;
    gap: 8px;
  }
  .modal-send-btn {
    width: 100%;
    align-self: stretch;
    padding: 10px 0;
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
