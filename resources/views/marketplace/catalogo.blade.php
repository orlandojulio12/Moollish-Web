@extends('layouts.marketplace')

@section('title', 'Catálogo — Moollish Marketplace')
@section('meta_description', 'Explora ganado, lotes e insumos ganaderos disponibles en Colombia.')

@section('content')

{{-- ============================================================
     PÁGINA WRAPPER — Alpine.js scope principal
============================================================ --}}
<div
    class="min-h-screen bg-cream"
    x-data="{
        sidebarOpen:    false,
        viewMode:       'grid',
        activeTab:      'todos',
        filtersApplied: false,

        filters: {
            tipos:     [],
            precioMin: '',
            precioMax: '',
            dpto:      '',
            raza:      '',
            proposito: [],
        },

        applyFilters() {
            this.filtersApplied = true;
            this.sidebarOpen = false;
        },
        clearFilters() {
            this.filters = { tipos: [], precioMin: '', precioMax: '', dpto: '', raza: '', proposito: [] };
            this.filtersApplied = false;
        },
        toggleTipo(val) {
            const i = this.filters.tipos.indexOf(val);
            i === -1 ? this.filters.tipos.push(val) : this.filters.tipos.splice(i, 1);
        },
        toggleProp(val) {
            const i = this.filters.proposito.indexOf(val);
            i === -1 ? this.filters.proposito.push(val) : this.filters.proposito.splice(i, 1);
        },
        activeFiltersCount() {
            return this.filters.tipos.length
                 + this.filters.proposito.length
                 + (this.filters.precioMin ? 1 : 0)
                 + (this.filters.precioMax ? 1 : 0)
                 + (this.filters.dpto ? 1 : 0)
                 + (this.filters.raza ? 1 : 0);
        }
    }"
