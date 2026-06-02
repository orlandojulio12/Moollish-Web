@extends('layouts.vendor-panel')

@section('title', 'Nueva Publicación — Moollish')
@section('page-title', 'Nueva Publicación')

@section('content')

        {{-- ---- Descripción ---- --}}
        <p class="text-sm text-gray-500 mb-8">Selecciona el tipo y completa la información del producto.</p>

        {{-- =========================================================
             FORMULARIO CON TABS Alpine.js
        ========================================================= --}}
        <div x-data="{ tipo: 'animal' }">

            {{-- ---- Selector de tabs ---- --}}
            <div class="flex flex-wrap gap-3 mb-8">
                <button
                    type="button"
                    @click="tipo = 'animal'"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                    :class="tipo === 'animal'
                        ? 'text-white shadow-md'
                        : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300'"
                    :style="tipo === 'animal' ? 'background-color:#e49b39;' : ''"
                >
                    🐄 Animal Individual
                </button>

                <button
                    type="button"
                    @click="tipo = 'lote'"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                    :class="tipo === 'lote'
                        ? 'text-white shadow-md'
                        : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300'"
                    :style="tipo === 'lote' ? 'background-color:#e49b39;' : ''"
                >
                    🐄🐄 Lote de Animales
                </button>

                <button
                    type="button"
                    @click="tipo = 'insumo'"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                    :class="tipo === 'insumo'
                        ? 'text-white shadow-md'
                        : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300'"
                    :style="tipo === 'insumo' ? 'background-color:#e49b39;' : ''"
                >
                    🌾 Insumo
                </button>
            </div>


            {{-- =================================================
                 TAB: ANIMAL INDIVIDUAL
            ================================================= --}}
            <div x-show="tipo==='animal'"
                 x-data="{
                   modo: '{{ $esMoollish && $animalesDisponibles->count() > 0 ? 'sistema' : 'manual' }}',
                   animalSeleccionado: null,
                   animales: {
                     @foreach($animalesDisponibles as $a)
                     '{{ $a->id_animal }}': {
                       nombre: '{{ addslashes($a->nombre ?? '') }}',
                       raza: '{{ addslashes($a->raza ?? '') }}',
                       sexo: '{{ $a->sexo ?? '' }}',
                       peso: '{{ $a->peso ?? '' }}',
                       municipio: '{{ addslashes($a->municipio ?? '') }}',
                       departamento: '{{ addslashes($a->departamento ?? '') }}',
                       finca: '{{ addslashes($a->nombre_predio ?? '') }}'
                     },
                     @endforeach
                   }
                 }">

              <form method="POST"
                    action="{{ route('marketplace.panel.publicar.animal') }}"
                    enctype="multipart/form-data"
                    class="bg-white border border-gray-200 rounded-xl p-8">
                @csrf

                {{-- SELECTOR DE MODO (solo si es usuario Moollish CON animales) --}}
                @if($esMoollish && $animalesDisponibles->count() > 0)
                <div class="flex gap-3 mb-6">

                  <div @click="modo='sistema'; animalSeleccionado=null"
                       :class="modo==='sistema'
                         ? 'border-2 bg-amber-50'
                         : 'border border-gray-200 hover:border-amber-300'"
                       class="cursor-pointer rounded-xl p-4 flex-1 transition-all"
                       style="border-color: modo==='sistema' ? '#e49b39' : ''">
                    <p class="font-semibold text-sm text-gray-900">🐄 Desde mi hato</p>
                    <p class="text-xs text-gray-500 mt-1">
                      Publica un animal que ya tienes registrado en Moollish.
                    </p>
                  </div>

                  <div @click="modo='manual'; animalSeleccionado=null"
                       :class="modo==='manual'
                         ? 'border-2 bg-amber-50'
                         : 'border border-gray-200 hover:border-amber-300'"
                       class="cursor-pointer rounded-xl p-4 flex-1 transition-all">
                    <p class="font-semibold text-sm text-gray-900">✏️ Animal externo</p>
                    <p class="text-xs text-gray-500 mt-1">
                      Publica un animal que no está en tu hato registrado.
                    </p>
                  </div>

                </div>
                @endif

                {{-- Input hidden para origen --}}
                <input type="hidden" name="origen"
                       :value="modo === 'sistema' ? 'sistema' : 'manual'">

                {{-- ── MODO SISTEMA: select de animales del hato ── --}}
                <div x-show="modo==='sistema'">
                  <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                      Selecciona el animal *
                    </label>
                    <select name="animal_id"
                            @change="animalSeleccionado = animales[$event.target.value] || null"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none"
                            :required="modo==='sistema'">
                      <option value="">-- Elige un animal de tu hato --</option>
                      @foreach($animalesDisponibles as $a)
                      <option value="{{ $a->id_animal }}">
                        {{ $a->nombre ?? 'Sin nombre' }}
                        — {{ $a->raza }}
                        — {{ $a->sexo }}
                        — Código: {{ $a->codigo }}
                        — {{ $a->nombre_predio }}
                      </option>
                      @endforeach
                    </select>
                  </div>

                  {{-- Preview datos del animal seleccionado --}}
                  <div x-show="animalSeleccionado"
                       class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                    <p class="text-xs font-semibold text-amber-800 mb-2">
                      Datos del animal (importados automáticamente)
                    </p>
                    <div class="grid grid-cols-3 gap-2 text-xs text-amber-700">
                      <span>🐄 Raza: <strong x-text="animalSeleccionado?.raza"></strong></span>
                      <span>⚖️ Peso: <strong x-text="animalSeleccionado?.peso + ' kg'"></strong></span>
                      <span>👤 Sexo: <strong x-text="animalSeleccionado?.sexo"></strong></span>
                      <span>📍 <strong x-text="animalSeleccionado?.municipio + ', ' + animalSeleccionado?.departamento"></strong></span>
                      <span>🏡 <strong x-text="animalSeleccionado?.finca"></strong></span>
                    </div>
                  </div>
                </div>

                {{-- ── MODO MANUAL: campos libres ── --}}
                <div x-show="modo==='manual'">
                  <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-4 text-xs text-blue-700">
                    ℹ️ Ingresa los datos del animal que deseas vender. No necesitas tenerlo registrado en Moollish.
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nombre / identificación *
                      </label>
                      <input type="text" name="m_nombre"
                             placeholder="Ej: Toro Brahman #B-1042"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none"
                             :required="modo==='manual'">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">Raza</label>
                      <select name="m_raza"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                        <option value="">Seleccionar raza</option>
                        @foreach(['Angus','Ayrshire','Blanco orejinegro','Brangus','Brahman',
                                  'Charolais','Cebú comercial','Costeño con cuernos','Guzerat',
                                  'Gyr','Gyr-Jersey','Gyrolanda','Harton del Valle','Holstein negro',
                                  'Holstein rojo','Jersey','Limousin','Murrah','Nelore','Normando',
                                  'Pardo Suizo','Romosinuano','Simmental','Otra'] as $raza)
                        <option value="{{ $raza }}">{{ $raza }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">Sexo *</label>
                      <select name="m_sexo"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm"
                              :required="modo==='manual'">
                        <option value="">Seleccionar</option>
                        <option value="macho">Macho</option>
                        <option value="hembra">Hembra</option>
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Peso aproximado (kg)
                      </label>
                      <input type="number" name="m_peso_kg"
                             placeholder="Ej: 480"
                             min="1" max="2000"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">Edad — años</label>
                      <input type="number" name="m_edad_anos"
                             placeholder="Ej: 3" min="0" max="30"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">Edad — meses</label>
                      <input type="number" name="m_edad_meses"
                             placeholder="Ej: 2" min="0" max="11"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">Color</label>
                      <input type="text" name="m_color"
                             placeholder="Ej: Rojo colorado"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Hierro / marca / arete
                      </label>
                      <input type="text" name="m_hierro"
                             placeholder="Ej: B-1042"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        ID SINIGAN (opcional)
                      </label>
                      <input type="text" name="m_id_sinigan"
                             placeholder="Número SINIGAN"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">Departamento *</label>
                      <select name="m_departamento"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm"
                              :required="modo==='manual'">
                        <option value="">Seleccionar</option>
                        @foreach(['Antioquia','Atlántico','Bolívar','Boyacá','Caldas','Caquetá',
                                  'Casanare','Cauca','Cesar','Córdoba','Cundinamarca','Huila',
                                  'La Guajira','Magdalena','Meta','Nariño','Norte de Santander',
                                  'Putumayo','Quindío','Risaralda','Santander','Sucre','Tolima',
                                  'Valle del Cauca','Vichada'] as $dpto)
                        <option value="{{ $dpto }}">{{ $dpto }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">Municipio *</label>
                      <input type="text" name="m_municipio"
                             placeholder="Ej: Montería"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none"
                             :required="modo==='manual'">
                    </div>
                  </div>
                </div>

                {{-- ── CAMPOS COMUNES (aplican para ambos modos) ── --}}
                <div class="border-t border-gray-200 mt-6 pt-6 grid grid-cols-2 gap-4">

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Precio de venta (COP) *
                    </label>
                    <input type="number" name="precio_venta"
                           placeholder="Ej: 5200000"
                           min="0" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Propósito *
                    </label>
                    <select name="proposito" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                      <option value="">Seleccionar</option>
                      <option value="engorde">Engorde</option>
                      <option value="leche">Leche</option>
                      <option value="reproduccion">Reproducción</option>
                      <option value="sacrificio">Listo para sacrificio</option>
                      <option value="cria">Cría</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Categoría del animal
                    </label>
                    <select name="categoria_animal"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                      <option value="">Seleccionar</option>
                      <option value="Toro adulto">Toro adulto</option>
                      <option value="Torete">Torete</option>
                      <option value="Vaca de vientre">Vaca de vientre</option>
                      <option value="Novilla">Novilla</option>
                      <option value="Ternero">Ternero</option>
                      <option value="Ternera">Ternera</option>
                      <option value="Buey">Buey</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Condición corporal (1-5)
                    </label>
                    <select name="condicion_corporal"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                      <option value="">Seleccionar</option>
                      <option value="1">1 — Muy delgado</option>
                      <option value="2">2 — Delgado</option>
                      <option value="3">3 — Normal</option>
                      <option value="4">4 — Buen estado</option>
                      <option value="5">5 — Excelente</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Temperamento</label>
                    <select name="temperamento"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                      <option value="">Seleccionar</option>
                      <option value="Manso">Manso</option>
                      <option value="Nervioso">Nervioso</option>
                      <option value="Bravo">Bravo</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Estado sanitario</label>
                    <select name="estado_sanitario"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                      <option value="Al día">Al día</option>
                      <option value="Pendiente">Pendiente</option>
                    </select>
                  </div>

                  <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="castrado" value="1"
                           id="castrado" class="w-4 h-4">
                    <label for="castrado" class="text-sm font-medium text-gray-700">
                      Animal castrado
                    </label>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Procedencia
                    </label>
                    <select name="procedencia"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                      <option value="Ganadería propia">Ganadería propia</option>
                      <option value="Comprado">Comprado</option>
                      <option value="Subasta">Subasta</option>
                      <option value="Otro">Otro</option>
                    </select>
                  </div>

                  <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Marcas y señales
                    </label>
                    <input type="text" name="marcas_senales"
                           placeholder="Ej: Chapeta izquierda #042, hierro en anca"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                  </div>

                  <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Video YouTube (opcional)
                    </label>
                    <input type="url" name="video_youtube"
                           placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1">
                      Sube el video a tu canal de YouTube y pega el enlace aquí.
                    </p>
                  </div>

                  <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                      Fotos del animal (máximo 3)
                    </label>
                    <input type="file" name="fotos[]"
                           accept="image/jpeg,image/png,image/webp"
                           multiple
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                    <p class="text-xs text-gray-400 mt-1">
                      JPG, PNG o WebP. Máximo 5MB por foto.
                    </p>
                  </div>

                </div>

                <div class="mt-6">
                  <button type="submit"
                          class="w-full py-3 rounded-xl text-white font-bold text-sm
                                 hover:opacity-90 transition-opacity"
                          style="background-color:#e49b39">
                    Publicar Animal
                  </button>
                </div>

              </form>
            </div><!-- /tab animal -->


            {{-- =================================================
                 TAB: LOTE DE ANIMALES
            ================================================= --}}
            <div x-show="tipo === 'lote'" x-cloak
                 x-data="{
                   modo: '{{ $esMoollish && $lotesInternos->count() > 0 ? 'sistema' : 'manual' }}',
                   lotePreview: null,
                   loadingLote: false,
                   numAnimales: 0,
                   precio: 0,
                   precioCabeza: 0,
                   precioTipo: 'por_cabeza',
                   get calculado() {
                     if (this.precioTipo === 'por_cabeza') {
                       return this.numAnimales > 0
                         ? 'Total lote: $' + (this.precio * this.numAnimales).toLocaleString('es-CO') + ' COP'
                         : '';
                     } else {
                       return this.numAnimales > 0
                         ? 'Por cabeza: $' + Math.round(this.precio / this.numAnimales).toLocaleString('es-CO') + ' COP'
                         : '';
                     }
                   },
                   async cargarLote(id) {
                     if (!id) { this.lotePreview = null; return; }
                     this.loadingLote = true;
                     try {
                       const r = await fetch('/marketplace/panel/lote-interno/' + id + '/datos', {
                         headers: { 'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest' }
                       });
                       this.lotePreview = await r.json();
                       this.numAnimales = this.lotePreview.num_animales;
                     } catch(e) { this.lotePreview = null; }
                     this.loadingLote = false;
                   }
                 }">

              <form method="POST"
                    action="{{ route('marketplace.panel.publicar.lote') }}"
                    enctype="multipart/form-data"
                    class="bg-white border border-gray-200 rounded-xl p-8">
                @csrf

                {{-- SELECTOR DE MODO --}}
                @if($esMoollish && $lotesInternos->count() > 0)
                <div class="flex gap-3 mb-6">
                  <div @click="modo='sistema'; lotePreview=null"
                       :class="modo==='sistema' ? 'border-2 bg-amber-50' : 'border border-gray-200 hover:border-amber-300'"
                       class="cursor-pointer rounded-xl p-4 flex-1 transition-all">
                    <p class="font-semibold text-sm text-gray-900">🐄🐄 Desde mis lotes</p>
                    <p class="text-xs text-gray-500 mt-1">Importa un lote que ya tienes registrado en Moollish.</p>
                  </div>
                  <div @click="modo='manual'; lotePreview=null"
                       :class="modo==='manual' ? 'border-2 bg-amber-50' : 'border border-gray-200 hover:border-amber-300'"
                       class="cursor-pointer rounded-xl p-4 flex-1 transition-all">
                    <p class="font-semibold text-sm text-gray-900">✏️ Lote externo</p>
                    <p class="text-xs text-gray-500 mt-1">Publica un lote sin registros previos en Moollish.</p>
                  </div>
                </div>
                @endif

                <input type="hidden" name="origen" :value="modo">

                {{-- ── MODO SISTEMA ── --}}
                <div x-show="modo==='sistema'">
                  <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                      Selecciona el lote *
                    </label>
                    <select name="lote_interno_id"
                            @change="cargarLote($event.target.value)"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none"
                            :required="modo==='sistema'">
                      <option value="">-- Elige un lote de tu hato --</option>
                      @foreach($lotesInternos as $lote)
                      <option value="{{ $lote->id }}">
                        {{ $lote->nombre }} — {{ $lote->nombre_predio }}
                      </option>
                      @endforeach
                    </select>
                  </div>

                  {{-- Spinner --}}
                  <div x-show="loadingLote" class="flex items-center gap-2 text-amber-600 text-sm mb-4">
                    <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    Cargando datos del lote...
                  </div>

                  {{-- Preview del lote --}}
                  <div x-show="lotePreview && !loadingLote"
                       class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                    <p class="text-xs font-semibold text-amber-800 mb-3">
                      Datos calculados del lote (importados automáticamente)
                    </p>
                    <div class="grid grid-cols-3 gap-3 text-xs text-amber-700 mb-3">
                      <div class="text-center">
                        <p class="text-amber-500">Animales</p>
                        <p class="font-bold text-lg text-amber-800" x-text="lotePreview?.num_animales"></p>
                      </div>
                      <div class="text-center">
                        <p class="text-amber-500">Peso prom.</p>
                        <p class="font-bold text-lg text-amber-800">
                          <span x-text="lotePreview?.peso_promedio"></span> kg
                        </p>
                      </div>
                      <div class="text-center">
                        <p class="text-amber-500">Edad prom.</p>
                        <p class="font-bold text-lg text-amber-800">
                          <span x-text="lotePreview?.edad_promedio_meses"></span> meses
                        </p>
                      </div>
                    </div>
                    {{-- Lista de animales --}}
                    <div class="max-h-36 overflow-y-auto space-y-1">
                      <template x-for="a in (lotePreview?.animales ?? [])" :key="a.id_animal">
                        <div class="flex items-center gap-2 text-xs text-amber-800 bg-white/60 rounded-lg px-3 py-1.5">
                          <span class="font-medium" x-text="a.nombre ?? 'Sin nombre'"></span>
                          <span class="text-amber-500">—</span>
                          <span x-text="a.raza"></span>
                          <span class="text-amber-500">—</span>
                          <span x-text="a.sexo"></span>
                          <span class="ml-auto font-semibold" x-text="a.peso + ' kg'"></span>
                        </div>
                      </template>
                    </div>
                  </div>
                </div>

                {{-- ── MODO MANUAL ── --}}
                <div x-show="modo==='manual'">
                  <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-4 text-xs text-blue-700">
                    ℹ️ Ingresa los datos del lote que deseas publicar.
                  </div>

                  <div class="grid grid-cols-2 gap-4">

                    <div class="col-span-2">
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nombre del lote *
                      </label>
                      <input type="text" name="nombre"
                             placeholder="Ej: Lote 18 Novillas Holstein"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none"
                             :required="modo==='manual'">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        N° de animales *
                      </label>
                      <input type="number" name="num_animales"
                             x-model.number="numAnimales"
                             placeholder="Ej: 20" min="2"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none"
                             :required="modo==='manual'">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Tipo de precio *
                      </label>
                      <div class="flex gap-4 pt-2">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                          <input type="radio" name="precio_tipo" value="por_cabeza"
                                 x-model="precioTipo"
                                 class="accent-amber-500 w-4 h-4"> Por cabeza
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                          <input type="radio" name="precio_tipo" value="total"
                                 x-model="precioTipo"
                                 class="accent-amber-500 w-4 h-4"> Total lote
                        </label>
                      </div>
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Precio (COP) *
                      </label>
                      <input type="number" name="precio"
                             x-model.number="precio"
                             placeholder="Ej: 4500000" min="0"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none"
                             :required="modo==='manual'">
                    </div>

                    <div>
                      <div x-show="calculado"
                           class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5 h-full">
                        <i class="fa-solid fa-calculator text-amber-500 flex-shrink-0 text-xs"></i>
                        <span class="text-xs font-semibold text-amber-700" x-text="calculado"></span>
                      </div>
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Peso promedio (kg)
                      </label>
                      <input type="number" name="peso_promedio"
                             placeholder="Ej: 380" min="0"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Edad promedio (meses)
                      </label>
                      <input type="number" name="edad_promedio"
                             placeholder="Ej: 24" min="0"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Raza predominante
                      </label>
                      <select name="raza"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                        <option value="">Seleccionar raza</option>
                        @foreach(['Angus','Ayrshire','Blanco orejinegro','Brangus','Brahman',
                                  'Cebú comercial','Charolais','Costeño con cuernos','Guzerat',
                                  'Gyr','Gyr-Jersey','Gyrolanda','Harton del Valle','Holstein negro',
                                  'Holstein rojo','Jersey','Limousin','Murrah','Nelore','Normando',
                                  'Pardo Suizo','Romosinuano','Simmental','Otra'] as $raza)
                        <option value="{{ $raza }}">{{ $raza }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Departamento *
                      </label>
                      <select name="departamento"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm"
                              :required="modo==='manual'">
                        <option value="">Seleccionar</option>
                        @foreach(['Antioquia','Atlántico','Bolívar','Boyacá','Caldas','Caquetá',
                                  'Casanare','Cauca','Cesar','Córdoba','Cundinamarca','Huila',
                                  'La Guajira','Magdalena','Meta','Nariño','Norte de Santander',
                                  'Putumayo','Quindío','Risaralda','Santander','Sucre','Tolima',
                                  'Valle del Cauca','Vichada'] as $dpto)
                        <option value="{{ $dpto }}">{{ $dpto }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Municipio *
                      </label>
                      <input type="text" name="municipio"
                             placeholder="Ej: Montería"
                             class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none"
                             :required="modo==='manual'">
                    </div>

                  </div>
                </div>

                {{-- ── CAMPOS COMUNES ── --}}
                <div class="border-t border-gray-200 mt-6 pt-6 grid grid-cols-2 gap-4">

                  <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                      Propósito *
                    </label>
                    <div class="flex flex-wrap gap-4">
                      <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="proposito" value="engorde"
                               class="accent-amber-500 w-4 h-4" required> Engorde
                      </label>
                      <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="proposito" value="leche"
                               class="accent-amber-500 w-4 h-4"> Leche
                      </label>
                      <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="proposito" value="reproduccion"
                               class="accent-amber-500 w-4 h-4"> Reproducción
                      </label>
                      <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="proposito" value="mixto"
                               class="accent-amber-500 w-4 h-4"> Mixto
                      </label>
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Punto de entrega
                    </label>
                    <input type="text" name="punto_entrega"
                           placeholder="Ej: En finca, municipio de Montería"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Responsable del flete
                    </label>
                    <select name="responsable_flete"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                      <option value="">Seleccionar</option>
                      <option value="vendedor">Vendedor</option>
                      <option value="comprador">Comprador</option>
                      <option value="negociable">Negociable</option>
                    </select>
                  </div>

                  <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="acepta_visita" value="1"
                           id="acepta_visita_lote" class="w-4 h-4">
                    <label for="acepta_visita_lote" class="text-sm font-medium text-gray-700">
                      Acepta visita previa del comprador
                    </label>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Condición de pago
                    </label>
                    <select name="condicion_pago"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                      <option value="">Seleccionar</option>
                      <option value="Contado">Contado</option>
                      <option value="Anticipo 50%">Anticipo 50%</option>
                      <option value="Negociable">Negociable</option>
                    </select>
                  </div>

                  <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                      Video YouTube (opcional)
                    </label>
                    <input type="url" name="video_youtube"
                           placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1">
                      Sube el video a tu canal de YouTube y pega el enlace aquí.
                    </p>
                  </div>

                  <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                      Fotos del lote (máximo 3)
                    </label>
                    <input type="file" name="fotos[]"
                           accept="image/jpeg,image/png,image/webp"
                           multiple
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG o WebP. Máximo 5MB por foto.</p>
                  </div>

                </div>

                <div class="mt-6">
                  <button type="submit"
                          class="w-full py-3 rounded-xl text-white font-bold text-sm
                                 hover:opacity-90 transition-opacity"
                          style="background-color:#e49b39">
                    Publicar Lote
                  </button>
                </div>

              </form>
            </div><!-- /tab lote -->


            {{-- =================================================
                 TAB: INSUMO
            ================================================= --}}
            <div x-show="tipo === 'insumo'" x-cloak>
                <form
                    method="POST"
                    action="{{ url('/marketplace/panel/publicar/insumo') }}"
                    enctype="multipart/form-data"
                    class="bg-white border border-gray-200 rounded-xl p-8 space-y-6"
                >
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        {{-- Nombre del producto --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del producto</label>
                            <input
                                type="text"
                                name="nombre"
                                placeholder="Ej: Sales Mineralizadas Premium 25kg"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#e49b39;"
                            >
                        </div>

                        {{-- Marca --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                            <input
                                type="text"
                                name="marca"
                                placeholder="Ej: Procampo"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#e49b39;"
                            >
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select
                                name="categoria"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none focus:ring-2 focus:border-transparent bg-white"
                                style="--tw-ring-color:#e49b39;">
                                <option value="">Selecciona una categoría...</option>
                                <option value="minerales">Minerales</option>
                                <option value="medicamentos">Medicamentos</option>
                                <option value="alimentos">Alimentos</option>
                                <option value="equipos">Equipos</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>

                        {{-- Precio unitario --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio unitario (COP)</label>
                            <input
                                type="number"
                                name="precio"
                                placeholder="Ej: 89000"
                                min="0"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#e49b39;"
                            >
                        </div>

                        {{-- Stock disponible --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock disponible</label>
                            <input
                                type="number"
                                name="stock"
                                placeholder="Ej: 48"
                                min="0"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#e49b39;"
                            >
                        </div>

                        {{-- Descripción --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea
                                name="descripcion"
                                rows="4"
                                placeholder="Describe el producto: composición, uso recomendado, beneficios..."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none resize-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#e49b39;"
                            ></textarea>
                        </div>

                        {{-- Foto --}}
                        <div class="sm:col-span-2"
                             x-data="{
                                 previews: [],
                                 agregar(e) {
                                     Array.from(e.target.files).forEach(file => {
                                         if (file.size > 5 * 1024 * 1024) { alert(file.name + ' supera 5 MB'); return; }
                                         const reader = new FileReader();
                                         reader.onload = ev => this.previews.push({ src: ev.target.result, nombre: file.name });
                                         reader.readAsDataURL(file);
                                     });
                                 },
                                 eliminar(i) { this.previews.splice(i, 1); }
                             }"
                        >
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Foto del producto
                                <span class="ml-2 text-xs font-semibold" style="color:#e49b39;"
                                      x-show="previews.length > 0"
                                      x-text="previews.length + ' foto(s) seleccionada(s)'"></span>
                            </label>

                            <label class="flex flex-col items-center justify-center w-full border-2 border-dashed border-gray-300 rounded-xl py-8 cursor-pointer hover:border-amber-400 hover:bg-amber-50 transition-colors">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                <span class="text-sm text-gray-500">Haz clic para seleccionar imagen</span>
                                <span class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — Máx. 5 MB</span>
                                <input type="file" name="fotos[]" accept="image/*" multiple class="hidden"
                                       @change="agregar($event)">
                            </label>

                            <div x-show="previews.length > 0" x-cloak
                                 class="mt-3 grid grid-cols-3 sm:grid-cols-5 gap-2">
                                <template x-for="(foto, i) in previews" :key="i">
                                    <div class="relative group rounded-lg overflow-hidden border border-gray-200 aspect-square">
                                        <img :src="foto.src" :alt="foto.nombre"
                                             class="w-full h-full object-cover">
                                        <button type="button"
                                                @click="eliminar(i)"
                                                class="absolute top-1 right-1 w-6 h-6 rounded-full bg-red-500 text-white text-xs
                                                       flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 rounded-xl font-bold text-white text-base transition-opacity hover:opacity-90 active:scale-95"
                        style="background-color:#e49b39;"
                    >
                        Publicar Insumo
                    </button>

                </form>
            </div><!-- /tab insumo -->

        </div><!-- /x-data tabs -->

@endsection
