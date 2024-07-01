<template>
  <section class="d-flex align-items-center min-vh-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow-lg">
            <div class="card-body">
              <h2 class="card-title text-center mb-4">Login</h2>
              <div v-if="errorMessage" class="alert alert-danger" role="alert">
                {{ errorMessage }}
              </div>
              <form @submit.prevent="login">
                <div class="mb-3">
                  <label for="inputEmail" class="form-label">Email</label>
                  <input id="inputEmail" v-model="email" type="email" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="inputPassword" class="form-label">Password</label>
                  <input id="inputPassword" v-model="password" type="password" class="form-control" required />
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
              </form>
              <div class="text-center mt-3">
                <p>Don't have an account? <router-link to="/register">Sign Up</router-link></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { useAuthStore } from '../stores/auth';

export default {
  name: "Login",
  data() {
    return {
      email: "",
      password: "",
      errorMessage: ""
    };
  },
  methods: {
    async login() {
      const authStore = useAuthStore();
      try {
        await authStore.login(this.email, this.password);
        this.$router.push('/');
      } catch (error) {
        this.errorMessage = 'Login failed: Invalid email or password';
      }
    }
  }
};
</script>

<style scoped>
.min-vh-100 {
  min-height: 100vh;
}

.card {
  border-radius: 1rem;
}
</style>
