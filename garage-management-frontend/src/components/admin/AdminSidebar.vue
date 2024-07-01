<template>
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 280px; height: 100vh;">
        <router-link to="/admin"
            class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4">Admin Menu</span>
        </router-link>
        <hr class="text-white">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <router-link to="/admin/parking" class="nav-link text-white">Manage Parking Spots</router-link>
            </li>
            <li class="nav-item">
                <router-link to="/admin/vehicles" class="nav-link text-white">View All Vehicles</router-link>
            </li>
            <li class="nav-item">
                <router-link to="/admin/payments" class="nav-link text-white">View All Payments</router-link>
            </li>
        </ul>
        <button @click="logout" class="btn btn-secondary mt-3">Logout</button>
    </div>
</template>

<script>
import { useAuthStore } from '../../stores/auth';
import { useRouter } from 'vue-router';

export default {
    name: "AdminSidebar",
    setup() {
        const authStore = useAuthStore();
        const router = useRouter();

        const logout = () => {
            authStore.logout();
            router.push('/login');
        };

        return { authStore, logout };
    }
};
</script>

<style scoped>
.bg-dark {
    background-color: #343a40 !important;
}

.nav-link {
    color: #f8f9fa !important;
}

.nav-link.active {
    font-weight: bold;
    background-color: #007bff;
}

.btn-secondary {
    background-color: #6c757d;
    border: none;
}
</style>