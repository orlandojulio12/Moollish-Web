    @extends('layouts')

@section('styles')
    <style>
        .double-container {
            display: flex;
        }

        .main {
            width: 46%;
            margin: 0px 20px 0px 0px;
        }

        .container {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
        }

        .card-header {
            margin: 20px 0px 0px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid lightgray;
        }

        tr {
            border: 1px solid lightgray;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid lightgray;
        }

        @media (width < 768px) {
            .container {
                overflow: auto;
            }
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

        /* SOLUCIÓN CRÍTICA PARA MODALES (CLONADA DE MOVIMIENTOS ECONÓMICOS) */
        body.modal-open {
            overflow: hidden !important;
            padding-right: 0 !important;
        }

        .modal-backdrop {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            z-index: 1040 !important;
            width: 100vw !important;
            height: 100vh !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }

        .modal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            z-index: 1055 !important;
            width: 100% !important;
            height: 100% !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            outline: 0 !important;
            opacity: 0;
            transition: opacity 0.15s linear !important;
            pointer-events: none;
        }

        .modal.show {
            display: block !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: 1.75rem auto;
            pointer-events: auto;
        }

        /* Estilos adicionales para alertas y modales */
        .error-container {
            padding: 7px 19px 4px 14px;
            border: 1px solid #a30000;
            border-radius: 4px;
            background: #ff00000f;
            color: #a30000;
            display: flex;
            align-items: flex-end;
            font-size: 14px;
            font-weight: 500;
            backdrop-filter: blur(16px) saturate(180%);
        }

        .success-container {
            padding: 7px 19px 4px 14px;
            border: 1px solid #1c8b00;
            border-radius: 4px;
            background: #19ff000f;
            color: #1c8b00;
            display: flex;
            align-items: flex-end;
            font-size: 14px;
            font-weight: 500;
            backdrop-filter: blur(16px) saturate(180%);
        }

        .error-icon, .success-icon {
            border-right: 1px solid;
            padding: 0px 8px 0px 0px;
            margin: 0px 10px 0px 0px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
@endsection

@section('title')
    Moollish Palpaciones
@endsection

@section('content')
    @if (session('error'))
        <div class="error-container">
            <span class="error-icon material-symbols-outlined">warning</span>
            <span class="error-alert">{{ session('error') }}</span>
        </div>
    @endif

    @if (session('success'))
        <div class="success-container">
            <span class="success-icon material-symbols-outlined">check_circle</span>
            <span class="success-alert">{{ session('success') }}</span>
        </div>
    @endif

    <div class="container">
        <div class="header-grid">
            <div class="breadcrumb">
                <a href="{{ route('inicio') }}">
                    <h3 class="cumb no-active-tab">Inicio</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <a href="{{ route('registros') }}">
                    <h3 class="cumb no-active-tab">Registros</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <a href="{{ route('reproduccionAnimal') }}">
                    <h3 class="cumb no-active-tab">Reproducción Animal</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <h3 class="cumb active-tab">Palpaciones</h3>
            </div>
            <hr>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-success" id="openImportModalPalpaciones">
                <span class="material-symbols-outlined me-2">upload_file</span>
                Importar Palpaciones
            </button>
        </div>

        <form id="palpacionForm" action="{{ route('palpaciones.store') }}" method="POST">
            @csrf
            <div class="double-container">
                <div class="main">
                    <!-- Fecha -->
                    <div class="col-md-12">
                        <label for="fecha" class="form-label">Fecha <span style="color: red;">*</span></label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <div class="col-md-12">
                        <label for="id_animal" class="form-label">Animal <span style="color: red;">*</span></label>
                        <input type="hidden" id="id_animal" name="id_animal" required>
                        <div class="input-dinamico-animal">
                            <div id="animalSeleccionado"></div>
                            <button type="button" class="buton-dinamico-animal" data-popup="{{ $nombre ?? 'id_animal' }}">
                                <span class="material-symbols-outlined">search</span>
                            </button>
                        </div>
                    </div>
                    @include('components.selector-animales', [
                        'predios' => $predios,
                        'animales' => $animales,
                    ])
                </div>
                <div class="main">
                    <!-- Resultado -->
                    <div class="col-md-12">
                        <label for="resultado" class="form-label">Resultado <span style="color: red;">*</span></label>
                        <select class="form-control" id="resultado" name="resultado" required>
                            <option value="" disabled selected>Seleccione un resultado</option>
                            <option value="Prenada">Preñada</option>
                            <option value="Vacia">Vacía</option>
                        </select>
                    </div>
                    <!-- Diagnóstico (condicional) -->
                    <div class="col-md-12" id="divDiagnostico" style="display: none;">
                        <label for="diagnostico" class="form-label">Diagnóstico</label>
                        <select class="form-control" id="diagnostico" name="diagnostico">
                            <option value="" disabled selected>Seleccione un diagnóstico</option>
                            <option value="Vacía ciclando">Vacía ciclando</option>
                            <option value="Vacía estática">Vacía estática</option>
                            <option value="Vacia normal">Vacia normal</option>
                            <option value="Cuerpo Luteo ovario derecho">Cuerpo Luteo ovario derecho</option>
                            <option value="Cuerpo Luteo ovario izquierdo">Cuerpo Luteo ovario izquierdo</option>
                            <option value="Folículo ovario derecho">Folículo ovario derecho</option>
                            <option value="Folículo ovario izquierdo">Folículo ovario izquierdo</option>
                            <option value="Quistes">Quistes</option>
                            <option value="indantilismo genital">Indantilismo genital</option>
                        </select>
                    </div>
                    <!-- Días de Preñada -->
                    <div class="col-md-12">
                        <label for="dias_prenada" class="form-label">Días de Preñada</label>
                        <input type="number" max="285" class="form-control" id="dias_prenada" name="dias_prenada" min="0" disabled>
                    </div>
                </div>
            </div>

            <div class="double-container">
                <!-- Veterinario -->
                <div class="main">
                    <label for="id_palpador" class="form-label">Veterinario <span style="color: red;">*</span></label>
                    <div class="input-group">
                        <select class="form-control" id="id_palpador" name="id_palpador" required>
                            <option value="" disabled selected>Seleccione un veterinario</option>
                            @foreach ($veterinarios as $veterinario)
                                <option value="{{ $veterinario->id }}">{{ $veterinario->nombre_completo }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createVeterinarioModal">
                            <span class="material-symbols-outlined">add</span>
                        </button>
                    </div>
                </div>
                <!-- Parto Proyectado -->
                <div class="main">
                    <label for="parto_proyectado" class="form-label">Parto Proyectado</label>
                    <input type="date" class="form-control" readonly id="parto_proyectado" name="parto_proyectado">
                </div>
            </div>

            <div class="card-header">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>

    <br>

    <div class="container">
        <h3>Historial de Palpaciones</h3>
        <table id="historial-palpaciones" class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Animal</th>
                    <th>Resultado</th>
                    <th>Diagnóstico</th>
                    <th>Parto Proyectado</th>
                    <th>Veterinario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($palpaciones as $palpacion)
                    <tr>
                        <td>{{ $palpacion->fecha }}</td>
                        <td>{{ $palpacion->animal->codigo }}</td>
                        <td>{{ $palpacion->resultado }}</td>
                        <td>{{ $palpacion->diagnostico ?? 'Sin diagnóstico' }}</td>
                        <td>{{ $palpacion->parto_proyectado ?? 'Sin fecha proyectada' }}</td>
                        <td>{{ $palpacion->palpador->numero_documento ?? 'Sin palpador' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal importación palpaciones -->
    <div class="modal fade" id="importPalpacionesModal" tabindex="-1" aria-labelledby="importPalpacionesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importPalpacionesModalLabel">
                        <span class="material-symbols-outlined me-2">upload_file</span>
                        Importar Palpaciones desde Excel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if(session('warning'))
                        <div class="alert alert-warning">
                            <h6>{{ session('warning') }}:</h6>
                            <ul class="mb-0">
                                @foreach(session('errores') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h6 class="fw-bold mb-2">📋 Instrucciones:</h6>
                        <ul class="mb-0">
                            <li>Descarga la plantilla y llena los datos</li>
                            <li>Campos obligatorios: Código Vaca, Fecha Palpación, Resultado, Veterinario</li>
                            <li>Si Resultado es Vacía, Diagnóstico es obligatorio</li>
                            <li>Formato de fecha: dd/mm/aaaa</li>
                            <li>Días de Preñada (0-285) y Parto Proyectado (dd/mm/aaaa) son opcionales, solo para Preñada. Parto Proyectado será calculado automáticamente si se proporciona Días de Preñada.</li>
                        </ul>
                    </div>

                    <div class="text-center mb-4">
                        <a href="{{ route('palpaciones.template') }}" class="btn btn-success">
                            <span class="material-symbols-outlined me-2">download</span>
                            Descargar Plantilla Excel
                        </a>
                    </div>

                    <form id="importPalpacionesForm" action="{{ route('palpaciones.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="predio_id_import_palpaciones" class="form-label">Predio <span class="text-danger">*</span></label>
                                <select class="form-control" name="predio_id" id="predio_id_import_palpaciones" required>
                                    <option value="">Seleccionar Predio</option>
                                    @foreach($predios as $predio)
                                        <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="file_import_palpaciones" class="form-label">Archivo Excel <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="file_import_palpaciones" name="file" accept=".xlsx,.xls,.csv" required>
                                <small class="text-muted">Máximo 5MB</small>
                            </div>
                        </div>

                        <div id="filePreviewPalpaciones" class="alert alert-secondary" style="display: none;">
                            <h6>📄 Archivo:</h6>
                            <p id="fileNamePalpaciones" class="mb-0"></p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" id="submitImportPalpaciones" class="btn btn-primary">
                                <span class="material-symbols-outlined me-2">upload</span>
                                Importar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="createVeterinarioModal" tabindex="-1" aria-labelledby="createVeterinarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="createVeterinarioForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createVeterinarioModalLabel">Agregar Veterinario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="predio_id">Predio</label>
                            <select id="predio_id" name="predio_id" class="form-control" required>
                                <option value="" disabled selected>Seleccione un predio</option>
                                @foreach ($predios as $predio)
                                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo</label>
                            <input type="text" name="nombre_completo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="numero_documento">Número de Documento</label>
                            <input type="text" name="numero_documento" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="text" name="celular" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="correo_electronico">Correo Electrónico</label>
                            <input type="email" name="correo_electronico" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <select name="sexo" class="form-control">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

    <script>
        $(document).ready(function() {
            // Mostrar/ocultar el select de diagnóstico cuando se seleccione "Vacía" en resultado
            $('#resultado').change(function() {
                var resultado = $(this).val();
                if (resultado === 'Vacia') {
                    $('#divDiagnostico').slideDown();
                    $('#diagnostico').prop('required', true);
                } else {
                    $('#divDiagnostico').slideUp();
                    $('#diagnostico').prop('required', false);
                }
            });

            // Activar/desactivar el campo de "Días de Preñada" según el resultado
            const $resultado = $('#resultado');
            const $diasPrenada = $('#dias_prenada');
            const $partoProyectado = $('#parto_proyectado');
            const $fecha = $('#fecha');

            $resultado.on('change', function() {
                if ($(this).val() === 'Prenada') {
                    $diasPrenada.prop('disabled', false);
                    $partoProyectado.prop('disabled', false);
                } else {
                    $diasPrenada.prop('disabled', true).val('');
                    $partoProyectado.prop('disabled', true).val('');
                }
            });

            // Calcular la fecha de "Parto Proyectado" al ingresar los días de preñada
            $diasPrenada.on('input', function() {
                const diasPrenada = parseInt($(this).val(), 10) || 0;
                const fechaPalpacion = $fecha.val();

                if (fechaPalpacion) {
                    const fecha = new Date(fechaPalpacion);
                    const diasFaltantes = 285 - diasPrenada;
                    fecha.setDate(fecha.getDate() + diasFaltantes);

                    const year = fecha.getFullYear();
                    const month = String(fecha.getMonth() + 1).padStart(2, '0');
                    const day = String(fecha.getDate()).padStart(2, '0');
                    $partoProyectado.val(`${year}-${month}-${day}`);
                }
            });

            // Formulario de creación de veterinario
            $('#createVeterinarioForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('veterinarios.store') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#id_palpador').append(new Option(response.veterinario.nombre_completo, response.veterinario.id));
                            Swal.fire({
                                icon: 'success',
                                title: 'Veterinario agregado',
                                text: 'Veterinario registrado con éxito.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#createVeterinarioModal').modal('hide');
                            $('#createVeterinarioForm')[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al agregar el veterinario.'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error. Inténtalo de nuevo.'
                        });
                        console.error(xhr.responseText);
                    }
                });
            });

            // Inicializar DataTable para historial
            if ($.fn.DataTable.isDataTable('#historial-palpaciones')) {
                $('#historial-palpaciones').DataTable().clear().destroy();
            }
            $('#historial-palpaciones').DataTable({
                language: {
                    "decimal": "",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "emptyTable": "No hay palpaciones registradas",
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
                }
            });

            // Modal importación
            const btnImport = document.getElementById('openImportModalPalpaciones');
            if (btnImport) {
                btnImport.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const modalEl = document.getElementById('importPalpacionesModal');
                    if (modalEl) {
                        window.palpacionesModalSystem.open('importPalpacionesModal');
                    }
                });
            }

            // Preview archivo
            const fileInput = document.getElementById('file_import_palpaciones');
            const filePreview = document.getElementById('filePreviewPalpaciones');
            const fileName = document.getElementById('fileNamePalpaciones');

            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (file.size > 5 * 1024 * 1024) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Archivo muy grande',
                                text: 'El archivo no debe superar los 5MB'
                            });
                            this.value = '';
                            filePreview.style.display = 'none';
                            return;
                        }
                        fileName.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                        filePreview.style.display = 'block';
                    } else {
                        filePreview.style.display = 'none';
                    }
                });
            }

            // Form import con fetch para JSON
            const formImport = document.getElementById('importPalpacionesForm');
            if (formImport) {
                formImport.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const predioId = document.getElementById('predio_id_import_palpaciones').value;
                    const file = document.getElementById('file_import_palpaciones').files[0];
                    const submitBtn = document.getElementById('submitImportPalpaciones');
                    const modalEl = document.getElementById('importPalpacionesModal');
                    const modal = window.palpacionesModalSystem.activeModal;

                    if (!predioId || !file) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campos incompletos',
                            text: 'Debe seleccionar un predio y cargar un archivo'
                        });
                        return;
                    }

                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importando...';
                    submitBtn.disabled = true;

                    const formData = new FormData(this);

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            body: formData
                        });
                        const result = await response.json();

                        window.palpacionesModalSystem.close('importPalpacionesModal');

                        if (result.status === 'success' && result.exitosos > 0) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Importación exitosa!',
                                html: `<p><strong>${result.message}</strong></p>`,
                                timer: 3000,
                                showConfirmButton: true
                            });
                            this.reset();
                            document.getElementById('filePreviewPalpaciones').style.display = 'none';
                            location.reload();
                        } else if (result.status === 'partial') {
                            let mensaje = `<div style="text-align: left;">
                                <p><strong>✅ ${result.exitosos} palpación(es) importadas correctamente</strong></p>`;
                            if (result.duplicados && result.duplicados.length > 0) {
                                mensaje += `
                                    <hr style="margin: 15px 0;">
                                    <p><strong>ℹ️ Registros duplicados (${result.duplicados.length}):</strong></p>
                                    <ul style="max-height: 150px; overflow-y: auto; text-align: left; color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0;">`;
                                result.duplicados.forEach(dup => {
                                    mensaje += `<li style="margin: 5px 0;">${dup}</li>`;
                                });
                                mensaje += `</ul>`;
                            }
                            if (result.errores && result.errores.length > 0) {
                                mensaje += `
                                    <hr style="margin: 15px 0;">
                                    <p><strong>❌ Errores encontrados (${result.errores.length}):</strong></p>
                                    <ul style="max-height: 150px; overflow-y: auto; text-align: left; color: #721c24; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0;">`;
                                result.errores.forEach(error => {
                                    mensaje += `<li style="margin: 5px 0;">${error}</li>`;
                                });
                                mensaje += `</ul>`;
                            }
                            mensaje += `
                                <hr style="margin: 15px 0;">
                                <p style="font-size: 14px; color: #666; margin-top: 15px;"><em>💡 Corrija los errores y vuelva a importar. Los duplicados se omitirán automáticamente.</em></p>
                            </div>`;
                            Swal.fire({
                                icon: 'warning',
                                title: 'Importación parcial',
                                html: mensaje,
                                width: 750,
                                confirmButtonText: 'Entendido'
                            });
                        } else if (result.status === 'error') {
                            let mensaje = '<div style="text-align: left;">';
                            const totalErrores = result.errores ? result.errores.length : 0;
                            const totalDuplicados = result.duplicados ? result.duplicados.length : 0;
                            mensaje += `<p><strong>📊 Resumen:</strong></p>
                                        <ul style="list-style: none; padding-left: 0; margin-bottom: 15px; background: #f8f9fa; padding: 10px; border-radius: 4px;">
                                            <li style="color: #28a745; margin: 3px 0;">✅ Importados: <strong>0</strong></li>
                                            <li style="color: #dc3545; margin: 3px 0;">❌ Con errores: <strong>${totalErrores}</strong></li>
                                            <li style="color: #ffc107; margin: 3px 0;">ℹ️ Duplicados: <strong>${totalDuplicados}</strong></li>
                                        </ul>`;
                            if (result.errores && result.errores.length > 0) {
                                mensaje += `
                                    <hr style="margin: 15px 0;">
                                    <p><strong>❌ Errores encontrados:</strong></p>
                                    <ul style="max-height: 200px; overflow-y: auto; text-align: left; color: #721c24; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0;">`;
                                result.errores.forEach(error => {
                                    mensaje += `<li style="margin: 5px 0;">${error}</li>`;
                                });
                                mensaje += `</ul>`;
                            }
                            if (result.duplicados && result.duplicados.length > 0) {
                                mensaje += `
                                    <hr style="margin: 15px 0;">
                                    <p><strong>ℹ️ Registros duplicados:</strong></p>
                                    <ul style="max-height: 150px; overflow-y: auto; text-align: left; color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0;">`;
                                result.duplicados.forEach(dup => {
                                    mensaje += `<li style="margin: 5px 0;">${dup}</li>`;
                                });
                                mensaje += `</ul>`;
                            }
                            mensaje += `
                                <hr style="margin: 15px 0;">
                                <p style="font-size: 14px; color: #666;"><em>💡 Corrija los errores en el archivo y vuelva a intentar.</em></p>
                            </div>`;
                            Swal.fire({
                                icon: 'error',
                                title: result.message || 'No se pudo importar ninguna palpación',
                                html: mensaje,
                                width: 750,
                                confirmButtonText: 'Cerrar'
                            });
                        }
                    } catch (error) {
                        window.palpacionesModalSystem.close('importPalpacionesModal');
                        console.error('Error catch:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'Error al procesar la importación. Por favor, intente nuevamente.'
                        });
                    } finally {
                        submitBtn.innerHTML = '<span class="material-symbols-outlined me-2">upload</span>Importar';
                        submitBtn.disabled = false;
                    }
                });
            }

            // Mensajes flash de sesión
            @if(session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
        });

        // Sistema de modales COMPLETAMENTE INDEPENDIENTE (CLONADO DE MOVIMIENTOS ECONÓMICOS)
        (function() {
            const modalSystem = {
                activeModal: null,
                activeBackdrop: null,

                open: function(modalId) {
                    this.closeAll();
                    
                    const modal = document.getElementById(modalId);
                    if (!modal) {
                        console.error('Modal no encontrado:', modalId);
                        return;
                    }

                    // Crear backdrop
                    this.activeBackdrop = document.createElement('div');
                    this.activeBackdrop.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0, 0, 0, 0.5);
                        z-index: 1040;
                        display: block;
                    `;
                    this.activeBackdrop.className = 'palpaciones-modal-backdrop';
                    document.body.appendChild(this.activeBackdrop);

                    // Mostrar modal
                    modal.style.cssText = `
                        display: block !important;
                        position: fixed !important;
                        top: 0 !important;
                        left: 0 !important;
                        width: 100% !important;
                        height: 100% !important;
                        z-index: 1050 !important;
                        overflow-y: auto !important;
                    `;
                    modal.classList.add('show');
                    this.activeModal = modal;

                    // Bloquear scroll del body
                    document.body.style.overflow = 'hidden';

                    // Click en backdrop para cerrar
                    const self = this;
                    this.activeBackdrop.addEventListener('click', function() {
                        self.close(modalId);
                    });
                },

                close: function(modalId) {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.style.display = 'none';
                        modal.classList.remove('show');
                    }

                    if (this.activeBackdrop) {
                        this.activeBackdrop.remove();
                        this.activeBackdrop = null;
                    }

                    document.body.style.overflow = '';
                    this.activeModal = null;
                },

                closeAll: function() {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.querySelectorAll('.modal').forEach(el => {
                        el.classList.remove('show', 'fade');
                        el.style.display = 'none';
                        el.style.opacity = '1';
                    });
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = '';
                    document.body.style.overflow = '';
                    this.activeModal = null;
                }
            };

            // Exponer sistema globalmente
            window.palpacionesModalSystem = modalSystem;

            // Interceptar TODOS los clicks en el documento
            document.addEventListener('click', function(e) {
                const openTrigger = e.target.closest('[data-bs-toggle="modal"]');
                if (openTrigger) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    const targetId = openTrigger.getAttribute('data-bs-target').replace('#', '');
                    window.palpacionesModalSystem.open(targetId);
                    return false;
                }

                const closeTrigger = e.target.closest('[data-bs-dismiss="modal"]');
                if (closeTrigger) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    const modal = closeTrigger.closest('.modal');
                    if (modal) {
                        window.palpacionesModalSystem.close(modal.id);
                    }
                    return false;
                }
            }, true);

            // Cerrar con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && window.palpacionesModalSystem.activeModal) {
                    window.palpacionesModalSystem.close(window.palpacionesModalSystem.activeModal.id);
                }
            });

            // Prevenir que Bootstrap JS maneje estos modales
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('show.bs.modal', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }, true);

                modal.addEventListener('shown.bs.modal', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }, true);

                modal.addEventListener('hide.bs.modal', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }, true);
            });

            // Limpieza cada 500ms por si acaso
            setInterval(function() {
                const backdrops = document.querySelectorAll('.modal-backdrop:not(.palpaciones-modal-backdrop)');
                backdrops.forEach(b => b.remove());
            }, 500);
        })();
    </script>
@endsection