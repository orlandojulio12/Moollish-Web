<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inicia sesión - Moollish</title>
    {{-- Icons google --}}
    <link rel="icon" type="image/x-icon" href="../assets/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            width: 100%;

        }

        /* CSS Reset */
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td,
        article,
        aside,
        canvas,
        details,
        embed,
        figure,
        figcaption,
        footer,
        header,
        hgroup,
        menu,
        nav,
        output,
        ruby,
        section,
        summary,
        time,
        mark,
        audio,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            vertical-align: baseline;
        }

        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        menu,
        nav,
        section {
            display: block;
        }

        body {
            line-height: 1;
        }

        ol,
        ul {
            list-style: none;
        }

        blockquote,
        q {
            quotes: none;
        }

        label {
            user-select: none;
        }

        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: '';
            content: none;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        * {
            font-family: "Plus Jakarta Sans", sans-serif;
        }


        /*   .image-container {
            background-image: url(../img/banner.webp);
            width: 60%;
            height: auto;
            margin: 0px 0px 0px 20px;
            border-radius: 10px;
            background-position: center;
            background-size: cover;
        } */

        .main {
            height: 100%;
            width: 100%;
        }

        .container-double {
            display: flex;
            padding: 10px;
            width: -webkit-fill-available;
            height: -webkit-fill-available;
            justify-content: center;
        }
        .form-recover {
            width: 32%;
            padding: 20px 3%;
            height: calc(100vh - 60px);
        }

        .form-login {
            width: 32%;
            padding: 20px 3%;
            height: calc(100vh - 60px);
        }

        .form-signup {
            width: 32%;
            padding: 20px 3%;
            overflow: auto;
            height: calc(100vh - 60px);
            scrollbar-width: none;
            scrollbar-color: #e49b3970 #ffffff;
        }

        /* Para navegadores basados en WebKit */
        .form-signup::-webkit-scrollbar {
            width: 10px;
        }

        .form-signup::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .form-signup::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #E49B39, #b37000);
            border-radius: 10px;
        }



        .logo-title {}

        .moollish {
            background-image: url(../img/moollish.png);
            width: 64px;
            height: 64px;
            background-size: contain;
        }

        .moollish-span {
            font-weight: 700;
            font-size: 30px;
        }

        .logo-moollish {}

        .logo-center {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .d-flex {
            display: flex;
            align-items: center;
            width: -webkit-fill-available;
        }

        .text-paragraph {
            color: #969696;
            margin: 15px 0px;
        }



        .main-form {
            margin: 50px 0px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .form-input {
            border: 1px solid #D8D9D9;
            background: white;
            border-radius: 8px;
            width: -webkit-fill-available;
            height: 48px;
            padding: 0px 15px;
            margin: 5px 0px 0px 0px;
        }

        input:focus {
            border: 2px solid #B57827 !important;
            outline-color: #B57827;
            color: #B57827;

        }

        input:focus-visible {
            border: 2px solid #B57827 !important;
            outline-color: #B57827;
            color: #B57827;

        }

        .input-password {
            width: -webkit-fill-available;
            margin: 10px 0px;
            display: flex;
            flex-direction: column;
            width: -moz-available;
        }

        .input-signup {
            width: -webkit-fill-available;
            margin: 10px 0px;
            display: flex;
            flex-direction: column;
            width: -moz-available;
        }

        .btn-login {
            background-color: #E49B39;
            color: white;
            display: flex;
            flex-direction: row;
            align-content: center;
            align-items: center;
            padding: 20px;
            height: 50px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            width: -webkit-fill-available;
            justify-content: center;
            margin: 20px 0px;
            cursor: pointer;
            transition: 50ms linear;
        }

        .btn-outline-login {
            color: #E49B39;
            background-color: white;
            display: flex;
            flex-direction: row;
            align-content: center;
            align-items: center;
            padding: 20px;
            height: 50px;
            font-size: 18px;
            border: 1px solid #E49B39;
            border-radius: 8px;
            width: -webkit-fill-available;
            justify-content: center;
            margin: 20px 0px;
            cursor: pointer;
            transition: 50ms linear;
        }

        .btn-outline-login:hover {
            transform: scale(1.05);
        }

        .btn-login:hover {
            transform: scale(1.05);
            background: #B57827;
        }

        .or {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
        }

        .or hr {
            width: -webkit-fill-available;
            color: #D9D9D9;
        }

        .or span {
            min-width: 50px;
            text-align: -webkit-center;
            color: #D9D9D9;

        }

        form {
            margin: 30px 0px 0px 0px;

        }

        label {
            font-size: 15px;

        }

        a {
            color: #B57827;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        input[type="checkbox"] {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        input[type="checkbox"]:checked {
            border-color: #b57827;
            background-color: #b57827;
        }

        input[type="checkbox"]:checked::before {
            content: '';
            position: absolute;
            top: 1px;
            left: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .chkcustom {
            margin-left: 10px;
            font-size: 14px;
            color: #333;
            cursor: pointer;
        }

        .card-header {
            padding: 0px 0px 20px 0px;
            border-bottom: 1px solid #e3e3e3;
            margin: 0px 0px 20px 0px;
        }

        @media (width < 700px) {
            .image-container {
                display: none;
            }

            .form-login {
                width: 92%;
                padding: 20px 3%;

            }

            .form-signup {
                width: 92%;
                padding: 20px 3%;

            }

            .rights {
                display: none;
            }
        }

        .rights {
            color: #ffffff;
            position: absolute;
            bottom: 45px;
            z-index: 2;
            left: 41%;
        }


        .footer {}

        .alert-danger {
            color: #dc3741;
            background: #dc374136;
            padding: 20px 10px;
            border-radius: 8px;
            margin: 0px 0px 20px 0px;
            border-left: 4px solid #dc3741;
            font-size: 14px;
        }

        .alert-success {
            color: #b57826;
            background: #e49b3936;
            padding: 10px 12px;
            border-radius: 6px;
            margin: 0px 0px 10px 0px;
            border-left: 4px solid #b57826;
            font-size: 14px;
        }

        /* Estilos para los mensajes de error inline */
        #recover-email-error {
            margin-top: 5px;
            display: block;
            font-size: 13px !important;
        }

        /* Estilos para el modal de verificación */
        #emailVerificationModal .custom-modal-content {
           /*  border-left: 5px solid #E49B39; */
        }

        #emailVerificationModal .card-header h3 {
            color: #B57827;
        }

        #emailVerificationModal .custom-modal-body {
            text-align: center;
            padding: 20px 0;
        }

        #emailVerificationModal .verification-icon {
            margin-bottom: 15px;
        }

        #emailVerificationModal .verification-message {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        #emailVerificationModal .verification-instructions {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        #emailVerificationModal .verification-note {
            font-size: 13px;
            color: #777;
            font-style: italic;
        }

        #emailVerificationModal .btn-primary {
            background-color: #E49B39;
            color: white;
            font-weight: 600;
            padding: 8px 25px;
        }

        #emailVerificationModal .btn-primary:hover {
            background-color: #B57827;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="container-double">
            <!-- Formulario de Login -->
            <div class="form-login">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any() && !session('error'))
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif
                    <div class="logo-center">
                        <div class="moollish"></div>
                        <span class="moollish-span">Moollish</span>
                    </div>
                    <div class="main-form">
                        <h1 class="moollish-span">Inicia Sesión</h1>
                        <span class="text-paragraph">
                            Inicia sesión y accede a todas las herramientas de Moollish.
                        </span>
                        <div class="input-password">
                            <label for="email">Correo</label>
                            <input id="email" class="form-input" type="email" name="email"
                                placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus
                                autocomplete="username">
                        </div>
                        <div class="input-password">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-input" id="passwordInput" name="password"
                                placeholder="Contraseña" required autocomplete="current-password">
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" id="showPasswordCheck">
                            <label class="chkcustom" for="showPasswordCheck">Ver contraseña</label>
                        </div>
                        <button type="submit" class="btn-login">Iniciar sesión</button>
                        <span>¿Aún no tienes Moollish?</span>
                        <button type="button" class="btn-outline-login" id="showSignupBtn">Regístrate</button>

                    </div>

                    <div class="footer">
                        <span>¿Olvidaste tu contraseña? </span>
                        <a href="{{ route('password.request') }}">Recupera tu cuenta</a>
                    </div>
                </form>
            </div>

            <div class="form-recover" style="display: none; position: relative; bottom: -50px;">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->has('email'))
                        <div class="alert alert-danger">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <div class="logo-center">
                        <div class="moollish"></div>
                        <span class="moollish-span">Moollish</span>
                    </div>
                    <div class="main-form">
                        <h1 class="moollish-span">Recupera tu Cuenta</h1>
                        <span class="text-paragraph">
                            Estamos para ayudarte, escribe tu correo electrónico a continuación para recuperar tu cuenta.
                        </span>
                        <div class="input-password">
                            <label for="email">Correo Electrónico</label>
                            <input id="email" class="form-input" type="email" name="email" placeholder="Correo electrónico" required autofocus>
                            <span id="recover-email-error" style="color: red; font-size: 12px; display: none;"></span>
                        </div>
                        <button type="submit" class="btn-login">Enviar Instrucciones</button>
                       </div>
                    <button type="button" class="btn-outline-login" id="showLoginBtnFromRecovery">Inicia Sesión</button>

                    <span>¿Necesitas ayuda adicional?</span>
                    <a href="https://wa.me/XXXXXXXXXX" target="_blank" class="btn-whatsapp">Contactar por WhatsApp</a>


                </form>
            </div>

            <!-- Formulario de Sign Up (inicialmente oculto) -->
            <div class="form-signup" style="display:none;">
                <form id="registerForm" method="POST" action="{{ route('register.user') }}">
                    @csrf
                    <div class="logo-center">
                        <div class="moollish"></div>
                        <span class="moollish-span">Moollish</span>
                    </div>
                    <div class="main-form" style="margin: 20px 0;">
                        <h1 class="moollish-span">Regístrate</h1>
                        <br>
                        <div class="input-signup">
                            <label for="id_rol">¿Qué tipo de usuario eres?</label>
                            <select name="id_rol" id="id_rol" required class="form-input">
                                <option value="3">Propietario</option>
                                <option value="2">Encuestador</option>
                                <option value="4">Veterinario</option>
                                <option value="5">Técnico</option>
                            </select>
                        </div>
                        <div class="input-signup">
                            <label for="tipo_documento">Tipo y numero de documento</label>
                            <div class="d-flex">
                                <select name="tipo_documento" id="tipo_documento" class="form-input"
                                    style="margin: 5px 10px 0px 0; width: 33%;">
                                    <option value="Cedula">C.C</option>
                                </select>
                                <input id="documento" class="form-input" type="number" name="documento"
                                    placeholder="documento" required>
                            </div>
                            <span style="color: red; font-size: 12px;margin: 8px 0px 0px;display: none;"
                                id="documento-error">Digita un numero de documento valido</span>
                        </div>
                        <div class="input-signup">
                            <label for="name">Nombre</label>
                            <input id="name" class="form-input" type="text" name="name"
                                placeholder="Tu nombre" required>
                        </div>
                        <div class="input-signup">
                            <label for="celular">Número de celular</label>
                            <div class="d-flex">
                                <select name="codigo_pais" id="codigo_pais" class="form-input"
                                    style="margin: 5px 10px 0px 0; width: 33%;">
                                    <option value="+57">+57</option>
                                </select>
                                <input id="celular" class="form-input" type="number" name="celular"
                                    placeholder="Celular" required>
                            </div>
                            <span style="color: red; font-size: 12px;margin: 8px 0px 0px;display: none;"
                                id="phone-error">Digita un numero de celular valido</span>
                        </div>
                        <div class="input-signup">
                            <label for="emailReg">Correo electrónico</label>
                            <input id="emailReg" class="form-input" type="email" name="email"
                                placeholder="Correo electrónico" required>
                        </div>
                        <span style="color: red; font-size: 12px; display: none;" id="email-error">Digita un correo
                            electrónico valido</span>
                        <div class="d-flex">
                            <div class="input-signup">
                                <label for="passwordReg">Contraseña</label>
                                <input type="password" class="form-input passwordInput2" id="passwordReg"
                                    name="password" placeholder="Contraseña" style="    margin: 5px 10px 0px 0px;"
                                    required>
                            </div>
                            <div class="input-signup">
                                <label for="password_confirmation">Confirmar Contraseña</label>
                                <input type="password" class="form-input passwordInput2" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirmar contraseña" required>
                            </div>
                        </div>
                        <span style="color: red; font-size: 12px;display: none;" id="password-error">Digita una
                            contrasñea valida</span>
                        <div class="checkbox-container" style="    margin: 10px 0px 0px;">
                            <input type="checkbox" id="showPasswordCheck2">
                            <label class="chkcustom" for="showPasswordCheck2">Ver contraseñas</label>
                        </div>
                        <button type="submit" class="btn-login" disabled>Registrarse</button>
                        <span>¿Ya tienes una cuenta?</span>
                        <button type="button" class="btn-outline-login" id="showLoginBtn">Inicia Sesión</button>
                    </div>
                </form>
                <!-- Modal de Confirmación -->
                <div id="confirmRegistrationModal" class="custom-modal">
                    <div class="custom-modal-content">
                        <div class="card-header">
                            <span class="custom-modal-close" id="confirmModalClose">&times;</span>
                            <h3>Confirma el registro</h3>
                        </div>
                        <p>¿Deseas crear tu usuario en Moollish.com?
                            <br>
                            <br>
                            Al registrarte aceptas nuestros términos y condiciones.
                            <br>
                            <br>
                            Tu prueba de 90 dias empezará hoy.
                        </p>
                        <div class="custom-modal-footer">
                            <button class="btn" id="confirmModalCancel">Cancelar</button>
                            <button class="btn btn-primary" id="confirmRegistrationBtn">Confirmar</button>
                        </div>
                    </div>
                </div>
                <!-- Modal de Error -->
                <div id="errorModal" class="custom-modal">
                    <div class="custom-modal-content">
                        <div class="card-header">
                            <span class="custom-modal-close" id="errorModalClose">&times;</span>
                            <h3>Error en el Registro</h3>
                        </div>
                        <div class="custom-modal-body">
                            <!-- El mensaje se inyecta aquí -->
                        </div>
                        <div class="custom-modal-footer">
                            <button class="btn" id="errorModalBtn">Aceptar</button>
                        </div>
                    </div>
                </div>
                <!-- Modal de Verificación de Email -->
                <div id="emailVerificationModal" class="custom-modal">
                    <div class="custom-modal-content">
                        <div class="card-header">
                            <span class="custom-modal-close" id="emailVerificationModalClose">&times;</span>
                            <h3>¡Registro exitoso!</h3>
                        </div>
                        <div class="custom-modal-body">
                            <div class="verification-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64">
                                    <path fill="#E49B39" d="M20,4H4C2.9,4,2,4.9,2,6v12c0,1.1,0.9,2,2,2h16c1.1,0,2-0.9,2-2V6C22,4.9,21.1,4,20,4z M20,8l-8,5L4,8V6l8,5l8-5V8z"/>
                                </svg>
                            </div>
                            <p class="verification-message">Te hemos enviado un correo electrónico para verificar tu cuenta.</p>
                            <p class="verification-instructions">Por favor, revisa tu bandeja de entrada y haz clic en el enlace para verificar tu correo electrónico.</p>
                            <p class="verification-note">Si no recibes el correo en unos minutos, revisa tu carpeta de spam.</p>
                        </div>
                        <div class="custom-modal-footer">
                            <button class="btn btn-primary" id="emailVerificationModalBtn">Entendido</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="image-container">
                <div class="fade-image" style="background-image: url('../img/banner.webp');"></div>
                <div class="fade-image" style="background-image: url('../img/2150454976.webp');"></div>
                <div class="fade-image" style="background-image: url('../img/42782.jpg');"></div>
                <div class="fade-image" style="background-image: url('../img/10813.webp');"></div>
            </div>
            <span class="rights">© Moollish 2025, Todos los derechos reservados.</span>
        </div>
    </div>
