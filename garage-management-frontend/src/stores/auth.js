import { defineStore } from 'pinia';
import axios from '../axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || '',
  }),
  actions: {
    async login(email, password) {
      try {
        const response = await axios.post('/login', { email, password });
        this.token = response.data.token;
        localStorage.setItem('token', this.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        this.user = response.data.user;
      } catch (error) {
        throw new Error('Failed to login');
      }
    },
    async register(user) {
      try {
        const response = await axios.post('/register', user);
        this.token = response.data.token;
        localStorage.setItem('token', this.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        this.user = response.data.user;
      } catch (error) {
        throw new Error('Failed to register');
      }
    },
    logout() {
      this.user = null;
      this.token = '';
      localStorage.removeItem('token');
      delete axios.defaults.headers.common['Authorization'];
    },
  },
});
