@extends('layouts.marketplace')

@section('title', 'Mis Pedidos — Moollish')

@section('content')

@php
    $pendientes   = $pedidos->where('estado', 'pendiente')->count();
    $coordinacion = $pedidos->where('estado', 'coordinacion')->count();
    $entregados   = $pedidos->where('estado', 'entregado')->count();

    $ordenEstados = ['pendiente' => 1, 'confirmado' => 2, 'coordinacion' => 3, 'entregado' => 4];
@endphp

<div class="max-w-4xl mx-auto px-4 py-8" x-data="{ tab: 'todos' }">

    {{-- =========================================================
         HEADER
    ========================================================= --}}
    <div class="flex items-baseline gap-3 mb-6">
        <h1 class="text-3xl font-bold text-gray-900">📦 Mis Pedidos</h1>
        <span class="text-gray-500 text-base font-normal">({{ $pedidos->count() }})</span>
    </div>

    {{-- =========================================================
         TABS
    ========================================================= --}}
    <div class="flex flex-wrap gap-1 border-b border-gray-200 mb-6">

        <button
            @click="tab = 'todos'"
            :class="tab === 'todos'
                ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                : 'text-gray-500 hover:text-gray-700'"
            class="px-4 pb-3 text-sm transition-colors"
        >Todos <span class="text-xs opacity-70">({{ $pedidos->count() }})</span></button>

        <button
            @click="tab = 'pendiente'"
            :class="tab === 'pendiente'
                ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                : 'text-gray-500 hover:text-gray-700'"
            class="px-4 pb-3 text-sm transition-colors"
        >Pendientes <span class="text-xs opacity-70">({{ $pendientes }})</span></button>

        <button
            @click="tab = 'coordinacion'"
            :class="tab === 'coordinacion'
                ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                : 'text-gray-500 hover:text-gray-700'"
            class="px-4 pb-3 text-sm transition-colors"
        >En coordinación <span class="text-xs opacity-70">({{ $coordinacion }})</span></button>

        <button
            @click="tab = 'entregado'"
            :class="tab === 'entregado'
                ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                : 'text-gray-500 hover:text-gray-700'"
            class="px-4 pb-3 text-sm transition-colors"
        >Entregados <span class="text-xs opacity-70">({{ $entregados }})</span></button>

    </div>

    {{-- =========================================================
         LISTA DE PEDIDOS
    ========================================================= --}}
    <div class="space-y-4">

        @forelse($pedidos as $pedido)

        @php
            $pasoActual = $ordenEstados[$pedido->estado] ?? 1;
        @endphp

        <div
            x-show="tab === 'todos' || tab === '{{ $pedido->estado }}'"
            x-cloak
            class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border-l-4
                {{ $pedido->estado === 'pendiente'    ? 'border-l-yellow-400'
                 : ($pedido->estado === 'coordinacion' ? 'border-l-blue-400'
                 : ($pedido->estado === 'entregado'    ? 'border-l-green-400'
                 : 'border-l-gray-300')) }}"
            x-data="{ open: false }"
        >
            <div class="p-5">

                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

                    {{-- Info principal --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-400 font-mono">
                            #MKT-{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}
                            · {{ $pedido->created_at->format('d M Y') }}
                        </p>
                        <p class="font-bold text-gray-900 text-base mt-1 leading-snug">
                            {{ $pedido->detalle->first()->nombre_snapshot ?? 'Pedido' }}
                            @if($pedido->detalle->count() > 1)
                                <span class="text-sm font-normal text-gray-400">
                                    + {{ $pedido->detalle->count() - 1 }} ítem(s) más
                                </span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Método: {{ ucfirst($pedido->metodo_pago ?? '—') }}
                        </p>
                    </div>

                    {{-- Badge + total --}}
                    <div class="text-right flex-shrink-0">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            {{ $pedido->estado === 'pendiente'    ? 'bg-yellow-100 text-yellow-800'
                             : ($pedido->estado === 'coordinacion' ? 'bg-blue-100 text-blue-800'
                             : ($pedido->estado === 'entregado'    ? 'bg-green-100 text-green-800'
                             : 'bg-gray-100 text-gray-600')) }}">
                            {{ ucfirst($pedido->estado) }}
                        </span>
                        <p class="font-bold text-lg mt-2" style="color:#e49b39;">
                            ${{ number_format($pedido->total, 0, ',', '.') }} COP
                        </p>
                    </div>

                </div>

                {{-- Acciones --}}
                <div class="flex flex-wrap gap-2 mt-4">
                    <button
                        @click="open = true"
                        class="text-sm font-semibold px-4 py-2 rounded-lg border transition-colors hover:bg-amber-50"
                        style="border-color:#e49b39; color:#c4801e;"
                    >
                        Ver detalle →
                    </button>
                </div>

            </div><!-- /card body -->


            {{-- =====================================================
                 MODAL DETALLE
            ===================================================== --}}
            <div
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @keydown.escape.window="open = false"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="background-color:rgba(0,0,0,0.5);"
            >
                <div
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    @click.outside="open = false"
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
                >

                    {{-- Header modal --}}
                    <div class="flex items-start justify-between p-6 border-b border-gray-100">
                        <div>
                            <p class="text-xs text-gray-400 font-mono mb-0.5">
                                Pedido #MKT-{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}
                            </p>
                            <h2 class="font-bold text-xl text-gray-900">Detalle del pedido</h2>
                            <p class="text-sm text-gray-500 mt-0.5">
                                {{ $pedido->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <button
                            @click="open = false"
                            class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors ml-4"
                            aria-label="Cerrar"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Cuerpo modal --}}
                    <div class="p-6 space-y-6">

                        {{-- Items del pedido --}}
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-3">Productos</p>
                            <div class="divide-y divide-gray-100">
                                @foreach($pedido->detalle as $item)
                                <div class="flex justify-between items-center py-2.5 text-sm">
                                    <span class="text-gray-700">
                                        {{ $item->nombre_snapshot }}
                                        <span class="text-gray-400">×{{ $item->cantidad }}</span>
                                    </span>
                                    <span class="font-semibold text-gray-900">
                                        ${{ number_format($item->precio_snapshot * $item->cantidad, 0, ',', '.') }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between items-center font-bold text-lg pt-3 border-t border-gray-100 mt-1">
                                <span>Total</span>
                                <span style="color:#e49b39;">
                                    ${{ number_format($pedido->total, 0, ',', '.') }} COP
                                </span>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Método de pago --}}
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Método de pago</span>
                            <span class="font-semibold text-gray-800">
                                {{ ucfirst($pedido->metodo_pago ?? '—') }}
                            </span>
                        </div>

                        <hr class="border-gray-100">

                        {{-- TIMELINE --}}
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-4">Estado del pedido</p>

                            <div class="relative flex items-start justify-between">

                                {{-- Línea fondo --}}
                                <div class="absolute top-4 h-0.5 bg-gray-200 z-0"
                                     style="left:2rem; right:2rem;"></div>

                                {{-- Línea progreso --}}
                                @php
                                    $porcentajes = [1 => '0%', 2 => '33%', 3 => '66%', 4 => '100%'];
                                    $progreso    = $porcentajes[$pasoActual] ?? '0%';
                                @endphp
                                <div class="absolute top-4 h-0.5 z-0"
                                     style="left:2rem; width:{{ $progreso }}; background-color:#e49b39;"></div>

                                @foreach(['pendiente' => 'Pedido', 'confirmado' => 'Confirmado', 'coordinacion' => 'En coordinación', 'entregado' => 'Entregado'] as $est => $label)
                                @php $paso = $ordenEstados[$est]; @endphp
                                <div class="flex flex-col items-center z-10 flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-colors
                                        {{ $pasoActual >= $paso ? 'text-white border-transparent' : 'bg-gray-100 text-gray-400 border-gray-200' }}"
                                         @if($pasoActual >= $paso) style="background-color:#e49b39; border-color:#e49b39;" @endif
                                    >
                                        @if($pasoActual > $paso) ✓
                                        @elseif($pasoActual === $paso) ●
                                        @else {{ $paso }}
                                        @endif
                                    </div>
                                    <p class="text-xs text-center mt-2 text-gray-600 leading-tight max-w-[70px]">
                                        {{ $label }}
                                    </p>
                                </div>
                                @endforeach

                            </div>
                        </div>

                        @if($pedido->notas)
                        <div class="flex items-start gap-3 bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm">
                            <span class="flex-shrink-0">📝</span>
                            <p class="text-gray-600">{{ $pedido->notas }}</p>
                        </div>
                        @endif

                        <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm">
                            <span class="flex-shrink-0">📋</span>
                            <p class="text-amber-800">
                                Coordina la entrega directamente con el vendedor.
                                Conserva todos los comprobantes de la transacción.
                            </p>
                        </div>

                        {{-- Footer modal --}}
                        <div class="flex gap-3">
                            <button
                                @click="open = false"
                                class="flex-1 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors"
                            >
                                Cerrar
                            </button>
                        </div>

                    </div><!-- /cuerpo modal -->

                </div><!-- /modal card -->
            </div><!-- /modal overlay -->

        </div><!-- /pedido card -->

        @empty

        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">📦</p>
            <p class="font-semibold text-gray-500">No tienes pedidos aún.</p>
            <a href="{{ url('/marketplace') }}"
               class="text-amber-600 text-sm mt-2 inline-block hover:underline">
                Explorar marketplace →
            </a>
        </div>

        @endforelse

    </div><!-- /lista pedidos -->

</div><!-- /container -->

@endsection
