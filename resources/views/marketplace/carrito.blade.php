@extends('layouts.marketplace')

@section('title', 'Tu Carrito — Moollish')

@section('content')

@php
    // Items enriquecidos del DB (solo para usuarios auth)
    $itemsJson = '[]';
    if (auth()->check()) {
        $raw = \App\Models\Marketplace\MkCarrito::where('user_id', auth()->id())->get();
        $controller = new \App\Http\Controllers\Marketplace\CarritoController();
        $itemsJson = json_encode($controller->enriquecer($raw));
    }
@endphp

<div
    class="max-w-6xl mx-auto px-4 py-8"
    x-data="carritoApp()"
    x-init="init()"
>

    {{-- ── HEADER ─────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div class="flex items-baseline gap-3">
            <h1 class="text-3xl font-bold text-gray-900">🛒 Tu Carrito</h1>
            <span class="text-gray-500 text-base font-normal"
                  x-text="'(' + items.length + (items.length === 1 ? ' item)' : ' items)')">
            </span>
        </div>
        <a href="{{ route('marketplace.catalogo') }}"
           class="text-sm font-medium transition-colors hover:text-amber-700"
           style="color:#c4801e;">
            ← Seguir comprando
        </a>
    </div>

    {{-- ── LOADING ─────────────────────────────────────────────── --}}
    <div x-show="loading" class="flex flex-col items-center justify-center py-24 text-gray-400">
        <svg class="animate-spin w-8 h-8 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <p class="text-sm">Cargando carrito...</p>
    </div>

    {{-- ── CARRITO VACÍO ────────────────────────────────────────── --}}
    <div x-show="!loading && items.length === 0" x-cloak
         class="flex flex-col items-center justify-center py-24 text-center">
        <p class="text-6xl mb-4">🛒</p>
        <p class="text-xl font-bold text-gray-800 mb-2">Tu carrito está vacío</p>
        <p class="text-gray-400 text-sm mb-6">Explora el marketplace y agrega animales, lotes o insumos.</p>
        <a href="{{ route('marketplace.catalogo') }}"
           class="px-6 py-3 rounded-xl text-white font-semibold text-sm hover:opacity-90 transition-opacity"
           style="background-color:#e49b39;">
            Ir al catálogo
        </a>
    </div>

    {{-- ── CONTENIDO PRINCIPAL ──────────────────────────────────── --}}
    <div x-show="!loading && items.length > 0" x-cloak>

        {{-- Alerta mezcla ganado + insumos --}}
        <div x-show="tieneMezcla"
             class="flex items-start gap-3 bg-yellow-50 border border-yellow-300 text-yellow-800
                    rounded-xl px-4 py-3 mb-6 text-sm">
            <span class="text-base leading-none mt-0.5 flex-shrink-0">⚠️</span>
            <div>
                <span class="font-semibold">Tienes ganado e insumos en el mismo carrito.</span>
                <span class="block mt-0.5 text-yellow-700">
                    Pueden requerir coordinaciones de entrega separadas.
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ── LISTA DE ITEMS ───────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-3">

                <template x-for="(item, index) in items" :key="item.id">
                    <div class="border border-gray-200 rounded-xl p-4 bg-white">
                        <div class="flex gap-4">

                            {{-- Foto --}}
                            <div class="w-24 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                <template x-if="item.foto_url">
                                    <img :src="item.foto_url" :alt="item.nombre"
                                         class="w-full h-full object-cover">
                                </template>
                                <template x-if="!item.foto_url">
                                    <div class="w-full h-full flex items-center justify-center text-2xl">
                                        <span x-text="item.tipo === 'insumo' ? '🌾' : (item.tipo === 'lote' ? '🐄🐄' : '🐄')"></span>
                                    </div>
                                </template>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">

                                        {{-- Badge tipo --}}
                                        <span class="inline-block text-xs font-bold text-white rounded-full px-2.5 py-0.5 mb-1.5"
                                              :style="item.tipo === 'insumo'
                                                  ? 'background-color:#7a5230'
                                                  : (item.tipo === 'lote' ? 'background-color:#16a34a' : 'background-color:#e49b39')"
                                              x-text="item.tipo === 'insumo' ? '🌾 INSUMO' : (item.tipo === 'lote' ? '🐄🐄 LOTE' : '🐄 GANADO')">
                                        </span>

                                        <p class="font-bold text-gray-900 text-base leading-snug" x-text="item.nombre"></p>

                                        <p class="text-xs text-gray-500 mt-0.5" x-show="item.detalle" x-text="item.detalle"></p>

                                        <p class="text-xs text-gray-400 mt-0.5" x-show="item.vendedor">
                                            Por: <span x-text="item.vendedor"></span>
                                        </p>
                                    </div>

                                    {{-- Botón eliminar --}}
                                    <button @click="quitar(item, index)"
                                            :disabled="quitando === item.id"
                                            class="text-red-400 hover:text-red-600 transition-colors flex-shrink-0 p-1
                                                   disabled:opacity-40"
                                            title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Fila precio / cantidad --}}
                                <div class="flex flex-wrap items-center justify-between gap-3 mt-3">

                                    {{-- Selector cantidad (solo insumos) --}}
                                    <template x-if="item.tipo === 'insumo'">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500">Cantidad:</span>
                                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                                <button @click="cambiarCantidad(item, -1)"
                                                        class="w-8 h-8 flex items-center justify-center text-gray-600
                                                               hover:bg-gray-100 transition-colors font-bold">−</button>
                                                <span class="w-10 h-8 flex items-center justify-center text-sm font-semibold
                                                             border-x border-gray-300"
                                                      x-text="item.cantidad"></span>
                                                <button @click="cambiarCantidad(item, +1)"
                                                        class="w-8 h-8 flex items-center justify-center text-gray-600
                                                               hover:bg-gray-100 transition-colors font-bold">+</button>
                                            </div>
                                            <span class="text-xs text-gray-400">
                                                × $<span x-text="fmtNum(item.precio_unitario)"></span>
                                            </span>
                                        </div>
                                    </template>

                                    {{-- Cantidad fija (animal / lote) --}}
                                    <template x-if="item.tipo !== 'insumo'">
                                        <span class="text-xs text-gray-400 italic">Cantidad fija — 1 unidad</span>
                                    </template>

                                    {{-- Subtotal --}}
                                    <div class="text-right">
                                        <template x-if="item.tipo === 'insumo'">
                                            <p class="text-xs text-gray-400">Subtotal</p>
                                        </template>
                                        <p class="text-lg font-bold" style="color:#e49b39;">
                                            $<span x-text="fmtNum(item.subtotal)"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Botón vaciar carrito --}}
                <div class="flex justify-end pt-1">
                    <button @click="vaciar()"
                            class="text-xs text-red-400 hover:text-red-600 transition-colors flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Vaciar carrito
                    </button>
                </div>

            </div><!-- /lista items -->

            {{-- ── RESUMEN DEL PEDIDO ───────────────────────────── --}}
            <div class="lg:col-span-1">
                <div class="border border-gray-200 rounded-xl p-6 bg-white sticky top-24">

                    <h2 class="font-bold text-xl text-gray-900 mb-4">Resumen del pedido</h2>

                    {{-- Desglose --}}
                    <div class="space-y-2 text-sm">
                        <template x-if="totalGanado > 0">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ganado</span>
                                <span class="font-medium text-gray-800">
                                    $<span x-text="fmtNum(totalGanado)"></span>
                                </span>
                            </div>
                        </template>
                        <template x-if="totalInsumos > 0">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal insumos</span>
                                <span class="font-medium text-gray-800">
                                    $<span x-text="fmtNum(totalInsumos)"></span>
                                </span>
                            </div>
                        </template>
                    </div>

                    <hr class="my-4 border-gray-200">

                    <div class="flex justify-between items-baseline mb-5">
                        <span class="font-bold text-gray-900 text-base">TOTAL</span>
                        <span class="text-2xl font-bold" style="color:#e49b39;">
                            $<span x-text="fmtNum(total)"></span>
                        </span>
                    </div>

                    <hr class="mb-4 border-gray-200">

                    {{-- Info de entregas --}}
                    <div class="bg-gray-50 rounded-lg p-3 mb-4 space-y-2 text-xs text-gray-600 leading-relaxed">
                        <template x-if="totalGanado > 0">
                            <p>🐄 <span class="font-medium">Ganado y lotes:</span> entrega coordinada directamente con el vendedor</p>
                        </template>
                        <template x-if="totalInsumos > 0">
                            <p>📦 <span class="font-medium">Insumos:</span> envío estándar 3–5 días hábiles</p>
                        </template>
                    </div>

                    {{-- CTA según auth --}}
                    @auth
                    <a href="{{ route('marketplace.checkout') }}"
                       class="block w-full py-4 rounded-xl font-bold text-lg text-white text-center
                              transition-opacity hover:opacity-90 active:scale-95"
                       style="background-color:#e49b39;">
                        Proceder al pago →
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                       class="block w-full py-4 rounded-xl font-bold text-lg text-white text-center
                              transition-opacity hover:opacity-90 active:scale-95"
                       style="background-color:#e49b39;">
                        Iniciar sesión para pagar →
                    </a>
                    <p class="text-xs text-gray-500 text-center mt-2">
                        🔒 Tu carrito se guardará al iniciar sesión
                    </p>
                    @endauth

                    {{-- Código de descuento --}}
                    <div class="mt-4" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="text-sm w-full text-left transition-colors hover:text-amber-700"
                                style="color:#c4801e;">
                            ¿Tienes un código de descuento?
                            <span x-text="open ? ' ▲' : ' ▼'" class="text-xs"></span>
                        </button>
                        <div x-show="open" x-cloak x-transition
                             class="mt-3 flex gap-2">
                            <input type="text"
                                   placeholder="Código de descuento"
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none
                                          focus:ring-2 focus:border-transparent"
                                   style="--tw-ring-color:#e49b39;">
                            <button class="px-4 py-2 rounded-lg text-sm font-semibold text-white
                                           transition-opacity hover:opacity-90"
                                    style="background-color:#e49b39;">
                                Aplicar
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>{{-- /contenido principal --}}

