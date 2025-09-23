@extends('layouts')

@section('title')
    Predios
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>Predios</h5>
            </div>
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


    @if (Auth::user() && Auth::user()->role->name == 'admin')
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <h3>Lista de asignación de predios</h3>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#asignacionModal">
                                    Crear Asignación
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table id="proposalList" class="table table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Encuestador</th>
                                            <th>Predio</th>
                                            <th>Fecha de Creación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asignaciones as $asignacion)
                                            <tr>
                                                <td>{{ $asignacion->encuestador->name . '-' . $asignacion->encuestador->documento }}
                                                </td>
                                                <td>{{ $asignacion->predio->nombre_predio . '-' . $asignacion->predio->cod_predio }}
                                                </td>
                                                <td>{{ $asignacion->created_at }}</td>
                                                <td>
                                                    <form id="formDelete{{ $asignacion->id }}"
                                                        action="{{ route('asignaciones.destroy', $asignacion->id) }}"
                                                        method="POST" style="display: flex; gap: 2px;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#asignacionModal{{ $asignacion->id }}"><i
                                                                class="fa fa-fw fa-edit"></i></a>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="confirmDelete({{ $asignacion->id }})"><i
                                                                class="fa fa-fw fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif




    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e49b39',
                cancelButtonColor: '#0000005e',
                confirmButtonText: 'Sí, eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario específico cuando se confirma la eliminación
                    document.getElementById('formDelete' + id).submit();
                }
            })
        }
    </script>

@endsection

@section('modal')
    <div class="modal fade" id="asignacionModal" tabindex="-1" aria-labelledby="editAreaModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAreaModalLabel">Crear Asignación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('asignaciones.store') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="id_encuestador">Seleccionar Encuestador</label>
                            <select name="id_encuestador" class="form-control" id="id_encuestador" required>
                                <option value="">Seleccione un encuestador</option>
                                @foreach ($encuestadores as $encuestador)
                                    <option value="{{ $encuestador->id }}">{{ $encuestador->name }}
                                        ({{ $encuestador->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_predio">Seleccionar Predio</label>
                            <select name="id_predio" class="form-control" id="id_predio" required>
                                <option value="">Seleccione un predio</option>
                                @foreach ($predios as $predio)
                                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }} -
                                        {{ $predio->departamento }} ({{ $predio->municipio }})</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- Botones -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Asignación</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    @foreach ($asignaciones as $asignacion)
        <div class="modal fade" id="asignacionModal{{ $asignacion->id }}" tabindex="-1"
            aria-labelledby="editAreaModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAreaModalLabel">Actualizar Asignación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('asignaciones.update', $asignacion->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Método PUT para actualizar -->

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="id_encuestador">Seleccionar Encuestador</label>
                                <select name="id_encuestador" class="form-control" id="id_encuestador" required>
                                    <option value="">Seleccione un encuestador</option>
                                    @foreach ($encuestadores as $encuestador)
                                        <option value="{{ $encuestador->id }}"
                                            {{ $asignacion->id_encuestador == $encuestador->id ? 'selected' : '' }}>
                                            {{ $encuestador->name }} ({{ $encuestador->email }})
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_predio">Seleccionar Predio</label>
                                @if (isset($asignacion) && count($prediosUpdate) > 0)
                                    <!-- Usar $prediosUpdate para el update -->
                                    <select name="id_predio" class="form-control">
                                        @foreach ($prediosUpdate as $predio)
                                            <option value="{{ $predio->id }}"
                                                {{ $asignacion->id_predio == $predio->id ? 'selected' : '' }}>
                                                {{ $predio->nombre_predio }} - {{ $predio->departamento }}
                                                ({{ $predio->municipio }})
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif(isset($predios) && count($predios) > 0)
                                    <!-- Usar $predios para la creación -->
                                    <select name="id_predio" class="form-control">
                                        @foreach ($predios as $predio)
                                            <option value="{{ $predio->id }}">
                                                {{ $predio->nombre_predio }} - {{ $predio->departamento }}
                                                ({{ $predio->municipio }})
                                            </option>
                                        @endforeach
                                    </select>
                                @endif


                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Asignación</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
    <!-- Tu script de inicialización -->
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
                    "emptyTable": "No hay información",
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
@endsection
