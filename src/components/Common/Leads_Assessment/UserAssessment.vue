<template>
  <MainLayout>
    <div class="page">
      <div class="user-assessment-table-outer">
        <div class="user-assessment-table-card">
          <div class="user-assessment-table-header-bar">
            <button
              class="btn btn-primary"
              @click="showScheduleModal = true"
            >
              Schedule Assessment
            </button>
          </div>
          <div class="user-assessment-table-container">
            <table class="user-assessment-table">
              <TableHeader
                :columns="tableColumns"
                @sort="sortBy"
              />
              <tbody>
                <tr
                  v-for="row in paginatedRows"
                  :key="row.id"
                >
                  <td class="user-name-td">{{ row.name }}</td>
                  <td>{{ row.email }}</td>
                  <td>{{ row.status }}</td>
                  <td>
                    <button
                      class="btn-view"
                      @click="viewAssessment(row)"
                    >
                      <img
                        src="@/assets/images/Notes.svg"
                        alt="View"
                        class="btn-view-icon"
                        width="18"
                        height="18"
                      />
                      View
                    </button>
                  </td>
                </tr>
                <tr v-if="paginatedRows.length === 0">
                  <td
                    colspan="4"
                    class="no-data"
                  >
                    No assessments found.
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
          @goToPage="goToPage"
          @selectPageSize="selectPageSize"
          @togglePageDropdown="togglePageDropdown"
        />
        <!-- Schedule Modal -->
        <div
          v-if="showScheduleModal"
          class="modal-overlay"
        >
          <ModalCard
            :title="'Schedule Assessment 1'"
            :actionLabel="'Schedule'"
            @close="showScheduleModal = false"
            @submit="submitSchedule"
          >
            <div class="modal-form-row">
              <div class="modal-form-group">
                <input
                  class="modal-input"
                  placeholder="MM/DD/YYYY"
                  type="text"
                />
              </div>
              <div class="modal-form-group">
                <input
                  class="modal-input"
                  placeholder="00:00"
                  type="text"
                />
              </div>
            </div>
            <div class="modal-form-row">
              <div class="modal-form-group custom-dropdown">
                <span class="modal-icon"><i class="fas fa-users"></i></span>
                <div class="custom-dropdown-input">
                  <span style="color: #888">Groups</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
              </div>
              <div class="modal-form-group custom-dropdown">
                <span class="modal-icon"><i class="fas fa-user"></i></span>
                <div class="custom-dropdown-input">
                  <span style="color: #888">Members</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
              </div>
            </div>
          </ModalCard>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
import Pagination from '@/components/layout/Pagination.vue';
import TableHeader from '@/components/Common/Common_UI/TableHeader.vue';
import ModalCard from '@/components/Common/Common_UI/ModalCard.vue';
import { ref, computed } from 'vue';

export default {
  name: 'UserAssessment',
  components: { MainLayout, Pagination, TableHeader, ModalCard },
  setup() {
    // Example data
    const rows = [
      {
        id: 1,
        name: 'Emily Carter',
        email: 'emily@example.com',
        status: 'Submitted',
      },
      {
        id: 2,
        name: 'James Parker',
        email: 'james@example.com',
        status: 'Pending',
      },
      {
        id: 3,
        name: 'Sophia Mitchell',
        email: 'sophia@example.com',
        status: 'Submitted',
      },
      {
        id: 4,
        name: 'Mason Walker',
        email: 'mason@example.com',
        status: 'Submitted',
      },
      {
        id: 5,
        name: 'Olivia Bennett',
        email: 'olivia@example.com',
        status: 'Pending',
      },
      {
        id: 6,
        name: 'Liam Johnson',
        email: 'liam@example.com',
        status: 'Submitted',
      },
      {
        id: 1,
        name: 'Emily Carter',
        email: 'emily@example.com',
        status: 'Submitted',
      },
      {
        id: 2,
        name: 'James Parker',
        email: 'james@example.com',
        status: 'Pending',
      },
      {
        id: 3,
        name: 'Sophia Mitchell',
        email: 'sophia@example.com',
        status: 'Submitted',
      },
      {
        id: 4,
        name: 'Mason Walker',
        email: 'mason@example.com',
        status: 'Submitted',
      },
      {
        id: 5,
        name: 'Olivia Bennett',
        email: 'olivia@example.com',
        status: 'Pending',
      },
      {
        id: 6,
        name: 'Liam Johnson',
        email: 'liam@example.com',
        status: 'Submitted',
      },
    ];

    const tableColumns = [
      { label: 'Name', key: 'name' },
      { label: 'Email', key: 'email' },
      { label: 'Status', key: 'status' },
      { label: 'Actions', key: 'actions' },
    ];

    const pageSizes = [10, 25, 100];
    const pageSize = ref(10);
    const currentPage = ref(1);
    const showPageDropdown = ref(false);
    const showScheduleModal = ref(false);

    const totalPages = computed(() =>
      Math.max(1, Math.ceil(rows.length / pageSize.value))
    );

    const paginatedRows = computed(() => {
      const start = (currentPage.value - 1) * pageSize.value;
      return rows.slice(start, start + pageSize.value);
    });

    function goToPage(page) {
      if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
      }
    }
    function selectPageSize(size) {
      pageSize.value = size;
      currentPage.value = 1;
      showPageDropdown.value = false;
    }
    function togglePageDropdown() {
      showPageDropdown.value = !showPageDropdown.value;
    }
    function sortBy() {
      // No-op: implement sorting if needed
    }
    function viewAssessment(row) {
      // Implement view logic or modal here
      alert(`Viewing assessment for ${row.name}`);
    }
    function submitSchedule() {
      showScheduleModal.value = false;
      // Add schedule logic here
    }

    return {
      tableColumns,
      paginatedRows,
      pageSizes,
      pageSize,
      currentPage,
      totalPages,
      showPageDropdown,
      showScheduleModal,
      goToPage,
      selectPageSize,
      togglePageDropdown,
      sortBy,
      viewAssessment,
      submitSchedule,
    };
  },
};
</script>

