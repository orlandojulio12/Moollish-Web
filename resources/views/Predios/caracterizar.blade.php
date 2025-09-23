@extends('layouts')


@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $Predios->id) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion2', $Predios->id) }}">Informacion Del Predio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Caracterizar Predio de {{ $Predios->nombre_predio }}</li>
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

    <style>
        .form-control:disabled,
        .form-control[readonly] {
            background-color: #ffffff;

        }
    </style>
 @if ($caratexists)
 @else
<div class="alert alert-info" role="alert">
    Nota: Si no tiene la información disponible en algún campo, puede colocar "0" o "no" en el campo correspondiente.
</div>
@endif

    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">

                <div class="panel-body">
                    @if ($caratexists)
                        <form action="{{ route('predios.updatadeCaracterizacion', $Predios->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Área</th>
                                        <th>Medidas</th>
                                        <th>Tipo De Medida</th>
                                        <th>Materiales Establecidos</th>
                                        <th>Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tiposAreas as $index => $tipoArea)
                                        @php
                                            // Busca si existe un área asociada a este tipo de área en el predio actual
                                            $areaExistente = $areasExistentes->firstWhere(
                                                'id_tipo_area',
                                                $tipoArea->id,
                                            );
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="hidden" name="areas[{{ $index }}][id_tipo_area]"
                                                    value="{{ $tipoArea->id }}" required>
                                                {{ $tipoArea->nombre_area }}
                                            </td>
                                            <td>
                                                <input type="number" name="areas[{{ $index }}][medidas]"
                                                    class="form-control @error('areas.' . $index . '.medidas') is-invalid @enderror"
                                                    value="{{ old('areas.' . $index . '.medidas', $areaExistente ? $areaExistente->medidas : '') }}"
                                                    placeholder="Medidas (m²)" step="0.01" required>
                                                {!! $errors->first(
                                                    'areas.' . $index . '.medidas',
                                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                                ) !!}
                                            </td>
                                            <td>
                                                <select name="areas[{{ $index }}][tipo_medidas]"
                                                    class="form-control">
                                                    <option value="">Seleccione</option>
                                                    <option value="Hectareas"
                                                        {{ old('areas.' . $index . '.tipo_medidas', $areaExistente ? $areaExistente->tipo_medidas : '') == 'Hectareas' ? 'selected' : '' }}>
                                                        Hectareas</option>
                                                    <option value="Metros Cuadrados"
                                                        {{ old('areas.' . $index . '.tipo_medidas', $areaExistente ? $areaExistente->tipo_medidas : '') == 'Metros Cuadrados' ? 'selected' : '' }}>
                                                        Metros Cuadrados</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    name="areas[{{ $index }}][materiales_establecidos]"
                                                    class="form-control @error('areas.' . $index . '.materiales_establecidos') is-invalid @enderror"
                                                    value="{{ old('areas.' . $index . '.materiales_establecidos', $areaExistente ? $areaExistente->materiales_establecidos : '') }}"
                                                    placeholder="{{ $tipoArea->nombre_area === 'JAGUAYES' ? 'Cantidad de Jaguayes' : 'Materiales Establecidos' }}"
                                                    required>
                                                {!! $errors->first(
                                                    'areas.' . $index . '.materiales_establecidos',
                                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                                ) !!}
                                            </td>
                                            <td
                                                style="    display: flex;
                                            gap: 10px;">
                                                <input type="file" class="form-control"
                                                    name="areas[{{ $index }}][imagen]" accept="image/*">
                                                @if ($areaExistente && $areaExistente->imagen)
                                                    <!-- Botón para abrir el modal -->
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#modalImagen{{ $index }}">
                                                        Ver Imagen
                                                    </button>
                                                    <!-- Modal -->
                                                    @section('modal')
                                                        <div class="modal fade" id="modalImagen{{ $index }}"
                                                            tabindex="-1"
                                                            aria-labelledby="modalLabelImagen{{ $index }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="modalLabelImagen{{ $index }}">Imagen
                                                                            Actual</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body"
                                                                        style="display: flex;
                                                                    align-items: center;
                                                                    flex-direction: column;">
                                                                        <img src="{{ asset('imagenes/' . $areaExistente->imagen) }}"
                                                                            alt="Imagen del área" class="img-fluid">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endsection
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="form-group" style="margin: 10px;">
                                <label for="cant_total">Cantidad Total</label>
                                <input type="text" id="cant_total" class="form-control"
                                    value="{{ $areasExistentes->sum('medidas') }}" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Actualizar') }}</button>
                        </form>
                    @else
                        <form action="{{ route('predios.storeCaracterizacion', $Predios->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Área</th>
                                        <th>Medidas</th>
                                        <th>Tipo De Medida</th>
                                        <th>
                                            Materiales Establecidos
                                        </th>
                                        <th>Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tiposAreas as $index => $area)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="areas[{{ $index }}][id_tipo_area]"
                                                    value="{{ $area->id }}" required>
                                                {{ $area->nombre_area }}
                                            </td>
                                            <td>
                                                <input type="number" name="areas[{{ $index }}][medidas]"
                                                    class="form-control @error('areas.' . $index . '.medidas') is-invalid @enderror"
                                                    value="{{ old('areas.' . $index . '.medidas') }}" placeholder="Medidas"
                                                    step="0.01"required>
                                                {!! $errors->first(
                                                    'areas.' . $index . '.medidas',
                                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                                ) !!}
                                            </td>
                                            <td>
                                                <select name="areas[{{ $index }}][tipo_medidas]"
                                                    class="form-control">
                                                    <option value="">Seleccione</option>
                                                    <option value="Hectareas">Hectareas</option>
                                                    <option value="Metros Cuadrados">Metros Cuadrados</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    name="areas[{{ $index }}][materiales_establecidos]"
                                                    class="form-control @error('areas.' . $index . '.materiales_establecidos') is-invalid @enderror"
                                                    value="{{ old('areas.' . $index . '.materiales_establecidos') }}"
                                                    placeholder="{{ $area->nombre_area === 'JAGUAYES' ? 'Cantidad de Jaguayes' : 'Materiales Establecidos' }}"required>
                                                {!! $errors->first(
                                                    'areas.' . $index . '.materiales_establecidos',
                                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                                ) !!}
                                            </td>
                                            <td>
                                                <input type="file" class="form-control"
                                                    name="areas[{{ $index }}][imagen]" accept="image/*">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="form-group" style="margin: 10px;">
                                <label for="cant_total">Cantidad Total</label>
                                <input type="text" id="cant_total" class="form-control" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                        </form>
                    @endif

                </div>
            </div>
        </div>


    @section('modal')
    @endsection
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const medidasInputs = document.querySelectorAll('input[name^="areas"][name$="[medidas]"]');
            const cantTotalInput = document.getElementById('cant_total');

            function updateCantTotal() {
                let total = 0;
                let allIntegers = true;

                medidasInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    if (!Number.isInteger(value)) {
                        allIntegers = false;
                    }
                    total += value;
                });

                if (allIntegers) {
                    cantTotalInput.value = total.toFixed(0);
                } else {
                    cantTotalInput.value = total.toFixed(2);
                }
            }

            medidasInputs.forEach(input => {
                input.addEventListener('input', updateCantTotal);
            });

            updateCantTotal();
        });
    </script>

@endsection
