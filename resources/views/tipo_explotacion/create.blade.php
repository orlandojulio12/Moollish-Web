@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion4', $predioId) }}">Risesgo Epidemiologico</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tipo de Explotación</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')


<div class="content-area-body">
    <div class="card mb-0">
        <div class="card-body">
            @if ($TiposExists)
            <form action="{{ route('tipo_explotacion.update', $explotacion->id) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_predio" value="{{ $explotacion->id_predio }}">

                <div class="row">
                    <!-- Bovinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bovinos">Bovinos:</label>
                            <select class="form-control" id="bovinos" name="bovinos" required>
                                <option value="">Seleccione</option>
                                <option value="Cria" {{ $explotacion->bovinos == 'Cria' ? 'selected' : '' }}>Cria</option>
                                <option value="Levante" {{ $explotacion->bovinos == 'Levante' ? 'selected' : '' }}>Levante</option>
                                <option value="Ceba" {{ $explotacion->bovinos == 'Ceba' ? 'selected' : '' }}>Ceba</option>
                                <option value="Lecheria" {{ $explotacion->bovinos == 'Lecheria' ? 'selected' : '' }}>Lecheria</option>
                                <option value="Ciclo completo" {{ $explotacion->bovinos == 'Ciclo completo' ? 'selected' : '' }}>Ciclo completo</option>
                                <option value="Estabulacion/Semi" {{ $explotacion->bovinos == 'Estabulacion/Semi' ? 'selected' : '' }}>Estabulación/Semi</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bufalinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bufalinos">Bufalinos:</label>
                            <select class="form-control" id="bufalinos" name="bufalinos" required>
                                <option value="">Seleccione</option>
                                <option value="Acopio Leche" {{ $explotacion->bufalinos == 'Acopio Leche' ? 'selected' : '' }}>Acopio Leche</option>
                                <option value="Curtiembre" {{ $explotacion->bufalinos == 'Curtiembre' ? 'selected' : '' }}>Curtiembre</option>
                                <option value="Bascula" {{ $explotacion->bufalinos == 'Bascula' ? 'selected' : '' }}>Báscula</option>
                                <option value="Alquiler corrales" {{ $explotacion->bufalinos == 'Alquiler corrales' ? 'selected' : '' }}>Alquiler corrales</option>
                                <option value="Reproducción" {{ $explotacion->bufalinos == 'Reproducción' ? 'selected' : '' }}>Reproducción</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Porcinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="porcinos">Porcinos:</label>
                            <select class="form-control" id="porcinos" name="porcinos" required>
                                <option value="">Seleccione</option>
                                <option value="Cría" {{ $explotacion->porcinos == 'Cría' ? 'selected' : '' }}>Cría</option>
                                <option value="Precebo" {{ $explotacion->porcinos == 'Precebo' ? 'selected' : '' }}>Precebo</option>
                                <option value="Ceba" {{ $explotacion->porcinos == 'Ceba' ? 'selected' : '' }}>Ceba</option>
                                <option value="Ciclo Completo" {{ $explotacion->porcinos == 'Ciclo Completo' ? 'selected' : '' }}>Ciclo Completo</option>
                                <option value="Traspatio" {{ $explotacion->porcinos == 'Traspatio' ? 'selected' : '' }}>Traspatio</option>
                                <option value="Mascotas" {{ $explotacion->porcinos == 'Mascotas' ? 'selected' : '' }}>Mascotas</option>
                            </select>
                        </div>
                    </div>

                    <!-- Equinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="equinos">Equinos:</label>
                            <select class="form-control" id="equinos" name="equinos" required>
                                <option value="">Seleccione</option>
                                <option value="Deporte" {{ $explotacion->equinos == 'Deporte' ? 'selected' : '' }}>Deporte</option>
                                <option value="Trabajo" {{ $explotacion->equinos == 'Trabajo' ? 'selected' : '' }}>Trabajo</option>
                                <option value="Exhibición" {{ $explotacion->equinos == 'Exhibición' ? 'selected' : '' }}>Exhibición</option>
                                <option value="Producción Carne" {{ $explotacion->equinos == 'Producción Carne' ? 'selected' : '' }}>Producción Carne</option>
                                <option value="Pesebreras" {{ $explotacion->equinos == 'Pesebreras' ? 'selected' : '' }}>Pesebreras</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Ovinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ovinos">Ovinos:</label>
                            <select class="form-control" id="ovinos" name="ovinos" required>
                                <option value="">Seleccione</option>
                                <option value="Cría" {{ $explotacion->ovinos == 'Cría' ? 'selected' : '' }}>Cría</option>
                                <option value="Ceba" {{ $explotacion->ovinos == 'Ceba' ? 'selected' : '' }}>Ceba</option>
                                <option value="Ciclo Completo" {{ $explotacion->ovinos == 'Ciclo Completo' ? 'selected' : '' }}>Ciclo Completo</option>
                                <option value="Producción Lana" {{ $explotacion->ovinos == 'Producción Lana' ? 'selected' : '' }}>Producción Lana</option>
                                <option value="Leche" {{ $explotacion->ovinos == 'Leche' ? 'selected' : '' }}>Leche</option>
                            </select>
                        </div>
                    </div>

                    <!-- Caprinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="caprinos">Caprinos:</label>
                            <select class="form-control" id="caprinos" name="caprinos" required>
                                <option value="">Seleccione</option>
                                <option value="Cría" {{ $explotacion->caprinos == 'Cría' ? 'selected' : '' }}>Cría</option>
                                <option value="Ceba" {{ $explotacion->caprinos == 'Ceba' ? 'selected' : '' }}>Ceba</option>
                                <option value="Ciclo Completo" {{ $explotacion->caprinos == 'Ciclo Completo' ? 'selected' : '' }}>Ciclo Completo</option>
                                <option value="Leche" {{ $explotacion->caprinos == 'Leche' ? 'selected' : '' }}>Leche</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Aves de corral -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aves_corral">Aves de corral:</label>
                            <select class="form-control" id="aves_corral" name="aves_corral" required>
                                <option value="">Seleccione</option>
                                <option value="Engorde" {{ $explotacion->aves_corral == 'Engorde' ? 'selected' : '' }}>Engorde</option>
                                <option value="Postura" {{ $explotacion->aves_corral == 'Postura' ? 'selected' : '' }}>Postura</option>
                                <option value="Genética" {{ $explotacion->aves_corral == 'Genética' ? 'selected' : '' }}>Genética</option>
                                <option value="Traspatio" {{ $explotacion->aves_corral == 'Traspatio' ? 'selected' : '' }}>Traspatio</option>
                                <option value="Otro" {{ $explotacion->aves_corral == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>

                    <!-- Aves no corral -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aves_no_corral">Aves no corral:</label>
                            <select class="form-control" id="aves_no_corral" name="aves_no_corral" required>
                                <option value="">Seleccione</option>
                                <option value="Aviario" {{ $explotacion->aves_no_corral == 'Aviario' ? 'selected' : '' }}>Aviario</option>
                                <option value="Zoológico" {{ $explotacion->aves_no_corral == 'Zoológico' ? 'selected' : '' }}>Zoológico</option>
                                <option value="Centro fauna silvestre" {{ $explotacion->aves_no_corral == 'Centro fauna silvestre' ? 'selected' : '' }}>Centro fauna silvestre</option>
                                <option value="Ornato y/o Canoras" {{ $explotacion->aves_no_corral == 'Ornato y/o Canoras' ? 'selected' : '' }}>Ornato y/o Canoras</option>
                                <option value="Otro" {{ $explotacion->aves_no_corral == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Peces -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="peces">Peces:</label>
                            <select class="form-control" id="peces" name="peces" required>
                                <option value="">Seleccione</option>
                                <option value="Ovas" {{ $explotacion->peces == 'Ovas' ? 'selected' : '' }}>Ovas</option>
                                <option value="Alevinos" {{ $explotacion->peces == 'Alevinos' ? 'selected' : '' }}>Alevinos</option>
                                <option value="Engorde" {{ $explotacion->peces == 'Engorde' ? 'selected' : '' }}>Engorde</option>
                                <option value="Reproductores" {{ $explotacion->peces == 'Reproductores' ? 'selected' : '' }}>Reproductores</option>
                                <option value="Ciclo Completo" {{ $explotacion->peces == 'Ciclo Completo' ? 'selected' : '' }}>Ciclo Completo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Crustáceos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="crustaceos">Crustáceos:</label>
                            <select class="form-control" id="crustaceos" name="crustaceos" required>
                                <option value="">Seleccione</option>
                                <option value="Larvicultura" {{ $explotacion->crustaceos == 'Larvicultura' ? 'selected' : '' }}>Larvicultura</option>
                                <option value="Maduración" {{ $explotacion->crustaceos == 'Maduración' ? 'selected' : '' }}>Maduración</option>
                                <option value="Engorde" {{ $explotacion->crustaceos == 'Engorde' ? 'selected' : '' }}>Engorde</option>
                                <option value="Ciclo Completo" {{ $explotacion->crustaceos == 'Ciclo Completo' ? 'selected' : '' }}>Ciclo Completo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Sistemas Acuáticos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sistem_acuaticos">Sistemas Acuáticos:</label>
                            <select class="form-control" id="sistem_acuaticos" name="sistem_acuaticos" required>
                                <option value="">Seleccione</option>
                                <option value="Estanques" {{ $explotacion->sistem_acuaticos == 'Estanques' ? 'selected' : '' }}>Estanques</option>
                                <option value="Jaulas" {{ $explotacion->sistem_acuaticos == 'Jaulas' ? 'selected' : '' }}>Jaulas</option>
                                <option value="Geomembrana" {{ $explotacion->sistem_acuaticos == 'Geomembrana' ? 'selected' : '' }}>Geomembrana</option>
                                <option value="Extensivo" {{ $explotacion->sistem_acuaticos == 'Extensivo' ? 'selected' : '' }}>Extensivo</option>
                                <option value="Intensivo" {{ $explotacion->sistem_acuaticos == 'Intensivo' ? 'selected' : '' }}>Intensivo</option>
                                <option value="Subsistencia" {{ $explotacion->sistem_acuaticos == 'Subsistencia' ? 'selected' : '' }}>Subsistencia</option>
                            </select>
                        </div>
                    </div>

                    <!-- Apícola -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apicolas">Apícola:</label>
                            <select class="form-control" id="apicolas" name="apicolas" required>
                                <option value="">Seleccione</option>
                                <option value="Apiarios" {{ $explotacion->apicolas == 'Apiarios' ? 'selected' : '' }}>Apiarios</option>
                                <option value="Núcleos" {{ $explotacion->apicolas == 'Núcleos' ? 'selected' : '' }}>Núcleos</option>
                                <option value="Reinas" {{ $explotacion->apicolas == 'Reinas' ? 'selected' : '' }}>Reinas</option>
                                <option value="Productos" {{ $explotacion->apicolas == 'Productos' ? 'selected' : '' }}>Productos</option>
                                <option value="Otro" {{ $explotacion->apicolas == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Enfermedades Ovinas/Caprinas -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="enferm_ovin_capri">Enfermedades Ovinas/Caprinas:</label>
                            <select class="form-control" id="enferm_ovin_capri" name="enferm_ovin_capri" onchange="showOrHideFields(this.value)" required>
                                <option value="">Seleccione</option>
                                <option value="SI" {{ $explotacion->enferm_ovin_capri == 'SI' ? 'selected' : '' }}>Sí</option>
                                <option value="NO" {{ $explotacion->enferm_ovin_capri == 'NO' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div id="enfermedades-details" style="display: {{ $explotacion->enferm_ovin_capri == 'SI' ? 'block' : 'none' }};">
                            <div class="form-group">
                                <label for="enferm_ovin_capri_cual">Cuáles Enfermedades Ovinas/Caprinas:</label>
                                <input type="text" class="form-control" id="enferm_ovin_capri_cual" name="enferm_ovin_capri_cual" value="{{ $explotacion->enferm_ovin_capri_cual }}">
                            </div>
                            <div class="form-group">
                                <label for="mortali_x_enfermedad">Mortalidad por Enfermedad:</label>
                                <select class="form-control" id="mortali_x_enfermedad" name="mortali_x_enfermedad" onchange="showOrHideMortality(this.value)">
                                    <option value="">Seleccione</option>
                                    <option value="SI" {{ $explotacion->mortali_x_enfermedad == 'SI' ? 'selected' : '' }}>Sí</option>
                                    <option value="NO" {{ $explotacion->mortali_x_enfermedad == 'NO' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <div id="mortality-details" style="display: {{ $explotacion->mortali_x_enfermedad == 'SI' ? 'block' : 'none' }};">
                            <div class="form-group">
                                <label for="mortali_x_enfermedad_cual">Cuáles Mortalidad por Enfermedad:</label>
                                <input type="text" class="form-control" id="mortali_x_enfermedad_cual" name="mortali_x_enfermedad_cual" value="{{ $explotacion->mortali_x_enfermedad_cual }}">
                            </div>
                        </div>
                    </div>

                    <!-- Producción Apícola -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pre_apic_produc_explot">Producción Apícola:</label>
                            <input type="text" class="form-control" id="pre_apic_produc_explot" name="pre_apic_produc_explot" value="{{ $explotacion->pre_apic_produc_explot }}" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 12px;">Actualizar</button>
            </form>

            @else
            <form action="{{ route('tipo_explotacion.store') }}" method="POST" class="form-horizontal">
                @csrf
                <input type="hidden" name="id_predio" value="{{ $predioId }}">

                <div class="row">
                    <!-- Bovinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bovinos">Bovinos:</label>
                            <select class="form-control" id="bovinos" name="bovinos" required>
                                <option value="">Seleccione</option>
                                <option value="Cria">Cria</option>
                                <option value="Levante">Levante</option>
                                <option value="Ceba">Ceba</option>
                                <option value="Lecheria">Lecheria</option>
                                <option value="Ciclo completo">Ciclo completo</option>
                                <option value="Estabulacion/Semi">Estabulación/Semi</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bufalinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bufalinos">Bufalinos:</label>
                            <select class="form-control" id="bufalinos" name="bufalinos" required>
                                <option value="">Seleccione</option>
                                <option value="Acopio Leche">Acopio Leche</option>
                                <option value="Curtiembre">Curtiembre</option>
                                <option value="Bascula">Báscula</option>
                                <option value="Alquiler corrales">Alquiler corrales</option>
                                <option value="Reproducción">Reproducción</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Porcinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="porcinos">Porcinos:</label>
                            <select class="form-control" id="porcinos" name="porcinos" required>
                                <option value="">Seleccione</option>
                                <option value="Cría">Cría</option>
                                <option value="Precebo">Precebo</option>
                                <option value="Ceba">Ceba</option>
                                <option value="Ciclo Completo">Ciclo Completo</option>
                                <option value="Traspatio">Traspatio</option>
                                <option value="Mascotas">Mascotas</option>
                            </select>
                        </div>
                    </div>

                    <!-- Equinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="equinos">Equinos:</label>
                            <select class="form-control" id="equinos" name="equinos" required>
                                <option value="">Seleccione</option>
                                <option value="Deporte">Deporte</option>
                                <option value="Trabajo">Trabajo</option>
                                <option value="Exhibición">Exhibición</option>
                                <option value="Producción Carne">Producción Carne</option>
                                <option value="Pesebreras">Pesebreras</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Ovinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ovinos">Ovinos:</label>
                            <select class="form-control" id="ovinos" name="ovinos" required>
                                <option value="">Seleccione</option>
                                <option value="Cría">Cría</option>
                                <option value="Ceba">Ceba</option>
                                <option value="Ciclo Completo">Ciclo Completo</option>
                                <option value="Producción Lana">Producción Lana</option>
                                <option value="Leche">Leche</option>
                            </select>
                        </div>
                    </div>

                    <!-- Caprinos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="caprinos">Caprinos:</label>
                            <select class="form-control" id="caprinos" name="caprinos" required>
                                <option value="">Seleccione</option>
                                <option value="Cría">Cría</option>
                                <option value="Ceba">Ceba</option>
                                <option value="Ciclo Completo">Ciclo Completo</option>
                                <option value="Leche">Leche</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Aves de corral -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aves_corral">Aves de corral:</label>
                            <select class="form-control" id="aves_corral" name="aves_corral" required>
                                <option value="">Seleccione</option>
                                <option value="Engorde">Engorde</option>
                                <option value="Postura">Postura</option>
                                <option value="Genética">Genética</option>
                                <option value="Traspatio">Traspatio</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <!-- Aves no corral -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aves_no_corral">Aves no corral:</label>
                            <select class="form-control" id="aves_no_corral" name="aves_no_corral" required>
                                <option value="">Seleccione</option>
                                <option value="Aviario">Aviario</option>
                                <option value="Zoológico">Zoológico</option>
                                <option value="Centro fauna silvestre">Centro fauna silvestre</option>
                                <option value="Ornato y/o Canoras">Ornato y/o Canoras</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Peces -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="peces">Peces:</label>
                            <select class="form-control" id="peces" name="peces" required>
                                <option value="">Seleccione</option>
                                <option value="Ovas">Ovas</option>
                                <option value="Alevinos">Alevinos</option>
                                <option value="Engorde">Engorde</option>
                                <option value="Reproductores">Reproductores</option>
                                <option value="Ciclo Completo">Ciclo Completo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Crustáceos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="crustaceos">Crustáceos:</label>
                            <select class="form-control" id="crustaceos" name="crustaceos" required>
                                <option value="">Seleccione</option>
                                <option value="Larvicultura">Larvicultura</option>
                                <option value="Maduración">Maduración</option>
                                <option value="Engorde">Engorde</option>
                                <option value="Ciclo Completo">Ciclo Completo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Sistemas Acuáticos -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sistem_acuaticos">Sistemas Acuáticos:</label>
                            <select class="form-control" id="sistem_acuaticos" name="sistem_acuaticos" required>
                                <option value="">Seleccione</option>
                                <option value="Estanques">Estanques</option>
                                <option value="Jaulas">Jaulas</option>
                                <option value="Geomembrana">Geomembrana</option>
                                <option value="Extensivo">Extensivo</option>
                                <option value="Intensivo">Intensivo</option>
                                <option value="Subsistencia">Subsistencia</option>
                            </select>
                        </div>
                    </div>

                    <!-- Apícola -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apicolas">Apícola:</label>
                            <select class="form-control" id="apicolas" name="apicolas" required>
                                <option value="">Seleccione</option>
                                <option value="Apiarios">Apiarios</option>
                                <option value="Núcleos">Núcleos</option>
                                <option value="Reinas">Reinas</option>
                                <option value="Productos">Productos</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Enfermedades Ovinas/Caprinas -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="enferm_ovin_capri">Enfermedades Ovinas/Caprinas:</label>
                            <select class="form-control" id="enferm_ovin_capri" name="enferm_ovin_capri" onchange="showOrHideFields(this.value)" required>
                                <option value="">Seleccione</option>
                                <option value="SI">Sí</option>
                                <option value="NO">No</option>
                            </select>
                        </div>
                        <div id="enfermedades-details" style="display: none;">
                            <div class="form-group">
                                <label for="enferm_ovin_capri_cual">Cuáles Enfermedades Ovinas/Caprinas:</label>
                                <input type="text" class="form-control" id="enferm_ovin_capri_cual" name="enferm_ovin_capri_cual">
                            </div>
                            <div class="form-group">
                                <label for="mortali_x_enfermedad">Mortalidad por Enfermedad:</label>
                                <select class="form-control" id="mortali_x_enfermedad" name="mortali_x_enfermedad" onchange="showOrHideMortality(this.value)">
                                    <option value="">Seleccione</option>
                                    <option value="SI">Sí</option>
                                    <option value="NO">No</option>
                                </select>
                            </div>
                        </div>
                        <div id="mortality-details" style="display: none;">
                            <div class="form-group">
                                <label for="mortali_x_enfermedad_cual">Cuáles Mortalidad por Enfermedad:</label>
                                <input type="text" class="form-control" id="mortali_x_enfermedad_cual" name="mortali_x_enfermedad_cual" >
                            </div>
                        </div>
                    </div>

                    <!-- Producción Apícola -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pre_apic_produc_explot">Producción Apícola:</label>
                            <input type="text" class="form-control" id="pre_apic_produc_explot" name="pre_apic_produc_explot" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 12px;">Siguiente</button>
            </form>
            @endif
        </div>
    </div>
</div>

<script>
function showOrHideFields(value) {
    const details = document.getElementById('enfermedades-details');
    details.style.display = (value === 'SI') ? 'block' : 'none';
}

function showOrHideMortality(value) {
    const details = document.getElementById('mortality-details');
    details.style.display = (value === 'SI') ? 'block' : 'none';
}
</script>

@endsection
