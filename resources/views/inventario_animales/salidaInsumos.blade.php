@extends('layouts')

{{-- @extends('inventario_animales.navbar') --}}

@section('title')
    Registro de Salida de Insumos
@endsection

@section('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
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

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .btn-primary {
            background-color: #e49b39;
            border-color: #e49b39;
        }

        .btn-primary:hover {
            background-color: #d38b29;
            border-color: #d38b29;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .alert {
            border-radius: 8px;
        }

        .input-dinamico-animal {
            display: flex;
            width: 100%;
            border: 1px solid rgb(229, 231, 235);
            border-radius: 5px;
            height: 46px;
            padding: 0px 0px 0px 12px;
            align-items: center;
            justify-content: space-between;
        }

        .buton-dinamico-animal {
            color: #e49b39;
            border: 0px solid;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 9px 16px;
            border-radius: 0px 5px 5px 0px;
            background: transparent;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
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
            border: 5px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #e49b39;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .form-section {
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .form-section h5,
        .form-section h3 {
            color: #e49b39;
            margin-bottom: 10px;
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        .text-muted {
            font-size: 14px;
            color: #767676;
        }

        .animal-selection-container,
        .potrero-selection-container,
        .lote-selection-container {
            display: none;
            /* Inicialmente ocultos */
        }

        /* Alertas flotantes */
        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

        .insumo-selected {
            background-color: #FFF8F0;
            border-left: 3px solid #e49b39;
        }

        .insumo-search {
            margin-bottom: 15px;
        }

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

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }

        .is-invalid~.invalid-feedback {
            display: block;
        }

        .form-select {
            width: 100%;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            appearance: revert;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-select:focus {
            border-color: #e49b39;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(228, 155, 57, 0.25);
        }
    </style>
@endsection

@section('content')
    <!-- Alertas flotantes -->
    <div class="alert-floating alert-success-custom" id="successAlert">
        <strong>¡Éxito!</strong> <span id="successMessage"></span>
    </div>
    <div class="alert-floating alert-danger-custom" id="errorAlert">
        <strong>Error:</strong> <span id="errorMessage"></span>
    </div>

    <!-- Overlay de carga -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="card-custom">
        <!-- Breadcrumb -->
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
                <h3 class="cumb active-tab">Salida de Insumos</h3>
            </div>
            <hr>
        </div>

        <div class="card-header-container mb-4">
            <div>
                <h2 class="card-title">Registro de Salida de Insumos</h2>
                <p class="card-subtitle">Registre la salida de insumos de su inventario</p>
            </div>
        </div>

        <form id="salidaInsumoForm" method="POST" action="{{ route('insumos.salida.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-section">
                        <h3>Datos de la Salida</h3>
                        <p class="text-muted">Información general sobre la salida de insumos</p>

                        <!-- Predio -->
                        <div class="mb-3">
                            <label for="predio_id" class="form-label required-field">Predio</label>
                            <select class="form-control" id="predio_id" name="predio_id" required>
                                <option value="">Seleccione un predio</option>
                                @foreach ($predios as $predio)
                                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Predio del que salen estos insumos</small>
                        </div>

                        <!-- Fecha de aplicación -->
                        <div class="mb-3">
                            <label for="fecha_aplicacion" class="form-label required-field">Fecha de Aplicación</label>
                            <input type="date" class="form-control" id="fecha_aplicacion" name="fecha_aplicacion"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Buscador de insumos -->
                        <div class="insumo-search mb-3">
                            <label for="buscar_insumo" class="form-label">Buscar Insumo</label>
                            <div class="search-wrapper">
                                <div class="search-input">
                                    <input type="text" id="buscar_insumo" placeholder="Buscar por código o nombre..."
                                        disabled>
                                    <div class="search-icon">
                                        <span class="material-symbols-outlined">search</span>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Seleccione primero un predio</small>
                        </div>

                        <!-- Selector de insumos -->
                        <div class="mb-3">
                            <label class="form-label required-field">Seleccionar Insumo</label>
                            <div class="insumo-selector" id="insumos-container" style="height: 200px;">
                                <div class="p-3 text-center text-muted">
                                    Seleccione un predio para ver los insumos disponibles
                                </div>
                            </div>
                            <input type="hidden" id="insumo_id" name="insumo_id" required>
                            <div class="invalid-feedback">
                                Debe seleccionar un insumo
                            </div>
                        </div>

                        <!-- Stock disponible (informativo) -->
                        <div class="mb-3">
                            <label for="stock_disponible" class="form-label">Stock Disponible</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="stock_disponible" readonly>
                                <span class="input-group-text" id="unidad_medida_texto">-</span>
                            </div>
                            <small class="text-muted">Cantidad disponible en el inventario</small>
                        </div>

                        <!-- Cantidad a aplicar -->
                        <div class="mb-3">
                            <label for="cantidad_aplicada" class="form-label required-field">Cantidad a Aplicar</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0.01" class="form-control"
                                    id="cantidad_aplicada" name="cantidad_aplicada" required>
                                <span class="input-group-text" id="unidad_medida_texto2">-</span>
                            </div>
                            <div class="invalid-feedback" id="cantidad_feedback">
                                La cantidad no puede ser mayor al stock disponible
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-section">
                        <h3>Destino del Insumo</h3>
                        <p class="text-muted">Indique a dónde se aplicará el insumo</p>

                        <!-- Tipo de aplicación (Animal, Potrero, Lote) -->
                        <div class="mb-3">
                            <label class="form-label required-field">Aplicar a</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_aplicacion"
                                        id="tipo_animal" value="animal">
                                    <label class="form-check-label" for="tipo_animal">Animal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_aplicacion"
                                        id="tipo_potrero" value="potrero">
                                    <label class="form-check-label" for="tipo_potrero">Potrero</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_aplicacion" id="tipo_lote"
                                        value="lote">
                                    <label class="form-check-label" for="tipo_lote">Lote</label>
                                </div>
                            </div>
                        </div>

                        <!-- Contenedor para selección de animal -->
                        <div class="mb-3 animal-selection-container" style="display: none;">
                            <label for="animal_id" class="form-label required-field">Animal</label>
                            <div class="input-dinamico-animal">
                                <input type="hidden" id="id_animal" name="animal_id">
                                <span id="animalSeleccionado">Seleccione un animal</span>

                                <button type="button" class="buton-dinamico-animal" data-popup="id_animal">
                                    <i class="material-icons">search</i>
                                </button>
                            </div>
                        </div>
                        @include('components.selector-animales', [
                            'predios' => $predios,
                            'animales' => $animales,
                        ])


                        <!-- Contenedor para selección de potrero -->
                        <div class="mb-3 potrero-selection-container" style="display: none;">
                            <label for="potrero_id" class="form-label required-field">Potrero</label>
                            <select class="form-control" id="potrero_id" name="potrero_id">
                                <option value="">Seleccione primero un predio</option>
                            </select>
                        </div>

                        <!-- Contenedor para selección de lote -->
                        <div class="mb-3 lote-selection-container" style="display: none;">
                            <label for="lote_id" class="form-label required-field">Lote</label>
                            <select class="form-control" id="lote_id" name="lote_id">
                                <option value="">Seleccione primero un predio</option>
                            </select>
                        </div>

                        <!-- Vía de administración -->
                        <div class="mb-3">
                            <label for="via_administracion" class="form-label">Vía de Administración</label>
                            <select class="form-control" id="via_administracion" name="via_administracion">
                                <option value="">Seleccione</option>
                                <option value="Oral">Oral</option>
                                <option value="Intramuscular">Intramuscular</option>
                                <option value="Intravenosa">Intravenosa</option>
                                <option value="Subcutánea">Subcutánea</option>
                                <option value="Tópica">Tópica</option>
                                <option value="Intramamaria">Intramamaria</option>
                                <option value="Intrauterina">Intrauterina</option>
                                <option value="Rectal">Rectal</option>
                                <option value="Otra">Otra</option>
                            </select>
                        </div>

                        <!-- Hora de aplicación -->
                        <div class="mb-3">
                            <label for="hora_aplicacion" class="form-label">Hora de Aplicación</label>
                            <input type="time" class="form-control" id="hora_aplicacion" name="hora_aplicacion"
                                value="{{ date('H:i') }}">
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('insumos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Cancelar
                </a>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <span id="submitText">Registrar Salida</span>
                </button>
            </div>
        </form>
    </div>
@endsection

{{-- @section('popup')
@endsection
 --}}
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>


         // Función global para mostrar alertas (mantenerla activa si es necesaria para el modo online)
         function showAlert(type, message) {
             const alertId = type === 'success' ? '#successAlert' :
                           type === 'error' ? '#errorAlert' :
                           '#warningAlert';
             const messageId = type === 'success' ? '#successMessage' :
                            type === 'error' ? '#errorMessage' :
                            '#warningMessage';

             if ($(alertId).length && $(messageId).length) {
                 $(messageId).html(message);
                 $(alertId).fadeIn().delay(5000).fadeOut();
             } else {
                  // Evitar alert nativo
                  console.warn(`[showAlert Fallback] ${type.toUpperCase()}: ${message}`);
             }
         }

        $(document).ready(function() {
            let insumosDisponibles = [];
            let insumoSeleccionado = null;
            let stockDisponible = 0;

            // Mostrar/ocultar alertas (renombrada para evitar colisión)
            function mostrarAlertaSalida(tipo, mensaje) {
                 showAlert(tipo, mensaje); // Llamar a la función global showAlert
            }

            // Mostrar/ocultar overlay de carga
            function toggleLoading(show) {
                if (show) {
                    $('#loadingOverlay').addClass('show');
                } else {
                    $('#loadingOverlay').removeClass('show');
                }
            }

            // Cargar insumos disponibles según el predio seleccionado
            $('#predio_id').on('change', function() {
                const predioId = $(this).val();

                // Resetear campos dependientes
                $('#insumos-container').html('<div class="p-3 text-center text-muted">Seleccione un predio para ver los insumos disponibles</div>');
                $('#buscar_insumo').prop('disabled', true).val('');
                $('#insumo_id').val('');
                $('#stock_disponible').val('');
                $('#unidad_medida_texto, #unidad_medida_texto2').text('-');
                $('#cantidad_aplicada').val('').removeClass('is-invalid');
                insumoSeleccionado = null;
                stockDisponible = 0;
                insumosDisponibles = [];
                // Resetear potreros y lotes también
                $('#potrero_id').empty().append('<option value="">Seleccione primero un predio</option>');
                $('#lote_id').empty().append('<option value="">Seleccione primero un predio</option>');


                if (!predioId) {
                    return;
                }

                toggleLoading(true);
                console.log('Cargando insumos para el predio ID:', predioId);

                // Cargar insumos del predio seleccionado
                $.ajax({
                    url: "{{ route('insumos.porPredio', ':predioId') }}".replace(':predioId',
                        predioId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Respuesta del servidor (insumos):', response);

                        if (response.success) {
                            insumosDisponibles = response.data || [];
                            renderizarInsumos(insumosDisponibles);
                            $('#buscar_insumo').prop('disabled', false);
                        } else {
                            mostrarAlertaSalida('error', response.message ||
                                'Error al cargar los insumos');
                            $('#insumos-container').html(
                                '<div class="p-3 text-center text-muted">Error al cargar los insumos</div>'
                                );
                        }
                     },
                     error: function(xhr, status, error) {
                         console.error('Error al cargar insumos:', error, xhr.responseText);
                         mostrarAlertaSalida('error', 'Error al cargar insumos del predio');
                         $('#insumos-container').html(
                             '<div class="p-3 text-center text-muted">Error al cargar los insumos</div>'
                             );
                         toggleLoading(false); // Asegurarse de ocultar el loading en error
                     },
                     complete: function() {
                         // No ocultar loading aquí, esperar a potreros y lotes
                     }
                });

                 // También cargar potreros del predio seleccionado
                 $.ajax({
                     url: "{{ route('potreros.porPredio', ':predioId') }}"
                         .replace(':predioId', predioId),
                     type: 'GET',
                     dataType: 'json',
                     success: function(potreros) {
                         console.log('Potreros recibidos:', potreros);
                         $('#potrero_id').empty().append(
                             '<option value="">Seleccione un potrero</option>'
                             );
                         potreros.forEach(function(potrero) {
                             $('#potrero_id').append(
                                 `<option value="${potrero.id}">${potrero.nombre}</option>`
                                 );
                         });
                     },
                     error: function(xhr, status, error) {
                         console.error('Error al cargar potreros:', error,
                             xhr.responseText);
                         mostrarAlertaSalida('error', 'Error al cargar potreros');
                     }
                 });

                 // Cargar lotes del predio seleccionado
                 $.ajax({
                     url: "{{ route('lotes.porPredio', ':predioId') }}".replace(
                         ':predioId', predioId),
                     type: 'GET',
                     dataType: 'json',
                     success: function(lotes) {
                         console.log('Lotes recibidos:', lotes);
                         $('#lote_id').empty().append(
                             '<option value="">Seleccione un lote</option>'
                             );
                         lotes.forEach(function(lote) {
                             $('#lote_id').append(
                                 `<option value="${lote.id}">${lote.nombre}</option>`
                                 );
                         });
                     },
                     error: function(xhr, status, error) {
                         console.error('Error al cargar lotes:', error, xhr
                             .responseText);
                         mostrarAlertaSalida('error', 'Error al cargar lotes');
                     },
                     complete: function() {
                         toggleLoading(false); // Ocultar loading después de cargar todo
                     }
                 });
            });

            // Renderizar lista de insumos
            function renderizarInsumos(insumos) {
                if (insumos.length === 0) {
                    $('#insumos-container').html(
                        '<div class="p-3 text-center text-muted">No hay insumos disponibles en este predio</div>'
                        );
                    return;
                }

                let html = '';
                insumos.forEach(function(insumo) {
                    if (insumo.stock > 0) {
                        html += `
                    <div class="insumo-item" data-id="${insumo.id}" data-stock="${insumo.stock}" data-unidad="${insumo.unidad_medida}">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="insumo-codigo">${insumo.codigo}</span> -
                                <span class="insumo-nombre">${insumo.nombre}</span>
                            </div>
                            <div>
                                <strong>${insumo.stock}</strong> ${insumo.unidad_medida}
                            </div>
                        </div>
                        <div class="insumo-categoria">${insumo.categoria}</div>
                    </div>
                    `;
                    }
                });

                if (html === '') {
                    $('#insumos-container').html(
                        '<div class="p-3 text-center text-muted">No hay insumos con stock disponible en este predio</div>'
                        );
                } else {
                    $('#insumos-container').html(html);
                }
            }

            // Búsqueda de insumos
            $('#buscar_insumo').on('input', function() {
                const query = $(this).val().toLowerCase();

                if (query === '') {
                    renderizarInsumos(insumosDisponibles);
                    return;
                }

                const filtrados = insumosDisponibles.filter(function(insumo) {
                    return insumo.nombre.toLowerCase().includes(query) ||
                        insumo.codigo.toLowerCase().includes(query) ||
                        insumo.categoria.toLowerCase().includes(query);
                });

                renderizarInsumos(filtrados);
            });

            // Seleccionar insumo
            $(document).on('click', '.insumo-item', function() {
                const insumoId = $(this).data('id');
                stockDisponible = parseFloat($(this).data('stock'));
                const unidadMedida = $(this).data('unidad');

                // Quitar selección anterior
                $('.insumo-item').removeClass('insumo-selected');
                $(this).addClass('insumo-selected');

                // Guardar insumo seleccionado
                insumoSeleccionado = insumosDisponibles.find(i => i.id == insumoId);
                $('#insumo_id').val(insumoId);

                // Actualizar información de stock
                $('#stock_disponible').val(stockDisponible);
                $('#unidad_medida_texto, #unidad_medida_texto2').text(unidadMedida);

                // Limpiar y habilitar cantidad
                $('#cantidad_aplicada').val('').removeClass('is-invalid');
                $('#insumos-container').removeClass('is-invalid'); // Limpiar posible error de validación
            });

            // Validar cantidad aplicada
            $('#cantidad_aplicada').on('input', function() {
                const cantidad = parseFloat($(this).val()) || 0;
                const $feedback = $('#cantidad_feedback');

                $(this).removeClass('is-invalid'); // Limpiar error al escribir

                if (cantidad <= 0) {
                    $(this).addClass('is-invalid');
                    $feedback.text('La cantidad debe ser mayor a 0');
                    return;
                }

                if (cantidad > stockDisponible) {
                    $(this).addClass('is-invalid');
                    $feedback.text(
                        `La cantidad no puede ser mayor al stock disponible (${stockDisponible})`);
                } else {
                    //$(this).removeClass('is-invalid'); // Ya se hizo al inicio
                    $feedback.text(''); // Limpiar texto de error si es válido
                }
            });

            // Mostrar/ocultar selectores según tipo de aplicación
            $('input[name="tipo_aplicacion"]').on('change', function() {
                const tipo = $(this).val();
                console.log('Tipo de aplicación cambiado a:', tipo);

                // Ocultar todos los contenedores y limpiar errores/valores
                $('.animal-selection-container, .potrero-selection-container, .lote-selection-container')
                    .hide();
                $('#id_animal, #potrero_id, #lote_id').val(''); // Limpiar valores
                $('#animalSeleccionado').text('Seleccione un animal'); // Resetear texto animal

                // Mostrar el contenedor correspondiente y requerir su campo
                if (tipo === 'animal') {
                    $('.animal-selection-container').show();
                    $('#id_animal').prop('required', true);
                    $('#potrero_id, #lote_id').prop('required', false);
                    console.log('Mostrando contenedor de animal');
                } else if (tipo === 'potrero') {
                    $('.potrero-selection-container').show();
                    $('#potrero_id').prop('required', true);
                    $('#id_animal, #lote_id').prop('required', false);
                    console.log('Mostrando contenedor de potrero');
                } else if (tipo === 'lote') {
                    $('.lote-selection-container').show();
                    $('#lote_id').prop('required', true);
                    $('#id_animal, #potrero_id').prop('required', false);
                    console.log('Mostrando contenedor de lote');
                }

                // Verificar visibilidad después del cambio
                console.log('Visibilidad después del cambio:');
                console.log('Animal:', $('.animal-selection-container').is(':visible'));
                console.log('Potrero:', $('.potrero-selection-container').is(':visible'));
                console.log('Lote:', $('.lote-selection-container').is(':visible'));
            });

            // Actualizar la etiqueta del animal seleccionado
            $(document).on('animal:seleccionado', function(e, animal) {
                $('#animalSeleccionado').text(`${animal.codigo} - ${animal.nombre || 'Sin nombre'}`);
            });

            // Al cargar la página, forzamos un evento change en predio_id para cargar los datos iniciales si hay un predio seleccionado
            if ($('#predio_id').val()) {
                $('#predio_id').trigger('change');
            }

            // Envío del formulario con manejo offline
            $('#salidaInsumoForm').on('submit', function(e) {
                e.preventDefault();

                // --- VALIDACIONES ---
                let isValid = true;

                // Validar insumo seleccionado
                if (!$('#insumo_id').val()) {
                    $('#insumos-container').addClass('is-invalid');
                    mostrarAlertaSalida('error', 'Debe seleccionar un insumo');
                    isValid = false;
                } else {
                     $('#insumos-container').removeClass('is-invalid');
                }

                // Validar cantidad
                const cantidad = parseFloat($('#cantidad_aplicada').val()) || 0;
                 const $cantidadInput = $('#cantidad_aplicada');
                 const $cantidadFeedback = $('#cantidad_feedback');
                 $cantidadInput.removeClass('is-invalid'); // Limpiar primero
                if (cantidad <= 0) {
                    $cantidadInput.addClass('is-invalid');
                    $cantidadFeedback.text('La cantidad debe ser mayor a 0');
                    isValid = false;
                } else if (cantidad > stockDisponible) {
                    $cantidadInput.addClass('is-invalid');
                     $cantidadFeedback.text(`La cantidad no puede ser mayor al stock disponible (${stockDisponible})`);
                    isValid = false;
                } else {
                     $cantidadFeedback.text('');
                 }

                // Validar tipo de aplicación
                const tipoAplicacion = $('input[name="tipo_aplicacion"]:checked').val();
                if (!tipoAplicacion) {
                    mostrarAlertaSalida('error',
                        'Debe seleccionar el tipo de aplicación (Animal, Potrero o Lote)');
                     isValid = false;
                }

                // Validar selección según tipo
                if (tipoAplicacion === 'animal' && !$('#id_animal').val()) {
                    mostrarAlertaSalida('error', 'Debe seleccionar un animal');
                    // Podrías añadir una clase de error al contenedor de animal si lo deseas
                     isValid = false;
                } else if (tipoAplicacion === 'potrero' && !$('#potrero_id').val()) {
                    mostrarAlertaSalida('error', 'Debe seleccionar un potrero');
                     $('#potrero_id').addClass('is-invalid');
                     isValid = false;
                } else if (tipoAplicacion === 'lote' && !$('#lote_id').val()) {
                    mostrarAlertaSalida('error', 'Debe seleccionar un lote');
                     $('#lote_id').addClass('is-invalid');
                     isValid = false;
                 }

                 // Si algo no es válido, detener el envío
                 if (!isValid) {
                     // Scroll al primer error visible (ej. cantidad o select de potrero/lote)
                     if ($('.is-invalid').length > 0) {
                         $('html, body').animate({
                             scrollTop: $('.is-invalid:first').offset().top - 100
                         }, 500);
                     }
                     return;
                 }
                 // --- FIN VALIDACIONES ---

                // Mostrar overlay de carga
                toggleLoading(true);
                $('#submitBtn').prop('disabled', true);
                $('#submitText').text('Procesando...');

                // Crear FormData
                const formData = new FormData(this);

                   $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                mostrarAlertaSalida('success', response.message ||
                                    'Salida de insumo registrada correctamente');
                                setTimeout(() => {
                                    window.location.href =
                                        "{{ route('insumos.index') }}";
                                }, 1500);
                            } else {
                                mostrarAlertaSalida('error', response.message ||
                                    'Error al registrar la salida de insumo');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al procesar la solicitud';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                const firstError = Object.values(errors)[0];
                                errorMsg = Array.isArray(firstError) ? firstError[0] :
                                    firstError;
                            }
                            mostrarAlertaSalida('error', errorMsg);
                        },
                        complete: function() {
                            toggleLoading(false);
                            $('#submitBtn').prop('disabled', false);
                            $('#submitText').text('Registrar Salida');
                        }
                    });
                // }
            });
        });
    </script>
@endsection
