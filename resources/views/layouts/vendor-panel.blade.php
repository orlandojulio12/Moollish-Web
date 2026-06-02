<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Vendedor') — Moollish</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #fafaf8; }
        [x-cloak] { display: none !important; }
        .nav-activo {
            background-color: #fff8ed;
            color: #b45309;
            border-left: 4px solid #e49b39;
            margin-left: -12px;
            padding-left: 28px;
            border-radius: 0 8px 8px 0;
        }
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 8px;
            font-size: 14px; font-weight: 500;
            color: #4b5563; transition: all .15s;
            text-decoration: none;
        }
        .nav-link:hover { background: #f9fafb; color: #111827; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-64 flex-shrink-0 bg-white border-r border-gray-200 flex flex-col h-screen sticky top-0">

        {{-- Logo Moollish --}}
        <div class="px-6 py-4 border-b border-gray-100">
            <a href="{{ route('marketplace.home') }}" class="text-xl font-bold text-gray-900">
                Moo<span style="color:#e49b39">llish</span>
            </a>
        </div>

        {{-- Info del usuario logueado --}}
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                {{-- Avatar con iniciales --}}
                <div class="w-12 h-12 rounded-full flex items-center justify-center
                            text-white font-bold text-lg flex-shrink-0"
                     style="background-color: #e49b39;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-gray-900 text-sm truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs font-medium mt-0.5" style="color:#e49b39;">
                        Panel de vendedor
                    </p>
                </div>
            </div>

            {{-- Perfil de finca (si existe) --}}
            @php
                $perfilVendedor = auth()->user()->perfilMarketplace;
            @endphp
            @if($perfilVendedor)
            <div class="mt-3 bg-amber-50 rounded-lg px-3 py-2">
                <p class="text-xs font-semibold text-amber-800 truncate">
                    🏡 {{ $perfilVendedor->nombre_finca }}
                </p>
                <p class="text-xs text-amber-600">
                    📍 {{ $perfilVendedor->municipio }}, {{ $perfilVendedor->departamento }}
                </p>
            </div>
            @else
            <a href="{{ url('/marketplace/panel/perfil') }}"
               class="mt-3 flex items-center gap-2 text-xs text-amber-600 font-medium
                      bg-amber-50 rounded-lg px-3 py-2 hover:bg-amber-100 transition-colors">
                ⚠️ Completar perfil de vendedor
            </a>
            @endif
        </div>

        {{-- Navegación principal --}}
        <nav class="flex-1 py-4 px-3 space-y-0.5 overflow-y-auto">

            <a href="{{ route('marketplace.panel') }}"
               class="nav-link {{ request()->routeIs('marketplace.panel') ? 'nav-activo' : '' }}">
                <i class="fa-solid fa-chart-line w-4 text-center text-gray-400"></i>
                Mi Dashboard
            </a>

            <a href="{{ route('marketplace.panel.publicaciones') }}"
               class="nav-link {{ request()->routeIs('marketplace.panel.publicaciones') ? 'nav-activo' : '' }}">
                <i class="fa-solid fa-box-open w-4 text-center text-gray-400"></i>
                Mis publicaciones
            </a>

            <a href="{{ route('marketplace.panel.publicar') }}"
               class="nav-link {{ request()->routeIs('marketplace.panel.publicar') ? 'nav-activo' : '' }}">
                <i class="fa-solid fa-plus w-4 text-center text-gray-400"></i>
                Nueva publicación
            </a>

            <a href="{{ route('marketplace.panel.ventas') }}"
               class="nav-link {{ request()->routeIs('marketplace.panel.ventas') ? 'nav-activo' : '' }}">
                <i class="fa-solid fa-cart-shopping w-4 text-center text-gray-400"></i>
                Mis ventas
            </a>

            <a href="{{ route('marketplace.panel.perfil') }}"
               class="nav-link {{ request()->routeIs('marketplace.panel.perfil') ? 'nav-activo' : '' }}">
                <i class="fa-solid fa-user w-4 text-center text-gray-400"></i>
                Mi perfil
            </a>

            <a href="{{ route('marketplace.mis-pedidos') }}"
               class="nav-link {{ request()->routeIs('marketplace.mis-pedidos') ? 'nav-activo' : '' }}">
                <i class="fa-solid fa-clock-rotate-left w-4 text-center text-gray-400"></i>
                Mis pedidos
            </a>
            @auth
    @if(auth()->user()->id_rol == 3)
        <a href="{{ route('inicio') }}" class="nav-link text-gray-500">
            <i class="fa-solid fa-arrow-left w-4 text-center text-gray-400"></i>
            Volver a Moollish
        </a>
    @endif
@endauth

            <div class="pt-4 mt-4 border-t border-gray-100">
                <a href="{{ route('marketplace.home') }}" class="nav-link text-gray-500">
                    <i class="fa-solid fa-arrow-left w-4 text-center text-gray-400"></i>
                    Volver al marketplace
                </a>

                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="nav-link text-red-400 hover:text-red-600 hover:bg-red-50">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                    Cerrar sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </nav>

    </aside>

    {{-- ===== CONTENIDO PRINCIPAL ===== --}}
    <main class="flex-1 overflow-y-auto">

        {{-- Topbar --}}
        <div class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h1 class="text-xl font-bold text-gray-900">@yield('page-title', 'Panel')</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                @yield('topbar-actions')
                <a href="{{ route('marketplace.panel.publicar') }}"
                   class="px-4 py-2 rounded-lg text-white text-sm font-semibold
                          flex items-center gap-2 hover:opacity-90 transition-opacity"
                   style="background-color: #e49b39;">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Nueva publicación
                </a>
            </div>
        </div>

        {{-- Mensajes flash --}}
        @if(session('success'))
        <div class="mx-8 mt-4 bg-green-50 border border-green-200 text-green-800
                    rounded-xl px-4 py-3 flex items-center gap-3">
            <span class="text-green-500">✅</span>
            <p class="font-medium text-sm">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mx-8 mt-4 bg-red-50 border border-red-200 text-red-800
                    rounded-xl px-4 py-3 flex items-center gap-3">
            <span class="text-red-500">❌</span>
            <p class="font-medium text-sm">{{ session('error') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="mx-8 mt-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3">
            <p class="font-semibold text-sm mb-1">Corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Contenido de cada vista --}}
        <div class="p-8">
            @yield('content')
        </div>

    </main>

</body>
</html>
