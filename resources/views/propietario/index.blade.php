@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>Propietarios</h5>
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
    <!--Page content-->
    <!--===================================================-->


    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h3>Lista de Propietarios</h3>
                            @php
                                $currentUser = Auth::user();
                            @endphp
                            @if ($currentUser->role->name == 'propietario')
                                @php
                                    // Verificar si el usuario autenticado ya tiene un registro en la tabla de propietarios
                                    $existePropietario = \App\Models\Propietario::where('id_user', $currentUser->id)->exists();
                                @endphp
    
                                @if (!$existePropietario)
                                    <!-- Mostrar el botón solo si el propietario NO tiene un registro en la tabla -->
                                    <a class="btn btn-primary" href="{{ route('propietarios.create') }}">{{ __('Crear Propietario') }}</a>
                                @endif
                            @elseif($currentUser->role->name == 'admin')
                                <!-- Mostrar el botón para el rol admin -->
                                <a class="btn btn-primary" href="{{ route('propietarios.create') }}">{{ __('Crear Propietario') }}</a>
                            @endif
    
                        </div>
                        <div class="table-responsive">
                            <table id="proposalList" class="table table-hover" cellspacing="0" width="100%">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre Completo</th>
                                        <th>Tipo de Documento</th>
                                        <th>Número de Documento</th>
                                        <th>Género</th>
                                        <th>Correo Electrónico</th>
                                        <th>Teléfono</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp
                                    @foreach ($propietarios as $propietario)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $propietario->nombre_completo }}</td>
                                            <td>{{ $propietario->tipo_doc }}</td>
                                            <td>{{ $propietario->num_doc }}</td>
                                            <td>{{ $propietario->genero }}</td>
                                            <td>{{ $propietario->correo_electronico }}</td>
                                            <td>{{ $propietario->telefono }}</td>
                                            <td>
                                                @if($currentUser->role->name === 'admin')
                                                    <form id="formDelete{{ $propietario->id }}" action="{{ route('propietarios.destroy', $propietario->id) }}" method="POST" style="display: flex; gap: 2px;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a class="btn btn-sm btn-success" href="{{ route('propietarios.edit', $propietario->id) }}">
                                                            <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $propietario->id }})">
                                                            <i class="fa fa-fw fa-trash"></i> {{ __('Borrar') }}
                                                        </button>
                                                    </form>
                                                @elseif($currentUser->role->name === 'propietario' && $propietario->id_user == $currentUser->id)
                                                    <a class="btn btn-sm btn-success" href="{{ route('propietarios.edit', $propietario->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                @else
                                                    <span>No tienes permisos para acciones</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Paginación, si es necesaria -->
                            {{ $propietarios->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Función JavaScript para confirmar eliminación -->
    <script>
        function confirmDelete(id) {
            if (confirm('¿Estás seguro que quieres eliminar este propietario?')) {
                document.getElementById('formDelete' + id).submit();
            }
        }
    </script>
    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                    // Ahora este código encontrará el formulario correcto por su ID
                    document.getElementById('formDelete' + id).submit();
                }
            })
        }
    </script>

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
