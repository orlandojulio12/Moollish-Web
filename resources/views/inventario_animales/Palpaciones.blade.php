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
    </style>
@endsection
@section('title')
    Moollish Palpaciones
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
    <div class="container">
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

                <h3 class="cumb active-tab"> Palpaciones </h3>
            </div>
            <hr>

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
                            <option value="Vacia">Vacia</option>
                        </select>
                    </div>
                    <!-- Select Diagnóstico (se muestra solo si Resultado es Vacia) -->
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
                            <option value="indantilismo genital">indantilismo genital</option>
                        </select>
                    </div>

                    <!-- Parto proyectado -->
                    <div class="col-md-12">
                        <label for="dias_prenada" class="form-label">Días de Preñada</label>
                        <input type="number" max="285" class="form-control" id="dias_prenada" name="dias_prenada"
                            min="0" disabled>
                    </div>
                </div>
            </div>

            <div class="double-container">
                <!-- Palpador -->
                <div class="main">
                    <label for="id_palpador" class="form-label">Veterinario <span style="color: red;">*</span></label>
                    <div class="input-group">
                        <select class="form-control" id="id_palpador" name="id_palpador" required>
                            <option value="" disabled selected>Seleccione un veterinario</option>
                            @foreach ($veterinarios as $veterinario)
                                <option value="{{ $veterinario->id }}">{{ $veterinario->nombre_completo }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createVeterinarioModal">
                            <span class="material-symbols-outlined">add</span>
                        </button>
                    </div>
                </div>
                <!-- Días de Preñada -->
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
        <h3>Historial de palpaciónes</h3>

        <table id="historial-palpaciones">
            <thead>
                <th>
                    Fecha
                </th>
                <th>
                    Animal
                </th>
                <th>
                    Resultado
                </th>
                <th>
                    Diagnostico
                </th>
                <th>
                    Parto proyectado
                </th>
                <th>
                    Veterinario
                </th>
            </thead>
            @foreach ($palpaciones as $palpacion)
                <tbody>

                    <td>
                        {{ $palpacion->fecha }}
                    </td>
                    <td>
                        {{ $palpacion->animal->codigo }}
                    </td>
                    <td>
                        {{ $palpacion->resultado }}
                    </td>
                    <td>
                        @if ($palpacion->diagnostico !== null)
                            {{ $palpacion->diagnostico }}
                        @else
                            Sin diagnostico
                        @endif
                    </td>

                    <td>
                        @if ($palpacion->parto_proyectado == null)
                            Sin fecha proyectada
                        @else
                            {{ $palpacion->parto_proyectado }}
                        @endif
                    </td>
                    <td>
                        @if ($palpacion->palpador)
                            {{ $palpacion->palpador->numero_documento }}
                        @else
                            Sin palpador
                        @endif

                    </td>
                </tbody>
            @endforeach

        </table>
    </div>



    <!-- Modal para agregar veterinario -->
@endsection
@section('modal')
    <div class="modal fade" id="createVeterinarioModal" tabindex="-1" aria-labelledby="createVeterinarioModalLabel"
        aria-hidden="true">
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
    <script>
        $(document).ready(function() {
            // Mostrar/ocultar el select de diagnóstico cuando se seleccione "Vacia" en resultado
            $('#resultado').change(function() {
                var resultado = $(this).val();
                if (resultado === 'Vacia') {
                    $('#divDiagnostico').slideDown();
                    // Hacemos que diagnostico sea requerido si el resultado es "Vacia"
                    $('#diagnostico').prop('required', true);
                } else {
                    $('#divDiagnostico').slideUp();
                    $('#diagnostico').prop('required', false);
                }
            });
        });
    </script>

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
        $(document).ready(function() {
            // Mostrar modal de creación de veterinarios
            $('#addVeterinarioBtn').on('click', function() {
                $('#createVeterinarioModal').modal('show');
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
                            // Añadir el veterinario recién creado al select
                            $('#id_palpador').append(new Option(response.veterinario
                                .nombre_completo, response.veterinario.id));

                            // Mostrar mensaje de éxito
                            alert('Veterinario agregado con éxito.');

                            // Cerrar el modal y resetear el formulario
                            $('#createVeterinarioModal').modal('hide');
                            $('#createVeterinarioForm')[0].reset();
                        } else {
                            alert('Error al agregar el veterinario.');
                        }
                    },
                    error: function(xhr) {
                        alert('Ocurrió un error. Inténtalo de nuevo.');
                        console.error(xhr.responseText);
                    }
                });
            });

            // Validar que haya predios disponibles al cargar el formulario
            if ($('#predioSelect option').length <= 1) {
                $('#addPalpacionForm button[type="submit"]').prop('disabled', true);
                console.log('No hay predios disponibles para el usuario actual.');
            } else {
                $('#addPalpacionForm button[type="submit"]').prop('disabled', false);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            const $resultado = $('#resultado');
            const $diasPrenada = $('#dias_prenada');
            const $partoProyectado = $('#parto_proyectado');
            const $fecha = $('#fecha');

            // Activar/desactivar el campo de "Días de Preñada" según el resultado
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
                    // Calcular la fecha del parto proyectado: Fecha de palpación + (285 - Días de Preñada)
                    const fecha = new Date(fechaPalpacion);
                    const diasFaltantes = 285 - diasPrenada;
                    fecha.setDate(fecha.getDate() + diasFaltantes);

                    // Formatear la fecha en formato "YYYY-MM-DD"
                    const year = fecha.getFullYear();
                    const month = String(fecha.getMonth() + 1).padStart(2, '0'); // Meses van de 0 a 11
                    const day = String(fecha.getDate()).padStart(2, '0');
                    $partoProyectado.val(`${year}-${month}-${day}`);
                }
            });
        });
    </script>
@endsection
