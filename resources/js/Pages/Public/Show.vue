<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    elevator: Object,
    revisions: Object,
    year: Number,
});

const currentRevisions = ref(props.revisions || {});
const currentYear = ref(props.year);
const isUpdating = ref(false);
const lastUpdateTime = ref(new Date());
let pollInterval = null;

const monthNames = [
    '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

const monthIcons = {
    1: '❄️', 2: '💝', 3: '🌸', 4: '🌧️',
    5: '🌺', 6: '☀️', 7: '🏖️', 8: '🌊',
    9: '🍂', 10: '🎃', 11: '🍁', 12: '🎄'
};

// Obtener estado de un mes específico
const getMonthRevision = (month) => {
    return currentRevisions.value[month] || null;
};

// Polling cada 1 minuto
const checkForUpdates = async () => {
    try {
        isUpdating.value = true;
        const response = await axios.get(`/api/public/elevator/${props.elevator.public_token}/revision`, {
            params: { year: currentYear.value }
        });
        const newRevisions = response.data.revisions || {};

        if (JSON.stringify(newRevisions) !== JSON.stringify(currentRevisions.value)) {
            currentRevisions.value = newRevisions;
        }
        lastUpdateTime.value = new Date();
    } catch (error) {
        console.error('Error verificando actualizaciones:', error);
    } finally {
        isUpdating.value = false;
    }
};

// Cambiar año con animación
const changeYear = (delta) => {
    currentYear.value += delta;
    router.get(`/p/${props.elevator.public_token}`, { year: currentYear.value }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            currentRevisions.value = page.props.revisions || {};
        }
    });
};

// Calcular estadísticas
const stats = computed(() => {
    const total = 12;
    const revisionsArray = Object.values(currentRevisions.value);
    const checked = revisionsArray.filter(r => r?.checked).length;
    const pending = total - checked;
    const percentage = Math.round((checked / total) * 100);

    return { total, checked, pending, percentage };
});

// Verificar si es el mes actual
const isCurrentMonth = (month) => {
    const now = new Date();
    return month === now.getMonth() + 1 && currentYear.value === now.getFullYear();
};

// Formatear fecha
const formatDate = (dateString) => {
    if (!dateString) return '';

    try {
        // Intenta parsear la fecha
        const date = new Date(dateString);

        // Verifica si la fecha es válida
        if (isNaN(date.getTime())) {
            return dateString; // Retorna el string original si no es válida
        }

        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        console.error('Error formateando fecha:', error);
        return dateString; // Retorna el string original en caso de error
    }
};
// Tiempo desde última actualización
const timeSinceUpdate = computed(() => {
    const seconds = Math.floor((new Date() - lastUpdateTime.value) / 1000);
    if (seconds < 60) return `hace ${seconds}s`;
    return `hace ${Math.floor(seconds / 60)}m`;
});

onMounted(() => {
    pollInterval = setInterval(checkForUpdates, 60000); // 1 minuto
});

onUnmounted(() => {
    if (pollInterval) clearInterval(pollInterval);
});
</script>

