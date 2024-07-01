<template>
    <section class="container">
        <h2>Guest Home Page</h2>
        <p v-if="authStore.user">Welcome, {{ authStore.user.email }}</p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3" @click="goToVehicles">
                    <div class="card-body">
                        <h5 class="card-title">Total Vehicles</h5>
                        <p class="card-text">{{ vehicleStore.vehicles.length }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { useAuthStore } from '../../stores/auth';
import { useVehicleStore } from '../../stores/vehicle';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';

export default {
    name: "GuestHome",
    setup() {
        const authStore = useAuthStore();
        const vehicleStore = useVehicleStore();
        const router = useRouter();

        onMounted(() => {
            vehicleStore.fetchAllVehiclesUser();
        });

        const goToVehicles = () => {
            router.push('/guest/vehicles');
        };

        return { authStore, vehicleStore, goToVehicles };
    }
};
</script>

<style scoped>
.container {
    padding-top: 20px;
}

.card {
    cursor: pointer;
}
</style>
