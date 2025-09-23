@extends('layouts')

@section('title')
    Movimientos Económicos del Predio
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

@section('content')
    <div class="dashboard-container">
        <!-- Resumen financiero -->
         <div class="financial-summary mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-2">Saldo disponible</h3>
                        <div
                            class="balance-amount {{ isset($saldo) && $saldo < 0 ? 'balance-negative' : 'balance-positive' }}">
                            <i class="bi bi-currency-dollar"></i>
                            {{ isset($saldo) ? number_format($saldo, 2, '.', ',') : '0.00' }}
                        </div>
                        
                    </div>
                    {{-- <div class="d-flex gap-3">
                        <a href="{{ route('movimientos.showExporte', $Predios) }}" class="btn btn-action btn-primary">
                            <i class="bi bi-file-excel"></i>
                            Exportar Excel
                        </a>
                        <button class="btn btn-action btn-primary" data-bs-toggle="modal" data-bs-target="#editAreaModal">
                            <i class="bi bi-plus-circle"></i>
                            Crear Movimiento
                        </button>
                    </div> --}}
                </div>
            </div>

    <!-- Botón crear movimiento -->
    <button class="btn btn-action btn-primary" data-bs-toggle="modal" data-bs-target="#editAreaModal">
        <i class="bi bi-plus-circle"></i>
        Crear Movimiento
    </button>
