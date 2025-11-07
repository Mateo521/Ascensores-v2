<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    elevator: Object,
    revisions: Object,
    year: Number,
});

const currentRevisions = ref(props.revisions || {}); // ← Asegurar que sea un objeto
const currentYear = ref(props.year);
let pollInterval = null;

const monthNames = [
    '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

// Obtener estado de un mes específico
const getMonthRevision = (month) => {
    return currentRevisions.value[month] || null;
};

// Polling cada 5 segundos
const checkForUpdates = async () => {
    try {
        const response = await axios.get(`/api/public/elevator/${props.elevator.public_token}/revision`, {
            params: { year: currentYear.value }
        });

        const newRevisions = response.data.revisions || {};

        // Solo actualizar si cambió
        if (JSON.stringify(newRevisions) !== JSON.stringify(currentRevisions.value)) {
            currentRevisions.value = newRevisions;
        }
    } catch (error) {
        console.error('Error verificando actualizaciones:', error);
    }
};

// Cambiar año
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

onMounted(() => {
    pollInterval = setInterval(checkForUpdates, 6 * 10000);
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
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Cabecera -->
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-6">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                    {{ elevator.designation }}
                </h1>
                <p v-if="elevator.address" class="text-gray-600 flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z" />
                    </svg>
                    {{ elevator.address }}
                </p>
                <!-- Estadísticas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                    <div class="bg-blue-50 rounded-lg p-3 text-center border border-blue-200">
                        <div class="text-2xl font-bold text-blue-800">{{ stats.total }}</div>
                        <div class="text-xs text-blue-600 font-medium">Total</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3 text-center border border-green-200">
                        <div class="text-2xl font-bold text-green-800">{{ stats.checked }}</div>
                        <div class="text-xs text-green-600 font-medium">Revisados</div>
                    </div>
                    <div class="bg-red-50 rounded-lg p-3 text-center border border-red-200">
                        <div class="text-2xl font-bold text-red-800">{{ stats.pending }}</div>
                        <div class="text-xs text-red-600 font-medium">Pendientes</div>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-3 text-center border border-indigo-200">
                        <div class="text-2xl font-bold text-indigo-800">{{ stats.percentage }}%</div>
                        <div class="text-xs text-indigo-600 font-medium">Completado</div>
                    </div>
                </div>
                <!-- Barra de progreso -->
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                        <span>Progreso anual</span>
                        <span class="font-semibold">{{ stats.percentage }}%</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-400 to-green-600 transition-all duration-500"
                            :style="{ width: stats.percentage + '%' }"></div>
                    </div>
                </div>
            </div>
            <!-- Selector de año -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Revisiones del año</h2>
                    <div class="flex items-center gap-3">
                        <button @click="changeYear(-1)"
                            class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">
                            ‹ {{ currentYear - 1 }}
                        </button>
                        <div class="text-xl font-bold text-gray-800 w-20 text-center">
                            {{ currentYear }}
                        </div>
                        <button @click="changeYear(1)"
                            class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">
                            {{ currentYear + 1 }} ›
                        </button>
                    </div>
                </div>
            </div>
            <!-- Grid de meses -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                <TransitionGroup name="fade">
                    <div v-for="m in 12" :key="`$${currentYear}-$$ {m}`"
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 overflow-hidden"
                        :class="[
                            getMonthRevision(m)?.checked ? 'border-2 border-green-300' : 'border-2 border-gray-200',
                            m === new Date().getMonth() + 1 && currentYear === new Date().getFullYear() ? 'ring-2 ring-blue-400' : ''
                        ]">
                        <div class="p-5">
                            <!-- Cabecera del mes -->
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <div class="font-bold text-lg text-gray-800">
                                        {{ monthNames[m] }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ currentYear }}
                                    </div>
                                </div>
                                <div class="text-3xl transition-opacity duration-300"
                                    :class="getMonthRevision(m)?.checked ? '' : 'opacity-30'">
                                    <span v-if="getMonthRevision(m)?.checked">
                                        <svg class="w-6 h-6 text-green-500 dark:text-green-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </span>
                                    <span v-else>
                                        <svg class="w-6 h-6 text-red-500 dark:text-red-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <!-- Estado -->
                            <div class="text-center py-2 rounded-lg font-semibold text-sm transition-colors duration-300"
                                :class="getMonthRevision(m)?.checked
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-gray-100 text-gray-600'">
                                {{ getMonthRevision(m)?.checked ? 'Revisado' : 'Pendiente' }}
                            </div>

                            <!-- Fecha de revisión -->
                            <div v-if="getMonthRevision(m)?.checked_at"
                                class="mt-3 text-xs text-gray-600 flex items-start gap-1 bg-blue-50 p-2 rounded">
                                <svg class="w-3 h-3 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span class="break-words">{{ getMonthRevision(m).checked_at }}</span>
                            </div>

                            <!-- Notas -->
                            <div v-if="getMonthRevision(m)?.notes"
                                class="mt-3 text-xs text-gray-700 bg-amber-50 p-2 rounded border border-amber-200">
                                <div class="font-semibold text-amber-800 mb-1 flex items-center gap-1">
                                    <svg class="w-3 h-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6.03v13m0-13c-2.819-.831-4.715-1.076-8.029-1.023A.99.99 0 0 0 3 6v11c0 .563.466 1.014 1.03 1.007 3.122-.043 5.018.212 7.97 1.023" />
                                    </svg>
                                    Nota:
                                </div>
                                <p class="line-clamp-3 break-words">{{ getMonthRevision(m).notes }}</p>
                            </div>
                        </div>
                    </div>
                </TransitionGroup>
            </div>

            <!-- Footer con indicador de actualización -->
            <div class="bg-white rounded-xl shadow-lg p-4">
                <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                    <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>Actualizando automáticamente cada 1 minuto</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
