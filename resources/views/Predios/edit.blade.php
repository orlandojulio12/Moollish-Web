@extends('layouts')

@section('template_title')
    {{ __('Update') }} Propietario
@endsection
@section('styles')
<style>
    #content-container:before {
        content: '';
        display: block;
        height: 165px;
        width: 100%;
        position: absolute;
        background-color: #C29F77 !important;
        z-index: 0;
    }

    /* Estilos para alertas flotantes */
    .alert-floating {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 4px;
        padding: 15px 20px;
        display: none;
    }

    .alert-success-custom {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger-custom {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .alert-warning-custom {
        background-color: #fff3cd;
        border-color: #ffecb5;
        color: #856404;
    }

    /* Spinner de carga */
    .spinner {
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
<style>

    .selected-propietarios-container {
            border: 1px solid #e5e7eb;
            padding: 10px;
            margin: 10px 0px;
            border-radius: 4px;
            display: flex;
            flex-wrap: wrap;
        }

        .icon-size-custom {
            font-size: 15px;
            margin: 1px 0px 0px 5px;
        }

        .no-selected {
            background: #6c757d12;
            border: 1px dotted lightgray;
            border-radius: 4px;
            padding: 5px 10px;
            color: #acacac;

        }

        .selected-propietario {
            background: #e49b392e;
            padding: 8px 10px;
            border: 1px solid #e49b39;
            border-radius: 4px;
            color: #9d6f19;
            display: flex;
            flex-direction: row;
            align-items: center;
            margin: 5px;
        }

    </style>
@endsection
@section('content')
    <!-- Alertas flotantes -->
    <div class="alert-floating alert-success-custom" id="successAlert">
        <strong>¡Éxito!</strong> <span id="successMessage"></span>
    </div>
    <div class="alert-floating alert-danger-custom" id="errorAlert">
        <strong>Error:</strong> <span id="errorMessage"></span>
    </div>
    <div class="alert-floating alert-warning-custom" id="warningAlert">
        <strong>¡Advertencia!</strong> <span id="warningMessage"></span>
    </div>

    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header" style="justify-content: flex-start;">
                        <a href="{{ route('predios.index') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a><!-- Añade un margen para espaciar el ícono -->
                        <span class="card-title">{{ __('Update') }} Propietario</span>

                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" id="predioForm" action="{{ route('predios.update', $Predios->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PUT') }}
                            @csrf

                            @include('Predios.form')

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" id="submitBtn" class="btn btn-primary">
                                    <span id="submitText">Actualizar Predio</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Variables para IndexedDB
        let prediosEditDB;
        const DB_NAME = 'moollish_offline_db';
        const DB_VERSION = 2; // Mantener la misma versión que en create.blade.php
        const STORE_NAME = 'pending_predios_updates';

        // Guardar el ID del predio que estamos editando
        const PREDIO_ID = {{ $Predios->id }};

        // Función para mostrar alertas
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success-custom' :
                            type === 'warning' ? 'alert-warning-custom' :
                            'alert-danger-custom';

            const alertId = type === 'success' ? '#successAlert' :
                        type === 'warning' ? '#warningAlert' :
                        '#errorAlert';

            const messageId = type === 'success' ? '#successMessage' :
                            type === 'warning' ? '#warningMessage' :
                            '#errorMessage';

            $(messageId).html(message);
            $(alertId).fadeIn().delay(5000).fadeOut();
        }

        // Estado inicial de la conexión
        console.log("Estado inicial:", navigator.onLine ? "Online" : "Offline");

        // Abrir la base de datos
        function openDatabase() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open(DB_NAME, DB_VERSION);

                request.onerror = (event) => {
                    console.error('Error al abrir la base de datos IndexedDB:', event.target.error);
                    reject('Error al abrir la base de datos');
                };

                request.onsuccess = (event) => {
                    prediosEditDB = event.target.result;
                    console.log('Base de datos IndexedDB abierta con éxito');
                    resolve(prediosEditDB);
                };

                request.onupgradeneeded = (event) => {
                    const db = event.target.result;
                    console.log('Actualizando esquema de base de datos a versión', DB_VERSION);

                    // Crear el almacén de objetos para actualizaciones de predios pendientes si no existe
                    if (!db.objectStoreNames.contains(STORE_NAME)) {
                        const store = db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
                        store.createIndex('predio_id', 'predio_id', { unique: false });
                        store.createIndex('timestamp', 'timestamp', { unique: false });
                        console.log('Almacén de objetos para actualizaciones de predios creado');
                    }
                };
            });
        }

        // Verificar si el almacén existe
        function checkStoreExists() {
            return new Promise((resolve) => {
                if (!prediosEditDB) {
                    resolve(false);
                    return;
                }

                try {
                    // Intenta abrir una transacción readonly para verificar si el almacén existe
                    const transaction = prediosEditDB.transaction([STORE_NAME], 'readonly');
                    transaction.onerror = () => resolve(false);
                    transaction.oncomplete = () => resolve(true);
                } catch (e) {
                    console.log('El almacén no existe:', e);
                    resolve(false);
                }
            });
        }

        // Guardar una actualización de predio en IndexedDB
        async function savePredioUpdateOffline(formData) {
            // Verificar si el almacén existe antes de intentar usarlo
            const storeExists = await checkStoreExists();
            if (!storeExists) {
                throw new Error('El almacén de actualizaciones de predios no está disponible');
            }

            return new Promise((resolve, reject) => {
                if (!prediosEditDB) {
                    reject('Base de datos no inicializada');
                    return;
                }

                // Convertir FormData a objeto para almacenar
                const predioUpdateData = {
                    predio_id: PREDIO_ID, // Guardamos el ID del predio que estamos actualizando
                    method: 'PUT',        // Para saber que es una actualización
                    data: {}
                };

                for (const [key, value] of formData.entries()) {
                    predioUpdateData.data[key] = value;
                }

                // Añadir timestamp
                predioUpdateData.timestamp = new Date().getTime();

                try {
                    const transaction = prediosEditDB.transaction([STORE_NAME], 'readwrite');
                    const store = transaction.objectStore(STORE_NAME);

                    const request = store.add(predioUpdateData);

                    request.onsuccess = () => {
                        console.log('Actualización de predio guardada localmente con ID:', request.result);
                        resolve(request.result);
                    };

                    request.onerror = (event) => {
                        console.error('Error al guardar la actualización del predio localmente:', event.target.error);
                        reject('Error al guardar localmente');
                    };
                } catch (e) {
                    reject('Error en la transacción: ' + e.message);
                }
            });
        }

        // Obtener todas las actualizaciones de predios pendientes
        async function getPendingPredioUpdates() {
            // Verificar si el almacén existe antes de intentar usarlo
            const storeExists = await checkStoreExists();
            if (!storeExists) {
                return []; // Retornar array vacío si el almacén no existe
            }

            return new Promise((resolve, reject) => {
                if (!prediosEditDB) {
                    reject('Base de datos no inicializada');
                    return;
                }

                try {
                    const transaction = prediosEditDB.transaction([STORE_NAME], 'readonly');
                    const store = transaction.objectStore(STORE_NAME);

                    // Usar un índice para obtener solo las actualizaciones del predio actual
                    const index = store.index('predio_id');
                    const request = index.getAll(PREDIO_ID);

                    request.onsuccess = () => {
                        resolve(request.result);
                    };

                    request.onerror = (event) => {
                        console.error('Error al obtener actualizaciones de predios pendientes:', event.target.error);
                        reject('Error al obtener pendientes');
                    };
                } catch (e) {
                    console.error('Error al obtener actualizaciones de predios:', e);
                    resolve([]); // En caso de error, devolvemos un array vacío
                }
            });
        }

        // Eliminar una actualización de predio pendiente por ID
        async function removePendingPredioUpdate(id) {
            // Verificar si el almacén existe antes de intentar usarlo
            const storeExists = await checkStoreExists();
            if (!storeExists) {
                return; // No hacer nada si el almacén no existe
            }

            return new Promise((resolve, reject) => {
                if (!prediosEditDB) {
                    reject('Base de datos no inicializada');
                    return;
                }

                try {
                    const transaction = prediosEditDB.transaction([STORE_NAME], 'readwrite');
                    const store = transaction.objectStore(STORE_NAME);
                    const request = store.delete(id);

                    request.onsuccess = () => {
                        console.log('Actualización de predio pendiente eliminada:', id);
                        resolve();
                    };

                    request.onerror = (event) => {
                        console.error('Error al eliminar actualización de predio pendiente:', event.target.error);
                        reject('Error al eliminar');
                    };
                } catch (e) {
                    console.error('Error al eliminar actualización de predio:', e);
                    resolve(); // Resolvemos la promesa sin errores
                }
            });
        }

        // Sincronizar actualizaciones de predios pendientes cuando hay conexión
        async function syncPendingPredioUpdates() {
            if (!navigator.onLine) {
                console.log('Sin conexión, no se puede sincronizar');
                return;
            }

            try {
                // Verificar si el almacén existe
                const storeExists = await checkStoreExists();
                if (!storeExists) {
                    console.log('El almacén de actualizaciones de predios no existe, no hay nada que sincronizar');
                    return;
                }

                const pendingUpdates = await getPendingPredioUpdates();
                console.log(`Intentando sincronizar ${pendingUpdates.length} actualizaciones de predios pendientes`);

                for (const update of pendingUpdates) {
                    try {
                        // Crear un objeto FormData para enviar
                        const formData = new FormData();
                        for (const key in update.data) {
                            formData.append(key, update.data[key]);
                        }

                        // Añadir el método PUT
                        formData.append('_method', 'PUT');

                        // Enviar el formulario
                        const response = await $.ajax({
                            url: "{{ route('predios.update', $Predios->id) }}",
                            method: "POST", // Aunque es PUT, usamos POST con _method=PUT
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        // Éxito en la sincronización
                        await removePendingPredioUpdate(update.id);
                        console.log('Actualización de predio sincronizada correctamente:', update.id);
                    } catch (error) {
                        console.error('Error al sincronizar actualización de predio:', update.id, error);
                    }
                }

                // Mostrar cuántos quedan pendientes
                const remainingUpdates = await getPendingPredioUpdates();
                if (remainingUpdates.length === 0) {
                    showAlert('success', 'Todas las actualizaciones de predios han sido sincronizadas');
                } else {
                    showAlert('warning', `${remainingUpdates.length} actualizaciones aún pendientes de sincronización`);
                }
            } catch (error) {
                console.error('Error durante la sincronización:', error);
                showAlert('error', 'Error durante la sincronización');
            }
        }

        // Mostrar contador de pendientes
        async function updatePendingCounter() {
            try {
                // Verificar si el almacén existe
                const storeExists = await checkStoreExists();
                if (!storeExists) {
                    console.log('El almacén de actualizaciones de predios no existe, no hay contador que actualizar');
                    return;
                }

                const pendingUpdates = await getPendingPredioUpdates();
                if (pendingUpdates.length > 0) {
                    // Crear o actualizar el badge de pendientes
                    let badge = $('#pending-badge');
                    if (badge.length === 0) {
                        $('.card-body').prepend(`
                            <div id="pending-badge" class="alert alert-warning" style="margin-bottom: 20px;">
                                <strong>${pendingUpdates.length} actualización(es) pendiente(s) de sincronización</strong>
                                <button id="sync-now" class="btn btn-sm btn-primary float-end">Sincronizar ahora</button>
                            </div>
                        `);

                        // Añadir event listener al botón de sincronización
                        $('#sync-now').on('click', function() {
                            syncPendingPredioUpdates();
                        });
                    } else {
                        badge.find('strong').text(`${pendingUpdates.length} actualización(es) pendiente(s) de sincronización`);
                    }
                } else {
                    // Eliminar el badge si no hay pendientes
                    $('#pending-badge').remove();
                }
            } catch (error) {
                console.error('Error al actualizar contador de pendientes:', error);
            }
        }

        // Event listeners para conexión
        window.addEventListener('online', function() {
            console.log("Conexión restablecida");
            showAlert('success', 'Conexión restablecida. Sincronizando datos...');
            syncPendingPredioUpdates();
        });

        window.addEventListener('offline', function() {
            console.log("Conexión perdida");
            showAlert('warning', 'Sin conexión. Los cambios se guardarán localmente.');
        });

        $(document).ready(function() {
            // Abrir la base de datos al cargar
            openDatabase().then(async () => {
                console.log('IndexedDB inicializada');

                // Verificar si el almacén existe después de inicializar
                const storeExists = await checkStoreExists();
                console.log('¿Existe el almacén de actualizaciones de predios?', storeExists);

                // Actualizar contador de pendientes
                if (storeExists) {
                    updatePendingCounter();

                    // Si hay conexión, intentar sincronizar al inicio
                    if (navigator.onLine) {
                        syncPendingPredioUpdates();
                    }
                }
            }).catch(error => {
                console.error('Error al inicializar IndexedDB:', error);
            });

            // Envío del formulario
            $('#predioForm').submit(async function(e) {
                e.preventDefault();

                // Desactivar botón de envío y mostrar loader
                $('#submitBtn').prop('disabled', true);
                $('#submitText').html('<span class="spinner"></span> Actualizando...');

                // Recoger datos del formulario
                let formData = new FormData(this);

                // Verificar el estado de la conexión
                if (!navigator.onLine) {
                    // Verificar que el almacén exista antes de guardar
                    const storeExists = await checkStoreExists();

                    if (!storeExists) {
                        showAlert('error', 'No se puede guardar offline: el almacén no está disponible');
                        resetSubmitButton();
                        return;
                    }

                    // OFFLINE: Guardar localmente
                    savePredioUpdateOffline(formData)
                        .then(id => {
                            // Mostrar mensaje de éxito
                            showAlert('success', 'Cambios guardados localmente. Se sincronizarán cuando haya conexión.');

                            // Actualizar contador de pendientes
                            updatePendingCounter();

                            // Restaurar botón
                            resetSubmitButton();
                        })
                        .catch(error => {
                            console.error('Error al guardar localmente:', error);
                            showAlert('error', 'Error al guardar localmente: ' + error);
                            resetSubmitButton();
                        });
                    return;
                }

                // ONLINE: Enviar al servidor mediante AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: "POST", // Usamos POST con _method=PUT
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log("Respuesta del servidor:", response);

                        // Mostrar mensaje de éxito
                        showAlert('success', 'Predio actualizado correctamente');

                        // Redireccionar después de 2 segundos a la página de predios
                        setTimeout(function() {
                            window.location.href = "{{ route('predios.index') }}";
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud:", xhr, status, error);

                        // Restaurar botón
                        resetSubmitButton();

                        // Determinar qué tipo de error ocurrió
                        let errorMsg = 'Ha ocurrido un error al procesar la solicitud.';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                const errorList = Object.values(xhr.responseJSON.errors).flat();
                                errorMsg = errorList.join('<br>');
                            }
                        }

                        showAlert('error', errorMsg);
                    }
                });
            });

            // Función para resetear el botón
            function resetSubmitButton() {
                $('#submitBtn').prop('disabled', false);
                $('#submitText').html('Actualizar Predio');
            }
        });
    </script>
@endsection
