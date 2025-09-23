/**
 * Administrador Offline para Moollish
 * Gestiona almacenamiento y sincronización de datos offline
 */

(function() {
    // Configuración de la base de datos
    const DB_NAME = 'moollish_offline_db';
    // Eliminar DB_VERSION local
    // const DB_VERSION = 9; // Ya no se usa aquí
    const STORES = {
        PREDIOS: 'pending_predios',
        ANIMALES: 'pending_animales',
        PESOS: 'pending_pesos',
        RELACIONES: 'offline_relations',
        INSUMOS: 'pending_insumos',
        ENTRADAS: 'pending_entradas_insumos',
        SALIDAS: 'pending_salidas_insumos'
    };

    // variable que inicializa la base de datos en el servicio oflline indexedDB
    let db;

    // Indicador de si estamos en una operación de sincronización
    let isSyncing = false;

    // Variable para controlar si la base de datos está inicializada
    let isDBInitialized = false;

    /**
     * Inicializar la base de datos
     */
    function initializeDB() {
        return new Promise((resolve, reject) => {
            // Si ya está inicializada, retornar inmediatamente
            if (db && isDBInitialized) {
             /*    console.log('La base de datos ya está inicializada'); */
                resolve(db);
                return;
            }

          /*   console.log('Inicializando base de datos IndexedDB...'); */

            // Usar la versión centralizada de MOOLLISH_DB_CONFIG
            const version = window.MoollishDB && window.MoollishDB.config ? window.MoollishDB.config.version : 1; // Fallback a 1
            if (!window.MoollishDB || !window.MoollishDB.config) {
                console.warn('[offline-manager] MoollishDB.config no está disponible todavía, usando versión fallback:', version);
            }

            // Primero, intentar abrir con la versión actual para verificar
            const checkVersionRequest = indexedDB.open(DB_NAME);

            checkVersionRequest.onsuccess = (event) => {
                const existingDB = event.target.result;
                const existingVersion = existingDB.version;
              /*   console.log('Versión existente de la base de datos:', existingVersion); */

                // Cerrar la conexión temporal
                existingDB.close();

                // Determinar la versión correcta a usar
                // const versionToUse = Math.max(DB_VERSION, existingVersion); // Ya no se usa DB_VERSION
                const versionToUse = Math.max(version, existingVersion); // Usar la versión centralizada
 /*                console.log('Usando versión de base de datos:', versionToUse); */

                // Ahora abrir con la versión correcta
                const request = indexedDB.open(DB_NAME, versionToUse);

                request.onerror = (event) => {
                    console.error('Error al abrir la base de datos IndexedDB:', event.target.error);
                    reject('Error al abrir la base de datos: ' + event.target.error);
                };

                request.onsuccess = (event) => {
                    db = event.target.result;
                    isDBInitialized = true;
                 /*    console.log('Base de datos IndexedDB abierta con éxito. Versión:', db.version); */
                    resolve(db);
                };

                // onupgradeneeded se manejará por db-checker.js
                request.onupgradeneeded = (event) => {
                    console.log('Actualizando esquema de base de datos a versión', versionToUse);
                    // No hacemos nada aquí, ya que db-checker.js se encarga de crear los stores
                };
            };

            checkVersionRequest.onerror = (event) => {
                console.error('Error al verificar versión de IndexedDB:', event.target.error);

                // Si hay error al verificar, intentar con la versión centralizada
                const request = indexedDB.open(DB_NAME, version);

                request.onerror = (event) => {
                    console.error('Error al abrir la base de datos IndexedDB:', event.target.error);
                    reject('Error al abrir la base de datos: ' + event.target.error);
                };

                request.onsuccess = (event) => {
                    db = event.target.result;
                    isDBInitialized = true;
                   /*  console.log('Base de datos IndexedDB abierta con éxito. Versión:', db.version); */
                    resolve(db);
                };

                request.onupgradeneeded = (event) => {
                  /*   console.log('Actualizando esquema de base de datos a versión', version); */
                    // No hacemos nada aquí, ya que db-checker.js se encarga de crear los stores
                };
            };
        });
    }

    /**
     * Verificar si un almacén existe
     */
    function checkStoreExists(storeName) {
        return new Promise((resolve) => {
            if (!db) {
                resolve(false);
                return;
            }

            try {
                const transaction = db.transaction([storeName], 'readonly');
                transaction.onerror = () => resolve(false);
                transaction.oncomplete = () => resolve(true);
            } catch (e) {
               /*  console.log(`El almacén ${storeName} no existe:`, e); */
                resolve(false);
            }
        });
    }

    /**
     * Guardar una entidad en IndexedDB
     */
    function saveEntity(storeName, data) {
        return new Promise(async (resolve, reject) => {
            // Verificar si el almacén existe
            const storeExists = await checkStoreExists(storeName);
            if (!storeExists) {
                reject(`El almacén ${storeName} no está disponible`);
                return;
            }

            try {
                const transaction = db.transaction([storeName], 'readwrite');
                const store = transaction.objectStore(storeName);

                // Añadir timestamp
                data.timestamp = new Date().getTime();
                data.sincronizado = false;

                const request = store.add(data);

                request.onsuccess = () => {
                    const tempId = request.result;
                /*     console.log(`Entidad guardada en ${storeName} con ID temporal:`, tempId); */
                    resolve(tempId);
                };

                request.onerror = (event) => {
                    /* console.error(`Error al guardar en ${storeName}:`, event.target.error); */
                    reject(`Error al guardar en ${storeName}`);
                };
            } catch (e) {
               /*  console.error(`Error en la transacción para ${storeName}:`, e); */
                reject(`Error en la transacción: ${e.message}`);
            }
        });
    }

    /**
     * Guardar relación entre entidades
     * Ejemplo: animal relacionado con predio
     */
    function saveRelation(parentEntity, parentTempId, childEntity, childTempId) {
        return new Promise(async (resolve, reject) => {
            const storeExists = await checkStoreExists(STORES.RELACIONES);
            if (!storeExists) {
                reject('El almacén de relaciones no está disponible');
                return;
            }

            try {
                const transaction = db.transaction([STORES.RELACIONES], 'readwrite');
                const store = transaction.objectStore(STORES.RELACIONES);

                const relation = {
                    parent_entity: parentEntity,
                    parent_temp_id: parentTempId,
                    parent_real_id: null, // Se actualizará cuando se sincronice
                    child_entity: childEntity,
                    child_temp_id: childTempId,
                    child_real_id: null, // Se actualizará cuando se sincronice
                    timestamp: new Date().getTime()
                };

                const request = store.add(relation);

                request.onsuccess = () => {
                 /*    console.log('Relación guardada con ID:', request.result); */
                    resolve(request.result);
                };

                request.onerror = (event) => {
                    console.error('Error al guardar relación:', event.target.error);
                    reject('Error al guardar relación');
                };
            } catch (e) {
                console.error('Error en la transacción de relación:', e);
                reject(`Error en la transacción: ${e.message}`);
            }
        });
    }

    /**
     * Actualizar una relación con el ID real
     */
    function updateRelation(entityType, tempId, realId) {
        return new Promise(async (resolve, reject) => {
            const storeExists = await checkStoreExists(STORES.RELACIONES);
            if (!storeExists) {
                resolve(); // No hay error si no existe
                return;
            }

            try {
                const transaction = db.transaction([STORES.RELACIONES], 'readwrite');
                const store = transaction.objectStore(STORES.RELACIONES);

                // Buscar como padre
                const indexParent = store.index('parent_entity');
                const requestParent = indexParent.openCursor(IDBKeyRange.only(entityType));

                requestParent.onsuccess = (event) => {
                    const cursor = event.target.result;
                    if (cursor) {
                        if (cursor.value.parent_temp_id === tempId) {
                            const updateData = cursor.value;
                            updateData.parent_real_id = realId;
                            cursor.update(updateData);
                        }
                        cursor.continue();
                    }
                };

                // Buscar como hijo
                const requestAll = store.getAll();
                requestAll.onsuccess = (event) => {
                    const items = event.target.result;
                    for (const item of items) {
                        if (item.child_entity === entityType && item.child_temp_id === tempId) {
                            const tx = db.transaction([STORES.RELACIONES], 'readwrite');
                            const st = tx.objectStore(STORES.RELACIONES);
                            item.child_real_id = realId;
                            st.put(item);
                        }
                    }
                    resolve();
                };

                requestAll.onerror = (event) => {
                    console.error('Error al actualizar relaciones:', event.target.error);
                    reject('Error al actualizar relaciones');
                };
            } catch (e) {
                console.error('Error en transacción al actualizar relaciones:', e);
                reject(`Error: ${e.message}`);
            }
        });
    }

    /**
     * Obtener todas las entidades pendientes de un almacén
     */
    function getPendingEntities(storeName) {
        return new Promise(async (resolve, reject) => {
            const storeExists = await checkStoreExists(storeName);
            if (!storeExists) {
                resolve([]); // Devolver array vacío si no existe
                return;
            }

            try {
                const transaction = db.transaction([storeName], 'readonly');
                const store = transaction.objectStore(storeName);
                const request = store.getAll();

                request.onsuccess = () => {
                    resolve(request.result);
                };

                request.onerror = (event) => {
                    console.error(`Error al obtener entidades de ${storeName}:`, event.target.error);
                    reject(`Error al obtener entidades de ${storeName}`);
                };
            } catch (e) {
                console.error(`Error al obtener entidades de ${storeName}:`, e);
                resolve([]); // Devolver array vacío en caso de error
            }
        });
    }

    /**
     * Eliminar una entidad pendiente por su ID temporal
     */
    function removePendingEntity(storeName, id) {
        return new Promise(async (resolve, reject) => {
            const storeExists = await checkStoreExists(storeName);
            if (!storeExists) {
                console.warn(`[OfflineManager] Intento de eliminar de almacén no existente: ${storeName}`);
                reject(`El almacén ${storeName} no está disponible`);
                return;
            }

            console.log(`[OfflineManager] Intentando eliminar entidad con ID ${id} de ${storeName}...`);

            try {
                const transaction = db.transaction([storeName], 'readwrite');
                const store = transaction.objectStore(storeName);
                const request = store.delete(id);

                request.onsuccess = () => {
                    console.log(`[OfflineManager] Entidad ID ${id} eliminada con éxito de ${storeName}.`);
                    resolve();
                };

                request.onerror = (event) => {
                    console.error(`[OfflineManager] Error al eliminar entidad ID ${id} de ${storeName}:`, event.target.error);
                    reject(`Error al eliminar de ${storeName}: ${event.target.error}`);
                };
            } catch (e) {
                console.error(`[OfflineManager] Error en transacción al eliminar de ${storeName} (ID: ${id}):`, e);
                reject(`Error en transacción de eliminación: ${e.message}`);
            }
        });
    }

    /**
     * Guardar un animal desde FormData offline
     */
    async function guardarAnimal(formData) {
        const data = {};
        formData.forEach((value, key) => {
            if (value === 'null' || value === '') {
                data[key] = null;
            } else {
                data[key] = value;
            }
        });

        if (!data.id) {
            data.id = Date.now();
        }

        delete data._token;
        delete data.bypass_service_worker;

        // Usar id_predio consistente
        if (!data.id_predio) {
            console.error("Error: id_predio es requerido para guardar offline", data);
            throw new Error("El ID del predio es requerido para guardar offline.");
        }

        // Verificar si el id_predio es temporal
        if (data.id_predio.toString().startsWith('temp_')) {
            const tempIdPredio = parseInt(data.id_predio.toString().replace('temp_', ''));
            if (!isNaN(tempIdPredio)) {
                data.temp_id_predio = tempIdPredio; // Guardar ID temporal con nombre consistente
                data.id_predio = null; // Limpiar id_predio hasta que se sincronice
                 console.log(`Detectado predio temporal ${data.temp_id_predio}, se limpió id_predio.`);
            } else {
                console.error('Error al parsear el ID temporal del predio:', data.id_predio);
                // Considerar lanzar un error o manejarlo de otra forma
            }
        }

        if (formData.has('fecha_parto')) data.fecha_parto = formData.get('fecha_parto') || null;
        if (formData.has('tipo_parto')) data.tipo_parto = formData.get('tipo_parto') || null;

        console.log('Datos del animal a guardar offline (con id_predio consistente):', data);

        try {
            const tempAnimalId = await saveEntity(STORES.ANIMALES, data);
            console.log('Animal guardado offline con ID temporal:', tempAnimalId);

            // Si se guardó un predio temporal, registrar la relación
            if (data.temp_id_predio) {
                await saveRelation('predio', data.temp_id_predio, 'animal', tempAnimalId);
                console.log(`Relación guardada: Predio temporal ${data.temp_id_predio} -> Animal temporal ${tempAnimalId}`);
            }

            return tempAnimalId;
        } catch (error) {
            console.error('Error detallado al guardar animal offline:', error);
            throw error;
        }
    }

    /**
     * Guardar un peso relacionado con un animal (adaptado para PesajeAnimal)
     */
    async function guardarPeso(formData, animalTempId) {
        if (!db) {
            await initializeDB();
        }

        // Convertir FormData a objeto para almacenar
        const pesoData = {};
        for (const [key, value] of formData.entries()) {
            if (key !== 'id_animal_pesaje') { // Ignorar id_animal_pesaje que será reemplazado
                pesoData[key] = value;
            }
        }

        // Guardar relación temporal con el animal
        pesoData.temp_animal_id = parseInt(animalTempId);
        pesoData.id_animal = null; // Se actualizará con el ID real al sincronizar

        // Asegurarse de que existe fecha_pesaje (si no viene en el form)
        if (!pesoData.fecha_pesaje) {
            pesoData.fecha_pesaje = new Date().toISOString().split('T')[0];
        }

        // Guardar el peso
        const pesoTempId = await saveEntity(STORES.PESOS, pesoData);

        // Guardar la relación
        await saveRelation('animal', animalTempId, 'peso', pesoTempId);

        return pesoTempId;
    }

    /**
     * Sincronizar todas las entidades pendientes
     */
    async function sincronizarTodo() {
        if (isSyncing) {
            console.log('Ya hay una sincronización en progreso');
            return;
        }

        if (!navigator.onLine) {
            console.log('Sin conexión, no se puede sincronizar');
            return;
        }

        isSyncing = true;

        try {
            if (!db) {
                await initializeDB();
            }

            // 1. Primero sincronizar predios (entidades padre)
            await sincronizarPredios();

            // 2. Luego sincronizar animales (que dependen de predios)
            await sincronizarAnimales();

            // 3. Finalmente sincronizar pesos (que dependen de animales)
            await sincronizarPesos();

            console.log('Sincronización completa');

            // Disparar evento de sincronización completada
            const event = new CustomEvent('moollish:sync-completed');
            window.dispatchEvent(event);

        } catch (error) {
            console.error('Error durante la sincronización:', error);
        } finally {
            isSyncing = false;
        }
    }

    /**
     * Sincronizar predios pendientes
     */
    async function sincronizarPredios() {
        // Verificar si el almacén existe
        const storeExists = await checkStoreExists(STORES.PREDIOS);
        if (!storeExists) {
            console.log('El almacén de predios no existe, no hay nada que sincronizar');
            return;
        }

        const pendingPredios = await getPendingEntities(STORES.PREDIOS);
        console.log(`Intentando sincronizar ${pendingPredios.length} predios pendientes`);

        for (const predio of pendingPredios) {
            try {
                // Crear un objeto FormData para enviar
                const formData = new FormData();
                for (const key in predio) {
                    if (!['id', 'timestamp', 'sincronizado', 'temp_id'].includes(key)) {
                        formData.append(key, predio[key]);
                    }
                }
                // Añadir indicador de sincronización
                formData.append('is_sync', 'true');

                // Enviar el formulario
                const response = await $.ajax({
                    url: "/predios",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Guardar el mapeo del ID temporal al ID real
                if (response.id) {
                    localStorage.setItem(`predio_temp_${predio.id}_real_id`, response.id);

                    // Actualizar las relaciones con el ID real
                    await updateRelation('predio', predio.id, response.id);
                }

                // Eliminar el predio pendiente
                await removePendingEntity(STORES.PREDIOS, predio.id);
                console.log('Predio sincronizado correctamente:', predio.id);
            } catch (error) {
                console.error('Error al sincronizar predio:', predio.id, error);
            }
        }
    }

    /**
     * Sincronizar animales pendientes
     */
    async function sincronizarAnimales() {
        // Verificar si el almacén existe
        const storeExists = await checkStoreExists(STORES.ANIMALES);
        if (!storeExists) {
            console.log('El almacén de animales no existe, no hay nada que sincronizar');
            return;
        }

        const pendingAnimales = await getPendingEntities(STORES.ANIMALES);
        console.log(`Intentando sincronizar ${pendingAnimales.length} animales pendientes`);

        if (pendingAnimales.length === 0) {
            return;
        }

        // Mostrar mensaje de que se está sincronizando
        if (window.showAlert && typeof window.showAlert === 'function') {
            window.showAlert('warning', `Sincronizando ${pendingAnimales.length} animales pendientes...`);
        }

        let sincronizados = 0;
        let errores = 0;

        for (const animal of pendingAnimales) {
            try {
                // Si el animal tiene un predio temporal, necesitamos buscar el ID real
                if (animal.temp_predio_id) {
                    const realPredioId = localStorage.getItem(`predio_temp_${animal.temp_predio_id}_real_id`);
                    if (realPredioId) {
                        animal.predio_id = realPredioId;
                    } else {
                        console.warn('No se puede sincronizar el animal porque su predio aún no tiene ID real');
                        continue; // Saltar este animal e intentar con el siguiente
                    }
                }

                // Crear un objeto FormData para enviar
                const formData = new FormData();
                for (const key in animal) {
                    if (!['id', 'timestamp', 'sincronizado', 'temp_predio_id'].includes(key)) {
                        formData.append(key, animal[key]);
                    }
                }

                // Enviar el formulario usando la ruta correcta
                const response = await $.ajax({
                    url: "/animal/store",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Guardar el mapeo del ID temporal al ID real
                if (response.id) {
                    localStorage.setItem(`animal_temp_${animal.id}_real_id`, response.id);

                    // Actualizar las relaciones con el ID real
                    await updateRelation('animal', animal.id, response.id);
                }

                // Eliminar el animal pendiente
                await removePendingEntity(STORES.ANIMALES, animal.id);
                console.log('Animal sincronizado correctamente:', animal.id);
                sincronizados++;
            } catch (error) {
                console.error('Error al sincronizar animal:', animal.id, error);
                errores++;
            }
        }

        // Mostrar resultado de la sincronización
        if (window.showAlert && typeof window.showAlert === 'function') {
            if (sincronizados > 0 && errores === 0) {
                window.showAlert('success', `${sincronizados} animales sincronizados correctamente.`);
            } else if (sincronizados > 0 && errores > 0) {
                window.showAlert('warning', `${sincronizados} animales sincronizados. Hubo errores con ${errores} animales.`);
            } else if (sincronizados === 0 && errores > 0) {
                window.showAlert('error', `No se pudo sincronizar ningún animal. Hubo ${errores} errores.`);
            }
        }

        return { sincronizados, errores };
    }

    /**
     * Sincronizar pesos pendientes (adaptado para PesajeAnimal)
     */
    async function sincronizarPesos() {
        // Verificar si el almacén existe
        const storeExists = await checkStoreExists(STORES.PESOS);
        if (!storeExists) {
            console.log('El almacén de pesos no existe, no hay nada que sincronizar');
            return;
        }

        const pendingPesos = await getPendingEntities(STORES.PESOS);
        console.log(`Intentando sincronizar ${pendingPesos.length} pesos pendientes`);

        for (const peso of pendingPesos) {
            try {
                // Si el peso tiene un animal temporal, necesitamos buscar el ID real
                if (peso.temp_animal_id) {
                    const realAnimalId = localStorage.getItem(`animal_temp_${peso.temp_animal_id}_real_id`);
                    if (realAnimalId) {
                        peso.id_animal = realAnimalId;
                        peso.id_animal_pesaje = realAnimalId; // Adaptado para PesajeAnimalController
                    } else {
                        console.warn('No se puede sincronizar el peso porque su animal aún no tiene ID real');
                        continue; // Saltar este peso e intentar con el siguiente
                    }
                }

                // Crear un objeto FormData para enviar
                const formData = new FormData();
                for (const key in peso) {
                    if (!['id', 'timestamp', 'sincronizado', 'temp_animal_id'].includes(key)) {
                        formData.append(key, peso[key]);
                    }
                }

                // Enviar el formulario - adaptado para PesajeAnimal
                const response = await $.ajax({
                    url: "/pesaje",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Eliminar el peso pendiente
                await removePendingEntity(STORES.PESOS, peso.id);
                console.log('Peso sincronizado correctamente:', peso.id);
            } catch (error) {
                console.error('Error al sincronizar peso:', peso.id, error);
            }
        }
    }

    /**
     * Verificar si hay entidades pendientes de sincronización
     */
    async function hayPendientes() {
        if (!db) {
            await initializeDB();
        }

        try {
            const pendingPredios = await getPendingEntities(STORES.PREDIOS);
            const pendingAnimales = await getPendingEntities(STORES.ANIMALES);
            const pendingPesos = await getPendingEntities(STORES.PESOS);

            return {
                predios: pendingPredios.length,
                animales: pendingAnimales.length,
                pesos: pendingPesos.length,
                total: pendingPredios.length + pendingAnimales.length + pendingPesos.length
            };
        } catch (error) {
            console.error('Error al verificar pendientes:', error);
            return { total: 0 };
        }
    }

    /**
     * Verificar si hay una relación entre dos entidades
     */
    async function verificarRelacion(parentType, parentId, childType) {
        if (!db) {
            await initializeDB();
        }

        const storeExists = await checkStoreExists(STORES.RELACIONES);
        if (!storeExists) {
            return false;
        }

        return new Promise((resolve, reject) => {
            try {
                const transaction = db.transaction([STORES.RELACIONES], 'readonly');
                const store = transaction.objectStore(STORES.RELACIONES);
                const request = store.getAll();

                request.onsuccess = () => {
                    const relaciones = request.result;
                    const relacionEncontrada = relaciones.some(rel =>
                        rel.parent_entity === parentType &&
                        (rel.parent_temp_id === parentId || rel.parent_real_id === parentId) &&
                        rel.child_entity === childType
                    );
                    resolve(relacionEncontrada);
                };

                request.onerror = (event) => {
                    console.error('Error al verificar relación:', event.target.error);
                    reject('Error al verificar relación');
                };
            } catch (e) {
                console.error('Error en la transacción al verificar relación:', e);
                resolve(false);
            }
        });
    }

    /**
     * Inicializar el administrador offline
     */
    async function init() {
        console.log('Iniciando Administrador Offline...');
        try {
            // Verificar que IndexedDB está disponible
            if (!window.indexedDB) {
                console.error('IndexedDB no está disponible en este navegador');
                return;
            }

            console.log('IndexedDB está disponible, intentando inicializar base de datos...');
            await initializeDB();
            console.log('Administrador Offline inicializado correctamente');

            // Si hay conexión, intentar sincronizar al inicio
            if (navigator.onLine) {
                const pendientes = await hayPendientes();
                if (pendientes && pendientes.total > 0) {
                    console.log(`Hay ${pendientes.total} entidades pendientes de sincronización`);
                    sincronizarTodo();
                } else {
                    console.log('No hay entidades pendientes de sincronizar');
                }
            } else {
                console.log('Sin conexión, se omite la sincronización inicial');
            }

            // Disparar un evento para notificar que la inicialización ha terminado
            window.dispatchEvent(new CustomEvent('moollish:offline-manager-ready'));
        } catch (error) {
            console.error('Error al inicializar el Administrador Offline:', error);
            // Intentar mostrar el error visualmente si existe la función showAlert
            if (window.showAlert && typeof window.showAlert === 'function') {
                window.showAlert('error', 'Error al inicializar el almacenamiento offline: ' + error.message);
            }
        }
    }

    // Event listeners para conexión
    window.addEventListener('online', function() {
        console.log("Conexión restablecida");
        // Intentar sincronizar cuando hay conexión
        setTimeout(sincronizarTodo, 1000); // Pequeño delay para asegurar que la conexión es estable
    });

    // Esperar a que la base de datos esté lista antes de inicializar
    function initOfflineManager() {
        if (isDBInitialized) {
            init();
        } else {
            window.addEventListener('moollish:db-ready', function() {
                console.log('Base de datos lista, inicializando Offline Manager...');
                init();
            });

            // Agregar un timeout por si acaso el evento nunca se dispara
            setTimeout(() => {
                if (!isDBInitialized) {
                    console.warn('No se recibió el evento moollish:db-ready en el tiempo esperado, inicializando de todos modos...');
                    init();
                }
            }, 5000);
        }
    }

    // Inicializar cuando el DOM esté cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, verificando estado de la base de datos...');
            initOfflineManager();
        });
    } else {
        console.log('DOM ya cargado, verificando estado de la base de datos inmediatamente...');
        initOfflineManager();
    }

    /**
     * Obtener los animales pendientes de sincronización
     */
    async function obtenerAnimalesPendientes() {
        if (!db) {
            await initializeDB();
        }

        try {
            const storeExists = await checkStoreExists(STORES.ANIMALES);
            if (!storeExists) {
                return [];
            }

            const pendientes = await getPendingEntities(STORES.ANIMALES);
            return pendientes;
        } catch (error) {
            console.error('Error al obtener animales pendientes:', error);
            return [];
        }
    }

    /**
     * Contar los animales pendientes de sincronización
     */
    async function countPendingAnimales() {
        try {
            const animales = await obtenerAnimalesPendientes();
            return animales.length;
        } catch (error) {
            console.error('Error al contar animales pendientes:', error);
            return 0;
        }
    }

    // Exponer API pública
    window.OfflineManager = {
        guardarAnimal: guardarAnimal,
        guardarPeso: guardarPeso,
        sincronizarTodo: sincronizarTodo,
        sincronizarPredios: sincronizarPredios,
        sincronizarAnimales: sincronizarAnimales,
        sincronizarPesos: sincronizarPesos,
        hayPendientes: hayPendientes,
        verificarRelacion: verificarRelacion,
        removePendingEntity: removePendingEntity,
        obtenerAnimalesPendientes: obtenerAnimalesPendientes,
        countPendingAnimales: countPendingAnimales,
        updateRelation: updateRelation
    };

    // Agregar detector global para manejar errores de fetch
    window.addEventListener('DOMContentLoaded', function() {
        // Interceptar envíos de formulario para registro de animales
        $(document).on('submit', 'form[action="/animal/store"]', function(e) {
            if (!navigator.onLine) {
                e.preventDefault(); // Evitar envío normal del formulario
                console.log('Modo offline detectado. Guardando animal localmente');

                const formData = new FormData(this);
                guardarAnimal(formData).then(tempId => {
                    console.log('Animal guardado localmente con ID temporal:', tempId);

                    // Mostrar mensaje de éxito usando alertas flotantes
                    if (window.showAlert && typeof window.showAlert === 'function') {
                        window.showAlert('success', 'El animal ha sido guardado localmente. Se sincronizará automáticamente cuando haya conexión a internet.');
                        // Redirigir al inventario después de un tiempo
                        setTimeout(() => {
                            window.location.href = '/fichaAnimal';
                        }, 2000);
                    } else {
                        alert('Animal guardado en modo offline. Se sincronizará cuando haya conexión.');
                        window.location.href = '/fichaAnimal'; // Redirigir al inventario
                    }
                }).catch(error => {
                    console.error('Error al guardar animal offline:', error);
                    if (window.showAlert && typeof window.showAlert === 'function') {
                        window.showAlert('error', 'Error al guardar el animal offline: ' + error);
                    } else {
                        alert('Error al guardar el animal offline. Por favor intenta nuevamente.');
                    }
                });

                return false;
            }
        });

        // Detectar fallos en las peticiones AJAX para intentar modo offline
        $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
            if (ajaxSettings.url === '/animal/store' && !navigator.onLine) {
                console.log('Error en petición AJAX a /animal/store en modo offline');

                // Si estamos en una petición AJAX para guardar animal y estamos offline
                if (ajaxSettings.data instanceof FormData) {
                    guardarAnimal(ajaxSettings.data).then(tempId => {
                        console.log('Animal guardado localmente con ID temporal:', tempId);
                        if (window.showAlert && typeof window.showAlert === 'function') {
                            window.showAlert('success', 'El animal ha sido guardado localmente. Se sincronizará automáticamente cuando haya conexión a internet.');
                        }
                    }).catch(error => {
                        console.error('Error al guardar animal offline tras fallo AJAX:', error);
                        if (window.showAlert && typeof window.showAlert === 'function') {
                            window.showAlert('error', 'Error al guardar el animal offline: ' + error);
                        }
                    });
                }
            }
        });
    });
})();
