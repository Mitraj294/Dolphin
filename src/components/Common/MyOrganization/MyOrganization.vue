<template>
  <MainLayout>
    <div class="page">
      <div class="my-org-table-outer">
        <div class="my-org-table-card">
          <div class="my-org-table-header">
            <div class="my-org-action-buttons">
              <button
                class="my-org-secondary"
                @click="$router.push({ name: 'MemberListing' })"
              >
                Members Listing
              </button>
              <button class="my-org-secondary">
                <img
                  src="@/assets/images/Templates.svg"
                  alt="Template"
                  style="
                    width: 18px;
                    height: 18px;
                    margin-right: 6px;
                    vertical-align: middle;
                  "
                />
                Template
              </button>
              <button
                class="my-org-primary"
                @click="showAddGroupModal = true"
              >
                <img
                  src="@/assets/images/Add.svg"
                  alt="Add"
                  style="
                    width: 18px;
                    height: 18px;
                    margin-right: 6px;
                    vertical-align: middle;
                  "
                />
                Add New Group
              </button>
              <button
                class="my-org-primary"
                @click="showAddMemberModal = true"
              >
                <img
                  src="@/assets/images/Add.svg"
                  alt="Add"
                  style="
                    width: 18px;
                    height: 18px;
                    margin-right: 6px;
                    vertical-align: middle;
                  "
                />
                Add New Member
              </button>
            </div>
          </div>
          <div class="my-org-table-header-spacer"></div>
          <div class="my-org-table-container">
            <table class="my-org-table">
              <thead>
                <tr>
                  <th class="rounded-th-left">Group Name</th>
                  <th class="rounded-th-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="group in paginatedGroups"
                  :key="group.id"
                >
                  <td>{{ group.name }}</td>
                  <td>
                    <button
                      class="icon-btn view-btn"
                      @click="viewGroup(group)"
                    >
                      <img
                        src="@/assets/images/Notes.svg"
                        alt="View"
                        class="view-icon"
                      />
                      <span class="view-label">View</span>
                    </button>
                  </td>
                </tr>
                <tr v-if="paginatedGroups.length === 0">
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
        <Pagination
          :pageSize="pageSize"
          :pageSizes="pageSizes"
          :showPageDropdown="showPageDropdown"
          :currentPage="currentPage"
          :totalPages="totalPages"
          @togglePageDropdown="togglePageDropdown"
          @selectPageSize="selectPageSize"
          @goToPage="goToPage"
        />
        <!-- Add New Member Modal -->
        <div
          v-if="showAddMemberModal"
          class="modal-overlay"
        >
          <div class="modal-card">
            <button
              class="modal-close"
              @click="showAddMemberModal = false"
            >
              &times;
            </button>
            <h2 class="modal-title">Add New Member</h2>
            <form
              class="modal-form"
              @submit.prevent="saveMember"
            >
              <div class="modal-form-row">
                <div class="modal-form-group">
                  <span class="modal-icon"><i class="fas fa-user"></i></span>
                  <input
                    type="text"
                    placeholder="First Name"
                    v-model="newMember.firstName"
                    required
                  />
                </div>
                <div class="modal-form-group">
                  <span class="modal-icon"><i class="fas fa-user"></i></span>
                  <input
                    type="text"
                    placeholder="Last Name"
                    v-model="newMember.lastName"
                    required
                  />
                </div>
              </div>
              <div class="modal-form-row">
                <div class="modal-form-group">
                  <span class="modal-icon"
                    ><i class="fas fa-envelope"></i
                  ></span>
                  <input
                    type="email"
                    placeholder="Email"
                    v-model="newMember.email"
                    required
                  />
                </div>
                <div class="modal-form-group">
                  <span class="modal-icon"><i class="fas fa-phone"></i></span>
                  <input
                    type="text"
                    placeholder="Phone Number"
                    v-model="newMember.phone"
                    required
                  />
                </div>
              </div>
              <div class="modal-form-row">
                <div class="modal-form-group custom-dropdown">
                  <span class="modal-icon"
                    ><i class="fas fa-user-tag"></i
                  ></span>
                  <div
                    class="custom-dropdown-input"
                    @click="toggleRoleDropdown"
                  >
                    <span
                      v-if="!newMember.roles.length"
                      style="color: #888"
                      >Role</span
                    >
                    <span
                      v-else
                      class="selected-value"
                      >{{ newMember.roles.join(', ') }}</span
                    >
                    <i class="fas fa-chevron-down"></i>
                  </div>
                  <div
                    v-if="roleDropdownOpen"
                    class="dropdown-list"
                  >
                    <input
                      class="dropdown-search"
                      v-model="roleSearch"
                      placeholder="Search"
                    />
                    <div
                      v-for="role in filteredRoles"
                      :key="role"
                      class="dropdown-item"
                      @click="selectRole(role)"
                    >
                      {{ role }}
                      <span
                        class="dropdown-checkbox"
                        :class="{ checked: newMember.roles.includes(role) }"
                      ></span>
                    </div>
                  </div>
                </div>
                <div class="modal-form-group custom-dropdown">
                  <span class="modal-icon"><i class="fas fa-users"></i></span>
                  <div
                    class="custom-dropdown-input"
                    @click="toggleGroupDropdown"
                  >
                    <span
                      v-if="!newMember.groups.length"
                      style="color: #888"
                      >Groups associated with</span
                    >
                    <span
                      v-else
                      class="selected-value"
                      >{{
                        newMember.groups.map((g) => g.name || g).join(', ')
                      }}</span
                    >
                    <i class="fas fa-chevron-down"></i>
                  </div>
                  <div
                    v-if="groupDropdownOpen"
                    class="dropdown-list"
                  >
                    <input
                      class="dropdown-search"
                      v-model="groupSearch"
                      placeholder="Search"
                    />
                    <div
                      v-for="group in filteredGroups"
                      :key="group.id"
                      class="dropdown-item"
                      @click="selectGroup(group)"
                    >
                      {{ group.name }}
                      <span
                        class="dropdown-checkbox"
                        :class="{
                          checked: newMember.groups.includes(group.name),
                        }"
                      ></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-form-actions">
                <button
                  type="submit"
                  class="modal-save-btn"
                >
                  Save Member
                </button>
              </div>
            </form>
          </div>
        </div>
        <!-- End Modal -->
        <!-- Add New Group Modal -->
        <div
          v-if="showAddGroupModal"
          class="modal-overlay"
        >
          <div class="modal-card">
            <button
              class="modal-close"
              @click="showAddGroupModal = false"
            >
              &times;
            </button>
            <h2 class="modal-title">Add New Group</h2>
            <form
              class="modal-form"
              @submit.prevent="saveGroup"
            >
              <div class="modal-form-row">
                <div class="modal-form-group">
                  <span class="modal-icon"><i class="fas fa-users"></i></span>
                  <input
                    type="text"
                    placeholder="Group Name"
                    v-model="newGroup.name"
                    required
                  />
                </div>
                <div
                  class="modal-form-group custom-dropdown"
                  style="position: relative"
                >
                  <span class="modal-icon"><i class="fas fa-user"></i></span>
                  <input
                    type="text"
                    placeholder="Members"
                    v-model="newGroup.members"
                    readonly
                    style="
                      background: transparent;
                      border: none;
                      outline: none;
                      font-size: 16px;
                      color: #222;
                      width: 100%;
                    "
                    @click="showMembersDropdown = !showMembersDropdown"
                  />
                  <i
                    class="fas fa-chevron-down"
                    style="margin-left: auto; color: #888; cursor: pointer"
                    @click="showMembersDropdown = !showMembersDropdown"
                  ></i>
                  <div
                    v-if="showMembersDropdown"
                    class="dropdown-list"
                    style="left: 0; top: 54px; width: 100%"
                  >
                    <div
                      class="dropdown-item"
                      style="color: #888"
                    >
                      No members available
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-form-actions">
                <button
                  type="submit"
                  class="modal-save-btn"
                >
                  Save Group
                </button>
              </div>
            </form>
          </div>
        </div>
        <!-- End Add New Group Modal -->
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../../layout/MainLayout.vue';
import Pagination from '../../layout/Pagination.vue';
export default {
  name: 'MyOrganization',
  components: { MainLayout, Pagination },
  data() {
    return {
      groups: [
        { id: 1, name: 'Flexi-Finders' },
        { id: 2, name: 'Interim Solutions' },
        { id: 3, name: 'Talent on Demand' },
        { id: 4, name: 'QuickStaff' },
      ],
      roles: ['Manager', 'CEO', 'Owner', 'Support'],
      currentPage: 1,
      pageSize: 10,
      pageSizes: [10, 25, 100],
      showPageDropdown: false,
      showAddMemberModal: false,
      showAddGroupModal: false,
      showMembersDropdown: false,
      newMember: {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        roles: [],
        groups: [],
      },
      newGroup: {
        name: '',
        members: '',
      },
      roleDropdownOpen: false,
      groupDropdownOpen: false,
      roleSearch: '',
      groupSearch: '',
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.groups.length / this.pageSize);
    },
    paginatedGroups() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.groups.slice(start, start + this.pageSize);
    },
    filteredRoles() {
      if (!this.roleSearch) return this.roles;
      return this.roles.filter((r) =>
        r.toLowerCase().includes(this.roleSearch.toLowerCase())
      );
    },
    filteredGroups() {
      if (!this.groupSearch) return this.groups;
      return this.groups.filter((g) =>
        g.name.toLowerCase().includes(this.groupSearch.toLowerCase())
      );
    },
  },
  methods: {
    viewGroup(group) {
      // Implement view group logic (e.g., navigate to group detail)
      alert(`Viewing group: ${group.name}`);
    },
    togglePageDropdown() {
      this.showPageDropdown = !this.showPageDropdown;
    },
    selectPageSize(size) {
      this.pageSize = size;
      this.currentPage = 1;
      this.showPageDropdown = false;
    },
    goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    toggleRoleDropdown() {
      this.roleDropdownOpen = !this.roleDropdownOpen;
    },
    toggleGroupDropdown() {
      this.groupDropdownOpen = !this.groupDropdownOpen;
    },
    selectRole(role) {
      if (!this.newMember.roles.includes(role)) {
        this.newMember.roles.push(role);
      } else {
        this.newMember.roles = this.newMember.roles.filter((r) => r !== role);
      }
    },
    selectGroup(group) {
      if (!this.newMember.groups.includes(group.name)) {
        this.newMember.groups.push(group.name);
      } else {
        this.newMember.groups = this.newMember.groups.filter(
          (g) => g !== group.name
        );
      }
    },
    removeRole(role) {
      this.newMember.roles = this.newMember.roles.filter((r) => r !== role);
    },
    removeGroup(group) {
      this.newMember.groups = this.newMember.groups.filter((g) => g !== group);
    },
    saveMember() {
      // Implement save logic here
      this.showAddMemberModal = false;
      this.newMember = {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        roles: [],
        groups: [],
      };
    },
    saveGroup() {
      // Implement save logic here
      this.showAddGroupModal = false;
      this.newGroup = { name: '', members: '' };
    },
  },
};
</script>

