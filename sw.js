const CACHE_NAME = 'goat-box-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/index.html',
  '/style.css',      // Corrigé (style.css au lieu de styles.css)
  '/monscript.js',   // Corrigé (monscript.js au lieu de app.js)
  '/manifest.json',
  '/goat.png',       // Ton icône de manifest
  '/goat.jpg',       // Image par défaut de la chèvre
  '/goat_meh.jpg',   // Image de la chèvre qui crie
  '/meeh.mp3'        // Son de la chèvre
];

// 1. Installation : mise en cache des fichiers statiques
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[Service Worker] Mise en cache des fichiers de l\'application');
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
  // Force l'activation immédiate du nouveau service worker
  self.skipWaiting();
});

// 2. Activation : nettoyage des anciens caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(
        keyList.map((key) => {
          if (key !== CACHE_NAME) {
            console.log('[Service Worker] Suppression de l\'ancien cache :', key);
            return caches.delete(key);
          }
        })
      );
    })
  );
  // Prend le contrôle des pages immédiatement
  self.clients.claim();
});

// 3. Interception des requêtes : Stratégie "Cache First" avec repli réseau
self.addEventListener('fetch', (event) => {
  // On ignore les requêtes qui ne sont pas en GET (ex: POST)
  if (event.request.method !== 'GET') return;

  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      if (cachedResponse) {
        // Retourne la ressource du cache si elle existe
        return cachedResponse;
      }

      // Sinon, essaie de la récupérer sur le réseau
      return fetch(event.request)
        .then((networkResponse) => {
          return networkResponse;
        })
        .catch(() => {
          // Repli en cas de panne réseau
          if (event.request.mode === 'navigate') {
            return caches.match('/index.html');
          }
        });
    })
  );
});