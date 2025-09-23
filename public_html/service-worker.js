const CACHE_NAME = 'moollish-cache-v1';
const URLS_TO_CACHE = [
  // Páginas principales que deben funcionar offline
  '/',
  '/insumos/registrar',
  '/moollish/inicio',           // inicio de moollish
  '/offline.html',              // Página de fallback cuando no hay conexión
  '/manifest.json',              // Página de fallback cuando no hay conexión

  // Rutas de API que necesitamos cachear
  '/api/web/predios',           // Lista de predios
  '/api/web/predio/*/details',  // Detalles de predios
  '/insumos/por-predio/*',      // Insumos por predio
  '/potreros/por-predio/*',     // Potreros por predio
  '/lotes/por-predio/*',        // Lotes por predio

  // Recursos estáticos compilados por Vite
  '/build/assets/app-HauAVEFt.css',  // CSS compilado
  '/build/assets/app-DlBKZGL7.js',   // JS compilado
  '/js/offline-manager.js',          // Script para manejo offline

  // Recursos estáticos esenciales - Solo los que realmente se usan
  '/assets/css/bootstrap.min.css',
  '/assets/css/theme.min.css',
  '/assets/vendors/css/vendors.min.css',
  '/assets/vendors/css/dataTables.bs5.min.css',
  '/assets/vendors/js/vendors.min.js',
  '/assets/vendors/js/dataTables.min.js',
  '/assets/vendors/js/dataTables.bs5.min.js',

  // Recursos de terceros necesarios
  'https://code.jquery.com/jquery-3.6.0.min.js',
  'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js',
  'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css',

  // Imágenes esenciales (logo, íconos, etc.)
  '/img/moollish.png',
];

// IndexedDB como respaldo
const DB_NAME = 'moollish_fallback_storage';
const DB_VERSION = 1;
const STORE_NAME = 'cached_resources';
let fallbackDB = null;

// Detección de dispositivos móviles
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

// Variable para controlar el uso de IndexedDB como alternativa
let useFallbackStorage = false;

// Inicialización de IndexedDB
async function initIndexedDB() {
  return new Promise((resolve, reject) => {
    if (!('indexedDB' in self)) {
      console.error('[Service Worker] IndexedDB no está disponible');
      resolve(false);
      return;
    }

    const request = indexedDB.open(DB_NAME, DB_VERSION);

    request.onerror = (event) => {
      console.error('[Service Worker] Error al abrir IndexedDB:', event.target.error);
      resolve(false);
    };

    request.onupgradeneeded = (event) => {
      const db = event.target.result;
      if (!db.objectStoreNames.contains(STORE_NAME)) {
        db.createObjectStore(STORE_NAME, { keyPath: 'url' });
        console.log('[Service Worker] Almacén de objetos creado en IndexedDB');
      }
    };

    request.onsuccess = (event) => {
      fallbackDB = event.target.result;
      console.log('[Service Worker] IndexedDB inicializada como almacenamiento alternativo');
      resolve(true);
    };
  });
}

// Instalar el Service Worker
self.addEventListener('install', event => {
  console.log('[Service Worker] Instalando...');
  event.waitUntil(
    (async () => {
      try {
        // Primero intentar usar Cache API
        if ('caches' in self) {
          try {
            const cache = await caches.open(CACHE_NAME);
            console.log('[Service Worker] Cacheando archivos con Cache API');
            await cache.addAll(URLS_TO_CACHE);
            console.log('[Service Worker] Caché inicial completado con Cache API');
          } catch (error) {
            console.warn('[Service Worker] Error al usar Cache API:', error);
            console.warn('[Service Worker] Intentando usar IndexedDB como alternativa');
            useFallbackStorage = true;

            // Inicializar IndexedDB y cachear las URLs
            await initIndexedDB();
            if (fallbackDB) {
              for (const url of URLS_TO_CACHE) {
                try {
                  await cacheUrlInIndexedDB(url);
                } catch (err) {
                  console.error(`[Service Worker] Error al cachear ${url} en IndexedDB:`, err);
                }
              }
            }
          }
        } else {
          console.warn('[Service Worker] Cache API no disponible, usando IndexedDB');
          useFallbackStorage = true;

          // Inicializar IndexedDB y cachear las URLs
          await initIndexedDB();
          if (fallbackDB) {
            for (const url of URLS_TO_CACHE) {
              try {
                await cacheUrlInIndexedDB(url);
              } catch (err) {
                console.error(`[Service Worker] Error al cachear ${url} en IndexedDB:`, err);
              }
            }
          }
        }
      } catch (error) {
        console.error('[Service Worker] Error durante la instalación:', error);
      }

      return self.skipWaiting();
    })()
  );
});

