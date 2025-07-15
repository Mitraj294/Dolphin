<template>
  <MainLayout>
    <div class="profile-outer">
      <div class="profile-card">
        <div class="profile-header">
          <div class="profile-title">
            <i class="fas fa-user-circle profile-avatar"></i>
            <span>Profile</span>
          </div>
          <div>
            <button
              class="btn btn-primary"
              @click="editAccount"
            >
              <i class="fas fa-pen-to-square"></i>
              Edit
            </button>
          </div>
        </div>
        <div class="profile-info-table">
          <div class="profile-info-row">
            <div class="profile-label">Name</div>
            <div class="profile-value">{{ user.name }}</div>
          </div>
          <div class="profile-info-row">
            <div class="profile-label">Email</div>
            <div class="profile-value">{{ user.email }}</div>
          </div>
          <div class="profile-info-row">
            <div class="profile-label">Role</div>
            <div class="profile-value">{{ user.role }}</div>
          </div>
          <div class="profile-info-row">
            <div class="profile-label">Country</div>
            <div class="profile-value">{{ user.country }}</div>
          </div>
          <div class="profile-info-row">
            <div class="profile-label">Phone</div>
            <div class="profile-value">{{ user.phone }}</div>
          </div>
          <!-- Removed company row -->
        </div>
        <div class="profile-actions">
          <button
            class="btn btn-danger"
            @click="deleteAccount"
          >
            <i class="fas fa-trash"></i>
            Delete Account
          </button>
        </div>
      </div>
      <div class="profile-card">
        <div class="profile-section-title">Change Password</div>
        <form
          class="profile-password-form"
          @submit.prevent="changePassword"
        >
          <div class="profile-form-row">
            <label class="profile-form-label">Current Password*</label>
            <div class="profile-input-wrapper">
              <input
                :type="showCurrentPassword ? 'text' : 'password'"
                v-model="currentPassword"
                required
                placeholder="Enter current password"
              />
              <span
                class="profile-eye-icon"
                @click="showCurrentPassword = !showCurrentPassword"
              >
                <i
                  :class="
                    showCurrentPassword ? 'fas fa-eye-slash' : 'fas fa-eye'
                  "
                ></i>
              </span>
            </div>
          </div>
          <div class="profile-form-row">
            <label class="profile-form-label">New Password*</label>
            <div class="profile-input-wrapper">
              <input
                :type="showNewPassword ? 'text' : 'password'"
                v-model="newPassword"
                required
                placeholder="Enter new password"
              />
              <span
                class="profile-eye-icon"
                @click="showNewPassword = !showNewPassword"
              >
                <i
                  :class="showNewPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                ></i>
              </span>
            </div>
          </div>
          <div class="profile-form-row">
            <label class="profile-form-label">Confirm New Password*</label>
            <div class="profile-input-wrapper">
              <input
                :type="showConfirmPassword ? 'text' : 'password'"
                v-model="confirmPassword"
                required
                placeholder="Confirm new password"
              />
              <span
                class="profile-eye-icon"
                @click="showConfirmPassword = !showConfirmPassword"
              >
                <i
                  :class="
                    showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'
                  "
                ></i>
              </span>
            </div>
          </div>
          <div class="profile-save-btn-row">
            <button
              type="submit"
              class="btn btn-primary"
            >
              <i class="fas fa-key"></i>
              Change Password
            </button>
          </div>
        </form>
        <div
          v-if="message"
          class="profile-message"
        >
          {{ message }}
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
export default {
  name: 'Profile',
  components: { MainLayout },
  data() {
    return {
      user: {
        name: localStorage.getItem('name') || 'User',
        email: localStorage.getItem('email') || 'user@example.com',
        role: localStorage.getItem('role') || 'user',
        country: localStorage.getItem('country') || 'India',
        phone: localStorage.getItem('phone') || '+919999999999',
        // company removed
      },
      currentPassword: '',
      newPassword: '',
      confirmPassword: '',
      message: '',
      showCurrentPassword: false,
      showNewPassword: false,
      showConfirmPassword: false,
    };
  },
  methods: {
    changePassword() {
      if (this.newPassword !== this.confirmPassword) {
        this.message = 'New passwords do not match.';
        return;
      }
      this.message = 'Password changed successfully!';
      this.currentPassword = '';
      this.newPassword = '';
      this.confirmPassword = '';
    },
  },
};
</script>

<style scoped>
.profile-outer {
  max-width: 900px;
  margin: 48px auto;
  display: flex;
  flex-direction: column;
  gap: 32px;
}
.profile-card {
  background: #fff;
  border-radius: 16px;
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.06);
  padding: 0 0 24px 0;
  margin-bottom: 0;
  display: flex;
  flex-direction: column;
  gap: 0;
}
.profile-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 28px 32px 0 32px;
}
.profile-title {
  display: flex;
  align-items: center;
  gap: 14px;
  font-size: 1.5rem;
  font-weight: 600;
  color: #0074c2;
}
.profile-avatar {
  font-size: 2.2rem;
  color: #0074c2;
}
.profile-info-table {
  padding: 18px 32px 0 32px;
  display: flex;
  flex-direction: column;
  gap: 0;
}
.profile-info-row {
  display: flex;
  border-bottom: 1px solid #f0f0f0;
  padding: 14px 0;
  align-items: center;
}
.profile-label {
  width: 160px;
  color: #888;
  font-weight: 400;
  font-size: 1rem;
}
.profile-value {
  color: #222;
  font-weight: 500;
  font-size: 1rem;
  word-break: break-word;
}
.profile-actions {
  display: flex;
  justify-content: flex-end;
  padding: 18px 32px 0 32px;
}
.profile-section-title {
  font-size: 1.15rem;
  font-weight: 600;
  color: #222;
  margin: 28px 0 18px 32px;
}
.profile-password-form {
  display: flex;
  flex-direction: column;
  gap: 14px;
  padding: 0 32px;
}
.profile-form-row {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 8px;
}
.profile-form-label {
  width: 170px;
  color: #888;
  font-size: 15px;
  font-weight: 400;
  margin-bottom: 0;
}
.profile-input-wrapper {
  position: relative;
  flex: 1;
  display: flex;
  align-items: center;
}
.profile-input-wrapper input {
  width: 100%;
  background: #fff;
  border: 1.5px solid #e0e0e0;
  border-radius: 8px;
  padding: 10px 38px 10px 14px;
  font-size: 15px;
  color: #222;
  outline: none;
  transition: border 0.2s;
}
.profile-eye-icon {
  position: absolute;
  right: 12px;
  cursor: pointer;
  color: #888;
  font-size: 1.1rem;
  z-index: 2;
}
.profile-save-btn-row {
  display: flex;
  justify-content: flex-end;
  margin-top: 4px;
}
.profile-message {
  margin-top: 12px;
  color: #2e7d32;
  font-weight: 500;
  padding-left: 32px;
}
@media (max-width: 900px) {
  .profile-outer {
    max-width: 98vw;
    padding: 0 2vw;
  }
  .profile-header,
  .profile-info-table,
  .profile-actions,
  .profile-section-title,
  .profile-password-form {
    padding-left: 12px;
    padding-right: 12px;
  }
  .profile-section-title {
    margin-left: 12px;
  }
}
</style>
