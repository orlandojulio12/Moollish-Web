@extends('layouts')

@section('template_title')
    Información de Tierra y Agua
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId ) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion2', $predioId ) }}">Informacion Del Predio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Información sobre tierras y aguas</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection
@section('content')
    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">
                @if ($InfoExists)
                <form action="{{ route('info_tierra_agua.updatadeCaracterizacion', $infoTierraAgua->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_predio" value="{{ $infoTierraAgua->id_predio }}">

                    <!-- Suelos Predominantes -->
                    <div class="form-group mb-2 mb20">
                        <label for="suelos_predominantes" class="form-label">{{ __('Suelos Predominantes') }}</label>
                        <select name="suelos_predominantes"
                                class="form-control @error('suelos_predominantes') is-invalid @enderror"
                                id="suelos_predominantes">
                            <option value="">Selecciona un suelos</option>
                            <option value="FRANCO" {{ $infoTierraAgua->suelos_predominantes == 'FRANCO' ? 'selected' : '' }}>FRANCO</option>
                            <option value="ARENOSO" {{ $infoTierraAgua->suelos_predominantes == 'ARENOSO' ? 'selected' : '' }}>ARENOSO</option>
                            <option value="LIMOSO" {{ $infoTierraAgua->suelos_predominantes == 'LIMOSO' ? 'selected' : '' }}>LIMOSO</option>
                            <option value="OTRO" {{ $infoTierraAgua->suelos_predominantes == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                        </select>
                        {!! $errors->first('suelos_predominantes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Drenaje -->
                    <div class="form-group mb-2 mb20">
                        <label for="drenaje" class="form-label">{{ __('Drenaje') }}</label>
                        <select name="drenaje" class="form-control @error('drenaje') is-invalid @enderror" id="drenaje">
                            <option value="">Selecciona un drenaje</option>
                            <option value="Bueno" {{ $infoTierraAgua->drenaje == 'Bueno' ? 'selected' : '' }}>Bueno</option>
                            <option value="Regular" {{ $infoTierraAgua->drenaje == 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Malo" {{ $infoTierraAgua->drenaje == 'Malo' ? 'selected' : '' }}>Malo</option>
                        </select>
                        {!! $errors->first('drenaje', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Manejo de Cuencas Naturales de Agua -->
                    <div class="form-group mb-2 mb20">
                        <label for="manejo_cuencas_nac_agua" class="form-label">{{ __('Manejo de Cuencas Naturales de Agua') }}</label>
                        <select name="manejo_cuencas_nac_agua"
                                class="form-control @error('manejo_cuencas_nac_agua') is-invalid @enderror"
                                id="manejo_cuencas_nac_agua" onchange="togglePreservacionFields(this.value)">
                            <option value="">Selecciona un manejo</option>
                            <option value="Si" {{ $infoTierraAgua->manejo_cuencas_nac_agua == 'Si' ? 'selected' : '' }}>Si</option>
                            <option value="No" {{ $infoTierraAgua->manejo_cuencas_nac_agua == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('manejo_cuencas_nac_agua', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Cantidad de Preservación -->
                    <div id="preservacion_fields" style="{{ $infoTierraAgua->manejo_cuencas_nac_agua == 'Si' ? '' : 'display: none;' }}">
                        <div class="form-group mb-2 mb20">
                            <label for="cantidad_preservacion" class="form-label">{{ __('Cantidad de Preservación (m²)') }}</label>
                            <input type="number" name="cantidad_preservacion"
                                   class="form-control @error('cantidad_preservacion') is-invalid @enderror"
                                   value="{{ old('cantidad_preservacion', $infoTierraAgua->cantidad_preservacion) }}"
                                   id="cantidad_preservacion" placeholder="Cantidad de Preservación">
                            {!! $errors->first('cantidad_preservacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Porcentaje de Preservación -->
                        <div class="form-group mb-2 mb20">
                            <label for="porcentaje_preservacion" class="form-label">{{ __('Porcentaje de Preservación') }}</label>
                            <input type="number" name="porcentaje_preservacion"
                                   class="form-control @error('porcentaje_preservacion') is-invalid @enderror"
                                   value="{{ old('porcentaje_preservacion', $infoTierraAgua->porcentaje_preservacion) }}"
                                   id="porcentaje_preservacion" placeholder="Porcentaje de Preservación">
                            {!! $errors->first('porcentaje_preservacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- Fuente de Calidad de Agua -->
                    <div class="form-group mb-2 mb20">
                        <label for="fuente_calidad_agua" class="form-label">{{ __('Fuente de Calidad de Agua') }}</label>
                        <select name="fuente_calidad_agua"
                                class="form-control @error('fuente_calidad_agua') is-invalid @enderror"
                                id="fuente_calidad_agua">
                            <option value="">Selecciona una fuente</option>
                            <option value="QUEBRADA" {{ $infoTierraAgua->fuente_calidad_agua == 'QUEBRADA' ? 'selected' : '' }}>QUEBRADA</option>
                            <option value="POZO" {{ $infoTierraAgua->fuente_calidad_agua == 'POZO' ? 'selected' : '' }}>POZO</option>
                            <option value="JAGUEYES" {{ $infoTierraAgua->fuente_calidad_agua == 'JAGUEYES' ? 'selected' : '' }}>JAGUEYES</option>
                        </select>
                        {!! $errors->first('fuente_calidad_agua', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Fuente de Calidad de Agua para Uso Doméstico -->
                    <div class="form-group mb-2 mb20">
                        <label for="fuente_calidad_agua_uso_domestic" class="form-label">{{ __('Fuente de Calidad de Agua para Uso Doméstico') }}</label>
                        <select name="fuente_calidad_agua_uso_domestic"
                                class="form-control @error('fuente_calidad_agua_uso_domestic') is-invalid @enderror"
                                id="fuente_calidad_agua_uso_domestic">
                            <option value="">Selecciona una fuente</option>
                            <option value="Acueducto" {{ $infoTierraAgua->fuente_calidad_agua_uso_domestic == 'Acueducto' ? 'selected' : '' }}>Acueducto</option>
                            <option value="Quebrada" {{ $infoTierraAgua->fuente_calidad_agua_uso_domestic == 'Quebrada' ? 'selected' : '' }}>Quebrada</option>
                            <option value="Pozo" {{ $infoTierraAgua->fuente_calidad_agua_uso_domestic == 'Pozo' ? 'selected' : '' }}>Pozo</option>
                            <option value="Agua Lluvia" {{ $infoTierraAgua->fuente_calidad_agua_uso_domestic == 'Agua Lluvia' ? 'selected' : '' }}>Agua Lluvia</option>
                        </select>
                        {!! $errors->first('fuente_calidad_agua_uso_domestic', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Disponibilidad de Agua durante el Verano para Animales -->
                    <div class="form-group mb-2 mb20">
                        <label for="disp_agua_durant_veran_anim" class="form-label">{{ __('Disponibilidad de Agua durante el Verano para Animales') }}</label>
                        <select name="disp_agua_durant_veran_anim"
                                class="form-control @error('disp_agua_durant_veran_anim') is-invalid @enderror"
                                id="disp_agua_durant_veran_anim" onchange="toggleAnimalesFuenteField(this.value)">
                            <option value="">Selecciona una disponibilidad</option>
                            <option value="Si" {{ $infoTierraAgua->disp_agua_durant_veran_anim == 'Si' ? 'selected' : '' }}>Si</option>
                            <option value="No" {{ $infoTierraAgua->disp_agua_durant_veran_anim == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('disp_agua_durant_veran_anim', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Fuente de Disponibilidad de Agua durante el Verano para Animales -->
                    <div id="animales_fuente_field" style="{{ $infoTierraAgua->disp_agua_durant_veran_anim == 'Si' ? '' : 'display: none;' }}">
                        <div class="form-group mb-2 mb20">
                            <label for="disp_agua_durant_veran_anim_fuente" class="form-label">{{ __('Fuente de Disponibilidad de Agua durante el Verano para Animales') }}</label>
                            <input type="text" name="disp_agua_durant_veran_anim_fuente"
                                   class="form-control @error('disp_agua_durant_veran_anim_fuente') is-invalid @enderror"
                                   value="{{ old('disp_agua_durant_veran_anim_fuente', $infoTierraAgua->disp_agua_durant_veran_anim_fuente) }}"
                                   id="disp_agua_durant_veran_anim_fuente" placeholder="Fuente de Disponibilidad de Agua para Animales">
                            {!! $errors->first('disp_agua_durant_veran_anim_fuente', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- Disponibilidad de Agua durante el Verano en Caso de Riesgo -->
                    <div class="form-group mb-2 mb20">
                        <label for="disp_agua_durant_veran_riesg" class="form-label">{{ __('Disponibilidad de Agua durante el Verano en Caso de Riesgo') }}</label>
                        <select name="disp_agua_durant_veran_riesg"
                                class="form-control @error('disp_agua_durant_veran_riesg') is-invalid @enderror"
                                id="disp_agua_durant_veran_riesg" onchange="toggleRiesgoFuenteField(this.value)">
                            <option value="">Selecciona una disponibilidad</option>
                            <option value="Si" {{ $infoTierraAgua->disp_agua_durant_veran_riesg == 'Si' ? 'selected' : '' }}>Si</option>
                            <option value="No" {{ $infoTierraAgua->disp_agua_durant_veran_riesg == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('disp_agua_durant_veran_riesg', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Fuente de Disponibilidad de Agua durante el Verano en Caso de Riesgo -->
                    <div id="riesgo_fuente_field" style="{{ $infoTierraAgua->disp_agua_durant_veran_riesg == 'Si' ? '' : 'display: none;' }}">
                        <div class="form-group mb-2 mb20">
                            <label for="disp_agua_durant_veran_riesg_fuente" class="form-label">{{ __('Fuente de Disponibilidad de Agua durante el Verano en Caso de Riesgo') }}</label>
                            <input type="text" name="disp_agua_durant_veran_riesg_fuente"
                                   class="form-control @error('disp_agua_durant_veran_riesg_fuente') is-invalid @enderror"
                                   value="{{ old('disp_agua_durant_veran_riesg_fuente', $infoTierraAgua->disp_agua_durant_veran_riesg_fuente) }}"
                                   id="disp_agua_durant_veran_riesg_fuente" placeholder="Fuente de Disponibilidad de Agua en Caso de Riesgo">
                            {!! $errors->first('disp_agua_durant_veran_riesg_fuente', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Actualizar') }}</button>
                </form>

                @else
                    <form action="{{ route('info_tierra_agua.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_predio" value="{{ $predioId }}">

                        <!-- Suelos Predominantes -->
                        <div class="form-group mb-2 mb20">
                            <label for="suelos_predominantes" class="form-label">{{ __('Suelos Predominantes') }}</label>
                            <select name="suelos_predominantes"
                                class="form-control @error('suelos_predominantes') is-invalid @enderror"
                                id="suelos_predominantes">
                                <option value="">Selecciona un suelos</option>
                                <option value="FRANCO">FRANCO</option>
                                <option value="ARENOSO">ARENOSO</option>
                                <option value="LIMOSO">LIMOSO</option>
                                <option value="OTRO">OTRO</option>
                            </select>
                            {!! $errors->first(
                                'suelos_predominantes',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Drenaje -->
                        <div class="form-group mb-2 mb20">
                            <label for="drenaje" class="form-label">{{ __('Drenaje') }}</label>
                            <select name="drenaje" class="form-control @error('drenaje') is-invalid @enderror"
                                id="drenaje">
                                <option value="">Selecciona un drenaje</option>
                                <option value="Bueno">Bueno</option>
                                <option value="Regular">Regular</option>
                                <option value="Malo">Malo</option>
                            </select>
                            {!! $errors->first('drenaje', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Manejo de Cuencas Naturales de Agua -->
                        <div class="form-group mb-2 mb20">
                            <label for="manejo_cuencas_nac_agua"
                                class="form-label">{{ __('Manejo de Cuencas Naturales de Agua') }}</label>
                            <select name="manejo_cuencas_nac_agua"
                                class="form-control @error('manejo_cuencas_nac_agua') is-invalid @enderror"
                                id="manejo_cuencas_nac_agua" onchange="togglePreservacionFields(this.value)">
                                <option value="">Selecciona un manejo</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>

                            </select>
                            {!! $errors->first(
                                'manejo_cuencas_nac_agua',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Cantidad y Porcentaje de Preservación -->
                        <div id="preservacion_fields" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="cantidad_preservacion"
                                    class="form-label">{{ __('Cantidad de Preservación (m²)') }}</label>
                                <input type="number" name="cantidad_preservacion"
                                    class="form-control @error('cantidad_preservacion') is-invalid @enderror"
                                    value="{{ old('cantidad_preservacion') }}" id="cantidad_preservacion"
                                    placeholder="Cantidad de Preservación">
                                {!! $errors->first(
                                    'cantidad_preservacion',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>

                            <div class="form-group mb-2 mb20">
                                <label for="porcentaje_preservacion"
                                    class="form-label">{{ __('Porcentaje de Preservación') }}</label>
                                <input type="number" name="porcentaje_preservacion"
                                    class="form-control @error('porcentaje_preservacion') is-invalid @enderror"
                                    value="{{ old('porcentaje_preservacion') }}" id="porcentaje_preservacion"
                                    placeholder="Porcentaje de Preservación">
                                {!! $errors->first(
                                    'porcentaje_preservacion',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <!-- Fuente de Calidad de Agua -->
                        <div class="form-group mb-2 mb20">
                            <label for="fuente_calidad_agua"
                                class="form-label">{{ __('Fuente de Calidad de Agua') }}</label>
                            <select name="fuente_calidad_agua"
                                class="form-control @error('fuente_calidad_agua') is-invalid @enderror"
                                id="fuente_calidad_agua">
                                <option value="">Selecciona una fuente</option>
                                <option value="QUEBRADA">QUEBRADA</option>
                                <option value="POZO">POZO</option>
                                <option value="JAGUEYES">JAGUEYES</option>
                            </select>
                            {!! $errors->first(
                                'fuente_calidad_agua',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Fuente de Calidad de Agua para Uso Doméstico -->
                        <div class="form-group mb-2 mb20">
                            <label for="fuente_calidad_agua_uso_domestic"
                                class="form-label">{{ __('Fuente de Calidad de Agua para Uso Doméstico') }}</label>
                            <select name="fuente_calidad_agua_uso_domestic"
                                class="form-control @error('fuente_calidad_agua_uso_domestic') is-invalid @enderror"
                                id="fuente_calidad_agua_uso_domestic">
                                <option value="">Selecciona una fuente</option>
                                <option value="Acueducto">Acueducto</option>
                                <option value="Quebrada">Quebrada</option>
                                <option value="Pozo">Pozo</option>
                                <option value="Agua Lluvia">Agua Lluvia</option>
                            </select>
                            {!! $errors->first(
                                'fuente_calidad_agua_uso_domestic',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Disponibilidad de Agua durante el Verano para Animales -->
                        <div class="form-group mb-2 mb20">
                            <label for="disp_agua_durant_veran_anim"
                                class="form-label">{{ __('Disponibilidad de Agua durante el Verano para Animales') }}</label>
                            <select name="disp_agua_durant_veran_anim"
                                class="form-control @error('disp_agua_durant_veran_anim') is-invalid @enderror"
                                id="disp_agua_durant_veran_anim" onchange="toggleAnimalesFuenteField(this.value)">
                                <option value="">Selecciona una disponibilidad</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>

                            </select>
                            {!! $errors->first(
                                'disp_agua_durant_veran_anim',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Fuente de Disponibilidad de Agua durante el Verano para Animales -->
                        <div id="animales_fuente_field" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="disp_agua_durant_veran_anim_fuente"
                                    class="form-label">{{ __('Fuente de Disponibilidad de Agua durante el Verano para Animales') }}</label>
                                <input type="text" name="disp_agua_durant_veran_anim_fuente"
                                    class="form-control @error('disp_agua_durant_veran_anim_fuente') is-invalid @enderror"
                                    value="{{ old('disp_agua_durant_veran_anim_fuente') }}"
                                    id="disp_agua_durant_veran_anim_fuente"
                                    placeholder="Fuente de Disponibilidad de Agua durante el Verano para Animales">
                                {!! $errors->first(
                                    'disp_agua_durant_veran_anim_fuente',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <!-- Disponibilidad de Agua durante el Verano en Caso de Riesgo -->
                        <div class="form-group mb-2 mb20">
                            <label for="disp_agua_durant_veran_riesg"
                                class="form-label">{{ __('Disponibilidad de Agua durante el Verano en Caso de Riesgo') }}</label>
                            <select name="disp_agua_durant_veran_riesg"
                                class="form-control @error('disp_agua_durant_veran_riesg') is-invalid @enderror"
                                id="disp_agua_durant_veran_riesg" onchange="toggleRiesgoFuenteField(this.value)">
                                <option value="">Selecciona una disponibilidad</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>

                            </select>
                            {!! $errors->first(
                                'disp_agua_durant_veran_riesg',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Fuente de Disponibilidad de Agua durante el Verano en Caso de Riesgo -->
                        <div id="riesgo_fuente_field" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="disp_agua_durant_veran_riesg_fuente"
                                    class="form-label">{{ __('Fuente de Disponibilidad de Agua durante el Verano en Caso de Riesgo') }}</label>
                                <input type="text" name="disp_agua_durant_veran_riesg_fuente"
                                    class="form-control @error('disp_agua_durant_veran_riesg_fuente') is-invalid @enderror"
                                    value="{{ old('disp_agua_durant_veran_riesg_fuente') }}"
                                    id="disp_agua_durant_veran_riesg_fuente"
                                    placeholder="Fuente de Disponibilidad de Agua durante el Verano en Caso de Riesgo">
                                {!! $errors->first(
                                    'disp_agua_durant_veran_riesg_fuente',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePreservacionFields(value) {
            var preservacionFields = document.getElementById('preservacion_fields');
            if (value === 'Si') {
                preservacionFields.style.display = 'block';
            } else {
                preservacionFields.style.display = 'none';
                document.getElementById('cantidad_preservacion').value = 'No Aplica';
                document.getElementById('porcentaje_preservacion').value = 'No Aplica';
            }
        }

        function toggleAnimalesFuenteField(value) {
            var animalesFuenteField = document.getElementById('animales_fuente_field');
            if (value === 'Si') {
                animalesFuenteField.style.display = 'block';
            } else {
                animalesFuenteField.style.display = 'none';
                document.getElementById('disp_agua_durant_veran_anim_fuente').value = 'No Aplica';
            }
        }

        function toggleRiesgoFuenteField(value) {
            var riesgoFuenteField = document.getElementById('riesgo_fuente_field');
            if (value === 'Si') {
                riesgoFuenteField.style.display = 'block';
            } else {
                riesgoFuenteField.style.display = 'none';
                document.getElementById('disp_agua_durant_veran_riesg_fuente').value = 'No Aplica';
            }
        }
    </script>
@endsection
