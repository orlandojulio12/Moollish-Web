@extends('layouts')

@section('template_title')
    Razas
@endsection
@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>Infraestructuras</h5>
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


@if ($message = Session::get('success'))
                <div class="alert alert-success m-4">
                    <p>{{ $message }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif



    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h3>Lista de Infraestructuras</h3>
                            <a class="btn btn-primary"
                                href="{{ route('tipos-instalaciones-equipos.create') }}">{{ __('Crear Infraestructura') }}</a>
                        </div>
                        <div class="table-responsive">
                            <table id="proposalList" class="table table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre Infraestructura</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tiposInstalacionesEquipos as $tiposInstalacionesEquipo)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $tiposInstalacionesEquipo->nombre_tipo }}</td>

                                            <td>
                                                <form
                                                    action="{{ route('tipos-instalaciones-equipos.destroy', $tiposInstalacionesEquipo->id) }}"
                                                    method="POST" style="display: flex; gap: 2px;">
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('tipos-instalaciones-equipos.edit', $tiposInstalacionesEquipo->id) }}"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                            class="fa fa-fw fa-trash"></i> {{ __('Borrar') }}</button>
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
