@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion6', $predioId) }}">Censo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">PORCINOS</li>
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
                <form action="{{ route('censo_porcinos.update', $CensoPorcino->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Input oculto para id_predio -->
                    <input type="hidden" name="id_predio" value="{{ $CensoPorcino->id_predio }}">

                    <!-- Lactancia hasta 30 días -->
                    <div class="mb-3">
                        <label for="lact_hast_30_dias2" class="form-label">Lactancia hasta 30 días:</label>
                        <input type="number" class="form-control" name="lact_hast_30_dias" id="lact_hast_30_dias2"
                            value="{{ old('lact_hast_30_dias', $CensoPorcino->lact_hast_30_dias) }}" required>
                    </div>

                    <!-- Precebo 31 a 60 días -->
                    <div class="mb-3">
                        <label for="precebo_31_a_60_dias2" class="form-label">Precebo 31 a 60 días:</label>
                        <input type="number" class="form-control" name="precebo_31_a_60_dias" id="precebo_31_a_60_dias2"
                            value="{{ old('precebo_31_a_60_dias', $CensoPorcino->precebo_31_a_60_dias) }}" required>
                    </div>

                    <!-- Levante y Cebo 61 a 180 días -->
                    <div class="mb-3">
                        <label for="lev_ceb_61_180_dias2" class="form-label">Levante y Cebo 61 a 180 días:</label>
                        <input type="number" class="form-control" name="lev_ceb_61_180_dias" id="lev_ceb_61_180_dias2"
                            value="{{ old('lev_ceb_61_180_dias', $CensoPorcino->lev_ceb_61_180_dias) }}" required>
                    </div>

                    <!-- Reemplazo menos de 8 meses (Hembras) -->
                    <div class="mb-3">
                        <label for="reempl_men_8_meses_h2" class="form-label">Reemplazo menos de 8 meses (Hembras):</label>
                        <input type="number" class="form-control" name="reempl_men_8_meses_h" id="reempl_men_8_meses_h2"
                            value="{{ old('reempl_men_8_meses_h', $CensoPorcino->reempl_men_8_meses_h) }}" required>
                    </div>

                    <!-- Cría menos de 8 meses (Hembras) -->
                    <div class="mb-3">
                        <label for="cria_men_8_meses_h2" class="form-label">Cría menos de 8 meses (Hembras):</label>
                        <input type="number" class="form-control" name="cria_men_8_meses_h" id="cria_men_8_meses_h2"
                            value="{{ old('cria_men_8_meses_h', $CensoPorcino->cria_men_8_meses_h) }}" required>
                    </div>

                    <!-- Macho Reproductor menos de 6 meses -->
                    <div class="mb-3">
                        <label for="macho_reprod_men_6_meses2" class="form-label">Macho Reproductor menos de 6 meses:</label>
                        <input type="number" class="form-control" name="macho_reprod_men_6_meses" id="macho_reprod_men_6_meses2"
                            value="{{ old('macho_reprod_men_6_meses', $CensoPorcino->macho_reprod_men_6_meses) }}" required>
                    </div>

                    <!-- Total de Porcinos -->
                    <div class="mb-3">
                        <label for="total_porcinos2" class="form-label">Total Porcinos:</label>
                        <input type="number" class="form-control" name="total_porcinos" id="total_porcinos2"
                            value="{{ old('total_porcinos', $CensoPorcino->total_porcinos) }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>


                @else

                <form action="{{ route('censo_porcinos.store') }}" method="POST">
                    @csrf

                    <!-- Input oculto para id_predio -->
                    <input type="hidden" name="id_predio" value="{{ $predioId }}">

                    <!-- Lactancia hasta 30 días -->
                    <div class="mb-3">
                        <label for="lact_hast_30_dias" class="form-label">Lactancia hasta 30 días:</label>
                        <input type="number" class="form-control" name="lact_hast_30_dias" id="lact_hast_30_dias"
                            value="{{ old('lact_hast_30_dias') }}" required>
                    </div>

                    <!-- Precebo 31 a 60 días -->
                    <div class="mb-3">
                        <label for="precebo_31_a_60_dias" class="form-label">Precebo 31 a 60 días:</label>
                        <input type="number" class="form-control" name="precebo_31_a_60_dias" id="precebo_31_a_60_dias"
                            value="{{ old('precebo_31_a_60_dias') }}" required>
                    </div>

                    <!-- Levante y Cebo 61 a 180 días -->
                    <div class="mb-3">
                        <label for="lev_ceb_61_180_dias" class="form-label">Levante y Cebo 61 a 180 días:</label>
                        <input type="number" class="form-control" name="lev_ceb_61_180_dias" id="lev_ceb_61_180_dias"
                            value="{{ old('lev_ceb_61_180_dias') }}" required>
                    </div>

                    <!-- Reemplazo menos de 8 meses (Hembras) -->
                    <div class="mb-3">
                        <label for="reempl_men_8_meses_h" class="form-label">Reemplazo menos de 8 meses (Hembras):</label>
                        <input type="number" class="form-control" name="reempl_men_8_meses_h" id="reempl_men_8_meses_h"
                            value="{{ old('reempl_men_8_meses_h') }}" required>
                    </div>

                    <!-- Cría menos de 8 meses (Hembras) -->
                    <div class="mb-3">
                        <label for="cria_men_8_meses_h" class="form-label">Cría menos de 8 meses (Hembras):</label>
                        <input type="number" class="form-control" name="cria_men_8_meses_h" id="cria_men_8_meses_h"
                            value="{{ old('cria_men_8_meses_h') }}" required>
                    </div>

                    <!-- Macho Reproductor menos de 6 meses -->
                    <div class="mb-3">
                        <label for="macho_reprod_men_6_meses" class="form-label">Macho Reproductor menos de 6 meses:</label>
                        <input type="number" class="form-control" name="macho_reprod_men_6_meses" id="macho_reprod_men_6_meses"
                            value="{{ old('macho_reprod_men_6_meses') }}" required>
                    </div>

                    <!-- Total de Porcinos -->
                    <div class="mb-3">
                        <label for="total_porcinos" class="form-label">Total Porcinos:</label>
                        <input type="number" class="form-control" name="total_porcinos" id="total_porcinos"
                            value="{{ old('total_porcinos') }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>

                </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        function calculateTotalPorcinos() {
            // Obtener los valores de todos los inputs
            let lact_hast_30_dias = parseInt(document.getElementById('lact_hast_30_dias').value) || 0;
            let precebo_31_a_60_dias = parseInt(document.getElementById('precebo_31_a_60_dias').value) || 0;
            let lev_ceb_61_180_dias = parseInt(document.getElementById('lev_ceb_61_180_dias').value) || 0;
            let reempl_men_8_meses_h = parseInt(document.getElementById('reempl_men_8_meses_h').value) || 0;
            let cria_men_8_meses_h = parseInt(document.getElementById('cria_men_8_meses_h').value) || 0;
            let macho_reprod_men_6_meses = parseInt(document.getElementById('macho_reprod_men_6_meses').value) || 0;

            // Calcular el total
            let total_porcinos = lact_hast_30_dias + precebo_31_a_60_dias + lev_ceb_61_180_dias + reempl_men_8_meses_h + cria_men_8_meses_h + macho_reprod_men_6_meses;

            // Establecer el valor en el campo total_porcinos
            document.getElementById('total_porcinos').value = total_porcinos;
        }

        // Ejecutar la función cada vez que se modifique un input
        document.getElementById('lact_hast_30_dias').oninput = calculateTotalPorcinos;
        document.getElementById('precebo_31_a_60_dias').oninput = calculateTotalPorcinos;
        document.getElementById('lev_ceb_61_180_dias').oninput = calculateTotalPorcinos;
        document.getElementById('reempl_men_8_meses_h').oninput = calculateTotalPorcinos;
        document.getElementById('cria_men_8_meses_h').oninput = calculateTotalPorcinos;
        document.getElementById('macho_reprod_men_6_meses').oninput = calculateTotalPorcinos;
    </script>

