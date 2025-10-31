<template>
  <div class="p-4 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Ascensores</h1>
    <form @submit.prevent="add" class="flex gap-2 mb-4">
      <input v-model="form.designation" placeholder="Designación" class="border p-2 rounded flex-1" />
      <input v-model="form.address" placeholder="Dirección" class="border p-2 rounded flex-1" />
      <button class="bg-blue-600 text-white px-4 py-2 rounded">Agregar</button>
    </form>
    <ul class="divide-y">
      <li v-for="e in store.list" :key="e.id" class="py-2 flex justify-between items-center">
        <div>
          <div class="font-medium">{{ e.designation }}</div>
          <div class="text-sm text-gray-500">{{ e.address }}</div>
        </div>
        <div class="flex gap-2">
          <router-link :to="`/app/elevators/${e.id}`" class="text-blue-600 underline">Abrir</router-link>
          <!-- Más acciones (PDF QR) en Fase 4 -->
        </div>
      </li>
    </ul>
  </div>
</template>
<script setup>
import { reactive, onMounted } from 'vue';
import { useElevatorsStore } from '../stores/elevators';
const store = useElevatorsStore();
const form = reactive({ designation: '', address: '' });

onMounted(() => store.fetch());
const add = async () => {
  if (!form.designation) return;
  await store.create({ designation: form.designation, address: form.address });
  form.designation = ''; form.address = '';
};
</script>
