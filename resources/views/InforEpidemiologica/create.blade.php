@extends('layouts')

@section('template_title')
    Información Epidemiológica
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion4', $predioId) }}">Risesgo Epidemiologico</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Información Epidemiológica</li>
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
            <form action="{{ route('inforEpidemiologica.update', $inforEpidemiologica->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_predio" value="{{ $inforEpidemiologica->id_predio }}">

                <!-- Animales con Enfermedad Controlada -->
                <div class="form-group mb-2 mb20">
                    <label for="anim_enferm_control" class="form-label">{{ __('Se encontraron animales con signología compatible con enfermedades de control oficial') }}</label>
                    <select class="form-control @error('anim_enferm_control') is-invalid @enderror" id="anim_enferm_control" name="anim_enferm_control" onchange="toggleCantidadField(this.value)" required>
                        <option value="">Seleccione</option>
                        <option value="Si" {{ $inforEpidemiologica->anim_enferm_control == 'Si' ? 'selected' : '' }}>Si</option>
                        <option value="No" {{ $inforEpidemiologica->anim_enferm_control == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                    {!! $errors->first('anim_enferm_control', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>

                <!-- Cantidad -->
                <div class="form-group mb-2 mb20" id="anim_enferm_control_cant_field" style="display: {{ $inforEpidemiologica->anim_enferm_control == 'Si' ? 'block' : 'none' }};">
                    <label for="anim_enferm_control_cant" class="form-label">{{ __('Indique Cantidad de Animales con signología') }}</label>
                    <input type="number" class="form-control @error('anim_enferm_control_cant') is-invalid @enderror" id="anim_enferm_control_cant" name="anim_enferm_control_cant" value="{{ $inforEpidemiologica->anim_enferm_control_cant }}">
                    {!! $errors->first('anim_enferm_control_cant', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>

                <!-- Cuadro Clínico Sospechoso -->
                <div class="form-group mb-2 mb20">
                    <label for="cuadr_clinc_sospec" class="form-label">{{ __('Cuadro Clínico Sospechoso') }}</label>
                    <input type="text" class="form-control @error('cuadr_clinc_sospec') is-invalid @enderror" id="cuadr_clinc_sospec" name="cuadr_clinc_sospec" value="{{ $inforEpidemiologica->cuadr_clinc_sospec }}" required>
                    {!! $errors->first('cuadr_clinc_sospec', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>

                <!-- Especies Afectadas -->
                <div class="form-group mb-2 mb20">
                    <label for="especies_afectadas" class="form-label">{{ __('Especies Afectadas') }}</label>
                    <input type="text" class="form-control @error('especies_afectadas') is-invalid @enderror" id="especies_afectadas" name="especies_afectadas" value="{{ $inforEpidemiologica->especies_afectadas }}" required>
                    {!! $errors->first('especies_afectadas', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>

                <!-- Toma de Muestra -->
                <div class="form-group mb-2 mb20">
                    <label for="toma_muestra" class="form-label">{{ __('Toma de Muestra') }}</label>
                    <select class="form-control @error('toma_muestra') is-invalid @enderror" id="toma_muestra" name="toma_muestra" onchange="toggleMuestraFields(this.value)" required>
                        <option value="">Seleccione</option>
                        <option value="Si" {{ $inforEpidemiologica->toma_muestra == 'Si' ? 'selected' : '' }}>Si</option>
                        <option value="No" {{ $inforEpidemiologica->toma_muestra == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                    {!! $errors->first('toma_muestra', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>

                <!-- Tipos de Muestras -->
                <div class="form-group mb-2 mb20" id="toma_muestra_tipos_field" style="display: {{ $inforEpidemiologica->toma_muestra == 'Si' ? 'block' : 'none' }};">
                    <label for="toma_muestra_tipos" class="form-label">{{ __('Tipos de Muestras') }}</label>
                    <input type="text" class="form-control @error('toma_muestra_tipos') is-invalid @enderror" id="toma_muestra_tipos" name="toma_muestra_tipos" value="{{ $inforEpidemiologica->toma_muestra_tipos }}">
                    {!! $errors->first('toma_muestra_tipos', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>

                <!-- Número de Muestras -->
                <div class="form-group mb-2 mb20" id="toma_muestra_numeros_field" style="display: {{ $inforEpidemiologica->toma_muestra == 'Si' ? 'block' : 'none' }};">
                    <label for="toma_muestra_numeros" class="form-label">{{ __('Número de Muestras') }}</label>
                    <input type="number" class="form-control @error('toma_muestra_numeros') is-invalid @enderror" id="toma_muestra_numeros" name="toma_muestra_numeros" value="{{ $inforEpidemiologica->toma_muestra_numeros }}">
                    {!! $errors->first('toma_muestra_numeros', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Actualizar') }}</button>
            </form>

                @else
                <form action="{{ route('inforEpidemiologica.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_predio" value="{{ $predioId }}">

                    <!-- Animales con Enfermedad Controlada -->
                    <div class="form-group mb-2 mb20">
                        <label for="anim_enferm_control" class="form-label">{{ __('Se encontraron animales con signología compatible con enfermedades de control oficial') }}</label>
                        <select class="form-control @error('anim_enferm_control') is-invalid @enderror" id="anim_enferm_control" name="anim_enferm_control" onchange="toggleCantidadField(this.value)" required>
                            <option value="">Seleccione</option>
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>
                        {!! $errors->first('anim_enferm_control', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Cantidad -->
                    <div class="form-group mb-2 mb20" id="anim_enferm_control_cant_field" style="display: none;">
                        <label for="anim_enferm_control_cant" class="form-label">{{ __('Indique Cantidad de Animales con signología') }}</label>
                        <input type="number" class="form-control @error('anim_enferm_control_cant') is-invalid @enderror" id="anim_enferm_control_cant" name="anim_enferm_control_cant">
                        {!! $errors->first('anim_enferm_control_cant', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Cuadro Clínico Sospechoso -->
                    <div class="form-group mb-2 mb20">
                        <label for="cuadr_clinc_sospec" class="form-label">{{ __('Cuadro Clínico Sospechoso') }}</label>
                        <input type="text" class="form-control @error('cuadr_clinc_sospec') is-invalid @enderror" id="cuadr_clinc_sospec" name="cuadr_clinc_sospec" placeholder="Descripción" required>
                        {!! $errors->first('cuadr_clinc_sospec', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Especies Afectadas -->
                    <div class="form-group mb-2 mb20">
                        <label for="especies_afectadas" class="form-label">{{ __('Especies Afectadas') }}</label>
                        <input type="text" class="form-control @error('especies_afectadas') is-invalid @enderror" id="especies_afectadas" name="especies_afectadas" placeholder="Especificar especies" required>
                        {!! $errors->first('especies_afectadas', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Toma de Muestra -->
                    <div class="form-group mb-2 mb20">
                        <label for="toma_muestra" class="form-label">{{ __('Toma de Muestra') }}</label>
                        <select class="form-control @error('toma_muestra') is-invalid @enderror" id="toma_muestra" name="toma_muestra" onchange="toggleMuestraFields(this.value)" required>
                            <option value="">Seleccione</option>
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>
                        {!! $errors->first('toma_muestra', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Tipos de Muestras -->
                    <div class="form-group mb-2 mb20" id="toma_muestra_tipos_field" style="display: none;">
                        <label for="toma_muestra_tipos" class="form-label">{{ __('Tipos de Muestras') }}</label>
                        <input type="text" class="form-control @error('toma_muestra_tipos') is-invalid @enderror" id="toma_muestra_tipos" name="toma_muestra_tipos" placeholder="Especificar tipos">
                        {!! $errors->first('toma_muestra_tipos', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Número de Muestras -->
                    <div class="form-group mb-2 mb20" id="toma_muestra_numeros_field" style="display: none;">
                        <label for="toma_muestra_numeros" class="form-label">{{ __('Número de Muestras') }}</label>
                        <input type="number" class="form-control @error('toma_muestra_numeros') is-invalid @enderror" id="toma_muestra_numeros" name="toma_muestra_numeros" placeholder="Cantidad">
                        {!! $errors->first('toma_muestra_numeros', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
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
        function toggleCantidadField(value) {
            var field = document.getElementById('anim_enferm_control_cant_field');
            field.style.display = (value === 'Si') ? 'block' : 'none';
            if (value !== 'Si') {
                document.getElementById('anim_enferm_control_cant').value = 'No Aplica';
            }
        }

        function toggleMuestraFields(value) {
            var muestraTiposField = document.getElementById('toma_muestra_tipos_field');
            var muestraNumerosField = document.getElementById('toma_muestra_numeros_field');
            muestraTiposField.style.display = muestraNumerosField.style.display = (value === 'Si') ? 'block' : 'none';
            if (value !== 'Si') {
                document.getElementById('toma_muestra_tipos').value = 'No Aplica';
                document.getElementById('toma_muestra_numeros').value = 'No Aplica';
            }
        }
    </script>
@endsection
