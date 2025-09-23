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
                    <li class="breadcrumb-item active" aria-current="page">BUFALINOS</li>
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
                    <form action="{{ route('censo_bufalinos.update', $CensoBufalino->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id_predio" value="{{ $CensoBufalino->id_predio }}">

                        <!-- Campos para Hembras -->
                        <!-- Campos para Hembras -->
                        <div class="mb-3">
                            <label for="men_3_meses_h" class="form-label">Menos de 3 meses (Hembras):</label>
                            <input type="number" class="form-control" name="men_3_meses_h" id="men_3_meses_h2"
                                value="{{ old('men_3_meses_h', $CensoBufalino->men_3_meses_h) }}"
                                oninput="calculateTotalHembras2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="3_a_9_meses_h" class="form-label">De 3 a 9 meses (Hembras):</label>
                            <input type="number" class="form-control" name="tres_a_9_meses_h" id="3_a_9_meses_h2"
                                value="{{ old('tres_a_9_meses_h', $CensoBufalino->tres_a_9_meses_h) }}"
                                oninput="calculateTotalHembras2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="9_a_12_meses_h" class="form-label">De 9 a 12 meses (Hembras):</label>
                            <input type="number" class="form-control" name="nueve_a_12_meses_h" id="9_a_12_meses_h2"
                                value="{{ old('nueve_a_12_meses_h', $CensoBufalino->nueve_a_12_meses_h) }}"
                                oninput="calculateTotalHembras2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="1_a_2_años_h" class="form-label">De 1 a 2 años (Hembras):</label>
                            <input type="number" class="form-control" name="uno_a_2_años_h" id="1_a_2_años_h2"
                                value="{{ old('uno_a_2_años_h', $CensoBufalino->uno_a_2_años_h) }}"
                                oninput="calculateTotalHembras2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="2_a_3_años_h" class="form-label">De 2 a 3 años (Hembras):</label>
                            <input type="number" class="form-control" name="dos_a_3_años_h" id="2_a_3_años_h2"
                                value="{{ old('dos_a_3_años_h', $CensoBufalino->dos_a_3_años_h) }}"
                                oninput="calculateTotalHembras2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="3_a_5_años_h" class="form-label">De 3 a 5 años (Hembras):</label>
                            <input type="number" class="form-control" name="tres_a_5_años_h" id="3_a_5_años_h2"
                                value="{{ old('tres_a_5_años_h', $CensoBufalino->tres_a_5_años_h) }}"
                                oninput="calculateTotalHembras2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="may_5_años_h" class="form-label">Mayores de 5 años (Hembras):</label>
                            <input type="number" class="form-control" name="may_5_años_h" id="may_5_años_h2"
                                value="{{ old('may_5_años_h', $CensoBufalino->may_5_años_h) }}"
                                oninput="calculateTotalHembras2()" required>
                        </div>

                        <!-- Total de Hembras -->
                        <div class="mb-3">
                            <label for="total_hembras" class="form-label">Total Hembras:</label>
                            <input type="number" class="form-control" name="total_hembras" id="total_hembras2"
                                value="{{ old('total_hembras', $CensoBufalino->total_hembras) }}" readonly>
                        </div>

                        <!-- Campos para Machos -->
                        <div class="mb-3">
                            <label for="men_3_meses_m" class="form-label">Menos de 3 meses (Machos):</label>
                            <input type="number" class="form-control" name="men_3_meses_m" id="men_3_meses_m2"
                                value="{{ old('men_3_meses_m', $CensoBufalino->men_3_meses_m) }}"
                                oninput="calculateTotalMachos2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="3_a_9_meses_m" class="form-label">De 3 a 9 meses (Machos):</label>
                            <input type="number" class="form-control" name="tres_a_9_meses_m" id="3_a_9_meses_m2"
                                value="{{ old('tres_a_9_meses_m', $CensoBufalino->tres_a_9_meses_m) }}"
                                oninput="calculateTotalMachos2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="9_a_12_meses_m" class="form-label">De 9 a 12 meses (Machos):</label>
                            <input type="number" class="form-control" name="nueve_a_12_meses_m" id="9_a_12_meses_m2"
                                value="{{ old('nueve_a_12_meses_m', $CensoBufalino->nueve_a_12_meses_m) }}"
                                oninput="calculateTotalMachos2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="1_a_2_años_m" class="form-label">De 1 a 2 años (Machos):</label>
                            <input type="number" class="form-control" name="uno_a_2_años_m" id="1_a_2_años_m2"
                                value="{{ old('uno_a_2_años_m', $CensoBufalino->uno_a_2_años_m) }}"
                                oninput="calculateTotalMachos2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="2_a_3_años_m" class="form-label">De 2 a 3 años (Machos):</label>
                            <input type="number" class="form-control" name="dos_a_3_años_m" id="2_a_3_años_m2"
                                value="{{ old('dos_a_3_años_m', $CensoBufalino->dos_a_3_años_m) }}"
                                oninput="calculateTotalMachos2()" required>
                        </div>

                        <div class="mb-3">
                            <label for="may_3_años_m" class="form-label">Mayores de 3 años (Machos):</label>
                            <input type="number" class="form-control" name="may_3_años" id="may_3_años_m2"
                                value="{{ old('may_3_años_m', $CensoBufalino->may_3_años) }}"
                                oninput="calculateTotalMachos2()" required>
                        </div>

                        <!-- Total de Machos -->
                        <div class="mb-3">
                            <label for="total_machos" class="form-label">Total Machos:</label>
                            <input type="number" class="form-control" name="total_machos" id="total_machos2"
                                value="{{ old('total_machos', $CensoBufalino->total_machos) }}" readonly>
                        </div>

                        <!-- Total de Bufalinos -->
                        <div class="mb-3">
                            <label for="total_bufalinos2" class="form-label">Total Bufalinos:</label>
                            <input type="number" class="form-control" name="total_bufalinos" id="total_bufalinos2"
                                value="{{ old('total_bufalinos2', $CensoBufalino->total_bufalinos) }}" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar</button>

                    </form>
                @else
                    <form action="{{ route('censo_bufalinos.store') }}" method="POST">
                        @csrf

                        <!-- Input oculto para id_predio -->
                        <input type="hidden" name="id_predio" value="{{ $predioId }}">

                        <!-- Campos para Hembras -->
                        <div class="mb-3">
                            <label for="men_3_meses_h" class="form-label">Menos de 3 meses (Hembras):</label>
                            <input type="number" class="form-control" name="men_3_meses_h" id="men_3_meses_h"
                                value="{{ old('men_3_meses_h') }}" oninput="calculateTotalHembras()" required>
                        </div>

                        <div class="mb-3">
                            <label for="3_a_9_meses_h" class="form-label">De 3 a 9 meses (Hembras):</label>
                            <input type="number" class="form-control" name="tres_a_9_meses_h" id="3_a_9_meses_h"
                                value="{{ old('3_a_9_meses_h') }}" oninput="calculateTotalHembras()" required>
                        </div>

                        <div class="mb-3">
                            <label for="9_a_12_meses_h" class="form-label">De 9 a 12 meses (Hembras):</label>
                            <input type="number" class="form-control" name="nueve_a_12_meses_h" id="9_a_12_meses_h"
                                value="{{ old('9_a_12_meses_h') }}" oninput="calculateTotalHembras()" required>
                        </div>

                        <div class="mb-3">
                            <label for="1_a_2_años_h" class="form-label">De 1 a 2 años (Hembras):</label>
                            <input type="number" class="form-control" name="uno_a_2_años_h" id="1_a_2_años_h"
                                value="{{ old('1_a_2_años_h') }}" oninput="calculateTotalHembras()" required>
                        </div>

                        <div class="mb-3">
                            <label for="2_a_3_años_h" class="form-label">De 2 a 3 años (Hembras):</label>
                            <input type="number" class="form-control" name="dos_a_3_años_h" id="2_a_3_años_h"
                                value="{{ old('2_a_3_años_h') }}" oninput="calculateTotalHembras()" required>
                        </div>

                        <div class="mb-3">
                            <label for="3_a_5_años_h" class="form-label">De 3 a 5 años (Hembras):</label>
                            <input type="number" class="form-control" name="tres_a_5_años_h" id="3_a_5_años_h"
                                value="{{ old('3_a_5_años_h') }}" oninput="calculateTotalHembras()" required>
                        </div>

                        <div class="mb-3">
                            <label for="may_5_años_h" class="form-label">Mayores de 5 años (Hembras):</label>
                            <input type="number" class="form-control" name="may_5_años_h" id="may_5_años_h"
                                value="{{ old('may_5_años_h') }}" oninput="calculateTotalHembras()" required>
                        </div>

                        <!-- Total de Hembras -->
                        <div class="mb-3">
                            <label for="total_hembras" class="form-label">Total Hembras:</label>
                            <input type="number" class="form-control" name="total_hembras" id="total_hembras"
                                value="{{ old('total_hembras') }}" readonly>
                        </div>

                        <!-- Campos para Machos -->
                        <div class="mb-3">
                            <label for="men_3_meses_m" class="form-label">Menos de 3 meses (Machos):</label>
                            <input type="number" class="form-control" name="men_3_meses_m" id="men_3_meses_m"
                                value="{{ old('men_3_meses_m') }}" oninput="calculateTotalMachos()" required>
                        </div>

                        <div class="mb-3">
                            <label for="3_a_9_meses_m" class="form-label">De 3 a 9 meses (Machos):</label>
                            <input type="number" class="form-control" name="tres_a_9_meses_m" id="3_a_9_meses_m"
                                value="{{ old('3_a_9_meses_m') }}" oninput="calculateTotalMachos()" required>
                        </div>

                        <div class="mb-3">
                            <label for="9_a_12_meses_m" class="form-label">De 9 a 12 meses (Machos):</label>
                            <input type="number" class="form-control" name="nueve_a_12_meses_m" id="9_a_12_meses_m"
                                value="{{ old('9_a_12_meses_m') }}" oninput="calculateTotalMachos()" required>
                        </div>

                        <div class="mb-3">
                            <label for="1_a_2_años_m" class="form-label">De 1 a 2 años (Machos):</label>
                            <input type="number" class="form-control" name="uno_a_2_años_m" id="1_a_2_años_m"
                                value="{{ old('1_a_2_años_m') }}" oninput="calculateTotalMachos()" required>
                        </div>

                        <div class="mb-3">
                            <label for="2_a_3_años_m" class="form-label">De 2 a 3 años (Machos):</label>
                            <input type="number" class="form-control" name="dos_a_3_años_m" id="2_a_3_años_m"
                                value="{{ old('2_a_3_años_m') }}" oninput="calculateTotalMachos()" required>
                        </div>

                        <div class="mb-3">
                            <label for="may_3_años" class="form-label">Mayores de 3 años (Machos):</label>
                            <input type="number" class="form-control" name="may_3_años" id="may_3_años"
                                value="{{ old('may_3_años') }}" oninput="calculateTotalMachos()" required>
                        </div>

                        <!-- Total de Machos -->
                        <div class="mb-3">
                            <label for="total_machos" class="form-label">Total Machos:</label>
                            <input type="number" class="form-control" name="total_machos" id="total_machos"
                                value="{{ old('total_machos') }}" readonly>
                        </div>

                        <!-- Total de Bovinos -->
                        <div class="mb-3">
                            <label for="total_bovinos" class="form-label">Total Bafalinos:</label>
                            <input type="number" class="form-control" name="total_bufalinos" id="total_bovinos"
                                value="{{ old('total_bovinos') }}" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        function calculateTotalHembras() {
            let men_3_meses_h = parseInt(document.getElementById('men_3_meses_h').value) || 0;
            let _3_a_9_meses_h = parseInt(document.getElementById('3_a_9_meses_h').value) || 0;
            let _9_a_12_meses_h = parseInt(document.getElementById('9_a_12_meses_h').value) || 0;
            let _1_a_2_años_h = parseInt(document.getElementById('1_a_2_años_h').value) || 0;
            let _2_a_3_años_h = parseInt(document.getElementById('2_a_3_años_h').value) || 0;
            let _3_a_5_años_h = parseInt(document.getElementById('3_a_5_años_h').value) || 0;
            let may_5_años_h = parseInt(document.getElementById('may_5_años_h').value) || 0;

            let total_hembras = men_3_meses_h + _3_a_9_meses_h + _9_a_12_meses_h + _1_a_2_años_h + _2_a_3_años_h +
                _3_a_5_años_h + may_5_años_h;

            document.getElementById('total_hembras').value = total_hembras;

            // Llamar a la función para calcular el total de bovinos
            calculateTotalBovinos();
        }

        function calculateTotalMachos() {
            let men_3_meses_m = parseInt(document.getElementById('men_3_meses_m').value) || 0;
            let _3_a_9_meses_m = parseInt(document.getElementById('3_a_9_meses_m').value) || 0;
            let _9_a_12_meses_m = parseInt(document.getElementById('9_a_12_meses_m').value) || 0;
            let _1_a_2_años_m = parseInt(document.getElementById('1_a_2_años_m').value) || 0;
            let _2_a_3_años_m = parseInt(document.getElementById('2_a_3_años_m').value) || 0;
            let may_3_años = parseInt(document.getElementById('may_3_años').value) || 0;

            let total_machos = men_3_meses_m + _3_a_9_meses_m + _9_a_12_meses_m + _1_a_2_años_m + _2_a_3_años_m +
                may_3_años;

            document.getElementById('total_machos').value = total_machos;

            // Llamar a la función para calcular el total de bovinos
            calculateTotalBovinos();
        }

        function calculateTotalBovinos() {
            let total_hembras = parseInt(document.getElementById('total_hembras').value) || 0;
            let total_machos = parseInt(document.getElementById('total_machos').value) || 0;

            let total_bovinos = total_hembras + total_machos;

            document.getElementById('total_bovinos').value = total_bovinos;
        }
    </script>

