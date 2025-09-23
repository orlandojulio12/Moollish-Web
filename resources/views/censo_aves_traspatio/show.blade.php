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
                    <li class="breadcrumb-item active" aria-current="page">AVES TRASPATIO</li>
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
                <form action="{{ route('censo_aves_traspatio.update', $predioId) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de Ave Transpatio</th>
                                <th scope="col">Número de Aves</th>
                                <th scope="col">Edad</th>
                                <th scope="col">Procedencia de las Aves</th>
                                <th scope="col">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($CensoAvesTraspatio->isEmpty())
                                <tr>
                                    <td colspan="5">No hay registros disponibles.</td>
                                </tr>
                            @else
                                @foreach ($CensoAvesTraspatio as $index => $censo)
                                    <tr>
                                        <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                        <td>
                                            <select name="id_tipo_ave_transp[]" class="form-control">
                                                @foreach ($TipoAveTranspatio as $tipoOption)
                                                    <option value="{{ $tipoOption->id }}"
                                                        @if (isset($censo->id_tipo_ave_transp) && $censo->id_tipo_ave_transp == $tipoOption->id) selected @endif>
                                                        {{ $tipoOption->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control" name="num_aves[]"
                                                value="{{ $censo->num_aves }}" placeholder="0"></td>
                                        <td><input type="number" step="0.01" class="form-control" name="edad[]"
                                                value="{{ $censo->edad }}" placeholder="0"></td>
                                        <td><input type="text" class="form-control" name="precedencia_aves[]"
                                                value="{{ $censo->precedencia_aves }}" placeholder="Procedencia"></td>
                                        <td><input type="text" class="form-control" name="observaciones[]"
                                                value="{{ $censo->observaciones }}" placeholder="Observaciones"></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>

                @else
                    <form action="{{ route('censo_aves_traspatio.store') }}" method="POST">
                        @csrf
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Tipo de Ave Transpatio</th>
                                    <th scope="col">Número de Aves</th>
                                    <th scope="col">Edad</th>
                                    <th scope="col">Procedencia de las Aves</th>
                                    <th scope="col">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                        <select name="id_tipo_ave_transp[]" class="form-control">
                                            <option value="">Seleccione</option>
                                            @foreach ($TipoAveTranspatio as $tipoOption)
                                                <option value="{{ $tipoOption->id }}">{{ $tipoOption->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control num_aves" name="num_aves[]"
                                            placeholder="0" oninput="calculateTotal(this)"></td>
                                    <td><input type="number" step="0.01" class="form-control edad" name="edad[]"
                                            placeholder="0"></td>
                                    <td><input type="text" class="form-control precedencia_aves"
                                            name="precedencia_aves[]" placeholder="Procedencia"></td>
                                    <td><input type="text" class="form-control observaciones" name="observaciones[]"
                                            placeholder="Observaciones"></td>
                                </tr>
                            </tbody>
                        </table>


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
