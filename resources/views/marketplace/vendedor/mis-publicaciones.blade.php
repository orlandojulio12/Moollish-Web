@extends('layouts.vendor-panel')
@section('title', 'Mis Publicaciones')
@section('page-title', 'Mis Publicaciones')

@section('topbar-actions')
@endsection

@section('content')

@php
    $totalAnimales = $animales->count();
    $totalLotes    = $lotes->count();
    $totalInsumos  = $insumos->count();
    $total         = $totalAnimales + $totalLotes + $totalInsumos;
@endphp

{{-- Stats rápidas --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-gray-900">{{ $total }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Total publicaciones</p>
    </div>
    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold" style="color:#e49b39;">{{ $totalAnimales }}</p>
        <p class="text-xs text-gray-500 mt-0.5">🐄 Animales</p>
    </div>
    <div class="bg-green-50 border border-green-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-green-700">{{ $totalLotes }}</p>
        <p class="text-xs text-gray-500 mt-0.5">🐄🐄 Lotes</p>
    </div>
    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-yellow-700">{{ $totalInsumos }}</p>
        <p class="text-xs text-gray-500 mt-0.5">🌾 Insumos</p>
    </div>
</div>

{{-- Tabs --}}
<div x-data="{ tab: 'animales' }">

    <div class="flex gap-1 p-1 bg-gray-100 rounded-xl mb-5 w-fit">
        <button @click="tab = 'animales'"
                :class="tab === 'animales' ? 'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm transition-all">
            🐄 Animales
            <span class="ml-1 text-xs opacity-70">({{ $totalAnimales }})</span>
        </button>
        <button @click="tab = 'lotes'"
                :class="tab === 'lotes' ? 'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm transition-all">
            🐄🐄 Lotes
            <span class="ml-1 text-xs opacity-70">({{ $totalLotes }})</span>
        </button>
        <button @click="tab = 'insumos'"
                :class="tab === 'insumos' ? 'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm transition-all">
            🌾 Insumos
            <span class="ml-1 text-xs opacity-70">({{ $totalInsumos }})</span>
        </button>
    </div>

    {{-- ── TAB ANIMALES ── --}}
    <div x-show="tab === 'animales'">
        @if($animales->isEmpty())
            <div class="bg-white border border-dashed border-gray-300 rounded-xl py-12 text-center text-gray-400">
                <p class="text-3xl mb-2">🐄</p>
                <p class="font-semibold">No tienes animales publicados</p>
                <a href="{{ route('marketplace.panel.publicar') }}"
                   class="inline-block mt-3 text-sm font-semibold hover:underline"
                   style="color:#e49b39;">Publicar primer animal →</a>
            </div>
        @else
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Animal</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Raza / Peso</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Precio</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Propósito</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Estado</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($animales as $pub)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($pub->fotos->isNotEmpty())
                                    <img src="{{ asset($pub->fotos->first()->url_foto) }}"
                                         class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-base">🐄</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm leading-tight">
                                        {{ $pub->animal->nombre ?? 'Animal #'.$pub->animal_id }}
                                    </p>
                                    <p class="text-xs text-gray-400 font-mono">
                                        {{ $pub->animal->codigo ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-600 text-sm">
                            {{ $pub->animal->raza ?? '—' }}
                            @if($pub->animal->peso)
                                <span class="text-gray-400">· {{ $pub->animal->peso }}kg</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 font-semibold text-gray-800">
                            ${{ number_format($pub->precio_venta, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-medium">
                                {{ ucfirst($pub->proposito) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($pub->estado === 'activo')
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                                </span>
                            @elseif($pub->estado === 'vendido')
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 bg-gray-100 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Vendido
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-yellow-700 bg-yellow-50 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> {{ ucfirst($pub->estado) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('marketplace.detalle-animal', $pub->id) }}"
                               class="text-xs font-semibold hover:underline" style="color:#e49b39;">
                                Ver →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- ── TAB LOTES ── --}}
    <div x-show="tab === 'lotes'" x-cloak>
        @if($lotes->isEmpty())
            <div class="bg-white border border-dashed border-gray-300 rounded-xl py-12 text-center text-gray-400">
                <p class="text-3xl mb-2">🐄🐄</p>
                <p class="font-semibold">No tienes lotes publicados</p>
                <a href="{{ route('marketplace.panel.publicar') }}"
                   class="inline-block mt-3 text-sm font-semibold hover:underline"
                   style="color:#e49b39;">Publicar primer lote →</a>
            </div>
        @else
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Lote</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Animales</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Precio</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Propósito</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Estado</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($lotes as $lote)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($lote->fotos->isNotEmpty())
                                    <img src="{{ asset($lote->fotos->first()->url_foto) }}"
                                         class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-base">🐄🐄</span>
                                    </div>
                                @endif
                                <p class="font-semibold text-gray-900 text-sm">{{ $lote->nombre }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $lote->animales->count() }} cabezas
                        </td>
                        <td class="px-5 py-4 font-semibold text-gray-800">
                            ${{ number_format($lote->precio, 0, ',', '.') }}
                            <span class="text-xs font-normal text-gray-400">
                                /{{ $lote->precio_tipo === 'por_cabeza' ? 'cab.' : 'total' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-700 font-medium">
                                {{ ucfirst($lote->proposito) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($lote->estado === 'activo')
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-yellow-700 bg-yellow-50 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> {{ ucfirst($lote->estado) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('marketplace.detalle-lote', $lote->id) }}"
                               class="text-xs font-semibold hover:underline" style="color:#e49b39;">
                                Ver →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- ── TAB INSUMOS ── --}}
    <div x-show="tab === 'insumos'" x-cloak>
        @if($insumos->isEmpty())
            <div class="bg-white border border-dashed border-gray-300 rounded-xl py-12 text-center text-gray-400">
                <p class="text-3xl mb-2">🌾</p>
                <p class="font-semibold">No tienes insumos publicados</p>
                <a href="{{ route('marketplace.panel.publicar') }}"
                   class="inline-block mt-3 text-sm font-semibold hover:underline"
                   style="color:#e49b39;">Publicar primer insumo →</a>
            </div>
        @else
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Producto</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Categoría</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Precio</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Stock</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Estado</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($insumos as $insumo)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($insumo->fotos->isNotEmpty())
                                    <img src="{{ asset($insumo->fotos->first()->url_foto) }}"
                                         class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-base">🌾</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm leading-tight">{{ $insumo->nombre }}</p>
                                    @if($insumo->marca)
                                        <p class="text-xs text-gray-400">{{ $insumo->marca }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-700 font-medium capitalize">
                                {{ $insumo->categoria }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-gray-800">
                            ${{ number_format($insumo->precio, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $insumo->stock }} uds.
                        </td>
                        <td class="px-5 py-4">
                            @if($insumo->estado === 'activo')
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-yellow-700 bg-yellow-50 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> {{ ucfirst($insumo->estado) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('marketplace.detalle-insumo', $insumo->id) }}"
                               class="text-xs font-semibold hover:underline" style="color:#e49b39;">
                                Ver →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>{{-- /x-data tabs --}}

@endsection
