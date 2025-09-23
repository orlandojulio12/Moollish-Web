@extends('layouts')

@section('template_title')
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href=""><i class="bi bi-arrow-left"
                        style="margin-right: 10px; font-size: 24px; color: black;"></i></a><!-- Añade un margen para espaciar el ícono -->
                <h5>Exportar las seciones en excel</h5>
            </div>
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
                
                <div>
                    <h5 class="mb-1">SECCIONES</h5>

                </div>
            </div>
            <div class="row justify-content-center">
               
                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <a href="{{ route('exportarInformacionDelPredio') }}">
                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/inofrmacion de predios.png') }}" class="sesion6"
                                    alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">INFORMACION DEL PREDIO</div>
                            </div>
                        </div>
                    </a>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style=" width: 35px;" alt="">
                </div>

                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <a href="{{ route('exportBgp') }}">
                        <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/inoformacion para bpg.png') }}" class="sesion6" alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">INFORMACION PARA BPG</div>
                            </div>
                        </div>
                    </a>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style=" width: 35px;" alt="">
                </div>

                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <a href="{{ route('export.riesgo.epidemiologico') }}">
                        <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/riesgo epidemiologio.png') }}" class="sesion6"
                                    alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">RIESGO EPIDEMIOLOGICO</div>
                            </div>
                        </div>
                    </a>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style=" width: 35px;" alt="">
                </div>

                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <a href="{{ route('exportServiciosAmbientales') }}">
                        <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/servicios ambientales.png') }}" class="sesion6" alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">SERVICIOS AMBIENTALES</div>
                            </div>
                        </div>
                    </a>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style=" width: 35px;" alt="">
                </div>

                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <a href="{{ route('exportCenso') }}">
                        <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/censo.png') }}" class="sesion6" alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">CENSO</div>
                            </div>
                        </div>
                    </a>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style=" width: 35px;" alt="">
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