<template>

    <Head>
        <title>{{ elevator.designation }} - Estado de Revisiones</title>
        <link rel="icon" type="image/svg+xml" href="/logo.svg" />
    </Head>

    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-6 sm:py-10 px-3 sm:px-4 lg:px-6">
        <!-- Patrón de fondo decorativo -->
        <div class="fixed inset-0 bg-grid-slate-200/50 pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header mejorado -->
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border-2 border-white/50 overflow-hidden">
                <!-- Banner superior con gradiente -->
                <div
                    class="relative bg-gradient-to-r from-slate-700 via-blue-700 to-indigo-800 px-6 sm:px-8 py-8 sm:py-12">
                    <div class="absolute inset-0 bg-grid-white/5"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

                    <div class="relative">
                        <!-- Icono del ascensor con efecto glow -->
                        <div class="inline-flex items-center gap-4 mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-white/30 rounded-2xl blur-xl animate-pulse"></div>
                                <div
                                    class="relative p-4 bg-white/20 backdrop-blur-sm rounded-2xl border border-white/30 shadow-2xl">
                                    <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight drop-shadow-lg">
                                    {{ elevator.designation }}
                                </h1>
                                <p class="text-sm sm:text-base text-blue-100 mt-1 font-medium">
                                    Control de Revisiones Periódicas
                                </p>
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div v-if="elevator.address"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white">
                            <svg class="w-5 h-5 text-emerald-300 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-sm sm:text-base">{{ elevator.address }}</span>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas con diseño mejorado -->
                <div class="px-6 sm:px-8 py-6 bg-gradient-to-br from-gray-50 to-white">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <!-- Total -->
                        <!--div
                            class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-4 sm:p-5 border-2 border-blue-200 hover:border-blue-400 transition-all duration-300 hover:scale-105 hover:shadow-xl cursor-default">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-400/0 via-blue-400/10 to-blue-400/0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="relative flex items-center gap-3">
                                <div class="p-2.5 bg-blue-600 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-3xl sm:text-4xl font-black text-blue-900">{{ stats.total }}</div>
                                    <div class="text-xs sm:text-sm text-blue-700 font-bold">Total Meses</div>
                                </div>
                            </div>
                        </div-->

                        <!-- Revisados -->
                        <!--div
                            class="group relative bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-4 sm:p-5 border-2 border-emerald-200 hover:border-emerald-400 transition-all duration-300 hover:scale-105 hover:shadow-xl cursor-default">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-emerald-400/0 via-emerald-400/10 to-emerald-400/0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="relative flex items-center gap-3">
                                <div class="p-2.5 bg-emerald-600 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-3xl sm:text-4xl font-black text-emerald-900">{{ stats.checked }}
                                    </div>
                                    <div class="text-xs sm:text-sm text-emerald-700 font-bold">Completados</div>
                                </div>
                            </div>
                        </div-->

                        <!-- Pendientes -->
                        <!--div
                            class="group relative bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-4 sm:p-5 border-2 border-amber-200 hover:border-amber-400 transition-all duration-300 hover:scale-105 hover:shadow-xl cursor-default">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-amber-400/0 via-amber-400/10 to-amber-400/0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="relative flex items-center gap-3">
                                <div class="p-2.5 bg-amber-600 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-3xl sm:text-4xl font-black text-amber-900">{{ stats.pending }}
                                    </div>
                                    <div class="text-xs sm:text-sm text-amber-700 font-bold">Pendientes</div>
                                </div>
                            </div>
                        </div-->

                        <!-- Porcentaje -->
                        <!--div
                            class="group relative bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-4 sm:p-5 border-2 border-purple-200 hover:border-purple-400 transition-all duration-300 hover:scale-105 hover:shadow-xl cursor-default">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-purple-400/0 via-purple-400/10 to-purple-400/0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="relative flex items-center gap-3">
                                <div class="p-2.5 bg-purple-600 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-3xl sm:text-4xl font-black text-purple-900">{{ stats.percentage }}%
                                    </div>
                                    <div class="text-xs sm:text-sm text-purple-700 font-bold">Progreso</div>
                                </div>
                            </div>
                        </div-->
                    </div>

                    <!-- Barra de progreso mejorada -->
                    <!--div class="mt-6">
                        <div class="flex items-center justify-between text-sm font-semibold mb-2">
                            <span class="text-gray-700">Progreso Anual {{ currentYear }}</span>
                            <span class="text-purple-700">{{ stats.checked }} / {{ stats.total }} meses</span>
                        </div>
                        <div class="relative h-5 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 rounded-full shadow-lg transition-all duration-700 ease-out progress-bar-animated"
                                :style="{ width: stats.percentage + '%' }">
                                <div class="absolute inset-0 bg-white/20 animate-shimmer"></div>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xs font-bold text-white drop-shadow-lg">{{ stats.percentage }}%</span>
                            </div>
                        </div>
                    </div-->
                </div>
                
            </div>

            <!-- Selector de año mejorado -->
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border-2 border-white/50 p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-800">Revisiones Mensuales</h2>
                    </div>

                    <div
                        class="flex items-center gap-3 bg-gradient-to-r from-gray-100 to-slate-100 rounded-xl p-2 shadow-inner">
                        <button @click="changeYear(-1)"
                            class="group px-4 py-2.5 bg-white hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 border-2 border-gray-200 hover:border-blue-400 rounded-lg font-bold text-sm text-gray-700 hover:text-blue-700 transition-all shadow-sm hover:shadow-md active:scale-95">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                {{ currentYear - 1 }}
                            </span>
                        </button>

                        <div class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg">
                            <div class="text-2xl font-black text-white text-center min-w-[80px]">
                                {{ currentYear }}
                            </div>
                        </div>

                        <button @click="changeYear(1)"
                            class="group px-4 py-2.5 bg-white hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 border-2 border-gray-200 hover:border-blue-400 rounded-lg font-bold text-sm text-gray-700 hover:text-blue-700 transition-all shadow-sm hover:shadow-md active:scale-95">
                            <span class="flex items-center gap-1">
                                {{ currentYear + 1 }}
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grid de meses mejorado -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                <TransitionGroup name="card">
                    <div v-for="m in 12" :key="`$${currentYear}-$$ {m}`"
                        class="group relative bg-white/90 backdrop-blur-sm rounded-2xl overflow-hidden transition-all duration-300 cursor-default"
                        :class="[
                            getMonthRevision(m)?.checked
                                ? 'border-3 border-emerald-400 shadow-xl shadow-emerald-100 hover:shadow-2xl hover:shadow-emerald-200'
                                : 'border-2 border-gray-200 shadow-lg hover:shadow-xl hover:border-blue-300',
                            isCurrentMonth(m) ? 'ring-4 ring-blue-400 ring-offset-2' : '',
                            'hover:scale-105 active:scale-100'
                        ]">
                        <!-- Badge del mes actual -->
                        <div v-if="isCurrentMonth(m)" class="absolute top-3 right-3 z-10">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                                <span class="w-2 h-2 bg-white rounded-full"></span>
                                Mes Actual
                            </span>
                        </div>

                        <!-- Efecto de brillo hover -->
                        <div class="absolute inset-0 bg-gradient-to-br opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                            :class="getMonthRevision(m)?.checked
                                ? 'from-emerald-400/10 via-teal-400/10 to-emerald-400/10'
                                : 'from-blue-400/10 via-indigo-400/10 to-blue-400/10'">
                        </div>

                        <div class="relative p-5 sm:p-6">
                            <!-- Header del mes con icono -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <!--span class="text-2xl">{{ monthIcons[m] }}</span-->
                                        <div class="font-black text-xl sm:text-2xl text-gray-900">
                                            {{ monthNames[m] }}
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500 font-medium">
                                        {{ currentYear }}
                                    </div>
                                </div>

                                <!-- Icono de estado grande -->
                                <div class="flex-shrink-0 transition-all duration-300"
                                    :class="getMonthRevision(m)?.checked ? 'scale-110' : 'scale-100 opacity-40'">
                                    <div v-if="getMonthRevision(m)?.checked" class="relative">
                                        <div class="absolute inset-0 bg-emerald-400 rounded-full blur-xl animate-pulse">
                                        </div>
                                        <div class="relative p-2 bg-emerald-500 rounded-full shadow-lg">
                                            <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div v-else class="p-2 bg-gray-200 rounded-full">
                                        <svg class="w-8 h-8 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Badge de estado -->
                            <div class="mb-4">
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all"
                                    :class="getMonthRevision(m)?.checked
                                        ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white'
                                        : 'bg-gradient-to-r from-gray-200 to-slate-200 text-gray-700'">
                                    <span class="w-2 h-2 rounded-full"
                                        :class="getMonthRevision(m)?.checked ? 'bg-white animate-pulse' : 'bg-gray-500'">
                                    </span>
                                    {{ getMonthRevision(m)?.checked ? 'Revisado' : 'Pendiente' }}
                                </div>
                            </div>

                            <!-- Fecha de revisión -->
                            <div v-if="getMonthRevision(m)?.checked_at"
                                class="mb-3 flex items-start gap-2 p-3 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-blue-600"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <div class="text-xs font-semibold text-blue-900 mb-0.5">Fecha de revisión</div>
                                    <div class="text-xs text-blue-700 font-medium">{{
                                        formatDate(getMonthRevision(m).checked_at) }}</div>
                                </div>
                            </div>

                            <!-- Notas -->
                            <div v-if="getMonthRevision(m)?.notes"
                                class="p-3 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    <span class="text-xs font-bold text-amber-900">Observaciones:</span>
                                </div>
                                <p class="text-xs text-amber-800 leading-relaxed line-clamp-4 break-words">
                                    {{ getMonthRevision(m).notes }}
                                </p>
                            </div>
                        </div>
                    </div>
                </TransitionGroup>
            </div>

            <!-- Footer con info de actualización -->
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border-2 border-white/50 p-5">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 bg-blue-400 rounded-full blur-md opacity-50 animate-pulse">
                            </div>
                            <div
                                class="relative p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full shadow-lg">
                                <svg class="w-5 h-5 text-white transition-transform"
                                    :class="isUpdating ? 'animate-spin' : ''" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-800">
                                Actualización Automática
                            </div>
                            <!--div class="text-xs text-gray-600">
                                {{ isUpdating ? 'Actualizando...' : `Última actualización ${timeSinceUpdate}` }}
                            </div-->
                        </div>
                    </div>

                    <!--div
                        class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-100 to-emerald-100 border-2 border-green-300 rounded-xl">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold text-green-800">
                            Sincronización cada 1 minuto
                        </span>
                    </div-->
                </div>
            </div>

          
            <!--div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border-2 border-white/50 p-6 sm:p-8">
                <div class="flex items-start gap-4">
                    <div
                        class="flex-shrink-0 p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">
                            Información del Sistema
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Los datos se actualizan automáticamente sin necesidad de recargar la página</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>El mes actual está destacado con un borde azul para fácil identificación</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                <span>Las observaciones técnicas se muestran cuando están disponibles</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Navegue entre diferentes años para consultar el historial completo</span>
                            </li>
                        </ul>
                    </div>

                    
                </div>
            </div>

          
            <div class="text-center py-6">
                <div
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white/50 backdrop-blur-sm rounded-full border border-white/80 shadow-lg">
                    <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full"></div>
                    <span class="text-sm font-medium text-gray-600">
                        Sistema de Control de Revisiones
                    </span>
                    <div class="w-2 h-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full"></div>
                </div>
            </div-->



        </div>
    </div>

