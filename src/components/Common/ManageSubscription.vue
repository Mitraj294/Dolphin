<template>
  <MainLayout>
    <div class="page">
      <div class="manage-subscription-outer">
        <div class="manage-subscription-card">
          <div class="manage-subscription-header">
            <!-- Reserved for future actions, keep for layout consistency -->
          </div>
          <div class="manage-subscription-header-spacer"></div>
          <div class="manage-subscription-container">
            <img
              src="@/assets/images/Group 14.svg"
              alt="No Plans"
              class="manage-subscription-img"
            />
            <div class="manage-subscription-msg">
              You have not selected any plans yet.
            </div>
            <button
              class="btn btn-primary"
              @click="explorePlans"
            >
              Explore Subscriptions
            </button>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
export default {
  name: 'ManageSubscription',
  components: { MainLayout },
  methods: {
    explorePlans() {
      this.$router.push({ name: 'SubscriptionPlans' });
    },
  },
};
</script>

<style scoped>
.manage-subscription-outer {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
}
.manage-subscription-card {
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
.manage-subscription-header {
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
.manage-subscription-header-spacer {
  height: 18px;
  width: 100%;
  background: transparent;
  display: block;
}
.manage-subscription-container {
  width: 100%;
  min-height: 320px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  padding: 0 24px 48px 24px;
  background: #fff;
  border-bottom-left-radius: 24px;
  border-bottom-right-radius: 24px;
}
.manage-subscription-img {
  width: 160px;
  margin-bottom: 24px;
  margin-top: 24px;
}
.manage-subscription-msg {
  font-size: 17px;
  color: #222;
  margin-bottom: 28px;
  text-align: center;
  font-family: 'Inter', Arial, sans-serif;
  font-weight: 500;
}

/* Responsive styles to match base pages */
@media (max-width: 1400px) {
  .manage-subscription-outer {
    margin: 12px;
    max-width: 100%;
  }
  .manage-subscription-card {
    border-radius: 14px;
    max-width: 100%;
  }
  .manage-subscription-header {
    padding: 8px 8px 0 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .manage-subscription-container {
    padding: 0 8px 24px 8px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
  }
}
@media (max-width: 900px) {
  .manage-subscription-outer {
    margin: 4px;
    max-width: 100%;
  }
  .manage-subscription-card {
    border-radius: 10px;
  }
  .manage-subscription-header {
    padding: 8px 4px 0 4px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .manage-subscription-container {
    padding: 0 4px 12px 4px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
@media (max-width: 600px) {
  .manage-subscription-container {
    padding: 0 2vw 8px 2vw;
    min-height: 180px;
  }
  .manage-subscription-img {
    width: 120px;
    margin-top: 12px;
    margin-bottom: 16px;
  }
  .manage-subscription-msg {
    font-size: 15px;
    margin-bottom: 18px;
  }
}
</style>
