@extends('layouts.marketplace')

@section('title', 'Toro Brahman Rojo #B-1042 — Moollish')
@section('meta_description', 'Toro Brahman Rojo de 480 kg, 3 años. Engorde. Montería, Córdoba. $5.200.000 COP en Moollish.')

@section('content')

@php
  // Helper: lee el dato del animal según origen de la publicación
  $pub = $publicacion;

  $nombreAnimal = $pub->origen === 'sistema'
    ? ($pub->animal->nombre ?? 'Animal #'.$pub->animal_id)
    : $pub->m_nombre;

  $razaAnimal = $pub->origen === 'sistema'
    ? ($pub->animal->raza ?? 'N/A')
    : ($pub->m_raza ?? 'N/A');

  $sexoAnimal = $pub->origen === 'sistema'
    ? ($pub->animal->sexo ?? 'N/A')
    : ($pub->m_sexo ?? 'N/A');

  $pesoAnimal = $pub->origen === 'sistema'
    ? ($ultimoPeso->peso ?? $pub->animal->peso ?? '—')
    : ($pub->m_peso_kg ?? '—');

  $colorAnimal = $pub->origen === 'sistema'
    ? ($pub->animal->color ?? 'N/A')
    : ($pub->m_color ?? 'N/A');

  $hierroAnimal = $pub->origen === 'sistema'
    ? ($pub->animal->hierro ?? 'N/A')
    : ($pub->m_hierro ?? 'N/A');

  $siniganAnimal = $pub->origen === 'sistema'
    ? ($pub->animal->id_sinigan ?? 'N/A')
    : ($pub->m_id_sinigan ?? 'N/A');

  $codigoAnimal = $pub->origen === 'sistema'
    ? ($pub->animal->codigo ?? 'N/A')
    : ($pub->m_hierro ?? 'N/A');

  // Edad
  if ($pub->origen === 'sistema' && $pub->animal?->fecha_nacimiento) {
    $nac   = \Carbon\Carbon::parse($pub->animal->fecha_nacimiento);
    $anos  = $nac->diffInYears(now());
    $meses = $nac->copy()->addYears($anos)->diffInMonths(now());
    $edadTexto = $anos . ' años ' . $meses . ' meses';
  } elseif ($pub->origen === 'manual') {
    $edadTexto = ($pub->m_edad_anos ?? 0) . ' años ' . ($pub->m_edad_meses ?? 0) . ' meses';
  } else {
    $edadTexto = 'N/A';
  }

  // Ubicación
  if ($pub->origen === 'sistema') {
    $municipio    = $pub->animal->predio->municipio ?? '';
    $departamento = $pub->animal->predio->departamento ?? '';
  } else {
    $municipio    = $pub->m_municipio ?? '';
    $departamento = $pub->m_departamento ?? '';
  }

  $ubicacion = trim($municipio . ', ' . $departamento, ', ');
@endphp

