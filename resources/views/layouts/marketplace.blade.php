<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Moollish — El marketplace ganadero de Colombia')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Moollish — Marketplace Ganadero')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- FontAwesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Tailwind config personalizado --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#e49b39',
                            light:   '#f0b96a',
                            dark:    '#c4801e',
                        },
                        dark:  '#1a1a1a',
                        cream: '#fafaf8',
                    },
                    fontFamily: {
                        sans:    ['Inter', 'sans-serif'],
                        display: ['"Playfair Display"', 'serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafaf8; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
        .nav-link {
            position: relative;
            transition: color 0.2s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #e49b39;
            transition: width 0.25s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after { width: 100%; }
        .nav-link:hover { color: #e49b39; }
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="text-dark antialiased" x-data>

{{-- ── Variables globales para JS del carrito ─────────────────── --}}
<script>
    const MK_AUTH  = {{ auth()->check() ? 'true' : 'false' }};
    const MK_CSRF  = '{{ csrf_token() }}';
    const MK_ROUTE = {
        carrito:  '{{ url("/marketplace/carrito") }}',
        login:    '{{ route("login") }}',
        agregar:  '{{ url("/marketplace/api/carrito/agregar") }}',
        quitarBase: '{{ url("/marketplace/api/carrito") }}',
        vaciar:   '{{ url("/marketplace/api/carrito/vaciar") }}',
        fusionar: '{{ url("/marketplace/api/carrito/fusionar") }}',
        count:    '{{ url("/marketplace/api/carrito/count") }}',
    };
</script>

    {{-- =========================================================
         NAVBAR
    ========================================================= --}}
    <nav
        class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm"
        x-data="{
            mobileOpen: false,
            searchOpen: false,
            get cartCount() { return $store.cart.count; }
        }"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- ---- Logo ---- --}}
                <a href="{{ url('/marketplace') }}" class="flex items-center gap-2 flex-shrink-0">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Moollish" class="h-9 w-auto">
                    @else
                        <span class="font-display font-bold text-2xl tracking-tight">
                            <span class="text-dark">Moo</span><span style="color:#e49b39">llish</span>
                        </span>
                    @endif
                </a>

                {{-- ---- Menú central (desktop) ---- --}}
                <ul class="hidden md:flex items-center gap-8">
                    <li>
                        <a href="{{ route('marketplace.home') }}"
                           class="nav-link text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors
                                  {{ request()->routeIs('marketplace.home') && !request('tipo') ? 'text-amber-600' : '' }}">
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('marketplace.catalogo', ['tipo' => 'animal']) }}"
                           class="nav-link text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors
                                  {{ request('tipo') === 'animal' ? 'text-amber-600 font-semibold' : '' }}">
                            Ganado
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('marketplace.catalogo', ['tipo' => 'lote']) }}"
                           class="nav-link text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors
                                  {{ request('tipo') === 'lote' ? 'text-amber-600 font-semibold' : '' }}">
                            Lotes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('marketplace.catalogo', ['tipo' => 'insumo']) }}"
                           class="nav-link text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors
                                  {{ request('tipo') === 'insumo' ? 'text-amber-600 font-semibold' : '' }}">
                            Insumos
                        </a>
                    </li>
                </ul>

                {{-- ---- Acciones derecha (desktop) ---- --}}
                <div class="hidden md:flex items-center gap-3">

                    {{-- Buscador --}}
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            class="p-2 text-gray-500 hover:text-brand transition-colors"
                            aria-label="Buscar"
                        >
                            <i class="fa-solid fa-magnifying-glass text-base"></i>
                        </button>
                        <div
                            x-show="open"
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            @click.outside="open = false"
                            class="absolute right-0 top-full mt-2 w-72"
                        >
                            <form action="{{ url('/marketplace/buscar') }}" method="GET">
                                <div class="flex items-center bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                                    <input
                                        type="text"
                                        name="q"
                                        placeholder="Buscar ganado, lotes..."
                                        class="flex-1 px-4 py-2.5 text-sm outline-none placeholder-gray-400"
                                        autofocus
                                    >
                                    <button type="submit" class="px-4 py-2.5 text-brand hover:text-brand-dark transition-colors">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Carrito --}}
                    <a href="{{ url('/marketplace/carrito') }}"
                       class="relative p-2 text-gray-500 hover:text-brand transition-colors"
                       aria-label="Carrito">
                        <i class="fa-solid fa-cart-shopping text-base"></i>
                        <span
                            x-show="cartCount > 0"
                            x-text="cartCount"
                            class="absolute -top-1 -right-1 min-w-[18px] h-[18px] flex items-center justify-center
                                   text-[10px] font-bold text-white rounded-full px-1"
                            style="background-color:#e49b39"
                        ></span>
                    </a>

                    {{-- Botón ingresar / menú usuario --}}
                    @guest
                    <a href="{{ route('login') }}?from=marketplace"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white
                              transition-all hover:opacity-90 active:scale-95"
                       style="background-color:#e49b39">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        Ingresar
                    </a>
                    @else
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center
                                        text-white font-bold text-sm"
                                 style="background-color: #e49b39;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700 hidden md:block">
                                {{ explode(' ', auth()->user()->name)[0] }}
                            </span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg
                                    border border-gray-100 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                <p class="text-xs font-semibold text-gray-900 truncate">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-gray-400 truncate">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>

                            <a href="{{ route('marketplace.panel') }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700
                                      hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-store w-4 text-gray-400"></i>
                                Panel de vendedor
                            </a>

                            <a href="{{ route('marketplace.mis-pedidos') }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700
                                      hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-box w-4 text-gray-400"></i>
                                Mis pedidos
                            </a>

                            <a href="{{ route('marketplace.carrito') }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700
                                      hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-cart-shopping w-4 text-gray-400"></i>
                                Mi carrito
                            </a>

                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('nav-logout').submit();"
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-red-500
                                          hover:bg-red-50 transition-colors">
                                    <i class="fa-solid fa-right-from-bracket w-4"></i>
                                    Cerrar sesión
                                </a>
                                <form id="nav-logout" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    @endguest
                </div>

                {{-- ---- Hamburger (mobile) ---- --}}
                <div class="flex items-center gap-3 md:hidden">
                    {{-- Carrito mobile --}}
                    <a href="{{ url('/marketplace/carrito') }}"
                       class="relative p-2 text-gray-500 hover:text-brand transition-colors">
                        <i class="fa-solid fa-cart-shopping text-base"></i>
                        <span
                            x-show="cartCount > 0"
                            x-text="cartCount"
                            class="absolute -top-1 -right-1 min-w-[18px] h-[18px] flex items-center justify-center
                                   text-[10px] font-bold text-white rounded-full px-1"
                            style="background-color:#e49b39"
                        ></span>
                    </a>

                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                        aria-label="Menú"
                    >
                        <i x-show="!mobileOpen" class="fa-solid fa-bars text-lg"></i>
                        <i x-show="mobileOpen" x-cloak class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

            </div><!-- /flex h-16 -->
        </div><!-- /container -->

        {{-- ---- Menú mobile desplegable ---- --}}
        <div
            x-show="mobileOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden bg-white border-t border-gray-100 shadow-lg"
        >
            {{-- Buscador mobile --}}
            <div class="px-4 pt-4 pb-2">
                <form action="{{ url('/marketplace/buscar') }}" method="GET">
                    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                        <input
                            type="text"
                            name="q"
                            placeholder="Buscar ganado, lotes, insumos..."
                            class="flex-1 px-4 py-2.5 text-sm bg-transparent outline-none placeholder-gray-400"
                        >
                        <button type="submit" class="px-4 py-2.5 text-brand">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Links --}}
            <ul class="px-4 py-2 space-y-1">
                <li>
                    <a href="{{ url('/marketplace') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700
                              hover:bg-orange-50 hover:text-brand transition-colors">
                        <i class="fa-solid fa-house w-4 text-gray-400"></i> Inicio
                    </a>
                </li>
                <li>
                    <a href="{{ url('/marketplace/ganado') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700
                              hover:bg-orange-50 hover:text-brand transition-colors">
                        <i class="fa-solid fa-cow w-4 text-gray-400"></i> Ganado
                    </a>
                </li>
                <li>
                    <a href="{{ url('/marketplace/lotes') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700
                              hover:bg-orange-50 hover:text-brand transition-colors">
                        <i class="fa-solid fa-layer-group w-4 text-gray-400"></i> Lotes
                    </a>
                </li>
                <li>
                    <a href="{{ url('/marketplace/insumos') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700
                              hover:bg-orange-50 hover:text-brand transition-colors">
                        <i class="fa-solid fa-seedling w-4 text-gray-400"></i> Insumos
                    </a>
                </li>
            </ul>

            {{-- Botón ingresar / usuario mobile --}}
            <div class="px-4 pb-4 pt-2 border-t border-gray-100 mt-1">
                @guest
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold
                          text-white transition-all hover:opacity-90"
                   style="background-color:#e49b39">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    Ingresar
                </a>
                @else
                <a href="{{ route('marketplace.panel') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          text-gray-700 hover:bg-orange-50 hover:text-brand transition-colors">
                    <i class="fa-solid fa-store w-4 text-gray-400"></i>
                    Panel de vendedor
                </a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('nav-logout-mobile').submit();"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                          text-red-500 hover:bg-red-50 transition-colors">
                    <i class="fa-solid fa-right-from-bracket w-4"></i>
                    Cerrar sesión
                </a>
                <form id="nav-logout-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                @endguest
            </div>
        </div>

    </nav><!-- /nav -->

    {{-- Espaciado para compensar navbar fijo --}}
    <div class="h-16"></div>

    {{-- =========================================================
         CONTENIDO PRINCIPAL
    ========================================================= --}}
    <main>

        {{-- Mensajes flash --}}
        @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3
                        flex items-center gap-3">
                <span class="text-green-500 text-xl">✅</span>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3
                        flex items-center gap-3">
                <span class="text-red-500 text-xl">❌</span>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3">
                <p class="font-semibold mb-2">❌ Corrige los siguientes errores:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- =========================================================
         FOOTER
    ========================================================= --}}
    <footer style="background-color:#1a1a1a" class="text-white">

        {{-- Sección principal --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- Col 1: Marca --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="{{ url('/marketplace') }}" class="inline-flex items-center gap-2 mb-3">
                        @if(file_exists(public_path('images/logo.png')))
                            <img src="{{ asset('images/logo.png') }}" alt="Moollish" class="h-8 w-auto brightness-0 invert">
                        @else
                            <span class="font-display font-bold text-2xl tracking-tight">
                                <span class="text-white">Moo</span><span style="color:#e49b39">llish</span>
                            </span>
                        @endif
                    </a>
                    <p class="text-sm text-gray-400 leading-relaxed max-w-xs">
                        El marketplace ganadero de Colombia. Conectamos compradores y vendedores de ganado, lotes e insumos del campo colombiano.
                    </p>
                    {{-- Redes sociales --}}
                    <div class="flex gap-3 mt-5">
                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10
                                           hover:bg-brand transition-colors" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10
                                           hover:bg-brand transition-colors" aria-label="Instagram">
                            <i class="fa-brands fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10
                                           hover:bg-brand transition-colors" aria-label="WhatsApp">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                        </a>
                    </div>
                </div>

                {{-- Col 2: Marketplace --}}
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Marketplace</h4>
                    <ul class="space-y-2.5">
                        <li>
                            <a href="{{ url('/marketplace/ganado') }}"
                               class="text-sm text-gray-300 hover:text-brand transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-cow text-xs text-gray-500"></i> Ganado
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/marketplace/lotes') }}"
                               class="text-sm text-gray-300 hover:text-brand transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-xs text-gray-500"></i> Lotes
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/marketplace/insumos') }}"
                               class="text-sm text-gray-300 hover:text-brand transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-seedling text-xs text-gray-500"></i> Insumos
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/marketplace/como-funciona') }}"
                               class="text-sm text-gray-300 hover:text-brand transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-xs text-gray-500"></i> Cómo funciona
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Col 3: Soporte --}}
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Soporte</h4>
                    <ul class="space-y-2.5">
                        <li>
                            <a href="{{ url('/ayuda') }}"
                               class="text-sm text-gray-300 hover:text-brand transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-headset text-xs text-gray-500"></i> Centro de ayuda
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/contacto') }}"
                               class="text-sm text-gray-300 hover:text-brand transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-envelope text-xs text-gray-500"></i> Contacto
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/terminos') }}"
                               class="text-sm text-gray-300 hover:text-brand transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-file-lines text-xs text-gray-500"></i> Términos de uso
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/privacidad') }}"
                               class="text-sm text-gray-300 hover:text-brand transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-xs text-gray-500"></i> Privacidad
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Col 4: Contacto --}}
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Contáctanos</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3 text-sm text-gray-300">
                            <i class="fa-solid fa-location-dot text-xs mt-0.5" style="color:#e49b39"></i>
                            <span>Colombia</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-300">
                            <i class="fa-brands fa-whatsapp text-xs mt-0.5" style="color:#e49b39"></i>
                            <a href="https://wa.me/57300000000" class="hover:text-brand transition-colors">
                                +57 300 000 0000
                            </a>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-300">
                            <i class="fa-solid fa-envelope text-xs mt-0.5" style="color:#e49b39"></i>
                            <a href="mailto:hola@moollish.co" class="hover:text-brand transition-colors">
                                hola@moollish.co
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        {{-- Barra legal --}}
        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs text-gray-500 text-center sm:text-left">
                    &copy; {{ date('Y') }} Moollish SAS. Todos los derechos reservados. NIT: 000.000.000-0
                </p>
                <p class="text-xs text-gray-600 text-center sm:text-right">
                    Hecho con <span style="color:#e49b39">&#9829;</span> en Colombia &bull; Versión 1.0
                </p>
            </div>
        </div>

    </footer>

    @stack('scripts')

