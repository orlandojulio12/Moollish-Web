@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId ) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion2', $predioId ) }}">Informacion Del Predio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manejor General del Ganado</li>
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
                    <form action="{{ route('man_gen_ganado.update', $manGenGanado->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Campo oculto para id_predio -->
                        <input type="hidden" name="id_predio" value="{{ $manGenGanado->id_predio }}">

                        <!-- Campo para RAZAS -->
                        <div class="form-group">
                            <label for="id_raza_gan">RAZAS:</label>
                            <select name="id_raza_gan" id="id_raza_gan" class="form-control">
                                <option value="">Seleccione</option>
                                @foreach ($razas as $raza)
                                    <option value="{{ $raza->id }}"
                                        {{ $manGenGanado->id_raza_gan == $raza->id ? 'selected' : '' }}>
                                        {{ $raza->nombre_razas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Campo para IDENTIFICACIÓN DE ANIMALES -->
                        <div class="form-group">
                            <label for="ident_animales">IDENTIFICACIÓN DE ANIMALES:</label>
                            <select name="ident_animales" id="ident_animales" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="HIERRO" {{ $manGenGanado->ident_animales == 'HIERRO' ? 'selected' : '' }}>
                                    HIERRO</option>
                                <option value="OREJERA" {{ $manGenGanado->ident_animales == 'OREJERA' ? 'selected' : '' }}>
                                    OREJERA</option>
                                <option value="DIN" {{ $manGenGanado->ident_animales == 'DIN' ? 'selected' : '' }}>DIN
                                </option>
                                <option value="OTRO" {{ $manGenGanado->ident_animales == 'OTRO' ? 'selected' : '' }}>OTRO
                                </option>
                                <option value="NO IDENTIFICA"
                                    {{ $manGenGanado->ident_animales == 'NO IDENTIFICA' ? 'selected' : '' }}>NO IDENTIFICA
                                </option>
                            </select>
                        </div>

                        <!-- Campo para SISTEMA DE CRÍA DE TERNEROS -->
                        <div class="form-group">
                            <label for="sistema_cria_ternero">SISTEMA DE CRÍA DE TERNEROS:</label>
                            <select name="sistema_cria_ternero" id="sistema_cria_ternero" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="SALA CUNA"
                                    {{ $manGenGanado->sistema_cria_ternero == 'SALA CUNA' ? 'selected' : '' }}>SALA CUNA
                                </option>
                                <option value="POTRERO"
                                    {{ $manGenGanado->sistema_cria_ternero == 'POTRERO' ? 'selected' : '' }}>POTRERO
                                </option>
                                <option value="CON ESTACA"
                                    {{ $manGenGanado->sistema_cria_ternero == 'CON ESTACA' ? 'selected' : '' }}>CON ESTACA
                                </option>
                                <option value="JAULA"
                                    {{ $manGenGanado->sistema_cria_ternero == 'JAULA' ? 'selected' : '' }}>JAULA</option>
                            </select>
                        </div>

                        <!-- Campo para ALIMENTACIÓN DE TERNEROS -->
                        <div class="form-group">
                            <label for="aliment_ternero">ALIMENTACIÓN DE TERNEROS:</label>
                            <select name="aliment_ternero" id="aliment_ternero" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="LECHE" {{ $manGenGanado->aliment_ternero == 'LECHE' ? 'selected' : '' }}>
                                    LECHE</option>
                                <option value="CONCENTRADO"
                                    {{ $manGenGanado->aliment_ternero == 'CONCENTRADO' ? 'selected' : '' }}>CONCENTRADO
                                </option>
                                <option value="LACTO REMPLAZADOR"
                                    {{ $manGenGanado->aliment_ternero == 'LACTO REMPLAZADOR' ? 'selected' : '' }}>LACTO
                                    REMPLAZADOR</option>
                            </select>
                        </div>

                        <!-- Campo para SISTEMA DE LEVANTE DE ANIMALES -->
                        <div class="form-group">
                            <label for="sistem_levant_animal">SISTEMA DE LEVANTE DE ANIMALES:</label>
                            <select name="sistem_levant_animal" id="sistem_levant_animal" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="POTRERO"
                                    {{ $manGenGanado->sistem_levant_animal == 'POTRERO' ? 'selected' : '' }}>POTRERO
                                </option>
                                <option value="CONCENTRADO"
                                    {{ $manGenGanado->sistem_levant_animal == 'CONCENTRADO' ? 'selected' : '' }}>
                                    CONCENTRADO</option>
                            </select>
                        </div>

                        <!-- Campo para MANEJO DE HEMBRAS PRÓXIMAS -->
                        <div class="form-group">
                            <label for="manej_hembras_prox">MANEJO DE HEMBRAS PRÓXIMAS:</label>
                            <select name="manej_hembras_prox" id="manej_hembras_prox" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="POTRERO PRE-PARTO"
                                    {{ $manGenGanado->manej_hembras_prox == 'POTRERO PRE-PARTO' ? 'selected' : '' }}>
                                    POTRERO PRE-PARTO</option>
                                <option value="CORRAL ESPECIAL"
                                    {{ $manGenGanado->manej_hembras_prox == 'CORRAL ESPECIAL' ? 'selected' : '' }}>CORRAL
                                    ESPECIAL</option>
                                <option value="CON TODO EL HATO"
                                    {{ $manGenGanado->manej_hembras_prox == 'CON TODO EL HATO' ? 'selected' : '' }}>CON
                                    TODO EL HATO</option>
                            </select>
                        </div>

                        <!-- Campo para MANEJO DE VACAS SECAS -->
                        <div class="form-group">
                            <label for="manej_vacas_secas">MANEJO DE VACAS SECAS:</label>
                            <select name="manej_vacas_secas" id="manej_vacas_secas" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="LOTE DE HORRO"
                                    {{ $manGenGanado->manej_vacas_secas == 'LOTE DE HORRO' ? 'selected' : '' }}>LOTE DE
                                    HORRO</option>
                                <option value="CON TODO EL HATO"
                                    {{ $manGenGanado->manej_vacas_secas == 'CON TODO EL HATO' ? 'selected' : '' }}>CON TODO
                                    EL HATO</option>
                            </select>
                        </div>

                        <!-- Campo para TIPO DE ORDEÑO -->
                        <div class="form-group">
                            <label for="tipo_ordeño">TIPO DE ORDEÑO:</label>
                            <select name="tipo_ordeño" id="tipo_ordeño" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="MANUAL" {{ $manGenGanado->tipo_ordeño == 'MANUAL' ? 'selected' : '' }}>
                                    MANUAL</option>
                                <option value="MECANICO" {{ $manGenGanado->tipo_ordeño == 'MECANICO' ? 'selected' : '' }}>
                                    MECÁNICO</option>
                                <option value="NO ORDEÑA"
                                    {{ $manGenGanado->tipo_ordeño == 'NO ORDEÑA' ? 'selected' : '' }}>NO ORDEÑA</option>
                            </select>
                        </div>

                        <!-- Campo para SISTEMA DE SERVICIOS REPRODUCTIVOS -->
                        <div class="form-group">
                            <label for="sistem_servic_reproduct">SISTEMA DE SERVICIOS REPRODUCTIVOS:</label>
                            <select name="sistem_servic_reproduct" id="sistem_servic_reproduct" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="NATURAL"
                                    {{ $manGenGanado->sistem_servic_reproduct == 'NATURAL' ? 'selected' : '' }}>NATURAL
                                </option>
                                <option value="INSEMINACIÓN"
                                    {{ $manGenGanado->sistem_servic_reproduct == 'INSEMINACIÓN' ? 'selected' : '' }}>
                                    INSEMINACIÓN</option>
                                <option value="TRANSFERENCIA DE EMBRIONES"
                                    {{ $manGenGanado->sistem_servic_reproduct == 'TRANSFERENCIA DE EMBRIONES' ? 'selected' : '' }}>
                                    TRANSFERENCIA DE EMBRIONES</option>
                            </select>
                        </div>

                        <!-- Campo para FORMA DE PROGRAMAR SERVICIOS -->
                        <div class="form-group">
                            <label for="form_program_servicios">FORMA DE PROGRAMAR SERVICIOS:</label>
                            <select name="form_program_servicios" id="form_program_servicios" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="SINCRONIZACIÓN"
                                    {{ $manGenGanado->form_program_servicios == 'SINCRONIZACIÓN' ? 'selected' : '' }}>
                                    SINCRONIZACIÓN</option>
                                <option value="PERIODOS DE MONTA"
                                    {{ $manGenGanado->form_program_servicios == 'PERIODOS DE MONTA' ? 'selected' : '' }}>
                                    PERIODOS DE MONTA</option>
                                <option value="NO PROGRAMA"
                                    {{ $manGenGanado->form_program_servicios == 'NO PROGRAMA' ? 'selected' : '' }}>NO
                                    PROGRAMA</option>
                            </select>
                        </div>

                        <!-- Campo para PESAJE DE ANIMALES -->
                        <div class="form-group">
                            <label for="pesaje_animal">PESAJE DE ANIMALES:</label>
                            <select name="pesaje_animal" id="pesaje_animal" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="CINTA" {{ $manGenGanado->pesaje_animal == 'CINTA' ? 'selected' : '' }}>
                                    CINTA</option>
                                <option value="BÁSCULA" {{ $manGenGanado->pesaje_animal == 'BÁSCULA' ? 'selected' : '' }}>
                                    BÁSCULA</option>
                                <option value="NO PESA" {{ $manGenGanado->pesaje_animal == 'NO PESA' ? 'selected' : '' }}>
                                    NO PESA</option>
                            </select>
                        </div>

                        <!-- Campo para CUÁLES ANIMALES PESA -->
                        <div class="form-group">
                            <label for="cuantos_animal_pesa">CUÁLES ANIMALES PESA:</label>
                            <select name="cuantos_animal_pesa" id="cuantos_animal_pesa" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="TODOS"
                                    {{ $manGenGanado->cuantos_animal_pesa == 'TODOS' ? 'selected' : '' }}>TODOS</option>
                                <option value="TERNEROS(AS)"
                                    {{ $manGenGanado->cuantos_animal_pesa == 'TERNEROS(AS)' ? 'selected' : '' }}>
                                    TERNEROS(AS)</option>
                                <option value="LEVANTES"
                                    {{ $manGenGanado->cuantos_animal_pesa == 'LEVANTES' ? 'selected' : '' }}>LEVANTES
                                </option>
                                <option value="NOVILLOS"
                                    {{ $manGenGanado->cuantos_animal_pesa == 'NOVILLOS' ? 'selected' : '' }}>NOVILLOS
                                </option>
                            </select>
                        </div>

                        <!-- Campo para CONTROL DE PARÁSITOS EXTERNOS -->
                        <div class="form-group">
                            <label for="control_parasito_extern">CONTROL DE PARÁSITOS EXTERNOS:</label>
                            <select name="control_parasito_extern" id="control_parasito_extern" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="sí"
                                    {{ $manGenGanado->control_parasito_extern == 'sí' ? 'selected' : '' }}>sí</option>
                                <option value="no"
                                    {{ $manGenGanado->control_parasito_extern == 'no' ? 'selected' : '' }}>no</option>
                            </select>
                        </div>

                        <!-- Campo para CONTROL DE PARÁSITOS EXTERNOS - PRODUCCIÓN -->
                        <div class="form-group">
                            <label for="control_parasito_extern_produc">CONTROL DE PARÁSITOS EXTERNOS - PRODUCCIÓN:</label>
                            <input type="text" name="control_parasito_extern_produc"
                                id="control_parasito_extern_produc" class="form-control"
                                value="{{ $manGenGanado->control_parasito_extern_produc }}">
                        </div>

                        <!-- Campo para CONTROL DE PARÁSITOS EXTERNOS - FRECUENCIA -->
                        <div class="form-group">
                            <label for="control_parasito_extern_frecuenc">CONTROL DE PARÁSITOS EXTERNOS -
                                FRECUENCIA:</label>
                            <input type="text" name="control_parasito_extern_frecuenc"
                                id="control_parasito_extern_frecuenc" class="form-control"
                                value="{{ $manGenGanado->control_parasito_extern_frecuenc }}">
                        </div>

                        <!-- Campo para CONTROL DE PARÁSITOS INTERNOS -->
                        <div class="form-group">
                            <label for="control_parasito_intern">CONTROL DE PARÁSITOS INTERNOS:</label>
                            <select name="control_parasito_intern" id="control_parasito_intern" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="sí"
                                    {{ $manGenGanado->control_parasito_intern == 'sí' ? 'selected' : '' }}>sí</option>
                                <option value="no"
                                    {{ $manGenGanado->control_parasito_intern == 'no' ? 'selected' : '' }}>no</option>
                            </select>
                        </div>

                        <!-- Campo para CONTROL DE PARÁSITOS INTERNOS - PRODUCCIÓN -->
                        <div class="form-group">
                            <label for="control_parasito_intern_produc">CONTROL DE PARÁSITOS INTERNOS - PRODUCCIÓN:</label>
                            <input type="text" name="control_parasito_intern_produc"
                                id="control_parasito_intern_produc" class="form-control"
                                value="{{ $manGenGanado->control_parasito_intern_produc }}">
                        </div>

                        <!-- Campo para CONTROL DE PARÁSITOS INTERNOS - FRECUENCIA -->
                        <div class="form-group">
                            <label for="control_parasito_intern_frecuenc">CONTROL DE PARÁSITOS INTERNOS -
                                FRECUENCIA:</label>
                            <input type="text" name="control_parasito_intern_frecuenc"
                                id="control_parasito_intern_frecuenc" class="form-control"
                                value="{{ $manGenGanado->control_parasito_intern_frecuenc }}">
                        </div>

                        <!-- Campo para SUMINISTRA SAL -->
                        <div class="form-group">
                            <label for="sumin_sal">SUMINISTRA SAL:</label>
                            <select name="sumin_sal" id="sumin_sal" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="MINERALIZADA"
                                    {{ $manGenGanado->sumin_sal == 'MINERALIZADA' ? 'selected' : '' }}>MINERALIZADA
                                </option>
                                <option value="BLANCA" {{ $manGenGanado->sumin_sal == 'BLANCA' ? 'selected' : '' }}>BLANCA
                                </option>
                                <option value="NO SUMINISTRA"
                                    {{ $manGenGanado->sumin_sal == 'NO SUMINISTRA' ? 'selected' : '' }}>NO SUMINISTRA
                                </option>
                            </select>
                        </div>

                        <!-- Campo para A LA SAL ANTERIOR LE ADICIONA PREMEZCLAS -->
                        <div class="form-group">
                            <label for="a_sal_add_premezcla">A LA SAL ANTERIOR LE ADICIONA PREMEZCLAS:</label>
                            <select name="a_sal_add_premezcla" id="a_sal_add_premezcla" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="sí" {{ $manGenGanado->a_sal_add_premezcla == 'sí' ? 'selected' : '' }}>
                                    sí</option>
                                <option value="no" {{ $manGenGanado->a_sal_add_premezcla == 'no' ? 'selected' : '' }}>
                                    no</option>
                            </select>
                        </div>

                        <!-- Campo para ESPECIFIQUE PREMEZCLA -->
                        <div class="form-group">
                            <label for="a_sal_add_premezcla_especifique">Especifique Premezcla:</label>
                            <input type="text" name="a_sal_add_premezcla_especifique"
                                id="a_sal_add_premezcla_especifique" class="form-control"
                                value="{{ $manGenGanado->a_sal_add_premezcla_especifique }}">
                        </div>

                        <!-- Campo para MANEJO DEL GANADO EN VERANO -->
                        <div class="form-group">
                            <label for="como_manej_ganad_veran">¿CÓMO SE MANEJA EL GANADO EN VERANO?</label>
                            <input type="text" name="como_manej_ganad_veran" id="como_manej_ganad_veran"
                                class="form-control" value="{{ $manGenGanado->como_manej_ganad_veran }}">
                        </div>

                        <!-- Campo para MANEJO DEL GANADO EN INVIERNO -->
                        <div class="form-group">
                            <label for="como_manej_ganad_invier">¿CÓMO SE MANEJA EL GANADO EN INVIERNO?</label>
                            <input type="text" name="como_manej_ganad_invier" id="como_manej_ganad_invier"
                                class="form-control" value="{{ $manGenGanado->como_manej_ganad_invier }}">
                        </div>

                        <!-- Campo para SE REALIZA PESAJE DE LECHE A HEMBRAS LACTANTES -->
                        <div class="form-group">
                            <label for="r_pesaje_leche_hembr_lactantes">SE REALIZA PESAJE DE LECHE A HEMBRAS
                                LACTANTES:</label>
                            <select name="r_pesaje_leche_hembr_lactantes" id="r_pesaje_leche_hembr_lactantes"
                                class="form-control">
                                <option value="">Seleccione</option>
                                <option value="sí"
                                    {{ $manGenGanado->r_pesaje_leche_hembr_lactantes == 'sí' ? 'selected' : '' }}>sí
                                </option>
                                <option value="no"
                                    {{ $manGenGanado->r_pesaje_leche_hembr_lactantes == 'no' ? 'selected' : '' }}>no
                                </option>
                            </select>
                        </div>

                        <!-- Campo para PERIODICIDAD DEL PESAJE DE LECHE -->
                        <div class="form-group">
                            <label for="r_pesaje_leche_hembr_periodicidad">Periodicidad del Pesaje de Leche:</label>
                            <select name="r_pesaje_leche_hembr_periodicidad" id="r_pesaje_leche_hembr_periodicidad"
                                class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Diario"
                                    {{ $manGenGanado->r_pesaje_leche_hembr_periodicidad == 'Diario' ? 'selected' : '' }}>
                                    Diario</option>
                                <option value="Semanal"
                                    {{ $manGenGanado->r_pesaje_leche_hembr_periodicidad == 'Semanal' ? 'selected' : '' }}>
                                    Semanal</option>
                                <option value="Quincenal"
                                    {{ $manGenGanado->r_pesaje_leche_hembr_periodicidad == 'Quincenal' ? 'selected' : '' }}>
                                    Quincenal</option>
                                <option value="Mensual"
                                    {{ $manGenGanado->r_pesaje_leche_hembr_periodicidad == 'Mensual' ? 'selected' : '' }}>
                                    Mensual</option>
                                <option value="No realiza"
                                    {{ $manGenGanado->r_pesaje_leche_hembr_periodicidad == 'No realiza' ? 'selected' : '' }}>
                                    No realiza</option>
                            </select>
                        </div>

                        <!-- Campo para SE SUPLEMENTA EL GANADO EN ÉPOCA CRÍTICA -->
                        <div class="form-group">
                            <label for="suplement_ganad_epoc_criti">SE SUPLEMENTA EL GANADO EN ÉPOCA CRÍTICA:</label>
                            <select name="suplement_ganad_epoc_criti" id="suplement_ganad_epoc_criti"
                                class="form-control">
                                <option value="">Seleccione</option>
                                <option value="sí"
                                    {{ $manGenGanado->suplement_ganad_epoc_criti == 'sí' ? 'selected' : '' }}>sí</option>
                                <option value="no"
                                    {{ $manGenGanado->suplement_ganad_epoc_criti == 'no' ? 'selected' : '' }}>no</option>
                            </select>
                        </div>

                        <!-- Campo para CON QUÉ SE SUPLEMENTA EL GANADO -->
                        <div class="form-group">
                            <label for="suplement_ganad_epoc_criti_con_que">Con qué se suplementa:</label>
                            <input type="text" name="suplement_ganad_epoc_criti_con_que"
                                id="suplement_ganad_epoc_criti_con_que" class="form-control"
                                value="{{ $manGenGanado->suplement_ganad_epoc_criti_con_que }}">
                        </div>

                        <!-- Campo para QUÉ LOTES SE SUPLEMENTAN -->
                        <div class="form-group">
                            <label for="suplement_ganad_epoc_criti_que_lotes">Qué lotes se suplementan:</label>
                            <input type="text" name="suplement_ganad_epoc_criti_que_lotes"
                                id="suplement_ganad_epoc_criti_que_lotes" class="form-control"
                                value="{{ $manGenGanado->suplement_ganad_epoc_criti_que_lotes }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar</button>

                    </form>
                @else
                    <form action="{{ route('man_gen_ganado.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="id_predio" value="{{ $predioId }}">

                        <div class="form-group">
                            <label for="id_raza_gan">RAZAS:</label>
                            <select name="id_raza_gan" id="id_raza_gan" class="form-control">
                                <option value="">Seleccione</option>
                                @foreach ($razas as $raza)
                                    <option value="{{ $raza->id }}">{{ $raza->nombre_razas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ident_animales">IDENTIFICACIÓN DE ANIMALES:</label>
                            <select name="ident_animales" id="ident_animales" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="HIERRO">HIERRO</option>
                                <option value="OREJERA">OREJERA</option>
                                <option value="DIN">DIN</option>
                                <option value="OTRO">OTRO</option>
                                <option value="NO IDENTIFICA">NO IDENTIFICA</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sistema_cria_ternero">SISTEMA DE CRÍA DE TERNEROS:</label>
                            <select name="sistema_cria_ternero" id="sistema_cria_ternero" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="SALA CUNA">SALA CUNA</option>
                                <option value="POTRERO">POTRERO</option>
                                <option value="CON ESTACA">CON ESTACA</option>
                                <option value="JAULA">JAULA</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="aliment_ternero">ALIMENTACIÓN DE TERNEROS:</label>
                            <select name="aliment_ternero" id="aliment_ternero" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="LECHE">LECHE</option>
                                <option value="CONCENTRADO">CONCENTRADO</option>
                                <option value="LACTO REMPLAZADOR">LACTO REMPLAZADOR</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sistem_levant_animal">SISTEMA DE LEVANTE DE ANIMALES:</label>
                            <select name="sistem_levant_animal" id="sistem_levant_animal" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="POTRERO">POTRERO</option>
                                <option value="CONCENTRADO">CONCENTRADO</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="manej_hembras_prox">MANEJO DE HEMBRAS PRÓXIMAS:</label>
                            <select name="manej_hembras_prox" id="manej_hembras_prox" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="POTRERO PRE-PARTO">POTRERO PRE-PARTO</option>
                                <option value="CORRAL ESPECIAL">CORRAL ESPECIAL</option>
                                <option value="CON TODO EL HATO">CON TODO EL HATO</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="manej_vacas_secas">MANEJO DE VACAS SECAS:</label>
                            <select name="manej_vacas_secas" id="manej_vacas_secas" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="LOTE DE HORRO">LOTE DE HORRO</option>
                                <option value="CON TODO EL HATO">CON TODO EL HATO</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tipo_ordeño">TIPO DE ORDEÑO:</label>
                            <select name="tipo_ordeño" id="tipo_ordeño" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="MANUAL">MANUAL</option>
                                <option value="MECANICO">MECÁNICO</option>
                                <option value="NO ORDEÑA">NO ORDEÑA</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sistem_servic_reproduct">SISTEMA DE SERVICIOS REPRODUCTIVOS:</label>
                            <select name="sistem_servic_reproduct" id="sistem_servic_reproduct" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="NATURAL">NATURAL</option>
                                <option value="INSEMINACIÓN">INSEMINACIÓN</option>
                                <option value="TRANSFERENCIA DE EMBRIONES">TRANSFERENCIA DE EMBRIONES</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="form_program_servicios">FORMA DE PROGRAMAR SERVICIOS:</label>
                            <select name="form_program_servicios" id="form_program_servicios" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="SINCRONIZACIÓN">SINCRONIZACIÓN</option>
                                <option value="PERIODOS DE MONTA">PERIODOS DE MONTA</option>
                                <option value="NO PROGRAMA">NO PROGRAMA</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="pesaje_animal">PESAJE DE ANIMALES:</label>
                            <select name="pesaje_animal" id="pesaje_animal" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="CINTA">CINTA</option>
                                <option value="BÁSCULA">BÁSCULA</option>
                                <option value="NO PESA">NO PESA</option>
                            </select>
                        </div>

                        <div class="form-group" id="cuantos_animal_pesa_div" style="display: none;">
                            <label for="cuantos_animal_pesa">CÚALES ANIMALES PESA:</label>
                            <select name="cuantos_animal_pesa" id="cuantos_animal_pesa" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="TODOS">TODOS</option>
                                <option value="TERNEROS(AS)">TERNEROS(AS)</option>
                                <option value="LEVANTES">LEVANTES</option>
                                <option value="NOVILLOS">NOVILLOS</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="control_parasito_extern">CONTROL DE PARÁSITOS EXTERNOS:</label>
                            <select name="control_parasito_extern" id="control_parasito_extern" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="sí">sí</option>
                                <option value="no">no</option>
                            </select>
                        </div>

                        <div class="form-group" id="control_parasito_extern_div" style="display: none;">
                            <label for="control_parasito_extern_produc">Control de parásitos externos - Producción:</label>
                            <input type="text" name="control_parasito_extern_produc"
                                id="control_parasito_extern_produc" class="form-control">

                            <label for="control_parasito_extern_frecuenc">Control de parásitos externos -
                                Frecuencia:</label>
                            <input type="text" name="control_parasito_extern_frecuenc"
                                id="control_parasito_extern_frecuenc" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="control_parasito_intern">CONTROL DE PARÁSITOS INTERNOS:</label>
                            <select name="control_parasito_intern" id="control_parasito_intern" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="sí">sí</option>
                                <option value="no">no</option>
                            </select>
                        </div>

                        <div class="form-group" id="control_parasito_intern_div" style="display: none;">
                            <label for="control_parasito_intern_produc">Control de parásitos internos - Producción:</label>
                            <input type="text" name="control_parasito_intern_produc"
                                id="control_parasito_intern_produc" class="form-control">

                            <label for="control_parasito_intern_frecuenc">Control de parásitos internos -
                                Frecuencia:</label>
                            <input type="text" name="control_parasito_intern_frecuenc"
                                id="control_parasito_intern_frecuenc" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="sumin_sal">SUMINISTRA SAL:</label>
                            <select name="sumin_sal" id="sumin_sal" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="MINERALIZADA">MINERALIZADA</option>
                                <option value="BLANCA">BLANCA</option>
                                <option value="NO SUMINISTRA">NO SUMINISTRA</option>
                            </select>
                        </div>

                        <div class="form-group" id="a_sal_add_premezcla_div" style="display: none;">
                            <label for="a_sal_add_premezcla">A LA SAL ANTERIOR LE ADICIONA PREMEZCLAS:</label>
                            <select name="a_sal_add_premezcla" id="a_sal_add_premezcla" class="form-control">
                                <option value="">Seleccione</option>

                                <option value="sí">sí</option>
                                <option value="no">no</option>
                            </select>
                        </div>

                        <div class="form-group" id="a_sal_add_premezcla_especifique_div" style="display: none;">
                            <label for="a_sal_add_premezcla_especifique">
                                Especifique:</label>
                            <input type="text" name="a_sal_add_premezcla_especifique"
                                id="a_sal_add_premezcla_especifique" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="como_manej_ganad_veran">¿CÓMO SE MANEJA EL GANADO EN VERANO?</label>
                            <input type="text" name="como_manej_ganad_veran" id="como_manej_ganad_veran"
                                class="form-control">
                        </div>

                        <!-- MANEJO DEL GANADO EN INVIERNO -->
                        <div class="form-group">
                            <label for="como_manej_ganad_invier">¿CÓMO SE MANEJA EL GANADO EN INVIERNO?</label>
                            <input type="text" name="como_manej_ganad_invier" id="como_manej_ganad_invier"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="r_pesaje_leche_hembr_lactantes">SE REALIZA PESAJE DE LECHE A HEMBRAS
                                LACTANTES:</label>
                            <select name="r_pesaje_leche_hembr_lactantes" id="r_pesaje_leche_hembr_lactantes"
                                class="form-control">
                                <option value="">Seleccione</option>

                                <option value="sí">sí</option>
                                <option value="no">no</option>
                            </select>
                        </div>

                        <div class="form-group" id="r_pesaje_leche_hembr_periodicidad_div" style="display: none;">
                            <label for="r_pesaje_leche_hembr_periodicidad">Periodicidad del pesaje de leche:</label>
                            <select name="r_pesaje_leche_hembr_periodicidad" id="r_pesaje_leche_hembr_periodicidad"
                                class="form-control">
                                <option value="">Seleccione</option>

                                <option value="Diario">Diario</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Quincenal">Quincenal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="No realiza">No realiza</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="suplement_ganad_epoc_criti">SE SUPLEMENTA EL GANADO EN ÉPOCA CRÍTICA:</label>
                            <select name="suplement_ganad_epoc_criti" id="suplement_ganad_epoc_criti"
                                class="form-control">
                                <option value="">Seleccione</option>

                                <option value="sí">sí</option>
                                <option value="no">no</option>
                            </select>
                        </div>


                        <div class="form-group" id="suplement_ganad_epoc_criti_con_que_div" style="display: none;">
                            <label for="suplement_ganad_epoc_criti_con_que">Con qué:</label>
                            <input type="text" name="suplement_ganad_epoc_criti_con_que"
                                id="suplement_ganad_epoc_criti_con_que" class="form-control">

                            <label for="suplement_ganad_epoc_criti_que_lotes"> Qué lotes:</label>
                            <input type="text" name="suplement_ganad_epoc_criti_que_lotes"
                                id="suplement_ganad_epoc_criti_que_lotes" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                @endif
            </div>
        </div>
    </div>


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Controlar la visibilidad de campos según selecciones
            const pesajeAnimal = document.getElementById('pesaje_animal');
            const cuantosAnimalPesaDiv = document.getElementById('cuantos_animal_pesa_div');

            const controlParasitoExtern = document.getElementById('control_parasito_extern');
            const controlParasitoExternDiv = document.getElementById('control_parasito_extern_div');

            const controlParasitoIntern = document.getElementById('control_parasito_intern');
            const controlParasitoInternDiv = document.getElementById('control_parasito_intern_div');

            const suminSal = document.getElementById('sumin_sal');
            const aSalAddPremezclaDiv = document.getElementById('a_sal_add_premezcla_div');
            const aSalAddPremezclaEspecifiqueDiv = document.getElementById('a_sal_add_premezcla_especifique_div');

            const pesajeLecheHembrasLactantes = document.getElementById('r_pesaje_leche_hembr_lactantes');
            const rPesajeLecheHembrasPeriodDiv = document.getElementById('r_pesaje_leche_hembr_periodicidad_div');

            const suplementGanadoEpocCritica = document.getElementById('suplement_ganad_epoc_criti');
            const suplementGanadoEpocCriticaConQueDiv = document.getElementById(
                'suplement_ganad_epoc_criti_con_que_div');

            function toggleVisibility(element, condition) {
                element.style.display = condition ? 'block' : 'none';
            }

            pesajeAnimal.addEventListener('change', function() {
                toggleVisibility(cuantosAnimalPesaDiv, this.value !== 'NO PESA');
            });

            controlParasitoExtern.addEventListener('change', function() {
                toggleVisibility(controlParasitoExternDiv, this.value === 'sí');
            });

            controlParasitoIntern.addEventListener('change', function() {
                toggleVisibility(controlParasitoInternDiv, this.value === 'sí');
            });

            suminSal.addEventListener('change', function() {
                const isNoSuministra = this.value === 'NO SUMINISTRA';
                toggleVisibility(aSalAddPremezclaDiv, !isNoSuministra);
                toggleVisibility(aSalAddPremezclaEspecifiqueDiv, !isNoSuministra);
            });

            pesajeLecheHembrasLactantes.addEventListener('change', function() {
                toggleVisibility(rPesajeLecheHembrasPeriodDiv, this.value === 'sí');
                if (this.value === 'no') {
                    document.getElementById('r_pesaje_leche_hembr_periodicidad').value = 'no aplica';
                }
            });

            suplementGanadoEpocCritica.addEventListener('change', function() {
                toggleVisibility(suplementGanadoEpocCriticaConQueDiv, this.value === 'sí');
            });
        });
    </script>
@endsection
@endsection
