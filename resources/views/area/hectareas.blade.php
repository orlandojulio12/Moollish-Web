@extends('layouts')

@section('template_title')
    Registrar Servicio Ambiental
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('dashboard') }}"><i class="bi bi-arrow-left"
                        style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>ÁREA EN HECTÁREAS</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h3>Áreas en Hectáreas</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="hectareasTable">
                                <thead>
                                    <tr>
                                        <th>Nombre Predio</th>
                                        <th>Total Hectáreas (ha)</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($areas as $area)
                                        <tr>
                                            <td>{{ $area->predio->nombre_predio }}</td>
                                            <td>{{ number_format($area->total_hectareas, 2) }}</td>
                                            <td>
                                                <a href="{{ route('areas.detalle.hectareas', ['id' => $area->id_predio]) }}" class="btn btn-primary btn-sm">Ver Más</a>
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
            if ($.fn.DataTable.isDataTable('#hectareasTable')) {
                $('#hectareasTable').DataTable().clear().destroy();
            }


            // Inicializar DataTable
            $('#hectareasTable').DataTable({
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
@endsection
