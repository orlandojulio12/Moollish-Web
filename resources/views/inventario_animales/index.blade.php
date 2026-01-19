@extends('layouts')
@section('template_title')
ficha animales
@endsection

@section('styles')
{{-- Ya no se necesitan estos estilos flotantes personalizados --}}
{{-- <style>
    .alert-floating {
        ...
    }

    .alert-success-custom {
        ...
    }

    .alert-danger-custom {
        ...
    }

    .alert-warning-custom {
        ...
    }
</style> --}}
{{-- Estilos originales --}}
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

    .title-modal-custom h4 {
        border-bottom: 1px solid #e5e7eb;
        padding: 0px 0px 20px 0px;
    }

    .padding-custom {
        padding: 1.5rem 0rem !important;
    }

    .spacing {
        margin: 0px 10px;
        padding: 0px 10px;

    }

    .custom-date-input .material-icons {
        font-size: 24px;
        color: #2196F3;
        /* Azul */
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
        justify-content: flex-end;
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
        width: 300px;
        justify-content: space-between;
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
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        align-items: baseline;
    }
</style>
{{-- Icons css --}}
<style>
    .input-group-custom {
        display: flex;
        align-items: center;
        position: relative;
    }

    .mensaje-codigo {
        color: green;
        margin: 10px 0px;
        border-radius: 4px;
        padding: 0px 10px;

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
</style>

<style>
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

    .form-error {
        margin: 20px 20px 0px;
        padding: 10px;
        background: #ff00001f;
        color: darkred;
        border-radius: 4px;
    }

    .form-success {
        margin: 20px 20px 0px;
        padding: 10px;
        background: #00ff401f;
        color: darkgreen;
        border-radius: 4px;
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
</style>
{{-- Error styles --}}
<style>
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

    @media (width < 768px) {
        .container-ficha {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            align-items: baseline;
            flex-direction: column;
        }
    }



    .card-custom {
        border-radius: 8px;
        background: white;
        padding: 30px !important;
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
<style>
.modal-lg {
    max-width: 800px;
}

#filePreview {
    border-left: 4px solid #0d6efd;
}

.table-responsive {
    max-height: 200px;
    overflow-y: auto;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endsection
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Eliminar estas alertas flotantes estáticas --}}
{{-- <div class="alert-floating alert-success-custom" id="successAlert" style="display: none;">
    <strong>¡Éxito!</strong> <span id="successMessage"></span>
</div>
<div class="alert-floating alert-danger-custom" id="errorAlert" style="display: none;">
    <strong>Error:</strong> <span id="errorMessage"></span>
</div>
<div class="alert-floating alert-warning-custom" id="warningAlert" style="display: none;">
    <strong>¡Advertencia!</strong> <span id="warningMessage"></span>
</div> --}}

<!--===================================================-->
<div id="page-head">
    @if (session('error'))
    <div class="error-container">
        <span class="error-icon material-symbols-outlined">
            warning
        </span>
        <span class="error-alert">
            {{ session('error') }}
        </span>
    </div>
    @endif

    @if (session('success'))
    <div class="success-container">
        <span class="success-icon material-symbols-outlined">
            check_circle
        </span>
        <span class="success-alert">
            {{ session('success') }}
        </span>
    </div>
    @endif

    @if ($errors->any())
    <div class="error-container">
        <span class="error-icon material-symbols-outlined">
            warning
        </span>
        <span class="error-alert">
            Por favor corrige los siguientes errores:
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </span>
    </div>
    @endif
</div>

<!--===================================================-->
<div class="main-content">
    <div class="row">
        <div class="col-lg-12" style="padding: 0px !important">
            <div class="card-custom">
                <div class="card-body p-0">
                    <div class="header-grid">
                        @if ($user->role->name === 'propietario')
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
                            <a href="{{ route('animales') }}">
                                <h3 class="cumb no-active-tab">
                                    Animales
                                </h3>
                            </a>

                            <span class="material-symbols-outlined bread">
                                chevron_forward
                            </span>
                            <h3 class="cumb active-tab"> Ficha animal </h3>
                        </div>
                        @elseif ($user->role->name === 'admin')
                        <h3>Administrar caracterizaciones</h3>
                        @endif
                        <hr>



                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                        <div style="width: 60%;">
                            <label for="id_animal" class="form-label">Seleccione un animal <span
                                    style="color: red;">*</span></label>
                            <input type="hidden" id="id_animal" name="codigo_animal" required>

                            <div class="input-dinamico-animal">
                                <div id="animalSeleccionado"></div>
                                <button type="button" class="buton-dinamico-animal"
                                    data-popup="{{ $nombre ?? 'id_animal' }}"> <span
                                        class="material-symbols-outlined">search</span>
                                </button>
                            </div>
                            @include('components.selector-animales', ['predios' => $predios, 'animales' => $animales])
                        </div>

                        {{-- Contenedor de botones modificado --}}
                        <div class="d-flex align-items-center">
                            <div id="syncIndicator" style="display: none; margin-right: 15px;">
                                <button id="syncButton" class="btn btn-warning">
                                    <span class="material-symbols-outlined">sync</span>
                                    Sincronizar <span id="pendingCount" class="badge bg-danger">0</span>
                                </button>
                            </div>

                            {{-- Botón para crear animal individual --}}
                            <button id="openCreateAnimalModal" class="btn btn-success me-2" style="height: 42px;">
                                <span class="material-symbols-outlined">add</span>
                                Crear animal
                            </button>

                            {{-- Nuevo botón para importar desde Excel --}}
                            <button id="openImportModal" class="btn btn-primary" style="height: 42px;">
                                <span class="material-symbols-outlined">upload_file</span>
                                Cargue Masivo
                            </button>
                        </div>
                    </div>


                    <div class="container-ficha">
                        <div class="modal-content">
                            <div class="title-modal-custom">
                                <h4 class="" id="createFichaAnimalModalLabel">Datos de identificación</h4>
                            </div>
                            <div class="d-flex space-b">
                                <div class="form-group three-column-custom">
                                    <label for="id_predio">Predio:</label>
                                    <input disabled type="text" class="form-control" id="id_predio" name="id_predio"
                                        required>
                                </div>
                                <div class="form-group three-column-custom">
                                    <label for="identificacion_electronica">ID Electrónica:</label>
                                    <input disabled type="text" class="form-control" id="identificacion_electronica"
                                        name="identificacion_electronica" required>
                                </div>
                                <div class="form-group three-column-custom">
                                    <label for="id_sinigan">ID Sinigan:</label>
                                    <input disabled type="text" class="form-control" id="id_sinigan" name="id_sinigan"
                                        required>
                                </div>
                            </div>
                            <div class="title-modal-custom">
                                <h4>Padres del animal</h4>
                            </div>
                            <div class="d-flex space-b">
                                <div class="form-group two-column-custom">
                                    <label for="padre">Padre:</label>
                                    <input disabled type="text" class="form-control" id="padre" name="padre">
                                </div>
                                <div class="form-group two-column-custom">
                                    <label for="madre">Madre:</label>
                                    <input disabled type="text" class="form-control" id="madre" name="madre">
                                </div>
                            </div>
                            <div class="d-flex space-b">
                                <div class="form-group two-column-custom">
                                    <label for="raza_padre">Raza Padre:</label>
                                    <input disabled type="text" class="form-control" id="raza_padre" name="raza_padre">
                                </div>
                                <div class="form-group two-column-custom">
                                    <label for="raza_madre">Raza Madre:</label>
                                    <input disabled type="text" class="form-control" id="raza_madre" name="raza_madre">
                                </div>
                            </div>
                            <div class="title-modal-custom">
                                <h4 class="title-with-link">Estados actuales</h4>
                            </div>
                            <div class="d-flex space-b">
                                <div class="form-group two-column-custom">
                                    <label for="estado_reproductivo">Estado reproductivo:</label>
                                    <input disabled type="text" class="form-control" id="estado_reproductivo"
                                        name="estado_reproductivo">
                                </div>
                                <div class="form-group two-column-custom">
                                    <label for="estado_productivo">Estado productivo:</label>
                                    <input disabled type="text" class="form-control" id="estado_productivo_texto"
                                        name="estado_productivo">
                                </div>
                            </div>
                            <div class="d-flex space-b">
                                <div class="form-group two-column-custom">
                                    <label for="ultimo_parto">Ultimo parto:</label>
                                    <input disabled type="date" class="form-control" id="ultimo_parto"
                                        name="ultimo_parto">

                                </div>
                                <div class="form-group two-column-custom">
                                    <label for="fecha_fin">Ultimo secado:</label>
                                    <input disabled type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                </div>
                            </div>
                            <div class="title-modal-custom">
                                <h4 class="title-with-link">Ubicación <span class="link-add"
                                        id="openCreateUbicacionModal"> Nueva ubicación <span style="font-size: 12px;
                                                margin: 0px 2px;" class="material-symbols-outlined">
                                            pin_drop
                                        </span></span></h4>
                            </div>
                            <div class="d-flex space-b">
                                <div class="form-group two-column-custom">
                                    <label for="lote">Lote:</label>
                                    <input disabled type="text" class="form-control" id="lote" name="lote">
                                </div>

                                <div class="form-group two-column-custom">
                                    <label for="fecha_ubicacion">Fecha de ingreso al lote:</label>
                                    <input disabled type="text" class="form-control" id="fecha_ubicacion_lote"
                                        name="fecha_ubicacion_lote">
                                </div>
                            </div>

                            <div class="d-flex space-b">
                                <div class="form-group two-column-custom">
                                    <label for="potrero">Potrero:</label>
                                    <input disabled type="text" class="form-control" id="potrero" name="potrero">
                                </div>
                                <div class="form-group two-column-custom">
                                    <label for="fecha_ubicacion">Fecha de ingreso al potrero:</label>
                                    <input disabled type="text" class="form-control" id="fecha_ubicacion_potrero"
                                        name="fecha_ubicacion_potrero">
                                </div>
                            </div>
                        </div>
                        <div class="modal-content spacing">
                            <form action="" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="title-modal-custom">
                                        <h4>Caracteristícas fenotípicas</h4>
                                    </div>
                                    <div class="d-flex space-b">
                                        <div class="form-group three-column-custom" style="margin: 0px 5px 0px 0px;">
                                            <label for="raza">Raza:</label>
                                            <input disabled type="text" class="form-control" id="raza" name="raza">
                                        </div>
                                        <div class="form-group three-column-custom">
                                            <label for="color">Color:</label>
                                            <input disabled type="text" class="form-control" id="color" name="color"
                                                placeholder="blanco">
                                        </div>
                                        <div class="form-group three-column-custom">
                                            <label for="hierro">Hierro:</label>
                                            <input disabled type="text" class="form-control" id="hierro" name="hierro">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre_animal">Nombre animal:</label>
                                        <input disabled type="text" class="form-control" id="nombre_animal"
                                            name="nombre_animal" placeholder="Nombre">
                                    </div>

                                    <div class="d-flex space-b">
                                        <!-- Fecha de Nacimiento -->
                                        <div class="form-group two-column-custom" style="margin: 0px 5px 0px 0px;">
                                            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                                            <input disabled type="date" class="form-control" id="fecha_nacimiento"
                                                name="fecha_nacimiento">
                                        </div>

                                        <!-- Años -->
                                        <div class="form-group four-column-custom">
                                            <label for="anos">Años:</label>
                                            <input disabled type="number" class="form-control" id="anos" name="anos">
                                        </div>

                                        <!-- Meses -->
                                        <div class="form-group four-column-custom">
                                            <label for="meses">Meses:</label>
                                            <input disabled type="number" class="form-control" id="meses" name="meses">
                                        </div>

                                        <!-- Días -->
                                        <div class="form-group four-column-custom">
                                            <label for="dias">Días:</label>
                                            <input disabled type="number" class="form-control" id="dias" name="dias">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="sexo">Sexo:</label>
                                        <select class="form-control" id="sexo" name="sexo" disabled>
                                            <option value="">Seleccione</option>
                                            <option value="macho">Macho</option>
                                            <option value="hembra">Hembra</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="fecha_ingreso_hato">Fecha de Ingreso al Hato:</label>
                                        <input disabled type="date" class="form-control" id="fecha_ingreso_hato">
                                    </div>
                                    <div class="title-modal-custom">
                                        <h4 class="title-with-link">Pesos <span id="openCreatePesoModal"
                                                class="link-add">
                                                Registrar peso del animal <span style="    font-size: 12px;
                                                margin: 0px 2px;" class="material-symbols-outlined">
                                                    calendar_today
                                                </span></span></h4>
                                    </div>
                                    <div class="d-flex space-b">
                                        <div class="form-group two-column-custom" style="margin: 0px 5px 0px 0px;">
                                            <label for="ultimo_peso_fecha">Último Peso (Fecha):</label>
                                            <input disabled type="date" class="form-control" id="ultimo_peso_fecha"
                                                name="ultimo_peso_fecha">
                                        </div>
                                        <div class="form-group two-column-custom">
                                            <label for="ultimo_peso_cantidad">Último Peso (Kg):</label>
                                            <input disabled type="number" class="form-control" id="ultimo_peso_cantidad"
                                                name="ultimo_peso_cantidad">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ultimo_servicio">Último Servicio:</label>
                                        <input disabled type="text" class="form-control" id="ultimo_servicio"
                                            name="ultimo_servicio">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="modal-footer-ficha padding-custom">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Modal de importación --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <span class="material-symbols-outlined me-2">upload_file</span>
                    Importar Animales desde Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Mensajes de éxito/error --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('errores'))
                    <div class="alert alert-warning">
                        <h6>Errores encontrados:</h6>
                        <ul class="mb-0">
                            @foreach(session('errores') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Instrucciones --}}
                <div class="alert alert-info">
                    <h6 class="fw-bold mb-2">📋 Instrucciones:</h6>
                    <ul class="mb-0">
                        <li>Descarga la plantilla haciendo clic en "Descargar Plantilla"</li>
                        <li>Llena los datos (Código y Sexo son obligatorios)</li>
                        <li>Formato de fecha: YYYY-MM-DD (Ej: 2024-12-25)</li>
                        <li>Para padre/madre: usa el código del animal existente</li>
                    </ul>
                </div>

                {{-- Botón descargar plantilla --}}
                <div class="text-center mb-4">
                    <a href="{{ route('animales.template') }}" class="btn btn-success">
                        <span class="material-symbols-outlined me-2">download</span>
                        Descargar Plantilla Excel
                    </a>
                </div>

                {{-- Formulario --}}
                <form id="importForm" action="{{ route('animales.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="predio_id_import" class="form-label">Predio <span class="text-danger">*</span></label>
                            <select class="form-control" name="predio_id" id="predio_id_import" required>
                                <option value="">Seleccionar Predio</option>
                                @foreach($predios as $predio)
                                    <option value="{{ $predio->id }}">
                                        {{ $predio->nombre_predio }} - {{ $predio->codigo ?? $predio->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="file_import" class="form-label">Archivo Excel <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file_import" name="file" 
                                   accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">Archivos permitidos: .xlsx, .xls, .csv (Máximo 5MB)</small>
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div id="filePreview" class="alert alert-secondary" style="display: none;">
                        <h6>📄 Archivo seleccionado:</h6>
                        <p id="fileName" class="mb-0"></p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id="submitImport" class="btn btn-primary">
                            <span class="material-symbols-outlined me-2">upload</span>
                            Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal para crear un animal --}}
