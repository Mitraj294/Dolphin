<template>
  <div class="table-card">
    <div class="table-container">
      <table class="table">
        <TableHeader
          :columns="[
            { label: 'Group Name', key: 'name' },
            { label: 'Actions', key: 'action' },
          ]"
        />
        <tbody>
          <tr
            v-for="group in groups"
            :key="group.id"
          >
            <td>{{ group.name }}</td>
            <td>
              <button
                class="btn-view"
                @click="viewGroup(group)"
              >
                <img
                  src="@/assets/images/Notes.svg"
                  alt="View"
                  class="btn-view-icon"
                />
                <span class="view-label">View</span>
              </button>
            </td>
          </tr>
          <tr v-if="groups.length === 0">
            <td
              colspan="2"
              class="empty-row"
            >
              No groups found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import TableHeader from '@/components/Common/Common_UI/TableHeader.vue';

export default {
  name: 'MemberTable',
  components: { TableHeader },
  props: {
    groups: {
      type: Array,
      default: () => [],
    },
  },
  methods: {
    viewGroup(group) {
      this.$emit('view-group', group);
    },
  },
};
</script>
