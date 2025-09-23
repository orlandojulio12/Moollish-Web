@extends('layouts')

@section('title')
    Inventario Fisico
@endsection
@section('styles')
    <style>
        .paso-checkbox {
            height: 40px;
            width: 40px;
            cursor: pointer;

        }

        .paso-checkbox:checked {
            background-color: #0dc453;
            border-color: #0dc453;
        }

        .no-paso-checkbox:checked {
            background-color: #fd0d35;
            border-color: #fd0d35;
        }


        .no-paso-checkbox {
            height: 40px;
            width: 40px;
            cursor: pointer;
        }

        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
        }

        .kardex {
            --bs-modal-width: 90%;

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

    /* Estilos para modales personalizados */
    .custom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
        padding-right: 17px;
    }

    .custom-modal.show {
        display: block !important;
    }

    .custom-modal .modal-dialog {
        margin: 30px auto;
        max-width: 800px;
        transform: translate(0, 0);
        transition: transform 0.3s ease-out;
    }

    /* Animación de aparición */
    .custom-modal.show .modal-dialog {
        animation: modalFadeIn 0.3s;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translate(0, -50px);
        }
        to {
            opacity: 1;
            transform: translate(0, 0);
        }
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
                    <h3 class="cumb active-tab"> Inventarios fisicos </h3>
                </div>
            @elseif ($user->role->name === 'admin')
                <h3>Administrar caracterizaciones</h3>
            @endif
            <hr>
        </div>
        <button type="button" class="btn btn-primary gestionarInventarioBtn" data-bs-toggle="modal"
            data-bs-target="#inventarioModal">
            Gestionar Inventario
        </button>
        <!-- Modal -->
        <hr>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Predio</th>
                    <th>Estado</th>
                    <th>Cantidad de Animales</th>
                    <th>Animales Faltantes</th>
                    <th>Acciones</th>
                    <th>Visualizacion</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($inventarios as $inventario)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($inventario['fecha_inicio'])->format('d/m/Y') }}</td>

                        <td>{{ $inventario['id_predio'] }}</td>
                        @if ($inventario['estado'] == 'abierto')
                            <td><span class="badge bg-success">Abierto</span></td>
                        @else
                            <td><span class="badge bg-danger">Cerrado</span></td>
                        @endif
                        <td>{{ $inventario['cantidad_animales'] }}</td>
                        <td>{{ $inventario['cantidad_faltantes'] }}</td>

                        @if ($inventario['estado'] == 'abierto')
                            <td>
                                <button class="btn btn-warning editarInventarioBtn" data-bs-toggle="modal"
                                    data-bs-target="#inventarioModal" data-id="{{ $inventario['id_inventario'] }}">
                                    Editar
                                </button>
                            </td>
                        @else
                            <td>
                                <button disabled class="btn btn-warning editarInventarioBtn" data-bs-toggle="modal"
                                    data-bs-target="#inventarioModal" data-id="{{ $inventario['id_inventario'] }}">
                                    Editar
                                </button>
                            </td>
                        @endif
                        <td>
                            <button class="btn btn-primary modal-trigger-custom" data-target="#animalesModal_{{ $inventario['id_inventario'] }}">
                                Ver Animales
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @foreach ($inventarios as $inventario)
            <div class="modal custom-modal" id="animalesModal_{{ $inventario['id_inventario'] }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Animales del Inventario</h5>
                            <button type="button" class="btn-close modal-close-custom" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Animales Presentes</h6>
                            @if (count($inventario['animales']) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Sexo</th>
                                            <th>Raza</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inventario['animales'] as $animal)
                                            <tr>
                                                <td>{{ $animal['id_animal'] }}</td>
                                                <td>{{ $animal['codigo'] }}</td>
                                                <td>{{ $animal['nombre'] }}</td>
                                                <td>{{ $animal['sexo'] }}</td>
                                                <td>{{ $animal['raza'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No hay animales presentes registrados en este inventario.</p>
                            @endif

                            <h6>Animales Faltantes</h6>
                            @if (count($inventario['animales_faltantes']) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Sexo</th>
                                            <th>Raza</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inventario['animales_faltantes'] as $animal)
                                            <tr>
                                                <td>{{ $animal['id_animal'] }}</td>
                                                <td>{{ $animal['codigo'] }}</td>
                                                <td>{{ $animal['nombre'] }}</td>
                                                <td>{{ $animal['sexo'] }}</td>
                                                <td>{{ $animal['raza'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No hay animales faltantes en este inventario.</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary modal-close-custom">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
</div>
@endsection

<!-- Modal para inventariar animales -->
<div class="modal fade" id="inventarioModal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg kardex">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="inventarioModalLabel">Inventariar Animales</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- Search Inputs -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="predio" class="form-label">Predio</label>
                    <select name="id_predio" id="predio" class="form-select">
                        @foreach ($predios as $predio)
                            <option value="{{ $predio['id'] }}">{{ $predio['nombre_predio'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="fecha_inicio" class="form-label">Fecha inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control">

                </div>

                <div class="col-md-2">
                    <label for="fecha_fin" class="form-label">Fecha fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control">
                </div>

                <div class="col-md-2">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="abierto">Abierto</option>
                        <option value="cerrado">Cerrado</option>
                    </select>
                </div>


                <div>
                    <label for="observaciones">Observaciónes</label>
                    <textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="3"></textarea>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="searchCodigo" class="form-label">Buscar por Código</label>
                    <input type="text" id="codigoFilter" placeholder="Buscar por Código"
                        class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="searchIdElectronica" class="form-label">Buscar por ID Electrónica</label>
                    <input type="text" id="idElectronicaFilter" placeholder="Buscar por ID Electrónica"
                        class="form-control">
                </div>
            </div>
            <!-- Animales Table -->
            <table id="animalsTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>ID Electrónica</th>
                        <th>Edad</th>
                        <th>Estado Productivo</th>
                        <th>Días de Parida</th>
                        <th>Estado Reproductivo</th>
                        <th>Color</th>
                        <th>Hierro</th>
                        <th>Ubicación</th>
                        <th>Pasó</th>
                        <th>No Pasó</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($animales as $animal)
                        <tr>
                            <td class="codigo">{{ $animal['codigo'] }}</td>
                            <td class="idElectronica">{{ $animal['identificacion_electronica'] }}</td>
                            <td>
                                @php
                                    $edad = \Carbon\Carbon::parse($animal['fecha_nacimiento'])->diff(
                                        \Carbon\Carbon::now(),
                                    );
                                @endphp
                                {{ $edad->y }} años y {{ $edad->m }} meses
                            </td>
                            <td>{{ $animal['estado_productivo'] }}</td>
                            <td>{{ $animal['dias_de_parida'] }}</td>
                            <td>{{ $animal['estado_reproductivo'] }}</td>
                            <td>{{ $animal['color'] }}</td>
                            <td>{{ $animal['hierro'] }}</td>
                            <td>{{ $animal['lote'] }} - {{ $animal['potrero'] }}</td>
                            <td>
                                <input type="checkbox" class="paso-checkbox form-check-input" name="animales[]"
                                    value="{{ $animal['id_animal'] }}">
                            </td>
                            <td>
                                <input type="checkbox" class="no-paso-checkbox form-check-input"
                                    name="animales_faltantes[]" value="{{ $animal['id_animal'] }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" id="guardarInventarioBtn">Guardar Inventario</button>
        </div>
    </div>
</div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        // Filtrar animales por código
        $('#codigoFilter').on('input', function() {
            const filter = $(this).val().toLowerCase();
            $('#animalsTable tbody tr').each(function() {
                const codigo = $(this).find('.codigo').text().toLowerCase();
                $(this).toggle(codigo.includes(filter));
            });
        });

        // Filtrar animales por ID electrónica
        $('#idElectronicaFilter').on('input', function() {
            const filter = $(this).val().toLowerCase();
            $('#animalsTable tbody tr').each(function() {
                const idElectronica = $(this).find('.idElectronica').text().toLowerCase();
                $(this).toggle(idElectronica.includes(filter));
            });
        });

        // Guardar el inventario
        $('#guardarInventarioBtn').on('click', function() {
            const idPredio = $('#predio').val();
            const nombreInventario = $('#nombre').val();
            const fechaFin = $('#fecha_fin').val();
            const fechaInicio = $('#fecha_inicio').val();
            const observaciones = $('#observaciones').val();
            const estado = $('#estado').val();
            const selectedAnimals = [];
            const missingAnimals = [];

            // Obtener animales seleccionados como "pasó"
            $('#animalsTable tbody input.paso-checkbox:checked').each(function() {
                selectedAnimals.push($(this).val());
            });

            // Obtener animales seleccionados como "no pasó"
            $('#animalsTable tbody input.no-paso-checkbox:checked').each(function() {
                missingAnimals.push($(this).val());
            });

            // Validar que al menos un animal esté seleccionado en cualquiera de las categorías
            if (selectedAnimals.length === 0 && missingAnimals.length === 0) {
                alert('Debe seleccionar al menos un animal en "Pasó" o "No Pasó".');
                return;
            }

            // Convertir arrays de IDs en cadenas separadas por comas
            const animalesStr = selectedAnimals.join(',');
            const animalesFaltantesStr = missingAnimals.join(',');

            // Enviar datos al servidor
            $.ajax({
                url: '{{ route('inventarioFisico.store') }}', // Ruta al método storeInventario
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    id_predio: idPredio,
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin,
                    observaciones: observaciones,
                    nombre_inventario: nombreInventario,
                    animales: animalesStr, // Enviar cadena de IDs
                    animales_faltantes: animalesFaltantesStr, // Enviar cadena de IDs
                    estado: estado,
                }),
                success: function(response) {
                    if (response.success) {
                        alert('Inventario guardado correctamente.');
                        $('#inventarioModal').modal('hide'); // Cerrar el modal
                        location.reload(); // Recargar la página
                    } else {
                        alert('Error al guardar el inventario: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Ocurrió un error al guardar el inventario.');
                    console.error(xhr.responseText);
                },
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Abrir modal para gestionar inventario (Crear o Editar)
        $('.gestionarInventarioBtn, .editarInventarioBtn').on('click', function() {
            const isEdit = $(this).hasClass('editarInventarioBtn');
            const inventarioId = isEdit ? $(this).data('id') : null;

            if (isEdit) {
                // Cargar datos del inventario para editar
                console.log(`Fetching data for Inventario ID: ${inventarioId}`);
                $.ajax({
                    url: `/inventarios/${inventarioId}/edit`,
                    method: 'GET',
                    success: function(data) {
                        console.log('Data received from server:', data);
                        llenarModalConDatos(data, true);
                    },
                    error: function(xhr) {
                        alert('Error al cargar el inventario.');
                        console.error(xhr.responseText);
                    }
                });
            } else {
                // Modal vacío para crear nuevo inventario
                limpiarModal();
            }

            // Cambiar acción del botón de guardar
            $('#guardarInventarioBtn').off('click').on('click', function() {
                if (isEdit) {
                    guardarInventarioEdit(inventarioId);
                } else {
                    guardarInventario();
                }
            });

            // Asegurarse de mostrar el modal después de configurar
            $('#inventarioModal').modal('show');

        });

        // Filtrar animales por código
        $('#codigoFilter').on('input', function() {
            const filter = $(this).val().toLowerCase();
            $('#animalsTable tbody tr').each(function() {
                const codigo = $(this).find('.codigo').text().toLowerCase();
                $(this).toggle(codigo.includes(filter));
            });
        });

        // Filtrar animales por ID electrónica
        $('#idElectronicaFilter').on('input', function() {
            const filter = $(this).val().toLowerCase();
            $('#animalsTable tbody tr').each(function() {
                const idElectronica = $(this).find('.idElectronica').text().toLowerCase();
                $(this).toggle(idElectronica.includes(filter));
            });
        });

        // Guardar nuevo inventario
        function guardarInventario() {
            const datos = recolectarDatosModal();
            enviarDatosInventario('{{ route('inventarioFisico.store') }}', 'POST', datos);
        }

        // Actualizar inventario existente
        function guardarInventarioEdit(inventarioId) {
            const datos = recolectarDatosModal();
            enviarDatosInventario(`/inventarios/${inventarioId}/update`, 'POST', datos);
        }

        function recolectarDatosModal() {
            const selectedAnimals = [];
            const missingAnimals = [];

            // Obtener animales seleccionados (casilla "paso")
            $('#animalsTable tbody input.paso-checkbox:checked').each(function() {
                // Convertir a entero (si los IDs son numéricos) o déjalo como cadena según convenga
                selectedAnimals.push(parseInt($(this).val()));
            });

            // Obtener animales faltantes (casilla "no-paso")
            $('#animalsTable tbody input.no-paso-checkbox:checked').each(function() {
                missingAnimals.push(parseInt($(this).val()));
            });

            return {
                id_predio: $('#predio').val(),
                nombre_inventario: $('#nombre').val(),
                fecha_inicio: $('#fecha_inicio').val(),
                fecha_fin: $('#fecha_fin').val(),
                observaciones: $('#observaciones').val(),
                estado: $('#estado').val(),
                animales: selectedAnimals, // Enviamos el arreglo directamente
                animales_faltantes: missingAnimals, // Enviamos el arreglo directamente
            };
        }



        // Enviar datos al servidor
        function enviarDatosInventario(url, method, datos) {
            $.ajax({
                url: url,
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                contentType: 'application/json',
                data: JSON.stringify(datos),
                success: function(response) {
                    if (response.success) {
                        alert('Inventario procesado correctamente.');
                        $('#inventarioModal').modal('hide'); // Cerrar modal
                        location.reload(); // Recargar la página
                    } else {
                        alert('Error al procesar el inventario: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Ocurrió un error al procesar el inventario.');
                    console.error(xhr.responseText);
                }
            });
        }

        function llenarModalConDatos(data, isEdit = false) {
    console.log('Filling modal with data:', data);

    $('#nombre').val(data.nombre_inventario);
    $('#predio').val(data.id_predio);
    $('#fecha_inicio').val(data.fecha_inicio.split(' ')[0]);
    $('#fecha_fin').val(data.fecha_fin ? data.fecha_fin.split(' ')[0] : '');
    $('#observaciones').val(data.observaciones || '');

    // Limpiar todos los checkboxes
    $('#animalsTable input[type="checkbox"]').prop('checked', false);

    // Función auxiliar para obtener un array a partir de data (si es string, se parsea)
    function parseArray(dataField) {
        if (!dataField) {
            return [];
        }
        if (typeof dataField === 'string') {
            try {
                return JSON.parse(dataField);
            } catch (error) {
                console.error("Error al parsear JSON:", error);
                return [];
            }
        }
        return Array.isArray(dataField) ? dataField : [];
    }

    // Parsear animales y animales_faltantes
    let animalesArray = parseArray(data.animales);
    let animalesFaltantesArray = parseArray(data.animales_faltantes);

    // Marcar checkboxes para animales "paso"
    if (animalesArray.length > 0) {
        console.log('Marking "paso" checkboxes for animals:', animalesArray);
        animalesArray.forEach(function(animal) {
            // Si el valor es un objeto con propiedad "id_animal", la usamos; si es número, lo usamos directamente.
            let animalId = (typeof animal === 'object' && animal !== null && animal.id_animal)
                ? animal.id_animal
                : animal;
            const checkbox = $(`#animalsTable input.paso-checkbox[value="${animalId}"]`);
            console.log(`Checking "paso" for Animal ID: ${animalId}`, checkbox.length > 0);
            checkbox.prop('checked', true);
        });
    } else {
        console.log('No "paso" animals to mark.');
    }

    // Marcar checkboxes para animales "no-paso"
    if (animalesFaltantesArray.length > 0) {
        console.log('Marking "no-paso" checkboxes for animals:', animalesFaltantesArray);
        animalesFaltantesArray.forEach(function(animal) {
            let animalId = (typeof animal === 'object' && animal !== null && animal.id_animal)
                ? animal.id_animal
                : animal;
            const checkbox = $(`#animalsTable input.no-paso-checkbox[value="${animalId}"]`);
            console.log(`Checking "no-paso" for Animal ID: ${animalId}`, checkbox.length > 0);
            checkbox.prop('checked', true);
        });
    } else {
        console.log('No "no-paso" animals to mark.');
    }
}


        // Limpiar el modal para una nueva creación
        function limpiarModal() {
            console.log('Clearing modal for new inventory creation.');
            $('#nombre').val('');
            $('#predio').val('');
            $('#fecha_inicio').val('');
            $('#fecha_fin').val('');
            $('#observaciones').val('');
            $('#animalsTable input[type="checkbox"]').prop('checked', false);
        }

        // Script específico para asegurar que los modales del foreach funcionen correctamente
        $('.btn-primary[data-bs-toggle="modal"]').on('click', function(e) {
            const targetId = $(this).data('bs-target');
            // Asegurarse de que el modal exista
            if ($(targetId).length) {
                // Limpiar cualquier backdrop y modales abiertos
                $('.modal-backdrop').remove();
                $('.modal.show').removeClass('show').hide();

                // Abrir el modal manualmente
                setTimeout(function() {
                    $(targetId).addClass('show').css('display', 'block');
                    $('body').addClass('modal-open').append('<div class="modal-backdrop fade show"></div>');
                }, 100);
            }
        });

        // Manejar botones de cierre de modales
        $('.modal .btn-close, .modal .btn-secondary').on('click', function() {
            const modal = $(this).closest('.modal');
            modal.removeClass('show').css('display', 'none');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('overflow', '');
        });
    });
</script>

<!-- Script específico para arreglar los modales generados por el foreach -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Eliminar cualquier modal-backdrop existente y restaurar el estado del body
    function limpiarModalBackdrops() {
        document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
            backdrop.remove();
        });
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
    }

    // Limpiar al inicio
    limpiarModalBackdrops();

    // 2. Capturar todos los botones que abren modales de animales
    document.querySelectorAll('.btn-primary[data-bs-target^="#animalesModal_"]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Obtener el ID del modal
            var targetId = this.getAttribute('data-bs-target');
            var modalElement = document.querySelector(targetId);

            if (!modalElement) return;

            // Limpiar cualquier modal o backdrop existente
            limpiarModalBackdrops();
            document.querySelectorAll('.modal.show').forEach(function(modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
            });

            // Abrir el modal manualmente
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            document.body.classList.add('modal-open');

            // Crear backdrop manualmente
            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        });
    });

    // 3. Manejar cierre de modales
    document.querySelectorAll('.modal .btn-close, .modal .btn-secondary').forEach(function(closeBtn) {
        closeBtn.addEventListener('click', function() {
            var modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
            }
            limpiarModalBackdrops();
        });
    });

    // 4. Cerrar modal al hacer clic en backdrop
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.remove('show');
            e.target.style.display = 'none';
            limpiarModalBackdrops();
        }
    });
});
</script>

