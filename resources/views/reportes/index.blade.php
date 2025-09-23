@extends('layouts')

@section('title')
    Centro de reportes
@endsection
@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>Centro de reportes</h5>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card-custom {
            width: 100%;
            display: flex;
            height: 100%;
            flex-direction: column;



            border-radius: 8px;
    background: white;
    padding: 30px;
    border: 1px solid #eaebef;

        }

        .report-container {
            display: flex;
        }

        .card-report {
            margin: 0px 12px;
            background: #FFE4C4;
            border-radius: 4px;
            width: 32%;
            transition: 0.2s;
        }

        .report-header {
            padding: 10px;
            background: #C87715;
            color: white;
            border-radius: 8px 8px 0px 0px;
            display: flex;
            align-items: center;
        }

        .report-header img {
            margin-right: 20px;
        }

        .report-body {
            padding: 10px 20px;
        }

        .report-header span {
            font-size: 16px;
            font-weight: 600;
        }

        .report-header span:hover {}

        .card-report:hover {
            transform: scale(1.05);

        }

        .card-report .report-header {
            transition: 0.2s;

        }

        .card-report:hover .report-header {
            background: #a55a00;
        }

        .readonly {
            padding: 10px;
            border-radius: 100px;
            background: #ffffff1c;
            width: 48px;
            height: 48px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
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
        <div>
            <h3>Centro de reportes</h3>
            <hr>
        </div>
        <div class="report-container">
            <div class="card-report">
                <div class="report-header">
                    <img src="../assets/images/report.png" alt="" width="48px">
                    <span>Proyecciones</span>
                </div>
                <div class="report-body">
                    <ul>
                        <li>
                            <a href="{{ route('proyeccionParto.index') }}">
                                Proyeccion de partos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('ProyeccionDestetes.index') }}">
                                Proyeccion destetes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('HembrasPorPalpar.index') }}">
                                Hembras a palpar
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('diasAbiertos.index') }}">
                                Dias abiertos
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="card-report">
                <div class="report-header">
                    <div class="readonly">
                        <span class="material-symbols-outlined"
                            style="    font-size: 24px;
                            font-weight: normal;">
                            history
                        </span>
                    </div>

                    <span>Listados</span>
                </div>
                <div class="report-body">
                    <ul>
                        <li>
                            <a href="{{ route('inventario.general') }}">
                                Inventario general
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('inventarioFisico.index') }}">
                                Inventario fisico
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('reportes.traslados.listado') }}">
                                Listado de traslados
                            </a>
                        </li>
                        <li>
                            Listado de actividades
                        </li>

                    </ul>
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
