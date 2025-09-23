@extends('layouts')

@section('title')
    Proyeccion destete
@endsection
@section('styles')
    <style>
        .card-custom {

            width: 100%;
            display: flex;
            height: 100%;

            flex-direction: column;

            border-radius: 8px;
    background: white;
    padding: 30px;
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

    .col-sm-12 {
        overflow: auto;
    }

    </style>
@endsection
@section('content')
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
    <div class="card-custom">
        <div class="">
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
                        <a href="{{ route('listados') }}">
                            <h3 class="cumb no-active-tab">
                                Listados
                            </h3>
                        </a>

                        <span class="material-symbols-outlined bread">
                            chevron_forward
                        </span>
                        <h3 class="cumb active-tab"> Proyeccion de destetes</h3>
                    </div>
                @elseif ($user->role->name === 'admin')
                <div class="breadcrumb">
                    <a href="{{ route('inicio') }}">
                        <h3 class="cumb no-active-tab">
                            Inicio
                        </h3>
                    </a>

                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <a href="{{ route('listados') }}">
                        <h3 class="cumb no-active-tab">
                            Listados
                        </h3>
                    </a>

                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <h3 class="cumb active-tab">Proyeccion de destetes</h3>
                </div>
                @endif
            </div>
            <hr>
            <table class="table table-bordered table-striped" id="proposalList">
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Id Electronica</th>
                        <th>Edad</th>
                        <th>Raza</th>
                        <th>Hierro</th>
                        <th>Fecha ultimo parto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($animales as $animal)
                        <tr>
                            <td>{{ $animal->codigo ?? 'N/A' }}</td>
                            <td>{{ $animal->identificacion_electronica ?? 'N/A' }}</td>
                            <td>{{ $animal->fecha_nacimiento ?? 'N/A' }}</td>
                            <td>{{ $animal->raza ?? 'N/A' }}</td>
                            <td>{{ $animal->hierro ?? 'N/A' }}</td>
                            <td>{{ $animal->ultimoParto?->fecha_parto ?? 'N/A' }}</td>
                            <td>{{ $animal->estadoProductivo?->nombre ?? 'N/A' }}</td>
                            <td>
                                <!-- Botón que abre el modal para secar/destetar -->
                                <button class="btn btn-primary btn-secar"
                                        data-animal-id="{{ $animal->id_animal }}"
                                        data-animal-codigo="{{ $animal->codigo }}"
                                        data-ultimo-parto="{{ $animal->ultimoParto?->fecha_parto ?? 'N/A' }}"
                                        data-animal-nombre="{{ $animal->nombre ?? 'N/A' }}"
                                        data-estado="{{ $animal->estadoProductivo?->nombre ?? 'N/A' }}">
                                    Secar
                                </button>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    @section('modal')
<!-- Modal Secado/Destete -->
<div class="modal fade" id="modalSecadoDestete" tabindex="-1" role="dialog" aria-labelledby="secadoDesteteLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <form action="{{ route('secado_destetes.store') }}" method="POST" id="formSecadoDestete">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="secadoDesteteLabel">Registrar Secado / Destete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Información Oculta de la Vaca -->
            <input type="hidden" id="id_animal" name="id_animal">
            <!-- Campos de la vaca (por ejemplo, código, nombre, etc. - de solo lectura) -->
            <div class="form-row">
                <div class="form-group ">
                    <label for="vaca_codigo">Código Vaca</label>
                    <input type="text" class="form-control" id="vaca_codigo" readonly>
                </div>
                <div class="form-group ">
                    <label for="vaca_nombre">Nombre Vaca</label>
                    <input type="text" class="form-control" id="vaca_nombre" readonly>
                </div>
                <div class="form-group ">
                    <label for="vaca_fecha_ultimo_parto">Último Parto</label>
                    <input type="text" class="form-control" id="vaca_fecha_ultimo_parto" readonly>
                </div>
                <div class="form-group ">
                    <label for="vaca_total_leche">Total Leche (litros)</label>
                    <input type="number" class="form-control" id="vaca_total_leche" readonly>
                </div>
            </div>

            <!-- Campos para Secado / Destete -->
            <div class="form-row">
                <div class="form-group ">
                    <label for="fecha_secado_destete">Fecha de Secado / Destete <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="fecha_secado_destete" name="fecha_destete" required>
                </div>
                <div class="form-group ">
                    <label for="motivo">Motivo <span class="text-danger">*</span></label>
                    <select class="form-control" id="motivo" name="motivo" required>
                        <option value="">-- Seleccione un Motivo --</option>
                        <option value="normal">Normal</option>
                        <option value="preñez">Preñez</option>
                        <option value="enfermedad">Enfermedad</option>
                        <option value="muerte">Muerte</option>
                        <option value="mala_madre">Mala Madre</option>
                        <option value="aborto">Aborto</option>
                    </select>
                </div>
                <div class="form-group ">
                    <label for="observacion">Observaciones</label>
                    <textarea class="form-control" id="observacion" name="observacion" rows="1"></textarea>
                </div>
            </div>

            <!-- Campos para Peso -->
            <div class="form-row">
                <div class="form-group ">
                    <label for="peso_vaca">Peso de la Madre (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" id="peso_vaca" name="peso_vaca" required>
                </div>
                <div class="form-group ">
                    <label for="peso_cria">Peso de la Cría al Destete (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" id="peso_cria" name="peso_cria" required>
                </div>
            </div>

            <!-- Seleccionar Cría: Solo se permite "destetar a todas las crias" -->
            <div class="form-group">
                <label for="id_cria_animal">Seleccionar Cría</label>
                <select class="form-control" id="id_cria_animal" name="id_cria_animal" required>
                    <option value="default" selected>-- Seleccione una Cría --</option>
                    <option value="allCrias">Todas las crias</option>
                    <!-- Opciones adicionales se poblarán dinámicamente (si se requiere) -->
                </select>
            </div>

            <!-- Checkboxes para acciones: Secar la vaca o destetar las crias -->
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="marcarvacaseca" name="vaca_secado" value="1">
                <label class="form-check-label" for="marcarvacaseca">Marcar vaca como seca</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pasarcrialevante" name="is_cria_levante" value="1">
                <label class="form-check-label" for="pasarcrialevante">Destetar a todas las crias</label>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Registrar Secado/Destete</button>
          </div>
        </form>
      </div>
    </div>
  </div>

    @endsection
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Destruir la instancia existente si ya está inicializada
            if ($.fn.DataTable.isDataTable('#proposalList')) {
                $('#proposalList').DataTable().clear().destroy();
            }
            // Inicializar DataTable
            $('#proposalList').DataTable({
                language: {
                    "decimal": "",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "emptyTable": "No tienes predios asignados",
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
                },
            });
        });
    </script>
<script>
    $(document).ready(function(){
        // Al hacer clic en el botón "Secar"
        $('.btn-secar').click(function(){
            // Recoger los datos de la vaca desde los atributos data del botón
            var animalId = $(this).data('animal-id');
            var codigo = $(this).data('animal-codigo');
            var nombre = $(this).data('animal-nombre');
            var fechaParto = $(this).data('ultimo-parto');
            var estado = $(this).data('estado');

            // Llenar campos del modal
            $('#id_animal').val(animalId);
            $('#vaca_codigo').val(codigo);
            $('#vaca_nombre').val(nombre);
            $('#vaca_fecha_ultimo_parto').val(fechaParto);
            // Puedes agregar otros campos si están disponibles (como total de leche, etc.)

            // Si fuera necesario, cargar dinámicamente el select de crias de la vaca (similar a tu script anterior)
            // Aquí se podría llamar a un AJAX que reciba el id de la vaca y devuelva la lista de crias
            // Para simplificar, se asume que el select se llenó previamente cuando se seleccionó la vaca

            // Mostrar el modal
            $('#modalSecadoDestete').modal('show');
        });
    });
</script>

<script>
    // Variable global para almacenar las crias de la vaca seleccionada
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
        $('#id_animal').change(function() {
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
                        if (response.success && response.type === 'vaca') {
                            console.log(response.data);
                            $('#vaca_info').show();

                            $('#vaca_codigo').val(response.data.vaca_codigo);
                            $('#vaca_fecha_ultimo_parto').val(response.data.fecha_ultimo_parto);
                            $('#vaca_nombre').val(response.data.vaca_nombre);
                            $('#vaca_total_leche').val(response.data.total_leche);
                            $('#vaca_fecha_ultimo_estado_reproductivo').val(response.data.fecha_ultimo_estado_reproductivo);
                            $('#vaca_dias_prenez').val(response.data.dias_prenez);

                            $('#madre_potrero').val(response.data.ubicacion_madre.potrero);
                            $('#madre_lote').val(response.data.ubicacion_madre.lote);

                            // Almacenar la lista de crias globalmente
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

            // Si se selecciona "allCrias", mostramos la info de todas las crias almacenadas
            if (idCria === 'allCrias') {
                if (criasData.length > 0) {
                    // Recopilar datos de cada campo y usar un valor por defecto si no existe
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
            }
            // Si se selecciona una cría individual (valor distinto de "default" y "allCrias")
            else if (idCria && idCria !== 'default') {
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