{{-- ── Carrito: Alpine Store + helpers globales ────────────────── --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('cart', { count: 0 });
});

document.addEventListener('DOMContentLoaded', () => {
    // Inicializar contador
    if (MK_AUTH) {
        // Fusionar carrito de invitado si existe
        const guestCart = _mkGetLocalCart();
        if (guestCart.length > 0) {
            fetch(MK_ROUTE.fusionar, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': MK_CSRF, 'Content-Type': 'application/json' },
                body: JSON.stringify({ items: guestCart })
            }).then(r => r.json()).then(data => {
                localStorage.removeItem('mk_carrito');
                Alpine.store('cart').count = data.count;
            }).catch(() => {});
        } else {
            fetch(MK_ROUTE.count)
                .then(r => r.json())
                .then(data => { Alpine.store('cart').count = data.count; })
                .catch(() => {});
        }
    } else {
        Alpine.store('cart').count = _mkGetLocalCart().length;
    }
});

// ── localStorage helpers ─────────────────────────────────────────
function _mkGetLocalCart() {
    try { return JSON.parse(localStorage.getItem('mk_carrito') || '[]'); }
    catch(e) { return []; }
}
function _mkSetLocalCart(items) {
    localStorage.setItem('mk_carrito', JSON.stringify(items));
    Alpine.store('cart').count = items.length;
}

