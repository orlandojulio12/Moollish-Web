@extends('layouts')

@section('template_title')
    Manejo de Pastos, Potreros y Cercas
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId ) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion2', $predioId ) }}">Informacion Del Predio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">MANEJO DE PASTOS - POTREROS - CERCAS</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <style>
        #content-container:before {
            content: '';
            display: block;
            height: 165px;
            width: 100%;
            position: absolute;
            background-color: #C29F77 !important;
            z-index: 0;
        }
    </style>




    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">
                @if ($ManExists)

                <form action="{{ route('man_past_potr_cerc.update', $ManPastPotrCer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id_predio" value="{{ $predioId }}">

                    <!-- Área Destinada a Pastos -->
                    <div class="form-group mb-2 mb20">
                        <label for="area_dest_past" class="form-label">{{ __('Área Destinada a Pastos (m²)') }}</label>
                        <select name="area_dest_past"
                                class="form-control @error('area_dest_past') is-invalid @enderror"
                                id="area_dest_past" required>
                            <option value="">Seleccione</option>
                            <option value="Mejorados" {{ $ManPastPotrCer->area_dest_past == 'Mejorados' ? 'selected' : '' }}>Mejorados</option>
                            <option value="Naturales" {{ $ManPastPotrCer->area_dest_past == 'Naturales' ? 'selected' : '' }}>Naturales</option>
                            <option value="Silvopastoril" {{ $ManPastPotrCer->area_dest_past == 'Silvopastoril' ? 'selected' : '' }}>Silvopastoril</option>
                        </select>
                        {!! $errors->first('area_dest_past', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Realiza Fertilización en los Potreros -->
                    <div class="form-group mb-2 mb20">
                        <label for="r_fertilazion_potreros" class="form-label">{{ __('Realiza Fertilización en los Potreros') }}</label>
                        <select name="r_fertilazion_potreros"
                                class="form-control @error('r_fertilazion_potreros') is-invalid @enderror"
                                id="r_fertilazion_potreros" onchange="toggleFertilizacionPotreros()" required>
                            <option value="">Seleccione</option>
                            <option value="Sí" {{ $ManPastPotrCer->r_fertilazion_potreros == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ $ManPastPotrCer->r_fertilazion_potreros == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('r_fertilazion_potreros', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Producto Usado en la Fertilización de Potreros -->
                    <div id="fertilizacion_potreros_campos" style="display: {{ $ManPastPotrCer->r_fertilazion_potreros == 'Sí' ? 'block' : 'none' }};">
                        <div class="form-group mb-2 mb20">
                            <label for="r_fertilazion_potreros_produc" class="form-label">{{ __('Producto Usado en la Fertilización de Potreros') }}</label>
                            <input type="text" name="r_fertilazion_potreros_produc"
                                   class="form-control @error('r_fertilazion_potreros_produc') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->r_fertilazion_potreros_produc }}" id="r_fertilazion_potreros_produc">
                            {!! $errors->first('r_fertilazion_potreros_produc', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- CUÁNTO USA AL AÑO? -->
                        <div class="form-group mb-2 mb20">
                            <label for="r_fertilazion_potreros_cuant_año" class="form-label">{{ __('CUÁNTO USA AL AÑO?') }}</label>
                            <input type="number" name="r_fertilazion_potreros_cuant_año"
                                   class="form-control @error('r_fertilazion_potreros_cuant_año') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->r_fertilazion_potreros_cuant_año }}" id="r_fertilazion_potreros_cuant_año">
                            {!! $errors->first('r_fertilazion_potreros_cuant_año', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- Presencia de Plagas y Enfermedades -->
                    <div class="form-group mb-2 mb20">
                        <label for="presen_plag_enferm" class="form-label">{{ __('Presencia de Plagas y Enfermedades') }}</label>
                        <select name="presen_plag_enferm"
                                class="form-control @error('presen_plag_enferm') is-invalid @enderror"
                                id="presen_plag_enferm" onchange="togglePlagasEnfermedades()" required>
                            <option value="">Seleccione</option>
                            <option value="Sí" {{ $ManPastPotrCer->presen_plag_enferm == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ $ManPastPotrCer->presen_plag_enferm == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('presen_plag_enferm', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Intensidad y Tipo -->
                    <div id="plagas_enfermedades_campos" style="display: {{ $ManPastPotrCer->presen_plag_enferm == 'Sí' ? 'block' : 'none' }};">
                        <div class="form-group mb-2 mb20">
                            <label for="presen_plag_enferm_tipos" class="form-label">{{ __('Intensidad y Tipo') }}</label>
                            <input type="text" name="presen_plag_enferm_tipos"
                                   class="form-control @error('presen_plag_enferm_tipos') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->presen_plag_enferm_tipos }}" id="presen_plag_enferm_tipos">
                            {!! $errors->first('presen_plag_enferm_tipos', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- Realiza Control de Plagas -->
                    <div class="form-group mb-2 mb20">
                        <label for="r_control_plagas" class="form-label">{{ __('Realiza Control de Plagas') }}</label>
                        <select name="r_control_plagas"
                                class="form-control @error('r_control_plagas') is-invalid @enderror"
                                id="r_control_plagas" onchange="toggleControlPlagas()" required>
                            <option value="">Seleccione</option>
                            <option value="Sí" {{ $ManPastPotrCer->r_control_plagas == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ $ManPastPotrCer->r_control_plagas == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('r_control_plagas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <div id="control_plagas_campos" style="display: {{ $ManPastPotrCer->r_control_plagas == 'Sí' ? 'block' : 'none' }};">
                        <!-- Producto Usado en el Control de Plagas -->
                        <div class="form-group mb-2 mb20">
                            <label for="r_control_plagas_produc" class="form-label">{{ __('Producto Usado en el Control de Plagas') }}</label>
                            <input type="text" name="r_control_plagas_produc"
                                   class="form-control @error('r_control_plagas_produc') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->r_control_plagas_produc }}" id="r_control_plagas_produc">
                            {!! $errors->first('r_control_plagas_produc', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- CUÁNTO USA AL AÑO? -->
                        <div class="form-group mb-2 mb20">
                            <label for="r_control_plagas_cuant_año" class="form-label">{{ __('CUÁNTO USA AL AÑO?') }}</label>
                            <input type="number" name="r_control_plagas_cuant_año"
                                   class="form-control @error('r_control_plagas_cuant_año') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->r_control_plagas_cuant_año }}" id="r_control_plagas_cuant_año">
                            {!! $errors->first('r_control_plagas_cuant_año', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- Realiza Control de Maleza -->
                    <div class="form-group mb-2 mb20">
                        <label for="r_control_maleza" class="form-label">{{ __('Realiza Control de Maleza') }}</label>
                        <select name="r_control_maleza"
                                class="form-control @error('r_control_maleza') is-invalid @enderror"
                                id="r_control_maleza" onchange="toggleControlMaleza()" required>
                            <option value="">Seleccione</option>
                            <option value="Sí" {{ $ManPastPotrCer->r_control_maleza == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ $ManPastPotrCer->r_control_maleza == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('r_control_maleza', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <div id="control_maleza_campos" style="display: {{ $ManPastPotrCer->r_control_maleza == 'Sí' ? 'block' : 'none' }};">
                        <!-- Producto Usado en el Control de Maleza -->
                        <div class="form-group mb-2 mb20">
                            <label for="r_control_maleza_product" class="form-label">{{ __('Producto Usado en el Control de Maleza') }}</label>
                            <input type="text" name="r_control_maleza_product"
                                   class="form-control @error('r_control_maleza_product') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->r_control_maleza_product }}" id="r_control_maleza_product">
                            {!! $errors->first('r_control_maleza_product', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- CUÁNTO USA AL AÑO? -->
                        <div class="form-group mb-2 mb20">
                            <label for="r_control_maleza_cuant_año" class="form-label">{{ __('CUÁNTO USA AL AÑO?') }}</label>
                            <input type="number" name="r_control_maleza_cuant_año"
                                   class="form-control @error('r_control_maleza_cuant_año') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->r_control_maleza_cuant_año }}" id="r_control_maleza_cuant_año">
                            {!! $errors->first('r_control_maleza_cuant_año', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- Presencia de Heladas -->
                    <div class="form-group mb-2 mb20">
                        <label for="precencia_heladas" class="form-label">{{ __('Presencia de Heladas') }}</label>
                        <select name="precencia_heladas"
                                class="form-control @error('precencia_heladas') is-invalid @enderror"
                                id="precencia_heladas" onchange="togglePresenciaHeladas()" required>
                            <option value="">Seleccione</option>
                            <option value="Sí" {{ $ManPastPotrCer->precencia_heladas == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ $ManPastPotrCer->precencia_heladas == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('precencia_heladas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <div id="presencia_heladas_campos" style="display: {{ $ManPastPotrCer->precencia_heladas == 'Sí' ? 'block' : 'none' }};">
                        <!-- Intensidad de las Heladas -->
                        <div class="form-group mb-2 mb20">
                            <label for="precencia_heladas_intensidad" class="form-label">{{ __('Intensidad de las Heladas') }}</label>
                            <input type="text" name="precencia_heladas_intensidad"
                                   class="form-control @error('precencia_heladas_intensidad') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->precencia_heladas_intensidad }}" id="precencia_heladas_intensidad">
                            {!! $errors->first('precencia_heladas_intensidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Épocas del Año en que se Presentan las Heladas -->
                        <div class="form-group mb-2 mb20">
                            <label for="precencia_heladas_epocas" class="form-label">{{ __('Épocas del Año en que se Presentan las Heladas') }}</label>
                            <input type="text" name="precencia_heladas_epocas"
                                   class="form-control @error('precencia_heladas_epocas') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->precencia_heladas_epocas }}" id="precencia_heladas_epocas">
                            {!! $errors->first('precencia_heladas_epocas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- División de Potreros -->
                    <div class="form-group mb-2 mb20">
                        <label for="div_potreros" class="form-label">{{ __('División de Potreros') }}</label>
                        <select name="div_potreros"
                                class="form-control @error('div_potreros') is-invalid @enderror"
                                id="div_potreros" onchange="toggleDivisionPotreros()" required>
                            <option value="">Seleccione</option>
                            <option value="Sí" {{ $ManPastPotrCer->div_potreros == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ $ManPastPotrCer->div_potreros == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('div_potreros', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <div id="division_potreros_campos" style="display: {{ $ManPastPotrCer->div_potreros == 'Sí' ? 'block' : 'none' }};">
                        <!-- Número de Potreros -->
                        <div class="form-group mb-2 mb20">
                            <label for="div_potreros_como" class="form-label">{{ __('Número de Potreros') }}</label>
                            <input type="text" name="div_potreros_como"
                                   class="form-control @error('div_potreros_como') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->div_potreros_como }}" id="div_potreros_como">
                            {!! $errors->first('div_potreros_como', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- Tipo de Pastoreo -->
                    <div class="form-group mb-2 mb20">
                        <label for="tipo_pastoreo" class="form-label">{{ __('Tipo de Pastoreo') }}</label>
                        <select name="tipo_pastoreo"
                                class="form-control @error('tipo_pastoreo') is-invalid @enderror"
                                id="tipo_pastoreo" onchange="toggleTipoPastoreo()" required>
                            <option value="">Seleccione</option>
                            <option value="Continuo" {{ $ManPastPotrCer->tipo_pastoreo == 'Continuo' ? 'selected' : '' }}>Continuo</option>
                            <option value="Alterno" {{ $ManPastPotrCer->tipo_pastoreo == 'Alterno' ? 'selected' : '' }}>Alterno</option>
                            <option value="Rotacional" {{ $ManPastPotrCer->tipo_pastoreo == 'Rotacional' ? 'selected' : '' }}>Rotacional</option>
                            <option value="Franja" {{ $ManPastPotrCer->tipo_pastoreo == 'Franja' ? 'selected' : '' }}>Franja</option>
                        </select>
                        {!! $errors->first('tipo_pastoreo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <div id="rotacional_campos" style="display: {{ $ManPastPotrCer->tipo_pastoreo == 'Rotacional' ? 'block' : 'none' }};">
                        <!-- Días de Ocupación en Pastoreo Rotacional -->
                        <div class="form-group mb-2 mb20">
                            <label for="rotacional_dias_ocupacion" class="form-label">{{ __('Días de Ocupación en Pastoreo Rotacional') }}</label>
                            <input type="number" name="rotacional_dias_ocupacion"
                                   class="form-control @error('rotacional_dias_ocupacion') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->rotacional_dias_ocupacion }}" id="rotacional_dias_ocupacion">
                            {!! $errors->first('rotacional_dias_ocupacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Días de Descanso en Pastoreo Rotacional -->
                        <div class="form-group mb-2 mb20">
                            <label for="rotacional_dias_descanso" class="form-label">{{ __('Días de Descanso en Pastoreo Rotacional') }}</label>
                            <input type="number" name="rotacional_dias_descanso"
                                   class="form-control @error('rotacional_dias_descanso') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->rotacional_dias_descanso }}" id="rotacional_dias_descanso">
                            {!! $errors->first('rotacional_dias_descanso', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- Cercas en la Propiedad -->
                    <div class="form-group mb-2 mb20">
                        <label for="cercas" class="form-label">{{ __('Cercas en la Propiedad') }}</label>
                        <select name="cercas"
                                class="form-control @error('cercas') is-invalid @enderror"
                                id="cercas" onchange="toggleCercas()" required>
                            <option value="">Seleccione</option>
                            <option value="Sí" {{ $ManPastPotrCer->cercas == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ $ManPastPotrCer->cercas == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('cercas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <div id="cercas_campos" style="display: {{ $ManPastPotrCer->cercas == 'Sí' ? 'block' : 'none' }};">
                        <!-- Cercas de Púas -->
                        <div class="form-group mb-2 mb20">
                            <label for="cercas_puas" class="form-label">{{ __('Cercas de Púas (KM)') }}</label>
                            <input type="number" name="cercas_puas"
                                   class="form-control @error('cercas_puas') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->cercas_puas }}" id="cercas_puas">
                            {!! $errors->first('cercas_puas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Cercas Eléctricas -->
                        <div class="form-group mb-2 mb20">
                            <label for="cercas_electricas" class="form-label">{{ __('Cercas Eléctricas (KM)') }}</label>
                            <input type="number" name="cercas_electricas"
                                   class="form-control @error('cercas_electricas') is-invalid @enderror"
                                   value="{{ $ManPastPotrCer->cercas_electricas }}" id="cercas_electricas">
                            {!! $errors->first('cercas_electricas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <!-- La Producción de Forraje es Suficiente Durante Todo el Año -->
                    <div class="form-group mb-2 mb20">
                        <label for="la_produccion_forraje_suficiente_año" class="form-label">{{ __('¿La Producción de Forraje es Suficiente Durante Todo el Año?') }}</label>
                        <select name="la_produccion_forraje_suficiente_año"
                                class="form-control @error('la_produccion_forraje_suficiente_año') is-invalid @enderror"
                                id="la_produccion_forraje_suficiente_año" required>
                            <option value="">Seleccione</option>
                            <option value="Sí" {{ $ManPastPotrCer->la_produccion_forraje_suficiente_año == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ $ManPastPotrCer->la_produccion_forraje_suficiente_año == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('la_produccion_forraje_suficiente_año', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- ¿Por Qué? -->
                    <div class="form-group mb-2 mb20">
                        <label for="porque" class="form-label">{{ __('¿Por Qué?') }}</label>
                        <textarea name="porque" class="form-control @error('porque') is-invalid @enderror" id="porque" required>{{ $ManPastPotrCer->porque }}</textarea>
                        {!! $errors->first('porque', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Actualizar') }}</button>
                </form>

                @else
                    <form action="{{ route('man_past_potr_cerc.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_predio" value="{{ $predioId }}">

                        <div class="form-group mb-2 mb20">
                            <label for="area_dest_past"
                                class="form-label">{{ __('Área Destinada a Pastos (m²)') }}</label>
                            <select name="area_dest_past"
                                class="form-control @error('area_dest_past') is-invalid @enderror" id="area_dest_past"
                                required>
                                <option value="">Seleccione</option>
                                <option value="Mejorados" {{ old('area_dest_past') == 'Mejorados' ? 'selected' : '' }}>
                                    Mejorados
                                </option>
                                <option value="Naturales" {{ old('area_dest_past') == 'Naturales' ? 'selected' : '' }}>
                                    Naturales
                                </option>
                                <option value="Silvopastoril"
                                    {{ old('area_dest_past') == 'Silvopastoril' ? 'selected' : '' }}>
                                    Silvopastoril</option>
                            </select>
                            {!! $errors->first(
                                'area_dest_past',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="r_fertilazion_potreros"
                                class="form-label">{{ __('Realiza Fertilización en los Potreros') }}</label>
                            <select name="r_fertilazion_potreros"
                                class="form-control @error('r_fertilazion_potreros') is-invalid @enderror"
                                id="r_fertilazion_potreros" onchange="toggleFertilizacionPotreros()" required>
                                <option value="">Seleccione</option>
                                <option value="Sí" {{ old('r_fertilazion_potreros') == 'Sí' ? 'selected' : '' }}>Sí
                                </option>
                                <option value="No" {{ old('r_fertilazion_potreros') == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            {!! $errors->first(
                                'r_fertilazion_potreros',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <div id="fertilizacion_potreros_campos" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="r_fertilazion_potreros_produc"
                                    class="form-label">{{ __('Producto Usado en la Fertilización de Potreros') }}</label>
                                <input type="text" name="r_fertilazion_potreros_produc"
                                    class="form-control @error('r_fertilazion_potreros_produc') is-invalid @enderror"
                                    value="{{ old('r_fertilazion_potreros_produc') }}"
                                    id="r_fertilazion_potreros_produc">
                                {!! $errors->first(
                                    'r_fertilazion_potreros_produc',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>

                            <div class="form-group mb-2 mb20">
                                <label for="r_fertilazion_potreros_cuant_año"
                                    class="form-label">{{ __('CUÁNTO USA AL AÑO?') }}</label>
                                <input type="number" name="r_fertilazion_potreros_cuant_año"
                                    class="form-control @error('r_fertilazion_potreros_cuant_año') is-invalid @enderror"
                                    value="{{ old('r_fertilazion_potreros_cuant_año') }}"
                                    id="r_fertilazion_potreros_cuant_año">
                                {!! $errors->first(
                                    'r_fertilazion_potreros_cuant_año',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="presen_plag_enferm"
                                class="form-label">{{ __('Presencia de Plagas y Enfermedades') }}</label>
                            <select name="presen_plag_enferm"
                                class="form-control @error('presen_plag_enferm') is-invalid @enderror"
                                id="presen_plag_enferm" onchange="togglePlagasEnfermedades()" required>
                                <option value="">Seleccione</option>
                                <option value="Sí" {{ old('presen_plag_enferm') == 'Sí' ? 'selected' : '' }}>Sí
                                </option>
                                <option value="No" {{ old('presen_plag_enferm') == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            {!! $errors->first(
                                'presen_plag_enferm',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <div id="plagas_enfermedades_campos" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="presen_plag_enferm_tipos"
                                    class="form-label">{{ __('Intensidad y Tipo') }}</label>
                                <input type="text" name="presen_plag_enferm_tipos"
                                    class="form-control @error('presen_plag_enferm_tipos') is-invalid @enderror"
                                    value="{{ old('presen_plag_enferm_tipos') }}" id="presen_plag_enferm_tipos">
                                {!! $errors->first(
                                    'presen_plag_enferm_tipos',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="r_control_plagas"
                                class="form-label">{{ __('Realiza Control de Plagas') }}</label>
                            <select name="r_control_plagas"
                                class="form-control @error('r_control_plagas') is-invalid @enderror"
                                id="r_control_plagas" onchange="toggleControlPlagas()" required>
                                <option value="">Seleccione</option>
                                <option value="Sí" {{ old('r_control_plagas') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('r_control_plagas') == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                            {!! $errors->first(
                                'r_control_plagas',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <div id="control_plagas_campos" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="r_control_plagas_produc"
                                    class="form-label">{{ __('Producto Usado en el Control de Plagas') }}</label>
                                <input type="text" name="r_control_plagas_produc"
                                    class="form-control @error('r_control_plagas_produc') is-invalid @enderror"
                                    value="{{ old('r_control_plagas_produc') }}" id="r_control_plagas_produc">
                                {!! $errors->first(
                                    'r_control_plagas_produc',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>

                            <div class="form-group mb-2 mb20">
                                <label for="r_control_plagas_cuant_año"
                                    class="form-label">{{ __('CUÁNTO USA AL AÑO?') }}</label>
                                <input type="number" name="r_control_plagas_cuant_año"
                                    class="form-control @error('r_control_plagas_cuant_año') is-invalid @enderror"
                                    value="{{ old('r_control_plagas_cuant_año') }}" id="r_control_plagas_cuant_año">
                                {!! $errors->first(
                                    'r_control_plagas_cuant_año',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="r_control_maleza"
                                class="form-label">{{ __('Realiza Control de Maleza') }}</label>
                            <select name="r_control_maleza"
                                class="form-control @error('r_control_maleza') is-invalid @enderror"
                                id="r_control_maleza" onchange="toggleControlMaleza()" required>
                                <option value="">Seleccione</option>
                                <option value="Sí" {{ old('r_control_maleza') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('r_control_maleza') == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                            {!! $errors->first(
                                'r_control_maleza',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <div id="control_maleza_campos" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="r_control_maleza_product"
                                    class="form-label">{{ __('Producto Usado en el Control de Maleza') }}</label>
                                <input type="text" name="r_control_maleza_product"
                                    class="form-control @error('r_control_maleza_product') is-invalid @enderror"
                                    value="{{ old('r_control_maleza_product') }}" id="r_control_maleza_product">
                                {!! $errors->first(
                                    'r_control_maleza_product',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>

                            <div class="form-group mb-2 mb20">
                                <label for="r_control_maleza_cuant_año"
                                    class="form-label">{{ __('CUÁNTO USA AL AÑO?') }}</label>
                                <input type="number" name="r_control_maleza_cuant_año"
                                    class="form-control @error('r_control_maleza_cuant_año') is-invalid @enderror"
                                    value="{{ old('r_control_maleza_cuant_año') }}" id="r_control_maleza_cuant_año">
                                {!! $errors->first(
                                    'r_control_maleza_cuant_año',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="precencia_heladas" class="form-label">{{ __('Presencia de Heladas') }}</label>
                            <select name="precencia_heladas"
                                class="form-control @error('precencia_heladas') is-invalid @enderror"
                                id="precencia_heladas" onchange="togglePresenciaHeladas()" required>
                                <option value="">Seleccione</option>
                                <option value="Sí" {{ old('precencia_heladas') == 'Sí' ? 'selected' : '' }}>Sí
                                </option>
                                <option value="No" {{ old('precencia_heladas') == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            {!! $errors->first(
                                'precencia_heladas',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <div id="presencia_heladas_campos" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="precencia_heladas_intensidad"
                                    class="form-label">{{ __('Intensidad de las Heladas') }}</label>
                                <input type="text" name="precencia_heladas_intensidad"
                                    class="form-control @error('precencia_heladas_intensidad') is-invalid @enderror"
                                    value="{{ old('precencia_heladas_intensidad') }}" id="precencia_heladas_intensidad">
                                {!! $errors->first(
                                    'precencia_heladas_intensidad',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>

                            <div class="form-group mb-2 mb20">
                                <label for="precencia_heladas_epocas"
                                    class="form-label">{{ __('Épocas del Año en que se Presentan las Heladas') }}</label>
                                <input type="text" name="precencia_heladas_epocas"
                                    class="form-control @error('precencia_heladas_epocas') is-invalid @enderror"
                                    value="{{ old('precencia_heladas_epocas') }}" id="precencia_heladas_epocas">
                                {!! $errors->first(
                                    'precencia_heladas_epocas',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="div_potreros" class="form-label">{{ __('División de Potreros') }}</label>
                            <select name="div_potreros" class="form-control @error('div_potreros') is-invalid @enderror"
                                id="div_potreros" onchange="toggleDivisionPotreros()" required>
                                <option value="">Seleccione</option>
                                <option value="Sí" {{ old('div_potreros') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('div_potreros') == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                            {!! $errors->first('div_potreros', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <div id="division_potreros_campos" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="div_potreros_como"
                                    class="form-label">{{ __('Numeros de Potreros') }}</label>
                                <input type="text" name="div_potreros_como"
                                    class="form-control @error('div_potreros_como') is-invalid @enderror"
                                    value="{{ old('div_potreros_como') }}" id="div_potreros_como">
                                {!! $errors->first(
                                    'div_potreros_como',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="tipo_pastoreo" class="form-label">{{ __('Tipo de Pastoreo') }}</label>
                            <select name="tipo_pastoreo"
                                class="form-control @error('tipo_pastoreo') is-invalid @enderror" id="tipo_pastoreo"
                                onchange="toggleTipoPastoreo()" required>
                                <option value="">Seleccione</option>
                                <option value="Continuo" {{ old('tipo_pastoreo') == 'Continuo' ? 'selected' : '' }}>
                                    Continuo
                                </option>
                                <option value="Alterno" {{ old('tipo_pastoreo') == 'Alterno' ? 'selected' : '' }}>Alterno
                                </option>
                                <option value="Rotacional" {{ old('tipo_pastoreo') == 'Rotacional' ? 'selected' : '' }}>
                                    Rotacional</option>
                                <option value="Franja" {{ old('tipo_pastoreo') == 'Franja' ? 'selected' : '' }}>Franja
                                </option>
                            </select>
                            {!! $errors->first(
                                'tipo_pastoreo',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <div id="rotacional_campos" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="rotacional_dias_ocupacion"
                                    class="form-label">{{ __('Días de Ocupación en Pastoreo Rotacional') }}</label>
                                <input type="number" name="rotacional_dias_ocupacion"
                                    class="form-control @error('rotacional_dias_ocupacion') is-invalid @enderror"
                                    value="{{ old('rotacional_dias_ocupacion') }}" id="rotacional_dias_ocupacion">
                                {!! $errors->first(
                                    'rotacional_dias_ocupacion',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>

                            <div class="form-group mb-2 mb20">
                                <label for="rotacional_dias_descanso"
                                    class="form-label">{{ __('Días de Descanso en Pastoreo Rotacional') }}</label>
                                <input type="number" name="rotacional_dias_descanso"
                                    class="form-control @error('rotacional_dias_descanso') is-invalid @enderror"
                                    value="{{ old('rotacional_dias_descanso') }}" id="rotacional_dias_descanso">
                                {!! $errors->first(
                                    'rotacional_dias_descanso',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="cercas" class="form-label">{{ __('Cercas en la Propiedad') }}</label>
                            <select name="cercas" class="form-control @error('cercas') is-invalid @enderror"
                                id="cercas" onchange="toggleCercas()" required>
                                <option value="">Seleccione</option>
                                <option value="Sí" {{ old('cercas') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('cercas') == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                            {!! $errors->first('cercas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <div id="cercas_campos" style="display: none;">
                            <div class="form-group mb-2 mb20">
                                <label for="cercas_puas" class="form-label">{{ __('Cercas de Púas (KM)') }}</label>
                                <input type="number" name="cercas_puas"
                                    class="form-control @error('cercas_puas') is-invalid @enderror"
                                    value="{{ old('cercas_puas') }}" id="cercas_puas">
                                {!! $errors->first('cercas_puas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                            </div>

                            <div class="form-group mb-2 mb20">
                                <label for="cercas_electricas"
                                    class="form-label">{{ __('Cercas Eléctricas (KM)') }}</label>
                                <input type="number" name="cercas_electricas"
                                    class="form-control @error('cercas_electricas') is-invalid @enderror"
                                    value="{{ old('cercas_electricas') }}" id="cercas_electricas">
                                {!! $errors->first(
                                    'cercas_electricas',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="la_produccion_forraje_suficiente_año"
                                class="form-label">{{ __('¿La Producción de Forraje es Suficiente Durante Todo el Año?') }}</label>
                            <select name="la_produccion_forraje_suficiente_año"
                                class="form-control @error('la_produccion_forraje_suficiente_año') is-invalid @enderror"
                                id="la_produccion_forraje_suficiente_año" required>
                                <option value="">Seleccione</option>
                                <option value="Sí"
                                    {{ old('la_produccion_forraje_suficiente_año') == 'Sí' ? 'selected' : '' }}>Sí
                                </option>
                                <option value="No"
                                    {{ old('la_produccion_forraje_suficiente_año') == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            {!! $errors->first(
                                'la_produccion_forraje_suficiente_año',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <div class="form-group mb-2 mb20">
                            <label for="porque" class="form-label">{{ __('¿Por Qué?') }}</label>
                            <textarea name="porque" class="form-control @error('porque') is-invalid @enderror" id="porque" required>{{ old('porque') }}</textarea>
                            {!! $errors->first('porque', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
                    </form>
                    @endif
            </div>
        </div>
    </div>

    <script>
        function toggleFertilizacionPotreros() {
            var fertilizacion = document.getElementById('r_fertilazion_potreros').value;
            document.getElementById('fertilizacion_potreros_campos').style.display = (fertilizacion == 'Sí') ? 'block' :
                'none';
        }

        function togglePlagasEnfermedades() {
            var plagasEnfermedades = document.getElementById('presen_plag_enferm').value;
            document.getElementById('plagas_enfermedades_campos').style.display = (plagasEnfermedades == 'Sí') ? 'block' :
                'none';
        }

        function toggleControlPlagas() {
            var controlPlagas = document.getElementById('r_control_plagas').value;
            document.getElementById('control_plagas_campos').style.display = (controlPlagas == 'Sí') ? 'block' : 'none';
        }

        function toggleControlMaleza() {
            var controlMaleza = document.getElementById('r_control_maleza').value;
            document.getElementById('control_maleza_campos').style.display = (controlMaleza == 'Sí') ? 'block' : 'none';
        }

        function togglePresenciaHeladas() {
            var heladas = document.getElementById('precencia_heladas').value;
            document.getElementById('presencia_heladas_campos').style.display = (heladas == 'Sí') ? 'block' : 'none';
        }

        function toggleDivisionPotreros() {
            var division = document.getElementById('div_potreros').value;
            document.getElementById('division_potreros_campos').style.display = (division == 'Sí') ? 'block' : 'none';
        }

        function toggleCercas() {
            var cercas = document.getElementById('cercas').value;
            document.getElementById('cercas_campos').style.display = (cercas == 'Sí') ? 'block' : 'none';
        }

        function toggleFertilizacionPotrerosProduc() {
            var fertilizacionProduc = document.getElementById('r_fertilazion_potreros_produc').value;
            document.getElementById('fertilizacion_potreros_produc_campos').style.display = (fertilizacionProduc == 'Sí') ?
                'block' : 'none';
        }

        function toggleControlPlagasProduc() {
            var controlPlagasProduc = document.getElementById('r_control_plagas_produc').value;
            document.getElementById('control_plagas_produc_campos').style.display = (controlPlagasProduc == 'Sí') ?
                'block' : 'none';
        }

        function toggleControlMalezaProduc() {
            var controlMalezaProduc = document.getElementById('r_control_maleza_product').value;
            document.getElementById('control_maleza_produc_campos').style.display = (controlMalezaProduc == 'Sí') ?
                'block' : 'none';
        }

        function toggleTipoPastoreo() {
            var tipoPastoreo = document.getElementById('tipo_pastoreo').value;
            document.getElementById('rotacional_campos').style.display = (tipoPastoreo == 'Rotacional') ? 'block' : 'none';
            if (tipoPastoreo != 'Rotacional') {
                document.getElementById('rotacional_dias_ocupacion').value = 'No Aplica';
                document.getElementById('rotacional_dias_descanso').value = 'No Aplica';
            }
        }

        function toggleCercas() {
            var cercas = document.getElementById('cercas').value;
            document.getElementById('cercas_campos').style.display = (cercas == 'Sí') ? 'block' : 'none';
            if (cercas != 'Sí') {
                document.getElementById('cercas_puas').value = 'No Aplica';
                document.getElementById('cercas_electricas').value = 'No Aplica';
            }
        }

        // Add similar functions for other fields if needed
    </script>
@endsection
