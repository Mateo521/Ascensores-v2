<!-- resources/js/views/PublicElevator.vue -->
<template>
  <div class="p-4 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-2">{{ info?.designation }}</h1>
    <div class="text-gray-600 mb-4" v-if="info?.address">{{ info.address }}</div>

    <div class="grid grid-cols-3 gap-4">
      <div v-for="m in 12" :key="m" class="border rounded p-4 flex items-center justify-between">
        <div class="font-medium">{{ monthName(m) }} {{ year }}</div>
        <div
          :class="rev[m]?.checked ? 'bg-green-500' : 'bg-gray-300'"
          class="w-5 h-5 rounded-full"
          title="Estado">
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref, computed } from 'vue';
import { useRoute } from 'vue-router';
import { api } from '../lib/api';

const route = useRoute();
const token = computed(() => route.params.token);
const info = ref(null);
const year = ref(new Date().getFullYear());
const rev = ref({});

const monthName = (m) => [
  '', 'Enero','Febrero','Marzo','Abril','Mayo','Junio',
  'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
][m];

onMounted(async () => {
  const { data: e } = await api.get(`/public/elevators/${token.value}`);
  info.value = e;
  const { data: r } = await api.get(`/public/elevators/${token.value}/revisions`, { params: { year: year.value } });
  rev.value = r.months; // {1:{checked:true}, ...}
});
</script>
