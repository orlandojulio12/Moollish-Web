/**
 * Verificador de base de datos IndexedDB para Moollish
 * Este script garantiza que la base de datos se inicialice correctamente
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

    // Usar la versión centralizada de MOOLLISH_DB_CONFIG
    const version = window.MoollishDB && window.MoollishDB.config ? window.MoollishDB.config.version : 1; // Fallback a 1
    if (!window.MoollishDB || !window.MoollishDB.config) {
        console.warn('[db-checker] MoollishDB.config no está disponible todavía, usando versión fallback:', version);
    }

    // Función para verificar y crear la base de datos
    function checkAndCreateDB() {
        return new Promise((resolve, reject) => {
    /*         console.log('Verificando base de datos IndexedDB...'); */

            if (!window.indexedDB) {
                console.error('Su navegador no soporta IndexedDB');
                reject('IndexedDB no soportado');
                return;
            }

            // Intentar abrir la base de datos con la versión centralizada
            const request = window.indexedDB.open(DB_NAME, version);

            request.onerror = function(event) {
                console.error('Error al abrir o crear la base de datos:', event.target.error);
                reject('Error al abrir la base de datos: ' + event.target.error);
            };

            request.onsuccess = function(event) {
                const db = event.target.result;
               /*  console.log('Base de datos abierta correctamente. Version:', db.version); */

                // Verificar que todos los stores existen
                const storeNames = Object.values(STORES);
                const missingStores = storeNames.filter(store => !db.objectStoreNames.contains(store));

                if (missingStores.length > 0) {
                    console.warn('Faltan algunos stores en la base de datos:', missingStores);
                    // Si faltan stores, cerramos y recreamos con una versión superior
                    db.close();
                    const upgradeRequest = window.indexedDB.open(DB_NAME, db.version + 1);
                    upgradeRequest.onupgradeneeded = createStores;
                    upgradeRequest.onsuccess = function() {
                     /*    console.log('Base de datos actualizada con los stores faltantes'); */
                        resolve(true);
                    };
                    upgradeRequest.onerror = function(event) {
                        console.error('Error al actualizar la base de datos:', event.target.error);
                        reject('Error al actualizar: ' + event.target.error);
                    };
                } else {
                    /* console.log('Todos los stores necesarios existen en la base de datos'); */
                    resolve(true);
                }

                db.close();
            };

            // Esta función se ejecuta cuando se necesita crear o actualizar la estructura
            request.onupgradeneeded = createStores;
        });
    }

    // Función para crear los stores en la base de datos
    function createStores(event) {
        const db = event.target.result;
        console.log('Creando/actualizando estructura de la base de datos a versión', event.newVersion);

        // Crear cada uno de los stores si no existen
        if (!db.objectStoreNames.contains(STORES.PREDIOS)) {
            const predioStore = db.createObjectStore(STORES.PREDIOS, { keyPath: 'id', autoIncrement: true });
            predioStore.createIndex('timestamp', 'timestamp', { unique: false });
            console.log('Store de predios creado');
        }

        if (!db.objectStoreNames.contains(STORES.ANIMALES)) {
            const animalStore = db.createObjectStore(STORES.ANIMALES, { keyPath: 'id', autoIncrement: true });
            animalStore.createIndex('timestamp', 'timestamp', { unique: false });
            animalStore.createIndex('predio_id', 'predio_id', { unique: false });
            animalStore.createIndex('temp_predio_id', 'temp_predio_id', { unique: false });
            console.log('Store de animales creado');
        }

        if (!db.objectStoreNames.contains(STORES.PESOS)) {
            const pesoStore = db.createObjectStore(STORES.PESOS, { keyPath: 'id', autoIncrement: true });
            pesoStore.createIndex('timestamp', 'timestamp', { unique: false });
            pesoStore.createIndex('id_animal', 'id_animal', { unique: false });
            pesoStore.createIndex('temp_animal_id', 'temp_animal_id', { unique: false });
            console.log('Store de pesos creado');
        }

        if (!db.objectStoreNames.contains(STORES.RELACIONES)) {
            const relationStore = db.createObjectStore(STORES.RELACIONES, { keyPath: 'id', autoIncrement: true });
            relationStore.createIndex('parent_entity', 'parent_entity', { unique: false });
            relationStore.createIndex('parent_temp_id', 'parent_temp_id', { unique: false });
            relationStore.createIndex('parent_real_id', 'parent_real_id', { unique: false });
            relationStore.createIndex('child_entity', 'child_entity', { unique: false });
            relationStore.createIndex('temp_id', 'temp_id', { unique: false });
            relationStore.createIndex('entity_type', 'entity_type', { unique: false });
            console.log('Store de relaciones creado');
        }

        if (!db.objectStoreNames.contains(STORES.INSUMOS)) {
            const insumoStore = db.createObjectStore(STORES.INSUMOS, { keyPath: 'id', autoIncrement: true });
            insumoStore.createIndex('timestamp', 'timestamp', { unique: false });
            insumoStore.createIndex('predio_id', 'predio_id', { unique: false });
            insumoStore.createIndex('temp_predio_id', 'temp_predio_id', { unique: false });
            console.log('Store de insumos creado');
        }

        if (!db.objectStoreNames.contains(STORES.ENTRADAS)) {
            const entradaStore = db.createObjectStore(STORES.ENTRADAS, { keyPath: 'id', autoIncrement: true });
            entradaStore.createIndex('timestamp', 'timestamp', { unique: false });
            entradaStore.createIndex('predio_id', 'predio_id', { unique: false });
            console.log('Store de entradas de insumos creado');
        }

        if (!db.objectStoreNames.contains(STORES.SALIDAS)) {
            const salidaStore = db.createObjectStore(STORES.SALIDAS, { keyPath: 'id', autoIncrement: true });
            salidaStore.createIndex('timestamp', 'timestamp', { unique: false });
            salidaStore.createIndex('predio_id', 'predio_id', { unique: false });
            console.log('Store de salidas de insumos creado');
        }
    }

    // Iniciar la verificación cuando el DOM esté cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            checkAndCreateDB()
                .then(() => {
                    console.log('Verificación de base de datos completada correctamente');
                    // Disparar un evento que indique que la base de datos está lista
                    window.dispatchEvent(new CustomEvent('moollish:db-ready'));
                })
                .catch(error => {
                    console.error('Error en la verificación de la base de datos:', error);
                    if (window.showAlert && typeof window.showAlert === 'function') {
                        window.showAlert('error', 'Error en la inicialización de la base de datos: ' + error);
                    }
                });
        });
    } else {
        checkAndCreateDB()
            .then(() => {
                console.log('Verificación de base de datos completada correctamente');
                window.dispatchEvent(new CustomEvent('moollish:db-ready'));
            })
            .catch(error => {
                console.error('Error en la verificación de la base de datos:', error);
                if (window.showAlert && typeof window.showAlert === 'function') {
                    window.showAlert('error', 'Error en la inicialización de la base de datos: ' + error);
                }
            });
    }
})();
