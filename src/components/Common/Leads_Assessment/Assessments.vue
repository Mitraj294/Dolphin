<template>
  <MainLayout>
    <div class="page">
      <div class="assessments-table-outer">
        <OrganizationAdminAssessmentsCard v-if="isOrganizationAdmin" />
        <UserAssessment v-else-if="isUser" />
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
import OrganizationAdminAssessmentsCard from './OrganizationAdminAssessmentsCard.vue';
import UserAssessment from './UserAssessment.vue';

export default {
  name: 'Assessments',
  components: { MainLayout, OrganizationAdminAssessmentsCard, UserAssessment },
  computed: {
    isOrganizationAdmin() {
      const role = localStorage.getItem('role') || 'user';
      return role === 'organizationadmin';
    },
    isUser() {
      const role = localStorage.getItem('role') || 'user';
      return role === 'user';
    },
  },
};
</script>

<style scoped>
.assessments-table-outer {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  margin: 0 auto 64px auto; /* Only bottom margin */
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
  background: none !important;
  padding: 0 !important;
}

/* Responsive: match other pages */
@media (max-width: 1400px) {
  .assessments-table-outer {
    margin: 0 12px 12px 12px;
    max-width: 100%;
  }
}
@media (max-width: 900px) {
  .assessments-table-outer {
    margin: 0 4px 4px 4px;
    max-width: 100%;
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

/* Modal overlay and card (match MyOrganization modal) */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.13);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-card {
  background: #fff;
  border-radius: 22px;
  box-shadow: 0 4px 32px 0 rgba(33, 150, 243, 0.1);
  padding: 40px 48px 32px 48px;
  min-width: 400px;
  max-width: 700px;
  width: 100%;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.modal-close {
  position: absolute;
  top: 24px;
  right: 32px;
  background: none;
  border: none;
  font-size: 32px;
  color: #888;
  cursor: pointer;
  z-index: 10;
}
.modal-title {
  font-size: 26px;
  font-weight: 600;
  margin-bottom: 32px;
  color: #222;
}
.modal-form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 18px;
}
.modal-form-row {
  display: flex;
  gap: 24px;
  width: 100%;
}
.modal-form-group {
  flex: 1 1 0;
  min-width: 0;
  background: #f6f6f6;
  border-radius: 9px;
  padding: 0 16px;
  height: 48px;
  display: flex;
  align-items: center;
  margin-bottom: 0;
  box-sizing: border-box;
}
.modal-form-group input,
.modal-form-group select {
  border: none;
  background: transparent;
  outline: none;
  font-size: 16px;
  color: #222;
  width: 100%;
  height: 44px;
  padding: 0;
  font-family: inherit;
}
.modal-form-actions {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  margin-top: 18px;
}
.modal-save-btn {
  border-radius: 22px;
  background: #0164a5;
  color: #fff;
  font-size: 17px;
  font-weight: 500;
  padding: 10px 32px;
  border: none;
  cursor: pointer;
  transition: background 0.2s;
}
.modal-save-btn:hover {
  background: #005fa3;
}

/* Responsive modal */
@media (max-width: 900px) {
  .modal-card {
    min-width: 0;
    max-width: 98vw;
    padding: 18px 8px 16px 8px;
    border-radius: 14px;
  }
  .modal-form-row {
    flex-direction: column;
    gap: 12px;
  }
  .modal-form-group {
    height: 42px;
    border-radius: 8px;
    padding: 0 8px;
  }
}
@media (max-width: 600px) {
  .modal-card {
    min-width: 0;
    max-width: 99vw;
    padding: 8px 2vw 8px 2vw;
    border-radius: 10px;
  }
}
</style>
