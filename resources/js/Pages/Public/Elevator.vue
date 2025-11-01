<!-- resources/js/Pages/Public/Elevator.vue -->
<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    elevator: Object,
    year: Number,
    revisions: Object
});

const monthNames = [
    '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];
</script>

<template>

    <Head>
        <title>Estado - {{ elevator.designation }}</title>
        <link rel="icon" type="image/svg+xml" href="/logo.svg" />
    </Head>


    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6 text-center">
                <div class="mb-4">
                    <div class="inline-block bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold mb-3">
                        Ascensores Nuevo Cuyo

                        
                    </div>
                     <img src="/logo.png" alt="Ascensores Nuevo Cuyo" class="h-26 flex mx-auto w-auto mb-3" />
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    {{ elevator.designation }}
                </h1>
                <p v-if="elevator.address" class="text-gray-600 justify-center flex text-lg mb-3">
                    <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z" />
                    </svg>
                    {{ elevator.address }}
                </p>
                <div class="inline-flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-lg">
                    <span class="text-sm font-medium text-blue-800">
                        Estado de revisiones mensuales
                    </span>
                    <span class="text-lg font-bold text-blue-900">{{ year }}</span>
                </div>
            </div>

            <!-- Grilla de meses -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                <div v-for="m in 12" :key="m"
                    class="bg-white rounded-lg shadow-md p-5 flex flex-col items-center justify-center gap-3 hover:shadow-lg transition-shadow">
                    <div class="text-center">
                        <div class="font-bold text-lg text-gray-800">
                            {{ monthNames[m] }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ year }}
                        </div>
                    </div>

                    <div class="w-12 h-12 rounded-full flex items-center justify-center"
                        :class="revisions[m]?.checked ? 'bg-green-500' : 'bg-gray-300'"
                        :title="revisions[m]?.checked ? 'Revisado' : 'Pendiente'">
                        <span class="text-white text-2xl">
                            {{ revisions[m]?.checked ? '✓' : '○' }}
                        </span>
                    </div>

                    <div class="text-xs font-semibold uppercase tracking-wide"
                        :class="revisions[m]?.checked ? 'text-green-700' : 'text-gray-500'">
                        {{ revisions[m]?.checked ? 'Revisado' : 'Pendiente' }}
                    </div>
                </div>
            </div>

            <!-- Footer informativo -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="mb-3">
                    <div class="text-sm text-gray-600 mb-2">En caso de emergencia, contactar:</div>
                    <a href="tel:2664555572"
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xl px-6 py-3 rounded-lg transition">
                        2664555572
                    </a>
                </div>
                <div class="text-xs text-gray-500 mt-4">
                    Esta página se actualiza automáticamente.
                </div>
            </div>
        </div>
    </div>
</template>
