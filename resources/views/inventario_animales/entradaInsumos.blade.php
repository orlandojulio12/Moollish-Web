@extends('layouts')

@section('title')
    Entrada de Insumos al Inventario
@endsection

@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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

        .btn-primary {
            background-color: #e49b39;
            border-color: #e49b39;
        }

        .btn-primary:hover {
            background-color: #c88428;
            border-color: #c88428;
        }

        .btn i, .btn .fas, .btn .fa {
            margin-right: 8px;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e49b39;
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

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .form-control:focus, .form-select:focus {
            border-color: #e49b39;
            box-shadow: 0 0 0 0.25rem rgba(228, 155, 57, 0.25);
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        .text-muted {
            font-size: 14px;
            color: #767676;
        }

        .form-section {
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .form-section h5, .form-section h3 {
            color: #e49b39;
            margin-bottom: 10px;
        }

        .entrada-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .remove-entrada {
            position: absolute;
            right: 10px;
            top: 10px;
            color: #dc3545;
            cursor: pointer;
            font-size: 18px;
        }

        /* Estilos para alertas */
        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 4px;
            padding: 15px 20px;
            display: none;
        }

        .alert-success-custom {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger-custom {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        /* Spinner de carga */
        .spinner-container {
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Overlay de carga */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9998;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.3s;
        }

        .loading-overlay.show {
            visibility: visible;
            opacity: 1;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #e49b39;
            animation: spin 1s linear infinite;
        }

        .badge-supplier {
            background-color: #FFF8F0;
            color: #e49b39;
            border: 1px solid #e49b39;
            font-weight: 500;
        }

        .insumo-selected {
            background-color: #FFF8F0;
            border-left: 3px solid #e49b39;
        }

        .total-container {
            background-color: #FFF8F0;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #FFDAB9;
        }

        .total-title {
            font-weight: 600;
            color: #e49b39;
            margin-bottom: 10px;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 700;
            color: #e49b39;
        }

        /* Estilos para el selector de insumos */
        .insumo-selector {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .insumo-item {
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            cursor: pointer;
            transition: all 0.2s;
        }

        .insumo-item:hover {
            background-color: #f8f9fa;
        }

        .insumo-item:last-child {
            border-bottom: none;
        }

        .insumo-item .insumo-codigo {
            font-weight: 600;
            color: #495057;
        }

        .insumo-item .insumo-nombre {
            color: #6c757d;
        }

        .insumo-item .insumo-categoria {
            font-size: 12px;
            color: #6c757d;
        }

        .insumo-search {
            margin-bottom: 15px;
        }

        /* Estilos para el componente de búsqueda */
        .search-wrapper {
            width: 100%;
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

        /* Estilos para el precio unitario calculado */
        .entrada-precio[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
            color: #6c757d;
        }

        .precio-unitario-info {
            display: block;
            font-size: 12px;
            color: #6c757d;
            margin-top: 2px;
        }

        /* Resaltar los campos obligatorios */
        .entrada-cantidad, .entrada-valor-total {
            border-color: #ced4da;
        }

        .entrada-cantidad:focus, .entrada-valor-total:focus {
            border-color: #e49b39;
            box-shadow: 0 0 0 0.25rem rgba(228, 155, 57, 0.25);
        }

        /* Animación para mostrar el cálculo del precio unitario */
        @keyframes highlight {
            0% { background-color: rgba(228, 155, 57, 0.2); }
            100% { background-color: transparent; }
        }

        .highlight-calculation {
            animation: highlight 1.5s ease-out;
        }
    </style>
@endsection

@section('content')
    <!-- Alertas flotantes -->
    <div class="alert-floating alert-success-custom" id="successAlert" style="display: none;">
        <strong>¡Éxito!</strong> <span id="successMessage"></span>
    </div>
    <div class="alert-floating alert-danger-custom" id="errorAlert" style="display: none;">
        <strong>Error:</strong> <span id="errorMessage"></span>
    </div>
    <div class="alert-floating alert-warning-custom" id="warningAlert" style="background-color: #fff3cd; border-color: #ffecb5; color: #856404; display: none;">
        <strong>¡Advertencia!</strong> <span id="warningMessage"></span>
    </div>

    <!-- Overlay de carga -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="card-custom">
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
                <h3 class="cumb active-tab">Entrada de Insumos</h3>
            </div>
            <hr>
        </div>

        <div class="card-header-container mb-4">
            <div>
                <h2 class="card-title">Entrada de Insumos al Inventario</h2>
                <p class="card-subtitle">Registre la entrada de insumos a su inventario</p>
            </div>
        </div>

        <form id="formEntradaInsumos" action="{{ route('insumos.entrada.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-section">
                        <h3>Datos de la Entrada</h3>
                        <p class="text-muted">Información general sobre la entrada de insumos</p>
                        <div class="mb-3">
                            <label for="predio_id" class="form-label required-field">Predio</label>
                            <select class="form-select" id="predio_id" name="predio_id" required>
                                <option value="">Seleccione un predio</option>
                                @if(isset($predios))
                                    @foreach($predios as $predio)
                                        <option value="{{ $predio->id }}" {{ old('predio_id') == $predio->id ? 'selected' : '' }}>{{ $predio->nombre_predio }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted">Predio al que se destinan estos insumos</small>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_entrada" class="form-label required-field">Fecha de Entrada</label>
                            <input type="date" class="form-control" id="fecha_entrada" name="fecha_entrada" required value="{{ old('fecha_entrada', date('Y-m-d')) }}">
                        </div>

                        <div class="mb-3">
                            <label for="factura_numero" class="form-label">Número de Factura</label>
                            <input type="text" class="form-control" id="factura_numero" name="factura_numero" maxlength="50" value="{{ old('factura_numero') }}">
                            <small class="text-muted">Número de la factura de compra (opcional)</small>
                        </div>

                        <div class="mb-3">
                            <label for="proveedor" class="form-label required-field">Proveedor</label>
                            <input type="text" class="form-control" id="proveedor" name="proveedor" required maxlength="100" value="{{ old('proveedor') }}">
                        </div>


                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-section">
                        <h3>Seleccionar Insumos</h3>
                        <p class="text-muted">Busque y seleccione los insumos que desea registrar en la entrada</p>

                        <div class="insumo-search mb-3">
                            <label for="buscar_insumo" class="form-label">Buscar Insumo</label>
                            <div class="search-wrapper">
                                <div class="search-input">
                                    <input type="text" id="buscar_insumo" placeholder="Buscar por código o nombre...">
                                    <div class="search-icon">
                                        <span class="material-symbols-outlined">search</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="insumo-selector" id="insumos-container">
                            <!-- Aquí se cargarán los insumos -->
                            @if(isset($insumos) && count($insumos) > 0)
                                @foreach($insumos as $insumo)
                                    <div class="insumo-item" data-id="{{ $insumo->id }}" data-codigo="{{ $insumo->codigo }}" data-nombre="{{ $insumo->nombre_comercial }}" data-unidad="{{ $insumo->unidad_medida }}">
                                        <div class="insumo-codigo">{{ $insumo->codigo }}</div>
                                        <div class="insumo-nombre">{{ $insumo->nombre_comercial }}</div>
                                        <div class="insumo-categoria">
                                            <span class="badge badge-supplier">{{ $insumo->categoria->nombre ?? 'Sin categoría' }}</span>
                                            <span class="text-muted">{{ $insumo->unidad_medida }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-3 text-center text-muted">
                                    No hay insumos registrados. <a href="{{ route('insumos.registroForm') }}">Registre un insumo primero</a>.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section mt-4">
                <h3>Insumos Seleccionados</h3>
                <p class="text-muted">Detalle las cantidades y precios de los insumos que está registrando</p>

                <div id="insumos-seleccionados-container">
                    <div class="alert alert-info" id="no-insumos-message">
                        <i class="fas fa-info-circle me-2"></i> No hay insumos seleccionados. Busque y seleccione insumos del panel derecho.
                    </div>

                    <!-- Aquí se agregarán los insumos seleccionados -->
                </div>

                <div class="total-container" id="total-container" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="total-title">Total de la Entrada:</div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="total-amount">$<span id="total-amount">0.00</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('insumos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Cancelar
                </a>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <span id="submitText">Registrar Entrada</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Definir showAlert en el ámbito global (mantener si se usa online)
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success-custom' :
                          type === 'warning' ? 'alert-warning-custom' :
                          'alert-danger-custom';

        const alertId = type === 'success' ? '#successAlert' :
                      type === 'warning' ? '#warningAlert' :
                      '#errorAlert';

        const messageId = type === 'success' ? '#successMessage' :
                        type === 'warning' ? '#warningMessage' :
                        '#errorMessage';

        // Asegurarse de que existe el alerta de warning
        if (type === 'warning' && $('#warningAlert').length === 0) {
            // Solo añade si no existe
             if ($('#warningAlert').length === 0) {
                $('.alert-floating').first().parent().prepend(`
                    <div class="alert-floating alert-warning-custom" id="warningAlert" style="background-color: #fff3cd; border-color: #ffecb5; color: #856404; display: none;">
                        <strong>¡Advertencia!</strong> <span id="warningMessage"></span>
                    </div>
                `);
             }
        }

        if ($(alertId).length && $(messageId).length) {
             $(messageId).html(message);
             $(alertId).fadeIn().delay(5000).fadeOut();
        } else {
            // Evitar alert nativo
             console.warn(`[showAlert Fallback] ${type.toUpperCase()}: ${message}`);
        }
    }

    // Variables para IndexedDB - Comentado - Offline
    // let registroInsumosDB;
    // const STORE_NAME = 'pending_entradas';

    // Estado inicial de la conexión - Comentado - Offline
    // console.log("Estado inicial:", navigator.onLine ? "Online" : "Offline");

    // Inicializar IndexedDB usando la configuración centralizada - Comentado - Offline
    /*
    MoollishDB.initialize()
        .then(db => {
            registroInsumosDB = db;
            console.log('IndexedDB inicializada para entrada de insumos');
            return updatePendingCounter();
        })
        .then(() => {
            if (navigator.onLine) {
                return syncPendingEntradas();
            }
        })
        .catch(error => {
            console.error('Error al inicializar IndexedDB:', error);
            showAlert('error', 'Error al inicializar el almacenamiento offline');
        });
    */

    // Función para guardar una entrada en IndexedDB - Comentado - Offline
    /*
    function saveEntradaOffline(formData) {
        const entradaData = {};
        for (const [key, value] of formData.entries()) {
            entradaData[key] = value;
        }
        return MoollishDB.saveData(registroInsumosDB, STORE_NAME, entradaData);
    }
    */

    // Obtener todas las entradas pendientes - Comentado - Offline
    /*
    function getPendingEntradas() {
        return MoollishDB.getPendingData(registroInsumosDB, STORE_NAME);
    }
    */

    // Eliminar una entrada pendiente por ID - Comentado - Offline
    /*
    function removePendingEntrada(id) {
        return MoollishDB.removeData(registroInsumosDB, STORE_NAME, id);
    }
    */

    // Sincronizar entradas pendientes cuando hay conexión - Comentado - Offline
    /*
    async function syncPendingEntradas() {
        if (!navigator.onLine) {
            console.log('Sin conexión, no se puede sincronizar');
            return;
        }

        try {
            const pendingEntradas = await getPendingEntradas();
            console.log(`Intentando sincronizar ${pendingEntradas.length} entradas pendientes`);

            for (const entrada of pendingEntradas) {
                try {
                    const formData = new FormData();
                    for (const key in entrada) {
                        if (key !== 'id' && key !== 'timestamp') {
                            formData.append(key, entrada[key]);
                        }
                    }

                    const response = await $.ajax({
                        url: "{{ route('insumos.entrada.store') }}",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    if (response.success) {
                        await removePendingEntrada(entrada.id);
                        console.log('Entrada sincronizada correctamente:', entrada.id);
                    }
                } catch (error) {
                    console.error('Error al sincronizar entrada:', entrada.id, error);
                }
            }

            const remainingEntradas = await getPendingEntradas();
            if (remainingEntradas.length === 0) {
                showAlert('success', 'Todas las entradas han sido sincronizadas');
            } else {
                showAlert('warning', `${remainingEntradas.length} entradas aún pendientes de sincronización`);
            }
        } catch (error) {
            console.error('Error durante la sincronización:', error);
            showAlert('error', 'Error durante la sincronización');
        }
    }
    */

    // Mostrar contador de pendientes - Comentado - Offline
    /*
    async function updatePendingCounter() {
        try {
            const pendingEntradas = await getPendingEntradas();
            if (pendingEntradas.length > 0) {
                // Crear o actualizar el badge de pendientes
                let badge = $('#pending-badge');
                if (badge.length === 0) {
                    $('.card-custom').prepend(`
                        <div id="pending-badge" class="alert alert-warning" style="margin-bottom: 20px;">
                            <strong>${pendingEntradas.length} registro(s) pendiente(s) de sincronización</strong>
                            <button id="sync-now" class="btn btn-sm btn-primary float-end">Sincronizar ahora</button>
                        </div>
                    `);

                    // Añadir event listener al botón de sincronización
                    $('#sync-now').on('click', function() {
                        syncPendingEntradas();
                    });
                } else {
                    badge.find('strong').text(`${pendingEntradas.length} registro(s) pendiente(s) de sincronización`);
                }
            } else {
                // Eliminar el badge si no hay pendientes
                $('#pending-badge').remove();
            }
        } catch (error) {
            console.error('Error al actualizar contador de pendientes:', error);
        }
    }
    */

    // Event listeners para conexión - Comentado - Offline
    /*
    window.addEventListener('online', function() {
        console.log("Conexión restablecida");
        showAlert('success', 'Conexión restablecida. Sincronizando datos...');
        syncPendingEntradas();
    });

    window.addEventListener('offline', function() {
        console.log("Conexión perdida");
        showAlert('warning', 'Sin conexión. Los registros se guardarán localmente.');
    });
    */

    // Exponer solo las funciones necesarias al ámbito global - Comentado - Offline
    /*
    window.entradaInsumos = {
        saveOffline: saveEntradaOffline,
        syncPending: syncPendingEntradas,
        showAlert: showAlert
    };
    */

    $(document).ready(function() {
        // Variables para el contador y total
        let counter = 0;
        let total = 0;

        // Función para calcular el total
        function calcularTotal() {
            total = 0;
            $('.entrada-valor-total').each(function() {
                const valorTotal = parseFloat($(this).val()) || 0;
                total += valorTotal;
            });
            $('#total-amount').text(total.toFixed(2));
        }

        // Seleccionar un insumo
        $(document).on('click', '.insumo-item', function() {
            const insumoId = $(this).data('id');
            const insumoCodigo = $(this).data('codigo');
            const insumoNombre = $(this).data('nombre');
            const insumoUnidad = $(this).data('unidad');

            // Comprobar si ya está seleccionado
            if ($(this).hasClass('insumo-selected')) {
                return;
            }

            // Marcar como seleccionado
            $(this).addClass('insumo-selected');

            // Crear elemento de entrada para este insumo
            const entradaHtml = `
                <div class="entrada-item" data-id="${insumoId}">
                    <span class="remove-entrada"><i class="fas fa-times-circle"></i></span>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Insumo</label>
                                <input type="hidden" name="entradas[${counter}][insumo_id]" value="${insumoId}">
                                <div class="form-control-plaintext">
                                    <strong>${insumoCodigo}</strong> - ${insumoNombre}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label required-field">Cantidad</label>
                                <div class="input-group">
                                    <input type="number" class="form-control entrada-cantidad" id="cantidad-${counter}"
                                        name="entradas[${counter}][cantidad]" step="0.01" min="0.01" required>
                                    <span class="input-group-text">${insumoUnidad}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label required-field">Valor Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control entrada-valor-total" id="valor-total-${counter}"
                                        name="entradas[${counter}][valor_total]" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Precio Unitario</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control entrada-precio" id="precio-${counter}"
                                        name="entradas[${counter}][precio]" step="0.01" min="0" required readonly>
                                </div>
                                <small style="display:none" class="text-muted precio-unitario-info" id="precio-info-${counter}">Calculado automáticamente</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Fecha Vencimiento</label>
                                <input type="date" class="form-control"
                                    name="entradas[${counter}][fecha_vencimiento]">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Notas</label>
                                <input type="text" class="form-control"
                                    name="entradas[${counter}][notas]" placeholder="Notas sobre este insumo (opcional)">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Actualizar UI
            $('#no-insumos-message').hide();
            $('#total-container').show();
            $('#insumos-seleccionados-container').append(entradaHtml);
            counter++;
        });

        // Eliminar insumo de la lista
        $(document).on('click', '.remove-entrada', function() {
            const $item = $(this).closest('.entrada-item');
            const insumoId = $item.data('id');

            // Quitar la marca de seleccionado
            $(`.insumo-item[data-id="${insumoId}"]`).removeClass('insumo-selected');

            // Eliminar el item
            $item.remove();

            // Mostrar mensaje si no hay insumos
            if ($('.entrada-item').length === 0) {
                $('#no-insumos-message').show();
                $('#total-container').hide();
            }

            calcularTotal();
        });

        // Calcular precio unitario cuando cambian cantidad o valor total
        $(document).on('input', '.entrada-cantidad, .entrada-valor-total', function() {
            const $item = $(this).closest('.entrada-item');
            const cantidad = parseFloat($item.find('.entrada-cantidad').val()) || 0;
            const valorTotal = parseFloat($item.find('.entrada-valor-total').val()) || 0;

            if (cantidad > 0 && valorTotal > 0) {
                // Calcular precio unitario
                const precioUnitario = valorTotal / cantidad;
                const $camposPrecio = $item.find('.entrada-precio, .precio-unitario-info');

                // Actualizar el campo de precio unitario
                $item.find('.entrada-precio').val(precioUnitario.toFixed(2));

                // Actualizar el mensaje informativo
                const unidad = $item.find('.input-group-text').text();
                $item.find('.precio-unitario-info').text(`${precioUnitario.toFixed(2)} por ${unidad}`);

                // Añadir efecto visual para indicar que se ha calculado
                $camposPrecio.addClass('highlight-calculation');
                setTimeout(() => {
                    $camposPrecio.removeClass('highlight-calculation');
                }, 1500);
            } else {
                // Resetear si alguno de los valores es cero
                $item.find('.entrada-precio').val('');
                $item.find('.precio-unitario-info').text('Calculado automáticamente');
            }

            // Recalcular el total
            calcularTotal();
        });

        // Búsqueda de insumos
        $('#buscar_insumo').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.insumo-item').each(function() {
                const codigo = $(this).data('codigo').toLowerCase();
                const nombre = $(this).data('nombre').toLowerCase();

                if (codigo.includes(searchTerm) || nombre.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Envío del formulario con manejo offline
        $('#formEntradaInsumos').submit(function(e) {
            e.preventDefault();

            // Verificar que haya al menos un insumo
            if ($('.entrada-item').length === 0) {
                showAlert('error', 'Debe agregar al menos un insumo a la entrada');
                return;
            }

            // Verificar que todos los insumos tengan valores completos
            let datosCompletos = true;
            $('.entrada-item').each(function() {
                const cantidad = parseFloat($(this).find('.entrada-cantidad').val()) || 0;
                const valorTotal = parseFloat($(this).find('.entrada-valor-total').val()) || 0;

                if (cantidad <= 0 || valorTotal <= 0) {
                    datosCompletos = false;
                }

                // Asegurarse de que el precio unitario esté calculado y almacenado
                const precioUnitario = valorTotal / cantidad;
                $(this).find('.entrada-precio').val(precioUnitario.toFixed(2));
            });

            if (!datosCompletos) {
                showAlert('error', 'Todos los insumos deben tener cantidad y valor total');
                return;
            }

            // Desactivar botón de envío y mostrar loader dentro del botón
            $('#submitBtn').prop('disabled', true);
            $('#submitText').html('<span class="spinner" style="display:inline-block; width:16px; height:16px; border:2px solid rgba(255,255,255,.3); border-radius:50%; border-top-color:#fff; animation:spin 1s linear infinite;"></span> Guardando...');

            // Mostrar overlay de carga
            $('#loadingOverlay').addClass('show');

            // Recoger datos del formulario
            let formData = new FormData(this);

            // Verificar el estado de la conexión - Comentado - Offline
            /*
            if (!navigator.onLine) {
                // OFFLINE: Guardar localmente
                window.entradaInsumos.saveOffline(formData)
                    .then(id => {
                        // Ocultar overlay
                        $('#loadingOverlay').removeClass('show');

                        // Resetear el formulario
                        $('#formEntradaInsumos')[0].reset();
                        $('#insumos-seleccionados-container').find('.entrada-item').remove();
                        $('.insumo-item').removeClass('insumo-selected');
                        $('#no-insumos-message').show();
                        $('#total-container').hide();
                        counter = 0;
                        total = 0;
                        $('#total-amount').text('0.00');

                        // Mostrar mensaje de éxito
                        showAlert('success', 'Entrada guardada localmente. Se sincronizará cuando haya conexión.');

                        // Restaurar botón
                        resetSubmitButton();
                    })
                    .catch(error => {
                        // Ocultar overlay
                        $('#loadingOverlay').removeClass('show');

                        console.error('Error al guardar localmente:', error);
                        showAlert('error', 'Error al guardar localmente: ' + error);
                        resetSubmitButton();
                    });
                return;
            }
            */

            // ONLINE: Enviar al servidor mediante AJAX (Siempre se ejecuta ahora)
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Ocultar overlay
                    $('#loadingOverlay').removeClass('show');

                    console.log("Respuesta del servidor:", response);

                    if (response.success) {
                        // Resetear el formulario
                        $('#formEntradaInsumos')[0].reset();
                        $('#insumos-seleccionados-container').find('.entrada-item').remove();
                        $('.insumo-item').removeClass('insumo-selected');
                        $('#no-insumos-message').show();
                        $('#total-container').hide();
                        counter = 0;
                        total = 0;
                        $('#total-amount').text('0.00');

                        // Mostrar mensaje de éxito usando la función global
                        // window.entradaInsumos.showAlert('success', response.message || 'Entrada de insumos registrada correctamente'); // Comentado - Offline
                        showAlert('success', response.message || 'Entrada de insumos registrada correctamente'); // Mantener alerta online

                        // Redireccionar después de 2 segundos
                        setTimeout(function() {
                            window.location.href = response.redirect || "{{ route('insumos.index') }}";
                        }, 2000);
                    } else {
                        // Si la respuesta es exitosa pero indica un error en la lógica
                        // window.entradaInsumos.showAlert('error', response.message || 'Error al registrar la entrada de insumos'); // Comentado - Offline
                        showAlert('error', response.message || 'Error al registrar la entrada de insumos'); // Mantener alerta online
                        resetSubmitButton();
                    }
                },
                error: function(xhr, status, error) {
                    // Ocultar overlay
                    $('#loadingOverlay').removeClass('show');

                    console.error("Error en la solicitud:", xhr, status, error);

                    // Restaurar botón
                    resetSubmitButton();

                    // Determinar qué tipo de error ocurrió
                    let errorMsg = 'Ha ocurrido un error al procesar la solicitud.';

                    if (xhr.responseJSON) {
                        console.log("Detalles del error:", xhr.responseJSON);

                        if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            const errorList = Object.values(xhr.responseJSON.errors).flat();
                            errorMsg = errorList.join('<br>');

                            // Marcar campos con error
                            markInvalidFields(xhr.responseJSON.errors);
                        }
                    }
                    // window.entradaInsumos.showAlert('error', errorMsg); // Comentado - Offline
                    showAlert('error', errorMsg); // Mantener alerta online
                }
            });
        });

        // Función para resetear el botón
        function resetSubmitButton() {
            $('#submitBtn').prop('disabled', false);
            $('#submitText').html('Registrar Entrada');
        }

        // Función para marcar campos inválidos
        function markInvalidFields(errors) {
            // Limpiar errores anteriores
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            // Marcar campos con errores
            Object.keys(errors).forEach(function(field) {
                const message = errors[field][0];
                let input = $(`[name="${field}"]`);

                // Manejar campos complejos como arrays
                if (field.includes('.') || field.includes('[')) {
                    const fieldName = field.replace(/[\[\]\.]/g, '\\$&'); // Escapar caracteres especiales
                    input = $(`[name="${fieldName}"]`);
                }

                if (input.length) {
                    input.addClass('is-invalid');
                    input.after(`<div class="invalid-feedback">${message}</div>`);
                }
            });

            // Scroll al primer error
            if ($('.is-invalid').length) {
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
            }
        }
    });
    </script>
@endsection
