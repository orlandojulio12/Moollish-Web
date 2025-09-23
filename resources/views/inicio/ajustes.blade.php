@extends('layouts')

@section('title')
    Ajustes
@endsection

@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
            margin-bottom: 20px;
        }

        /* Banner */
        .banner {
            border-radius: 8px;
            height: 200px;
            background-image: url('../img/andy-kelly-5APBLfC2hUs-unsplash.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Círculo de usuario */
        .user-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #fff;
            position: relative;
            left: 7%;
            transform: translateX(-50%);
            bottom: 60px;
            background: #ccc;
        }

        .user-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .tabs button {
            background: none;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            color: #333;
        }

        .form-select {
            font-size: 14px;

        }

        .tabs button.active {
            border-bottom: 2px solid #4CAF50;
            font-weight: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Link card */
        .links-card a {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        .membership-status {
            margin: 0px 20px;
            border: 1px solid rgba(228, 155, 57, 1);
            border-radius: 6px;
            padding: 5px;
            display: flex;
            /*   background: #8000801f; */
            transition: opacity 0.3s ease;
            transition: 100ms;
            align-items: center;
            justify-content: center;
            color: rgba(228, 155, 57, 1);
        }

        .email-container {
            position: relative;
        }

        .verification-status {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            font-weight: 600;
        }

        .verification-status.verified {
            color: #28a745;
        }

        .verification-status.not-verified {
            color: #E49B39;
        }

        input[readonly] {
            background-color: #fff !important;
            cursor: default;
        }

        /* Estilos para la alerta personalizada */
        #alertaMensaje {
            border-radius: 4px;
            border: none;
            border-left: 4px solid #E49B39;
            padding: 10px 15px;
        }

        #alertaMensaje .alert-verification-content {
            display: flex;
            align-items: center;
        }

        #alertaMensaje .alert-verification-icon {
            margin-right: 10px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        #alertaMensaje .alert-verification-text {
            font-size: 14px;
            color: #333;
            line-height: 1.4;
        }
        @media (width < 600px)
        {
            .user-circle {
                left: 15%;
            }
        }
    </style>
@endsection

