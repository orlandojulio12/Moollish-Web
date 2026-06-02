<header>
    <nav class="navbar mobile-sidenav inc-shape navbar-common navbar-sticky navbar-default validnavs">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('assets/moolish/img/moolish/logo_negro3.png') }}" class="logo" alt="Logo">
                </a>
            </div>

            <div class="main-nav-content">
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{ url('/') }}">Inicio</a></li>
                        <li><a href="{{ url('/login') }}">Moollish</a></li>
                        <li><a href="{{ url('/Sat2Farm') }}">Sat2Farm</a></li>
                        @if (!isset($hideContact) || !$hideContact)
                            <li><a href="{{ url('/contactanos') }}">Contacto</a></li>
                        @endif

                    </ul>
                </div>
                <div class="attr-right">
                    <div class="attr-nav">
                        <ul>
                            <li class="contact">
                                <div class="call">
                                    <div class="icon"><i class="fas fa-comments-alt-dollar"></i></div>
                                    <div class="info">
                                        <p>Tienes dudas?</p>
                                        <h5><a href="mailto:info@moollish.com">info@moollish.com</a></h5>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="overlay-screen"></div>
            </div>
        </div>
    </nav>
</header>