<div class="modal fade" id="createAnimalModal" tabindex="-1" aria-labelledby="createAnimalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAnimalModal">Registrar animal</h5>
                <span class="material-symbols-outlined closeCreateAnimalModal" id="" style="cursor: pointer;">
                    close
                </span>
            </div>
            <span class="form-error" id="form-error" style="display: none;">
                <!-- El mensaje de error se mostrará aquí -->
            </span>
            <span class="form-success" id="form-success" style="display: none;">
                <!-- El mensaje de error se mostrará aquí -->
            </span>

            <form id="animalForm" action="javascript:void(0)" method="POST">
                @csrf
                <input type="hidden" name="bypass_service_worker" value="true">
                <div class="modal-body">
                    <div class="d-flex space-b">
                        <div class="form-group column-custom">
                            <label for="nombre">Nombre del animal</label>
                            <div class="input-group-custom">
                                <input type="text" class="form-control" id="nombre" name="nombre">
                                <span class="input-icon material-symbols-outlined">sell</span>
                            </div>
                        </div>

                        <div class="form-group four-column-custom">
                            <label for="id_predio">Predio <span style="color: red;">*</span></label>
                            <div class="input-group-custom">
                                <select class="form-control" id="id_predio_registrar" name="id_predio" required>
                                    <option value="" disabled selected>Selecciona</option>
                                    @foreach ($predios as $predio)
                                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                                    @endforeach
                                </select>
                                <span class="input-icon material-symbols-outlined">gite</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex space-b">
                        <!-- Código -->
                        <div class="form-group three-column-custom">
                            <label for="codigo-edit">Código <span style="color: red;">*</span></label>
                            <div class="input-group-custom">
                                <input type="text" class="form-control" id="codigo-edit" name="codigo" required>
                                <span class="input-icon material-symbols-outlined">tag</span>
                            </div>
                        </div>

                        <div class="form-group three-column-custom">
                            <label for="identificacion_electronica">ID Electrónica</label>
                            <div class="input-group-custom">
                                <input type="text" class="form-control" id="identificacion_electronica"
                                    name="identificacion_electronica">
                                <span class="input-icon material-symbols-outlined">vpn_key</span>
                            </div>
                        </div>

                        <!-- ID SINIGAN -->
                        <div class="form-group three-column-custom">
                            <label for="id_sinigan">ID Sinigan</label>
                            <div class="input-group-custom">
                                <input type="text" class="form-control" id="id_sinigan" name="id_sinigan">
                                <span class="input-icon material-symbols-outlined">pin</span>
                            </div>
                        </div>
                    </div>

                    <div class="mensaje-codigo" id="messagealert">

                    </div>

                    <div class="d-flex space-b">
                        <div class="form-group two-column-custom">
                            <label for="fecha_nacimiento-edit">Fecha de Nacimiento <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control" id="fecha_nacimiento_edit" name="fecha_nacimiento"
                                required>
                        </div>

                        <!-- Años -->
                        <div class="form-group four-column-custom">
                            <label for="anos">Años:</label>
                            <input type="number" class="form-control" id="anos_edit" name="anos">
                        </div>

                        <!-- Meses -->
                        <div class="form-group four-column-custom">
                            <label for="meses">Meses:</label>
                            <input type="number" class="form-control" id="meses_edit" name="meses">
                        </div>

                        <!-- Días -->
                        <div class="form-group four-column-custom">
                            <label for="dias">Días:</label>
                            <input type="number" class="form-control" id="dias_edit" name="dias">
                        </div>
                    </div>

                    <div class="d-flex space-b">
                        <div class="form-group two-column-custom">
                            <label for="sexo-edit">Sexo <span style="color: red;">*</span></label>
                            <div class="input-group-custom">
                                <select class="form-control" id="sexo-edit" name="sexo" required>
                                    <option value="" disabled selected>Selecciona</option>
                                    <option value="macho">Macho</option>
                                    <option value="hembra">Hembra</option>
                                </select>
                                <span class="input-icon material-symbols-outlined">female</span>
                            </div>
                        </div>

                        <!-- Raza -->
                        <div class="form-group two-column-custom">
                            <label for="raza-id">Raza</label>
                            <div class="input-group-custom">
                                <select class="form-control" id="raza-id" name="raza">
                                    <option value="" disabled selected>Selecciona una raza</option>
                                    @foreach ($razas as $raza)
                                    <option value="{{ $raza->nombre_razas }}">{{ $raza->nombre_razas }}</option>
                                    @endforeach
                                </select>
                                <span class="input-icon material-symbols-outlined">eco</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex space-b">
                        <div class="form-group two-column-custom">
                            <label for="color-edit">Color</label>
                            <div class="input-group-custom">
                                <input type="text" class="form-control" id="color-edit" name="color">
                                <span class="input-icon material-symbols-outlined">palette</span>
                            </div>
                        </div>

                        <!-- Hierro -->
                        <div class="form-group two-column-custom">
                            <label for="hierro-id">Hierro</label>
                            <div class="input-group-custom">
                                <input type="number" class="form-control" id="hierro-id" name="hierro">
                                <span class="input-icon material-symbols-outlined">ev_shadow</span>
                            </div>
                        </div>
                    </div>

                    <!-- Estado productivo -->
                    <div class="form-group">
                        <label for="estado_productivo">Estado Productivo</label>
                        <select class="form-control" id="estado_productivo" name="estado_productivo">
                            <option value="" selected disabled>Selecciona</option>
                            <option value="vaca_parida">Vaca Parida</option>
                            <option value="vaca_seca">Vaca Seca</option>
                            <option value="novilla_vientre">Novilla de Vientre</option>
                            <option value="hembra_levante">Hembra de Levante</option>
                            <option value="cria_hembra">Cría Hembra</option>
                            <option value="reproductor_toro">Reproductor Toro</option>
                            <option value="macho_ceba">Macho de Ceba</option>
                            <option value="macho_levante">Macho de Levante</option>
                            <option value="cria_macho">Cría Macho</option>
                        </select>
                    </div>
                    <div>
                        <label for="fecha_ingreso_hato">Fecha ingreso hato</label>
                        <input type="date" class="form-control" name="fecha_ingreso_hato" id="fecha_ingreso_hato">
                    </div>
                    <!-- Campos Parto -->
                    <div id="partoFields" class="d-none">
                        <div class="form-group">
                            <label for="fecha_parto">Fecha de Parto <span style="color: red;">*</span></label>
                            <input type="date" class="form-control" id="fecha_parto" name="fecha_parto">
                        </div>
                        <div class="form-group">
                            <label for="tipo_parto">Tipo de Parto <span style="color: red;">*</span></label>
                            <select class="form-control" id="tipo_parto" name="tipo_parto">
                                <option value="" selected disabled>Selecciona</option>
                                <option value="Parto">Parto</option>
                                <option value="Gemelar">Gemelar</option>
                                <option value="Trillizo">Trillizo</option>
                            </select>
                        </div>

                        <!-- Crías -->
                        <div id="criasContainer" class="mt-3">
                            <div id="criasList"></div>
                            <button type="button" id="addCriaBtn" class="btn btn-sm btn-success mt-2">Agregar
                                Cría</button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="animalIsCompra">
                            <input type="checkbox" name="isComprado" id="animalIsCompra">
                            Marcar como comprado
                        </label>
                    </div>
                    <div id="compraFields" class="d-none">
                        <div class="form-group">
                            <label for="proveedor">Proveedor <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="proveedor" name="proveedor">
                        </div>
                        <div class="form-group">
                            <label for="fechaCompra">Fecha sde Compra <span style="color: red;">*</span></label>
                            <input type="date" class="form-control" id="fechaCompra" name="fechaCompra">
                        </div>
                        <div class="form-group">
                            <label for="precioCompra">Precio de Compra <span style="color: red;">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="precioCompra" name="precioCompra">
                        </div>
                    </div>
                </div>
                {{-- <button type="button" class="btn btn-secondary closeCreateAnimalModal">Cerrar</button>

                <button type="submit" class="btn btn-primary">Guardar</button>

                --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeCreateAnimalModal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                {{-- <button type="submit" class="btn btn-primary">Guardar</button>
                --}}
            </form>
        </div>
    </div>
</div>

{{-- Modal para crear una nueva ubicacion --}}
<div class="modal fade" id="CreateUbicacionModal" tabindex="-1" aria-labelledby="CreateUbicacionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="CreateUbicacionModal">Crear Ubicacion</h3>
                <span class="material-symbols-outlined closeCreateUbicacionModal" id="" style="cursor: pointer;">
                    close
                </span>
            </div>
            <form id="ubicacionForm" action="javascript:void(0)" method="POST">
                @csrf
                <div class="modal-body">
                    <h3 class="title-with-link" style="border-bottom: 1px solid lightgray;
                        margin: 0px 0px 10px 0px;">
                        Asignar
                        animales </h3>
                    <div class="form-group">
                        <label for="codigo_animal_">Código Animal:</label>
                        <select class="form-control" id="codigo_animal_" name="id_animal[]">
                            <option disabled selected value="">Seleccionar</option>
                            @foreach ($animales as $animal)
                            <option value="{{ $animal->id_animal }}">{{ $animal->codigo }}
                            </option>
                            @endforeach
                        </select>

                        <div class="selected-animals-container">
                            <span class="no-selected">Selecciona un animal</span>
                        </div>
                    </div>

                    <h3 class="title-with-link" style="border-bottom: 1px solid lightgray;
                        margin: 10px 0px;">
                        Ubicación <button class="link-add" id="openCreateUbicacionModal">Asignar con ubicaciones
                            existentes
                            <span style="font-size: 12px;
                        margin: 0px 2px;" class="material-symbols-outlined">
                                pin_drop
                            </span></button></h3>

                    <div class="d-flex space-b">
                        <!-- Lote Select -->
                        <div class="form-group two-column-custom" style="margin: 0px 5px 0px 0px;">
                            <label for="lote">Lote (opcional)</label>
                            <div class="input-group-custom">
                                <select class="form-control" id="lote" name="lote">
                                    <option disabled selected value="">Seleccionar Lote </option>
                                    @foreach ($lotes as $lote)
                                    <option value="{{ $lote->id }}">{{ $lote->nombre }}</option>
                                    @endforeach
                                </select>
                                <span class="input-icon material-symbols-outlined">
                                    grid_on
                                </span>
                            </div>
                        </div>

                        <!-- Potrero Select -->
                        <div class="form-group two-column-custom">
                            <label for="potrero">Potrero</label>
                            <div class="input-group-custom">
                                <select class="form-control" id="potrero" name="potrero">
                                    <option disabled selected value="">Seleccionar Potrero</option>
                                    @foreach ($potreros as $potrero)
                                    <option value="{{ $potrero->id }}">{{ $potrero->nombre }}</option>
                                    @endforeach
                                </select>
                                <span class="input-icon material-symbols-outlined">
                                    outdoor_garden
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fecha_ubicacion">Fecha de Ubicación</label>
                        <input type="date" class="form-control" id="fecha_movimiento" name="fecha_movimiento" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_ubicacion">Motivo (opcional)</label>
                        <textarea name="motivo" class="form-control" id="motivo" cols="30" rows="2"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary closeCreateUbicacionModal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>

{{-- Modal para registrar un peso --}}
<div class="modal fade" id="CreatePesoModal" tabindex="-1" aria-labelledby="CreatePesoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="CreatePesoModal">Registrar Peso</h3>
                <span class="material-symbols-outlined closeCreatePesoModal" id="" style="cursor: pointer;">
                    close
                </span>
            </div>
            <form id="pesoForm" action="javascript:void(0)" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fecha_pesaje">Fecha del pesaje </label>
                        <input type="date" class="form-control" id="fecha_pesaje" name="fecha_pesaje" required>
                    </div>
                    <div class="d-flex space-b">
                        <div class="form-group two-column-custom " style="    margin: 0px 5px 0px 0px;">
                            <label for="id_animal_pesaje">Código Animal:</label>
                            <select class="form-control" id="id_animal_pesaje" name="id_animal_pesaje" required>
                                <option value="">Seleccionar</option>
                                @foreach ($animales as $animal)
                                <option value="{{ $animal->id_animal }}">{{ $animal->codigo }} -
                                    {{ $animal->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group two-column-custom">
                            <label for="peso">Peso en Kg</label>
                            <div class="input-group-custom">
                                <input type="number" class="form-control" id="peso" name="peso" required>
                                <span class="input-icon material-symbols-outlined">
                                    weight
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary closeCreatePesoModal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Modal estado productivo y reproductivo --}}
<div class="modal fade" id="CreateEstadoModal" tabindex="-1" aria-labelledby="CreateEstadoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="CreateEstadoModalLabel">Registrar Estado Animal</h4>
                <span class="material-symbols-outlined closeCreateEstadoModal" style="cursor: pointer;">
                    close
                </span>
            </div>

            <form id="estadoForm" action="{{ route('animal.estado.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Selección del Código de Animal -->
                    <div class="form-group">
                        <label for="id_animal">Código Animal:</label>
                        <select class="form-control" id="id_animal" name="id_animal" required>
                            <option value="">Seleccionar Animal</option>
                            @foreach ($animales as $animal)
                            <option value="{{ $animal->id_animal }}" data-sexo="{{ $animal->sexo }}">
                                {{ $animal->codigo }} - {{ $animal->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado Productivo (solo hembras) -->
                    <div class="form-group" id="estado_productivo_wrapper">
                        <label for="estado_productivo_id">Estado productivo</label>
                        <select class="form-control @error('estado_productivo_id') is-invalid @enderror"
                            id="estado_productivo_id" name="estado_productivo_id">
                            <option value="">Seleccionar estado productivo</option>
                        </select>
                        @error('estado_productivo_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estado Reproductivo (para machos y hembras) -->
                    <div class="form-group" id="estado_reproductivo_wrapper" style="display: none;">
                        <label for="estado_reproductivo_id">Estado reproductivo</label>
                        <select class="form-control @error('estado_reproductivo_id') is-invalid @enderror"
                            id="estado_reproductivo_id" name="estado_reproductivo_id">
                            <option value="">Seleccionar estado reproductivo</option>
                        </select>
                        @error('estado_reproductivo_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex space-b">
                        <!-- Fecha de inicio -->
                        <div class="form-group two-column-custom ">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                id="fecha_inicio" name="fecha_inicio" required>
                            @error('fecha_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fecha de fin -->
                        <div class="form-group two-column-custom">
                            <label for="fecha_fin">Fecha de fin (opcional)</label>
                            <input type="date" class="form-control  @error('fecha_fin') is-invalid @enderror"
                                id="fecha_fin" name="fecha_fin">
                            @error('fecha_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeCreateEstadoModal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>


        </div>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../assets/js/inventario/estadoScript.js"></script>
<script src="../assets/js/inventario/fichaDinamicaScript.js"></script>
<script src="../assets/js/inventario/hiddenShowModalScript.js"></script>
<script src="../assets/js/inventario/selectedMultipleScript.js"></script>
<script src="../assets/js/inventario/CreateAnimalFecha.js"></script>
<script>

document.addEventListener('DOMContentLoaded', function() {
    // Abrir modal de importación
    document.getElementById('openImportModal').addEventListener('click', function() {
        const importModal = new bootstrap.Modal(document.getElementById('importModal'));
        importModal.show();
    });

    // Validación del archivo
    const fileInput = document.getElementById('file_import');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validar tamaño (5MB)
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('El archivo es demasiado grande. Máximo 5MB permitido.');
                this.value = '';
                filePreview.style.display = 'none';
                return;
            }
            
            // Validar tipo de archivo
            const allowedTypes = [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                'application/vnd.ms-excel', // .xls
                'text/csv' // .csv
            ];
            
            if (!allowedTypes.includes(file.type)) {
                alert('Tipo de archivo no permitido. Solo se permiten archivos .xlsx, .xls, .csv');
                this.value = '';
                filePreview.style.display = 'none';
                return;
            }
            
            // Mostrar preview del archivo
            fileName.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            filePreview.style.display = 'block';
        } else {
            filePreview.style.display = 'none';
        }
    });

    // Enviar formulario
    document.getElementById('submitImport').addEventListener('click', function() {
        const form = document.getElementById('importForm');
        const predioId = document.getElementById('predio_id_import').value;
        const fileInput = document.getElementById('file_import');
        
        // Validaciones
        if (!predioId) {
            alert('Por favor selecciona un predio');
            return;
        }
        
        if (!fileInput.files[0]) {
            alert('Por favor selecciona un archivo');
            return;
        }
        
        // Mostrar indicador de carga
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importando...';
        this.disabled = true;
        
        // Enviar formulario
        form.submit();
    });
});
</script>

<script>
    // Variable global para la instancia de la BD
        let moollishDBInstance;
        // Variable para almacenar la promesa de inicialización
        let dbInitializationPromise;
        const ANIMAL_STORE = 'pending_animales';
        const PREDIO_STORE = 'pending_predios';
        const MAPPING_STORE = 'id_mappings';

        document.addEventListener("DOMContentLoaded", function() {
            // Configurar el token CSRF para todas las solicitudes AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // --- Inicialización de IndexedDB ---
            // Iniciar la inicialización y guardar la promesa
            // dbInitializationPromise = MoollishDB.initialize(); // Comentado - Offline

            /* // Comentado - Offline
            dbInitializationPromise
                .then(db => {
                    moollishDBInstance = db;
                    console.log('IndexedDB inicializada correctamente en index animales.');
                    // Inicialmente verificar pendientes al cargar la página
                    checkPendingRecords();
                    if (navigator.onLine) {
                        // Si estamos online, intentar sincronizar todo al inicio
                        sincronizarTodo();
                    }
                })
                .catch(error => {
                    console.error('Error CRÍTICO al inicializar IndexedDB:', error);
                    showAlert('error', 'No se pudo inicializar la base de datos offline. Funcionalidad limitada.');
                    // Deshabilitar funcionalidades offline si falla la BD
                    $('#openCreateAnimalModal').prop('disabled', true).attr('title', 'Almacenamiento offline no disponible');
                    $('#syncButton').prop('disabled', true);
                });
            */
            // --- Fin Inicialización ---

            // --- Lógica carga de predios --- (Se llamará al abrir el modal)
            async function cargarPrediosConOffline() {
                console.log('[cargarPredios] Iniciando carga de predios (desde Blade)...'); // Modificado
                const $select = $('#id_predio_registrar');
                $select.empty().append('<option value="" disabled selected>Cargando...</option>');

                // Comentado - Offline
                /*
                // Asegurarse que moollishDBInstance esté listo (ya debería estarlo si se llama después del await)
                if (!moollishDBInstance) {
                    $select.empty().append('<option value="">Error BD Offline</option>');
                    showAlert('error', 'La base de datos offline no está lista (inesperado).');
                    return;
                }
                */

                try {
                    // 1. Obtener predios desde la variable Blade (convertida a JSON)
                    const prediosDesdeBlade = {!! json_encode($predios->map(function($predio) { return ['id' => $predio->id, 'nombre_predio' => $predio->nombre_predio]; })->all()) !!};
                    console.log('[cargarPredios] Predios obtenidos desde Blade:', prediosDesdeBlade);

                    // --- ELIMINAR LLAMADA AJAX ---
                    /*
                    let prediosOnline = [];
                    try {
                         // Ajusta la URL si es necesario
                        prediosOnline = await $.get("{{ route('api.predios.list') }}");
                    } catch (apiError) {
                        console.warn('No se pudieron cargar predios online:', apiError);
                        showAlert('error', 'Error al cargar los predios desde el servidor.');
                        $select.empty().append('<option value="">Error al cargar</option>');
                        return; // Detener si falla la carga online
                    }
                    */
                    // --- FIN ELIMINAR LLAMADA AJAX ---

                    // Comentado - Offline
                    /*
                    // 2. Cargar predios pendientes de sincronización
                    const prediosPendientes = await MoollishDB.getPendingData(moollishDBInstance, PREDIO_STORE);

                    // 3. Cargar mapeos existentes para no duplicar
                    const mappings = await MoollishDB.getPendingData(moollishDBInstance, MAPPING_STORE); // Obtener todos los mapeos
                    const mappedTempIds = new Set(mappings.filter(m => m.tipo === 'predio').map(m => m.temp_id));
                    */

                    // 4. Combinar listas (Solo datos de Blade)
                    const prediosParaSelect = [];

                    // Añadir predios desde Blade
                    if (Array.isArray(prediosDesdeBlade)) {
                        prediosDesdeBlade.forEach(p => {
                            prediosParaSelect.push({
                                id: p.id, // ID real
                                nombre: p.nombre_predio, // Ajustar si el nombre de la propiedad es diferente
                                esOffline: false
                            });
                        });
                    }

                    // Comentado - Offline
                    /*
                    // Añadir predios offline PENDIENTES (que no tengan mapeo aún)
                    prediosPendientes.forEach(p => {
                        // Usar el temp_id generado al guardar offline
                        const tempId = p.temp_id;
                        if (tempId && !mappedTempIds.has(tempId)) {
                            prediosParaSelect.push({
                                id: tempId, // Usar temp_id como valor
                                nombre: p.nombre_predio,
                                esOffline: true
                            });
                        } else if (!tempId) {
                            console.warn('Predio pendiente sin temp_id encontrado:', p.id);
                        }
                    });
                    */

                    // 5. Limpiar y poblar el select
                    $select.empty().append('<option value="" disabled selected>Selecciona</option>');
                    if (prediosParaSelect.length === 0) {
                         $select.append('<option value="" disabled>No hay predios disponibles</option>');
                         showAlert('warning', 'No hay predios disponibles. Crea uno primero.');
                    } else {
                        // Ordenar alfabéticamente
                        prediosParaSelect.sort((a, b) => a.nombre.localeCompare(b.nombre));

                        prediosParaSelect.forEach(predio => {
                            const optionText = predio.nombre; // Solo nombre online
                            const option = new Option(optionText, predio.id);
                            // Comentado - Offline
                            /*
                            if (predio.esOffline) {
                                $(option).addClass('text-warning'); // Estilo para offline
                            }
                            */
                            $select.append(option);
                        });
                    }

                } catch (error) {
                    console.error('[cargarPredios] Error al procesar predios desde Blade:', error);
                    $select.empty().append('<option value="">Error al cargar</option>');
                    showAlert('error', 'Error al cargar la lista de predios.');
                }
                 console.log('[cargarPredios] Carga de predios (desde Blade) finalizada.');
            }

            // Evento para cargar predios al abrir el modal de crear animal (MODIFICADO para solo online)
            $('#openCreateAnimalModal').on('click', async function() { // Hacer la función async
                // Deshabilitar el botón mientras se espera/carga
                $(this).prop('disabled', true);
                try {
                    // Comentado - Offline
                    /*
                    // Esperar a que la promesa de inicialización se resuelva
                    await dbInitializationPromise;
                    console.log('[Click Crear Animal] DB inicializada, procediendo a cargar predios.');
                    */
                    console.log('[Click Crear Animal] Procediendo a cargar predios (solo online).');
                    await cargarPrediosConOffline(); // Cargar/recargar predios (ahora es seguro)
                    $('#createAnimalModal').modal('show');
                } catch (error) {
                    console.error('[Click Crear Animal] Error esperando la inicialización de la BD:', error);
                    showAlert('error', 'No se pudo preparar el formulario: Error al acceder a la base de datos offline.');
                    // Podrías decidir no abrir el modal si la BD falla
                    // $('#createAnimalModal').modal('hide');
                } finally {
                    // Volver a habilitar el botón
                    $(this).prop('disabled', false);
                }
            });

            const checkbox = document.getElementById("animalIsCompra");
            const compraFields = document.getElementById("compraFields");
            if (checkbox) {
            checkbox.addEventListener("change", function() {
                if (checkbox.checked) {
                    compraFields.classList.remove("d-none");
                    compraFields.querySelectorAll("input").forEach(input => {
                        input.setAttribute("required", "true");
                    });
                } else {
                    compraFields.classList.add("d-none");
                    compraFields.querySelectorAll("input").forEach(input => {
                        input.removeAttribute("required");
                    });
                }
            });
            }

            // --- Manejo del formulario de Animal (Modificado para solo Online) ---
            document.querySelector('#animalForm button[type="submit"]').addEventListener('click', function(e) {
                console.log('[Submit Animal] Click detectado en botón Guardar (modo solo online).');
                e.preventDefault();
                var form = $('#animalForm');
                // Desactivar botón para evitar doble click
                $(this).prop('disabled', true);

                // Obtener el ID del predio seleccionado
                const selectedPredioId = $('#id_predio_registrar').val();
                console.log(`[Submit Animal] Predio Seleccionado: ${selectedPredioId}`);

                // Comentado - Offline
                /*
                // *** Nueva Lógica: Priorizar offline si el predio es temporal ***
                if (selectedPredioId && selectedPredioId.startsWith('temp_')) {
                    console.log('[Submit Animal] Predio temporal detectado. Forzando guardado offline...');
                    handleOfflineSubmit(form);
                    } else {
                    // Si el predio no es temporal (o no se seleccionó), usar navigator.onLine
                    const isOnline = navigator.onLine;
                    console.log(`[Submit Animal] Estado navigator.onLine: ${isOnline}`);
                    if (!isOnline) {
                        console.log('[Submit Animal] Ejecutando handleOfflineSubmit (navigator offline)...');
                        handleOfflineSubmit(form);
                    } else {
                        console.log('[Submit Animal] Ejecutando handleOnlineSubmit (navigator online o predio real)...');
                        handleOnlineSubmit(form);
                    }
                }
                */

                // Siempre intentar enviar online
                console.log('[Submit Animal] Ejecutando handleOnlineSubmit...');
                handleOnlineSubmit(form); // Llamar directamente a la función online

                // Reactivar después de un tiempo prudencial o en finally
                setTimeout(() => $(this).prop('disabled', false), 1500);
                return false;
            });

            // Función para manejar envío online (Asegurar que esté presente)
            function handleOnlineSubmit(form) {
                console.log('[handleOnlineSubmit] Función iniciada.');
                var formData = new FormData(form[0]);
                console.log('[Online Submit] Iniciando envío AJAX...');
                $('#form-error').hide();
                $('#form-success').text('Enviando datos al servidor...').fadeIn();

                 // Log FormData antes de enviar
                console.log('[handleOnlineSubmit] FormData a enviar:');
                for (let [key, value] of formData.entries()) {
                    console.log(`  ${key}: ${value}`);
                }

                $.ajax({
                    url: "{{    ('animal.store') }}", // Ruta para guardar animal
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('[Online Submit] Respuesta del servidor:', response);
                        if (response.success) {
                            form[0].reset(); // Limpiar formulario
                            $('#form-error').hide();
                            $('#form-success').text(response.message || 'Animal registrado correctamente').fadeIn();

                            // Actualizar la lista de animales si es necesario o cerrar modal
                            setTimeout(function() {
                                $('.closeCreateAnimalModal').trigger('click');
                                // Limpiar mensaje después de cerrar (opcional)
                                $('#form-success').hide().text('');
                            }, 2500);
                        } else {
                            $('#form-success').hide();
                            $('#form-error').text(response.message || 'Error en la respuesta del servidor.').fadeIn();
                            // showAlert('error', 'Error al registrar el animal.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('[Online Submit] Error AJAX:', xhr, status, error);
                        $('#form-success').hide();
                        let errorMsg = 'Error al enviar los datos.';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                // Manejar errores de validación (ejemplo)
                                let errors = Object.values(xhr.responseJSON.errors).flat().join('\n');
                                errorMsg = `Por favor corrige los siguientes errores:\n${errors}`;
                            }
                        }
                        $('#form-error').text(errorMsg).fadeIn();
                        // showAlert('error', 'Error al enviar el animal: ' + errorMsg);
                    },
                    complete: function() {
                        // Reactivar botón (ya se hace con setTimeout, pero puede ser redundante seguro)
                        form.find('button[type="submit"]').prop('disabled', false);
                    }
                });
            }
        });

</script>

@endsection