// resources/js/stores/revisions.js
import { defineStore } from 'pinia';
import { api } from '../lib/api';

export const useRevisionsStore = defineStore('revisions', {
  state: () => ({
    byMonth: {}, // { 1:true, 2:false, ... }
    year: new Date().getFullYear(),
    loading: false
  }),
  actions: {
    async load(elevatorId, year = this.year) {
      this.loading = true;
      const { data } = await api.get(`/elevators/${elevatorId}/revisions`, { params: { year } });
      const map = {};
      for (let m=1; m<=12; m++) map[m] = false;
      data.months.forEach(r => { map[r.month] = !!r.checked; });
      this.byMonth = map;
      this.year = data.year;
      this.loading = false;
    },
    async saveBulk(elevatorId) {
      await api.put(`/elevators/${elevatorId}/revisions/bulk`, {
        year: this.year,
        months: this.byMonth
      });
    }
  }
});
