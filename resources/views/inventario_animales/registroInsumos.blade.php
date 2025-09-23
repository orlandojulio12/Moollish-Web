@extends('layouts')

@section('title')
    Registro de Insumos
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

        .nav-tabs .nav-link.active {
            color: #fff;
            background-color: #e49b39;
            border-color: #e49b39;
            font-weight: bold;
        }

        .nav-tabs .nav-link {
            color: #495057;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #e49b39;
            border-color: #e49b39;
        }

        .btn-primary:hover {
            background-color: #c88428;
            border-color: #c88428;
        }

        /* Añadir espacio entre ícono y texto en botones */
        .btn i, .btn .fas, .btn .fa {
            margin-right: 8px;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e49b39;
        }

        .badge-success {
            background-color: #28a74533;
            color: #28a745;
        }

        .badge-warning {
            background-color: #ffc10733;
            color: #d39e00;
        }

        .badge-danger {
            background-color: #dc354533;
            color: #dc3545;
        }

        .card-dashboard {
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
            border: 1px solid #eaebef;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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


        .card-dashboard .icon {
            font-size: 2.5rem;
            color: #e49b39;
            margin-bottom: 1rem;
        }

        .card-dashboard .number {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
        }

        .card-dashboard .text {
            color: #6c757d;
            font-size: 0.9rem;
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

        .tipo-uso-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            position: relative;
        }

        .remove-tipo-uso {
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
                <h3 class="cumb active-tab">Registro de Insumo</h3>
            </div>
            <hr>
        </div>

        <form id="formInsumo" action="{{ route('insumos.store') }}" method="POST">
            @csrf
            <div class="row">
                <br>

                <!-- Columna izquierda -->
                <div class="col-md-6">
                    <div class="form-section">
                        <h3>Información General</h3>
                        <p class="text-muted">Datos básicos para identificar y clasificar el insumo en el sistema</p>

                        <div class="mb-3">
                            <label for="codigo" class="form-label required-field">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required maxlength="20" value="{{ old('codigo') }}">
                            <small class="text-muted">Código único para identificar el insumo</small>
                        </div>

                        <div class="mb-3">
                            <label for="nombre_comercial" class="form-label required-field">Nombre Comercial</label>
                            <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" required maxlength="100" value="{{ old('nombre_comercial') }}">
                        </div>

                        <div class="mb-3">
                            <label for="nombre_generico" class="form-label">Nombre Genérico</label>
                            <input type="text" class="form-control" id="nombre_generico" name="nombre_generico" maxlength="100" value="{{ old('nombre_generico') }}">
                        </div>

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
                            <small class="text-muted">Predio al que pertenece este insumo</small>
                        </div>

                        <div class="mb-3">
                            <label for="categoria_id" class="form-label required-field">Categoría</label>
                            <select class="form-select" id="categoria_id" name="categoria_id">
                                <option value="1">Sanidad</option>
                                <option value="2">Vacunas</option>
                                <option value="3">Baños</option>
                                <option value="4">Alimentación</option>
                                <option value="5">Potreros</option>
                                <option value="6">Reproducción</option>
                                <option value="7">Manejo</option>
                            </select>
                            <small class="text-muted">Las categorías serán configuradas por el administrador</small>
                        </div>

                    </div>

                    <div class="form-section">
                        <h3>Inventario y Costos</h3>
                        <p class="text-muted">Especificaciones para el control de inventario y gestión de costos</p>

                        <div class="mb-3">
                            <label for="unidad_medida" class="form-label required-field">Unidad de Medida</label>
                            <select class="form-select" id="unidad_medida" name="unidad_medida" required>
                                <option value="">Seleccione</option>
                                <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramo (kg)</option>
                                <option value="g" {{ old('unidad_medida') == 'g' ? 'selected' : '' }}>Gramo (g)</option>
                                <option value="l" {{ old('unidad_medida') == 'l' ? 'selected' : '' }}>Litro (l)</option>
                                <option value="ml" {{ old('unidad_medida') == 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                                <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                <option value="dosis" {{ old('unidad_medida') == 'dosis' ? 'selected' : '' }}>Dosis</option>
                                <option value="bolsa" {{ old('unidad_medida') == 'bolsa' ? 'selected' : '' }}>Bolsa</option>
                                <option value="frasco" {{ old('unidad_medida') == 'frasco' ? 'selected' : '' }}>Frasco</option>
                                <option value="sobre" {{ old('unidad_medida') == 'sobre' ? 'selected' : '' }}>Sobre</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="precio_referencia" class="form-label">Precio de Referencia</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="precio_referencia" name="precio_referencia" step="0.01" min="0" value="{{ old('precio_referencia', 0) }}">
                            </div>
                            <small class="text-muted">Precio promedio del insumo para cálculos</small>
                        </div>

                        <h3>Tipos de Uso</h3>
                        <p class="text-muted">Indique para qué usos está destinado este insumo</p>

                        <div id="tipos-uso-container">
                            <div class="tipo-uso-item">
                                <div class="mb-3">
                                    <label for="tipos_uso_custom[0][nombre]" class="form-label required-field">Nombre del Uso</label>
                                    <input type="text" class="form-control" name="tipos_uso_custom[0][nombre]" required>
                                </div>


                            </div>
                        </div>
                    </div>


                </div>

                <!-- Columna derecha -->
                <div class="col-md-6">
                    <div class="form-section">
                        <h3>Características Adicionales</h3>
                        <p class="text-muted">Información técnica y detalles específicos del insumo</p>

                        <div class="mb-3">
                            <label for="fabricante" class="form-label">Fabricante</label>
                            <input type="text" class="form-control" id="fabricante" name="fabricante" maxlength="100" value="{{ old('fabricante') }}">
                        </div>

                        <div class="mb-3">
                            <label for="registro_ica" class="form-label">Registro ICA</label>
                            <input type="text" class="form-control" id="registro_ica" name="registro_ica" maxlength="50" value="{{ old('registro_ica') }}">
                            <small class="text-muted">Número de registro ICA (si aplica)</small>
                        </div>

                        <div class="mb-3">
                            <label for="principio_activo" class="form-label">Principio Activo</label>
                            <input type="text" class="form-control" id="principio_activo" name="principio_activo" maxlength="200" value="{{ old('principio_activo') }}">
                            <small class="text-muted">Para medicamentos o agroquímicos</small>
                        </div>

                        <div class="mb-3">
                            <label for="tiempo_retiro_leche" class="form-label">Tiempo de Retiro en Leche (días)</label>
                            <input type="number" class="form-control" id="tiempo_retiro_leche" name="tiempo_retiro_leche" min="0" value="{{ old('tiempo_retiro_leche', 0) }}">
                            <small class="text-muted">Tiempo durante el cual la leche no debe consumirse después de aplicar el medicamento</small>
                        </div>

                        <div class="mb-3">
                            <label for="tiempo_retiro_carne" class="form-label">Tiempo de Retiro en Carne (días)</label>
                            <input type="number" class="form-control" id="tiempo_retiro_carne" name="tiempo_retiro_carne" min="0" value="{{ old('tiempo_retiro_carne', 0) }}">
                            <small class="text-muted">Tiempo de espera antes del sacrificio después de aplicar el medicamento</small>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="form-section">
                            <h3>Cuenta Contable para Salida</h3>
                            <p class="text-muted">Seleccione la cuenta que se afectará al registrar la salida de este insumo.</p>
                            <div class="mb-3" style="display: none;">
                                <label for="naturaleza" class="form-label required-field">Clase (Naturaleza)</label>
                                <select name="naturaleza" id="naturaleza" class="form-select" required>
                                    <option value="">Seleccione una clase</option>
                                    <option value="Costos de ventas" >Costos</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="categoria" class="form-label required-field">Categoría</label>
                                <select name="categoria" id="categoria" class="form-select" required>
                                    <option value="">Seleccione una categoría</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="subcuenta" class="form-label required-field">Sub cuenta</label>
                                <select name="plan_cuenta" id="subcuenta" class="form-select" required>
                                    <option value="">Seleccione una subcuenta</option>
                                </select>
                                <small class="text-muted">Esta cuenta se usará para registrar el movimiento económico de salida.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Notas y Observaciones</h3>
                <p class="text-muted">Información adicional o comentarios relevantes sobre el insumo</p>
                <div class="mb-3">
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('insumos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Cancelar
                </a>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <span id="submitText">Guardar Insumo</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Modal eliminado ya que no tenemos la tabla de categorías aún -->
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log("Document ready. Iniciando script de selects contables.");

            const naturalezaSelect = document.getElementById('naturaleza');
            const categoriaSelect = document.getElementById('categoria');
            const subcuentaSelect = document.getElementById('subcuenta');

            const categoriasPrincipales = @json($categoriasPrincipales ?? []);
            const subcuentas = @json($subcuentasDetalles ?? []);
            console.log("Datos contables cargados:", { categoriasPrincipales, subcuentas });

            // --- Inicio: Lógica para fijar Naturaleza (Priorizando 'Costos de ventas') ---
            let fixedNaturaleza = null;
            const targetNaturaleza = "Costos de ventas";

            // 1. Intentar usar "Costos de ventas" directamente si es válida
            console.log(`Intentando fijar naturaleza objetivo: ${targetNaturaleza}`);
            const targetNaturalezaEsValida = categoriasPrincipales.some(categoria => {
                const esTargetNat = categoria.naturaleza === targetNaturaleza;
                if (!esTargetNat) return false;
                const hasSubcuentas = subcuentas.some(subcuenta =>
                    subcuenta.codcta.startsWith(categoria.codcta) &&
                    subcuenta.naturaleza === targetNaturaleza
                );
                // console.log(`   Check Cat ${categoria.codcta} (${categoria.naturaleza}): tieneSubcuentas=${hasSubcuentas}`);
                return hasSubcuentas;
            });

            if (targetNaturalezaEsValida) {
                fixedNaturaleza = targetNaturaleza;
                console.log(`Naturaleza objetivo '${targetNaturaleza}' es válida y se usará.`);
            } else {
                console.warn(`Naturaleza objetivo '${targetNaturaleza}' no es válida o no tiene categorías/subcuentas. Buscando fallback...`);
                // 2. Fallback: Buscar la primera naturaleza disponible que SÍ sea válida
                const naturalezasDisponibles = [...new Set(categoriasPrincipales.map(c => c.naturaleza))];
                console.log("Naturalezas disponibles (para fallback):", naturalezasDisponibles);

                for (const nat of naturalezasDisponibles) {
                    console.log(`Verificando naturaleza (fallback): ${nat}`);
                    const esValidaFallback = categoriasPrincipales.some(categoria => {
                         const esEstaNat = categoria.naturaleza === nat;
                         if (!esEstaNat) return false;
                         const hasSubcuentas = subcuentas.some(subcuenta =>
                            subcuenta.codcta.startsWith(categoria.codcta) &&
                            subcuenta.naturaleza === nat
                         );
                         return hasSubcuentas;
                    });

                    if (esValidaFallback) {
                        fixedNaturaleza = nat;
                        console.log(`Naturaleza de fallback encontrada: ${fixedNaturaleza}`);
                        break; // Usar la primera válida encontrada como fallback
                    }
                }
            }
            // --- Fin: Lógica para fijar Naturaleza ---

            function loadCategorias(selectedNaturaleza) {
                console.log(`-> Ejecutando loadCategorias con: ${selectedNaturaleza}`);
                categoriaSelect.innerHTML = '<option value="">Seleccione una categoría</option>';
                subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';
                categoriaSelect.disabled = true; // Deshabilitar hasta que se carguen
                subcuentaSelect.disabled = true;

                if (!selectedNaturaleza) {
                     console.log("   loadCategorias: Naturaleza no seleccionada, saliendo.");
                     return;
                }

                const categoriasFiltradas = categoriasPrincipales.filter(categoria => {
                    const hasSubcuentas = subcuentas.some(subcuenta =>
                        subcuenta.codcta.startsWith(categoria.codcta) &&
                        subcuenta.naturaleza === selectedNaturaleza
                    );
                    // Log detalle filtro categoría
                    // console.log(`   Filtrando cat: ${categoria.codcta}, nat: ${categoria.naturaleza}, tieneSub: ${hasSubcuentas}, coincideNat: ${categoria.naturaleza === selectedNaturaleza}`);
                    return categoria.naturaleza === selectedNaturaleza && hasSubcuentas;
                });
                console.log("   loadCategorias: Categorías filtradas:", categoriasFiltradas);

                if (categoriasFiltradas.length > 0) {
                     categoriasFiltradas.forEach(categoria => {
                        console.log(`      Añadiendo opción categoría: ${categoria.codcta} - ${categoria.nomcta}`);
                        const option = document.createElement('option');
                        option.value = categoria.codcta;
                        option.textContent = `${categoria.codcta} - ${categoria.nomcta}`;
                        categoriaSelect.appendChild(option);
                    });
                    categoriaSelect.disabled = false; // Habilitar si hay categorías
                    console.log("   loadCategorias: Select de categoría HABILITADO.");
                } else {
                     console.warn('   loadCategorias: No se encontraron categorías válidas para la naturaleza:', selectedNaturaleza);
                }
            }

            // Función para cargar subcuentas según la categoría seleccionada
            function loadSubcuentas(selectedCategoria, selectedNaturaleza) {
                 console.log(`-> Ejecutando loadSubcuentas con Cat: ${selectedCategoria}, Nat: ${selectedNaturaleza}`);
                subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';
                subcuentaSelect.disabled = true; // Deshabilitar hasta que se carguen

                if (!selectedCategoria || !selectedNaturaleza){
                    console.log("   loadSubcuentas: Categoría o Naturaleza no seleccionada, saliendo.");
                    return;
                }

                const subcuentasFiltradas = subcuentas.filter(subcuenta =>
                    subcuenta.codcta.startsWith(selectedCategoria) &&
                    subcuenta.naturaleza === selectedNaturaleza
                );
                 console.log("   loadSubcuentas: Subcuentas filtradas:", subcuentasFiltradas);

                 if (subcuentasFiltradas.length > 0) {
                    subcuentasFiltradas.forEach(subcuenta => {
                        console.log(`      Añadiendo opción subcuenta: ${subcuenta.codcta} - ${subcuenta.nomcta}`);
                        const option = document.createElement('option');
                        option.value = subcuenta.id; // Guardamos el ID de la subcuenta
                        option.textContent = `${subcuenta.codcta} - ${subcuenta.nomcta}`;
                        subcuentaSelect.appendChild(option);
                    });
                    subcuentaSelect.disabled = false; // Habilitar si hay subcuentas
                    console.log("   loadSubcuentas: Select de subcuenta HABILITADO.");
                 } else {
                     console.warn('   loadSubcuentas: No se encontraron subcuentas para la categoría seleccionada.');
                 }
            }

            // --- Inicio: Carga automática al iniciar ---
            if (fixedNaturaleza) {
                console.log(`Carga inicial: Estableciendo naturaleza ${fixedNaturaleza} y llamando a loadCategorias.`);
                // Establecer el valor en el select (aunque esté oculto)
                naturalezaSelect.value = fixedNaturaleza;
                // Cargar categorías inmediatamente
                loadCategorias(fixedNaturaleza);
            } else {
                console.error("Carga inicial: No se pudo determinar una naturaleza contable válida.");
                // Deshabilitar selects dependientes si no hay naturaleza
                categoriaSelect.disabled = true;
                subcuentaSelect.disabled = true;
            }
            // --- Fin: Carga automática al iniciar ---

            // Evento al cambiar la categoría (actualizado para usar fixedNaturaleza)
            categoriaSelect.addEventListener('change', function() {
                console.log(`Evento change en categoría: ${this.value}`);
                // Usar la naturaleza fija en lugar de leer el select oculto
                loadSubcuentas(this.value, fixedNaturaleza);
            });

            // El evento para naturalezaSelect ya no es necesario
        });
    </script>
@endsection
