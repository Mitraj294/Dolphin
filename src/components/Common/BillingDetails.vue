<template>
  <MainLayout>
    <div class="page">
      <div class="billing-table-outer">
        <div class="billing-table-card">
          <div class="billing-table-header">
            <div class="billing-title">
              Billing Details (Current Subscription)
            </div>
          </div>
          <div class="billing-plan-box">
            <div>
              <div class="plan-name">Standard</div>
              <div class="plan-price">$2500/annual</div>
            </div>
            <div class="plan-meta">
              <div>Subscription Start : <b>March 18, 2025</b></div>
              <div>Subscription End : <b>March 18, 2026</b></div>
              <div class="plan-next">(Next bill on March 19, 2026)</div>
            </div>
          </div>
          <div class="billing-history-title">Billing History</div>
          <div class="billing-history-table-container">
            <table class="billing-history-table">
              <thead>
                <tr>
                  <th>Payment Method</th>
                  <th>Payment Date</th>
                  <th>Subscription End</th>
                  <th>Amount</th>
                  <th>PDF</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Paypal<br />jack@test.com</td>
                  <td>Mar 4, 2025</td>
                  <td>Mar 4, 2026</td>
                  <td>$2500.00</td>
                  <td>
                    <a
                      href="#"
                      class="receipt-link"
                      ><svg
                        width="16"
                        height="16"
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
                          stroke="#0074c2"
                          stroke-width="2"
                        />
                        <path
                          d="M7 7h10M7 11h10M7 15h6"
                          stroke="#0074c2"
                          stroke-width="2"
                        />
                      </svg>
                      View Receipt</a
                    >
                  </td>
                </tr>
                <tr>
                  <td>Credit Card<br />4580..........</td>
                  <td>Feb 26, 2025</td>
                  <td>Feb 26, 2026</td>
                  <td>$2500.00</td>
                  <td>
                    <a
                      href="#"
                      class="receipt-link"
                      ><svg
                        width="16"
                        height="16"
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
                          stroke="#0074c2"
                          stroke-width="2"
                        />
                        <path
                          d="M7 7h10M7 11h10M7 15h6"
                          stroke="#0074c2"
                          stroke-width="2"
                        />
                      </svg>
                      View Receipt</a
                    >
                  </td>
                </tr>
                <tr>
                  <td>Paypal<br />jack@test.com</td>
                  <td>Feb 22, 2025</td>
                  <td>Feb 22, 2026</td>
                  <td>$2500.00</td>
                  <td>
                    <a
                      href="#"
                      class="receipt-link"
                      ><svg
                        width="16"
                        height="16"
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
                          stroke="#0074c2"
                          stroke-width="2"
                        />
                        <path
                          d="M7 7h10M7 11h10M7 15h6"
                          stroke="#0074c2"
                          stroke-width="2"
                        />
                      </svg>
                      View Receipt</a
                    >
                  </td>
                </tr>
                <tr>
                  <td>Paypal<br />jack@test.com</td>
                  <td>Jan 4, 2025</td>
                  <td>Jan 4, 2026</td>
                  <td>$2500.00</td>
                  <td>
                    <a
                      href="#"
                      class="receipt-link"
                      ><svg
                        width="16"
                        height="16"
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
                          stroke="#0074c2"
                          stroke-width="2"
                        />
                        <path
                          d="M7 7h10M7 11h10M7 15h6"
                          stroke="#0074c2"
                          stroke-width="2"
                        />
                      </svg>
                      View Receipt</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/components/layout/MainLayout.vue';
export default {
  name: 'BillingDetails',
  components: { MainLayout },
};
</script>

