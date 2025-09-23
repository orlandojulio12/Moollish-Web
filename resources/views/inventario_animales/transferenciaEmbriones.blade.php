@extends('layouts')

@section('title')
    Transferencia de Embriones
@endsection

@section('styles')
    <style>
        .card-custom {
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

    <div class="card-custom mb-4">
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

                <h3 class="cumb active-tab"> Transferencia de embriones </h3>
            </div>
            <hr>

        </div>
        <form action="{{ route('transferencias.store') }}" method="POST" id="form-transferencia">
            @csrf

            <div class="row">
                <!-- Selección de Predio -->
                {{--  <div class="col-md-4 mb-3">
                    <label for="predio_id">Predio</label>
                    <select name="predio_id" id="predio_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione un predio</option>
                        @foreach ($predios as $predio)
                            <option value="{{ $predio->id }}">
                                {{ $predio->nombre ?? 'Predio #'.$predio->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                 <!-- Receptora -->
                 <div class="col-md-4 mb-3">
                    <label for="receptora_id">Receptora</label>
                    <select name="receptora_id" id="receptora_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione receptora</option>
                    </select>
                </div> --}}

                <div class="col-md-12">
                    <label for="id_animal" class="form-label">Receptora <span style="color: red;">*</span></label>
                    <input type="hidden" id="receptora_id" name="receptora_id" required>
                    <input type="hidden" id="predio_id" name="predio_id" required>
                    <div class="input-dinamico-animal">
                        <div id="animalSeleccionado"></div>
                        <button type="button" class="buton-dinamico-animal" data-popup="{{ $nombre ?? 'id_animal' }}">                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </div>
                </div>

                @include('components.selector-animales', ['predios' => $predios, 'animales' => $animales])




            </div>

            <div class="row">
                <!-- Embrion -->
                <div class="col-md-4 mb-3">
                    <label for="embrion_id">Embrion</label>
                    <select name="embrion_id" id="embrion_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione embrion</option>
                        <!-- Se llenará vía AJAX -->
                    </select>
                </div>

                <!-- Fecha de Transferencia -->
                <div class="col-md-4 mb-3">
                    <label for="fecha_transferencia">Fecha de Transferencia</label>
                    <input type="datetime-local" name="fecha_transferencia" id="fecha_transferencia" class="form-control"
                        required>
                </div>
                <!-- Observaciones -->
                <div class="col-md-4 mb-3">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" class="form-control" rows="1"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Registrar Transferencia</button>
                </div>
            </div>
        </form>

    </div>
    <div class="card-custom ">
        <h5 class="mb-3">Histórico de Transferencias</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Predio</th>
                        <th>Fecha de Transferencia</th>
                        <th>Embrion</th>
                        <th>Vaca Receptora</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transferencias as $transferencia)
                        <tr>
                            <!-- Predio: se muestra el nombre del predio -->
                            <td>{{ $transferencia->predio->nombre_predio ?? 'N/A' }}</td>
                            <!-- Fecha: se formatea la fecha de transferencia -->
                            <td>{{ \Carbon\Carbon::parse($transferencia->fecha_transferencia)->format('d/m/Y H:i') }}</td>
                            <!-- Embrion: aquí se muestra el identificador del embrión -->
                            <td>{{ $transferencia->id_embrion }}</td>
                            <!-- Receptora: se muestra el código y el nombre de la vaca receptora, si existe -->
                            <td>
                                @if ($transferencia->receptora)
                                    {{ $transferencia->receptora->codigo }} - {{ $transferencia->receptora->nombre }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <!-- Observaciones -->
                            <td>{{ $transferencia->observaciones }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron transferencias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modal')
    {{-- Sección de modales, si tuvieras alguno adicional --}}
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#predio_id').change(function() {
            var predioId = $(this).val();
            if (!predioId) {
                return;
            }

            $('#donadora_id').empty().append('<option value="" disabled selected>Cargando...</option>');
            $('#receptora_id').empty().append('<option value="" disabled selected>Cargando...</option>');

            $.ajax({
                url: '{{ route('buscar.animales.por.predio') }}',
                method: 'GET',
                data: {
                    predio_id: predioId
                },
                success: function(response) {
                    $('#donadora_id').empty().append(
                        '<option value="" disabled selected>Seleccione Donadora</option>');
                    $('#receptora_id').empty().append(
                        '<option value="" disabled selected>Seleccione Receptora</option>');

                    $.each(response, function(index, animal) {
                        let optionText = animal.nombre ?
                            (animal.nombre + ' (' + (animal.codigo ?? 's/cod') + ')') :
                            'ID ' + animal.id_animal;

                        $('#donadora_id').append(`
                            <option value="${animal.id_animal}">${optionText}</option>
                        `);
                        $('#receptora_id').append(`
                            <option value="${animal.id_animal}">${optionText}</option>
                        `);
                    });
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    Swal.fire('Error', 'No se pudo cargar la lista de animales', 'error');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#predio_id').change(function() {
                var predioId = $(this).val();
                if (!predioId) {
                    return;
                }

                // Vaciar el select y agregar la opción por defecto mientras se carga
                $('#embrion_id').empty().append(
                    '<option value="" disabled selected>Cargando embriones...</option>');

                $.ajax({
                    url: '{{ route('buscar.embriones.por.predio') }}',
                    method: 'GET',
                    data: {
                        predio_id: predioId
                    },
                    success: function(response) {
                        // Vaciar el select y agregar la opción por defecto
                        $('#embrion_id').empty().append(
                            '<option value="" disabled selected>Seleccione embrion</option>'
                            );
                        $.each(response, function(index, embrion) {
                            // Suponemos que el embrion tiene los campos "codigo_embrion" y opcionalmente "nombre_reproductor"
                            var optionText = embrion.codigo_embrion;
                            if (embrion.nombre_reproductor) {
                                optionText += ' - ' + embrion.nombre_reproductor;
                            }
                            $('#embrion_id').append('<option value="' + embrion.id +
                                '">' + optionText + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Error al cargar los embriones');
                    }
                });
            });
        });
    </script>
@endsection
