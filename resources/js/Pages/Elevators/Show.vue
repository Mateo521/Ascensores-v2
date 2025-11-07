<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    elevator: Object,
    year: Number,
    revisions: Object,
    publicUrl: String
});

const form = useForm({
    year: props.year,
    months: {}
});

// Inicializar el formulario con los datos de revisiones
for (let m = 1; m <= 12; m++) {
    form.months[m] = props.revisions[m]?.checked || false;
}

const submit = () => {
    form.put(route('revisions.bulk', props.elevator.id), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Revisiones guardadas correctamente');
        }
    });
};

const changeYear = (delta) => {
    router.get(route('elevators.show', props.elevator.id), { year: props.year + delta });
};

const monthNames = [
    '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

// Estadísticas computadas
const stats = computed(() => {
    const completed = Object.values(form.months).filter(Boolean).length;
    const pending = 12 - completed;
    const percentage = Math.round((completed / 12) * 100);
    return { completed, pending, percentage };
});

const copyPublicUrl = () => {
    navigator.clipboard.writeText(props.publicUrl);
    // Aquí podrías agregar una notificación toast
};
</script>

<template>
    <Head>
        <title>Revisiones - {{ elevator.designation }}</title>
        <link rel="icon" type="image/svg+xml" href="/logo.svg" />
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl blur-md opacity-50 animate-pulse"></div>
                            <div class="relative p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                                {{ elevator.designation }}
                            </h2>
                            <p class="text-sm text-gray-500">Control de revisiones mensuales</p>
                        </div>
                    </div>
                    <div v-if="elevator.address" class="flex items-center gap-2 text-sm text-gray-600 ml-14">
                        <svg class="w-5 h-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ elevator.address }}</span>
                    </div>
                </div>
                <Link
                    :href="route('elevators.index')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-200 hover:border-blue-300 hover:bg-blue-50 text-gray-700 hover:text-blue-700 font-semibold rounded-xl transition-all shadow-sm hover:shadow-md"
                >
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver a la lista
                </Link>
            </div>
        </template>

        <div class="py-6 sm:py-8 lg:py-12 px-3 sm:px-4 lg:px-6">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Cards de Estadísticas -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-emerald-600 rounded-xl shadow-md">
                                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completadas</p>
                                <p class="text-3xl font-bold text-emerald-600">{{ stats.completed }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-amber-600 rounded-xl shadow-md">
                                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pendientes</p>
                                <p class="text-3xl font-bold text-amber-600">{{ stats.pending }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-600 rounded-xl shadow-md">
                                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Progreso</p>
                                <p class="text-3xl font-bold text-blue-600">{{ stats.percentage }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Principal -->
                <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border-2 border-gray-100">
                    <!-- Header -->
                    <div class="relative overflow-hidden bg-gradient-to-r from-slate-700 via-gray-800 to-slate-900 px-6 py-6">
                        <div class="absolute inset-0 bg-grid-white/5"></div>
                        <div class="relative">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white/10 backdrop-blur-sm rounded-xl">
                                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">Revisiones Mensuales</h3>
                                        <p class="text-sm text-gray-300">Marca las revisiones completadas</p>
                                    </div>
                                </div>

                                <!-- Controles de año -->
                                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-2">
                                    <button
                                        @click="changeYear(-1)"
                                        class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold transition-all hover:scale-105 active:scale-95"
                                    >
                                        ‹ {{ year - 1 }}
                                    </button>
                                    <div class="text-2xl font-bold text-white px-4 min-w-[100px] text-center">
                                        {{ year }}
                                    </div>
                                    <button
                                        @click="changeYear(1)"
                                        class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold transition-all hover:scale-105 active:scale-95"
                                    >
                                        {{ year + 1 }} ›
                                    </button>
                                </div>
                            </div>

                            <!-- Enlace público con QR -->
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-white/20 transition-all group">
                                <svg class="w-5 h-5 text-blue-300 group-hover:text-blue-200 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                <a :href="publicUrl" target="_blank" class="text-sm font-medium text-blue-200 hover:text-white transition-colors">
                                    Ver página pública (QR)
                                </a>
                                <button
                                    @click="copyPublicUrl"
                                    class="p-1.5 hover:bg-white/20 rounded-md transition-colors"
                                    title="Copiar enlace"
                                >
                                    <svg class="w-4 h-4 text-gray-300 hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de revisiones -->
                    <div class="p-6 sm:p-8">
                        <form @submit.prevent="submit" class="space-y-8">
                            <!-- Barra de progreso visual -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm font-medium">
                                    <span class="text-gray-600">Progreso anual</span>
                                    <span class="text-blue-600">{{ stats.completed }} / 12 meses</span>
                                </div>
                                <div class="w-full h-4 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                    <div 
                                        class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-500 shadow-lg"
                                        :style="{ width: `${stats.percentage}%` }"
                                    ></div>
                                </div>
                                <p class="text-xs text-gray-500 text-center">{{ stats.percentage }}% completado</p>
                            </div>

                            <!-- Grid de meses -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                <div
                                    v-for="m in 12"
                                    :key="m"
                                    class="group relative border-2 rounded-xl p-5 transition-all duration-300 cursor-pointer hover:scale-105 active:scale-95"
                                    :class="form.months[m] 
                                        ? 'bg-gradient-to-br from-emerald-50 to-teal-50 border-emerald-300 shadow-lg shadow-emerald-100' 
                                        : 'bg-white border-gray-200 hover:border-blue-300 hover:shadow-xl'"
                                    @click="form.months[m] = !form.months[m]"
                                >
                                    <!-- Badge de estado -->
                                    <div class="absolute top-3 right-3">
                                        <div 
                                            class="w-3 h-3 rounded-full transition-all duration-300"
                                            :class="form.months[m] ? 'bg-emerald-500 animate-pulse' : 'bg-gray-300'"
                                        ></div>
                                    </div>

                                    <div class="flex flex-col gap-4">
                                        <!-- Icono del mes -->
                                        <div 
                                            class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300"
                                            :class="form.months[m] 
                                                ? 'bg-emerald-500 shadow-lg' 
                                                : 'bg-gray-100 group-hover:bg-blue-100'"
                                        >
                                            <svg 
                                                class="w-6 h-6 transition-colors"
                                                :class="form.months[m] ? 'text-white' : 'text-gray-500 group-hover:text-blue-600'"
                                                xmlns="http://www.w3.org/2000/svg" 
                                                fill="none" 
                                                viewBox="0 0 24 24" 
                                                stroke="currentColor"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>

                                        <!-- Info del mes -->
                                        <div>
                                            <div class="font-bold text-lg text-gray-800 mb-1">
                                                {{ monthNames[m] }}
                                            </div>
                                            <div class="text-sm text-gray-500 mb-3">
                                                {{ year }}
                                            </div>

                                            <!-- Checkbox con label -->
                                            <label class="flex items-center gap-3 cursor-pointer" @click.stop>
                                                <input
                                                    type="checkbox"
                                                    v-model="form.months[m]"
                                                    class="w-5 h-5 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2 cursor-pointer transition-all"
                                                />
                                                <span 
                                                    class="text-sm font-semibold transition-colors"
                                                    :class="form.months[m] ? 'text-emerald-700' : 'text-gray-500'"
                                                >
                                                    {{ form.months[m] ? '✓ Revisado' : 'Pendiente' }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Efecto de brillo al hover -->
                                    <div 
                                        class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                                        :class="form.months[m] 
                                            ? 'bg-gradient-to-r from-emerald-400/10 to-teal-400/10' 
                                            : 'bg-gradient-to-r from-blue-400/10 to-indigo-400/10'"
                                    ></div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t-2 border-gray-100">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="group relative flex-1 sm:flex-none inline-flex items-center justify-center gap-3 overflow-hidden bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-sm py-4 px-8 rounded-xl shadow-xl hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:-translate-y-1 active:scale-95"
                                >
                                    <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                    <svg 
                                        class="w-5 h-5 relative z-10"
                                        :class="form.processing ? 'animate-spin' : ''"
                                        xmlns="http://www.w3.org/2000/svg" 
                                        fill="none" 
                                        viewBox="0 0 24 24" 
                                        stroke="currentColor"
                                    >
                                        <path v-if="!form.processing" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        <circle v-else class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path v-if="form.processing" class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="relative z-10">{{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}</span>
                                </button>

                                <Link
                                    :href="route('elevators.index')"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-white border-2 border-gray-300 hover:border-gray-400 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-all shadow-md hover:shadow-lg"
                                >
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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

<style scoped>
/* Grid pattern para backgrounds */
.bg-grid-white\/5 {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Animaciones personalizadas */
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slide-in-right {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes pulse-slow {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

/* Animación para las cards de meses */
.animate-fade-in {
  animation: fade-in 0.5s ease-out;
}

/* Efecto de entrada escalonada para las cards */
.group:nth-child(1) { animation-delay: 0ms; }
.group:nth-child(2) { animation-delay: 50ms; }
.group:nth-child(3) { animation-delay: 100ms; }
.group:nth-child(4) { animation-delay: 150ms; }
.group:nth-child(5) { animation-delay: 200ms; }
.group:nth-child(6) { animation-delay: 250ms; }
.group:nth-child(7) { animation-delay: 300ms; }
.group:nth-child(8) { animation-delay: 350ms; }
.group:nth-child(9) { animation-delay: 400ms; }
.group:nth-child(10) { animation-delay: 450ms; }
.group:nth-child(11) { animation-delay: 500ms; }
.group:nth-child(12) { animation-delay: 550ms; }

/* Efecto de destello en checkboxes */
input[type="checkbox"]:checked {
  animation: checkbox-pop 0.3s ease;
}

@keyframes checkbox-pop {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}

/* Mejora visual de los checkboxes */
input[type="checkbox"] {
  transition: all 0.2s ease;
}

input[type="checkbox"]:hover {
  transform: scale(1.1);
}

input[type="checkbox"]:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}

/* Efecto de brillo para botones */
@keyframes shimmer {
  0% {
    transform: translateX(-100%) skewX(-15deg);
  }
  100% {
    transform: translateX(200%) skewX(-15deg);
  }
}

/* Hover effect para las estadísticas */
.stats-card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stats-card:hover {
  transform: translateY(-4px) scale(1.02);
}

/* Efecto de ripple para botones */
.button-ripple {
  position: relative;
  overflow: hidden;
}

.button-ripple::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.button-ripple:active::after {
  width: 300px;
  height: 300px;
}

/* Sombra suave para elementos elevados */
.shadow-smooth {
  box-shadow: 0 2px 15px -3px rgba(0, 0, 0, 0.07), 
              0 10px 20px -2px rgba(0, 0, 0, 0.04);
}

.shadow-smooth:hover {
  box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.15), 
              0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Gradiente animado para la barra de progreso */
@keyframes gradient-shift {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.progress-bar-animated {
  background-size: 200% 200%;
  animation: gradient-shift 3s ease infinite;
}

/* Estilo para el backdrop blur */
.backdrop-blur-custom {
  backdrop-filter: blur(12px) saturate(180%);
  -webkit-backdrop-filter: blur(12px) saturate(180%);
}

/* Efecto de glow en elementos activos */
.glow-effect {
  box-shadow: 0 0 20px rgba(16, 185, 129, 0.3),
              0 0 40px rgba(16, 185, 129, 0.2);
}

.glow-effect-blue {
  box-shadow: 0 0 20px rgba(59, 130, 246, 0.3),
              0 0 40px rgba(59, 130, 246, 0.2);
}

/* Animación de latido para badges */
@keyframes heartbeat {
  0%, 100% {
    transform: scale(1);
  }
  10%, 30% {
    transform: scale(0.9);
  }
  20%, 40%, 60%, 80% {
    transform: scale(1.1);
  }
  50%, 70% {
    transform: scale(1.05);
  }
}

.animate-heartbeat {
  animation: heartbeat 2s ease-in-out infinite;
}

/* Efecto de typing para textos */
@keyframes typing {
  from {
    width: 0;
  }
  to {
    width: 100%;
  }
}

/* Scroll suave */
html {
  scroll-behavior: smooth;
}

/* Personalización del scrollbar */
::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #3b82f6, #8b5cf6);
  border-radius: 10px;
  transition: background 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #2563eb, #7c3aed);
}

/* Efecto de focus mejorado */
*:focus {
  outline: none;
}

*:focus-visible {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
  border-radius: 4px;
}

/* Transiciones globales suaves */
* {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Efecto de glassmorphism */
.glass-effect {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px) saturate(180%);
  -webkit-backdrop-filter: blur(10px) saturate(180%);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Animación de rotación suave */
@keyframes rotate-smooth {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-rotate {
  animation: rotate-smooth 2s linear infinite;
}

/* Efecto de bounce suave */
@keyframes bounce-soft {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

.animate-bounce-soft {
  animation: bounce-soft 2s ease-in-out infinite;
}

/* Efecto de shake para errores */
@keyframes shake {
  0%, 100% {
    transform: translateX(0);
  }
  10%, 30%, 50%, 70%, 90% {
    transform: translateX(-5px);
  }
  20%, 40%, 60%, 80% {
    transform: translateX(5px);
  }
}

.animate-shake {
  animation: shake 0.5s ease-in-out;
}

/* Gradientes personalizados */
.gradient-text {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.gradient-border {
  position: relative;
}

.gradient-border::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: inherit;
  padding: 2px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
}

/* Efecto de fade para modales */
@keyframes fade-in-scale {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.modal-enter {
  animation: fade-in-scale 0.3s ease-out;
}

/* Efecto de skeleton loading */
@keyframes skeleton-loading {
  0% {
    background-position: -200px 0;
  }
  100% {
    background-position: calc(200px + 100%) 0;
  }
}

.skeleton {
  background: linear-gradient(90deg, #f0f0f0 0px, #f8f8f8 40px, #f0f0f0 80px);
  background-size: 200px 100%;
  animation: skeleton-loading 1.5s ease-in-out infinite;
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}

/* Dark mode ready (opcional para futuro) */
@media (prefers-color-scheme: dark) {
  /* Estilos para modo oscuro si lo implementas */
}

/* Utilidades de espacio negativo para overlays */
.overlay-dark {
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
}

.overlay-light {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(4px);
}

/* Efectos de texto con sombra */
.text-shadow-sm {
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.text-shadow-md {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.text-shadow-lg {
  text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Print styles */
@media print {
  .no-print {
    display: none !important;
  }
  
  body {
    background: white !important;
  }
  
  * {
    box-shadow: none !important;
    text-shadow: none !important;
  }
}
</style>