</div>

            </div>
                        </div>

        <!-- Contenedor principal -->
        <div class="dashboard-card">
            <!-- Breadcrumb y título -->
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
                    <h3 class="cumb active-tab">Detalle de Movimientos</h3>
                                </div>
                <hr>
                                </div>

            <!-- Título y descripción -->
            <div class="card-header-container mb-4">
                <div>
                    <h2 class="card-title">Gestión de Movimientos Económicos</h2>
                    <p class="card-subtitle">Administre los movimientos económicos del predio, incluyendo ingresos, gastos y
                        costos. Visualice el saldo disponible y realice un seguimiento detallado de todas las transacciones.
                    </p>
                </div>
            </div>

            <!-- Tabla de movimientos -->
            <div class="table-responsive">
                <table id="tabla-Predios" class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Monto</th>
                            <th>Predio</th>
                                        <th>Fecha</th>
                            <th>Descripción</th>
                                        <th>Naturaleza</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gastos as $gasto)
                                        <tr>
                                <td>{{ $gasto->planCuenta->nomcta }}</td>
                                <td>
                                    <div
                                        class="amount-cell {{ $gasto->planCuenta->naturaleza === 'Ingresos' || $gasto->planCuenta->naturaleza === 'Activos' ? 'amount-positive' : 'amount-negative' }}">
                                                <i class="bi bi-currency-dollar"></i>
                                        {{ number_format($gasto->cantidad, 2, '.', ',') }}
                                    </div>
                                            </td>
                                            <td>{{ $gasto->predio->nombre_predio }}</td>
                                            <td>{{ $gasto->fecha }}</td>
                                            <td>{{ $gasto->descripcion }}</td>
                                            <td>{{ $gasto->planCuenta->naturaleza }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-icon btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editAreaModal{{ $gasto->id }}" title="Editar">
                                            <i class="fa fa-fw fa-edit"></i>
                                        </button>
                                        <button class="btn btn-icon btn-danger"
                                            onclick="confirmDelete({{ $gasto->id }})" title="Eliminar">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </button>
                                    </div>
                                                <form id="formDelete{{ $gasto->id }}"
                                        action="{{ route('gastos.destroy', $gasto->id) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
            </div>
        </div>
    </div>

    <!-- Modal de creación -->


    <!-- Modales de edición -->


    <!-- Modal de filtro por fechas -->
    <div class="modal fade custom-modal" id="dateFilterModal" tabindex="-1" aria-labelledby="dateFilterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dateFilterModalLabel">
                        <i class="bi bi-calendar-range me-2"></i>
                        Filtrar por fechas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="date-filter-container">
                        <div class="form-group">
                            <label for="min">Fecha desde</label>
                            <input type="date" id="min" name="min" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="max">Fecha hasta</label>
                            <input type="date" id="max" name="max" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="applyDateFilter">
                        <i class="bi bi-funnel me-2"></i>
                        Aplicar filtro
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
<div class="modal fade custom-modal" id="editAreaModal" tabindex="-1" aria-labelledby="editAreaModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAreaModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    Crear Movimiento
                </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gastos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="usuario_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="id_predio" value="{{ $Predios }}">

                        <div class="form-group">
                        <label for="naturaleza">Clase</label>
                        <select name="naturaleza" id="naturaleza" class="form-control" required>
                                <option value="">Seleccione una clase</option>
                                <option value="Ingresos">Ingresos</option>
                                <option value="Gastos">Gastos</option>
                                <option value="Costos de ventas">Costos</option>
                            </select>
                        </div>

                        <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <select name="categoria" id="categoria" class="form-control" required>
                                <option value="">Seleccione una categoría</option>
                            </select>
                        </div>

                        <div class="form-group">
                        <label for="subcuenta">Sub cuenta</label>
                        <select name="plan_cuenta" id="subcuenta" class="form-control" required>
                                <option value="">Seleccione una subcuenta</option>
                            </select>
                        </div>

                        <div class="form-group">
                        <label for="gasto">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" id="gasto" class="form-control" placeholder="Ej: 1,234.56"
                                required>
                        </div>
                            <input type="hidden" name="cantidad" id="gastoReal">
                        </div>

                        <div class="form-group">
                        <label for="descripcion">Concepto</label>
                            <input type="text" name="descripcion" id="descripcion" class="form-control"
                                placeholder="Ej: Pago de alquiler" required>
                        </div>

                    <div class="form-group mb-0">
                            <label for="fecha">Fecha</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        Guardar
                    </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($gastos as $gasto)
    <div class="modal fade custom-modal" id="editAreaModal{{ $gasto->id }}" tabindex="-1"
        aria-labelledby="editAreaModalLabel{{ $gasto->id }}" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="editAreaModalLabel{{ $gasto->id }}">
                        <i class="bi bi-pencil-square me-2"></i>
                        Editar Movimiento
                    </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('gastos.update', $gasto->id) }}" method="POST">
                        @csrf
                    @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" name="usuario_id" value="{{ $gasto->usuario_id }}">
                            <input type="hidden" name="id_predio" value="{{ $gasto->id_predio }}">

                            <div class="form-group">
                            <label for="naturaleza-update-{{ $gasto->id }}">Clase</label>
                                <select name="naturaleza" id="naturaleza-update-{{ $gasto->id }}"
                                class="form-control naturaleza-update" data-id="{{ $gasto->id }}" required>
                                    <option value="">Seleccione una clase</option>
                                    <option value="Ingresos"
                                    {{ $gasto->planCuenta->naturaleza == 'Ingresos' ? 'selected' : '' }}>
                                    Ingresos
                                    </option>
                                    <option value="Gastos"
                                    {{ $gasto->planCuenta->naturaleza == 'Gastos' ? 'selected' : '' }}>
                                    Gastos
                                </option>
                                    <option value="Costos de ventas"
                                    {{ $gasto->planCuenta->naturaleza == 'Costos de ventas' ? 'selected' : '' }}>
                                    Costos
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                            <label for="categoria-update-{{ $gasto->id }}">Categoría</label>
                                <select name="categoria" id="categoria-update-{{ $gasto->id }}" class="form-control"
                                data-selected-categoria="{{ substr($gasto->planCuenta->codcta, 0, 6) }}" required>
                                    <option value="">Seleccione una categoría</option>
                                </select>
                            </div>

                            <div class="form-group">
                            <label for="subcuenta-update-{{ $gasto->id }}">Sub cuenta</label>
                                <select name="plan_cuenta" id="subcuenta-update-{{ $gasto->id }}"
                                class="form-control" data-selected-id="{{ $gasto->plan_cuenta }}" required>
                                    <option value="">Seleccione una subcuenta</option>
                                </select>
                            </div>

                            <div class="form-group">
                            <label for="gasto-{{ $gasto->id }}">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" id="gasto-{{ $gasto->id }}" class="form-control"
                                    value="{{ number_format($gasto->cantidad, 2, '.', ',') }}" required>
                            </div>
                                <input type="hidden" name="cantidad" id="gastoReal-{{ $gasto->id }}"
                                    value="{{ $gasto->cantidad }}">
                            </div>

                            <div class="form-group">
                            <label for="descripcion-{{ $gasto->id }}">Concepto</label>
                                <input type="text" name="descripcion" id="descripcion-{{ $gasto->id }}"
                                class="form-control" value="{{ $gasto->descripcion }}" required>
                            </div>

                        <div class="form-group mb-0">
                            <label for="fecha-{{ $gasto->id }}">Fecha</label>
                                <input type="date" name="fecha" id="fecha-{{ $gasto->id }}"
                                    class="form-control" value="{{ $gasto->fecha }}" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Guardar cambios
                        </button>
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
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Variables globales para los datos de categorías y subcuentas
        const categoriasPrincipales = @json($categoriasPrincipales);
        const subcuentas = @json($subcuentasDetalles);

        // Función para formatear números con separadores de miles
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Función para manejar el formato de montos
        function setupAmountInput(input, hiddenInput) {
            input.addEventListener('input', function(event) {
            let rawValue = event.target.value.replace(/,/g, '');
            if (!isNaN(rawValue) && rawValue !== '') {
                const cursorPosition = event.target.selectionStart;
                const formattedValue = formatNumber(rawValue);
                event.target.value = formattedValue;
                    hiddenInput.value = rawValue;
                const newCursorPosition = cursorPosition + (formattedValue.length - rawValue.length);
                event.target.setSelectionRange(newCursorPosition, newCursorPosition);
            } else {
                event.target.value = '';
                    hiddenInput.value = '';
                }
            });
        }

        // Función para cargar categorías según la naturaleza seleccionada
        function loadCategorias(naturalezaSelect, categoriaSelect, subcuentaSelect) {
            const selectedNaturaleza = naturalezaSelect.value;

            // Limpiar selects
            categoriaSelect.innerHTML = '<option value="">Seleccione una categoría</option>';
            subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';

            if (!selectedNaturaleza) return;

            // Filtrar categorías según la naturaleza
            const categoriasFiltradas = categoriasPrincipales.filter(categoria => {
                const hasSubcuentas = subcuentas.some(subcuenta =>
                    subcuenta.codcta.startsWith(categoria.codcta) &&
                    subcuenta.naturaleza === selectedNaturaleza
                );
                return categoria.naturaleza === selectedNaturaleza && hasSubcuentas;
            });

            // Agregar opciones filtradas
            categoriasFiltradas.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.codcta;
                option.textContent = categoria.nomcta;
                categoriaSelect.appendChild(option);
            });
        }

        // Función para cargar subcuentas según la categoría seleccionada
        function loadSubcuentas(naturalezaSelect, categoriaSelect, subcuentaSelect) {
            const selectedCategoria = categoriaSelect.value;
            const selectedNaturaleza = naturalezaSelect.value;

            subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';

            if (!selectedCategoria || !selectedNaturaleza) return;

            // Filtrar subcuentas
            const subcuentasFiltradas = subcuentas.filter(subcuenta =>
                subcuenta.codcta.startsWith(selectedCategoria) &&
                subcuenta.naturaleza === selectedNaturaleza
            );

            // Agregar opciones filtradas
            subcuentasFiltradas.forEach(subcuenta => {
                const option = document.createElement('option');
                option.value = subcuenta.id;
                option.textContent = subcuenta.nomcta;
                subcuentaSelect.appendChild(option);
            });
        }

        // Inicialización cuando el DOM está listo
        $(document).ready(function() {
            // Configurar DataTables
            const table = $('#tabla-Predios').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                },
                responsive: true,
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex"f>>t<"d-flex justify-content-between align-items-center mt-4"<"me-auto"i><"ms-auto"p>>',
                pageLength: 10,
                order: [
                    [3, 'desc']
                ], // Ordenar por fecha descendente
            });

            // Configurar el botón de filtro por fechas
                $("#tabla-Predios_filter").append(`
                <button type="button" class="btn btn-action btn-primary ms-3"
                        data-bs-toggle="modal" data-bs-target="#dateFilterModal">
                    <i class="bi bi-calendar-range me-2"></i>
                    Filtrar por fechas
                </button>
            `);

            // Configurar filtro de fechas
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const min = $('#min').val();
                const max = $('#max').val();
                const date = data[3]; // Índice de la columna fecha

                if (!min && !max) return true;

                const dateValue = new Date(date).getTime();
                const minDate = min ? new Date(min).getTime() : null;
                const maxDate = max ? new Date(max).getTime() : null;

                if ((!minDate && maxDate >= dateValue) ||
                    (!maxDate && minDate <= dateValue) ||
                    (minDate <= dateValue && maxDate >= dateValue)) {
                            return true;
                        }
                        return false;
            });

            // Aplicar filtro de fechas
            $('#applyDateFilter').click(function() {
                table.draw();
                $('#dateFilterModal').modal('hide');
            });

            // Configurar inputs de montos
            setupAmountInput(
                document.getElementById('gasto'),
                document.getElementById('gastoReal')
            );

            // Configurar selects del modal de creación
            const createNaturalezaSelect = document.getElementById('naturaleza');
            const createCategoriaSelect = document.getElementById('categoria');
            const createSubcuentaSelect = document.getElementById('subcuenta');

            // Eventos para el modal de creación
            createNaturalezaSelect.addEventListener('change', function() {
                loadCategorias(createNaturalezaSelect, createCategoriaSelect, createSubcuentaSelect);
            });

            createCategoriaSelect.addEventListener('change', function() {
                loadSubcuentas(createNaturalezaSelect, createCategoriaSelect, createSubcuentaSelect);
            });

            // Configurar modales de edición
            @foreach ($gastos as $gasto)
                setupAmountInput(
                    document.getElementById('gasto-{{ $gasto->id }}'),
                    document.getElementById('gastoReal-{{ $gasto->id }}')
                );

                const editNaturalezaSelect{{ $gasto->id }} = document.getElementById(
                    'naturaleza-update-{{ $gasto->id }}');
                const editCategoriaSelect{{ $gasto->id }} = document.getElementById(
                    'categoria-update-{{ $gasto->id }}');
                const editSubcuentaSelect{{ $gasto->id }} = document.getElementById(
                    'subcuenta-update-{{ $gasto->id }}');

                editNaturalezaSelect{{ $gasto->id }}.addEventListener('change', function() {
                    loadCategorias(editNaturalezaSelect{{ $gasto->id }},
                        editCategoriaSelect{{ $gasto->id }}, editSubcuentaSelect{{ $gasto->id }}
                    );
                });

                editCategoriaSelect{{ $gasto->id }}.addEventListener('change', function() {
                    loadSubcuentas(editNaturalezaSelect{{ $gasto->id }},
                        editCategoriaSelect{{ $gasto->id }}, editSubcuentaSelect{{ $gasto->id }}
                    );
                });

                // Cargar datos iniciales para edición
                loadCategorias(editNaturalezaSelect{{ $gasto->id }}, editCategoriaSelect{{ $gasto->id }},
                    editSubcuentaSelect{{ $gasto->id }});
                loadSubcuentas(editNaturalezaSelect{{ $gasto->id }}, editCategoriaSelect{{ $gasto->id }},
                    editSubcuentaSelect{{ $gasto->id }});
            @endforeach
        });

        // Función para confirmar eliminación
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E49B39',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formDelete' + id).submit();
                }
            });
        }

        // Configurar animaciones de modales
        $('.modal').on('show.bs.modal', function() {
            $(this).removeClass('hiding');
        }).on('hide.bs.modal', function() {
            $(this).addClass('hiding');
        });
    </script>

    @if (session('error') || session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
    @endif

    @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}'
                });
                @endif
            });
        </script>
    @endif
@endsection
