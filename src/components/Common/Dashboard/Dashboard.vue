<template>
  <MainLayout>
    <div>
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
/* No button styles here. All button styles should come from global.css only. */
</style>
