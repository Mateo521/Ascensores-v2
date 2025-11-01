<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>
<template>
    <section>
        <header class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Actualizar Contraseña
            </h2>

            <p class="mt-2 text-base text-gray-600">
                Asegúrate de usar una contraseña larga y segura para proteger tu cuenta.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-8 space-y-6">
            <!-- Contraseña actual -->
            <div>
                <InputLabel 
                    for="current_password" 
                    value="Contraseña Actual" 
                    class="text-base font-semibold text-gray-800 mb-2" 
                />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full text-base px-4 py-3 rounded-lg"
                    autocomplete="current-password"
                    placeholder="Ingresa tu contraseña actual"
                />

                <InputError
                    :message="form.errors.current_password"
                    class="mt-2 text-sm"
                />
            </div>

            <!-- Nueva contraseña -->
            <div>
                <InputLabel 
                    for="password" 
                    value="Nueva Contraseña" 
                    class="text-base font-semibold text-gray-800 mb-2" 
                />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full text-base px-4 py-3 rounded-lg"
                    autocomplete="new-password"
                    placeholder="Mínimo 8 caracteres"
                />

                <InputError :message="form.errors.password" class="mt-2 text-sm" />
            </div>

            <!-- Confirmar contraseña -->
            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirmar Nueva Contraseña"
                    class="text-base font-semibold text-gray-800 mb-2"
                />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full text-base px-4 py-3 rounded-lg"
                    autocomplete="new-password"
                    placeholder="Repetí la nueva contraseña"
                />

                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-2 text-sm"
                />
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton 
                    :disabled="form.processing"
                    class="text-base px-6 py-3 font-bold"
                >
                    {{ form.processing ? 'Guardando...' : 'Guardar Contraseña' }}
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
                        Contraseña actualizada
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
