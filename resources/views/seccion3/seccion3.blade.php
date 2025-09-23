@extends('layouts')

@section('template_title')
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>CARATERIZACIÓN DE PREDIOS</h5>
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
                <div class="hstack justify-content-between mb-4 pb-">
                    <div>
                        <h5 class="mb-1">SECCIONES</h5>

                    </div>
                </div>
                <div class="row justify-content-center">

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="#">
                            <div class="card stretch stretch-full border border-dashed border-gray-5">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/bocinos.png') }}" class="sesion6" alt="">
                                    <div class="fs-4 fw-bolder text-dark mt-3 mb-1">SANIDAD ANIMAL</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="#">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative"> <!-- Agregamos 'position-relative' -->
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/bufalinos.png') }}" class="sesion6" alt="">
                                    <div class="fs-4 fw-bolder text-dark mt-3 mb-1">IDENTIFICACIÓN</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="#">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/porcinos.png') }}" class="sesion6" alt="">
                                    <div class="fs-4 fw-bolder text-dark mt-3 mb-1">BIOSEGURIDAD</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="#">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/equidos.png') }}" class="sesion6" alt="">
                                    <div class="fs-4 fw-bolder text-dark mt-3 mb-1">MEDICAMENTOS VETERINARIOS</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                    <div class="row justify-content-center mt-4">
                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="#">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/ovinos y caprios.png') }}" class="sesion6" style="width: 100px" alt="">
                                    <div class="fs-4 fw-bolder text-dark mt-3 mb-1">ALIMENTACIÓN ANIMAL</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="#">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/ovinos y caprios.png') }}" class="sesion6" style="width: 100px" alt="">
                                    <div class="fs-4 fw-bolder text-dark mt-3 mb-1">REQUISITOS DE SANEAMIENTO</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="#">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/ovinos y caprios.png') }}" class="sesion6" style="width: 100px" alt="">
                                    <div class="fs-4 fw-bolder text-dark mt-3 mb-1">BIENESTAR ANIMAL </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                        <a href="#">
                            <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                                <div class="card-body rounded-3 text-center">
                                    <img src="{{ asset('img/sesion/ovinos y caprios.png') }}" class="sesion6" style="width: 100px" alt="">
                                    <div class="fs-4 fw-bolder text-dark mt-3 mb-1">REQUISITOS DE  PERSONAL</div>
                                </div>
                            </div>
                        </a>
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
