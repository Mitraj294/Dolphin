<template>
  <MainLayout>
    <div class="page">
      <div class="table-outer">
        <div class="table-card">
          <div class="billing-title">
            Billing Details (Current Subscription)
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
          <div class="billing-title">Billing History</div>
          <div class="table-container">
            <table class="table">
              <TableHeader
                :columns="[
                  { label: 'Payment Method', key: 'paymentMethod' },
                  { label: 'Payment Date', key: 'paymentDate' },
                  { label: 'Subscription End', key: 'subscriptionEnd' },
                  { label: 'Amount', key: 'amount' },
                  { label: 'PDF', key: 'pdf' },
                ]"
              />
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
                    >
                      <svg
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
                      View Receipt
                    </a>
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
                    >
                      <svg
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
                      View Receipt
                    </a>
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
                    >
                      <svg
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
                      View Receipt
                    </a>
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
                    >
                      <svg
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
                      View Receipt
                    </a>
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
import TableHeader from '@/components/Common/Common_UI/TableHeader.vue';
export default {
  name: 'BillingDetails',
  components: { MainLayout, TableHeader },
};
</script>

<style scoped>
.billing-title {
  font-size: 24px;
  font-weight: 600;
  margin: 24px;
}
.billing-plan-box {
  background: #f6f6f6;
  border-radius: 12px;
  padding: 24px 32px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0;
  box-sizing: border-box;
  margin: 24px 16px 0 16px;
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
  .billing-plan-box {
    padding: 18px 8px;
  }
}
@media (max-width: 900px) {
  .billing-plan-box {
    padding: 16px 8px;
    flex-direction: column;
    gap: 54px;
    align-items: center;
    justify-content: center;
    text-align: center;
  }
}
</style>
