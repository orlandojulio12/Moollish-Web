@extends('layouts.marketplace')

@section('title', 'Checkout — Moollish')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8"
     x-data="{
       paso: 1,
       tipoEntrega: 'finca',
       metodoPago: 'transferencia',
       notas: ''
     }">

    {{-- =========================================================
         STEPPER
    ========================================================= --}}
    <div class="max-w-2xl mx-auto mt-8 mb-10">
        <div class="flex items-center justify-center">

            {{-- Paso 1 --}}
            <div class="flex flex-col items-center">
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors"
                    :class="paso > 1 ? 'bg-green-500 text-white' : paso === 1 ? 'text-white' : 'bg-gray-200 text-gray-500'"
                    :style="paso === 1 ? 'background-color:#e49b39' : ''"
                >
                    <span x-show="paso <= 1">1</span>
                    <span x-show="paso > 1" x-cloak>✓</span>
                </div>
                <span class="text-xs mt-1.5 font-medium"
                      :class="paso === 1 ? 'text-amber-600' : paso > 1 ? 'text-green-600' : 'text-gray-400'">
                    Tus datos
                </span>
            </div>

            {{-- Línea 1-2 --}}
            <div class="flex-1 h-0.5 mx-3 mb-5 transition-colors"
                 :class="paso > 1 ? 'bg-green-400' : 'bg-gray-200'"></div>

            {{-- Paso 2 --}}
            <div class="flex flex-col items-center">
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors"
                    :class="paso > 2 ? 'bg-green-500 text-white' : paso === 2 ? 'text-white' : 'bg-gray-200 text-gray-500'"
                    :style="paso === 2 ? 'background-color:#e49b39' : ''"
                >
                    <span x-show="paso <= 2">2</span>
                    <span x-show="paso > 2" x-cloak>✓</span>
                </div>
                <span class="text-xs mt-1.5 font-medium"
                      :class="paso === 2 ? 'text-amber-600' : paso > 2 ? 'text-green-600' : 'text-gray-400'">
                    Entrega
                </span>
            </div>

            {{-- Línea 2-3 --}}
            <div class="flex-1 h-0.5 mx-3 mb-5 transition-colors"
                 :class="paso > 2 ? 'bg-green-400' : 'bg-gray-200'"></div>

            {{-- Paso 3 --}}
            <div class="flex flex-col items-center">
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors"
                    :class="paso > 3 ? 'bg-green-500 text-white' : paso === 3 ? 'text-white' : 'bg-gray-200 text-gray-500'"
                    :style="paso === 3 ? 'background-color:#e49b39' : ''"
                >
                    <span x-show="paso <= 3">3</span>
                    <span x-show="paso > 3" x-cloak>✓</span>
                </div>
                <span class="text-xs mt-1.5 font-medium"
                      :class="paso === 3 ? 'text-amber-600' : paso > 3 ? 'text-green-600' : 'text-gray-400'">
                    Pago
                </span>
            </div>

        </div>
    </div><!-- /stepper -->


    {{-- =========================================================
         PASO 1 — DATOS DEL COMPRADOR
    ========================================================= --}}
    <div x-show="paso === 1" x-cloak>
        <div class="max-w-2xl mx-auto border border-gray-200 rounded-xl p-8 bg-white">

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Datos del comprador</h2>

            <form class="space-y-5">

                {{-- Fila 1: Nombre + Cédula --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                        <input
                            type="text"
                            value="Orlando Julio"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none
                                   focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color: #e49b39;"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cédula / NIT</label>
                        <input
                            type="text"
                            value="1081804258"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none
                                   focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color: #e49b39;"
                        >
                    </div>
                </div>

                {{-- Fila 2: Email + Teléfono --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                        <input
                            type="email"
                            value="orlando@ejemplo.com"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none
                                   focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color: #e49b39;"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono / WhatsApp</label>
                        <input
                            type="tel"
                            value="3001234567"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none
                                   focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color: #e49b39;"
                        >
                    </div>
                </div>

                {{-- Fila 3: Departamento + Municipio --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                        <select
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none
                                   focus:ring-2 focus:border-transparent bg-white"
                            style="--tw-ring-color: #e49b39;"
                        >
                            <option value="">Selecciona...</option>
                            <option>Antioquia</option>
                            <option>Bolívar</option>
                            <option>Boyacá</option>
                            <option>Caldas</option>
                            <option>Casanare</option>
                            <option>Córdoba</option>
                            <option>Cundinamarca</option>
                            <option>Meta</option>
                            <option>Santander</option>
                            <option>Tolima</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                        <input
                            type="text"
                            placeholder="Ej. Villavicencio"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none
                                   focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color: #e49b39;"
                        >
                    </div>
                </div>

                {{-- Botón continuar --}}
                <button
                    type="button"
                    @click="paso = 2"
                    class="w-full py-3 rounded-xl font-bold text-white text-base transition-opacity hover:opacity-90 active:scale-95 mt-2"
                    style="background-color: #e49b39;"
                >
                    Continuar →
                </button>

            </form>
        </div>
    </div><!-- /paso 1 -->


    {{-- =========================================================
         PASO 2 — ENTREGA
    ========================================================= --}}
    <div x-show="paso === 2" x-cloak
         x-data="{ entregaInsumo: 'domicilio' }">
        <div class="max-w-2xl mx-auto border border-gray-200 rounded-xl p-8 bg-white">

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Tipo de entrega</h2>

            {{-- ---- Ganado y lotes ---- --}}
            <p class="font-semibold text-gray-800 mb-3">🐄 Para el ganado y lotes:</p>

            <div class="space-y-3 mb-4">

                {{-- Opción: Recojo en finca --}}
                <label
                    class="flex items-start gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                    :class="tipoEntrega === 'finca' ? 'border-2' : 'border-gray-200 hover:border-gray-300'"
                    :style="tipoEntrega === 'finca' ? 'border-color:#e49b39; background-color:#fffbf5;' : ''"
                >
                    <input type="radio" name="entrega_ganado" value="finca"
                           x-model="tipoEntrega" class="mt-0.5 accent-amber-500">
                    <div>
                        <span class="text-sm font-semibold text-gray-800">Recojo en finca del vendedor</span>
                        <span class="ml-2 text-xs font-semibold text-white bg-green-500 rounded-full px-2 py-0.5">Recomendado</span>
                        <p class="text-xs text-gray-500 mt-0.5">El comprador coordina el transporte del animal desde la finca.</p>
                    </div>
                </label>

                {{-- Opción: Coordinar --}}
                <label
                    class="flex items-start gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                    :class="tipoEntrega === 'coordinar' ? 'border-2' : 'border-gray-200 hover:border-gray-300'"
                    :style="tipoEntrega === 'coordinar' ? 'border-color:#e49b39; background-color:#fffbf5;' : ''"
                >
                    <input type="radio" name="entrega_ganado" value="coordinar"
                           x-model="tipoEntrega" class="mt-0.5 accent-amber-500">
                    <div>
                        <span class="text-sm font-semibold text-gray-800">Coordinar directamente con vendedor</span>
                        <p class="text-xs text-gray-500 mt-0.5">Se habilitará el contacto con el vendedor para acordar la logística.</p>
                    </div>
                </label>
            </div>

            {{-- Info ICA --}}
            <div class="flex items-start gap-3 rounded-lg p-4 mb-6 text-sm"
                 style="background-color:#fff7ed; border:1px solid #fed7aa;">
                <span class="text-base leading-none mt-0.5 flex-shrink-0">📋</span>
                <p style="color:#92400e;">
                    La movilización requiere <span class="font-semibold">guía sanitaria ICA</span>.
                    El vendedor te orientará en el proceso.
                </p>
            </div>

            {{-- ---- Insumos ---- --}}
            <p class="font-semibold text-gray-800 mb-3">📦 Para los insumos:</p>

            <div class="space-y-3 mb-5">

                {{-- Opción: Envío a domicilio --}}
                <label
                    class="flex items-start gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                    :class="entregaInsumo === 'domicilio' ? 'border-2' : 'border-gray-200 hover:border-gray-300'"
                    :style="entregaInsumo === 'domicilio' ? 'border-color:#e49b39; background-color:#fffbf5;' : ''"
                >
                    <input type="radio" name="entrega_insumo" value="domicilio"
                           x-model="entregaInsumo" class="mt-0.5 accent-amber-500">
                    <div class="flex-1 min-w-0">
                        <span class="text-sm font-semibold text-gray-800">Envío a domicilio</span>
                        <p class="text-xs text-gray-500 mt-0.5 mb-2">Estándar 3–5 días hábiles.</p>

                        {{-- Input dirección (aparece al seleccionar) --}}
                        <div x-show="entregaInsumo === 'domicilio'" x-cloak>
                            <input
                                type="text"
                                placeholder="Dirección de entrega"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none
                                       focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color: #e49b39;"
                            >
                        </div>
                    </div>
                </label>

                {{-- Opción: Retiro en punto --}}
                <label
                    class="flex items-start gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                    :class="entregaInsumo === 'retiro' ? 'border-2' : 'border-gray-200 hover:border-gray-300'"
                    :style="entregaInsumo === 'retiro' ? 'border-color:#e49b39; background-color:#fffbf5;' : ''"
                >
                    <input type="radio" name="entrega_insumo" value="retiro"
                           x-model="entregaInsumo" class="mt-0.5 accent-amber-500">
                    <div>
                        <span class="text-sm font-semibold text-gray-800">Retiro en punto del proveedor</span>
                        <p class="text-xs text-gray-500 mt-0.5">Coordina horario directamente con AgroInsumos del Norte.</p>
                    </div>
                </label>
            </div>

            {{-- Notas al vendedor --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Notas al vendedor <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <textarea
                    rows="3"
                    x-model="notas"
                    placeholder="Ej. Prefiero que me contacten por WhatsApp, disponible en la tarde..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none resize-none
                           focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color: #e49b39;"
                ></textarea>
            </div>

            {{-- Botones navegación --}}
            <div class="flex gap-3">
                <button
                    type="button"
                    @click="paso = 1"
                    class="flex-1 py-3 rounded-xl font-semibold text-gray-600 border border-gray-300
                           hover:bg-gray-50 transition-colors text-sm"
                >
                    ← Atrás
                </button>
                <button
                    type="button"
                    @click="paso = 3"
                    class="flex-1 py-3 rounded-xl font-bold text-white text-sm transition-opacity hover:opacity-90 active:scale-95"
                    style="background-color: #e49b39;"
                >
                    Continuar →
                </button>
            </div>

        </div>
    </div><!-- /paso 2 -->


    {{-- =========================================================
         PASO 3 — PAGO
    ========================================================= --}}
    <div x-show="paso === 3" x-cloak>
        <div class="max-w-2xl mx-auto border border-gray-200 rounded-xl p-8 bg-white">

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Confirmar y pagar</h2>

            {{-- ---- Resumen real del carrito ---- --}}
            <div x-data="{ abierto: true }"
                 class="border border-gray-200 rounded-xl overflow-hidden mb-6">
                <button type="button"
                        @click="abierto = !abierto"
                        class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-sm font-semibold text-gray-700">
                    <span>Resumen del pedido ({{ $items->count() }} producto{{ $items->count() !== 1 ? 's' : '' }})</span>
                    <span x-text="abierto ? '▲' : '▼'" class="text-xs text-gray-400"></span>
                </button>

                <div x-show="abierto"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="px-4 pb-4 pt-3 space-y-3">

                    @foreach($items as $item)
                    @php
                      $badge = match($item->tipo) {
                        'animal' => ['🐄 GANADO', '#e49b39'],
                        'lote'   => ['🐄🐄 LOTE', '#2d5a27'],
                        'insumo' => ['🌾 INSUMO', '#7a5230'],
                        default  => ['PRODUCTO', '#6b7280'],
                      };
                    @endphp
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="flex-shrink-0 text-xs font-semibold text-white rounded-full px-2 py-0.5"
                                  style="background-color:{{ $badge[1] }}">{{ $badge[0] }}</span>
                            <span class="text-gray-700 truncate">
                                {{ $item->nombre }}
                                @if($item->cantidad > 1) ×{{ $item->cantidad }}@endif
                            </span>
                        </div>
                        <span class="font-semibold text-gray-800 ml-4 flex-shrink-0">
                            ${{ number_format($item->precio_unitario * $item->cantidad, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach

                    <hr class="border-gray-200">

                    <div class="flex justify-between items-baseline">
                        <span class="font-bold text-gray-900 text-sm">TOTAL</span>
                        <span class="text-xl font-bold" style="color:#e49b39;">
                            ${{ number_format($total, 0, ',', '.') }} COP
                        </span>
                    </div>
                </div>
            </div><!-- /resumen -->

            {{-- ---- Formulario real ---- --}}
            <form method="POST" action="{{ route('marketplace.checkout.procesar') }}">
                @csrf

                {{-- Hidden: valores de paso 2 y 3 enlazados con Alpine --}}
                <input type="hidden" name="tipo_entrega" x-model="tipoEntrega">
                <input type="hidden" name="metodo_pago"  x-model="metodoPago">
                <input type="hidden" name="notas"        x-model="notas">

                {{-- ---- Métodos de pago ---- --}}
                <p class="font-semibold text-gray-800 mb-3 mt-2">Método de pago</p>

                <div class="space-y-3 mb-5">

                    <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                           :class="metodoPago === 'transferencia' ? 'border-2' : 'border-gray-200 hover:border-gray-300'"
                           :style="metodoPago === 'transferencia' ? 'border-color:#e49b39; background-color:#fffbf5;' : ''">
                        <input type="radio" value="transferencia" x-model="metodoPago"
                               class="accent-amber-500 flex-shrink-0">
                        <span class="text-sm font-semibold text-gray-800">🏦 Transferencia bancaria</span>
                    </label>

                    <div x-show="metodoPago === 'transferencia'" x-cloak
                         class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-sm space-y-1.5 text-blue-900">
                        <div class="flex justify-between">
                            <span class="text-blue-600 font-medium">Banco</span>
                            <span class="font-semibold">Bancolombia</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-600 font-medium">Tipo</span>
                            <span>Cuenta Corriente</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-600 font-medium">Número</span>
                            <span class="font-mono font-semibold">123-456789-00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-600 font-medium">NIT</span>
                            <span>900.181.974-1</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-600 font-medium">Titular</span>
                            <span>Moollish SAS</span>
                        </div>
                        <hr class="border-blue-200 my-1">
                        <p class="text-xs text-blue-700">
                            📲 Envía el comprobante por WhatsApp al finalizar tu pedido.
                        </p>
                    </div>

                    <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                           :class="metodoPago === 'pse' ? 'border-2' : 'border-gray-200 hover:border-gray-300'"
                           :style="metodoPago === 'pse' ? 'border-color:#e49b39; background-color:#fffbf5;' : ''">
                        <input type="radio" value="pse" x-model="metodoPago"
                               class="accent-amber-500 flex-shrink-0">
                        <span class="text-sm font-semibold text-gray-800">💳 PSE — Pagos en línea</span>
                    </label>

                    <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                           :class="metodoPago === 'nequi' ? 'border-2' : 'border-gray-200 hover:border-gray-300'"
                           :style="metodoPago === 'nequi' ? 'border-color:#e49b39; background-color:#fffbf5;' : ''">
                        <input type="radio" value="nequi" x-model="metodoPago"
                               class="accent-amber-500 flex-shrink-0">
                        <span class="text-sm font-semibold text-gray-800">📱 Nequi</span>
                    </label>

                    <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                           :class="metodoPago === 'efectivo' ? 'border-2' : 'border-gray-200 hover:border-gray-300'"
                           :style="metodoPago === 'efectivo' ? 'border-color:#e49b39; background-color:#fffbf5;' : ''">
                        <input type="radio" value="efectivo" x-model="metodoPago"
                               class="accent-amber-500 flex-shrink-0">
                        <span class="text-sm font-semibold text-gray-800">💵 Efectivo (coordinar con vendedor)</span>
                    </label>

                </div>

                {{-- ---- Checkboxes ---- --}}
                <div class="mt-6 space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" required class="mt-0.5 accent-amber-500 flex-shrink-0">
                        <span class="text-sm text-gray-700">
                            He leído y acepto los
                            <a href="#" class="underline hover:text-amber-600" style="color:#c4801e;">términos y condiciones</a>
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" required class="mt-0.5 accent-amber-500 flex-shrink-0">
                        <span class="text-sm text-gray-700">
                            Autorizo el
                            <a href="#" class="underline hover:text-amber-600" style="color:#c4801e;">tratamiento de mis datos personales</a>
                        </span>
                    </label>
                </div>

                {{-- ---- Botón confirmar ---- --}}
                <button type="submit"
                        class="w-full mt-6 py-4 rounded-xl font-bold text-xl text-white transition-opacity hover:opacity-90 active:scale-95"
                        style="background-color:#e49b39">
                    ✅ Confirmar pedido
                </button>

            </form>

            {{-- ---- Atrás ---- --}}
            <div class="text-center mt-4">
                <button type="button" @click="paso = 2"
                        class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    ← Atrás
                </button>
            </div>

        </div>
    </div><!-- /paso 3 -->

    {{-- ---- Mini sección de seguridad ---- --}}
    <p class="text-xs text-gray-400 text-center mt-8 mb-4">
        🔒 Pago seguro &nbsp;·&nbsp; Datos protegidos &nbsp;·&nbsp; Transacciones encriptadas
    </p>

</div><!-- /container principal -->

@endsection
