<template>
  <div class="user-assessment-outer">
    <div class="user-assessment-card">
      <template v-if="!submitted">
        <div class="user-assessment-header">
          <div class="user-assessment-title">
            <template v-if="step === 1">
              Q.1 Please select the words below that describe how other people
              expect you to be in most daily situations.
            </template>
            <template v-else>
              Q.2 Please select the words below that describe how you really
              are, not how you are expected to be.
            </template>
          </div>
        </div>
        <div class="user-assessment-table-container">
          <div class="user-assessment-words-grid">
            <div
              v-for="(word, idx) in words"
              :key="word"
              class="user-assessment-word-cell"
            >
              <label
                :class="[
                  'user-assessment-checkbox-label',
                  { checked: selectedWords[step - 1].includes(word) },
                ]"
              >
                <input
                  type="checkbox"
                  :value="word"
                  v-model="selectedWords[step - 1]"
                />
                <span class="user-assessment-checkbox-custom"></span>
                {{ word }}
              </label>
            </div>
          </div>
        </div>
        <div class="user-assessment-footer">
          <button
            class="btn btn-secondary user-assessment-step-btn"
            disabled
          >
            Question {{ step }} of 2
          </button>
          <div style="display: flex; gap: 12px; margin-left: auto">
            <template v-if="step === 1">
              <button
                class="btn btn-primary user-assessment-next-btn"
                @click="goToNext"
              >
                Next
              </button>
            </template>
            <template v-else>
              <button
                class="btn btn-secondary user-assessment-back-btn"
                @click="goToBack"
              >
                Back
              </button>
              <button
                class="btn btn-primary user-assessment-next-btn"
                @click="submitAssessment"
              >
                Submit
              </button>
            </template>
          </div>
        </div>
      </template>
      <template v-else>
        <div class="user-assessment-success-card">
          <div class="user-assessment-success-icon">
            <svg
              width="80"
              height="80"
              viewBox="0 0 80 80"
              fill="none"
            >
              <circle
                cx="40"
                cy="40"
                r="40"
                fill="#2ECC40"
              />
              <path
                d="M25 42l13 13 17-23"
                stroke="#fff"
                stroke-width="5"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </div>
          <div class="user-assessment-success-title">
            Assessment submitted successfully and processed!
          </div>
          <div class="user-assessment-success-desc">
            Lorem Ipsum is simply dummy text of the printing and typesetting
            industry. Lorem Ipsum has been the industry's standard dummy text
            ever since the 1500s, when an unknown printer took a galley of type
            and scrambled it to make a type specimen book. It has survived not
            only five centuries, but also the leap into electronic typesetting,
            remaining essentially unchanged
          </div>
          <button
            class="btn btn-primary user-assessment-success-btn"
            @click="$router.push({ name: 'ManageSubscription' })"
          >
            Manage Subscription
          </button>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserAssessment',
  data() {
    return {
      step: 1,
      words: [
        'Relaxed',
        'Persuasive',
        'Stable',
        'Charismatic',
        'Individualistic',
        'Optimistic',
        'Conforming',
        'Methodical',
        'Serious',
        'Friendly',
        'Humble',
        'Unrestrained',
        'Competitive',
        'Docile',
        'Restless',
      ],
      selectedWords: [[], []], // [step1, step2]
      submitted: false,
    };
  },
  methods: {
    goToNext() {
      this.step = 2;
    },
    goToBack() {
      this.step = 1;
    },
    submitAssessment() {
      this.submitted = true;
      this.$emit('submit', this.selectedWords);
    },
  },
};
</script>

<style scoped>
/* --- Base layout and card structure (matches Leads/OrganizationTable/Notifications) --- */
.user-assessment-outer {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
}

.user-assessment-card {
  width: 100%;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 0 auto;
  box-sizing: border-box;
  min-width: 0;
  max-width: 1400px;
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
  padding: 0;
}

.user-assessment-header {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px 46px 0 24px;
  background: #fff;
  border-top-left-radius: 24px;
  border-top-right-radius: 24px;
  min-height: 64px;
  box-sizing: border-box;
}

.user-assessment-title {
  font-size: 18px;
  font-weight: 600;
  text-align: center;
  width: 100%;
}

.user-assessment-table-container {
  width: 100%;
  box-sizing: border-box;
  padding: 0 24px 24px 24px;
  background: #fff;
  border-bottom-left-radius: 24px;
  border-bottom-right-radius: 24px;
}

