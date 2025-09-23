// Configuración centralizada de IndexedDB
const MOOLLISH_DB_CONFIG = {
    name: 'moollish_offline_db',
    version: 10,
    stores: {
        pending_registros: { keyPath: 'id', autoIncrement: true },
        pending_entradas: { keyPath: 'id', autoIncrement: true },
        pending_salidas: { keyPath: 'id', autoIncrement: true },
        pending_animales: { keyPath: 'id', autoIncrement: true },
        pending_predios: { keyPath: 'id', autoIncrement: true },
        id_mappings: { keyPath: 'temp_id' }
    }
};

// Función para inicializar la base de datos
function initializeDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(MOOLLISH_DB_CONFIG.name, MOOLLISH_DB_CONFIG.version);

        request.onerror = (event) => {
            console.error('Error al abrir IndexedDB:', event.target.error);
            reject(event.target.error);
        };

        request.onsuccess = (event) => {
            const db = event.target.result;
         /*    console.log('IndexedDB inicializada correctamente, versión:', db.version); */

            // Verificar que todos los stores existan
            const storeNames = Array.from(db.objectStoreNames);
            const configStores = Object.keys(MOOLLISH_DB_CONFIG.stores);
            const missingStores = configStores.filter(store => !storeNames.includes(store));

            if (missingStores.length > 0) {
                console.warn('¡Atención! Faltan los siguientes stores en la base de datos:', missingStores.join(', '));
                console.warn('Cerrando conexión e incrementando versión para recrear stores...');
                db.close();

                // Si faltan stores, incrementar la versión y actualizar la configuración
                MOOLLISH_DB_CONFIG.version++;
/*                 console.log('Incrementando versión a', MOOLLISH_DB_CONFIG.version); */
                // Llamar recursivamente para forzar la actualización de la BD
                initializeDB().then(resolve).catch(reject);
                return;
            }

            resolve(db);
        };

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            const oldVersion = event.oldVersion;
      /*       console.log(`Actualizando IndexedDB de v${oldVersion} a v${MOOLLISH_DB_CONFIG.version}`); */

            // En vez de verificar versiones específicas, asegurar que todos los stores configurados existan
/*             console.log('Verificando y creando todos los stores configurados...'); */

            // Crear o recrear todos los stores
            Object.keys(MOOLLISH_DB_CONFIG.stores).forEach(storeName => {
               /*  console.log(`Verificando store "${storeName}"...`); */

                if (storeName === 'id_mappings') {
                    // Manejo especial para el store de mapeos
                    if (db.objectStoreNames.contains(storeName)) {
                      /*   console.log(`Eliminando store de mapeo "${storeName}" existente...`); */
                        db.deleteObjectStore(storeName);
                    }
                    createMappingStore(db, storeName);
                } else {
                    // Manejo para stores normales
                    if (db.objectStoreNames.contains(storeName)) {
                        /* console.log(`Eliminando store "${storeName}" existente...`); */
                        db.deleteObjectStore(storeName);
                    }
                    createStore(db, storeName);
                }
            });

            /* console.log('Actualización de stores completada.'); */
        };
    });
}

// Función auxiliar para crear stores
function createStore(db, storeName) {
    if (db.objectStoreNames.contains(storeName)) {
        db.deleteObjectStore(storeName);
    }
    const store = db.createObjectStore(storeName, MOOLLISH_DB_CONFIG.stores[storeName]);
    store.createIndex('timestamp', 'timestamp', { unique: false });
/*     console.log(`Store "${storeName}" creado/actualizado`); */
}

// Función auxiliar específica para crear el store de mapeo
function createMappingStore(db, storeName) {
    if (db.objectStoreNames.contains(storeName)) {
        console.warn(`Store "${storeName}" ya existe. Será eliminado y recreado.`);
        db.deleteObjectStore(storeName);
    }
    const store = db.createObjectStore(storeName, MOOLLISH_DB_CONFIG.stores[storeName]);
    store.createIndex('real_id', 'real_id', { unique: false });
    store.createIndex('tipo', 'tipo', { unique: false });
 /*    console.log(`Store de mapeo "${storeName}" creado/actualizado`); */
}

// Función para verificar si un store existe
function checkStoreExists(db, storeName) {
    return db.objectStoreNames.contains(storeName);
}

