<template>
  <MainLayout>
    <div class="schedule-demo-outer">
      <div class="schedule-demo-card">
        <h2 class="schedule-demo-title">Schedule Classes/Training</h2>
        <div class="schedule-demo-desc">
          Lorem Ipsum is simply dummy text of the printing and typesetting
          industry.
        </div>
        <form class="schedule-demo-form">
          <div class="schedule-demo-row">
            <div class="schedule-demo-field">
              <label>Select Organization</label>
              <select>
                <option>Select</option>
              </select>
            </div>
            <div class="schedule-demo-field">
              <label>Select Admin</label>
              <select>
                <option>Select</option>
              </select>
            </div>
            <div class="schedule-demo-field">
              <label>Select User</label>
              <select>
                <option>Select</option>
              </select>
            </div>
          </div>
          <div class="schedule-demo-row">
            <div class="schedule-demo-field">
              <label>Subject</label>
              <input
                type="text"
                placeholder="Type here"
              />
            </div>
            <div class="schedule-demo-field">
              <label>Select Classes/Training</label>
              <select>
                <option>Select</option>
              </select>
            </div>
            <div class="schedule-demo-field schedule-demo-schedule-field">
              <label>Schedule</label>
              <div class="schedule-demo-schedule-inputs">
                <input
                  type="date"
                  placeholder="MM/DD/YYYY"
                />
                <input
                  type="time"
                  placeholder="00:00"
                />
              </div>
            </div>
          </div>
          <div class="schedule-demo-actions">
            <button
              type="submit"
              class="schedule-demo-btn"
            >
              Schedule Classes/Training
            </button>
          </div>
        </form>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../../layout/MainLayout.vue';
export default {
  name: 'ScheduleClassTraining',
  components: { MainLayout },
};
</script>

<style scoped>
.schedule-demo-outer {
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
.schedule-demo-card {
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
.schedule-demo-title {
  font-size: 22px;
  font-weight: 600;
  margin-top: 0;
  margin-bottom: 8px;
  text-align: left;
  color: #222;
}
.schedule-demo-desc {
  font-size: 16px;
  color: #222;
  margin-bottom: 24px;
  text-align: left;
}
.schedule-demo-form {
  width: 100%;
}
.schedule-demo-row {
  display: flex;
  gap: 18px;
  margin-bottom: 18px;
  flex-direction: row !important;
}
.schedule-demo-field {
  flex: 1 1 0;
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.schedule-demo-field label {
  color: #222;
  font-size: 15px;
  font-weight: 400;
  text-align: left;
}
.schedule-demo-field input,
.schedule-demo-field select {
  background: #fafafa;
  border: 1.5px solid #e0e0e0;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 15px;
  color: #222;
  outline: none;
  transition: border 0.2s;
}
.schedule-demo-schedule-inputs {
  display: flex;
  gap: 18px;
  width: 100%;
}
.schedule-demo-schedule-inputs input[type='date'],
.schedule-demo-schedule-inputs input[type='time'] {
  flex: 1 1 0;
  min-width: 0;
  background: #fafafa;
  border: 1.5px solid #e0e0e0;
  border-radius: 8px;
  padding: 10px 14px 10px 44px;
  font-size: 15px;
  color: #222;
  outline: none;
  transition: border 0.2s;
  box-sizing: border-box;
}
.schedule-demo-schedule-inputs input[type='date']::placeholder,
.schedule-demo-schedule-inputs input[type='time']::placeholder {
  color: #888;
  opacity: 1;
}
.schedule-demo-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 18px;
}
.schedule-demo-btn {
  background: #0164a5;
  color: #fff;
  border: none;
  border-radius: 24px;
  padding: 12px 32px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}
.schedule-demo-btn:hover {
  background: #005b8e;
}
/* Responsive styles to match other pages */
@media (max-width: 1400px) {
  .schedule-demo-outer {
    margin: 12px;
    max-width: 100%;
  }
  .schedule-demo-card {
    max-width: 100%;
    border-radius: 14px;
    padding: 18px 8px 12px 8px;
  }
  .schedule-demo-row {
    gap: 12px;
  }
}
@media (max-width: 900px) {
  .schedule-demo-outer {
    margin: 4px;
    max-width: 100%;
  }
  .schedule-demo-card {
    padding: 8px 2vw 8px 2vw;
    border-radius: 10px;
  }
  .schedule-demo-row {
    flex-direction: column !important;
    gap: 12px;
  }
}
</style>
