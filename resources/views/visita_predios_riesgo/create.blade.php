@extends('layouts')



@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion4', $predioId) }}">Risesgo Epidemiologico</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Visita a predios de riesgo</li>
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
                <form action="{{ route('visita_predios_riesgo.update', $visita->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_predio" value="{{ $visita->id_predio }}">

                    <!-- Enfermedades Bajo Vigilancia -->
                    <div class="form-group mb-2 mb20">
                        <label for="enferm_baj_vigil" class="form-label">{{ __('Enfermedades bajo vigilancia en el predio') }}</label>
                        <input type="text" class="form-control @error('enferm_baj_vigil') is-invalid @enderror" id="enferm_baj_vigil" name="enferm_baj_vigil" value="{{ $visita->enferm_baj_vigil }}" required>
                        {!! $errors->first('enferm_baj_vigil', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Especie -->
                    <div class="form-group mb-2 mb20">
                        <label for="especie" class="form-label">{{ __('Especies inspeccionadas') }}</label>
                        <input type="text" class="form-control @error('especie') is-invalid @enderror" id="especie" name="especie" value="{{ $visita->especie }}" required>
                        {!! $errors->first('especie', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Número de Animales Inspeccionados -->
                    <div class="form-group mb-2 mb20">
                        <label for="num_anim_inspec" class="form-label">{{ __('Número de animales inspeccionados') }}</label>
                        <input type="text" class="form-control @error('num_anim_inspec') is-invalid @enderror" id="num_anim_inspec" name="num_anim_inspec" value="{{ $visita->num_anim_inspec }}" required>
                        {!! $errors->first('num_anim_inspec', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Toma de Muestras -->
                    <div class="form-group mb-2 mb20">
                        <label for="toma_muestras" class="form-label">{{ __('¿Se realizaron tomas de muestras?') }}</label>
                        <select class="form-control @error('toma_muestras') is-invalid @enderror" id="toma_muestras" name="toma_muestras" onchange="toggleMuestraFields(this.value)" required>
                            <option value="">Seleccione</option>
                            <option value="Si" {{ $visita->toma_muestras == 'Si' ? 'selected' : '' }}>Si</option>
                            <option value="No" {{ $visita->toma_muestras == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        {!! $errors->first('toma_muestras', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Tipo de Muestras -->
                    <div class="form-group mb-2 mb20" id="toma_muestra_tipo_field" style="display: {{ $visita->toma_muestras == 'Si' ? 'block' : 'none' }};">
                        <label for="toma_muestra_tipo" class="form-label">{{ __('Tipo de muestras tomadas') }}</label>
                        <input type="text" class="form-control @error('toma_muestra_tipo') is-invalid @enderror" id="toma_muestra_tipo" name="toma_muestra_tipo" value="{{ $visita->toma_muestra_tipo }}">
                        {!! $errors->first('toma_muestra_tipo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Número de Muestras -->
                    <div class="form-group mb-2 mb20" id="num_muestras_field" style="display: {{ $visita->toma_muestras == 'Si' ? 'block' : 'none' }};">
                        <label for="num_muestras" class="form-label">{{ __('Número de muestras tomadas') }}</label>
                        <input type="number" class="form-control @error('num_muestras') is-invalid @enderror" id="num_muestras" name="num_muestras" value="{{ $visita->num_muestras }}">
                        {!! $errors->first('num_muestras', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Actualizar') }}</button>
                </form>

                @else
                <form action="{{route('visita_predios_riesgo.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id_predio" value="{{ $predioId }}">

                    <!-- Enfermedades Bajo Vigilancia -->
                    <div class="form-group mb-2 mb20">
                        <label for="enferm_baj_vigil" class="form-label">{{ __('Enfermedades bajo vigilancia en el predio') }}</label>
                        <input type="text" class="form-control @error('enferm_baj_vigil') is-invalid @enderror" id="enferm_baj_vigil" name="enferm_baj_vigil" placeholder="Ingrese enfermedades" required>
                        {!! $errors->first('enferm_baj_vigil', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Especie -->
                    <div class="form-group mb-2 mb20">
                        <label for="especie" class="form-label">{{ __('Especies inspeccionadas') }}</label>
                        <input type="text" class="form-control @error('especie') is-invalid @enderror" id="especie" name="especie" placeholder="Describa las especies" required>
                        {!! $errors->first('especie', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Número de Animales Inspeccionados -->
                    <div class="form-group mb-2 mb20">
                        <label for="num_anim_inspec" class="form-label">{{ __('Número de animales inspeccionados') }}</label>
                        <input type="text" class="form-control @error('num_anim_inspec') is-invalid @enderror" id="num_anim_inspec" name="num_anim_inspec" placeholder="Ingrese el número" required>
                        {!! $errors->first('num_anim_inspec', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Toma de Muestras -->
                    <div class="form-group mb-2 mb20">
                        <label for="toma_muestras" class="form-label">{{ __('¿Se realizaron tomas de muestras?') }}</label>
                        <select class="form-control @error('toma_muestras') is-invalid @enderror" id="toma_muestras" name="toma_muestras" onchange="toggleMuestraFields(this.value)" required>
                            <option value="">Seleccione</option>
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>
                        {!! $errors->first('toma_muestras', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Tipo de Muestras -->
                    <div class="form-group mb-2 mb20" id="toma_muestra_tipo_field" style="display: none;">
                        <label for="toma_muestra_tipo" class="form-label">{{ __('Tipo de muestras tomadas') }}</label>
                        <input type="text" class="form-control @error('toma_muestra_tipo') is-invalid @enderror" id="toma_muestra_tipo" name="toma_muestra_tipo" placeholder="Describa el tipo de muestras">
                        {!! $errors->first('toma_muestra_tipo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <!-- Número de Muestras -->
                    <div class="form-group mb-2 mb20" id="num_muestras_field" style="display: none;">
                        <label for="num_muestras" class="form-label">{{ __('Número de muestras tomadas') }}</label>
                        <input type="number" class="form-control @error('num_muestras') is-invalid @enderror" id="num_muestras" name="num_muestras" placeholder="Ingrese el número de muestras">
                        {!! $errors->first('num_muestras', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
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
    function toggleMuestraFields(value) {
        var muestraTipoField = document.getElementById('toma_muestra_tipo_field');
        var muestraNumField = document.getElementById('num_muestras_field');
        muestraTipoField.style.display = muestraNumField.style.display = (value === 'Si') ? 'block' : 'none';
        if (value !== 'Si') {
            if (muestraTipoField.tagName === 'INPUT') {
                muestraTipoField.value = ''; // Clear the input field when not applicable
            }
            if (muestraNumField.tagName === 'INPUT') {
                muestraNumField.value = ''; // Clear the input field when not applicable
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Setup initial visibility based on current value of selects when the page loads
        manageInitialVisibility('toma_muestras');
    });

    function manageInitialVisibility(selectId) {
        var select = document.getElementById(selectId);
        var selectedValue = select.options[select.selectedIndex].value;
        toggleMuestraFields(selectedValue);
    }
</script>
@endsection
