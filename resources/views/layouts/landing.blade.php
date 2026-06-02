<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Moollish - Plataforma de Gestión Ganadera">
    <title>@yield('title', 'Moollish - Gestión Ganadera')</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/moolish/favicon.ico') }}" type="image/x-icon">

    <!-- ========== CSS ========== -->
    <link href="{{ asset('assets/moolish/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/elegant-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/flaticon-set.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/validnavs.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/moolish/css/style.css') }}" rel="stylesheet">
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
        
        @stack('styles')
</head>

<body>
    <!-- Loader -->
    <div class="se-pre-con"></div>

    <!-- 🔹 Header Superior -->
    @include('layouts.partials.header')

    <!-- 🔹 Navbar -->
    @include('layouts.partials.navbar')

    <!-- 🔹 Contenido principal -->
    <main>
        @yield('content')
        
       <!-- 🔹 Botón flotante de WhatsApp -->
        <a href="https://wa.me/573143963815?text=¡Hola!%20Quisiera%20más%20información%20sobre%20Moollish."
           class="whatsapp-float"
           target="_blank"
           aria-label="Chat en WhatsApp">
            <i class="fab fa-whatsapp my-float"></i>
        </a>
        
        <style>
            .whatsapp-float {
                position: fixed;
                width: 60px;
                height: 60px;
                bottom: 25px;
                right: 25px;
                background-color: #25d366;
                color: #fff;
                border-radius: 50%;
                text-align: center;
                font-size: 32px;
                box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
                z-index: 9999;
                transition: all 0.3s ease-in-out;
            }
        
            .whatsapp-float:hover {
                background-color: #20ba5a;
                transform: scale(1.1);
                color: #fff;
            }
        
            .my-float {
                margin-top: 13px;
            }
        
            @keyframes bounceIn {
                0% { transform: scale(0); opacity: 0; }
                60% { transform: scale(1.2); opacity: 1; }
                100% { transform: scale(1); }
            }
        
            .whatsapp-float {
                animation: bounceIn 0.8s ease;
            }
        </style>


        
    </main>

    <!-- 🔹 Footer -->
    @include('layouts.partials.footer')

    <!-- ========== JS ========== -->
    <script src="{{ asset('assets/moolish/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/modernizr.custom.13711.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/progress-bar.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/circle-progress.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/count-to.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/jquery.scrolla.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/YTPlayer.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/TweenMax.min.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/validnavs.js') }}"></script>
    <script src="{{ asset('assets/moolish/js/main.js') }}"></script>
    

    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>

    @stack('scripts')
</body>
</html>