<style scoped>
.user-assessment-table-outer {
  width: 100%;
  max-width: 1400px;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
}
.user-assessment-table-card {
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
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
}
.user-assessment-table-header-bar {
  padding: 24px 24px 24px 24px;
  background: #fff;
  border-top-left-radius: 24px;
  border-top-right-radius: 24px;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-end;
}
.user-assessment-table-container {
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
.user-assessment-table-container::-webkit-scrollbar {
  display: none;
}
.user-assessment-table {
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
.user-assessment-table th,
.user-assessment-table td {
  padding: 12px 8px;
  text-align: left;
  font-size: 14px;
  border-bottom: 1px solid #f0f0f0;
  background: #fff;
}
.user-assessment-table th {
  background: #f8f8f8;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: 100px;
  border-bottom: 1.5px solid #ebebeb;
}
.user-assessment-table th.rounded-th-left {
  border-top-left-radius: 24px;
  border-bottom-left-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
  padding-left: 20px !important;
}
.user-name-td {
  padding-left: 20px !important;
}
.user-assessment-table th.rounded-th-right {
  border-top-right-radius: 24px;
  border-bottom-right-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
}
.user-assessment-table td {
  color: #222;
  background: #fff;
}
.no-data {
  text-align: center;
  color: #888;
  font-size: 16px;
  padding: 32px 0;
}
.btn-view {
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
.btn-view:hover {
  border: 1.5px solid #0074c2;
}
.btn-view-icon {
  width: 16px;
  height: 16px;
  display: inline-block;
  vertical-align: middle;
  margin-right: 4px;
}

.assessment-table th:first-child {
  padding-left: 20px !important;
}
@media (max-width: 1400px) {
  .user-assessment-table-outer {
    margin: 12px;
    max-width: 100%;
  }
  .user-assessment-table-card {
    border-radius: 14px;
    max-width: 100%;
  }
  .user-assessment-table-header-bar {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .user-assessment-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
  }
  .user-assessment-table th,
  .user-assessment-table td {
    font-size: 12px;
    padding: 8px 4px;
  }
  .user-assessment-table th.rounded-th-left {
    border-top-left-radius: 14px;
    border-bottom-left-radius: 14px;
    padding-left: 20px !important;
  }
  .user-name-td {
    padding-left: 16px !important;
  }
  .user-assessment-table th.rounded-th-right {
    border-top-right-radius: 14px;
    border-bottom-right-radius: 14px;
  }
}
@media (max-width: 900px) {
  .user-assessment-table-outer {
    margin: 4px;
    max-width: 100%;
  }
  .user-assessment-table-card {
    border-radius: 10px;
  }
  .user-assessment-table-header-bar {
    padding: 12px 8px 12px 8px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .user-assessment-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  .user-assessment-table th,
  .user-assessment-table td {
    font-size: 11px;
    padding: 6px 2px;
  }
  .user-assessment-table th.rounded-th-left {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    padding-left: 8px !important;
  }
  .user-name-td {
    padding-left: 8px !important;
  }
  .user-assessment-table th.rounded-th-right {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
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
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal-card {
  background: #fff;
  border-radius: 16px;
  padding: 24px;
  max-width: 600px;
  width: 100%;
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
  position: relative;
}
.modal-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}
.modal-card-title {
  font-size: 18px;
  font-weight: 600;
  color: #333;
}
.modal-card-close {
  cursor: pointer;
  font-size: 16px;
  color: #888;
  transition: color 0.2s;
}
.modal-card-close:hover {
  color: #333;
}
.modal-card-body {
  max-height: 400px;
  overflow-y: auto;
}
.modal-form-row {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  margin-bottom: 16px;
}
.modal-form-group {
  flex: 1;
  min-width: 120px;
}
.modal-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  color: #333;
  transition: border 0.2s;
}
.modal-input:focus {
  border-color: #0074c2;
  outline: none;
}
.custom-dropdown {
  position: relative;
  display: flex;
  align-items: center;
  cursor: pointer;
}
.custom-dropdown-input {
  flex: 1;
  padding: 12px 16px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  color: #333;
  display: flex;
  align-items: center;
  gap: 8px;
}
.custom-dropdown-input .modal-icon {
  color: #0074c2;
  font-size: 16px;
}
.custom-dropdown-input i {
  color: #888;
  font-size: 14px;
}
@media (max-width: 600px) {
  .modal-card {
    padding: 16px;
  }
  .modal-card-header {
    flex-direction: column;
    align-items: flex-start;
  }
  .modal-card-title {
    font-size: 16px;
  }
  .modal-card-close {
    position: absolute;
    top: 16px;
    right: 16px;
  }
}
</style>
