<template>
  <div class="org-admin-graphs-outer">
    <div class="org-admin-graphs-card">
      <div class="org-admin-graphs-header">
        <span class="org-admin-graphs-title">Hidden Culture</span>
        <span class="org-admin-graphs-title">Current State Graph</span>
      </div>
      <div class="org-admin-graphs-content">
        <div class="graph-section">
          <div class="graph-controls">
            <Dropdown
              mode="radio"
              :options="orgTypeOptions"
              v-model="orgType"
              style="margin-right: 18px"
            />
            <Dropdown
              mode="quarter"
              v-model:quarter="orgQuarter"
              v-model:year="orgYear"
            />
          </div>
          <Bar
            :data="hiddenCultureChartData"
            :options="chartOptions"
          />
        </div>
        <div class="graph-section">
          <div class="graph-controls">
            <Dropdown
              mode="radio"
              :options="deptTypeOptions"
              v-model="deptType"
              style="margin-right: 18px"
            />
            <Dropdown
              mode="quarter"
              v-model:quarter="deptQuarter"
              v-model:year="deptYear"
            />
          </div>
          <Bar
            :data="currentStateChartData"
            :options="chartOptions"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Bar } from 'vue-chartjs';
import {
  Chart,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
} from 'chart.js';
import { ref, h } from 'vue';
import Dropdown from '@/assets/Dropdown.vue';

Chart.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const orgType = ref('Entire Organization');
const orgQuarter = ref('Q4-2024');
const orgYear = ref(2024);
const deptType = ref('Single Department');
const deptQuarter = ref('Q2-2024');
const deptYear = ref(2024);

const orgTypeOptions = [
  { label: 'Entire Organization', value: 'Entire Organization' },
  { label: 'Single Department', value: 'Single Department' },
];
const deptTypeOptions = [
  { label: 'Single Department', value: 'Single Department' },
  { label: 'Entire Organization', value: 'Entire Organization' },
];

const hiddenCultureData = [60, 100, 40, 40, 20, 60];
const currentStateData = [80, 40, 60, 20, 30, 50];

const hiddenCultureChartData = {
  labels: ['Info 1', 'Info 2', 'Info 3', 'Info 4', 'Info 5', 'Info 6'],
  datasets: [
    {
      label: 'Hidden Culture',
      backgroundColor: '#0164A5',
      data: hiddenCultureData,
      barPercentage: 0.7,
      categoryPercentage: 0.7,
    },
  ],
};

const currentStateChartData = {
  labels: ['Info 1', 'Info 2', 'Info 3', 'Info 4', 'Info 5', 'Info 6'],
  datasets: [
    {
      label: 'Current State',
      backgroundColor: '#0164A5',
      data: currentStateData,
      barPercentage: 0.7,
      categoryPercentage: 0.7,
    },
  ],
};

const chartOptions = {
  responsive: true,
  plugins: {
    legend: { display: false },
    tooltip: { enabled: true },
  },
  scales: {
    y: {
      beginAtZero: true,
      max: 100,
      ticks: { stepSize: 20 },
    },
  },
};
</script>

<style scoped>
.org-admin-graphs-outer {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  margin: 64px auto 64px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
}
.org-admin-graphs-card {
  width: 100%;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 0 auto;
  box-sizing: border-box;
  min-width: 0;
  max-width: 1400px;
  padding: 32px 32px 24px 32px;
  display: flex;
  flex-direction: column;
  position: relative;
}
.org-admin-graphs-header {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0;
  padding: 0 0 24px 0;
}
.org-admin-graphs-title {
  font-size: 22px;
  font-weight: 600;
  flex: 1 1 0;
  text-align: left;
}
.org-admin-graphs-content {
  display: flex;
  flex-direction: row;
  gap: 32px;
  justify-content: center;
  width: 100%;
}
.graph-section {
  background: #f8f8f8;
  border-radius: 16px;
  box-shadow: 0 1px 8px rgba(33, 150, 243, 0.08);
  padding: 24px 24px 24px 24px;
  min-width: 340px;
  flex: 1 1 0;
  display: flex;
  flex-direction: column;
  align-items: stretch;
}
.graph-controls {
  display: flex;
  flex-wrap: wrap;
  gap: 32px;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 18px;
  width: 100%;
  box-sizing: border-box;
}
.graph-controls .dropdown-radio {
  width: 240px !important;
  min-width: 240px !important;
  max-width: 240px !important;
  box-sizing: border-box;
}
@media (max-width: 600px) {
  .graph-section {
    padding: 12px 4px 12px 4px;
  }
  .graph-controls {
    flex-direction: column;
    gap: 8px;
    align-items: flex-end;
    justify-content: flex-end;
    width: 100%;
    display: flex;
  }
  .graph-controls .dropdown-radio {
    width: 240px !important;
    min-width: 240px !important;
    max-width: 240px !important;
    flex-basis: unset !important;
    margin-right: 0 !important;
    align-self: flex-end;
  }
}
@media (max-width: 1400px) {
  .org-admin-graphs-card {
    border-radius: 14px;
    max-width: 100%;
    padding: 18px 8px 12px 8px;
  }
  .org-admin-graphs-content {
    gap: 18px;
  }
  .graph-section {
    border-radius: 14px;
    padding: 18px 8px 18px 8px;
    min-width: 0;
  }
}
@media (max-width: 900px) {
  .org-admin-graphs-card {
    border-radius: 10px;
    padding: 8px 2vw 8px 2vw;
  }
  .org-admin-graphs-header {
    flex-direction: column;
    gap: 8px;
    padding-bottom: 12px;
  }
  .org-admin-graphs-content {
    flex-direction: column;
    gap: 12px;
  }
  .graph-section {
    border-radius: 10px;
    padding: 8px 2vw 0 2vw;
    min-width: 0;
    width: 100%;
  }
}
</style>
