@extends('layouts')

@section('template_title')
    Caracterización de Riesgo
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion4', $predioId) }}">Risesgo Epidemiologico</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Caracterizacion de riesgo</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">
                @if ($RiesgoExists)
                    <form action="{{ route('caracterizacion_riesgo.update', $caracterizacionRiesgo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id_predio" value="{{ $caracterizacionRiesgo->id_predio }}">

                        <!-- Colindancia con Establecimientos de Riesgo -->
                        <div class="form-group mb-2 mb20">
                            <label for="colinda_establecim_riesgo"
                                class="form-label">{{ __('El predio colinda con establecimiento de riesgo') }}</label>
                            <select class="form-control @error('colinda_establecim_riesgo') is-invalid @enderror"
                                id="colinda_establecim_riesgo" name="colinda_establecim_riesgo"
                                onchange="toggleField(this.value, 'colinda_establecim_cual_field')" required>
                                <option value="">Seleccione</option>
                                <option value="Si"
                                    {{ $caracterizacionRiesgo->colinda_establecim_riesgo == 'Si' ? 'selected' : '' }}>Si
                                </option>
                                <option value="No"
                                    {{ $caracterizacionRiesgo->colinda_establecim_riesgo == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            {!! $errors->first('colinda_establecim_riesgo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Cuál Establecimiento -->
                        <div class="form-group mb-2 mb20" id="colinda_establecim_cual_field"
                            style="display: {{ $caracterizacionRiesgo->colinda_establecim_riesgo == 'Si' ? 'block' : 'none' }};">
                            <label for="colinda_establecim_cual"
                                class="form-label">{{ __('¿Cuál establecimiento?') }}</label>
                            <input type="text"
                                class="form-control @error('colinda_establecim_cual') is-invalid @enderror"
                                id="colinda_establecim_cual" name="colinda_establecim_cual"
                                value="{{ $caracterizacionRiesgo->colinda_establecim_cual }}">
                            {!! $errors->first('colinda_establecim_cual', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Ubicación en Vía -->
                        <div class="form-group mb-2 mb20">
                            <label for="ubica_en_via" class="form-label">{{ __('El predio se ubica en vía:') }}</label>
                            <select class="form-control @error('ubica_en_via') is-invalid @enderror" id="ubica_en_via"
                                name="ubica_en_via">
                                <option value="">Seleccione</option>
                                <option value="Nacional"
                                    {{ $caracterizacionRiesgo->ubica_en_via == 'Nacional' ? 'selected' : '' }}>Nacional
                                </option>
                                <option value="Secundaria"
                                    {{ $caracterizacionRiesgo->ubica_en_via == 'Secundaria' ? 'selected' : '' }}>Secundaria
                                </option>
                                <option value="Veredal"
                                    {{ $caracterizacionRiesgo->ubica_en_via == 'Veredal' ? 'selected' : '' }}>Veredal
                                </option>
                                <option value="Tiene servidumbre"
                                    {{ $caracterizacionRiesgo->ubica_en_via == 'Tiene servidumbre' ? 'selected' : '' }}>
                                    Tiene servidumbre</option>
                            </select>
                            {!! $errors->first('ubica_en_via', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Alimentación de Animales -->
                        <div class="form-group mb-2 mb20">
                            <label for="alimen_animal" class="form-label">{{ __('Alimentación de animales') }}</label>
                            <select class="form-control @error('alimen_animal') is-invalid @enderror" id="alimen_animal"
                                name="alimen_animal" onchange="toggleField(this.value, 'alimen_animl_otro_field')">
                                <option value="">Seleccione</option>
                                <option value="Pastoreo"
                                    {{ $caracterizacionRiesgo->alimen_animal == 'Pastoreo' ? 'selected' : '' }}>Pastoreo
                                </option>
                                <option value="Concentrado"
                                    {{ $caracterizacionRiesgo->alimen_animal == 'Concentrado' ? 'selected' : '' }}>
                                    Concentrado</option>
                                <option value="Heno"
                                    {{ $caracterizacionRiesgo->alimen_animal == 'Heno' ? 'selected' : '' }}>Heno</option>
                                <option value="Subproductos"
                                    {{ $caracterizacionRiesgo->alimen_animal == 'Subproductos' ? 'selected' : '' }}>
                                    Subproductos</option>
                                <option value="Otro"
                                    {{ $caracterizacionRiesgo->alimen_animal == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            {!! $errors->first('alimen_animal', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Especificar Otro Alimento -->
                        <div class="form-group mb-2 mb20" id="alimen_animl_otro_field"
                            style="display: {{ $caracterizacionRiesgo->alimen_animal == 'Otro' ? 'block' : 'none' }};">
                            <label for="alimen_animl_otro" class="form-label">{{ __('Especificar otro alimento') }}</label>
                            <input type="text" class="form-control @error('alimen_animl_otro') is-invalid @enderror"
                                id="alimen_animl_otro" name="alimen_animl_otro"
                                value="{{ $caracterizacionRiesgo->alimen_animl_otro }}">
                            {!! $errors->first('alimen_animl_otro', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Lavazas de Desperdicios Alimentarios Porcinos -->
                        <div class="form-group mb-2 mb20">
                            <label for="lavazas_desper_alimen_porc"
                                class="form-label">{{ __('Suministra lavazas y/o desperdicios de alimentación humana en la alimentación de porcinos') }}</label>
                            <select class="form-control @error('lavazas_desper_alimen_porc') is-invalid @enderror"
                                id="lavazas_desper_alimen_porc" name="lavazas_desper_alimen_porc">
                                <option value="">Seleccione</option>
                                <option value="Si"
                                    {{ $caracterizacionRiesgo->lavazas_desper_alimen_porc == 'Si' ? 'selected' : '' }}>Si
                                </option>
                                <option value="No"
                                    {{ $caracterizacionRiesgo->lavazas_desper_alimen_porc == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            {!! $errors->first(
                                'lavazas_desper_alimen_porc',
                                '<div class="invalid-feedback"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Realización de Cocción Previa -->
                        <div class="form-group mb-2 mb20">
                            <label for="real_coccion_previa" class="form-label">{{ __('Realiza cocción previa') }}</label>
                            <select class="form-control @error('real_coccion_previa') is-invalid @enderror"
                                id="real_coccion_previa" name="real_coccion_previa">
                                <option value="">Seleccione</option>
                                <option value="Si"
                                    {{ $caracterizacionRiesgo->real_coccion_previa == 'Si' ? 'selected' : '' }}>Si</option>
                                <option value="No"
                                    {{ $caracterizacionRiesgo->real_coccion_previa == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                            {!! $errors->first('real_coccion_previa', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Sacrificio de Animales en el Predio -->
                        <div class="form-group mb-2 mb20">
                            <label for="sacrif_anim_pred"
                                class="form-label">{{ __('¿Se realiza sacrificio de animales en el predio?') }}</label>
                            <select class="form-control @error('sacrif_anim_pred') is-invalid @enderror"
                                id="sacrif_anim_pred" name="sacrif_anim_pred"
                                onchange="toggleField(this.value, 'sacrif_anim_pred_periodic_field')">
                                <option value="">Seleccione</option>
                                <option value="Si"
                                    {{ $caracterizacionRiesgo->sacrif_anim_pred == 'Si' ? 'selected' : '' }}>Si</option>
                                <option value="No"
                                    {{ $caracterizacionRiesgo->sacrif_anim_pred == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                            {!! $errors->first('sacrif_anim_pred', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Periodicidad del Sacrificio -->
                        <div class="form-group mb-2 mb20" id="sacrif_anim_pred_periodic_field"
                            style="display: {{ $caracterizacionRiesgo->sacrif_anim_pred == 'Si' ? 'block' : 'none' }};">
                            <label for="sacrif_anim_pred_periodic"
                                class="form-label">{{ __('Periodicidad del sacrificio') }}</label>
                            <input type="text"
                                class="form-control @error('sacrif_anim_pred_periodic') is-invalid @enderror"
                                id="sacrif_anim_pred_periodic" name="sacrif_anim_pred_periodic"
                                value="{{ $caracterizacionRiesgo->sacrif_anim_pred_periodic }}">
                            {!! $errors->first('sacrif_anim_pred_periodic', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Servicios de Reproducción -->
                        <div class="form-group mb-2 mb20">
                            <label for="servic_reproduc"
                                class="form-label">{{ __('¿Servicio de reproducción?') }}</label>
                            <select class="form-control @error('servic_reproduc') is-invalid @enderror"
                                id="servic_reproduc" name="servic_reproduc"
                                onchange="toggleField(this.value, 'servic_reproduc_otro_field')">
                                <option value="">Seleccione</option>
                                <option value="Propio"
                                    {{ $caracterizacionRiesgo->servic_reproduc == 'Propio' ? 'selected' : '' }}>Propio
                                </option>
                                <option value="Ingresa Animales"
                                    {{ $caracterizacionRiesgo->servic_reproduc == 'Ingresa Animales' ? 'selected' : '' }}>
                                    Ingresa Animales</option>
                                <option value="Presta Animales"
                                    {{ $caracterizacionRiesgo->servic_reproduc == 'Presta Animales' ? 'selected' : '' }}>
                                    Presta Animales</option>
                                <option value="Biotecnología"
                                    {{ $caracterizacionRiesgo->servic_reproduc == 'Biotecnología' ? 'selected' : '' }}>
                                    Biotecnología</option>
                                <option value="Otro"
                                    {{ $caracterizacionRiesgo->servic_reproduc == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            {!! $errors->first('servic_reproduc', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Especificar Otros Servicios de Reproducción -->
                        <div class="form-group mb-2 mb20" id="servic_reproduc_otro_field"
                            style="display: {{ $caracterizacionRiesgo->servic_reproduc == 'Otro' ? 'block' : 'none' }};">
                            <label for="servic_reproduc_otro"
                                class="form-label">{{ __('Especificar otros servicios de reproducción') }}</label>
                            <input type="text"
                                class="form-control @error('servic_reproduc_otro') is-invalid @enderror"
                                id="servic_reproduc_otro" name="servic_reproduc_otro"
                                value="{{ $caracterizacionRiesgo->servic_reproduc_otro }}">
                            {!! $errors->first('servic_reproduc_otro', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Número de Trabajadores -->
                        <div class="form-group mb-2 mb20">
                            <label for="num_trabajadores"
                                class="form-label">{{ __('Número de trabajadores en el predio') }}</label>
                            <input type="number" class="form-control @error('num_trabajadores') is-invalid @enderror"
                                id="num_trabajadores" name="num_trabajadores"
                                value="{{ $caracterizacionRiesgo->num_trabajadores }}">
                            {!! $errors->first('num_trabajadores', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Trabajadores en Otra Explotación -->
                        <div class="form-group mb-2 mb20">
                            <label for="trabajan_otr_explotacion"
                                class="form-label">{{ __('¿Trabajadores laboran en otra explotación?') }}</label>
                            <select class="form-control @error('trabajan_otr_explotacion') is-invalid @enderror"
                                id="trabajan_otr_explotacion" name="trabajan_otr_explotacion">
                                <option value="">Seleccione</option>
                                <option value="Si"
                                    {{ $caracterizacionRiesgo->trabajan_otr_explotacion == 'Si' ? 'selected' : '' }}>Si
                                </option>
                                <option value="No"
                                    {{ $caracterizacionRiesgo->trabajan_otr_explotacion == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            {!! $errors->first('trabajan_otr_explotacion', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Asistencia Técnica -->
                        <div class="form-group mb-2 mb20">
                            <label for="asistencia_tecnica"
                                class="form-label">{{ __('¿Recibe asistencia técnica?') }}</label>
                            <select class="form-control @error('asistencia_tecnica') is-invalid @enderror"
                                id="asistencia_tecnica" name="asistencia_tecnica"
                                onchange="toggleField(this.value, 'asistencia_tecnica_frecuen_field')">
                                <option value="">Seleccione</option>
                                <option value="Si"
                                    {{ $caracterizacionRiesgo->asistencia_tecnica == 'Si' ? 'selected' : '' }}>Si</option>
                                <option value="No"
                                    {{ $caracterizacionRiesgo->asistencia_tecnica == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                            {!! $errors->first('asistencia_tecnica', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Frecuencia de Asistencia Técnica -->
                        <div class="form-group mb-2 mb20" id="asistencia_tecnica_frecuen_field"
                            style="display: {{ $caracterizacionRiesgo->asistencia_tecnica == 'Si' ? 'block' : 'none' }};">
                            <label for="asistencia_tecnica_frecuen"
                                class="form-label">{{ __('Frecuencia de asistencia técnica') }}</label>
                            <input type="text"
                                class="form-control @error('asistencia_tecnica_frecuen') is-invalid @enderror"
                                id="asistencia_tecnica_frecuen" name="asistencia_tecnica_frecuen"
                                value="{{ $caracterizacionRiesgo->asistencia_tecnica_frecuen }}">
                            {!! $errors->first(
                                'asistencia_tecnica_frecuen',
                                '<div class="invalid-feedback"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Atiende Otros Predios -->
                        <div class="form-group mb-2 mb20">
                            <label for="atiend_otr_predi" class="form-label">{{ __('¿Atiende otros predios?') }}</label>
                            <select class="form-control @error('atiend_otr_predi') is-invalid @enderror"
                                id="atiend_otr_predi" name="atiend_otr_predi"
                                onchange="toggleField(this.value, 'atiend_otr_predi_cual_field')">
                                <option value="">Seleccione</option>
                                <option value="Si"
                                    {{ $caracterizacionRiesgo->atiend_otr_predi == 'Si' ? 'selected' : '' }}>Si</option>
                                <option value="No"
                                    {{ $caracterizacionRiesgo->atiend_otr_predi == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                            {!! $errors->first('atiend_otr_predi', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Cuáles Otros Predios -->
                        <div class="form-group mb-2 mb20" id="atiend_otr_predi_cual_field"
                            style="display: {{ $caracterizacionRiesgo->atiend_otr_predi == 'Si' ? 'block' : 'none' }};">
                            <label for="atiend_otr_predi_cual" class="form-label">{{ __('¿El predio es?') }}</label>
                            <select class="form-control @error('atiend_otr_predi_cual') is-invalid @enderror"
                                id="atiend_otr_predi_cual" name="atiend_otr_predi_cual">
                                <option value="">Seleccione</option>
                                <option value="Oficial"
                                    {{ $caracterizacionRiesgo->atiend_otr_predi_cual == 'Oficial' ? 'selected' : '' }}>
                                    Oficial</option>
                                <option value="Particular"
                                    {{ $caracterizacionRiesgo->atiend_otr_predi_cual == 'Particular' ? 'selected' : '' }}>
                                    Particular</option>
                            </select>
                            {!! $errors->first('atiend_otr_predi_cual', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <button type="submit"
                            class="btn btn-primary">{{ __('Actualizar Caracterización de Riesgo') }}</button>
                    </form>
                @else
                    <form action="{{ route('caracterizacion_riesgo.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_predio" value="{{ $predioId }}">

                        <!-- Colindancia con Establecimientos de Riesgo -->
                        <div class="form-group mb-2 mb20">
                            <label for="colinda_establecim_riesgo"
                                class="form-label">{{ __('El predio colinda con establecimiento de riesgo (Feria, Paradero, PBA, acopio, basurero, curtiembre, etc.)') }}</label>
                            <select class="form-control @error('colinda_establecim_riesgo') is-invalid @enderror"
                                id="colinda_establecim_riesgo" name="colinda_establecim_riesgo"
                                onchange="toggleField(this.value, 'colinda_establecim_cual_field')" required>
                                <option value="">Seleccione</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            {!! $errors->first('colinda_establecim_riesgo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Cuál Establecimiento -->
                        <div class="form-group mb-2 mb20" id="colinda_establecim_cual_field" style="display: none;">
                            <label for="colinda_establecim_cual"
                                class="form-label">{{ __('¿Cuál establecimiento?') }}</label>
                            <input type="text"
                                class="form-control @error('colinda_establecim_cual') is-invalid @enderror"
                                id="colinda_establecim_cual" name="colinda_establecim_cual">
                            {!! $errors->first('colinda_establecim_cual', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Ubicación en Vía -->
                        <div class="form-group mb-2 mb20">
                            <label for="ubica_en_via" class="form-label">{{ __('El predio se ubica en vía:') }}</label>
                            <select class="form-control @error('ubica_en_via') is-invalid @enderror" id="ubica_en_via"
                                name="ubica_en_via">
                                <option value="">Seleccione</option>
                                <option value="Nacional">Nacional</option>
                                <option value="Secundaria">Secundaria</option>
                                <option value="Veredal">Veredal</option>
                                <option value="Tiene servidumbre">Tiene servidumbre</option>
                            </select>
                            {!! $errors->first('ubica_en_via', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Alimentación de Animales -->
                        <div class="form-group mb-2 mb20">
                            <label for="alimen_animal" class="form-label">{{ __('Alimentación de animales') }}</label>
                            <select class="form-control @error('alimen_animal') is-invalid @enderror" id="alimen_animal"
                                name="alimen_animal" onchange="toggleField(this.value, 'alimen_animl_otro_field')">
                                <option value="">Seleccione</option>
                                <option value="Pastoreo">Pastoreo</option>
                                <option value="Concentrado">Concentrado</option>
                                <option value="Heno">Heno</option>
                                <option value="Subproductos">Subproductos</option>
                                <option value="Otro">Otro</option>
                            </select>
                            {!! $errors->first('alimen_animal', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Especificar Otro Alimento -->
                        <div class="form-group mb-2 mb20" id="alimen_animl_otro_field" style="display: none;">
                            <label for="alimen_animl_otro" the form-label">{{ __('Especificar otro alimento') }}</label>
                            <input type="text" class="form-control @error('alimen_animl_otro') is-invalid @enderror"
                                id="alimen_animl_otro" name="alimen_animl_otro">
                            {!! $errors->first('alimen_animl_otro', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Lavazas de Desperdicios Alimentarios Porcinos -->
                        <div class="form-group mb-2 mb20">
                            <label for="lavazas_desper_alimen_porc"
                                class="form-label">{{ __('Suministra lavazas y/o desperdicios de alimentación humana en la alimentación de porcinos') }}</label>
                            <select class="form-control @error('lavazas_desper_alimen_porc') is-invalid @enderror"
                                id="lavazas_desper_alimen_porc" name="lavazas_desper_alimen_porc">
                                <option value="">Seleccione</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            {!! $errors->first(
                                'lavazas_desper_alimen_porc',
                                '<div class="invalid-feedback"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Realización de Cocción Previa -->
                        <div class="form-group mb-2 mb20">
                            <label for="real_coccion_previa"
                                class="form-label">{{ __('Realiza cocción previa') }}</label>
                            <select class="form-control @error('real_coccion_previa') is-invalid @enderror"
                                id="real_coccion_previa" name="real_coccion_previa">
                                <option value="">Seleccione</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            {!! $errors->first('real_coccion_previa', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Sacrificio de Animales en el Predio -->
                        <div class="form-group mb-2 mb20">
                            <label for="sacrif_anim_pred"
                                class="form-label">{{ __('¿Se realiza sacrificio de animales en el predio?') }}</label>
                            <select class="form-control @error('sacrif_anim_pred') is-invalid @enderror"
                                id="sacrif_anim_pred" name="sacrif_anim_pred"
                                onchange="toggleField(this.value, 'sacrif_anim_pred_periodic_field')">
                                <option value="">Seleccione</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            {!! $errors->first('sacrif_anim_pred', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Periodicidad del Sacrificio -->
                        <div class="form-group mb-2 mb20" id="sacrif_anim_pred_periodic_field" style="display: none;">
                            <label for="sacrif_anim_pred_periodic"
                                class="form-label">{{ __('Periodicidad del sacrificio') }}</label>
                            <input type="text"
                                class="form-control @error('sacrif_anim_pred_periodic') is-invalid @enderror"
                                id="sacrif_anim_pred_periodic" name="sacrif_anim_pred_periodic">
                            {!! $errors->first('sacrif_anim_pred_periodic', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Servicios de Reproducción -->
                        <div class="form-group mb-2 mb20">
                            <label for="servic_reproduc"
                                class="form-label">{{ __('¿Servicio de reproducción?') }}</label>
                            <select class="form-control @error('servic_reproduc') is-invalid @enderror"
                                id="servic_reproduc" name="servic_reproduc"
                                onchange="toggleField(this.value, 'servic_reproduc_otro_field')">
                                <option value="">Seleccione</option>
                                <option value="Propio">Propio</option>
                                <option value="Ingresa Animales">Ingresa Animales</option>
                                <option value="Presta Animales">Presta Animales</option>
                                <option value="Biotecnología">Biotecnología</option>
                                <option value="Otro">Otro</option>
                            </select>
                            {!! $errors->first('servic_reproduc', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Especificar Otros Servicios de Reproducción -->
                        <div class="form-group mb-2 mb20" id="servic_reproduc_otro_field" style="display: none;">
                            <label for="servic_reproduc_otro"
                                class="form-label">{{ __('Especificar otros servicios de reproducción') }}</label>
                            <input type="text"
                                class="form-control @error('servic_reproduc_otro') is-invalid @enderror"
                                id="servic_reproduc_otro" name="servic_reproduc_otro">
                            {!! $errors->first('servic_reproduc_otro', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Número de Trabajadores -->
                        <div class="form-group mb-2 mb20">
                            <label for="num_trabajadores"
                                class="form-label">{{ __('Número de trabajadores en el predio') }}</label>
                            <input type="number" class="form-control @error('num_trabajadores') is-invalid @enderror"
                                id="num_trabajadores" name="num_trabajadores">
                            {!! $errors->first('num_trabajadores', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Trabajadores en Otra Explotación -->
                        <div class="form-group mb-2 mb20">
                            <label for="trabajan_otr_explotacion"
                                class="form-label">{{ __('¿Trabajadores laboran en otra explotación?') }}</label>
                            <select class="form-control @error('trabajan_otr_explotacion') is-invalid @enderror"
                                id="trabajan_otr_explotacion" name="trabajan_otr_explotacion">
                                <option value="">Seleccione</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            {!! $errors->first('trabajan_otr_explotacion', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Asistencia Técnica -->
                        <div class="form-group mb-2 mb20">
                            <label for="asistencia_tecnica"
                                class="form-label">{{ __('¿Recibe asistencia técnica?') }}</label>
                            <select class="form-control @error('asistencia_tecnica') is-invalid @enderror"
                                id="asistencia_tecnica" name="asistencia_tecnica"
                                onchange="toggleField(this.value, 'asistencia_tecnica_frecuen_field')">
                                <option value="">Seleccione</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            {!! $errors->first('asistencia_tecnica', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Frecuencia de Asistencia Técnica -->
                        <div class="form-group mb-2 mb20" id="asistencia_tecnica_frecuen_field" style="display: none;">
                            <label for="asistencia_tecnica_frecuen"
                                class="form-label">{{ __('Frecuencia de asistencia técnica') }}</label>
                            <input type="text"
                                class="form-control @error('asistencia_tecnica_frecuen') is-invalid @enderror"
                                id="asistencia_tecnica_frecuen" name="asistencia_tecnica_frecuen">
                            {!! $errors->first(
                                'asistencia_tecnica_frecuen',
                                '<div class="invalid-feedback"><strong>:message</strong></div>',
                            ) !!}
                        </div>

                        <!-- Atiende Otros Predios -->
                        <div class="form-group mb-2 mb20">
                            <label for="atiend_otr_predi" class="form-label">{{ __('¿ Atiende otros predios?') }}</label>
                            <select class="form-control @error('atiend_otr_predi') is-invalid @enderror"
                                id="atiend_otr_predi" name="atiend_otr_predi"
                                onchange="toggleField(this.value, 'atiend_otr_predi_cual_field')" required>
                                <option value="">Seleccione</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            {!! $errors->first('atiend_otr_predi', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <!-- Cuáles Otros Predios -->
                        <div class="form-group mb-2 mb20" id="atiend_otr_predi_cual_field" style="display: none;">
                            <label for="atiend_otr_predi_cual" class="form-label">{{ __('¿El predio es ?') }}</label>
                            <select class="form-control @error('atiend_otr_predi_cual') is-invalid @enderror"
                                id="atiend_otr_predi_cual" name="atiend_otr_predi_cual">
                                <option value="">Seleccione</option>
                                <option value="Oficial">Oficial</option>
                                <option value="Particular">Particular</option>
                            </select>
                            {!! $errors->first('atiend_otr_predi_cual', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                        </div>

                        <button type="submit"
                            class="btn btn-primary">{{ __('Registrar Caracterización de Riesgo') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleField(value, fieldId) {
            var field = document.getElementById(fieldId);
            field.style.display = (value === 'Si' || value === 'Otro') ? 'block' : 'none';
            if ((value !== 'Si' && value !== 'Otro') && field.tagName === 'INPUT') {
                field.value = 'No Aplica'; // Resetea el valor de los campos de texto si no son aplicables.
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Setup initial visibility based on current value of selects when the page loads
            manageInitialVisibility('colinda_establecim_riesgo', 'colinda_establecim_cual_field');
            manageInitialVisibility('alimen_animal', 'alimen_animl_otro_field');
            manageInitialVisibility('sacrif_anim_pred', 'sacrif_anim_pred_periodic_field');
            manageInitialVisibility('servic_reproduc', 'servic_reproduc_otro_field');
            manageInitialVisibility('asistencia_tecnica', 'asistencia_tecnica_frecuen_field');
            manageInitialVisibility('atiend_otr_predi', 'atiend_otr_predi_cual_field');

            // Attach event listeners for fields that require conditional visibility
            attachChangeListener('colinda_establecim_riesgo', 'colinda_establecim_cual_field');
            attachChangeListener('alimen_animal', 'alimen_animl_otro_field');
            attachChangeListener('sacrif_anim_pred', 'sacrif_anim_pred_periodic_field');
            attachChangeListener('servic_reproduc', 'servic_reproduc_otro_field');
            attachChangeListener('asistencia_tecnica', 'asistencia_tecnica_frecuen_field');
            attachChangeListener('atiend_otr_predi', 'atiend_otr_predi_cual_field');
        });

        function manageInitialVisibility(selectId, fieldId) {
            var select = document.getElementById(selectId);
            var selectedValue = select.options[select.selectedIndex].value;
            toggleField(selectedValue, fieldId);
        }

        function attachChangeListener(selectId, fieldId) {
            var select = document.getElementById(selectId);
            select.addEventListener('change', function() {
                toggleField(this.value, fieldId);
            });
        }
    </script>
@endsection
