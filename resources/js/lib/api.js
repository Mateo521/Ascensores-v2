// resources/js/lib/api.js
import axios from 'axios';

export const api = axios.create({
  baseURL: '/api',
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
});

// Opcional: interceptor para errores
api.interceptors.response.use(
  r => r,
  err => {
    console.error(err);
    return Promise.reject(err);
  }
);