</body>
<style>
    .image-container {
        width: 60%;
        margin: 20px auto;
        position: relative;
        overflow: hidden;
        border-radius: 10px;
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
    .alert-success {
        color: #b57826;
    background: #e49b3936;
    padding: 10px 12px;
    border-radius: 6px;
    margin: 0px 0px 10px 0px;
    }

    /* Estilos para el modal */
    .custom-modal {
        display: none;
        /* Oculto por defecto */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .custom-modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        position: relative;
    }

    .custom-modal-close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        font-weight: bold;
        color: #aaa;
        cursor: pointer;
    }

    .custom-modal-body {
        margin: 20px 0px;
    }

    .custom-modal-close:hover {
        color: #000;
    }

    .custom-modal-footer {
        text-align: right;
        border-top: 1px solid #e3e3e3;
        margin-top: 20px;
        padding: 20px 0px 0px 0px;
    }
    #resendBtn {
        background: transparent;
    border: none;
    text-decoration: underline;
    color: #e49b39;
    font-weight: 600;
    padding: 0px;
    }
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    /* Estilos para mensajes de feedback */
    .feedback-message {
        padding: 10px 12px;
        border-radius: 6px;
        margin: 10px 0px;
        font-size: 14px;
    }

    .feedback-message.success {
        color: #b57826;
        background: #e49b3936;
    }

    .feedback-message.error {
        color: #dc3741;
        background: #dc374136;
    }
