@extends('layouts')

@section('template_title')
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predios) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Risesgo Epidemiologico</li>
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
                        <h5 class="mb-1">FORMULARIOS</h5>

                    </div>
                </div>
                @php
                    $registroTipoExplotacion = \App\Models\Tipo_explotacion::where('id_predio', $predios->id)->first();
                    $registroInforEpidemiologica = \App\Models\InforEpidemiologica::where('id_predio',$predios->id,)->first();
                    $registroCaracterizacionRiesgo = \App\Models\CarazterizacionRiesgo::where('id_predio', $predios->id)->first();
                    $registroVisitaPrediosRiesgo = \App\Models\VisitaPrediosRiesgo::where('id_predio', $predios->id)->first();
                @endphp
                <div class="row justify-content-center">

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('tipo_explotacion.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion4/tipo de explotacion.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">TIPO EXPLOTACIÓN</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroTipoExplotacion)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('inforEpidemiologica.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion4/informacion epidemiologica.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">INFORMACIÓN EPIDEMIOLÓGICA</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroInforEpidemiologica)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('caracterizacion_riesgo.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion4/caracterizacion de riesgo.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">CARACTERIZACIÓN DE RIESGO</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCaracterizacionRiesgo)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('visita_predios_riesgo.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion4/visita a predios de riesgo..png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">VISITA A PREDIOS DE RIESGO</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroVisitaPrediosRiesgo)
                            <!-- Mostrar el ícono si existe el registro -->
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
