@extends('layouts')

@section('title')
    Moollish Monta natural
@endsection

@section('styles')

<style>



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
    @section('content')

    <div class="container" style="background: white;
    padding: 20px;
    border-radius: 8px;">
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
                    <a href="{{ route('registros') }}">
                        <h3 class="cumb no-active-tab">
                            Registros
                        </h3>
                    </a>
                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <a href="{{ route('reproduccionAnimal') }}">
                        <h3 class="cumb no-active-tab">
                            Reproduccion animal
                        </h3>
                    </a>
                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>

                    <h3 class="cumb active-tab"> Monta natural </h3>
                </div>
                <hr>

            </div>
        <form action="{{ route('monta_natural.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="id_vaca" class="form-label">Selecciona una Vaca</label>
                <select name="id_vaca" id="id_vaca" class="form-control" required>
                    <option value="" disabled selected>-- Selecciona una vaca --</option>
                    @foreach ($vacas as $vaca)
                        <option value="{{ $vaca->id_animal }}">{{ $vaca->codigo }} - {{ $vaca->nombre }} </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="id_toro" class="form-label">Selecciona un Toro</label>
                <select name="id_toro" id="id_toro" class="form-control" required>
                    <option value="" disabled selected>-- Selecciona un toro --</option>
                    @foreach ($toros as $toro)
                        <option value="{{ $toro->id_animal }}">{{ $toro->codigo }} - {{ $toro->nombre }} </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_monta" class="form-label">Fecha de Monta</label>
                <input type="date" name="fecha_monta" id="fecha_monta" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Monta</button>
        </form>

        <hr>
        <h2>Montas Registradas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vaca</th>
                    <th>Toro</th>
                    <th>Fecha de Monta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($montas as $monta)
                    <tr>
                        <td>{{ $monta->id }}</td>
                        <td>{{ $monta->vaca->nombre ?? 'N/A' }}</td>
                        <td>{{ $monta->toro->nombre ?? 'N/A' }}</td>
                        <td>{{ $monta->fecha_monta }}</td>
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
