// resources/js/router.js
import { createRouter, createWebHistory } from 'vue-router';
import ElevatorList from './views/ElevatorList.vue';
import ElevatorDetail from './views/ElevatorDetail.vue';
import PublicElevator from './views/PublicElevator.vue';

const routes = [
  { path: '/app/elevators', component: ElevatorList },
  { path: '/app/elevators/:id', component: ElevatorDetail, props: true },
  { path: '/p/:token', component: PublicElevator, props: true },
  { path: '/', redirect: '/app/elevators' }
];

export const router = createRouter({
  history: createWebHistory(),
  routes
});
