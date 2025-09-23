@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion6', $predioId) }}">Censo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">PECES</li>
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

                <form action="{{ route('censo_peces.update', $CensoPez->first()->id ?? '') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de Especie de Peces</th>
                                <th scope="col">Ovas</th>
                                <th scope="col">Alevinos</th>
                                <th scope="col">Engorde</th>
                                <th scope="col">Reproductores</th>
                                <th scope="col">Total Pez Especie</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($TipoEspeciePeces as $index => $tipo)
                            @php
                                // Buscar si existe un registro de censo para este tipo de especie
                                $censoPezData = $CensoPez->firstWhere('id_tipo_esp_peces', $tipo->id);
                            @endphp
                            <tr>
                                <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                <input type="hidden" name="id_tipo_esp_peces[]" value="{{ $tipo->id }}">
                                <td>{{ $tipo->nombre }}</td>
                                <td><input type="number" class="form-control" name="ovas[]" placeholder="0" value="{{ $censoPezData ? $censoPezData->ovas : 0 }}" oninput="updateTotals()"></td>
                                <td><input type="number" class="form-control" name="alevinos[]" placeholder="0" value="{{ $censoPezData ? $censoPezData->alevinos : 0 }}" oninput="updateTotals()"></td>
                                <td><input type="number" class="form-control" name="engorde[]" placeholder="0" value="{{ $censoPezData ? $censoPezData->engorde : 0 }}" oninput="updateTotals()"></td>
                                <td><input type="number" class="form-control" name="reproductores[]" placeholder="0" value="{{ $censoPezData ? $censoPezData->reproductores : 0 }}" oninput="updateTotals()"></td>
                                <td><input type="number" class="form-control" name="total_pez_especie[]" placeholder="0" value="{{ $censoPezData ? $censoPezData->total_pez_especie : 0 }}" readonly></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total Peces</td>
                                <td colspan="5"><input type="number" class="form-control" name="total_peces" id="total_peces" placeholder="0" value="{{ $CensoPez->sum('total_pez_especie') }}" readonly></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>




               @else
                <form action="{{ route('censo_peces.store') }}" method="POST">
                    @csrf

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de Especie de Peces</th>
                                <th scope="col">Ovas</th>
                                <th scope="col">Alevinos</th>
                                <th scope="col">Engorde</th>
                                <th scope="col">Reproductores</th>
                                <th scope="col">Total Pez Especie</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($TipoEspeciePeces as $tipo)
                            <tr>
                                <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                <input type="hidden" name="id_tipo_esp_peces[]" value="{{ $tipo->id }}">
                                <td>{{ $tipo->nombre }}</td>
                                <td><input type="number" class="form-control ovas" name="ovas[]" placeholder="0" oninput="calculateTotal(this)"></td>
                                <td><input type="number" class="form-control alevinos" name="alevinos[]" placeholder="0" oninput="calculateTotal(this)"></td>
                                <td><input type="number" class="form-control engorde" name="engorde[]" placeholder="0" oninput="calculateTotal(this)"></td>
                                <td><input type="number" class="form-control reproductores" name="reproductores[]" placeholder="0" oninput="calculateTotal(this)"></td>
                                <td><input type="number" class="form-control" name="total_pez_especie[]" placeholder="0" readonly></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total Peces</td>
                                <td colspan="5"><input type="number" class="form-control" name="total_peces" placeholder="0" readonly></td>
                            </tr>
                        </tfoot>
                    </table>


                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
                @endif
            </div>
        </div>
    </div>


    <script>
      function calculateTotal(element) {
        // Obtener la fila actual
        const row = element.closest('tr');

        // Obtener valores de los inputs en la fila
        const ovas = parseInt(row.querySelector('.ovas').value) || 0;
        const alevinos = parseInt(row.querySelector('.alevinos').value) || 0;
        const engorde = parseInt(row.querySelector('.engorde').value) || 0;
        const reproductores = parseInt(row.querySelector('.reproductores').value) || 0;

        // Calcular el total de peces de esa especie
        const totalPezEspecie = ovas + alevinos + engorde + reproductores;

        // Establecer el valor del campo Total Pez Especie
        row.querySelector('input[name="total_pez_especie[]"]').value = totalPezEspecie;

        // Calcular el total de todos los peces (sumando todos los total_pez_especie)
        calculateTotalPeces();
    }

    function calculateTotalPeces() {
        let totalPeces = 0;

        // Sumar todos los valores de Total Pez Especie
        document.querySelectorAll('input[name="total_pez_especie[]"]').forEach(function(input) {
            totalPeces += parseInt(input.value) || 0;
        });

        console.log('Total Peces Calculado:', totalPeces); // Verificación

        // Establecer el valor del campo Total Peces
        document.querySelector('input[name="total_peces"]').value = totalPeces;
    }

    // Configurar el evento para calcular el total cuando se modifiquen los valores
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[name="ovas[]"], input[name="alevinos[]"], input[name="engorde[]"], input[name="reproductores[]"]').forEach(function(input) {
            input.addEventListener('input', function() {
                calculateTotal(input);
            });
        });
    });
        </script>

<script>
    // Esta función es la principal para actualizar los totales
    function updateTotals() {
        let totalPeces = 0;

        // Recorremos cada fila y calculamos el total de peces por especie
        document.querySelectorAll('tbody tr').forEach(function(row) {
            const ovas = parseInt(row.querySelector('input[name="ovas[]"]').value) || 0;
            const alevinos = parseInt(row.querySelector('input[name="alevinos[]"]').value) || 0;
            const engorde = parseInt(row.querySelector('input[name="engorde[]"]').value) || 0;
            const reproductores = parseInt(row.querySelector('input[name="reproductores[]"]').value) || 0;

            // Calcular el total de peces de esa especie (fila)
            const totalPezEspecie = ovas + alevinos + engorde + reproductores;

            // Actualizar el campo del total de peces por especie
            row.querySelector('input[name="total_pez_especie[]"]').value = totalPezEspecie;

            // Acumular al total general
            totalPeces += totalPezEspecie;
        });

        // Establecer el total general en el campo de total de peces
        document.getElementById('total_peces').value = totalPeces;
    }

    // Añadir los eventos cuando se cargue la página
    document.addEventListener('DOMContentLoaded', function() {
        // Añadir el evento de input a los campos de Ovas, Alevinos, Engorde, y Reproductores
        document.querySelectorAll('input[name="ovas[]"], input[name="alevinos[]"], input[name="engorde[]"], input[name="reproductores[]"]').forEach(function(input) {
            input.addEventListener('input', updateTotals); // Cada vez que se cambie un input, recalcula los totales
        });

        // Llamar a la función una vez para asegurarse de que los totales estén calculados al inicio
        updateTotals();
    });
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
