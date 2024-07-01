<template>
  <nav class="navbar navbar-expand-md navbar-dark">
    <div class="container-fluid">
      <router-link to="/" class="navbar-brand">Garage Management</router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <router-link to="/" class="nav-link" active-class="active">Home</router-link>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item" v-if="!authStore.user">
            <router-link to="/login" class="nav-link" active-class="active">Login</router-link>
          </li>
          <li class="nav-item dropdown" v-if="authStore.user">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              {{ authStore.user.lastname }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li><button class="dropdown-item" @click="logout">Logout</button></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

export default {
  name: "Navigation",
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
.navbar {
  background-color: #282d33;
}

.nav-link {
  color: #f8f9fa !important;
}

.nav-link.active {
  font-weight: bold;
  border-bottom: 2px solid #f8f9fa;
}

.dropdown-menu {
  background-color: #343a40;
}

.dropdown-item {
  color: #f8f9fa;
}

.dropdown-item:hover {
  background-color: #007bff;
  color: #f8f9fa;
}
</style>
  