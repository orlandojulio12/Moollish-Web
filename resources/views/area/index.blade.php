@extends('layouts')

@section('template_title')
    Users
@endsection
@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>Tipos de Areas</h5>
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
            background-color: #C29F77  !important;
            z-index: 0;
        }
    </style>

   

    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h3>Lista de Tipos de Areas</h3>
                            <a class="btn btn-primary"
                                href="{{ route('areas.create') }}">{{ __('Crear Tipos de Areas') }}</a>
                        </div>
                        <div class="table-responsive">
                            <table id="proposalList" class="table table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Área</th>
                                        <th>Creada por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp <!-- Inicializa la variable $i -->
                                    @foreach ($areas as $area)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $area->TiposAreas->nombre_area }}</td>
                                            <td>
                                                @if ($area->created_by === null)
                                                    Sistema
                                                @else
                                                    @php
                                                        $creator = $area->user; // Relación con el usuario que creó el área
                                                    @endphp
                            
                                                    @if ($creator && $creator->role->name === 'admin')
                                                        Sistema
                                                    @else
                                                        {{ $creator->name ?? 'Desconocido' }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $currentUser = auth()->user();
                                                @endphp
                            
                                                <!-- Solo permite borrar según las reglas -->
                                                @if ($currentUser->role->name === 'admin' || $area->created_by === $currentUser->id)
                                                    <form action="{{ route('areas.destroy', $area->id) }}" method="POST" style="display: flex; gap: 2px;">
                                                        <a class="btn btn-sm btn-success" href="{{ route('areas.edit', $area->id) }}">
                                                            <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                        </a>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro que quieres eliminar esta área?') ? this.closest('form').submit() : false;">
                                                            <i class="fa fa-fw fa-trash"></i> {{ __('Borrar') }}
                                                        </button>
                                                    </form>
                                                @else
                                                <button style="background: lightgray; color: gray; border:none; border-radius:2px; padding:5px 12px;">
                                                    <span class="material-symbols-outlined" style="color: gray; font-size:13px;">
                                                        lock
                                                        </span>
                                                </button>
                                                @endif
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
