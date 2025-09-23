@extends('layouts')

@section('template_title')
    Crear Animal
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('animales.index') }}"><i class="bi bi-arrow-left"
                        style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Crear Animal</h5>
            </div>
        </div>
    </div>
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

    .alert-info-custom {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    /* Indicador de estado offline */
    .offline-indicator {
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        margin-left: 10px;
        font-size: 12px;
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

    .required-field::after {
        content: " *";
        color: red;
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
    <div class="alert-floating alert-info-custom" id="infoAlert">
        <strong>Información:</strong> <span id="infoMessage"></span>
    </div>

    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">
                <div id="connection-status">
                    <span id="offline-badge" style="display: none;" class="offline-indicator">
                        <i class="fas fa-wifi-slash"></i> Modo Offline
                    </span>
                </div>

                <form method="POST" id="animalForm" action="{{ route('animales.store') }}" role="form" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label required-field">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="identificacion" class="form-label required-field">Identificación</label>
                            <input type="text" class="form-control" id="identificacion" name="identificacion" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="predio_id" class="form-label required-field">Predio</label>
                            <select class="form-select" id="predio_id" name="predio_id" required>
                                <option value="">Seleccione un predio</option>
                                @foreach($predios ?? [] as $predio)
                                    <option value="{{ $predio->id }}">{{ $predio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="raza" class="form-label">Raza</label>
                            <input type="text" class="form-control" id="raza" name="raza">
                        </div>
                        <div class="col-md-6">
                            <label for="genero" class="form-label required-field">Género</label>
                            <select class="form-select" id="genero" name="genero" required>
                                <option value="">Seleccione género</option>
                                <option value="macho">Macho</option>
                                <option value="hembra">Hembra</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="peso_nacimiento" class="form-label">Peso al Nacer (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="peso_nacimiento" name="peso_nacimiento">
                        </div>
                        <div class="col-md-6">
                            <label for="madre_id" class="form-label">Madre</label>
                            <select class="form-select" id="madre_id" name="madre_id">
                                <option value="">Seleccione madre</option>
                                @foreach($madres ?? [] as $madre)
                                    <option value="{{ $madre->id }}">{{ $madre->nombre }} - {{ $madre->identificacion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('animales.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancelar
                        </a>
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> <span id="submitText">Guardar Animal</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Variables para IndexedDB
        let animalDB;
        const DB_NAME = 'moollish_offline_db';
        const DB_VERSION = 3; // Incrementamos versión para añadir el store de animales
        const ANIMAL_STORE = 'pending_animales';
        const PREDIO_STORE = 'pending_predios';

        // Función para mostrar alertas
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success-custom' :
                            type === 'warning' ? 'alert-warning-custom' :
                            type === 'info' ? 'alert-info-custom' :
                            'alert-danger-custom';

            const alertId = type === 'success' ? '#successAlert' :
                        type === 'warning' ? '#warningAlert' :
                        type === 'info' ? '#infoAlert' :
                        '#errorAlert';

            const messageId = type === 'success' ? '#successMessage' :
                            type === 'warning' ? '#warningMessage' :
                            type === 'info' ? '#infoMessage' :
                            '#errorMessage';

            $(messageId).html(message);
            $(alertId).fadeIn().delay(5000).fadeOut();
        }

        // Abrir la base de datos
        function openDatabase() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open(DB_NAME, DB_VERSION);

                request.onerror = (event) => {
                    console.error('Error al abrir la base de datos IndexedDB:', event.target.error);
                    reject('Error al abrir la base de datos');
                };

                request.onsuccess = (event) => {
                    animalDB = event.target.result;
                    console.log('Base de datos IndexedDB abierta con éxito');
                    resolve(animalDB);
                };

                request.onupgradeneeded = (event) => {
                    const db = event.target.result;
                    console.log('Actualizando esquema de base de datos a versión', DB_VERSION);

                    // Crear el almacén de objetos para predios pendientes si no existe
                    if (!db.objectStoreNames.contains(PREDIO_STORE)) {
                        const predioStore = db.createObjectStore(PREDIO_STORE, { keyPath: 'id', autoIncrement: true });
                        predioStore.createIndex('timestamp', 'timestamp', { unique: false });
                        console.log('Almacén de objetos para predios creado');
                    }

                    // Crear el almacén de objetos para animales pendientes si no existe
                    if (!db.objectStoreNames.contains(ANIMAL_STORE)) {
                        const animalStore = db.createObjectStore(ANIMAL_STORE, { keyPath: 'id', autoIncrement: true });
                        animalStore.createIndex('timestamp', 'timestamp', { unique: false });
                        animalStore.createIndex('predio_id', 'predio_id', { unique: false });
                        animalStore.createIndex('temp_predio_id', 'temp_predio_id', { unique: false });
                        console.log('Almacén de objetos para animales creado');
                    }
                };
            });
        }

        // Verificar si el almacén existe
        function checkStoreExists(storeName) {
            return new Promise((resolve) => {
                if (!animalDB) {
                    resolve(false);
                    return;
                }

                try {
                    // Intenta abrir una transacción readonly para verificar si el almacén existe
                    const transaction = animalDB.transaction([storeName], 'readonly');
                    transaction.onerror = () => resolve(false);
                    transaction.oncomplete = () => resolve(true);
                } catch (e) {
                    console.log(`El almacén ${storeName} no existe:`, e);
                    resolve(false);
                }
            });
        }

        // Guardar un animal en IndexedDB
        async function saveAnimalOffline(formData) {
            // Verificar si el almacén existe antes de intentar usarlo
            const storeExists = await checkStoreExists(ANIMAL_STORE);
            if (!storeExists) {
                throw new Error('El almacén de animales no está disponible');
            }

            return new Promise((resolve, reject) => {
                if (!animalDB) {
                    reject('Base de datos no inicializada');
                    return;
                }

                // Convertir FormData a objeto para almacenar
                const animalData = {};
                for (const [key, value] of formData.entries()) {
                    animalData[key] = value;
                }

                // Verificar si el predio_id corresponde a un predio temporal
                if (animalData.predio_id && animalData.predio_id.toString().startsWith('temp_')) {
                    // Extraer el ID temporal del predio
                    const tempPredioId = animalData.predio_id.toString().replace('temp_', '');
                    animalData.temp_predio_id = tempPredioId;
                    animalData.predio_id = null; // Limpiar el predio_id hasta que se sincronice
                }

                // Añadir timestamp
                animalData.timestamp = new Date().getTime();
                animalData.sincronizado = false;

                try {
                    const transaction = animalDB.transaction([ANIMAL_STORE], 'readwrite');
                    const store = transaction.objectStore(ANIMAL_STORE);

                    const request = store.add(animalData);

                    request.onsuccess = () => {
                        const tempId = request.result;
                        console.log('Animal guardado localmente con ID temporal:', tempId);

                        // Guardar el ID temporal en localStorage para referencia futura
                        localStorage.setItem('ultimo_animal_temp_id', tempId);
                        localStorage.setItem('ultimo_animal_info', JSON.stringify({
                            nombre: animalData.nombre,
                            identificacion: animalData.identificacion
                        }));

                        resolve(tempId);
                    };

                    request.onerror = (event) => {
                        console.error('Error al guardar el animal localmente:', event.target.error);
                        reject('Error al guardar localmente');
                    };
                } catch (e) {
                    reject('Error en la transacción: ' + e.message);
                }
            });
        }

        // Obtener todos los animales pendientes
        async function getPendingAnimales() {
            // Verificar si el almacén existe antes de intentar usarlo
            const storeExists = await checkStoreExists(ANIMAL_STORE);
            if (!storeExists) {
                return []; // Retornar array vacío si el almacén no existe
            }

            return new Promise((resolve, reject) => {
                if (!animalDB) {
                    reject('Base de datos no inicializada');
                    return;
                }

                try {
                    const transaction = animalDB.transaction([ANIMAL_STORE], 'readonly');
                    const store = transaction.objectStore(ANIMAL_STORE);
                    const request = store.getAll();

                    request.onsuccess = () => {
                        resolve(request.result);
                    };

                    request.onerror = (event) => {
                        console.error('Error al obtener animales pendientes:', event.target.error);
                        reject('Error al obtener pendientes');
                    };
                } catch (e) {
                    console.error('Error al obtener animales:', e);
                    resolve([]); // En caso de error, devolvemos un array vacío
                }
            });
        }

        // Eliminar un animal pendiente por ID
        async function removePendingAnimal(id) {
            // Verificar si el almacén existe antes de intentar usarlo
            const storeExists = await checkStoreExists(ANIMAL_STORE);
            if (!storeExists) {
                return; // No hacer nada si el almacén no existe
            }

            return new Promise((resolve, reject) => {
                if (!animalDB) {
                    reject('Base de datos no inicializada');
                    return;
                }

                try {
                    const transaction = animalDB.transaction([ANIMAL_STORE], 'readwrite');
                    const store = transaction.objectStore(ANIMAL_STORE);
                    const request = store.delete(id);

                    request.onsuccess = () => {
                        console.log('Animal pendiente eliminado:', id);
                        resolve();
                    };

                    request.onerror = (event) => {
                        console.error('Error al eliminar animal pendiente:', event.target.error);
                        reject('Error al eliminar');
                    };
                } catch (e) {
                    console.error('Error al eliminar animal:', e);
                    resolve(); // Resolvemos la promesa sin errores
                }
            });
        }

        // Obtener predios pendientes
        async function getPendingPredios() {
            const storeExists = await checkStoreExists(PREDIO_STORE);
            if (!storeExists) {
                return [];
            }

            return new Promise((resolve, reject) => {
                if (!animalDB) {
                    reject('Base de datos no inicializada');
                    return;
                }

                try {
                    const transaction = animalDB.transaction([PREDIO_STORE], 'readonly');
                    const store = transaction.objectStore(PREDIO_STORE);
                    const request = store.getAll();

                    request.onsuccess = () => {
                        resolve(request.result);
                    };

                    request.onerror = (event) => {
                        console.error('Error al obtener predios pendientes:', event.target.error);
                        reject('Error al obtener predios pendientes');
                    };
                } catch (e) {
                    console.error('Error al obtener predios:', e);
                    resolve([]);
                }
            });
        }

        // Sincronizar animales pendientes cuando hay conexión
        async function syncPendingAnimales() {
            if (!navigator.onLine) {
                console.log('Sin conexión, no se puede sincronizar');
                return;
            }

            try {
                // Verificar si el almacén existe
                const storeExists = await checkStoreExists(ANIMAL_STORE);
                if (!storeExists) {
                    console.log('El almacén de animales no existe, no hay nada que sincronizar');
                    return;
                }

                const pendingAnimales = await getPendingAnimales();
                console.log(`Intentando sincronizar ${pendingAnimales.length} animales pendientes`);

                for (const animal of pendingAnimales) {
                    try {
                        // Si el animal tiene un predio temporal, necesitamos buscar el ID real
                        if (animal.temp_predio_id) {
                            // Actualizar el predio_id si corresponde a un predio temporal
                            // Aquí necesitaríamos una manera de mapear el ID temporal al ID real
                            // después de sincronizar los predios
                            console.log('Animal con predio temporal:', animal.temp_predio_id);

                            // Implementar lógica para buscar el mapeo de IDs si existe
                            // Por ahora, si no se puede resolver, dejamos el error para reintento
                            if (localStorage.getItem(`predio_temp_${animal.temp_predio_id}_real_id`)) {
                                animal.predio_id = localStorage.getItem(`predio_temp_${animal.temp_predio_id}_real_id`);
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

                        // Enviar el formulario
                        const response = await $.ajax({
                            url: "{{ route('animales.store') }}",
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
                        }

                        // Éxito en la sincronización
                        await removePendingAnimal(animal.id);
                        console.log('Animal sincronizado correctamente:', animal.id);
                    } catch (error) {
                        console.error('Error al sincronizar animal:', animal.id, error);
                    }
                }

                // Mostrar cuántos quedan pendientes
                const remainingAnimales = await getPendingAnimales();
                if (remainingAnimales.length === 0) {
                    showAlert('success', 'Todos los animales han sido sincronizados');
                } else {
                    showAlert('warning', `${remainingAnimales.length} animales aún pendientes de sincronización`);
                }
            } catch (error) {
                console.error('Error durante la sincronización:', error);
                showAlert('error', 'Error durante la sincronización');
            }
        }

        // Cargar predios pendientes en el selector
        async function loadPendingPrediosIntoSelector() {
            try {
                const pendingPredios = await getPendingPredios();

                if (pendingPredios.length > 0) {
                    // Añadir grupo de optgroup para predios pendientes
                    const $select = $('#predio_id');
                    let optgroupExists = $select.find('optgroup[label="Predios pendientes de sincronización"]').length > 0;

                    if (!optgroupExists) {
                        $select.append('<optgroup label="Predios pendientes de sincronización" id="pending-predios-group"></optgroup>');
                    }

                    const $optgroup = $select.find('#pending-predios-group');
                    $optgroup.empty(); // Limpiar grupo existente

                    // Añadir cada predio pendiente
                    pendingPredios.forEach(predio => {
                        $optgroup.append(`<option value="temp_${predio.id}">${predio.nombre} (pendiente)</option>`);
                    });

                    showAlert('info', `Se han cargado ${pendingPredios.length} predios pendientes de sincronización`);
                }
            } catch (error) {
                console.error('Error al cargar predios pendientes:', error);
            }
        }

        // Actualizar contador de pendientes
        async function updatePendingCounter() {
            try {
                // Verificar si el almacén existe
                const storeExists = await checkStoreExists(ANIMAL_STORE);
                if (!storeExists) {
                    console.log('El almacén de animales no existe, no hay contador que actualizar');
                    return;
                }

                const pendingAnimales = await getPendingAnimales();
                if (pendingAnimales.length > 0) {
                    // Crear o actualizar el badge de pendientes
                    let badge = $('#pending-badge');
                    if (badge.length === 0) {
                        $('.card-body').prepend(`
                            <div id="pending-badge" class="alert alert-warning" style="margin-bottom: 20px;">
                                <strong>${pendingAnimales.length} animal(es) pendiente(s) de sincronización</strong>
                                <button id="sync-now" class="btn btn-sm btn-primary float-end">Sincronizar ahora</button>
                            </div>
                        `);

                        // Añadir event listener al botón de sincronización
                        $('#sync-now').on('click', function() {
                            syncPendingAnimales();
                        });
                    } else {
                        badge.find('strong').text(`${pendingAnimales.length} animal(es) pendiente(s) de sincronización`);
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
            $('#offline-badge').hide();
            showAlert('success', 'Conexión restablecida. Sincronizando datos...');
            syncPendingAnimales();
        });

        window.addEventListener('offline', function() {
            console.log("Conexión perdida");
            $('#offline-badge').show();
            showAlert('warning', 'Sin conexión. Los registros se guardarán localmente.');
        });

        $(document).ready(function() {
            // Verificar estado inicial de conexión
            if (!navigator.onLine) {
                $('#offline-badge').show();
            }

            // Abrir la base de datos al cargar
            openDatabase().then(async () => {
                console.log('IndexedDB inicializada');

                // Verificar si el almacén existe después de inicializar
                const animalStoreExists = await checkStoreExists(ANIMAL_STORE);
                console.log('¿Existe el almacén de animales?', animalStoreExists);

                // Cargar predios pendientes en el selector
                await loadPendingPrediosIntoSelector();

                // Actualizar contador de pendientes
                if (animalStoreExists) {
                    updatePendingCounter();

                    // Si hay conexión, intentar sincronizar al inicio
                    if (navigator.onLine) {
                        syncPendingAnimales();
                    }
                }
            }).catch(error => {
                console.error('Error al inicializar IndexedDB:', error);
            });

            // Envío del formulario
            $('#animalForm').submit(async function(e) {
                e.preventDefault();

                // Desactivar botón de envío y mostrar loader
                $('#submitBtn').prop('disabled', true);
                $('#submitText').html('<span class="spinner"></span> Guardando...');

                // Recoger datos del formulario
                let formData = new FormData(this);

                // Verificar el estado de la conexión
                if (!navigator.onLine) {
                    // Verificar que el almacén exista antes de guardar
                    const storeExists = await checkStoreExists(ANIMAL_STORE);

                    if (!storeExists) {
                        showAlert('error', 'No se puede guardar offline: el almacén no está disponible');
                        resetSubmitButton();
                        return;
                    }

                    // OFFLINE: Guardar localmente
                    saveAnimalOffline(formData)
                        .then(id => {
                            // Resetear el formulario
                            $('#animalForm')[0].reset();

                            // Mostrar mensaje de éxito
                            showAlert('success', 'Animal guardado localmente. Se sincronizará cuando haya conexión.');

                            // Actualizar contador de pendientes
                            updatePendingCounter();

                            // Restaurar botón
                            resetSubmitButton();

                            // Ofrecer ir al registro de peso
                            setTimeout(() => {
                                showAlert('info', '¿Deseas registrar el peso de este animal? <a href="/animales/registro-peso" class="btn btn-sm btn-primary ms-2">Registrar Peso</a>');
                            }, 1000);
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
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log("Respuesta del servidor:", response);

                        // Mostrar mensaje de éxito
                        showAlert('success', 'Animal registrado correctamente');

                        // Redireccionar después de 2 segundos a la página de animales
                        setTimeout(function() {
                            window.location.href = "{{ route('animales.index') }}";
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
                $('#submitText').html('Guardar Animal');
            }
        });

        // Exponer funciones al objeto window para que sean accesibles globalmente
        window.OfflineManager = {
            guardarAnimal: saveAnimalOffline,
            sincronizarAnimales: syncPendingAnimales,
            obtenerAnimalesPendientes: getPendingAnimales
        };
    </script>
@endsection
