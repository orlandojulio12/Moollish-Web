@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion6', $predioId) }}">Censo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">CRUSTÁCEOS</li>
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
                <form action="{{ route('censo_cructaceos.update', $CensoCructaceo->first()->id ?? '') }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Método PUT para actualización -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de Especie de Crustáceos</th>
                                <th scope="col">Nauplinos</th>
                                <th scope="col">Larvicultura</th>
                                <th scope="col">Engorde</th>
                                <th scope="col">Reproductores</th>
                                <th scope="col">Total Especie Crustáceo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($TipoEspecieCructaceos as $index => $tipo)
                            @php
                                $censoCructaceoData = $CensoCructaceo->firstWhere('id_tipo_esp_cructac', $tipo->id);
                            @endphp
                            <tr>
                                <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                <input type="hidden" name="id_tipo_esp_cructac[]" value="{{ $tipo->id }}">
                                <td>{{ $tipo->nombre }}</td>
                                <td><input type="number" class="form-control nauplinos" name="nauplinos[]" placeholder="0" value="{{ $censoCructaceoData ? $censoCructaceoData->nauplinos : 0 }}" oninput="calculateTotalUpdate(this)"></td>
                                <td><input type="number" class="form-control larvicultura" name="larvicultura[]" placeholder="0" value="{{ $censoCructaceoData ? $censoCructaceoData->larvicultura : 0 }}" oninput="calculateTotalUpdate(this)"></td>
                                <td><input type="number" class="form-control engorde" name="engorde[]" placeholder="0" value="{{ $censoCructaceoData ? $censoCructaceoData->engorde : 0 }}" oninput="calculateTotalUpdate(this)"></td>
                                <td><input type="number" class="form-control reproductores" name="reproductores[]" placeholder="0" value="{{ $censoCructaceoData ? $censoCructaceoData->reproductores : 0 }}" oninput="calculateTotalUpdate(this)"></td>
                                <td><input type="number" class="form-control total_especie_crustaceo" name="total_especie_cructacio[]" placeholder="0" value="{{ $censoCructaceoData ? $censoCructaceoData->total_especie_cructacio : 0 }}" readonly></td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td>Total Crustáceos</td>
                                <td colspan="5"><input type="number" class="form-control" name="total_cructaceos" id="total_crustaceos" value="{{ $CensoCructaceo->sum('total_especie_cructacio') }}" readonly></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>


                @else
                    <form action="{{ route('censo_cructaceos.store') }}" method="POST">
                    @csrf
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de Especie de Crustáceos</th>
                                <th scope="col">Nauplinos</th>
                                <th scope="col">Larvicultura</th>
                                <th scope="col">Engorde</th>
                                <th scope="col">Reproductores</th>
                                <th scope="col">Total Especie Crustáceo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($TipoEspecieCructaceos as $tipo)
                                <tr>
                                    <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                    <input type="hidden" name="id_tipo_esp_cructac[]" value="{{ $tipo->id }}">
                                    <td>{{ $tipo->nombre }}</td>
                                    <td><input type="number" class="form-control nauplinos" name="nauplinos[]"
                                            placeholder="0" oninput="calculateTotal(this)"></td>
                                    <td><input type="number" class="form-control larvicultura" name="larvicultura[]"
                                            placeholder="0" oninput="calculateTotal(this)"></td>
                                    <td><input type="number" class="form-control engorde" name="engorde[]" placeholder="0"
                                            oninput="calculateTotal(this)"></td>
                                    <td><input type="number" class="form-control reproductores" name="reproductores[]"
                                            placeholder="0" oninput="calculateTotal(this)"></td>
                                    <td><input type="number" class="form-control" name="total_especie_cructacio[]"
                                            placeholder="0" readonly></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total Crustáceos</td>
                                <td colspan="5"><input type="number" class="form-control" name="total_cructaceos"
                                        placeholder="0" readonly></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button type="submit" class="btn btn-primary">Guardar</button>
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
            const nauplinos = parseInt(row.querySelector('.nauplinos').value) || 0;
            const larvicultura = parseInt(row.querySelector('.larvicultura').value) || 0;
            const engorde = parseInt(row.querySelector('.engorde').value) || 0;
            const reproductores = parseInt(row.querySelector('.reproductores').value) || 0;

            // Calcular el total de crustáceos de esa especie
            const totalEspecieCructacio = nauplinos + larvicultura + engorde + reproductores;

            // Establecer el valor del campo Total Especie Crustáceo
            row.querySelector('input[name="total_especie_cructacio[]"]').value = totalEspecieCructacio;

            // Calcular el total de todos los crustáceos (sumando todos los total_especie_cructacio)
            calculateTotalCrustaceos();
        }

        function calculateTotalCrustaceos() {
            let totalCrustaceos = 0;

            // Sumar todos los valores de Total Especie Crustáceo
            document.querySelectorAll('input[name="total_especie_cructacio[]"]').forEach(function(input) {
                totalCrustaceos += parseInt(input.value) || 0;
            });

            // Establecer el valor del campo Total Crustáceos
            document.querySelector('input[name="total_cructaceos"]').value = totalCrustaceos;
        }

        // Configurar el evento para calcular el total cuando se modifiquen los valores
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll(
                'input[name="nauplinos[]"], input[name="larvicultura[]"], input[name="engorde[]"], input[name="reproductores[]"]'
                ).forEach(function(input) {
                input.addEventListener('input', function() {
                    calculateTotal(input);
                });
            });
        });
    </script>

<script>
   function calculateTotalUpdate(element) {
    // Obtener la fila actual
    const row = element.closest('tr');

    // Obtener valores de los inputs en la fila
    const nauplinos = parseInt(row.querySelector('.nauplinos').value) || 0;
    const larvicultura = parseInt(row.querySelector('.larvicultura').value) || 0;
    const engorde = parseInt(row.querySelector('.engorde').value) || 0;
    const reproductores = parseInt(row.querySelector('.reproductores').value) || 0;

    // Calcular el total de crustáceos de esa especie
    const totalEspecieCrustaceo = nauplinos + larvicultura + engorde + reproductores;

    // Establecer el valor del campo Total Especie Crustáceo
    row.querySelector('input[name="total_especie_cructacio[]"]').value = totalEspecieCrustaceo;

    // Calcular el total de todos los crustáceos
    calculateTotalCrustaceosUpdate();
}

function calculateTotalCrustaceosUpdate() {
    let totalCrustaceos = 0;

    // Sumar todos los valores de Total Especie Crustáceo
    document.querySelectorAll('input[name="total_especie_cructacio[]"]').forEach(function(input) {
        totalCrustaceos += parseInt(input.value) || 0;
    });

    // Establecer el valor del campo Total Crustáceos
    document.getElementById('total_crustaceos').value = totalCrustaceos;
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="nauplinos[]"], input[name="larvicultura[]"], input[name="engorde[]"], input[name="reproductores[]"]').forEach(function(input) {
        input.addEventListener('input', function() {
            calculateTotalUpdate(input);
        });
    });

    // Calcular total inicial
    calculateTotalCrustaceosUpdate();
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
