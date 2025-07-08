<template>
  <MainLayout>
    <div class="org-edit-outer">
      <div class="org-edit-card">
        <h2 class="org-edit-title">Edit Details</h2>
        <form class="org-edit-form" @submit.prevent="updateDetails">
          <!-- First row: Org Name, Size, Source -->
          <div class="org-edit-grid org-edit-grid-3">
            <div class="org-edit-field">
              <label>Organization Name</label>
              <input type="text" v-model="form.orgName" />
            </div>
            <div class="org-edit-field">
              <label>Organization Size</label>
              <select v-model="form.orgSize">
                <option>250+ Employees (Large)</option>
                <option>100-249 Employees (Medium)</option>
                <option>1-99 Employees (Small)</option>
              </select>
            </div>
            <div class="org-edit-field">
              <label>Source</label>
              <input type="text" v-model="form.source" />
            </div>
          </div>
          <!-- Address section: first row -->
          <div class="org-edit-grid org-edit-grid-3">
            <div class="org-edit-field">
              <label>Address</label>
              <input type="text" v-model="form.address1" />
            </div>
            <div class="org-edit-field">
              <label>&nbsp;</label>
              <input type="text" v-model="form.address2" />
            </div>
            <div class="org-edit-field">
              <label>&nbsp;</label>
              <input type="text" v-model="form.city" />
            </div>
          </div>
          <!-- Address section: second row -->
          <div class="org-edit-grid org-edit-grid-3">
            <div class="org-edit-field">
              <label>&nbsp;</label>
              <select v-model="form.state">
                <option>Arkansas(AR)</option>
                <option>California(CA)</option>
                <option>New York(NY)</option>
              </select>
            </div>
            <div class="org-edit-field">
              <label>&nbsp;</label>
              <input type="text" v-model="form.zip" />
            </div>
            <div class="org-edit-field">
              <label>&nbsp;</label>
              <select v-model="form.country">
                <option>United States</option>
                <option>Canada</option>
              </select>
            </div>
          </div>
          <!-- Contract dates row: use 3 columns, last is empty -->
          <div class="org-edit-grid org-edit-grid-3">
            <div class="org-edit-field">
              <label>Contract Start</label>
              <input type="text" v-model="form.contractStart" disabled />
            </div>
            <div class="org-edit-field">
              <label>Contract End</label>
              <input type="text" v-model="form.contractEnd" disabled />
            </div>
            <div class="org-edit-field"><label>&nbsp;</label></div>
          </div>
          <!-- Divider line -->
          <div class="org-edit-divider"></div>
          <!-- Admin/contact section: 2 rows of 3 fields -->
          <div class="org-edit-grid org-edit-grid-3">
            <div class="org-edit-field">
              <label>Main Contact</label>
              <input type="text" v-model="form.mainContact" />
            </div>
            <div class="org-edit-field">
              <label>Admin Email</label>
              <input type="email" v-model="form.adminEmail" disabled />
            </div>
            <div class="org-edit-field">
              <label>Admin Phone#</label>
              <input type="text" v-model="form.adminPhone" disabled />
            </div>
            <div class="org-edit-field">
              <label>Sales Person</label>
              <input type="text" v-model="form.salesPerson" />
            </div>
            <div class="org-edit-field">
              <label>Last Contacted</label>
              <input type="text" v-model="form.lastContacted" disabled />
            </div>
            <div class="org-edit-field">
              <label>Certified Staff</label>
              <input type="number" v-model="form.certifiedStaff" />
            </div>
          </div>
          <div class="org-edit-actions">
            <button type="button" class="org-edit-cancel" @click="cancelEdit">Cancel</button>
            <button type="submit" class="org-edit-update">Update Details</button>
          </div>
        </form>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';

