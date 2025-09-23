@extends('layouts')

@section('title')
    Proyeccion de partos
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
                    <h3 class="cumb active-tab"> Proyeccion de partos</h3>
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
                <h3 class="cumb active-tab"> Proyeccion de partos</h3>
            </div>
            @endif
            <hr>
        </div>
        
        @if ($proyecciones->isEmpty())
            <div class="alert alert-info">No hay proyecciones de parto disponibles.</div>
        @else
            <table class="table table-striped" id="proposalList">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Fecha preñada</th>
                        <th>Fecha Proyectada</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proyecciones as $proyeccion)
                        <tr>
                            <td>{{ $proyeccion['codigo'] }}</td>
                            <td>{{ $proyeccion['nombre'] }}</td>
                            <td>{{ $proyeccion['fecha_prenada'] }}</td>
                            <td>{{ $proyeccion['fecha_proyeccion'] }}</td>
                            <td>
                                @if ($proyeccion['estado'] == 1)
                                    <span>Preñada</span>
                                @else
                                    <span>Vacia</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
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