>

    {{-- ── Breadcrumb ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ url('/marketplace') }}" class="hover:text-brand transition-colors">Inicio</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <span class="text-dark font-medium">Catálogo</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <div class="flex gap-6 items-start">


            {{-- ============================================================
                 SIDEBAR — desktop fijo, mobile overlay
            ============================================================ --}}

            {{-- Overlay mobile --}}
            <div
                x-show="sidebarOpen"
                x-cloak
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-black/50 z-30 lg:hidden"
                x-transition:enter="transition duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>

            {{-- Panel sidebar --}}
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                class="fixed lg:static top-0 left-0 h-full lg:h-auto w-72 lg:w-64 xl:w-72
                       z-40 lg:z-auto bg-white lg:bg-white rounded-none lg:rounded-2xl
                       border-r lg:border border-gray-100 lg:shadow-sm
                       overflow-y-auto lg:overflow-visible
                       transition-transform duration-300 ease-out
                       lg:flex-shrink-0 lg:sticky lg:top-20 lg:max-h-[calc(100vh-6rem)]"
            >
                {{-- Cabecera sidebar --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-sm" style="color:#e49b39"></i>
                        <h2 class="font-semibold text-dark text-sm">Filtrar</h2>
                        <span
                            x-show="activeFiltersCount() > 0"
                            x-text="activeFiltersCount()"
                            class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold
                                   text-white rounded-full"
                            style="background-color:#e49b39"
                        ></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            x-show="activeFiltersCount() > 0"
                            @click="clearFilters()"
                            class="text-xs text-gray-400 hover:text-red-500 transition-colors underline"
                        >Limpiar</button>
                        <button @click="sidebarOpen = false" class="lg:hidden p-1 text-gray-400 hover:text-dark">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>
                </div>

                <form method="GET" action="{{ route('marketplace.catalogo') }}">
                <input type="hidden" name="tipo" value="{{ $tipo }}">
                <div class="px-5 py-4 space-y-6 overflow-y-auto lg:max-h-[calc(100vh-10rem)]">

                    {{-- ── Tipo de producto ── --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Tipo de producto</p>
                        <div class="relative">
                            <select name="tipo"
                                    class="w-full appearance-none px-3 py-2.5 pr-8 text-sm border border-gray-200 rounded-lg
                                           bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#e49b39] cursor-pointer">
                                <option value="todos" {{ $tipo==='todos'?'selected':'' }}>Todos</option>
                                <option value="animal" {{ $tipo==='animal'?'selected':'' }}>🐄 Ganado</option>
                                <option value="lote" {{ $tipo==='lote'?'selected':'' }}>🐄🐄 Lotes</option>
                                <option value="insumo" {{ $tipo==='insumo'?'selected':'' }}>🌾 Insumos</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- ── Rango de precio ── --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Rango de precio (COP)</p>
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="block text-[10px] text-gray-400 mb-1">Desde</label>
                                <div class="relative">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                                    <input
                                        type="number"
                                        name="precio_min"
                                        x-model="filters.precioMin"
                                        value="{{ $precio_min }}"
                                        placeholder="0"
                                        class="w-full pl-6 pr-2 py-2 text-sm border border-gray-200 rounded-lg
                                               focus:outline-none focus:ring-1 focus:ring-[#e49b39] focus:border-[#e49b39]"
                                    >
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-[10px] text-gray-400 mb-1">Hasta</label>
                                <div class="relative">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                                    <input
                                        type="number"
                                        name="precio_max"
                                        x-model="filters.precioMax"
                                        value="{{ $precio_max }}"
                                        placeholder="∞"
                                        class="w-full pl-6 pr-2 py-2 text-sm border border-gray-200 rounded-lg
                                               focus:outline-none focus:ring-1 focus:ring-[#e49b39] focus:border-[#e49b39]"
                                    >
                                </div>
                            </div>
                        </div>
                        {{-- Sugerencias rápidas --}}
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach([
                                ['< 1M',   '',        '1000000'],
                                ['1M–5M',  '1000000', '5000000'],
                                ['> 5M',   '5000000', ''],
                            ] as [$label, $min, $max])
                            <button
                                @click="filters.precioMin='{{ $min }}'; filters.precioMax='{{ $max }}'"
                                class="px-2.5 py-1 rounded-lg text-[11px] border border-gray-200 text-gray-500
                                       hover:border-[#e49b39] hover:text-[#e49b39] transition-colors"
                            >{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- ── Departamento ── --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Departamento</p>
                        <div class="relative">
                            <select
                                name="departamento"
                                x-model="filters.dpto"
                                class="w-full appearance-none px-3 py-2.5 pr-8 text-sm border border-gray-200 rounded-lg
                                       bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#e49b39] cursor-pointer"
                            >
                                <option value="">Todos los departamentos</option>
                                @foreach([
                                    'Antioquia','Córdoba','Meta','Casanare',
                                    'Cundinamarca','Boyacá','Cesar','Magdalena',
                                    'Nariño','Valle del Cauca'
                                ] as $dpto)
                                <option value="{{ $dpto }}" {{ $departamento===$dpto?'selected':'' }}>{{ $dpto }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    @if($tipo === 'animal')
                    <hr class="border-gray-100">

                    {{-- ── Raza (solo ganado) ── --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Raza</p>
                        <div class="relative">
                            <select name="raza"
                                class="w-full appearance-none px-3 py-2.5 pr-8 text-sm border border-gray-200 rounded-lg
                                       bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#e49b39] cursor-pointer"
                            >
                                <option value="">Todas las razas</option>
                                @foreach([
                                    'Brahman','Holstein','Angus','Normando',
                                    'Cebú Nelore','Simmental','Gyrholando','Romosinuano','BON'
                                ] as $raza)
                                <option value="{{ $raza }}" {{ request('raza')===$raza?'selected':'' }}>{{ $raza }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    @endif

                    @if($tipo !== 'insumo')
                    <hr class="border-gray-100">

                    {{-- ── Propósito (ganado y lotes) ── --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Propósito</p>
                        <div class="relative">
                            <select name="proposito"
                                    class="w-full appearance-none px-3 py-2.5 pr-8 text-sm border border-gray-200 rounded-lg
                                           bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#e49b39] cursor-pointer">
                                <option value="">Todos los propósitos</option>
                                <option value="engorde"      {{ $proposito==='engorde'?'selected':'' }}>Engorde</option>
                                <option value="leche"        {{ $proposito==='leche'?'selected':'' }}>Leche</option>
                                <option value="reproduccion" {{ $proposito==='reproduccion'?'selected':'' }}>Reproducción</option>
                                <option value="sacrificio"   {{ $proposito==='sacrificio'?'selected':'' }}>Sacrificio</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    @endif

                    {{-- ── Botón aplicar ── --}}
                    <button
                        type="submit"
                        class="w-full py-3 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-95 shadow-sm"
                        style="background-color:#e49b39"
                    >
                        <i class="fa-solid fa-filter mr-2 text-xs"></i>
                        Aplicar filtros
                    </button>

                    <div class="pb-2 text-center">
                        <a href="{{ route('marketplace.catalogo') }}" class="text-xs text-gray-400 hover:text-dark underline transition-colors">
                            Limpiar todos los filtros
                        </a>
                    </div>

                </div>
                </form>
            </aside>


            {{-- ============================================================
                 ÁREA PRINCIPAL
            ============================================================ --}}
            <div class="flex-1 min-w-0">

                {{-- ── Header resultados ── --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div class="flex items-center gap-3">

                        {{-- Botón filtros mobile --}}
                        <button
                            @click="sidebarOpen = true"
                            class="lg:hidden flex items-center gap-2 px-3.5 py-2 rounded-xl border border-gray-200 text-sm
                                   font-medium text-gray-700 bg-white hover:border-[#e49b39] hover:text-[#e49b39] transition-colors"
                        >
                            <i class="fa-solid fa-sliders text-xs"></i>
                            Filtros
                            <span
                                x-show="activeFiltersCount() > 0"
                                x-text="activeFiltersCount()"
                                class="inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold
                                       text-white rounded-full"
                                style="background-color:#e49b39"
                            ></span>
                        </button>

                        <div>
                            <h1 class="font-semibold text-dark text-base sm:text-lg leading-tight">
                                <span style="color:#e49b39">{{ $productos->total() }}</span> resultados
                            </h1>
                            <p x-show="filtersApplied" x-cloak class="text-xs text-gray-400">Filtros aplicados</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Ordenar --}}
                        <div class="relative">
                            <select
                                class="appearance-none pl-3 pr-8 py-2 text-sm border border-gray-200 rounded-xl
                                       bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#e49b39] cursor-pointer"
                                onchange="window.location='{{ route('marketplace.catalogo') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), orden: this.value}).toString()"
                            >
                                <option value="recientes"   {{ $orden==='recientes'  ?'selected':'' }}>Más recientes</option>
                                <option value="precio_asc"  {{ $orden==='precio_asc' ?'selected':'' }}>Menor precio</option>
                                <option value="precio_desc" {{ $orden==='precio_desc'?'selected':'' }}>Mayor precio</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                        </div>

                        {{-- Toggle vista --}}
                        <div class="flex rounded-xl border border-gray-200 overflow-hidden bg-white">
                            <button
                                @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'text-white' : 'text-gray-400 hover:text-dark'"
                                :style="viewMode === 'grid' ? 'background-color:#e49b39' : ''"
                                class="px-3 py-2 transition-all"
                                aria-label="Vista grilla"
                            ><i class="fa-solid fa-grid-2 text-sm"></i></button>
                            <button
                                @click="viewMode = 'list'"
                                :class="viewMode === 'list' ? 'text-white' : 'text-gray-400 hover:text-dark'"
                                :style="viewMode === 'list' ? 'background-color:#e49b39' : ''"
                                class="px-3 py-2 transition-all"
                                aria-label="Vista lista"
                            ><i class="fa-solid fa-list text-sm"></i></button>
                        </div>
                    </div>
                </div>

                {{-- ── Tabs ── --}}
                <div class="flex gap-1 p-1 bg-gray-100 rounded-xl mb-6 overflow-x-auto">
                    @php $qTodos = array_merge(request()->query(), ['tipo' => 'todos', 'page' => 1]); @endphp
                    <a href="{{ route('marketplace.catalogo', $qTodos) }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm transition-all whitespace-nowrap flex-1 justify-center
                              {{ request('tipo', 'todos') === 'todos' ? 'bg-white shadow-sm text-dark font-semibold' : 'text-gray-500 hover:text-dark' }}">
                        Todos
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-gray-100 text-gray-500">
                            {{ $contadores['todos'] }}
                        </span>
                    </a>
                    @php $qAnimal = array_merge(request()->query(), ['tipo' => 'animal', 'page' => 1]); @endphp
                    <a href="{{ route('marketplace.catalogo', $qAnimal) }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm transition-all whitespace-nowrap flex-1 justify-center
                              {{ request('tipo') === 'animal' ? 'bg-white shadow-sm text-dark font-semibold' : 'text-gray-500 hover:text-dark' }}">
                        🐄 Ganado
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-md text-[10px] font-bold text-white"
                              style="background-color: {{ request('tipo') === 'animal' ? '#e49b39' : '#e49b3988' }}">
                            {{ $contadores['animal'] }}
                        </span>
                    </a>
                    @php $qLote = array_merge(request()->query(), ['tipo' => 'lote', 'page' => 1]); @endphp
                    <a href="{{ route('marketplace.catalogo', $qLote) }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm transition-all whitespace-nowrap flex-1 justify-center
                              {{ request('tipo') === 'lote' ? 'bg-white shadow-sm text-dark font-semibold' : 'text-gray-500 hover:text-dark' }}">
                        🐄🐄 Lotes
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-md text-[10px] font-bold text-white"
                              style="background-color: {{ request('tipo') === 'lote' ? '#2d5a27' : '#2d5a2788' }}">
                            {{ $contadores['lote'] }}
                        </span>
                    </a>
                    @php $qInsumo = array_merge(request()->query(), ['tipo' => 'insumo', 'page' => 1]); @endphp
                    <a href="{{ route('marketplace.catalogo', $qInsumo) }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm transition-all whitespace-nowrap flex-1 justify-center
                              {{ request('tipo') === 'insumo' ? 'bg-white shadow-sm text-dark font-semibold' : 'text-gray-500 hover:text-dark' }}">
                        🌾 Insumos
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-md text-[10px] font-bold text-white"
                              style="background-color: {{ request('tipo') === 'insumo' ? '#7a5230' : '#7a523088' }}">
                            {{ $contadores['insumo'] }}
                        </span>
                    </a>
                </div>

                {{-- ── Chips filtros activos ── --}}
                <div x-show="activeFiltersCount() > 0" x-cloak class="flex flex-wrap gap-2 mb-4">
                    <template x-for="tipo in filters.tipos" :key="tipo">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">
                            <i class="fa-solid fa-tag text-[9px]"></i>
                            <span x-text="tipo"></span>
                            <button @click="toggleTipo(tipo)" class="ml-1 hover:text-red-500"><i class="fa-solid fa-xmark text-[9px]"></i></button>
                        </span>
                    </template>
                    <template x-if="filters.dpto">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            <i class="fa-solid fa-map-pin text-[9px]"></i>
                            <span x-text="filters.dpto"></span>
                            <button @click="filters.dpto=''" class="ml-1 hover:text-red-500"><i class="fa-solid fa-xmark text-[9px]"></i></button>
                        </span>
                    </template>
                    <template x-if="filters.raza">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                            <i class="fa-solid fa-cow text-[9px]"></i>
                            <span x-text="filters.raza"></span>
                            <button @click="filters.raza=''" class="ml-1 hover:text-red-500"><i class="fa-solid fa-xmark text-[9px]"></i></button>
                        </span>
                    </template>
                </div>

                {{-- ===========================================================
                     GRID DE PRODUCTOS
                =========================================================== --}}
                <p class="text-sm text-gray-500 mb-4">
                    {{ $productos->total() }} resultados
                </p>

                {{-- ── Vista GRID ── --}}
                <div
                    x-show="viewMode === 'grid'"
                    class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 lg:gap-5"
                >
                    @forelse($productos as $item)
                        @if($item->_tipo === 'animal')
                            {{-- TARJETA ANIMAL --}}
                            <div class="border rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                @if($item->fotos->isNotEmpty())
                                    <img src="{{ asset($item->fotos->first()->url_foto) }}"
                                         class="w-full h-44 object-cover">
                                @else
                                    <img src="https://placehold.co/400x300/e49b39/white?text=Animal"
                                         class="w-full h-44 object-cover">
                                @endif
                                <div class="p-4">
                                    <div class="flex gap-2 mb-2">
                                        <span class="text-xs px-2 py-0.5 rounded-full text-white font-semibold"
                                              style="background:#e49b39">GANADO</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-700">
                                            {{ ucfirst($item->proposito) }}
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-gray-900">
                                        {{ $item->animal->nombre ?? 'Animal #'.$item->animal_id }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $item->animal->raza ?? '' }} ·
                                        {{ $item->animal->peso ?? '—' }}kg
                                    </p>
                                    <p class="text-lg font-bold mt-2" style="color:#e49b39">
                                        ${{ number_format($item->precio_venta, 0, ',', '.') }} COP
                                    </p>
                                    <a href="{{ url('/marketplace/ganado/'.$item->id) }}"
                                       class="mt-3 block text-center py-2 rounded-lg text-white text-sm font-semibold"
                                       style="background:#e49b39">
                                        Ver detalle
                                    </a>
                                </div>
                            </div>

                        @elseif($item->_tipo === 'lote')
                            {{-- TARJETA LOTE --}}
                            <div class="border rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                @if($item->fotos->isNotEmpty())
                                    <img src="{{ asset($item->fotos->first()->url_foto) }}"
                                         class="w-full h-44 object-cover">
                                @else
                                    <img src="https://placehold.co/400x300/2d5a27/white?text=Lote"
                                         class="w-full h-44 object-cover">
                                @endif
                                <div class="p-4">
                                    <span class="text-xs px-2 py-0.5 rounded-full text-white font-semibold bg-green-700">
                                        🐄🐄 LOTE
                                    </span>
                                    <h3 class="font-bold text-gray-900 mt-2">{{ $item->nombre }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $item->animales->count() }} animales ·
                                        {{ ucfirst($item->proposito) }}
                                    </p>
                                    <p class="text-lg font-bold mt-2" style="color:#e49b39">
                                        ${{ number_format($item->precio, 0, ',', '.') }} COP
                                        <span class="text-xs font-normal text-gray-500">
                                            /{{ $item->precio_tipo === 'por_cabeza' ? 'cabeza' : 'total' }}
                                        </span>
                                    </p>
                                    <a href="{{ url('/marketplace/lote/'.$item->id) }}"
                                       class="mt-3 block text-center py-2 rounded-lg text-white text-sm font-semibold bg-green-700">
                                        Ver lote
                                    </a>
                                </div>
                            </div>

                        @elseif($item->_tipo === 'insumo')
                            {{-- TARJETA INSUMO --}}
                            <div class="border rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                @if($item->fotos->isNotEmpty())
                                    <img src="{{ asset($item->fotos->first()->url_foto) }}"
                                         class="w-full h-44 object-cover">
                                @else
                                    <img src="https://placehold.co/400x300/7a5230/white?text=Insumo"
                                         class="w-full h-44 object-cover">
                                @endif
                                <div class="p-4">
                                    <span class="text-xs px-2 py-0.5 rounded-full text-white font-semibold"
                                          style="background:#7a5230">🌾 INSUMO</span>
                                    <h3 class="font-bold text-gray-900 mt-2">{{ $item->nombre }}</h3>
                                    <p class="text-sm text-gray-500">{{ $item->marca }}</p>
                                    <p class="text-lg font-bold mt-2" style="color:#e49b39">
                                        ${{ number_format($item->precio, 0, ',', '.') }} COP
                                    </p>
                                    <a href="{{ url('/marketplace/insumo/'.$item->id) }}"
                                       class="mt-3 block text-center py-2 rounded-lg text-white text-sm font-semibold"
                                       style="background:#7a5230">
                                        Ver producto
                                    </a>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-span-3 text-center py-16 text-gray-400">
                            <p class="text-4xl mb-3">🔍</p>
                            <p class="font-semibold">No se encontraron productos con esos filtros.</p>
                            <a href="{{ route('marketplace.catalogo') }}" class="text-amber-600 text-sm mt-2 inline-block">
                                Limpiar filtros
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- ── Vista LISTA ── --}}
                <div
                    x-show="viewMode === 'list'"
                    x-cloak
                    class="space-y-3"
                >
                    @forelse($productos as $item)
                        @if($item->_tipo === 'animal')
                        <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm
                                    hover:shadow-lg transition-all duration-200 flex">
                            <div class="relative w-32 sm:w-44 flex-shrink-0 overflow-hidden">
                                @if($item->fotos->isNotEmpty())
                                    <img src="{{ asset($item->fotos->first()->url_foto) }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <img src="https://placehold.co/200x150/e49b39/white?text=Animal"
                                         class="w-full h-full object-cover">
                                @endif
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md text-[9px] font-bold tracking-widest text-white"
                                      style="background-color:#e49b39">GANADO</span>
                            </div>
                            <div class="flex-1 p-4 flex flex-col sm:flex-row sm:items-center gap-3">
                                <div class="flex-1">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700">
                                        {{ ucfirst($item->proposito) }}
                                    </span>
                                    <h3 class="font-semibold text-dark text-sm mt-1 mb-1">
                                        {{ $item->animal->nombre ?? 'Animal #'.$item->animal_id }}
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        {{ $item->animal->raza ?? '' }} · {{ $item->animal->peso ?? '—' }}kg
                                    </p>
                                </div>
                                <div class="sm:text-right flex-shrink-0">
                                    <p class="font-display font-bold text-xl" style="color:#e49b39">
                                        ${{ number_format($item->precio_venta, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mb-3">COP</p>
                                    <a href="{{ url('/marketplace/ganado/'.$item->id) }}"
                                       class="inline-block px-4 py-2 rounded-xl text-xs font-bold text-white
                                              transition-all hover:opacity-90 active:scale-95"
                                       style="background-color:#e49b39">
                                        Ver detalle
                                    </a>
                                </div>
                            </div>
                        </div>

                        @elseif($item->_tipo === 'lote')
                        <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm
                                    hover:shadow-lg transition-all duration-200 flex">
                            <div class="relative w-32 sm:w-44 flex-shrink-0 overflow-hidden">
                                @if($item->fotos->isNotEmpty())
                                    <img src="{{ asset($item->fotos->first()->url_foto) }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <img src="https://placehold.co/200x150/2d5a27/white?text=Lote"
                                         class="w-full h-full object-cover">
                                @endif
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md text-[9px] font-bold tracking-widest text-white bg-green-700">
                                    LOTE
                                </span>
                            </div>
                            <div class="flex-1 p-4 flex flex-col sm:flex-row sm:items-center gap-3">
                                <div class="flex-1">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-800">
                                        {{ $item->animales->count() }} animales
                                    </span>
                                    <h3 class="font-semibold text-dark text-sm mt-1 mb-1">{{ $item->nombre }}</h3>
                                    <p class="text-xs text-gray-500">{{ ucfirst($item->proposito) }}</p>
                                </div>
                                <div class="sm:text-right flex-shrink-0">
                                    <p class="font-display font-bold text-xl" style="color:#e49b39">
                                        ${{ number_format($item->precio, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mb-3">
                                        COP /{{ $item->precio_tipo === 'por_cabeza' ? 'cabeza' : 'total' }}
                                    </p>
                                    <a href="{{ url('/marketplace/lote/'.$item->id) }}"
                                       class="inline-block px-4 py-2 rounded-xl text-xs font-bold text-white
                                              transition-all hover:opacity-90 active:scale-95 bg-green-700">
                                        Ver lote
                                    </a>
                                </div>
                            </div>
                        </div>

                        @elseif($item->_tipo === 'insumo')
                        <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm
                                    hover:shadow-lg transition-all duration-200 flex">
                            <div class="relative w-32 sm:w-44 flex-shrink-0 overflow-hidden">
                                @if($item->fotos->isNotEmpty())
                                    <img src="{{ asset($item->fotos->first()->url_foto) }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <img src="https://placehold.co/200x150/7a5230/white?text=Insumo"
                                         class="w-full h-full object-cover">
                                @endif
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md text-[9px] font-bold tracking-widest text-white"
                                      style="background-color:#7a5230">INSUMO</span>
                            </div>
                            <div class="flex-1 p-4 flex flex-col sm:flex-row sm:items-center gap-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-dark text-sm mb-1">{{ $item->nombre }}</h3>
                                    <p class="text-xs text-gray-500">{{ $item->marca }}</p>
                                </div>
                                <div class="sm:text-right flex-shrink-0">
                                    <p class="font-display font-bold text-xl" style="color:#e49b39">
                                        ${{ number_format($item->precio, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mb-3">COP</p>
                                    <a href="{{ url('/marketplace/insumo/'.$item->id) }}"
                                       class="inline-block px-4 py-2 rounded-xl text-xs font-bold text-white
                                              transition-all hover:opacity-90 active:scale-95"
                                       style="background-color:#7a5230">
                                        Ver producto
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="text-center py-16 text-gray-400">
                            <p class="text-4xl mb-3">🔍</p>
                            <p class="font-semibold">No se encontraron productos con esos filtros.</p>
                            <a href="{{ route('marketplace.catalogo') }}" class="text-amber-600 text-sm mt-2 inline-block">
                                Limpiar filtros
                            </a>
                        </div>
                    @endforelse
                </div>


                {{-- ============================================================
                     PAGINACIÓN
                ============================================================ --}}
                @if($productos->hasPages())
                <div class="mt-10 pt-6 border-t border-gray-100">
                    {{ $productos->withQueryString()->links() }}
                </div>
                @endif

            </div>{{-- /área principal --}}
        </div>{{-- /flex layout --}}
    </div>{{-- /container --}}
</div>{{-- /x-data --}}

@endsection
