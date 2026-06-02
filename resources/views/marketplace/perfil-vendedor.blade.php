@extends('layouts.marketplace')

@section('title', 'Hacienda El Paraíso — Moollish')

@section('content')

    {{-- =========================================================
         BANNER HEADER
    ========================================================= --}}
    <div style="background-color:#2d5a27;" class="py-16">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center sm:items-end gap-6">

            {{-- Avatar --}}
            <img
                src="https://placehold.co/120x120/ffffff/2d5a27?text=HE"
                alt="Hacienda El Paraíso"
                class="w-28 h-28 rounded-full object-cover ring-4 ring-white/30 flex-shrink-0"
            >

            {{-- Info --}}
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mb-2">
                    <h1 class="text-4xl font-bold text-white">Hacienda El Paraíso</h1>
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-white rounded-full px-3 py-1"
                          style="background-color:#e49b39;">
                        ✓ Vendedor verificado
                    </span>
                </div>

                <p class="text-white text-sm mb-1">📍 Montería, Córdoba</p>
                <p class="text-green-200 text-sm">Miembro desde Enero 2023 · 24 productos publicados</p>
            </div>

            {{-- Botón WhatsApp --}}
            <a
                href="https://wa.me/573001234567"
                target="_blank"
                class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-sm text-white bg-green-500 hover:bg-green-600 transition-colors shadow-md"
            >
                <i class="fa-brands fa-whatsapp text-lg"></i>
                Contactar por WhatsApp
            </a>

        </div>
    </div><!-- /banner -->


    {{-- =========================================================
         TABS + GRID DE PRODUCTOS
    ========================================================= --}}
    <div class="max-w-6xl mx-auto px-4" x-data="{ tab: 'ganado' }">

        {{-- Tabs --}}
        <div class="flex gap-1 border-b border-gray-200 mt-8 mb-8">
            <button
                @click="tab = 'ganado'"
                :class="tab === 'ganado'
                    ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                    : 'text-gray-500 hover:text-gray-700'"
                class="px-5 pb-3 text-sm transition-colors"
            >🐄 Ganado <span class="text-xs ml-1 opacity-70">(8)</span></button>

            <button
                @click="tab = 'lotes'"
                :class="tab === 'lotes'
                    ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                    : 'text-gray-500 hover:text-gray-700'"
                class="px-5 pb-3 text-sm transition-colors"
            >🐄🐄 Lotes <span class="text-xs ml-1 opacity-70">(3)</span></button>

            <button
                @click="tab = 'insumos'"
                :class="tab === 'insumos'
                    ? 'border-b-2 border-amber-500 text-amber-600 font-semibold'
                    : 'text-gray-500 hover:text-gray-700'"
                class="px-5 pb-3 text-sm transition-colors"
            >🌾 Insumos <span class="text-xs ml-1 opacity-70">(13)</span></button>
        </div>


        {{-- =================================================
             TAB: GANADO (6 tarjetas)
        ================================================= --}}
        <div x-show="tab === 'ganado'" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                $ganado = [
                    ['id'=>'B-1042','nombre'=>'Toro Brahman Rojo','raza'=>'Brahman','sexo'=>'Macho','peso'=>'480 kg','precio'=>'$5.200.000','color'=>'e49b39'],
                    ['id'=>'V-033', 'nombre'=>'Vaca Holstein Lechera','raza'=>'Holstein','sexo'=>'Hembra','peso'=>'420 kg','precio'=>'$3.800.000','color'=>'5c8a4e'],
                    ['id'=>'N-210', 'nombre'=>'Novilla Angus Negra','raza'=>'Angus','sexo'=>'Hembra','peso'=>'310 kg','precio'=>'$4.100.000','color'=>'2d5a27'],
                    ['id'=>'T-077', 'nombre'=>'Toro Cebú Reproductor','raza'=>'Cebú','sexo'=>'Macho','peso'=>'520 kg','precio'=>'$6.500.000','color'=>'7a5230'],
                    ['id'=>'V-088', 'nombre'=>'Vaca Normanda','raza'=>'Normando','sexo'=>'Hembra','peso'=>'395 kg','precio'=>'$3.400.000','color'=>'9b6b3a'],
                    ['id'=>'N-305', 'nombre'=>'Novilla Brangus','raza'=>'Brangus','sexo'=>'Hembra','peso'=>'280 kg','precio'=>'$3.950.000','color'=>'c4801e'],
                ];
                @endphp

                @foreach($ganado as $g)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="relative">
                        <img
                            src="https://placehold.co/400x220/{{ $g['color'] }}/white?text={{ urlencode($g['raza']) }}"
                            alt="{{ $g['nombre'] }}"
                            class="w-full object-cover group-hover:scale-105 transition-transform duration-300"
                            style="height:180px;"
                        >
                        <span class="absolute top-3 left-3 text-xs font-semibold text-white rounded-full px-2.5 py-0.5"
                              style="background-color:#e49b39;">
                            🐄 GANADO
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-400 mb-0.5">#{{ $g['id'] }} · {{ $g['raza'] }} · {{ $g['sexo'] }}</p>
                        <p class="font-bold text-gray-900 text-base leading-snug mb-1">{{ $g['nombre'] }}</p>
                        <p class="text-xs text-gray-500 mb-3">⚖️ {{ $g['peso'] }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold" style="color:#e49b39;">{{ $g['precio'] }}</span>
                            <a href="#"
                               class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition-colors hover:bg-amber-50"
                               style="border-color:#e49b39; color:#c4801e;">
                                Ver más
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div><!-- /tab ganado -->


        {{-- =================================================
             TAB: LOTES (3 tarjetas)
        ================================================= --}}
        <div x-show="tab === 'lotes'" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                $lotes = [
                    ['nombre'=>'Lote 18 Novillas Holstein','cabezas'=>18,'precioTotal'=>'$81.000.000','precioCabeza'=>'$4.500.000','color'=>'5c8a4e','raza'=>'Holstein'],
                    ['nombre'=>'Lote 8 Terneros Cebú','cabezas'=>8,'precioTotal'=>'$24.000.000','precioCabeza'=>'$3.000.000','color'=>'7a5230','raza'=>'Cebú'],
                    ['nombre'=>'Lote 25 Vacas Brahman','cabezas'=>25,'precioTotal'=>'$112.500.000','precioCabeza'=>'$4.500.000','color'=>'2d5a27','raza'=>'Brahman'],
                ];
                @endphp

                @foreach($lotes as $l)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="relative">
                        <img
                            src="https://placehold.co/400x220/{{ $l['color'] }}/white?text={{ urlencode($l['raza']) }}+%C3%97{{ $l['cabezas'] }}"
                            alt="{{ $l['nombre'] }}"
                            class="w-full object-cover group-hover:scale-105 transition-transform duration-300"
                            style="height:180px;"
                        >
                        <span class="absolute top-3 left-3 text-xs font-semibold text-white rounded-full px-2.5 py-0.5 bg-green-600">
                            🐄🐄 LOTE
                        </span>
                        <span class="absolute top-3 right-3 text-xs font-bold text-white bg-black/40 rounded-full px-2 py-0.5">
                            {{ $l['cabezas'] }} cabezas
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="font-bold text-gray-900 text-base leading-snug mb-1">{{ $l['nombre'] }}</p>
                        <p class="text-xs text-gray-500 mb-1">{{ $l['precioCabeza'] }} / cabeza</p>
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                <p class="text-xs text-gray-400">Precio total</p>
                                <span class="text-lg font-bold" style="color:#e49b39;">{{ $l['precioTotal'] }}</span>
                            </div>
                            <a href="#"
                               class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition-colors hover:bg-amber-50"
                               style="border-color:#e49b39; color:#c4801e;">
                                Ver lote
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div><!-- /tab lotes -->


        {{-- =================================================
             TAB: INSUMOS (6 tarjetas)
        ================================================= --}}
        <div x-show="tab === 'insumos'" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                $insumos = [
                    ['nombre'=>'Sales Mineralizadas Premium 25kg','marca'=>'Procampo','precio'=>'$89.000','stock'=>48,'color'=>'7a5230','cat'=>'Minerales'],
                    ['nombre'=>'Vacuna Aftosa Dosis 50ml','marca'=>'Vecol','precio'=>'$18.500','stock'=>120,'color'=>'2d5a27','cat'=>'Medicamentos'],
                    ['nombre'=>'Concentrado Engorde 40kg','marca'=>'Italcol','precio'=>'$120.000','stock'=>30,'color'=>'5c8a4e','cat'=>'Alimentos'],
                    ['nombre'=>'Desparasitante Ivermectina 500ml','marca'=>'Virbac','precio'=>'$45.000','stock'=>60,'color'=>'9b6b3a','cat'=>'Medicamentos'],
                    ['nombre'=>'Sal Mineral Bloque 10kg','marca'=>'Procampo','precio'=>'$32.000','stock'=>85,'color'=>'c4801e','cat'=>'Minerales'],
                    ['nombre'=>'Comedero Plástico 20L','marca'=>'AgroTec','precio'=>'$75.000','stock'=>15,'color'=>'e49b39','cat'=>'Equipos'],
                ];
                @endphp

                @foreach($insumos as $i)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="relative">
                        <img
                            src="https://placehold.co/400x200/{{ $i['color'] }}/white?text={{ urlencode($i['cat']) }}"
                            alt="{{ $i['nombre'] }}"
                            class="w-full object-cover group-hover:scale-105 transition-transform duration-300"
                            style="height:160px;"
                        >
                        <span class="absolute top-3 left-3 text-xs font-semibold text-white rounded-full px-2.5 py-0.5"
                              style="background-color:#7a5230;">
                            🌾 {{ strtoupper($i['cat']) }}
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-amber-600 font-medium mb-0.5">{{ $i['marca'] }}</p>
                        <p class="font-bold text-gray-900 text-sm leading-snug mb-1">{{ $i['nombre'] }}</p>
                        <p class="text-xs text-green-600 mb-3">✅ {{ $i['stock'] }} unidades disponibles</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold" style="color:#e49b39;">{{ $i['precio'] }}</span>
                            <a href="#"
                               class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition-colors hover:bg-amber-50"
                               style="border-color:#e49b39; color:#c4801e;">
                                Ver más
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div><!-- /tab insumos -->


        {{-- =========================================================
             SECCIÓN CONTACTO
        ========================================================= --}}
        <div class="mt-10 mb-12 border border-amber-200 bg-amber-50 rounded-xl p-6 flex flex-col sm:flex-row items-start sm:items-center gap-5">

            <div class="flex-1 min-w-0">
                <p class="font-semibold text-amber-900 mb-1">Para consultas sobre entrega o negociación:</p>
                <p class="text-xs text-amber-700 leading-relaxed">
                    Las transacciones formales se gestionan dentro de la plataforma Moollish para tu seguridad y la del vendedor.
                </p>
            </div>

            <a
                href="https://wa.me/573001234567"
                target="_blank"
                class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-sm text-white bg-green-500 hover:bg-green-600 transition-colors shadow-sm"
            >
                <i class="fa-brands fa-whatsapp text-lg"></i>
                Contactar por WhatsApp
            </a>

        </div><!-- /contacto -->

    </div><!-- /max-w-6xl tabs container -->

@endsection
