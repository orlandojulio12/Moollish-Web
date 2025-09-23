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
                    <li class="breadcrumb-item active" aria-current="page">ÉQUIDOS</li>
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
                <form action="{{ route('censo_equidos.update', $CensoEquido->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Input oculto para id_predio -->
                    <input type="hidden" name="id_predio" value="{{ $CensoEquido->id_predio }}">

                    <!-- Campos para Caballar -->
                    <div class="mb-3">
                        <label for="men_6_mese_caballar" class="form-label">Menos de 6 meses (Caballar):</label>
                        <input type="number" class="form-control" name="men_6_mese_caballar" id="men_6_mese_caballar2"
                            value="{{ old('men_6_mese_caballar', $CensoEquido->men_6_mese_caballar) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="6_12_meses_caballar" class="form-label">6 a 12 meses (Caballar):</label>
                        <input type="number" class="form-control" name="seis_12_meses_caballar" id="6_12_meses_caballar2"
                            value="{{ old('6_12_meses_caballar', $CensoEquido->seis_12_meses_caballar) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_1_año_caballar" class="form-label">Mayores de 1 año (Caballar):</label>
                        <input type="number" class="form-control" name="may_1_año_caballar" id="may_1_año_caballar2"
                            value="{{ old('may_1_año_caballar', $CensoEquido->may_1_año_caballar) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_caballar" class="form-label">Total Caballar:</label>
                        <input type="number" class="form-control" name="total_caballar" id="total_caballar2"
                            value="{{ old('total_caballar', $CensoEquido->total_caballar) }}" readonly>
                    </div>

                    <!-- Campos para Mular -->
                    <div class="mb-3">
                        <label for="men_6_mese_mular" class="form-label">Menos de 6 meses (Mular):</label>
                        <input type="number" class="form-control" name="men_6_mese_mular" id="men_6_mese_mular2"
                            value="{{ old('men_6_mese_mular', $CensoEquido->men_6_mese_mular) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="6_12_meses_mular" class="form-label">6 a 12 meses (Mular):</label>
                        <input type="number" class="form-control" name="seis_12_meses_mular" id="6_12_meses_mular2"
                            value="{{ old('6_12_meses_mular', $CensoEquido->seis_12_meses_mular) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_1_año_mular" class="form-label">Mayores de 1 año (Mular):</label>
                        <input type="number" class="form-control" name="may_1_año_mular" id="may_1_año_mular2"
                            value="{{ old('may_1_año_mular', $CensoEquido->may_1_año_mular) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_mular" class="form-label">Total Mular:</label>
                        <input type="number" class="form-control" name="total_mular" id="total_mular2"
                            value="{{ old('total_mular', $CensoEquido->total_mular) }}" readonly>
                    </div>

                    <!-- Campos para Asnal -->
                    <div class="mb-3">
                        <label for="men_6_mese_asnal" class="form-label">Menos de 6 meses (Asnal):</label>
                        <input type="number" class="form-control" name="men_6_mese_asnal" id="men_6_mese_asnal2"
                            value="{{ old('men_6_mese_asnal', $CensoEquido->men_6_mese_asnal) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="6_12_meses_asnal" class="form-label">6 a 12 meses (Asnal):</label>
                        <input type="number" class="form-control" name="seis_12_meses_asnal" id="6_12_meses_asnal2"
                            value="{{ old('6_12_meses_asnal', $CensoEquido->seis_12_meses_asnal) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_1_año_asnal" class="form-label">Mayores de 1 año (Asnal):</label>
                        <input type="number" class="form-control" name="may_1_año_asnal" id="may_1_año_asnal2"
                            value="{{ old('may_1_año_asnal', $CensoEquido->may_1_año_asnal) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_asnal" class="form-label">Total Asnal:</label>
                        <input type="number" class="form-control" name="total_asnal" id="total_asnal2"
                            value="{{ old('total_asnal', $CensoEquido->total_asnal) }}" readonly>
                    </div>

                    <!-- Total Equidos -->
                    <div class="mb-3">
                        <label for="total_equidos" class="form-label">Total Équidos:</label>
                        <input type="number" class="form-control" name="total_equidos" id="total_equidos2"
                            value="{{ old('total_equidos', $CensoEquido->total_equidos) }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>

                @else
                <form action="{{ route('censo_equidos.store') }}" method="POST">
                    @csrf

                    <!-- Input oculto para id_predio -->
                    <input type="hidden" name="id_predio" value="{{ $predioId }}">

                    <!-- Campos para Caballar -->
                    <div class="mb-3">
                        <label for="men_6_mese_caballar" class="form-label">Menos de 6 meses (Caballar):</label>
                        <input type="number" class="form-control" name="men_6_mese_caballar" id="men_6_mese_caballar"
                            value="{{ old('men_6_mese_caballar') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="6_12_meses_caballar" class="form-label">6 a 12 meses (Caballar):</label>
                        <input type="number" class="form-control" name="seis_12_meses_caballar" id="6_12_meses_caballar"
                            value="{{ old('6_12_meses_caballar') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_1_año_caballar" class="form-label">Mayores de 1 año (Caballar):</label>
                        <input type="number" class="form-control" name="may_1_año_caballar" id="may_1_año_caballar"
                            value="{{ old('may_1_año_caballar') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_caballar" class="form-label">Total Caballar:</label>
                        <input type="number" class="form-control" name="total_caballar" id="total_caballar"
                            value="{{ old('total_caballar') }}" readonly>
                    </div>

                    <!-- Campos para Mular -->
                    <div class="mb-3">
                        <label for="men_6_mese_mular" class="form-label">Menos de 6 meses (Mular):</label>
                        <input type="number" class="form-control" name="men_6_mese_mular" id="men_6_mese_mular"
                            value="{{ old('men_6_mese_mular') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="6_12_meses_mular" class="form-label">6 a 12 meses (Mular):</label>
                        <input type="number" class="form-control" name="seis_12_meses_mular" id="6_12_meses_mular"
                            value="{{ old('6_12_meses_mular') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_1_año_mular" class="form-label">Mayores de 1 año (Mular):</label>
                        <input type="number" class="form-control" name="may_1_año_mular" id="may_1_año_mular"
                            value="{{ old('may_1_año_mular') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_mular" class="form-label">Total Mular:</label>
                        <input type="number" class="form-control" name="total_mular" id="total_mular"
                            value="{{ old('total_mular') }}" readonly>
                    </div>

                    <!-- Campos para Asnal -->
                    <div class="mb-3">
                        <label for="men_6_mese_asnal" class="form-label">Menos de 6 meses (Asnal):</label>
                        <input type="number" class="form-control" name="men_6_mese_asnal" id="men_6_mese_asnal"
                            value="{{ old('men_6_mese_asnal') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="6_12_meses_asnal" class="form-label">6 a 12 meses (Asnal):</label>
                        <input type="number" class="form-control" name="seis_12_meses_asnal" id="6_12_meses_asnal"
                            value="{{ old('6_12_meses_asnal') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_1_año_asnal" class="form-label">Mayores de 1 año (Asnal):</label>
                        <input type="number" class="form-control" name="may_1_año_asnal" id="may_1_año_asnal"
                            value="{{ old('may_1_año_asnal') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_asnal" class="form-label">Total Asnal:</label>
                        <input type="number" class="form-control" name="total_asnal" id="total_asnal"
                            value="{{ old('total_asnal') }}" readonly>
                    </div>

                    <!-- Total Equidos -->
                    <div class="mb-3">
                        <label for="total_equidos" class="form-label">Total Équidos:</label>
                        <input type="number" class="form-control" name="total_equidos" id="total_equidos"
                            value="{{ old('total_equidos') }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        function calculateTotals() {
            // Calcular total caballar
            let men_6_mese_caballar = parseInt(document.getElementById('men_6_mese_caballar').value) || 0;
            let six_12_meses_caballar = parseInt(document.getElementById('6_12_meses_caballar').value) || 0;
            let may_1_año_caballar = parseInt(document.getElementById('may_1_año_caballar').value) || 0;

            let total_caballar = men_6_mese_caballar + six_12_meses_caballar + may_1_año_caballar;
            document.getElementById('total_caballar').value = total_caballar;

            // Calcular total mular
            let men_6_mese_mular = parseInt(document.getElementById('men_6_mese_mular').value) || 0;
            let six_12_meses_mular = parseInt(document.getElementById('6_12_meses_mular').value) || 0;
            let may_1_año_mular = parseInt(document.getElementById('may_1_año_mular').value) || 0;

            let total_mular = men_6_mese_mular + six_12_meses_mular + may_1_año_mular;
            document.getElementById('total_mular').value = total_mular;

            // Calcular total asnal
            let men_6_mese_asnal = parseInt(document.getElementById('men_6_mese_asnal').value) || 0;
            let six_12_meses_asnal = parseInt(document.getElementById('6_12_meses_asnal').value) || 0;
            let may_1_año_asnal = parseInt(document.getElementById('may_1_año_asnal').value) || 0;

            let total_asnal = men_6_mese_asnal + six_12_meses_asnal + may_1_año_asnal;
            document.getElementById('total_asnal').value = total_asnal;

            // Calcular total équidos
            let total_equidos = total_caballar + total_mular + total_asnal;
            document.getElementById('total_equidos').value = total_equidos;
        }

        // Ejecutar la función cada vez que se modifique un input
        document.getElementById('men_6_mese_caballar').oninput = calculateTotals;
        document.getElementById('6_12_meses_caballar').oninput = calculateTotals;
        document.getElementById('may_1_año_caballar').oninput = calculateTotals;
        document.getElementById('men_6_mese_mular').oninput = calculateTotals;
        document.getElementById('6_12_meses_mular').oninput = calculateTotals;
        document.getElementById('may_1_año_mular').oninput = calculateTotals;
        document.getElementById('men_6_mese_asnal').oninput = calculateTotals;
        document.getElementById('6_12_meses_asnal').oninput = calculateTotals;
        document.getElementById('may_1_año_asnal').oninput = calculateTotals;
    </script>

