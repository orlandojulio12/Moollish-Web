@extends('layouts')

@section('template_title')
    Manejo de Pastos, Potreros y Cercas
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion6', $predioId) }}">Censo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">OTRAS ESPECIES</li>
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
                @if ($censoExistente)
                <form action="{{ route('censo_otras_espec.update', $CensoOtrasEspec->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Input oculto para id_predio -->
                    <input type="hidden" name="id_predio" value="{{ $CensoOtrasEspec->id_predio }}">

                    <!-- Input para Llamas -->
                    <div class="mb-3">
                        <label for="llamas" class="form-label">Llamas:</label>
                        <input type="number" class="form-control" name="llamas" id="llamas"
                            value="{{ old('llamas', $CensoOtrasEspec->llamas) }}" required>
                    </div>

                    <!-- Input para Alpacas -->
                    <div class="mb-3">
                        <label for="alpacas" class="form-label">Alpacas:</label>
                        <input type="number" class="form-control" name="alpacas" id="alpacas"
                            value="{{ old('alpacas', $CensoOtrasEspec->alpacas) }}" required>
                    </div>

                    <!-- Input para Avestruces -->
                    <div class="mb-3">
                        <label for="avectruces" class="form-label">Avestruces:</label>
                        <input type="number" class="form-control" name="avectruces" id="avectruces"
                            value="{{ old('avectruces', $CensoOtrasEspec->avectruces) }}" required>
                    </div>

                    <!-- Input para Otras Especies -->
                    <div class="mb-3">
                        <label for="otras" class="form-label">Otras Especies (Especificar):</label>
                        <input type="text" class="form-control" name="otras" id="otras"
                            value="{{ old('otras', $CensoOtrasEspec->otras) }}">
                    </div>

                    <!-- Input para Cuántas Otras Especies -->
                    <div class="mb-3">
                        <label for="cuantas_otras" class="form-label">Cantidad de Otras Especies:</label>
                        <input type="number" class="form-control" name="cuantas_otras" id="cuantas_otras"
                            value="{{ old('cuantas_otras', $CensoOtrasEspec->cuantas_otras) }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>


               @else
                <form action="{{ route('censo_otras_espec.store') }}" method="POST">
                    @csrf

                    <!-- Input oculto para id_predio -->
                    <input type="hidden" name="id_predio" value="{{ $predioId }}">

                    <!-- Input para Llamas -->
                    <div class="mb-3">
                        <label for="llamas" class="form-label">Llamas:</label>
                        <input type="number" class="form-control" name="llamas" id="llamas"
                            value="{{ old('llamas') }}" required>
                    </div>

                    <!-- Input para Alpacas -->
                    <div class="mb-3">
                        <label for="alpacas" class="form-label">Alpacas:</label>
                        <input type="number" class="form-control" name="alpacas" id="alpacas"
                            value="{{ old('alpacas') }}" required>
                    </div>

                    <!-- Input para Avestruces -->
                    <div class="mb-3">
                        <label for="avectruces" class="form-label">Avestruces:</label>
                        <input type="number" class="form-control" name="avectruces" id="avectruces"
                            value="{{ old('avectruces') }}" required>
                    </div>

                    <!-- Input para Otras Especies -->
                    <div class="mb-3">
                        <label for="otras" class="form-label">Otras Especies (Especificar):</label>
                        <input type="text" class="form-control" name="otras" id="otras"
                            value="{{ old('otras') }}">
                    </div>

                    <!-- Input para Cuántas Otras Especies -->
                    <div class="mb-3">
                        <label for="cuantas_otras" class="form-label">Cantidad de Otras Especies:</label>
                        <input type="number" class="form-control" name="cuantas_otras" id="cuantas_otras"
                            value="{{ old('cuantas_otras') }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
                @endif

            </div>
        </div>
    </div>



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