@section('content')
    @if (session('error'))
        <div class="alert alert-danger" style="border-radius: 4px; border: none; border-left: 4px solid #dc3545; padding: 10px 15px;">
            <div style="display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="margin-right: 10px; flex-shrink: 0;">
                    <path fill="#dc3545" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2h-2v-2zm0-8h2v6h-2V7z"/>
                </svg>
                <span style="font-size: 14px; color: #333; line-height: 1.4;">{{ session('error') }}</span>
            </div>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" style="border-radius: 4px; border: none; border-left: 4px solid #28a745; padding: 10px 15px;">
            <div style="display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="margin-right: 10px; flex-shrink: 0;">
                    <path fill="#28a745" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2h-2v-2zm0-8h2v6h-2V7z"/>
                </svg>
                <span style="font-size: 14px; color: #333; line-height: 1.4;">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Card 1: Banner con usuario --}}
    <div class="card-custom">
        <div class="banner">
            <!-- Banner con imagen de fondo -->
        </div>
        <!-- Círculo centrado, superpuesto al banner -->
        <div class="user-circle" style="margin: 0 15px;">
            <!-- Aquí se muestra la imagen del usuario; ajusta la ruta según corresponda -->
            @if (Auth::user()->profile_photo_path === null)
                <span class="material-symbols-outlined" style="    margin: 44px;">
                    person
                </span>
            @else
                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto de usuario">
            @endif
        </div>
        <div style="    display: flex;
    align-items: baseline; margin: -50px 0px 0px;">
            <h2>{{ $user->name }}</h2>
            @if ($user->membership && $user->membership->isActive())
                <div class="membership-status">
                    <span class="material-symbols-outlined" style="height: 22px">
                        diamond
                    </span>
                </div>
            @else
            @endif
        </div>
    </div>

    <!-- Alerta para mensajes -->
    <div id="alertaMensaje" class="alert-verification d-none" role="alert"
        style="transition: opacity 0.5s ease-in-out; margin-bottom: 20px;">
        <div class="alert-verification-content">
            <div class="alert-verification-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" id="alertaIcono">
                    <path fill="#E49B39"
                        d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2h-2v-2zm0-8h2v6h-2V7z"
                        id="alertaIconoPath" />
                </svg>
            </div>
            <div class="alert-verification-text" id="alertaMensajeTexto">
            </div>
        </div>
    </div>

    {{-- Card 2: Formulario con tabs --}}
    <div class="card-custom" style="margin-top: 20px;"> {{-- Ajuste de margin-top para que no se superponga el círculo --}}
        <!-- Navegación de Tabs -->
        <div class="tabs">
            <button type="button" class="tab-link active" data-tab="perfil">Mi perfil</button>
            <button type="button" class="tab-link" data-tab="parametros">Mis parámetros</button>
        </div>
        <!-- Contenido de Tabs -->
        <div id="perfil" class="tab-content active">
            <form id="perfilForm" action="{{ route('users.update', $user->id) }}" method="POST">
                {{-- Se muestra el formulario de ajustes de perfil --}}
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="name">Nombre completo</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}">
                </div>

                <div class="form-group">
                    <label for="id_rol">Rol</label>
                    <select class="form-select" name="id_rol" id="id_rol">
                        <option value="{{ $user->id_rol }}" selected>
                            @if ($user->id_rol === 1)
                                Administrador
                            @elseif($user->id_rol === 2)
                                Encuestador
                            @elseif($user->id_rol === 3)
                                Propietario
                            @else
                                {{ $user->id_rol }} <!-- En caso de que haya un valor inesperado -->
                            @endif
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="email-container position-relative">
                        <input type="email" id="email" name="email" class="form-control pr-5"
                            value="{{ $user->email }}" readonly>
                        @if ($user->email_verified_at)
                            <span class="verification-status verified">Verificado</span>
                        @else
                            <span class="verification-status not-verified">No verificado</span>
                        @endif
                    </div>
                    @if (!$user->email_verified_at)
                        <small class="form-text text-muted">
                            Para verificar tu correo electrónico, revisa tu bandeja de entrada o
                            <a href="#" id="resendVerificationEmail" style="color: #E49B39; font-weight: 600;">reenvía
                                el correo de verificación</a>
                        </small>
                    @endif
                </div>
                <div class="form-group">
                    <label for="tipo_documento">Tipo de documento</label>
                    <input type="text" id="tipo_documento" name="tipo_documento" class="form-control"
                        value="{{ $user->tipo_documento }}" disabled>
                </div>

                <div class="form-group">
                    <label for="documento">Documento</label>
                    <input type="text" id="documento" name="documento" class="form-control"
                        value="{{ $user->documento }}">
                </div>

                {{-- Nueva sección para Régimen Tributario --}}
                <div class="form-group">
                    <label for="regimen">Régimen Tributario</label>
                    <select class="form-select" id="regimen" name="regimen">
                        <option value="" {{ is_null($user->regimen) ? 'selected' : '' }} disabled>-- Seleccione --</option>
                        <option value="comun" {{ $user->regimen == 'comun' ? 'selected' : '' }}>Común</option>
                        <option value="simplificado" {{ $user->regimen == 'simplificado' ? 'selected' : '' }}>Simplificado</option>
                    </select>
                    <small>Selecciona <strong>Régimen Común</strong> si eres responsable del IVA (19%) o de otros impuestos al consumo. Selecciona <strong>Régimen Simplificado</strong> si no eres responsable del IVA. Esta selección puede afectar la forma en que se calculan o muestran ciertos valores en la aplicación.</small>
                </div>
                {{-- Fin nueva sección --}}

                <br>
                <button type="submit" class="btn btn-primary">Guardar Perfil</button>
            </form>
        </div>
        <div id="parametros" class="tab-content">
            <form id="parametrosForm" action="{{ route('parametros.guardar') }}" method="POST">
                @csrf
                @foreach ($predios as $predio)
                    <h4>{{ $predio->nombre_predio }}</h4>

                    @php
                        // Configuraciones existentes para transición reproductiva
                        $paramHembra = $predio->parametros->firstWhere('estado_actual_id', 4);
                        $paramMacho = $predio->parametros->firstWhere('estado_actual_id', 14);
                        // Obtener el parámetro de días de gestación (relación que retorna el registro de parametro_dias_gestacion)
                        $paramGestacion = $predio->parametroDiasGestacion;
                        // Verificar si el usuario autenticado es el creador del predio
                        $canEdit = $predio->created_by == auth()->id();
                        // Obtener el nombre del creador o un valor por defecto
                        $creador = optional($predio->createdBy)->name ?: 'Moollish';
                    @endphp

                    @if (!$canEdit)
                        <div class="alert alert-warning">
                            No tienes permiso para editar estos parámetros. Consulta al administrador
                            "<strong>{{ $creador }}</strong>" para ajustar estos parámetros.
                        </div>
                    @endif

                    <!-- Parámetros para transición reproductiva -->
                    <div class="form-group">
                        <label for="transicionHembra_{{ $predio->id }}">
                            Días para transición reproductiva (hembra de levante a novilla de vientre)
                        </label>
                        <input type="number" id="transicionHembra_{{ $predio->id }}"
                            name="parametros[{{ $predio->id }}][transicionHembra]" class="form-control"
                            placeholder="Ingrese días" value="{{ $paramHembra ? $paramHembra->dias_transicion : '' }}"
                            @if (!$canEdit) readonly @endif>
                    </div>
                    <div class="form-group">
                        <label for="transicionMacho_{{ $predio->id }}">
                            Días para transición reproductiva (macho de levante a macho de ceba)
                        </label>
                        <input type="number" id="transicionMacho_{{ $predio->id }}"
                            name="parametros[{{ $predio->id }}][transicionMacho]" class="form-control"
                            placeholder="Ingrese días" value="{{ $paramMacho ? $paramMacho->dias_transicion : '' }}"
                            @if (!$canEdit) readonly @endif>
                    </div>

                    <!-- Nuevo parámetro: Días de gestación -->
                    <div class="form-group">
                        <label for="diasGestacion_{{ $predio->id }}">Días de gestación</label>
                        <input type="number" id="diasGestacion_{{ $predio->id }}"
                            name="parametros_dias_gestacion[{{ $predio->id }}]" class="form-control"
                            placeholder="Ingrese días de gestación"
                            value="{{ $paramGestacion ? $paramGestacion->dias_gestacion : 280 }}"
                            @if (!$canEdit) readonly @endif>
                    </div>

                    <hr>
                @endforeach

                @if (
                    $predios->contains(function ($predio) {
                        return $predio->created_by == auth()->id();
                    }))
                    <button type="submit" class="btn btn-primary">Guardar Parámetros</button>
                @endif
            </form>
        </div>





    </div>

    {{-- Card 3: Links legales --}}
    <div class="card-custom">
        <h3>Información legal</h3>
        <hr>
        <div class="links-card">
            <a href="/politica-uso" target="_blank" style="text-decoration:underline">Politica de tratamiento de
                datos</a>
            <a href="/informacion-legal" target="_blank" style="text-decoration:underline">Información legal</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Script para cambiar de tabs
            $('.tab-link').click(function() {
                var tabID = $(this).data('tab');

                // Remover clase active de todos los botones y contenidos
                $('.tab-link').removeClass('active');
                $('.tab-content').removeClass('active');

                // Agregar active al botón seleccionado y al contenido correspondiente
                $(this).addClass('active');
                $('#' + tabID).addClass('active');
            });

            // Función para mostrar mensajes
            function mostrarMensaje(mensaje, tipo) {
                // tipo puede ser 'success' o 'danger'
                const alerta = $('#alertaMensaje');
                const alertaTexto = $('#alertaMensajeTexto');
                const alertaIconoPath = $('#alertaIconoPath');

                // Configurar el texto
                alertaTexto.html('<span>' + mensaje + '</span>');

                // Configurar el color según el tipo
                if (tipo === 'success') {
                    alerta.css('background-color', '#dcfce7');
                    alerta.css('border-left', '4px solid #16a34a');
                    alertaIconoPath.attr('fill', '#16a34a');
                    alertaIconoPath.attr('d',
                        'M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z'
                        );
                } else {
                    alerta.css('background-color', '#FEF3E4');
                    alerta.css('border-left', '4px solid #E49B39');
                    alertaIconoPath.attr('fill', '#E49B39');
                    alertaIconoPath.attr('d',
                        'M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2h-2v-2zm0-8h2v6h-2V7z'
                        );
                }

                // Mostrar la alerta con animación
                alerta.removeClass('d-none').hide().fadeIn();

                // Desplazarse hasta la alerta
                $('html, body').animate({
                    scrollTop: alerta.offset().top - 100
                }, 200);

                // Ocultar después de 3 segundos
                setTimeout(function() {
                    alerta.fadeOut(function() {
                        alerta.addClass('d-none');
                    });
                }, 3000);
            }

            // Script para el reenvío del correo de verificación
            $('#resendVerificationEmail').on('click', function(e) {
                e.preventDefault();
                console.log('Botón de reenvío de correo clickeado');

                // Mostrar indicador de carga
                const originalText = $(this).text();
                $(this).text('Enviando...');
                $(this).css('pointer-events', 'none');
                console.log('Estado del botón cambiado a "Enviando..."');

                // Realizar solicitud AJAX para reenviar el correo
                console.log('Iniciando solicitud AJAX a /resend-verification-email');
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                console.log('Token CSRF encontrado:', csrfToken ? 'Sí' : 'No', 'Longitud:', csrfToken ?
                    csrfToken.length : 0);

                $.ajax({
                    url: '/resend-verification-email',
                    method: 'POST',
                    data: {
                        email: '{{ $user->email }}'
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        console.log('Respuesta exitosa recibida:', response);

                        // Verificar si la respuesta es un objeto JSON o una cadena HTML
                        let message = '';
                        if (typeof response === 'object' && response.message) {
                            message = response.message;
                        } else if (typeof response === 'string') {
                            console.log(
                                'La respuesta parece ser HTML, no JSON. Enviando mensaje genérico.'
                                );
                            message =
                                '¡Correo de verificación reenviado! Revisa tu bandeja de entrada.';
                        }

                        // Usar la alerta en lugar del modal
                        mostrarMensaje(message, 'success');
                    },
                    error: function(xhr) {
                        console.error('Error en la solicitud AJAX:', xhr);
                        console.log('Estado HTTP:', xhr.status);
                        console.log('Respuesta:', xhr.responseJSON);

                        // Mostrar mensaje de error
                        let errorMessage =
                            'Ocurrió un error al reenviar el correo. Inténtalo nuevamente.';

                        try {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                // Intenta analizar el texto de respuesta como JSON
                                const responseObj = JSON.parse(xhr.responseText);
                                if (responseObj && responseObj.message) {
                                    errorMessage = responseObj.message;
                                }
                            }
                        } catch (e) {
                            console.error('Error al procesar la respuesta:', e);
                        }

                        console.log('Mensaje de error a mostrar:', errorMessage);

                        // Usar la alerta en lugar del modal
                        mostrarMensaje(errorMessage, 'danger');
                    },
                    complete: function() {
                        console.log('Solicitud AJAX completada, restaurando estado del botón');
                        // Restaurar el texto original
                        $('#resendVerificationEmail').text(originalText);
                        $('#resendVerificationEmail').css('pointer-events', 'auto');
                    }
                });
            });
        });
    </script>
@endsection
