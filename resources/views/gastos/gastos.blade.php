@extends('layouts')

@section('title')
    Predios
@endsection

@section('styles')
    <style>
        /* Estilos generales */
        .dashboard-container {
            padding: 0px;
        }

        .dashboard-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        /* Estilos para el resumen financiero */
        .financial-summary {
            background: #FFF8F0;
            border: 1px solid #E49B39;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .balance-amount {
            font-size: 24px;
            font-weight: 700;
            font-family: monospace;
        }

        .balance-positive {
            color: #28a745;
        }

        .balance-negative {
            color: #dc3545;
        }

        /* Estilos para la tabla */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .custom-table th {
            background-color: #F9FAFB;
            color: #4B5563;
            font-weight: 600;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 2px solid #E49B39;
        }

        .custom-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #E5E7EB;
            color: #111827;
        }

        .custom-table tr:hover {
            background-color: #FFF8F0;
        }

        /* Estilos para montos */
        .amount-cell {
            font-family: monospace;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .amount-positive {
            color: #28a745;
        }

        .amount-negative {
            color: #dc3545;
        }

        /* Estilos para botones */
        .btn-action {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: #E49B39;
            border-color: #E49B39;
            color: white;
        }

        .btn-primary:hover {
            background-color: #C97917;
            border-color: #C97917;
            transform: translateY(-1px);
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        /* Estilos para modales */
        .custom-modal .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .custom-modal .modal-header {
            background-color: #FFF8F0;
            border-bottom: 1px solid #FFE8CC;
            padding: 20px 24px;
        }

        .custom-modal .modal-body {
            padding: 24px;
        }

        .custom-modal .modal-footer {
            border-top: 1px solid #E5E7EB;
            padding: 16px 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #4B5563;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #E49B39;
            box-shadow: 0 0 0 3px rgba(228, 155, 57, 0.1);
            outline: none;
        }

        /* Animaciones para el modal */
        @keyframes modalZoomIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes modalZoomOut {
            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: 0;
                transform: scale(0.95);
            }
        }

        .modal.show .modal-dialog {
            animation: modalZoomIn 0.3s ease-out;
        }

        .modal.hiding .modal-dialog {
            animation: modalZoomOut 0.2s ease-in;
        }

        /* Estilos para el filtro de fechas */
        .date-filter-container {
            display: flex;
            gap: 16px;
            align-items: center;
            margin-bottom: 20px;
        }

        .date-filter-container .form-group {
            margin-bottom: 0;
            flex: 1;
        }

        .bread {
            font-size: 28px;
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
            color: #E49B39;
        }

        .no-active-tab:hover {
            color: #E49B39;
            cursor: pointer;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 16px;
            }

            .financial-summary {
                padding: 16px;
            }

            .balance-amount {
                font-size: 20px;
            }

            .custom-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
@endsection

{{-- @section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('gastos.show') }}">Plan de cuentas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lista de Gastos</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection --}}

@section('content')
    <style>
        #content-container:before {
            content: '';
            display: block;
            height: 165px;
            width: 100%;
            position: absolute;
            background-color: #C29F77 !important;
            z-index: 0;
        }

        @media (max-width: 576px) {
            #tabla-Predios_filter div {
                margin-top: 10px;
            }
        }
    </style>

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



    <div class="main-content">
        <div class="header-grid">
            <div class="breadcrumb">
                <a href="{{ route('inicio') }}">
                    <h3 class="cumb no-active-tab">
                        Inicio
                    </h3>
                </a>
                {{--     <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <a href="{{ route('economia') }}">
                        <h3 class="cumb no-active-tab">
                            Economía
                        </h3>
                    </a> --}}
                <span class="material-symbols-outlined bread">
                    chevron_forward
                </span>
                <a href="{{ route('gastos.show') }}">
                    <h3 class="cumb no-active-tab">
                        Movimientos
                    </h3>
                </a>
                <span class="material-symbols-outlined bread">
                    chevron_forward
                </span>
                <h3 class="cumb active-tab">Lista de Gastos</h3>
            </div>
            <hr>
        </div>
        <div class="row">
            <div class="col-lg-16">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">

                            @php
                                $saldo = 0; // Inicializar la variable saldo
                            @endphp

                            @foreach ($gastos as $gasto)
                                @if ($gasto->planCuenta->naturaleza === 'Ingresos' || $gasto->planCuenta->naturaleza === 'Activos')
                                    @php
                                        $saldo += abs($gasto->cantidad); // Sumar si es un ingreso o activo
                                    @endphp
                                @else
                                    @php
                                        $saldo -= abs($gasto->cantidad); // Restar si es un gasto
                                    @endphp
                                @endif
                            @endforeach

                            <!-- Contenedor del saldo con color dinámico solo para el icono y número -->
                            <h3 class="me-auto" style="width: 50%;">
                                total de gastos:
                                <i class="bi bi-currency-dollar"
                                    style="font-size: 24px; color: {{ $saldo < 0 ? 'red' : '' }};"></i>
                                <span style="color: {{ $saldo < 0 ? 'red' : '' }};">
                                    {{ number_format($saldo, 2, '.', ',') }}
                                </span>
                            </h3>
                        </div>

                        <div class="table-responsive">
                            {{-- <div class="row">
                                <div class="col-md-3">
                                    <label for="min">Fecha desde:</label>
                                    <input type="date" id="min" name="min" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="max">Fecha hasta:</label>
                                    <input type="date" id="max" name="max" class="form-control">
                                </div>
                            </div> --}}

                            <table id="tabla-Predios" class="table table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Monto</th>
                                        <th>Nombre predios</th>
                                        <th>Fecha</th>
                                        <th>Descripcion</th>
                                        <th>Naturaleza</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gastos as $gasto)
                                        <tr>
                                            <td>{{ $gasto->planCuenta->nomcta }}</td> <!-- Muestra el nombre del gasto -->
                                            <td
                                                style="display: flex; color: {{ $gasto->planCuenta->naturaleza === 'Ingresos' || $gasto->planCuenta->naturaleza === 'Activos' ? 'green' : 'red' }};">
                                                <i class="bi bi-currency-dollar"></i>
                                                <h5
                                                    style="color: {{ $gasto->planCuenta->naturaleza === 'Ingresos' || $gasto->planCuenta->naturaleza === 'Activos' ? 'green' : 'red' }};">
                                                    {{ number_format($gasto->cantidad, 2, '.', ',') }}
                                                </h5>
                                            </td>
                                            <!-- Muestra la cantidad -->
                                            <td>{{ $gasto->predio->nombre_predio }}</td>
                                            <td>{{ $gasto->fecha }}</td>
                                            <td>{{ $gasto->descripcion }}</td>
                                            <td>{{ $gasto->planCuenta->naturaleza }}</td>
                                            <!-- Muestra el nombre del gasto -->

                                            <td>
                                                <form id="formDelete{{ $gasto->id }}"
                                                    action="{{ route('gastos.destroy', $gasto->id) }}" method="POST"
                                                    style="display: flex; gap: 2px;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-sm btn-warning" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#editAreaModal{{ $gasto->id }}">
                                                        <i class="fa fa-fw fa-edit"></i> <!-- Tamaño en píxeles -->
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete({{ $gasto->id }})">
                                                        <i class="fa fa-fw fa-trash"></i> <!-- Tamaño en píxeles -->
                                                    </button>
                                                </form>
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




    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e49b39',
                cancelButtonColor: '#0000005e',
                confirmButtonText: 'Sí, eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formDelete' + id).submit();
                }
            })
        }
    </script>
