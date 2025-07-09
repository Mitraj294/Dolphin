<template>
  <MainLayout>
    <div class="page">
      <div class="lead-capture-outer">
        <div class="lead-capture-card">
          <h3 class="lead-capture-card-title">Enter Lead Details</h3>
          <form class="lead-capture-form">
            <div class="lead-capture-grid">
              <div class="lead-capture-field">
                <label>First Name</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-user"></i>
                  <input
                    type="text"
                    placeholder="Type here"
                  />
                </div>
              </div>
              <div class="lead-capture-field">
                <label>Last Name</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-user"></i>
                  <input
                    type="text"
                    placeholder="Type here"
                  />
                </div>
              </div>
              <div class="lead-capture-field">
                <label>Email</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-envelope"></i>
                  <input
                    type="email"
                    placeholder="abc@gmail.com"
                  />
                </div>
              </div>
              <div class="lead-capture-field">
                <label>Phone</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-phone"></i>
                  <input
                    type="text"
                    placeholder="Type here"
                  />
                </div>
              </div>
              <div class="lead-capture-field">
                <label>Password</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-lock"></i>
                  <input
                    :type="showPassword ? 'text' : 'password'"
                    placeholder="Type here"
                  />
                  <i
                    :class="[
                      'fas',
                      showPassword ? 'fa-eye-slash' : 'fa-eye',
                      'input-eye',
                    ]"
                    @click="togglePassword"
                  ></i>
                </div>
              </div>
              <div class="lead-capture-field">
                <label>How did you find us?</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-search"></i>
                  <select>
                    <option>Select</option>
                  </select>
                  <i class="fas fa-chevron-down input-chevron"></i>
                </div>
              </div>
              <div class="lead-capture-field">
                <label>Organization Name</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-cog"></i>
                  <input
                    type="text"
                    placeholder="Flexi-Finders"
                  />
                </div>
              </div>
              <div class="lead-capture-field">
                <label>Organization Size</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-users"></i>
                  <select>
                    <option>250+ Employees (Large)</option>
                  </select>
                  <i class="fas fa-chevron-down input-chevron"></i>
                </div>
              </div>
              <!-- Add empty div for grid alignment -->
              <div
                class="lead-capture-field"
                style="visibility: hidden"
              ></div>
              <div class="lead-capture-field">
                <label>Address Line</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-map-marker-alt"></i>
                  <input
                    type="text"
                    placeholder="153, Maggie Loop Pottsville"
                  />
                </div>
              </div>
              <div class="lead-capture-field">
                <label>Country</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-globe"></i>
                  <select>
                    <option>United States</option>
                  </select>
                  <i class="fas fa-chevron-down input-chevron"></i>
                </div>
              </div>
              <div class="lead-capture-field">
                <label>State</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-map-marker-alt"></i>
                  <select>
                    <option>Arkansas(AR)</option>
                  </select>
                  <i class="fas fa-chevron-down input-chevron"></i>
                </div>
              </div>
              <div class="lead-capture-field">
                <label>City</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-map-marker-alt"></i>
                  <select>
                    <option>Adona</option>
                  </select>
                  <i class="fas fa-chevron-down input-chevron"></i>
                </div>
              </div>
              <div class="lead-capture-field">
                <label>Zip Code</label>
                <div class="input-icon-wrapper">
                  <i class="fas fa-map-marker-alt"></i>
                  <input
                    type="text"
                    placeholder="72858"
                  />
                </div>
              </div>
            </div>
            <div class="lead-capture-actions">
              <button
                type="button"
                class="btn btn-secondary"
                @click="$router.push('/leads')"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="btn btn-primary"
              >
                Save Lead
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
export default {
  name: 'LeadCapture',
  components: {
    MainLayout,
  },
  data() {
    return {
      showPassword: false,
    };
  },
  methods: {
    togglePassword() {
      this.showPassword = !this.showPassword;
    },
  },
};
</script>

<style scoped>
.lead-capture-outer {
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

.lead-capture-card {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 0 auto;
  box-sizing: border-box;
  padding: 32px 32px 24px 32px;
  display: flex;
  flex-direction: column;
  gap: 32px;
  position: relative;
}

.lead-capture-card-title {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 24px;
  text-align: left;
  width: 100%;
}

.lead-capture-form {
  width: 100%;
}

.lead-capture-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px 24px;
  margin-bottom: 32px;
}

.lead-capture-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.lead-capture-field label {
  color: #222;
  font-size: 15px;
  font-weight: 400;
  text-align: left;
  width: 100%;
}

.input-icon-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon-wrapper select {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background: #fff;
  border: 1.5px solid #e0e0e0;
  border-radius: 8px;
  padding: 10px 14px 10px 36px; /* add left padding for icon */
  font-size: 15px;
  color: #222;
  outline: none;
  transition: border 0.2s;
  width: 100%;
  box-sizing: border-box;
}

.input-icon-wrapper input {
  background: #fff;
  border: 1.5px solid #e0e0e0;
  border-radius: 8px;
  padding: 10px 14px 10px 36px;
  font-size: 15px;
  color: #222;
  outline: none;
  transition: border 0.2s;
  width: 100%;
  box-sizing: border-box;
}

.input-icon-wrapper input:focus,
.input-icon-wrapper select:focus {
  border: 1.5px solid #0074c2;
}

.input-icon-wrapper i:not(.input-eye):not(.input-chevron) {
  position: absolute;
  left: 12px;
  color: #b0b0b0;
  font-size: 1rem;
  z-index: 2;
  pointer-events: none;
}

.input-icon-wrapper .input-eye {
  right: 12px;
  left: auto;
  position: absolute;
  color: #b0b0b0;
  cursor: pointer;
  z-index: 3;
}

.input-icon-wrapper .input-chevron {
  right: 12px;
  left: auto;
  position: absolute;
  color: #b0b0b0;
  pointer-events: none;
}

.lead-capture-actions {
  display: flex;
  justify-content: flex-end;
  gap: 18px;
}

/* Removed local button styles. Use only global .btn classes for buttons. */

/* Responsive styles to match other pages */
@media (max-width: 1400px) {
  .lead-capture-outer {
    margin: 12px;
    max-width: 100%;
  }
  .lead-capture-card {
    max-width: 100%;
    border-radius: 14px;
    padding: 18px 8px 12px 8px;
  }
  .lead-capture-grid {
    gap: 12px 8px;
  }
}

@media (max-width: 900px) {
  .lead-capture-outer {
    margin: 4px;
    max-width: 100%;
  }
  .lead-capture-card {
    padding: 8px 2vw 8px 2vw;
    border-radius: 10px;
  }
  .lead-capture-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
  .lead-capture-field {
    grid-column: auto !important;
    grid-row: auto !important;
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
