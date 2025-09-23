@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId ) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion2', $predioId ) }}">Informacion Del Predio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Instalaciones y equipos</li>
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
                @if ($ManExists)
                <form action="{{ route('instalaciones_equipos2.update', $predioId) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id_predio" value="{{ $predioId }}">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tipo de Equipo</th>
                                <th>Sí</th>
                                <th>No</th>
                                <th>Especificar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tipos_equipos as $equipo)
                                @php
                                    $equipoData = $instalacionesEquipos->get($equipo->id); // Obtener el equipo actual relacionado con el predio
                                @endphp
                                <tr>
                                    <td>{{ $equipo->nombre_tipo }}</td>
                                    <td>
                                        <input type="radio" name="equipos[{{ $equipo->id }}][si]" value="si"
                                            {{ isset($equipoData) && $equipoData->si == 'si' ? 'checked' : '' }}
                                            onclick="toggleRadio(this, {{ $equipo->id }}, 'no')">
                                    </td>
                                    <td>
                                        <input type="radio" name="equipos[{{ $equipo->id }}][no]" value="no"
                                            {{ isset($equipoData) && $equipoData->no == 'no' ? 'checked' : '' }}
                                            onclick="toggleRadio(this, {{ $equipo->id }}, 'si')">
                                    </td>
                                    <td>
                                        <input type="text" name="equipos[{{ $equipo->id }}][especificar]" class="form-control"
                                            value="{{ isset($equipoData) ? $equipoData->especificar : '' }}">
                                    </td>
                                    <input type="hidden" name="equipos[{{ $equipo->id }}][id_tipos_equipos]" value="{{ $equipo->id }}">
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
                @else
                    <form action="{{ route('instalaciones_equipos.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_predio" value="{{ $predioId }}">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tipo de Equipo</th>
                                    <th>Sí</th>
                                    <th>No</th>
                                    <th>Especificar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tipos_equipos as $equipo)
                                    <tr>
                                        <td>{{ $equipo->nombre_tipo }}</td>
                                        <td>
                                            <input type="radio" name="equipos[{{ $equipo->id }}][si]" value="si"
                                                onclick="toggleRadio(this, {{ $equipo->id }}, 'no')">
                                        </td>
                                        <td>
                                            <input type="radio" name="equipos[{{ $equipo->id }}][no]" value="no"
                                                onclick="toggleRadio(this, {{ $equipo->id }}, 'si')">
                                        </td>
                                        <td>
                                            <input type="text" name="equipos[{{ $equipo->id }}][especificar]"
                                                class="form-control">
                                        </td>
                                        <input type="hidden" name="equipos[{{ $equipo->id }}][id_tipos_equipos]"
                                            value="{{ $equipo->id }}">
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

    <script>
        // Función que asegura que solo se seleccione una opción (sí o no) por cada equipo
        function toggleRadio(currentRadio, equipoId, oppositeRadioName) {
            const oppositeRadio = document.querySelector(`input[name="equipos[${equipoId}][${oppositeRadioName}]"]`);

            if (currentRadio.checked) {
                oppositeRadio.checked = false; // Desmarcar el otro radio button
            }
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
