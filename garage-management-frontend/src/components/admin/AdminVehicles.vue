<template>
    <section>
        <div class="container">
            <h2>All Vehicles</h2>
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
<!--            <div v-if="vehicleStore.successMessage" class="alert alert-success alert-dismissible fade show">-->
<!--                <button type="button" class="btn-close" @click="clearMessages"></button>-->
<!--                {{ vehicleStore.successMessage }}-->
<!--            </div>-->
            <div v-if="vehicleStore.errorMessage" class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="btn-close" @click="clearMessages"></button>
                {{ vehicleStore.errorMessage }}
            </div>
            <div v-if="vehicleStore.vehicles.length">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">License Plate</th>
                            <th scope="col">Vehicle Type</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Check In Time</th>
                            <th scope="col">Check Out Time</th>
                            <th scope="col">Parking Spot</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="vehicle in vehicleStore.vehicles" :key="vehicle.id">
                            <th scope="row">{{ vehicle.id }}</th>
                            <td>{{ vehicle.license_plate }}</td>
                            <td>{{ vehicle.vehicle_type }}</td>
                            <td>{{ vehicle.user_id }}</td>
                            <td>{{ vehicle.check_in_time }}</td>
                            <td>{{ vehicle.check_out_time }}</td>
                            <td>{{ vehicle.spot_number || 'N/A' }}</td>
                            <td>
                              <div v-if="vehicle.is_checked_in === 0">
                                <button @click="checkInVehicle(vehicle.id)"
                                        class="btn btn-success btn-sm">Check-In</button>
                              </div>
                              <div v-else-if="vehicle.is_checked_in === 1">
                                <button @click="checkOutVehicle(vehicle.id)"
                                        class="btn btn-warning btn-sm">Check-Out</button>
                              </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <p>No vehicles found.</p>
            </div>
        </div>
    </section>
</template>

<script>
import { useVehicleStore } from '../../stores/vehicle';

export default {
    name: "AdminVehicles",
    setup() {
        const vehicleStore = useVehicleStore();

        const clearMessages = () => {
            vehicleStore.clearMessages();
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

        vehicleStore.fetchAllVehicles();

        return {
            vehicleStore,
            clearMessages,
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
.table {
    margin-top: 20px;
}

.alert {
    margin-top: 20px;
}
</style>