<style scoped>
.my-org-table-outer {
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
.my-org-table-card {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 0 auto;
  box-sizing: border-box;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
}
.my-org-table-header {
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
.my-org-action-buttons {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}
.my-org-primary,
.my-org-secondary {
  border-radius: 29.01px;
  font-family: 'Helvetica Neue LT Std', Helvetica, Arial, sans-serif;
  font-weight: 500;
  font-size: 15px;
  padding: 8px 24px 8px 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-right: 0;
  margin-top: 0;
  box-shadow: none;
  border: none;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
  white-space: nowrap;
  min-width: 0;
  max-width: none;
  overflow: visible;
}
.my-org-primary {
  background: #0164a5;
  color: #fff;
}
.my-org-primary:hover {
  background: #005fa3;
  color: #fff;
}
.my-org-secondary {
  background: #f5f5f5;
  color: #888;
}

.my-org-action-buttons .my-org-secondary:nth-child(2) {
  background: #0164a5 !important;
  color: #fff !important;
}
.my-org-action-buttons .my-org-secondary:nth-child(2):hover {
  background: #005fa3 !important;
  color: #fff !important;
}
.my-org-table-header-spacer {
  height: 18px;
  width: 100%;
  background: transparent;
  display: block;
}
.my-org-table-container {
  width: 100%;
  overflow-x: auto;
  box-sizing: border-box;
  padding: 0 24px 24px 24px;
  background: #fff;
  border-bottom-left-radius: 24px;
  border-bottom-right-radius: 24px;
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.my-org-table-container::-webkit-scrollbar {
  display: none;
}
.my-org-table {
  min-width: 800px;
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  margin-bottom: 8px;
  background: transparent;
  margin-left: 0;
  margin-right: 0;
  table-layout: auto;
  border: none;
  margin-top: 0;
}
.my-org-table th,
.my-org-table td {
  padding: 12px 8px;
  text-align: left;
  font-size: 14px;
  border-bottom: 1px solid #f0f0f0;
  background: #fff;
  font-family: 'Inter', Arial, sans-serif;
  font-weight: 400;
  line-height: 18px;
  letter-spacing: 0.01em;
}
.my-org-table th {
  background: #f8f8f8;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: 100px;
  border-bottom: 1.5px solid #ebebeb;
}
.rounded-th-left {
  border-top-left-radius: 24px;
  border-bottom-left-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
}
.rounded-th-right {
  border-top-right-radius: 24px;
  border-bottom-right-radius: 24px;
  overflow: hidden;
  background: #f8f8f8;
}
.my-org-table td {
  color: #222;
  background: #fff;
}
.my-org-table .empty-row {
  text-align: center;
  color: #888;
  font-style: italic;
  background: #fff;
}
.icon-btn.view-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  font-size: 15px;
  color: #222;
}
.icon-btn.view-btn:hover .view-label {
  text-decoration: underline;
}
.view-icon {
  width: 18px;
  height: 18px;
  display: inline-block;
  vertical-align: middle;
}
.view-label {
  color: #222;
  text-decoration: underline;
  font-weight: 500;
  font-size: 15px;
  cursor: pointer;
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.13);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-card {
  background: #fff;
  border-radius: 22px;
  box-shadow: 0 4px 32px 0 rgba(33, 150, 243, 0.1);
  padding: 40px 48px 32px 48px;
  min-width: 600px;
  max-width: 700px;
  width: 100%;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.modal-close {
  position: absolute;
  top: 24px;
  right: 32px;
  background: none;
  border: none;
  font-size: 32px;
  color: #888;
  cursor: pointer;
  z-index: 10;
}
.modal-title {
  font-size: 26px;
  font-weight: 600;
  margin-bottom: 32px;
  color: #222;
}
.modal-form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 18px;
}
.modal-form-row {
  display: flex;
  gap: 24px;
  width: 100%;
}

.modal-form-group,
.modal-form-group.custom-dropdown {
  flex: 1 1 0;
  min-width: 0;
  background: #f6f6f6;
  border-radius: 9px;
  padding: 0 16px;
  height: 48px;
  display: flex;
  align-items: center;
  margin-bottom: 0;
  box-sizing: border-box;
}

.modal-form-group.custom-dropdown {
  flex-direction: row;
  align-items: center;
  gap: 0;
  min-height: 48px;
  width: 100%;
}
.modal-icon {
  margin-right: 10px;
  display: flex;
  align-items: center;
}
.modal-form-group input,
.modal-form-group select {
  border: none;
  background: transparent;
  outline: none;
  font-size: 16px;
  color: #222;
  width: 100%;
  height: 44px;
  padding: 0;
  font-family: inherit;
}
.modal-form-group select {
  appearance: none;
  cursor: pointer;
}
.modal-form-actions {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  margin-top: 18px;
}
.modal-save-btn {
  border-radius: 22px;
  background: #0164a5;
  color: #fff;
  font-size: 17px;
  font-weight: 500;
  padding: 10px 32px;
  border: none;
  cursor: pointer;
  transition: background 0.2s;
}
.modal-save-btn:hover {
  background: #005fa3;
}
.custom-dropdown {
  position: relative;
  width: 100%;
}
.custom-dropdown-input {
  width: 100%;
  background: #f6f6f6;
  border: none;
  border-radius: 9px;
  height: 48px;
  padding: 0;
  font-size: 16px;
  color: #222;
  display: flex;
  align-items: center;
  cursor: pointer;
  position: relative;
  box-shadow: none;
  margin-bottom: 0;
  font-family: inherit;
  font-weight: 500;
}
.custom-dropdown-input .fas {
  color: #222;
  font-size: 18px;
  margin-right: 1px;
  position: static;
  left: unset;
  top: unset;
  transform: none;
}
.custom-dropdown-input .fas.fa-chevron-down {
  margin-left: auto;
  color: #888;
}
.custom-dropdown-input span[style] {
  color: #888 !important;
  font-size: 16px;
  font-weight: 500;
  font-family: inherit;
  margin-left: 2px;
}
.selected-tags-below {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  justify-content: flex-start;
  gap: 10px 18px;
  margin-top: 10px;
  margin-bottom: 0;
  min-height: 0;
  width: 100%;
  box-sizing: border-box;
  white-space: normal;
}
.selected-tag {
  background: #f6f6f6;
  border-radius: 22px;
  padding: 8px 24px 8px 16px;
  font-size: 17px;
  color: #222;
  display: flex;
  align-items: center;
  gap: 10px;
  border: none;
  box-shadow: none;
  min-height: 32px;
  font-weight: 500;
  white-space: nowrap;
  margin-bottom: 0;
}
.selected-tag .fas {
  cursor: pointer;
  margin-left: 10px;
  color: #bbb;
  font-size: 17px;
  transition: color 0.15s;
}
.selected-tag .fas:hover {
  color: #0164a5;
}
@media (max-width: 900px) {
  .modal-form-group.custom-dropdown {
    padding: 8px 6px 12px 6px;
    min-height: 60px;
    border-radius: 14px;
  }
  .custom-dropdown-input {
    height: 38px;
    font-size: 15px;
    padding: 0 10px 0 28px;
    border-radius: 8px;
  }
  .selected-tags-below {
    gap: 8px 10px;
    min-height: 24px;
  }
  .selected-tag {
    padding: 5px 14px 5px 10px;
    font-size: 13px;
    border-radius: 12px;
    min-height: 22px;
  }
  .selected-tag .fas {
    font-size: 13px;
    margin-left: 6px;
  }
}
.dropdown-list {
  position: absolute;
  top: 54px;
  left: 0;
  width: 100%;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(33, 150, 243, 0.08);
  border: 1px solid #eee;
  z-index: 10;
  max-height: 220px;
  overflow-y: auto;
  padding: 12px 0 8px 0;
}
.dropdown-search {
  width: 92%;
  margin: 0 4% 12px 4%;
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #e0e0e0;
  font-size: 15px;
  outline: none;
  background: #f6f6f6;
}
.dropdown-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 18px;
  font-size: 15px;
  color: #222;
  cursor: pointer;
  transition: background 0.15s;
  background: #fff;
  border: none;
}
.dropdown-item:hover {
  background: #f6f6f6;
}
.dropdown-checkbox {
  width: 18px;
  height: 18px;
  border-radius: 4px;
  border: 1.5px solid #bbb;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.15s, border 0.15s;
}
.dropdown-checkbox.checked {
  background: #f6f6f6;
  border-color: #888;
}
.dropdown-checkbox.checked:after {
  content: '\2713';
  color: #888;
  font-size: 13px;
  font-weight: bold;
}
.dropdown-checkbox:after {
  content: '';
}
.custom-dropdown-input .selected-tags {
  margin-top: 0;
}
.custom-dropdown-input .selected-tag {
  background: #f6f6f6;
  color: #222;
  font-size: 15px;
  border-radius: 9px;
  padding: 2px 10px;
  margin-right: 4px;
}
.custom-dropdown-input .selected-tag .fas {
  color: #bbb;
  font-size: 13px;
  margin-left: 4px;
}
.custom-dropdown-input .selected-tag:last-child {
  margin-right: 0;
}
.custom-dropdown-input .fas.fa-chevron-down {
  position: static;
  margin-left: auto;
  color: #888;
}
@media (max-width: 1400px) {
  .my-org-table-outer {
    margin: 12px;
    max-width: 100%;
  }
  .my-org-table-card {
    max-width: 100%;
    border-radius: 14px;
  }
  .my-org-table-header {
    padding: 8px 8px 0 8px;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .my-org-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
  }
  .my-org-table th,
  .my-org-table td {
    font-size: 12px;
    padding: 8px 4px;
  }
  .rounded-th-left {
    border-top-left-radius: 14px;
    border-bottom-left-radius: 14px;
  }
  .rounded-th-right {
    border-top-right-radius: 14px;
    border-bottom-right-radius: 14px;
  }
}
@media (max-width: 900px) {
  .my-org-table-outer {
    margin: 4px;
    max-width: 100%;
  }
  .my-org-table-card {
    border-radius: 10px;
  }
  .my-org-table-header {
    padding: 8px 4px 0 4px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .my-org-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  .my-org-table th,
  .my-org-table td {
    font-size: 11px;
    padding: 6px 2px;
  }
  .rounded-th-left {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
  }
  .rounded-th-right {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
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