</template>

<style scoped>
/* Grid pattern para backgrounds */
.bg-grid-slate-200\/50 {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23cbd5e1' fill-opacity='0.3'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.bg-grid-white\/5 {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Animaciones de transición para las cards */
.card-enter-active,
.card-leave-active {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-enter-from {
    opacity: 0;
    transform: translateY(30px) scale(0.9);
}

.card-leave-to {
    opacity: 0;
    transform: translateY(-30px) scale(0.9);
}

.card-move {
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Animación de fade clásica */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Line clamp para texto */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animación de shimmer para la barra de progreso */
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

/* Gradiente animado para barra de progreso */
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

/* Efecto de pulse personalizado */
@keyframes pulse-soft {

    0%,
    100% {
        opacity: 1;
        transform: scale(1);
    }

    50% {
        opacity: 0.8;
        transform: scale(1.05);
    }
}

.animate-pulse-soft {
    animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Animación de entrada escalonada */
.card-enter-active {
    animation: slide-in 0.5s ease-out;
}

@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Efecto hover para las cards de estadísticas */
.stats-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stats-card:hover {
    transform: translateY(-4px) scale(1.02);
}

/* Mejora del scroll suave */
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
    transition: background 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #2563eb, #4f46e5);
}

/* Efecto de glow para elementos importantes */
.glow-emerald {
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.4),
        0 0 40px rgba(16, 185, 129, 0.2);
}

.glow-blue {
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.4),
        0 0 40px rgba(59, 130, 246, 0.2);
}

/* Animación de rotate suave para spinner */
@keyframes rotate-smooth {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.animate-rotate-smooth {
    animation: rotate-smooth 2s linear infinite;
}

/* Efecto de bounce suave */
@keyframes bounce-gentle {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-8px);
    }
}

