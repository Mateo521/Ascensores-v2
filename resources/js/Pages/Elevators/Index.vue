<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';

const toDelete = ref(null);
const showDelete = ref(false);
const searchQuery = ref('');
const isSearching = ref(false);

const openDelete = (elevator) => {
  toDelete.value = elevator;
  showDelete.value = true;
};

const closeDelete = () => {
  showDelete.value = false;
  toDelete.value = null;
};

const onDeleted = () => {
  router.reload({ only: ['elevators'], preserveScroll: true, preserveState: true });
  closeDelete();
};

const props = defineProps({
  elevators: Object,
  filters: Object
});

const form = useForm({
  designation: '',
  address: '',
  is_active: true
});

const submit = () => {
  form.post(route('elevators.store'), {
    onSuccess: () => {
      form.reset();
      showSuccessNotification();
    }
  });
};

const changePerPage = (e) => {
  const per = Number(e.target.value);
  router.get(route('elevators.index'), { per_page: per }, { preserveScroll: true, preserveState: true });
};

const translatePaginationLabel = (label) => {
  const translations = {
    'Previous': '« Anterior',
    'Next': 'Siguiente »',
    '« Previous': '« Anterior',
    'Next »': 'Siguiente »'
  };
  return translations[label] || label;
};

// Computed para estadísticas
const stats = computed(() => ({
  total: props.elevators?.total ?? 0,
  perPage: props.elevators?.per_page ?? 10,
  currentPage: props.elevators?.current_page ?? 1
}));

// Función para copiar token
const copyToken = (token) => {
  navigator.clipboard.writeText(token);
  // Aquí podrías agregar una notificación toast
};

const showSuccessNotification = () => {
  // Implementar notificación toast
};
</script>