.user-assessment-words-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px 24px;
  margin: 0 auto 32px auto;
  max-width: 900px;
}

.user-assessment-word-cell {
  display: flex;
  align-items: center;
}

.user-assessment-checkbox-label {
  display: flex;
  align-items: center;
  background: #f8f9fb;
  border-radius: 10px;
  padding: 12px 18px;
  font-size: 16px;
  font-weight: 500;
  color: #222;
  cursor: pointer;
  border: 2px solid #f8f9fb;
  transition: border 0.18s, background 0.18s;
  width: 100%;
  user-select: none;
}
.user-assessment-checkbox-label.checked {
  background: #e6f0fa;
  border: 2px solid #0074c2;
}
.user-assessment-checkbox-label input[type='checkbox'] {
  display: none;
}
.user-assessment-checkbox-custom {
  width: 22px;
  height: 22px;
  border-radius: 6px;
  border: 2px solid #bbb;
  background: #fff;
  margin-right: 12px;
  display: inline-block;
  position: relative;
}
.user-assessment-checkbox-label.checked .user-assessment-checkbox-custom {
  border: 2px solid #0074c2;
  background: #0074c2;
}
.user-assessment-checkbox-label.checked .user-assessment-checkbox-custom:after {
  content: '';
  display: block;
  position: absolute;
  left: 6px;
  top: 2px;
  width: 6px;
  height: 12px;
  border: solid #fff;
  border-width: 0 3px 3px 0;
  transform: rotate(45deg);
}

.user-assessment-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 24px;
  padding: 0 24px 24px 24px;
}

.user-assessment-step-btn {
  background: #f5f5f5;
  border: none;
  border-radius: 999px;
  padding: 8px 24px;
  font-size: 15px;
  color: #888;
  font-weight: 500;
  cursor: default;
}

.user-assessment-next-btn {
  background: #0074c2;
  color: #fff;
  border: none;
  border-radius: 999px;
  padding: 10px 32px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.18s;
}
.user-assessment-next-btn:hover {
  background: #005fa3;
}
.user-assessment-back-btn {
  background: #fff;
  color: #222;
  border: 1.5px solid #e0e0e0;
  border-radius: 999px;
  padding: 10px 32px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  margin-right: 12px;
  transition: background 0.18s, border 0.18s;
}
.user-assessment-back-btn:hover {
  background: #f5f5f5;
  border: 1.5px solid #0074c2;
}

/* Success Card */
.user-assessment-success-card {
  background: #fff;
  border-radius: 24px;
  box-shadow: none;
  padding: 48px 32px 40px 32px;
  max-width: 700px;
  width: 100%;
  text-align: center;
  margin: 0 auto;
}
.user-assessment-success-icon {
  margin-bottom: 32px;
}
.user-assessment-success-title {
  font-size: 2rem;
  font-weight: 600;
  margin-bottom: 24px;
  color: #234056;
}
.user-assessment-success-desc {
  font-size: 1.1rem;
  color: #444;
  margin-bottom: 32px;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}
.user-assessment-success-btn {
  background: #0074c2;
  color: #fff;
  border: none;
  border-radius: 999px;
  padding: 12px 36px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.18s;
}
.user-assessment-success-btn:hover {
  background: #005fa3;
}

/* --- Responsive styles to match base pages --- */
@media (max-width: 1400px) {
  .user-assessment-outer {
    margin: 12px;
    max-width: 100%;
  }
  .user-assessment-card {
    border-radius: 14px;
    max-width: 100%;
  }
  .user-assessment-header {
    padding: 8px 8px 0 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .user-assessment-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
  }
  .user-assessment-footer {
    padding: 0 8px 8px 8px;
  }
  .user-assessment-success-card {
    border-radius: 14px;
    padding: 18px 8px 18px 8px;
    max-width: 100%;
  }
  .user-assessment-words-grid {
    gap: 12px 12px;
  }
}
@media (max-width: 900px) {
  .user-assessment-outer {
    margin: 4px;
    max-width: 100%;
  }
  .user-assessment-card {
    border-radius: 10px;
  }
  .user-assessment-header {
    padding: 8px 4px 0 4px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .user-assessment-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  .user-assessment-footer {
    padding: 0 4px 4px 4px;
  }
  .user-assessment-success-card {
    border-radius: 10px;
    padding: 8px 4px 8px 4px;
    max-width: 100%;
  }
  .user-assessment-words-grid {
    grid-template-columns: 1fr;
    gap: 8px 8px;
  }
}
</style>
