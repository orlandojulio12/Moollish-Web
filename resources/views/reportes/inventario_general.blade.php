@extends('layouts')

@section('title')
    Inventario general
@endsection

@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
            margin-bottom: 20px;
            box-shadow: none;
        }

        .bread {
            font-size: 28px;
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

        .form-group {
            margin: 0px 10px;
        }

        .flex-end {
            display: flex;
            align-items: flex-end;
        }

        .fila-vacas {
            display: flex;
            width: 100%;
            flex-wrap: wrap;
        }

        .fila-toros {
            display: flex;
            width: 100%;
            flex-wrap: wrap;
        }

        .card-inventario {
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin: 10px 5px;
            cursor: pointer;
            transition: 100ms linear;
            box-shadow: none;
            border: 2px solid #e5e7eb;

            min-width: 180px;
        }

        .card-inventario:hover {
            background: #e49b3912;
            box-shadow: none;
            border: 2px solid lightgray;
        }

        .card-active {
            border: 2px solid #b76900 !important;
            background: #e49b39 !important;
            color: white;
        }

        .btn-primary {
            background-color: #e49b39;
            border-color: #e49b39;
        }

        .btn-primary:hover {
            background-color: #d38b29;
            border-color: #d38b29;
        }

        .card-header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .card-title {
            font-size: 23px;
            font-weight: 700;
            color: #292929;
            margin: 0;
        }

        .card-subtitle {
            font-size: 14px;
            color: #767676;
            margin-top: 5px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e49b39;
            font-weight: 600;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .form-control {
            padding: 10px 15px;
            height: auto;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            width: 100%;
            font-size: 1rem;
        }

        .search-input {
            display: flex;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            margin-bottom: 15px;
        }

        .search-input input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            outline: none;
            font-size: 14px;
        }

        .search-input .search-icon {
            display: flex;
            align-items: center;
            padding: 0 15px;
            background-color: #f8f9fa;
            color: #E49B39;
        }

        #predioTotals {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        #predioTotals div {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 5px;
            box-shadow: none;
            font-weight: 500;
        }

        /* Estilos para el popup de detalles */
        .popup-detalles {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            max-width: 90%;
            background: white;
            border-radius: 10px;
            z-index: 2000;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .popup-header {
            background-color: #e49b39;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .popup-title {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .popup-close {
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
        }

        .popup-body {
            padding: 20px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .popup-icon {
            font-size: 30px;
            color: #e49b39;
            margin-right: 10px;
        }

        .animal-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .animal-info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .animal-info-table th {
            width: 40%;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            text-align: left;
            color: #495057;
            font-weight: 600;
        }

        .animal-info-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            color: #212529;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1999;
        }

        .btn-ver-detalles {
            background-color: #e49b39;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-ver-detalles:hover {
            background-color: #d38b29;
        }

        .btn-cerrar-popup {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 15px;
        }

        .btn-cerrar-popup:hover {
            background-color: #5a6268;
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
                    <a href="{{ route('listados') }}">
                        <h3 class="cumb no-active-tab">
                            Listados
                        </h3>
                    </a>

                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <h3 class="cumb active-tab"> Inventario general</h3>
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
                    <h3 class="cumb active-tab"> Inventario animal</h3>
                </div>
            @endif
            <hr>
        </div>

        <div class="card-header-container ">
            <div>
                <h2 class="card-title">Inventario General de Animales</h2>
                <p class="card-subtitle">Consulta y filtra los animales de tu inventario</p>
            </div>
            <div>
                <div class="total-animales-container"
                    style="background-color: #e49b39; color: white; padding: 12px 20px; border-radius: 8px; display: flex; align-items: center; box-shadow: 0 3px 8px rgba(228, 155, 57, 0.2);">
                    <div>
                        <div style="font-size: 14px; font-weight: 500;">Total de animales</div>
                        <div style="font-size: 24px; font-weight: 700;"><span
                                id="animales-filtrados">{{ $totalAnimales }}</span>/<span
                                id="animales-total">{{ $totalAnimales }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="predioTotals" class="fila-predios">
            @foreach ($predios as $predio)
                <div>
                    {{ $predio->nombre_predio }}: {{ $predio->total_animales }} animales
                </div>
            @endforeach
        </div>

        <div class="fila-vacas">
            <div class="card-inventario" data-filter="hembras">
                <span>
                    Hembras : {{ $hembrasTotales }}
                </span>
            </div>

            <div class="card-inventario" data-filter="vacas_prenadas">
                <span>
                    Vacas preñadas: {{ $vacasPrenadas }}
                </span>
            </div>

            <div class="card-inventario" data-filter="vacas_vacias">
                <span>
                    Vacas vacías: {{ $vacasVacias }}
                </span>
            </div>

            <div class="card-inventario" data-filter="crias_hembra">
                <span>
                    Crias hembra: {{ $criasHembra }}
                </span>
            </div>
        </div>
        <div class="fila-toros">
            <div class="card-inventario" data-filter="machos">
                <span>
                    Machos : {{ $machosTotales }}
                </span>
            </div>

            <div class="card-inventario" data-filter="toros">
                <span>
                    Toros: {{ $toros }}
                </span>
            </div>

            <div class="card-inventario" data-filter="crias_macho">
                <span>
                    Crias macho: {{ $criasMacho }}
                </span>
            </div>
        </div>

        <div class="form-section mt-4">
            <h3 style="color: #e49b39; margin-bottom: 10px;">Filtros de búsqueda</h3>
            <p class="text-muted" style="font-size: 14px; color: #767676;">Selecciona los criterios para filtrar los
                animales</p>

            <!-- Formulario de filtros de ubicación -->
            <form id="filterForm">
                <div class="d-flex col-12 flex-wrap">
                    <div class="form-group">
                        <label for="predios" class="form-label">Predio</label>
                        <select name="predios" id="predios" class="form-control">
                            <option value="">Seleccionar predio</option>
                            @foreach ($predios as $predio)
                                <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="lotes" class="form-label">Lote</label>
                        <select name="lotes" id="lotes" class="form-control">
                            <option value="">Seleccionar lote</option>
                            @foreach ($predios as $predio)
                                @foreach ($predio->lotes as $lote)
                                    <option value="{{ $lote->id }}">{{ $lote->nombre }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="potreros" class="form-label">Potrero</label>
                        <select name="potreros" id="potreros" class="form-control">
                            <option value="">Seleccionar potrero</option>
                            @foreach ($predios as $predio)
                                @foreach ($predio->potreros as $potrero)
                                    <option value="{{ $potrero->id }}">{{ $potrero->nombre }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" id="codigo" name="codigo" class="form-control"
                            placeholder="Filtrar por código">
                    </div>

                    <div class="form-group">
                        <label for="identificacion_electronica" class="form-label">ID Electrónica</label>
                        <input type="text" id="identificacion_electronica" name="identificacion_electronica"
                            class="form-control" placeholder="Filtrar por ID electrónica">
                    </div>

                    <div class="flex-end">
                        <button class="btn-ver-detalles" type="button" id="filterButton"
                            style="margin: 0px 10px; padding: 10px 20px;">
                            <i class="fas fa-search me-2"></i> Filtrar
                        </button>
                        <button class="btn btn-secondary" id="resetButton" type="button" style="padding: 11px;">
                            <span style="font-size: 17px" class="material-symbols-outlined">cached</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabla de animales -->
        <div class="table-responsive mt-4">
            @if ($animales->isEmpty())
                <div class="alert alert-info">No hay animales registrados.</div>
            @else
                <table class="table table-bordered table-striped" id="proposalList">
                    <thead class="thead-dark">
                        <tr>
                            <th>Código</th>
                            <th>ID Electrónica</th>
                            <th>Sexo</th>
                            <th>Raza</th>
                            <th>Estado Productivo</th>
                            <th>Predio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($animales as $animal)
                            <tr data-sexo="{{ strtolower($animal['sexo']) }}"
                                data-estado-reproductivo="{{ strtolower($animal['estado_reproductivo']) }}"
                                data-estado-productivo="{{ strtolower($animal['estado_productivo']) }}"
                                data-predio="{{ $animal['predio'] }}">
                                <td>{{ $animal['codigo'] }}</td>
                                <td>{{ $animal['identificacion_electronica'] ?? 'N/A' }}</td>
                                <td>{{ $animal['sexo'] }}</td>
                                <td>{{ $animal['raza'] ?? 'N/A' }}</td>
                                <td>{{ $animal['estado_productivo'] }}</td>
                                <td>{{ $animal['predio'] ?? 'N/A' }}</td>
                                <td>
                                    <button type="button" class="btn-ver-detalles"
                                        data-codigo="{{ $animal['codigo'] }}"
                                        data-id-electronica="{{ $animal['identificacion_electronica'] ?? 'N/A' }}"
                                        data-sexo="{{ $animal['sexo'] }}" data-raza="{{ $animal['raza'] ?? 'N/A' }}"
                                        data-estado-productivo="{{ $animal['estado_productivo'] }}"
                                        data-estado-reproductivo="{{ $animal['estado_reproductivo'] }}"
                                        data-dias-prenez="{{ is_numeric($animal['dias_de_prenez']) ? intval($animal['dias_de_prenez']) : 'N/A' }}"
                                        data-dias-parida="{{ $animal['dias_de_parida'] !== 'N/A' ? $animal['dias_de_parida'] : 'N/A' }}"
                                        data-predio="{{ $animal['predio'] ?? 'N/A' }}"
                                        data-potrero="{{ $animal['potrero'] ?? 'N/A' }}"
                                        data-lote="{{ $animal['lote'] ?? 'N/A' }}">
                                        Ver detalles
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Popup para mostrar detalles del animal -->
        <div class="overlay" id="overlayDetalles"></div>
        <div class="popup-detalles" id="popupDetalles">
            <div class="popup-header">
                <div class="popup-title">
                    <div id="animal-icon" class="animal-icon">
                        <!-- Icono animal se insertará por JS -->
                    </div>
                    <span id="popup-title-text">Detalles del Animal</span>
                </div>
                <button class="popup-close" id="cerrarPopup">&times;</button>
            </div>
            <div class="popup-body">
                <table class="animal-info-table">
                    <tbody>
                        <tr>
                            <th>Código</th>
                            <td id="detalle-codigo"></td>
                        </tr>
                        <tr>
                            <th>ID Electrónica</th>
                            <td id="detalle-id-electronica"></td>
                        </tr>
                        <tr>
                            <th>Sexo</th>
                            <td id="detalle-sexo"></td>
                        </tr>
                        <tr>
                            <th>Raza</th>
                            <td id="detalle-raza"></td>
                        </tr>
                        <tr>
                            <th>Estado Productivo</th>
                            <td id="detalle-estado-productivo"></td>
                        </tr>
                        <tr>
                            <th>Estado Reproductivo</th>
                            <td id="detalle-estado-reproductivo"></td>
                        </tr>
                        <tr>
                            <th>Días de Preñez</th>
                            <td id="detalle-dias-prenez"></td>
                        </tr>
                        <tr>
                            <th>Días de Parida</th>
                            <td id="detalle-dias-parida"></td>
                        </tr>
                        <tr>
                            <th>Predio</th>
                            <td id="detalle-predio"></td>
                        </tr>
                        <tr>
                            <th>Potrero</th>
                            <td id="detalle-potrero"></td>
                        </tr>
                        <tr>
                            <th>Lote</th>
                            <td id="detalle-lote"></td>
                        </tr>
                    </tbody>
                </table>
                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn-cerrar-popup" id="cerrarPopupBtn">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Variable para almacenar el total de animales
            const totalAnimales = {{ $totalAnimales }};

            // Función para actualizar el contador de animales filtrados
            function actualizarContador() {
                const animalesFiltrados = $('#proposalList tbody tr:visible').length;
                $('#animales-filtrados').text(animalesFiltrados);
            }

            // Ejecutar inicialmente para establecer el contador
            actualizarContador();

            // Función para actualizar filtros de cards sobre la tabla
            function updateFilters() {
                var activeFilters = [];
                $('.card-inventario.card-active').each(function() {
                    activeFilters.push($(this).data('filter'));
                });

                // Si no hay filtros activos, muestra todas las filas
                if (activeFilters.length === 0) {
                    $('#proposalList tbody tr').show();
                    updatePredioTotals();
                    actualizarContador();
                    return;
                }

                // Lógica OR: se muestra la fila si cumple al menos uno de los filtros activos
                $('#proposalList tbody tr').each(function() {
                    var $row = $(this);
                    var showRow = false;
                    activeFilters.forEach(function(filter) {
                        if (filter === 'hembras') {
                            if ($row.data('sexo') === 'hembra') {
                                showRow = true;
                            }
                        } else if (filter === 'vacas_prenadas') {
                            if (String($row.data('estado-reproductivo')).toLowerCase().indexOf(
                                    'preñada') !== -1) {
                                showRow = true;
                            }
                        } else if (filter === 'vacas_vacias') {
                            if (String($row.data('estado-reproductivo')).toLowerCase().indexOf(
                                    'vacia') !== -1) {
                                showRow = true;
                            }
                        } else if (filter === 'crias_hembra') {
                            if (String($row.data('estado-productivo')).toLowerCase().indexOf(
                                    'cria hembra') !== -1) {
                                showRow = true;
                            }
                        } else if (filter === 'machos') {
                            if ($row.data('sexo') === 'macho') {
                                showRow = true;
                            }
                        } else if (filter === 'toros') {
                            if (String($row.data('estado-productivo')).toLowerCase().indexOf(
                                'toro') !== -1) {
                                showRow = true;
                            }
                        } else if (filter === 'crias_macho') {
                            if (String($row.data('estado-productivo')).toLowerCase().indexOf(
                                    'cria macho') !== -1) {
                                showRow = true;
                            }
                        }
                    });
                    if (showRow) {
                        $row.show();
                    } else {
                        $row.hide();
                    }
                });
                updatePredioTotals();
                actualizarContador();
            }

            // Actualiza totales por predio (ejemplo básico)
            function updatePredioTotals() {
                var totals = {};
                $('#proposalList tbody tr:visible').each(function() {
                    var predio = $(this).data('predio');
                    if (predio) {
                        totals[predio] = (totals[predio] || 0) + 1;
                    }
                });
                var html = '';
                $.each(totals, function(predio, count) {
                    html += '<div>' + predio + ': ' + count + ' animales</div>';
                });
                $('#predioTotals').html(html);
            }

            // Manejar clic en cards de filtros
            $('.card-inventario').on('click', function() {
                $(this).toggleClass('card-active');
                updateFilters();
            });

            $('#resetButton').on('click', function() {
                $('.card-inventario').removeClass('card-active');
                $('#codigo, #identificacion_electronica').val('');
                $('#filterForm')[0].reset();
                updateFilters();
                $('#proposalList tbody tr').show();
                updatePredioTotals();
            });

            // Filtrado por ubicación: Al presionar el botón "Filtrar" del formulario
            $('#filterButton').on('click', function() {
                const predio = $('#predios').val();
                const lote = $('#lotes').val();
                const potrero = $('#potreros').val();
                const codigo = $('#codigo').val();
                const idElectronica = $('#identificacion_electronica').val();

                fetch('/inventario/general/filtrar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            predio,
                            lote,
                            potrero,
                            codigo,
                            identificacion_electronica: idElectronica
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        let tableBody = '';
                        data.animales.forEach(animal => {
                            tableBody += `
                    <tr data-sexo="${animal.sexo.toLowerCase()}"
                        data-estado-reproductivo="${animal.estado_reproductivo.toLowerCase()}"
                        data-estado-productivo="${animal.estado_productivo.toLowerCase()}"
                        data-predio="${animal.predio}">
                        <td>${animal.codigo}</td>
                        <td>${animal.identificacion_electronica || 'N/A'}</td>
                        <td>${animal.sexo === 'macho' ? 'Macho' : animal.sexo === 'hembra' ? 'Hembra' : 'N/A'}</td>
                        <td>${animal.raza || 'N/A'}</td>
                        <td>${animal.estado_productivo || 'N/A'}</td>
                        <td>${animal.predio || 'N/A'}</td>
                        <td>
                          <button type="button" class="btn-ver-detalles"
                            data-codigo="${animal.codigo}"
                            data-id-electronica="${animal.identificacion_electronica || 'N/A'}"
                            data-sexo="${animal.sexo}"
                            data-raza="${animal.raza || 'N/A'}"
                            data-estado-productivo="${animal.estado_productivo}"
                            data-estado-reproductivo="${animal.estado_reproductivo}"
                            data-dias-prenez="${typeof animal.dias_de_prenez === 'number' ? animal.dias_de_prenez : 'N/A'}"
                            data-dias-parida="${animal.dias_de_parida || 'N/A'}"
                            data-predio="${animal.predio || 'N/A'}"
                            data-potrero="${animal.potrero || 'N/A'}"
                            data-lote="${animal.lote || 'N/A'}">
                            <i class="fas fa-eye"></i> Ver detalles
                          </button>
                        </td>
                    </tr>
                `;
                        });
                        $('#proposalList tbody').html(tableBody);
                        updateFilters(); // Aplica los filtros de cards sobre el resultado de ubicación
                        actualizarContador(); // Actualiza el contador de animales
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un error al intentar obtener los datos. Intenta nuevamente.');
                    });
            });

            // Filtrado de texto en tiempo real
            $('#codigo, #identificacion_electronica').on('keyup', function() {
                const codigoTexto = $('#codigo').val().toLowerCase();
                const idElectronicaTexto = $('#identificacion_electronica').val().toLowerCase();

                // Filtrar filas de la tabla según texto ingresado
                $('#proposalList tbody tr').each(function() {
                    const $row = $(this);
                    const codigo = $row.find('td:eq(0)').text().toLowerCase();
                    const idElectronica = $row.find('td:eq(1)').text().toLowerCase();

                    if ((codigoTexto === '' || codigo.includes(codigoTexto)) &&
                        (idElectronicaTexto === '' || idElectronica.includes(idElectronicaTexto))) {
                        $row.show();
                    } else {
                        $row.hide();
                    }
                });

                // Actualizar totales por predio y contador
                updatePredioTotals();
                actualizarContador();
            });

            // Mostrar detalles del animal al hacer clic en el botón "Ver detalles"
            $('#proposalList').on('click', '.btn-ver-detalles', function() {
                const codigo = $(this).data('codigo');
                const idElectronica = $(this).data('id-electronica');
                const sexo = $(this).data('sexo');
                const raza = $(this).data('raza');
                const estadoProductivo = $(this).data('estado-productivo');
                const estadoReproductivo = $(this).data('estado-reproductivo');
                const diasPrenez = $(this).data('dias-prenez');
                const diasParida = $(this).data('dias-parida');
                const predio = $(this).data('predio');
                const potrero = $(this).data('potrero');
                const lote = $(this).data('lote');

                // Llenar datos en el popup
                $('#detalle-codigo').text(codigo);
                $('#detalle-id-electronica').text(idElectronica);
                $('#detalle-sexo').text(sexo);
                $('#detalle-raza').text(raza);
                $('#detalle-estado-productivo').text(estadoProductivo);
                $('#detalle-estado-reproductivo').text(estadoReproductivo);
                $('#detalle-dias-prenez').text(diasPrenez);
                $('#detalle-dias-parida').text(diasParida);
                $('#detalle-predio').text(predio);
                $('#detalle-potrero').text(potrero);
                $('#detalle-lote').text(lote);

                // Añadir icono según el sexo
                let iconoAnimal = '';
                if (sexo.toLowerCase() === 'hembra') {
                    iconoAnimal = '<img src="/images/vaca.png" alt="Vaca" width="30" height="30" />';
                    if (!$('img[src="/images/vaca.png"]').length) {
                        iconoAnimal =
                            '<i class="fas fa-venus" style="color: #e49b39; font-size: 24px;"></i>';
                    }
                } else {
                    iconoAnimal = '<img src="/images/toro.png" alt="Toro" width="30" height="30" />';
                    if (!$('img[src="/images/toro.png"]').length) {
                        iconoAnimal =
                        '<i class="fas fa-mars" style="color: #e49b39; font-size: 24px;"></i>';
                    }
                }
                $('#animal-icon').html(iconoAnimal);
                $('#popup-title-text').text('Detalles del ' + (sexo.toLowerCase() === 'hembra' ?
                    'Animal Hembra' : 'Animal Macho'));

                // Mostrar el popup
                $('#overlayDetalles').show();
                $('#popupDetalles').show();
            });

            // Cerrar popup al hacer clic en el botón de cerrar o fuera del popup
            $('#cerrarPopup, #cerrarPopupBtn, #overlayDetalles').on('click', function() {
                $('#overlayDetalles').hide();
                $('#popupDetalles').hide();
            });

            // Evitar que el clic dentro del popup cierre el popup
            $('#popupDetalles').on('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
@endsection