<script>
    function calculateTotalHembras2() {
        let men_3_meses_h = parseInt(document.getElementById('men_3_meses_h2').value) || 0;
        let _3_a_9_meses_h = parseInt(document.getElementById('3_a_9_meses_h2').value) || 0;
        let _9_a_12_meses_h = parseInt(document.getElementById('9_a_12_meses_h2').value) || 0;
        let _1_a_2_años_h = parseInt(document.getElementById('1_a_2_años_h2').value) || 0;
        let _2_a_3_años_h = parseInt(document.getElementById('2_a_3_años_h2').value) || 0;
        let _3_a_5_años_h = parseInt(document.getElementById('3_a_5_años_h2').value) || 0;
        let may_5_años_h = parseInt(document.getElementById('may_5_años_h2').value) || 0;

        let total_hembras = men_3_meses_h + _3_a_9_meses_h + _9_a_12_meses_h + _1_a_2_años_h + _2_a_3_años_h +
            _3_a_5_años_h + may_5_años_h;

        document.getElementById('total_hembras2').value = total_hembras;

        // Llamar a la función para calcular el total de bovinos
        calculateTotalBovinos2();
    }

    function calculateTotalMachos2() {
        let men_3_meses_m = parseInt(document.getElementById('men_3_meses_m2').value) || 0;
        let _3_a_9_meses_m = parseInt(document.getElementById('3_a_9_meses_m2').value) || 0;
        let _9_a_12_meses_m = parseInt(document.getElementById('9_a_12_meses_m2').value) || 0;
        let _1_a_2_años_m = parseInt(document.getElementById('1_a_2_años_m2').value) || 0;
        let _2_a_3_años_m = parseInt(document.getElementById('2_a_3_años_m2').value) || 0;
        let may_3_años = parseInt(document.getElementById('may_3_años2').value) || 0;

        let total_machos = men_3_meses_m + _3_a_9_meses_m + _9_a_12_meses_m + _1_a_2_años_m + _2_a_3_años_m +
            may_3_años;

        document.getElementById('total_machos2').value = total_machos;

        // Llamar a la función para calcular el total de bovinos
        calculateTotalBovinos2();
    }

    function calculateTotalBovinos2() {
        let total_hembras = parseInt(document.getElementById('total_hembras2').value) || 0;
        let total_machos = parseInt(document.getElementById('total_machos2').value) || 0;

        let total_bovinos = total_hembras + total_machos;

        document.getElementById('total_bufalinos2').value = total_bovinos;
    }
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