</div>

@push('scripts')
<script>
function carritoApp() {
    return {
        items:   [],
        loading: true,
        quitando: null,

        // ── Inicializar ───────────────────────────────────────────
        init() {
            if (MK_AUTH) {
                // Items ya cargados server-side para evitar flash
                this.items   = {!! $itemsJson !!};
                this.loading = false;
            } else {
                this.items   = _mkGetLocalCart();
                this.loading = false;
            }
        },

        // ── Getters ───────────────────────────────────────────────
        get total() {
            return this.items.reduce((s, i) => s + i.precio_unitario * i.cantidad, 0);
        },
        get totalGanado() {
            return this.items
                .filter(i => i.tipo !== 'insumo')
                .reduce((s, i) => s + i.precio_unitario * i.cantidad, 0);
        },
        get totalInsumos() {
            return this.items
                .filter(i => i.tipo === 'insumo')
                .reduce((s, i) => s + i.precio_unitario * i.cantidad, 0);
        },
        get tieneMezcla() {
            const tipos = new Set(this.items.map(i => i.tipo === 'insumo' ? 'insumo' : 'ganado'));
            return tipos.size > 1;
        },

        // ── Quitar item ───────────────────────────────────────────
        async quitar(item, index) {
            this.quitando = item.id;

            if (MK_AUTH) {
                try {
                    await fetch(`${MK_ROUTE.quitarBase}/${item.id}`, {
                        method:  'DELETE',
                        headers: { 'X-CSRF-TOKEN': MK_CSRF },
                    });
                } catch(e) {}
            } else {
                const cart = _mkGetLocalCart().filter(i => i.id !== item.id);
                _mkSetLocalCart(cart);
            }

            this.items.splice(index, 1);
            Alpine.store('cart').count = this.items.length;
            this.quitando = null;
        },

        // ── Cambiar cantidad (solo insumos) ───────────────────────
        async cambiarCantidad(item, delta) {
            const nueva = Math.max(1, item.cantidad + delta);
            if (nueva === item.cantidad) return;

            if (MK_AUTH) {
                try {
                    const res = await fetch(
                        `${MK_ROUTE.quitarBase}/${item.id}/cantidad`,
                        {
                            method:  'PATCH',
                            headers: { 'X-CSRF-TOKEN': MK_CSRF, 'Content-Type': 'application/json' },
                            body:    JSON.stringify({ cantidad: nueva }),
                        }
                    );
                    if (res.ok) {
                        const data = await res.json();
                        item.cantidad = nueva;
                        item.subtotal = data.subtotal;
                    }
                } catch(e) {}
            } else {
                const cart = _mkGetLocalCart();
                const idx  = cart.findIndex(i => i.id === item.id);
                if (idx !== -1) {
                    cart[idx].cantidad = nueva;
                    cart[idx].subtotal = cart[idx].precio_unitario * nueva;
                    _mkSetLocalCart(cart);
                }
                item.cantidad = nueva;
                item.subtotal = item.precio_unitario * nueva;
            }
        },

        // ── Vaciar carrito ────────────────────────────────────────
        async vaciar() {
            if (!confirm('¿Vaciar todo el carrito?')) return;

            if (MK_AUTH) {
                try {
                    await fetch(MK_ROUTE.vaciar, {
                        method:  'DELETE',
                        headers: { 'X-CSRF-TOKEN': MK_CSRF },
                    });
                } catch(e) {}
            } else {
                _mkSetLocalCart([]);
            }

            this.items = [];
            Alpine.store('cart').count = 0;
        },

        // ── Utilidades ────────────────────────────────────────────
        fmtNum(val) {
            return new Intl.NumberFormat('es-CO').format(Math.round(val));
        },
    };
}
</script>
@endpush

@endsection
