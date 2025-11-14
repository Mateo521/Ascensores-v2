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
      <link rel="icon" type="image/png" href="/logo.png" />
      <link rel="icon" type="image/png" href="/favicon-32x32.png" />
    </Head>

    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center">
      <!-- Fondo decorativo -->
      <div class="fixed inset-0 -z-10 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100"></div>
      <div class="fixed inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_right,rgba(79,70,229,0.12),transparent_50%)]"></div>

      <div class="w-full max-w-md mx-auto">
        <!-- Branding -->
        <div class="flex flex-col items-center mb-6">
          <div class="relative mb-3">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl blur-xl opacity-40"></div>
            <img src="/logo.png" alt="Ascensores Nuevo Cuyo" class="relative h-16 w-auto rounded-lg" />
          </div>
          <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
            Ascensores Nuevo Cuyo
          </h1>
          <p class="mt-1 text-sm text-gray-600">
            Accede al panel de administración
          </p>
        </div>

        <!-- Estado -->
        <div v-if="status" class="mb-4 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
          {{ status }}
        </div>

        <!-- Card de login -->
        <form @submit.prevent="submit" class="bg-white/95 backdrop-blur-xl border border-white/60 rounded-2xl shadow-xl p-6 sm:p-8">
          <!-- Email -->
          <div class="mb-5">
            <InputLabel for="email" value="Correo electrónico" class="text-sm font-semibold text-gray-800 mb-2" />
            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full text-base px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-200"
              v-model="form.email"
              required
              autofocus
              autocomplete="username"
              placeholder="ejemplo@correo.com"
            />
            <InputError class="mt-2 text-sm" :message="form.errors.email" />
          </div>

          <!-- Password -->
          <div class="mb-5">
            <InputLabel for="password" value="Contraseña" class="text-sm font-semibold text-gray-800 mb-2" />
            <div class="relative">
              <TextInput
                :type="showPassword ? 'text' : 'password'"
                id="password"
                class="mt-1 block w-full text-base px-4 py-3 pr-12 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-200"
                v-model="form.password"
                required
                autocomplete="current-password"
                placeholder="Ingresa tu contraseña"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700"
                :aria-label="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
              >
                <span v-if="showPassword">
                  <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                  </svg>
                </span>
                <span v-else>
                  <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                    <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                  </svg>
                </span>
              </button>
            </div>
            <InputError class="mt-2 text-sm" :message="form.errors.password" />
          </div>

          <!-- Opciones -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <label class="flex items-center">
              <Checkbox name="remember" v-model:checked="form.remember" />
              <span class="ms-2 text-sm text-gray-700">Recordarme</span>
            </label>

            <Link
              v-if="canResetPassword"
              :href="route('password.request')"
              class="text-sm text-indigo-600 hover:text-indigo-800 underline font-medium"
            >
              ¿Olvidaste tu contraseña?
            </Link>
          </div>

          <!-- Botón -->
          <PrimaryButton
            class="w-full text-base cursor-pointer justify-center py-3 font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-lg hover:shadow-xl"
            :class="{ 'opacity-50': form.processing }"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Ingresando...' : 'Ingresar' }}
          </PrimaryButton>

          <!-- Sello de seguridad -->
          <div class="mt-4 flex items-center justify-center gap-2 text-xs text-gray-500">
            <svg class="w-4 h-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-width="2" d="M7 10V7a5 5 0 0 1 10 0v3m-9 4h8a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2Z"/>
            </svg>
            Conexión segura (HTTPS)
          </div>
        </form>

        <!-- Footer -->
        <div class="flex items-center justify-center gap-3 mt-6 text-sm text-gray-500">
          <span>© {{ new Date().getFullYear() }} Ascensores Nuevo Cuyo</span>
          <a target="_blank" href="https://github.com/Mateo521" class="inline-flex items-center gap-2 hover:opacity-80 transition">
            <img class="w-5 h-5" src="https://github.githubassets.com/assets/GitHub-Mark-ea2971cee799.png" alt="Mateo">
            <span class="hidden sm:inline">GitHub</span>
          </a>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
