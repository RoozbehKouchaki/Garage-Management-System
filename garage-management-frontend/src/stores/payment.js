import { defineStore } from 'pinia';
import axios from '../axios';

export const usePaymentStore = defineStore('payment', {
    state: () => ({
        payments: [],
        successMessage: '',
        errorMessage: ''
    }),
    actions: {
        async fetchAllPayments() {
            try {
                const response = await axios.get('/payments/all');
                this.payments = response.data.data;
                this.successMessage = response.data.message;
            } catch (error) {
                this.errorMessage = 'Failed to fetch all payments.';
            }
        },
        async processPayment(payment) {
            try {
                const paymentId = payment.id;
                const response = await axios.post(`/payments/process`, { paymentId });
                this.successMessage = response.data.message;
                payment.isPaid = 1;
                await this.fetchAllPayments();
            } catch (error) {
                this.errorMessage = 'Failed to process payment.';
            }
        },
        clearMessages() {
            this.successMessage = '';
            this.errorMessage = '';
        }
    }
});
