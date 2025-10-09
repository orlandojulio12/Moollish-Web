/**
 * Gestor de funcionalidades offline para la aplicación Moollish
 * Este script permite gestionar el contenido disponible sin conexión a internet
 */

class OfflineManager {
    constructor() {
        this.CACHE_NAME = 'moollish-cache-v1';
        this.DB_NAME = 'moollish_fallback_storage';
        this.DB_VERSION = 1;
        this.STORE_NAME = 'cached_resources';
        this.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        this.useFallbackStorage = false;
        this.fallbackDB = null;

        this.initStorage();
        this.initEvents();
    }

    /**
     * Inicializa el almacenamiento, intentando usar Cache API primero
     * o IndexedDB como alternativa
     */
    async initStorage() {
        // Verificar si Cache API está disponible
        if (!('caches' in window)) {
            console.warn('Cache API no está disponible, usando IndexedDB como alternativa');
            this.useFallbackStorage = true;
            await this.initIndexedDB();
        } else {
            try {
                // Intentar abrir el caché para verificar si realmente funciona
                const cache = await caches.open(this.CACHE_NAME);
                // Prueba simple para verificar que podemos usar el caché
                const testResponse = new Response('test');
                await cache.put(new URL('/test-cache-availability', window.location.origin).href, testResponse);
                await cache.delete(new URL('/test-cache-availability', window.location.origin).href);
                console.log('Cache API está disponible y funciona correctamente');
            } catch (error) {
                console.warn('Error al usar Cache API:', error);
                console.warn('Usando IndexedDB como alternativa');
                this.useFallbackStorage = true;
                await this.initIndexedDB();
            }
        }
    }

    /**
     * Inicializa IndexedDB como alternativa a Cache API
     */
    async initIndexedDB() {
        if (!('indexedDB' in window)) {
            console.error('IndexedDB no está disponible');
            return false;
        }

        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.DB_NAME, this.DB_VERSION);