</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>


<script>
    document.getElementById('showPasswordCheck').addEventListener('change', function() {
        var passwordInput = document.getElementById('passwordInput');
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
</script>

<script>
    document.getElementById('showPasswordCheck2').addEventListener('change', function() {
        var passwordInputs = document.getElementsByClassName('passwordInput2');
        for (var i = 0; i < passwordInputs.length; i++) {
            passwordInputs[i].type = this.checked ? 'text' : 'password';
        }
    });
</script>


<script>
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
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Referencias a los elementos del formulario
        const registerForm = document.getElementById("registerForm");
        const documentoInput = document.getElementById("documento");
        const celularInput = document.getElementById("celular");
        const emailInput = document.getElementById("emailReg");
        const passwordInput = document.getElementById("passwordReg");
        const passwordConfirmInput = document.getElementById("password_confirmation");

        // Spans para mostrar mensajes de error (ya definidos en el HTML)
        const documentoError = document.getElementById("documento-error");
        const phoneError = document.getElementById("phone-error");
        const emailError = document.getElementById("email-error");
        const passwordError = document.getElementById("password-error");

        // Botón de submit
        const submitButton = registerForm.querySelector("button[type='submit']");

        // Función para validar Documento (entre 3 y 15 dígitos)
        function validateDocumento() {
            const value = documentoInput.value.trim();
            if (value.length < 3 || value.length > 15) {
                if (documentoInput.dataset.touched === "true") {
                    documentoInput.style.borderColor = "red";
                    documentoError.textContent = "El número de documento debe tener entre 3 y 15 dígitos.";
                    documentoError.style.display = "block";
                }
                return false;
            } else {
                documentoInput.style.borderColor = "";
                documentoError.textContent = "";
                documentoError.style.display = "none";
                return true;
            }
        }

        // Función para validar Celular (mínimo 7 dígitos, puedes ajustar)
        function validateCelular() {
            const value = celularInput.value.trim();
            if (value === "" || isNaN(value) || value.length < 7) {
                if (celularInput.dataset.touched === "true") {
                    celularInput.style.borderColor = "red";
                    phoneError.textContent = "Digita un número de celular válido.";
                    phoneError.style.display = "block";
                }
                return false;
            } else {
                celularInput.style.borderColor = "";
                phoneError.textContent = "";
                phoneError.style.display = "none";
                return true;
            }
        }

        // Función para validar Email (regex básico)
        function validateEmail() {
            const value = emailInput.value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(value)) {
                if (emailInput.dataset.touched === "true") {
                    emailInput.style.borderColor = "red";
                    emailError.textContent = "Digita un correo electrónico válido.";
                    emailError.style.display = "block";
                }
                return false;
            } else {
                emailInput.style.borderColor = "";
                emailError.textContent = "";
                emailError.style.display = "none";
                return true;
            }
        }

        // Función para validar Contraseña: mínimo 8 caracteres y que coincida con confirmación
        function validatePassword() {
            const pwd = passwordInput.value;
            const confirmPwd = passwordConfirmInput.value;
            if (pwd.length < 8) {
                if (passwordInput.dataset.touched === "true") {
                    passwordInput.style.borderColor = "red";
                    passwordError.textContent = "La contraseña debe tener al menos 8 caracteres.";
                    passwordError.style.display = "block";
                }
                return false;
            } else if (pwd !== confirmPwd) {
                if (passwordInput.dataset.touched === "true" || passwordConfirmInput.dataset.touched ===
                    "true") {
                    passwordInput.style.borderColor = "red";
                    passwordConfirmInput.style.borderColor = "red";
                    passwordError.textContent = "Las contraseñas no coinciden.";
                    passwordError.style.display = "block";
                }
                return false;
            } else {
                passwordInput.style.borderColor = "";
                passwordConfirmInput.style.borderColor = "";
                passwordError.textContent = "";
                passwordError.style.display = "none";
                return true;
            }
        }

        // Función global para validar todos los campos y habilitar o deshabilitar el botón
        function validateForm() {
            const docValid = validateDocumento();
            const celularValid = validateCelular();
            const emailValid = validateEmail();
            const passwordValid = validatePassword();

            if (docValid && celularValid && emailValid && passwordValid) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        }

        // Agregar eventos "blur" para marcar que el campo fue tocado y validar
        [documentoInput, celularInput, emailInput, passwordInput, passwordConfirmInput].forEach(function(
            input) {
            input.addEventListener("blur", function() {
                input.dataset.touched = "true";
                validateForm();
            });
            // En el evento "input" solo se valida si ya fue tocado
            input.addEventListener("input", function() {
                if (input.dataset.touched === "true") {
                    validateForm();
                }
            });
        });
    });
