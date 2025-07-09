<template>
  <MainLayout>
    <div class="page">
      <div class="page-content-wrapper">
        <div class="lead-detail-page">
          <div class="lead-detail-card-group">
            <button class="btn btn-primary edit-details-btn">
              Edit Details
            </button>
            <div class="lead-detail-card left">
              <div class="lead-detail-row">
                <span class="label">Main Contact</span
                ><span class="value">{{ lead.contact }}</span>
              </div>
              <div class="lead-detail-row">
                <span class="label">Admin Email</span
                ><span class="value">{{ lead.email }}</span>
              </div>
              <div class="lead-detail-row">
                <span class="label">Admin Phone #</span
                ><span class="value">{{ lead.phone }}</span>
              </div>
              <div class="lead-detail-row">
                <span class="label">Sales Person</span
                ><span class="value">John</span>
              </div>
              <div class="lead-detail-row">
                <span class="label">Source</span
                ><span class="value">{{ lead.source }}</span>
              </div>
              <div class="lead-detail-row">
                <span class="label">Status</span
                ><span class="value">{{ lead.status }}</span>
              </div>
            </div>
            <div class="lead-detail-card right">
              <div class="lead-detail-row">
                <span class="label">Organization Name</span
                ><span class="value">{{ lead.organization }}</span>
              </div>
              <div class="lead-detail-row">
                <span class="label">Organization Size</span
                ><span class="value">{{
                  lead.size === 'Large' ? '250+ Employees (Large)' : lead.size
                }}</span>
              </div>
              <div class="lead-detail-row">
                <span class="label">Contract Start</span
                ><span class="value bold">Jun 18, 2024</span>
              </div>
              <div class="lead-detail-row">
                <span class="label">Contract End</span
                ><span class="value bold">Jun 18, 2025</span>
              </div>
              <div class="lead-detail-row address-row">
                <span class="label">Address</span
                ><span class="value bold"
                  >153 Maggie Loop<br />Pottsville, Arkansas(AR), 72858</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
export default {
  name: 'LeadDetail',
  components: { MainLayout },
  props: {
    lead: {
      type: Object,
      default: () => ({
        contact: '',
        email: '',
        phone: '',
        source: '',
        status: '',
        organization: '',
        size: '',
      }),
    },
  },
};
</script>

<style scoped>
.page-content-wrapper {
  min-height: 0;
  padding-bottom: 0;
  margin-bottom: 0;
  background: none;
}
.lead-detail-page {
  padding: 32px;
  position: relative;
}
.lead-detail-card-group {
  display: flex;
  gap: 32px;
  background: #fff;
  border-radius: 24px;
  box-shadow: 0 1px 8px rgba(33, 150, 243, 0.08);
  padding: 60px 32px 32px 32px;
  justify-content: center;
  align-items: flex-start;
  max-width: 1100px;
  margin: 0 auto;
  position: relative;
}
.lead-detail-card {
  background: #f7f7f7;
  border-radius: 16px;
  padding: 32px 36px;
  min-width: 320px;
  flex: 1 1 0;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  justify-content: center;
  max-width: 420px;
}
.lead-detail-card.left {
  margin-right: 0;
}
.lead-detail-card.right {
  margin-left: 0;
}
.lead-detail-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 18px;
  font-size: 17px;
}
.lead-detail-row .label {
  color: #aaa;
  font-weight: 400;
  min-width: 140px;
  text-align: left;
}
.lead-detail-row .value {
  color: #222;
  font-weight: 600;
  text-align: right;
  word-break: break-word;
}
.lead-detail-row .value.bold {
  font-weight: 700;
  color: #222;
}
.address-row .value {
  white-space: pre-line;
  font-weight: 700;
}
.edit-details-btn {
  /* Only positioning styles remain. All button appearance comes from global .btn and .btn-primary classes. */
  position: absolute;
  top: 0;
  right: 0;
  z-index: 2;
}
@media (max-width: 900px) {
  .lead-detail-card-group {
    flex-direction: column;
    gap: 18px;
    padding: 18px 6px 12px 6px;
    min-width: 0;
  }
  .lead-detail-card {
    padding: 16px 10px;
    min-width: 0;
    max-width: 100vw;
  }
  .edit-details-btn {
    top: -12px;
    right: 0;
    /* Removed padding and font-size, use only global .btn classes for button appearance. */
  }
}
</style>
