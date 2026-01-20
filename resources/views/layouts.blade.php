<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="flexilecode" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <style>
       * {
            font-family: "Plus Jakarta Sans", sans-serif;
        }
    </style>
    <title> @yield('title') - Moollish</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo4.png') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/dataTables.bs5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}">
    @yield('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const miniButton = document.getElementById('menu-mini-button');
            const expandButton = document.getElementById('menu-expend-button');
            const navigation = document.querySelector('.nxl-navigation');
            const container = document.querySelector('.nxl-container');
            const header = document.querySelector('.nxl-header');

            // Función para cargar el estado inicial
            function loadInitialState() {
                const savedState = localStorage.getItem('sidebarState');

                if (savedState === 'closed') {
                    navigation.classList.remove('open');
                    navigation.classList.add('closed');
                    container.classList.remove('open');
                    container.classList.add('closed');
                    header.classList.remove('open');
                    header.classList.add('closed');
                    miniButton.style.display = 'block';
                    expandButton.style.display = 'none';
                } else {
                    navigation.classList.remove('closed');
                    navigation.classList.add('open');
                    container.classList.remove('closed');
                    container.classList.add('open');
                    header.classList.remove('closed');
                    header.classList.add('open');
                    miniButton.style.display = 'none';
                    expandButton.style.display = 'block';
                }

                // Marcar como inicializado
                navigation.classList.add('initialized');
                container.classList.add('initialized');
                header.classList.add('initialized');
            }

            // Cargar estado inicial
            loadInitialState();

            // Toggle Sidebar
            function toggleSidebar() {
                const isOpen = navigation.classList.contains('open');

                if (isOpen) {
                    navigation.classList.remove('open');
                    navigation.classList.add('closed');
                    container.classList.remove('open');
                    container.classList.add('closed');
                    header.classList.remove('open');
                    header.classList.add('closed');
                    miniButton.style.display = 'block';
                    expandButton.style.display = 'none';
                    localStorage.setItem('sidebarState', 'closed');
                } else {
                    navigation.classList.remove('closed');
                    navigation.classList.add('open');
                    container.classList.remove('closed');
                    container.classList.add('open');
                    header.classList.remove('closed');
                    header.classList.add('open');
                    miniButton.style.display = 'none';
                    expandButton.style.display = 'block';
                    localStorage.setItem('sidebarState', 'open');
                }
            }

            // Event Listeners
            miniButton.addEventListener('click', toggleSidebar);
            expandButton.addEventListener('click', toggleSidebar);
        });
    </script>
    


    <style>
        .header-wrapper {
            min-height: 80px;
            display: flex;
            align-items: center;
        }

        /* Estilos iniciales para evitar flash de contenido */
        .nxl-navigation,
        .nxl-container,
        .nxl-header {
            transition: none !important;
            visibility: hidden;
        }

        .nxl-navigation.initialized,
        .nxl-container.initialized,
        .nxl-header.initialized {
            transition: all 0.3s ease !important;
            visibility: visible;
        }

        .sesion6 {
            width: 60px
        }

        .animal-row {
            transition: 0.1s linear;
            cursor: pointer;

        }


        .animal-row:hover {
            background: #e49b39;
            color: white;

        }

        .input-dinamico-animal {
            display: flex;
            width: auto;
            border: 1px solid rgb(229, 231, 235);
            border-radius: 5px;
            height: 46px;
            padding: 0px 0px 0px 12px;
            align-items: center;
            justify-content: space-between
        }

        .buton-dinamico-animal {
            color: #e49b39;
            border: 0px solid;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 9px 16px;
            border-radius: 0px 5px 5px 0px;
            background: transparent;
        }

        .buton-dinamico-animal:hover {
            cursor: pointer;
        }

        .alert-verification {
            background-color: #FEF3E4;
            border-left: 4px solid #E49B39;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 10px 15px;
        }

        .alert-verification-content {
            display: flex;
            align-items: center;
        }

        .alert-verification-icon {
            margin-right: 10px;
            flex-shrink: 0;
        }

        .alert-verification-text {
            font-size: 14px;
            color: #333;
        }

        .alert-verification-text a {
            color: #E49B39;
            font-weight: 600;
            text-decoration: none;
        }

        .alert-verification-text a:hover {
            text-decoration: underline;
        }

        @media (width < 600px) {
            .container {
                padding: 20px 10px !important;
            }
        }
    </style>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#e49b39">
    <link rel="stylesheet" href="{{ asset('build/assets/app-HauAVEFt.css') }}">
    <script src="{{ asset('build/assets/app-DlBKZGL7.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <!-- Otros estilos y scripts que necesites en head -->
</head>

