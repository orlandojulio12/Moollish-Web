@extends('layouts')

@section('title')
    Consulta de Insumos Disponibles
@endsection

@section('styles')
    <style>
        /* Estilos generales basados en dashboard.blade.php */
        .dashboard-container {
         /*    padding: 20px; */
        }

        .dashboard-row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }

        .dashboard-card {
            flex: 1;
            min-width: 250px;
            background: white;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            /* margin: 10px; */
            padding: 22px 22px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .card-header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 23px;
            font-weight: 700;
            color: #292929;
            margin: 0;
        }

        .card-subtitle {
            font-size: 14px;
            color: #767676;
            margin-top: 5px;
        }

        /* Breadcrumb personalizado */
        .breadcrumb-custom {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .breadcrumb-item {
            color: #767676;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
        }

        .breadcrumb-item:hover {
            color: #E49B39;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #E49B39;
            font-weight: 600;
        }

        .breadcrumb-separator {
            margin: 0 10px;
            color: #767676;
            font-size: 18px;
        }

        /* Search y actions container */
        .actions-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-wrapper {
            flex: 1;
            max-width: 450px;
        }

        .search-input {
            display: flex;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .search-input input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            outline: none;
            font-size: 14px;
        }

        .search-input input:focus {
            box-shadow: none;
        }

        .search-input .search-icon {
            display: flex;
            align-items: center;
            padding: 0 15px;
            background-color: #f8f9fa;
            color: #E49B39;
        }

        .btn-action {
            padding: 10px 20px;
            background-color: #E49B39;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-action:hover {
            background-color: #C97917;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        /* Tabla personalizada */
        .table-container {
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
            font-weight: 600;
            color: #4B5563;
            background-color: #F9FAFB;
        }

        .custom-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #E5E7EB;
            color: #111827;
        }

        /* Estilo para las celdas numéricas */
        .custom-table td.numeric-cell {
            text-align: right;
            font-family: monospace;
            font-weight: 500;
        }

        /* Estilo para celdas de valores monetarios */
        .custom-table td.money-cell {
            text-align: right;
            font-family: monospace;
            font-weight: 600;
            color: #E49B39;
        }

        .custom-table tr:last-child td {
            border-bottom: none;
        }

        .custom-table tr:hover {
            background-color: #FFF8F0;
        }

        /* Botones personalizados dentro de la tabla */
        .btn-table {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background-color: transparent;
        }

        .btn-view {
            color: #E49B39;
            border: 1px solid #E49B39;
        }

        .btn-view:hover {
            background-color: #FFF8F0;
            transform: translateY(-2px);
        }

        /* Estado vacío */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: #F9FAFB;
            border-radius: 8px;
        }

        .empty-state-icon {
            font-size: 48px;
            color: #E5E7EB;
            margin-bottom: 16px;
        }

        .empty-state-text {
            font-size: 16px;
            color: #6B7280;
            margin-bottom: 20px;
        }

        /* Modal personalizado */
        .custom-modal .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .custom-modal .modal-header {
            color: #E49B39;
            padding: 20px 25px;
        }

        .custom-modal .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .custom-modal .modal-body {
            padding: 25px;
        }

        .custom-modal .modal-footer {
            border-top: 1px solid #F3F4F6;
            padding: 15px 25px;
        }

        .custom-modal .btn-close {
            color: #E49B39;
            opacity: 0.8;
        }

        .custom-modal .btn-close:hover {
            opacity: 1;
        }

        /* Diseño moderno del modal con tarjetas de información */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-card {
            background-color: #F9FAFB;
            border-radius: 8px;
            padding: 16px;
            transition: all 0.2s;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .info-card-title {
            color: #6B7280;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .info-card-value {
            color: #111827;
            font-size: 15px;
            font-weight: 500;
        }

        .info-card-value.highlighted {
            color: #E49B39;
            font-size: 16px;
            font-weight: 600;
        }

        .section-divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }

        .section-divider hr {
            flex: 1;
            border: none;
            height: 1px;
            background-color: #E5E7EB;
        }

        .section-title {
            margin: 0 15px;
            font-size: 14px;
            font-weight: 600;
            color: #6B7280;
        }

        /* Spinner personalizado */
        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }

        .custom-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #FFF8F0;
            border-radius: 50%;
            border-top-color: #E49B39;
            animation: spin 1s linear infinite;
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
            color: #dc7a00;
        }

        .no-active-tab:hover {
            color: #dc7a00;
            cursor: pointer;
            text-decoration: underline;
        }

        .nav-link {
            color: #dc7a00;
        }

        .nav-link:hover {
            color: #dc7a00;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .actions-container {
                flex-direction: column;
                align-items: stretch;
            }

            .search-wrapper {
                max-width: 100%;
                margin-bottom: 15px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <!-- Breadcrumb -->

        <div class="dashboard-card">
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
                    <a href="{{ route('insumos.index') }}">
                        <h3 class="cumb no-active-tab">
                            Gestión de Insumos
                        </h3>
                    </a>
                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <h3 class="cumb active-tab">Consulta de Insumos</h3>
                </div>
            </div>
            <hr>
        <!-- Tarjeta principal -->

            <div class="card-header-container">
                <div>
                    <h2 class="card-title">Consulta de Insumos</h2>
                    <p class="card-subtitle">Visualiza y administra los insumos registrados en el sistema</p>
                </div>
            </div>

            <!-- Contenedor de búsqueda y acciones -->
            <div class="actions-container">
                <div class="search-wrapper">
                    <div class="search-input">
                        <input type="text" id="searchInput" placeholder="Buscar por código, nombre o categoría...">
                        <div class="search-icon">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                    </div>
                </div>

                <!-- Selector de predios para filtrar -->
                <div class="predio-filter-wrapper" style="margin: 0px 15px; flex: 0 0 auto; min-width: 200px;">
                    <div class="search-input">
                        <select id="predioFilter" class="form-select" style="border: none; outline: none;     padding: 10px 25px 10px 8px; width: 100%; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">Todos los predios</option>
                            @foreach($predios as $predio)
                                <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                            @endforeach
                        </select>
                        <div class="search-icon">
                            <span class="material-symbols-outlined">filter_alt</span>
                        </div>
                    </div>
                </div>

                <!-- Selector de categorías para filtrar -->
                <div class="categoria-filter-wrapper" style="margin-right: 15px; flex: 0 0 auto; min-width: 200px;">
                    <div class="search-input">
                        <select id="categoriaFilter" class="form-select" style="border: none; outline: none; padding: 10px 25px 10px 8px; width: 100%; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">Todas las categorías</option>
                            {{-- Asegúrate de pasar la variable $categorias desde el controlador --}}
                            @isset($categorias)
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            @endisset
                        </select>
                        <div class="search-icon">
                            <span class="material-symbols-outlined">category</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('insumos.registroForm') }}" class="btn-action">
                    Registrar Nuevo Insumo
                </a>
            </div>

            <!-- Tabla de insumos -->
            <div class="table-container">
                <table class="custom-table" id="insumosTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre Comercial</th>
                            <th>Predio</th>
                            <th>Cantidad en Stock</th>
                            <th>Valor Unitario</th>
                            <th>Valor Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($insumosPorPredioNumbered as $item)
                            <tr data-predio-id="{{ $item['predio_id'] }}" data-categoria-id="{{ $item['categoria_id'] ?? '' }}">
                                <td>{{ $item['row_number'] }}</td>
                                <td>{{ $item['nombre_comercial'] }}</td>
                                <td>{{ $item['nombre_predio'] }}</td>
                                <td class="numeric-cell">{{ number_format($item['stock_actual'], 2) }} {{ $item['unidad_medida'] }}</td>
                                <td class="money-cell">
                                    ${{ number_format($item['valor_unitario'], 0, ',', '.') }}
                                </td>
                                <td class="money-cell">${{ number_format($item['valor_total'], 0, ',', '.') }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <button type="button"
                                                class="btn-table btn-view btn-ver-detalles"
                                                data-url="{{ route('insumos.show', $item['insumo_id']) }}">
                                            Ficha Técnica
                                        </button>
                                        <button type="button"
                                                class="btn-table btn-view btn-ver-historial"
                                                data-url="{{ route('insumos.historial', $item['insumo_id']) }}">
                                            <span class="material-symbols-outlined">history</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <span class="material-symbols-outlined empty-state-icon">inventory_2</span>
                                        <p class="empty-state-text">No hay insumos registrados en el sistema</p>
                                        <a href="{{ route('insumos.registroForm') }}" class="btn-action">
                                            Registrar Primer Insumo
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Resumen del inventario fuera de la tabla -->
            <div class="inventory-summary" style="margin-top: 25px; background-color: #FFF8F0; padding: 20px; border-radius: 8px; border: 1px solid #E49B39; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #292929;">Resumen del Inventario</h3>
                        <p style="margin: 5px 0 0 0; color: #6B7280; font-size: 14px;" id="predio-filter-text">
                            Todos los predios
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <span style="display: block; font-size: 24px; font-weight: 700; color: #E49B39; font-family: monospace;" id="valor-total-display">
                            ${{ number_format($valorTotalInventario, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Modal para Ver Detalles del Insumo --}}
<div class="modal fade custom-modal" id="verInsumoModal" tabindex="-1" aria-labelledby="verInsumoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verInsumoModalLabel">
                    <span class="material-symbols-outlined me-2">inventory_2</span>
                    Ficha Técnica del Insumo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Spinner de carga -->
                <div class="spinner-container" id="modalLoadingSpinner" style="display: none;">
                    <div class="custom-spinner"></div>
                </div>

                <div id="modalInsumoContent">
                    <!-- Información Básica -->
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-card-title">Código</div>
                            <div class="info-card-value" id="modalCodigo"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Nombre Comercial</div>
                            <div class="info-card-value highlighted" id="modalNombreComercial"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Categoría</div>
                            <div class="info-card-value" id="modalCategoria"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Predio</div>
                            <div class="info-card-value" id="modalPredio"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Unidad Medida</div>
                            <div class="info-card-value" id="modalUnidadMedida"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Stock Actual</div>
                            <div class="info-card-value highlighted" id="modalStock"></div>
                        </div>
                    </div>

                    <div class="info-card mb-4">
                        <div class="info-card-title">Usos Registrados</div>
                        <div class="info-card-value" id="modalUsos"></div>
                    </div>

                    <!-- Divisor de sección -->
                    <div class="section-divider">
                        <hr>
                        <span class="section-title">INFORMACIÓN DETALLADA</span>
                        <hr>
                    </div>

                    <!-- Información Detallada -->
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-card-title">Precio Referencia</div>
                            <div class="info-card-value highlighted" id="modalPrecio"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Fabricante</div>
                            <div class="info-card-value" id="modalFabricante"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Reg. ICA</div>
                            <div class="info-card-value" id="modalIca"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Principio Activo</div>
                            <div class="info-card-value" id="modalPrincipio"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Retiro de Leche (días)</div>
                            <div class="info-card-value" id="modalRetLeche"></div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-title">Retiro de Carne (días)</div>
                            <div class="info-card-value" id="modalRetCarne"></div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="info-card mt-4">
                        <div class="info-card-title">Observaciones</div>
                        <div class="info-card-value" id="modalObservaciones"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal para Ver Historial del Insumo --}}
