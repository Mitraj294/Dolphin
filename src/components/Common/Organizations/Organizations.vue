<template>
  <MainLayout>
    <div class="page">
      <OrganizationTable />
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

<style>
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
