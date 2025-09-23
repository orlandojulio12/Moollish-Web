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
                    <li class="breadcrumb-item active" aria-current="page">AVES COMERCIALES</li>
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
                <form action="{{ route('censo_aves_comerciales.update', $predioId) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de Ave Comercial</th>
                                <th scope="col">Línea</th>
                                <th scope="col">Número de Aves</th>
                                <th scope="col">Edad</th>
                                <th scope="col">Número de Galones</th>
                                <th scope="col">Área de Galones</th>
                                <th scope="col">Densidad</th>
                                <th scope="col">Tiempo de Descanso de Lotes</th>
                                <th scope="col">Procedencia de las Aves</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($CensoAvesComerciales as $index => $censo)
                            <tr>
                                <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                <input type="hidden" name="id_tipo_ave_comercial[]" value="{{ $censo->id_tipo_ave_comercial }}">
                                <td>{{ $censo->tipoAveComercial->nombre }}</td>
                                <td>
                                    <select name="linea[]" class="form-control linea">
                                        <option value="">Seleccione</option>
                                        <option value="leghorn_blanca" {{ $censo->linea == 'leghorn_blanca' ? 'selected' : '' }}>Leghorn Blanca</option>
                                        <option value="rhode island roja" {{ $censo->linea == 'rhode island roja' ? 'selected' : '' }}>Rhode Island Roja</option>
                                        <!-- Resto de opciones -->
                                    </select>
                                </td>
                                <td><input type="number" class="form-control num_aves" name="num_aves[]" value="{{ $censo->num_aves }}" placeholder="0"></td>
                                <td><input type="number" step="0.01" class="form-control edad" name="edad[]" value="{{ $censo->edad }}" placeholder="0"></td>
                                <td><input type="number" class="form-control num_galones" name="num_galones[]" value="{{ $censo->num_galones }}" placeholder="0"></td>
                                <td><input type="text" class="form-control area_galones" name="area_galones[]" value="{{ $censo->area_galones }}" placeholder="Área de galones"></td>
                                <td><input type="number" step="0.01" class="form-control densidad" name="densidad[]" value="{{ $censo->densidad }}" placeholder="0"></td>
                                <td><input type="text" class="form-control tiemp_descan_lotes" name="tiemp_descan_lotes[]" value="{{ $censo->tiemp_descan_lotes }}" placeholder="Tiempo de descanso"></td>
                                <td><input type="text" class="form-control procedencia_aves" name="procedencia_aves[]" value="{{ $censo->procedencia_aves }}" placeholder="Procedencia"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>



               @else
                <form action="{{ route('censo_aves_comerciales.store') }}" method="POST">
                    @csrf

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de Ave Comercial</th>
                                <th scope="col">Línea</th>
                                <th scope="col">Número de Aves</th>
                                <th scope="col">Edad</th>
                                <th scope="col">Número de Galones</th>
                                <th scope="col">Área de Galones</th>
                                <th scope="col">Densidad</th>
                                <th scope="col">Tiempo de Descanso de Lotes</th>
                                <th scope="col">Procedencia de las Aves</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($TipoAveComercial as $tipo)
                            <tr>
                                <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                <input type="hidden" name="id_tipo_ave_comercial[]" value="{{ $tipo->id }}">
                                <td>{{ $tipo->nombre }}</td>
                                <td>
                                    <select name="linea[]" class="form-control linea" id="chickens">
                                        <option value="">Seleccione</option>
                                        <option value="leghorn_blanca">Leghorn Blanca</option>
                                        <option value="rhode island roja">Rhode Island Roja</option>
                                        <option value="new hampshire">New Hampshire</option>
                                        <option value="playmouth rock blanca">Playmouth Rock Blanca</option>
                                        <option value="cornish">Cornish</option>
                                        <option value="plymouth rock barrada">Plymouth Rock Barrada</option>
                                        <option value="sussex clara">Sussex Clara</option>
                                    </select>
                                    </td>
                                <td><input type="number" class="form-control num_aves" name="num_aves[]" placeholder="0" oninput="calculateTotal(this)"></td>
                                <td><input type="number" step="0.01" class="form-control edad" name="edad[]" placeholder="0"></td>
                                <td><input type="number" class="form-control num_galones" name="num_galones[]" placeholder="0"></td>
                                <td><input type="text" class="form-control area_galones" name="area_galones[]" placeholder="Área de galones"></td>
                                <td><input type="number" step="0.01" class="form-control densidad" name="densidad[]" placeholder="0"></td>
                                <td><input type="text" class="form-control tiemp_descan_lotes" name="tiemp_descan_lotes[]" placeholder="Tiempo de descanso"></td>
                                <td><input type="text" class="form-control procedencia_aves" name="procedencia_aves[]" placeholder="Procedencia"></td>
                            </tr>
                            @endforeach
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
