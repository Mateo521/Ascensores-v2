<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
        default: true
    },
    status: {
        type: String,
        default: ''
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head>
            <title>Iniciar sesión - Ascensores Nuevo Cuyo</title>
            <link rel="icon" type="image/svg+xml" href="/logo.png" />
            <link rel="icon" type="image/png" href="/favicon-32x32.png" />
        </Head>

        <div class="w-full max-w-md mx-auto">
            <!-- Logo -->
            <div class="flex flex-col items-center mb-6">
                <img src="/logo.png" alt="Ascensores Nuevo Cuyo" class="h-26 w-auto mb-3" />
                <h1 class="text-2xl font-extrabold text-gray-900">Ascensores Nuevo Cuyo</h1>
            </div>


            <div v-if="status"
                class="mb-4 text-base font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
                {{ status }}
            </div>


            <form @submit.prevent="submit" class="bg-white rounded-xl shadow-lg p-6 sm:p-8">

                <div class="mb-5">
                    <InputLabel for="email" value="Correo electrónico"
                        class="text-base font-semibold text-gray-800 mb-2" />
                    <TextInput id="email" type="email" class="mt-1 block w-full text-base px-4 py-3 rounded-lg"
                        v-model="form.email" required autofocus autocomplete="username"
                        placeholder="ejemplo@correo.com" />
                    <InputError class="mt-2 text-sm" :message="form.errors.email" />
                </div>


                <div class="mb-5">
                    <InputLabel for="password" value="Contraseña" class="text-base font-semibold text-gray-800 mb-2" />
                    <div class="relative">
                        <TextInput :type="showPassword ? 'text' : 'password'" id="password"
                            class="mt-1 block w-full text-base px-4 py-3 pr-12 rounded-lg" v-model="form.password"
                            required autocomplete="current-password" placeholder="Ingresa tu contraseña" />

                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700"
                            :aria-label="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                            <span v-if="showPassword"><svg class="w-6 h-6 text-gray-800 "
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </span>
                            <span v-else><svg class="w-6 h-6 text-gray-800 " aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="2"
                                        d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                    <path stroke="currentColor" stroke-width="2"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                    <InputError class="mt-2 text-sm" :message="form.errors.password" />
                </div>


                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-700">Recordarme</span>
                    </label>

                    <Link v-if="canResetPassword" :href="route('password.request')"
                        class="text-sm text-indigo-600 hover:text-indigo-800 underline font-medium">
                    ¿Olvidaste tu contraseña?
                    </Link>
                </div>


                <PrimaryButton class="w-full text-base cursor-pointer justify-center py-3 font-bold"
                    :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                    {{ form.processing ? 'Ingresando...' : 'Ingresar' }}
                </PrimaryButton>
            </form>


            <p class="text-center flex items-center justify-center text-sm text-gray-500 mt-6">
                © {{ new Date().getFullYear() }} Ascensores Nuevo Cuyo
            <div class="px-4"> <a target="_blank" href="https://github.com/Mateo521"><img class="w-7 h-7"
                        src="https://github.githubassets.com/assets/GitHub-Mark-ea2971cee799.png" alt="Mateo"></a></div>
            </p>
        </div>
    </GuestLayout>
</template>