<body>
    <div id="app">
        @include('navigation-menu') {{-- Intentando con el archivo estándar de Jetstream/Breeze --}}

        <main class="">
            @if(auth()->user() && auth()->user()->email_verified_at === null)
            <div class="alert-verification">
                <div class="alert-verification-content">
                    <div class="alert-verification-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="#E49B39" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2h-2v-2zm0-8h2v6h-2V7z"/>
                        </svg>
            </div>
                    <div class="alert-verification-text">
                        <span>Aún no has verificado tu correo electrónico, revisa tu bandeja o </span>
                        <a href="#" id="resendVerificationEmail">reenvía el correo de verificación</a>
                    </div>
                </div>
            </div>
                                @endif
            <div id="offline-indicator"
                style="display: none; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background-color: #dc3545; color: white; padding: 10px 20px; border-radius: 5px; z-index: 9999; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                <span class="material-symbols-outlined"
                    style="vertical-align: middle; font-size: 18px; margin-right: 5px;">signal_wifi_off</span>
                Estás sin conexión
                        </div>

            <!-- Indicador Online -->
            <div id="online-indicator"
                style="display: none; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background-color: #28a745; color: white; padding: 10px 20px; border-radius: 5px; z-index: 9999; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                <span class="material-symbols-outlined"
                    style="vertical-align: middle; font-size: 18px; margin-right: 5px;">wifi</span>
                Vuelves a tener conexión
            </div>

                @yield('content')
            @yield('popup')
    </main>

   {{--  @yield('modal') --}}

    </div>

    <!-- Script para registrar el Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(registration => {
                        console.log('Service Worker registrado con éxito:', registration.scope);
                    })
                    .catch(error => {
                        console.log('Error al registrar el Service Worker:', error);
                    });
            });
        }
    </script>

    <!-- vendors.min.js {always must need to be top} -->
    <script src="{{ asset('js/indexedDB-config.js') }}"></script>
    <script src="{{ asset('js/db-init.js') }}"></script>
    <script src="{{ asset('js/alert-helper.js') }}"></script>
    @yield('scripts')
    <!-- Indicador Offline -->
    <!--! BEGIN: Vendors JS !-->
    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/js/dataTables.bs5.min.js') }} "></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>

 <!-- esto se lo acabo de agg ahora par alos modales 19/01/2026 quitar si daña algo} -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- vendors.min.js {always must need to be top} -->

    <script>
        // Encapsular todo en una función ejecutada después de que los scripts estén cargados
        $(document).ready(function() {
            console.log('Initializing connection status script...');

            // Usar localStorage para persistencia entre vistas
            // Si no hay valor guardado, usar el estado actual
            let isCurrentlyOnline = localStorage.getItem('isOnline') !== null
                ? localStorage.getItem('isOnline') === 'true'
                : navigator.onLine;

            console.log('Initial connection state:', isCurrentlyOnline ? 'Online' : 'Offline');

            // Asegurar que los elementos del DOM existen
            const $offlineIndicator = $('#offline-indicator');
            const $onlineIndicator = $('#online-indicator');

            if (!$offlineIndicator.length || !$onlineIndicator.length) {
                console.error('Connection indicators not found in DOM!');
                return; // No ejecutar si no se encuentran los elementos
            }

            function handleOnline() {
                console.log('Online event triggered');
                if (!isCurrentlyOnline) { // Solo actuar si ANTES estaba offline
                    isCurrentlyOnline = true;
                    localStorage.setItem('isOnline', 'true'); // Guardar estado en localStorage
                    showTemporaryOnlineIndicator();
                    hideOfflineIndicator();

                    // Intentar sincronizar datos pendientes si existe la función
                    if (typeof checkAndSyncPendingData === 'function') {
                        checkAndSyncPendingData();
                    }
                }
            }

            function handleOffline() {
                console.log('Offline event triggered');
                if (isCurrentlyOnline) { // Solo actuar si ANTES estaba online
                    isCurrentlyOnline = false;
                    localStorage.setItem('isOnline', 'false'); // Guardar estado en localStorage
                    showPersistentOfflineIndicator();
                    hideOnlineIndicator();
                }
            }

            // Funciones auxiliares con comprobaciones de existencia
            function showTemporaryOnlineIndicator() {
                console.log('Showing temporary online indicator');
                if ($onlineIndicator.length) {
                    $onlineIndicator.fadeIn();
                    // Asegurar que cualquier temporizador anterior se cancele
                    if (window.onlineIndicatorTimeout) {
                        clearTimeout(window.onlineIndicatorTimeout);
                    }
                    // Crear nuevo temporizador y guardarlo globalmente
                    window.onlineIndicatorTimeout = setTimeout(function() {
                        hideOnlineIndicator();
                    }, 5000);
                }
            }

            function hideOfflineIndicator() {
                console.log('Hiding offline indicator');
                if ($offlineIndicator.length) {
                    $offlineIndicator.fadeOut();
                }
            }

            function showPersistentOfflineIndicator() {
                console.log('Showing persistent offline indicator');
                if ($offlineIndicator.length) {
                    $offlineIndicator.fadeIn();
                }
            }

            function hideOnlineIndicator() {
                console.log('Hiding online indicator');
                if ($onlineIndicator.length) {
                    $onlineIndicator.fadeOut();
                }
            }

            // Configuración inicial basada en estado
            // Comprobar el estado actual según el navegador y localStorage
            if (!navigator.onLine || localStorage.getItem('isOnline') === 'false') {
                console.log('Initial state: OFFLINE - showing indicator');
                showPersistentOfflineIndicator();
                isCurrentlyOnline = false;
                localStorage.setItem('isOnline', 'false');
            } else {
                console.log('Initial state: ONLINE - hiding indicators');
                hideOfflineIndicator();
                hideOnlineIndicator();
                isCurrentlyOnline = true;
                localStorage.setItem('isOnline', 'true');
            }

            // Añadir event listeners
            window.addEventListener('online', handleOnline);
            window.addEventListener('offline', handleOffline);
            console.log('Connection event listeners registered');
        });
    </script>

    {{-- Script para reenviar correo de verificación --}}
    <script>
        $(document).ready(function() {
            // Usar delegación de eventos por si el enlace se reemplaza dinámicamente
            $(document).on('click', '#resendVerificationEmail', function(e) {
                e.preventDefault();
                console.log('Botón de reenvío de correo clickeado');

                const $link = $(this);
                const originalText = $link.text();
                const verificationDiv = $link.closest('.alert-verification-text'); // Encontrar el contenedor padre

                // Mostrar indicador de carga
                $link.text('Enviando...');
                $link.css('pointer-events', 'none'); // Deshabilitar clics repetidos
                console.log('Estado del botón cambiado a "Enviando..."');

                // Realizar solicitud AJAX para reenviar el correo
                console.log('Iniciando solicitud AJAX a /email/verification-notification'); // URL estándar de Laravel
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                if (!csrfToken) {
                    console.error('Token CSRF no encontrado!');
                    verificationDiv.html('<span style="color: #dc3545;">Error de configuración. No se pudo enviar.</span>');
                    // Restaurar después de error
                    setTimeout(() => {
                        verificationDiv.html(`<span>Aún no has verificado tu correo electrónico, revisa tu bandeja o </span><a href="#" id="resendVerificationEmail">${originalText}</a>`);
                    }, 5000);
                    return; // Detener si no hay token
                }

                $.ajax({
                    url: '{{ route("verification.resend") }}', // Usar la ruta nombrada estándar de Laravel
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        console.log('Respuesta exitosa recibida:', response);
                        let message = '¡Correo de verificación reenviado! Revisa tu bandeja de entrada (y spam).'; // Mensaje por defecto
                        if (typeof response === 'object' && response.message) {
                             // Si la respuesta es JSON y tiene mensaje
                             message = response.message;
                        }
                        verificationDiv.html('<span style="color: #28a745;">' + message + '</span>');
                        console.log('Mensaje de éxito mostrado');

                        // No restauramos el enlace automáticamente en caso de éxito,
                        // el usuario debería recargar o verificar su correo.
                        // Podríamos ocultar el aviso completo si se prefiere.
                        // verificationDiv.closest('.alert-verification').fadeOut(5000);
                    },
                    error: function(xhr) {
                        console.error('Error en la solicitud AJAX:', xhr);
                        let errorMessage = 'Ocurrió un error al reenviar el correo. Inténtalo nuevamente más tarde.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                         verificationDiv.html('<span style="color: #dc3545;">' + errorMessage + '</span>');
                         console.log('Mensaje de error mostrado');

                        // Restaurar el enlace después de 5 segundos en caso de error
                        setTimeout(function() {
                            console.log('Restaurando enlace de reenvío después de error');
                             verificationDiv.html(`<span>Aún no has verificado tu correo electrónico, revisa tu bandeja o </span><a href="#" id="resendVerificationEmail">${originalText}</a>`);
                        }, 5000);
                    },
                    complete: function() {
                        // Ya no se restaura el texto/estado aquí para el caso de éxito
                         if (!$link.parent().find('span[style*="color: #28a745"]').length) { // Solo restaurar si no fue éxito
                             $link.text(originalText);
                             $link.css('pointer-events', 'auto');
                             console.log('Solicitud AJAX completada (error), restaurando estado del botón');
                         }
                    }
                });
            });
        });
    </script>
</body>

</html>