            request.onerror = (event) => {
                console.error('Error al abrir IndexedDB:', event.target.error);
                reject('Error al abrir IndexedDB');
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains(this.STORE_NAME)) {
                    db.createObjectStore(this.STORE_NAME, { keyPath: 'url' });
                    console.log('Almacén de objetos creado en IndexedDB');
                }
            };

            request.onsuccess = (event) => {
                this.fallbackDB = event.target.result;
                console.log('IndexedDB inicializada como almacenamiento alternativo');
                resolve(true);
            };
        });
    }

    /**
     * Inicializa los eventos y detecta el estado de la conexión
     */
    initEvents() {
        // Detectar estado de la conexión
        window.addEventListener('online', () => this.handleConnectionChange(true));
        window.addEventListener('offline', () => this.handleConnectionChange(false));

        // Verificar estado inicial
        this.updateUI(navigator.onLine);
    }

    /**
     * Maneja los cambios en la conexión
     * @param {boolean} isOnline - Estado de la conexión
     */
    handleConnectionChange(isOnline) {
        this.updateUI(isOnline);

        if (isOnline) {
            // Intentar sincronizar cuando se recupera la conexión
            this.syncPendingData();
        }
    }

    /**
     * Actualiza la interfaz según el estado de la conexión
     * @param {boolean} isOnline - Estado de la conexión
     */
    updateUI(isOnline) {
        const statusElement = document.getElementById('connection-status');
        if (!statusElement) return;

        if (isOnline) {
            statusElement.textContent = 'Online';
            statusElement.className = 'badge bg-success';
        } else {
            statusElement.textContent = 'Offline';
            statusElement.className = 'badge bg-danger';
        }
    }

    /**
     * Sincroniza los datos pendientes cuando se recupera la conexión
     */
    syncPendingData() {
        // Aquí implementa la lógica para sincronizar datos cuando vuelve la conexión
        console.log('Sincronizando datos pendientes...');
    }

    /**
     * Obtiene el espacio de almacenamiento usado por la aplicación
     * @returns {Promise<Object>} - Información sobre el almacenamiento
     */
    async getStorageEstimate() {
        if (!('storage' in navigator) || !('estimate' in navigator.storage)) {
            return {
                supported: false,
                usedMB: 0,
                totalMB: 0,
                percentUsed: 0
            };
        }

        try {
            const estimate = await navigator.storage.estimate();
            const usedSpace = estimate.usage || 0;
            const totalSpace = estimate.quota || 0;
            const percentUsed = Math.round((usedSpace / totalSpace) * 100);

            return {
                supported: true,
                usedMB: Math.round(usedSpace / (1024 * 1024)),
                totalMB: Math.round(totalSpace / (1024 * 1024)),
                percentUsed: percentUsed
            };
        } catch (error) {
            console.error('Error al obtener información de almacenamiento:', error);
            return {
                supported: false,
                error,
                usedMB: 0,
                totalMB: 0,
                percentUsed: 0
            };
        }
    }

    /**
     * Guarda una URL en caché para uso offline
     * @param {string} url - URL a cachear
     * @returns {Promise<boolean>} - Resultado de la operación
     */
    async cacheUrl(url) {
        // Usar IndexedDB si Cache API no está disponible
        if (this.useFallbackStorage) {
            return this.cacheUrlInIndexedDB(url);
        }

        try {
            const cache = await caches.open(this.CACHE_NAME);
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Error al obtener ${url}: ${response.status} ${response.statusText}`);
            }
            await cache.put(url, response);
            console.log(`URL cacheada: ${url}`);
            return true;
        } catch (error) {
            console.error(`Error al cachear URL ${url} en Cache API:`, error);

            // Intentar usar IndexedDB como alternativa
            if (!this.useFallbackStorage) {
                console.log('Intentando usar IndexedDB como alternativa...');
                this.useFallbackStorage = true;
                await this.initIndexedDB();
                return this.cacheUrlInIndexedDB(url);
            }

            return false;
        }
    }

    /**
     * Guarda una URL en IndexedDB como alternativa a Cache API
     * @param {string} url - URL a cachear
     * @returns {Promise<boolean>} - Resultado de la operación
     */
    async cacheUrlInIndexedDB(url) {
        if (!this.fallbackDB) {
            console.error('IndexedDB no está disponible como almacenamiento alternativo');
            return false;
        }

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Error al obtener ${url}: ${response.status} ${response.statusText}`);
            }

            const responseText = await response.text();
            const contentType = response.headers.get('Content-Type') || 'text/html';

            return new Promise((resolve, reject) => {
                const transaction = this.fallbackDB.transaction([this.STORE_NAME], 'readwrite');
                const store = transaction.objectStore(this.STORE_NAME);

                const request = store.put({
                    url: url,
                    content: responseText,
                    contentType: contentType,
                    timestamp: Date.now()
                });

                request.onsuccess = () => {
                    console.log(`URL cacheada en IndexedDB: ${url}`);
                    resolve(true);
                };

                request.onerror = (event) => {
                    console.error(`Error al guardar URL en IndexedDB: ${url}`, event.target.error);
                    reject(false);
                };
            });
        } catch (error) {
            console.error(`Error al cachear URL ${url} en IndexedDB:`, error);
            return false;
        }
    }

    /**
     * Descarga una sección completa para uso offline
     * @param {string[]} urls - Lista de URLs a cachear
     * @returns {Promise<number>} - Número de URLs cacheadas con éxito
     */
    async downloadUrls(urls) {
        if (!navigator.onLine) {
            console.warn('No hay conexión a internet para descargar contenido');
            return 0;
        }

        let successCount = 0;

        for (const url of urls) {
            try {
                const success = await this.cacheUrl(url);
                if (success) successCount++;
            } catch (error) {
                console.error(`Error al descargar ${url}:`, error);
            }
        }

        return successCount;
    }

    /**
     * Limpia todo el caché de la aplicación
     * @returns {Promise<boolean>} - Resultado de la operación
     */
    async clearCache() {
        let success = true;

        // Limpiar Cache API
        if ('caches' in window) {
            try {
                const cacheNames = await caches.keys();
                await Promise.all(cacheNames.map(name => caches.delete(name)));
                console.log('Cache API limpiada correctamente');
            } catch (error) {
                console.error('Error al limpiar Cache API:', error);
                success = false;
            }
        }

        // Limpiar IndexedDB
        if (this.fallbackDB) {
            try {
                return new Promise((resolve, reject) => {
                    const transaction = this.fallbackDB.transaction([this.STORE_NAME], 'readwrite');
                    const store = transaction.objectStore(this.STORE_NAME);
                    const request = store.clear();

                    request.onsuccess = () => {
                        console.log('IndexedDB limpiada correctamente');
                        resolve(true && success);
                    };

                    request.onerror = (event) => {
                        console.error('Error al limpiar IndexedDB:', event.target.error);
                        resolve(false);
                    };
                });
            } catch (error) {
                console.error('Error al limpiar IndexedDB:', error);
                return false;
            }
        }

        return success;
    }

    /**
     * Verifica si una URL está almacenada en IndexedDB
     * @param {string} url - URL a verificar
     * @returns {Promise<boolean>} - true si está en IndexedDB, false si no
     */
    async isInIndexedDB(url) {
        if (!this.fallbackDB) {
            await this.initIndexedDB();
            if (!this.fallbackDB) return false;
        }

        return new Promise(resolve => {
            try {
                const transaction = this.fallbackDB.transaction([this.STORE_NAME], 'readonly');
                const store = transaction.objectStore(this.STORE_NAME);
                const request = store.get(url);

                request.onsuccess = () => {
                    resolve(!!request.result); // Convertir a booleano
                };

                request.onerror = () => {
                    console.error(`Error al verificar URL en IndexedDB: ${url}`);
                    resolve(false);
                };
            } catch (error) {
                console.error('Error al verificar IndexedDB:', error);
                resolve(false);
            }
        });
    }

    /**
     * Verifica si una URL está en caché (Cache API o IndexedDB)
     * @param {string} url - URL a verificar
     * @returns {Promise<boolean>} - true si está en caché, false si no
     */
    async isInCache(url) {
        // Verificar primero en Cache API
        if (!this.useFallbackStorage && 'caches' in window) {
            try {
                const cache = await caches.open(this.CACHE_NAME);
                const cachedResponse = await cache.match(url);
                if (cachedResponse) return true;
            } catch (error) {
                console.error('Error al verificar Cache API:', error);
            }
        }

        // Si no está en Cache API o estamos usando IndexedDB, verificar ahí
        if (this.useFallbackStorage || !('caches' in window)) {
            return this.isInIndexedDB(url);
        }

        return false;
    }

    /**
     * Obtiene todas las URLs en caché
     * @returns {Promise<string[]>} - Array de URLs en caché
     */
    async getCachedUrls() {
        const urls = [];

        // Obtener de Cache API
        if (!this.useFallbackStorage && 'caches' in window) {
            try {
                const cache = await caches.open(this.CACHE_NAME);
                const keys = await cache.keys();
                keys.forEach(request => {
                    urls.push(request.url);
                });
            } catch (error) {
                console.error('Error al obtener URLs de Cache API:', error);
            }
        }

        // Obtener de IndexedDB
        if (this.useFallbackStorage || urls.length === 0) {
            if (!this.fallbackDB) {
                await this.initIndexedDB();
                if (!this.fallbackDB) return urls;
            }

            try {
                return new Promise((resolve, reject) => {
                    const transaction = this.fallbackDB.transaction([this.STORE_NAME], 'readonly');
                    const store = transaction.objectStore(this.STORE_NAME);
                    const request = store.getAllKeys();

                    request.onsuccess = () => {
                        const dbUrls = request.result || [];
                        resolve([...new Set([...urls, ...dbUrls])]);
                    };

                    request.onerror = (event) => {
                        console.error('Error al obtener URLs de IndexedDB:', event);
                        resolve(urls); // Devolver lo que tengamos
                    };
                });
            } catch (error) {
                console.error('Error al obtener URLs de IndexedDB:', error);
            }
        }

        return urls;
    }
}

// Inicializar el gestor cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    window.offlineManager = new OfflineManager();
});