// Función para guardar en IndexedDB
async function cacheUrlInIndexedDB(url) {
  if (!fallbackDB) {
    console.error('[Service Worker] IndexedDB no inicializada');
    return false;
  }

  try {
    const fullUrl = new URL(url, self.location.origin).href;
    const response = await fetch(fullUrl);
    if (!response.ok) {
      throw new Error(`Error al obtener ${fullUrl}: ${response.status}`);
    }

    const responseText = await response.text();
    const contentType = response.headers.get('Content-Type') || 'text/html';

    return new Promise((resolve, reject) => {
      const transaction = fallbackDB.transaction([STORE_NAME], 'readwrite');
      const store = transaction.objectStore(STORE_NAME);

      const request = store.put({
        url: fullUrl,
        content: responseText,
        contentType: contentType,
        timestamp: Date.now()
      });

      request.onsuccess = () => {
        console.log(`[Service Worker] URL cacheada en IndexedDB: ${fullUrl}`);
        resolve(true);
      };

      request.onerror = (event) => {
        console.error(`[Service Worker] Error al guardar URL en IndexedDB: ${fullUrl}`, event.target.error);
        reject(false);
      };
    });
  } catch (error) {
    console.error(`[Service Worker] Error al cachear URL ${url} en IndexedDB:`, error);
    return false;
  }
}

// Función para obtener de IndexedDB
async function getFromIndexedDB(url) {
  if (!fallbackDB) {
    await initIndexedDB();
    if (!fallbackDB) return null;
  }

  return new Promise((resolve, reject) => {
    const transaction = fallbackDB.transaction([STORE_NAME], 'readonly');
    const store = transaction.objectStore(STORE_NAME);
    const request = store.get(url);

    request.onsuccess = () => {
      const data = request.result;
      if (data) {
        console.log(`[Service Worker] Recuperado de IndexedDB: ${url}`);
        const blob = new Blob([data.content], { type: data.contentType });
        resolve(new Response(blob, {
          headers: { 'Content-Type': data.contentType }
        }));
      } else {
        console.log(`[Service Worker] No encontrado en IndexedDB: ${url}`);
        resolve(null);
      }
    };

    request.onerror = (event) => {
      console.error(`[Service Worker] Error al recuperar de IndexedDB: ${url}`, event.target.error);
      resolve(null);
    };
  });
}

// Activar el Service Worker
self.addEventListener('activate', event => {
  console.log('[Service Worker] Activado');
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            console.log('[Service Worker] Limpiando cache viejo');
            return caches.delete(cache);
          }
        })
      );
    }).then(() => {
      // Inicializar IndexedDB después de activar
      return initIndexedDB();
    })
  );
  return self.clients.claim();
});

