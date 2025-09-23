@extends('layouts')

@section('title', 'Contenido disponible offline')

@section('styles')

<style>
    /* Estilos para el modal personalizado */
    .custom-modal-container {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1050;
    }

    .custom-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1051;
    }

    .custom-modal {
        position: relative;
        width: 90%;
        max-width: 500px;
        margin: 50px auto;
        background-color: #fff;
        border-radius: 5px;
        z-index: 1052;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .custom-modal-header {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .custom-modal-body {
        padding: 15px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .custom-modal-footer {
        padding: 15px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .custom-close-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #666;
    }

    .custom-btn {
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        border: none;
    }

    .custom-btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .custom-btn-primary {
        background-color: #0d6efd;
        color: white;
    }
    </style>
@endsection
@section('content')

<div class="container mt-4">
        <div class="card-header">
            <h2 class="mb-0">Gestionar contenido offline</h2>
        </div>
        <div class="card-body">
            <br>

            <!-- Advertencia de compatibilidad -->
            <div id="compatibility-warning" class="alert alert-info" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i> Tu navegador tiene compatibilidad limitada con la API de Caché. Algunas funciones pueden no estar disponibles o funcionar correctamente.
            </div>
            <!-- Mensajes emergentes para notificaciones -->
            <div id="notification-area" style="position: fixed; top: 20px; right: 20px; z-index: 1050; max-width: 350px;"></div>

            <div id="offline-status" class="mb-4">
                <div class="d-flex align-items-center mb-2">
                    <div id="connection-status" class="badge bg-success me-2">Online</div>
                    <span id="storage-info">Espacio utilizado: Calculando...</span>
                </div>
                <div class="progress" style="height: 20px;">
                    <div id="storage-progress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>

            <h4 class="mb-3">Módulos disponibles</h4>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Selecciona las secciones que deseas tener disponibles sin conexión a internet. Estas secciones se descargarán y podrás acceder a ellas cuando no tengas conexión.
            </div>

            <div class="list-group mb-4" id="offline-sections">
                <!-- Sección de Insumos -->
                <div class="list-group-item">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="insumos-check" data-section="insumos">
                        <label class="form-check-label d-flex justify-content-between align-items-center" for="insumos-check">
                            <div>
                                <h5 class="mb-1">Gestión de Insumos</h5>
                                <p class="mb-1">Registro y consulta de insumos del inventario</p>
                            </div>
                            <span class="badge bg-secondary download-status">No descargado</span>
                        </label>
                    </div>
                    <div class="mt-2 section-details" style="display: none;">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="insumos-start" data-url="/moollish/inicio">
                                    <label class="form-check-label" for="insumos-start">Inicio</label>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="insumos-list" data-url="/insumos">
                                    <label class="form-check-label" for="insumos-list">Gesion de insumos</label>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="insumos-register" data-url="/insumos/registrar">
                                    <label class="form-check-label" for="insumos-register">Registro de Insumos</label>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="insumos-entrada" data-url="/insumos/entrada">
                                    <label class="form-check-label" for="insumos-entrada">Entrada de Insumos</label>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="insumos-salida" data-url="/insumosSalida">
                                    <label class="form-check-label" for="insumos-salida">Salida de Insumos</label>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="insumos-consulta" data-url="/insumos/consulta">
                                    <label class="form-check-label" for="insumos-consulta">Consulta de insumos</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Inventario -->
                <div class="list-group-item">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="inventario-check" data-section="inventario">
                        <label class="form-check-label d-flex justify-content-between align-items-center" for="inventario-check">
                            <div>
                                <h5 class="mb-1">Inventario animal</h5>
                                <p class="mb-1">Revisa tus animales y sus detalles.</p>
                            </div>
                            <span class="badge bg-secondary download-status">No descargado</span>
                        </label>
                    </div>
                    <div class="mt-2 section-details" style="display: none;">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="inventario-list" data-url="/inventario/general">
                                    <label class="form-check-label" for="inventario-list">Mis animales</label>
                                </div>

                            </div>

                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="opcionesAnimal" data-url="/moollish/animales">
                                    <label class="form-check-label" for="opcionesAnimal">Opciones animal</label>
                                </div>
                            </div>

                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="registros" data-url="/moollish/registros">
                                    <label class="form-check-label" for="registros">Registros</label>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="fichaAnimal" data-url="/fichaAnimal">
                                    <label class="form-check-label" for="fichaAnimal">Ficha animal</label>
                                </div>
                            </div>
                           {{--  <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="fichaAnimalCreate" data-url="/animal/store">
                                    <label class="form-check-label" for="fichaAnimalCreate">Crear animal</label>
                                </div>
                            </div> --}}

                        </div>
                    </div>

                   {{--  <div class="mt-2 section-details" style="display: none;">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="fichaAnimal" data-url="/fichaAnimal">
                                    <label class="form-check-label" for="fichaAnimal">Ficha animal</label>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <!-- Sección de Predios -->
                <div class="list-group-item">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="predios-check" data-section="predios">
                        <label class="form-check-label d-flex justify-content-between align-items-center" for="predios-check">
                            <div>
                                <h5 class="mb-1">Predios</h5>
                                <p class="mb-1">Gestión de predios</p>
                            </div>
                            <span class="badge bg-secondary download-status">No descargado</span>
                        </label>
                    </div>
                    <div class="mt-2 section-details" style="display: none;">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="predios-list" data-url="/predios">
                                    <label class="form-check-label" for="predios-list">Listar predios</label>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="predios-create" data-url="/predios/create">
                                    <label class="form-check-label" for="predios-create">Crear predio</label>
                                </div>
                            </div>

                            <!-- Sección dinámica para edición de predios del usuario -->
                            <div class="list-group-item list-group-item-action">
                                <div class="mb-2">
                                    <strong>Edición de mis predios:</strong>
                                </div>
                                <div id="user-predios-container">
                                    <div class="text-center">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <span class="ms-2">Cargando predios...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="economia-check" data-section="economia">
                        <label class="form-check-label d-flex justify-content-between align-items-center" for="economia-check">
                            <div>
                                <h5 class="mb-1">Economia</h5>
                                <p class="mb-1">Unicamente (visualiza tu informacion economica) </p>
                            </div>
                            <span class="badge bg-secondary download-status">No descargado</span>
                        </label>
                    </div>
                    <div class="mt-2 section-details" style="display: none;">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-action">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="economia-list" data-url="/movimiento/show">
                                    <label class="form-check-label" for="economia -list">Economia de predios</label>
                                </div>
                            </div>


                            <!-- Sección dinámica para edición de predios del usuario -->

                        </div>
                    </div>
                </div>
                <!-- Añade más secciones según tu aplicación -->
            </div>
            <div class="row mb-4">
                <div class="col-md-12 d-flex ">
                    <button id="sync-all" class="btn btn-primary ">
                        <i class="fas fa-sync"></i> Sincronizar todo el contenido seleccionado
                    </button>
                    <button id="clear-cache" class="btn btn-outline-danger mx-2">
                        <i class="fas fa-trash"></i> Limpiar caché
                    </button>
                </div>
            </div>
    </div>
</div>

<!-- Modal personalizado de compatibilidad -->
<div id="custom-compatibility-modal" class="custom-modal-container">
    <div class="custom-modal-backdrop"></div>
    <div class="custom-modal">
        <div class="custom-modal-header">
            <h5>Compatibilidad limitada</h5>
            <button type="button" class="custom-close-btn" id="custom-close-btn">&times;</button>
        </div>
        <div class="custom-modal-body" id="custom-modal-content">
            <!-- El contenido será insertado dinámicamente -->
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="custom-btn custom-btn-secondary" id="custom-cancel-btn">Cancelar</button>
            <button type="button" class="custom-btn custom-btn-primary" id="custom-force-cache-btn">Intentar de todos modos</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Verificar si el navegador soporta Service Worker y Cache API
    const supportsServiceWorker = 'serviceWorker' in navigator;
    const supportsCache = 'caches' in window;
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

    // Variable para permitir intentar usar caché aunque no se detecte inicialmente
    let forceAttemptCache = false;

    // Clave para localStorage
    const STORAGE_KEY = 'moollish_offline_sections';

    // Función para cargar los predios del usuario autenticado
    async function loadUserPredios() {
        // Contendor donde se mostrarán los predios
        const container = document.getElementById('user-predios-container');

        try {
            // Realizar petición para obtener los predios del usuario usando la ruta web
            const response = await fetch('/web/predios', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include' // Para incluir cookies de sesión
            });

            if (!response.ok) {
                // Si no está autorizado, mostrar mensaje adecuado
                if (response.status === 401) {
                    container.innerHTML = `
                        <div class="alert alert-warning py-2">
                            Debes iniciar sesión para ver tus predios.
                        </div>`;
                    return;
                }
                throw new Error('Error al cargar los predios');
            }

            const data = await response.json();

            // Si no hay predios
            if (!data.predios || data.predios.length === 0) {
                container.innerHTML = '<div class="alert alert-info py-2">No tienes predios asignados.</div>';
                return;
            }

            // Crear checkbox para cada predio
            let prediosHtml = '';

            data.predios.forEach(predio => {
                const checkboxId = `predio-edit-${predio.id}`;
                const predioUrl = `/predios/${predio.id}/edit`;

                prediosHtml += `
                <div class="form-check mb-2">
                    <input class="form-check-input predio-edit-checkbox"
                           type="checkbox"
                           id="${checkboxId}"
                           data-url="${predioUrl}"
                           data-predio-id="${predio.id}"
                           data-predio-name="${predio.nombre_predio || 'Predio ' + predio.id}">
                    <label class="form-check-label" for="${checkboxId}">
                        ${predio.nombre_predio || 'Predio ' + predio.id}
                    </label>
                </div>`;
            });

            container.innerHTML = prediosHtml;

            // Añadir listeners a los nuevos checkboxes
            document.querySelectorAll('.predio-edit-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked && navigator.onLine) {
                        const url = this.dataset.url;
                        cacheUrl(new URL(url, window.location.origin).href);
                        updateStorageInfo();

                        // También cacheamos recursos asociados que necesita la página de edición
                        const predioId = this.dataset.predioId;
                        cacheUrl(new URL(`/web/predio/${predioId}/details`, window.location.origin).href);
                        saveSectionsState();
                    }
                });
            });

        } catch (error) {
            console.error('Error al cargar predios:', error);
            container.innerHTML = `
                <div class="alert alert-danger py-2">
                    Error al cargar predios: ${error.message}
                    <button class="btn btn-sm btn-outline-danger ms-2" onclick="loadUserPredios()">
                        <i class="fas fa-sync-alt"></i> Reintentar
                    </button>
                </div>
            `;
        }
    }

    // Funciones para el modal personalizado
    function showCustomModal(content, showForceButton = true) {
        document.getElementById('custom-modal-content').innerHTML = content;
        document.getElementById('custom-compatibility-modal').style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevenir scroll

        // Mostrar u ocultar el botón "Intentar de todos modos"
        document.getElementById('custom-force-cache-btn').style.display = showForceButton ? 'block' : 'none';

        // Gestionar eventos de cierre
        document.getElementById('custom-close-btn').onclick = hideCustomModal;
        document.getElementById('custom-cancel-btn').onclick = hideCustomModal;
        document.querySelector('.custom-modal-backdrop').onclick = hideCustomModal;

        if (showForceButton) {
            document.getElementById('custom-force-cache-btn').onclick = function() {
                forceAttemptCache = true;
                document.getElementById('compatibility-warning').style.display = 'block';
                initializeOfflineFeatures();
                hideCustomModal();
            };
        }
    }

    function hideCustomModal() {
        document.getElementById('custom-compatibility-modal').style.display = 'none';
        document.body.style.overflow = ''; // Restaurar scroll
    }

    // Función para mostrar notificaciones
    function showNotification(message, type = 'success', duration = 5000) {
        const notificationArea = document.getElementById('notification-area');
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show`;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        notificationArea.appendChild(notification);

        // Auto-cerrar después de X segundos
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, duration);

        return notification;
    }

    // Función para guardar el estado de las secciones
    function saveSectionsState() {
        if (!('localStorage' in window)) return;

        const sectionsState = {};

        // Guardar estado de cada sección principal
        document.querySelectorAll('[data-section]').forEach(checkbox => {
            const sectionId = checkbox.dataset.section;
            const sectionContainer = checkbox.closest('.list-group-item');
            const statusBadge = sectionContainer.querySelector('.download-status');
            const urlCheckboxes = sectionContainer.querySelectorAll('.section-details input[type="checkbox"][data-url]');

            // Obtener URLs marcadas en esta sección
            const checkedUrls = [];
            urlCheckboxes.forEach(urlCheckbox => {
                if (urlCheckbox.checked) {
                    checkedUrls.push(urlCheckbox.dataset.url);
                }
            });

            sectionsState[sectionId] = {
                checked: checkbox.checked,
                status: statusBadge.textContent,
                statusClass: statusBadge.className,
                urls: checkedUrls
            };
        });

        // Guardar en localStorage
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(sectionsState));
            console.log('Estado de secciones guardado en localStorage');
        } catch (error) {
            console.error('Error al guardar en localStorage:', error);
        }
    }

    // Función para cargar el estado de las secciones
    function loadSectionsState() {
        if (!('localStorage' in window)) return;

        try {
            const savedState = localStorage.getItem(STORAGE_KEY);
            if (!savedState) return;

            const sectionsState = JSON.parse(savedState);

            // Aplicar estado a cada sección
            Object.keys(sectionsState).forEach(sectionId => {
                const sectionData = sectionsState[sectionId];
                const sectionCheckbox = document.querySelector(`[data-section="${sectionId}"]`);

                if (sectionCheckbox) {
                    const sectionContainer = sectionCheckbox.closest('.list-group-item');
                    const statusBadge = sectionContainer.querySelector('.download-status');
                    const sectionDetails = sectionContainer.querySelector('.section-details');
                    const urlCheckboxes = sectionContainer.querySelectorAll('.section-details input[type="checkbox"][data-url]');

                    // Actualizar checkbox principal
                    sectionCheckbox.checked = sectionData.checked;

                    // Mostrar/ocultar detalles
                    if (sectionData.checked) {
                        sectionDetails.style.display = 'block';
                    }

                    // Actualizar estado
                    statusBadge.textContent = sectionData.status;
                    statusBadge.className = sectionData.statusClass;

                    // Actualizar URLs marcadas
                    urlCheckboxes.forEach(urlCheckbox => {
                        urlCheckbox.checked = sectionData.urls.includes(urlCheckbox.dataset.url);
                    });
                }
            });

            console.log('Estado de secciones cargado desde localStorage');
        } catch (error) {
            console.error('Error al cargar desde localStorage:', error);
        }
    }

    // Verificar compatibilidad
    function checkCompatibility() {
        let message = '';
        let isError = false;

        if (!supportsServiceWorker) {
            message += '• Tu navegador no soporta Service Workers, necesario para el modo offline.<br>';
            isError = true;
        }

        if (!supportsCache) {
            message += '• Tu navegador podría no soportar completamente la API de Caché.<br>';

            // Si estamos en Safari móvil o iOS WebView, dar instrucciones específicas
            if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) {
                message += '<br><strong>Para usuarios de iOS:</strong><br>';
                message += '• Ve a Ajustes > Safari > Avanzado > Caché web y activa la opción.<br>';
                message += '• Es posible que algunas funciones offline tengan limitaciones en iOS.<br>';
            }

            message += '<br>Puedes intentar continuar, pero algunas funciones podrían no estar disponibles.';

            // Mostrar el modal personalizado
            showCustomModal(message, true);
            return false;
        }

        if (isError) {
            // Mostrar alerta para errores críticos sin la opción de continuar
            showCustomModal(message, false);
            return false;
        }

        return true;
    }

    // Función para verificar si una URL está en caché
    async function checkUrlInCache(url) {
        // Usar el método de offlineManager si está disponible
        if (window.offlineManager && typeof window.offlineManager.isInCache === 'function') {
            try {
                return await window.offlineManager.isInCache(url);
            } catch (error) {
                console.error('Error al verificar caché con offlineManager:', error);
            }
        }

        // Alternativa si no está disponible offlineManager
        if ('caches' in window) {
            try {
                const cache = await caches.open('moollish-cache-v1');
                const cachedResponse = await cache.match(new URL(url, window.location.origin).href);
                return !!cachedResponse;
            } catch (error) {
                console.error('Error al verificar caché:', error);
            }
        }

        return false;
    }

    // Función para verificar el estado real de las secciones en caché
    async function verifyDownloadedSections() {
        // Comprobar cada sección
        const sections = document.querySelectorAll('[data-section]');

        for (const sectionCheckbox of sections) {
            const sectionId = sectionCheckbox.dataset.section;
            const sectionContainer = sectionCheckbox.closest('.list-group-item');
            const statusBadge = sectionContainer.querySelector('.download-status');
            const urlCheckboxes = sectionContainer.querySelectorAll('.section-details input[type="checkbox"][data-url]');

            let cachedUrls = 0;

            // Verificar cada URL de la sección
            for (const urlCheckbox of urlCheckboxes) {
                const url = urlCheckbox.dataset.url;
                const isInCache = await checkUrlInCache(url);

                // Actualizar el checkbox de acuerdo al resultado
                urlCheckbox.checked = isInCache;

                if (isInCache) {
                    cachedUrls++;
                }
            }

            // Actualizar el estado de la sección
            if (cachedUrls === urlCheckboxes.length && cachedUrls > 0) {
                statusBadge.className = 'badge bg-success download-status';
                statusBadge.textContent = 'Descargado';
                sectionCheckbox.checked = true;
                sectionContainer.querySelector('.section-details').style.display = 'block';
            } else if (cachedUrls > 0) {
                statusBadge.className = 'badge bg-warning download-status';
                statusBadge.textContent = `Parcial (${cachedUrls}/${urlCheckboxes.length})`;
                sectionCheckbox.checked = true;
                sectionContainer.querySelector('.section-details').style.display = 'block';
            } else {
                statusBadge.className = 'badge bg-secondary download-status';
                statusBadge.textContent = 'No descargado';
                sectionCheckbox.checked = false;
                sectionContainer.querySelector('.section-details').style.display = 'none';
            }
        }

        // Guardar el estado verificado
        saveSectionsState();
    }

    // Inicializar características offline si hay compatibilidad
    function initializeOfflineFeatures() {
        // Actualizar estado inicial
        updateConnectionStatus();
        updateStorageInfo();

        // Cargar los predios del usuario
        loadUserPredios();

        // Primero cargar estado desde localStorage
        loadSectionsState();

        // Después verificar el estado real de caché (esto puede tardar un poco)
        setTimeout(() => {
            verifyDownloadedSections();
        }, 500);

        // Listeners de conexión
        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);

        // Toggle para mostrar/ocultar detalles de sección
        document.querySelectorAll('[data-section]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const sectionDetails = this.closest('.list-group-item').querySelector('.section-details');
                if (this.checked) {
                    sectionDetails.style.display = 'block';

                    // Si está marcada la sección, intentar descargar
                    if (navigator.onLine) {
                        downloadSection(this.dataset.section);
                    } else {
                        showNotification('No hay conexión a internet. No se pueden descargar nuevas secciones.', 'warning');
                        this.checked = false;
                    }
                } else {
                    sectionDetails.style.display = 'none';
                    saveSectionsState(); // Guardar cuando se desmarca una sección
                }
            });
        });

        // Botón para sincronizar todo
        document.getElementById('sync-all').addEventListener('click', async function() {
            if (!navigator.onLine) {
                showNotification('No hay conexión a internet. No se pueden sincronizar las secciones.', 'warning');
                return;
            }

            const checkedSections = document.querySelectorAll('[data-section]:checked');
            if (checkedSections.length === 0) {
                showNotification('Selecciona al menos una sección para sincronizar.', 'info');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spin fa-spinner"></i> Sincronizando...';

            const promises = Array.from(checkedSections).map(checkbox =>
                downloadSection(checkbox.dataset.section)
            );

            await Promise.all(promises);

            this.disabled = false;
            this.innerHTML = '<i class="fas fa-sync"></i> Sincronizar todo el contenido seleccionado';

            showNotification('Sincronización completada correctamente.', 'success');
        });

        // Botón para limpiar cache
        document.getElementById('clear-cache').addEventListener('click', async function() {
            const result = await clearAllCache();
            if (result) {
                // Limpiar también el estado guardado
                localStorage.removeItem(STORAGE_KEY);
            }
        });

        // Cachear URLs individuales
        document.querySelectorAll('input[data-url]').forEach(checkbox => {
            checkbox.addEventListener('change', async function() {
                if (this.checked && navigator.onLine) {
                    const url = this.dataset.url;
                    await cacheUrl(new URL(url, window.location.origin).href);
                    await updateStorageInfo();
                    saveSectionsState(); // Guardar cuando se marca/desmarca una URL
                }
            });
        });
    }

    // Verificar estado de la conexión
    function updateConnectionStatus() {
        const status = document.getElementById('connection-status');
        if (navigator.onLine) {
            status.className = 'badge bg-success me-2';
            status.textContent = 'Online';
        } else {
            status.className = 'badge bg-danger me-2';
            status.textContent = 'Offline';
        }
    }

    // Actualizar información de almacenamiento
    async function updateStorageInfo() {
        if ('storage' in navigator && 'estimate' in navigator.storage) {
            try {
                const estimate = await navigator.storage.estimate();
                const usedSpace = estimate.usage || 0;
                const totalSpace = estimate.quota || 0;
                const percentUsed = Math.round((usedSpace / totalSpace) * 100);

                const usedMB = Math.round(usedSpace / (1024 * 1024));
                const totalMB = Math.round(totalSpace / (1024 * 1024));

                document.getElementById('storage-info').textContent =
                    `Espacio utilizado: ${usedMB} MB de ${totalMB} MB`;

                const progressBar = document.getElementById('storage-progress');
                progressBar.style.width = `${percentUsed}%`;
                progressBar.textContent = `${percentUsed}%`;
                progressBar.setAttribute('aria-valuenow', percentUsed);

                // Cambiar color basado en el porcentaje usado
                if (percentUsed > 80) {
                    progressBar.className = 'progress-bar bg-danger';
                } else if (percentUsed > 60) {
                    progressBar.className = 'progress-bar bg-warning';
                } else {
                    progressBar.className = 'progress-bar bg-success';
                }
            } catch (error) {
                console.error('Error al obtener información de almacenamiento:', error);
                document.getElementById('storage-info').textContent =
                    'No se pudo obtener información de almacenamiento';
            }
        } else {
            document.getElementById('storage-info').textContent =
                'Tu navegador no soporta la API de Storage';
        }
    }

    // Cargar una URL en el cache
    async function cacheUrl(url) {
        try {
            const cache = await caches.open('moollish-cache-v1');
            await cache.add(url);
            console.log(`URL cacheada: ${url}`);
            return true;
        } catch (error) {
            console.error(`Error al cachear URL ${url}:`, error);
            return false;
        }
    }

    // Descargar sección completa
    async function downloadSection(sectionId) {
        const sectionElement = document.querySelector(`[data-section="${sectionId}"]`);
        if (!sectionElement) return false;

        const sectionContainer = sectionElement.closest('.list-group-item');
        const statusBadge = sectionContainer.querySelector('.download-status');
        const urlCheckboxes = sectionContainer.querySelectorAll('.section-details input[type="checkbox"][data-url]');

        statusBadge.className = 'badge bg-warning download-status';
        statusBadge.textContent = 'Descargando...';

        let successCount = 0;

        for (const checkbox of urlCheckboxes) {
            checkbox.checked = true;
            const url = checkbox.dataset.url;

            try {
                const success = await cacheUrl(new URL(url, window.location.origin).href);

                // Si es un predio para editar, cacheamos también los detalles API
                if (checkbox.classList.contains('predio-edit-checkbox') && success) {
                    const predioId = checkbox.dataset.predioId;
                    await cacheUrl(new URL(`/api/predio/${predioId}/details`, window.location.origin).href);
                }

                if (success) successCount++;
            } catch (error) {
                console.error(`Error al descargar ${url}:`, error);
            }
        }

        if (successCount === urlCheckboxes.length) {
            statusBadge.className = 'badge bg-success download-status';
            statusBadge.textContent = 'Descargado';
        } else if (successCount > 0) {
            statusBadge.className = 'badge bg-warning download-status';
            statusBadge.textContent = `Parcial (${successCount}/${urlCheckboxes.length})`;
        } else {
            statusBadge.className = 'badge bg-danger download-status';
            statusBadge.textContent = 'Error';
        }

        await updateStorageInfo();
        saveSectionsState(); // Guardar después de descargar sección
        return successCount > 0;
    }

    // Limpiar todo el cache
    async function clearAllCache() {
        if (!confirm('¿Estás seguro de que deseas eliminar todo el contenido offline? Necesitarás conexión a internet para volver a usar la aplicación.')) {
            return false;
        }

        try {
            const cacheNames = await caches.keys();
            await Promise.all(cacheNames.map(name => caches.delete(name)));

            // Actualizar UI
            document.querySelectorAll('.download-status').forEach(badge => {
                badge.className = 'badge bg-secondary download-status';
                badge.textContent = 'No descargado';
            });

            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });

            document.querySelectorAll('.section-details').forEach(section => {
                section.style.display = 'none';
            });

            showNotification('Cache limpiado correctamente', 'success');
            await updateStorageInfo();
            saveSectionsState(); // Guardar después de limpiar
            return true;
        } catch (error) {
            console.error('Error al limpiar cache:', error);
            showNotification('Error al limpiar el cache', 'danger');
            return false;
        }
    }

    // Inicializar al cargar el documento
    document.addEventListener('DOMContentLoaded', async () => {
        if (checkCompatibility() || forceAttemptCache) {
            initializeOfflineFeatures();

            // Agregar botón para verificar el estado real
            const actionsContainer = document.querySelector('.col-md-12.d-flex');
            if (actionsContainer) {
                const verifyButton = document.createElement('button');
                verifyButton.id = 'verify-cache';
                verifyButton.className = 'btn btn-outline-info mx-2';
                verifyButton.innerHTML = '<i class="fas fa-sync-alt"></i> Verificar estado';
                verifyButton.addEventListener('click', async function() {
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spin fa-spinner"></i> Verificando...';

                    await verifyDownloadedSections();

                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-sync-alt"></i> Verificar estado';

                    showNotification('El estado de las secciones ha sido actualizado según el contenido real en caché.', 'info');
                });

                actionsContainer.appendChild(verifyButton);
            }
        }
    });
</script>
@endsection