.animate-bounce-gentle {
    animation: bounce-gentle 2s ease-in-out infinite;
}

/* Gradiente de texto */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Glassmorphism effect */
.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px) saturate(180%);
    -webkit-backdrop-filter: blur(10px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Efecto de shimmer en botones */
.button-shimmer {
    position: relative;
    overflow: hidden;
}

.button-shimmer::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
            transparent,
            rgba(255, 255, 255, 0.3),
            transparent);
    transition: left 0.5s;
}

.button-shimmer:hover::before {
    left: 100%;
}

/* Border animado con gradiente */
@keyframes border-flow {
    0% {
        border-color: #3b82f6;
    }

    25% {
        border-color: #8b5cf6;
    }

    50% {
        border-color: #ec4899;
    }

    75% {
        border-color: #f59e0b;
    }

    100% {
        border-color: #3b82f6;
    }
}

.border-animated {
    animation: border-flow 4s linear infinite;
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

/* Focus mejorado para accesibilidad */
*:focus-visible {
    outline: 3px solid #3b82f6;
    outline-offset: 3px;
    border-radius: 6px;
}

/* Mejoras para impresión */
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

    .bg-gradient-to-br,
    .bg-gradient-to-r {
        background: white !important;
        color: black !important;
    }
}

/* Responsive mejoras */
@media (max-width: 640px) {
    .card-enter-from {
        transform: translateY(15px) scale(0.95);
    }
}