// Estrategia de cache para recursos estáticos: Network first con fallback a cache
async function handleFetch(event) {
  const url = new URL(event.request.url);

  // Verificar si la solicitud tiene el parámetro bypass_sw=true o el encabezado X-Bypass-Service-Worker
  const bypassSW = url.searchParams.has('bypass_sw') ||
                  event.request.headers.has('X-Bypass-Service-Worker');

  // Si la solicitud tiene el parámetro bypass_sw=true, dejar que vaya directamente al servidor
  if (bypassSW) {
    console.log('[Service Worker] Bypassing SW for:', event.request.url);
    // Retornamos la promesa de fetch para que la solicitud vaya al servidor
    return fetch(event.request);
  }

  // Manejar solicitudes POST específicas cuando estamos offline
  if (!navigator.onLine && event.request.method === 'POST') {
    // Manejar registro de animales offline
    if (url.pathname === '/animal/store') {
      console.log('[Service Worker] Interceptando solicitud POST a /animal/store en modo offline');

      // Aquí devolvemos una respuesta simulada para que el cliente sepa que debe almacenar localmente
      return new Response(JSON.stringify({
        success: true,
        offline: true,
        message: 'Animal guardado en modo offline. Se sincronizará cuando haya conexión.',
        temp_id: Date.now() // ID temporal para referencia
      }), {
        status: 200,
        headers: {
          'Content-Type': 'application/json',
          'X-Offline-Response': 'true'
        }
      });
    }
  }

  // Ignorar las solicitudes a la API que no sean GET
  if (url.pathname.startsWith('/api/') && event.request.method !== 'GET') {
    return fetch(event.request);
  }

  try {
    // Estrategia 1: Intentar la red primero
    const networkResponse = await fetch(event.request);

    // Si la respuesta es válida, actualizar la caché (Cache o IndexedDB)
    if (networkResponse.ok) {
      if (!useFallbackStorage && 'caches' in self) {
        // Solo cachear si la solicitud es GET
        if (event.request.method === 'GET') {
          const cache = await caches.open(CACHE_NAME);
          cache.put(event.request, networkResponse.clone());
        }
      } else if (fallbackDB) {
        // Solo cachear HTML, CSS, JS e imágenes
        if (networkResponse.headers.get('content-type') &&
            (networkResponse.headers.get('content-type').includes('text/html') ||
             networkResponse.headers.get('content-type').includes('text/css') ||
             networkResponse.headers.get('content-type').includes('application/javascript') ||
             networkResponse.headers.get('content-type').includes('image/') ||
             networkResponse.headers.get('content-type').includes('application/json'))) {
          cacheUrlInIndexedDB(event.request.url);
        }
      }
    }

    return networkResponse;
  } catch (error) {
    // Estrategia 2: Si la red falla, intentar caché
    console.log(`[Service Worker] Red no disponible para ${url.pathname}, intentando caché`);

    let cachedResponse = null;

    // Intentar primero con Cache API si está disponible
    if (!useFallbackStorage && 'caches' in self) {
      cachedResponse = await caches.match(event.request);
    }

    // Si no se encuentra en Cache API, intentar con IndexedDB
    if (!cachedResponse && fallbackDB) {
      cachedResponse = await getFromIndexedDB(event.request.url);
    }

    if (cachedResponse) {
      return cachedResponse;
    }

    // Si no se encuentra en ningún caché, retornar la página offline para solicitudes HTML
    if (event.request.headers.get('accept').includes('text/html')) {
      return caches.match('/offline.html')
        .then(offlineResponse => {
          return offlineResponse || new Response('No hay conexión a internet', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: { 'Content-Type': 'text/html' }
          });
        });
    }

    // Para CSS/JS no cacheados, devolver una respuesta vacía para que la app no se rompa
    if (event.request.headers.get('accept').includes('text/css') ||
        event.request.headers.get('accept').includes('application/javascript')) {
      return new Response('/* Recurso no disponible offline */', {
        headers: { 'Content-Type': 'text/css' }
      });
    }

    // Para otros recursos, devolver un error simple
    return new Response('Recurso no disponible offline', {
      status: 404,
      headers: { 'Content-Type': 'text/plain' }
    });
  }
}

// Interceptar solicitudes fetch
self.addEventListener('fetch', event => {
  event.respondWith(handleFetch(event));
});
