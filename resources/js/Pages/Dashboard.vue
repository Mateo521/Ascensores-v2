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

const monthNames = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
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
const agregarAscensor = () => router.visit(route('elevators.create'));

// Calcular porcentaje de progreso
const progressPercentage = computed(() => {
  if (props.stats.total === 0) return 0;
  return Math.round((props.stats.revisadosMes / props.stats.total) * 100);
});
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
          <div v-if="settings?.logo_path"
            class="relative p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
            <img :src="`/storage/${settings.logo_path}`" alt="Logo" class="h-6 w-auto" />
          </div>
          <h2 class="font-black text-2xl text-gray-900 leading-tight">
            Panel Principal
          </h2>
        </div>
        <div class="flex items-center gap-2">
          <div class="relative">
            <div v-if="online" class="absolute inset-0 bg-green-400 rounded-full blur-md opacity-50 animate-pulse">
            </div>
            <div class="relative px-4 py-2 rounded-xl font-bold text-sm shadow-lg transition-all"
              :class="online ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white' : 'bg-gradient-to-r from-red-500 to-rose-600 text-white'">
              <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full" :class="online ? 'bg-white animate-pulse' : 'bg-white'"></span>
                {{ online ? ' Conectado' : ' Sin conexión' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </template>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-6 sm:py-10 px-3 sm:px-4 lg:px-6">
      <!-- Patrón de fondo decorativo -->
      <div class="fixed inset-0 bg-grid-slate-200/50 pointer-events-none -z-10"></div>

      <div class="max-w-7xl mx-auto space-y-6">
        <!-- Acciones rápidas mejoradas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
          <!-- Ver Ascensores -->
          <button @click="irAscensores"
            class="group relative bg-gradient-to-br from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-black py-8 px-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 active:scale-100 overflow-hidden">
            <div
              class="absolute inset-0 bg-gradient-to-r from-blue-400/0 via-blue-400/30 to-blue-400/0 opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="relative flex flex-col items-center gap-3">
              <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
              </div>
              <span class="text-xl">Ver Ascensores</span>
            </div>
          </button>

          <!-- Agregar Ascensor -->
          <button @click="agregarAscensor"
            class="group relative bg-gradient-to-br from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white font-black py-8 px-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 active:scale-100 overflow-hidden">
            <div
              class="absolute inset-0 bg-gradient-to-r from-emerald-400/0 via-emerald-400/30 to-emerald-400/0 opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="relative flex flex-col items-center gap-3">
              <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
                </svg>
              </div>
              <span class="text-xl">Agregar Ascensor</span>
            </div>
          </button>

          <!-- Generar PDF QR -->
          <Link :href="route('elevators.index')"
            class="group relative bg-gradient-to-br from-indigo-600 to-purple-700 hover:from-indigo-700 hover:to-purple-800 text-white font-black py-8 px-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 active:scale-100 overflow-hidden">
          <div
            class="absolute inset-0 bg-gradient-to-r from-indigo-400/0 via-indigo-400/30 to-indigo-400/0 opacity-0 group-hover:opacity-100 transition-opacity">
          </div>
          <div class="relative flex flex-col items-center gap-3">
            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
              <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
              </svg>
            </div>
            <span class="text-xl">Generar PDF QR</span>
          </div>
          </Link>
        </div>

        <!-- Indicadores de estadísticas mejorados -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
          <!-- Total -->
          <div
            class="group relative bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-blue-200 hover:border-blue-400 p-6 sm:p-8 transition-all duration-300 hover:scale-105 overflow-hidden">
            <div
              class="absolute inset-0 bg-gradient-to-r from-blue-400/0 via-blue-400/10 to-blue-400/0 opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="relative flex items-center gap-4">
              <div class="flex-shrink-0 p-4 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div class="flex-1">
                <div class="text-sm font-bold text-gray-600 mb-1">Total de Ascensores</div>
                <div class="text-5xl font-black text-gray-900">{{ stats.total }}</div>
              </div>
            </div>
          </div>

          <!-- Revisados -->
          <div
            class="group relative bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-emerald-200 hover:border-emerald-400 p-6 sm:p-8 transition-all duration-300 hover:scale-105 overflow-hidden">
            <div
              class="absolute inset-0 bg-gradient-to-r from-emerald-400/0 via-emerald-400/10 to-emerald-400/0 opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="relative flex items-center gap-4">
              <div class="flex-shrink-0 p-4 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="flex-1">
                <div class="text-sm font-bold text-gray-600 mb-1">Revisados ({{ mesLabel }})</div>
                <div class="text-5xl font-black text-emerald-700">{{ stats.revisadosMes }}</div>
              </div>
            </div>
          </div>

          <!-- Pendientes -->
          <div
            class="group relative bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-amber-200 hover:border-amber-400 p-6 sm:p-8 transition-all duration-300 hover:scale-105 overflow-hidden">
            <div
              class="absolute inset-0 bg-gradient-to-r from-amber-400/0 via-amber-400/10 to-amber-400/0 opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="relative flex items-center gap-4">
              <div class="flex-shrink-0 p-4 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="flex-1">
                <div class="text-sm font-bold text-gray-600 mb-1">Pendientes ({{ mesLabel }})</div>
                <div class="text-5xl font-black text-amber-700">{{ stats.pendientesMes }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Barra de progreso global -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-white/50 p-6">
          <div class="flex items-center justify-between text-sm font-semibold mb-3">
            <span class="text-gray-700">Progreso del mes {{ mesLabel }}</span>
            <span class="text-purple-700">{{ stats.revisadosMes }} / {{ stats.total }} ascensores</span>
          </div>
          <div class="relative h-6 bg-gray-200 rounded-full overflow-hidden shadow-inner">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 rounded-full shadow-lg transition-all duration-700 ease-out"
              :style="{ width: progressPercentage + '%' }">
              <div class="absolute inset-0 bg-white/20 animate-shimmer"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
              <span class="text-xs font-bold text-white drop-shadow-lg">{{ progressPercentage }}%</span>
            </div>
          </div>
        </div>

        <!-- Pendientes del mes -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-white/50 overflow-hidden">
          <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                  <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <h3 class="text-xl font-black text-white">
                  Pendientes del mes (Últimos 10)
                </h3>
              </div>
              <Link :href="route('elevators.index')"
                class="px-4 py-2 bg-white text-amber-700 rounded-xl font-bold text-sm hover:bg-amber-50 transition-all shadow-lg">
              Ver todos
              </Link>
            </div>
          </div>

          <div class="p-6">
            <div v-if="(pendientes?.length ?? 0) === 0"
              class="text-center py-12 text-gray-500 flex flex-col items-center gap-3">
              <svg class="w-16 h-16 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-lg font-semibold"> No hay pendientes este mes.</p>
            </div>

            <div v-else class="space-y-3">
              <div v-for="e in pendientes" :key="e.id"
                class="group relative bg-gradient-to-r from-gray-50 to-white border-2 border-gray-200 hover:border-amber-400 rounded-xl p-4 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center justify-between gap-4">
                  <div class="flex-1">
                    <div class="font-black text-lg text-gray-900 mb-1">{{ e.designation }}</div>
                    <div v-if="e.address" class="flex items-center gap-2 text-sm text-gray-600">
                      <svg class="w-4 h-4 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      <span>{{ e.address }}</span>
                    </div>
                  </div>

                  <div class="flex items-center gap-2">
                    <a :href="route('elevators.show', e.id)"
                      class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold text-sm hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
                      Abrir
                    </a>
                    <a :href="route('elevators.poster', e.id)" target="_blank"
                      class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-bold text-sm hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl">
                      Póster QR
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Últimas actualizaciones -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-white/50 overflow-hidden">
          <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-black text-white">
                Últimas Actualizaciones
              </h3>
            </div>
          </div>

          <div class="p-6">
            <div v-if="(ultimos?.length ?? 0) === 0"
              class="text-center py-12 text-gray-500 flex flex-col items-center gap-3">
              <svg class="w-16 h-16 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <p class="text-lg font-semibold">Todavía no hay actualizaciones</p>
            </div>

            <div v-else class="space-y-3">
              <div v-for="u in ultimos" :key="u.id"
                class="group relative bg-gradient-to-r from-gray-50 to-white border-2 border-gray-200 hover:border-indigo-400 rounded-xl p-4 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center justify-between gap-4">
                  <div class="flex-1">
                    <div class="font-black text-lg text-gray-900 mb-1">{{ u.elevator }}</div>
                    <div class="flex items-center gap-2 text-sm">
                      <span class="px-2 py-1 rounded-lg font-semibold"
                        :class="u.checked ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'">
                        {{ monthNames[u.month] }} {{ u.year }}
                      </span>
                      <span class="px-2 py-1 rounded-lg font-semibold"
                        :class="u.checked ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                        {{ u.checked ? '✓ Revisado' : 'Pendiente' }}
                      </span>
                    </div>
                  </div>

                  <div class="text-sm text-gray-500 font-medium">
                    {{ u.updated_at }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Grid pattern para backgrounds */
.bg-grid-slate-200\/50 {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23cbd5e1' fill-opacity='0.3'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Animación de shimmer */
@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }

  100% {
    transform: translateX(100%);
  }
}

.animate-shimmer {
  animation: shimmer 2s infinite;
}

/* Mejora del scroll */
html {
  scroll-behavior: smooth;
}

/* Personalización del scrollbar */
::-webkit-scrollbar {
  width: 12px;
  height: 12px;
}

::-webkit-scrollbar-track {
  background: linear-gradient(to bottom, #f1f5f9, #e2e8f0);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #3b82f6, #6366f1);
  border-radius: 10px;
  border: 2px solid #f1f5f9;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #2563eb, #4f46e5);
}
</style>
