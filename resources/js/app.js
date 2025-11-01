// resources/js/app.js
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';
import { registerSW } from 'virtual:pwa-register';

const appName = import.meta.env.VITE_APP_NAME || 'Ascensores Nuevo Cuyo';

// Registrar PWA
registerSW({
  immediate: true,
  onNeedRefresh() {},
  onOfflineReady() {}
});

createInertiaApp({
  title: (title) => `${title} - ${appName}`, // ← sin símbolos extra
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(createPinia())
      .mount(el);
  },
  progress: { color: '#4B5563' },
});

/*

\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'mateovillalva42@gmail.com',
    'password' => bcrypt('password123')
]);
*/


