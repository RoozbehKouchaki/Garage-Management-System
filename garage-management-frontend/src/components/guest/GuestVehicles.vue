<template>
    <section class="container">
        <h2>My Vehicles</h2>
        <button @click="goToRegisterVehicle" class="btn btn-primary mb-3">Register Vehicle</button>

        <!-- Success and Error Messages -->
        <div v-if="vehicleStore.checkInMessage" class="alert alert-success alert-dismissible fade show">
            <button type="button" class="btn-close" @click="clearCheckInMessage"></button>
            {{ vehicleStore.checkInMessage }}
        </div>
        <div v-if="vehicleStore.checkInError" class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="btn-close" @click="clearCheckInError"></button>
            {{ vehicleStore.checkInError }}
        </div>
        <div v-if="vehicleStore.checkOutMessage" class="alert alert-success alert-dismissible fade show">
            <button type="button" class="btn-close" @click="clearCheckOutMessage"></button>
            {{ vehicleStore.checkOutMessage }}
        </div>
        <div v-if="vehicleStore.checkOutError" class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="btn-close" @click="clearCheckOutError"></button>
            {{ vehicleStore.checkOutError }}
        </div>

        <div v-if="vehicleStore.vehicles.length" class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">License Plate</th>
                        <th scope="col">Vehicle Type</th>
                        <th scope="col">Check-In Time</th>
                        <th scope="col">Check-Out Time</th>
                        <th scope="col">Spot Number</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="vehicle in vehicleStore.vehicles" :key="vehicle.id">
                        <td>{{ vehicle.license_plate }}</td>
                        <td>{{ vehicle.vehicle_type }}</td>
                        <td>{{ vehicle.check_in_time || 'Not checked in' }}</td>
                        <td>{{ vehicle.check_out_time || 'Not checked out' }}</td>
                        <td>{{ vehicle.spot_number || 'N/A' }}</td>
                        <td>
                          <div v-if="vehicle.is_checked_in === 1">
                            <div class="text-success">Checked-In</div>
                          </div>
                          <div v-else-if="vehicle.is_checked_in === 0">
                            <div class="text-danger">Checked-Out</div>
                          </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="alert alert-warning">
            No vehicles registered.
        </div>
    </section>
</template>

<script>
import { useVehicleStore } from '../../stores/vehicle';
import { useRouter } from 'vue-router';

export default {
    name: "GuestVehicles",
    setup() {
        const vehicleStore = useVehicleStore();
        const router = useRouter();

        vehicleStore.fetchAllVehiclesUser();

        const goToRegisterVehicle = () => {
            router.push('/guest/register-vehicle');
        };

        const checkInVehicle = async (vehicleId) => {
            await vehicleStore.checkInVehicle(vehicleId);
        };

        const checkOutVehicle = async (vehicleId) => {
            await vehicleStore.checkOutVehicle(vehicleId);
        };

        const clearCheckInMessage = () => {
            vehicleStore.clearCheckInMessage();
        };

        const clearCheckInError = () => {
            vehicleStore.clearCheckInError();
        };

        const clearCheckOutMessage = () => {
            vehicleStore.clearCheckOutMessage();
        };

        const clearCheckOutError = () => {
            vehicleStore.clearCheckOutError();
        };

        return {
            vehicleStore,
            goToRegisterVehicle,
            checkInVehicle,
            checkOutVehicle,
            clearCheckInMessage,
            clearCheckInError,
            clearCheckOutMessage,
            clearCheckOutError
        };
    }
};
</script>

<style scoped>
.container {
    padding-top: 20px;
}
</style>
