@extends('layouts')
@section('title')
    Historial de partos
@endsection
@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>Consultar Historial de partos</h5>
            </div>
        </div>
    </div>
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
</style>
<style>
#vacaSearch {
border: none;
}

#selectvacadiv2 {
border: 1px solid #ececec;
border-radius: 5px;
/* margin: 10px 0px; */
padding: 9px;

}
    .dropdown-item {
        cursor: pointer;
    }
    .multi-select-container {
        min-height: 100px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        align-items: center;
        cursor: text;
        position: relative;
    }

    .multi-select-input {
        border: none;
        outline: none;
        flex-grow: 1;
        min-width: 150px;
    }

    .selected-items {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .selected-item {
        background-color: #c97917;
        color: #fff;
        padding: 5px 10px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        font-size: 14px;
    }

    .selected-item span {
        margin-left: 10px;
        cursor: pointer;
    }

    .selected-item span:hover {
        color: #663100;
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
                <a href="{{ route('partos.index') }}">
                    <h3 class="cumb no-active-tab">
                        Partos
                    </h3>
                </a>

                <span class="material-symbols-outlined bread">
                    chevron_forward
                </span>

                <h3 class="cumb active-tab"> Historal de partos </h3>
            </div>
        <hr>
        </div>
        <div class="">
            <!-- Filtros -->
            <form id="searchForm">
                <div class="row">
                    <!-- Selector de Tipo de Búsqueda -->
                    <div class="col-md-4">
                        <label for="tipo_busqueda">Tipo de Búsqueda</label>
                        <select name="tipo_busqueda" id="tipo_busqueda" class="form-control" required>
                            <option value="">-- Seleccione Tipo de Búsqueda --</option>
                            <option value="fecha">Por Fecha</option>
                            <option value="vaca">Por Vaca</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <!-- Selector de Predios -->
                    <div class="col-md-4">
                        <label for="predioSearch">Seleccionar Predios</label>
                        <div id="multiSelectContainer" class="multi-select-container form-control">
                            <input type="text" id="predioSearch" class="multi-select-input"
                                placeholder="Escriba para buscar predios..." autocomplete="off">
                            <div id="selectedPredios" class="selected-items"></div>
                        </div>
                        <select id="predioOptions" style="display:none;">
                            @foreach ($prediosDisponibles as $predio)
                                <option value="{{ $predio->id }}" data-name="{{ $predio->nombre_predio }}">
                                    {{ $predio->nombre_predio }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" id="prediosSelected" name="predios" value="">
                    </div>
                    <!-- Campos Fecha Inicio y Fin -->
                    <div class="col-md-3 d-none" id="fecha_inicio_field">
                        <label for="fecha_inicio">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                    </div>
                    <div class="col-md-3 d-none" id="fecha_fin_field">
                        <label for="fecha_fin">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                    </div>
                    <!-- Campo para Buscar Vaca -->
                    <div id="selectvacadiv" class="col-md-3 d-none">
                    <label for="vacaSearch">Selecciona animal</label>
                        <div id="selectvacadiv2" >
                            <input type="text" id="vacaSearch" class="form-control"
                                placeholder="Escriba para buscar una vaca...">
                            <div id="selectedVaca" class="selected-items mt-2"></div>
                            <select id="vacaOptions" style="display:none;"></select>
                            <input type="hidden" id="vacaSelected" name="id_vaca" value="">
                        </div>
                    </div>

                    <!-- Botón de Buscar -->
                    <div class="col-md-2">
                        <button type="button" id="buscarBtn" class="btn btn-primary mt-4">Buscar</button>
                    </div>
                </div>
            </form>
            <hr>
            <!-- Resultados -->
            <div id="resultadoPartos" class="mt-4"></div>

            <!-- Paginación -->
        </div>
    </div>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e49b39',
                cancelButtonColor: '#0000005e',
                confirmButtonText: 'Sí, eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formDelete' + id).submit();
                }
            })
        }
    </script>
