<template>
  <MainLayout>
    <div class="organizations-page">
      <OrganizationTable />
      <!-- Remove role check and always show the table -->
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
import OrganizationTable from './OrganizationTable.vue';
export default {
  name: 'Organizations',
  components: { MainLayout, OrganizationTable },
};
</script>

<style scoped>
.organizations-page {
  padding: 32px;
  display: flex;
  background-color: #fff;
  justify-content: center;
}
</style>