<script>
    function calculateTotalPorcinos2() {
        // Obtener los valores de todos los inputs
        let lact_hast_30_dias2 = parseInt(document.getElementById('lact_hast_30_dias2').value) || 0;
        let precebo_31_a_60_dias2 = parseInt(document.getElementById('precebo_31_a_60_dias2').value) || 0;
        let lev_ceb_61_180_dias2 = parseInt(document.getElementById('lev_ceb_61_180_dias2').value) || 0;
        let reempl_men_8_meses_h2 = parseInt(document.getElementById('reempl_men_8_meses_h2').value) || 0;
        let cria_men_8_meses_h2 = parseInt(document.getElementById('cria_men_8_meses_h2').value) || 0;
        let macho_reprod_men_6_meses2 = parseInt(document.getElementById('macho_reprod_men_6_meses2').value) || 0;

        // Calcular el total
        let total_porcinos2 = lact_hast_30_dias2 + precebo_31_a_60_dias2 + lev_ceb_61_180_dias2 + reempl_men_8_meses_h2 + cria_men_8_meses_h2 + macho_reprod_men_6_meses2;

        // Establecer el valor en el campo total_porcinos
        document.getElementById('total_porcinos2').value = total_porcinos2;
    }

    // Ejecutar la función cada vez que se modifique un input
    document.getElementById('lact_hast_30_dias2').oninput = calculateTotalPorcinos2;
    document.getElementById('precebo_31_a_60_dias2').oninput = calculateTotalPorcinos2;
    document.getElementById('lev_ceb_61_180_dias2').oninput = calculateTotalPorcinos2;
    document.getElementById('reempl_men_8_meses_h2').oninput = calculateTotalPorcinos2;
    document.getElementById('cria_men_8_meses_h2').oninput = calculateTotalPorcinos2;
    document.getElementById('macho_reprod_men_6_meses2').oninput = calculateTotalPorcinos2;
</script>


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