export default {
  name: 'OrganizationEdit',
  components: { MainLayout },
  data() {
    return {
      form: {
        orgName: this.$route.params.orgName || 'Flexi-Finders',
        orgSize: '250+ Employees (Large)',
        source: 'Google',
        address1: '153',
        address2: 'Maggie Loop',
        city: 'Pottsville',
        state: 'Arkansas(AR)',
        zip: '72858',
        country: 'United States',
        contractStart: 'Jun 18, 2024',
        contractEnd: 'Jun 18, 2025',
        mainContact: 'Aaliyah Moss',
        adminEmail: 'aaliyah@dolphin.org',
        adminPhone: '313-586-7462',
        salesPerson: 'John',
        lastContacted: 'Dec 15, 2024',
        certifiedStaff: 2
      }
    }
  },
  methods: {
    cancelEdit() {
      this.$router.push(`/organizations/${this.form.orgName}`);
    },
    updateDetails() {
      this.$router.push(`/organizations/${this.form.orgName}`);
    }
  }
}
</script>

<style scoped>
/* --- Layout and spacing to match OrganizationTable/Leads/Notifications --- */
.org-edit-outer {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
  background: none !important;
  padding: 0;
}

.org-edit-card {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #EBEBEB;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 0 auto;
  box-sizing: border-box;
  padding: 32px 32px 24px 32px;
  display: flex;
  flex-direction: column;
  gap: 32px;
  position: relative;
}

.org-edit-title {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 24px;
  text-align: left;
}

.org-edit-form {
  width: 100%;
}

.org-edit-grid {
  display: grid;
  gap: 18px 24px;
  margin-bottom: 0;
}
.org-edit-grid-3 {
  grid-template-columns: repeat(3, 1fr);
  margin-bottom: 18px;
}
.org-edit-grid-2 {
  grid-template-columns: repeat(2, 1fr);
  margin-bottom: 18px;
}

.org-edit-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.org-edit-field label {
  color: #888;
  font-size: 15px;
  font-weight: 400;
  text-align: left;
}

.org-edit-field input,
.org-edit-field select {
  background: #fff;
  border: 1.5px solid #e0e0e0;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 15px;
  color: #222;
  outline: none;
  transition: border 0.2s;
}

.org-edit-field input:disabled {
  color: #bdbdbd;
  background: #f5f5f5;
}

.org-edit-field select {
  appearance: none;
  background: #fff url('data:image/svg+xml;utf8,<svg fill="%23888" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7.293 7.293a1 1 0 011.414 0L10 8.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z"/></svg>') no-repeat right 10px center/18px 18px;
}

.org-edit-divider {
  width: 100%;
  border-bottom: 1.5px solid #E0E0E0;
  margin: 24px 0 24px 0;
}

.org-edit-actions {
  display: flex;
  justify-content: flex-end;
  gap: 18px;
}

.org-edit-cancel {
  background: #f5f5f5;
  color: #888;
  border: none;
  border-radius: 24px;
  padding: 10px 32px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}
.org-edit-cancel:hover {
  background: #e0e0e0;
}
.org-edit-update {
  background: #0074c2;
  color: #fff;
  border: none;
  border-radius: 24px;
  padding: 10px 32px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}
.org-edit-update:hover {
  background: #005fa3;
}

/* Responsive styles to match other pages */
@media (max-width: 1400px) {
  .org-edit-outer {
    margin: 12px;
    max-width: 100%;
  }
  .org-edit-card {
    max-width: 100%;
    border-radius: 14px;
    padding: 18px 8px 12px 8px;
  }
  .org-edit-grid {
    gap: 12px 8px;
  }
  .org-edit-grid-3,
  .org-edit-grid-2 {
    margin-bottom: 12px;
  }
  .org-edit-divider {
    margin: 16px 0 16px 0;
  }
}

@media (max-width: 900px) {
  .org-edit-outer {
    margin: 4px;
    max-width: 100%;
  }
  .org-edit-card {
    padding: 8px 2vw 8px 2vw;
    border-radius: 10px;
  }
  .org-edit-grid-3,
  .org-edit-grid-2 {
    grid-template-columns: 1fr;
    margin-bottom: 12px;
  }
  .org-edit-divider {
    margin: 12px 0 12px 0;
  }
}
</style>
