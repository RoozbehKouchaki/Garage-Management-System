<template>
    <section>
        <div class="container">
            <h2>Make Payment</h2>
            <div v-if="paymentInfo">
                <p>Total Amount to be Paid: {{ paymentInfo.amount }}</p>
                <button class="btn btn-primary" @click="processPayment">Pay Now</button>
            </div>
            <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="btn-close" @click="clearErrorMessage"></button>
                {{ errorMessage }}
            </div>
            <div v-if="successMessage" class="alert alert-success alert-dismissible fade show">
                <button type="button" class="btn-close" @click="clearSuccessMessage"></button>
                {{ successMessage }}
            </div>
        </div>
    </section>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from '../axios';
import { useVehicleStore } from '../stores/vehicle';

export default {
    name: 'MakePayment',
    setup() {
        const vehicleStore = useVehicleStore();
        const paymentInfo = ref(null);
        const errorMessage = ref('');
        const successMessage = ref('');

        const fetchPaymentInfo = async () => {
            try {
                const response = await axios.get('/payments/get');
                paymentInfo.value = response.data;
            } catch (error) {
                errorMessage.value = 'Failed to fetch payment information.';
            }
        };

        const processPayment = async () => {
            try {
                const response = await axios.post('/payments/process', { vehicle_id: vehicleStore.vehicle.id });
                successMessage.value = response.data.message;
                paymentInfo.value = null;
            } catch (error) {
                errorMessage.value = 'Failed to process payment.';
            }
        };

        const clearErrorMessage = () => {
            errorMessage.value = '';
        };

        const clearSuccessMessage = () => {
            successMessage.value = '';
        };

        onMounted(fetchPaymentInfo);

        return { paymentInfo, errorMessage, successMessage, processPayment, clearErrorMessage, clearSuccessMessage };
    }
};
</script>
