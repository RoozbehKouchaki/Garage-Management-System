<template>
    <div class="d-flex">
        <admin-sidebar />
        <section class="container flex-grow-1">
            <div class="container">
                <h2>Admin Home Page</h2>
                <p v-if="authStore.user">Welcome, {{ authStore.user.email }}</p>
                <router-view />
            </div>
        </section>
    </div>
</template>

<script>
import { useAuthStore } from '../../stores/auth';
import AdminSidebar from '../admin/AdminSidebar.vue';

export default {
    name: "AdminHome",
    components: {
        AdminSidebar
    },
    setup() {
        const authStore = useAuthStore();
        return { authStore };
    }
};
</script>

<style scoped>
.d-flex {
    display: flex;
}

.flex-grow-1 {
    flex-grow: 1;
}
</style>