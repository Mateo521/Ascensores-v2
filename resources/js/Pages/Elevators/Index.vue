<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

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
    onSuccess: () => form.reset()
  });
};

const changePerPage = (e) => {
  const per = Number(e.target.value);
  router.get(route('elevators.index'), { per_page: per }, { preserveScroll: true, preserveState: true });
};

// Traducir labels de paginación
const translatePaginationLabel = (label) => {
  const translations = {
    'Previous': '« Anterior',
    'Next': 'Siguiente »',
    '« Previous': '« Anterior',
    'Next »': 'Siguiente »'
  };
  return translations[label] || label;
};
</script>


<template>

  <Head title="Ascensores" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Ascensores
      </h2>
    </template>

    <div class="py-12 px-2">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Formulario para agregar ascensor -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="submit" class="mb-8">
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Designación -->
                <div>
                  <label class="block text-base font-semibold text-gray-800 mb-2">
                    Designación *
                  </label>
                  <input v-model="form.designation" type="text"
                    class="w-full text-base px-4 py-3 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-lg shadow-sm"
                    placeholder="Ej: UNSL - Rectorado - Ascensor 1" required />
                  <div v-if="form.errors.designation" class="text-red-600 text-sm mt-2">
                    {{ form.errors.designation }}
                  </div>
                </div>

                <!-- Dirección -->
                <div>
                  <label class="block text-base font-semibold text-gray-800 mb-2">
                    Dirección
                  </label>
                  <input v-model="form.address" type="text"
                    class="w-full text-base px-4 py-3 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-lg shadow-sm"
                    placeholder="Ej: Av. Ejército de los Andes 950" />
                </div>

                <!-- Botón y selector -->
                <div class="grid grid-cols-[1fr_116px] justify-end gap-3">
                  <button type="submit" :disabled="form.processing"
                    class="w-full bg-blue-600 hover:bg-blue-700 cursor-pointer text-white font-bold text-base py-3 px-6 rounded-lg shadow-md disabled:opacity-50 transition-all">
                    {{ form.processing ? 'Agregando...' : '+ Agregar Ascensor' }}
                  </button>

                  <!-- Selector per_page (solo visible en pantallas grandes) -->
                  <div class="hidden lg:flex items-center justify-end gap-2">
                    <label class="text-sm font-medium text-gray-600">Por página:</label>
                    <select class="text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-200"
                      :value="filters?.per_page ?? 10" @change="changePerPage">
                      <option :value="5">5</option>
                      <option :value="10">10</option>
                      <option :value="15">15</option>
                      <option :value="25">25</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Selector per_page para móvil (debajo del formulario) -->
              <div class="flex lg:hidden items-center justify-center gap-2 mt-4">
                <label class="text-sm font-medium text-gray-600">Mostrar por página:</label>
                <select class="text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-200"
                  :value="filters?.per_page ?? 10" @change="changePerPage">
                  <option :value="5">5</option>
                  <option :value="10">10</option>
                  <option :value="15">15</option>
                  <option :value="25">25</option>
                </select>
              </div>
            </form>


            <!-- Lista paginada -->
            <div v-if="(elevators?.data?.length ?? 0) === 0" class="text-center py-12 px-2 text-gray-500 text-lg">
              No hay ascensores registrados. <br/> Agrega el primero usando el formulario de arriba.
            </div>

            <div v-else class="space-y-4">
              <div v-for="elevator in elevators.data" :key="elevator.id"
                class="border border-gray-200 rounded-xl p-5 lg:p-6 hover:shadow-lg transition-shadow bg-white">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
                  <!-- Info del ascensor -->
                  <div class="flex-1">
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2">
                      {{ elevator.designation }}
                    </h3>
                    <p v-if="elevator.address" class="text-base flex text-gray-600 mb-2">
                      <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z"/>
</svg>
 {{ elevator.address }}
                    </p>
                    <p class="text-sm text-gray-400">
                      Token: {{ elevator.public_token.substring(0, 20) }}...
                    </p>
                  </div>

                  <!-- Botones de acción -->
                  <div class="flex flex-col sm:flex-row lg:flex-col gap-3 lg:min-w-[200px]">
                    <a :href="route('elevators.show', elevator.id)"
                      class="inline-flex gap-3 items-center justify-center px-5 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-wide hover:bg-indigo-700 transition-colors shadow-md">
                      <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.03v13m0-13c-2.819-.831-4.715-1.076-8.029-1.023A.99.99 0 0 0 3 6v11c0 .563.466 1.014 1.03 1.007 3.122-.043 5.018.212 7.97 1.023m0-13c2.819-.831 4.715-1.076 8.029-1.023A.99.99 0 0 1 21 6v11c0 .563-.466 1.014-1.03 1.007-3.122-.043-5.018.212-7.97 1.023"/>
</svg>
 Ver Revisiones
                    </a>
                    <a :href="route('elevators.poster', elevator.id)" target="_blank"
                      class="inline-flex gap-3 items-center justify-center px-5 py-3 bg-green-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-wide hover:bg-green-700 transition-colors shadow-md">
                      <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17v-5h1.5a1.5 1.5 0 1 1 0 3H5m12 2v-5h2m-2 3h2M5 10V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1v6M5 19v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1M10 3v4a1 1 0 0 1-1 1H5m6 4v5h1.375A1.627 1.627 0 0 0 14 15.375v-1.75A1.627 1.627 0 0 0 12.375 12H11Z"/>
</svg>
 Descargar PDF
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Controles de paginación -->
            <div v-if="elevators?.links" class="mt-8 flex flex-wrap items-center justify-center gap-2">
              <template v-for="(link, i) in elevators.links" :key="i">
                <span v-if="!link.url" class="px-4 py-2 text-base text-gray-400 cursor-not-allowed"
                  v-html="translatePaginationLabel(link.label)" />
                <Link v-else :href="link.url" class="px-4 py-2 text-base rounded-lg border transition-all" :class="link.active
                  ? 'bg-indigo-600 text-white border-indigo-600 font-bold shadow-md'
                  : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-indigo-300'"
                  v-html="translatePaginationLabel(link.label)" preserve-scroll />
              </template>
            </div>





          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
