@extends('layouts')

@section('template_title')
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Caracterizacion De Predios</li>
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

        .border-dashed {
            border-style: none !important;
        }

        .sesion6 {
            width: 57px;
        }
    </style>




    <!-- [Invoices Awaiting Payment] start -->
    <div class="col-12">
        <div class="card stretch stretch-full">
            <div class="card-body">
                <div class="hstack justify-content-between mb-4 pb-">
                    <div>
                        <h5 class="mb-1">SECCIONES</h5>

                    </div>
                </div>
                <div class="row justify-content-center">
                    @php
                        $registroAreas = \App\Models\Areas::where('id_predio', $predios->id)->first();
                        $registroInfoTierraAgua = \App\Models\InfoTierraAgua::where('id_predio', $predios->id)->first();
                        $registroManPastPotrCer = \App\Models\ManPastPotrCer::where('id_predio', $predios->id)->first();
                        $registroManGenGanado = \App\Models\ManGenGanado::where('id_predio', $predios->id)->first();
                        $registroInformAspectMedAmbient = \App\Models\InformAspectMedAmbient::where(
                            'id_predio',
                            $predios->id,
                        )->first();
                        $registroInstalacionesEquipos = \App\Models\InstalacionesEquipos::where(
                            'id_predio',
                            $predios->id,
                        )->first();
                        $registroGestionInformacion = \App\Models\GestionInformacion::where(
                            'id_predio',
                            $predios->id,
                        )->first();

                        // Verifica si todos los registros están presentes
                        $completado =
                            $registroAreas &&
                            $registroInfoTierraAgua &&
                            $registroManPastPotrCer &&
                            $registroManGenGanado &&
                            $registroInformAspectMedAmbient &&
                            $registroInstalacionesEquipos &&
                            $registroGestionInformacion;

                        $registroCensoBovino = \App\Models\CensoBovino::where('id_predio', $predios->id)->first();
                        $registroCensoBufalino = \App\Models\CensoBufalino::where('id_predio', $predios->id)->first();
                        $registroCensoPorcino = \App\Models\CensoPorcino::where('id_predio', $predios->id)->first();
                        $registroCensoEquido = \App\Models\CensoEquido::where('id_predio', $predios->id)->first();
                        $registroCensoOvinoCaprino = \App\Models\CensoOvinoCaprino::where(
                            'id_predio',
                            $predios->id,
                        )->first();
                        $registroCensoOtrasEspec = \App\Models\CensoOtrasEspec::where(
                            'id_predio',
                            $predios->id,
                        )->first();
                        $registroCensoPez = \App\Models\CensoPez::where('id_predio', $predios->id)->first();
                        $registroCensoCructaceo = \App\Models\CensoCructaceo::where('id_predio', $predios->id)->first();
                        $registroCensoAvesComerciales = \App\Models\CensoAvesComerciales::where(
                            'id_predio',
                            $predios->id,
                        )->first();
                        $registroCensoAvesTraspatio = \App\Models\CensoAvesTraspatio::where(
                            'id_predio',
                            $predios->id,
                        )->first();
                        $registroCensoAbejas = \App\Models\CensoAbejas::where('id_predio', $predios->id)->first();
                        $registroIdentificacionAnimal = \App\Models\IdentificacionAnimal::where(
                            'id_predio',
                            $predios->id,
                        )->first();

                        // Verifica si todos los registros están presentes
                        $todosRegistrosCompletos =
                            $registroCensoBovino &&
                            $registroCensoBufalino &&
                            $registroCensoPorcino &&
                            $registroCensoEquido &&
                            $registroCensoOvinoCaprino &&
                            $registroCensoOtrasEspec &&
                            $registroCensoPez &&
                            $registroCensoCructaceo &&
                            $registroCensoAvesComerciales &&
                            $registroCensoAvesTraspatio &&
                            $registroCensoAbejas &&
                            $registroIdentificacionAnimal;

                        $registroTipoExplotacion = \App\Models\Tipo_explotacion::where(
                            'id_predio',
                            $predios->id,
                        )->first();
                        $registroInforEpidemiologica = \App\Models\InforEpidemiologica::where(
                            'id_predio',
                            $predios->id,
                        )->first();
                        $registroCarazterizacionRiesgo = \App\Models\CarazterizacionRiesgo::where(
                            'id_predio',
                            $predios->id,
                        )->first();
                        $registroVisitaPrediosRiesgo = \App\Models\VisitaPrediosRiesgo::where(
                            'id_predio',
                            $predios->id,
                        )->first();

                        // Verifica si todos los registros están presentes
                        $todosRegistrosRiesgoCompletos =
                            $registroTipoExplotacion &&
                            $registroInforEpidemiologica &&
                            $registroCarazterizacionRiesgo &&
                            $registroVisitaPrediosRiesgo;


                            $registroServiciosAmbientales = \App\Models\ServiciosAmbientales::where('id_predio', $predios->id)->first();

                            $registroInformacionBgp = \App\Models\InformacionBgp::where('id_predio', $predios->id)->first();

                    @endphp

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('Seccion2', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/caracterizacion/inofrmacion de predios.png') }}" class="sesion6"
                                        alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">INFORMACION DEL PREDIO</div>
                                </div>
                            </div>
                        </a>

                        @if ($completado)
                            <!-- Mostrar el ícono si todos los registros están presentes -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    @if ($registroInformacionBgp)
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('informacion-bgps.show', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/caracterizacion/inoformacion para bpg.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">INFORMACION PARA BPG</div>
                                </div>
                            </div>
                        </a>

                            <!-- Mostrar el ícono si el registro está presente -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                    </div>
                    @else
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('informacion-bgps.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/caracterizacion/inoformacion para bpg.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">INFORMACION PARA BPG</div>
                                </div>
                            </div>
                        </a>

                    </div>
                    @endif


                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('Seccion4', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/caracterizacion/riesgo epidemiologio.png') }}" class="sesion6"
                                        alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">RIESGO EPIDEMIOLOGICO</div>
                                </div>
                            </div>
                        </a>

                        @if ($todosRegistrosRiesgoCompletos)
                            <!-- Mostrar el ícono si todos los registros están presentes -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('servicios_ambientales.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/caracterizacion/servicios ambientales.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">SERVICIOS AMBIENTALES</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroServiciosAmbientales)
                            <!-- Mostrar el ícono si el registro está presente -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('Seccion6', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/caracterizacion/censo.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">CENSO</div>
                                </div>
                            </div>
                        </a>

                        @if ($todosRegistrosCompletos)
                            <!-- Mostrar el ícono si todos los registros están presentes -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- [Conversion Rate] end -->

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}'
                });
            });
        </script>
    @endif
@endsection
