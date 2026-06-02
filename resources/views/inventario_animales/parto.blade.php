<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />

<style>
    .nxl-container .nxl-content .main-content {
        padding: 0;
    }

    .title-modal-custom {
        padding: 10px 0px;
    }

    .column-custom {
        width: 72%;
    }

    .p-20 {
        padding: 20px;
    }

    .title-with-link {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
    }

    .link-add {
        color: #e49b39;
        font-size: 12px;
        display: flex;
        align-items: center;
        border: none;
        background: none;
        cursor: pointer;
    }

    a:hover {
        color: #6c757d !important;
    }

    .title-modal-custom h3 {
        border-bottom: 1px solid #e5e7eb;
    }

    .padding-custom {
        padding: 1.5rem 0rem !important;
    }

    .spacing {
        margin: 0px 10px;
        border-right: 1px solid #e5e7eb;
        padding: 0px 10px;
    }

    .custom-date-input .material-icons {
        font-size: 24px;
        color: #2196F3;
    }

    .filled {
        background: rgb(255 255 255 / 60%) !important;
        border: 1px solid #ced5e5 !important;
        color: #6891b5 !important;
    }

    .modal-footer {
        display: flex;
        flex-shrink: 0;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        background-color: var(--bs-modal-footer-bg);
        border-top: var(--bs-modal-footer-border-width) solid var(--bs-modal-footer-border-color);
        border-bottom-right-radius: var(--bs-modal-inner-border-radius);
        border-bottom-left-radius: var(--bs-modal-inner-border-radius);
        margin: 10px 0px 0px 0px !important;
        padding: 10px 0px 0px 0px !important;
    }

    .modal-footer-ficha {
        display: flex;
        flex-shrink: 0;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-start;
        background-color: var(--bs-modal-footer-bg);
        border-top: var(--bs-modal-footer-border-width) solid var(--bs-modal-footer-border-color);
        border-bottom-right-radius: var(--bs-modal-inner-border-radius);
        border-bottom-left-radius: var(--bs-modal-inner-border-radius);
        margin: 10px !important;
        padding: 10px !important;
    }

    .border-bot {
        border-bottom: 1px solid #e5e7eb;
    }

    .container-buttons {
        display: flex;
        justify-content: space-around;
    }

    .container-buttons button {
        padding: 5px 10px !important;
    }

    .modal-dialog {
        max-width: 1200px !important;
        margin-right: auto;
        margin-left: auto;
        width: 50% !important;
    }

    .five-column-custom {
        width: 19%;
        margin: 0px 5px;
    }

    .four-column-custom {
        width: 24%;
        margin: 0px 5px;
    }

    .three-column-custom {
        width: 32%;
        margin: 0px 5px;
    }

    .two-column-custom {
        width: 48%;
        margin: 0px 5px 0px 0px;
    }

    .space-b {
        justify-content: space-between;
    }

    .container-ficha {
        padding: 1rem 2rem;
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        align-items: baseline;
    }

    .input-group-custom {
        display: flex;
        align-items: center;
        position: relative;
    }

    .input-group-custom .form-control {
        flex: 1;
        padding-right: 2.5rem;
    }

    .input-icon {
        display: flex;
        align-items: center;
        padding: 11px 5px 11px 11px;
        border-left: 1px solid #e5e7eb;
        color: rgb(155, 155, 155);
        position: absolute;
        right: 0.5rem;
        height: 100%;
        pointer-events: none;
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

    .selected-animals-container {
        border: 1px solid #e5e7eb;
        padding: 10px;
        margin: 10px 0px;
        border-radius: 4px;
        display: flex;
    }

    .icon-size-custom {
        font-size: 15px;
        margin: 1px 0px 0px 5px;
    }

    .no-selected {
        background: #6c757d12;
        border: 1px dotted lightgray;
        border-radius: 4px;
        padding: 5px 10px;
        color: #acacac;
    }

    .selected-animal {
        background: #e49b392e;
        padding: 8px 10px;
        border: 1px solid #e49b39;
        border-radius: 4px;
        color: #9d6f19;
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 5px;
    }

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

    .error-icon {
        border-right: 1px solid;
        padding: 0px 8px 0px 0px;
        margin: 0px 10px 0px 0px;
    }

    .success-icon {
        border-right: 1px solid;
        padding: 0px 8px 0px 0px;
        margin: 0px 10px 0px 0px;
    }

    card {
        display: flex;
        height: 140px;
        border-radius: 6px;
        color: white;
        background: linear-gradient(297deg, rgb(201 121 23) 0%, rgba(228, 155, 57, 1) 100%);
        padding: 10px;
        width: 160px;
        margin: 0px 15px 0px 0px;
        flex-direction: column;
        transition: 200ms cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .container-table-data {
        background: white;
        padding: 20px;
        border-radius: 8px;
    }

    card h3 {
        color: white;
    }

    card:hover {
        transform: scale(1.05);
    }

    .cards-container {
        display: flex;
        width: 100%;
        align-items: center;
    }

    .title-card {
        font-weight: 700;
    }

    .btn-custom {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        padding: 2px 10px;
        margin: 0px 0px 5px 0px;
        cursor: pointer;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        border: none;
        color: #fffbf2;
        text-decoration: none;
        font-weight: 400;
    }

    .btn-custom:hover {
        background: rgb(255 255 255 / 48%);
        color: white !important;
    }

    .btn-custom .material-symbols-outlined {
        font-size: 16px;
        color: #ffffffd6;
    }

    .icon-card {
        display: flex;
        justify-content: space-between;
    }

    .icon-title {
        display: flex;
        justify-content: space-between;
    }

    .icon-title .material-symbols-outlined {
        background: #efefef63;
        height: 32px;
        width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid wheat;
        font-size: 21px;
    }

    .container-form-group {
        display: flex;
        width: 100%;
        padding: 10px 0px;
        align-items: center;
    }

    .container-form-group .form-group {
        margin: 0px 10px 0px 0px;
    }

    .container-form-group .btn {
        height: 46px;
        width: 130px;
    }

    .container-form-group .form-group label {
        position: absolute;
        background: white;
        margin: -10px 0px 0px 10px;
        padding: 0px 5px;
    }

    .dataTables_wrapper .row:first-child,
    .dataTables_wrapper .row:last-child {
        padding: 15px 15px !important;
    }

    .cria {
        padding: 25px 10px;
        border: 1px solid #eeeeee;
        border-radius: 6px;
        margin: 0px 0px 20px 0px;
    }

    .madre {
        margin: 0px 25px 0px 0px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .madre-info {
        width: 51%;
    }

    .container-cria {
        width: 100%;
    }

    @media(width < 768px) {
        .madre {
            flex-direction: column;
        }

        .madre-info {
            width: 100% !important;
        }
    }
</style>

@extends('layouts')
@section('template_title')
Partos
@endsection

@section('content')
<div id="page-head">

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
</div>

<div class="main-content">
    <div class="row">
        <div class="col-lg-12" style="padding: 0px !important">
            <div class="card stretch stretch-full">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-4 border-bot">
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
                                    <h3 class="cumb no-active-tab">Reproduccion animal</h3>
                                </a>
                                <span class="material-symbols-outlined bread">chevron_forward</span>
                                <h3 class="cumb active-tab">Partos</h3>
                            </div>
                        </div>
                        <div class="container-buttons">
                            <a href="{{ route('partos.historial') }}" style="color: rgb(255, 255, 255)">
                                <button class="btn btn-primary" style="padding: 10px !important;">Historial de
                                    partos</button>
                            </a>
                            <button type="button" class="btn btn-success ms-2" id="openImportModalPartos">
                                <span class="material-symbols-outlined">upload_file</span>
                                Importar Partos
                            </button>
                        </div>
                    </div>

                    <div class="modal-footer-ficha padding-custom">
                        <form class="modal-body d-flex" action="{{ route('partos.store') }}" method="POST"
                            style="flex-direction: column;">
                            @csrf
                            <div class="madre">
                                <div class="madre-info">
                                    <div class="title-modal-custom">
                                        <h3>Información de la Hembra</h3>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                            <label for="id_animal">Animal <span style="color: red;">*</span></label>
                                            <input type="hidden" id="id_animal" name="id_animal" required>
                                            <div class="input-dinamico-animal">
                                                <div id="animalSeleccionado"></div>
                                                <button type="button" class="buton-dinamico-animal"
                                                    data-popup="{{ $nombre ?? 'id_animal' }}">
                                                    <span class="material-symbols-outlined">search</span>
                                                </button>
                                            </div>
                                        </div>
                                        @include('components.selector-animales', ['predios' => $predios, 'animales' =>
                                        $animales])
                                    </div>
                                    <div class="d-flex space-b">
                                        <div class="form-group two-column-custom">
                                            <label for="fecha_parto">Fecha de Parto<span
                                                    style="color: red;">*</span></label>
                                            <input class="form-control" type="date" name="fecha_parto" id="fecha_parto"
                                                required>
                                        </div>
                                        <div class="form-group two-column-custom">
                                            <label for="tipo_parto">Tipo de Parto<span
                                                    style="color: red;">*</span></label>
                                            <select class="form-control" name="tipo_parto" id="tipo_parto" required>
                                                <option value="" disabled selected>Selecciona</option>
                                                <option value="Parto">Parto</option>
                                                <option value="Gemelar">Gemelar</option>
                                                <option value="Trillizo">Trillizo</option>
                                                <option value="Muerte Fetal">Muerte fetal</option>
                                                <option value="Aborto">Aborto</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="padre">Padre:</label>
                                        <select name="padre" class="form-control" id="padre">
                                            <option value="">Selecciona</option>
                                            @foreach ($toros as $toro)
                                            <option value="{{ $toro->id_animal }}">{{ $toro->nombre }} ({{ $toro->codigo
                                                }})</option>
                                            @endforeach
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2" id="otro-padre-container" style="display: none;">
                                        <label for="otro_padre">Escribe el nombre del padre:</label>
                                        <input type="text" name="otro_padre" id="otro_padre" class="form-control"
                                            placeholder="Nombre del padre">
                                    </div>
                                </div>
                                <div class="madre-info" style="width:46%">
                                    <div class="title-modal-custom">
                                        <h3>Más Información</h3>
                                    </div>
                                    <div class="d-flex space-b">
                                        <div class="form-group two-column-custom">
                                            <label for="iep">I.E.P</label>
                                            <input readonly class="form-control" type="text" name="iep" id="iep">
                                        </div>
                                        <div class="form-group two-column-custom">
                                            <label for="peso_madre">Peso (kg):</label>
                                            <input class="form-control" type="number" name="peso_madre" id="peso_madre"
                                                step="0.01" readonly>
                                        </div>
                                    </div>
                                    <div class="d-flex space-b">
                                        <div class="form-group two-column-custom">
                                            <label for="fecha_ultimo_parto">Último Parto:</label>
                                            <input type="date" name="fecha_ultimo_parto" class="form-control"
                                                id="fecha_ultimo_parto" readonly>
                                            <span id="ultimoPartoDias">Parió hace días</span>
                                        </div>
                                        <div class="form-group two-column-custom">
                                            <label for="fecha_ultimo_tacto">Último Tacto:</label>
                                            <input class="form-control" type="date" name="fecha_ultimo_tacto"
                                                id="fecha_ultimo_tacto" readonly>
                                            <span id="ultimaPrenezDias">Preñada hace días</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="observaciones">Observaciones:</label>
                                        <textarea class="form-control" name="observaciones" id="observaciones"
                                            rows="1"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div id="container-crias">
                                <div class="container-cria" id="cria-1" style="display: none;">
                                    <div class="title-modal-custom">
                                        <h3>Cría #1</h3>
                                    </div>
                                    @include('partials.campos_cria', ['numero' => 1])
                                </div>
                                <div class="container-cria" id="cria-2" style="display: none;">
                                    <div class="title-modal-custom">
                                        <h3>Cría #2</h3>
                                    </div>
                                    @include('partials.campos_cria', ['numero' => 2])
                                </div>
                                <div class="container-cria" id="cria-3" style="display: none;">
                                    <div class="title-modal-custom">
                                        <h3>Cría #3</h3>
                                    </div>
                                    @include('partials.campos_cria', ['numero' => 3])
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Registrar Parto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Modal importación partos -->
<div class="modal fade" id="importPartosModal" tabindex="-1" aria-labelledby="importPartosModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importPartosModalLabel">
                    <span class="material-symbols-outlined me-2">upload_file</span>
                    Importar Partos desde Excel
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
                        <li>El sistema detecta automáticamente partos gemelares/trillizos</li>
                        <li>Para Aborto/Muerte Fetal: llena solo madre y tipo evento</li>
                        <li>Valida IEP automáticamente</li>
                    </ul>
                </div>

                <div class="text-center mb-4">
                    <div class="mb-3">
                        <label for="predio_id_template_partos" class="form-label">
                            <strong>Seleccione el Predio <span class="text-danger">*</span></strong>
                        </label>
                        <select class="form-control" id="predio_id_template_partos" required>
                            <option value="">Seleccionar Predio</option>
                            @foreach($predios as $predio)
                            <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="downloadTemplatePartosBtn" class="btn btn-success" disabled>
                        <span class="material-symbols-outlined me-2">download</span>
                        Descargar Plantilla Excel
                    </button>
                </div>

                <form id="importPartosForm" action="{{ route('partos.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        {{-- <div class="col-12 mb-3">
                            <label for="predio_id_import_partos" class="form-label">Predio <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" name="predio_id" id="predio_id_import_partos" required>
                                <option value="">Seleccionar Predio</option>
                                @foreach($predios as $predio)
                                <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-12 mb-3">
                            <label for="file_import_partos" class="form-label">Archivo Excel <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file_import_partos" name="file"
                                accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">Máximo 5MB</small>
                        </div>
                    </div>

                    <div id="filePreviewPartos" class="alert alert-secondary" style="display: none;">
                        <h6>📄 Archivo:</h6>
                        <p id="fileNamePartos" class="mb-0"></p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id="submitImportPartos" class="btn btn-primary">
                            <span class="material-symbols-outlined me-2">upload</span>
                            Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal alerta -->
<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mensaje del sistema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@section('modal')

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/inventario/fichaDinamicaScriptParto.js"></script>
<script src="../assets/js/inventario/CriaTipoParto.js"></script>
<script src="../assets/js/inventario/otropadre.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Modal importación
    const btnImport = document.getElementById('openImportModalPartos');
    if (btnImport) {
        btnImport.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const modalEl = document.getElementById('importPartosModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });
    }

    // Preview archivo
    const fileInput = document.getElementById('file_import_partos');
    const filePreview = document.getElementById('filePreviewPartos');
    const fileName = document.getElementById('fileNamePartos');
    
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
    const formImport = document.getElementById('importPartosForm');
    if (formImport) {
        formImport.addEventListener('submit', async function(e) {
            e.preventDefault();

            const predioId = document.getElementById('predio_id_template_partos').value;
            const file = document.getElementById('file_import_partos').files[0];
            const submitBtn = document.getElementById('submitImportPartos');
            const modalEl = document.getElementById('importPartosModal');

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
            formData.append('predio_id', predioId);

            let swalConfig = null;
            let resetForm = false;

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();
                console.log('Resultado importación:', result);

                if (result.status === 'success' && result.exitosos > 0) {
                    swalConfig = {
                        icon: 'success',
                        title: '¡Importación exitosa!',
                        html: `<p><strong>${result.message}</strong></p>`,
                        timer: 3000,
                        showConfirmButton: true
                    };
                    resetForm = true;
                } else if (result.status === 'partial') {
                    let mensaje = `<div style="text-align: left;">
                        <p><strong>✅ ${result.exitosos} parto(s) importados correctamente</strong></p>`;
                    if (result.duplicados && result.duplicados.length > 0) {
                        mensaje += `<hr style="margin:15px 0"><p><strong>ℹ️ Duplicados (${result.duplicados.length}):</strong></p>
                            <ul style="max-height:150px;overflow-y:auto;color:#856404;background:#fff3cd;padding:10px;border-radius:4px;">`;
                        result.duplicados.forEach(d => { mensaje += `<li style="margin:5px 0">${d}</li>`; });
                        mensaje += `</ul>`;
                    }
                    if (result.errores && result.errores.length > 0) {
                        mensaje += `<hr style="margin:15px 0"><p><strong>❌ Errores (${result.errores.length}):</strong></p>
                            <ul style="max-height:150px;overflow-y:auto;color:#721c24;background:#f8d7da;padding:10px;border-radius:4px;">`;
                        result.errores.forEach(err => { mensaje += `<li style="margin:5px 0">${err}</li>`; });
                        mensaje += `</ul>`;
                    }
                    mensaje += `<hr style="margin:15px 0"><p style="font-size:14px;color:#666"><em>💡 Corrija los errores y vuelva a importar.</em></p></div>`;
                    swalConfig = { icon: 'warning', title: 'Importación parcial', html: mensaje, width: 750, confirmButtonText: 'Entendido' };
                } else if (result.status === 'error') {
                    const totalErrores = result.errores ? result.errores.length : 0;
                    const totalDuplicados = result.duplicados ? result.duplicados.length : 0;
                    let mensaje = `<div style="text-align:left">
                        <p><strong>📊 Resumen:</strong></p>
                        <ul style="list-style:none;padding-left:0;background:#f8f9fa;padding:10px;border-radius:4px;">
                            <li style="color:#28a745;margin:3px 0">✅ Importados: <strong>0</strong></li>
                            <li style="color:#dc3545;margin:3px 0">❌ Con errores: <strong>${totalErrores}</strong></li>
                            <li style="color:#ffc107;margin:3px 0">ℹ️ Duplicados: <strong>${totalDuplicados}</strong></li>
                        </ul>`;
                    if (result.errores && result.errores.length > 0) {
                        mensaje += `<hr style="margin:15px 0"><p><strong>❌ Errores encontrados:</strong></p>
                            <ul style="max-height:200px;overflow-y:auto;color:#721c24;background:#f8d7da;padding:10px;border-radius:4px;">`;
                        result.errores.forEach(err => { mensaje += `<li style="margin:5px 0">${err}</li>`; });
                        mensaje += `</ul>`;
                    }
                    if (result.duplicados && result.duplicados.length > 0) {
                        mensaje += `<hr style="margin:15px 0"><p><strong>ℹ️ Registros duplicados:</strong></p>
                            <ul style="max-height:150px;overflow-y:auto;color:#856404;background:#fff3cd;padding:10px;border-radius:4px;">`;
                        result.duplicados.forEach(d => { mensaje += `<li style="margin:5px 0">${d}</li>`; });
                        mensaje += `</ul>`;
                    }
                    mensaje += `<hr style="margin:15px 0"><p style="font-size:14px;color:#666"><em>💡 Corrija los errores y vuelva a intentar.</em></p></div>`;
                    swalConfig = { icon: 'error', title: result.message || 'No se pudo importar ningún parto', html: mensaje, width: 750, confirmButtonText: 'Cerrar' };
                } else {
                    swalConfig = { icon: 'info', title: 'Procesado', text: result.message || 'Operación completada' };
                }

            } catch (error) {
                console.error('Error importación:', error);
                swalConfig = {
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'Error al procesar la importación. Por favor, intente nuevamente.'
                };
            } finally {
                submitBtn.innerHTML = '<span class="material-symbols-outlined me-2">upload</span>Importar';
                submitBtn.disabled = false;
            }

            // Esperar a que la modal termine de cerrarse antes de mostrar SweetAlert
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalEl.addEventListener('hidden.bs.modal', function handler() {
                modalEl.removeEventListener('hidden.bs.modal', handler);
                if (resetForm) {
                    formImport.reset();
                    const preview = document.getElementById('filePreviewPartos');
                    if (preview) preview.style.display = 'none';
                }
                if (swalConfig) Swal.fire(swalConfig);
            });
            modal.hide();
        });
    }

    // Form parto normal
    const form = document.querySelector('form[action="{{ route('partos.store') }}"]');
    const alertModalEl = document.getElementById('alertModal');
    const modalMessage = document.getElementById('modalMessage');

    if (form && alertModalEl) {
        const alertModal = new bootstrap.Modal(alertModalEl);
        
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData,
                });
                const result = await response.json();
                modalMessage.textContent = result.message;
                alertModal.show();
                if (result.status === 'success') form.reset();
            } catch (error) {
                modalMessage.textContent = 'Error al procesar la solicitud.';
                alertModal.show();
            }
        });
    }

    // Calcular días
    $('#id_animal').on('change', function(){
        var animalId = $(this).val();
        if(animalId) {
            $.ajax({
                url: '{{ route("animales.calcularDias") }}',
                method: 'POST',
                data: {id_animal: animalId, _token: '{{ csrf_token() }}'},
                success: function(data) {
                    $('#ultimoPartoDias').text("Parió hace " + data.diasUltimoParto + " días");
                    $('#ultimaPrenezDias').text("Preñada hace " + data.diasPrenez + " días");
                }
            });
        }
    });
});

// Habilitar descarga plantilla solo si selecciona predio
const predioSelect = document.getElementById('predio_id_template_partos');
const downloadBtn = document.getElementById('downloadTemplatePartosBtn');

if (predioSelect && downloadBtn) {
    predioSelect.addEventListener('change', function() {
        downloadBtn.disabled = !this.value;
    });

    downloadBtn.addEventListener('click', function() {
        const predioId = predioSelect.value;
        if (predioId) {
            window.location.href = `{{ route('partos.template') }}?predio_id=${predioId}`;
        }
    });
}

// Manejo de mensajes flash de sesión (solo para casos excepcionales)
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
</script>
@endsection