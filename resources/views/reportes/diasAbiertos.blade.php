@extends('layouts')

@section('title')
Dias abiertos
@endsection
@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>
    Dias abiertos
                </h5>
            </div>
        </div>
    </div>
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
                <h3 class="cumb active-tab"> Dias abiertos</h3>
            </div>
            <hr>
        </div>
        <table id="proposalList" class="table table-bordered">
            <thead>
                <tr>
                    <th>Animal</th>
                    <th>Estado Productivo</th>
                    <th>Fecha Último Parto</th>
                    <th>Fecha Última Preñez</th>
                    <th>Días Abiertos</th>
                    <th>Fecha Última Palpación</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hembras as $hembra)
                    <tr>
                        <td>{{ $hembra->codigo }} - {{ $hembra->nombre }}</td>
                        <td>{{ $hembra->estadoProductivo->nombre ?? 'N/A' }}</td>
                        <td>{{ $hembra->fecha_parto }}</td>
                        <td>{{ $hembra->fecha_prenez }}</td>
{{--                         <td>{{ $hembra->dias_abiertos }}</td> --}}
                        <td>{{ is_numeric($hembra->dias_abiertos) ? intval($hembra->dias_abiertos) : 'N/A' }}</td>

                        <td>{{ $hembra->fecha_ultima_palpacion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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