<div class="modal fade custom-modal" id="verHistorialModal" tabindex="-1" aria-labelledby="verHistorialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verHistorialModalLabel">
                    <span class="material-symbols-outlined me-2">history</span>
                    Historial del Insumo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Spinner de carga -->
                <div class="spinner-container" id="modalHistorialLoadingSpinner" style="display: none;">
                    <div class="custom-spinner"></div>
                </div>

                <div id="modalHistorialContent">
                    <!-- Título del insumo -->
                    <h4 class="text-center mb-4" id="modalInsumoTitle" style="color: #E49B39;"></h4>

                    <!-- Resumen de Valor Total Entradas (Nuevo) -->
                    <div class="text-center mb-3" id="modalHistorialTotalEntradas" style="font-size: 1.1rem; font-weight: 500;">
                        <!-- Se llenará dinámicamente -->
                    </div>

                    <!-- Resumen de Valor Total Salidas (Nuevo) -->
                    <div class="text-center mb-3" id="modalHistorialTotalSalidas" style="font-size: 1.1rem; font-weight: 500;">
                        <!-- Se llenará dinámicamente -->
                    </div>

                    <!-- Pestañas -->
                    <ul class="nav nav-tabs mb-3" id="historialTabs" role="tablist">
                        <li class="nav-item" role="presentation" style=" text-align: center;">
                            <button class="nav-link active" id="entradas-tab" data-bs-toggle="tab" data-bs-target="#entradas" type="button" role="tab" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <span class="material-symbols-outlined">input</span>
                                Entradas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation" style="flex: 1; text-align: center;">
                            <button class="nav-link" id="salidas-tab" data-bs-toggle="tab" data-bs-target="#salidas" type="button" role="tab" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <span class="material-symbols-outlined">output</span>
                                Salidas
                            </button>
                        </li>
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content" id="historialTabContent">
                        <div class="tab-pane fade show active" id="entradas" role="tabpanel">
                            <div class="table-container">
                                <table class="custom-table" id="entradasTable">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Caducidad</th>
                                            <th>Cant. Comprada</th>
                                            <th>Valor Unitario</th>
                                            <th>Valor de la compra</th>
                                            <th>Cant. Restante</th>

                                            <th>Proveedor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="entradasTableBody">
                                        <!-- Se llenará dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="salidas" role="tabpanel">
                            <div class="table-container">
                                <table class="custom-table" id="salidasTable">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Caducidad</th>
                                            <th>Cantidad</th>
                                            <th>Valor Unitario</th>
                                            <th>Valor Total</th>
                                            <th>Destino</th>
                                            <th>Responsable</th>
                                        </tr>
                                    </thead>
                                    <tbody id="salidasTableBody">
                                        <!-- Se llenará dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const predioFilter = document.getElementById('predioFilter');
            const categoriaFilter = document.getElementById('categoriaFilter');
            const tableBody = document.getElementById('insumosTable').querySelector('tbody');
            const rows = tableBody.querySelectorAll('tr');

            // Variables para almacenar valores totales
            const insumos = [];
            let valorTotalInventario = {{ $valorTotalInventario }};

            // Inicializar datos de insumos para cálculos
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length > 0 && !row.querySelector('.empty-state')) {
                    const predioId = row.getAttribute('data-predio-id') || '';
                    const categoriaId = row.getAttribute('data-categoria-id') || '';
                    const valorUnitario = parseFloat(cells[4].textContent.replace('$', '').replace(/\./g, '').replace(',', '.')) || 0;
                    const cantidad = parseFloat(cells[3].textContent.split(' ')[0].replace(',', '.')) || 0;
                    const valorTotal = parseFloat(cells[5].textContent.replace('$', '').replace(/\./g, '').replace(',', '.')) || 0;

                    insumos.push({
                        predioId: predioId,
                        categoriaId: categoriaId,
                        valorUnitario: valorUnitario,
                        cantidad: cantidad,
                        valorTotal: valorTotal
                    });
                }
            });

            // Función para filtrar las filas según los criterios aplicados
            function filterRows() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedPredioId = predioFilter.value;
                const selectedCategoriaId = categoriaFilter.value;
                let visibleRows = 0;
                let totalFiltrado = 0;

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length === 0 || row.querySelector('.empty-state')) {
                        if(row.querySelector('.empty-state')) {
                            row.style.display = 'none';
                        }
                        return;
                    }

                    const numero = cells[0].textContent.toLowerCase();
                    const nombre = cells[1].textContent.toLowerCase();
                    const predioNombre = cells[2].textContent.toLowerCase();
                    const categoriaNombreEnCelda = cells[1].textContent.toLowerCase();
                    const predioId = row.getAttribute('data-predio-id');
                    const categoriaId = row.getAttribute('data-categoria-id');
                    const valorTotalCelda = parseFloat(cells[5].textContent.replace('$', '').replace(/\./g, '').replace(',', '.')) || 0;

                    const matchesSearch = !searchTerm ||
                                         numero.includes(searchTerm) ||
                                         nombre.includes(searchTerm) ||
                                         predioNombre.includes(searchTerm);

                    const matchesPredio = !selectedPredioId ||
                                         (predioId && predioId === selectedPredioId);

                    const matchesCategoria = !selectedCategoriaId ||
                                             (categoriaId && categoriaId === selectedCategoriaId);

                    if (matchesSearch && matchesPredio && matchesCategoria) {
                        row.style.display = '';
                        visibleRows++;
                        totalFiltrado += valorTotalCelda;
                    } else {
                        row.style.display = 'none';
                    }
                });

                actualizarResumenInventario(selectedPredioId, selectedCategoriaId, totalFiltrado);

                const emptyStateRow = tableBody.querySelector('tr td .empty-state');
                if (emptyStateRow) {
                    const emptyStateRowParent = emptyStateRow.closest('tr');
                    if (visibleRows === 0 && rows.length > 1) {
                        emptyStateRowParent.style.display = '';
                    } else {
                        emptyStateRowParent.style.display = 'none';
                    }
                }
            }

            // Función para actualizar el resumen del inventario
            function actualizarResumenInventario(predioId, categoriaId, total) {
                const valorTotalDisplay = document.getElementById('valor-total-display');
                const predioFilterText = document.getElementById('predio-filter-text');

                valorTotalDisplay.textContent = '$' + total.toLocaleString('es-CO', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });

                if (predioId) {
                    const predioText = predioFilter.options[predioFilter.selectedIndex].text;
                    predioFilterText.textContent = 'Predio: ' + predioText;
                } else {
                    predioFilterText.textContent = 'Todos los predios';
                }

                if (categoriaId) {
                    const categoriaText = categoriaFilter.options[categoriaFilter.selectedIndex].text;
                    predioFilterText.textContent += (predioId ? ', ' : '') + 'Categoría: ' + categoriaText;
                } else {
                    if (!predioId) {
                         predioFilterText.textContent += '';
                    }
                }
            }

            // Evento para la búsqueda por texto
            searchInput.addEventListener('keyup', filterRows);

            // Evento para el filtro por predio
            predioFilter.addEventListener('change', filterRows);

            // Evento para el filtro por categoría
            categoriaFilter.addEventListener('change', filterRows);

            // Calcular los totales iniciales
            filterRows();

            // Modal para ver detalles del insumo
            const verInsumoModal = new bootstrap.Modal(document.getElementById('verInsumoModal'));
            const modalContent = document.getElementById('modalInsumoContent');
            const modalLoadingSpinner = document.getElementById('modalLoadingSpinner');

            // Delegación de eventos para los botones "Ver detalles"
            document.getElementById('insumosTable').addEventListener('click', function(event) {
                const targetButton = event.target.closest('.btn-ver-detalles');

                if (targetButton) {
                    event.preventDefault();
                    const insumoUrl = targetButton.dataset.url;

                    // Mostrar spinner y ocultar contenido anterior
                    modalLoadingSpinner.style.display = 'flex';
                    modalContent.style.display = 'none';
                    verInsumoModal.show();

                    // Petición AJAX para obtener los datos
                    fetch(insumoUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al cargar los datos del insumo');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const insumo = data.insumo;
                            const stock = data.stock;
                            const usos = data.usos_nombres;

                            // Llenar el modal con los datos
                            document.getElementById('modalCodigo').textContent = insumo.codigo || '-';
                            document.getElementById('modalNombreComercial').textContent = insumo.nombre_comercial || '-';
                            document.getElementById('modalCategoria').textContent = insumo.categoria ? insumo.categoria.nombre : 'N/A';
                            document.getElementById('modalPredio').textContent = insumo.predio ? insumo.predio.nombre_predio : 'N/A';
                            document.getElementById('modalUnidadMedida').textContent = insumo.unidad_medida || '-';
                            document.getElementById('modalStock').textContent = stock !== null ? `${stock} ${insumo.unidad_medida}` : 'No calculado';
                            document.getElementById('modalUsos').textContent = usos || 'Ninguno';
                            document.getElementById('modalPrecio').textContent = insumo.precio_referencia ? `$${parseFloat(insumo.precio_referencia).toLocaleString('es-CO')}` : '-';
                            document.getElementById('modalFabricante').textContent = insumo.fabricante || '-';
                            document.getElementById('modalIca').textContent = insumo.registro_ica || '-';
                            document.getElementById('modalPrincipio').textContent = insumo.principio_activo || '-';
                            document.getElementById('modalRetLeche').textContent = insumo.tiempo_retiro_leche !== null ? insumo.tiempo_retiro_leche : '-';
                            document.getElementById('modalRetCarne').textContent = insumo.tiempo_retiro_carne !== null ? insumo.tiempo_retiro_carne : '-';
                            document.getElementById('modalObservaciones').textContent = insumo.observaciones || '-';

                            // Ocultar spinner y mostrar contenido
                            modalLoadingSpinner.style.display = 'none';
                            modalContent.style.display = 'block';
                        } else {
                            throw new Error(data.message || 'Error al obtener los datos');
                        }
                    })
                    .catch(error => {
                        console.error('Error en fetch:', error);
                        modalLoadingSpinner.style.display = 'none';
                        modalContent.innerHTML = `
                            <div class="alert alert-danger">
                                <span class="material-symbols-outlined me-2">error</span>
                                ${error.message}
                            </div>
                        `;
                        modalContent.style.display = 'block';
                    });
                }
            });

            // Modal para ver historial del insumo
            const verHistorialModal = new bootstrap.Modal(document.getElementById('verHistorialModal'));
            const modalHistorialContent = document.getElementById('modalHistorialContent');
            const modalHistorialLoadingSpinner = document.getElementById('modalHistorialLoadingSpinner');
            const modalInsumoTitle = document.getElementById('modalInsumoTitle');

            // Delegación de eventos para los botones "Ver historial"
            document.getElementById('insumosTable').addEventListener('click', function(event) {
                const targetButton = event.target.closest('.btn-ver-historial');

                if (targetButton) {
                    event.preventDefault();
                    const historialUrl = targetButton.dataset.url;
                    const insumoRow = targetButton.closest('tr');
                    const insumoNombre = insumoRow.querySelector('td:nth-child(2)').textContent;

                    // Mostrar spinner y ocultar contenido anterior
                    modalHistorialLoadingSpinner.style.display = 'flex';
                    modalHistorialContent.style.display = 'none';
                    modalInsumoTitle.textContent = insumoNombre;
                    verHistorialModal.show();

                    // Petición AJAX para obtener los datos del historial
                    fetch(historialUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al cargar el historial del insumo');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const entradas = data.entradas || [];
                            const salidas = data.salidas || [];
                            console.log(entradas);

                            // Calcular valor total de entradas (Nuevo)
                            let valorTotalEntradas = 0;
                            entradas.forEach(entrada => {
                                valorTotalEntradas += (entrada.valor_total_original || 0);
                            });

                            // Mostrar valor total de entradas (Nuevo)
                            const totalEntradasDisplay = document.getElementById('modalHistorialTotalEntradas');
                            if (totalEntradasDisplay) {
                                totalEntradasDisplay.innerHTML = `<strong>Valor Total Entradas:</strong> $${valorTotalEntradas.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
                            }

                            // Calcular valor total de salidas (Nuevo)
                            let valorTotalSalidas = 0;
                            salidas.forEach(salida => {
                                valorTotalSalidas += salida.valor_total; // Asumiendo que valor_total ahora viene del backend
                            });

                            // Mostrar valor total de salidas (Nuevo)
                            const totalSalidasDisplay = document.getElementById('modalHistorialTotalSalidas');
                            if (totalSalidasDisplay) {
                                totalSalidasDisplay.innerHTML = `<strong>Valor Total Salidas (Estimado):</strong> $${valorTotalSalidas.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
                            }

                            // Llenar tabla de entradas
                            const entradasTableBody = document.getElementById('entradasTableBody');
                            entradasTableBody.innerHTML = entradas.map(entrada => {
                                // Calcular días restantes para vencimiento
                                let diasRestantesText = '';
                                if (entrada.fecha_vencimiento) {
                                    try {
                                        // Asegurar formato correcto: convertir DD/MM/YYYY a YYYY-MM-DD para Date()
                                        let fechaPartes = entrada.fecha_vencimiento.split('/');
                                        if (fechaPartes.length === 3) {
                                            // Formato español DD/MM/YYYY
                                            const fechaISO = `${fechaPartes[2]}-${fechaPartes[1]}-${fechaPartes[0]}`;
                                            const fechaVencimiento = new Date(fechaISO);
                                            const hoy = new Date();

                                            // Verificar que la fecha es válida
                                            if (!isNaN(fechaVencimiento.getTime())) {
                                                const diferenciaTiempo = fechaVencimiento - hoy;
                                                const diasRestantes = Math.ceil(diferenciaTiempo / (1000 * 60 * 60 * 24));

                                                if (diasRestantes > 0) {
                                                    diasRestantesText = ` (${diasRestantes} días)`;
                                                } else if (diasRestantes === 0) {
                                                    diasRestantesText = ` (Vence hoy)`;
                                                } else {
                                                    diasRestantesText = ` (Vencido hace ${Math.abs(diasRestantes)} días)`;
                                                }
                                            }
                                        }
                                    } catch (e) {
                                        console.error("Error al procesar fecha:", e);
                                    }
                                }

                                return `
                                <tr>
                                    <td>${entrada.fecha}</td>
                                    <td>${entrada.fecha_vencimiento !== '-' ? entrada.fecha_vencimiento + diasRestantesText : '-'}</td>
                                    <td class="numeric-cell">${(entrada.cantidad_original || 0).toLocaleString('es-CO')} ${entrada.unidad_medida}</td>
                                    <td class="money-cell">$${(entrada.valor_unitario || 0).toLocaleString('es-CO')}</td>
                                    <td class="money-cell">$${(entrada.valor_total_original || 0).toLocaleString('es-CO')}</td>
                                    <td style="color: green;" class="numeric-cell">${(entrada.cantidad_restante || 0).toLocaleString('es-CO')} ${entrada.unidad_medida}</td>
                                    <td >${entrada.proveedor || '-'}</td>
                                </tr>
                                `;
                            }).join('');

                            // Llenar tabla de salidas
                            const salidasTableBody = document.getElementById('salidasTableBody');
                            salidasTableBody.innerHTML = salidas.map(salida => {
                                // Calcular días restantes para vencimiento
                                let diasRestantesText = '';
                                if (salida.fecha_vencimiento) {
                                    try {
                                        // Asegurar formato correcto: convertir DD/MM/YYYY a YYYY-MM-DD para Date()
                                        let fechaPartes = salida.fecha_vencimiento.split('/');
                                        if (fechaPartes.length === 3) {
                                            // Formato español DD/MM/YYYY
                                            const fechaISO = `${fechaPartes[2]}-${fechaPartes[1]}-${fechaPartes[0]}`;
                                            const fechaVencimiento = new Date(fechaISO);
                                            const hoy = new Date();

                                            // Verificar que la fecha es válida
                                            if (!isNaN(fechaVencimiento.getTime())) {
                                                const diferenciaTiempo = fechaVencimiento - hoy;
                                                const diasRestantes = Math.ceil(diferenciaTiempo / (1000 * 60 * 60 * 24));

                                                if (diasRestantes > 0) {
                                                    diasRestantesText = ` (${diasRestantes} días)`;
                                                } else if (diasRestantes === 0) {
                                                    diasRestantesText = ` (Vence hoy)`;
                                                } else {
                                                    diasRestantesText = ` (Vencido hace ${Math.abs(diasRestantes)} días)`;
                                                }
                                            }
                                        }
                                    } catch (e) {
                                        console.error("Error al procesar fecha:", e);
                                    }
                                }

                                return `
                                <tr>
                                    <td>${salida.fecha}</td>
                                    <td>${salida.fecha_vencimiento ? salida.fecha_vencimiento + diasRestantesText : '-'}</td>
                                    <td class="numeric-cell">${salida.cantidad} ${salida.unidad_medida}</td>
                                    <td class="money-cell">$${salida.valor_unitario.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                    <td class="money-cell">$${salida.valor_total.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                    <td>${salida.destino || '-'}</td>
                                    <td>${salida.responsable || '-'}</td>
                                </tr>
                                `;
                            }).join('');

                            // Ocultar spinner y mostrar contenido
                            modalHistorialLoadingSpinner.style.display = 'none';
                            modalHistorialContent.style.display = 'block';
                        } else {
                            throw new Error(data.message || 'Error al obtener el historial');
                        }
                    })
                    .catch(error => {
                        console.error('Error en fetch:', error);
                        modalHistorialLoadingSpinner.style.display = 'none';
                        modalHistorialContent.innerHTML = `
                            <div class="alert alert-danger">
                                <span class="material-symbols-outlined me-2">error</span>
                                ${error.message}
                            </div>
                        `;
                        modalHistorialContent.style.display = 'block';
                    });
                }
            });
        });
    </script>
@endsection