@endsection

@section('scripts')
    <!-- Tu script de inicialización -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    const $buscarBtn = $('#buscarBtn');
    const $resultadoPartos = $('#resultadoPartos');
    const $tipoBusqueda = $('#tipo_busqueda');
    const $fechaInicioField = $('#fecha_inicio_field');
    const $fechaFinField = $('#fecha_fin_field');
    const $selectVacaDiv = $('#selectvacadiv');
    const $hiddenInput = $('#prediosSelected');
    const $vacaSelected = $('#vacaSelected');

    // Mostrar/Ocultar campos según tipo de búsqueda
    $tipoBusqueda.change(function () {
        const tipo = $(this).val();
        $fechaInicioField.addClass('d-none');
        $fechaFinField.addClass('d-none');
        $selectVacaDiv.addClass('d-none');

        if (tipo === 'fecha') {
            $fechaInicioField.removeClass('d-none');
            $fechaFinField.removeClass('d-none');
        } else if (tipo === 'vaca') {
            $selectVacaDiv.removeClass('d-none');
        }
    });

    // Evento de buscar
    $buscarBtn.click(function () {
        const tipoBusqueda = $tipoBusqueda.val();
        const predios = JSON.parse($hiddenInput.val() || '[]');
        const fechaInicio = $('#fecha_inicio').val();
        const fechaFin = $('#fecha_fin').val();
        const idVaca = $vacaSelected.val();

        if (!tipoBusqueda || predios.length === 0) {
            alert('Por favor selecciona un tipo de búsqueda y al menos un predio.');
            return;
        }

        const data = {
            tipo_busqueda: tipoBusqueda,
            predios: predios,
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin,
            id_vaca: idVaca,
        };

        console.log('Enviando datos:', data);

        $.ajax({
            url: "{{ route('partos.buscar') }}", // Ruta al controlador
            method: 'GET',
            data: data,
            success: function (response) {
                console.log('Respuesta recibida:', response);
                if (response.success) {
                    mostrarResultados(response.partos);
                } else {
                    $resultadoPartos.html('<p>No se encontraron resultados.</p>');
                }
            },
            error: function (error) {
                console.error('Error en la búsqueda:', error);
                alert('Ocurrió un error al realizar la búsqueda.');
            }
        });
    });

    // Función para mostrar resultados
    function mostrarResultados(partos) {
    $('#resultadoPartos').empty();

    if (!partos.length) {
        $('#resultadoPartos').html('<p>No se encontraron partos para los criterios seleccionados.</p>');
        return;
    }

    // Crear tabla
    let tablaHtml = `
        <table style="border-collapse: collapse; width: 100%; border: 1px solid lightgray;">
            <thead>
                <tr style="border: 1px solid lightgray;">
                    <th style="padding: 8px; border: 1px solid lightgray;">Fecha de Parto</th>
                    <th style="padding: 8px; border: 1px solid lightgray;">Codigo Madre</th>
                    <th style="padding: 8px; border: 1px solid lightgray;">Cantidad Crías</th>
                    <th style="padding: 8px; border: 1px solid lightgray;">Codigo de Crías</th>
                    <th style="padding: 8px; border: 1px solid lightgray;">Tipo de Parto</th>
                </tr>
            </thead>
            <tbody>
    `;

    partos.forEach(parto => {
        const fechaParto = parto.fecha_parto || '-';
        const madre = parto.madre || '-';
        const numCrias = parto.crias ? parto.crias.length : 0;
        const nombresCrias = parto.crias && parto.crias.length > 0 ? parto.crias.join(', ') : 'Sin crias';
        const tipoParto = parto.tipo_parto || '-';

        tablaHtml += `
            <tr style="border: 1px solid lightgray;">
                <td style="padding: 8px; border: 1px solid lightgray;">${fechaParto}</td>
                <td style="padding: 8px; border: 1px solid lightgray;">${madre}</td>
                <td style="padding: 8px; border: 1px solid lightgray;">${numCrias}</td>
                <td style="padding: 8px; border: 1px solid lightgray;">${nombresCrias}</td>
                <td style="padding: 8px; border: 1px solid lightgray;">${tipoParto}</td>
            </tr>
        `;
    });

    tablaHtml += `
            </tbody>
        </table>
    `;

    // Insertar la tabla en el contenedor de resultados
    $('#resultadoPartos').html(tablaHtml);
}


});


