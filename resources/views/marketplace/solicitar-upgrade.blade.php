@extends('layouts.marketplace')
@section('content')

<div class="max-w-lg mx-auto py-16 px-4">

    @if($solicitudPendiente)
    {{-- Ya tiene solicitud pendiente --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center">
        <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center
                    justify-center text-3xl mx-auto mb-4">⏳</div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">
            Solicitud en revisión
        </h2>
        <p class="text-gray-500 text-sm mb-6">
            Enviaste tu solicitud el
            {{ $solicitudPendiente->created_at->format('d/m/Y') }}.
            El equipo de Moollish te notificará pronto.
        </p>
        <div class="bg-amber-50 rounded-xl p-4 text-left">
            <p class="text-xs font-semibold text-amber-800 mb-1">Tu mensaje:</p>
            <p class="text-sm text-amber-700">
                {{ $solicitudPendiente->mensaje ?? 'Sin mensaje adicional.' }}
            </p>
        </div>
        <a href="{{ route('marketplace.home') }}"
           class="mt-6 inline-block px-6 py-3 rounded-xl text-white font-semibold text-sm"
           style="background-color:#e49b39">
            Volver al marketplace
        </a>
    </div>

    @else
    {{-- Formulario de solicitud --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-8">

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center
                        justify-center text-3xl mx-auto mb-4">🐄</div>
            <h2 class="text-2xl font-bold text-gray-900">
                Conviértete en proveedor
            </h2>
            <p class="text-gray-500 text-sm mt-2">
                Publica y vende ganado, lotes e insumos en el marketplace de Moollish.
            </p>
        </div>

        {{-- Beneficios --}}
        <div class="bg-amber-50 rounded-xl p-4 mb-6 space-y-2">
            <p class="text-xs font-bold text-amber-800 mb-3">
                ¿Qué puedes hacer como proveedor?
            </p>
            @foreach([
                'Publicar animales individuales con ficha completa',
                'Crear lotes de venta con precio por cabeza o total',
                'Vender insumos ganaderos',
                'Gestionar tus publicaciones desde tu panel',
                'Recibir pagos por PSE, Nequi o transferencia',
            ] as $beneficio)
            <div class="flex items-center gap-2 text-sm text-amber-800">
                <span class="text-green-500 font-bold">✓</span>
                {{ $beneficio }}
            </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('marketplace.guardar.upgrade') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    ¿Qué planeas vender? (opcional)
                </label>
                <textarea name="mensaje" rows="3"
                    placeholder="Ej: Tengo 50 cabezas de Brahman en Córdoba y quiero venderlas..."
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm
                           focus:outline-none resize-none"
                    maxlength="500">{{ old('mensaje') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Máximo 500 caracteres</p>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl text-white font-bold text-sm
                           hover:opacity-90 transition-opacity"
                    style="background-color:#e49b39">
                Enviar solicitud
            </button>

            <p class="text-xs text-gray-400 text-center mt-4">
                El equipo de Moollish revisará tu solicitud en 24-48 horas hábiles.
            </p>
        </form>

    </div>
    @endif

</div>

@endsection
