<!-- resources/views/home.blade.php -->
@extends('layouts')

@section('title', 'Dashboard')

{{-- @section('content')
    <style>
        .col-xxl-3 {
            padding-bottom: 10px;
        }

        .category-image {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 70px;
            object-fit: cover;
        }

        .page-header {
            margin-bottom: 10px;
            border-radius: 8px;
        }

        #content-container:before {
            content: '';
            display: block;
            height: 165px;
            width: 100%;
            position: absolute;
            background-color: #C29F77 !important;
            z-index: 0;
        }

        .border-dashed {
            border-style: none !important;
        }

        .sesion6 {
            width: 57px;
        }

    </style>

    @php
        // 1. Proporción de predios que colindan con establecimientos de riesgo
        $colindaEstablecim = DB::table('caracterizacion_riesgo')
            ->selectRaw(
                "
IFNULL(COUNT(CASE WHEN colinda_establecim_riesgo = 'Si' THEN 1 END), 0) AS colinda,
IFNULL(COUNT(CASE WHEN colinda_establecim_riesgo = 'No' THEN 1 END), 0) AS no_colinda
",
            )
            ->first();

        // 2. Frecuencia de ubicación en vía
        $ubicacionVia = DB::table('caracterizacion_riesgo')
            ->select('ubica_en_via', DB::raw('COUNT(*) AS frecuencia'))
            ->groupBy('ubica_en_via')
            ->get();

        // 3. Frecuencia de tipos de alimentación de animales
        $alimenAnimal = DB::table('caracterizacion_riesgo')
            ->select('alimen_animal', DB::raw('COUNT(*) AS frecuencia'))
            ->groupBy('alimen_animal')
            ->get();

        // 4. Proporción de predios que suministran desperdicios alimentarios para porcinos
        $lavazasPorcinos = DB::table('caracterizacion_riesgo')
            ->selectRaw(
                "
IFNULL(COUNT(CASE WHEN lavazas_desper_alimen_porc = 'Si' THEN 1 END), 0) AS suministra,
IFNULL(COUNT(CASE WHEN lavazas_desper_alimen_porc = 'No' THEN 1 END), 0) AS no_suministra
",
            )
            ->first();

        // 5. Proporción de predios donde se realiza sacrificio de animales
        $sacrificioAnimales = DB::table('caracterizacion_riesgo')
            ->selectRaw(
                "
IFNULL(COUNT(CASE WHEN sacrif_anim_pred = 'Si' THEN 1 END), 0) AS realiza_sacrificio,
IFNULL(COUNT(CASE WHEN sacrif_anim_pred = 'No' THEN 1 END), 0) AS no_realiza_sacrificio
",
            )
            ->first();

        // 6. Proporción de predios que reciben asistencia técnica
        $asistenciaTecnica = DB::table('caracterizacion_riesgo')
            ->selectRaw(
                "
IFNULL(COUNT(CASE WHEN asistencia_tecnica = 'Si' THEN 1 END), 0) AS recibe_asistencia,
IFNULL(COUNT(CASE WHEN asistencia_tecnica = 'No' THEN 1 END), 0) AS no_recibe_asistencia
",
            )
            ->first();

        // 7. Distribución del número de trabajadores en los predios
        $numTrabajadores = DB::table('caracterizacion_riesgo')
            ->select('num_trabajadores', DB::raw('COUNT(*) AS frecuencia'))
            ->groupBy('num_trabajadores')
            ->orderBy('num_trabajadores')
            ->get();
    @endphp

    @php
        $resultEnfermedades = DB::table('infor_epidemiologica')
            ->selectRaw(
                "
IFNULL(COUNT(CASE WHEN anim_enferm_control = 'Si' THEN 1 END), 0) AS predios_con_enfermedad,
IFNULL(COUNT(CASE WHEN anim_enferm_control = 'No' THEN 1 END), 0) AS predios_sin_enfermedad
",
            )
            ->first();

        // Segunda consulta: Proporción de predios con toma de muestra
        $resultMuestras = DB::table('infor_epidemiologica')
            ->selectRaw(
                "
IFNULL(COUNT(CASE WHEN toma_muestra = 'Si' THEN 1 END), 0) AS predios_con_muestra,
IFNULL(COUNT(CASE WHEN toma_muestra = 'No' THEN 1 END), 0) AS predios_sin_muestra
",
            )
            ->first();

        // Tercera consulta: Tipos de muestras más comunes
        $tiposMuestras = DB::table('infor_epidemiologica')
            ->select('toma_muestra_tipos', DB::raw('COUNT(*) AS frecuencia'))
            ->where('toma_muestra', 'Si')
            ->groupBy('toma_muestra_tipos')
            ->orderByDesc('frecuencia')
            ->limit(5)
            ->get();
    @endphp

    @php
        $data = [
            'bovinos' => DB::table('tp_explotacion')
                ->select('bovinos as tipo', DB::raw('count(*) as total'))
                ->groupBy('bovinos')
                ->get(),
            'bufalinos' => DB::table('tp_explotacion')
                ->select('bufalinos as tipo', DB::raw('count(*) as total'))
                ->groupBy('bufalinos')
                ->get(),
            'porcinos' => DB::table('tp_explotacion')
                ->select('porcinos as tipo', DB::raw('count(*) as total'))
                ->groupBy('porcinos')
                ->get(),
            'equinos' => DB::table('tp_explotacion')
                ->select('equinos as tipo', DB::raw('count(*) as total'))
                ->groupBy('equinos')
                ->get(),
            'ovinos' => DB::table('tp_explotacion')
                ->select('ovinos as tipo', DB::raw('count(*) as total'))
                ->groupBy('ovinos')
                ->get(),
            'caprinos' => DB::table('tp_explotacion')
                ->select('caprinos as tipo', DB::raw('count(*) as total'))
                ->groupBy('caprinos')
                ->get(),
            'aves_corral' => DB::table('tp_explotacion')
                ->select('aves_corral as tipo', DB::raw('count(*) as total'))
                ->groupBy('aves_corral')
                ->get(),
            'aves_no_corral' => DB::table('tp_explotacion')
                ->select('aves_no_corral as tipo', DB::raw('count(*) as total'))
                ->groupBy('aves_no_corral')
                ->get(),
            'peces' => DB::table('tp_explotacion')
                ->select('peces as tipo', DB::raw('count(*) as total'))
                ->groupBy('peces')
                ->get(),
            'crustaceos' => DB::table('tp_explotacion')
                ->select('crustaceos as tipo', DB::raw('count(*) as total'))
                ->groupBy('crustaceos')
                ->get(),
            'sistem_acuaticos' => DB::table('tp_explotacion')
                ->select('sistem_acuaticos as tipo', DB::raw('count(*) as total'))
                ->groupBy('sistem_acuaticos')
                ->get(),
            'apicolas' => DB::table('tp_explotacion')
                ->select('apicolas as tipo', DB::raw('count(*) as total'))
                ->groupBy('apicolas')
                ->get(),
        ];

        // Indicador de los tipos más comunes en cada categoría
        $summary = [];
        $categoryImages = [
            'bovinos' => 'bocinos.png',
            'bufalinos' => 'bufalinos1.png',
            'porcinos' => 'porcinos.png',
            'equinos' => 'equidos.png',
            'ovinos' => 'ovino.png',
            'caprinos' => 'caprinos.png',
            'aves_corral' => 'aves_comerciales.png',
            'aves_no_corral' => 'otras_aves.png',
            'peces' => 'peces.png',
            'crustaceos' => 'crustaceos.png',
            'sistem_acuaticos' => 'otras_especies.png',
            'apicolas' => 'abejas.png',
        ];
        foreach ($data as $key => $values) {
            $total = $values->sum('total');
            $common = $values->sortByDesc('total')->first();
            $summary[$key] = [
                'total' => $total,
                'most_common' => $common->tipo ?? 'N/A',
                'common_count' => $common->total ?? 0,
            ];
        }
    @endphp


    @php
        // Total de medidas agrupadas por tipo de medida
        $totalMedidasPorTipo = \App\Models\Areas::whereNotNull('tipo_medidas')
    ->select('tipo_medidas', DB::raw('SUM(medidas) as total'))
    ->groupBy('tipo_medidas')
    ->get();

    @endphp

@php
    use Illuminate\Support\Facades\DB;

    // Realiza la consulta directamente en la vista
    $totalAreasPorMunicipio = DB::table('areas')
        ->join('predios', 'areas.id_predio', '=', 'predios.id')
        ->select('predios.municipio', DB::raw('SUM(areas.cant_total) as total_areas'))
        ->groupBy('predios.municipio')
        ->get();

    // Validación para asegurarse de que hay datos disponibles
    $municipios = $totalAreasPorMunicipio->pluck('municipio')->toArray();
    $totalAreas = $totalAreasPorMunicipio->pluck('total_areas')->toArray();
@endphp
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Dashboard</h5>
                </div>
            </div>
        </div>

        @php
            // Obtener el primer registro de ManGenGanado
            $manGenGanado = \App\Models\ManGenGanado::first(); // O puedes especificar un ID si lo deseas
            $totalAnimalesIdentificados = \App\Models\ManGenGanado::whereNotNull('ident_animales')->count();
        @endphp
        @php
            use App\Models\ManGenGanado;

            // Obtener el tipo de ordeño que más predomina
            $tipoOrdeñoPredominante = ManGenGanado::select('tipo_ordeño')
                ->groupBy('tipo_ordeño')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(1)
                ->pluck('tipo_ordeño')
                ->first();
        @endphp
        <!-- [Converted Leads] end -->
        <!-- [Projects In Progress] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="hstack justify-content-between mb-4 pb-4">
                        <div>
                            <h5 class="mb-1">FORMULARIOS</h5>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3 position-relative">
                            <a href="{{ route('predios.grafica') }}">
                                <div class="card stretch stretch-full border border-dashed border-gray-5 text-center">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                        <img src="{{ asset('img/inofrmacion de predios.png') }}"
                                            class="sesion6 img-fluid mb-2" style="max-width: 70px;"
                                            alt="Información de Predios">
                                        <div class="fs-6 fw-bold text-dark">PREDIO</div>
                                        <div class="fs-4 fw-bold text-dark mt-1">
                                            <span class="counter">{{ \App\Models\Predios::count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3 position-relative">
                            <a href="{{ route('grafico.prepietario') }}">
                                <div class="card stretch stretch-full border border-dashed border-gray-5 text-center">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                        <img src="{{ asset('img/propietarios.png') }}" class="sesion6 img-fluid mb-2"
                                            style="max-width: 70px;" alt="Propietarios">
                                        <div class="fs-6 fw-bold text-dark">PROPIETARIO</div>
                                        <div class="fs-4 fw-bold text-dark mt-1">
                                            <span class="counter">{{ \App\Models\Propietario::count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3 position-relative">
                            <a href="">
                                <div class="card stretch stretch-full border border-dashed border-gray-5 text-center">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                        <img src="{{ asset('img/raza de ganado.png') }}" class="sesion6 img-fluid mb-2"
                                            style="max-width: 70px;" alt="Cantidad de Ganado">
                                        <div class="fs-6 fw-bold text-dark">CANTIDAD DE GANADO</div>
                                        <div class="fs-4 fw-bold text-dark mt-1">
                                            <span class="counter">{{ \App\Models\CensoBovino::count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3 position-relative">
                            <a href="{{ route('gastos.show') }}">
                                <div class="card stretch stretch-full border border-dashed border-gray-5 text-center">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                        <img src="{{ asset('img/dolar orlando.png') }}" class="sesion6 img-fluid mb-2"
                                            style="max-width: 70px;" alt="Movimientos">
                                        <div class="fs-6 fw-bold text-dark">MOVIMIENTOS</div>
                                        <div class="fs-4 fw-bold text-dark mt-1">
                                            <span class="counter">{{ \App\Models\Movimientos::count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3 position-relative">
                            <a href="{{ route('animalesIndenticacion') }}">
                                <div class="card stretch stretch-full border border-dashed border-gray-5 text-center">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                        <img src="{{ asset('/img/icono oscar 2.png') }}" class="sesion6 img-fluid mb-2"
                                            style="max-width: 70px;" alt="Movimientos">
                                        <div class="fs-6 fw-bold text-dark">Tipo de Ordeño</div>
                                        <div class="fs-4 fw-bold text-dark mt-1">
                                            <span
                                                class="counter">{{ $totalAnimalesIdentificados ?? 'No especificado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3 position-relative">
                            <a href="">
                                <div class="card stretch stretch-full border border-dashed border-gray-5 text-center">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                        <img src="{{ asset('img/icono oscar 4.png') }}" class="sesion6 img-fluid mb-2"
                                            style="max-width: 70px;" alt="Movimientos">
                                        <div class="fs-6 fw-bold text-dark">Identificación de Animales</div>
                                        <div class="fs-4 fw-bold text-dark mt-1">
                                            <span
                                                class="fs-5 fw-bold text-dark">{{ $tipoOrdeñoPredominante ?? 'No especificado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="col-md-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h3>Cumplimiento de Información BPG</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="proposalList">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center">Predio</th>
                                        <th class="text-center">Tipo de Información BGP</th>
                                        <th class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Obtener todos los registros de `informacion_bgp` con sus relaciones de predio y tipo de información BGP
                                        $informacionBgp = \App\Models\InformacionBgp::with([
                                            'predio',
                                            'tiposInformacionBgp',
                                        ])->get();
                                    @endphp

                                    @foreach ($informacionBgp as $info)
                                        <tr>
                                            <td class="align-middle text-center">
                                                {{ $info->predio->nombre_predio ?? 'No especificado' }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $info->tiposInformacionBgp->nombre ?? 'No especificado' }}
                                            </td>
                                            <td class="align-middle text-center">
                                                @if ($info->estado == 'Si')
                                                    <span class="badge text-success border border-success p-2"
                                                        style="width: 100px; display: inline-block; text-align: center;">Cumple</span>
                                                @elseif ($info->estado == 'No')
                                                    <span class="badge text-danger border border-danger p-2"
                                                        style="width: 100px; display: inline-block; text-align: center;">No
                                                        Cumple</span>
                                                @else
                                                    <span class="badge text-secondary border border-secondary p-2"
                                                        style="width: 100px; display: inline-block; text-align: center;">No
                                                        Aplica</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Gráfico de Barras para Tipos de Muestras Más Comunes -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tipos de Muestras Más Comunes</h5>
                            <canvas id="tiposMuestrasChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Frecuencia de Ubicación en Vía -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ubicación en Vía</h5>
                            <canvas id="ubicacionViaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Gráfico Circular para Proporción de Predios con y sin Enfermedades -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Proporción de Predios con y sin Enfermedades</h5>
                            <canvas id="enfermedadesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfico Circular para Proporción de Predios con y sin Toma de Muestra -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Proporción de Predios con y sin Toma de Muestra</h5>
                            <canvas id="muestrasChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de predios que colindan con establecimientos de riesgo -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Predios que Colindan con Establecimientos de Riesgo</h5>
                            <canvas id="colindaEstablecimChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard - Resumen de Tipo de Explotación de los Predios</h5>
                    </div>

                </div>
            </div>
            <div class="row">
                <!-- Verificar si hay datos en el resumen -->
                @if (count($summary) == 0)
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            No hay datos disponibles para mostrar.
                        </div>
                    </div>
                @else
                    <!-- Mostrar las tarjetas solo si hay datos -->
                    @foreach ($summary as $category => $info)
                        <div class="col-xxl-3 col-md-6">
                            <div class="card stretch custom-card-style">
                                <div class="card-body">
                                    <!-- Imagen en la esquina superior derecha -->
                                    <img src="{{ asset('/img/dashboard/' . ($categoryImages[$category] ?? 'default.png')) }}"
                                        alt="{{ ucfirst($category) }}" class="category-image">

                                    <h3 class="fs-13 fw-semibold text-truncate-1-line">
                                        {{ ucfirst($category) }}</h3>
                                    <p>Tipo Más Común: <strong>{{ $info['most_common'] }}</strong>
                                        ({{ $info['common_count'] }} predios)
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <!--! END: [Team Progress] !-->
        </div>
    </div>
    </div>
    </div>

    </div>
@endsection --}}

@section('scripts')
 {{--    <script src="{{ asset('assets/vendors/js/daterangepicker.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#proposalList')) {
                $('#proposalList').DataTable().clear().destroy();
            }
            $('#proposalList').DataTable({
                language: {
                    "decimal": "",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "emptyTable": "No tienes predios asignados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
            });
        });
    </script>
    <script>
        new Chart(document.getElementById('colindaEstablecimChart'), {
            type: 'pie',
            data: {
                labels: ['Colinda', 'No Colinda'],
                datasets: [{
                    data: [{{ $colindaEstablecim->colinda }}, {{ $colindaEstablecim->no_colinda }}],
                    backgroundColor: ['#FF6384', '#36A2EB']
                }]
            }
        });

        new Chart(document.getElementById('ubicacionViaChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($ubicacionVia->pluck('ubica_en_via')) !!},
                datasets: [{
                    label: 'Frecuencia',
                    data: {!! json_encode($ubicacionVia->pluck('frecuencia')) !!},
                    backgroundColor: '#FFCE56'
                }]
            },
            options: {
                indexAxis: 'y'
            }
        });

    </script>
    <script>
        var prediosConEnfermedad = {{ $resultEnfermedades->predios_con_enfermedad }};
        var prediosSinEnfermedad = {{ $resultEnfermedades->predios_sin_enfermedad }};
        var ctxEnfermedades = document.getElementById('enfermedadesChart').getContext('2d');
        new Chart(ctxEnfermedades, {
            type: 'pie',
            data: {
                labels: ['Con Enfermedad', 'Sin Enfermedad'],
                datasets: [{
                    data: [prediosConEnfermedad, prediosSinEnfermedad],
                    backgroundColor: ['#FF6384', '#36A2EB'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label + ' predios';
                            }
                        }
                    }
                }
            }
        });

        var prediosConMuestra = {{ $resultMuestras->predios_con_muestra }};
        var prediosSinMuestra = {{ $resultMuestras->predios_sin_muestra }};
        var ctxMuestras = document.getElementById('muestrasChart').getContext('2d');
        new Chart(ctxMuestras, {
            type: 'pie',
            data: {
                labels: ['Con Muestra', 'Sin Muestra'],
                datasets: [{
                    data: [prediosConMuestra, prediosSinMuestra],
                    backgroundColor: ['#4BC0C0', '#FFCE56'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label + ' predios';
                            }
                        }
                    }
                }
            }
        });

        var tiposMuestrasLabels = {!! json_encode($tiposMuestras->pluck('toma_muestra_tipos')) !!};
        var tiposMuestrasData = {!! json_encode($tiposMuestras->pluck('frecuencia')) !!};
        var ctxTiposMuestras = document.getElementById('tiposMuestrasChart').getContext('2d');
        new Chart(ctxTiposMuestras, {
            type: 'bar',
            data: {
                labels: tiposMuestrasLabels,
                datasets: [{
                    label: 'Frecuencia',
                    data: tiposMuestrasData,
                    backgroundColor: '#FF9F40',
                    borderColor: '#FF9F40',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' predios';
                            }
                        }
                    }
                }
            }
        });
    </script>

<script>
    var tipoMedidasLabels = {!! json_encode($totalMedidasPorTipo->pluck('tipo_medidas')) !!};
    var totalMedidasData = {!! json_encode($totalMedidasPorTipo->pluck('total')) !!};

    var rutaHectareas = @json(route('areas.hectareas'));
    var rutaMetrosCuadrados = @json(route('areas.metros_cuadrados'));

    var ctx = document.getElementById('areasPorTipoMedidaChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: tipoMedidasLabels,
            datasets: [{
                label: 'Total de Medidas (m²)',
                data: totalMedidasData,
                backgroundColor: '#4BC0C0',
                borderColor: '#36A2EB',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    categoryPercentage: 0.5,
                    barPercentage: 0.8
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' m²';
                        }
                    }
                }
            },
            onClick: (e, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const tipoMedida = tipoMedidasLabels[index];

                    if (tipoMedida === "Hectareas") {
                        window.location.href = rutaHectareas;
                    } else if (tipoMedida === "Metros Cuadrados") {
                        window.location.href = rutaMetrosCuadrados;
                    }
                }
            }
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const municipios = @json($municipios);
        const totalAreas = @json($totalAreas);

        const ctx = document.getElementById('graficaAreasMunicipios').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: municipios,
                datasets: [{
                    data: totalAreas,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'left',
                        align: 'center',
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Total de Áreas por Municipio',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    });
</script>
 --}}

@endsection
