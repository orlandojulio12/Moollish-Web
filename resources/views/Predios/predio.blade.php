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



    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h3>Lista de Predios</h3>
                            <a class="btn btn-primary" href="{{ route('predios.create') }}">{{ __('Crear Predios') }}</a>
                        </div>
                        <div class="table-responsive">
                            <table id="proposalList" class="table table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Caracterizaciones</th>
                                        <th>Propietario</th>
                                        <th>cod_predio</th>
                                        <th>nombre_predio</th>
                                        <th>departamento</th>
                                        <th>municipio</th>
                                        <th>Acciones</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Predios as $predio)
                                    <tr>
                                        <td style="display: flex; gap: 2px;">
                                            <a class="btn btn-sm btn-primary" href="{{ route('Secciones', $predio->id) }}">
                                                {{ __('SECCIONES') }}
                                            </a>
                                        </td>
                                        <td>{{ $predio->propietario->nombre_completo }}</td> <!-- Cambiado a propietario -->
                                        <td>{{ $predio->cod_predio }}</td>
                                        <td>{{ $predio->nombre_predio }}</td>
                                        <td>{{ $predio->departamento }}</td>
                                        <td>{{ $predio->municipio }}</td>
                                
                                        <td>
                                            <form action="{{ route('predios.destroy', $predio->id) }}" method="POST"
                                                id="formDelete{{ $predio->id }}" style="display: flex; gap: 2px;">
                                                @csrf
                                                <a class="btn btn-sm btn-warning" href="{{ route('predios.edit', $predio->id) }}">
                                                    <i class="fa fa-fw fa-edit"></i> <!-- Tamaño en píxeles -->
                                                </a>
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $predio->id }})">
                                                    <i class="fa fa-fw fa-trash"></i> <!-- Tamaño en píxeles -->
                                                </button>
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
                    document.getElementById('formDelete' + id).submit();
                }
            })
        }
    </script>
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
