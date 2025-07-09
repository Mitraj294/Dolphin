<template>
  <MainLayout>
    <div class="page">
      <div class="page-content-wrapper">
        <div class="training-resources-page">
          <h2>Training & Resources</h2>
          <p>Welcome to the Training & Resources page.</p>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../layout/MainLayout.vue';
export default {
  name: 'TrainingResources',
  components: { MainLayout },
};
</script>

<style scoped>
.page-content-wrapper {
  min-height: 0;
  padding-bottom: 0;
  margin-bottom: 0;
  background: none;
}

.training-resources-page {
  padding: 32px;
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
