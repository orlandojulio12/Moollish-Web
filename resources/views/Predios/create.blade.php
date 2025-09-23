@extends('layouts')

@section('template_title')
    {{ __('Create') }} Propietario
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('predios.index') }}"><i class="bi bi-arrow-left"
                        style="margin-right: 10px; font-size: 24px; color: black;"></i></a><!-- Añade un margen para espaciar el ícono -->
                <h5>Crear Predios</h5>
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

    /* Estilos para alertas flotantes */
    .alert-floating {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 4px;
        padding: 15px 20px;
        display: none;
    }

    .alert-success-custom {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger-custom {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .alert-warning-custom {
        background-color: #fff3cd;
        border-color: #ffecb5;
        color: #856404;
    }

    /* Spinner de carga */
    .spinner {
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
<style>

    .selected-propietarios-container {
            border: 1px solid #e5e7eb;
            padding: 10px;
            margin: 10px 0px;
            border-radius: 4px;
            display: flex;
            flex-wrap: wrap;
        }

        .icon-size-custom {
            font-size: 15px;
            margin: 1px 0px 0px 5px;
        }

        .no-selected {
            background: #6c757d12;
            border: 1px dotted lightgray;
            border-radius: 4px;
            padding: 5px 10px;
            color: #acacac;

        }

        .selected-propietario {
            background: #e49b392e;
            padding: 8px 10px;
            border: 1px solid #e49b39;
            border-radius: 4px;
            color: #9d6f19;
            display: flex;
            flex-direction: row;
            align-items: center;
            margin: 5px;
        }

    </style>
@endsection

@section('content')
    <!-- Alertas flotantes -->
    <div class="alert-floating alert-success-custom" id="successAlert">
        <strong>¡Éxito!</strong> <span id="successMessage"></span>
    </div>
    <div class="alert-floating alert-danger-custom" id="errorAlert">
        <strong>Error:</strong> <span id="errorMessage"></span>
    </div>
    <div class="alert-floating alert-warning-custom" id="warningAlert">
        <strong>¡Advertencia!</strong> <span id="warningMessage"></span>
    </div>

    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">
                <form method="POST" id="predioForm" action="{{ route('predios.store') }}" role="form" enctype="multipart/form-data">
                    @csrf
                    @include('Predios.form')

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <span id="submitText">Guardar Predio</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="{{ asset('js/indexedDB-config.js') }}"></script> --}}{{-- Eliminada referencia a config offline --}}

    <script>
        // Función para mostrar alertas (se mantiene)
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success-custom' :
                            type === 'warning' ? 'alert-warning-custom' :
                            'alert-danger-custom';
            const alertId = type === 'success' ? '#successAlert' :
                        type === 'warning' ? '#warningAlert' :
                        '#errorAlert';
            const messageId = type === 'success' ? '#successMessage' :
                            type === 'warning' ? '#warningMessage' :
                            '#errorMessage';

            // Asegurar que el elemento existe antes de intentar actualizarlo
             if ($(alertId).length && $(messageId).length) {
                 $(messageId).html(message);
                 $(alertId).fadeIn().delay(5000).fadeOut();
             } else {
                 // Fallback si el elemento no existe (ej. durante desarrollo)
                 console.warn(`Elemento de alerta no encontrado: ${alertId}`);
                 alert(`${type.toUpperCase()}: ${message}`); // Usar alert nativo como último recurso
             }
        }

        // Función para resetear el botón (se mantiene)
        function resetSubmitButton() {
            $('#submitBtn').prop('disabled', false);
            $('#submitText').html('Guardar Predio');
        }

        $(document).ready(function() {

            // Envío del formulario (simplificado para solo online)
            $('#predioForm').submit(function(e) {
                e.preventDefault();

                // Validar que se haya seleccionado al menos un propietario
                if ($('input[name="id_usuario[]"]').length === 0) {
                    showAlert('error', 'Debes seleccionar al menos un propietario.');
                    resetSubmitButton(); // Asegurarse que el botón se reactive
                    return;
                }

                // Desactivar botón de envío y mostrar loader
                $('#submitBtn').prop('disabled', true);
                $('#submitText').html('<span class="spinner"></span> Guardando...');

                // Recoger datos del formulario
                let formData = new FormData(this);

                // ONLINE: Enviar al servidor mediante AJAX
                console.log('Enviando predio al servidor...');
                $.ajax({
                    url: $(this).attr('action'),
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Respuesta del servidor:", response);
                        // Ahora SÍ analizamos la respuesta JSON
                        if (response.success) {
                            $('#predioForm')[0].reset();
                            // Limpiar visualmente los propietarios seleccionados
                            $('.selected-propietarios-container').empty().append('<span class="no-selected">Selecciona un propietario</span>');
                            $('#propietario_ option').prop('disabled', false); // Habilitar todas las opciones
                            $('#propietario_').val(''); // Resetear select
                            showAlert('success', response.message || 'Predio registrado correctamente'); // Usar mensaje del servidor

                            // Redireccionar después de 2 segundos manualmente
                            setTimeout(function() {
                                window.location.href = "{{ route('predios.index') }}";
                            }, 2000);
                        } else {
                            // Si success es false en la respuesta JSON
                            showAlert('error', response.message || 'Ocurrió un error inesperado.');
                            resetSubmitButton();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", status, error, xhr.responseText);
                        resetSubmitButton();
                        // Manejo de errores específico para respuesta JSON
                        let errorMsg = 'Ha ocurrido un error al procesar la solicitud. Verifique los datos e inténtelo de nuevo.';

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            // Error de validación con detalles JSON
                            const errors = xhr.responseJSON.errors;
                            let specificMessages = [];

                            if (errors.departamento) {
                                specificMessages.push('El campo Departamento es obligatorio.');
                            }
                            if (errors.municipio) {
                                specificMessages.push('El campo Municipio es obligatorio.');
                            }
                             // Puedes añadir más campos requeridos aquí si es necesario
                             // if (errors.cod_predio) { specificMessages.push('El Código del Predio es obligatorio y único.'); }
                             // if (errors.id_usuario) { specificMessages.push('Debe seleccionar al menos un propietario.'); }

                            if (specificMessages.length > 0) {
                                errorMsg = specificMessages.join('<br>');
                            } else {
                                // Si hay otros errores de validación no específicos
                                errorMsg = xhr.responseJSON.message || 'Error de validación. Por favor revise los campos.';
                                // Opcional: listar todos los errores
                                // const allErrors = Object.values(errors).flat();
                                // errorMsg = allErrors.join('<br>');
                            }
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            // Otro tipo de error JSON con mensaje (ej. error 500)
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 0) {
                             errorMsg = 'No se pudo conectar con el servidor. Verifique su conexión a internet.';
                        }
                        // Para otros errores (ej. 403 Forbidden, 404 Not Found), el mensaje genérico puede ser suficiente

                        showAlert('error', errorMsg);
                    }
                });
            });
        });
    </script>
@endsection
