<!-- resources/js/Components/ConfirmDeleteModal.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref, watch } from 'vue';

const props = defineProps({
  show: { type: Boolean, default: false },
  elevator: { type: Object, required: true }, // { id, designation, public_token? }
});

const emit = defineEmits(['close', 'deleted']);

const form = useForm({});

const processing = ref(false);

const onDelete = () => {
  if (processing.value) return;
  processing.value = true;

  form.delete(route('elevators.destroy', props.elevator.id), {
    preserveScroll: true,
    onSuccess: () => {
      processing.value = false;
      emit('deleted');
      emit('close');
    },
    onError: () => {
      processing.value = false;
    },
    onFinish: () => {
      processing.value = false;
    }
  });
};

const close = () => emit('close');

watch(() => props.show, (v) => { if (!v) processing.value = false; });
</script>

<template>
  <Modal :show="show" @close="close" maxWidth="lg">
    <div class="p-6">
      <h2 class="text-xl font-bold text-gray-900">
        Eliminar ascensor
      </h2>
      <p class="mt-3 text-gray-700">
        ¿Seguro que deseas eliminar el ascensor
        <span class="font-semibold">“{{ elevator.designation }}”</span>?
        Esta acción eliminará también sus revisiones asociadas y el QR dejará de funcionar.
      </p>

      <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
        <SecondaryButton @click="close">Cancelar</SecondaryButton>
        <DangerButton :disabled="processing" @click="onDelete">
          {{ processing ? 'Eliminando...' : 'Eliminar definitivamente' }}
        </DangerButton>
      </div>
    </div>
  </Modal>
</template>
