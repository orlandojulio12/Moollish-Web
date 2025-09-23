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
                    <li class="breadcrumb-item active" aria-current="page">Informacion Del Predio</li>
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
                <div class="row justify-content-center">
                    @php
                        $registroAreas = \App\Models\Areas::where('id_predio', $predios->id)->first();
                        $registroInfoTierraAgua = \App\Models\InfoTierraAgua::where('id_predio', $predios->id)->first();
                        $registroManPastPotrCer = \App\Models\ManPastPotrCer::where('id_predio', $predios->id)->first();
                        $registroManGenGanado = \App\Models\ManGenGanado::where('id_predio', $predios->id)->first();
                        $registroInformAspectMedAmbient = \App\Models\InformAspectMedAmbient::where('id_predio', $predios->id)->first();
                        $registroInstalacionesEquipos = \App\Models\InstalacionesEquipos::where('id_predio', $predios->id)->first();
                        $registroGestionInformacion = \App\Models\GestionInformacion::where('id_predio', $predios->id)->first();

                    @endphp
                    <!-- Primera fila: 4 tarjetas -->
                    <div class="col-xxl-2 col-lg-3 col-md-6 position-relative">
                        <a href="{{ route('predios.caracterizar', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion2/inofrmacion de areas.png') }}" class="sesion6"
                                        alt="">
                                    <div class="fs-6 fw-bolder text-dark mt-3 mb-1">INFORMACIÓN DE ÁREAS</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroAreas)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>


                    <div class="col-xxl-2 col-lg-3 col-md-6 position-relative">
                        <a href="{{ route('info_tierra_agua.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion2/inoformacion de tierras y aguas.png') }}"
                                        class="sesion6" alt="">
                                    <div class="fs-6 fw-bolder text-dark mt-3 mb-1">INFORMACIÓN TIERRAS Y AGUAS</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroInfoTierraAgua)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                    <div class="col-xxl-2 col-lg-3 col-md-6 position-relative">
                        <a href="{{ route('man_past_potr_cerc.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion2/manejo de pastos.png') }}" class="sesion6"
                                        alt="">
                                    <div class="fs-6 fw-bolder text-dark mt-3 mb-1">MANEJO DE PASTOS - POTREROS - CERCAS
                                    </div>
                                </div>
                            </div>
                        </a>

                        @if ($registroManPastPotrCer)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>


                    <div class="col-xxl-2 col-lg-3 col-md-6 position-relative">
                        <a href="{{ route('man_gen_ganado.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion2/manejo general de ganado.png') }}" class="sesion6"
                                        alt="">
                                    <div class="fs-6 fw-bolder text-dark mt-3 mb-1">MANEJO GENERAL GANADO</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroManGenGanado)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                </div>

                <!-- Segunda fila: 3 tarjetas -->
                <div class="row justify-content-center mt-4">
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('info_aspect_med_ambient.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion2/informacion medio ambientales.png') }}" class="sesion6" alt="">
                                    <div class="fs-6 fw-bolder text-dark mt-3 mb-1">INFORMACIÓN MEDIO AMBIENTALES</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroInformAspectMedAmbient)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('instalaciones_equipos.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion2/instalaciones y equipos..png') }}" class="sesion6" alt="">
                                    <div class="fs-6 fw-bolder text-dark mt-3 mb-1">INSTALACIONES Y EQUIPOS</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroInstalacionesEquipos)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('gestion_informacion.create', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/seccion2/gestion de informacion.png') }}" class="sesion6" alt="">
                                    <div class="fs-6 fw-bolder text-dark mt-3 mb-1">GESTIÓN DE INFORMACIÓN</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroGestionInformacion)
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
