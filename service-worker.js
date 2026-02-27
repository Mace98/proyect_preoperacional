const CACHE_VERSION = 'preoperacional-cache-v2';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const DYNAMIC_CACHE = `${CACHE_VERSION}-dynamic`;

const urlsToCache = [
  './proyect_preoperacional/',
  './proyect_preoperacional/index.php',
  './proyect_preoperacional/manifest.json',
];


// Instalación del Service Worker
self.addEventListener('install', event => {
  console.log('Service Worker: Instalando...', STATIC_CACHE);
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(cache => {
        console.log('Service Worker: Cacheando archivos estáticos');
        // Cachear archivos principales de forma tolerante a errores
        return Promise.allSettled(
          urlsToCache.map(url => 
            fetch(url)
              .then(response => {
                if (response.status === 200) {
                  return cache.put(url, response);
                } else {
                  console.warn(`Service Worker: URL no pudo ser cacheada (${response.status}):`, url);
                }
              })
              .catch(error => {
               // console.warn('Service Worker: Error cacheando URL:', url, error);
              })
          )
        );
      })
      .catch(error => {
        console.error('Service Worker: Error durante la instalación', error);
      })
  );
  self.skipWaiting();
});

// Activación del Service Worker
self.addEventListener('activate', event => {
  //console.log('Service Worker: Activando...', CACHE_VERSION);
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (!cacheName.startsWith(CACHE_VERSION)) {
            //console.log('Service Worker: Eliminando caché antiguo', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch Event - Estrategia inteligente de caché
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);

  // Solo manejar GET
  if (request.method !== 'GET') {
    return;
  }

  // No cachear solicitudes a otros orígenes
  if (url.origin !== location.origin) {
    return;
  }

  // Estrategia para documentos HTML
  if (request.method === 'GET' && request.headers.get('accept').includes('text/html')) {
    event.respondWith(
      fetch(request)
        .then(response => {
          const responseClone = response.clone();
          caches.open(DYNAMIC_CACHE).then(cache => {
            cache.put(request, responseClone);
          });
          return response;
        })
        .catch(() => {
          return caches.match(request)
            .then(response => response || caches.match('/index.php'));
        })
    );
    return;
  }

  // Estrategia Cache-First para assets (CSS, JS, imágenes)
  if (request.destination === 'style' || 
      request.destination === 'script' || 
      request.destination === 'image' ||
      request.destination === 'font') {
    event.respondWith(
      caches.match(request)
        .then(response => {
          if (response) {
            return response;
          }
          return fetch(request)
            .then(response => {
              const responseClone = response.clone();
              caches.open(DYNAMIC_CACHE).then(cache => {
                cache.put(request, responseClone);
              });
              return response;
            });
        })
        .catch(() => {
          // Retornar un fallback según el tipo de recurso
          if (request.destination === 'image') {
            return new Response('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><text x="50" y="50">No Image</text></svg>', 
              { headers: { 'Content-Type': 'image/svg+xml' } });
          }
          return new Response('Recurso no disponible', { status: 503 });
        })
    );
    return;
  }

  // Estrategia Network-First para otras solicitudes (API)
  event.respondWith(
    fetch(request)
      .then(response => {
        const responseClone = response.clone();
        caches.open(DYNAMIC_CACHE).then(cache => {
          cache.put(request, responseClone);
        });
        return response;
      })
      .catch(() => {
        return caches.match(request);
      })
  );
});

// Sincronización en segundo plano (opcional)
self.addEventListener('sync', event => {
  if (event.tag === 'sync-data') {
    event.waitUntil(
      // Aquí puedes sincronizar datos cuando haya conexión
      Promise.resolve()
    );
  }
});

// Manejo de mensajes desde el cliente
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
