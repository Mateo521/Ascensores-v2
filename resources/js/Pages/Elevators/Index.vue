<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  elevators: Object, // paginator
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
</script>

<template>
  <Head title="Ascensores" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Ascensores
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Formulario para agregar ascensor -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="submit" class="mb-6">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Designación *
                  </label>
                  <input
                    v-model="form.designation"
                    type="text"
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    placeholder="Ej: Torre A - Ascensor 1"
                    required
                  />
                  <div v-if="form.errors.designation" class="text-red-600 text-sm mt-1">
                    {{ form.errors.designation }}
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Dirección
                  </label>
                  <input
                    v-model="form.address"
                    type="text"
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    placeholder="Ej: Av. Libertador 1234"
                  />
                </div>

                <div class="flex items-end gap-3">
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md disabled:opacity-50"
                  >
                    {{ form.processing ? 'Agregando...' : 'Agregar Ascensor' }}
                  </button>

                  <!-- Selector per_page -->
                  <div class="ml-auto">
                    <label class="text-sm text-gray-600 mr-2">Por página</label>
                    <select
                      class="border-gray-300 rounded-md"
                      :value="filters?.per_page ?? 10"
                      @change="changePerPage"
                    >
                      <option :value="5">5</option>
                      <option :value="10">10</option>
                      <option :value="15">15</option>
                      <option :value="25">25</option>
                    </select>
                  </div>
                </div>
              </div>
            </form>

            <!-- Lista paginada -->
            <div v-if="(elevators?.data?.length ?? 0) === 0" class="text-center py-8 text-gray-500">
              No hay ascensores registrados. Agrega el primero usando el formulario de arriba.
            </div>

            <div v-else class="space-y-3">
              <div
                v-for="elevator in elevators.data"
                :key="elevator.id"
                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow bg-white"
              >
                <div class="flex justify-between items-start">
                  <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">
                      {{ elevator.designation }}
                    </h3>
                    <p v-if="elevator.address" class="text-sm text-gray-600 mt-1">
                      📍 {{ elevator.address }}
                    </p>
                    <p class="text-xs text-gray-400 mt-2">
                      Token público: {{ elevator.public_token.substring(0, 20) }}...
                    </p>
                  </div>

                  <div class="flex flex-col gap-2 ml-4">
                    <a
                      :href="route('elevators.show', elevator.id)"
                      class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                    >
                      Ver Revisiones
                    </a>
                    <a
                      :href="route('elevators.poster', elevator.id)"
                      target="_blank"
                      class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700"
                    >
                      Descargar PDF
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Controles de paginación -->
            <div v-if="elevators?.links" class="mt-6 flex flex-wrap items-center gap-2">
              <template v-for="(link, i) in elevators.links" :key="i">
                <span
                  v-if="!link.url"
                  class="px-3 py-1 text-sm text-gray-400"
                  v-html="link.label"
                />
                <Link
                  v-else
                  :href="link.url"
                  class="px-3 py-1 text-sm rounded border"
                  :class="link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                  v-html="link.label"
                  preserve-scroll
                />
              </template>
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