// ── Toast ────────────────────────────────────────────────────────
function _mkToast(msg, tipo = 'ok') {
    const t = document.createElement('div');
    t.className = [
        'fixed bottom-6 right-6 z-[9999] px-5 py-3 rounded-xl shadow-lg text-white text-sm font-semibold',
        'flex items-center gap-2 transition-all duration-300 opacity-0 translate-y-2',
        tipo === 'ok'  ? 'bg-green-600' : 'bg-red-500'
    ].join(' ');
    t.innerHTML = (tipo === 'ok' ? '✅ ' : '❌ ') + msg;
    document.body.appendChild(t);
    requestAnimationFrame(() => {
        t.classList.remove('opacity-0', 'translate-y-2');
        t.classList.add('opacity-100', 'translate-y-0');
    });
    setTimeout(() => {
        t.classList.add('opacity-0');
        setTimeout(() => t.remove(), 300);
    }, 3000);
}

// ── Agregar al carrito (llamado desde páginas de detalle) ────────
// extra: string adicional de info (raza, cabezas, marca…)
window.mkAgregarAlCarrito = async function(tipo, referenciaId, nombre, precio, fotoUrl, extra) {
    if (MK_AUTH) {
        try {
            const res = await fetch(MK_ROUTE.agregar, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': MK_CSRF, 'Content-Type': 'application/json' },
                body: JSON.stringify({ tipo, referencia_id: referenciaId, cantidad: 1 })
            });
            const data = await res.json();
            if (!res.ok) {
                _mkToast(data.error || 'No se pudo agregar.', 'err');
                return false;
            }
            Alpine.store('cart').count = data.count;
            _mkToast('¡Agregado al carrito!');
            return true;
        } catch(e) {
            _mkToast('Error de conexión.', 'err');
            return false;
        }
    } else {
        const cart = _mkGetLocalCart();
        const idx  = cart.findIndex(i => i.tipo === tipo && i.referencia_id === referenciaId);
        if (idx !== -1 && tipo === 'insumo') {
            cart[idx].cantidad++;
            cart[idx].subtotal = cart[idx].precio_unitario * cart[idx].cantidad;
        } else if (idx !== -1) {
            _mkToast('Este item ya está en tu carrito.');
            return false;
        } else {
            cart.push({
                id: 'g_' + Date.now(),
                tipo, referencia_id: referenciaId,
                nombre, foto_url: fotoUrl || null,
                precio_unitario: precio, subtotal: precio,
                cantidad: 1, vendedor: null, detalle: extra || null
            });
        }
        _mkSetLocalCart(cart);
        _mkToast('¡Agregado al carrito!');
        return true;
    }
};
</script>
</body>
</html>
