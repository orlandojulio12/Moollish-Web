@extends('layouts')

@section('template_title')
    {{ $propietario->nombre_completo ?? __('Show') . ' ' . __('Propietario') }}
@endsection

@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .bread {
            font-size: 28px;
            color: black;
        }

        .cumb {
            margin: 0px !important;
            align-content: center;
        }

        .breadcrumb {
            display: flex;
        }

        .active-tab {
            color: #dc7a00;
        }

        .no-active-tab:hover {
            color: #dc7a00;
            cursor: pointer;
            text-decoration: underline;
        }

        .card-header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 23px;
            font-weight: 700;
            color: #292929;
            margin: 0;
        }

        .card-subtitle {
            font-size: 14px;
            color: #767676;
            margin-top: 5px;
        }

        .dashboard-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        .table th {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 2px solid #e49b39;
            font-weight: 600;
            color: #4B5563;
            background-color: #F9FAFB;
        }

        .table td {
            padding: 14px 16px;
            border-bottom: 1px solid #E5E7EB;
            color: #111827;
        }

        .table tr:hover {
            background-color: #FFF8F0;
        }

        .btn-primary {
            background-color: #e49b39;
            border-color: #e49b39;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #d38b29;
            border-color: #d38b29;
            transform: translateY(-2px);
        }

        .btn-warning {
            color: #e49b39;
            border: 1px solid #e49b39;
            background-color: transparent;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-warning:hover {
            background-color: #FFF8F0;
            transform: translateY(-2px);
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            color: #e49b39;
            font-weight: 600;
        }

        .form-label {
            font-weight: 600;
            color: #4B5563;
            margin-bottom: 0.5rem;
        }

        .form-control:focus {
            border-color: #e49b39;
            box-shadow: 0 0 0 0.25rem rgba(228, 155, 57, 0.25);
        }
    </style>
@endsection

@section('content')
    <div class="card-custom">
        <!-- Breadcrumb -->
        <div class="header-grid">
            <div class="breadcrumb">
                <a href="{{ route('inicio') }}">
                    <h3 class="cumb no-active-tab">Inicio</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <a href="{{ route('predios.index') }}">
                    <h3 class="cumb no-active-tab">Predios</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <h3 class="cumb active-tab">Detalle del Predio</h3>
            </div>
        </div>
        <hr>

        <!-- Título y descripción -->
        <div class="card-header-container">
            <div>
                <h2 class="card-title">Información Detallada del Predio</h2>
                <p class="card-subtitle">Visualización completa de la información del predio y sus características</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <!-- Información del Predio -->
                <div class="dashboard-card mb-4">
                    <div class="card-header-container">
                        <h4 class="card-title h5">Datos Generales</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                                <tbody>
                                    <tr>
                                    <th style="width: 30%">Nombre Predio:</th>
                                        <td>{{ $Predios->nombre_predio ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nombre propietario:</th>
                                        <td>{{ $Predios->propietarios->nombre_completo ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>

                <!-- Áreas del Predio -->
                <div class="dashboard-card mb-4">
                    <div class="card-header-container">
                        <h4 class="card-title h5">Áreas del Predio</h4>
                            </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th>Medidas</th>
                                    <th>Materiales Establecidos</th>
                                        <th>Acciones</th>
                                    </tr>
                            </thead>
                            <tbody>
                                    @foreach ($Areas as $p)
                                        <tr>
                                            <td>{{ $p->TiposAreas->nombre_area ?? 'N/A' }}</td>
                                            <td>{{ $p->medidas ?? 'N/A' }}</td>
                                            <td>{{ $p->materiales_establecidos ?? 'N/A' }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editAreaModal{{ $p->id }}">
                                                    <i data-feather="edit"></i> Editar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>

                <!-- Información de Tierra y Agua -->
                <div class="dashboard-card mb-4">
                    <div class="card-header-container">
                        <div>
                            <h4 class="card-title h5">Información de Tierra y Agua</h4>
                        </div>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editInfoModal">
                            <i data-feather="edit"></i> Actualizar Info
                                </button>
                            </div>
                    <div class="table-responsive">
                        <table class="table">
                                <tbody>
                                    @foreach ($infoTierraAgua as $info)
                                    <tr>
                                        <th style="width: 30%">Suelos Predominantes</th>
                                        <td>{{ $info->suelos_predominantes }}</td>
                                    </tr>
                                    <tr>
                                        <th>Drenaje</th>
                                        <td>{{ $info->drenaje }}</td>
                                    </tr>
                                    <tr>
                                        <th>Manejo Cuencas Nac Agua</th>
                                        <td>{{ $info->manejo_cuencas_nac_agua }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cantidad Preservación</th>
                                        <td>{{ $info->cantidad_preservacion }}</td>
                                    </tr>
                                    <tr>
                                        <th>Porcentaje Preservación</th>
                                        <td>{{ $info->porcentaje_preservacion }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fuente Calidad Agua</th>
                                        <td>{{ $info->fuente_calidad_agua }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fuente Calidad Agua Uso Doméstico</th>
                                        <td>{{ $info->fuente_calidad_agua_uso_domestic }}</td>
                                    </tr>
                                    <tr>
                                        <th>Disponibilidad de Agua Durante Verano para Animales</th>
                                        <td>{{ $info->disp_agua_durant_veran_anim }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fuente de Agua para Animales Durante Verano</th>
                                        <td>{{ $info->disp_agua_durant_veran_anim_fuente }}</td>
                                    </tr>
                                    <tr>
                                        <th>Disponibilidad de Agua Durante Verano para Riego</th>
                                        <td>{{ $info->disp_agua_durant_veran_riesg }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fuente de Agua para Riego Durante Verano</th>
                                        <td>{{ $info->disp_agua_durant_veran_riesg_fuente }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>

                <!-- Información de Manejo de Pastoreo -->
                <div class="dashboard-card mb-4">
                    <div class="card-header-container">
                        <h4 class="card-title h5">Información de Manejo de Pastoreo</h4>
                            </div>
                    <div class="table-responsive">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th>Campo</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($manPastPotrCerc as $man)
                                    <tr>
                                        <td><strong>Área Destinada a Pastoreo:</strong></td>
                                        <td>{{ $man->area_dest_past }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Realiza Fertilización de Potreros:</strong></td>
                                        <td>{{ $man->r_fertilazion_potreros }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Producto Utilizado en Fertilización de Potreros:</strong></td>
                                        <td>{{ $man->r_fertilazion_potreros_produc }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Frecuencia Anual de Fertilización de Potreros:</strong></td>
                                        <td>{{ $man->r_fertilazion_potreros_cuant_año }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Presencia de Plagas o Enfermedades:</strong></td>
                                        <td>{{ $man->presen_plag_enferm }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipos de Plagas o Enfermedades Presentes:</strong></td>
                                        <td>{{ $man->presen_plag_enferm_tipos }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Realiza Control de Plagas:</strong></td>
                                        <td>{{ $man->r_control_plagas }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Producto Utilizado en Control de Plagas:</strong></td>
                                        <td>{{ $man->r_control_plagas_produc }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Frecuencia Anual de Control de Plagas:</strong></td>
                                        <td>{{ $man->r_control_plagas_cuant_año }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Realiza Control de Maleza:</strong></td>
                                        <td>{{ $man->r_control_maleza }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Producto Utilizado en Control de Maleza:</strong></td>
                                        <td>{{ $man->r_control_maleza_product }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Frecuencia Anual de Control de Maleza:</strong></td>
                                        <td>{{ $man->r_control_maleza_cuant_año }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Presencia de Heladas:</strong></td>
                                        <td>{{ $man->precencia_heladas }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Intensidad de las Heladas:</strong></td>
                                        <td>{{ $man->precencia_heladas_intensidad }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Épocas de Heladas:</strong></td>
                                        <td>{{ $man->precencia_heladas_epocas }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>División de Potreros:</strong></td>
                                        <td>{{ $man->div_potreros }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cómo se Divide los Potreros:</strong></td>
                                        <td>{{ $man->div_potreros_como }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo de Pastoreo:</strong></td>
                                        <td>{{ $man->tipo_pastoreo }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Días de Ocupación en Pastoreo Rotacional:</strong></td>
                                        <td>{{ $man->rotacional_dias_ocupacion }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Días de Descanso en Pastoreo Rotacional:</strong></td>
                                        <td>{{ $man->rotacional_dias_descanso }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipos de Cercas:</strong></td>
                                        <td>{{ $man->cercas }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Uso de Cercas de Púas:</strong></td>
                                        <td>{{ $man->cercas_puas }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Uso de Cercas Eléctricas:</strong></td>
                                        <td>{{ $man->cercas_electricas }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Producción de Forraje Suficiente Durante el Año:</strong></td>
                                        <td>{{ $man->la_produccion_forraje_suficiente_año }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Razón de Suficiencia/Insuficiencia de Forraje:</strong></td>
                                        <td>{{ $man->porque }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>

                <!-- Información de Manejo General del Ganado -->
                <div class="dashboard-card mb-4">
                    <div class="card-header-container">
                        <h4 class="card-title h5">Información de Manejo General del Ganado</h4>
                            </div>
                    <div class="table-responsive">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th>Campo</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($manGenGanado as $ganado)
                                    <tr>
                                        <td><strong>Razas:</strong></td>
                                        <td>{{ $ganado->razas->nombre_razas }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Identificación de Animales:</strong></td>
                                        <td>{{ $ganado->ident_animales }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sistema de Cría de Ternero:</strong></td>
                                        <td>{{ $ganado->sistema_cria_ternero }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alimentación de Ternero:</strong></td>
                                        <td>{{ $ganado->aliment_ternero }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sistema de Levantamiento de Animal:</strong></td>
                                        <td>{{ $ganado->sistem_levant_animal }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Manejo de Hembras Próximas:</strong></td>
                                        <td>{{ $ganado->manej_hembras_prox }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Manejo de Vacas Secas:</strong></td>
                                        <td>{{ $ganado->manej_vacas_secas }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo de Ordeño:</strong></td>
                                        <td>{{ $ganado->tipo_ordeño }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sistema de Servicio Reproductivo:</strong></td>
                                        <td>{{ $ganado->sistem_servic_reproduct }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Forma de Programa de Servicios:</strong></td>
                                        <td>{{ $ganado->form_program_servicios }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pesaje de Animal:</strong></td>
                                        <td>{{ $ganado->pesaje_animal }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cuántos Animales se Pesa:</strong></td>
                                        <td>{{ $ganado->cuantos_animal_pesa }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Control de Parásitos Externos:</strong></td>
                                        <td>{{ $ganado->control_parasito_extern }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Producto para Control de Parásitos Externos:</strong></td>
                                        <td>{{ $ganado->control_parasito_extern_produc }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Frecuencia de Control de Parásitos Externos:</strong></td>
                                        <td>{{ $ganado->control_parasito_extern_frecuenc }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Control de Parásitos Internos:</strong></td>
                                        <td>{{ $ganado->control_parasito_intern }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Producto para Control de Parásitos Internos:</strong></td>
                                        <td>{{ $ganado->control_parasito_intern_produc }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Frecuencia de Control de Parásitos Internos:</strong></td>
                                        <td>{{ $ganado->control_parasito_intern_frecuenc }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Suministro de Sal:</strong></td>
                                        <td>{{ $ganado->sumin_sal }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Adición de Premezcla a la Sal:</strong></td>
                                        <td>{{ $ganado->a_sal_add_premezcla }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Especificar Adición de Premezcla:</strong></td>
                                        <td>{{ $ganado->a_sal_add_premezcla_especifique }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Manejo de Ganado en Verano:</strong></td>
                                        <td>{{ $ganado->como_manej_ganad_veran }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Manejo de Ganado en Invierno:</strong></td>
                                        <td>{{ $ganado->como_manej_ganad_invier }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Rendimiento de Pesaje de Leche en Hembras Lactantes:</strong></td>
                                        <td>{{ $ganado->r_pesaje_leche_hembr_lactantes }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Periodicidad del Pesaje de Leche:</strong></td>
                                        <td>{{ $ganado->r_pesaje_leche_hembr_periodicidad }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Suplementación de Ganado en Épocas Críticas:</strong></td>
                                        <td>{{ $ganado->suplement_ganad_epoc_criti }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Con qué se Suplementa:</strong></td>
                                        <td>{{ $ganado->suplement_ganad_epoc_criti_con_que }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Qué Lotes se Suplementan:</strong></td>
                                        <td>{{ $ganado->suplement_ganad_epoc_criti_que_lotes }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>

                <!-- Instalaciones y Equipos -->
                <div class="dashboard-card mb-4">
                    <div class="card-header-container">
                        <h4 class="card-title h5">Instalaciones y Equipos</h4>
                            </div>
                    <div class="table-responsive">
                            <form action="{{ route('instalaciones_equipos.update', $id_predio) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id_predio" value="{{ $id_predio }}">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tipo de Equipo</th>
                                            <th>Sí</th>
                                            <th>No</th>
                                            <th>Especificar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instalacionesEquipos as $equipo)
                                        <tr>
                                            <td>{{ $equipo->tipos_equipos->nombre_tipo }}</td>
                                            <td>
                                                <input type="radio" name="equipos[{{ $equipo->id }}][si]" value="si" {{ $equipo->si ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="radio" name="equipos[{{ $equipo->id }}][no]" value="no" {{ $equipo->no ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="equipos[{{ $equipo->id }}][especificar]" class="form-control" value="{{ $equipo->especificar }}">
                                            </td>
                                            <input type="hidden" name="equipos[{{ $equipo->id }}][id_tipos_equipos]" value="{{ $equipo->tipos_equipos->id }}">
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
                        </div>

    <!-- Mantener los modales como estaban -->
    @yield('modal')
@endsection
