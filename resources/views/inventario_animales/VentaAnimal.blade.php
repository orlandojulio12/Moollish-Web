@extends('layouts')

@section('title')
    Venta de animales
@endsection
@section('styles')

<style>
.card-custom {
    border-radius: 8px;
    background: white;
    padding: 30px;
    border: 1px solid #eaebef;
}
</style>

@endsection
@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>Vender animal</h5>
            </div>
        </div>
    </div>
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
        <br>
        <h3>Registrar una venta</h3>
        <hr>
        <form action="{{ route('VentaAnimal.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="id_animal">Animal</label>
                <select name="id_animal" id="id_animal" class="form-control" required>
                    <option value="">Seleccione un animal</option>
                    @foreach($animales as $animal)
                        <option value="{{ $animal->id_animal }}">{{ $animal->codigo }} - {{ $animal->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="fecha_venta">Fecha de Venta</label>
                <input type="date" name="fecha_venta" id="fecha_venta" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="comprador">Comprador</label>
                <input type="text" name="comprador" id="comprador" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control" rows="4"></textarea>
            </div>
    <br>
            <button type="submit" class="btn btn-primary">Registrar Venta</button>
        </form>

        <hr>

        <!-- Tabla con el historial de ventas -->
        <h2>Historial de Ventas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Animal</th>
                    <th>Fecha</th>
                    <th>Precio</th>
                    <th>Comprador</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                    <tr>
                        <td>{{ $venta->animal->codigo }} - {{ $venta->animal->nombre }}</td>
                        <td>{{ $venta->fecha_venta }}</td>
                        <td>{{ $venta->precio }}</td>
                        <td>{{ $venta->comprador }}</td>
                        <td>{{ $venta->observaciones }}</td>
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
