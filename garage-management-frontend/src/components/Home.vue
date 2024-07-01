<template>
  <div></div>
</template>

<script>
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

export default {
  name: "HomePage",
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();

    if (authStore.user) {
      if (authStore.user.role.toUpperCase() === 'ADMIN') {
        router.push('/admin');
      } else if (authStore.user.role.toUpperCase() === 'GUEST') {
        router.push('/guest');
      }
    } else {
      router.push('/login');
    }
  }
};
</script>
