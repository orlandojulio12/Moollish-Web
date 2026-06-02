@extends('layouts.marketplace')

@section('title', $insumo->nombre.' — Moollish')

@section('content')

    {{-- =========================================================
         BREADCRUMB
    ========================================================= --}}
    <div class="max-w-6xl mx-auto px-4 py-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ url('/marketplace') }}" class="hover:text-brand transition-colors">Inicio</a>
            <span class="text-gray-300">/</span>
            <a href="{{ url('/marketplace/insumos') }}" class="hover:text-brand transition-colors">Insumos</a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-800 font-medium truncate">{{ $insumo->nombre }}</span>
        </nav>
    </div>

    {{-- =========================================================
         SECCIÓN PRINCIPAL
    ========================================================= --}}
    <section class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- ---- COLUMNA IZQUIERDA: Galería ---- --}}
            <div
                x-data="{
                    active: '{{ $insumo->fotos->isNotEmpty() ? asset($insumo->fotos->first()->url_foto) : "https://placehold.co/600x500/7a5230/white?text=Sin+foto" }}',
                    photos: [
                        @foreach($insumo->fotos as $foto)
                            '{{ asset($foto->url_foto) }}',
                        @endforeach
                    ]
                }"
            >
                {{-- Imagen principal --}}
                <img
                    :src="active"
                    alt="{{ $insumo->nombre }}"
                    class="rounded-xl w-full mb-3 object-cover"
                    style="height: 380px;"
                >

                {{-- Miniaturas dinámicas --}}
                <div class="flex gap-3">
                    <template x-for="(img, i) in photos" :key="i">
                        <img
                            :src="img"
                            :alt="'Foto ' + (i + 1)"
                            @click="active = img"
                            :class="active === img ? 'ring-2 ring-offset-2' : 'opacity-70 hover:opacity-100'"
                            class="rounded-lg object-cover cursor-pointer transition-all flex-1"
                            style="height: 90px; --tw-ring-color: #e49b39;"
                        >
                    </template>
                    {{-- Fallback si no hay fotos --}}
                    <template x-if="photos.length === 0">
                        <div class="w-full h-[90px] rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                            Sin fotos adicionales
                        </div>
                    </template>
                </div>
            </div>

            {{-- ---- COLUMNA DERECHA: Info del producto ---- --}}
            <div
                x-data="{ qty: 1, price: {{ $insumo->precio }}, agregando: false, enCarrito: false }"
                class="flex flex-col gap-4"
            >
                {{-- Badge categoría --}}
                <div>
                    <span
                        class="inline-block rounded-full px-3 py-1 text-sm font-semibold text-white"
                        style="background-color: #7a5230;"
                    >
                        🌾 INSUMO
                    </span>
                </div>

                {{-- Marca --}}
                <a href="#" class="text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors w-fit">
                    {{ $insumo->marca ?? 'Sin marca' }}
                </a>

                {{-- Título --}}
                <h1 class="text-3xl font-bold text-gray-900 leading-tight">
                    {{ $insumo->nombre }}
                </h1>

                {{-- Rating --}}
                <div class="flex items-center gap-2">
                    <span class="text-lg" style="color: #e49b39;">⭐⭐⭐⭐✨</span>
                    <span class="text-sm text-gray-500">23 compras</span>
                </div>

                {{-- Precio --}}
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold" style="color: #e49b39;">${{ number_format($insumo->precio, 0, ',', '.') }} COP</span>
                    <span class="text-sm text-gray-500">por {{ $insumo->unidad ?? 'unidad' }}</span>
                </div>

                {{-- Selector de cantidad --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Cantidad</label>
                    <div class="flex items-center gap-3">
                        <button
                            @click="qty = Math.max(1, qty - 1)"
                            class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center
                                   text-gray-600 hover:bg-gray-100 transition-colors font-bold text-lg"
                        >−</button>

                        <input
                            type="number"
                            x-model="qty"
                            min="1"
                            class="w-16 h-10 text-center border border-gray-300 rounded-lg text-sm font-semibold
                                   focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color: #e49b39;"
                        >

                        <button
                            @click="qty++"
                            class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center
                                   text-gray-600 hover:bg-gray-100 transition-colors font-bold text-lg"
                        >+</button>
                    </div>

                    {{-- Subtotal dinámico --}}
                    <p class="text-sm font-semibold text-gray-700">
                        Total:
                        <span
                            x-text="'$' + (qty * price).toLocaleString('es-CO') + ' COP'"
                            style="color: #e49b39;"
                        ></span>
                    </p>
                </div>

                {{-- Stock --}}
                <p class="font-medium text-sm {{ $insumo->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $insumo->stock > 0 ? '✅ '.$insumo->stock.' unidades disponibles' : '❌ Agotado' }}
                </p>

                {{-- Botón agregar al carrito --}}
                <button
                    @click="if (!enCarrito) {
                        agregando = true;
                        mkAgregarAlCarrito(
                            'insumo',
                            {{ $insumo->id }},
                            '{{ addslashes($insumo->nombre) }}',
                            {{ (float) $insumo->precio }},
                            '{{ $insumo->fotos->first() ? asset($insumo->fotos->first()->url_foto) : '' }}',
                            '{{ addslashes($insumo->marca ?? '') }}'
                        ).then(ok => { agregando = false; })
                    }"
                    :disabled="agregando || $insumo->stock <= 0"
                    class="w-full py-3 rounded-xl font-bold text-white text-base transition-opacity
                           hover:opacity-90 active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed
                           flex items-center justify-center gap-2"
                    :style="enCarrito ? 'background-color:#16a34a' : 'background-color:#e49b39'"
                >
                    <template x-if="!agregando">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i>
                            <span x-text="enCarrito ? 'Agregado ✓' : 'Agregar al carrito'"></span>
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
                </button>

                {{-- Info de envío --}}
                <div class="bg-amber-50 p-3 rounded-lg">
                    <p class="text-sm text-amber-800 font-medium">
                        📦 Envío estándar o retiro en punto de venta
                    </p>
                </div>

                {{-- Tiempo estimado --}}
                <p class="text-sm text-gray-500">
                    ⏱ Tiempo estimado: 3-5 días hábiles
                </p>

            </div><!-- /columna derecha -->

        </div><!-- /grid -->

        {{-- =========================================================
             TABS: Descripción / Especificaciones / Uso recomendado
        ========================================================= --}}
        <div class="mt-12" x-data="{ tab: 'descripcion' }">

            {{-- Botones tab --}}
            <div class="flex gap-6 border-b border-gray-200 mb-6">
                <button
                    @click="tab = 'descripcion'"
                    :class="tab === 'descripcion'
                        ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                        : 'text-gray-500 hover:text-gray-700'"
                    class="pb-3 text-sm transition-colors"
                >Descripción</button>

                <button
                    @click="tab = 'especificaciones'"
                    :class="tab === 'especificaciones'
                        ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                        : 'text-gray-500 hover:text-gray-700'"
                    class="pb-3 text-sm transition-colors"
                >Especificaciones</button>

                <button
                    @click="tab = 'uso'"
                    :class="tab === 'uso'
                        ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                        : 'text-gray-500 hover:text-gray-700'"
                    class="pb-3 text-sm transition-colors"
                >Uso recomendado</button>
            </div>

            {{-- Tab: Descripción --}}
            <div x-show="tab === 'descripcion'" class="space-y-4 text-gray-700 text-sm leading-relaxed">
                <p>{{ $insumo->descripcion ?? 'Sin descripción.' }}</p>
            </div>

            {{-- Tab: Especificaciones --}}
            <div x-show="tab === 'especificaciones'" x-cloak>
                @php
                    $specs = [
                        ['Precio',          '$'.number_format($insumo->precio, 0, ',', '.').' COP'],
                        ['Unidad',          $insumo->unidad ?? '—'],
                        ['Stock',           $insumo->stock > 0 ? $insumo->stock.' unidades' : 'Agotado'],
                        ['Registro ICA',    $insumo->registro_ica ?? '—'],
                        ['Composición',     $insumo->composicion ?? '—'],
                        ['Categoría',       $insumo->categoria ?? '—'],
                        ['Animales objetivo', $insumo->animales_objetivo ?? '—'],
                    ];
                @endphp
                <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <tbody>
                        @foreach($specs as $i => [$label, $valor])
                        <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="px-4 py-3 font-medium text-gray-600 w-1/3 border-b border-gray-100">{{ $label }}</td>
                            <td class="px-4 py-3 text-gray-800 border-b border-gray-100">{{ $valor }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Tab: Uso recomendado --}}
            <div x-show="tab === 'uso'" x-cloak class="space-y-3 text-sm text-gray-700">
                @if($insumo->dosis)
                <div class="flex items-start gap-3">
                    <span class="font-semibold text-gray-900 min-w-max">Dosis recomendada:</span>
                    <span>{{ $insumo->dosis }}</span>
                </div>
                @endif

                @if($insumo->animales_objetivo)
                <div class="flex items-start gap-3">
                    <span class="font-semibold text-gray-900 min-w-max">Animales:</span>
                    <span>{{ $insumo->animales_objetivo }}</span>
                </div>
                @endif

                @if($insumo->uso_recomendado)
                <div class="flex items-start gap-3">
                    <span class="font-semibold text-gray-900 min-w-max">Instrucciones:</span>
                    <span>{{ $insumo->uso_recomendado }}</span>
                </div>
                @endif

                @if(!$insumo->dosis && !$insumo->animales_objetivo && !$insumo->uso_recomendado)
                <p class="text-gray-400">Sin instrucciones de uso registradas.</p>
                @endif

                <div class="mt-4 flex items-start gap-3 bg-orange-50 border border-orange-200 rounded-lg p-4 text-orange-800">
                    <span class="text-lg leading-none">⚠️</span>
                    <span>Consulta con tu veterinario la dosis exacta según el peso, edad y condición productiva de tus animales.</span>
                </div>
            </div>

        </div><!-- /tabs -->

        {{-- =========================================================
             CARD VENDEDOR / PROVEEDOR
        ========================================================= --}}
        @if($insumo->vendedor && $insumo->vendedor->perfilMarketplace)
        @php $perfil = $insumo->vendedor->perfilMarketplace; @endphp
        <div class="mt-12 border border-gray-200 rounded-xl p-6 flex flex-col sm:flex-row items-start sm:items-center gap-5">

            {{-- Avatar con iniciales --}}
            <div class="w-16 h-16 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-xl"
                 style="background-color: #7a5230;">
                {{ strtoupper(substr($perfil->nombre_finca, 0, 2)) }}
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="font-bold text-xl text-gray-900">{{ $perfil->nombre_finca }}</p>
                @if($perfil->verificado)
                <p class="text-sm font-medium text-amber-600 mt-0.5">Proveedor verificado ✓</p>
                @endif
                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-sm text-gray-500">
                    @if($perfil->municipio || $perfil->departamento)
                    <span>📍 {{ trim(($perfil->municipio ?? '').', '.($perfil->departamento ?? ''), ', ') }}</span>
                    @endif
                </div>
            </div>

            {{-- Botón --}}
            <a
                href="{{ url('/marketplace/vendedor/'.$insumo->user_id) }}"
                class="flex-shrink-0 px-5 py-2 rounded-xl border-2 text-sm font-semibold transition-colors hover:bg-amber-50"
                style="border-color: #e49b39; color: #c4801e;"
            >
                Ver todos sus productos
            </a>

        </div><!-- /card vendedor -->
        @endif

        {{-- =========================================================
             PRODUCTOS RELACIONADOS
        ========================================================= --}}
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Insumos relacionados</h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                @forelse($relacionados as $rel)
                <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                    <img
                        src="{{ $rel->fotos->isNotEmpty() ? asset($rel->fotos->first()->url_foto) : 'https://placehold.co/200x140/7a5230/white?text=Insumo' }}"
                        alt="{{ $rel->nombre }}"
                        class="w-full object-cover"
                        style="height: 120px;"
                    >
                    <div class="p-3">
                        <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $rel->nombre }}</p>
                        <p class="text-sm font-bold mt-1" style="color: #e49b39;">${{ number_format($rel->precio, 0, ',', '.') }} COP</p>
                        <a href="{{ url('/marketplace/insumos/'.$rel->id) }}"
                           class="mt-2 inline-block w-full text-center text-xs font-semibold py-1.5 rounded-lg border transition-colors hover:bg-amber-50"
                           style="border-color: #e49b39; color: #c4801e;">
                            Ver
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-8 text-gray-400 text-sm">
                    No hay insumos relacionados.
                </div>
                @endforelse

            </div><!-- /grid relacionados -->
        </div><!-- /productos relacionados -->

    </section>

@endsection
