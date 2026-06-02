@extends('layouts.marketplace')

@section('title', 'Pedido Confirmado')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    {{-- Hero --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 mb-4"
             style="animation: confirmCheck .6s cubic-bezier(.17,.67,.32,1.4) both;">
            <svg class="w-14 h-14 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">¡Pedido confirmado!</h1>
        <p class="text-gray-500 mt-1">
            Orden
            <span class="font-semibold text-gray-700">
                #MKT-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}
            </span>
        </p>
    </div>

    {{-- Alerta múltiples órdenes --}}
    @if($ordenesRecientes->count() > 1)
    <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 mb-6">
        <p class="font-semibold text-amber-800 mb-2">
            Se crearon {{ $ordenesRecientes->count() }} órdenes separadas (un pedido por vendedor)
        </p>
        <ul class="space-y-1">
            @foreach($ordenesRecientes as $o)
            <li>
                <a href="{{ route('marketplace.pedido.confirmacion', $o->id) }}"
                   class="text-amber-700 hover:underline font-medium">
                    #MKT-{{ str_pad($o->id, 4, '0', STR_PAD_LEFT) }}
                    — {{ $o->vendedor->perfilMarketplace->nombre_finca ?? $o->vendedor->name ?? 'Vendedor' }}
                    ({{ '$' . number_format($o->total, 0, ',', '.') }} COP)
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Resumen del pedido --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-700">Resumen de la orden</h2>
        </div>

        <ul class="divide-y divide-gray-50">
            @foreach($orden->detalle as $detalle)
            <li class="px-6 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    @php
                        $badge = match($detalle->tipo ?? '') {
                            'animal' => ['GANADO', '#e49b39'],
                            'lote'   => ['LOTE',   '#2d5a27'],
                            'insumo' => ['INSUMO', '#6b7280'],
                            default  => ['ITEM',   '#6b7280'],
                        };
                    @endphp
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full text-white shrink-0"
                          style="background:{{ $badge[1] }}">
                        {{ $badge[0] }}
                    </span>
                    <span class="text-gray-800 truncate">{{ $detalle->nombre_snapshot }}</span>
                    @if(($detalle->cantidad ?? 1) > 1)
                        <span class="text-gray-400 text-sm shrink-0">× {{ $detalle->cantidad }}</span>
                    @endif
                </div>
                <span class="font-semibold text-gray-700 shrink-0">
                    ${{ number_format($detalle->precio_snapshot * ($detalle->cantidad ?? 1), 0, ',', '.') }} COP
                </span>
            </li>
            @endforeach
        </ul>

        <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
            <span class="text-gray-500">Total</span>
            <span class="text-xl font-bold text-gray-800">
                ${{ number_format($orden->total, 0, ',', '.') }} COP
            </span>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-400 block mb-0.5">Método de pago</span>
                <span class="font-medium text-gray-700">
                    @php
                        $pagoLabel = [
                            'transferencia'   => 'Transferencia bancaria',
                            'pse'             => 'PSE',
                            'nequi'           => 'Nequi',
                            'efectivo'        => 'Efectivo',
                        ][$orden->metodo_pago] ?? $orden->metodo_pago;
                    @endphp
                    {{ $pagoLabel }}
                </span>
            </div>
            <div>
                <span class="text-gray-400 block mb-0.5">Tipo de entrega</span>
                <span class="font-medium text-gray-700">
                    @php
                        $entregaLabel = [
                            'finca'            => 'Entrega en finca',
                            'coordinar'        => 'Coordinar con vendedor',
                            'envio_domicilio'  => 'Envío a domicilio',
                            'retiro'           => 'Retiro en punto',
                        ][$orden->tipo_entrega] ?? $orden->tipo_entrega;
                    @endphp
                    {{ $entregaLabel }}
                </span>
            </div>
        </div>
    </div>

    {{-- Instrucciones de pago --}}
    @if($orden->metodo_pago === 'transferencia')
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-6">
        <h3 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Datos para transferencia
        </h3>
        <dl class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm mb-4">
            <div>
                <dt class="text-blue-600">Banco</dt>
                <dd class="font-semibold text-blue-900">Bancolombia</dd>
            </div>
            <div>
                <dt class="text-blue-600">Tipo de cuenta</dt>
                <dd class="font-semibold text-blue-900">Cuenta Corriente</dd>
            </div>
            <div>
                <dt class="text-blue-600">Número de cuenta</dt>
                <dd class="font-semibold text-blue-900">000-000000-00</dd>
            </div>
            <div>
                <dt class="text-blue-600">NIT</dt>
                <dd class="font-semibold text-blue-900">900.000.000-0 (Moollish S.A.S)</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-blue-600">Referencia</dt>
                <dd class="font-semibold text-blue-900">
                    #MKT-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}
                </dd>
            </div>
        </dl>
        <p class="text-blue-700 text-sm font-medium">
            Envía el comprobante de pago al vendedor por WhatsApp para agilizar la confirmación.
        </p>
    </div>
    @elseif($orden->metodo_pago === 'efectivo')
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 mb-6">
        <h3 class="font-semibold text-yellow-800 mb-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Pago en efectivo
        </h3>
        <p class="text-yellow-700 text-sm">
            Coordina con el vendedor el lugar y fecha de pago al momento de la entrega.
            Usa el botón de WhatsApp a continuación para ponerte en contacto.
        </p>
    </div>
    @endif

    {{-- Card del vendedor --}}
    @php
        $vendedor = $orden->vendedor;
        $perfil   = $vendedor?->perfilMarketplace;
        $nombreFinca = $perfil?->nombre_finca ?? $vendedor?->name ?? 'Vendedor';
        $whatsapp    = $perfil?->whatsapp ?? null;
    @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8">
        <h3 class="font-semibold text-gray-700 mb-3">Vendedor</h3>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white text-lg font-bold shrink-0"
                 style="background:#2d5a27">
                {{ strtoupper(substr($nombreFinca, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800">{{ $nombreFinca }}</p>
                @if($perfil?->municipio && $perfil?->departamento)
                <p class="text-sm text-gray-400">{{ $perfil->municipio }}, {{ $perfil->departamento }}</p>
                @endif
            </div>
            @if($whatsapp)
            <a href="https://wa.me/57{{ preg_replace('/\D/', '', $whatsapp) }}?text={{ urlencode('Hola, soy ' . auth()->user()->name . '. Acabo de realizar el pedido #MKT-' . str_pad($orden->id, 4, '0', STR_PAD_LEFT) . ' en Moollish.') }}"
               target="_blank" rel="noopener"
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shrink-0"
               style="background:#25D366">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Contactar por WhatsApp
            </a>
            @endif
        </div>
    </div>

    {{-- Botones de navegación --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('marketplace.mis-pedidos') }}"
           class="flex-1 text-center py-3 rounded-xl border-2 font-semibold text-gray-700 hover:bg-gray-50 transition-colors"
           style="border-color:#2d5a27; color:#2d5a27">
            Ver mis pedidos
        </a>
        <a href="{{ route('marketplace.home') }}"
           class="flex-1 text-center py-3 rounded-xl font-semibold text-white transition-colors"
           style="background:#e49b39">
            Seguir comprando
        </a>
    </div>

</div>

@push('styles')
<style>
@keyframes confirmCheck {
    0%   { opacity: 0; transform: scale(.4); }
    70%  { transform: scale(1.1); }
    100% { opacity: 1; transform: scale(1); }
}
</style>
@endpush
@endsection
