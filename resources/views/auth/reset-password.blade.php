<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicia sesión - Moollish</title>
    {{-- Icons google --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">

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
             background-image: url('{{ asset("img/22510.jpg") }}');
        }
        .form-recover {
            width: 32%;
            padding: 20px 3%;
            height: calc(100vh - 60px);
        }

        .form-login {
            padding: 20px 3%;
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
            background-image: url('{{ asset("img/moollish.png") }}');
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
            color: #684804;
        }


        .footer {}

        .alert-danger {
            color: #dc3741;
            background: #dc374136;
            padding: 20px 10px;
            border-radius: 8px;
            margin: 0px 0px 20px 0px;
        }
        
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
    #password-error {
        margin: 8px 0px 0px;
    }
    #password-success {
        margin: 8px 0px 0px;
        color: #22af3b;
        font-weight: 600;
    }
    #confirm-error {
        margin: 8px 0px 0px;
    }
    #confirm-success {
        margin: 8px 0px 0px;
        color: #22af3b;
        font-weight: 600;
    }
    </style>
</head>

<body>
    <div class="main">
        <div class="container-double">
            <!-- Formulario de Login -->
            <div class="form-login">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                    @if ($errors->any())
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
                        <h1 class="moollish-span">Recupera tu contraseña</h1>
                        <span class="text-paragraph">
                            Crea una nueva contraseña para tu cuenta.
                        </span>

                        <div class="input-password">
                            <label for="password">Nueva contraseña</label>
                            <input type="password" class="form-input" id="password" name="password"
                                placeholder="Nueva contraseña" required autofocus>
                            <span id="password-error" style="color: red; font-size: 12px; display: none;"></span>
                            <span id="password-success" style="color: green; font-size: 12px; display: none;">✓ Contraseña válida</span>
                        </div>
                        <div class="input-password">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <input type="password" class="form-input" id="password_confirmation" name="password_confirmation"
                                placeholder="Confirmar contraseña" required>
                            <span id="confirm-error" style="color: red; font-size: 12px; display: none;">Las contraseñas no coinciden</span>
                            <span id="confirm-success" style="color: green; font-size: 12px; display: none;">✓ Las contraseñas coinciden</span>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" id="showPasswordCheck">
                            <label class="chkcustom" for="showPasswordCheck">Ver contraseña</label>
                        </div>

                        <button type="submit" class="btn-login" id="submit-btn">Restablecer contraseña</button>
                    </div>
                    <span class="rights">© Moollish 2025 Todos los derechos reservados.</span>

                </form>
            </div>
        </div>
    </div>
</body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const passwordError = document.getElementById('password-error');
        const confirmError = document.getElementById('confirm-error');
        const passwordSuccess = document.getElementById('password-success');
        const confirmSuccess = document.getElementById('confirm-success');
        const submitBtn = document.getElementById('submit-btn');

        // Función para validar la contraseña
        function validatePassword() {
            const password = passwordInput.value;
            const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password);
            const hasMinLength = password.length >= 6;

            if (password === '') {
                passwordError.style.display = 'none';
                return false;
            } else if (!hasMinLength && !hasSpecialChar) {
                passwordError.textContent = 'La contraseña debe tener al menos 6 caracteres y un carácter especial';
                passwordError.style.display = 'block';
                passwordInput.style.borderColor = 'red';
                return false;
            } else if (!hasMinLength) {
                passwordError.textContent = 'La contraseña debe tener al menos 6 caracteres';
                passwordError.style.display = 'block';
                passwordInput.style.borderColor = 'red';
                return false;
            } else if (!hasSpecialChar) {
                passwordError.textContent = 'La contraseña debe contener al menos un carácter especial (!@#$%^&*...)';
                passwordError.style.display = 'block';
                passwordInput.style.borderColor = 'red';
                return false;
            } else {
                passwordError.style.display = 'none';
                passwordInput.style.borderColor = '';
                passwordSuccess.style.display = 'block';
                return true;
            }
        }

        // Función para validar que las contraseñas coincidan
        function validateConfirm() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (confirm !== '') {
                if (password !== confirm) {
                    confirmError.style.display = 'block';
                    confirmSuccess.style.display = 'none';
                    confirmInput.style.borderColor = 'red';
                    return false;
                } else {
                    confirmError.style.display = 'none';
                    confirmSuccess.style.display = 'block';
                    confirmInput.style.borderColor = '#D8D9D9';
                    if (validatePassword()) {
                        confirmInput.style.borderColor = '#b57827';
                    }
                    return true;
                }
            } else {
                confirmSuccess.style.display = 'none';
            }
        }

        // Función para mostrar feedback visual cuando la contraseña es válida
        function showValidFeedback(input) {
            if (validatePassword() && input === passwordInput) {
                passwordInput.style.borderColor = '#b57827';
                passwordInput.style.borderWidth = '2px';
            }
            if (validateConfirm() && confirmInput.value !== '' && input === confirmInput) {
                confirmInput.style.borderColor = '#b57827';
                confirmInput.style.borderWidth = '2px';
            }
        }

        // Función para validar el formulario completo
        function validateForm() {
            const isPasswordValid = validatePassword();
            const isConfirmValid = validateConfirm();

            submitBtn.disabled = !(isPasswordValid && isConfirmValid && confirmInput.value !== '');
        }

        // Eventos para validar en tiempo real
        passwordInput.addEventListener('input', function() {
            validatePassword();
            if (confirmInput.value !== '') {
                validateConfirm();
            }
            showValidFeedback(passwordInput);
            validateForm();
        });

        confirmInput.addEventListener('input', function() {
            validateConfirm();
            showValidFeedback(confirmInput);
            validateForm();
        });

        // Verificar inicialmente
        validateForm();

        // Manejar el evento showPasswordCheck
        document.getElementById('showPasswordCheck').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');

            if (this.checked) {
                passwordInput.type = 'text';
                passwordConfirmInput.type = 'text';
            } else {
                passwordInput.type = 'password';
                passwordConfirmInput.type = 'password';
            }
        });
    });
</script>

</html>
