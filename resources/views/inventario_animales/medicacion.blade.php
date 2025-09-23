@extends('layouts')

@section('title')
    Registro de Medicación Animal
@endsection

@section('styles')
    <style>
        /* Estilos generales (adaptados de otras vistas) */
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 25px 30px;
            /* Ajuste padding */
            border: 1px solid #eaebef;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Breadcrumbs */
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

        hr {
            border-top: 1px solid #6b7885;
            /* Color estándar hr */
            margin-top: 15px;
            /* Espacio después del breadcrumb */
            margin-bottom: 25px;
            /* Espacio antes del contenido */
        }

        /* Card Header */
        .card-header-container {
            margin-bottom: 25px;
            /* Espacio después del header */
        }

        .card-title {
            font-size: 23px;
            font-weight: 700;
            color: #292929;
            margin: 0 0 5px 0;
            /* Ajuste margen */
        }

        .card-subtitle {
            font-size: 14px;
            color: #767676;
            margin: 0;
        }

        /* Formulario */
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            /* Estándar Bootstrap */
        }

        .required-field::after {
            content: " *";
            color: red;
            font-weight: normal;
            /* Para que no sea negrita como el label */
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #e49b39;
            box-shadow: 0 0 0 0.25rem rgba(228, 155, 57, 0.25);
        }

        /* Botón Acción */
        .btn-action {
            padding: 10px 20px;
            background-color: #E49B39;
            color: white !important;
            /* Forzar color */
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            /* Evitar que el texto se rompa */
        }

        .btn-action:hover {
            background-color: #C97917;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-action .material-symbols-outlined {
            font-size: 20px;
            vertical-align: middle;
        }

        /* Tabla Histórica */
        .table-container {
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        .custom-table {
            /* Usar custom-table si se prefiere sobre .table de Bootstrap */
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        .custom-table th {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 2px solid #E5E7EB;
            font-weight: 600;
            font-size: 13px;
            color: #4B5563;
            background-color: #F9FAFB;
            letter-spacing: 0.5px;
        }

        .custom-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #E5E7EB;
            color: #374151;
            font-size: 14px;
            vertical-align: middle;
        }

        .custom-table tbody tr:hover {
            background-color: #FFF8F0;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .custom-table .text-center {
            /* Para el mensaje de "No hay registros" */
            text-align: center;
            padding: 30px;
            color: #6c757d;
        }

        /* Alertas personalizadas (como en user/index) */
        .alert-container {
            margin-bottom: 20px;
        }

        .error-container,
        .success-container {
            padding: 12px 15px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            gap: 10px;
        }

        .error-container {
            border: 1px solid #f8b4b4;
            background: #fde8e8;
            color: #9a0202;
        }

        .success-container {
            border: 1px solid #bcf0c0;
            background: #e8fdef;
            color: #1c8b00;
        }

        .error-container .material-symbols-outlined,
        .success-container .material-symbols-outlined {
            font-size: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="card-custom">
        {{-- Alertas --}}
        <div class="alert-container">
            @if (session('error'))
                <div class="error-container">
                    <span class="material-symbols-outlined">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if (session('success'))
                <div class="success-container">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            {{-- Mostrar errores de validación si es necesario --}}
            @if ($errors->any())
                <div class="error-container">
                    <span class="material-symbols-outlined">error</span>
                    <div>
                        <span>Por favor corrige los siguientes errores:</span>
                        <ul style="margin: 5px 0 0 15px; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        {{-- Breadcrumbs --}}
        <div class="breadcrumb">
            <a href="{{ route('inicio') }}">
                <h3 class="cumb no-active-tab">Inicio</h3>
            </a>
            <span class="material-symbols-outlined bread">chevron_forward</span>
            {{-- Aquí puedes poner un enlace intermedio si existe, ej: Sanidad --}}
            {{-- <a href="{{ route('ruta.intermedia') }}"><h3 class="cumb no-active-tab">Sanidad</h3></a> --}}
            {{-- <span class="material-symbols-outlined bread">chevron_forward</span> --}}
            <h3 class="cumb active-tab">Registro de Medicación</h3>
        </div>
        <hr>

        {{-- Card Header --}}
        <div class="card-header-container">
            <h2 class="card-title">Registro de Medicación</h2>
            <p class="card-subtitle">Ingrese los datos de la medicación aplicada al animal</p>
        </div>

        {{-- Formulario para registrar medicación --}}
        <form action="{{ route('medicacion.store') }}" method="POST" id="formMedicacion">
            @csrf
            <div class="row">
                <!-- Fecha de medicación -->
                <div class="col-md-4 mb-3">
                    <label for="fecha_medicacion" class="form-label required-field">Fecha de medicación</label>
                    <input type="date" class="form-control @error('fecha_medicacion') is-invalid @enderror"
                        id="fecha_medicacion" name="fecha_medicacion" required
                        value="{{ old('fecha_medicacion', date('Y-m-d')) }}">
                    @error('fecha_medicacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Seleccionar Predio -->
                <div class="col-md-4 mb-3">
                    <label for="id_predio" class="form-label required-field">Predio</label>
                    <select class="form-select @error('id_predio') is-invalid @enderror" id="id_predio" name="id_predio"
                        required>
                        <option value="">-- Seleccione un predio --</option>
                        @foreach ($predios as $predio)
                            <option value="{{ $predio->id }}" {{ old('id_predio') == $predio->id ? 'selected' : '' }}>
                                {{ $predio->nombre_predio }}</option>
                        @endforeach
                    </select>
                    @error('id_predio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Animal -->
                <div class="col-md-4 mb-3">
                    <label for="id_animal" class="form-label required-field">Animal</label>
                    {{-- El select empieza deshabilitado y se llena con JS --}}
                    <select name="id_animal" id="id_animal" class="form-select @error('id_animal') is-invalid @enderror"
                        required disabled>
                        <option value="" selected disabled>Seleccione un predio primero</option>
                        {{-- Las opciones se añadirán dinámicamente --}}
                    </select>
                    @error('id_animal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <!-- Veterinario -->
                <div class="col-md-4 mb-3">
                    <label for="id_veterinario" class="form-label required-field">Veterinario</label>
                    <select class="form-select @error('id_veterinario') is-invalid @enderror" id="id_veterinario"
                        name="id_veterinario" required>
                        <option value="">-- Seleccione un veterinario --</option>
                        @foreach ($veterinarios as $vet)
                            <option value="{{ $vet->id }}" {{ old('id_veterinario') == $vet->id ? 'selected' : '' }}>
                                {{ $vet->nombre_completo }}</option>
                        @endforeach
                    </select>
                    @error('id_veterinario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Motivo -->
                <div class="col-md-4 mb-3">
                    <label for="motivo" class="form-label required-field">Motivo</label>
                    <select class="form-select @error('motivo') is-invalid @enderror" id="motivo" name="motivo"
                        required>
                        <option value="">-- Seleccione un motivo --</option>
                        <option value="Tratamiento curativo"
                            {{ old('motivo') == 'Tratamiento curativo' ? 'selected' : '' }}>Tratamiento curativo</option>
                        <option value="Tratamiento preventivo"
                            {{ old('motivo') == 'Tratamiento preventivo' ? 'selected' : '' }}>Tratamiento preventivo
                        </option>
                        <option value="Inducidores de celos"
                            {{ old('motivo') == 'Inducidores de celos' ? 'selected' : '' }}>Inducidores de celos</option>
                        <option value="Desparasitacion" {{ old('motivo') == 'Desparasitacion' ? 'selected' : '' }}>
                            Desparasitacion</option>
                        <option value="Transferencia de Embriones"
                            {{ old('motivo') == 'Transferencia de Embriones' ? 'selected' : '' }}>Transferencia de
                            Embriones</option>
                        <option value="IATF" {{ old('motivo') == 'IATF' ? 'selected' : '' }}>IATF</option>
                        <option value="Quirúrgicos" {{ old('motivo') == 'Quirúrgicos' ? 'selected' : '' }}>Quirúrgicos
                        </option>
                        <option value="Otro" {{ old('motivo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        {{-- Considerar añadir 'Otro' --}}
                    </select>
                    @error('motivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Observación -->
                <div class="col-md-4 mb-3">
                    <label for="observacion" class="form-label">Observación</label>
                    <textarea class="form-control @error('observacion') is-invalid @enderror" id="observacion" name="observacion"
                        rows="1">{{ old('observacion') }}</textarea>
                    @error('observacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Nuevos campos para Insumo --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="insumo_id" class="form-label">Insumo Utilizado (Opcional)</label>
                    <select name="insumo_id" id="insumo_id" class="form-select @error('insumo_id') is-invalid @enderror"
                        disabled>
                        <option value="" selected>Seleccione un predio primero</option>
                        {{-- Opciones llenadas por JS --}}
                    </select>
                    @error('insumo_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2 mb-3">
                    <label for="cantidad" class="form-label">Cantidad Aplicada</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0"
                            class="form-control @error('cantidad') is-invalid @enderror" id="cantidad"
                            name="cantidad" value="{{ old('cantidad') }}" disabled>
                        <span class="input-group-text" id="unidad-medida-span">Und</span>
                    </div>
                    <small id="stock-disponible-info" class="text-muted" style="display: none;">Disponible: -</small>
                    @error('cantidad')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback d-block" id="cantidad-stock-error"></div>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="via_administracion" class="form-label">Vía Administración</label>
                    <select name="via_administracion" id="via_administracion"
                        class="form-select @error('via_administracion') is-invalid @enderror">
                        <option value="">Seleccione</option>
                        <option value="Intramuscular"
                            {{ old('via_administracion') == 'Intramuscular' ? 'selected' : '' }}>Intramuscular</option>
                        <option value="Subcutánea" {{ old('via_administracion') == 'Subcutánea' ? 'selected' : '' }}>
                            Subcutánea</option>
                        <option value="Intravenosa" {{ old('via_administracion') == 'Intravenosa' ? 'selected' : '' }}>
                            Intravenosa</option>
                        <option value="Oral" {{ old('via_administracion') == 'Oral' ? 'selected' : '' }}>Oral</option>
                        <option value="Tópica" {{ old('via_administracion') == 'Tópica' ? 'selected' : '' }}>Tópica
                        </option>
                        <option value="Otra" {{ old('via_administracion') == 'Otra' ? 'selected' : '' }}>Otra</option>
                    </select>
                    @error('via_administracion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Fin nuevos campos --}}
            </div>
            <div class="row mt-3"> {{-- Añadido margen superior para el botón --}}
                <div class="col-md-12 text-end"> {{-- Alineado a la derecha --}}
                    {{-- Botón de envío directo --}}
                    <button type="submit" class="btn-action">
                        Registrar Medicación
                    </button>
                </div>
            </div>
        </form>
    </div>


    {{-- Separar la tabla histórica en su propia tarjeta para claridad --}}
    <div class="card-custom">
        <div class="card-header-container"> {{-- Añadido header a la tabla también --}}
            <h2 class="card-title">Histórico de Medicación</h2>
            <p class="card-subtitle">Registros anteriores de medicación</p>
        </div>

        <div class="table-container"> {{-- Envolver tabla --}}
            <table class="custom-table"> {{-- Usar clase custom-table --}}
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>Animal (Código)</th>
                        <th>Fecha Medicación</th>
                        <th>Motivo</th>
                        <th>Veterinario</th>
                        <th>Predio</th> {{-- Añadido Predio --}}
                        <th>Insumo Aplicado</th> {{-- Nueva columna --}}
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($medicaciones as $med)
                        {{-- Usar forelse para manejar vacío --}}
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            {{-- <td>{{ $med->id }}</td> --}}
                            <td>{{ $med->animal->codigo ?? 'N/A' }} - {{ $med->animal->nombre ?? 'N/A' }}</td>
                            {{-- Acceder a relación animal --}}
                            <td>{{ $med->fecha_medicacion ? \Carbon\Carbon::parse($med->fecha_medicacion)->format('d/m/Y') : 'N/A' }}
                            </td> {{-- Formatear fecha --}}
                            <td>{{ $med->motivo }}</td>
                            <td>{{ $med->veterinario->nombre_completo ?? 'N/A' }}</td> {{-- Acceder a relación veterinario --}}
                            <td>{{ $med->predio->nombre_predio ?? 'N/A' }}</td> {{-- Acceder a relación predio --}}
                            <td>{{ $med->insumo->nombre_comercial ?? 'N/A' }}
                                {{ $med->cantidad ? '(' . $med->cantidad . ' ' . ($med->insumo->unidad_medida ?? '') . ')' : '' }}
                            </td> {{-- Mostrar insumo y cantidad --}}
                            <td>{{ $med->observacion ?? '-' }}</td> {{-- Mostrar '-' si es nulo --}}
                        </tr>
                    @empty {{-- Mensaje si no hay registros --}}
                        <tr>
                            <td colspan="7" class="text-center">
                                <span class="material-symbols-outlined"
                                    style="font-size: 38px; color: #ccc;">vaccines</span>
                                <p style="margin-top: 5px;">No se encontraron registros de medicación.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

{{-- Pre-procesamos los datos de animales para JSON usando función flecha --}}
@php
    $animalesJsonData = $animales
        ->map(
            fn($animal) => [
                // Asegúrate que las claves de abajo coinciden con tu modelo Animal
                'id' => $animal->id_animal ?? null,
                'nombre' => $animal->nombre ?? 'N/A',
                'codigo' => $animal->codigo ?? 'N/A',
                'predio_id' => $animal->id_predio ?? null,
            ],
        )
        ->values()
        ->toArray(); // Convertir a array PHP para json_encode

    // Pre-procesar datos de insumos para JSON
    $insumosJsonData = collect($insumosConStock)
        ->map(
            fn($insumo) => [
                'id' => $insumo['id'],
                'nombre' => $insumo['nombre_comercial'],
                'unidad' => $insumo['unidad_medida'],
                'predio_id' => $insumo['predio_id'],
                'stock' => $insumo['stock'],
            ],
        )
        ->values()
        ->toArray();
@endphp

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Script de medicación cargado y DOM listo.");

            const predioSelect = document.getElementById('id_predio');
            const animalSelect = document.getElementById('id_animal');
            const insumoSelect = document.getElementById('insumo_id');
            const cantidadInput = document.getElementById('cantidad');
            const stockInfo = document.getElementById('stock-disponible-info');
            const cantidadErrorDiv = document.getElementById('cantidad-stock-error');
            const unidadMedidaSpan = document.getElementById('unidad-medida-span');

            let todosLosAnimales = [];
            let todosLosInsumos = [];
            let stockSeleccionado = 0;
            try {
                // Inicializar animales
                todosLosAnimales = {!! json_encode($animalesJsonData) !!};
                console.log(`Se cargaron ${todosLosAnimales.length} animales.`);

                // Inicializar insumos
                todosLosInsumos = {!! json_encode($insumosJsonData) !!};
                console.log(`Se cargaron ${todosLosInsumos.length} insumos.`);

            } catch (e) {
                console.error("Error al parsear datos JSON (json_encode):", e);
                animalSelect.innerHTML = '<option value="" disabled selected>Error al cargar animales</option>';
                insumoSelect.innerHTML = '<option value="" disabled selected>Error al cargar insumos</option>';
                return;
            }

            // Guardar valores antiguos
            const oldAnimalValue = "{{ old('id_animal') }}";
            const oldInsumoValue = "{{ old('insumo_id') }}";

            // Función reutilizable para poblar selects
            function populateSelect(selectElement, data, valueField, textFieldPrefix, textFieldSuffix, filterValue =
                null, filterField = null, placeholderText = "-- Seleccione --", emptyText =
                "No hay opciones disponibles") {
                selectElement.innerHTML = '';
                selectElement.disabled = true;

                let filteredData = data;
                if (filterValue && filterField) {
                    filteredData = data.filter(item => String(item[filterField]) === String(filterValue));
                }

                const placeholderOption = document.createElement('option');
                placeholderOption.value = "";
                placeholderOption.textContent = filteredData.length > 0 ? placeholderText : emptyText;
                placeholderOption.disabled = true;
                placeholderOption.selected = true;
                selectElement.appendChild(placeholderOption);

                if (filteredData.length > 0) {
                    selectElement.disabled = false;
                    filteredData.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item[valueField];
                        option.textContent =
                            `${item[textFieldPrefix] || ''} - ${item[textFieldSuffix] || ''}`.trim();
                        option.dataset.unidad = item.unidad || 'Und';
                        option.dataset.stock = item.stock || 0;
                        selectElement.appendChild(option);
                    });
                } else {
                    selectElement.disabled = true;
                }
                return filteredData;
            }

            // Event listener para cambio de Predio
            predioSelect.addEventListener('change', function() {
                const selectedPredioId = this.value;
                console.log(`Predio seleccionado: ${selectedPredioId}`);

                // Filtrar y poblar Animales
                const animalesFiltrados = populateSelect(
                    animalSelect,
                    todosLosAnimales,
                    'id',
                    'codigo',
                    'nombre',
                    selectedPredioId,
                    'predio_id',
                    '-- Seleccione un animal --',
                    'No hay animales en este predio'
                );

                // Restaurar selección antigua de animal si aplica
                if (oldAnimalValue && animalesFiltrados.some(a => String(a.id) === oldAnimalValue)) {
                    animalSelect.value = oldAnimalValue;
                }

                // Filtrar y poblar Insumos
                const insumosFiltrados = populateSelect(
                    insumoSelect,
                    todosLosInsumos,
                    'id',
                    'nombre',
                    null,
                    selectedPredioId,
                    'predio_id',
                    '-- Seleccione un insumo --',
                    'No hay insumos de sanidad/vacunas en este predio'
                );

                // Restaurar selección antigua de insumo si aplica
                if (oldInsumoValue && insumosFiltrados.some(i => String(i.id) === oldInsumoValue)) {
                    insumoSelect.value = oldInsumoValue;
                    // Disparar change en insumo para actualizar unidad si había valor antiguo
                    insumoSelect.dispatchEvent(new Event('change'));
                } else {
                    // Si no hay valor antiguo, resetear unidad y stock
                    if (unidadMedidaSpan) unidadMedidaSpan.textContent = 'Und';
                    if (cantidadInput) cantidadInput.value = '';
                    if (cantidadInput) cantidadInput.disabled = true;
                    if (stockInfo) stockInfo.style.display = 'none';
                    if (cantidadErrorDiv) cantidadErrorDiv.textContent = '';
                }
            });

            // Event listener para cambio de Insumo
            insumoSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                cantidadInput.disabled = !this.value;
                cantidadInput.value = '';
                cantidadInput.classList.remove('is-invalid');
                cantidadErrorDiv.textContent = '';

                if (selectedOption && this.value) {
                    const unidad = selectedOption.dataset.unidad || 'Und';
                    stockSeleccionado = parseFloat(selectedOption.dataset.stock) || 0;
                    if (unidadMedidaSpan) unidadMedidaSpan.textContent = unidad;
                    if (stockInfo) {
                        stockInfo.textContent = `Disponible: ${stockSeleccionado} ${unidad}`;
                        stockInfo.style.display = 'block';
                    }
                } else {
                    stockSeleccionado = 0;
                    if (unidadMedidaSpan) unidadMedidaSpan.textContent = 'Und';
                    if (stockInfo) stockInfo.style.display = 'none';
                }
            });

            // Event listener para input en Cantidad Aplicada
            cantidadInput.addEventListener('input', function() {
                const cantidadIngresada = parseFloat(this.value) || 0;
                this.classList.remove('is-invalid');
                cantidadErrorDiv.textContent = '';

                if (cantidadIngresada < 0) {
                    this.classList.add('is-invalid');
                    cantidadErrorDiv.textContent = 'La cantidad no puede ser negativa.';
                } else if (stockSeleccionado > 0 && cantidadIngresada > stockSeleccionado) {
                    this.classList.add('is-invalid');
                    cantidadErrorDiv.textContent =
                        `La cantidad excede el stock disponible (${stockSeleccionado}).`;
                }
            });

            // Simular un evento change al cargar la página si ya hay un predio seleccionado
            if (predioSelect.value) {
                predioSelect.dispatchEvent(new Event('change'));
            }

        });
    </script>
@endsection
