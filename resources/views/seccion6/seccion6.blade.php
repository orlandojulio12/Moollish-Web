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
                    <li class="breadcrumb-item active" aria-current="page">Censo</li>
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
                    @php
                        $registroCensoBovinos = \App\Models\CensoBovino::where('id_predio', $predios->id)->first();
                        $registroCensoBufalino = \App\Models\CensoBufalino::where('id_predio', $predios->id)->first();
                        $registroCensoPorcino = \App\Models\CensoPorcino::where('id_predio', $predios->id)->first();
                        $registroCensoEquido = \App\Models\CensoEquido::where('id_predio', $predios->id)->first();
                        $registroCensoOvinoCaprino = \App\Models\CensoOvinoCaprino::where('id_predio', $predios->id)->first();
                        $registroCensoOtrasEspec = \App\Models\CensoOtrasEspec::where('id_predio', $predios->id)->first();
                        $registroCensoPez = \App\Models\CensoPez::where('id_predio', $predios->id)->first();
                        $registroCensoCructaceo = \App\Models\CensoCructaceo::where('id_predio', $predios->id)->first();
                        $registroCensoAvesComerciales = \App\Models\CensoAvesComerciales::where('id_predio', $predios->id)->first();
                        $registroCensoAvesTraspatio = \App\Models\CensoAvesTraspatio::where('id_predio', $predios->id)->first();
                        $registroCensoAbejas = \App\Models\CensoAbejas::where('id_predio', $predios->id)->first();
                        $registroIdentificacionAnimal = \App\Models\IdentificacionAnimal::where('id_predio', $predios->id)->first();
                    @endphp

                </div>
                <div class="row">

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('CensoBovino', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/bocinos.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">BOVINOS</div>
                                </div>
                            </div>
                        </a>
                        <!-- Icono en la esquina superior izquierda -->

                        @if ($registroCensoBovinos)
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>


                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('CensoBufalino', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative"> <!-- Agregamos 'position-relative' -->
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/bufalinos.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">BUFALINOS</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoBufalino)
                            <!-- Colocamos el ícono dentro del contenedor relativo -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('censo_porcinos', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/porcinos.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">PORCINOS</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoPorcino)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('censo_equidos', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/equidos.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">ÉQUIDOS</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoEquido)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('censo_ovino_caprino', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/ovinos y caprios.png') }}" class="sesion6" style="width: 100px" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">OVINOS Y CAPRINOS</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoOvinoCaprino)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('censo_otras_espec', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/otras especies.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">OTRAS ESPECIES</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoOtrasEspec)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('censo_peces', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/peces.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">PECES</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoPez)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('censo_cructaceos', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/crustaceos.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">CRUSTÁCEOS</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoCructaceo)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('censo_aves_comerciales', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/aves comerciales .png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">AVES COMERCIALES</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoAvesComerciales)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('censo_aves_traspatio', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/otras aves.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">OTRAS AVES</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoAvesTraspatio)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('censo_abejas', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/abejas.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">ABEJAS</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroCensoAbejas)
                            <!-- Mostrar el ícono si existe el registro -->
                            <i class="bi bi-check-circle text-success position-absolute top-0 end-0 mt-2 me-4"></i>
                        @endif
                    </div>


                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="{{ route('identificacionAnimal', $predios) }}">
                            <div class="card stretch stretch-full border border-dashed border-gray-5">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/identificacion animal.png') }}" class="sesion6" alt="">
                                    <div class="fs-5 fw-bolder text-dark mt-3 mb-1">IDENTIFICACIÓN ANIMAL</div>
                                </div>
                            </div>
                        </a>

                        @if ($registroIdentificacionAnimal)
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
