<nav class="nxl-navigation" id="mainNavigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <div class="logo-center">
                <div class="moollish"></div>
                <span class="moollish-span" style="font-size: 25px;     color :black">Moollish®</span>
            </div>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                @php
                $role = Auth::check() ? Auth::user()->role->name : null;
                @endphp
                @if ($user->membership?->is_active)
                @if ($role)
                <li class="nxl-item nxl-caption">
                    <label>Paneles</label>
                </li>
                @if ($role == 'admin')
                <li class="nxl-item nxl-hasmenu">
                    <a href="/dashboard" class="nxl-link">
                        <span class="nxl-micon"><span class="material-symbols-outlined">
                                trending_up
                            </span></span>
                        <span class="nxl-mtext">Dashboard</span><span class="nxl-arrow"></span>
                    </a>
                </li>
                @endif
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('inicio') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <span class="material-symbols-outlined">
                                grid_view
                            </span>
                        </span>

                        <span class="nxl-mtext">Inicio

                        </span><span class="nxl-arrow"></span>
                    </a>
                </li>
                @if ($role == 'admin' || $role == 'propietario')
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('predios.index') }}" class="nxl-link">
                        <span class="nxl-micon"><span class="material-symbols-outlined">
                                villa
                            </span></span>
                        <span class="nxl-mtext">Predios</span><span class="nxl-arrow"></span>
                    </a>
                </li>

                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('users.index') }}" class="nxl-link">
                        <span class="nxl-micon"><span class="material-symbols-outlined">
                                person
                            </span></span>
                        <span class="nxl-mtext">Usuarios</span><span class="nxl-arrow"></span>
                    </a>
                </li>

                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('inventario.general') }}" class="nxl-link">
                        <span class="nxl-micon"><span class="material-symbols-outlined">
                                subject
                            </span></span>
                        <span class="nxl-mtext">Mis animales</span><span class="nxl-arrow"></span>
                    </a>
                </li>
                @endif
                @if ($role == 'admin')
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('ExportePredio') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <span class="material-symbols-outlined">
                                table_view
                            </span>
                        </span>
                        <span class="nxl-mtext">Export Caracterización</span>
                        <span class="nxl-arrow"></span>
                    </a>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('dashboards.index') }}" class="nxl-link">
                        <span class="nxl-micon"><span class="material-symbols-outlined">
                                trending_up
                            </span></span>
                        <span class="nxl-mtext">Dashboard Caracterización</span><span class="nxl-arrow"></span>
                    </a>
                </li>

                @endif

                <li class="nxl-item nxl-caption">
                    <label>Suscripciones</label>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('membresias') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <span class="material-symbols-outlined">
                                diamond
                            </span>
                        </span>

                        <span class="nxl-mtext">Mi suscripción</span><span class="nxl-arrow"></span>
                    </a>
                </li>
                <li class="nxl-item nxl-caption">
                    <label>Ajustes</label>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('ajustes') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <span class="material-symbols-outlined">
                                settings
                            </span>
                        </span>
                        <span class="nxl-mtext">Configuraciones</span><span class="nxl-arrow"></span>
                    </a>
                </li>
                {{-- Política de Uso --}}
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('politica.uso') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <span class="material-symbols-outlined">
                                policy
                            </span>
                        </span>
                        <span class="nxl-mtext">Política de Uso</span><span class="nxl-arrow"></span>
                    </a>
                </li>
                {{-- Información Legal --}}
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('informacion.legal') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <span class="material-symbols-outlined">
                                gavel
                            </span>
                        </span>
                        <span class="nxl-mtext">Información Legal</span><span class="nxl-arrow"></span>
                    </a>
                </li>

                {{-- <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('offline.content') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <span class="material-symbols-outlined">
                                wifi_off
                            </span>
                        </span>
                        <span class="nxl-mtext">Modo Offline</span><span class="nxl-arrow"></span>
                    </a>
                </li> --}}

                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('logout') }}" class="nxl-link logout-tab"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="nxl-micon"><span class="material-symbols-outlined">
                                logout
                            </span></span>
                        <span class="nxl-mtext">Cerrar sesión</span><span class="nxl-arrow"></span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                @endif
                @else
                <li class="nxl-item nxl-caption">
                    <label>Suscripciones</label>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('membresias') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <span class="material-symbols-outlined">
                                diamond
                            </span>
                        </span>

                        <span class="nxl-mtext">Mi suscripción</span><span class="nxl-arrow"></span>
                    </a>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('logout') }}" class="nxl-link logout-tab"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="
     ">
                        <span class="nxl-micon"><span class="material-symbols-outlined">
                                logout
                            </span></span>
                        <span class="nxl-mtext">Cerrar sesión</span><span class="nxl-arrow"></span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<!--! ================================================================ !-->
