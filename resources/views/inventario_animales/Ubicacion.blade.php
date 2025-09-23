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
</style>

<style>

.tooltip-container {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.tooltip-container .tooltip-text {
    visibility: hidden;
    width: 150px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 4px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%; /* Cambia esto según la posición que desees */
    left: 50%;
    margin-left: -75px; /* La mitad del ancho */
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip-container:hover {
    color: #015499;
}
.tooltip-container:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}



</style>
{{-- Icons css --}}
<style>
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
</style>
<style>
    .selected-animals-container {
        border: 1px solid #e5e7eb;
        padding: 10px;
        margin: 10px 0px;
        border-radius: 4px;
        display: flex;
        flex-wrap: wrap;
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
</style>
{{-- Ubicacion styles --}}
<style>
    card {
        display: flex;
        height: 140px;
        border-radius: 6px;
        color: white;
        background: linear-gradient(297deg, rgb(201 121 23) 0%, rgba(228, 155, 57, 1) 100%);
        padding: 10px;
        width: 160px;
        margin: 0px 15px 15px 0px;
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
        flex-wrap: wrap;
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
        /* padding: 0px 0px 3px; */
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

    @media (width < 768px) {
        card {
            width: 100%;
        }

        .col-sm-12 {
            overflow: auto;
        }

        .modal-dialog {
    width: 97% !important;
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
@extends('layouts')
@section('template_title')
    Ubicacion animal
@endsection
@section('content')
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

        <!-- Mostrar mensaje de éxito si existe -->
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
                                    <h3 class="cumb active-tab"> Ubicaciones </h3>
                                </div>
                            @elseif ($user->role->name === 'admin')
                                <h3>Administrar caracterizaciones</h3>
                            @endif
                            <hr>
                        </div>

                        <div class="modal-footer-ficha padding-custom">
                            <div class="cards-container">
                                <card>
                                    <div class="icon-title">
                                        <h3>{{ $movimientos->count() }}</h3>
                                        <span class="material-symbols-outlined">cached</span>
                                    </div>
                                    <div class="title-card">Movimientos</div>
                                    <div class="btn-custom" id="openCreateUbicacionModal">Crear nuevo <span
                                            class="material-symbols-outlined">add</span></div>
                                    <div class="btn-custom" onclick="showTable('movimientos')">Ver todos <span
                                            class="material-symbols-outlined">chevron_right</span></div>
                                </card>

                                <card>
                                    <div class="icon-title">
                                        <h3>{{ $predios->count() }}</h3>
                                        <span class="material-symbols-outlined">villa</span>
                                    </div>
                                    <div class="title-card">Predios</div>
                                    <a class="btn-custom" href="/predios/create">Crear nuevo <span
                                            class="material-symbols-outlined">add</span></a>
                                    <div class="btn-custom" onclick="showTable('predios')">Ver todos <span
                                            class="material-symbols-outlined">chevron_right</span></div>
                                </card>

                                <card>
                                    <div class="icon-title">
                                        <h3>{{ $potreros->count() }}</h3>
                                        <span class="material-symbols-outlined">outdoor_garden</span>
                                    </div>
                                    <div class="title-card">Potreros</div>
                                    <div class="btn-custom" onclick="openModal('modal-potreros')">Crear nuevo <span
                                            class="material-symbols-outlined">add</span></div>
                                    <div class="btn-custom" onclick="showTable('potreros')">Ver todos <span
                                            class="material-symbols-outlined">chevron_right</span></div>
                                </card>

                                <card>
                                    <div class="icon-title">
                                        <h3>{{ $lotes->count() }}</h3>
                                        <span class="material-symbols-outlined">sell</span>
                                    </div>
                                    <div class="title-card">Lotes</div>
                                    <div class="btn-custom" onclick="openModal('modal-lotes')">Crear nuevo <span
                                            class="material-symbols-outlined">add</span></div>
                                    <div class="btn-custom" onclick="showTable('lotes')">Ver todos <span
                                            class="material-symbols-outlined">chevron_right</span></div>
                                </card>
                            </div>

                            <!-- Contenedor para mostrar la tabla seleccionada -->



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content" id="table-container">
        <div class="row">
            <div class="card-custom" cellspacing="0" id="container-table-movimientos"
            style="display: none;">
            <h3>Movimientos</h3>
            <table id="table-movimientos">
                <thead>
                    <tr>

                        <th>Animal</th>
                        <th>Predio</th>
                        <th>Lote</th>
                        <th>Potrero</th>
                        <th>Fecha Movimiento</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>

                    {{--    {{ $movimientos }} --}}
                    @foreach ($movimientos as $movimiento)
                        <tr>

                            <td>{{ $movimiento->animal->codigo }}</td>
                            <td>{{ $movimiento->predio->nombre_predio ?? 'N/A' }}</td>
                            <td>{{ $movimiento->lote->nombre ?? 'N/A' }}</td>
                            <td>{{ $movimiento->potrero->nombre ?? 'N/A' }}</td>
                            <td>{{ $movimiento->fecha_movimiento }}</td>
                            <td>{{ $movimiento->motivo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="table table-hover card-custom" cellspacing="0" id="container-table-predios"
            style="display: none;">
            <h3>Predios</h3>
            <table id="table-predios">
                <thead>
                    <tr>

                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Propietario</th>
                        <th>Animales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($predios as $predio)
                        <tr>

                            <td>{{ $predio->cod_predio }}</td>
                            <td>{{ $predio->nombre_predio }}</td>
                            <td>{{ $predio->departamento }}</td>
                            <td>{{ $predio->municipio }}</td>
                            <td>
                                @if ($predio->usuarios->isNotEmpty())
                                    @foreach ($predio->usuarios as $usuario)
                                        <span class="tooltip-container">
                                            {{ $usuario->documento }}
                                            <span class="tooltip-text">{{ $usuario->name }}</span>
                                        </span><br>
                                    @endforeach
                                @else
                                    <span>N/A</span>
                                @endif
                            </td>


                            <td>{{ $predio->animales_count }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm show-animals-btn" data-type="predio" data-id="{{ $predio->id }}" data-name="{{ $predio->nombre_predio }}">Ver Animales</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>


        <div class="table table-hover card-custom" cellspacing="0" id="container-table-potreros"
            style="display: none;">
            <h3>Potreros</h3>

            <table id="table-potreros">
                <thead>
                    <tr>

                        <th>Nombre</th>
                        <th>Area</th>
                        <th>Predio</th>
                        <th>Animales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($potreros as $potrero)
                        <tr>

                            <td>{{ $potrero->nombre }}</td>
                            <td>{{ $potrero->area }} Hectarea(s)</td>
                            <td>{{ $potrero->predio->nombre_predio ?? 'N/A' }}</td>
                            <td>{{ $potrero->animales_count }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm show-animals-btn" data-type="potrero" data-id="{{ $potrero->id }}" data-name="{{ $potrero->nombre }}">Ver Animales</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>


        <div class="table table-hover card-custom" cellspacing="0" id="container-table-lotes"
            style="display: none;">
            <h3>Lotes</h3>
            <table id="table-lotes">
                <thead>
                    <tr>

                        <th>Nombre</th>
                        <th>Predio</th>
                        <th>Animales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lotes as $lote)
                        <tr>

                            <td>{{ $lote->nombre }}</td>
                            <td>{{ $lote->predio->nombre_predio ?? 'N/A' }}</td>
                            <td>{{ $lote->animales_count }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm show-animals-btn" data-type="lote" data-id="{{ $lote->id }}" data-name="{{ $lote->nombre }}">Ver Animales</button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        </div>

    </div>
@endsection

    <div class="modal fade" id="CreateUbicacionModal" tabindex="-1" aria-labelledby="CreateUbicacionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="CreateUbicacionModal">Registrar movimiento</h3>
                    <span class="material-symbols-outlined closeCreateUbicacionModal" id=""
                        style="cursor: pointer;">
                        close
                    </span>
                </div>
                <form id="ubicacionForm" action="{{ route('storeUbicacion.store') }}" method="POST">
                    @csrf
                        <div class="modal-body">
                        <h3 class="title-with-link"
                            style="border-bottom: 1px solid lightgray;
                            margin: 0px 0px 10px 0px;">Asignar
                            animales </h3>
                        <div class="form-group">
                            <label for="codigo_animal_">Código Animal:</label>
                            <select class="form-control" id="codigo_animal_" name="id_animal[]">
                                <option disabled selected value="">Seleccionar</option>
                                @foreach ($animales as $animal)
                                    <option value="{{ $animal->id_animal }}">{{ $animal->codigo }} -
                                        {{ $animal->nombre }}</option>
                                @endforeach
                            </select>

                            <div class="selected-animals-container">
                                <span class="no-selected">Selecciona un animal</span>
                            </div>
                        </div>

                        <h3 class="title-with-link"
                            style="border-bottom: 1px solid lightgray;
                        margin: 10px 0px;">
                            Ubicación <button class="link-add" id="openCreateUbicacionModal">Asignar con ubicaciones
                                existentes
                                <span style="font-size: 12px;
                        margin: 0px 2px;"
                                    class="material-symbols-outlined">
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
                                    <select class="form-control" id="potrero" name="potrero" >
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
                            <input type="date" class="form-control" id="fecha_movimiento" name="fecha_movimiento"
                                required>
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
    <!-- Modal para Predios -->
    <div id="modal-predios" class="modal-create" style="display: none;">
        <div class="modal-content-create">
            <span class="close" onclick="closeModal('modal-predios')">&times;</span>

            <form id="predioForm" action="{{ route('predios.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <h3 class="title-with-link-c" style="border-bottom: 1px solid lightgray; margin-bottom: 10px;">
                        Crear Nuevo Predio
                    </h3>

                    <!-- Nombre del Predio -->
                    <div class="form-group">
                        <label for="nombre_predio">Nombre del Predio</label>
                        <input type="text" class="form-control" id="nombre_predio" name="nombre_predio" required>
                    </div>

                    <!-- Código del Predio -->
                    <div class="form-group">
                        <label for="cod_predio">Código del Predio</label>
                        <input type="text" class="form-control" id="cod_predio" name="cod_predio" required>
                    </div>

                    <!-- Departamento -->
                    <div class="form-group">
                        <label for="departamento">Departamento</label>
                        <input type="text" class="form-control" id="departamento" name="departamento" required>
                    </div>

                    <!-- Municipio -->
                    <div class="form-group">
                        <label for="municipio">Municipio</label>
                        <input type="text" class="form-control" id="municipio" name="municipio" required>
                    </div>

                    <!-- Vereda -->
                    <div class="form-group">
                        <label for="vereda">Vereda</label>
                        <input type="text" class="form-control" id="vereda" name="vereda" required>
                    </div>

                    <!-- Forma de Llegar -->
                    <div class="form-group">
                        <label for="forma_de_llegar">Forma de Llegar</label>
                        <textarea class="form-control" id="forma_de_llegar" name="forma_de_llegar" rows="2"></textarea>
                    </div>

                    <!-- Latitud -->
                    <div class="form-group">
                        <label for="latitud">Latitud</label>
                        <input type="text" class="form-control" id="latitud" name="latitud">
                    </div>

                    <!-- Longitud -->
                    <div class="form-group">
                        <label for="longitud">Longitud</label>
                        <input type="text" class="form-control" id="longitud" name="longitud">
                    </div>

                    <!-- Botones de acción -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary margin-5" onclick="closeModal('modal-predios')">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Predio</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal para Potreros -->
    <div id="modal-potreros" class="modal-create" style="display: none;">
        <div class="modal-content-create">
            <form id="potreroForm" action="{{ route('potreros.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <span class="close closeModalPotreros">&times;</span>
                    <h3 class="title-with-link-c" style="border-bottom: 1px solid lightgray; margin-bottom: 10px;">
                        Crear Nuevo Potrero
                    </h3>
                    <!-- Nombre del Potrero -->
                    <div class="form-group">
                        <label for="nombre">Nombre del Potrero</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <!-- Capacidad del Potrero -->
                    <div class="form-group">
                        <label for="area">Área</label>
                        <input type="number" class="form-control" id="area" name="area" >
                    </div>
                    <!-- Selección de Predio -->
                    <div class="form-group">
                        <label for="predio_id">Seleccionar Predio</label>
                        <select class="form-control" id="predio_id" name="predio_id" required>
                            <option disabled selected value="">Seleccione un predio</option>
                            @foreach ($predios as $predio)
                                <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Botones de acción -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary margin-5" onclick="closeModal('modal-potreros')">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Potrero</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Modal para mostrar animales --}}
    <div class="modal fade" id="animalsModal" tabindex="-1" aria-labelledby="animalsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="animalsModalLabel">Lista de Animales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="animalsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Animal</th>
                                <th>Nombre</th>
                                <th>Raza</th>
                                <th>Sexo</th>
                                <th>Edad</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para Lotes -->
    <div id="modal-lotes" class="modal-create" style="display: none;">
        <div class="modal-content-create">

            <form id="loteForm" action="{{ route('lotes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
            <span class="close closeModalLotes" >&times;</span>

                    <h3 class="title-with-link-c" style="border-bottom: 1px solid lightgray; margin-bottom: 10px;">
                        Crear Nuevo Lote
                    </h3>

                    <!-- Nombre del Lote -->
                    <div class="form-group">
                        <label for="nombre">Nombre del Lote</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <!-- Área del Lote -->


                    <!-- Selección de Predio -->
                    <div class="form-group">
                        <label for="predio_id">Seleccionar Predio</label>
                        <select class="form-control" id="predio_id" name="predio_id" required>
                            <option disabled selected value="">Seleccione un predio</option>
                            @foreach ($predios as $predio)
                                <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botones de acción -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary margin-5" onclick="closeModal('modal-lotes')">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Lote</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../assets/js/inventario/estadoScript.js"></script>
    <script src="../assets/js/inventario/fichaDinamicaScript.js"></script>
    <script src="../assets/js/inventario/hiddenShowModalScript.js"></script>
    <script src="../assets/js/inventario/selectedMultipleScript.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

<script>
    $(document).ready(function () {
    $('.show-animals-btn').on('click', function () {
        const type = $(this).data('type');
        const id = $(this).data('id');
        const name = $(this).data('name'); // Obtener el nombre del predio, potrero o lote
        let url = '';

        // Actualizar el título del modal
        let title = `Lista de Animales`;
        if (type === 'predio') {
            title = `Lista de animales del predio "${name}"`;
            url = `/animales/predio/${id}`;
        } else if (type === 'potrero') {
            title = `Lista de animales del potrero "${name}"`;
            url = `/animales/potrero/${id}`;
        } else if (type === 'lote') {
            title = `Lista de animales del lote "${name}"`;
            url = `/animales/lote/${id}`;
        }
        $('#animalsModalLabel').text(title); // Cambiar el título dinámicamente

        // Llamada AJAX para obtener los animales
        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                if (response.success) {
                    const animales = response.animales;
                    const $tableBody = $('#animalsTable tbody');
                    $tableBody.empty();

                    if (animales.length > 0) {
                        animales.forEach(animal => {
                            $tableBody.append(`
                                <tr>
                                    <td>${animal.id_animal}</td>
                                    <td>${animal.codigo}</td>
                                    <td>${animal.nombre}</td>
                                    <td>${animal.raza || 'N/A'}</td>
                                    <td>${animal.sexo}</td>
                                    <td>${animal.edad || 'N/A'}</td>
                                </tr>
                            `);
                        });
                    } else {
                        $tableBody.append('<tr><td colspan="6">No hay animales en esta ubicación.</td></tr>');
                    }

                    $('#animalsModal').modal('show');
                } else {
                    alert('Error al cargar los animales.');
                }
            },
            error: function () {
                alert('Ocurrió un error al obtener los animales.');
            }
        });
    });
});


</script>
    <script>
        function showTable(tableName) {
            // Ocultar todas las tablas
            document.getElementById("container-table-movimientos").style.display = "none";
            document.getElementById("container-table-predios").style.display = "none";
            document.getElementById("container-table-potreros").style.display = "none";
            document.getElementById("container-table-lotes").style.display = "none";

            // Mostrar la tabla seleccionada
            document.getElementById(`container-table-${tableName}`).style.display = "block";
        }
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
    <script>
        $(document).ready(function() {
            $('#table-movimientos').DataTable();
        });

        $(document).ready(function() {
            $('#table-predios').DataTable();
        });

        $(document).ready(function() {
            $('#table-potreros').DataTable();
        });

        $(document).ready(function() {
            $('#table-lotes').DataTable();
        });

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
@endsection

<style>
    .modal-create {
        display: none;
        /* Oculto por defecto */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1026;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content-create {
        background-color: white;
    padding: 40px;
    border-radius: 8px !important;
    width: 500px !important;
    max-width: 100%;
    }

    .close {
        float: right;
        font-size: 1.5em;
        cursor: pointer;
    }
    .margin-5{
        margin: 0px 5px 0px 0px !important;
    }
</style>
