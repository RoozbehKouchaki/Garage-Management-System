<template>
    <section>
        <div class="container">
            <h2>Manage Parking Spots</h2>
            <div v-if="parkingStore.successMessage" class="alert alert-success alert-dismissible fade show">
                <button type="button" class="btn-close" @click="clearMessages"></button>
                {{ parkingStore.successMessage }}
            </div>
            <div v-if="parkingStore.errorMessage" class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="btn-close" @click="clearMessages"></button>
                {{ parkingStore.errorMessage }}
            </div>
            <form @submit.prevent="addParkingSpot">
                <div class="mb-3">
                    <label for="spotNumber" class="form-label">Spot Number</label>
                    <input type="text" id="spotNumber" required class="form-control" v-model="spotNumber" />
                </div>
                <button type="submit" class="btn btn-primary">Add Parking Spot</button>
            </form>
            <h3 class="mt-4">Existing Parking Spots</h3>
            <ul class="list-group">
                <li class="list-group-item" v-for="spot in parkingStore.spots" :key="spot.id">
                    {{ spot.spot_number }}
                    <button class="btn btn-sm btn-warning float-end" @click="editSpot(spot)">Edit</button>
                    <button class="btn btn-sm btn-danger float-end me-2" @click="deleteSpot(spot.id)">Delete</button>
                </li>
                <li v-if="!parkingStore.spots">No Data</li>
            </ul>
        </div>
    </section>
</template>

<script>
import { ref } from 'vue';
import { useParkingStore } from '../../stores/parking';

export default {
    name: 'AdminParking',
    setup() {
        const parkingStore = useParkingStore();
        const spotNumber = ref('');
        const editingSpot = ref(null);

        const addParkingSpot = () => {
            if (editingSpot.value) {
                parkingStore.updateParkingSpot(editingSpot.value.id, spotNumber.value);
            } else {
                parkingStore.addParkingSpot(spotNumber.value);
            }
            spotNumber.value = '';
            editingSpot.value = null;
        };

        const editSpot = (spot) => {
            spotNumber.value = spot.spot_number;
            editingSpot.value = spot;
        };

        const deleteSpot = (id) => {
            parkingStore.deleteParkingSpot(id);
        };

        const clearMessages = () => {
            parkingStore.clearMessages();
        };

        parkingStore.fetchParkingSpots();

        return {
            parkingStore,
            spotNumber,
            addParkingSpot,
            editSpot,
            deleteSpot,
            clearMessages
        };
    }
};
</script>
