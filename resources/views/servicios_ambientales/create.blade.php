@extends('layouts')

@section('template_title')
    Registrar Servicio Ambiental
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $id_predio) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">REGISTRAR SERVICIO AMBIENTAL</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">
                @if ($ServiciosAmbientalesExists)
                <form action="{{ route('servicios_ambientales.update', $id_predio) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_predio" value="{{ $id_predio }}">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tipo de Servicio</th>
                                <th>Hectáreas</th>
                                <th>Materiales Establecidos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tipos as $index => $tipo)
                            <tr>
                                <td>{{ $tipo->nombre }}</td>
                                <td>
                                    <input type="number" name="hectareas[{{ $tipo->id }}]" class="form-control hectareas"
                                        value="{{ old('hectareas.'.$tipo->id, $servicios_ambientales[$tipo->id]->hectareas ?? '') }}"
                                        placeholder="Número de hectáreas" onchange="calculateTotal()">
                                </td>
                                <td>
                                    <input type="text" name="materiales_establecidos[{{ $tipo->id }}]" class="form-control"
                                        value="{{ old('materiales_establecidos.'.$tipo->id, $servicios_ambientales[$tipo->id]->materiales_establecidos ?? '') }}"
                                        placeholder="Describa los materiales">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group mb-2 mb20">
                        <label for="sum_total" class="form-label">{{ __('Suma Total de Hectáreas') }}</label>
                        <input type="number" step="0.01" class="form-control" id="sum_total" name="sum_total" placeholder="Suma total de hectáreas" value="{{ old('sum_total', $sum_total ?? '') }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Actualizar Servicio Ambiental') }}</button>
                </form>

                @else
                <form action="{{route('servicios_ambientales.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id_predio" value="{{ $id_predio }}">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tipo de Servicio</th>
                                <th>Hectáreas</th>
                                <th>Materiales Establecidos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tipos as $index => $tipo)
                            <tr>
                                <td>{{ $tipo->nombre }}</td>
                                <td>
                                    <input type="number" name="hectareas[{{ $tipo->id }}]" class="form-control hectareas" placeholder="Número de hectáreas" onchange="calculateTotal()">
                                </td>
                                <td>
                                    <input type="text" name="materiales_establecidos[{{ $tipo->id }}]" class="form-control" placeholder="Describa los materiales">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group mb-2 mb20">
                        <label for="sum_total" class="form-label">{{ __('Suma Total de Hectáreas') }}</label>
                        <input type="number" step="0.01" class="form-control" id="sum_total" name="sum_total" placeholder="Suma total de hectáreas" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Registrar Servicio Ambiental') }}</button>
                </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Selecciona todos los inputs de hectáreas usando una clase específica o atributo name que empieza con 'hectareas'
        const hectareasInputs = document.querySelectorAll('input[name^="hectareas"]');
        const sumTotalInput = document.getElementById('sum_total');

        function updateSumTotal() {
            let total = 0;
            let allIntegers = true;

            hectareasInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                // Comprobar si el número es entero
                if (!Number.isInteger(value)) {
                    allIntegers = false;
                }
                total += value;
            });

            // Actualizar el total usando formato entero o decimal según corresponda
            if (allIntegers) {
                sumTotalInput.value = total.toFixed(0); // Si todos los números son enteros, mostrar sin decimales
            } else {
                sumTotalInput.value = total.toFixed(2); // Si alguno es decimal, mostrar dos decimales
            }
        }

        // Agregar evento a cada input para actualizar la suma total al modificar cualquier valor
        hectareasInputs.forEach(input => {
            input.addEventListener('input', updateSumTotal);
        });

        // Actualiza el total inicial al cargar la página
        updateSumTotal();
    });
</script>
@endsection

