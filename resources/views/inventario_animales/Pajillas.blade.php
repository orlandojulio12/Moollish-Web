@extends('layouts')

@section('title')
    Pajillas
@endsection

@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
    background: white;
    padding: 30px;
    border: 1px solid #eaebef;
        }

        .two-column {
            width: 49%;
        }

        .three-column {
            width: 32%;
        }

        .sumar {
            display: flex;
            border: 1px solid green;
            width: 50px;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            color: green;
            cursor: pointer;
        }

        .restar {
            display: flex;
            border: 1px solid rgb(128, 0, 28);
            width: 50px;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            color: rgb(128, 0, 21);
            cursor: pointer;
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
        <div class="">
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

                        <h3 class="cumb active-tab">Pajillas </h3>
                </div>
                <hr>

            </div>
            <!-- Formulario para registrar una pajilla -->
            <form action="{{ route('pajillas.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="id_predio">Predio</label>
                    <select name="id_predio" id="id_predio" class="form-select">
                        @foreach ($predios as $predio)
                            <option value="{{ $predio->id }}">
                                {{ $predio->id }} - {{ $predio->nombre_predio }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="form-group">
                    <label for="codigo">Código de la pajilla</label>
                    <input type="text" id="codigo" name="codigo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="nombre_reproductor_create">Nombre del reproductor</label>
                    <input type="text" id="nombre_reproductor_create" name="nombre_reproductor" class="form-control"
                        required>
                </div>
                <div class="form-group">
                    <label for="raza">Raza</label>
                    <input type="text" id="raza" name="raza" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="costo_unidad">Costo por unidad</label>
                    <input type="number" id="costo_unidad" name="costo_unidad" class="form-control" min="0"
                        step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label for="valor_total">Valor total</label>
                    <input type="number" id="valor_total" name="valor_total" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="fecha_entrada">Fecha de entrada</label>
                    <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="vendedor">Vendedor</label>
                    <input type="text" id="vendedor" name="vendedor" class="form-control" required>
                </div>
                <hr>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla para listar pajillas -->
    <div class="card-custom mt-4">
        <h4>Lista de Pajillas</h4>
        <hr>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Macho Reproductor</th>
                    <th>Código</th>
                    <th>Fecha de Entrada</th>
                    <th>Cantidad Disponible</th>
                    <th>Ajuste</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pajillas as $pajilla)
                    <tr>
                        <td>{{ $pajilla->nombre_reproductor }}</td>
                        <td>{{ $pajilla->codigo_pajilla }}</td>
                        <td>{{ $pajilla->fecha_entrada }}</td>
                        <td>{{ $pajilla->cantidad }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ajusteModal"
                                data-id="{{ $pajilla->id }}" data-cantidad="{{ $pajilla->cantidad }}"
                                data-nombre="{{ $pajilla->nombre_reproductor }}">
                                Ajustar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection


@section('modal')
    <!-- Modal para ajustar pajillas -->
    <div class="modal fade" id="ajusteModal" tabindex="-1" aria-labelledby="ajusteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="ajusteForm" method="POST" action="{{ route('pajillas.ajuste') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="ajusteModalLabel">Ajustar Pajilla</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_pajilla" id="id_pajilla">
                        <div class="mb-3">
                            <label for="nombre_reproductor" class="form-label">Nombre del Reproductor</label>
                            <input type="text" class="form-control" id="nombre_reproductor" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad_disponible" class="form-label">Cantidad Disponible</label>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-outline-secondary restar">-</button>
                                <input
                                    style="margin: 0 10px; text-align: center; max-width: 100px;"
                                    type="number"
                                    class="form-control"
                                    id="cantidad_disponible"
                                    name="cantidad"
                                    value="0"
                                    min="0"
                                    required
                                >
                                <button type="button" class="btn btn-outline-secondary sumar">+</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="motivo" class="form-label">Motivo</label>
                            <select class="form-control" id="motivo" name="motivo" required>
                                <option value="">Seleccione un motivo</option>
                                <option value="Entrada">Entrada</option>
                                <option value="Salida">Salida</option>
                                <option value="Corrección">Corrección</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea class="form-control" id="observacion" name="observacion" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Ajuste</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>

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
    <script>
   document.addEventListener('DOMContentLoaded', function () {
    const restarButtons = document.querySelectorAll('.restar');
    const sumarButtons = document.querySelectorAll('.sumar');

    restarButtons.forEach(button => {
        button.addEventListener('click', function () {
            const input = button.nextElementSibling;
            let currentValue = parseInt(input.value, 10) || 0;
            if (currentValue > 0) {
                input.value = currentValue - 1;
            }
        });
    });

    sumarButtons.forEach(button => {
        button.addEventListener('click', function () {
            const input = button.previousElementSibling;
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;
        });
    });
});

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ajusteModal = document.getElementById('ajusteModal');

            ajusteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const idPajilla = button.getAttribute('data-id');
                const cantidadDisponible = button.getAttribute('data-cantidad');
                const nombreReproductor = button.getAttribute('data-nombre');

                // Rellenar los campos del modal
                document.getElementById('id_pajilla').value = idPajilla;
                document.getElementById('cantidad_disponible').value = cantidadDisponible;
                document.getElementById('nombre_reproductor').value = nombreReproductor;
            });
        });
    </script>
    <!-- Script para calcular el valor total -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const costoUnidadInput = document.getElementById('costo_unidad');
            const cantidadInput = document.getElementById('cantidad');
            const valorTotalInput = document.getElementById('valor_total');

            function calcularValorTotal() {
                const costoUnidad = parseFloat(costoUnidadInput.value) || 0;
                const cantidad = parseInt(cantidadInput.value) || 0;
                valorTotalInput.value = (costoUnidad * cantidad).toFixed(2);
            }

            costoUnidadInput.addEventListener('input', calcularValorTotal);
            cantidadInput.addEventListener('input', calcularValorTotal);
        });
    </script>
@endsection
