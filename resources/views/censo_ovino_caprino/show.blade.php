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
                    <li class="breadcrumb-item active" aria-current="page">OVINOS Y CAPRINOS</li>
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
                <form action="{{ route('censo_ovino_caprino.update', $CensoOvinoCaprino->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Input oculto para id_predio -->
                    <input type="hidden" name="id_predio" value="{{ $CensoOvinoCaprino->id_predio }}">

                    <!-- Campos para Ovinos -->
                    <div class="mb-3">
                        <label for="men_6_meses_h_ovi" class="form-label">Menos de 6 meses Hembras (Ovinos):</label>
                        <input type="number" class="form-control" name="men_6_meses_h_ovi" id="men_6_meses_h_ovi2"
                            value="{{ old('men_6_meses_h_ovi', $CensoOvinoCaprino->men_6_meses_h_ovi) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_6_meses_h_ovi" class="form-label">Más de 6 meses Hembras (Ovinos):</label>
                        <input type="number" class="form-control" name="may_6_meses_h_ovi" id="may_6_meses_h_ovi2"
                            value="{{ old('may_6_meses_h_ovi', $CensoOvinoCaprino->may_6_meses_h_ovi) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_hembras_ovinas" class="form-label">Total Hembras (Ovinos):</label>
                        <input type="number" class="form-control" name="total_hembras_ovinas" id="total_hembras_ovinas2"
                            value="{{ old('total_hembras_ovinas', $CensoOvinoCaprino->total_hembras_ovinas) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="men_6_meses_m_ovi" class="form-label">Menos de 6 meses Machos (Ovinos):</label>
                        <input type="number" class="form-control" name="men_6_meses_m_ovi" id="men_6_meses_m_ovi2"
                            value="{{ old('men_6_meses_m_ovi', $CensoOvinoCaprino->men_6_meses_m_ovi) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_6_meses_m_ovi" class="form-label">Más de 6 meses Machos (Ovinos):</label>
                        <input type="number" class="form-control" name="may_6_meses_m_ovi" id="may_6_meses_m_ovi2"
                            value="{{ old('may_6_meses_m_ovi', $CensoOvinoCaprino->may_6_meses_m_ovi) }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_machos_ovi" class="form-label">Total Machos (Ovinos):</label>
                        <input type="number" class="form-control" name="total_machos_ovi" id="total_machos_ovi2"
                            value="{{ old('total_machos_ovi', $CensoOvinoCaprino->total_machos_ovi) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="total_ovinos" class="form-label">Total Ovinos:</label>
                        <input type="number" class="form-control" name="total_ovinos" id="total_ovinos2"
                            value="{{ old('total_ovinos', $CensoOvinoCaprino->total_ovinos) }}" readonly>
                    </div>

                    <!-- Campos para Caprinos -->
                    <div class="mb-3">
                        <label for="men_6_meses_h_capri" class="form-label">Menos de 6 meses Hembras (Caprinos):</label>
                        <input type="number" class="form-control" name="men_6_meses_h_capri" id="men_6_meses_h_capri2"
                            value="{{ old('men_6_meses_h_capri', $CensoOvinoCaprino->men_6_meses_h_capri) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_6_meses_h_capri" class="form-label">Más de 6 meses Hembras (Caprinos):</label>
                        <input type="number" class="form-control" name="may_6_meses_h_capri" id="may_6_meses_h_capri2"
                            value="{{ old('may_6_meses_h_capri', $CensoOvinoCaprino->may_6_meses_h_capri) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_hembras_capri" class="form-label">Total Hembras (Caprinos):</label>
                        <input type="number" class="form-control" name="total_hembras_capri" id="total_hembras_capri2"
                            value="{{ old('total_hembras_capri', $CensoOvinoCaprino->total_hembras_capri) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="men_6_meses_m_capri" class="form-label">Menos de 6 meses Machos (Caprinos):</label>
                        <input type="number" class="form-control" name="men_6_meses_m_capri" id="men_6_meses_m_capri2"
                            value="{{ old('men_6_meses_m_capri', $CensoOvinoCaprino->men_6_meses_m_capri) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_6_meses_m_capri" class="form-label">Más de 6 meses Machos (Caprinos):</label>
                        <input type="number" class="form-control" name="may_6_meses_m_capri" id="may_6_meses_m_capri2"
                            value="{{ old('may_6_meses_m_capri', $CensoOvinoCaprino->may_6_meses_m_capri) }}" oninput="calculateTotals2()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_machos_capri" class="form-label">Total Machos (Caprinos):</label>
                        <input type="number" class="form-control" name="total_machos_capri" id="total_machos_capri2"
                            value="{{ old('total_machos_capri', $CensoOvinoCaprino->total_machos_capri) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="total_caprinos" class="form-label">Total Caprinos:</label>
                        <input type="number" class="form-control" name="total_caprinos" id="total_caprinos2"
                            value="{{ old('total_caprinos', $CensoOvinoCaprino->total_caprinos) }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>


                @else
                <form action="{{ route('censo_ovino_caprino.store') }}" method="POST">
                    @csrf

                    <!-- Input oculto para id_predio -->
                    <input type="hidden" name="id_predio" value="{{ $predioId }}">

                    <!-- Campos para Ovinos -->
                    <div class="mb-3">
                        <label for="men_6_meses_h_ovi" class="form-label">Menos de 6 meses Hembras (Ovinos):</label>
                        <input type="number" class="form-control" name="men_6_meses_h_ovi" id="men_6_meses_h_ovi"
                            value="{{ old('men_6_meses_h_ovi') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_6_meses_h_ovi" class="form-label">Más de 6 meses Hembras (Ovinos):</label>
                        <input type="number" class="form-control" name="may_6_meses_h_ovi" id="may_6_meses_h_ovi"
                            value="{{ old('may_6_meses_h_ovi') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_hembras_ovinas" class="form-label">Total Hembras (Ovinos):</label>
                        <input type="number" class="form-control" name="total_hembras_ovinas" id="total_hembras_ovinas"
                            value="{{ old('total_hembras_ovinas') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="men_6_meses_m_ovi" class="form-label">Menos de 6 meses Machos (Ovinos):</label>
                        <input type="number" class="form-control" name="men_6_meses_m_ovi" id="men_6_meses_m_ovi"
                            value="{{ old('men_6_meses_m_ovi') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_6_meses_m_ovi" class="form-label">Más de 6 meses Machos (Ovinos):</label>
                        <input type="number" class="form-control" name="may_6_meses_m_ovi" id="may_6_meses_m_ovi"
                            value="{{ old('may_6_meses_m_ovi') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_machos_ovi" class="form-label">Total Machos (Ovinos):</label>
                        <input type="number" class="form-control" name="total_machos_ovi" id="total_machos_ovi"
                            value="{{ old('total_machos_ovi') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="total_ovinos" class="form-label">Total Ovinos:</label>
                        <input type="number" class="form-control" name="total_ovinos" id="total_ovinos"
                            value="{{ old('total_ovinos') }}" readonly>
                    </div>

                    <!-- Campos para Caprinos -->
                    <div class="mb-3">
                        <label for="men_6_meses_h_capri" class="form-label">Menos de 6 meses Hembras (Caprinos):</label>
                        <input type="number" class="form-control" name="men_6_meses_h_capri" id="men_6_meses_h_capri"
                            value="{{ old('men_6_meses_h_capri') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_6_meses_h_capri" class="form-label">Más de 6 meses Hembras (Caprinos):</label>
                        <input type="number" class="form-control" name="may_6_meses_h_capri" id="may_6_meses_h_capri"
                            value="{{ old('may_6_meses_h_capri') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_hembras_capri" class="form-label">Total Hembras (Caprinos):</label>
                        <input type="number" class="form-control" name="total_hembras_capri" id="total_hembras_capri"
                            value="{{ old('total_hembras_capri') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="men_6_meses_m_capri" class="form-label">Menos de 6 meses Machos (Caprinos):</label>
                        <input type="number" class="form-control" name="men_6_meses_m_capri" id="men_6_meses_m_capri"
                            value="{{ old('men_6_meses_m_capri') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="may_6_meses_m_capri" class="form-label">Más de 6 meses Machos (Caprinos):</label>
                        <input type="number" class="form-control" name="may_6_meses_m_capri" id="may_6_meses_m_capri"
                            value="{{ old('may_6_meses_m_capri') }}" oninput="calculateTotals()" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_machos_capri" class="form-label">Total Machos (Caprinos):</label>
                        <input type="number" class="form-control" name="total_machos_capri" id="total_machos_capri"
                            value="{{ old('total_machos_capri') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="total_caprinos" class="form-label">Total Caprinos:</label>
                        <input type="number" class="form-control" name="total_caprinos" id="total_caprinos"
                            value="{{ old('total_caprinos') }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>

                @endif
            </div>
        </div>
    </div>

    <script>
        function calculateTotals() {
            // Calcular total hembras ovinas
            let men_6_meses_h_ovi = parseInt(document.getElementById('men_6_meses_h_ovi').value) || 0;
            let may_6_meses_h_ovi = parseInt(document.getElementById('may_6_meses_h_ovi').value) || 0;
            let total_hembras_ovinas = men_6_meses_h_ovi + may_6_meses_h_ovi;
            document.getElementById('total_hembras_ovinas').value = total_hembras_ovinas;

            // Calcular total machos ovinos
            let men_6_meses_m_ovi = parseInt(document.getElementById('men_6_meses_m_ovi').value) || 0;
            let may_6_meses_m_ovi = parseInt(document.getElementById('may_6_meses_m_ovi').value) || 0;
            let total_machos_ovi = men_6_meses_m_ovi + may_6_meses_m_ovi;
            document.getElementById('total_machos_ovi').value = total_machos_ovi;

            // Calcular total ovinos
            let total_ovinos = total_hembras_ovinas + total_machos_ovi;
            document.getElementById('total_ovinos').value = total_ovinos;

            // Calcular total hembras caprinas
            let men_6_meses_h_capri = parseInt(document.getElementById('men_6_meses_h_capri').value) || 0;
            let may_6_meses_h_capri = parseInt(document.getElementById('may_6_meses_h_capri').value) || 0;
            let total_hembras_capri = men_6_meses_h_capri + may_6_meses_h_capri;
            document.getElementById('total_hembras_capri').value = total_hembras_capri;

            // Calcular total machos caprinos
            let men_6_meses_m_capri = parseInt(document.getElementById('men_6_meses_m_capri').value) || 0;
            let may_6_meses_m_capri = parseInt(document.getElementById('may_6_meses_m_capri').value) || 0;
            let total_machos_capri = men_6_meses_m_capri + may_6_meses_m_capri;
            document.getElementById('total_machos_capri').value = total_machos_capri;

            // Calcular total caprinos
            let total_caprinos = total_hembras_capri + total_machos_capri;
            document.getElementById('total_caprinos').value = total_caprinos;
        }

        // Ejecutar la función cada vez que se modifique un input
        document.getElementById('men_6_meses_h_ovi').oninput = calculateTotals;
        document.getElementById('may_6_meses_h_ovi').oninput = calculateTotals;
        document.getElementById('men_6_meses_m_ovi').oninput = calculateTotals;
        document.getElementById('may_6_meses_m_ovi').oninput = calculateTotals;
        document.getElementById('men_6_meses_h_capri').oninput = calculateTotals;
        document.getElementById('may_6_meses_h_capri').oninput = calculateTotals;
        document.getElementById('men_6_meses_m_capri').oninput = calculateTotals;
        document.getElementById('may_6_meses_m_capri').oninput = calculateTotals;
    </script>


    <script>
        function calculateTotals2() {
            // Calcular total hembras ovinas
            let men_6_meses_h_ovi = parseInt(document.getElementById('men_6_meses_h_ovi2').value) || 0;
            let may_6_meses_h_ovi = parseInt(document.getElementById('may_6_meses_h_ovi2').value) || 0;
            let total_hembras_ovinas = men_6_meses_h_ovi + may_6_meses_h_ovi;
            document.getElementById('total_hembras_ovinas2').value = total_hembras_ovinas;

            // Calcular total machos ovinos
            let men_6_meses_m_ovi = parseInt(document.getElementById('men_6_meses_m_ovi2').value) || 0;
            let may_6_meses_m_ovi = parseInt(document.getElementById('may_6_meses_m_ovi2').value) || 0;
            let total_machos_ovi = men_6_meses_m_ovi + may_6_meses_m_ovi;
            document.getElementById('total_machos_ovi2').value = total_machos_ovi;

            // Calcular total ovinos
            let total_ovinos = total_hembras_ovinas + total_machos_ovi;
            document.getElementById('total_ovinos2').value = total_ovinos;

            // Calcular total hembras caprinas
            let men_6_meses_h_capri = parseInt(document.getElementById('men_6_meses_h_capri2').value) || 0;
            let may_6_meses_h_capri = parseInt(document.getElementById('may_6_meses_h_capri2').value) || 0;
            let total_hembras_capri = men_6_meses_h_capri + may_6_meses_h_capri;
            document.getElementById('total_hembras_capri2').value = total_hembras_capri;

            // Calcular total machos caprinos
            let men_6_meses_m_capri = parseInt(document.getElementById('men_6_meses_m_capri2').value) || 0;
            let may_6_meses_m_capri = parseInt(document.getElementById('may_6_meses_m_capri2').value) || 0;
            let total_machos_capri = men_6_meses_m_capri + may_6_meses_m_capri;
            document.getElementById('total_machos_capri2').value = total_machos_capri;

            // Calcular total caprinos
            let total_caprinos = total_hembras_capri + total_machos_capri;
            document.getElementById('total_caprinos2').value = total_caprinos;
        }

        // Ejecutar la función cada vez que se modifique un input
        document.getElementById('men_6_meses_h_ovi').oninput = calculateTotals;
        document.getElementById('may_6_meses_h_ovi').oninput = calculateTotals;
        document.getElementById('men_6_meses_m_ovi').oninput = calculateTotals;
        document.getElementById('may_6_meses_m_ovi').oninput = calculateTotals;
        document.getElementById('men_6_meses_h_capri').oninput = calculateTotals;
        document.getElementById('may_6_meses_h_capri').oninput = calculateTotals;
        document.getElementById('men_6_meses_m_capri').oninput = calculateTotals;
        document.getElementById('may_6_meses_m_capri').oninput = calculateTotals;
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
