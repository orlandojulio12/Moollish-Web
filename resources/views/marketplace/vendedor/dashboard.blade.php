@extends('layouts.vendor-panel')

@section('title', 'Panel de Vendedor — Moollish')
@section('page-title', 'Mi Dashboard')

@section('content')

        {{-- ---- Header bienvenida ---- --}}
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-900">Bienvenido, {{ $perfil ? $perfil->nombre_finca : auth()->user()->name }} 👋</h2>
        </div>

        @if(!$perfil)
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6 flex items-center gap-3">
            <span class="text-amber-500 text-lg">⚠️</span>
            <span class="text-sm text-amber-800">Completa tu perfil de vendedor para aparecer en el marketplace.</span>
            <a href="{{ url('/marketplace/panel/perfil') }}" class="text-amber-600 font-semibold ml-1 text-sm hover:text-amber-700 transition-colors">
                Completar perfil →
            </a>
        </div>
        @else
        <div class="mb-8"></div>
        @endif

        {{-- ---- 4 Stats cards ---- --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

            {{-- Publicaciones activas --}}
            <div class="bg-amber-50 border border-amber-100 rounded-xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-amber-100">
                        <i class="fa-solid fa-cow text-amber-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up text-[10px]"></i> +2
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['publicaciones_activas'] }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Publicaciones activas</p>
            </div>

            {{-- Ventas este mes --}}
            <div class="bg-green-50 border border-green-100 rounded-xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-green-100">
                        <i class="fa-solid fa-money-bill-wave text-green-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-green-600 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up text-[10px]"></i> +18%
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['ventas_mes'], 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Ventas este mes</p>
            </div>

            {{-- Visitas esta semana --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-100">
                        <i class="fa-solid fa-eye text-blue-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up text-[10px]"></i> +34
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900">342</p>
                <p class="text-sm text-gray-500 mt-0.5">Visitas esta semana</p>
            </div>

            {{-- Ventas completadas --}}
            <div class="bg-purple-50 border border-purple-100 rounded-xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-purple-100">
                        <i class="fa-solid fa-circle-check text-purple-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-purple-600 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up text-[10px]"></i>
                        {{ $stats['ordenes_pendientes'] }} pendientes
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['ventas_completadas'] }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Ventas completadas</p>
            </div>

        </div><!-- /stats -->


        {{-- ---- Tabla: Publicaciones recientes ---- --}}
        <div class="bg-white border border-gray-200 rounded-xl mb-6 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-900 text-base">Mis publicaciones recientes</h2>
                <a href="#" class="text-xs font-semibold transition-colors hover:text-amber-700" style="color:#c4801e;">
                    Ver todas →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        @forelse($publicaciones as $pub)
                        <tr class="hover:bg-gray-50 transition-colors {{ $pub->estado === 'vendido' ? 'opacity-60' : '' }}">
                            <td class="px-6 py-4">
                                <span class="inline-block text-xs font-semibold text-white rounded-full px-2.5 py-0.5
                                    {{ $pub->_tipo === 'animal' ? 'bg-amber-500'
                                       : ($pub->_tipo === 'lote' ? 'bg-green-700' : 'bg-yellow-700') }}">
                                    {{ strtoupper($pub->_tipo) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $pub->_nombre }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-700">${{ number_format($pub->_precio, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="#" class="text-xs font-semibold text-amber-600 hover:text-amber-800 transition-colors">Editar</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="#" class="text-xs font-semibold text-gray-400 hover:text-gray-600 transition-colors">Pausar</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400 py-8 text-sm">
                                No tienes publicaciones aún.
                                <a href="{{ url('/marketplace/panel/publicar') }}"
                                   class="text-amber-600 font-semibold ml-1 hover:text-amber-700 transition-colors">Crear primera →</a>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div><!-- /tabla publicaciones -->


        {{-- ---- Tabla: Últimas ventas ---- --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-900 text-base">Últimas ventas</h2>
                <a href="#" class="text-xs font-semibold transition-colors hover:text-amber-700" style="color:#c4801e;">
                    Ver historial →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Comprador</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        @forelse($ultimasVentas as $venta)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $venta->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $venta->detalle->first()->nombre_snapshot ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $venta->comprador->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">${{ number_format($venta->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($venta->estado === 'entregado')
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ ucfirst($venta->estado) }}
                                </span>
                                @elseif($venta->estado === 'pendiente')
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-yellow-700 bg-yellow-50 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> {{ ucfirst($venta->estado) }}
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 bg-blue-50 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> {{ ucfirst($venta->estado) }}
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400 py-6 text-sm">
                                No hay ventas registradas aún.
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div><!-- /tabla ventas -->

@endsection