<div class="min-h-screen bg-cream">

    {{-- ============================================================
         1. BREADCRUMB
    ============================================================ --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-xs text-gray-400 flex-wrap">
                <a href="{{ url('/marketplace') }}" class="hover:text-brand transition-colors">Inicio</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <a href="{{ url('/marketplace/ganado') }}" class="hover:text-brand transition-colors">Ganado</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <span class="text-dark font-medium truncate max-w-[200px] sm:max-w-none">
                    {{ $nombreAnimal }}
                </span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">


        {{-- ============================================================
             2. GALERÍA + INFO PRINCIPAL
        ============================================================ --}}
        <div
            class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 mb-10"
            x-data="{
                active: '{{ $publicacion->fotos->isNotEmpty() ? asset($publicacion->fotos->first()->url_foto) : "https://placehold.co/600x500/e49b39/white?text=Sin+foto" }}',
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
                <div class="relative rounded-2xl overflow-hidden bg-gray-100 aspect-[4/3] shadow-md">
                    <img
                        :src="active"
                        alt="{{ $nombreAnimal }}"
                        class="w-full h-full object-cover transition-all duration-300"
                    >
                    {{-- Badge sobre imagen --}}
                    <span class="absolute top-4 left-4 px-3 py-1.5 rounded-xl text-xs font-bold tracking-widest text-white shadow"
                          style="background-color:#e49b39">
                        GANADO
                    </span>
                    {{-- Favorito --}}
                    <button class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full
                                   bg-white/90 text-gray-400 hover:text-red-500 transition-colors shadow"
                            aria-label="Guardar en favoritos">
                        <i class="fa-regular fa-heart text-base"></i>
                    </button>
                    {{-- Flechas navegación --}}
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
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="(img, i) in photos" :key="i">
                        <button
                            @click="active = img"
                            :class="active === img
                                ? 'ring-2 ring-offset-1 opacity-100'
                                : 'opacity-60 hover:opacity-90'"
                            class="rounded-xl overflow-hidden aspect-square transition-all duration-200"
                            style="--tw-ring-color: #e49b39"
                        >
                            <img :src="img" alt="" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>

                {{-- Share bar --}}
                <div class="flex items-center gap-2 pt-1">
                    <span class="text-xs text-gray-400 mr-1">Compartir:</span>
                    @foreach([
                        ['fa-whatsapp',  'WhatsApp',  '#25d366'],
                        ['fa-facebook-f','Facebook',  '#1877f2'],
                        ['fa-x-twitter', 'X/Twitter', '#000000'],
                        ['fa-link',      'Copiar link','#6b7280'],
                    ] as [$icon, $label, $color])
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-full text-white text-xs
                               transition-all hover:scale-110 active:scale-95 shadow-sm"
                        style="background-color:{{ $color }}"
                        aria-label="{{ $label }}"
                    ><i class="fa-brands {{ $icon }}"></i></button>
                    @endforeach
                </div>
            </div>

            {{-- ── Info derecha ── --}}
            <div class="flex flex-col gap-5">

                {{-- Badges --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-lg text-xs font-bold tracking-widest text-white"
                          style="background-color:#e49b39">GANADO</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                        {{ ucfirst($publicacion->proposito) }}
                    </span>
                    <span class="ml-auto flex items-center gap-1.5 text-xs font-medium text-green-600">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        Disponible
                    </span>
                </div>

                {{-- Título + código --}}
                <div>
                    <p class="text-xs text-gray-400 font-mono mb-1">
                        Código: {{ $codigoAnimal }}
                    </p>
                    <h1 class="font-display font-bold text-2xl sm:text-3xl text-dark leading-tight">
                        {{ $nombreAnimal }}
                    </h1>
                </div>

                {{-- Precio --}}
                <div class="flex items-end gap-3">
                    <p class="font-display font-bold text-4xl" style="color:#e49b39">
                        ${{ number_format($publicacion->precio_venta, 0, ',', '.') }}
                    </p>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">COP</span>
                        <p class="text-xs text-gray-400 leading-none">Precio por cabeza</p>
                    </div>
                </div>

                {{-- Grid datos clave --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3.5">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background-color:#e49b3920">
                            <i class="fa-solid fa-cow text-sm" style="color:#e49b39"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 leading-none mb-0.5">Raza</p>
                            <p class="text-sm font-semibold text-dark">{{ $razaAnimal }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3.5">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background-color:#e49b3920">
                            <i class="fa-solid fa-weight-scale text-sm" style="color:#e49b39"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 leading-none mb-0.5">Peso vivo</p>
                            <p class="text-sm font-semibold text-dark">{{ $pesoAnimal }} kg</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3.5">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background-color:#e49b3920">
                            <i class="fa-solid fa-calendar-days text-sm" style="color:#e49b39"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 leading-none mb-0.5">Edad</p>
                            <p class="text-sm font-semibold text-dark">{{ $edadTexto }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3.5">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background-color:#e49b3920">
                            <i class="fa-solid fa-location-dot text-sm" style="color:#e49b39"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 leading-none mb-0.5">Ubicación</p>
                            <p class="text-sm font-semibold text-dark">{{ $ubicacion ?: 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Propósito chips --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Propósito</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Engorde','Listo para sacrificio'] as $prop)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                                     bg-orange-50 text-orange-700 border border-orange-200">
                            <i class="fa-solid fa-circle-dot text-[9px]"></i>{{ $prop }}
                        </span>
                        @endforeach
                    </div>
                </div>

                {{-- Botones CTA --}}
                <div class="flex flex-col sm:flex-row gap-3"
                     x-data="{ agregando: false, enCarrito: false }">
                    <button
                        @click="if (!enCarrito) {
                            agregando = true;
                            mkAgregarAlCarrito(
                                'animal',
                                {{ $publicacion->id }},
                                '{{ addslashes($nombreAnimal) }}',
                                {{ (float) $publicacion->precio_venta }},
                                '{{ $publicacion->fotos->first() ? asset($publicacion->fotos->first()->url_foto) : '' }}',
                                '{{ addslashes($razaAnimal.' · '.$pesoAnimal.'kg') }}'
                            ).then(ok => { if(ok) enCarrito = true; agregando = false; })
                        }"
                        :disabled="agregando || enCarrito"
                        class="flex-1 flex items-center justify-center gap-2.5 py-3.5 rounded-2xl
                               text-sm font-bold text-white transition-all hover:opacity-90 active:scale-95 shadow-md
                               disabled:opacity-70 disabled:cursor-not-allowed"
                        :style="enCarrito ? 'background-color:#16a34a' : 'background-color:#e49b39'"
                    >
                        <template x-if="!agregando && !enCarrito">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-cart-plus"></i> Agregar al carrito
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
                    @if($publicacion->vendedor?->perfilMarketplace?->whatsapp)
                    <a href="https://wa.me/57{{ preg_replace('/\D/', '', $publicacion->vendedor->perfilMarketplace->whatsapp) }}?text={{ urlencode('Hola, estoy interesado en '.$nombreAnimal.' del marketplace Moollish.') }}"
                       target="_blank"
                       class="flex-1 flex items-center justify-center gap-2.5 py-3.5 rounded-2xl
                              text-sm font-bold transition-all hover:bg-orange-50 active:scale-95 border-2"
                       style="border-color:#e49b39; color:#e49b39">
                        <i class="fa-brands fa-whatsapp"></i>
                        Contactar vendedor
                    </a>
                    @else
                    <button
                        class="flex-1 flex items-center justify-center gap-2.5 py-3.5 rounded-2xl
                               text-sm font-bold transition-all hover:bg-orange-50 active:scale-95 border-2"
                        style="border-color:#e49b39; color:#e49b39"
                    >
                        <i class="fa-solid fa-message"></i>
                        Contactar vendedor
                    </button>
                    @endif
                </div>

                {{-- Nota entrega --}}
                <div class="flex items-center gap-3 p-3.5 rounded-xl bg-amber-50 border border-amber-100">
                    <i class="fa-solid fa-truck text-base flex-shrink-0" style="color:#e49b39"></i>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        <span class="font-semibold">Entrega coordinada</span> directamente con el vendedor.
                        El precio puede incluir o no el flete según acuerdo.
                    </p>
                </div>

                {{-- Vistas / publicado --}}
                <div class="flex items-center gap-4 text-xs text-gray-400 pt-1">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-eye text-[11px]"></i> 1.247 vistas
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-clock text-[11px]"></i> Publicado hace 5 días
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-heart text-[11px]"></i> 34 guardados
                    </span>
                </div>

            </div>{{-- /info derecha --}}
        </div>


        {{-- ============================================================
             3. TABS DE DETALLE
        ============================================================ --}}
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-8 overflow-hidden"
            x-data="{ tab: 'caracteristicas' }"
        >
            {{-- Tab nav --}}
            <div class="flex border-b border-gray-100 overflow-x-auto">
                @foreach([
                    ['caracteristicas', 'fa-list-check',   'Características'],
                    ['sanitario',       'fa-syringe',       'Datos Sanitarios'],
                    ['certificados',    'fa-certificate',   'Certificados'],
                    ['descripcion',     'fa-align-left',    'Descripción'],
                ] as [$key, $icon, $label])
                <button
                    @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}'
                        ? 'border-b-2 font-semibold text-dark'
                        : 'text-gray-400 hover:text-dark border-b-2 border-transparent'"
                    class="flex items-center gap-2 px-5 sm:px-7 py-4 text-sm whitespace-nowrap transition-colors flex-shrink-0"
                    :style="tab === '{{ $key }}' ? 'border-color:#e49b39;color:#1a1a1a' : ''"
                >
                    <i class="fa-solid {{ $icon }} text-xs"></i>
                    {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- ── Tab: Características ── --}}
            <div x-show="tab === 'caracteristicas'" class="p-5 sm:p-7">
                @php
                $caracteristicas = [
                    ['Código arete',       $codigoAnimal],
                    ['ID SINIGAN',         $siniganAnimal],
                    ['Sexo',               ucfirst($sexoAnimal)],
                    ['Categoría',          $publicacion->categoria_animal ?? 'N/A'],
                    ['Raza',               $razaAnimal],
                    ['Peso actual',        $pesoAnimal.' kg'],
                    ['Color',              $colorAnimal],
                    ['Hierro / marca',     $hierroAnimal],
                    ['Marcas / señales',   $publicacion->marcas_senales ?? 'N/A'],
                    ['Procedencia',        $publicacion->procedencia ?? 'N/A'],
                    ['Condición corporal', ($publicacion->condicion_corporal ?? 'N/A').' / 5'],
                    ['Temperamento',       $publicacion->temperamento ?? 'N/A'],
                    ['Castrado',           ($publicacion->castrado ?? false) ? 'Sí' : 'No'],
                    ['Estado sanitario',   $publicacion->estado_sanitario ?? 'N/A'],
                ];
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-0 rounded-xl overflow-hidden border border-gray-100">
                    @foreach($caracteristicas as $i => [$key, $val])
                    <div class="flex items-start gap-4 px-5 py-3.5
                                {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/60' }}
                                {{ $i < count($caracteristicas) - 2 ? 'border-b border-gray-100' : '' }}">
                        <span class="text-xs text-gray-400 w-40 flex-shrink-0 pt-0.5">{{ $key }}</span>
                        <span class="text-sm font-medium text-dark">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Tab: Datos Sanitarios ── --}}
            <div x-show="tab === 'sanitario'" x-cloak class="p-5 sm:p-7">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    {{-- Vacunas --}}
                    <div>
                        <h3 class="font-semibold text-dark text-sm mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-syringe text-xs" style="color:#e49b39"></i>
                            Vacunas Aplicadas
                        </h3>
                        <div class="space-y-3">
                            @forelse($vacunas as $vacuna)
                            <div class="flex items-center justify-between py-2.5 px-4 rounded-xl
                                        border border-gray-100 bg-gray-50/60">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-green-100">
                                        <i class="fa-solid fa-check text-xs text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-dark">
                                            {{ $vacuna->nombre_medicamento ?? $vacuna->tipo ?? 'Tratamiento' }}
                                        </p>
                                        <p class="text-[11px] text-gray-400">
                                            {{ \Carbon\Carbon::parse($vacuna->created_at)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">
                                    Registrado
                                </span>
                            </div>
                            @empty
                            <p class="text-gray-400 text-sm">No hay registros sanitarios disponibles.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Controles --}}
                    <div class="space-y-6">
                        <div>
                            <h3 class="font-semibold text-dark text-sm mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-stethoscope text-xs" style="color:#e49b39"></i>
                                Controles Veterinarios
                            </h3>
                            <div class="space-y-3">
                                @foreach([
                                    ['Último control veterinario', 'Enero 2026', 'fa-calendar-check'],
                                    ['Prueba de tuberculosis',     'Negativa — Nov 2025', 'fa-vial'],
                                    ['Prueba de brucelosis',       'Negativa — Nov 2025', 'fa-flask'],
                                    ['Desparasitación',            'Diciembre 2025', 'fa-pills'],
                                ] as [$item, $val, $icon])
                                <div class="flex items-start gap-3 py-2.5 px-4 rounded-xl border border-gray-100 bg-gray-50/60">
                                    <i class="fa-solid {{ $icon }} text-xs mt-0.5 flex-shrink-0" style="color:#e49b39"></i>
                                    <div>
                                        <p class="text-xs text-gray-400">{{ $item }}</p>
                                        <p class="text-sm font-medium text-dark">{{ $val }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Certificados (resumen) --}}
                        <div>
                            <h3 class="font-semibold text-dark text-sm mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-certificate text-xs" style="color:#e49b39"></i>
                                Certificados adjuntos
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @forelse($publicacion->certificados as $cert)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs
                                             font-semibold bg-green-50 text-green-700 border border-green-200">
                                    <i class="fa-solid fa-shield-halved text-[9px]"></i>
                                    {{ $cert->nombre_certificado }}
                                </span>
                                @empty
                                <p class="text-gray-400 text-xs">Sin certificados adjuntos.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tab: Certificados (detalle) ── --}}
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

            {{-- ── Tab: Descripción ── --}}
            <div x-show="tab === 'descripcion'" x-cloak class="p-5 sm:p-7">
                <div class="max-w-3xl space-y-4 text-sm text-gray-600 leading-relaxed">
                    <p>
                        Se ofrece en venta <strong class="text-dark">Toro Brahman Rojo #B-1042</strong>, un ejemplar de
                        excelente conformación corporal y genética comprobada, criado en pasturas naturales de la región
                        de Montería, Córdoba. El animal presenta una condición corporal de <strong class="text-dark">4/5</strong>,
                        musculatura bien desarrollada y temperamento dócil, ideal para trabajos en campo abierto.
                    </p>
                    <p>
                        Con <strong class="text-dark">480 kg de peso vivo</strong>, este toro es apto para engorde
                        intensivo o para sacrificio inmediato en frigorífico. Su linaje proviene de una ganadería de
                        selección con más de 20 años de mejoramiento genético en la costa atlántica colombiana.
                    </p>
                    <p>
                        El animal se encuentra al día en su plan sanitario certificado por médico veterinario oficial,
                        con todas las vacunas exigidas por el ICA para movilización dentro del territorio nacional.
                        Se entrega con guía de movilización y certificado sanitario vigente.
                    </p>
                    <div class="mt-5 p-4 rounded-xl bg-amber-50 border border-amber-100">
                        <p class="text-xs font-semibold text-amber-800 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i> Nota del vendedor
                        </p>
                        <p class="text-xs text-amber-700">
                            El precio incluye el animal en finca. El costo del flete hacia el destino del comprador se
                            cotiza por separado. Se acepta visita previa con cita agendada. Precio negociable
                            para compra de más de un animal.
                        </p>
                    </div>
                </div>
            </div>

        </div>{{-- /tabs --}}

        {{-- Video YouTube --}}
        @if($publicacion->video_youtube)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
            <h3 class="font-bold text-dark mb-3">🎥 Video del animal</h3>
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
             4. GRID: VENDEDOR + MAPA
        ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

            {{-- Card vendedor --}}
            <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col gap-5">

                @if($publicacion->vendedor && $publicacion->vendedor->perfilMarketplace)
                @php $perfil = $publicacion->vendedor->perfilMarketplace; @endphp

                <div class="flex items-start gap-4">
                    {{-- Avatar iniciales --}}
                    <div class="relative flex-shrink-0">
                        <div class="w-16 h-16 rounded-2xl bg-amber-100 flex items-center justify-center
                                    text-amber-700 font-bold text-xl">
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
                              text-white transition-all hover:opacity-90 active:scale-95"
                       style="background-color:#e49b39">
                        <i class="fa-solid fa-store text-xs"></i> Ver perfil del vendedor
                    </a>
                    <a href="#"
                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold
                              border-2 transition-all hover:bg-orange-50 active:scale-95"
                       style="border-color:#e49b39; color:#e49b39">
                        <i class="fa-brands fa-whatsapp text-xs"></i> Enviar WhatsApp
                    </a>
                </div>

                @else
                <p class="text-sm text-gray-400">Información del vendedor no disponible.</p>
                @endif

                {{-- Aviso seguridad --}}
                <div class="flex items-start gap-2 p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <i class="fa-solid fa-shield-halved text-xs mt-0.5 flex-shrink-0 text-gray-400"></i>
                    <p class="text-[11px] text-gray-400 leading-relaxed">
                        Moollish recomienda verificar el animal en persona antes de realizar cualquier pago.
                    </p>
                </div>
            </div>

            {{-- Mapa / ubicación placeholder --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-dark text-sm flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-xs" style="color:#e49b39"></i>
                        Ubicación del animal
                    </h3>
                    <span class="text-xs text-gray-400">
                        {{ $municipio }}, {{ $departamento }}, Colombia
                    </span>
                </div>
                <div class="flex-1 min-h-[220px] relative bg-gray-100">
                    <img
                        src="https://placehold.co/800x300/d4e8d0/2d5a27?text=Mapa+Monteria%2C+Cordoba"
                        alt="Ubicación Montería, Córdoba"
                        class="w-full h-full object-cover"
                    >
                    {{-- Pin simulado --}}
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg border-2 border-white"
                                 style="background-color:#e49b39">
                                <i class="fa-solid fa-cow text-white text-sm"></i>
                            </div>
                            <div class="px-3 py-1 rounded-full text-xs font-semibold text-white shadow-md"
                                 style="background-color:#1a1a1a">
                                Montería, Córdoba
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 flex items-center gap-3 text-xs text-gray-400">
                    <i class="fa-solid fa-circle-info text-[11px]"></i>
                    La ubicación exacta se comparte al contactar al vendedor.
                </div>
            </div>

        </div>


        {{-- ============================================================
             5. PRODUCTOS RELACIONADOS
        ============================================================ --}}
        <section>
            <div class="flex items-end justify-between mb-6">
                <div>
                    @if($publicacion->vendedor && $publicacion->vendedor->perfilMarketplace)
                    <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#e49b39">
                        {{ $publicacion->vendedor->perfilMarketplace->nombre_finca }}
                    </p>
                    @endif
                    <h2 class="font-display font-bold text-2xl text-dark">Más ganado de este vendedor</h2>
                </div>
                <a href="{{ url('/marketplace/vendedor/'.$publicacion->user_id) }}"
                   class="hidden sm:flex items-center gap-2 text-sm font-semibold transition-colors hover:opacity-80"
                   style="color:#e49b39">
                    Ver todos <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @forelse($relacionados as $rel)
                @php
                  $relNombre = $rel->origen === 'sistema'
                    ? ($rel->animal->nombre ?? 'Animal #'.$rel->animal_id)
                    : $rel->m_nombre;
                  $relRaza   = $rel->origen === 'sistema'
                    ? ($rel->animal->raza ?? 'N/A')
                    : ($rel->m_raza ?? 'N/A');
                  $relPeso   = $rel->origen === 'sistema'
                    ? ($rel->animal->peso ?? '—')
                    : ($rel->m_peso_kg ?? '—');
                  $relCodigo = $rel->origen === 'sistema'
                    ? ($rel->animal->codigo ?? '#'.$rel->animal_id)
                    : ($rel->m_hierro ?? '#'.$rel->id);
                @endphp
                <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm
                            hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col">

                    {{-- Imagen --}}
                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                        @if($rel->fotos->isNotEmpty())
                        <img src="{{ asset($rel->fotos->first()->url_foto) }}"
                             alt="{{ $relNombre }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             loading="lazy">
                        @else
                        <img src="https://placehold.co/400x300/e49b39/white?text={{ urlencode($relNombre) }}"
                             alt="{{ $relNombre }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             loading="lazy">
                        @endif
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-bold
                                     tracking-widest text-white" style="background-color:#e49b39">GANADO</span>
                        <span class="absolute bottom-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-semibold
                                     bg-orange-100 text-orange-700">
                            {{ ucfirst($rel->proposito) }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-[10px] font-mono text-gray-400 mb-0.5">
                            {{ $relCodigo }}
                        </p>
                        <h3 class="font-semibold text-dark text-sm leading-snug mb-3">
                            {{ $relNombre }}
                        </h3>

                        <div class="grid grid-cols-2 gap-1.5 mb-3">
                            <div class="bg-gray-50 rounded-lg p-2 text-center">
                                <p class="text-[9px] text-gray-400">Raza</p>
                                <p class="text-[11px] font-semibold text-dark truncate">
                                    {{ $relRaza }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-2 text-center">
                                <p class="text-[9px] text-gray-400">Peso</p>
                                <p class="text-[11px] font-semibold text-dark">
                                    {{ $relPeso }} kg
                                </p>
                            </div>
                        </div>

                        <p class="font-display font-bold text-lg mb-3" style="color:#e49b39">
                            ${{ number_format($rel->precio_venta, 0, ',', '.') }}
                            <span class="text-xs font-sans font-normal text-gray-400">COP</span>
                        </p>

                        <a href="{{ url('/marketplace/ganado/'.$rel->id) }}"
                           class="mt-auto block text-center py-2.5 rounded-xl text-xs font-bold text-white
                                  transition-all hover:opacity-90 active:scale-95"
                           style="background-color:#e49b39">
                            Ver detalle
                        </a>
                    </div>
                </div>
                @empty
                <p class="col-span-3 text-center text-gray-400 py-8">
                    No hay más publicaciones de este vendedor.
                </p>
                @endforelse
            </div>

            {{-- Ver todos mobile --}}
            <div class="flex justify-center mt-6 sm:hidden">
                <a href="{{ url('/marketplace/vendedor/'.$publicacion->user_id) }}"
                   class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold border-2"
                   style="border-color:#e49b39; color:#e49b39">
                    Ver todos del vendedor <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </section>

    </div>{{-- /container --}}
</div>{{-- /wrapper --}}

@endsection
