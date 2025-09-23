/**
 * Script para ayudar a sincronizar datos offline
 */

(function() {
    // Referencia al OfflineManager existente
    const STORES = {
        ANIMALES: 'pending_animales',
        PESOS: 'pending_pesos',
        PREDIOS: 'pending_predios'
    };

    /**
     * Sincronizar un animal específico usando fetch en lugar de $.ajax
     */
    async function syncOneAnimal(animal, retryCount = 0) {
        console.log('Intentando sincronizar animal:', animal);

        const MAX_RETRIES = 3;

        try {
            // --- Lógica de Verificación de Predio con Nombres Consistentes ---
            const hasRealPredioId = !!animal.id_predio;
            const hasTempPredioId = !!animal.temp_id_predio; // Usar temp_id_predio

            if (!hasRealPredioId && !hasTempPredioId) {
                console.error(`Error Crítico: Animal ${animal.id || '(sin ID)'} no tiene id_predio ni temp_id_predio. Datos:`, animal);
                throw new Error('Falta ID del predio (real o temporal).');
            }

            if (!hasRealPredioId && hasTempPredioId) {
                console.log(`Animal ${animal.id} tiene predio temporal ${animal.temp_id_predio}, buscando ID real...`);
                // Usar temp_id_predio para buscar en localStorage
                const realPredioId = localStorage.getItem(`predio_temp_${animal.temp_id_predio}_real_id`);
                if (realPredioId) {
                    animal.id_predio = realPredioId;
                    console.log(`ID real ${realPredioId} asignado a animal ${animal.id}.`);
                } else {
                    console.warn(`ID real para predio temp ${animal.temp_id_predio} no encontrado. Saltando animal ${animal.id}.`);
                    return { success: false, error: 'Predio asociado aún no sincronizado', skipped: true };
                }
            }

            if (!animal.id_predio) {
                 console.error(`Error Inesperado: id_predio sigue inválido tras verificación.`, animal);
                 throw new Error('Error inesperado procesando ID predio.');
            }
            // --- Fin Lógica Verificación Predio ---

            const formData = new FormData();
            for (const key in animal) {
                // Excluir campos internos y el temporal del predio con nombre nuevo
                if (!['id', 'timestamp', 'sincronizado', 'temp_id_predio'].includes(key)) {
                    const value = animal[key];
                    if (value !== null && value !== undefined && value !== 'null' && value !== '') {
                        formData.append(key, value);
                    }
                }
            }

            // Asegurar que id_predio se envíe (con nombre correcto)
            if (!formData.has('id_predio') && animal.id_predio) {
                 formData.append('id_predio', animal.id_predio);
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                formData.append('_token', csrfToken);
            }

            const timestamp = new Date().getTime();
            formData.append('bypass_service_worker', 'true');
            formData.append('_timestamp', timestamp.toString());
            formData.append('is_sync', 'true');
            formData.append('retry_count', retryCount.toString());

            const baseUrl = window.location.origin;
            const url = `${baseUrl}/animal/store?bypass_sw=true&is_sync=true&t=${timestamp}`;

            console.log(`Intento #${retryCount + 1} de sincronizar animal a:`, url);

            try {
                const ajaxPromise = new Promise((resolve, reject) => {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 30000,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-Bypass-Service-Worker': 'true',
                            'Cache-Control': 'no-cache, no-store'
                        },
                        success: function(data) {
                            resolve(data);
                        },
                        error: function(xhr, status, error) {
                            console.error(`Error en AJAX [Intento ${retryCount + 1}]:`, status, error);
                            let errorMessage = error;
                            try {
                                if (xhr.responseText) {
                                    const errorResponse = JSON.parse(xhr.responseText);
                                    if (errorResponse.error) {
                                        errorMessage = errorResponse.error;
                                    }
                                }
                            } catch (e) {
                                console.warn('No se pudo parsear la respuesta de error');
                            }
                            reject(new Error(`Error (${xhr.status}): ${errorMessage}`));
                        }
                    });
                });

                const data = await ajaxPromise;
                console.log(`Respuesta del servidor (Ajax) [Intento ${retryCount + 1}]:`, data);

                if (!data.success) {
                    throw new Error(data.error || 'La respuesta del servidor no indica éxito');
                }
                if (!data.id) {
                    throw new Error('La respuesta del servidor no incluye un ID válido');
                }

                localStorage.setItem(`animal_temp_${animal.id}_real_id`, data.id);
                if (window.OfflineManager && typeof window.OfflineManager.updateRelation === 'function') {
                    await window.OfflineManager.updateRelation('animal', animal.id, data.id);
                }

                // Log ANTES de intentar eliminar
                console.log(`[SyncHelper] Sincronización exitosa para animal temp ID ${animal.id}. Intentando eliminar de IndexedDB...`);

                if (window.OfflineManager && typeof window.OfflineManager.removePendingEntity === 'function') {
                    try {
                        // Asegurarse que STORES.ANIMALES está disponible o usar el string directamente
                        const storeName = window.STORES ? window.STORES.ANIMALES : 'pending_animales';
                        await window.OfflineManager.removePendingEntity(storeName, animal.id);
                        // Log DESPUÉS de eliminar (si no hay error)
                        console.log(`[SyncHelper] Animal temp ID ${animal.id} eliminado exitosamente de IndexedDB.`);
                    } catch (removalError) {
                        // Log si removePendingEntity lanza un error
                        console.error(`[SyncHelper] Error al intentar eliminar animal temp ID ${animal.id} de IndexedDB:`, removalError);
                        // Considerar si este error debe impedir que syncOneAnimal devuelva éxito
                        // throw new Error('Fallo al limpiar IndexedDB tras sincronización exitosa.');
                    }
                } else {
                     console.warn(`[SyncHelper] OfflineManager.removePendingEntity no encontrado. No se pudo eliminar animal temp ID ${animal.id}`);
                }

                return { success: true, id: data.id };
            } catch (ajaxError) {
                if (ajaxError.message.includes('500') && retryCount < MAX_RETRIES - 1) {
                    console.warn(`Error 500 detectado, reintentando (${retryCount + 1}/${MAX_RETRIES})...`);
                    await new Promise(resolve => setTimeout(resolve, 2000 * (retryCount + 1)));
                    return await syncOneAnimal(animal, retryCount + 1);
                }
                throw ajaxError;
            }
        } catch (error) {
            console.error(`Error al sincronizar animal [Intento ${retryCount + 1}]:`, error);
            return { success: false, error: error.message };
        }
    }

    /**
     * Sincronizar todos los animales pendientes
     */
    async function syncAllAnimals() {
        try {
            // Obtener los animales pendientes
            let pendingAnimals = [];
            let pendingCount = 0;

            if (window.OfflineManager) {
                if (typeof window.OfflineManager.countPendingAnimales === 'function') {
                    pendingCount = await window.OfflineManager.countPendingAnimales();
                    if (pendingCount > 0) {
                        pendingAnimals = await window.OfflineManager.obtenerAnimalesPendientes();
                    }
                } else if (typeof window.OfflineManager.hayPendientes === 'function') {
                    const pending = await window.OfflineManager.hayPendientes();
                    pendingCount = pending ? pending.animales : 0;
                    if (pendingCount > 0) {
                        pendingAnimals = await window.OfflineManager.obtenerAnimalesPendientes();
                    }
                } else if (typeof window.OfflineManager.obtenerAnimalesPendientes === 'function') {
                    pendingAnimals = await window.OfflineManager.obtenerAnimalesPendientes();
                    pendingCount = pendingAnimals.length;
                } else {
                    console.warn('No se puede acceder a los métodos de OfflineManager para obtener animales pendientes');
                    return { success: false, error: 'No se puede acceder a los animales pendientes' };
                }
            } else {
                console.warn('OfflineManager no está disponible');
                return { success: false, error: 'OfflineManager no está disponible' };
            }

            console.log(`Intentando sincronizar ${pendingCount} animales pendientes con sync-helper.js`);

            if (pendingCount === 0) {
                return { success: true, message: 'No hay animales pendientes', count: 0 };
            }

            // Mostrar notificación
            if (window.showAlert && typeof window.showAlert === 'function') {
                window.showAlert('warning', `Sincronizando ${pendingCount} animales pendientes...`);
            }

            // Resultados
            let success = 0;
            let errors = 0;

            // Sincronizar cada animal
            for (const animal of pendingAnimals) {
                const result = await syncOneAnimal(animal);
                if (result.success) {
                    success++;
                } else {
                    errors++;
                    console.error(`Error al sincronizar animal #${animal.id}:`, result.error);
                }
            }

            // Mensaje de resultado
            let message = '';
            if (success > 0 && errors === 0) {
                message = `${success} animales sincronizados correctamente.`;
            } else if (success > 0 && errors > 0) {
                message = `${success} animales sincronizados. Hubo errores con ${errors} animales.`;
            } else if (success === 0 && errors > 0) {
                message = `No se pudo sincronizar ningún animal. Hubo ${errors} errores.`;
            }

            // Mostrar resultado
            if (window.showAlert && typeof window.showAlert === 'function') {
                const alertType = (errors === 0) ? 'success' : (success > 0) ? 'warning' : 'error';
                window.showAlert(alertType, message);
            }

            return {
                success: true,
                sincronizados: success,
                errores: errors,
                message
            };
        } catch (error) {
            console.error('Error en syncAllAnimals:', error);
            return { success: false, error: error.message };
        }
    }

    // Exponer funciones al ámbito global
    window.SyncHelper = {
        syncOneAnimal,
        syncAllAnimals
    };
})();
