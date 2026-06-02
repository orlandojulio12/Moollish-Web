@extends('layouts.marketplace')

@section('title', 'Lote 18 Novillas Holstein — Moollish')
@section('meta_description', 'Lote de 18 Novillas Holstein aptas para lechería. Peso prom. 310 kg. Montería, Córdoba. $81.000.000 COP en Moollish.')

@section('content')

@php
  $pub = $publicacion;

  // Número de animales
  $numAnimales = $pub->origen === 'sistema'
    ? $pub->animales->count()
    : (int) ($pub->m_num_animales ?? 0);

  // Peso promedio (el controller ya pasa $pesoPromedio para origen sistema)
  $pesoPromedioLote = $pub->origen === 'sistema'
    ? $pesoPromedio
    : $pub->m_peso_promedio;

  // Edad promedio (el controller ya pasa $edadPromedio para origen sistema)
  $edadPromedioLote = $pub->origen === 'sistema'
    ? $edadPromedio
    : $pub->m_edad_promedio;

  // Ubicación
  if ($pub->origen === 'sistema') {
    $perfVendedor = $pub->vendedor->perfilMarketplace ?? null;
    $ubicacionLote = $perfVendedor
      ? trim($perfVendedor->municipio.', '.$perfVendedor->departamento, ', ')
      : ($pub->punto_entrega ?? '');
  } else {
    $ubicacionLote = trim(($pub->m_municipio ?? '').', '.($pub->m_departamento ?? ''), ', ');
  }
@endphp

