import { defineStore } from 'pinia';
import axios from '../axios';

export const useParkingStore = defineStore('parking', {
    state: () => ({
        spots: [],
        successMessage: '',
        errorMessage: ''
    }),
    actions: {
        async fetchParkingSpots() {
            try {
                const response = await axios.get('/parking/spots');
                this.spots = response.data.data;
            } catch (error) {
                this.errorMessage = 'Failed to fetch parking spots.';
            }
        },
        async addParkingSpot(spotNumber) {
            try {
                const response = await axios.post('/parking/add', { spot_number: spotNumber });
                this.successMessage = response.data.message;
                this.fetchParkingSpots();
            } catch (error) {
                this.errorMessage = 'Failed to add parking spot.';
            }
        },
        async updateParkingSpot(id, spotNumber) {
            try {
                const response = await axios.put(`/parking/update?id=${id}`, { spot_number: spotNumber });
                this.successMessage = response.data.message;
                this.fetchParkingSpots();
            } catch (error) {
                this.errorMessage = 'Failed to update parking spot.';
            }
        },
        async deleteParkingSpot(id) {
            try {
                const response = await axios.delete(`/parking/delete?id=${id}`);
                this.successMessage = response.data.message;
                this.fetchParkingSpots();
            } catch (error) {
                this.errorMessage = 'Failed to delete parking spot.';
            }
        },
        clearMessages() {
            this.successMessage = '';
            this.errorMessage = '';
        }
    }
});
