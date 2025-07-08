<template>
  <MainLayout>
    <div class="leads-table-outer">
      <div class="leads-table-card">
        <div class="leads-table-header">
          <button
            class="leads-add-btn"
            @click="$router.push('/leads/lead-capture')"
          >
            <img
              src="@/assets/images/Add.svg"
              alt="Add"
              class="leads-add-btn-icon"
            />
            Add New
          </button>
        </div>
        <div class="leads-table-header-spacer"></div>
        <div class="leads-table-container">
          <table class="leads-table">
            <thead>
              <tr>
                <th class="rounded-th-left">Contact</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Organization</th>
                <th>Size</th>
                <th>Source</th>
                <th>Status</th>
                <th>Notes</th>
                <th class="rounded-th-right"></th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(lead, idx) in paginatedLeads"
                :key="lead.email"
              >
                <td>
                  <span
                    class="lead-contact-link"
                    @click="goToLeadDetail(lead)"
                    >{{ lead.contact }}</span
                  >
                </td>
                <td>{{ lead.email }}</td>
                <td>{{ lead.phone }}</td>
                <td>{{ lead.organization }}</td>
                <td>{{ lead.size }}</td>
                <td>{{ lead.source }}</td>
                <td>{{ lead.status }}</td>
                <td>
                  <button
                    class="leads-notes-btn"
                    @click="openNotesModal(lead, idx)"
                  >
                    <span v-if="lead.notesAction === 'View'">
                      <svg
                        width="18"
                        height="18"
                        fill="none"
                        viewBox="0 0 24 24"
                        style="vertical-align: middle; margin-right: 4px"
                      >
                        <rect
                          x="3"
                          y="3"
                          width="18"
                          height="18"
                          rx="2"
                          stroke="#888"
                          stroke-width="2"
                        />
                        <path
                          d="M7 7h10M7 11h10M7 15h6"
                          stroke="#888"
                          stroke-width="2"
                        />
                      </svg>
                      View
                    </span>
                    <span v-else>
                      <svg
                        width="18"
                        height="18"
                        fill="none"
                        viewBox="0 0 24 24"
                        style="vertical-align: middle; margin-right: 4px"
                      >
                        <circle
                          cx="12"
                          cy="12"
                          r="9"
                          stroke="#888"
                          stroke-width="2"
                        />
                        <path
                          d="M12 8v8M8 12h8"
                          stroke="#888"
                          stroke-width="2"
                        />
                      </svg>
                      Add
                    </span>
                  </button>
                </td>
                <td
                  style="position: relative"
                  @click.stop
                >
                  <button
                    class="leads-menu-btn"
                    @click.stop="toggleMenu(idx)"
                  >
                    <svg
                      width="20"
                      height="20"
                      fill="none"
                      viewBox="0 0 24 24"
                    >
                      <circle
                        cx="5"
                        cy="12"
                        r="2"
                        fill="#888"
                      />
                      <circle
                        cx="12"
                        cy="12"
                        r="2"
                        fill="#888"
                      />
                      <circle
                        cx="19"
                        cy="12"
                        r="2"
                        fill="#888"
                      />
                    </svg>
                  </button>
                  <div
                    v-if="menuOpen === idx"
                    class="leads-menu custom-leads-menu"
                    @click.stop
                  >
                    <div
                      class="leads-menu-item"
                      v-for="option in customMenuOptions"
                      :key="option"
                      @click="selectCustomAction(idx, option)"
                    >
                      {{ option }}
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <Pagination
        :pageSize="pageSize"
        :pageSizes="[10, 25, 100]"
        :showPageDropdown="showPageDropdown"
        :currentPage="currentPage"
        :totalPages="totalPages"
        :paginationPages="paginationPages"
        @goToPage="goToPage"
        @selectPageSize="selectPageSize"
        @togglePageDropdown="showPageDropdown = !showPageDropdown"
      />
      <!-- Notes Modal -->
      <div
        v-if="showNotesModal"
        class="notes-modal-overlay"
        @click.self="closeNotesModal"
      >
        <div class="notes-modal">
          <h3>{{ notesModalMode === 'add' ? 'Add Notes' : 'Notes' }}</h3>
          <textarea
            v-model="notesInput"
            rows="5"
            placeholder="Enter notes here..."
            class="notes-textarea"
          ></textarea>
          <div class="notes-modal-actions">
            <button
              class="notes-modal-btn"
              @click="notesModalMode === 'add' ? submitNotes() : saveNotes()"
            >
              {{ notesModalMode === 'add' ? 'Submit' : 'Save' }}
            </button>
            <button
              class="notes-modal-btn notes-modal-cancel"
              @click="closeNotesModal"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
