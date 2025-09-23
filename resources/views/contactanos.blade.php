<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contáctanos | Moollish</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo4.png') }}" />
    <style>
        /* Estilo para el contenedor del spinner */
        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            /* 100% del ancho de la ventana */
            height: 100vh;
            /* 100% de la altura de la ventana */
            background: rgb(255, 255, 255);
            /* Fondo semi-transparente blanco */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            /* Asegura que esté sobre otros elementos */
        }
    
        /* Animación de respiración */
        @keyframes respiracion {
            0% {
                transform: scale(0.9);
            }
    
            50% {
                transform: scale(1.0);
            }
    
            100% {
                transform: scale(0.9);
            }
        }
    
        /* Aplicar la animación a la imagen de precarga */
        #loader-wrapper img {
            width: 200px;
            /* Ancho de la imagen */
            animation: respiracion 2s ease-in-out infinite;
        }
    </style>
       
    <style>
     @font-face {
        font-family: 'Garet';
        src: url('path-to-your-font/Garet-Regular.woff2') format('woff2'),
            url('path-to-your-font/Garet-Regular.woff') format('woff'),
            url('path-to-your-font/Garet-Regular.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
        :root {
    --primary-color: #010712;
    --secondary-color: #818386;
    --bg-color: #FCFDFD;
    --button-color: #3B3636;
    --h1-color: #3F444C;
}

[data-theme="dark"] {
    --primary-color: #FCFDFD;
    --secondary-color: #818386;
    --bg-color: #010712;
    --button-color: #818386;
    --h1-color: #FCFDFD;
}

* {
    margin: 0;
    box-sizing: border-box;
    transition: all 0.3s ease-in-out;
}

.contact-container {
    display: flex;
    width: 100vw;
    height: 100vh;
    background: var(--bg-color);
}
.primary-menu .menu-area ul.main-menu li a.active, .primary-menu .menu-area ul.main-menu li a:hover {
    color: #e49b39 !important;
}

.left-col {
    width: 45vw;
    height: 100%;
    background-image: url("/assets/images/contactanos correguida4.png");
    background-size: cover;
    background-repeat: no-repeat;
}

.logo {
    width: 10rem;
    padding: 1.5rem;
}

.right-col {
    background: var(--bg-color);
    width: 50vw;
    height: 100vh;
    padding: 5rem 3.5rem;
}

.contact-title,
.contact-label,
.contact-button,
.description {
    font-family: 'Anton';
    font-weight: 400;
    letter-spacing: 0.1rem;
}

.contact-title {
    color: var(--h1-color);
    text-transform: uppercase;
    font-size: 2.5rem;
    letter-spacing: 0.5rem;
    font-weight: 300;
}

.contact-description {
    color: var(--secondary-color);
    font-size: 0.9rem;
    letter-spacing: 0.01rem;
    width: 40vw;
    margin: 0.25rem 0;
}

.contact-label,
.description {
    color: var(--secondary-color);
    text-transform: uppercase;
    font-size: 0.625rem;
}

.contact-form {
    width: 31.25rem;
    position: relative;
    margin-top: 2rem;
    padding: 1rem 0;
}

.contact-input,
.contact-textarea,
.contact-label {
    width: 40vw;
    display: block;
}

.contact-input::placeholder,
.contact-textarea::placeholder {
    color: var(--primary-color);
}

.contact-input,
.contact-textarea {
    color: var(--primary-color) !important;
    font-weight: 500 !important;
    background: var(--bg-color) !important;
    border: none !important;
    border-bottom: 1px solid var(--secondary-color) !important;
    padding: 0.5rem 0 !important;
    margin-bottom: 1rem !important;
    outline: none !important;
}

.contact-textarea {
    resize: none;
}

.contact-button {
    text-transform: uppercase;
    font-weight: 300;
    background: var(--button-color);
    color: var(--bg-color);
    width: 10rem;
    /* height: 2.25rem; */
    border: none;
    border-radius: 2px;
    outline: none;
    cursor: pointer;
}

.contact-input:hover,
.contact-textarea:hover,
.contact-button:hover {
    opacity: 0.5;
}

.contact-button:active {
    opacity: 0.8;
}

#success-msg,
#contact-error {
    width: 40vw;
    margin: 0.125rem 0;
    font-size: 0.75rem;
    text-transform: uppercase;
    font-family: 'Garet';
    color: var(--secondary-color);
}

#success-msg {
    transition-delay: 3s;
}

/* Toggle Switch */
.theme-switch-wrapper {
    display: flex;
    align-items: center;
    text-align: center;
    width: 160px;
    position: absolute;
    top: 0.5rem;
    right: 0;
}