<script>
    function calculateTotals2() {
        // Calcular total caballar
        let men_6_mese_caballar = parseInt(document.getElementById('men_6_mese_caballar2').value) || 0;
        let six_12_meses_caballar = parseInt(document.getElementById('6_12_meses_caballar2').value) || 0;
        let may_1_año_caballar = parseInt(document.getElementById('may_1_año_caballar2').value) || 0;

        let total_caballar = men_6_mese_caballar + six_12_meses_caballar + may_1_año_caballar;
        document.getElementById('total_caballar2').value = total_caballar;

        // Calcular total mular
        let men_6_mese_mular = parseInt(document.getElementById('men_6_mese_mular2').value) || 0;
        let six_12_meses_mular = parseInt(document.getElementById('6_12_meses_mular2').value) || 0;
        let may_1_año_mular = parseInt(document.getElementById('may_1_año_mular2').value) || 0;

        let total_mular = men_6_mese_mular + six_12_meses_mular + may_1_año_mular;
        document.getElementById('total_mular2').value = total_mular;

        // Calcular total asnal
        let men_6_mese_asnal = parseInt(document.getElementById('men_6_mese_asnal2').value) || 0;
        let six_12_meses_asnal = parseInt(document.getElementById('6_12_meses_asnal2').value) || 0;
        let may_1_año_asnal = parseInt(document.getElementById('may_1_año_asnal2').value) || 0;

        let total_asnal = men_6_mese_asnal + six_12_meses_asnal + may_1_año_asnal;
        document.getElementById('total_asnal2').value = total_asnal;

        // Calcular total équidos
        let total_equidos = total_caballar + total_mular + total_asnal;
        document.getElementById('total_equidos2').value = total_equidos;
    }

    // Ejecutar la función cada vez que se modifique un input
    document.getElementById('men_6_mese_caballar2').oninput = calculateTotals2;
    document.getElementById('6_12_meses_caballar2').oninput = calculateTotals2;
    document.getElementById('may_1_año_caballar2').oninput = calculateTotals2;
    document.getElementById('men_6_mese_mular2').oninput = calculateTotals2;
    document.getElementById('6_12_meses_mular2').oninput = calculateTotals2;
    document.getElementById('may_1_año_mular2').oninput = calculateTotals2;
    document.getElementById('men_6_mese_asnal2').oninput = calculateTotals2;
    document.getElementById('6_12_meses_asnal2').oninput = calculateTotals2;
    document.getElementById('may_1_año_asnal2').oninput = calculateTotals2;
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
