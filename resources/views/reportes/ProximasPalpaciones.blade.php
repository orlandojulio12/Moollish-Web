@extends('layouts')

@section('title')
    Hembras por palpar
@endsection
@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
    background: white;
    padding: 30px;
    border: 1px solid #eaebef;
        }

        .bread {
        font-size: 28px !important;
        color: black;
    }

    .cumb {
        margin: 0px !important;
        align-content: center;
    }

    .breadcrumb {
        display: flex;
    }

    .active-tab {
        color: #dc7a00;
    }

    .no-active-tab:hover {
        color: #dc7a00;
        cursor: pointer;
        text-decoration: underline;

    }

    .col-sm-12 {
        overflow: auto;
    }
    </style>
@endsection
@section('content')
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
    <div class="card-custom">
        <div class="header-grid">
            @if ($user->role->name === 'propietario')
                <div class="breadcrumb">
                    <a href="{{ route('inicio') }}">
                        <h3 class="cumb no-active-tab">
                            Inicio
                        </h3>
                    </a>

                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <a href="{{ route('listados') }}">
                        <h3 class="cumb no-active-tab">
                            Listados
                        </h3>
                    </a>

                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <h3 class="cumb active-tab"> Hembras por palpar</h3>
                </div>
            @elseif ($user->role->name === 'admin')
            <div class="breadcrumb">
                <a href="{{ route('inicio') }}">
                    <h3 class="cumb no-active-tab">
                        Inicio
                    </h3>
                </a>

                <span class="material-symbols-outlined bread">
                    chevron_forward
                </span>
                <a href="{{ route('listados') }}">
                    <h3 class="cumb no-active-tab">
                        Listados
                    </h3>
                </a>

                <span class="material-symbols-outlined bread">
                    chevron_forward
                </span>
                <h3 class="cumb active-tab"> Hembras por palpar</h3>
            </div>
            @endif
            <hr>
        </div>
        <table id="proposalList" class="table table-bordered">
            <thead>
                <tr>
                    <th>Animal</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Edad </th>
                    <th>Estado Productivo</th>
                    <th>Estado Reproductivo</th>
                    <th>Última Palpación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hembras as $hembra)
                    <tr>
                        <td>{{ $hembra->codigo }} </td>
                        <td>{{ \Carbon\Carbon::parse($hembra->fecha_nacimiento)->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $edadEnMeses = \Carbon\Carbon::parse($hembra->fecha_nacimiento)->diffInMonths(now());
                                $años = floor($edadEnMeses / 12);
                                $meses = $edadEnMeses % 12;
                            @endphp
                            @if ($años > 0)
                                {{ $años }} años{{ $meses > 0 ? " y $meses meses" : '' }}
                            @else
                                {{ $meses }} meses
                            @endif
                        </td>
                        <td>{{ $hembra->estadoProductivo->nombre ?? 'N/A' }}</td>
                        <td>{{ $hembra->estadoReproductivo->nombre ?? 'N/A' }}</td>
                        <td>
                            ({{ $hembra->ultimoTacto ? \Carbon\Carbon::parse($hembra->ultimoTacto->fecha)->format('d/m/Y') : 'Sin palpar' }})
                            @if ($hembra->ultimoTacto?->resultado == 'Prenada')
                                Preñada
                            @else
                                {{ $hembra->ultimoTacto->resultado ?? 'N/A' }}
                            @endif
                        </td>

                        <td>
                            <button class="btn btn-primary registrar-palpacion-btn" data-id="{{ $hembra->id_animal }}"
                                data-codigo="{{ $hembra->codigo }}" data-nombre="{{ $hembra->nombre }}"
                                data-bs-toggle="modal" data-bs-target="#createVeterinarioModal">
                                Registrar palpación
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection



<!-- Modal -->
@section('modal')
    <div class="modal fade" id="createVeterinarioModal" tabindex="-1" aria-labelledby="createVeterinarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="createVeterinarioForm" action="{{ route('palpaciones.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createVeterinarioModalLabel">Registrar Palpación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Animal -->
                        <div class="col-md-12">
                            <label for="id_animal" class="form-label">Animal</label>
                            <input type="text" class="form-control" id="animalInfo" readonly>
                            <input type="hidden" id="id_animal" name="id_animal" required>
                        </div>

                        <!-- Fecha -->
                        <div class="col-md-12 mt-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>

                        <!-- Resultado -->
                        <div class="col-md-12 mt-3">
                            <label for="resultado" class="form-label">Resultado</label>
                            <select class="form-control" id="resultado" name="resultado" required>
                                <option value="" disabled selected>Seleccione un resultado</option>
                                <option value="Prenada">Preñada</option>
                                <option value="Vacia">Vacía</option>
                            </select>
                        </div>

                        <!-- Parto proyectado (opcional, solo si resultado es "Prenada") -->
                        <div class="col-md-12 mt-3" id="partoProyectadoContainer" style="display: none;">
                            <label for="parto_proyectado" class="form-label">Fecha de parto proyectado</label>
                            <input type="date" class="form-control" id="parto_proyectado" name="parto_proyectado">
                        </div>

                        <!-- Veterinario -->
                        <div class="col-md-12 mt-3">
                            <label for="id_palpador" class="form-label">Veterinario</label>
                            <select class="form-control" id="id_palpador" name="id_palpador" required>
                                <option value="" disabled selected>Seleccione un veterinario</option>
                                @foreach ($veterinarios as $veterinario)
                                    <option value="{{ $veterinario->id }}">{{ $veterinario->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Botón para guardar -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Destruir la instancia existente si ya está inicializada
            if ($.fn.DataTable.isDataTable('#proposalList')) {
                $('#proposalList').DataTable().clear().destroy();
            }
            // Inicializar DataTable
            $('#proposalList').DataTable({
                language: {
                    "decimal": "",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "emptyTable": "No tienes predios asignados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('createVeterinarioModal');
            const animalInfoInput = document.getElementById('animalInfo');
            const animalIdInput = document.getElementById('id_animal');

            document.querySelectorAll('.registrar-palpacion-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const codigo = button.getAttribute('data-codigo');
                    const nombre = button.getAttribute('data-nombre');

                    // Actualizar los inputs del modal
                    animalInfoInput.value = `${codigo} - ${nombre}`;
                    animalIdInput.value = id;
                });
            });
        });
    </script>
@endsection