import Pagination from '@/components/layout/Pagination.vue';
export default {
  name: 'Leads',
  components: { MainLayout, Pagination },
  data() {
    return {
      menuOpen: null,
      customMenuOptions: [
        'Schedule Follow Up',
        'Schedule Demo',
        'Schedule Class/Training',
        'Send Assessment',
        'Send Agreement/Payment Link',
        'Convert to Client',
      ],
      leads: [
        {
          contact: 'Moss, Aaliyah',
          email: 'aaliyah@dolphin.org',
          phone: '313-586-7462',
          organization: 'Flexi-Finders',
          size: 'Large',
          source: 'Google',
          status: 'Lead Stage 1',
          notesAction: 'View',
          notes: 'Initial call completed.',
        },
        {
          contact: 'Reed, Clarence',
          email: 'clarence@dolphin.org',
          phone: '313-652-4586',
          organization: 'Interim Solutions',
          size: 'Small',
          source: 'Google',
          status: 'Lead Stage 2',
          notesAction: 'Add',
        },
        {
          contact: 'Smith, Patricia',
          email: 'patricia@dolphin.org',
          phone: '313-647-4256',
          organization: 'Talent on Demand',
          size: 'Large',
          source: 'Friend',
          status: 'Lead Stage 2',
          notesAction: 'Add',
        },
        {
          contact: 'Brucker, Mary',
          email: 'mary@dolphin.org',
          phone: '313-652-4586',
          organization: 'QuickStaff',
          size: 'Large',
          source: 'Colleague',
          status: 'Lead Stage 2',
          notesAction: 'View',
          notes: 'Waiting for documents.',
        },
        {
          contact: 'Bonds, Bonnie',
          email: 'bonnie@dolphin.org',
          phone: '313-586-7462',
          organization: 'Shifting Gears',
          size: 'Medium',
          source: 'Colleague',
          status: 'Lead Stage 3',
          notesAction: 'Add',
        },
        {
          contact: 'Gloria, Kia',
          email: 'kia@dolphin.org',
          phone: '313-652-4586',
          organization: 'Ready Workforce',
          size: 'Small',
          source: 'Friend',
          status: 'Lead Stage 1',
          notesAction: 'Add',
        },
        {
          contact: 'Doe, Daisy',
          email: 'daisy@dolphin.org',
          phone: '313-647-4256',
          organization: 'On-Call Crew',
          size: 'Small',
          source: 'Friend',
          status: 'Lead Stage 1',
          notesAction: 'View',
          notes: 'Requested pricing.',
        },
        {
          contact: 'Smith, John',
          email: 'john@dolphin.org',
          phone: '313-652-4586',
          organization: 'Peak Personnel',
          size: 'Large',
          source: 'Google',
          status: 'Lead Stage 1',
          notesAction: 'View',
          notes: 'Demo scheduled for next week.',
        },
        {
          contact: 'Dallas, David',
          email: 'david@dolphin.org',
          phone: '313-586-7462',
          organization: 'Project People',
          size: 'Large',
          source: 'Google',
          status: 'Lead Stage 3',
          notesAction: 'Add',
        },
        {
          contact: 'Moss, Bella',
          email: 'bella@dolphin.org',
          phone: '313-652-4586',
          organization: 'Agile Staffing',
          size: 'Large',
          source: 'Google',
          status: 'Lead Stage 2',
          notesAction: 'Add',
        },
      ],
      pageSize: 10,
      currentPage: 1,
      showPageDropdown: false,
      showNotesModal: false,
      notesModalMode: 'add', // 'add' or 'view'
      notesInput: '',
      currentLead: null,
      currentLeadIdx: null,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.leads.length / this.pageSize) || 1;
    },
    paginatedLeads() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.leads.slice(start, start + this.pageSize);
    },
    paginationPages() {
      // Show 1, 2, 3, ..., 8, 9, 10 (with ellipsis in the middle)
      const total = this.totalPages;
      if (total <= 7) {
        return Array.from({ length: total }, (_, i) => i + 1);
      } else {
        const pages = [1];
        if (this.currentPage > 4) pages.push('...');
        for (
          let i = Math.max(2, this.currentPage - 1);
          i <= Math.min(total - 1, this.currentPage + 1);
          i++
        ) {
          pages.push(i);
        }
        if (this.currentPage < total - 3) pages.push('...');
        pages.push(total);
        return pages;
      }
    },
  },
  methods: {
    toggleMenu(idx) {
      this.menuOpen = this.menuOpen === idx ? null : idx;
      this.$nextTick(() => {
        if (this.menuOpen !== null) {
          const btns = document.querySelectorAll('.leads-menu-btn');
          const btn = btns[idx];
          const menus = document.querySelectorAll(
            '.leads-menu.custom-leads-menu'
          );
          const menu = Array.from(menus).find((m) => m.offsetParent !== null);
          if (btn && menu) {
            const rect = btn.getBoundingClientRect();
            const scrollY = window.scrollY || window.pageYOffset;
            const left = rect.left;
            const top = rect.bottom + scrollY + 4;
            menu.style.position = 'fixed';
            menu.style.left = `${left}px`;
            menu.style.top = `${top}px`;
            menu.style.right = '';
            menu.style.minWidth = '240px';
            menu.style.maxWidth = '320px';
          }
        }
      });
    },
    selectCustomAction(idx, option) {
      this.menuOpen = null;
      const lead = this.leads[idx];
      if (option === 'Send Assessment') {
        // Pass all lead details as query params
        const query = {
          contact: lead.contact,
          email: lead.email,
          phone: lead.phone,
          organization: lead.organization,
          size: lead.size,
          source: lead.source,
          status: lead.status,
        };
        if (this.$route.path.startsWith('/assessments')) {
          this.$router.push({ path: '/assessments/send-assessment', query });
        } else {
          this.$router.push({ path: '/leads/send-assessment', query });
        }
        return;
      }
      if (option === 'Schedule Demo') {
        this.$router.push('/leads/schedule-demo');
        return;
      }
      if (option === 'Schedule Class/Training') {
        this.$router.push('/leads/schedule-class-training');
        return;
      }
      this.$set(this.leads[idx], 'selectedCustomAction', option);
    },
    handleGlobalClick(e) {
      if (this.menuOpen !== null) {
        let menu = document.querySelector('.leads-menu.custom-leads-menu');
        let btns = document.querySelectorAll('.leads-menu-btn');
        if (menu && menu.contains(e.target)) return;
        for (let btn of btns) {
          if (btn.contains(e.target)) return;
        }
        this.menuOpen = null;
      }
    },
    goToLeadDetail(lead) {
      this.$router.push({
        name: 'LeadDetail',
        params: { email: lead.email },
        query: { ...lead },
      });
    },
    goToPage(page) {
      if (page === '...' || page < 1 || page > this.totalPages) return;
      this.currentPage = page;
    },
    selectPageSize(size) {
      this.pageSize = size;
      this.currentPage = 1;
      this.showPageDropdown = false;
    },
    openNotesModal(lead, idx) {
      this.currentLead = lead;
      this.currentLeadIdx = idx + (this.currentPage - 1) * this.pageSize;
      if (lead.notesAction === 'View') {
        this.notesModalMode = 'view';
      } else {
        this.notesModalMode = 'add';
      }
      this.notesInput = lead.notes || '';
      this.showNotesModal = true;
    },
    submitNotes() {
      // Logic to save notes for add
      const lead = this.leads[this.currentLeadIdx];
      lead.notes = this.notesInput;
      lead.notesAction = 'View';
      this.closeNotesModal();
    },
    saveNotes() {
      // Logic to save notes for view/edit
      const lead = this.leads[this.currentLeadIdx];
      lead.notes = this.notesInput;
      // notesAction remains 'View'
      this.closeNotesModal();
    },
    closeNotesModal() {
      this.showNotesModal = false;
      this.notesInput = '';
      this.currentLead = null;
      this.currentLeadIdx = null;
    },
  },
  mounted() {
    document.addEventListener('click', this.handleGlobalClick);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleGlobalClick);
  },
};
</script>

