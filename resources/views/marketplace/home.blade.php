@extends('layouts.marketplace')

@section('title', 'Moollish — El marketplace ganadero de Colombia')
@section('meta_description', 'Compra y vende ganado, lotes e insumos ganaderos en Colombia. Más de 2.400 animales disponibles.')

@section('content')

{{-- ============================================================
     1. HERO BANNER
============================================================ --}}
<section
    class="relative min-h-[88vh] flex items-center justify-center overflow-hidden"
    style="background-image: linear-gradient(to bottom, rgba(10,10,10,0.72) 0%, rgba(10,10,10,0.55) 60%, rgba(10,10,10,0.80) 100%),
           url('https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1600');
           background-size: cover; background-position: center;"
>
    {{-- Partícula decorativa --}}
    <div class="absolute inset-0 bg-gradient-to-r from-black/30 via-transparent to-black/30 pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 text-center py-20">

        {{-- Badge superior --}}
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-widest mb-6
                     border border-white/20 text-white/80 bg-white/10 backdrop-blur-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
            Marketplace Ganadero Colombiano
        </span>

        {{-- Título --}}
        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4 drop-shadow-lg">
            Compra y vende ganado<br>
            <span style="color:#e49b39">con confianza</span>
        </h1>

        {{-- Subtítulo --}}
        <p class="text-lg sm:text-xl text-white/75 mb-10 max-w-2xl mx-auto leading-relaxed">
            Animales, lotes e insumos ganaderos en un solo lugar.
            Conectamos el campo colombiano.
        </p>

        {{-- Barra de búsqueda --}}
        <div class="bg-white rounded-2xl shadow-2xl p-2 flex flex-col sm:flex-row gap-2 max-w-3xl mx-auto">
            {{-- Selector categoría --}}
            <div class="relative flex-shrink-0">
                <select class="appearance-none h-full w-full sm:w-44 px-4 py-3 pr-8 rounded-xl bg-gray-50 border border-gray-200
                               text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 cursor-pointer"
                        style="--tw-ring-color:#e49b39">
                    <option value="">Todas las categorías</option>
                    <option value="ganado">🐄 Ganado</option>
                    <option value="lote">🐄🐄 Lotes</option>
                    <option value="insumo">🌾 Insumos</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
            </div>

            {{-- Divisor vertical --}}
            <div class="hidden sm:block w-px bg-gray-200 self-stretch my-1"></div>

            {{-- Input búsqueda --}}
            <input
                type="text"
                placeholder="Buscar raza, peso, departamento, precio..."
                class="flex-1 px-4 py-3 text-sm text-gray-700 placeholder-gray-400 outline-none bg-transparent"
            >

            {{-- Botón buscar --}}
            <button
                class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white
                       transition-all hover:opacity-90 active:scale-95 flex-shrink-0"
                style="background-color:#e49b39"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Buscar</span>
            </button>
        </div>

        {{-- Tags rápidos --}}
        <div class="flex flex-wrap justify-center gap-2 mt-4">
            @foreach(['Brahman', 'Holstein', 'Angus', 'Novillas', 'Toros reproductores', 'Sales minerales'] as $tag)
            <a href="#" class="px-3 py-1 rounded-full text-xs text-white/70 border border-white/20 bg-white/10
                               hover:bg-white/20 hover:text-white transition-all backdrop-blur-sm">
                {{ $tag }}
            </a>
            @endforeach
        </div>

        {{-- Stats --}}
        <div class="flex flex-wrap justify-center gap-6 sm:gap-12 mt-14">
            <div class="flex flex-col items-center gap-1">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-cow text-sm" style="color:#e49b39"></i>
                    <span class="font-display font-bold text-3xl text-white">{{ $stats['total_animales'] }}+</span>
                </div>
                <span class="text-xs text-white/55 uppercase tracking-wider">Animales</span>
            </div>
            <div class="flex flex-col items-center gap-1">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-sm" style="color:#e49b39"></i>
                    <span class="font-display font-bold text-3xl text-white">{{ $stats['total_lotes'] }}+</span>
                </div>
                <span class="text-xs text-white/55 uppercase tracking-wider">Lotes disponibles</span>
            </div>
            <div class="flex flex-col items-center gap-1">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-seedling text-sm" style="color:#e49b39"></i>
                    <span class="font-display font-bold text-3xl text-white">{{ $stats['total_insumos'] }}+</span>
                </div>
                <span class="text-xs text-white/55 uppercase tracking-wider">Insumos</span>
            </div>
        </div>

    </div>

    {{-- Ola inferior --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 80L1440 80L1440 30C1200 75 960 85 720 55C480 25 240 10 0 50L0 80Z" fill="#fafaf8"/>
        </svg>
    </div>
</section>


{{-- ============================================================
     2. CATEGORÍAS
============================================================ --}}
<section class="py-16 lg:py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12">
            <p class="text-sm font-semibold uppercase tracking-widest mb-2" style="color:#e49b39">¿Qué estás buscando?</p>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-dark">Explora el marketplace</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Ganado Individual --}}
            <a href="{{ url('/marketplace/ganado') }}"
               class="group relative overflow-hidden rounded-2xl p-8 flex flex-col gap-4 transition-all duration-300
                      hover:-translate-y-1 hover:shadow-2xl cursor-pointer"
               style="background: linear-gradient(135deg, #e49b39 0%, #c4801e 100%)">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center text-3xl backdrop-blur-sm">
                    🐄
                </div>
                <div>
                    <h3 class="font-display font-bold text-xl text-white mb-1">Ganado Individual</h3>
                    <p class="text-sm text-white/80 leading-relaxed">Toros, vacas, novillas y terneros de razas seleccionadas. Engorde, leche y reproducción.</p>
                </div>
                <div class="flex items-center gap-2 text-sm font-semibold text-white mt-auto">
                    Ver todos
                    <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                </div>
                {{-- Decoración --}}
                <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-8 -right-8 w-32 h-32 rounded-full bg-white/5"></div>
            </a>

            {{-- Lotes --}}
            <a href="{{ url('/marketplace/lotes') }}"
               class="group relative overflow-hidden rounded-2xl p-8 flex flex-col gap-4 transition-all duration-300
                      hover:-translate-y-1 hover:shadow-2xl cursor-pointer"
               style="background: linear-gradient(135deg, #2d5a27 0%, #1e3d1a 100%)">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center text-3xl backdrop-blur-sm">
                    🐄🐄
                </div>
                <div>
                    <h3 class="font-display font-bold text-xl text-white mb-1">Lotes de Animales</h3>
                    <p class="text-sm text-white/80 leading-relaxed">Conjuntos de animales homogéneos. Ideal para invernaderos, haciendas y engorde masivo.</p>
                </div>
                <div class="flex items-center gap-2 text-sm font-semibold text-white mt-auto">
                    Ver todos
                    <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                </div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-8 -right-8 w-32 h-32 rounded-full bg-white/5"></div>
            </a>

            {{-- Insumos --}}
            <a href="{{ url('/marketplace/insumos') }}"
               class="group relative overflow-hidden rounded-2xl p-8 flex flex-col gap-4 transition-all duration-300
                      hover:-translate-y-1 hover:shadow-2xl cursor-pointer"
               style="background: linear-gradient(135deg, #7a5230 0%, #55371f 100%)">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center text-3xl backdrop-blur-sm">
                    🌾
                </div>
                <div>
                    <h3 class="font-display font-bold text-xl text-white mb-1">Insumos Ganaderos</h3>
                    <p class="text-sm text-white/80 leading-relaxed">Sales, concentrados, vacunas, equipo veterinario y todo lo que tu hato necesita.</p>
                </div>
                <div class="flex items-center gap-2 text-sm font-semibold text-white mt-auto">
                    Ver todos
                    <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                </div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-8 -right-8 w-32 h-32 rounded-full bg-white/5"></div>
            </a>

        </div>
    </div>
</section>


{{-- ============================================================
     3. GANADO DESTACADO
============================================================ --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Cabecera sección --}}
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest mb-1" style="color:#e49b39">Animales disponibles</p>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-dark">Ganado Destacado</h2>
            </div>
            <a href="{{ url('/marketplace/ganado') }}"
               class="hidden sm:flex items-center gap-2 text-sm font-semibold transition-colors hover:opacity-80"
               style="color:#e49b39">
                Ver todo <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        {{-- Grid tarjetas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            @forelse($animalesDestacados as $pub)
            <div class="border rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                {{-- Imagen --}}
                @if($pub->fotos->isNotEmpty())
                    <img src="{{ asset($pub->fotos->first()->url_foto) }}"
                         class="w-full object-cover h-48">
                @else
                    <img src="https://placehold.co/400x300/e49b39/white?text={{ urlencode($pub->animal->nombre ?? 'Animal') }}"
                         class="w-full object-cover h-48">
                @endif

                <div class="p-4">
                    {{-- Badge propósito --}}
                    <span class="text-xs font-semibold px-2 py-1 rounded-full text-white"
                          style="background-color: #e49b39;">
                        {{ ucfirst($pub->proposito) }}
                    </span>

                    {{-- Nombre --}}
                    <h3 class="font-bold text-gray-900 mt-2">
                        {{ $pub->animal->nombre ?? 'Animal #'.$pub->animal_id }}
                    </h3>

                    {{-- Datos clave --}}
                    <div class="text-sm text-gray-500 mt-1 space-y-0.5">
                        <p>🐄 {{ $pub->animal->raza ?? 'N/A' }}</p>
                        <p>⚖️ {{ $pub->animal->peso ?? '—' }} kg</p>
                        <p>📍 {{ $pub->animal->predio->municipio ?? '' }},
                              {{ $pub->animal->predio->departamento ?? '' }}</p>
                    </div>

                    {{-- Precio --}}
                    <p class="text-xl font-bold mt-3" style="color: #e49b39;">
                        ${{ number_format($pub->precio_venta, 0, ',', '.') }} COP
                    </p>

                    {{-- Botón --}}
                    <a href="{{ url('/marketplace/ganado/'.$pub->id) }}"
                       class="mt-3 block text-center py-2 rounded-lg text-white font-semibold"
                       style="background-color: #e49b39;">
                        Ver detalle
                    </a>
                </div>
            </div>
            @empty
            <p class="col-span-4 text-center text-gray-400 py-8">
                No hay animales destacados aún.
            </p>
            @endforelse

        </div>

        {{-- Ver todo mobile --}}
        <div class="flex justify-center mt-8 sm:hidden">
            <a href="{{ url('/marketplace/ganado') }}"
               class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold border-2 transition-colors"
               style="border-color:#e49b39; color:#e49b39">
                Ver todo el ganado <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

    </div>
</section>


{{-- ============================================================
     4. LOTES DESTACADOS
============================================================ --}}
<section class="py-16 lg:py-20" style="background-color:#fafaf8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest mb-1" style="color:#2d5a27">Ventas en conjunto</p>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-dark">Lotes Destacados</h2>
            </div>
            <a href="{{ url('/marketplace/lotes') }}"
               class="hidden sm:flex items-center gap-2 text-sm font-semibold text-green-800 hover:opacity-80 transition-opacity">
                Ver todo <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @forelse($lotesDestacados as $lote)
            <div class="border rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                @if($lote->fotos->isNotEmpty())
                    <img src="{{ asset($lote->fotos->first()->url_foto) }}"
                         class="w-full object-cover h-48">
                @else
                    <img src="https://placehold.co/600x300/2d5a27/white?text={{ urlencode($lote->nombre) }}"
                         class="w-full object-cover h-48">
                @endif

                <div class="p-4">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full text-white bg-green-700">
                        🐄🐄 LOTE
                    </span>

                    <h3 class="font-bold text-gray-900 mt-2">{{ $lote->nombre }}</h3>

                    <div class="text-sm text-gray-500 mt-1 space-y-0.5">
                        <p>🐄 {{ $lote->animales->count() }} animales</p>
                        @if($lote->precio_tipo === 'total')
                            <p>💰 ${{ number_format($lote->precio, 0, ',', '.') }} total</p>
                            @if($lote->animales->count() > 0)
                                <p style="color:#e49b39" class="font-semibold">
                                    ${{ number_format($lote->precio / $lote->animales->count(), 0, ',', '.') }}/cabeza
                                </p>
                            @endif
                        @else
                            <p style="color:#e49b39" class="font-semibold">
                                ${{ number_format($lote->precio, 0, ',', '.') }}/cabeza
                            </p>
                        @endif
                        <p>📍 {{ $lote->punto_entrega }}</p>
                    </div>

                    <a href="{{ url('/marketplace/lote/'.$lote->id) }}"
                       class="mt-3 block text-center py-2 rounded-lg text-white font-semibold"
                       style="background-color: #2d5a27;">
                        Ver lote
                    </a>
                </div>
            </div>
            @empty
            <p class="col-span-3 text-center text-gray-400 py-8">
                No hay lotes destacados aún.
            </p>
            @endforelse

        </div>

        <div class="flex justify-center mt-8 sm:hidden">
            <a href="{{ url('/marketplace/lotes') }}"
               class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold border-2 text-green-800 border-green-800">
                Ver todos los lotes <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

    </div>
</section>


{{-- ============================================================
     5. INSUMOS GANADEROS
============================================================ --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest mb-1" style="color:#7a5230">Para tu hato</p>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-dark">Insumos Ganaderos</h2>
            </div>
            <a href="{{ url('/marketplace/insumos') }}"
               class="hidden sm:flex items-center gap-2 text-sm font-semibold hover:opacity-80 transition-opacity"
               style="color:#7a5230">
                Ver todo <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

            @forelse($insumosDestacados as $insumo)
            <div class="border rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                @if($insumo->fotos->isNotEmpty())
                    <img src="{{ asset($insumo->fotos->first()->url_foto) }}"
                         class="w-full object-cover h-36">
                @else
                    <img src="https://placehold.co/400x200/7a5230/white?text={{ urlencode($insumo->nombre) }}"
                         class="w-full object-cover h-36">
                @endif

                <div class="p-3">
                    <p class="text-sm font-semibold text-gray-800 leading-snug">
                        {{ $insumo->nombre }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $insumo->marca }}</p>
                    <p class="text-base font-bold mt-1" style="color: #e49b39;">
                        ${{ number_format($insumo->precio, 0, ',', '.') }} COP
                    </p>
                    <a href="{{ url('/marketplace/insumo/'.$insumo->id) }}"
                       class="mt-2 block text-center text-xs font-semibold py-1.5 rounded-lg border"
                       style="border-color: #e49b39; color: #c4801e;">
                        Ver producto
                    </a>
                </div>
            </div>
            @empty
            <p class="col-span-4 text-center text-gray-400 py-8">
                No hay insumos disponibles aún.
            </p>
            @endforelse

        </div>

        <div class="flex justify-center mt-8 sm:hidden">
            <a href="{{ url('/marketplace/insumos') }}"
               class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold border-2"
               style="border-color:#7a5230; color:#7a5230">
                Ver todos los insumos <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

    </div>
</section>


{{-- ============================================================
     6. BANNER CTA VENDEDOR
============================================================ --}}
<section style="background-color:#e49b39" class="py-16 lg:py-20 relative overflow-hidden">

    {{-- Decoraciones de fondo --}}
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white/10 -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-black/10 translate-y-1/2 -translate-x-1/4"></div>
    <div class="absolute top-1/2 left-1/4 w-32 h-32 rounded-full bg-white/5 -translate-y-1/2"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8">

            {{-- Texto --}}
            <div class="text-center lg:text-left">
                <p class="text-sm font-semibold uppercase tracking-widest text-black/50 mb-3">Para vendedores</p>
                <h2 class="font-display font-bold text-3xl sm:text-4xl lg:text-5xl text-white leading-tight mb-4">
                    ¿Tienes ganado<br class="hidden sm:block"> para vender?
                </h2>
                <p class="text-white/80 text-base sm:text-lg max-w-xl leading-relaxed">
                    Publica tus animales, lotes e insumos en minutos. Llega a miles de compradores en toda Colombia sin intermediarios.
                </p>

                {{-- Bullets --}}
                <ul class="mt-5 space-y-2 text-left inline-block">
                    @foreach(['Publicación gratuita', 'Compradores verificados', 'Pagos seguros en línea', 'Soporte 7 días a la semana'] as $bullet)
                    <li class="flex items-center gap-3 text-sm text-white/90">
                        <i class="fa-solid fa-circle-check text-white/60 flex-shrink-0"></i>
                        {{ $bullet }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 flex-shrink-0">
                <a href="{{ url('/registro/vendedor') }}"
                   class="flex items-center justify-center gap-3 px-8 py-4 rounded-2xl text-base font-bold
                          text-white transition-all hover:opacity-90 active:scale-95 shadow-xl"
                   style="background-color:#1a1a1a">
                    <i class="fa-solid fa-rocket text-sm"></i>
                    Publicar ahora
                </a>
                <a href="{{ url('/marketplace/como-funciona') }}"
                   class="flex items-center justify-center gap-3 px-8 py-4 rounded-2xl text-base font-semibold
                          text-dark bg-white/30 hover:bg-white/40 transition-all backdrop-blur-sm">
                    <i class="fa-solid fa-play-circle text-sm"></i>
                    Cómo funciona
                </a>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Lógica futura: lazy load imágenes, carrito dinámico, etc.
</script>
@endpush
