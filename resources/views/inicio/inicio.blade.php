@extends('layouts')

@section('title')
    Moollish - Inicio
@endsection
@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
        }

        .light-text {
            font-size: 11px;
            font-weight: 300;
        }

        .grid-card {
            border: 2px solid #ffffff;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            background: linear-gradient(297deg, rgb(201 121 23) 0%, rgba(228, 155, 57, 1) 100%);
            color: white;
            width: 225px;
            margin: 15px 15px 0px 0px;
            cursor: pointer;
            transition: 200ms cubic-bezier(0.075, 0.82, 0.165, 1);
            justify-content: center;
            text-align: center;
            align-items: center;
        }


        .grid-card:hover {
            transform: scale(1.05);
            box-shadow: 1px 1px 1px #0003;

        }

        .title-card {
            font-size: 16px;
            font-weight: 800;
        }

        /* End styles */
        .grid-playground {
            display: grid;
            gap: 15px;
            padding: 15px;
            grid-template-columns: repeat(2, 1fr);
        }
        @media (width < 500px) {
            .grid-playground {
                gap: 15px !important;
            }
        }
        /* Tablet - 3 columnas */
        @media (min-width: 768px) {
            .grid-playground {
                grid-template-columns: repeat(3, 1fr);

            }
        }

        /* Laptop - 4 columnas */
        @media (min-width: 1024px) {
            .grid-playground {
                grid-template-columns: repeat(4, 1fr);
            }
        }


        .title-card-end {
            font-size: 16px;
            font-weight: 800;
        }

        .description {
            color: gray;
            font-weight: 400;
            line-height: 110%;
            margin: 10px 0px 0px;

        }

        .grid-card-end {
            /* Elimina el ancho fijo y márgenes */
            width: 100%;
            margin: 0;
            /* Mantén el resto de estilos */
            border: 1px solid #eaebef;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            background: white;
            color: rgba(228, 155, 57, 1);
            cursor: pointer;
            transition: 200ms cubic-bezier(0.075, 0.82, 0.165, 1);
            justify-content: center;
            text-align: center;
            align-items: center;
        }

        .grid-card-end:hover {
            transform: scale(1.05);

        }

        .novilla {
            background-image: url('../../img/inicio/novilla.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .predio {
            background-image: url('../../img/inicio/predio.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }


        .potrero {
            background-image: url('../../img/inicio/Potrero.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .veterinario {
            background-image: url('../../img/inicio/veterinario.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        /* End styles */

        .novilla-orange {
            background-image: url('../../img/inicio/novillaOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .potrero-orange {
            background-image: url('../../img/inicio/potreroOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .veterinario-orange {
            background-image: url('../../img/inicio/veterinarioOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .caracterizacion-orange {
            background-image: url('../../img/inicio/caracterizacionOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .economia-orange {
            background-image: url('../../img/inicio/economiaOrange3.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .listados-orange {
            background-image: url('../../img/inicio/listadosOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .insumos-orange {
            background-image: url('../../img/inicio/insumosOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .inventario-orange {
            background-image: url('../../img/inicio/inventario_animalOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .description {
            color: gray;
            font-weight: 400;
            line-height: 110%;
            margin: 10px 0px 0px;

        }

        .image-container {
            width: auto;
            height: 200px;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
        }

        .fade-image {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .fade-image.active {
            opacity: 1;
        }


        /* Estilos para la alerta de verificación de correo */

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

    {{-- Alerta de verificación de correo electrónico --}}
   {{--  @if(auth()->user() && auth()->user()->email_verified_at === null)
        <div class="alert-verification">
            <div class="alert-verification-content">
                <div class="alert-verification-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="#E49B39" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2h-2v-2zm0-8h2v6h-2V7z"/>
                    </svg>
                </div>
                <div class="alert-verification-text">
                    <span>Aún no has verificado tu correo electrónico, revisa tu bandeja o </span>
                    <a href="#" id="resendVerificationEmail">reenvía el correo de verificación</a>
                </div>
            </div>
        </div>
    @endif --}}

  {{--   <div class="card-custom" style="margin: 0px 0px 20px 0px; padding: 0px; ">
        <div class="image-container" style="height: 150px;">
            <div class="fade-image" style="background-image: url('../img/andy-kelly-5APBLfC2hUs-unsplash.jpg');"></div>
            <div class="fade-image" style="background-image: url('../img/ab-RgAPSLYHWG0-unsplash.jpg');"></div>
            <div class="fade-image" style="background-image: url('../img/42782.jpg');"></div>
            <div class="fade-image" style="background-image: url('../img/10813.webp');"></div>
        </div>
    </div> --}}

    <div class="card-custom">
        <div class="header-grid">
            @if ($user->role->name === 'propietario')
                <h3>¡Bienvenido a <span
                        style="  background: linear-gradient(to right, rgba(201, 121, 23, 1) 30%, rgba(250, 156, 43, 1) 100%);
                                -webkit-background-clip: text;
                                -webkit-text-fill-color: transparent;">Moollish</span>,
                    {{ $user->name }}!</h3>
            @elseif ($user->role->name === 'admin')
                <h3> Iniciaste sesión como administrador</h3>
            @endif
            <hr>
        </div>

        @if (!$configurado)
            {{-- Grid de "Primeros pasos" cuando aún no se han configurado todos los registros --}}
            <div class="grid-start">
                <span>¿Aún no has configurado Moollish? Empecemos con los primeros pasos.</span>
                <div class="grid-playground">
                    {{-- Tarjeta para crear el primer predio --}}
                    @if ($predioCount == 0)
                        <a href="/predios/create">
                            <div class="grid-card">
                                <span class="light-text">Primeros pasos</span>
                                <span class="title-card">1. Crea tu primera finca o predio</span>
                                <div class="predio"></div>
                                <span>Visualiza y administra tus ubicaciones</span>
                            </div>
                        </a>
                    @endif

                    {{-- Tarjeta para crear los lotes/potreros: se muestra si ambos no existen --}}
                    @if ($loteCount == 0 && $potreroCount == 0)
                        <a href="{{ route('ubicacion.index') }}">
                            <div class="grid-card">
                                <span class="light-text">Primeros pasos</span>
                                <span class="title-card">2. Crea tus primeros lotes o potreros</span>
                                <div class="potrero"></div>
                                <span>Visualiza y administra tus ubicaciones</span>
                            </div>
                        </a>
                    @endif

                    {{-- Tarjeta para registrar animales --}}
                    @if ($animalCount == 0)
                        <a href="{{ route('inventario.index') }}">
                            <div class="grid-card">
                                <span class="light-text">Primeros pasos</span>
                                <span class="title-card">3. Registra tus primeros animales</span>
                                <div class="novilla"></div>
                                <span>Registra su información y eventos</span>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        @else
            {{-- Grid completo cuando ya se han registrado todos los datos básicos --}}
            <div class="grid-end">
                <span>Administra tu finca, al completo y sin restricciones!</span>
                <div class="grid-playground">
                    <a href="{{ route('caracterizacion') }}">
                        <div class="grid-card-end">
                            <span class="light-text">Inicio</span>
                            <span class="title-card-end">Caracterización</span>
                            <div class="caracterizacion-orange"></div>
                            <span class="description">
                                Caracteriza tus predios y configura tu entorno virtual
                            </span>
                        </div>
                    </a>
                     <a href="{{ route('gastos.show') }}">
                        <div class="grid-card-end">
                            <span class="light-text">Inicio</span>
                            <span class="title-card-end">Economia</span>
                            <div class="economia-orange"></div>
                            <span class="description">
                                Mantén un control de tus gastos en tiempo real
                            </span>
                        </div>
                    </a>
                    <a href="{{ route('registros') }}">

                        <div class="grid-card-end">
                            <span class="light-text">Inicio</span>
                            <span class="title-card-end">Registros</span>
                            <div class="inventario-orange"></div>
                            <span class="description">
                                Registra todos los eventos de tus animales
                            </span>
                        </div>
                    </a>
                    <a href="{{ route('listados') }}">

                        <div class="grid-card-end">
                            <span class="light-text">Inicio</span>
                            <span class="title-card-end">Listados</span>
                            <div class="listados-orange"></div>
                            <span class="description">
                                Revisa tus listas, históricos y demás.
                            </span>
                        </div>
                    </a>

                    <a href="{{ route('insumos.index') }}">
                        <div class="grid-card-end">
                            <span class="light-text">Inicio</span>
                            <span class="title-card-end">Insumos</span>
                            <div class="insumos-orange">

                            </div>
                            <span class="description">
                               Gestiona tus insumos y materiales de manera eficiente
                            </span>
                        </div>
                    </a>

                </div>
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   {{--  <script>
        let currentIndex = 0;
        const images = document.querySelectorAll('.fade-image');
        const totalImages = images.length;

        function changeImage() {
            // Quita la clase activa de la imagen actual
            images[currentIndex].classList.remove('active');

            // Calcula la siguiente imagen
            currentIndex = (currentIndex + 1) % totalImages;

            // Añade la clase activa a la nueva imagen
            images[currentIndex].classList.add('active');
        }

        // Cambia la imagen cada 5 segundos
        setInterval(changeImage, 5000);

        // Inicia mostrando la primera imagen
        images[currentIndex].classList.add('active');

        // Script para el reenvío del correo de verificación
        $(document).ready(function() {
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
                console.log('Token CSRF encontrado:', csrfToken ? 'Sí' : 'No', 'Longitud:', csrfToken ? csrfToken.length : 0);

                $.ajax({
                    url: '/resend-verification-email',
                    method: 'POST',
                    data: {
                        email: '{{ auth()->user()->email }}'
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
                            console.log('La respuesta parece ser HTML, no JSON. Enviando mensaje genérico.');
                            message = '¡Correo de verificación reenviado! Revisa tu bandeja de entrada.';
                        }

                        // Mostrar mensaje de éxito
                        const verificationDiv = $('.alert-verification-text');
                        verificationDiv.html('<span style="color: #28a745;">' + message + '</span>');
                        console.log('Mensaje de éxito mostrado');

                        // Restaurar el enlace después de 5 segundos
                        setTimeout(function() {
                            console.log('Restaurando enlace de reenvío después de timeout');
                            verificationDiv.html('<span>Aún no has verificado tu correo electrónico, revisa tu bandeja o </span><a href="#" id="resendVerificationEmail">reenvía el correo de verificación</a>');
                            // Volver a enlazar el evento click
                            $('#resendVerificationEmail').on('click', arguments.callee);
                        }, 5000);
                    },
                    error: function(xhr) {
                        console.error('Error en la solicitud AJAX:', xhr);
                        console.log('Estado HTTP:', xhr.status);
                        console.log('Respuesta:', xhr.responseJSON);

                        // Mostrar mensaje de error
                        let errorMessage = 'Ocurrió un error al reenviar el correo. Inténtalo nuevamente.';

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
                        $('.alert-verification-text').html('<span style="color: #dc3545;">' + errorMessage + '</span>');

                        // Restaurar el enlace después de 5 segundos
                        setTimeout(function() {
                            console.log('Restaurando enlace de reenvío después de error');
                            $('.alert-verification-text').html('<span>Aún no has verificado tu correo electrónico, revisa tu bandeja o </span><a href="#" id="resendVerificationEmail">reenvía el correo de verificación</a>');
                            // Volver a enlazar el evento click
                            $('#resendVerificationEmail').on('click', arguments.callee);
                        }, 5000);
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
    </script> --}}
@endsection
