import { defineStore } from 'pinia';
import axios from '../axios';

export const useVehicleStore = defineStore('vehicle', {
    state: () => ({
        vehicle: null,
        vehicles: [],
        allocatedParking: {
            spot_number: null,
            vehicle_id: null,
            license_plate: null,
            vehicle_type: null
        },
        checkInMessage: '',
        checkInError: '',
        checkOutMessage: '',
        checkOutError: '',
        successMessage: '',
        errorMessage: ''
    }),
    actions: {
        async fetchAllVehiclesUser() {
            try {
                const response = await axios.get('/vehicles/user');
                this.vehicles = response.data.data;
                this.successMessage = response.data.message;
            } catch (error) {
                this.errorMessage = 'Failed to fetch vehicles.';
            }
        },
        async registerVehicle(licensePlate, vehicleType) {
            try {
                const response = await axios.post('/vehicle/register', {
                    license_plate: licensePlate,
                    vehicle_type: vehicleType
                });
                this.vehicle = response.data.data;
                return { success: true, message: response.data.message };
            } catch (error) {
                if (error.response && error.response.data) {
                    return { success: false, message: error.response.data.errorMessage };
                }
                return { success: false, message: 'An error occurred during vehicle registration.' };
            }
        },
        async allocateParking(vehicleId, vehicleType, licensePlate) {
            try {
                const response = await axios.post('/parking/allocate', {
                    vehicle_id: vehicleId
                });
                this.allocatedParking.spot_number = response.data.spot_number;
                this.allocatedParking.vehicle_id = vehicleId;
                this.allocatedParking.vehicle_type = vehicleType;
                this.allocatedParking.license_plate = licensePlate;
                this.vehicle.spot_number = response.data.spot_number;
                return { success: true, message: response.data.message, spot_number: response.data.spot_number };
            } catch (error) {
                if (error.response && error.response.data) {
                    return { success: false, message: error.response.data.errorMessage };
                }
                return { success: false, message: 'An error occurred during parking allocation.' };
            }
        },
        async fetchAllVehicles() {
            try {
                const response = await axios.get('/vehicles');
                this.vehicles = response.data.data;
                this.successMessage = response.data.message;
            } catch (error) {
                this.errorMessage = 'Failed to fetch all vehicles.';
            }
        },
        clearMessages() {
            this.successMessage = '';
            this.errorMessage = '';
        },
        async checkInVehicle(vehicleId) {
            try {
                const response = await axios.post('/vehicle/check-in', {
                    vehicle_id: vehicleId
                });
                this.checkInMessage = response.data.message;
                this.vehicle = response.data.data;
                this.checkInError = '';
                const allocationResponse = await this.allocateParking(vehicleId, this.vehicle.vehicle_type, this.vehicle.license_plate);
                if (!allocationResponse.success) {
                    this.checkInError = allocationResponse.message;
                    this.checkInMessage = '';
                    this.checkOutVehicle(vehicleId)
                } else {
                    this.fetchAllVehicles();
                }
            } catch (error) {
                if (error.response && error.response.data) {
                    this.checkInError = error.response.data.errorMessage;
                } else {
                    this.checkInError = 'An error occurred during check-in.';
                }
                this.checkInMessage = '';
            }
        },
        async checkOutVehicle(vehicleId) {
            try {
                const response = await axios.post('/vehicle/check-out', {
                    vehicle_id: vehicleId
                });
                this.checkOutMessage = response.data.message;
                this.vehicle = response.data.data;
                this.checkOutError = '';
                await this.fetchAllVehicles();
            } catch (error) {
                if (error.response && error.response.data) {
                    this.checkOutError = error.response.data.errorMessage;
                } else {
                    this.checkOutError = 'An error occurred during check-out.';
                }
                this.checkOutMessage = '';
            }
        },
        setVehicle(vehicle) {
            this.vehicle = vehicle;
        },
        resetState() {
            this.vehicle = null;
            this.allocatedParking = {
                spot_number: null,
                vehicle_id: null,
                license_plate: null,
                vehicle_type: null
            };
            this.checkInMessage = '';
            this.checkInError = '';
            this.checkOutMessage = '';
            this.checkOutError = '';
        },
        clearCheckInMessage() {
            this.checkInMessage = '';
        },
        clearCheckInError() {
            this.checkInError = '';
        },
        clearCheckOutMessage() {
            this.checkOutMessage = '';
        },
        clearCheckOutError() {
            this.checkOutError = '';
        }
    }
});
