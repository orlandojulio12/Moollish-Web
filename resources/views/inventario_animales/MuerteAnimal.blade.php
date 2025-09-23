@extends('layouts')

@section('title')
    Muerte animal
@endsection

@section('styles')
    <style>
        .container {
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
        <div class="container">
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
                        <a href="{{ route('registros') }}">
                            <h3 class="cumb no-active-tab">
                                Registros
                            </h3>
                        </a>
                        <span class="material-symbols-outlined bread">
                            chevron_forward
                        </span>
                        <a href="{{ route('animales') }}">
                            <h3 class="cumb no-active-tab">
                                Animales
                            </h3>
                        </a>

                        <span class="material-symbols-outlined bread">
                            chevron_forward
                        </span>
                        <h3 class="cumb active-tab"> Muerte animal </h3>
                    </div>
                @elseif ($user->role->name === 'admin')
                    <h3>Administrar caracterizaciones</h3>
                @endif
                <hr>
            </div>




            <form action="{{ route('muerte.store') }}" method="POST" id="muerte-form">
                @csrf

                <div class="form-group">
                   {{--  <label for="id_animal">Animal</label>
                    <select name="id_animal" id="id_animal" class="form-control" required>
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal->id_animal }}">{{ $animal->codigo }} - {{ $animal->nombre }}</option>
                        @endforeach
                    </select> --}}

                    <label for="id_animal" class="form-label">Seleccione un animal <span style="color: red;">*</span></label>
                    <input type="hidden" id="id_animal" name="id_animal" required>
                    <div class="input-dinamico-animal">
                        <div id="animalSeleccionado"></div>
                        <button type="button" class="buton-dinamico-animal" data-popup="{{ $nombre ?? 'id_animal' }}">                            <span class="material-symbols-outlined">search</span>
                          </button>
                    </div>
                </div>
                @include('components.selector-animales', ['predios' => $predios, 'animales' => $animales])



                <div class="form-group">
                    <label for="fecha_muerte">Fecha de Muerte</label>
                    <input type="date" name="fecha_muerte" id="fecha_muerte" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" class="form-control" rows="4" placeholder="Opcional"></textarea>
                </div>
                <br>
                <button type="button" class="btn btn-primary" id="open-modal-btn">Registrar</button>
            </form>

            @section('modal')
            <!-- Modal de confirmación -->
            <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-modal-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirm-modal-label">Confirmación de Acción</h5>
                            <button style="    border: none;
    background: white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="modal-message"></p>
                            <p><strong>Advertencia:</strong> Marcar este animal como muerto es irreversible. Después de realizar esta acción, no podrás registrar más eventos para él.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" id="confirm-btn">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
            <hr>

            <h3>Historial de muertes</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Nombre</th>
                        <th>Predio</th>
                        <th>Fecha</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($muertes as $muerte)
                        <tr>
                            <td>{{ $muerte->animal->codigo }}</td>
                            <td>{{ $muerte->animal->nombre }}</td>
                            <td>{{ $muerte->animal->predio->nombre_predio }}</td>
                            <td>{{ $muerte->fecha_muerte }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

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
    const form = document.getElementById('muerte-form');
const modal = $('#confirm-modal');
const openModalBtn = document.getElementById('open-modal-btn');
const confirmBtn = document.getElementById('confirm-btn');
const animalSelect = document.getElementById('id_animal');

openModalBtn.addEventListener('click', function() {
    const animalNombre = document.getElementById('animalSeleccionado').textContent;

    if (!animalNombre) {
        alert('Debes seleccionar un animal antes de registrar la muerte.');
        return;
    }

    const message = `¿Estás seguro de que deseas registrar la muerte del animal "${animalNombre}"? Esta acción es irreversible.`;
    document.getElementById('modal-message').textContent = message;
    modal.modal('show');
});

confirmBtn.addEventListener('click', function() {
    form.submit();
});

        </script>

        <!-- Incluye los archivos de Bootstrap si no están incluidos ya -->
   {{--  --}}

@endsection