</script>
   <script>

$(document).ready(function () {
    const $multiSelectInput = $('#predioSearch');
    const $predioOptions = $('#predioOptions');
    const $selectedPredios = $('#selectedPredios');
    const $hiddenInput = $('#prediosSelected');
    const $vacaOptions = $('#vacaOptions');
    const $selectedVaca = $('#selectedVaca');
    const $vacaSearch = $('#vacaSearch');

    let selectedPredios = JSON.parse($hiddenInput.val() || '[]');

    console.log("Predios inicializados:", selectedPredios);

    // Renderizar predios seleccionados
    function renderSelectedPredios() {
        console.log("Renderizando predios seleccionados:", selectedPredios);
        $selectedPredios.empty();
        selectedPredios.forEach(value => {
            const name = $predioOptions.find(`option[value="${value}"]`).data('name');
            console.log("Nombre del predio:", name);
            $selectedPredios.append(`
                <div class="selected-item" data-value="${value}">
                    ${name} <span class="remove-item">&times;</span>
                </div>
            `);
        });
    }

    // Mostrar dropdown de predios no seleccionados
    function showPredioDropdown(searchValue = '') {
        console.log("Buscando predios con valor:", searchValue);
        const filteredOptions = $predioOptions.find('option').filter(function () {
            const isSelected = selectedPredios.includes($(this).val());
            const matchesSearch = $(this).data('name').toLowerCase().includes(searchValue.toLowerCase());
            return !isSelected && matchesSearch;
        });

        let dropdownHtml = '';
        filteredOptions.each(function () {
            dropdownHtml += `
                <div class="dropdown-item" data-value="${$(this).val()}">
                    ${$(this).data('name')}
                </div>`;
        });

        $('#multiSelectDropdown').remove();
        if (dropdownHtml) {
            $multiSelectInput.after(`<div id="multiSelectDropdown" class="dropdown-menu">${dropdownHtml}</div>`);
            $('#multiSelectDropdown').show();
        }
    }

    // Evento para mostrar dropdown al enfocar o escribir
    $multiSelectInput.on('focus input', function () {
        const searchValue = $(this).val().toLowerCase();
        console.log("Input predio enfocado/escrito. Valor:", searchValue);
        showPredioDropdown(searchValue);
    });

    // Seleccionar un predio del dropdown
    $(document).on('click', '#multiSelectDropdown .dropdown-item', function () {
        const value = $(this).data('value');
        console.log("Predio seleccionado:", value);
        if (!selectedPredios.includes(value)) {
            selectedPredios.push(value);
            renderSelectedPredios();
            updateHiddenInput();
        }
        $('#multiSelectDropdown').remove();
        $multiSelectInput.val('');
    });

    // Eliminar predio seleccionado
    $selectedPredios.on('click', '.remove-item', function () {
        const valueToRemove = $(this).parent().data('value');
        console.log("Eliminando predio seleccionado:", valueToRemove);
        selectedPredios = selectedPredios.filter(value => value !== valueToRemove);
        renderSelectedPredios();
        updateHiddenInput();
    });

    // Actualizar campo oculto y obtener vacas
    function updateHiddenInput() {
        console.log("Actualizando input oculto. Predios seleccionados:", selectedPredios);
        $hiddenInput.val(JSON.stringify(selectedPredios));
        fetchVacas(selectedPredios); // Llamada AJAX para obtener vacas
    }

    // Obtener vacas según predios seleccionados
    function fetchVacas(predios) {
        console.log("Obteniendo vacas para predios:", predios);
        $.ajax({
            url: "{{ route('partos.getVacasByPredios') }}",
            method: 'GET',
            data: { predios: predios },
            success: function (response) {
                console.log("Respuesta de vacas obtenida:", response);
                if (response.animales && response.animales.length > 0) {
                    renderVacaOptions(response.animales);
                } else {
                    renderVacaOptions([]);
                }
            },
            error: function (error) {
                console.error("Error al cargar las vacas:", error);

            }
        });
    }
    // Renderizar vacas en el selector dinámico
    function renderVacaOptions(animales) {
        console.log("Renderizando opciones de vacas:", animales);
        $vacaOptions.empty();
        $selectedVaca.empty();
        $vacaSearch.val('');
        animales.forEach(animal => {
            $vacaOptions.append(`
                <option value="${animal.id_animal}" data-name="${animal.codigo} - ${animal.nombre}">
                    ${animal.codigo} - ${animal.nombre}
                </option>
            `);
        });
        setupVacaSearch();
    }

    // Configurar búsqueda dinámica de vacas
    function setupVacaSearch() {
        console.log("Configurando búsqueda dinámica de vacas.");
        $vacaSearch.off('focus input').on('focus input', function () {
            const searchValue = $(this).val().toLowerCase();
            console.log("Buscando vacas con valor:", searchValue);

            let dropdownHtml = '';
            $vacaOptions.find('option').each(function () {
                const name = $(this).data('name').toLowerCase();
                if (name.includes(searchValue)) {
                    dropdownHtml += `
                        <div class="dropdown-item" data-value="${$(this).val()}">${name}</div>`;
                }
            });

            $('#vacaDropdown').remove();
            if (dropdownHtml) {
                $vacaSearch.after(`<div id="vacaDropdown" class="dropdown-menu">${dropdownHtml}</div>`);
                $('#vacaDropdown').show(); // Mostrar el dropdown
            }
        });

        // Seleccionar vaca del dropdown
        $(document).on('click', '#vacaDropdown .dropdown-item', function () {
            const value = $(this).data('value');
            const name = $(this).text();
            console.log("Vaca seleccionada:", value, name);
            $selectedVaca.html(`
                <div class="selected-item" data-value="${value}">
                    ${name} <span>&times;</span>
                </div>
            `);
            $('#vacaSelected').val(value);
            $('#vacaDropdown').remove();
        });

        // Limpiar vaca seleccionada
        $selectedVaca.on('click', '.selected-item span', function () {
            console.log("Limpiando vaca seleccionada.");
            $selectedVaca.empty();
            $('#vacaSelected').val('');
        });
    }

    // Inicializar predios seleccionados
    renderSelectedPredios();
});



   </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoBusqueda = document.getElementById('tipo_busqueda');
            const fechaInicioField = document.getElementById('fecha_inicio_field');
            const fechaFinField = document.getElementById('fecha_fin_field');
            const codigoVacaField = document.getElementById('selectvacadiv');

            // Función para controlar la visibilidad
            function toggleFields() {
                const tipo = tipoBusqueda.value;

                // Resetear visibilidad
                fechaInicioField.classList.add('d-none');
                fechaFinField.classList.add('d-none');
                codigoVacaField.classList.add('d-none');

                // Mostrar los campos según la selección
                if (tipo === 'fecha') {
                    fechaInicioField.classList.remove('d-none');
                    fechaFinField.classList.remove('d-none');
                } else if (tipo === 'vaca') {
                    codigoVacaField.classList.remove('d-none');
                }
            }
            // Evento: Al cambiar el selector de tipo de búsqueda
            tipoBusqueda.addEventListener('change', toggleFields);
            // Llamada inicial para manejar preselecciones
            toggleFields();
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
@endsection