<style scoped>
.billing-table-outer {
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
.billing-table-card {
  width: 100%;
  max-width: 1400px;
  min-width: 0;
  background: #fff;
  border-radius: 24px;
  border: 1px solid #ebebeb;
  box-shadow: 0 2px 16px 0 rgba(33, 150, 243, 0.04);
  margin: 0 auto;
  box-sizing: border-box;
  padding: 32px 32px 24px 32px;
  display: flex;
  flex-direction: column;
  gap: 32px;
  position: relative;
}
.billing-table-header {
  width: 100%;
  display: flex;
  align-items: center;
  padding: 0 0 18px 0;
  background: #fff;
  border-top-left-radius: 24px;
  border-top-right-radius: 24px;
  min-height: 0;
  box-sizing: border-box;
}
.billing-title {
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 0;
}
.billing-plan-box {
  background: #f6f6f6;
  border-radius: 12px;
  padding: 24px 32px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0;
}
.plan-name {
  font-size: 22px;
  font-weight: 500;
  margin-bottom: 8px;
}
.plan-price {
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 0;
}
.plan-meta {
  text-align: right;
  color: #888;
  font-size: 17px;
  font-weight: 400;
}
.plan-meta b {
  color: #222;
  font-weight: 500;
}
.plan-next {
  font-size: 15px;
  color: #bdbdbd;
  margin-top: 4px;
}
.billing-history-title {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 12px;
}
.billing-history-table-container {
  width: 100%;
  overflow-x: auto;
  box-sizing: border-box;
  padding: 0;
  background: #fff;
  border-radius: 0 0 24px 24px;
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.billing-history-table-container::-webkit-scrollbar {
  display: none;
}
.billing-history-table {
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
.billing-history-table th,
.billing-history-table td {
  padding: 16px 12px;
  text-align: left;
  font-size: 17px;
  border-bottom: 1px solid #f0f0f0;
  background: #fff;
}
.billing-history-table th {
  background: #f8f8f8;
  font-family: 'Inter', Arial, sans-serif;
  font-size: 18px;
  font-weight: 700;
  color: #bdbdbd;
  position: relative;
  vertical-align: middle;
  min-width: 100px;
  border-bottom: 1.5px solid #ebebeb;
  height: 44px;
  line-height: 44px;
  padding: 0 8px;
  letter-spacing: 0.01em;
}
.billing-history-table td {
  font-family: 'Inter', Arial, sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 18px;
  letter-spacing: 0.01em;
  color: #222;
  padding-left: 0;
  background: #fff;
}
.receipt-link {
  color: #0074c2;
  text-decoration: underline;
  font-size: 17px;
  display: flex;
  align-items: center;
  gap: 4px;
}

/* Responsive styles to match other pages */
@media (max-width: 1400px) {
  .billing-table-outer {
    margin: 12px;
    max-width: 100%;
  }
  .billing-table-card {
    max-width: 100%;
    border-radius: 14px;
    padding: 18px 8px 12px 8px;
  }
  .billing-table-header {
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .billing-plan-box {
    padding: 18px 8px;
  }
  .billing-history-table-container {
    border-radius: 0 0 14px 14px;
  }
  .billing-history-table th,
  .billing-history-table td {
    font-size: 12px;
    height: 40px;
    line-height: 40px;
    padding: 0 4px;
  }
  .billing-history-table th {
    padding-top: 0;
    padding-bottom: 0;
  }
}
@media (max-width: 900px) {
  .billing-table-outer {
    margin: 4px;
    max-width: 100%;
  }
  .billing-table-card {
    border-radius: 10px;
    padding: 8px 2vw 8px 2vw;
  }
  .billing-table-header {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .billing-plan-box {
    padding: 8px 4px;
    flex-direction: column;
    gap: 8px;
  }
  .billing-history-table-container {
    border-radius: 0 0 10px 10px;
  }
  .billing-history-table th,
  .billing-history-table td {
    font-size: 11px;
    height: 36px;
    line-height: 36px;
    padding: 0 2px;
  }
  .billing-history-table th {
    padding-top: 0;
    padding-bottom: 0;
  }
}
</style>
