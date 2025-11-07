<!-- resources/js/Pages/Elevators/Show.vue -->
<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

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
            // Opcional: mensaje de éxito
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
</script>

<template>
    <Head>
        <title>Revisiones - {{ elevator.designation }}</title>
        <link rel="icon" type="image/svg+xml" href="/logo.svg" />
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ elevator.designation }}
                    </h2>
                    <p class="text-sm text-gray-600 flex mt-1" v-if="elevator.address">
                        <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z"/>
                        </svg>
                        {{ elevator.address }}
                    </p>
                </div>
                <Link
                    :href="route('elevators.index')"
                    class="text-sm text-blue-600 hover:text-blue-800 underline"
                >
                    ← Volver a la lista
                </Link>
            </div>
        </template>

        <div class="py-12 px-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Controles de año -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-semibold">Revisiones Mensuales</h3>
                                <a :href="publicUrl" target="_blank" class="text-sm flex items-center text-blue-600 hover:underline">
                                    <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961"/>
                                    </svg>
                                    Ver página pública (QR)
                                </a>
                            </div>
                            <div class="flex items-center gap-3">
                                <button
                                    @click="changeYear(-1)"
                                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 font-medium"
                                >
                                    ‹ {{ year - 1 }}
                                </button>
                                <div class="text-2xl font-bold text-gray-800 w-24 text-center">
                                    {{ year }}
                                </div>
                                <button
                                    @click="changeYear(1)"
                                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 font-medium"
                                >
                                    {{ year + 1 }} ›
                                </button>
                            </div>
                        </div>

                        <!-- Formulario de revisiones -->
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                                <div
                                    v-for="m in 12"
                                    :key="m"
                                    class="border rounded-lg p-4 hover:shadow-md transition-shadow"
                                    :class="form.months[m] ? 'bg-green-50 border-green-300' : 'bg-white border-gray-200'"
                                >
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-semibold text-gray-800">
                                                {{ monthNames[m] }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ year }}
                                            </div>
                                        </div>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                v-model="form.months[m]"
                                                class="w-6 h-6 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2 cursor-pointer"
                                            />
                                            <span class="text-sm font-medium" :class="form.months[m] ? 'text-green-700' : 'text-gray-500'">
                                                {{ form.months[m] ? 'Revisado' : 'Pendiente' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex gap-3">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-md disabled:opacity-50 transition"
                                >
                                    {{ form.processing ? 'Guardando...' : '✓ Guardar Cambios' }}
                                </button>
                                <Link
                                    :href="route('elevators.index')"
                                    class="border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-md transition"
                                >
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