<template>

  <Head title="Ascensores" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="relative">
            <div
              class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl blur-lg opacity-50 animate-pulse">
            </div>
            <div class="relative p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl">
              <svg class="w-7 h-7 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
              </svg>
            </div>
          </div>
          <div>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
              Gestión de Ascensores
            </h2>
            <p class="text-sm text-gray-500 mt-0.5">
              Administra y supervisa todos tus ascensores
            </p>
          </div>
        </div>

        <!-- Stats rápidos -->
        <div class="flex gap-3">
          <div class="px-4 py-2 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-100">
            <p class="text-xs font-medium text-gray-600">Total</p>
            <p class="text-2xl font-bold text-blue-600">{{ stats.total }}</p>
          </div>
          <div class="px-4 py-2 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border-2 border-emerald-100">
            <p class="text-xs font-medium text-gray-600">Página</p>
            <p class="text-2xl font-bold text-emerald-600">{{ stats.currentPage }}</p>
          </div>
        </div>
      </div>
    </template>

    <div class="py-6 sm:py-8 lg:py-12 px-3 sm:px-4 lg:px-6">
      <div class="max-w-7xl mx-auto space-y-6">

        <!-- Formulario mejorado con animaciones -->
        <div
          class="group bg-white overflow-hidden shadow-2xl sm:rounded-3xl border-2 border-gray-100 hover:border-blue-200 transition-all duration-300">
          <!-- Header del formulario -->
          <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-6 py-5">
            <div class="absolute inset-0 bg-grid-white/10 [mask-image:linear-gradient(0deg,transparent,black)]"></div>
            <div class="relative flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 backdrop-blur-sm rounded-xl">
                  <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-white">
                    Agregar Nuevo Ascensor
                  </h3>
                  <p class="text-xs text-blue-100">Completa la información básica del ascensor</p>
                </div>
              </div>
              <div class="hidden sm:flex items-center gap-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-xs text-white font-medium">Sistema activo</span>
              </div>
            </div>
          </div>

          <div class="p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="submit" class="space-y-6">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Designación mejorado -->
                <div class="space-y-2 animate-fade-in">
                  <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Designación
                    <span class="px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded">Requerido</span>
                  </label>
                  <div class="relative group">
                    <div
                      class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl opacity-0 group-hover:opacity-10 transition duration-300 blur">
                    </div>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors"
                          xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                      </div>
                      <input v-model="form.designation" type="text"
                        class="w-full pl-12 pr-4 py-3.5 text-sm bg-gray-50 border-2 border-gray-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl transition-all duration-300"
                        placeholder="Ej: UNSL - Rectorado - Ascensor 1" required />
                      <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <div v-if="form.designation" class="text-green-500">
                          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                  <transition enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 transform scale-95" enter-to-class="opacity-100 transform scale-100"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100 transform scale-100" leave-to-class="opacity-0 transform scale-95">
                    <div v-if="form.errors.designation"
                      class="flex items-center gap-2 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                      <svg class="w-5 h-5 text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span class="text-red-700 text-sm font-medium">{{ form.errors.designation }}</span>
                    </div>
                  </transition>
                </div>

                <!-- Dirección mejorado -->
                <div class="space-y-2 animate-fade-in" style="animation-delay: 0.1s;">
                  <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <svg class="w-4 h-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Dirección
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded">Opcional</span>
                  </label>
                  <div class="relative group">
                    <div
                      class="absolute -inset-0.5 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl opacity-0 group-hover:opacity-10 transition duration-300 blur">
                    </div>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transition-colors"
                          xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                      </div>
                      <input v-model="form.address" type="text"
                        class="w-full pl-12 pr-4 py-3.5 text-sm bg-gray-50 border-2 border-gray-200 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 rounded-xl transition-all duration-300"
                        placeholder="Ej: Av. Ejército de los Andes 950" />
                      <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <div v-if="form.address" class="text-green-500">
                          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Botones y controles mejorados -->
              <div
                class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 pt-4 border-t-2 border-gray-100">
                <button type="submit" :disabled="form.processing"
                  class="group relative flex-1 sm:flex-none inline-flex items-center justify-center gap-3 overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 text-white font-bold text-sm py-4 px-8 rounded-xl shadow-xl hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:-translate-y-1 active:scale-95">
                  <div
                    class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700">
                  </div>
                  <svg class="w-5 h-5 relative z-10 transition-transform group-hover:rotate-90 duration-300"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  <span class="relative z-10">{{ form.processing ? 'Agregando...' : 'Agregar Ascensor' }}</span>
                  <div v-if="form.processing" class="relative z-10">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                      viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                      </path>
                    </svg>
                  </div>
                </button>

                <!-- Selector per_page mejorado -->
                <div
                  class="flex items-center justify-center sm:justify-end gap-3 px-5 py-3 bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl border-2 border-gray-200 hover:border-gray-300 transition-colors">
                  <svg class="w-5 h-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                  </svg>
                  <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                    Por página:
                  </label>
                  <select
                    class="text-sm font-medium border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 bg-white shadow-sm hover:shadow-md transition-all cursor-pointer"
                    :value="filters?.per_page ?? 10" @change="changePerPage">
                    <option :value="5">5</option>
                    <option :value="10">10</option>
                    <option :value="15">15</option>
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Lista de ascensores mejorada -->
        <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border-2 border-gray-100">
          <!-- Header de la lista -->
          <div class="relative overflow-hidden bg-gradient-to-r from-slate-700 via-gray-800 to-slate-900 px-6 py-5">
            <div class="absolute inset-0 bg-grid-white/5"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-white/10 backdrop-blur-sm rounded-xl">
                  <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-white">
                    Ascensores Registrados
                  </h3>
                  <p class="text-xs text-gray-300">{{ stats.total }} ascensor{{ stats.total !== 1 ? 'es' : '' }} en
                    total
                  </p>
                </div>
              </div>

              <!-- Barra de búsqueda (opcional para futuro) -->
              <!--div class="relative max-w-xs w-full">
                <input v-model="searchQuery" type="text" placeholder="Buscar ascensor..."
                  class="w-full pl-10 pr-4 py-2 bg-white/10 border-2 border-white/20 text-white placeholder-gray-400 rounded-xl focus:bg-white/20 focus:border-white/40 focus:ring-4 focus:ring-white/10 transition-all" />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
              </div-->
            </div>
          </div>

          <div class="p-4 sm:p-6">
            <!-- Estado vacío mejorado -->
            <transition enter-active-class="transition ease-out duration-300"
              enter-from-class="opacity-0 transform scale-95" enter-to-class="opacity-100 transform scale-100">
              <div v-if="(elevators?.data?.length ?? 0) === 0" class="text-center py-20 px-4">
                <div class="relative inline-block mb-6">
                  <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-200 to-indigo-200 rounded-full blur-2xl opacity-50 animate-pulse">
                  </div>
                  <div
                    class="relative inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 shadow-xl">
                    <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                  </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No hay ascensores registrados</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-md mx-auto">
                  Comienza agregando tu primer ascensor usando el formulario de arriba.
                </p>
                <div
                  class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl">
                  <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="text-sm font-medium text-blue-800">Tip: Completa todos los campos para un mejor
                    registro</span>
                </div>
              </div>
            </transition>

            <!-- Lista de tarjetas con animación stagger -->
            <!-- Estado vacío -->
            <div v-if="(elevators?.data?.length ?? 0) === 0" class="text-center py-20 px-4">
              <div class="relative inline-block mb-6">
                <div
                  class="absolute inset-0 bg-gradient-to-br from-blue-200 to-indigo-200 rounded-full blur-2xl opacity-50 animate-pulse">
                </div>
                <div
                  class="relative inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 shadow-xl">
                  <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                </div>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2">No hay ascensores registrados</h3>
              <p class="text-gray-500 text-sm mb-6 max-w-md mx-auto">
                Comienza agregando tu primer ascensor usando el formulario de arriba. Es rápido y sencillo.
              </p>
              <div
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl">
                <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-blue-800">Tip: Completa todos los campos para un mejor
                  registro</span>
              </div>
            </div>

            <!-- Lista de tarjetas -->
            <transition-group v-else name="list" tag="div" class="space-y-4">
              <div v-for="(elevator, index) in elevators.data" :key="elevator.id"
                :style="{ 'animation-delay': `${index * 50}ms` }"
                class="group relative border-2 border-gray-100 rounded-2xl p-5 sm:p-6 hover:border-blue-300 hover:shadow-2xl transition-all duration-500 bg-gradient-to-br from-white via-gray-50 to-white animate-fade-in-up">
                <!-- Efecto de brillo al hover -->
                <div
                  class="absolute inset-0 bg-gradient-to-r from-blue-500/0 via-indigo-500/0 to-purple-500/0 group-hover:from-blue-500/5 group-hover:via-indigo-500/5 group-hover:to-purple-500/5 rounded-2xl transition-all duration-500">
                </div>

                <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                  <!-- Info del ascensor -->
                  <div class="flex-1 space-y-4">
                    <div class="flex items-start gap-4">
                      <div class="relative flex-shrink-0">
                        <div
                          class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl blur-md opacity-50 group-hover:opacity-75 transition-opacity">
                        </div>
                        <div
                          class="relative p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                          <svg class="w-7 h-7 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                          </svg>
                        </div>
                      </div>

                      <div class="flex-1 min-w-0">
                        <h3
                          class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 break-words group-hover:text-blue-600 transition-colors">
                          {{ elevator.designation }}
                        </h3>
                        <div v-if="elevator.address" class="flex items-start gap-2 text-sm text-gray-600 mb-3">
                          <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                          </svg>
                          <span class="break-words">{{ elevator.address }}</span>
                        </div>

                        <!-- Token con funcionalidad de copiar -->
                        <div class="inline-flex items-center gap-2 group/token">
                          <div
                            class="flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-gray-100 to-slate-100 rounded-xl border border-gray-200 group-hover/token:border-blue-300 transition-colors">
                            <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                              viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span class="text-xs font-mono font-semibold text-gray-700">
                              Token: {{ elevator.public_token.substring(0, 16) }}...
                            </span>
                            <button @click="copyToken(elevator.public_token)"
                              class="p-1 hover:bg-white rounded-md transition-colors" title="Copiar token completo">
                              <svg class="w-4 h-4 text-gray-400 hover:text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                              </svg>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Botones de acción mejorados -->
                  <div class="flex flex-col sm:flex-row lg:flex-col gap-3 lg:min-w-[220px]">
                    <a :href="route('elevators.show', elevator.id)"
                      class="group/btn relative overflow-hidden inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl font-semibold text-xs text-white uppercase tracking-wide transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 active:scale-95">
                      <div
                        class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700">
                      </div>
                      <svg class="w-5 h-5 relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <span class="relative z-10">Ver Revisiones</span>
                    </a>

                    <a :href="route('elevators.poster', elevator.id)" target="_blank"
                      class="group/btn relative overflow-hidden inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 rounded-xl font-semibold text-xs text-white uppercase tracking-wide transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 active:scale-95">
                      <div
                        class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700">
                      </div>
                      <svg class="w-5 h-5 relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <span class="relative z-10">Descargar PDF</span>
                    </a>

                    <button type="button"
                      class="group/btn relative overflow-hidden inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 rounded-xl font-semibold text-xs text-white uppercase tracking-wide transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                      @click="openDelete(elevator)">
                      <div
                        class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700">
                      </div>
                      <svg class="w-5 h-5 relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      <span class="relative z-10">Eliminar</span>
                    </button>
                  </div>
                </div>

                <!-- Badge de estado -->
                <!--div class="absolute top-4 right-4 lg:top-6 lg:right-6">
                  <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full border-2 border-green-200">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Activo
                  </span>
                </div-->
              </div>
            </transition-group>




            <!-- Controles de paginación mejorados -->
            <transition enter-active-class="transition ease-out duration-300"
              enter-from-class="opacity-0 transform translate-y-4" enter-to-class="opacity-100 transform translate-y-0">
              <div v-if="elevators?.links && elevators.data.length > 0" class="mt-10">
                <div class="flex flex-wrap items-center justify-center gap-2">
                  <template v-for="(link, i) in elevators.links" :key="i">
                    <!-- Botón deshabilitado -->
                    <span v-if="!link.url" class="px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed opacity-50"
                      v-html="translatePaginationLabel(link.label)" />
                    <!-- Botón activo -->
                    <Link v-else :href="link.url"
                      class="relative px-5 py-2.5 text-sm rounded-xl border-2 font-semibold transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95"
                      :class="link.active
                        ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-transparent shadow-lg hover:shadow-xl scale-105'
                        : 'bg-white text-gray-700 border-gray-200 hover:border-blue-400 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:shadow-md'"
                      v-html="translatePaginationLabel(link.label)" preserve-scroll />
                  </template>
                </div>

                <!-- Info de paginación -->
                <div class="mt-6 text-center">
                  <p class="text-sm text-gray-600">
                    Mostrando
                    <span class="font-bold text-blue-600">{{ elevators.from }}</span>
                    -
                    <span class="font-bold text-blue-600">{{ elevators.to }}</span>
                    de
                    <span class="font-bold text-blue-600">{{ elevators.total }}</span>
                    resultados
                  </p>
                </div>
              </div>
            </transition>
          </div>
        </div>

        <!-- Card de información adicional (opcional) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div
            class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-blue-600 rounded-xl">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600">Total Activos</p>
                <p class="text-3xl font-bold text-blue-600">{{ stats.total }}</p>
              </div>
            </div>
          </div>

          <div
            class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-2xl p-6 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-emerald-600 rounded-xl">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600">Revisiones</p>
                <p class="text-3xl font-bold text-emerald-600">-</p>
              </div>
            </div>
          </div>

          <div
            class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-2xl p-6 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-purple-600 rounded-xl">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600">Última Act.</p>
                <p class="text-xl font-bold text-purple-600">Hoy</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmación de borrado -->
    <ConfirmDeleteModal v-if="toDelete" :show="showDelete" :elevator="toDelete" @close="closeDelete"
      @deleted="onDeleted" />
  </AuthenticatedLayout>
</template>


<style scoped>
/* Transiciones para transition-group */
.list-move,
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from {
  opacity: 0;
  transform: translateY(30px);
}

.list-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}

.list-leave-active {
  position: absolute;
}

/* Animaciones personalizadas */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out forwards;
}

/* Animaciones personalizadas */
@keyframes fade-in {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.5s ease-out;
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out forwards;
}

/* Grid pattern para backgrounds */
.bg-grid-white\/10 {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.bg-grid-white\/5 {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Transiciones para listas */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from {
  opacity: 0;
  transform: translateY(30px);
}

.list-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}

/* Scroll suave */
html {
  scroll-behavior: smooth;
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {

  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
</style>