@endsection

@section('modal')
    {{-- inicio de modales  --}}

    @foreach ($gastos as $gasto)
        <div class="modal fade" id="editAreaModal{{ $gasto->id }}" tabindex="-1" aria-labelledby="editAreaModalLabel"
            aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAreaModalLabel">Editar Movimiento:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('gastos.update', $gasto->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Cambiamos el método a PUT para la actualización -->

                        <div class="modal-body">
                            <input type="hidden" name="usuario_id" value="{{ $gasto->usuario_id }}">
                            <input type="hidden" name="id_predio" value="{{ $gasto->id_predio }}">

                            <!-- Select para Clase (Naturaleza) -->
                            <div class="form-group">
                                <label for="naturaleza">Seleccione clase:</label>
                                <select name="naturaleza" id="naturaleza-update-{{ $gasto->id }}"
                                    class="form-control naturaleza-update" data-id="{{ $gasto->id }}">
                                    <option value="">Seleccione una clase</option>
                                    <option value="Gastos"
                                        {{ $gasto->planCuenta->naturaleza == 'Gastos' ? 'selected' : '' }}>Gastos</option>
                                </select>
                            </div>

                            <!-- Select para Categorías Principales -->
                            <div class="form-group">
                                <label for="categoria">Seleccione categoría:</label>
                                <select name="categoria" id="categoria-update-{{ $gasto->id }}" class="form-control"
                                    data-selected-categoria="{{ substr($gasto->planCuenta->codcta, 0, 6) }}">
                                    <option value="">Seleccione una categoría</option>
                                </select>
                            </div>

                            <!-- Select para Subcuenta -->
                            <div class="form-group">
                                <label for="subcuenta">Seleccione sub cuenta:</label>
                                <select name="plan_cuenta" id="subcuenta-update-{{ $gasto->id }}" class="form-control"
                                    data-selected-id="{{ $gasto->plan_cuenta }}">
                                    <option value="">Seleccione una subcuenta</option>
                                </select>
                            </div>

                            <!-- Campo Monto -->
                            <div class="form-group">
                                <label for="cantidad">Monto:</label>
                                <input type="text" id="gasto-{{ $gasto->id }}" class="form-control"
                                    placeholder="Ej: 1,234.56" value="{{ number_format($gasto->cantidad, 2, '.', ',') }}"
                                    required>

                                <!-- Campo oculto para enviar el valor real -->
                                <input type="hidden" name="cantidad" id="gastoReal-{{ $gasto->id }}"
                                    value="{{ $gasto->cantidad }}">
                            </div>

                            <!-- Campo Concepto -->
                            <div class="form-group">
                                <label for="cantidad">Concepto:</label>
                                <input type="text" name="descripcion" id="descripcion-{{ $gasto->id }}"
                                    class="form-control" placeholder="Ej: Pago de alquiler"
                                    value="{{ $gasto->descripcion }}" required>
                            </div>

                            <!-- Campo Fecha -->
                            <div class="form-group">
                                <label for="fecha">Fecha</label>
                                <input type="date" name="fecha" id="fecha-{{ $gasto->id }}"
                                    class="form-control" value="{{ $gasto->fecha }}" required>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Obtener todos los selectores de naturaleza en los formularios de actualización
                const naturalezaUpdateSelects = document.querySelectorAll('.naturaleza-update');
                const categoriasPrincipales = @json($categoriasPrincipales);
                const subcuentas = @json($subcuentasDetalles);

                naturalezaUpdateSelects.forEach(naturalezaUpdateSelect => {
                    // Obtener el ID único del gasto desde el atributo data-id
                    const gastoId = naturalezaUpdateSelect.getAttribute('data-id');
                    const categoriaUpdateSelect = document.getElementById(`categoria-update-${gastoId}`);
                    const subcuentaUpdateSelect = document.getElementById(`subcuenta-update-${gastoId}`);
                    const selectedSubcuentaId = subcuentaUpdateSelect.getAttribute('data-selected-id');
                    const selectedCategoriaCodcta = categoriaUpdateSelect.getAttribute(
                        'data-selected-categoria');

                    // Función para llenar las categorías principales según la naturaleza seleccionada
                    function fillCategoriasUpdate(selectedNaturaleza) {
                        // Limpiar el select de categoría y subcuenta
                        categoriaUpdateSelect.innerHTML = ''; // Vaciar antes de agregar la opción por defecto
                        subcuentaUpdateSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';

                        // Agregar la opción por defecto para la categoría
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.text = 'Seleccione una categoría';
                        categoriaUpdateSelect.appendChild(defaultOption);

                        // Filtrar las categorías principales según la naturaleza seleccionada
                        const categoriasFiltradas = categoriasPrincipales.filter(categoria => {
                            const hasSubcuentas = subcuentas.some(subcuenta => subcuenta.codcta
                                .startsWith(categoria.codcta) && subcuenta.naturaleza ===
                                selectedNaturaleza);
                            return categoria.naturaleza === selectedNaturaleza && hasSubcuentas;
                        });

                        // Llenar el select de categoría con las opciones filtradas
                        categoriasFiltradas.forEach(categoria => {
                            const option = document.createElement('option');
                            option.value = categoria.codcta;
                            option.text = categoria.nomcta;

                            categoriaUpdateSelect.appendChild(option);
                        });

                        // Seleccionar la categoría actual si coincide
                        if (selectedCategoriaCodcta) {
                            categoriaUpdateSelect.value = selectedCategoriaCodcta;
                        }
                    }

                    // Función para llenar las subcuentas según la categoría seleccionada
                    function fillSubcuentasUpdate(selectedCategoria, selectedNaturaleza) {
                        // Limpiar el select de subcuenta
                        subcuentaUpdateSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';

                        // Filtrar las subcuentas relacionadas con la categoría y naturaleza seleccionadas
                        const subcuentasFiltradas = subcuentas.filter(subcuenta => subcuenta.codcta.startsWith(
                            selectedCategoria) && subcuenta.naturaleza === selectedNaturaleza);

                        // Llenar el select de subcuenta con las opciones filtradas
                        subcuentasFiltradas.forEach(subcuenta => {
                            const option = document.createElement('option');
                            option.value = subcuenta.id;
                            option.text = subcuenta.nomcta;

                            // Seleccionar la subcuenta actual si coincide
                            if (subcuenta.id == selectedSubcuentaId) {
                                option.selected = true;
                            }

                            subcuentaUpdateSelect.appendChild(option);
                        });
                    }

                    // Llenar las categorías y subcuentas al cargar la página para cada formulario de actualización
                    fillCategoriasUpdate(naturalezaUpdateSelect.value);
                    fillSubcuentasUpdate(selectedCategoriaCodcta, naturalezaUpdateSelect.value);

                    // Evento al cambiar la naturaleza en cada formulario de actualización
                    naturalezaUpdateSelect.addEventListener('change', function() {
                        fillCategoriasUpdate(this.value);
                        categoriaUpdateSelect.value = ''; // Resetear la selección de categoría
                    });

                    // Evento al cambiar la categoría en cada formulario de actualización
                    categoriaUpdateSelect.addEventListener('change', function() {
                        fillSubcuentasUpdate(this.value, naturalezaUpdateSelect.value);
                    });
                });
            });
        </script>
    @endforeach




    <!-- Modal para seleccionar las fechas -->
    <div class="modal fade" id="dateFilterModal" tabindex="-1" role="dialog" aria-labelledby="dateFilterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dateFilterModalLabel">Filtrar por fechas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="min">Fecha desde:</label>
                        <input type="date" id="min" name="min" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="max">Fecha hasta:</label>
                        <input type="date" id="max" name="max" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="applyDateFilter">Aplicar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Seleccionar los elementos
        const gastoInput = document.getElementById('gasto');
        const gastoRealInput = document.getElementById('gastoReal');

        // Función para formatear el número en sistema decimal
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Evento al escribir en el input
        gastoInput.addEventListener('input', function(event) {
            // Obtener el valor sin comas
            let rawValue = event.target.value.replace(/,/g, '');

            // Verificar si el valor es un número
            if (!isNaN(rawValue) && rawValue !== '') {
                // Guardar la posición del cursor
                const cursorPosition = event.target.selectionStart;

                // Formatear el valor
                const formattedValue = formatNumber(rawValue);

                // Mostrar el valor formateado en el input
                event.target.value = formattedValue;

                // Guardar el valor sin formato en el campo oculto
                gastoRealInput.value = rawValue;

                // Restaurar la posición del cursor
                const newCursorPosition = cursorPosition + (formattedValue.length - rawValue.length);
                event.target.setSelectionRange(newCursorPosition, newCursorPosition);
            } else {
                // Si el valor no es válido, limpiar ambos inputs
                event.target.value = '';
                gastoRealInput.value = '';
            }
        });
    </script>
    <script>
        // Obtener los selectores
        const naturalezaSelect = document.getElementById('naturaleza');
        const categoriaSelect = document.getElementById('categoria');
        const subcuentaSelect = document.getElementById('subcuenta');

        // Obtener todas las categorías principales y subcuentas desde el backend
        const categoriasPrincipales = @json($categoriasPrincipales);
        const subcuentas = @json($subcuentasDetalles);

        // Evento para cambiar las categorías según la naturaleza seleccionada
        naturalezaSelect.addEventListener('change', function() {
            const selectedNaturaleza = this.value;

            // Limpiar el select de categoría y subcuenta
            categoriaSelect.innerHTML = '<option value="">Seleccione una categoría</option>';
            subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';

            // Filtrar las categorías principales según la naturaleza seleccionada
            const categoriasFiltradas = categoriasPrincipales.filter(categoria => {
                // Verificar si la categoría tiene subcuentas relacionadas
                const hasSubcuentas = subcuentas.some(subcuenta => subcuenta.codcta.startsWith(categoria
                    .codcta) && subcuenta.naturaleza === selectedNaturaleza);
                return categoria.naturaleza === selectedNaturaleza && hasSubcuentas;
            });

            // Llenar el select de categoría con las opciones filtradas
            categoriasFiltradas.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.codcta;
                option.text = categoria.nomcta;
                categoriaSelect.appendChild(option);
            });
        });

        // Evento para cambiar las subcuentas según la categoría seleccionada
        categoriaSelect.addEventListener('change', function() {
            const selectedCategoria = this.value;

            // Limpiar el select de subcuenta
            subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';

            // Filtrar las subcuentas relacionadas con la categoría seleccionada y naturaleza seleccionada
            const subcuentasFiltradas = subcuentas.filter(subcuenta => subcuenta.codcta.startsWith(
                selectedCategoria) && subcuenta.naturaleza === naturalezaSelect.value);

            // Llenar el select de subcuenta con las opciones filtradas
            subcuentasFiltradas.forEach(subcuenta => {
                const option = document.createElement('option');
                option.value = subcuenta.id; // Puedes usar subcuenta.id si prefieres guardar el ID
                option.text = subcuenta.nomcta;
                subcuentaSelect.appendChild(option);
            });
        });
    </script>



    <style>
        @keyframes zoomIn {
            0% {
                opacity: 0;
                transform: scale(0);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes zoomOut {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: scale(0);
            }
        }

        .modal-dialog {
            transition: transform 0.4s ease-in-out;
        }

        .modal-content {
            background: white;
            border-radius: 10px;
            /* Estilo iOS */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>


    <!-- Script para manejar los selects dependientes -->
@endsection


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            // Inicializar DataTable
            var table = $('#tabla-Predios').DataTable({
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


    <script>
        $(document).ready(function() {
            console.log('Script de DataTables ejecutado');

            // Verificar si el elemento existe
            if ($('#tabla-Predios').length) {
                console.log('Elemento #tabla-Predios encontrado en el DOM');

                // Destruir la instancia de DataTable si ya está inicializada
                if ($.fn.DataTable.isDataTable('#tabla-Predios')) {
                    console.log('Destruyendo instancia existente de DataTable');
                    $('#tabla-Predios').DataTable().destroy(); // Solo destruir, no usar clear()
                }

                console.log('Inicializando DataTable');
                var table = $('#tabla-Predios').DataTable();

                // Colocar el botón de filtro de fechas
                $("#tabla-Predios_filter").append(`
            <div style="display: inline-block; margin-left: 20px; margin-top: 10px;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#dateFilterModal" style="margin-left: 10px;">
                    Filtrar por fechas
                </button>
            </div>
        `);
                console.log('Botón de filtro de fechas agregado');

                // Lógica de filtrado por fecha
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var min = $('#min').val(); // Fecha desde
                        var max = $('#max').val(); // Fecha hasta
                        var date = data[3]; // Columna de la fecha (índice 3)

                        // Convertir las fechas a formato comparable
                        if (min) {
                            min = new Date(min).getTime();
                        }
                        if (max) {
                            max = new Date(max).getTime();
                        }
                        var fechaGasto = new Date(date).getTime();

                        // Filtrar según el rango de fechas
                        if (
                            (!min && !max) || // No hay fechas seleccionadas
                            (!min && fechaGasto <= max) || // Solo fecha hasta seleccionada
                            (!max && fechaGasto >= min) || // Solo fecha desde seleccionada
                            (fechaGasto >= min && fechaGasto <= max) // Rango de fechas válido
                        ) {
                            return true;
                        }
                        return false;
                    }
                );

                // Evento para aplicar el filtro al hacer clic en "Aplicar" en el modal
                $('#applyDateFilter').on('click', function() {
                    $('#dateFilterModal').modal('hide'); // Cerrar el modal
                    table.draw(); // Redibujar la tabla con los filtros aplicados
                });

                // Evento para abrir el modal cuando se hace clic en el botón de filtro de fechas
                $('#btnFilterDates').on('click', function() {
                    $('#dateFilterModal').modal('show'); // Abrir el modal
                });
            } else {
                console.log('Elemento #tabla-Predios NO encontrado en el DOM');
            }
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Configuración inicial del modal
            $('.btn-primary, .btn-warning').click(function(event) {
                var button = $(this);
                var modalId = button.data('bs-target');
                var modal = $(modalId);
                var modalDialog = modal.find('.modal-dialog');

                // Calcula el centro del botón
                var buttonOffset = button.offset();
                var buttonCenterX = buttonOffset.left + button.outerWidth() / 2 - $(window).scrollLeft();
                var buttonCenterY = buttonOffset.top + button.outerHeight() / 2 - $(window).scrollTop();

                // Aplica el origen de la transformación
                modalDialog.css({
                    'transform-origin': buttonCenterX + 'px ' + buttonCenterY + 'px'
                });

                // Establece la animación de apertura
                modalDialog.css('animation', 'zoomIn 0.2s forwards');
            });

            // Maneja el cierre del modal
            $('.modal').on('hide.bs.modal', function() {
                var modalDialog = $(this).find('.modal-dialog');
                modalDialog.css('animation', 'zoomOut 0.4s forwards');
            });
        });
    </script>



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
    {{-- Efectos de modales  --}}
@endsection
