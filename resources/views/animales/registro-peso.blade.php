@extends('layouts')

@section('title')
    Registro de Peso
@endsection

@section('styles')
<style>
    .card-custom {
        border-radius: 8px;
        background: white;
        padding: 30px;
        border: 1px solid #eaebef;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .required-field::after {
        content: " *";
        color: red;
    }

    .btn-primary {
        background-color: #e49b39;
        border-color: #e49b39;
    }

    .btn-primary:hover {
        background-color: #c88428;
        border-color: #c88428;
    }

    .form-control:focus, .form-select:focus {
        border-color: #e49b39;
        box-shadow: 0 0 0 0.25rem rgba(228, 155, 57, 0.25);
    }

    /* Indicador de estado offline */
    .offline-badge {
        background-color: #dc3545;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-left: 10px;
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
                <h3 class="cumb active-tab"> Pesaje </h3>
            </div>
            <hr>
        </div>
        <h2 class="mb-4">Registro de Peso
            <span id="offline-info" class="offline-badge" style="display: none;">
                <i class="fas fa-wifi-slash"></i> Modo Offline
            </span>
        </h2>

        <form id="formPeso" action="{{ route('pesaje.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_animal_pesaje" class="form-label required-field">Animal</label>
                    <select class="form-select" id="id_animal_pesaje" name="id_animal_pesaje" required>
                        <option value="">Seleccione un animal</option>
                        @if(isset($animales))
                            @foreach($animales as $animal)
                                <option value="{{ $animal->id_animal }}" {{ (request('animal_id') == $animal->id_animal) ? 'selected' : '' }}>
                                     {{ $animal->codigo }} - {{ $animal->nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="fecha_pesaje" class="form-label required-field">Fecha de Pesaje</label>
                    <input type="date" class="form-control" id="fecha_pesaje" name="fecha_pesaje" required value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="peso" class="form-label required-field">Peso (kg)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="peso" name="peso" required>
                </div>

                <div class="col-md-6">
                    <label for="metodo_pesaje" class="form-label">Método de Pesaje</label>
                    <select class="form-select" id="metodo_pesaje" name="metodo_pesaje">
                        <option value="bascula">Báscula</option>
                        <option value="cinta">Cinta Métrica</option>
                        <option value="estimacion">Estimación Visual</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
               <a href="{{ route('produccionAnimal') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Cancelar
                </a>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <span id="submitText">Registrar Peso</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Alertas flotantes -->
    <div class="alert alert-success" id="successAlert" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999;">
        <strong>¡Éxito!</strong> <span id="successMessage"></span>
    </div>

    <div class="alert alert-danger" id="errorAlert" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999;">
        <strong>Error:</strong> <span id="errorMessage"></span>
    </div>

    <div class="alert alert-warning" id="warningAlert" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999;">
        <strong>¡Advertencia!</strong> <span id="warningMessage"></span>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertId = type === 'success' ? '#successAlert' :
                      type === 'warning' ? '#warningAlert' :
                      '#errorAlert';

        const messageId = type === 'success' ? '#successMessage' :
                       type === 'warning' ? '#warningMessage' :
                       '#errorMessage';

        $(messageId).html(message);
        $(alertId).fadeIn().delay(5000).fadeOut();
    }

    $(document).ready(function() {
        // Verificar si hay un animal temporal seleccionado en localStorage
        const animalTempId = localStorage.getItem('ultimo_animal_temp_id');
        const animalInfo = localStorage.getItem('ultimo_animal_info') ? JSON.parse(localStorage.getItem('ultimo_animal_info')) : null;

        // Verificar estado de conexión
        if (!navigator.onLine) {
            $('#offline-info').show();
        }

        // Si existe un animal temporal y estamos offline, mostrar información al usuario
        if (animalTempId && !navigator.onLine) {
            $('#id_animal_pesaje').empty(); // Limpiar opciones existentes
            $('#id_animal_pesaje').append(`<option value="temp_${animalTempId}" selected>${animalInfo?.nombre || 'Animal offline'} (pendiente de sincronización)</option>`);
            $('#id_animal_pesaje').attr('disabled', 'disabled');

            // Mostrar alerta informativa
            showAlert('warning', 'Estás trabajando con un animal que aún no ha sido sincronizado.');
        }

        // Manejar envío del formulario
        $('#formPeso').submit(async function(e) {
            e.preventDefault();

            // Desactivar botón de envío
            $('#submitBtn').prop('disabled', true);
            $('#submitText').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');

            // Obtener datos del formulario
            const formData = new FormData(this);

            // Verificar estado de la conexión
            if (!navigator.onLine) {
                try {
                    // Si hay un animal temporal, usar su ID
                    const animalTempId = localStorage.getItem('ultimo_animal_temp_id');

                    if (!animalTempId) {
                        throw new Error('No se encontró información del animal para registrar el peso');
                    }

                    // Guardar peso offline con relación al animal
                    // Usamos window.OfflineManager que está definido en offline-manager.js
                    if (typeof window.OfflineManager !== 'undefined' && window.OfflineManager.guardarPeso) {
                        const result = await window.OfflineManager.guardarPeso(formData, animalTempId);

                        // Resetear formulario
                        $('#formPeso')[0].reset();

                        // Mostrar mensaje de éxito
                        showAlert('success', 'Peso guardado localmente. Se sincronizará cuando haya conexión.');

                        // Mantener la fecha actual
                        $('#fecha_pesaje').val(new Date().toISOString().substr(0, 10));
                    } else {
                        throw new Error('El administrador offline no está disponible');
                    }

                } catch (error) {
                    console.error('Error al guardar peso offline:', error);
                    showAlert('danger', 'Error: ' + error.message);
                }

                // Restaurar botón
                $('#submitBtn').prop('disabled', false);
                $('#submitText').text('Registrar Peso');

                return;
            }

            // Si hay conexión, enviar al servidor normalmente
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showAlert('success', 'Peso registrado correctamente');

                    // Resetear formulario pero mantener el animal seleccionado
                    const animalId = $('#id_animal_pesaje').val();
                    $('#formPeso')[0].reset();
                    $('#id_animal_pesaje').val(animalId);
                    $('#fecha_pesaje').val(new Date().toISOString().substr(0, 10));

                    // Restaurar botón
                    $('#submitBtn').prop('disabled', false);
                    $('#submitText').text('Registrar Peso');
                },
                error: function(xhr) {
                    $('#submitBtn').prop('disabled', false);
                    $('#submitText').text('Registrar Peso');

                    let errorMsg = 'Error al registrar el peso';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    showAlert('danger', errorMsg);
                }
            });
        });

        // Event listeners para conexión
        window.addEventListener('online', function() {
            $('#offline-info').hide();
            showAlert('success', 'Conexión restablecida. Los datos pendientes se sincronizarán automáticamente.');

            // Intentar sincronizar cuando hay conexión
            if (typeof window.OfflineManager !== 'undefined' && window.OfflineManager.sincronizarTodo) {
                window.OfflineManager.sincronizarTodo();
            }
        });

        window.addEventListener('offline', function() {
            $('#offline-info').show();
            showAlert('warning', 'Sin conexión. Los registros se guardarán localmente.');
        });
    });
</script>
@endsection
