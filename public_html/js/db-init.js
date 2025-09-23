/**
 * Inicialización simplificada de la base de datos IndexedDB para Moollish
 */

(function() {
    // Configuración de la base de datos
    const DB_NAME = 'moollish_offline_db';
    const STORES = {
        PREDIOS: 'pending_predios',
        ANIMALES: 'pending_animales',
        PESOS: 'pending_pesos',
        RELACIONES: 'offline_relations',
        INSUMOS: 'pending_insumos',
        ENTRADAS: 'pending_entradas_insumos',
        SALIDAS: 'pending_salidas_insumos'
    };

    // Variable global para la conexión a la base de datos
    let db = null;

    // Función para inicializar la base de datos
    function initDB() {
      /*   console.log('Inicializando base de datos IndexedDB...'); */
        return new Promise((resolve, reject) => {
            if (!window.indexedDB) {
                const error = 'Su navegador no soporta IndexedDB';
                console.error(error);
                reject(error);
                return;
            }

            // Usar la versión centralizada de MOOLLISH_DB_CONFIG
            const version = window.MoollishDB && window.MoollishDB.config ? window.MoollishDB.config.version : 1; // Fallback a 1 si no está listo
            if (!window.MoollishDB || !window.MoollishDB.config) {
                console.warn('[db-init] MoollishDB.config no está disponible todavía, usando versión fallback:', version);
            }

            const request = window.indexedDB.open(DB_NAME, version);

            request.onerror = (event) => {
                const error = 'Error al abrir la base de datos: ' + event.target.error;
                console.error(error);
                reject(error);
            };

            request.onupgradeneeded = (event) => {
             /*    console.log('Creando o actualizando la estructura de la base de datos'); */
                const oldVersion = event.oldVersion;
                const newVersion = event.newVersion;
                /* console.log(`Actualizando de versión ${oldVersion} a versión ${newVersion}`); */

                db = event.target.result;

                // Crear los object stores si no existen
                if (!db.objectStoreNames.contains(STORES.PREDIOS)) {
                    const predioStore = db.createObjectStore(STORES.PREDIOS, { keyPath: 'id', autoIncrement: true });
                    predioStore.createIndex('timestamp', 'timestamp', { unique: false });
/*                     console.log('Almacén de predios creado'); */
                }

                if (!db.objectStoreNames.contains(STORES.ANIMALES)) {
                    const animalStore = db.createObjectStore(STORES.ANIMALES, { keyPath: 'id', autoIncrement: true });
                    animalStore.createIndex('timestamp', 'timestamp', { unique: false });
                    animalStore.createIndex('predio_id', 'predio_id', { unique: false });
            /*         console.log('Almacén de animales creado'); */
                }

                if (!db.objectStoreNames.contains(STORES.PESOS)) {
                    const pesoStore = db.createObjectStore(STORES.PESOS, { keyPath: 'id', autoIncrement: true });
                    pesoStore.createIndex('timestamp', 'timestamp', { unique: false });
                    pesoStore.createIndex('id_animal', 'id_animal', { unique: false });
     /*                console.log('Almacén de pesos creado'); */
                }

                if (!db.objectStoreNames.contains(STORES.RELACIONES)) {
                    const relationStore = db.createObjectStore(STORES.RELACIONES, { keyPath: 'id', autoIncrement: true });
                    relationStore.createIndex('parent_entity', 'parent_entity', { unique: false });
                    relationStore.createIndex('parent_temp_id', 'parent_temp_id', { unique: false });
                  /*   console.log('Almacén de relaciones creado'); */
                }

                if (!db.objectStoreNames.contains(STORES.INSUMOS)) {
                    const insumoStore = db.createObjectStore(STORES.INSUMOS, { keyPath: 'id', autoIncrement: true });
                    insumoStore.createIndex('timestamp', 'timestamp', { unique: false });
                    insumoStore.createIndex('predio_id', 'predio_id', { unique: false });
               /*      console.log('Almacén de insumos creado'); */
                }

                if (!db.objectStoreNames.contains(STORES.ENTRADAS)) {
                    const entradaStore = db.createObjectStore(STORES.ENTRADAS, { keyPath: 'id', autoIncrement: true });
                    entradaStore.createIndex('timestamp', 'timestamp', { unique: false });
                  /*   console.log('Almacén de entradas creado'); */
                }

                if (!db.objectStoreNames.contains(STORES.SALIDAS)) {
                    const salidaStore = db.createObjectStore(STORES.SALIDAS, { keyPath: 'id', autoIncrement: true });
                    salidaStore.createIndex('timestamp', 'timestamp', { unique: false });
                  /*   console.log('Almacén de salidas creado'); */
                }
            };

            request.onsuccess = (event) => {
                db = event.target.result;
                console.log('Base de datos IndexedDB abierta con éxito. Versión:', db.version);
                console.log('Stores disponibles:', Array.from(db.objectStoreNames));
                resolve(db);

                // Disparar evento de base de datos lista
                window.dispatchEvent(new CustomEvent('moollish:db-ready'));
            };
        });
    }

    // Función para guardar un animal offline
    function guardarAnimal(formData) {
        return new Promise((resolve, reject) => {
            if (!db) {
                reject('La base de datos no está inicializada');
                return;
            }

            // Convertir FormData a objeto para almacenar
            const animalData = {};
            for (const [key, value] of formData.entries()) {
                animalData[key] = value;
            }

            animalData.timestamp = new Date().getTime();
            animalData.sincronizado = false;

            try {
                const transaction = db.transaction([STORES.ANIMALES], 'readwrite');
                const store = transaction.objectStore(STORES.ANIMALES);
                const request = store.add(animalData);

                request.onsuccess = () => {
                    const tempId = request.result;
                    console.log('Animal guardado con ID temporal:', tempId);
                    resolve(tempId);
                };

                request.onerror = (event) => {
                    console.error('Error al guardar animal:', event.target.error);
                    reject('Error al guardar animal: ' + event.target.error);
                };
            } catch (error) {
                console.error('Error en la transacción:', error);
                reject('Error en la transacción: ' + error.message);
            }
        });
    }

    // Función para obtener animales pendientes
    function obtenerAnimalesPendientes() {
        return new Promise((resolve, reject) => {
            if (!db) {
                resolve([]);
                return;
            }

            try {
                const transaction = db.transaction([STORES.ANIMALES], 'readonly');
                const store = transaction.objectStore(STORES.ANIMALES);
                const request = store.getAll();

                request.onsuccess = () => {
                    const animales = request.result.filter(a => !a.sincronizado);
                    console.log(`Se encontraron ${animales.length} animales pendientes`);
                    resolve(animales);
                };

                request.onerror = (event) => {
                    console.error('Error al obtener animales pendientes:', event.target.error);
                    reject('Error al obtener animales pendientes');
                };
            } catch (error) {
                console.error('Error en la transacción:', error);
                resolve([]);
            }
        });
    }

    // Función para sincronizar animales
    function sincronizarAnimales() {
        return new Promise((resolve, reject) => {
            if (!db) {
                reject('La base de datos no está inicializada');
                return;
            }

            if (!navigator.onLine) {
                reject('Sin conexión a internet');
                return;
            }

            obtenerAnimalesPendientes()
                .then(animales => {
                    if (animales.length === 0) {
                        console.log('No hay animales pendientes para sincronizar');
                        resolve([]);
                        return;
                    }

                    console.log(`Sincronizando ${animales.length} animales...`);

                    // Aquí iría el código para enviar los animales al servidor
                    // Por ahora, simulamos que se sincronizan con éxito
                    setTimeout(() => {
                        console.log('Animales sincronizados con éxito');
                        resolve(animales);
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error al sincronizar:', error);
                    reject(error);
                });
        });
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Iniciando sistema offline...');
        initDB()
            .then(() => {
                console.log('Inicialización completa');

                // Inicializar el OfflineManager
                if (!window.OfflineManager) {
                    window.OfflineManager = {
                        guardarAnimal,
                        obtenerAnimalesPendientes,
                        sincronizarAnimales
                    };
                    console.log('OfflineManager inicializado correctamente');
                } else {
                    console.warn('OfflineManager ya existía, se omitió la inicialización');
                }
            })
            .catch(error => {
                console.error('Error al inicializar la base de datos:', error);
            });
    });
})();
