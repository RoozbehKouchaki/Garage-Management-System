<template>
    <section class="container">
        <h2>My Payments</h2>
        <div v-if="payments.length" class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Time</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">License Plate</th>
                        <th scope="col">Vehicle Type</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Is Paid</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="payment in payments" :key="payment.id">
                        <td>{{ payment.payment_time }}</td>
                        <td>{{ payment.firstname }}</td>
                        <td>{{ payment.lastname }}</td>
                        <td>{{ payment.license_plate }}</td>
                        <td>{{ payment.vehicle_type }}</td>
                        <td>{{ payment.amount }}</td>
                        <td>
                          <div v-if="payment.isPaid">
                            Paid
                          </div>
                          <div v-else>
                            <button class="btn btn-primary" @click="processPayment(payment)">Pay</button>
                          </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="alert alert-warning">
            No payments found.
        </div>
    </section>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from '../../axios';
import {useVehicleStore} from "@/stores/vehicle";
import {usePaymentStore} from "@/stores/payment";

export default {
    name: "GuestPayments",
    setup() {
      const paymentStore = usePaymentStore();
      const payments = ref([]);
      const errorMessage = ref('');

      const fetchPayments = async () => {
          try {
              const response = await axios.get('/payments/user');
              payments.value = response.data.data;
          } catch (error) {
              errorMessage.value = 'Failed to fetch payments.';
          }
      };

      const processPayment = async (payment) => {
          try {
              await paymentStore.processPayment(payment);
          } catch (error) {
              errorMessage.value = 'Failed to process payment.';
          }
      };

      onMounted(() => {
          fetchPayments();
      });

      return { payments, errorMessage, processPayment};
    }
};
</script>

<style scoped>
.container {
    padding-top: 20px;
}
</style>
