@extends('layouts')

@section('title')
    Secado y destete
@endsection

@section('styles')
    <style>
        #content-container:before {
            content: '';
            display: block;
            height: 165px;
            width: 100%;
            position: absolute;
            background-color: #C29F77 !important;
            z-index: 0;
        }

        .card-custom {
            border-radius: 8px;
    background: white;
    padding: 30px;
    border: 1px solid #eaebef;
        }

        .one-column {
            width: 79%;
        }

        .four-column {
            width: 19%;
        }

        .two-column {
            width: 49%;
        }

        .three-column {
            width: 32%;
        }

        .space-b {
            justify-content: space-between;
        }

        .container-table {
            margin: 10px 0px;
            border: 1px solid #d3d3d375;
            padding: 10px;
            border-radius: 3px;
        }

        .two-column {
            width: 49%;
        }

        .three-column {
            width: 32%;
        }

        .space-b {
            justify-content: space-between;
        }

        .column-column {
            width: 49%
        }

        .obligatorio {
            color: red;
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

@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-custom">
        <div class="">
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

                        <h3 class="cumb active-tab"> Secado y destetes </h3>
                </div>
                <hr>

            </div>
                <form action="{{ route('secado_destetes.store') }}" method="POST" id="mainForm">
                @csrf
                <div class="d-flex space-b">
                    <div class="column-column">
                        <div class="form-group">
                            <label for="fecha_secado_destete">Fecha de Secado/Destete <span
                                    class="obligatorio">*</span></label>
                            <input type="date" class="form-control" id="fecha_secado_destete" name="fecha_destete"
                                required>
                        </div>
                        <div class="">
                            <div class="form-group four-column" style="display: none">
                                <label for="vaca_codigo">Código</label>
                                <input type="text" class="form-control" id="vaca_codigo" readonly>
                            </div>
                            <div class="form-group ">
                                <div class="form-group two-column-custom">
                                    <label for="id_animal">Animal <span style="color: red;">*</span></label>
                                    <input type="hidden" id="id_animal" name="id_animal" required>
                                    <div class="input-dinamico-animal">
                                        <div id="animalSeleccionado"></div>
                                        <button type="button" class="buton-dinamico-animal" data-popup="{{ $nombre ?? 'id_animal' }}">                                            <span class="material-symbols-outlined">search</span>
                                          </button>
                                    </div>
                                </div>
                                @include('components.selector-animales', ['predios' => $predios, 'animales' => $vacas])
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="motivo">Motivo</label>
                            <select class="form-control" id="motivo" name="motivo" required>
                                <option value=""> -- Seleccione un Motivo --</option>
                                <option value="normal">Normal</option>
                                <option value="preñez">Preñez</option>
                                <option value="enfermedad">Enfermedad</option>
                                <option value="muerte">Muerte</option>
                                <option value="mala_madre">Mala Madre</option>
                                <option value="aborto">Aborto</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_cria_animal">Seleccionar Cría</label>
                            <select class="form-control" id="id_cria_animal" name="id_cria_animal" required>
                                <option value="default" selected>-- Seleccione una Cría --</option>
                                <option value="allCrias">Todas las crias</option>
                                <!-- Las opciones serán pobladas dinámicamente -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="peso_cria">Peso de la Cría al Destete (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="peso_cria" name="peso_cria"
                                required>
                        </div>
                        <!-- Información de la Cría -->
                        <div class="cria_info" style=" margin-top: 20px;">
                            <h4>Información de la Cría</h4>
                            <div class="form-row d-flex space-b">
                                <div class="form-group two-column">
                                    <label for="cria_codigo">Código</label>
                                    <input type="text" class="form-control" id="cria_codigo" readonly>
                                </div>
                                <div class="form-group two-column">
                                    <label for="cria_nombre">Nombre</label>
                                    <input type="text" class="form-control" id="cria_nombre" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-column">
                        {{--  --}}
                        <div class="form-group ">
                            <label for="vaca_total_leche">Total Leche (litros)</label>
                            <input type="number" class="form-control" id="vaca_total_leche" readonly>
                        </div>

                        <div class="form-group ">
                            <label for="vaca_fecha_ultimo_parto">Fecha Último Parto</label>
                            <input type="text" class="form-control" id="vaca_fecha_ultimo_parto" readonly>
                        </div>
                        <div class="form-group ">
                            <label for="vaca_dias_prenez">Días de Preñez</label>
                            <input type="text" class="form-control" id="vaca_dias_prenez" readonly>
                        </div>

                        <div class="form-group">
                            <label for="observacion">Observaciones</label>
                            <textarea class="form-control" id="observacion" name="observacion" rows="1"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="peso_vaca">Peso de la Madre al Secado (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="peso_vaca" name="peso_vaca"
                                required>
                        </div>
                        <hr>
                        <div class="cria_info">
                            <div class="d-flex space-b " >
                                <div class="form-group two-column">
                                    <label for="cria_sexo">Sexo</label>
                                    <input type="text" class="form-control" id="cria_sexo" readonly>
                                </div>
                                <div class="form-group two-column">
                                    <label for="cria_raza">Raza</label>
                                    <input type="text" class="form-control" id="cria_raza" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div>
                    <div id="" style=" margin-top: 20px;">

                        <h4> Ubicacion de la madre</h4>
                        <div class="d-flex space-b">
                            <div class="form-group two-column">
                                <label for="madre_potrero">Potrero de la Madre</label>
                                <input type="text" class="form-control" id="madre_potrero" readonly>
                            </div>
                            <div class="form-group two-column ">
                                <label for="madre_lote">Lote de la Madre</label>
                                <input type="text" class="form-control" id="madre_lote" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="vaca_info" style=" margin-top: 20px;">
                        <h4> Ubicacion de la cria</h4>
                        <div class="d-flex space-b">

                            <div class="form-group two-column">
                                <label for="cria_potrero">Potrero de la Cría</label>
                                <input type="text" class="form-control" id="cria_potrero" readonly>
                            </div>
                            <div class="form-group two-column">
                                <label for="cria_lote">Lote de la Cría</label>
                                <input type="text" class="form-control" id="cria_lote" readonly>
                            </div>
                        </div>
                        <div>

                        </div>
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="marcarvacaseca" name="vaca_secado" value="1">
                            <label class="form-check-label" for="marcarvacaseca">
                                Marcar vaca como seca
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pasarcrialevante" name="is_cria_levante" value="1">
                            <label class="form-check-label" for="pasarcrialevante">
                                Pasar la cría a levante
                            </label>
                        </div>


                    </div>
                    <hr>
                    <button type="button" id="formSubmitButton" class="btn btn-primary">Registrar Secado y Destete</button>
                </div>
            </form>
        </div>

    </div>

    <div class="container mt-4">
     <!-- Modal de confirmación -->
    </div>
@endsection


@section('modal')
<!-- Modal de Confirmación -->
<div id="confirmModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1050;">
    <div class="modal-dialog" style="position: relative; margin: 10% auto; width: 400px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmación</h5>
                <button type="button" id="cancelAction" style="background: none; border: none; font-size: 1.5em;">&times;</button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer" style="text-align: right;">
                <button id="cancelAction" type="button" style="margin-right: 10px; padding: 5px 15px; border: 1px solid #ccc; background: #f0f0f0; border-radius: 4px; cursor: pointer;">Cancelar</button>
                <button id="confirmAction" type="button" style="padding: 5px 15px; border: 1px solid #007bff; background: #007bff; color: white; border-radius: 4px; cursor: pointer;">Aceptar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/js/inventario/modalConfirm.js"></script>
    <script>
        var criasData = [];
        $(document).ready(function() {

            function limpiarVacaCampos() {
                $('#vaca_codigo').val('');
                $('#vaca_nombre').val('');
                $('#vaca_total_leche').val('');
                $('#vaca_fecha_ultimo_estado_reproductivo').val('');
                $('#vaca_dias_prenez').val('');
                $('#madre_potrero').val('');
                $('#madre_lote').val('');
                $('#vaca_fecha_ultimo_parto').val('');
            }

            function limpiarCriaCampos() {
                $('#cria_codigo').val('');
                $('#cria_nombre').val('');
                $('#cria_sexo').val('');
                $('#cria_raza').val('');
                $('#cria_total_leche_cria').val('');
                $('#cria_fecha_ultimo_estado_reproductivo').val('');
                $('#cria_potrero').val('');
                $('#cria_lote').val('');
                $('#madre_codigo').val('');
                $('#madre_nombre').val('');
            }

            function mostrarError(mensaje) {
                $('#error_message').text(mensaje).show();
                setTimeout(function() {
                    $('#error_message').fadeOut();
                }, 5000);
            }

            // Al cambiar la vaca seleccionada
            $('#id_animal').one('change', function() {
                var idVaca = $(this).val();
                if (idVaca) {
                    // Vaciar el select de crias e insertar las opciones fijas
                    $('#id_cria_animal').empty()
                        .append('<option value="default" selected>-- Seleccione una Cría --</option>')
                        .append('<option value="allCrias">Todas las crias</option>')
                        .prop('disabled', false);
                    $('#cria_info').hide();
                    limpiarCriaCampos();

                    $.ajax({
                        url: "{{ route('secado_destetes.getVacaDetails') }}",
                        type: "GET",
                        data: { 'id_animal': idVaca },
                        dataType: "json",
                        success: function(response) {
                            console.log('Datos de crias:', response.data.crias);

                            if (response.success && response.type === 'vaca') {
                                $('#vaca_info').show();

                                $('#vaca_codigo').val(response.data.vaca_codigo);
                                $('#vaca_fecha_ultimo_parto').val(response.data.fecha_ultimo_parto);
                                $('#vaca_nombre').val(response.data.vaca_nombre);
                                $('#vaca_total_leche').val(response.data.total_leche);
                                $('#vaca_fecha_ultimo_estado_reproductivo').val(response.data.fecha_ultimo_estado_reproductivo);
                                $('#vaca_dias_prenez').val(response.data.dias_prenez);

                                $('#madre_potrero').val(response.data.ubicacion_madre.potrero);
                                $('#madre_lote').val(response.data.ubicacion_madre.lote);
                                // Almacenar las crias globalmente
                                criasData = response.data.crias || [];
                                // Agregar las opciones dinámicas para las crias sin eliminar las opciones fijas
                                if (criasData.length > 0) {
                                    $.each(criasData, function(index, cria) {
                                        $('#id_cria_animal').append(
                                            '<option value="' + cria.id_animal + '">' +
                                            (cria.codigo || 'Sin código') + ' - ' +
                                            (cria.nombre || 'Sin nombre') +
                                            '</option>'
                                        );
                                    });
                                } else {
                                    mostrarError('No hay crías asociadas a esta vaca.');
                                }
                            } else {
                                mostrarError(response.message || 'No se pudieron obtener los detalles de la vaca.');
                                $('#vaca_info').hide();
                                limpiarVacaCampos();
                                $('#id_cria_animal').empty()
                                    .append('<option value="default">-- Seleccione una Cría --</option>')
                                    .prop('disabled', true);
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            mostrarError('Ocurrió un error al obtener los detalles de la vaca.');
                            $('#vaca_info').hide();
                            limpiarVacaCampos();
                            $('#id_cria_animal').empty()
                                .append('<option value="default">-- Seleccione una Cría --</option>')
                                .prop('disabled', true);
                        }
                    });
                } else {
                    $('#vaca_info').hide();
                    limpiarVacaCampos();
                    $('#id_cria_animal').empty()
                        .append('<option value="default">-- Seleccione una Cría --</option>')
                        .prop('disabled', true);
                    $('#cria_info').hide();
                    limpiarCriaCampos();
                }
            });

            // Manejar la selección en el select de crias
            $('#id_cria_animal').change(function() {
                var idCria = $(this).val();
                if (idCria === 'allCrias') {
                    if (criasData.length > 0) {
                        // Recopilar datos de todas las crias, usando valores por defecto si faltan datos
                        var codigos = [],
                            nombres = [],
                            sexos = [],
                            razas = [],
                            totalLeches = [],
                            fechasEstado = [],
                            potreros = [],
                            lotes = [],
                            madreCodigos = [],
                            madreNombres = [];

                        $.each(criasData, function(index, cria) {
                            codigos.push(cria.codigo || 'Sin código');
                            nombres.push(cria.nombre || 'Sin nombre');
                            sexos.push(cria.sexo || 'No definido');
                            razas.push(cria.raza || 'N/A');
                            totalLeches.push(cria.total_leche_cria || '0');
                            fechasEstado.push(cria.fecha_ultimo_estado_reproductivo || 'N/A');
                            potreros.push(cria.ubicacion_cria && cria.ubicacion_cria.potrero ? cria.ubicacion_cria.potrero : 'No definida');
                            lotes.push(cria.ubicacion_cria && cria.ubicacion_cria.lote ? cria.ubicacion_cria.lote : 'No definida');
                            madreCodigos.push(cria.madre_codigo || 'No definida');
                            madreNombres.push(cria.madre_nombre || 'No definida');
                        });

                        $('#cria_codigo').val(codigos.join(' / '));
                        $('#cria_nombre').val(nombres.join(' / '));
                        $('#cria_sexo').val(sexos.join(' / '));
                        $('#cria_raza').val(razas.join(' / '));
                        $('#cria_total_leche_cria').val(totalLeches.join(' / '));
                        $('#cria_fecha_ultimo_estado_reproductivo').val(fechasEstado.join(' / '));
                        $('#cria_potrero').val(potreros.join(' / '));
                        $('#cria_lote').val(lotes.join(' / '));
                        $('#madre_codigo').val(madreCodigos.join(' / '));
                        $('#madre_nombre').val(madreNombres.join(' / '));
                        $('#cria_info').show();
                    } else {
                        mostrarError('No hay crias para mostrar.');
                        $('#cria_info').hide();
                        limpiarCriaCampos();
                    }
                } else if (idCria && idCria !== 'default') {
                    $.ajax({
                        url: "{{ route('secado_destetes.getCriaDetails') }}",
                        type: "GET",
                        data: { 'id_cria_animal': idCria },
                        dataType: "json",
                        beforeSend: function() {
                            // Opcional: mostrar un loader
                        },
                        complete: function() {
                            // Opcional: ocultar el loader
                        },
                        success: function(response) {
                            if (response.success && response.type === 'cria') {
                                $('#cria_info').show();

                                $('#cria_codigo').val(response.data.cria_codigo);
                                $('#cria_nombre').val(response.data.cria_nombre);
                                $('#cria_sexo').val(response.data.cria_sexo);
                                $('#cria_raza').val(response.data.cria_raza || 'N/A');
                                $('#cria_total_leche_cria').val(response.data.total_leche_cria);
                                $('#cria_fecha_ultimo_estado_reproductivo').val(response.data.fecha_ultimo_estado_reproductivo);
                                $('#cria_potrero').val(response.data.ubicacion_cria && response.data.ubicacion_cria.potrero ? response.data.ubicacion_cria.potrero : 'No definida');
                                $('#cria_lote').val(response.data.ubicacion_cria && response.data.ubicacion_cria.lote ? response.data.ubicacion_cria.lote : 'No definida');
                                $('#madre_codigo').val(response.data.madre_codigo);
                                $('#madre_nombre').val(response.data.madre_nombre);
                            } else {
                                mostrarError(response.message || 'No se pudieron obtener los detalles de la cría.');
                                $('#cria_info').hide();
                                limpiarCriaCampos();
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            mostrarError('Ocurrió un error al obtener los detalles de la cría.');
                            $('#cria_info').hide();
                            limpiarCriaCampos();
                        }
                    });
                } else {
                    $('#cria_info').hide();
                    limpiarCriaCampos();
                }
            });
        });
    </script>


@endsection


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
