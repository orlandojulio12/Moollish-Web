@extends('layouts')

@section('title')
    Pesajes de leche
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

    @media (width < 768px) {
        .d-flex {
            flex-wrap: wrap;

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
                <a href="{{ route('produccionAnimal') }}">
                    <h3 class="cumb no-active-tab">
                        Produccion animal
                    </h3>
                </a>

                <span class="material-symbols-outlined bread">
                    chevron_forward
                </span>
                <h3 class="cumb active-tab"> Pesajes de leche </h3>
            </div>
            <hr>
        </div>
        <div class="">
            <form action="{{ route('pesaje_leche.store') }}" method="POST">
                @csrf
                <div class="d-flex space-b" style="align-items: flex-end;">
                    <div class="form-group two-column">
                        <label for="id_animal" class="form-label">Seleccione un animal <span
                                style="color: red;">*</span></label>
                        <input type="hidden" id="id_animal" name="id_animal" required>
                        <div class="input-dinamico-animal">
                            <div id="animalSeleccionado"></div>
                            <button type="button" class="buton-dinamico-animal" data-popup="id_animal">
                                <span class="material-symbols-outlined">search</span>
                            </button>
                        </div>
                    </div>
                    @include('components.selector-animales', [
                        'predios' => $predios,
                        'animales' => $animales_store,
                    ])

                    <div class="form-group two-column">
                        <label for="dias_parida_texto">Días de parida</label>
                        <input type="text" id="dias_parida_texto" class="form-control" readonly>
                        <input type="number" name="dias_parida" id="dias_parida" readonly hidden>
                        @error('dias_parida')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="d-flex space-b">
                    <div class="form-group three-column">
                        <label for="pesaje_am">Pesaje AM (L)</label>
                        <input type="number" name="pesaje_am" class="form-control"
                            placeholder="Ingrese el pesaje AM en litros">
                        @error('pesaje_am')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group three-column">
                        <label for="pesaje_pm">Pesaje PM (L)</label>
                        <input type="number" name="pesaje_pm" class="form-control"
                            placeholder="Ingrese el pesaje PM en litros">
                        @error('pesaje_pm')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group three-column">
                        <label for="total_pesaje">Total pesaje en litros</label>
                        <input disabled type="number" name="total_pesaje" class="form-control"
                            placeholder="Ingrese el total">
                        @error('total_pesaje')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <!-- Otros campos del formulario -->
                <div class="form-group">
                    <label for="fecha_pesaje">Fecha y Hora de Pesaje</label>
                    <input type="datetime-local" name="fecha_pesaje" id="fecha_pesaje" class="form-control"
                        value="{{ old('fecha_pesaje', isset($pesaje->fecha_pesaje) ? $pesaje->fecha_pesaje->format('Y-m-d\TH:i') : '') }}"
                        required>
                    @error('fecha_pesaje')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <hr>
                <button type="submit" class="btn btn-primary" style="margin: 10px 0px;">Registrar peso</button>
            </form>
            <hr>
            {{-- AQUI EMPIEZA TABLA DINAMICA --}}
            <h3>Consultar Pesajes de Leche</h3>
            <form action="{{ route('pesajeLeche.historial') }}" method="GET">
                @csrf
                <div class="d-flex space-b">
                    <div class="form-group two-column" style="    margin: 0px 10px 0px 0px;">

                        <label for="id_animal" class="form-label">Seleccione un animal <span
                                style="color: red;">*</span></label>
                        {{--  <input type="hidden" id="animal2" name="animal2" required>
                                <div class="input-dinamico-animal">
                                    <div id="animal2_text"></div>
                                    <button type="button" class="buton-dinamico-animal" data-popup="animal2">
                                        <span class="material-symbols-outlined">search</span>
                                    </button>
                                </div>
                            </div> --}}
                        {{--    @include('components.selector-animales', [
                                    'nombre' => 'animal2',
                                    'predios' => $predios,
                                    'animales' => $animales_historial,
                                ]) --}}
                      {{--   <label for="id_animal_busqueda">Animal</label> --}}
                        <select name="id_animal" id="id_animal_busqueda" class="form-control" required>
                            <option value="" selected>Seleccione un animal</option>
                            @foreach ($animales_historial as $animal)
                                <option value="{{ $animal->id_animal }}">
                                    {{ $animal->nombre }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="d-flex space-b" style="align-items: flex-end; margin: 0px 0px 10px 0px;">
                    <div class="form-group two-column" style="margin: 0px 10px 0px 0px">
                        <label for="fecha_inicio">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                    </div>

                    <div class="form-group two-column">
                        <label for="fecha_fin">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="height: 46px;">
                    Mostrar historial de pesos
                </button>
        </div>
        </form>

        <!-- Aquí es donde se mostrará la tabla con los resultados -->
        @if (isset($historialPesajes) && $historialPesajes->isNotEmpty())
            <div class="container-table">
                <h4>Resultados</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre del animal</th>
                            <th>Fecha de Pesaje</th>
                            <th>Pesaje AM (L)</th>
                            <th>Pesaje PM (L)</th>
                            <th>Total Pesaje (L)</th>
                            <th>Días de parida</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historialPesajes as $pesaje)
                            <tr>
                                <td>{{ $pesaje->animal->nombre }}</td>
                                <td>{{ $pesaje->fecha_pesaje }}</td>
                                <td>{{ $pesaje->pesaje_am }}</td>
                                <td>{{ $pesaje->pesaje_pm }}</td>
                                <td>{{ $pesaje->total_pesaje }}</td>

                                <td>
                                    @if ($pesaje->dias_parida !== null)
                                        {{ $pesaje->dias_parida }}
                                    @else
                                        Sin registros
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        {{-- AQUI EMPIEZA TABLA DINAMICA --}}
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
        // Función para actualizar los campos de "Días de parida"
        function actualizarDiasParida() {
            var animalId = parseInt(document.getElementById('id_animal').value);
            console.log("Valor del input (animalId):", animalId);
            var animalSeleccionado = animalesGlobal.filter(function(animal) {
                return parseInt(animal.id_animal) === animalId;
            })[0];
            console.log("Animal seleccionado:", animalSeleccionado);

            if (animalSeleccionado) {
                document.getElementById('dias_parida_texto').value = animalSeleccionado.dias_parida || '';
                document.getElementById('dias_parida').value = Math.round(parseFloat(animalSeleccionado
                    .dias_parida_numero || 0));
            } else {
                document.getElementById('dias_parida_texto').value = '';
                document.getElementById('dias_parida').value = '';
            }
        }

        document.getElementById('id_animal').addEventListener('change', function() {
            console.log('EVENTO CHANGE DISPARADO ✅');
            console.log('Valor seleccionado:', this.value);
        });

        // Evento cuando se selecciona el animal desde el popup
        document.getElementById('id_animal').addEventListener('change', actualizarDiasParida);

        // Al cargar la página, si hay un animal seleccionado, se actualizan los días de parida
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('id_animal').value) {
                actualizarDiasParida();
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionamos los inputs
            var pesajeAM = document.querySelector('input[name="pesaje_am"]');
            var pesajePM = document.querySelector('input[name="pesaje_pm"]');
            var totalPesaje = document.querySelector('input[name="total_pesaje"]');

            // Función para actualizar el total
            function actualizarTotal() {
                // Convertimos los valores a números, si están vacíos se consideran 0
                var am = parseFloat(pesajeAM.value) || 0;
                var pm = parseFloat(pesajePM.value) || 0;
                totalPesaje.value = am + pm;
            }

            // Añadimos el listener de eventos 'input'
            pesajeAM.addEventListener('input', actualizarTotal);
            pesajePM.addEventListener('input', actualizarTotal);
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
