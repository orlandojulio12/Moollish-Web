@extends('layouts')

@section('title')
    Inseminacion artificial
@endsection

@section(
    'styles'
)

<style>
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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                        <h3 class="cumb active-tab">Inseminacion artificial </h3>
                </div>
                <hr>
            </div>
            <form id="inseminacionForm" method="POST" action="{{ route('inseminacion.registrar') }}">
                @csrf
                <!-- Selección de la vaca -->
                <div class="mb-3">
                {{--   <label for="id_vaca" class="form-label">Seleccione la Vaca</label>
                   <select class="form-control" id="id_vaca" name="id_vaca" required>
                        <option value="">Seleccione una vaca</option>
                        @foreach ($vacas as $vaca)
                            <option value="{{ $vaca->id_animal }}">
                                {{ $vaca->nombre }}
                            </option>
                        @endforeach
                    </select> --}}

                  <label for="id_animal" class="form-label">Seleccione una vaca <span style="color: red;">*</span></label>
                    <input type="hidden" id="id_animal" name="id_vaca" required>
                    <div class="input-dinamico-animal">
                        <div id="animalSeleccionado"></div>
                        <button type="button" class="buton-dinamico-animal" data-popup="{{ $nombre ?? 'id_animal' }}">                            <span class="material-symbols-outlined">search</span>
                          </button>
                    </div>
                </div>
                @include('components.selector-animales', ['predios' => $predios, 'animales' => $vacas])


                </div>

                <!-- Selección de la pajilla -->
                <div class="mb-3">
                    <label for="id_pajilla" class="form-label">Seleccione la Pajilla</label>
                    <select class="form-control" id="id_pajilla" name="id_pajilla" required>
                        <option value="">Seleccione una pajilla</option>
                        @foreach ($pajillas as $pajilla)
                            <option value="{{ $pajilla->id }}">
                                {{ $pajilla->nombre_reproductor }} (Cantidad: {{ $pajilla->cantidad }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha de inseminación -->
                <div class="mb-3">
                    <label for="fecha_inseminacion" class="form-label">Fecha de Inseminación</label>
                    <input type="date" class="form-control" id="fecha_inseminacion" name="fecha_inseminacion" required>
                </div>

                <!-- Botón de envío -->
              <button type="submit" class="btn btn-primary" id="enviarinseminacion">Registrar Inseminación</button>
            </form>
        </div>
    </div>
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
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('#inseminacionForm');
        form.addEventListener('submit', async (event) => {
            event.preventDefault(); // Evitar el envío tradicional del formulario
            console.log('Formulario enviado, procesando...');
            const formData = new FormData(form);
            const submitButton = document.querySelector('#enviarinseminacion');

            console.log('Datos enviados:', Object.fromEntries(formData.entries()));
            submitButton.disabled = true; // Desactivar el botón mientras se procesa
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                console.log('Respuesta recibida:', response);

                const data = await response.json();

                console.log('Datos JSON recibidos:', data);

                if (response.ok) {
                    console.log('Solicitud procesada correctamente.');
                    showAlert('success', data.success); // Mostrar mensaje de éxito
                    form.reset(); // Limpiar el formulario
                } else {
                    console.warn('Error en la solicitud:', data.error);
                    showAlert('danger', data.error); // Mostrar mensaje de error
                }
            } catch (error) {
                console.error('Error de red o servidor:', error);
                showAlert('danger', 'Error al procesar la solicitud. Intente nuevamente.');
            } finally {
                console.log('Procesamiento finalizado, reactivando el botón.');
                submitButton.disabled = false; // Reactivar el botón
            }
        });

        function showAlert(type, message) {
            console.log(`Mostrando alerta [${type}]:`, message);

            const alertBox = document.createElement('div');
            alertBox.className = `alert alert-${type}`;
            alertBox.textContent = message;
            alertBox.style.position = 'fixed';
            alertBox.style.top = '20px';
            alertBox.style.right = '20px';
            alertBox.style.zIndex = '1050';
            alertBox.style.minWidth = '300px';

            document.body.appendChild(alertBox);

            setTimeout(() => {
                console.log('Alerta eliminada.');
                alertBox.remove();
            }, 4000); // Desaparece después de 4 segundos
        }
    });
</script>


@endsection