.description {
    margin-left: 1.25rem;
}

.theme-switch {
    display: inline-block;
    height: 34px;
    position: relative;
    width: 60px;
}

.theme-switch input {
    display: none;
}

.slider {
    background-color: #ccc;
    bottom: 0;
    cursor: pointer;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    transition: .4s;
}

.slider:before {
    background-color: #fff;
    bottom: 0.25rem;
    content: "";
    width: 26px;
    height: 26px;
    left: 0.25rem;
    position: absolute;
    transition: .4s;
}

input:checked + .slider {
    background-color: var(--button-color);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

@media only screen and (max-width: 950px) {
    .logo {
        width: 8rem;
    }

    .contact-title {
        font-size: 1.75rem;
    }

    .contact-description {
        font-size: 0.7rem;
    }

    .contact-input,
    .contact-textarea,
    .contact-button {
        font-size: 0.65rem;
    }

    .description {
        font-size: 0.3rem;
        margin-left: 0.4rem;
    }

    .contact-button {
        width: 7rem;
    }

    .theme-switch-wrapper {
        width: 120px;
    }

    .theme-switch {
        height: 28px;
        width: 50px;
    }

    .theme-switch input {
        display: none;
    }

    .slider:before {
        background-color: #fff;
        bottom: 0.25rem;
        content: "";
        width: 20px;
        height: 20px;
        left: 0.25rem;
        position: absolute;
        transition: .4s;
    }

    input:checked + .slider:before {
        transform: translateX(16px);
    }

    .slider.round {
        border-radius: 15px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

}

     
    </style>
     <link rel="shortcut icon" type="image/x-icon" href="assets/images/logo4.png">
     <link rel="stylesheet" type="text/css" href="Landing/css/animate.css">
     <link rel="stylesheet" type="text/css" href="Landing/css/bootstrap.min.css">
     <link rel="stylesheet" type="text/css" href="Landing/css/all.min.css">
     <link rel="stylesheet" type="text/css" href="Landing/css/lightcase.css">
     <link rel="stylesheet" type="text/css" href="Landing/flaticon/flaticon.css">
     <link rel="stylesheet" type="text/css" href="Landing/css/swiper.min.css">
     <link rel="stylesheet" type="text/css" href="Landing/css/slick.css">
     <link rel="stylesheet" type="text/css" href="Landing/css/slick-theme.css">
     <link rel="stylesheet" type="text/css" href="Landing/css/style.css">
</head>
<div id="loader-wrapper">
    <img src="{{asset('assets/images/logo4.png')}}" alt="Cargando..." />
</div>
<body>
    
  
    <!-- Responsive Contact Page with Dark Mode and Form Validation (vanilla JS).

*Designed & built for desktop and tablets with viewport width >= 720px and in landscape orientation.  -->
 <!-- mobile-nav section start here -->
 <div class="mobile-menu">
    <nav class="mobile-header primary-menu d-lg-none">
        <div class="header-logo">
            <a href="index.html" class="logo"><img src="assets/images/logo4.png" alt="logo"
                    style="width: 80px;"></a>
        </div>
        <div class="header-bar" id="open-button">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
    <nav class="menu">
        <div class="mobile-menu-area d-lg-none">
            <div class="mobile-menu-area-inner" id="scrollbar">
                <ul class="m-menu">
                    <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    <li><a href="service.html">Services</a></li>
                </ul>

            </div>
        </div>
    </nav>
</div>
<!-- mobile-nav section ending here -->

<!-- header section start here -->
<header class="header-section style-2 style-4  d-none d-lg-block">
    <div class="header-bottom">
        <div class="row">
            <nav class="primary-menu">
                <div class="menu-area">
                    <div class="row justify-content-between align-items-center">
                        <a href="{{ route('/') }}" class="logo">
                            <img src="/assets/images/logo-letra3.png" alt="logo" style="width:180px">
                        </a>
                        <div class="main-menu-area d-flex align-items-center">
                            <ul class="main-menu d-flex align-items-center">
                                <li><a href="{{ route('/') }}">Inicio</a>

                                </li>


                                <li><a href="{{ route('contactanos') }}" class="active">Contáctanos</a></li>

                                <li><a href="{{ route('login') }}"><button href="contact-us.html"
                                            style="background-color: #e49b39;
                                    color: white;
                                    padding: 10px 41px;
                                    border: #e49b39 solid 1px;
                                    border-radius: 20px;
                                    font-family: 'Garet', sans- serif;
                                    font-size: 18px;">Iniciar
                                            Sesión</button></a></li>
                            </ul>
                        </div>


                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

<div class="contact-container">
    <div class="left-col">

    </div>
    <div class="right-col">
        {{-- <div class="theme-switch-wrapper">
            <label class="theme-switch" for="checkbox">
                <input type="checkbox" id="checkbox" />
                <div class="slider round"></div>
            </label>
            <div class="description">Modo oscuro</div>
        </div> --}}

        <h1 class="contact-title">Contáctanos</h1>
        <p class="contact-description" style="font-family: 'Garet' !important;">Para mayor información por favor enviarnos sus datos en el Siguiente formato y lo contactaremos.</p>

        <form id="contact-form" method="post" class="contact-form">
            <label for="name" class="contact-label">Nombre completo</label>
            <input type="text" id="name" name="name" class="contact-input" placeholder="Tu nombre completo" required>

            <label for="email" class="contact-label">Correo electrónico</label>
            <input type="email" id="email" name="email" class="contact-input" placeholder="Tu dirección de correo electrónico" required>

            <label for="message" class="contact-label">Mensaje</label>
            <textarea rows="6" id="message" name="message" class="contact-textarea" placeholder="Tu mensaje" required></textarea>

            <button type="submit" id="submit" name="submit" class="contact-button">Enviar</button>
        </form>

        <div id="error" class="contact-error"></div>
        <div id="success-msg" class="contact-success-msg"></div>
    </div>
</div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ocultar la imagen de precarga 5 segundos después de que la página ha cargado completamente
            var loaderWrapper = document.getElementById("loader-wrapper");
            setTimeout(function() {
                loaderWrapper.style.display = "none";
            }, 2000); // 5000 milisegundos = 5 segundos
        });
    </script>
    <!-- Image credit: Oliver Sjöström https://www.pexels.com/photo/body-of-water-near-green-mountain-931018/  -->
    <script>
        // Selector para el interruptor de tema (claro/oscuro)
        const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');

        // Función para cambiar el tema
        function switchTheme(e) {
            if (e.target.checked) {
                document.documentElement.setAttribute("data-theme", "dark");
            } else {
                document.documentElement.setAttribute("data-theme", "light");
            }
        }

        // Agregar evento para cambiar el tema
        toggleSwitch.addEventListener("change", switchTheme, false);

        // Elementos del formulario
        const name = document.getElementById("name");
        const email = document.getElementById("email");
        const message = document.getElementById("message");
        const contactForm = document.getElementById("contact-form");
        const errorElement = document.getElementById("error");
        const successMsg = document.getElementById("success-msg");
        const submitBtn = document.getElementById("submit");

        // Validación del formulario
        const validate = (e) => {
            e.preventDefault();

            if (name.value.length < 3) {
                errorElement.innerHTML = "Tu nombre debe tener al menos 3 caracteres.";
                return false;
            }

            if (!(email.value.includes(".") && email.value.includes("@"))) {
                errorElement.innerHTML = "Por favor, ingresa una dirección de correo electrónico válida.";
                return false;
            }

            if (!emailIsValid(email.value)) {
                errorElement.innerHTML = "Por favor, ingresa una dirección de correo electrónico válida.";
                return false;
            }

            if (message.value.length < 15) {
                errorElement.innerHTML = "Por favor, escribe un mensaje más largo.";
                return false;
            }

            // Si todas las validaciones pasan
            errorElement.innerHTML = "";
            successMsg.innerHTML = "¡Gracias! Me pondré en contacto contigo lo antes posible.";

            setTimeout(function() {
                successMsg.innerHTML = "";
                document.getElementById("contact-form").reset();
            }, 6000);

            return true;
        };

        // Validación del correo electrónico
        const emailIsValid = (email) => {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        };

        // Agregar evento para el botón de envío
        submitBtn.addEventListener("click", validate);
    </script>

<script src="Landing/js/jquery.js"></script>
<script src="Landing/js/fontawesome.min.js"></script>
<script src="Landing/js/jquery.counterup.min.js"></script>
<script src='Landing/js/jquery.easing.js'></script>
<script src='Landing/js/slick.min.js'></script>
<script src="Landing/js/lightcase.js"></script>
<script src="Landing/js/circular-countdown.js"></script>
<script src="Landing/js/jquery.countdown.min.js"></script>
<script src="Landing/js/waypoints.min.js"></script>
<script src="Landing/js/bootstrap.min.js"></script>
<script src="Landing/js/isotope.pkgd.min.js"></script>
<script src="Landing/js/wow.min.js"></script>
<script src="Landing/js/theia-sticky-sidebar.js"></script>
<script src="Landing/js/swiper.min.js"></script>
<script src="Landing/js/functions.js"></script>

</body>

</html>