<header class="nxl-header open">
    <div class="header-wrapper">
        <div class="header-left d-flex align-items-center gap-4">
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button" style="display: none">
                    <span class="material-symbols-outlined menuitem">
                        menu
                    </span>
                </a>
                <a href="javascript:void(0);" id="menu-expend-button">
                    <span class="material-symbols-outlined menuitem2">
                        menu_open
                    </span>
                </a>
            </div>
        </div>
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">
                {{-- Membership --}}
                <div class="membership-status me-3">
                    <span class="material-symbols-outlined" style="margin: 0px 5px 0px 0px;">
                        diamond
                    </span>
                    <div class="membership-days" style="font-size: 12px;">
                        Plan {{ $user->membership?->membershipPlan?->nombre ?? 'Inactivo' }} /
                        @if ($user->membership)
                        {{ (int)
                        \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($user->membership->fecha_expiracion),
                        false) }}
                        @else
                        0
                        @endif
                        días restantes
                    </div>
                </div>

                {{-- User Profile Dropdown - Versión fija --}}
                <div class="border-start ps-3 ms-3">
                    @php
                    // Dividimos el nombre del usuario en un array de palabras
                    $userName = explode(' ', Auth::user()->name);
                    // Obtenemos los dos primeros nombres
                    $firstTwoNames = implode(' ', array_slice($userName, 0, 2));
                    @endphp

                    <div class="d-flex align-items-center">
                        <h6 class="text-dark mb-0 me-2">{{ $firstTwoNames }}</h6>

                        <div class="dropdown">
                            <button class="btn btn-link p-0 border-0 shadow-none" type="button" id="userMenuDropdown">
                                @if (Auth::user()->profile_photo_path)
                                <div style="
                                        width: 50px;
                                        height: 50px;
                                        background-image: url('{{ asset('storage/' . Auth::user()->profile_photo_path) }}');
                                        background-size: cover;
                                        background-position: center;
                                        border-radius: 50%;
                                    "></div>
                                @else
                                <div style="
                                        width: 50px;
                                        height: 50px;
                                        background-color: #e2e6ea;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        border-radius: 50%;
                                        color: #6c757d;
                                        font-size: 24px;
                                    ">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                @endif
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown"
                                id="userDropdownMenu" style="display: none">
                                <li>
                                    <div class="dropdown-header">
                                        <div class="d-flex align-items-center">
                                            @if (Auth::user()->profile_photo_path)
                                            <div style="
                                                    width: 50px;
                                                    height: 50px;
                                                    background-image: url('{{ asset('storage/' . Auth::user()->profile_photo_path) }}');
                                                    background-size: cover;
                                                    background-position: center;
                                                    border-radius: 50%;
                                                    margin-right: 15px;
                                                "></div>
                                            @else
                                            <div style="
                                                    width: 50px;
                                                    height: 50px;
                                                    background-color: #e2e6ea;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    border-radius: 50%;
                                                    color: #6c757d;
                                                    font-size: 24px;
                                                    margin-right: 15px;
                                                ">
                                                <span class="material-symbols-outlined">person</span>
                                            </div>
                                            @endif

                                            <div>
                                                <h6 class="text-dark mb-0">{{ $firstTwoNames }}</h6>
                                                <div class="column">
                                                    <span class="fs-12 fw-medium text-muted">{{ Auth::user()->email
                                                        }}</span>
                                                    @if (Auth::user()->role->id == 1)
                                                    <span class="fs-12 fw-medium text-muted">Administrador</span>
                                                    @elseif (Auth::user()->role->id == 2)
                                                    <span class="fs-12 fw-medium text-muted">Encuestador</span>
                                                    @elseif (Auth::user()->role->id == 3)
                                                    <span class="fs-12 fw-medium text-muted">Propietario</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('ajustes') }}">
                                        <i class="feather-user"></i>
                                        <span>Mi perfil</span>
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a href="{{ route('logout') }}" class="dropdown-item"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="feather-log-out"></i>
                                        <span>Cerrar sesión</span>
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    // Control manual del dropdown
document.addEventListener('DOMContentLoaded', function() {
    var userDropdownBtn = document.getElementById('userMenuDropdown');
    var userDropdownMenu = document.getElementById('userDropdownMenu');

    if (userDropdownBtn && userDropdownMenu) {
        // Asegurar que el dropdown esté cerrado inicialmente
        userDropdownMenu.classList.remove('show');
        userDropdownMenu.style.display = 'none';

        // Implementación manual del toggle de dropdown
        userDropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Toggle manual de la visualización
            if (userDropdownMenu.style.display === 'none') {
                userDropdownMenu.style.display = 'block';
                userDropdownMenu.classList.add('show');
            } else {
                userDropdownMenu.style.display = 'none';
                userDropdownMenu.classList.remove('show');
            }
        });

        // Cerrar dropdown cuando se hace clic fuera
        document.addEventListener('click', function(e) {
            if (!userDropdownBtn.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                userDropdownMenu.style.display = 'none';
                userDropdownMenu.classList.remove('show');
            }
        });
    }

    // Función para limpiar por completo el DOM de backdrops y restaurar el body
    function cleanupModalBackdrops() {
        // Remover TODAS las modal-backdrop
        const allBackdrops = document.querySelectorAll('.modal-backdrop');
        allBackdrops.forEach(function(backdrop) {
            backdrop.parentNode.removeChild(backdrop);
        });

        // Restaurar el body a su estado normal
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    // Limpiar backdrops al cargar la página
    cleanupModalBackdrops();

    // Fix para los modales Bootstrap al cerrarlos
    document.addEventListener('hidden.bs.modal', function(event) {
        setTimeout(function() {
            // Verificar si todavía hay modales abiertos
            const openModals = document.querySelectorAll('.modal.show');
            if (openModals.length === 0) {
                cleanupModalBackdrops();
            }
        }, 200); // Un pequeño retraso para asegurar que Bootstrap haya terminado
    });

    // Asegurarse de que el modal funcione correctamente al abrirlo
    const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(event) {
            // Prevenir la apertura automática del modal
            event.preventDefault();

            // Limpiar primero cualquier backdrop existente
            cleanupModalBackdrops();

            // Obtener el ID del modal a abrir
            const targetModalId = event.currentTarget.getAttribute('data-bs-target');

            if (targetModalId) {
                // Esperar un momento y luego abrir el modal programáticamente
                setTimeout(function() {
                    const targetModal = document.querySelector(targetModalId);
                    if (targetModal) {
                        // Asegurarse de que el modal no tenga clases show o fade antes de abrirlo
                        targetModal.classList.remove('show');

                        // Crear y mostrar el modal
                        try {
                            const bsModal = new bootstrap.Modal(targetModal);
                            bsModal.show();
                        } catch (e) {
                            console.error('Error al mostrar el modal:', e);
                            // Plan B: mostrar el modal manualmente si bootstrap.Modal falla
                            targetModal.classList.add('show');
                            targetModal.style.display = 'block';
                            document.body.classList.add('modal-open');

                            // Crear backdrop manualmente
                            const backdrop = document.createElement('div');
                            backdrop.className = 'modal-backdrop fade show';
                            document.body.appendChild(backdrop);
                        }
                    } else {
                        console.error('No se encontró el modal:', targetModalId);
                    }
                }, 100);
            }
        });
    });

    // Añadir un observador de mutaciones para detectar y arreglar problemas de modal-backdrop
    const bodyObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                // Contar backdrops
                const backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length > 1) {
                    // Conservar solo la primera backdrop
                    for (let i = 1; i < backdrops.length; i++) {
                        backdrops[i].parentNode.removeChild(backdrops[i]);
                    }
                }
            }
        });
    });

    // Configurar el observador para detectar cambios en los hijos del body
    bodyObserver.observe(document.body, { childList: true });

    // Función para guardar el estado de la sidebar
    function saveSidebarState(isOpen) {
        localStorage.setItem('sidebarState', isOpen ? 'open' : 'closed');
    }

    // Función para cargar el estado de la sidebar
    function loadSidebarState() {
        const savedState = localStorage.getItem('sidebarState');
        const nav = document.querySelector('.nxl-navigation');
        const container = document.querySelector('.nxl-container');
        const header = document.querySelector('.nxl-header');

        if (savedState === 'closed') {
            nav.classList.remove('open');
            nav.classList.add('closed');
            container.classList.remove('open');
            container.classList.add('closed');
            header.classList.remove('open');
            header.classList.add('closed');
        } else {
            nav.classList.remove('closed');
            nav.classList.add('open');
            container.classList.remove('closed');
            container.classList.add('open');
            header.classList.remove('closed');
            header.classList.add('open');
        }

        // Marcar como inicializado para mostrar la navegación
        nav.classList.add('initialized');
    }

    // Cargar el estado guardado al iniciar
    loadSidebarState();

    // Manejar el botón de toggle
    const menuMiniButton = document.getElementById('menu-mini-button');
    const menuExpendButton = document.getElementById('menu-expend-button');
    const nav = document.querySelector('.nxl-navigation');
    const container = document.querySelector('.nxl-container');
    const header = document.querySelector('.nxl-header');

    if (menuMiniButton && menuExpendButton) {
        menuMiniButton.addEventListener('click', function() {
            nav.classList.remove('closed');
            nav.classList.add('open');
            container.classList.remove('closed');
            container.classList.add('open');
            header.classList.remove('closed');
            header.classList.add('open');
            menuMiniButton.style.display = 'none';
            menuExpendButton.style.display = 'block';
            saveSidebarState(true);
        });

        menuExpendButton.addEventListener('click', function() {
            nav.classList.remove('open');
            nav.classList.add('closed');
            container.classList.remove('open');
            container.classList.add('closed');
            header.classList.remove('open');
            header.classList.add('closed');
            menuExpendButton.style.display = 'none';
            menuMiniButton.style.display = 'block';
            saveSidebarState(false);
        });
    }
});
</script>

<style>
    /* Estilo para el menú desplegable */
    #userDropdownMenu {
        position: absolute;
        right: 0;
        top: 100%;
        z-index: 1000;
        min-width: 10rem;
        padding: 0.5rem 0;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
    }

    /* Estilo inicial para evitar flash de contenido */
    .nxl-navigation {
        transition: none !important;
        visibility: hidden;
    }

    .nxl-navigation.initialized {
        transition: all 0.3s ease !important;
        visibility: visible;
    }

    /* Cuando el menú está abierto */
    #userDropdownMenu.show {
        display: block;
    }

    /* Asegurar que los modales tengan un z-index mayor que cualquier otra cosa */
    .modal {
        z-index: 1060 !important;
    }

    .modal-backdrop {
        z-index: 1050 !important;
        opacity: 0.5 !important;
    }

    /* Prevenir múltiples backdrops */
    .modal-backdrop~.modal-backdrop {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
    }
</style>

<main class="nxl-container open">
    <div class="nxl-content">
        @yield('page-header')
        <div class="main-content">