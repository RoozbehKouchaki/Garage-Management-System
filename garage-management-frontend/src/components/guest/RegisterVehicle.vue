<template>
    <div class="container">
        <div v-if="message" :class="{ 'alert alert-success': success, 'alert alert-danger': !success }">
            {{ message }}
            <div v-if="success && spotNumber">
                <p>Parking Spot Number: {{ spotNumber }}</p>
            </div>
        </div>
        <h2>Register Vehicle</h2>
        <form @submit.prevent="registerVehicle">
            <div class="mb-3">
                <label for="licensePlate" class="form-label">License Plate</label>
                <input type="text" class="form-control" id="licensePlate" v-model="licensePlate" required />
            </div>
            <div class="mb-3">
                <label for="vehicleType" class="form-label">Vehicle Type</label>
                <input type="text" class="form-control" id="vehicleType" v-model="vehicleType" required />
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>        
    </div>
</template>

<script>
import { useVehicleStore } from '../../stores/vehicle';

export default {
    name: "RegisterVehicle",
    data() {
        return {
            licensePlate: '',
            vehicleType: '',
            message: '',
            success: false,
            spotNumber: null
        };
    },
    methods: {
        async registerVehicle() {
            const vehicleStore = useVehicleStore();
            const response = await vehicleStore.registerVehicle(this.licensePlate, this.vehicleType);
            this.message = response.message;
            this.success = response.success;
            if (response.success) {
                this.licensePlate = '';
                this.vehicleType = '';
                this.$router.push('/guest/vehicles');
            }
        }
    }
};
</script>

<style scoped>
.container {
    padding-top: 20px;
}
</style>    