/* Dark mode ready (opcional) */
@media (prefers-color-scheme: dark) {
    /* Estilos para modo oscuro si se implementa en el futuro */
}

/* Accesibilidad: reducir animaciones */
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

/* Hover states mejorados */
.hover-lift {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
    transform: translateY(-4px);
}

/* Sombras personalizadas */
.shadow-elegant {
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06),
        0 0 0 1px rgba(0, 0, 0, 0.05);
}

.shadow-elegant-lg {
    box-shadow:
        0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05),
        0 0 0 1px rgba(0, 0, 0, 0.05);
}

/* Efecto de parallax sutil */
.parallax {
    transition: transform 0.3s ease-out;
}

.parallax:hover {
    transform: translateZ(20px);
    transform-style: preserve-3d;
}

/* Utilidades adicionales */
.backdrop-blur-strong {
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
}

.text-shadow-sm {
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.text-shadow-md {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.text-shadow-lg {
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Animación de heartbeat para elementos importantes */
@keyframes heartbeat {

    0%,
    100% {
        transform: scale(1);
    }

    10%,
    30% {
        transform: scale(0.95);
    }

    20%,
    40%,
    60%,
    80% {
        transform: scale(1.05);
    }

    50%,
    70% {
        transform: scale(1.02);
    }
}

.animate-heartbeat {
    animation: heartbeat 2s ease-in-out infinite;
}
</style>
