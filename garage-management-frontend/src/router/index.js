import { createRouter, createWebHistory } from 'vue-router'

import Home from '../components/Home.vue';
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import GuestDashboard from '../components/guest/GuestDashboard.vue';
import GuestHome from '../components/guest/GuestHome.vue';
import AdminHome from '../components/admin/AdminHome.vue';
import RegisterVehicle from '../components/guest/RegisterVehicle.vue';
import MakePayment from '../components/MakePayment.vue';
import AdminParking from '../components/admin/AdminParking.vue';
import AdminVehicles from '../components/admin/AdminVehicles.vue';
import AdminPayments from '../components/admin/AdminPayments.vue';
import GuestPayments from '../components/guest/GuestPayments.vue';
import GuestVehicles from '../components/guest/GuestVehicles.vue';
import { useAuthStore } from '../stores/auth';

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  {
    path: '/guest',
    component: GuestDashboard,
    meta: { requiresAuth: true, role: 'guest' },
    children: [
      { path: '', component: GuestHome },
      { path: 'vehicles', component: GuestVehicles },
      { path: 'register-vehicle', component: RegisterVehicle },
      { path: 'payments', component: GuestPayments },
      { path: 'make-payment', component: MakePayment }
    ]
  },
  {
    path: '/admin',
    component: AdminHome,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: 'parking', name: 'AdminParking', component: AdminParking },
      { path: 'vehicles', name: 'AdminVehicles', component: AdminVehicles },
      { path: 'payments', name: 'AdminPayments', component: AdminPayments }
    ]
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!authStore.token) {
      next('/login');
    } else if (to.meta.role && authStore.user.role !== to.meta.role) {
      next('/');
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
