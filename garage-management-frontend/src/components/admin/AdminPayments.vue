<template>
    <section>
        <div class="container">
            <h2>All Payments</h2>
            <div v-if="paymentStore.successMessage" class="alert alert-success alert-dismissible fade show">
                <button type="button" class="btn-close" @click="clearMessages"></button>
                {{ paymentStore.successMessage }}
            </div>
            <div v-if="paymentStore.errorMessage" class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="btn-close" @click="clearMessages"></button>
                {{ paymentStore.errorMessage }}
            </div>
            <div v-if="paymentStore.payments.length">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Guest Name</th>
                            <th scope="col">License Plate</th>
                            <th scope="col">Vehicle Type</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Payment Date</th>
                            <th scope="col">Is Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payment in paymentStore.payments" :key="payment.id">
                            <th scope="row">{{ payment.id }}</th>
                            <td>{{ payment.firstname }} {{ payment.lastname }}</td>
                            <td>{{ payment.license_plate }}</td>
                            <td>{{ payment.vehicle_type }}</td>
                            <td>{{ payment.amount }}</td>
                            <td>{{ payment.payment_time }}</td>
                            <td>
                                <div v-if="payment.isPaid">
                                    Paid
                                </div>
                                <div v-else>
                                    Not Paid
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <p>No payments found.</p>
            </div>
        </div>
    </section>
</template>

<script>
import { usePaymentStore } from '../../stores/payment';

export default {
    name: "AdminPayments",
    setup() {
        const paymentStore = usePaymentStore();

        const clearMessages = () => {
            paymentStore.clearMessages();
        };

        paymentStore.fetchAllPayments();

        return {
            paymentStore,
            clearMessages
        };
    }
};
</script>

<style scoped>
.table {
    margin-top: 20px;
}

.alert {
    margin-top: 20px;
}
</style>