// Función genérica para guardar datos
function saveOfflineData(db, storeName, data) {
    return new Promise((resolve, reject) => {
        if (!checkStoreExists(db, storeName)) {
            reject(new Error(`El store "${storeName}" no existe`));
            return;
        }

        try {
            const transaction = db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);

            const dataToSave = {
                ...data,
                timestamp: Date.now()
            };

            const request = store.add(dataToSave);

            request.onsuccess = () => {
               /*  console.log(`Datos guardados en ${storeName} con ID:`, request.result); */
                resolve(request.result);
            };

            request.onerror = () => {
                console.error(`Error al guardar en ${storeName}:`, request.error);
                reject(request.error);
            };
        } catch (error) {
            console.error('Error en la transacción:', error);
            reject(error);
        }
    });
}

// Función específica para guardar/actualizar mapeos de ID
function saveMapping(db, mappingData) {
    const storeName = 'id_mappings';
    return new Promise((resolve, reject) => {
        if (!checkStoreExists(db, storeName)) {
            reject(new Error(`El store "${storeName}" no existe`));
            return;
        }
        try {
            const transaction = db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            const request = store.put(mappingData);

            request.onsuccess = () => {
               /*  console.log(`Mapeo guardado/actualizado: ${mappingData.temp_id} -> ${mappingData.real_id}`); */
                resolve(request.result);
            };
            request.onerror = () => {
                console.error(`Error al guardar/actualizar mapeo para ${mappingData.temp_id}:`, request.error);
                reject(request.error);
            };
        } catch (error) {
            console.error(`Error en la transacción de guardado de mapeo:`, error);
            reject(error);
        }
    });
}

// Función específica para obtener un mapeo por ID temporal
function getMappingByTempId(db, tempId) {
    const storeName = 'id_mappings';
    return new Promise((resolve, reject) => {
        if (!checkStoreExists(db, storeName)) {
            reject(new Error(`El store "${storeName}" no existe`));
            return;
        }
        try {
            const transaction = db.transaction([storeName], 'readonly');
            const store = transaction.objectStore(storeName);
            const request = store.get(tempId);

            request.onsuccess = () => {
                resolve(request.result);
            };
            request.onerror = () => {
                console.error(`Error al obtener mapeo para ${tempId}:`, request.error);
                reject(request.error);
            };
        } catch (error) {
            console.error(`Error en la transacción de obtención de mapeo:`, error);
            reject(error);
        }
    });
}

// Función genérica para obtener datos pendientes
function getPendingData(db, storeName) {
    return new Promise((resolve, reject) => {
        if (!checkStoreExists(db, storeName)) {
            reject(new Error(`El store "${storeName}" no existe`));
            return;
        }

        try {
            const transaction = db.transaction([storeName], 'readonly');
            const store = transaction.objectStore(storeName);
            const request = store.getAll();

            request.onsuccess = () => {
               /*  console.log(`Datos obtenidos de ${storeName}:`, request.result.length); */
                resolve(request.result);
            };

            request.onerror = () => {
                console.error(`Error al obtener datos de ${storeName}:`, request.error);
                reject(request.error);
            };
        } catch (error) {
            console.error('Error en la transacción:', error);
            reject(error);
        }
    });
}

// Función genérica para eliminar datos
function removeData(db, storeName, id) {
    return new Promise((resolve, reject) => {
        if (!checkStoreExists(db, storeName)) {
            reject(new Error(`El store "${storeName}" no existe`));
            return;
        }

        try {
            const transaction = db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            const request = store.delete(id);

            request.onsuccess = () => {
              /*   console.log(`Registro eliminado de ${storeName}:`, id); */
                resolve();
            };

            request.onerror = () => {
                console.error(`Error al eliminar de ${storeName}:`, request.error);
                reject(request.error);
            };
        } catch (error) {
            console.error('Error en la transacción:', error);
            reject(error);
        }
    });
}

// Exportar las funciones y configuración
window.MoollishDB = {
    config: MOOLLISH_DB_CONFIG,
    initialize: initializeDB,
    checkStore: checkStoreExists,
    saveData: saveOfflineData,
    getPendingData: getPendingData,
    removeData: removeData,
    saveMapping: saveMapping,
    getMappingByTempId: getMappingByTempId
};