<style scoped>
/* --- Layout and spacing to match OrganizationTable.vue --- */
.leads-table-outer {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
}

.leads-table-card {
  width: 100%;
  background: #fff;
  border-radius: 24px; /* was 18px */
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  overflow: visible;
  margin: 0 auto;
  box-sizing: border-box;
  min-width: 0;
  max-width: 1400px;
}

.leads-table-header {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 24px 46px 0 24px;
  background: #fff;
  border-top-left-radius: 24px; /* was 18px */
  border-top-right-radius: 24px; /* was 18px */
  min-height: 64px;
  box-sizing: border-box;
}

.leads-table-header-spacer {
  height: 18px;
  width: 100%;
  background: transparent;
  display: block;
}

.leads-table-container {
  width: 100%;
  overflow-x: auto;
  box-sizing: border-box;
  padding: 0 24px 24px 24px;
  background: #fff;
  border-bottom-left-radius: 24px; /* was 18px */
  border-bottom-right-radius: 24px; /* was 18px */
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.leads-table-container::-webkit-scrollbar {
  display: none;
}

.leads-table {
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

.leads-table th,
.leads-table td {
  padding: 12px 8px;
  text-align: left;
  font-size: 14px;
  border-bottom: 1px solid #f0f0f0;
  background: #fff;
}

.leads-table th {
  background: #f8f8f8;
  font-family: 'Inter', Arial, sans-serif;
  font-size: 14px;
  font-weight: 600;
  color: #888;
  position: relative;
  vertical-align: middle;
  min-width: 100px;
  border-bottom: 1.5px solid #ebebeb;
  height: 44px;
  line-height: 44px;
  padding: 0 8px;
  letter-spacing: 0.01em;
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

/* --- Responsive: shrink margin and font on small screens --- */
@media (max-width: 1400px) {
  .leads-table-outer {
    margin: 12px;
    max-width: 100%;
  }
  .leads-table-card {
    border-radius: 14px; /* was 10px */
    max-width: 100%;
  }
  .leads-table-header {
    padding: 8px 8px 0 8px;
    border-top-left-radius: 14px; /* was 10px */
    border-top-right-radius: 14px; /* was 10px */
  }
  .leads-table-container {
    padding: 0 8px 8px 8px;
    border-bottom-left-radius: 14px; /* was 10px */
    border-bottom-right-radius: 14px; /* was 10px */
  }
  .leads-table th,
  .leads-table td {
    font-size: 12px;
    padding: 8px 4px;
  }
  .leads-table th {
    height: 40px;
    line-height: 40px;
    padding: 0 4px;
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
  .leads-table-outer {
    margin: 4px;
    max-width: 100%;
  }
  .leads-table-card {
    border-radius: 10px; /* was 6px */
  }
  .leads-table-header {
    padding: 8px 4px 0 4px;
    border-top-left-radius: 10px; /* was 6px */
    border-top-right-radius: 10px; /* was 6px */
  }
  .leads-table-container {
    padding: 0 4px 4px 4px;
    border-bottom-left-radius: 10px; /* was 6px */
    border-bottom-right-radius: 10px; /* was 6px */
  }
  .leads-table th,
  .leads-table td {
    font-size: 11px;
    padding: 6px 2px;
  }
  .leads-table th {
    height: 36px;
    line-height: 36px;
    padding: 0 2px;
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

/* --- Other styles (unchanged) --- */
.leads-notes-btn {
  background: #fff;
  border: 1.5px solid #e0e0e0;
  border-radius: 24px;
  padding: 4px 12px;
  font-size: 14px;
  color: #222;
  cursor: pointer;
  transition: border 0.2s;
  display: flex;
  align-items: center;
  font-weight: 500;
  gap: 4px;
}

.leads-notes-btn:hover {
  border: 1.5px solid #0074c2;
}

.leads-menu-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 2px;
  border-radius: 50%;
  transition: background 0.2s;
}

.leads-menu-btn:hover {
  background: #f0f0f0;
}

.leads-menu.custom-leads-menu {
  position: fixed;
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(33, 150, 243, 0.08);
  min-width: 180px;
  z-index: 2000;
  display: flex;
  flex-direction: column;
  padding: 4px 0;
}

.leads-menu-item {
  padding: 8px 12px;
  font-size: 14px;
  color: #222;
  cursor: pointer;
  transition: background 0.2s;
}

.leads-menu-item:hover {
  background: #f0f8ff;
}

.leads-add-btn {
  border-radius: 29.01px;
  background: #0164a5;
  color: #fff;
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

.leads-add-btn:hover {
  background: #005fa3;
  color: #fff;
}

.leads-add-btn-icon {
  width: 18px;
  height: 18px;
  margin-right: 6px;
  display: inline-block;
  vertical-align: middle;
}

/* --- Notes Modal (unchanged) --- */
.notes-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.25);
  z-index: 3000;
  display: flex;
  align-items: center;
  justify-content: center;
}
.notes-modal {
  background: #fff;
  border-radius: 12px;
  padding: 32px 32px 24px 32px; /* Equal padding on all sides */
  min-width: 480px; /* Increased from 340px */
  max-width: 600px; /* Set a reasonable max width */
  box-shadow: 0 4px 32px rgba(0, 0, 0, 0.12);
  display: flex;
  flex-direction: column;
  align-items: stretch; /* changed from center to stretch for full width */
}
.notes-modal h3 {
  margin-top: 0;
  margin-bottom: 18px;
  text-align: center;
  font-size: 20px;
  font-weight: 600;
}
.notes-textarea {
  width: 100%;
  box-sizing: border-box;
  min-height: 100px;
  border-radius: 8px;
  border: 1.5px solid #e0e0e0;
  padding: 14px 16px;
  font-size: 15px;
  margin-bottom: 18px;
  resize: vertical;
  display: block;
  /* Remove min-width/max-width for full modal width */
}
.notes-modal-actions {
  display: flex;
  gap: 12px;
  justify-content: center;
  margin-top: 8px;
}
.notes-modal-btn {
  background: #0164a5;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 8px 20px;
  font-size: 15px;
  font-weight: 500;

  transition: background 0.2s;
}
.notes-modal-btn:hover {
  background: #005fa3;
}
.notes-view-content {
  padding: 12px;
  background: #f8f8f8;
  border-radius: 8px;
  font-size: 15px;
  color: #222;
  margin-bottom: 12px;
  min-width: 200px;
}
.notes-view-empty {
  color: #888;
  font-size: 15px;
  padding: 12px;
  background: #f8f8f8;
  border-radius: 8px;
  margin-bottom: 12px;
  min-width: 200px;
  text-align: center;
}
.lead-contact-link {
  cursor: pointer;
  font-weight: 500;
}
</style>
