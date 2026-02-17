@extends('layouts')

@section('template_title')
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('inicio') }}"><i class="bi bi-arrow-left"
                        style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Exportar las secciones en excel</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')

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

        .border-dashed {
            border-style: none !important;
        }

        .sesion6 {
            width: 57px;
        }

        /* Estilos del filtro - Color Moollish */
        .filter-section {
            background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(228, 155, 57, 0.3);
        }

        .filter-section h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .filter-section h5 i {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .filter-label {
            color: white;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        /* Select2 personalizado - Moollish */
        .select2-container--default .select2-selection--multiple {
            min-height: 45px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.95);
            transition: all 0.3s ease;
        }

        .select2-container--default .select2-selection--multiple:focus-within {
            border-color: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #E49B39;
            border: none;
            color: white;
            border-radius: 5px;
            padding: 5px 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #ff6b6b;
        }

        .select2-dropdown {
            border: 2px solid #E49B39;
            border-radius: 8px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #E49B39;
        }

        /* Botones - Moollish */
        .btn-search {
            background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);
            border: none;
            color: white;
            font-weight: 500;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(228, 155, 57, 0.3);
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(228, 155, 57, 0.4);
            color: white;
        }

        .btn-clear {
            background: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: #E49B39;
            font-weight: 500;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-clear:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            color: white;
            transform: translateY(-2px);
        }

        .filter-help-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem;
            margin-top: 8px;
        }

        .filter-help-text i {
            margin-right: 5px;
        }

        /* Tarjetas de exportación */
        .export-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .export-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .export-card .card {
            border: 2px solid #e5e7eb;
            transition: border-color 0.3s ease;
        }

        .export-card:hover .card {
            border-color: #E49B39;
        }

        /* Contador de predios seleccionados */
        .predios-counter {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            margin-top: 10px;
        }

        /* Input de búsqueda */
        #search_input {
            background: white !important;
            border: 2px solid rgba(255, 255, 255, 0.5) !important;
            border-radius: 8px !important;
            padding: 12px 15px 12px 40px !important;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        #search_input:focus {
            outline: none !important;
            border-color: white !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2) !important;
        }

        #search_input::placeholder {
            color: #999;
            font-style: italic;
        }

        /* Icono de búsqueda en el input */
        .bi-search {
            pointer-events: none;
            font-size: 1.1rem;
        }
    </style>

    <!-- Sección de filtros -->
    <div class="col-12 mb-4">
        <div class="filter-section">
            <h5><i class="bi bi-funnel-fill"></i> Filtros de Exportación</h5>
            
            <form id="filterForm">
                <div class="row align-items-end">
                    <!-- Campo de búsqueda de predios -->
                    <div class="col-lg-8 col-md-7 mb-3">
                        <label for="predios_filter" class="filter-label">
                            <i class="bi bi-search"></i> Buscar Predios
                        </label>
                        
                        <!-- INPUT PARA ESCRIBIR Y BUSCAR -->
                        <div class="mb-2 position-relative">
                            <i class="bi bi-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #999; z-index: 10;"></i>
                            <input type="text" id="search_input" class="form-control" placeholder="Escriba código o nombre para filtrar (ej: COD001, el infierno)..." style="padding-left: 40px;">
                            <small id="result_count" class="text-white" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 0.85rem; display: none;"></small>
                        </div>
                        
                        <!-- SELECT CON LOS PREDIOS -->
                        <select name="predios[]" id="predios_filter" class="form-control" multiple="multiple">
                            <option value="all">✓ Todos los predios</option>
                            @foreach($predios as $predio)
                                <option value="{{ $predio->id }}">{{ $predio->cod_predio }} - {{ $predio->nombre_predio }}</option>
                            @endforeach
                        </select>
                        
                        <small class="filter-help-text">
                            <i class="bi bi-lightbulb"></i> Escriba arriba para buscar, luego seleccione uno o varios predios del listado.
                        </small>
                        <div id="predios-counter" class="predios-counter" style="display: none;">
                            <i class="bi bi-check-circle"></i> <span id="counter-text">0 predios seleccionados</span>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="col-lg-4 col-md-5 mb-3">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-clear flex-fill" onclick="limpiarFiltros()">
                                <i class="bi bi-arrow-counterclockwise"></i> Limpiar
                            </button>
                            <button type="button" class="btn btn-search flex-fill" onclick="aplicarFiltros()">
                                <i class="bi bi-funnel"></i> Aplicar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Secciones de exportación -->
    <div class="col-12">
        <div class="card stretch stretch-full">
            <div class="card-body">
                <div>
                    <h5 class="mb-1">SECCIONES DISPONIBLES PARA EXPORTAR</h5>
                    <p class="text-muted">Seleccione los predios arriba y haga clic en la sección que desea exportar</p>
                </div>
            </div>
            <div class="row justify-content-center">
               
                <!-- Información del Predio -->
                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <div class="export-card" onclick="exportarSeccion('{{ route('exportarInformacionDelPredio') }}')">
                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/inofrmacion de predios.png') }}" class="sesion6" alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">INFORMACION DEL PREDIO</div>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style="width: 35px;" alt="">
                </div>

                <!-- Información para BPG -->
                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <div class="export-card" onclick="exportarSeccion('{{ route('exportBgp') }}')">
                        <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/inoformacion para bpg.png') }}" class="sesion6" alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">INFORMACION PARA BPG</div>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style="width: 35px;" alt="">
                </div>

                <!-- Riesgo Epidemiológico -->
                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <div class="export-card" onclick="exportarSeccion('{{ route('export.riesgo.epidemiologico') }}')">
                        <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/riesgo epidemiologio.png') }}" class="sesion6" alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">RIESGO EPIDEMIOLOGICO</div>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style="width: 35px;" alt="">
                </div>

                <!-- Servicios Ambientales -->
                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <div class="export-card" onclick="exportarSeccion('{{ route('exportServiciosAmbientales') }}')">
                        <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/servicios ambientales.png') }}" class="sesion6" alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">SERVICIOS AMBIENTALES</div>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style="width: 35px;" alt="">
                </div>

                <!-- Censo -->
                <div class="col-xxl-2 col-lg-4 col-md-6 position-relative">
                    <div class="export-card" onclick="exportarSeccion('{{ route('exportCenso') }}')">
                        <div class="card stretch stretch-full border border-dashed border-gray-5 position-relative">
                            <div class="card-body rounded-3 text-center">
                                <img src="{{ asset('img/caracterizacion/censo.png') }}" class="sesion6" alt="">
                                <div class="fs-5 fw-bolder text-dark mt-3 mb-1">CENSO</div>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('img/EXCEL.png') }}" class="position-absolute top-0 end-0 mt-2 me-4" style="width: 35px;" alt="">
                </div>

            </div>
        </div>
    </div>

    <script>
        // Guardar todas las opciones originales
        var todasLasOpciones = [];
        
        $(document).ready(function() {
            // Guardar todas las opciones al inicio
            $('#predios_filter option').each(function() {
                todasLasOpciones.push({
                    value: $(this).val(),
                    text: $(this).text()
                });
            });
            
            // Inicializar Select2 simple
            $('#predios_filter').select2({
                placeholder: 'Seleccione predios del listado...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function () {
                        return 'No se encontraron predios';
                    }
                }
            });

            // Seleccionar "Todos" por defecto
            $('#predios_filter').val(['all']).trigger('change');
            actualizarContador();

            // FILTRO EN TIEMPO REAL AL ESCRIBIR - MEJORADO
            $('#search_input').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase().trim();
                
                // Guardar selección actual
                var seleccionActual = $('#predios_filter').val() || [];
                
                // Destruir Select2
                $('#predios_filter').select2('destroy');
                
                // Limpiar opciones
                $('#predios_filter').empty();
                
                // Filtrar y agregar opciones
                var opcionesFiltradas = todasLasOpciones.filter(function(opcion) {
                    // Siempre incluir "Todos"
                    if (opcion.value === 'all') {
                        return true;
                    }
                    
                    // Si no hay búsqueda, mostrar todo
                    if (searchTerm === '') {
                        return true;
                    }
                    
                    // Buscar en el texto
                    return opcion.text.toLowerCase().indexOf(searchTerm) > -1;
                });
                
                // Agregar opciones filtradas
                opcionesFiltradas.forEach(function(opcion) {
                    $('#predios_filter').append(new Option(opcion.text, opcion.value));
                });
                
                // Reinicializar Select2
                $('#predios_filter').select2({
                    placeholder: 'Seleccione predios del listado...',
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function () {
                            return 'No se encontraron predios';
                        }
                    }
                });
                
                // Restaurar selección
                $('#predios_filter').val(seleccionActual).trigger('change');
                
                // Mostrar contador de resultados
                var cantidadResultados = opcionesFiltradas.length - 1; // Restar "Todos"
                if (searchTerm !== '') {
                    if (cantidadResultados === 0) {
                        $('#result_count').text('Sin resultados').css('color', '#ff6b6b').show();
                    } else {
                        $('#result_count').text(cantidadResultados + ' encontrado' + (cantidadResultados !== 1 ? 's' : '')).css('color', 'white').show();
                    }
                } else {
                    $('#result_count').hide();
                }
                
                // Mostrar mensaje si no hay resultados
                if (opcionesFiltradas.length === 1 && searchTerm !== '') {
                    $('#predios_filter').append(new Option('❌ No se encontraron predios con "' + searchTerm + '"', '', false, false));
                }
            });

            // Cuando se selecciona "Todos"
            $('#predios_filter').on('select2:select', function (e) {
                var data = e.params.data;
                if (data.id === 'all') {
                    $('#predios_filter').val(['all']).trigger('change');
                }
                actualizarContador();
            });

            // Cuando se deselecciona algo
            $('#predios_filter').on('select2:unselect', function (e) {
                var data = e.params.data;
                if (data.id === 'all') {
                    $('#predios_filter').val([]).trigger('change');
                }
                actualizarContador();
            });

            // Quitar "Todos" si se selecciona otro predio
            $('#predios_filter').on('select2:selecting', function (e) {
                var data = e.params.args.data;
                if (data.id !== 'all') {
                    var valores = $('#predios_filter').val() || [];
                    var index = valores.indexOf('all');
                    if (index > -1) {
                        valores.splice(index, 1);
                        $('#predios_filter').val(valores);
                    }
                }
            });

            // Actualizar contador cuando cambian las selecciones
            $('#predios_filter').on('change', function() {
                actualizarContador();
            });
        });

        function actualizarContador() {
            var seleccionados = $('#predios_filter').val() || [];
            var counter = $('#predios-counter');
            var counterText = $('#counter-text');
            
            if (seleccionados.length === 0 || seleccionados.includes('all')) {
                counterText.text('Todos los predios seleccionados');
                counter.show();
            } else {
                counterText.text(seleccionados.length + ' predio' + (seleccionados.length !== 1 ? 's' : '') + ' seleccionado' + (seleccionados.length !== 1 ? 's' : ''));
                counter.show();
            }
        }

        function aplicarFiltros() {
            var prediosSeleccionados = $('#predios_filter').val();
            
            if (!prediosSeleccionados || prediosSeleccionados.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Filtros aplicados',
                    text: 'Se exportarán todos los predios disponibles',
                    confirmButtonColor: '#E49B39',
                    timer: 2000,
                    timerProgressBar: true
                });
            } else if (prediosSeleccionados.includes('all')) {
                Swal.fire({
                    icon: 'success',
                    title: 'Filtros aplicados',
                    text: 'Se exportarán todos los predios',
                    confirmButtonColor: '#E49B39',
                    timer: 2000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Filtros aplicados',
                    text: prediosSeleccionados.length + ' predio(s) seleccionado(s)',
                    confirmButtonColor: '#E49B39',
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        }

        function limpiarFiltros() {
            // Limpiar el input de búsqueda
            $('#search_input').val('');
            
            // Destruir Select2
            $('#predios_filter').select2('destroy');
            
            // Restaurar todas las opciones
            $('#predios_filter').empty();
            todasLasOpciones.forEach(function(opcion) {
                $('#predios_filter').append(new Option(opcion.text, opcion.value));
            });
            
            // Reinicializar Select2
            $('#predios_filter').select2({
                placeholder: 'Seleccione predios del listado...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function () {
                        return 'No se encontraron predios';
                    }
                }
            });
            
            // Seleccionar "Todos"
            $('#predios_filter').val(['all']).trigger('change');
            
            Swal.fire({
                icon: 'info',
                title: 'Filtros limpiados',
                text: 'Se seleccionaron todos los predios',
                confirmButtonColor: '#E49B39',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false
            });
            
            actualizarContador();
        }

        function exportarSeccion(rutaBase) {
            var prediosSeleccionados = $('#predios_filter').val();
            
            // Si no hay predios seleccionados o se seleccionó "all", exportar todos
            if (!prediosSeleccionados || prediosSeleccionados.length === 0 || prediosSeleccionados.includes('all')) {
                prediosSeleccionados = ['all'];
            }

            // Construir URL con parámetros
            var url = rutaBase + "?predios=" + prediosSeleccionados.join(',');
            
            // Mostrar mensaje de carga
            Swal.fire({
                title: '<i class="bi bi-hourglass-split"></i> Generando archivo Excel...',
                html: 'Por favor espere mientras se procesa su solicitud',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Realizar la descarga
            window.location.href = url;

            // Cerrar el mensaje después de 2 segundos
            setTimeout(function() {
                Swal.close();
                
                Swal.fire({
                    icon: 'success',
                    title: '¡Archivo generado!',
                    text: 'La descarga debería comenzar automáticamente',
                    confirmButtonColor: '#E49B39',
                    timer: 3000,
                    timerProgressBar: true
                });
            }, 2000);
        }
    </script>

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#E49B39'
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#E49B39',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif
@endsection