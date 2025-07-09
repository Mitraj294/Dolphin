<template>
  <MainLayout>
    <div class="page">
      <OrgAdminGraphs v-if="isOrgAdmin" />
      <UserGraphs v-else-if="isUser" />
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../../layout/MainLayout.vue';
import OrgAdminGraphs from './OrgAdminGraphs.vue';
import UserGraphs from './UserGraphs.vue';

export default {
  components: {
    MainLayout,
    OrgAdminGraphs,
    UserGraphs,
  },
  computed: {
    isOrgAdmin() {
      const role = localStorage.getItem('role');
      return (
        role === 'organizationadmin' ||
        role === 'superadmin' ||
        role === 'Dolphinadmin'
      );
    },
    isUser() {
      const role = localStorage.getItem('role');
      return role === 'user' || role === 'salesperson';
    },
  },
};
</script>
<style scoped>
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
