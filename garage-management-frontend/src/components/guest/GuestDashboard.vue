<template>
    <div class="d-flex">
        <guest-sidebar />
        <section class="container flex-grow-1">
            <router-view />
        </section>
    </div>
</template>

<script>
import GuestSidebar from './GuestSidebar.vue';

export default {
    name: "GuestDashboard",
    components: {
        GuestSidebar
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
