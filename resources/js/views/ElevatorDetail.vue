<!-- resources/js/views/ElevatorDetail.vue -->
<template>
  <div class="p-4 max-w-3xl mx-auto">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Revisiones {{ year }}</h1>
      <div class="flex items-center gap-2">
        <button @click="prevYear" class="px-3 py-1 border rounded">«</button>
        <div class="w-24 text-center">{{ year }}</div>
        <button @click="nextYear" class="px-3 py-1 border rounded">»</button>
        <button @click="save" class="ml-4 bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mt-6">
      <div v-for="m in 12" :key="m" class="border rounded p-4 flex items-center justify-between">
        <div class="font-medium">{{ monthName(m) }} {{ year }}</div>
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="rev.byMonth[m]" class="size-5 accent-blue-600" />
          <span>Revisado</span>
        </label>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import { useRevisionsStore } from '../stores/revisions';

const route = useRoute();
const id = computed(() => route.params.id);
const rev = useRevisionsStore();
const year = computed(() => rev.year);

onMounted(() => rev.load(id.value));

const save = () => rev.saveBulk(id.value);
const prevYear = () => rev.load(id.value, rev.year - 1);
const nextYear = () => rev.load(id.value, rev.year + 1);

const monthName = (m) => [
  '', 'Enero','Febrero','Marzo','Abril','Mayo','Junio',
  'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
][m];
</script>
