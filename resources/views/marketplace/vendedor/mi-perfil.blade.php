@extends('layouts.vendor-panel')
@section('title', 'Mi Perfil de Vendedor')
@section('page-title', 'Mi Perfil de Vendedor')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- ── Avatar + datos básicos ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 flex items-center gap-5">
        <div class="w-20 h-20 rounded-full flex items-center justify-center
                    text-white font-bold text-2xl flex-shrink-0"
             style="background-color:#e49b39;">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-xl text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
            @if(auth()->user()->celular)
                <p class="text-sm text-gray-500 mt-0.5">📱 {{ auth()->user()->celular }}</p>
            @endif
            @if($perfil->exists && $perfil->verificado)
                <span class="inline-block mt-2 text-xs font-semibold px-2.5 py-0.5
                             rounded-full bg-green-100 text-green-700">
                    ✓ Vendedor verificado
                </span>
            @endif
        </div>
    </div>

    @if($predios->isNotEmpty())

        {{-- ══════════════════════════════════════════
             USUARIO CON PREDIOS: mostrar info, solo editar descripción
        ══════════════════════════════════════════ --}}

        {{-- Cards de predios --}}
        <div>
            <h2 class="text-base font-bold text-gray-900 mb-3">
                Mis predios registrados
                <span class="text-sm font-normal text-gray-400">({{ $predios->count() }})</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($predios as $predio)
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center
                                    justify-center flex-shrink-0">
                            <i class="fa-solid fa-tractor text-amber-500"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-gray-900 text-sm leading-snug">
                                {{ $predio->nombre_predio }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                📍 {{ $predio->municipio }}, {{ $predio->departamento }}
                            </p>
                            @if($predio->vereda)
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Vereda: {{ $predio->vereda }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($predio->cod_predio)
                        <p class="text-xs text-gray-400 mt-3 font-mono">
                            Código: {{ $predio->cod_predio }}
                        </p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Formulario: solo descripción + whatsapp --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-1">
                Información de venta
            </h2>
            <p class="text-sm text-gray-500 mb-5">
                Cuéntales a los compradores sobre tu ganadería y cómo contactarte.
            </p>

            <form method="POST" action="{{ route('marketplace.panel.perfil.update') }}">
                @csrf
                <div class="space-y-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            WhatsApp de contacto
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                <i class="fa-brands fa-whatsapp text-green-500"></i>
                            </span>
                            <input type="text" name="whatsapp"
                                   value="{{ old('whatsapp', $perfil->whatsapp) }}"
                                   placeholder="Ej: 3001234567"
                                   class="w-full pl-9 border border-gray-300 rounded-xl px-4 py-2.5
                                          text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="--tw-ring-color:#e49b39;">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Descripción de tu ganadería
                        </label>
                        <textarea name="descripcion" rows="5"
                                  placeholder="Cuéntale a los compradores sobre tu ganadería: razas que manejas, años de experiencia, certificaciones, etc."
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5
                                         text-sm focus:outline-none focus:ring-2 focus:border-transparent resize-none"
                                  style="--tw-ring-color:#e49b39;">{{ old('descripcion', $perfil->descripcion) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">
                            Esta descripción aparecerá en tu perfil público del marketplace.
                        </p>
                    </div>

                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl text-white font-semibold text-sm
                                   hover:opacity-90 transition-opacity"
                            style="background-color:#e49b39;">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

    @else

        {{-- ══════════════════════════════════════════
             USUARIO SIN PREDIOS: formulario completo
        ══════════════════════════════════════════ --}}

        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-start gap-3">
            <span class="text-amber-500 text-lg flex-shrink-0">⚠️</span>
            <div>
                <p class="text-sm font-semibold text-amber-800">No tienes predios registrados</p>
                <p class="text-xs text-amber-700 mt-0.5">
                    Completa tu información de vendedor para aparecer en el marketplace.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-5">Información del vendedor</h2>

            <form method="POST" action="{{ route('marketplace.panel.perfil.update') }}">
                @csrf
                <div class="space-y-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Nombre de la finca / hacienda *
                        </label>
                        <input type="text" name="nombre_finca"
                               value="{{ old('nombre_finca', $perfil->nombre_finca) }}"
                               placeholder="Ej: Hacienda El Paraíso"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5
                                      text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#e49b39;" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Departamento *
                            </label>
                            <select name="departamento"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5
                                           text-sm focus:outline-none" required>
                                <option value="">Selecciona...</option>
                                @foreach(['Antioquia','Atlántico','Bolívar','Boyacá','Caldas',
                                          'Caquetá','Casanare','Cauca','Cesar','Córdoba',
                                          'Cundinamarca','Huila','La Guajira','Magdalena','Meta',
                                          'Nariño','Norte de Santander','Putumayo','Quindío',
                                          'Risaralda','Santander','Sucre','Tolima',
                                          'Valle del Cauca','Vichada'] as $dpto)
                                <option value="{{ $dpto }}"
                                    {{ old('departamento', $perfil->departamento) === $dpto ? 'selected' : '' }}>
                                    {{ $dpto }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Municipio *
                            </label>
                            <input type="text" name="municipio"
                                   value="{{ old('municipio', $perfil->municipio) }}"
                                   placeholder="Ej: Montería"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5
                                          text-sm focus:outline-none" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            WhatsApp de contacto
                        </label>
                        <input type="text" name="whatsapp"
                               value="{{ old('whatsapp', $perfil->whatsapp) }}"
                               placeholder="Ej: 3001234567"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5
                                      text-sm focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Descripción de tu ganadería
                        </label>
                        <textarea name="descripcion" rows="4"
                                  placeholder="Cuéntale a los compradores sobre tu ganadería..."
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5
                                         text-sm focus:outline-none resize-none">{{ old('descripcion', $perfil->descripcion) }}</textarea>
                    </div>

                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl text-white font-semibold text-sm
                                   hover:opacity-90 transition-opacity"
                            style="background-color:#e49b39;">
                        Guardar perfil
                    </button>
                </div>
            </form>
        </div>

    @endif

</div>
@endsection
