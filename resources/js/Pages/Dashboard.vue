<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  stats: Object,
  pendientes: Array,
  ultimos: Array,
  settings: Object
});

const monthNames = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
const mesLabel = computed(() => monthNames[props.stats.month] + ' ' + props.stats.year);

// Estado de conexión
const online = ref(navigator.onLine);
const updateOnline = () => { online.value = navigator.onLine; };
onMounted(() => {
  window.addEventListener('online', updateOnline);
  window.addEventListener('offline', updateOnline);
});
onBeforeUnmount(() => {
  window.removeEventListener('online', updateOnline);
  window.removeEventListener('offline', updateOnline);
});

// Acciones rápidas
const irAscensores = () => router.visit(route('elevators.index'));
const agregarAscensor = () => router.visit(route('elevators.index')); // redirige al formulario en la parte superior
</script>

<template>
  <Head>
    <title>Panel principal</title>
    <link rel="icon" type="image/svg+xml" href="/logo.svg" />
  </Head>

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <img v-if="settings?.logo_path" :src="`/storage/${settings.logo_path}`" alt="Logo" class="h-8 w-auto rounded" />
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel principal
          </h2>
        </div>
        <div class="text-sm">
          <span
            class="px-2 py-1 rounded font-semibold"
            :class="online ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
          >
            {{ online ? 'Conectado' : 'Sin conexión' }}
          </span>
        </div>
      </div>
    </template>

    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Acciones grandes -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <button @click="irAscensores" class="w-full bg-blue-600 cursor-pointer hover:bg-blue-700 text-white font-bold py-6 px-4 rounded-lg text-xl">
            Ver Ascensores
          </button>
          <button @click="agregarAscensor" class="w-full cursor-pointer bg-green-600 hover:bg-green-700 text-white font-bold py-6 px-4 rounded-lg text-xl">
            Agregar Ascensor
          </button>
          <Link
            :href="route('elevators.index')"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-6 px-4 rounded-lg text-xl text-center"
          >
            Generar PDF QR
          </Link>
        </div>

        <!-- Indicadores -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-sm text-gray-500 mb-1">Total de Ascensores</div>
            <div class="text-4xl font-extrabold text-gray-900">{{ stats.total }}</div>
          </div>
          <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-sm text-gray-500 mb-1">Revisados ({{ mesLabel }})</div>
            <div class="text-4xl font-extrabold text-green-600">{{ stats.revisadosMes }}</div>
          </div>
          <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-sm text-gray-500 mb-1">Pendientes ({{ mesLabel }})</div>
            <div class="text-4xl font-extrabold text-red-600">{{ stats.pendientesMes }}</div>
          </div>
        </div>

        <!-- Pendientes -->
        <div class="bg-white rounded-xl shadow p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Pendientes del mes (Últimos 10)</h3>
            <Link :href="route('elevators.index')" class="text-blue-600 underline text-sm">Ver todos</Link>
          </div>
          <div v-if="(pendientes?.length ?? 0) === 0" class="text-gray-500">No hay pendientes este mes.</div>
          <ul v-else class="divide-y">
            <li v-for="e in pendientes" :key="e.id" class="py-3 flex items-center justify-between">
              <div>
                <div class="font-semibold flex text-gray-900">{{ e.designation }}</div>
                <div v-if="e.address" class="text-sm text-gray-500"><svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z"/>
</svg>
 {{ e.address }}</div>
              </div>
              <div class="flex items-center gap-2">
                <a :href="route('elevators.show', e.id)" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                  Abrir
                </a>
                <a :href="route('elevators.poster', e.id)" target="_blank" class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                  Póster QR
                </a>
              </div>
            </li>
          </ul>
        </div>

        <!-- Últimos movimientos -->
        <div class="bg-white rounded-xl shadow p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Últimas actualizaciones</h3>
          <div v-if="(ultimos?.length ?? 0) === 0" class="text-gray-500">Todavía no hay actualizaciones.</div>
          <ul v-else class="divide-y">
            <li v-for="u in ultimos" :key="u.id" class="py-3 flex items-center justify-between">
              <div>
                <div class="font-semibold text-gray-900">{{ u.elevator }}</div>
                <div class="text-sm text-gray-500">
                  {{ monthNames[u.month] }} {{ u.year }} — {{ u.checked ? 'Revisado' : 'Pendiente' }}
                </div>
              </div>
              <div class="text-sm text-gray-500">{{ u.updated_at }}</div>
            </li>
          </ul>
        </div>

        <!-- Emergencias -->
        <!--div class="bg-white rounded-xl shadow p-6 text-center">
          <div class="text-gray-600 mb-2">En caso de emergencia</div>
          <a :href="`tel:${settings?.emergency_phone ?? '2664555572'}`" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xl px-6 py-3 rounded-lg">
            📞 {{ settings?.emergency_phone ?? '266 455 5572' }}
          </a>
        </div-->

      </div>
    </div>
  </AuthenticatedLayout>
</template>
