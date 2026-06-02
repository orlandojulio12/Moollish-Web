@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Solicitudes de upgrade — Marketplace</h2>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800
                    rounded-xl px-4 py-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">#</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Usuario</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Solicita</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Mensaje</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Fecha</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Estado</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($solicitudes as $sol)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">#{{ $sol->id }}</td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-900">{{ $sol->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $sol->user->email }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                     bg-amber-100 text-amber-800">
                            {{ $sol->nombre_rol_solicitado }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs">
                        <p class="truncate">{{ $sol->mensaje ?? '—' }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs">
                        {{ $sol->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $sol->estado === 'pendiente'  ? 'bg-yellow-100 text-yellow-800' :
                               ($sol->estado === 'aprobado'  ? 'bg-green-100 text-green-700'  :
                                                               'bg-red-100 text-red-700') }}">
                            {{ ucfirst($sol->estado) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($sol->estado === 'pendiente')
                        <div class="flex gap-2">
                            <form method="POST"
                                  action="{{ route('admin.upgrades.responder', $sol->id) }}">
                                @csrf
                                <input type="hidden" name="decision" value="aprobado">
                                <button type="submit"
                                        class="px-3 py-1 rounded-lg text-xs font-semibold
                                               bg-green-100 text-green-700 hover:bg-green-200">
                                    Aprobar
                                </button>
                            </form>
                            <form method="POST"
                                  action="{{ route('admin.upgrades.responder', $sol->id) }}">
                                @csrf
                                <input type="hidden" name="decision" value="rechazado">
                                <button type="submit"
                                        class="px-3 py-1 rounded-lg text-xs font-semibold
                                               bg-red-100 text-red-700 hover:bg-red-200">
                                    Rechazar
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-xs text-gray-400">
                                Respondido {{ $sol->respondido_at?->format('d/m/Y') }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400">
                        No hay solicitudes pendientes.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
