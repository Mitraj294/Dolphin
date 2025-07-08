<template>
  <div class="assessments-card">
    <div class="assessments-header-row">
      <div></div>
      <div class="assessments-header-actions">
        <button class="sent-assessments-btn" @click="goToSendAssessment">Sent Assessments</button>
        <button class="create-assessment-btn">+ Create Assessments</button>
      </div>
    </div>
    <div class="assessments-table-container">
      <table class="assessments-table">
        <thead>
          <tr>
            <th>Assessments</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in assessments" :key="item.id">
            <td class="assessment-name-cell">
              <button class="assessment-link" @click="goToSummary(item)">{{ item.name }}</button>
            </td>
            <td>
              <button class="schedule-btn" @click="openScheduleModal(item)">
                <img src="@/assets/images/Schedule.svg" alt="Schedule" style="margin-right:6px; width:18px; height:18px; vertical-align:middle; display:inline-block;" />
                Schedule
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Schedule Modal -->
    <div v-if="showScheduleModal" class="modal-overlay">
      <div class="modal-content">
        <button class="modal-close" @click="closeScheduleModal">&times;</button>
        <h2 class="modal-title">
          Schedule {{ selectedAssessment && selectedAssessment.name }}
        </h2>
        <div class="modal-form-row">
          <input class="modal-input" placeholder="MM/DD/YYYY" type="text" />
          <input class="modal-input" placeholder="00:00" type="text" />
        </div>
        <div class="modal-form-row">
          <select class="modal-input">
            <option>Groups</option>
          </select>
          <select class="modal-input">
            <option>Members</option>
          </select>
        </div>
        <div class="modal-actions">
          <button class="modal-schedule-btn">Schedule</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrganizationAdminAssessmentsCard',
  data() {
    return {
      assessments: Array.from({ length: 10 }, (_, i) => ({ id: i + 1, name: `Assessment ${i + 1}` })),
      showScheduleModal: false,
      selectedAssessment: null
    }
  },
  methods: {
    openScheduleModal(item) {
      this.selectedAssessment = item;
      this.showScheduleModal = true;
    },
    closeScheduleModal() {
      this.showScheduleModal = false;
      this.selectedAssessment = null;
    },
    goToSummary(item) {
      this.$router.push({ name: 'AssessmentSummary', params: { assessmentId: item.id } });
    },
    goToSendAssessment() {
      // Open the SendAssessment page ("/assessments/send-assessment")
      this.$router.push({ name: 'SendAssessment' });
    }
  }
}
</script>

<style scoped>
.assessments-card {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #EBEBEB;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 64px auto 0 auto;
  padding: 0;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
}

.assessments-header-row {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 24px 46px 24px 24px;
  background: #fff;
  border-top-left-radius: 24px;
  border-top-right-radius: 24px;
  min-height: 64px;
  box-sizing: border-box;
  margin: 0; /* Remove any margin */
}

.assessments-header-actions {
  display: flex;
  gap: 16px;
}
.sent-assessments-btn {
  background: #f5f5f5;
  border: none;
  border-radius: 999px;
  padding: 10px 32px;
  font-size: 16px;
  color: #222;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.18s;
}
.sent-assessments-btn:hover {
  background: #e6f0fa;
}
.create-assessment-btn {
  background: #0074c2;
  color: #fff;
  border: none;
  border-radius: 999px;
  padding: 10px 32px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.18s;
}
.create-assessment-btn:hover {
  background: #005fa3;
}
.assessments-table-container {
  padding: 0 24px 24px 24px;
  margin-top: 0; /* Remove top margin */
  background: #fff;
  border-bottom-left-radius: 24px;
  border-bottom-right-radius: 24px;
  box-sizing: border-box;
}
.assessments-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  background: transparent;
}
.assessments-table th {
  background: #f5f5f5;
  color: #888;
  font-weight: 600;
  font-size: 16px;
  padding: 18px 12px;
  text-align: left;
  border-bottom: 1.5px solid #eee;
  border-top: none;
}
.assessments-table th:first-child {
  border-top-left-radius: 99px;
  border-bottom-left-radius: 99px;
}
.assessments-table th:last-child {
  border-top-right-radius: 99px;
  border-bottom-right-radius: 99px; 
}
.assessments-table td {
  background: #fff;
  font-size: 16px;
  padding: 22px 12px;
  border-bottom: 1px solid #f0f0f0;
}
.assessment-name-cell {
  text-align: left;
}
.schedule-btn {
  background: #f5f5f5;
  border: none;
  border-radius: 999px;
  padding: 8px 24px;
  font-size: 15px;
  color: #222;
  font-weight: 500;
  display: flex;
  align-items: center;
  cursor: pointer;
  transition: background 0.18s;
}
.schedule-btn:hover {
  background: #e6f0fa;
}
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.12);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}
.modal-content {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 4px 32px rgba(0,0,0,0.08);
  padding: 40px 40px 32px 40px;
  min-width: 420px;
  max-width: 520px;
  width: 100%;
  position: relative;
}
.modal-close {
  position: absolute;
  top: 18px;
  right: 22px;
  background: none;
  border: none;
  font-size: 2rem;
  color: #bbb;
  cursor: pointer;
}
.modal-title {
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 32px;
}
.modal-form-row {
  display: flex;
  gap: 18px;
  margin-bottom: 18px;
}
.modal-input {
  flex: 1;
  padding: 16px 18px;
  border: 1px solid #e0e0e0;
  border-radius: 7px;
  font-size: 16px;
  background: #fafafa;
}
.modal-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 18px;
}
.modal-schedule-btn {
  background: #0074c2;
  color: #fff;
  border: none;
  border-radius: 999px;
  padding: 12px 40px;
  font-size: 18px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.18s;
}
.modal-schedule-btn:hover {
  background: #005fa3;
}
.assessment-link {
  background: none;
  border: none;
  color: #0074c2;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  text-align: left;
  padding: 0;
  transition: color 0.18s;
}
.assessment-link:hover {
  color: #005fa3;
  text-decoration: underline;
}
@media (max-width: 1400px) {
  .assessments-card {
    border-radius: 14px;
    max-width: 100%;
    margin-top: 12px; /* Responsive top margin */
  }
  .assessments-header-row {
    padding: 8px 8px 0 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .assessments-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
  }
}
@media (max-width: 900px) {
  .assessments-card {
    border-radius: 10px;
    margin-top: 4px; /* Responsive top margin */
  }
  .assessments-header-row {
    padding: 8px 4px 0 4px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .assessments-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>