<div class="min-h-screen bg-cream">

    {{-- ============================================================
         1. BREADCRUMB
    ============================================================ --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-xs text-gray-400 flex-wrap">
                <a href="{{ url('/marketplace') }}"        class="hover:text-brand transition-colors">Inicio</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <a href="{{ url('/marketplace/lotes') }}"  class="hover:text-brand transition-colors">Lotes</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <span class="text-dark font-medium truncate max-w-[220px] sm:max-w-none">
                    {{ $publicacion->nombre }}
                </span>
            </nav>
        </div>
    </div>


    {{-- ============================================================
         2. HERO DEL LOTE
    ============================================================ --}}
    <section class="relative overflow-hidden py-10 sm:py-14" style="background-color:#2d5a27">

        {{-- Fondo decorativo --}}
        <div class="absolute inset-0 opacity-10"
             style="background-image: repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 0, transparent 50%);
                    background-size: 20px 20px;"></div>
        <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-12 -left-12 w-56 h-56 rounded-full bg-black/10"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                <div>
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-xl mb-4
                                bg-white/15 backdrop-blur-sm border border-white/20">
                        <span class="text-xl leading-none">🐄🐄</span>
                        <span class="text-xs font-bold uppercase tracking-widest text-white">Lote de Animales</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-green-300 animate-pulse"></span>
                        <span class="text-xs text-green-300 font-semibold">Disponible</span>
                    </div>

                    {{-- Título --}}
                    <h1 class="font-display font-bold text-3xl sm:text-4xl lg:text-5xl text-white leading-tight mb-2">
                        {{ $publicacion->nombre }}
                    </h1>
                    <p class="text-white/70 text-base sm:text-lg">
                        {{ ucfirst($publicacion->proposito) }} &mdash; {{ $publicacion->punto_entrega }}
                    </p>
                </div>

                {{-- Stats rápidos hero --}}
                <div class="flex sm:flex-col gap-4 sm:gap-3 flex-shrink-0">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2.5 border border-white/15">
                        <i class="fa-solid fa-cow text-sm text-white/60 flex-shrink-0"></i>
                        <div>
                            <p class="text-lg font-bold text-white leading-none">{{ $numAnimales }}</p>
                            <p class="text-[10px] text-white/55 uppercase tracking-wide">animales</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2.5 border border-white/15">
                        <i class="fa-solid fa-weight-scale text-sm text-white/60 flex-shrink-0"></i>
                        <div>
                            <p class="text-lg font-bold text-white leading-none">
                                {{ $pesoPromedioLote ? number_format($pesoPromedioLote, 0).' kg' : '—' }}
                            </p>
                            <p class="text-[10px] text-white/55 uppercase tracking-wide">peso prom.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2.5 border border-white/15">
                        <i class="fa-solid fa-calendar text-sm text-white/60 flex-shrink-0"></i>
                        <div>
                            <p class="text-lg font-bold text-white leading-none">
                                {{ $edadPromedioLote ? number_format($edadPromedioLote, 0).' m.' : '—' }}
                            </p>
                            <p class="text-[10px] text-white/55 uppercase tracking-wide">edad prom.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         3. GALERÍA + RESUMEN FINANCIERO
    ============================================================ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

        <div
            class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 mb-10"
            x-data="{
                active: '{{ $publicacion->fotos->isNotEmpty() ? asset($publicacion->fotos->first()->url_foto) : "https://placehold.co/800x560/2d5a27/white?text=Sin+foto" }}',
                photos: [
                    @foreach($publicacion->fotos as $foto)
                        '{{ asset($foto->url_foto) }}',
                    @endforeach
                ]
            }"
        >

            {{-- ── Galería izquierda ── --}}
            <div class="flex flex-col gap-3">

                {{-- Imagen principal --}}
                <div class="relative rounded-2xl overflow-hidden bg-gray-100 shadow-md" style="aspect-ratio:16/10">
                    <img
                        :src="active"
                        alt="{{ $publicacion->nombre }}"
                        class="w-full h-full object-cover transition-all duration-300"
                    >
                    {{-- Badge LOTE --}}
                    <span class="absolute top-4 left-4 px-3 py-1.5 rounded-xl text-xs font-bold tracking-widest text-white shadow"
                          style="background-color:#2d5a27">
                        LOTE
                    </span>
                    {{-- Favorito --}}
                    <button class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full
                                   bg-white/90 text-gray-400 hover:text-red-500 transition-colors shadow"
                            aria-label="Guardar">
                        <i class="fa-regular fa-heart text-base"></i>
                    </button>
                    {{-- Flechas --}}
                    <button @click="active = photos[(photos.indexOf(active) - 1 + photos.length) % photos.length]"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 flex items-center justify-center
                                   rounded-full bg-black/30 text-white hover:bg-black/50 transition-colors backdrop-blur-sm">
                        <i class="fa-solid fa-chevron-left text-sm"></i>
                    </button>
                    <button @click="active = photos[(photos.indexOf(active) + 1) % photos.length]"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 flex items-center justify-center
                                   rounded-full bg-black/30 text-white hover:bg-black/50 transition-colors backdrop-blur-sm">
                        <i class="fa-solid fa-chevron-right text-sm"></i>
                    </button>
                    {{-- Contador --}}
                    <span class="absolute bottom-4 right-4 px-2.5 py-1 rounded-full text-xs text-white bg-black/40 backdrop-blur-sm">
                        <span x-text="photos.indexOf(active) + 1"></span> / <span x-text="photos.length"></span>
                    </span>
                </div>

                {{-- Miniaturas --}}
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="(img, i) in photos" :key="i">
                        <button
                            @click="active = img"
                            :class="active === img ? 'ring-2 ring-offset-1 opacity-100' : 'opacity-55 hover:opacity-85'"
                            class="rounded-xl overflow-hidden transition-all duration-200"
                            style="aspect-ratio:4/3; --tw-ring-color:#2d5a27"
                        >
                            <img :src="img" alt="" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>

                {{-- Share --}}
                <div class="flex items-center gap-2 pt-1">
                    <span class="text-xs text-gray-400 mr-1">Compartir:</span>
                    @foreach([
                        ['fa-whatsapp',  'WhatsApp', '#25d366'],
                        ['fa-facebook-f','Facebook', '#1877f2'],
                        ['fa-x-twitter','X/Twitter', '#000000'],
                        ['fa-link',     'Copiar',    '#6b7280'],
                    ] as [$icon, $label, $color])
                    <button class="w-8 h-8 flex items-center justify-center rounded-full text-white text-xs
                                   transition-all hover:scale-110 active:scale-95 shadow-sm"
                            style="background-color:{{ $color }}"
                            aria-label="{{ $label }}">
                        <i class="fa-brands {{ $icon }}"></i>
                    </button>
                    @endforeach
                    <span class="ml-auto text-xs text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-eye text-[10px]"></i> 2.831 vistas
                    </span>
                </div>
            </div>


            {{-- ── Box financiero derecha ── --}}
            <div class="flex flex-col gap-4">

                {{-- Card precio principal --}}
                <div class="bg-white rounded-2xl border-2 shadow-lg overflow-hidden" style="border-color:#e49b39">

                    {{-- Cabecera card --}}
                    <div class="px-6 py-4 flex items-center justify-between"
                         style="background: linear-gradient(135deg,#2d5a27 0%,#1e3d1a 100%)">
                        <div>
                            @if($publicacion->precio_tipo === 'total')
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-white/60 mb-0.5">Precio total del lote</p>
                            <p class="font-display font-bold text-4xl sm:text-5xl text-white leading-none">
                                ${{ number_format($publicacion->precio, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-white/50 mt-1">COP · {{ $numAnimales }} animales</p>
                            @else
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-white/60 mb-0.5">Precio por cabeza</p>
                            <p class="font-display font-bold text-4xl sm:text-5xl text-white leading-none">
                                ${{ number_format($publicacion->precio, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-white/50 mt-1">COP · {{ $numAnimales }} animales</p>
                            @endif
                        </div>
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl bg-white/10 flex-shrink-0">
                            🐄🐄
                        </div>
                    </div>

                    <div class="px-6 py-5 space-y-4">

                        {{-- Precio derivado --}}
                        @if($publicacion->precio_tipo === 'total' && $numAnimales > 0)
                        <div class="flex items-center justify-between py-3 px-4 rounded-xl"
                             style="background-color:#fff8ee; border:1px solid #fde8c3">
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Precio por cabeza</p>
                                <p class="font-display font-bold text-2xl" style="color:#e49b39">
                                    ${{ number_format($publicacion->precio / $numAnimales, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400">= Total ÷ Animales</p>
                                <p class="text-xs text-gray-400">÷ {{ $numAnimales }} cab.</p>
                            </div>
                        </div>
                        @elseif($publicacion->precio_tipo !== 'total')
                        <div class="flex items-center justify-between py-3 px-4 rounded-xl"
                             style="background-color:#fff8ee; border:1px solid #fde8c3">
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Precio total estimado</p>
                                <p class="font-display font-bold text-2xl" style="color:#e49b39">
                                    ${{ number_format($publicacion->precio * $numAnimales, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400">= Cabeza × Animales</p>
                                <p class="text-xs text-gray-400">× {{ $numAnimales }} cab.</p>
                            </div>
                        </div>
                        @endif

                        <hr class="border-gray-100">

                        {{-- Grid resumen --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center gap-2.5 bg-gray-50 rounded-xl p-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                     style="background-color:#2d5a271a">
                                    <i class="fa-solid fa-cow text-xs" style="color:#2d5a27"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 leading-none mb-0.5">Animales</p>
                                    <p class="text-xs font-semibold text-dark leading-tight">
                                        {{ $numAnimales }} cabezas
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 bg-gray-50 rounded-xl p-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                     style="background-color:#2d5a271a">
                                    <i class="fa-solid fa-weight-scale text-xs" style="color:#2d5a27"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 leading-none mb-0.5">Peso prom.</p>
                                    <p class="text-xs font-semibold text-dark leading-tight">
                                        {{ $pesoPromedioLote ? number_format($pesoPromedioLote, 0).' kg' : '—' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 bg-gray-50 rounded-xl p-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                     style="background-color:#2d5a271a">
                                    <i class="fa-solid fa-calendar-days text-xs" style="color:#2d5a27"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 leading-none mb-0.5">Edad prom.</p>
                                    <p class="text-xs font-semibold text-dark leading-tight">
                                        {{ $edadPromedioLote ? number_format($edadPromedioLote, 0).' meses' : '—' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 bg-gray-50 rounded-xl p-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                     style="background-color:#2d5a271a">
                                    <i class="fa-solid fa-location-dot text-xs" style="color:#2d5a27"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 leading-none mb-0.5">Ubicación</p>
                                    <p class="text-xs font-semibold text-dark leading-tight">
                                        {{ $ubicacionLote ?: ($publicacion->punto_entrega ?? 'N/A') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Etiqueta propósito --}}
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                                         bg-green-50 text-green-800 border border-green-200">
                                <i class="fa-solid fa-circle-dot text-[9px]"></i>
                                {{ ucfirst($publicacion->proposito) }}
                            </span>
                        </div>

                        {{-- CTA comprar --}}
                        <div x-data="{ agregando: false, enCarrito: false }">
                        <button
                            @click="if (!enCarrito) {
                                agregando = true;
                                mkAgregarAlCarrito(
                                    'lote',
                                    {{ $publicacion->id }},
                                    '{{ addslashes($publicacion->nombre) }}',
                                    {{ $publicacion->precio_tipo === 'por_cabeza'
                                        ? (float) $publicacion->precio * $numAnimales
                                        : (float) $publicacion->precio }},
                                    '{{ $publicacion->fotos->first() ? asset($publicacion->fotos->first()->url_foto) : '' }}',
                                    '{{ $numAnimales }} cabezas{{ $publicacion->precio_tipo === "por_cabeza" ? " × $".number_format($publicacion->precio,0,",",".")." c/u" : " — precio total" }}'
                                ).then(ok => { if(ok) enCarrito = true; agregando = false; })
                            }"
                            :disabled="agregando || enCarrito"
                            class="w-full flex items-center justify-center gap-3 py-4 rounded-2xl text-base font-bold
                                   text-white transition-all hover:opacity-90 active:scale-95 shadow-md
                                   disabled:opacity-70 disabled:cursor-not-allowed"
                            :style="enCarrito ? 'background-color:#16a34a' : 'background-color:#e49b39'"
                        >
                            <template x-if="!agregando && !enCarrito">
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-cart-shopping"></i> Comprar este lote
                                </span>
                            </template>
                            <template x-if="agregando">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    Agregando...
                                </span>
                            </template>
                            <template x-if="!agregando && enCarrito">
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-check"></i> En tu carrito
                                </span>
                            </template>
                        </button>
                        </div>

                        {{-- CTA secundario --}}
                        @if($publicacion->vendedor?->perfilMarketplace?->whatsapp)
                        <a href="https://wa.me/57{{ preg_replace('/\D/', '', $publicacion->vendedor->perfilMarketplace->whatsapp) }}?text={{ urlencode('Hola, estoy interesado en el lote '.$publicacion->nombre.' del marketplace Moollish.') }}"
                           target="_blank"
                           class="w-full flex items-center justify-center gap-2.5 py-3 rounded-2xl text-sm font-bold
                                  border-2 transition-all hover:bg-green-50 active:scale-95"
                           style="border-color:#2d5a27; color:#2d5a27">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                            Contactar vendedor
                        </a>
                        @else
                        <button
                            class="w-full flex items-center justify-center gap-2.5 py-3 rounded-2xl text-sm font-bold
                                   border-2 transition-all hover:bg-green-50 active:scale-95"
                            style="border-color:#2d5a27; color:#2d5a27"
                        >
                            <i class="fa-solid fa-message text-xs"></i>
                            Contactar vendedor
                        </button>
                        @endif

                        {{-- Nota --}}
                        <div class="flex items-start gap-2.5 p-3.5 rounded-xl bg-amber-50 border border-amber-100">
                            <i class="fa-solid fa-lightbulb text-sm flex-shrink-0 mt-0.5" style="color:#e49b39"></i>
                            <p class="text-xs text-amber-800 leading-relaxed">
                                <span class="font-semibold">Precio negociable.</span> Contacta al vendedor para
                                coordinar la entrega, logística y condiciones del negocio.
                            </p>
                        </div>

                    </div>
                </div>

                {{-- Trust badges --}}
                <div class="grid grid-cols-3 gap-2">
                    @foreach([
                        ['fa-shield-halved',  'Vendedor verificado'],
                        ['fa-file-lines',     'Documentación completa'],
                        ['fa-truck',          'Entrega coordinada'],
                    ] as [$icon, $label])
                    <div class="flex flex-col items-center gap-1.5 py-3 px-2 bg-white rounded-xl border border-gray-100 text-center">
                        <i class="fa-solid {{ $icon }} text-base" style="color:#2d5a27"></i>
                        <p class="text-[10px] text-gray-500 leading-tight">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>

            </div>{{-- /box financiero --}}
        </div>


        {{-- ============================================================
             4. TABS DE DETALLE
        ============================================================ --}}
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-8 overflow-hidden"
            x-data="{ tab: 'composicion' }"
        >
            {{-- Tab nav --}}
            <div class="flex border-b border-gray-100 overflow-x-auto">
                @foreach([
                    ['composicion',  'fa-table-list',   'Composición del lote'],
                    ['condiciones',  'fa-handshake',    'Condiciones de venta'],
                    ['certificados', 'fa-certificate',  'Certificados'],
                    ['fotos',        'fa-images',       'Fotos del lote'],
                ] as [$key, $icon, $label])
                <button
                    @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}'
                        ? 'border-b-2 font-semibold text-dark'
                        : 'text-gray-400 hover:text-dark border-b-2 border-transparent'"
                    class="flex items-center gap-2 px-5 sm:px-7 py-4 text-sm whitespace-nowrap transition-colors flex-shrink-0"
                    :style="tab === '{{ $key }}' ? 'border-color:#2d5a27' : ''"
                >
                    <i class="fa-solid {{ $icon }} text-xs"></i>{{ $label }}
                </button>
                @endforeach
            </div>

            {{-- ── Tab: Composición ── --}}
            <div x-show="tab === 'composicion'" class="p-5 sm:p-7">

                @if($pub->origen === 'sistema')
                {{-- ── Origen sistema: tabla real de animales ── --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h3 class="font-semibold text-dark text-sm">Detalle de animales del lote</h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Mostrando {{ min(5, $numAnimales) }} de {{ $numAnimales }} animales.
                            El listado completo se entrega al comprador.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold
                                 bg-green-50 text-green-800 border border-green-200 self-start sm:self-auto">
                        <i class="fa-solid fa-cow text-[10px]"></i>
                        {{ $numAnimales }} cabezas en total
                    </span>
                </div>

                <div class="rounded-xl overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="background-color:#2d5a27">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-white/80 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-white/80 uppercase tracking-wider">Arete</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-white/80 uppercase tracking-wider">Raza</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-white/80 uppercase tracking-wider">Peso</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-white/80 uppercase tracking-wider">Sexo</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-white/80 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($publicacion->animales->take(5) as $index => $item)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }} hover:bg-green-50/30 transition-colors">
                                    <td class="px-4 py-3 text-xs font-mono text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-xs font-mono font-semibold text-dark">
                                        {{ $item->animal->codigo ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-700">{{ $item->animal->raza ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-dark">
                                        {{ $item->animal->peso ?? '—' }} kg
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        {{ ucfirst($item->animal->sexo ?? 'N/A') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px]
                                                     font-semibold bg-green-100 text-green-700">
                                            <i class="fa-solid fa-circle-check text-[9px]"></i>
                                            {{ $item->animal->estado_sanitario ?? 'Al día' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-xs text-gray-400">
                                        No hay animales registrados en este lote.
                                    </td>
                                </tr>
                                @endforelse
                                @if($numAnimales > 5)
                                <tr>
                                    <td colspan="6" class="text-center text-gray-400 text-sm py-3 bg-gray-50">
                                        ... y {{ $numAnimales - 5 }} animales más
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                {{-- ── Origen manual: mensaje descriptivo ── --}}
                <div class="flex flex-col items-center justify-center py-10 text-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl"
                         style="background-color:#2d5a2715">🐄🐄</div>
                    <div>
                        <p class="font-semibold text-dark text-sm mb-1">
                            Este lote contiene <span class="font-bold" style="color:#2d5a27">{{ $numAnimales }} animales</span>.
                        </p>
                        <p class="text-xs text-gray-500 max-w-sm leading-relaxed">
                            El vendedor proporcionará la composición detallada al contactar.
                            Puedes solicitar el listado completo por WhatsApp o mensajería directa.
                        </p>
                    </div>
                    @if($pub->m_raza_predominante)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                                 bg-green-50 text-green-800 border border-green-200">
                        <i class="fa-solid fa-cow text-[10px]"></i>
                        Raza predominante: {{ $pub->m_raza_predominante }}
                    </span>
                    @endif
                </div>
                @endif

                {{-- Resumen estadístico (ambos orígenes) --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
                    <div class="text-center py-4 px-3 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fa-solid fa-weight-scale text-lg mb-2" style="color:#2d5a27"></i>
                        <p class="font-display font-bold text-lg text-dark leading-none">
                            {{ $pesoPromedioLote ? number_format($pesoPromedioLote, 0).' kg' : '—' }}
                        </p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wide">Peso promedio</p>
                    </div>
                    <div class="text-center py-4 px-3 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fa-solid fa-calendar text-lg mb-2" style="color:#2d5a27"></i>
                        <p class="font-display font-bold text-lg text-dark leading-none">
                            {{ $edadPromedioLote ? number_format($edadPromedioLote, 0).' m.' : '—' }}
                        </p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wide">Edad promedio</p>
                    </div>
                    <div class="text-center py-4 px-3 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fa-solid fa-cow text-lg mb-2" style="color:#2d5a27"></i>
                        <p class="font-display font-bold text-lg text-dark leading-none">
                            {{ $numAnimales }}
                        </p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wide">Animales</p>
                    </div>
                    <div class="text-center py-4 px-3 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fa-solid fa-syringe text-lg mb-2" style="color:#2d5a27"></i>
                        <p class="font-display font-bold text-lg text-dark leading-none">
                            {{ ucfirst($publicacion->proposito) }}
                        </p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wide">Propósito</p>
                    </div>
                </div>
            </div>

            {{-- ── Tab: Condiciones de venta ── --}}
            <div x-show="tab === 'condiciones'" x-cloak class="p-5 sm:p-7">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <div class="space-y-4">
                        <h3 class="font-semibold text-dark text-sm mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-truck text-xs" style="color:#2d5a27"></i> Entrega y movilización
                        </h3>
                        @foreach([
                            ['Punto de entrega',         $publicacion->punto_entrega ?? 'A coordinar'],
                            ['Responsable del flete',    $publicacion->responsable_flete ?? 'A coordinar'],
                            ['Guía de movilización',     $publicacion->guia_movilizacion ?? 'A coordinar'],
                            ['Tiempo para retiro',       $publicacion->tiempo_retiro ?? 'A coordinar'],
                            ['Acepta visita previa',     ($publicacion->acepta_visita ?? false) ? 'Sí, con cita' : 'No'],
                        ] as [$item, $val])
                        <div class="flex items-start justify-between gap-4 py-2.5 border-b border-gray-100 last:border-0">
                            <span class="text-xs text-gray-400 flex-shrink-0 w-44">{{ $item }}</span>
                            <span class="text-xs font-semibold text-dark text-right">{{ $val }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-4">
                        <h3 class="font-semibold text-dark text-sm mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-file-lines text-xs" style="color:#2d5a27"></i> Documentación incluida
                        </h3>
                        <div class="space-y-2">
                            @forelse($publicacion->certificados as $cert)
                            <div class="flex items-center gap-3 py-2 px-3 rounded-xl bg-green-50">
                                <i class="fa-solid fa-circle-check text-sm flex-shrink-0 text-green-600"></i>
                                <span class="text-xs text-green-800">{{ $cert->nombre_certificado }}</span>
                            </div>
                            @empty
                            <p class="text-xs text-gray-400">Sin documentos registrados.</p>
                            @endforelse
                        </div>

                        <div class="mt-5 p-4 rounded-xl bg-amber-50 border border-amber-100">
                            <p class="text-xs font-semibold text-amber-800 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation"></i> Condición de pago
                            </p>
                            <p class="text-xs text-amber-700 leading-relaxed">
                                {{ $publicacion->condicion_pago ?? 'Consultar con el vendedor.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tab: Certificados ── --}}
            <div x-show="tab === 'certificados'" x-cloak class="p-5 sm:p-7">
                <div class="space-y-3">
                    @forelse($publicacion->certificados as $cert)
                    <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                        <span class="text-green-600 text-base">✅</span>
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-dark">{{ $cert->nombre_certificado }}</p>
                            @if($cert->fecha_emision)
                            <p class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($cert->fecha_emision)->format('d/m/Y') }}
                            </p>
                            @endif
                        </div>
                        @if($cert->url_archivo)
                        <a href="{{ asset($cert->url_archivo) }}" target="_blank"
                           class="text-xs font-semibold text-amber-600 hover:text-amber-700 flex-shrink-0">
                            Ver PDF
                        </a>
                        @endif
                    </div>
                    @empty
                    <p class="text-gray-400 text-sm">No hay certificados adjuntos.</p>
                    @endforelse
                </div>
            </div>

            {{-- ── Tab: Fotos del lote ── --}}
            <div x-show="tab === 'fotos'" x-cloak class="p-5 sm:p-7">
                <p class="text-xs text-gray-400 mb-5">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Galería fotográfica del lote — {{ $publicacion->punto_entrega }}
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @forelse($publicacion->fotos as $i => $foto)
                    <div class="group relative rounded-xl overflow-hidden aspect-square cursor-zoom-in">
                        <img
                            src="{{ asset($foto->url_foto) }}"
                            alt="Foto lote {{ $i + 1 }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-magnifying-glass-plus text-white opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </div>
                    </div>
                    @empty
                    <p class="col-span-3 text-center text-gray-400 py-8 text-sm">
                        No hay fotos disponibles para este lote.
                    </p>
                    @endforelse
                </div>
            </div>

        </div>{{-- /tabs --}}

        {{-- Video YouTube --}}
        @if($publicacion->video_youtube)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
            <h3 class="font-bold text-dark mb-3">🎥 Video del lote</h3>
            <div class="aspect-video rounded-xl overflow-hidden">
                <iframe
                    src="{{ str_replace('watch?v=', 'embed/', $publicacion->video_youtube) }}"
                    class="w-full h-full"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
        @endif


        {{-- ============================================================
             5. CARD VENDEDOR
        ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

            <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col gap-5">
                @if($publicacion->vendedor && $publicacion->vendedor->perfilMarketplace)
                @php $perfil = $publicacion->vendedor->perfilMarketplace; @endphp

                <div class="flex items-start gap-4">
                    <div class="relative flex-shrink-0">
                        <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center
                                    text-green-800 font-bold text-xl">
                            {{ strtoupper(substr($perfil->nombre_finca, 0, 2)) }}
                        </div>
                        @if($perfil->verificado)
                        <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-white flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-circle-check text-xs" style="color:#e49b39"></i>
                        </span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-dark text-base leading-tight">
                            {{ $perfil->nombre_finca }}
                        </h3>
                        @if($perfil->verificado)
                        <p class="text-xs font-semibold mt-0.5 flex items-center gap-1" style="color:#e49b39">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Vendedor verificado
                        </p>
                        @endif
                    </div>
                </div>

                <hr class="border-gray-100">

                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-location-dot text-xs flex-shrink-0" style="color:#e49b39"></i>
                        <div>
                            <p class="text-[10px] text-gray-400 leading-none">Ubicación</p>
                            <p class="text-xs font-semibold text-dark">
                                {{ $perfil->municipio }}, {{ $perfil->departamento }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-box-open text-xs flex-shrink-0" style="color:#e49b39"></i>
                        <div>
                            <p class="text-[10px] text-gray-400 leading-none">Publicaciones</p>
                            <p class="text-xs font-semibold text-dark">Activas</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div class="flex flex-col gap-2">
                    <a href="{{ url('/marketplace/vendedor/'.$publicacion->user_id) }}"
                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-bold
                              text-white transition-all hover:opacity-90"
                       style="background-color:#e49b39">
                        <i class="fa-solid fa-store text-xs"></i> Ver perfil del vendedor
                    </a>
                    <a href="#"
                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold
                              border-2 transition-all hover:bg-green-50"
                       style="border-color:#2d5a27; color:#2d5a27">
                        <i class="fa-brands fa-whatsapp text-xs"></i> Enviar WhatsApp
                    </a>
                </div>

                @else
                <p class="text-sm text-gray-400">Información del vendedor no disponible.</p>
                @endif

                <div class="flex items-start gap-2 p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <i class="fa-solid fa-shield-halved text-xs mt-0.5 flex-shrink-0 text-gray-400"></i>
                    <p class="text-[11px] text-gray-400 leading-relaxed">
                        Moollish recomienda visitar el lote antes de concretar la compra.
                    </p>
                </div>
            </div>

            {{-- Mapa placeholder --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-dark text-sm flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-xs" style="color:#2d5a27"></i>
                        Ubicación del lote
                    </h3>
                    <span class="text-xs text-gray-400">{{ $publicacion->punto_entrega }}, Colombia</span>
                </div>
                <div class="flex-1 min-h-[220px] relative bg-gray-100">
                    <img src="https://placehold.co/800x300/d4e8d0/2d5a27?text=Mapa+Monteria%2C+Cordoba"
                         alt="Mapa Montería" class="w-full h-full object-cover">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg border-2 border-white"
                                 style="background-color:#2d5a27">
                                <span class="text-xl">🐄🐄</span>
                            </div>
                            <div class="px-3 py-1 rounded-full text-xs font-semibold text-white shadow-md"
                                 style="background-color:#1a1a1a">
                                Lote — Montería, Córdoba
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 flex items-center gap-3 text-xs text-gray-400">
                    <i class="fa-solid fa-circle-info text-[11px]"></i>
                    Coordenadas exactas disponibles al contactar al vendedor.
                </div>
            </div>
        </div>


        {{-- ============================================================
             6. OTROS LOTES DEL VENDEDOR
        ============================================================ --}}
        <section>
            <div class="flex items-end justify-between mb-6">
                <div>
                    @if($publicacion->vendedor && $publicacion->vendedor->perfilMarketplace)
                    <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#2d5a27">
                        {{ $publicacion->vendedor->perfilMarketplace->nombre_finca }}
                    </p>
                    @endif
                    <h2 class="font-display font-bold text-2xl text-dark">Otros lotes disponibles</h2>
                </div>
                <a href="{{ url('/marketplace/vendedor/'.$publicacion->user_id) }}"
                   class="hidden sm:flex items-center gap-2 text-sm font-semibold hover:opacity-80 transition-opacity"
                   style="color:#2d5a27">
                    Ver todos <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @forelse($relacionados as $rel)
                @php
                  $relNumAnimales = $rel->origen === 'sistema'
                    ? $rel->animales->count()
                    : (int) ($rel->m_num_animales ?? 0);
                  $relUbicacion = $rel->origen === 'sistema'
                    ? ($rel->punto_entrega ?? '')
                    : trim(($rel->m_municipio ?? '').', '.($rel->m_departamento ?? ''), ', ');
                @endphp
                <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm
                            hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col">

                    {{-- Imagen --}}
                    <div class="relative overflow-hidden bg-gray-100" style="aspect-ratio:16/9">
                        @if($rel->fotos->isNotEmpty())
                        <img src="{{ asset($rel->fotos->first()->url_foto) }}"
                             alt="{{ $rel->nombre }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             loading="lazy">
                        @else
                        <img src="https://placehold.co/500x280/2d5a27/white?text={{ urlencode($rel->nombre) }}"
                             alt="{{ $rel->nombre }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             loading="lazy">
                        @endif
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-bold
                                     tracking-widest text-white" style="background-color:#2d5a27">LOTE</span>
                        <span class="absolute bottom-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-semibold
                                     bg-green-100 text-green-800">
                            {{ ucfirst($rel->proposito) }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="font-semibold text-dark text-sm leading-snug mb-3">{{ $rel->nombre }}</h3>

                        {{-- Grid stats mini --}}
                        <div class="grid grid-cols-2 gap-1.5 mb-3">
                            <div class="bg-gray-50 rounded-lg p-2">
                                <p class="text-[9px] text-gray-400">Animales</p>
                                <p class="text-[11px] font-semibold text-dark">{{ $relNumAnimales }} cab.</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-2">
                                <p class="text-[9px] text-gray-400">Ubicación</p>
                                <p class="text-[11px] font-semibold text-dark truncate">{{ $relUbicacion ?: 'N/A' }}</p>
                            </div>
                        </div>

                        {{-- Precio --}}
                        <div class="flex items-end justify-between mb-4">
                            @if($rel->precio_tipo === 'total')
                            <div>
                                <p class="text-[10px] text-gray-400">Precio total</p>
                                <p class="font-display font-bold text-base text-dark">
                                    ${{ number_format($rel->precio, 0, ',', '.') }}
                                </p>
                            </div>
                            @if($relNumAnimales > 0)
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400">Por cabeza</p>
                                <p class="text-sm font-semibold" style="color:#e49b39">
                                    ${{ number_format($rel->precio / $relNumAnimales, 0, ',', '.') }}
                                </p>
                            </div>
                            @endif
                            @else
                            <div>
                                <p class="text-[10px] text-gray-400">Por cabeza</p>
                                <p class="font-display font-bold text-base" style="color:#e49b39">
                                    ${{ number_format($rel->precio, 0, ',', '.') }}
                                </p>
                            </div>
                            @endif
                        </div>

                        <a href="{{ url('/marketplace/lote/'.$rel->id) }}"
                           class="mt-auto block text-center py-2.5 rounded-xl text-xs font-bold text-white
                                  transition-all hover:opacity-90 active:scale-95"
                           style="background-color:#2d5a27">
                            Ver lote
                        </a>
                    </div>
                </div>
                @empty
                <p class="col-span-3 text-center text-gray-400 py-8">
                    No hay más lotes de este vendedor.
                </p>
                @endforelse
            </div>

            <div class="flex justify-center mt-6 sm:hidden">
                <a href="{{ url('/marketplace/vendedor/'.$publicacion->user_id) }}"
                   class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold border-2"
                   style="border-color:#2d5a27; color:#2d5a27">
                    Ver todos los lotes <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </section>

    </div>{{-- /container --}}
</div>{{-- /wrapper --}}

@endsection
