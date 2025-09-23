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

    .cria {
        padding: 25px 10px;
        border: 1px solid #eeeeee;
        border-radius: 6px;
        margin: 0px 0px 20px 0px;
    }

    .madre {
        margin: 0px 25px 0px 0px;
        /* width: 49%; */
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
    ficha animales
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
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4 border-bot">
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
                                    <a href="{{ route('registros') }}">
                                        <h3 class="cumb no-active-tab">
                                            Registros
                                        </h3>
                                    </a>
                                    <span class="material-symbols-outlined bread">
                                        chevron_forward
                                    </span>
                                    <a href="{{ route('reproduccionAnimal') }}">
                                        <h3 class="cumb no-active-tab">
                                            Reproduccion animal
                                        </h3>
                                    </a>


                                    <span class="material-symbols-outlined bread">
                                        chevron_forward
                                    </span>

                                    <h3 class="cumb active-tab"> Partos </h3>
                                </div>
                            </div>
                            <!-- Botón que activa el modal -->
                            <div class="container-buttons">
                                <a href="{{ route('partos.historial') }}" style="color: rgb(255, 255, 255)">
                                    <button id="openInfoModal" class="btn btn-primary" style="padding: 10px !important;">
                                        Historial de partos
                                    </button>

                                </a>
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
                                            <div class="form-group ">
                                                <label for="id_animal">Animal <span style="color: red;">*</span></label>
                                                <input type="hidden" id="id_animal" name="id_animal" required>
                                                <div class="input-dinamico-animal">
                                                    <div id="animalSeleccionado"></div>
                                                    <button type="button" class="buton-dinamico-animal" data-popup="{{ $nombre ?? 'id_animal' }}">                                                        <span class="material-symbols-outlined">search</span>
                                                      </button>
                                                </div>
                                            </div>
                                            @include('components.selector-animales', ['predios' => $predios, 'animales' => $animales])
                                        </div>
                                        <div class="d-flex space-b">
                                            <div class="form-group two-column-custom">
                                                <label for="fecha_parto">Fecha de Parto<span style="color: red;">*</span></label>
                                                <input class="form-control" type="date" name="fecha_parto"
                                                    id="fecha_parto" required>
                                            </div>
                                            <div class="form-group two-column-custom">
                                                <label for="tipo_parto">Tipo de Parto<span style="color: red;">*</span></label>
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
                                                    <option value="{{ $toro->id_animal }}">{{ $toro->nombre }}
                                                        ({{ $toro->codigo }})
                                                    </option>
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
                                                    <span id="ultimoPartoDias">Parió hace  días</span>
                                            </div>
                                            <div class="form-group two-column-custom">
                                                <label for="fecha_ultimo_tacto">Último Tacto:</label>
                                                <input class="form-control" type="date" name="fecha_ultimo_tacto"
                                                    id="fecha_ultimo_tacto" readonly>
                                            <span id="ultimaPrenezDias">Preñada hace  días</span>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="observaciones">Observaciones:</label>
                                            <textarea class="form-control" name="observaciones" id="observaciones" rows="1"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- Contenedor de Crías -->
                                <div id="container-crias">
                                    <!-- Cría 1 -->
                                    <div class="container-cria" id="cria-1" style="display: none;">
                                        <div class="title-modal-custom">
                                            <h3>Cría #1</h3>
                                        </div>
                                        @include('partials.campos_cria', ['numero' => 1])
                                    </div>

                                    <!-- Cría 2 -->
                                    <div class="container-cria" id="cria-2" style="display: none;">
                                        <div class="title-modal-custom">
                                            <h3>Cría #2</h3>
                                        </div>
                                        @include('partials.campos_cria', ['numero' => 2])
                                    </div>

                                    <!-- Cría 3 -->
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

@section('modal')
  <!-- Modal de alerta -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="alertModalLabel">Mensaje del sistema</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../assets/js/inventario/fichaDinamicaScriptParto.js"></script>
    <script src="../assets/js/inventario/CriaTipoParto.js"></script>
    <script src="../assets/js/inventario/otropadre.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

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

        <script></script>
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
        document.addEventListener('DOMContentLoaded', () => {
          const form = document.querySelector('form[action="{{ route('partos.store') }}"]');
          const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
          const modalMessage = document.getElementById('modalMessage');

          form.addEventListener('submit', async (event) => {
            event.preventDefault(); // Evitar el envío tradicional del formulario

            const formData = new FormData(form);
            const url = form.action;

            try {
              const response = await fetch(url, {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
              });

              const result = await response.json();

              // Mostrar el mensaje en el modal
              modalMessage.textContent = result.message;
              alertModal.show();

              if (result.status === 'success') {
                form.reset(); // Opcional: limpiar el formulario si el registro fue exitoso
              }
            } catch (error) {
              // Mostrar error genérico si falla la solicitud
              modalMessage.textContent = 'Ocurrió un error al procesar la solicitud.';
              alertModal.show();
            }
          });
        });
      </script>

<script>
    $(document).ready(function(){
        // Suponiendo que el campo hidden con id "id_animal" se llena al seleccionar un animal.
        $('#id_animal').on('change', function(){
            var animalId = $(this).val();
            if(animalId) {
                $.ajax({
                    url: '{{ route("animales.calcularDias") }}',
                    method: 'POST',
                    data: {
                        id_animal: animalId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#ultimoPartoDias').text("Parió hace " + data.diasUltimoParto + " días");
                        $('#ultimaPrenezDias').text("Preñada hace " + data.diasPrenez + " días");
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al calcular días:", error);
                    }
                });
            }
        });
    });
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

    .margin-5 {
        margin: 0px 5px 0px 0px !important;
    }
</style>
