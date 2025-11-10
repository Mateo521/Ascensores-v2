<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();

// Obtener iniciales del usuario
const userInitials = computed(() => {
  const name = page.props.auth.user.name || '';
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .substring(0, 2);
});
</script>

<template>
  <div>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
      <!-- Patrón de fondo decorativo -->
      <div class="fixed inset-0 bg-grid-slate-200/50 pointer-events-none -z-10"></div>

      <nav class="sticky top-0 z-50 border-b-2 border-white/50 bg-white/80 backdrop-blur-xl shadow-lg">
        <!-- Primary Navigation Menu -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-20 justify-between items-center">
            <div class="flex items-center gap-8">
              <!-- Logo mejorado -->
              <div class="flex shrink-0 items-center">
                <Link :href="route('dashboard')"
                  class="group relative flex items-center gap-3 px-4 py-2 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50">
                <div class="relative">
                  <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl blur-md opacity-0 group-hover:opacity-50 transition-opacity">
                  </div>
                  <ApplicationLogo class="relative h-12 w-auto fill-current text-gray-800" />
                </div>
                <span class="hidden lg:block text-xl font-black text-gray-800 group-hover:text-blue-700 transition-colors">
                  RevisionControl
                </span>
                </Link>
              </div>

              <!-- Navigation Links mejorados -->
              <div class="hidden md:flex items-center gap-2">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                  class="group relative px-4 py-2 rounded-xl font-bold text-sm transition-all duration-300"
                  :class="route().current('dashboard')
                    ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg'
                    : 'text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-700'">
                <span class="relative flex items-center gap-2">
                  <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                  Inicio
                </span>
                </NavLink>

                <NavLink :href="route('elevators.index')" :active="route().current('elevators.*')"
                  class="group relative px-4 py-2 rounded-xl font-bold text-sm transition-all duration-300"
                  :class="route().current('elevators.*')
                    ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg'
                    : 'text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-700'">
                <span class="relative flex items-center gap-2">
                  <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                  </svg>
                  Ascensores
                </span>
                </NavLink>
              </div>
            </div>

            <div class="hidden md:flex md:items-center md:gap-3">
              <!-- User dropdown mejorado -->
              <div class="relative">
                <Dropdown align="right" width="64">
                  <template #trigger>
                    <button type="button"
                      class="group inline-flex items-center gap-3 rounded-xl border-2 border-gray-200 bg-white px-4 py-2.5 text-sm font-bold leading-4 text-gray-700 transition-all duration-300 hover:border-blue-400 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                      <!-- Avatar con iniciales -->
                      <div class="relative">
                        <div
                          class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full blur-md opacity-0 group-hover:opacity-50 transition-opacity">
                        </div>
                        <div
                          class="relative flex items-center justify-center w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-full font-black text-sm shadow-lg">
                          {{ userInitials }}
                        </div>
                      </div>

                      <div class="text-left hidden lg:block">
                        <div class="font-black text-gray-900 group-hover:text-blue-700">
                          {{ $page.props.auth.user.name }}
                        </div>
                        <div class="text-xs text-gray-500 font-medium">
                          {{ $page.props.auth.user.email }}
                        </div>
                      </div>

                      <svg class="h-5 w-5 transition-transform group-hover:rotate-180 duration-300"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd" />
                      </svg>
                    </button>
                  </template>

                  <template #content>
                    <div class="p-3 border-b border-gray-100">
                      <div class="flex items-center gap-3">
                        <div
                          class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-full font-black shadow-lg">
                          {{ userInitials }}
                        </div>
                        <div>
                          <div class="font-black text-gray-900">{{ $page.props.auth.user.name }}</div>
                          <div class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>
                      </div>
                    </div>

                    <DropdownLink :href="route('profile.edit')"
                      class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-700 transition-all">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Perfil
                    </DropdownLink>

                    <div class="border-t border-gray-100"></div>

                    <DropdownLink :href="route('logout')" method="post" as="button"
                      class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 transition-all w-full">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Cerrar sesión
                    </DropdownLink>
                  </template>
                </Dropdown>
              </div>
            </div>

            <!-- Hamburger mejorado -->
            <div class="flex items-center md:hidden">
              <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex items-center justify-center rounded-xl p-3 text-gray-600 transition-all duration-300 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-700 focus:bg-blue-50 focus:text-blue-700 focus:outline-none border-2 border-gray-200 hover:border-blue-400">
                <svg class="h-6 w-6 transition-transform duration-300"
                  :class="{ 'rotate-90': showingNavigationDropdown }" stroke="currentColor" fill="none"
                  viewBox="0 0 24 24">
                  <path :class="{
                    hidden: showingNavigationDropdown,
                    'inline-flex': !showingNavigationDropdown,
                  }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  <path :class="{
                    hidden: !showingNavigationDropdown,
                    'inline-flex': showingNavigationDropdown,
                  }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu mejorado -->
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 -translate-y-1"
          enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-1">
          <div v-show="showingNavigationDropdown" class="md:hidden border-t-2 border-gray-100 bg-white/95 backdrop-blur-xl">
            <div class="space-y-1 px-4 pb-3 pt-4">
              <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all"
                :class="route().current('dashboard')
                  ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg'
                  : 'text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-700'">
              <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Inicio
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('elevators.index')" :active="route().current('elevators.*')"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all"
                :class="route().current('elevators.*')
                  ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg'
                  : 'text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-700'">
              <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
              Ascensores
              </ResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options mejorado -->
            <div class="border-t-2 border-gray-100 pb-4 pt-4 bg-gradient-to-r from-gray-50 to-slate-50">
              <div class="px-4 mb-3">
                <div class="flex items-center gap-3">
                  <div
                    class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-full font-black shadow-lg">
                    {{ userInitials }}
                  </div>
                  <div>
                    <div class="text-base font-black text-gray-900">
                      {{ $page.props.auth.user.name }}
                    </div>
                    <div class="text-sm font-medium text-gray-500">
                      {{ $page.props.auth.user.email }}
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-3 space-y-1 px-4">
                <ResponsiveNavLink :href="route('profile.edit')"
                  class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-700 transition-all">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Perfil
                </ResponsiveNavLink>

                <ResponsiveNavLink :href="route('logout')" method="post" as="button"
                  class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-red-600 hover:bg-red-50 transition-all w-full">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Cerrar sesión
                </ResponsiveNavLink>
              </div>
            </div>
          </div>
        </Transition>
      </nav>

      <!-- Page Heading mejorado -->
      <header v-if="$slots.header"
        class="sticky top-20 z-40 bg-white/80 backdrop-blur-xl shadow-lg border-b-2 border-white/50">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Grid pattern para backgrounds */
.bg-grid-slate-200\/50 {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23cbd5e1' fill-opacity='0.3'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Mejora del scroll */
html {
  scroll-behavior: smooth;
}
</style>
