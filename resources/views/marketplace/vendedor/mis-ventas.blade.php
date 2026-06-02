@extends('layouts.vendor-panel')
@section('title', 'Mis Ventas')
@section('page-title', 'Mis Ventas')

@section('content')

@php
    $estados = [
        'pendiente'  => ['label' => 'Pendiente',   'color' => 'yellow'],
        'pagado'     => ['label' => 'Pagado',       'color' => 'blue'],
        'preparando' => ['label' => 'Preparando',   'color' => 'amber'],
        'enviado'    => ['label' => 'Enviado',      'color' => 'indigo'],
        'entregado'  => ['label' => 'Entregado',    'color' => 'green'],
        'cancelado'  => ['label' => 'Cancelado',    'color' => 'red'],
    ];

    $totalVentas        = $ventas->count();
    $ventasCompletadas  = $ventas->where('estado', 'entregado')->count();
    $ingresoTotal       = $ventas->where('estado', 'entregado')->sum('total');
    $pendientes         = $ventas->where('estado', 'pendiente')->count();
@endphp

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-gray-900">{{ $totalVentas }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Total órdenes</p>
    </div>
    <div class="bg-green-50 border border-green-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-green-700">{{ $ventasCompletadas }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Completadas</p>
    </div>
    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold" style="color:#e49b39;">
            ${{ number_format($ingresoTotal, 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-500 mt-0.5">Ingresos totales</p>
    </div>
    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-yellow-700">{{ $pendientes }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Pendientes</p>
    </div>
</div>

@if($ventas->isEmpty())

    <div class="bg-white border border-dashed border-gray-300 rounded-xl py-16 text-center text-gray-400">
        <p class="text-4xl mb-3">🛒</p>
        <p class="font-semibold text-gray-600">Aún no tienes ventas</p>
        <p class="text-sm text-gray-400 mt-1">
            Cuando alguien compre tus publicaciones, las verás aquí.
        </p>
        <a href="{{ route('marketplace.panel.publicar') }}"
           class="inline-block mt-4 px-5 py-2.5 rounded-xl text-white text-sm font-semibold
                  hover:opacity-90 transition-opacity"
           style="background-color:#e49b39;">
            Crear publicación
        </a>
    </div>

@else

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">
                        Orden
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">
                        Comprador
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">
                        Productos
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">
                        Total
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">
                        Estado
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">
                        Fecha
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($ventas as $orden)
                @php
                    $cfg = $estados[$orden->estado] ?? ['label' => ucfirst($orden->estado), 'color' => 'gray'];
                    $colorMap = [
                        'yellow' => 'bg-yellow-50 text-yellow-700',
                        'blue'   => 'bg-blue-50 text-blue-700',
                        'amber'  => 'bg-amber-50 text-amber-700',
                        'indigo' => 'bg-indigo-50 text-indigo-700',
                        'green'  => 'bg-green-50 text-green-700',
                        'red'    => 'bg-red-50 text-red-700',
                        'gray'   => 'bg-gray-100 text-gray-600',
                    ];
                    $dotMap = [
                        'yellow' => 'bg-yellow-400',
                        'blue'   => 'bg-blue-400',
                        'amber'  => 'bg-amber-400',
                        'indigo' => 'bg-indigo-400',
                        'green'  => 'bg-green-500',
                        'red'    => 'bg-red-400',
                        'gray'   => 'bg-gray-400',
                    ];
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">

                    {{-- Orden ID --}}
                    <td class="px-5 py-4">
                        <span class="font-mono text-xs text-gray-500 font-semibold">
                            #{{ str_pad($orden->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>

                    {{-- Comprador --}}
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-900 text-sm leading-tight">
                            {{ $orden->comprador->name ?? 'Comprador' }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $orden->comprador->email ?? '' }}</p>
                    </td>

                    {{-- Productos --}}
                    <td class="px-5 py-4 text-gray-600 text-sm">
                        {{ $orden->detalle->count() }}
                        {{ $orden->detalle->count() === 1 ? 'producto' : 'productos' }}
                    </td>

                    {{-- Total --}}
                    <td class="px-5 py-4 font-semibold text-gray-800">
                        ${{ number_format($orden->total, 0, ',', '.') }}
                    </td>

                    {{-- Estado --}}
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold
                                     rounded-full px-2.5 py-0.5 {{ $colorMap[$cfg['color']] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dotMap[$cfg['color']] }}"></span>
                            {{ $cfg['label'] }}
                        </span>
                    </td>

                    {{-- Fecha --}}
                    <td class="px-5 py-4 text-gray-500 text-xs whitespace-nowrap">
                        {{ $orden->created_at->format('d/m/Y') }}
                        <span class="text-gray-400">{{ $orden->created_at->format('H:i') }}</span>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endif

@endsection