</script>





<script>
    $(document).ready(function() {
        function showForm(targetSelector) {
            // Ocultar el formulario visible y, al terminar, mostrar el destino
            $('.form-login:visible, .form-recover:visible, .form-signup:visible')
                .fadeOut(150)
                .promise()
                .done(function() {
                    $(targetSelector).fadeIn(150);
                });
        }

        // Cambiar a formulario de registro
        $('#showSignupBtn').on('click', function() {
            showForm('.form-signup');
        });

        // Cambiar a formulario de login (desde registro o recuperación)
        $('#showLoginBtn, #showLoginBtnFromRecovery').on('click', function() {
            showForm('.form-login');
        });

        // Cambiar a formulario de recuperación desde el enlace en el footer del login
        $('.form-login .footer a:contains("Recupera tu cuenta")').on('click', function(e) {
            e.preventDefault();
            showForm('.form-recover');
        });
    });
</script>




<script>
    $(document).ready(function() {
        // Función para mostrar un modal personalizado
        function showModal(modalId) {
            $('#' + modalId).fadeIn(300);
        }
        // Función para ocultar un modal personalizado
        function hideModal(modalId) {
            $('#' + modalId).fadeOut(300);
        }

        // Cerrar modales al hacer clic en la "X" o en el botón "Aceptar"
        $('.custom-modal-close, #confirmModalCancel, #errorModalBtn').click(function() {
            $(this).closest('.custom-modal').fadeOut(300);
        });

        // Manejador para el botón del modal de verificación de correo
        $('#emailVerificationModalBtn, #emailVerificationModalClose').click(function() {
            hideModal('emailVerificationModal');
            // Redirigir al usuario después de cerrar el modal
            var redirectUrl = window.lastRedirectUrl || '/inicio';
            window.location.href = redirectUrl;
        });

        // Interceptar el envío del formulario para validar email y celular vía AJAX
        $('#registerForm').submit(function(e) {
            e.preventDefault(); // Prevenir el envío normal

            var email = $('#emailReg').val();
            var celular = $('#celular').val();

            $.ajax({
                url: '/api/check-user',
                method: 'POST',
                data: {
                    email: email,
                    celular: celular,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Si el email o celular ya están en uso
                    if (response.exists) {
                        $('#errorModal .custom-modal-body').text(
                            'El correo electrónico o el número de celular ya están en uso.'
                        );
                        showModal('errorModal');
                    } else {
                        // Si no están en uso, mostrar el modal de confirmación
                        showModal('confirmRegistrationModal');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    $('#errorModal .custom-modal-body').text('Error al validar los datos.');
                    showModal('errorModal');
                }
            });
        });

        // Al hacer clic en el botón de confirmación del modal, enviar el formulario de registro vía AJAX
        $('#confirmRegistrationBtn').click(function() {
            hideModal('confirmRegistrationModal');
            var formData = $('#registerForm').serialize();
            $.ajax({
                url: $('#registerForm').attr('action'),
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Guardar la URL de redirección para usarla después de cerrar el modal
                        window.lastRedirectUrl = response.redirect_url;
                        // Mostrar modal de verificación de correo electrónico
                        showModal('emailVerificationModal');
                    } else {
                        $('#errorModal .custom-modal-body').text('Error: ' + response
                            .message);
                        showModal('errorModal');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    $('#errorModal .custom-modal-body').text(
                        'Ocurrió un error al registrar el usuario.');
                    showModal('errorModal');
                }
            });
        });


    });
