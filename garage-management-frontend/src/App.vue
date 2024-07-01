<template>
  <div>
    <navigation v-if="authStore.token" />
    <router-view />
  </div>
</template>

<script>
import { useAuthStore } from './stores/auth';
import Navigation from './components/Navigation.vue';

export default {
  name: "App",
  components: {
    Navigation
  },
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  }
};
</script>
