<template>
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 280px; height: 90vh;">
        <router-link to="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4">Guest Menu</span>
        </router-link>
        <hr class="bg-secondary">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <router-link to="/guest/register-vehicle" class="nav-link text-white">Register Vehicle</router-link>
            </li>
            <li class="nav-item">
                <router-link to="/guest/vehicles" class="nav-link text-white">My Vehicles</router-link>
            </li>
            <li class="nav-item">
                <router-link to="/guest/payments" class="nav-link text-white">Payments</router-link>
            </li>
        </ul>
        <button @click="logout" class="btn btn-secondary mt-3">Logout</button>
    </div>
</template>

<script>
import { useAuthStore } from '../../stores/auth';
import { useRouter } from 'vue-router';

export default {
    name: "GuestSidebar",
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

.text-white {
    color: #ffffff !important;
}

.nav-link {
    color: #c2c7d0 !important;
    margin-bottom: 10px;
}

.nav-link:hover {
    background-color: #2c3e50 !important;
    color: #ffffff !important;
}

.btn-secondary {
    width: 100%;
}
</style>
