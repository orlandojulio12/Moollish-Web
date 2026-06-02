<footer class="bg-dark text-light" style="background-image: url({{ asset('assets/moolish/img/shape/brush-down.png') }});">
    <div class="container">
        <div class="f-items default-padding">
            <div class="row">
                <div class="col-lg-4 col-md-6 item">
                    <div class="footer-item about">
                        <img class="logo" src="{{ asset('assets/moolish/img/moolish/logo_blanco.png') }}" alt="Logo">
                        <p style="font-size: 20px; line-height: 1.8; margin-bottom: 30px;">
                            Transformando la ganadería colombiana con tecnología de punta. La unión perfecta entre
                            gestión ganadera y agricultura de precisión satelital.
                        </p>
                        <form action="#">
                            <input type="email" placeholder="Tu correo" class="form-control" name="email">
                            <button type="submit">></button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 item">
                    <div class="footer-item link">
                        <h4 class="widget-title">Explora</h4>
                        <ul>
                            <li><a href="{{ url('/') }}">Inicio</a></li>
                            <li><a href="{{ url('/login') }}">Moollish</a></li>
                            <li><a href="{{ url('/Sat2Farm') }}">Sat2Farm</a></li>
                            <li><a href="{{ url('/') }}">Contacto</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 item">
                    <div class="footer-item contact">
                        <h4 class="widget-title">Contáctanos</h4>
                        <ul>
                            <li>
                                <div class="icon"><i class="fas fa-home"></i></div>
                                <div class="content">
                                    <strong>Dirección:</strong> Barranquilla, Colombia
                                </div>
                            </li>
                            <li>
                                <div class="icon"><i class="fas fa-envelope"></i></div>
                                <div class="content">
                                    <strong>Email:</strong>
                                    <a href="mailto:info@moollish.com">info@moollish.com</a>
                                </div>
                            </li>
                            <li>
                                <div class="icon"><i class="fas fa-phone"></i></div>
                                <div class="content">
                                    <strong>Teléfono:</strong>
                                    <a href="tel:+573143963815">+57 3143963815</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 item">
                    <div class="footer-item social">
                        <h4 class="widget-title">Síguenos</h4>
                        <p>Conéctate con nosotros en nuestras redes sociales.</p>
                        <ul class="social-icons d-flex gap-3 list-unstyled mt-3">
                            <li><a href="https://www.facebook.com/share/17LHQQakUs/?mibextid=wwXIfr"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://www.youtube.com/@MoollishMoollish"><i class="fab fa-youtube"></i></a>
                            </li>
                            <li><a href="https://www.instagram.com/moollish/"><i class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parte inferior -->
        <div class="footer-bottom">
            <div class="row">
                <div class="col-lg-6">
                    <p>&copy; <span id="year"></span> Todos los derechos reservados por
                        <a href="#">Moollish</a>
                    </p>
                </div>
                <div class="col-lg-6 text-end">
                    <ul>
                        <li><a href="#">Términos</a></li>
                        <li><a href="#">Privacidad</a></li>
                        <li><a href="#">Soporte</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="shape-right-bottom">
        <img src="{{ asset('assets/moolish/img/shape/10.png') }}" alt="Image Not Found">
    </div>
    <div class="shape-left-bottom">
        <img src="{{ asset('assets/moolish/img/shape/11.png') }}" alt="Image Not Found">
    </div>
</footer>
