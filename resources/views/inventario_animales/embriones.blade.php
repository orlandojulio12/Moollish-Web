@extends('layouts')

@section('title')
    Registro de embriones
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

    <div class="card-custom mb-4 p-4">
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

                    <h3 class="cumb active-tab"> Embriones </h3>
                </div>
                <hr>

            </div>
            <form action="{{ route('embriones.store') }}" method="POST" id="formEmbriones">
                @csrf

                <div class="row">
                    <!-- Fecha de Entrada -->
                    <div class="col-md-4 mb-3">
                        <label for="fecha_entrada">Fecha de Entrada</label>
                        <input type="date" class="form-control" id="fecha_entrada" name="fecha_entrada" required>
                    </div>

                    <!-- Seleccionar Predio -->
                    <div class="col-md-4 mb-3">
                        <label for="id_predio">Predio</label>
                        <select class="form-control" id="id_predio" name="id_predio" required>
                            <option value="">-- Seleccione un predio --</option>
                            @if (isset($predios))
                                @foreach ($predios as $predio)
                                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Código del Embrión -->
                    <div class="col-md-4 mb-3">
                        <label for="codigo_embrion">Código de Embrión</label>
                        <input type="text" class="form-control" id="codigo_embrion" name="codigo_embrion" required>
                    </div>
                </div>
                <div class="row">
                    <!-- Nombre del Reproductor -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre_reproductor">Nombre del Reproductor</label>
                        <input type="text" class="form-control" id="nombre_reproductor" name="nombre_reproductor"
                            required>
                    </div>

                    <!-- Raza del Reproductor -->
                    <div class="col-md-6 mb-3">
                        <label for="raza_reproductor">Raza del Reproductor</label>
                        <input type="text" class="form-control" id="raza_reproductor" name="raza_reproductor" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Vaca Donadora -->
                    <div class="col-md-6 mb-3">
                        <label for="vaca_donadora">Vaca Donadora</label>
                        <input type="text" class="form-control" id="vaca_donadora" name="vaca_donadora" required>
                    </div>

                    <!-- Raza de la Vaca -->
                    <div class="col-md-6 mb-3">
                        <label for="raza_vaca">Raza de la Vaca</label>
                        <input type="text" class="form-control" id="raza_vaca" name="raza_vaca" required>
                    </div>
                </div>

                <hr>
                <h3>Información de venta</h3>
                <!-- Vendedor -->
                <div class="col-md-4 mb-3">
                    <label for="vendedor">Vendedor</label>
                    <input type="text" class="form-control" id="vendedor" name="vendedor" required>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="costo_unidad">Costo por unidad</label>
                        <input type="number" step="0.01" class="form-control" id="costo_unidad" name="costo_unidad"
                            required>
                    </div>

                    <!-- Cantidad -->
                    <div class="col-md-4 mb-3">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                    </div>

                    <!-- Valor Total (calculado) -->
                    <div class="col-md-4 mb-3">
                        <label for="valor_total">Valor Total</label>
                        <input type="number" step="0.01" class="form-control" id="valor_total" name="valor_total"
                            readonly>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <!-- En lugar de enviar directamente, se mostrará el modal de confirmación -->
                        <button type="button" id="confirmSubmit" class="btn btn-primary">Registrar Embriones</button>
                    </div>
                </div>
            </form>
        </div>



    </div>



    <div class="card-custom p-4">
    <h3>Histórico de embriones</h3>
    <table class="table table-bordered table-striped" id="proposalsList"> {{-- <-- con "s" --}}
        <thead>
            <tr>
                <th>Predio</th>
                <th>Código de Embrión</th>
                <th>Nombre del Reproductor</th>
                <th>Raza del Reproductor</th>
                <th>Vaca Donadora</th>
                <th>Raza de la Vaca</th>
                <th>Vendedor</th>
                <th>Costo por Unidad</th>
                <th>Fecha de Entrada</th>
                <th>Cantidad</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($embriones) && $embriones->count() > 0)
                @foreach ($embriones as $embrion)
                    <tr>
                        <td>{{ $embrion->predio->nombre_predio ?? 'Sin predio' }}</td>
                        <td>{{ $embrion->codigo_embrion ?? '-' }}</td>
                        <td>{{ $embrion->nombre_reproductor ?? '-' }}</td>
                        <td>{{ $embrion->raza_reproductor ?? '-' }}</td>
                        <td>{{ $embrion->vaca_donadora ?? '-' }}</td>
                        <td>{{ $embrion->raza_vaca ?? '-' }}</td>
                        <td>{{ $embrion->vendedor ?? '-' }}</td>
                        <td>{{ $embrion->costo_unidad ?? 0 }}</td>
                        <td>{{ $embrion->fecha_entrada ?? '-' }}</td>
                        <td>{{ $embrion->cantidad ?? 0 }}</td>
                        <td>{{ $embrion->valor_total ?? 0 }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
            <td colspan="11" class="text-center">No se encontraron registros.</td>
        </tr>
        </tbody>
    </table>
</div>



@endsection

@section('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Función para calcular el valor total
            function calcularValorTotal() {
                var cantidad = parseFloat($('#cantidad').val()) || 0;
                var costo = parseFloat($('#costo_unidad').val()) || 0;
                var total = cantidad * costo;
                $('#valor_total').val(total.toFixed(2));
            }

            // Actualizar el total al cambiar cantidad o costo
            $('#cantidad, #costo_unidad').on('input', function() {
                calcularValorTotal();
            });

            // Modal de confirmación antes de enviar el formulario
            $('#confirmSubmit').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se registrarán los embriones con los datos ingresados.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#formEmbriones').submit();
                    }
                });
            });
        });
    </script>


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
