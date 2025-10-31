// resources/js/stores/elevators.js
import { defineStore } from 'pinia';
import { api } from '../lib/api';

export const useElevatorsStore = defineStore('elevators', {
  state: () => ({ list: [], loading: false }),
  actions: {
    async fetch() {
      this.loading = true;
      const { data } = await api.get('/elevators');
      this.list = data;
      this.loading = false;
    },
    async create(payload) {
      const { data } = await api.post('/elevators', payload);
      this.list.push(data);
    }
  }
});
