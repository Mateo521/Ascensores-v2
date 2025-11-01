<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Información del Perfil
            </h2>

            <p class="mt-2 text-base text-gray-600">
                Actualiza la información de tu cuenta y dirección de correo electrónico.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-8 space-y-6"
        >
            <!-- Nombre -->
            <div>
                <InputLabel for="name" value="Nombre" class="text-base font-semibold text-gray-800 mb-2" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full text-base px-4 py-3 rounded-lg"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2 text-sm" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Correo Electrónico" class="text-base font-semibold text-gray-800 mb-2" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full text-base px-4 py-3 rounded-lg"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2 text-sm" :message="form.errors.email" />
            </div>

            <!-- Verificación de email -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-base text-gray-800">
                    Tu dirección de correo no está verificada.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="font-semibold text-indigo-600 underline hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md"
                    >
                        Haz clic aquí para reenviar el correo de verificación.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-3 text-base font-semibold text-green-700 bg-green-50 border border-green-200 rounded-lg p-3"
                >
                    ✓ Se ha enviado un nuevo enlace de verificación a tu correo.
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton 
                    :disabled="form.processing"
                    class="text-base px-6 py-3 font-bold"
                >
                    {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out duration-300"
                    enter-from-class="opacity-0 translate-x-2"
                    leave-active-class="transition ease-in-out duration-300"
                    leave-to-class="opacity-0 translate-x-2"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-base font-semibold text-green-600 flex items-center gap-2"
                    >
                        <span class="inline-block">✓</span>
                        Guardado correctamente
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
