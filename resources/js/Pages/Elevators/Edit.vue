<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  elevator: Object
});

const form = useForm({
  designation: props.elevator.designation || '',
  address: props.elevator.address || '',
  max_capacity_kg: props.elevator.max_capacity_kg || null,
  comments: props.elevator.comments || '',
});

const submit = () => {
  form.put(route('elevators.update', props.elevator.id), {
    onSuccess: () => {
      // Opcional: mostrar notificación
    }
  });
};
</script>

<template>
  <Head title="Editar Ascensor" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">
      <div class="max-w-5xl mx-auto">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-6 animate-fade-in">
          <Link :href="route('dashboard')" class="hover:text-blue-600 transition-colors">
            Dashboard
          </Link>
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
          <Link :href="route('elevators.index')" class="hover:text-blue-600 transition-colors">
            Ascensores
          </Link>
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
          <span class="font-semibold text-gray-900">Editar</span>
        </nav>

        <!-- Card principal -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 animate-slide-up">
          
          <!-- Header -->
          <div class="bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 px-6 py-6 sm:px-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
              <div>
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                  <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                  Editar Ascensor
                </h3>
                <p class="text-xs text-amber-100 mt-1">Modifica la información del ascensor</p>
              </div>
              <Link :href="route('elevators.index')" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-medium text-sm transition-all">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
              </Link>
            </div>
          </div>

          <!-- Formulario -->
          <div class="p-6 sm:p-8 lg:p-10">
            <form @submit.prevent="submit" class="space-y-6">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Designación -->
                <div class="space-y-2 animate-fade-in">
                  <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Designación
                    <span class="px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded">Requerido</span>
                  </label>
                  <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl opacity-0 group-hover:opacity-10 transition duration-300 blur"></div>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                      </div>
                      <input v-model="form.designation" type="text" class="w-full pl-12 pr-4 py-3.5 text-sm bg-gray-50 border-2 border-gray-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl transition-all duration-300" placeholder="Ej: UNSL - Rectorado - Ascensor 1" required/>
                      <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <div v-if="form.designation" class="text-green-500">
                          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                  <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 transform scale-95" enter-to-class="opacity-100 transform scale-100">
                    <div v-if="form.errors.designation" class="flex items-center gap-2 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                      <svg class="w-5 h-5 text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span class="text-red-700 text-sm font-medium">{{ form.errors.designation }}</span>
                    </div>
                  </transition>
                </div>

                <!-- Dirección -->
                <div class="space-y-2 animate-fade-in" style="animation-delay: 0.1s;">
                  <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <svg class="w-4 h-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Dirección
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded">Opcional</span>
                  </label>
                  <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl opacity-0 group-hover:opacity-10 transition duration-300 blur"></div>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                      </div>
                      <input v-model="form.address" type="text" class="w-full pl-12 pr-4 py-3.5 text-sm bg-gray-50 border-2 border-gray-200 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 rounded-xl transition-all duration-300" placeholder="Ej: Av. Ejército de los Andes 950"/>
                    </div>
                  </div>
                </div>

                <!-- Capacidad Máxima -->
                <div class="space-y-2 animate-fade-in" style="animation-delay: 0.2s;">
                  <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <svg class="w-4 h-4 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                    Capacidad Máxima (kg)
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded">Opcional</span>
                  </label>
                  <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl opacity-0 group-hover:opacity-10 transition duration-300 blur"></div>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                      </div>
                      <input v-model.number="form.max_capacity_kg" type="number" min="0" step="1" class="w-full pl-12 pr-16 py-3.5 text-sm bg-gray-50 border-2 border-gray-200 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 rounded-xl transition-all duration-300" placeholder="Ej: 450"/>
                      <div class="absolute inset-y-0 right-0 pr-4 flex items-center gap-2">
                        <span v-if="form.max_capacity_kg" class="text-xs font-medium text-gray-500">kg</span>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              <!-- Comentarios -->
              <div class="space-y-2 animate-fade-in" style="animation-delay: 0.3s;">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                  <svg class="w-4 h-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                  </svg>
                  Comentarios
                  <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded">Opcional</span>
                </label>
                <div class="relative group">
                  <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-xl opacity-0 group-hover:opacity-10 transition duration-300 blur"></div>
                  <div class="relative">
                    <textarea v-model="form.comments" rows="3" class="w-full px-4 py-3.5 text-sm bg-gray-50 border-2 border-gray-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 rounded-xl transition-all duration-300 resize-none" placeholder="Notas adicionales o información relevante sobre el ascensor..."></textarea>
                  </div>
                </div>
              </div>

              <!-- Botones -->
              <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-6 border-t-2 border-gray-100">
                <button type="submit" :disabled="form.processing" class="group relative flex-1 sm:flex-none inline-flex items-center justify-center gap-3 overflow-hidden bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 hover:from-amber-700 hover:via-orange-700 hover:to-red-700 text-white font-bold text-sm py-4 px-8 rounded-xl shadow-xl hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:-translate-y-1 active:scale-95">
                  <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                  <svg class="w-5 h-5 relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  <span class="relative z-10">{{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}</span>
                </button>

                <Link :href="route('elevators.index')" class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-all">
                  <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Cancelar
                </Link>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