<!-- Script para los modales personalizados -->
<script>
    $(document).ready(function() {
        // Función para manejar el scroll al abrir el modal
        function bloquearScroll() {
            $('body').css({
                'overflow': 'hidden',
                'padding-right': '17px'
            });
        }

        // Función para restaurar el scroll al cerrar el modal
        function restaurarScroll() {
            $('body').css({
                'overflow': '',
                'padding-right': ''
            });
        }

        // Función para abrir modal personalizado
        function abrirModal(modalId) {
            // Cerrar cualquier modal abierto
            $('.custom-modal.show').removeClass('show');

            // Abrir el modal solicitado
            $(modalId).addClass('show');
            bloquearScroll();
        }

        // Función para cerrar modal personalizado
        function cerrarModal(modalEl) {
            $(modalEl).removeClass('show');
            restaurarScroll();
        }

        // Event Listener para los botones que abren modales
        $('.modal-trigger-custom').click(function(e) {
            e.preventDefault();
            var targetModal = $(this).data('target');
            abrirModal(targetModal);
        });

        // Event Listener para los botones que cierran modales
        $('.modal-close-custom').click(function() {
            var modal = $(this).closest('.custom-modal');
            cerrarModal(modal);
        });

        // Cerrar modal al hacer clic en su fondo
        $('.custom-modal').click(function(e) {
            if ($(e.target).is('.custom-modal')) {
                cerrarModal(this);
            }
        });

        // Cerrar modal con Escape
        $(document).keydown(function(e) {
            if (e.keyCode === 27) { // ESC
                cerrarModal($('.custom-modal.show'));
            }
        });
    });
</script>
<!-- Modal para inventariar animales -->
@endsection


