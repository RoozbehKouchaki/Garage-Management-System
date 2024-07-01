<template>
    <section class="d-flex align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4">Sign Up</h2>
                            <div v-if="errorMessage" class="alert alert-danger" role="alert">
                                {{ errorMessage }}
                            </div>
                            <form @submit.prevent="register">
                                <div class="mb-3">
                                    <label for="inputFirstName" class="form-label">First Name</label>
                                    <input id="inputFirstName" v-model="firstname" type="text" class="form-control"
                                        required />
                                </div>
                                <div class="mb-3">
                                    <label for="inputLastName" class="form-label">Last Name</label>
                                    <input id="inputLastName" v-model="lastname" type="text" class="form-control"
                                        required />
                                </div>
                                <div class="mb-3">
                                    <label for="inputEmail" class="form-label">Email</label>
                                    <input id="inputEmail" v-model="email" type="email" class="form-control" required />
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword" class="form-label">Password</label>
                                    <input id="inputPassword" v-model="password" type="password" class="form-control"
                                        required />
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                            </form>
                            <div class="text-center mt-3">
                                <p>Already have an account? <router-link to="/login">Login</router-link></p>
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
    name: "Register",
    data() {
        return {
            firstname: "",
            lastname: "",
            email: "",
            password: "",
            errorMessage: ""
        };
    },
    methods: {
        async register() {
            const authStore = useAuthStore();
            try {
                await authStore.register({
                    firstname: this.firstname,
                    lastname: this.lastname,
                    email: this.email,
                    password: this.password
                });
                this.$router.push('/');
            } catch (error) {
                this.errorMessage = 'Registration failed: Email already in use or invalid data provided';
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
