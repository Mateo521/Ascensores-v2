// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.js', 'resources/css/app.css'],
      refresh: true,
    }),
    vue({
      template: { transformAssetUrls: { base: null, includeAbsolute: false } },
    }),
    VitePWA({
      registerType: 'autoUpdate',
      manifestFilename: 'manifest.webmanifest',
      includeAssets: ['favicon.ico', 'logo.svg', 'offline.html'],
      manifest: {
        name: 'Ascensores Nuevo Cuyo',
        short_name: 'Ascensores',
        description: 'Sistema de revisiones de ascensores',
        start_url: '/dashboard',
        scope: '/',
        display: 'standalone',
        background_color: '#ffffff',
        theme_color: '#2563eb',
        icons: [
       
          { src: '/icons/android/android-launchericon-192-192.png', sizes: '192x192', type: 'image/png', purpose: 'any' },
          { src: '/icons/android/android-launchericon-512-512.png', sizes: '512x512', type: 'image/png', purpose: 'any' },
     
          { src: '/icons/android/android-launchericon-512-512.png', sizes: '512x512', type: 'image/png', purpose: 'maskable' },
        ],
      },
      workbox: {
        additionalManifestEntries: [{ url: '/offline.html', revision: null }],
        navigateFallback: '/offline.html',
        runtimeCaching: [
          {
            urlPattern: ({ url }) => url.pathname.startsWith('/api/public/'),
            handler: 'NetworkFirst',
            options: { cacheName: 'public-api', networkTimeoutSeconds: 3, expiration: { maxEntries: 200, maxAgeSeconds: 60 * 60 * 24 * 7 } }
          },
          {
            urlPattern: ({ request }) => request.mode === 'navigate',
            handler: 'NetworkFirst',
            options: { cacheName: 'app-shell' }
          },
          {
            urlPattern: ({ request }) => ['style', 'script', 'worker', 'image', 'font'].includes(request.destination),
            handler: 'StaleWhileRevalidate',
            options: { cacheName: 'static-assets' }
          }
        ]
      }
    })
  ],
  resolve: { alias: { '@': '/resources/js' } }
});
