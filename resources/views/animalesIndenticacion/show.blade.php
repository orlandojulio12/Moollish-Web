@extends('layouts')

@section('template_title')
    Registrar Servicio Ambiental
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href=""><i class="bi bi-arrow-left"
                        style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>REGISTRAR SERVICIO AMBIENTAL</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">
                @php
                use App\Models\ManGenGanado;

                // Obtener el total de animales identificados
                $totalAnimalesIdentificados = ManGenGanado::whereNotNull('ident_animales')->count();

            @endphp
                <table id="proposalList" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Información</th>
                            <th>Dato</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total de Animales Identificados</td>
                            <td>{{ $totalAnimalesIdentificados ?? 'No especificado' }}</td>
                        </tr>
                    </tbody>
                </table>
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