</script>



<script>
    $(document).ready(function(){
        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function sendRecoveryEmail() {
            var form = $('.form-recover form');
            var url = form.attr('action');
            var $submitBtn = form.find('.btn-login');
            var $emailInput = form.find('input[name="email"]');
            var $emailError = form.find('#recover-email-error');
            var email = $emailInput.val().trim();

            // Ocultar mensajes de error anteriores
            $emailError.hide();
            form.find('.feedback-message').remove();
            form.find('.alert').remove();

            // Validar el formato del correo electrónico
            if (!validateEmail(email)) {
                $emailError.text('Por favor, ingresa un correo electrónico válido.').show();
                $emailInput.css('border-color', 'red');
                return;
            }

            // Restablecer estilos
            $emailInput.css('border-color', '');

            // Deshabilita el botón y muestra un texto de carga
            $submitBtn.prop('disabled', true).text('Enviando...');

            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Si hay mensaje de éxito en la respuesta, el correo fue enviado correctamente
                    if (response.status === 'success' || (response.message && response.message.includes('enviado'))) {
                        var message = '<div class="feedback-message success">Instrucciones enviadas a tu correo. Recuerda que la solicitud puede tardar unos minutos en llegar, <button type="button" id="resendBtn">Reenviar correo</button>.</div>';
                        form.find('.main-form').prepend(message);
                    } else {
                        // En caso de que la respuesta no sea clara, mostrar mensaje genérico
                        var message = '<div class="alert alert-danger">Ocurrió un error al procesar tu solicitud. Por favor, intenta nuevamente.</div>';
                        form.prepend(message);
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'Ocurrió un error al enviar el correo. Inténtalo nuevamente.';

                    // Intentar obtener mensaje de error específico del servidor
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        if (xhr.responseJSON.errors.email) {
                            errorMessage = xhr.responseJSON.errors.email[0];

                            // Si el mensaje indica que el correo no está registrado
                            if (errorMessage.includes('no se encuentra registrado')) {
                                errorMessage = 'El correo que ingresaste no se encuentra registrado en Moollish';
                                $emailInput.css('border-color', 'red');
                            }
                        }
                    }

                    // Muestra mensaje de error
                    var message = '<div class="alert alert-danger">' + errorMessage + '</div>';
                    form.prepend(message);
                },
                complete: function(){
                    $submitBtn.prop('disabled', false).text('Enviar Instrucciones');
                }
            });
        }

        // Captura el envío del formulario
        $('.form-recover form').on('submit', function(e) {
            e.preventDefault();
            sendRecoveryEmail();
        });

        // Manejador para el botón de reenvío
        $(document).on('click', '#resendBtn', function(){
            sendRecoveryEmail();
        });

        // Limpiar mensajes de error al escribir en el campo
        $('.form-recover input[name="email"]').on('input', function() {
            $(this).css('border-color', '');
            $('#recover-email-error').hide();
            $('.form-recover .alert-danger').remove();
            $('.form-recover .feedback-message').remove();
        });
    });
</script>


</html>
