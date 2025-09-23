@extends('layouts')

@section('title')
    Layout
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
                <h5>Veterinarios</h5>
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
        <div class="">
            <div class="space-btw" style="    display: flex
;
    justify-content: space-between;
">
                <h3>Veterinarios Registrados</h3>

                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                data-bs-target="#createVeterinarioModal">
                <span class="material-symbols-outlined">
                    add
                </span>
            </button>
            </div>
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Número de Documento</th>
                        <th>Celular</th>
                        <th>Correo Electrónico</th>
                        <th>Sexo</th>
                        <th>Predio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($veterinarios as $veterinario)
                        <tr>
                            <td>{{ $veterinario->id }}</td>
                            <td>{{ $veterinario->nombre_completo }}</td>
                            <td>{{ $veterinario->numero_documento ?? 'N/A' }}</td>
                            <td>{{ $veterinario->celular ?? 'N/A' }}</td>
                            <td>{{ $veterinario->correo_electronico ?? 'N/A' }}</td>
                            <td>{{ $veterinario->sexo === 'M' ? 'Masculino' : 'Femenino' }}</td>
                            <td>{{ $veterinario->predio->nombre_predio ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay veterinarios registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('modal')
    <div class="modal fade" id="createVeterinarioModal" tabindex="-1" aria-labelledby="createVeterinarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="createVeterinarioForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createVeterinarioModalLabel">Agregar Veterinario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="predio_id">Predio</label>
                            <select id="predio_id" name="predio_id" class="form-control" required>
                                <option value="" disabled selected>Seleccione un predio</option>
                                @foreach ($predios as $predio)
                                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo</label>
                            <input type="text" name="nombre_completo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="numero_documento">Número de Documento</label>
                            <input type="text" name="numero_documento" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="text" name="celular" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="correo_electronico">Correo Electrónico</label>
                            <input type="email" name="correo_electronico" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <select name="sexo" class="form-control">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
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

    <script>
        $(document).ready(function() {
            // Mostrar modal de creación de veterinarios
            $('#addVeterinarioBtn').on('click', function() {
                $('#createVeterinarioModal').modal('show');
            });

            // Formulario de creación de veterinario
            $('#createVeterinarioForm').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('veterinarios.store') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Añadir el veterinario recién creado al select
                            $('#id_palpador').append(new Option(response.veterinario
                                .nombre_completo, response.veterinario.id));

                            // Mostrar mensaje de éxito
                            alert('Veterinario agregado con éxito.');

                            // Cerrar el modal y resetear el formulario
                            $('#createVeterinarioModal').modal('hide');
                            $('#createVeterinarioForm')[0].reset();
                        } else {
                            alert('Error al agregar el veterinario.');
                        }
                    },
                    error: function(xhr) {
                        alert('Ocurrió un error. Inténtalo de nuevo.');
                        console.error(xhr.responseText);
                    }
                });
            });

            // Validar que haya predios disponibles al cargar el formulario
            if ($('#predioSelect option').length <= 1) {
                $('#addPalpacionForm button[type="submit"]').prop('disabled', true);
                console.log('No hay predios disponibles para el usuario actual.');
            } else {
                $('#addPalpacionForm button[type="submit"]').prop('disabled', false);
            }
        });
    </script>
@endsection
