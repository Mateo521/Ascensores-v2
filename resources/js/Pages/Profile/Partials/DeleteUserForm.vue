<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>
<template>
    <section class="space-y-6">
        <header class="mb-6">
            <h2 class="text-2xl font-bold text-red-600">
                Eliminar Cuenta
            </h2>

            <p class="mt-2 text-base text-gray-600">
                Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán  borrados permanentemente. Antes de eliminar tu cuenta, descarga cualquier 
                información que desees conservar.
                 (Para agregar un nuevo usuario contactá con el Administrador)
            </p>
        </header>

        <DangerButton 
            @click="confirmUserDeletion"
            class="text-base px-6 py-3 font-bold"
        >
             Eliminar Cuenta
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal" >
            <div class="p-6 lg:p-8  ">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    ¿Estás seguro de que deseas eliminar tu cuenta?
                </h2>

                <p class="text-base text-gray-600  mb-6">
                    Una vez eliminada, todos los recursos y datos de tu cuenta van a ser 
                    borrados permanentemente. Por favor ingresa tu contraseña para 
                    confirmar que deseas eliminar tu cuenta de forma permanente.
                    (Para agregar un nuevo usuario contactá con el Administrador)
                </p>

                <div class="mb-6">
                    <InputLabel
                        for="password"
                        value="Contraseña"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="block w-full text-base px-4 py-3 rounded-lg"
                        placeholder="Ingresa tu contraseña para confirmar"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2 text-sm" />
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <SecondaryButton 
                        @click="closeModal"
                        class="text-base px-6 py-3 font-semibold w-full sm:w-auto"
                    >
                        Cancelar
                    </SecondaryButton>

                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                        class="text-base px-6 py-3 font-bold w-full sm:w-auto"
                    >
                        {{ form.processing ? 'Eliminando...' : ' Eliminar Cuenta' }}
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

