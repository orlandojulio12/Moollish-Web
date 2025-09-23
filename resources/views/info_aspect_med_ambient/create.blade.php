@extends('layouts')

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

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId ) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion2', $predioId ) }}">Informacion Del Predio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Información de aspecto medio ambientales</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection
<!-- Basic Data Tables -->
<!--===================================================-->
<div class="content-area-body">
    <div class="card mb-0">
        <div class="card-body">
            <div class="panel-body">
                @if ($InfoExists)
                    <form action="{{ route('info_aspect_med_ambient.update', $informAspectMedAmbient->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id_predio" value="{{ $predioId}}">

                        <div class="form-group">
                            <label for="dispos_aguas_servid">Disposición de Aguas Servidas</label>
                            <select name="dispos_aguas_servid"
                                class="form-control @error('dispos_aguas_servid') is-invalid @enderror"
                                id="dispos_aguas_servid">
                                <option value="">Seleccione</option>
                                <option value="POZO SÉPTICO"
                                    {{ $informAspectMedAmbient->dispos_aguas_servid == 'POZO SÉPTICO' ? 'selected' : '' }}>
                                    POZO SÉPTICO</option>
                                <option value="TRATAMIENTO"
                                    {{ $informAspectMedAmbient->dispos_aguas_servid == 'TRATAMIENTO' ? 'selected' : '' }}>
                                    TRATAMIENTO</option>
                                <option value="CAUSE NATURAL"
                                    {{ $informAspectMedAmbient->dispos_aguas_servid == 'CAUSE NATURAL' ? 'selected' : '' }}>
                                    CAUSE NATURAL</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="dispos_excrement_bovinos">Disposición de Excrementos de Bovinos</label>
                            <select name="dispos_excrement_bovinos"
                                class="form-control @error('dispos_excrement_bovinos') is-invalid @enderror"
                                id="dispos_excrement_bovinos">
                                <option value="">Seleccione</option>
                                <option value="ESTERCOLERO"
                                    {{ $informAspectMedAmbient->dispos_excrement_bovinos == 'ESTERCOLERO' ? 'selected' : '' }}>
                                    ESTERCOLERO</option>
                                <option value="ABONO"
                                    {{ $informAspectMedAmbient->dispos_excrement_bovinos == 'ABONO' ? 'selected' : '' }}>
                                    ABONO</option>
                                <option value="BIOGÁS"
                                    {{ $informAspectMedAmbient->dispos_excrement_bovinos == 'BIOGÁS' ? 'selected' : '' }}>
                                    BIOGÁS</option>
                                <option value="COMPOST"
                                    {{ $informAspectMedAmbient->dispos_excrement_bovinos == 'COMPOST' ? 'selected' : '' }}>
                                    COMPOST</option>
                                <option value="OTRO"
                                    {{ $informAspectMedAmbient->dispos_excrement_bovinos == 'OTRO' ? 'selected' : '' }}>
                                    OTRO</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="manejo_basuras">Manejo de Basuras</label>
                            <select name="manejo_basuras"
                                class="form-control @error('manejo_basuras') is-invalid @enderror" id="manejo_basuras">
                                <option value="">Seleccione</option>
                                <option value="QUEMA"
                                    {{ $informAspectMedAmbient->manejo_basuras == 'QUEMA' ? 'selected' : '' }}>QUEMA
                                </option>
                                <option value="RECICLAJE"
                                    {{ $informAspectMedAmbient->manejo_basuras == 'RECICLAJE' ? 'selected' : '' }}>
                                    RECICLAJE</option>
                                <option value="RELLENO SANITARIO"
                                    {{ $informAspectMedAmbient->manejo_basuras == 'RELLENO SANITARIO' ? 'selected' : '' }}>
                                    RELLENO SANITARIO</option>
                                <option value="BOTADERO"
                                    {{ $informAspectMedAmbient->manejo_basuras == 'BOTADERO' ? 'selected' : '' }}>
                                    BOTADERO</option>
                                <option value="ENTIERRA"
                                    {{ $informAspectMedAmbient->manejo_basuras == 'ENTIERRA' ? 'selected' : '' }}>
                                    ENTIERRA</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="manejo_empaq_produc_quimic">Manejo de Empaques de Productos Químicos</label>
                            <select name="manejo_empaq_produc_quimic"
                                class="form-control @error('manejo_empaq_produc_quimic') is-invalid @enderror"
                                id="manejo_empaq_produc_quimic">
                                <option value="">Seleccione</option>
                                <option value="QUEMA"
                                    {{ $informAspectMedAmbient->manejo_empaq_produc_quimic == 'QUEMA' ? 'selected' : '' }}>
                                    QUEMA</option>
                                <option value="ENTIERRA"
                                    {{ $informAspectMedAmbient->manejo_empaq_produc_quimic == 'ENTIERRA' ? 'selected' : '' }}>
                                    ENTIERRA</option>
                                <option value="OTRO"
                                    {{ $informAspectMedAmbient->manejo_empaq_produc_quimic == 'OTRO' ? 'selected' : '' }}>
                                    OTRO</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 10px">Actualizar</button>
                    </form>
                @else
                    <form action="{{ route('info_aspect_med_ambient.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_predio" value="{{ $predioId }}">

                        <div class="form-group">
                            <label for="dispos_aguas_servid">Disposición de Aguas Servidas</label>

                            <select name="dispos_aguas_servid"
                                class="form-control @error('dispos_aguas_servid') is-invalid @enderror"
                                id="dispos_aguas_servid">
                                <option value="">Seleccione</option>
                                <option value="POZO SÉPTICO"
                                    {{ old('dispos_aguas_servid') == 'POZO SÉPTICO' ? 'selected' : '' }}>POZO SÉPTICO
                                </option>
                                <option value="TRATAMIENTO"
                                    {{ old('dispos_aguas_servid') == 'TRATAMIENTO' ? 'selected' : '' }}>
                                    TRATAMIENTO</option>
                                <option value="CAUSE NATURAL"
                                    {{ old('dispos_aguas_servid') == 'CAUSE NATURAL' ? 'selected' : '' }}>CAUSE NATURAL
                                </option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="dispos_excrement_bovinos">Disposición de Excrementos de Bovinos</label>

                            <select name="dispos_excrement_bovinos"
                                class="form-control @error('dispos_excrement_bovinos') is-invalid @enderror"
                                id="dispos_excrement_bovinos">
                                <option value="">Seleccione</option>
                                <option value="ESTERCOLERO"
                                    {{ old('dispos_excrement_bovinos') == 'ESTERCOLERO' ? 'selected' : '' }}>
                                    ESTERCOLERO
                                </option>
                                <option value="ABONO"
                                    {{ old('dispos_excrement_bovinos') == 'ABONO' ? 'selected' : '' }}>
                                    ABONO</option>
                                <option value="BIOGÁS"
                                    {{ old('dispos_excrement_bovinos') == 'BIOGÁS' ? 'selected' : '' }}>
                                    BIOGÁS
                                </option>
                                <option value="COMPOST"
                                    {{ old('dispos_excrement_bovinos') == 'COMPOST' ? 'selected' : '' }}>
                                    COMPOST
                                </option>
                                <option value="OTRO"
                                    {{ old('dispos_excrement_bovinos') == 'OTRO' ? 'selected' : '' }}>
                                    OTRO
                                </option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="manejo_basuras">Manejo de Basuras</label>
                            <select name="manejo_basuras"
                                class="form-control @error('manejo_basuras') is-invalid @enderror" id="manejo_basuras">
                                <option value="">Seleccione</option>
                                <option value="QUEMA" {{ old('manejo_basuras') == 'QUEMA' ? 'selected' : '' }}>QUEMA
                                </option>
                                <option value="RECICLAJE" {{ old('manejo_basuras') == 'RECICLAJE' ? 'selected' : '' }}>
                                    RECICLAJE</option>
                                <option value="RELLENO SANITARIO"
                                    {{ old('manejo_basuras') == 'RELLENO SANITARIO' ? 'selected' : '' }}>
                                    RELLENO SANITARIO
                                </option>
                                <option value="BOTADERO" {{ old('manejo_basuras') == 'BOTADERO' ? 'selected' : '' }}>
                                    BOTADERO
                                </option>
                                <option value="ENTIERRA" {{ old('manejo_basuras') == 'ENTIERRA' ? 'selected' : '' }}>
                                    ENTIERRA
                                </option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="manejo_empaq_produc_quimic">Manejo de Empaques de Productos Químicos</label>
                            <select name="manejo_empaq_produc_quimic"
                                class="form-control @error('manejo_empaq_produc_quimic') is-invalid @enderror"
                                id="manejo_empaq_produc_quimic">
                                <option value="">Seleccione</option>
                                <option value="QUEMA"
                                    {{ old('manejo_empaq_produc_quimic') == 'QUEMA' ? 'selected' : '' }}>QUEMA
                                </option>

                                <option value="ENTIERRA"
                                    {{ old('manejo_empaq_produc_quimic') == 'ENTIERRA' ? 'selected' : '' }}>
                                    ENTIERRA
                                </option>
                                <option value="OTRO"
                                    {{ old('manejo_empaq_produc_quimic') == 'OTRO' ? 'selected' : '' }}>
                                    OTRO
                                </option>

                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 10px">Siguiente</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
