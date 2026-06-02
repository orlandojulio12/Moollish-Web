@extends('layouts.landing')

@section('title', 'Inicio | Moollish')

@section('content')

    <!-- Contenido principal -->
    <main>
        <!-- Start About
    ============================================= -->
        <div class="about-style-one-area bg-gray default-padding">

            <!-- Shape -->
            <div class="shape-right-top ">
                <img src="assets/moolish/img/shape/leaf.png" alt="Image not found">
            </div>
            <!-- End Shape -->

            <div class="container">
                <div class="row align-start">
                    <div class="col-xl-5 col-lg-6 about-style-one pr-50 pr-md-15 pr-xs-15 ">
                        <div class="thumb">
                            <img src="https://moollish.com/img/slogan2.webp" alt="Image Not Found">
                            <div class="sub-item">
                                <img src="assets/moolish/img/moolish/cafe2.jpg" alt="Image Not Found">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-6 about-style-one">
                        <div class="row align-start">
                            <div class="col-xl-7 col-lg-12">
                                <h2 class="heading">Un enfoque <strong>todo en uno</strong> que le permite <br>
                                    impulsar
                                    la
                                    transición agropecuaria</h2>
                                <p style="font-size: 20px; line-height: 1.8;">
                                    Disfruta de soporte técnico todo el año, acceso remoto desde cualquier lugar y la
                                    mejor
                                    relación calidad-precio. Una solución confiable, eficiente y siempre disponible para
                                    optimizar tu finca.
                                </p>
                                <ul class="check-solid-list mt-20">
                                    <li>Soporte al cliente todo el año</li>
                                    <li>Mejor precio garantizado</li>
                                    <li>Accede desde cualquier parte</li>
                                </ul>
                            </div>
                            <div class="col-xl-5 col-lg-12 pl-10 pl-md-15 pl-xs-15">
                                <div class="top-product-item">
                                    <img src="assets/moolish/img/moolish/1.svg" alt="Icon">
                                    <h5><a href="#">Gestión Inteligente</a></h5>
                                    <p>
                                        Optimiza el manejo de tu ganado con herramientas digitales que mejoran la toma
                                        de
                                        decisiones y el control operativo.
                                    </p>
                                </div>
                                <div class="top-product-item">
                                    <img src="assets/moolish/img/moolish/2.svg" alt="Icon">
                                    <h5><a href="#">Datos en Tiempo Real</a></h5>
                                    <p>
                                        Supervisa la salud, producción y ubicación de tus animales desde cualquier
                                        lugar,
                                        con acceso remoto y seguro.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End About -->

        <!-- Start Why Choose Us
    ============================================= -->
        <div class="choose-us-style-one-area overflow-hidden default-padding">
            <div class="container">
                <div class="row align-start">
                    <div class="col-lg-6 choose-us-style-one">
                        <div class="thumb">
                            <img src="https://cdn.pixabay.com/photo/2017/05/29/01/05/brahaman-2352658_960_720.jpg"
                                alt="Image Not Found">
                            <div class="shape">
                                <img class="wow fadeInDown" src="assets/moolish/img/shape/22.png"
                                    alt="Image not found">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 choose-us-style-one">
                        <h5 class="sub-title">Conózcanos</h5>
                        <h2 class="title">Acelera la transformación digital de tu negocio agropecuario, <br>con la
                            mejor
                            solución tecnológica de gestión ganadera</h2>
                        <div class="accordion accordion-regular mt-35" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">
                                        Control total de tu finca
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p style="font-size: 20px; line-height: 1.8;">
                                            Gestiona toda la información de tu hato en tiempo real, desde partos,
                                            pesajes,
                                            tratamientos y más facilitando la toma de decisiones en cada aspecto de la
                                            finca..
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        Aumenta la productividad
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse"
                                    aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p style="font-size: 20px; line-height: 1.8;">
                                            Optimiza la alimentación, salud y reproducción de tu ganado, mejorando la
                                            eficiencia de cada proceso para obtener mayores resultados con menos
                                            esfuerzo.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                        aria-expanded="false" aria-controls="collapseThree">
                                        Seguridad y trazabilidad
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p style="font-size: 20px; line-height: 1.8;">
                                            Monitorea tu finca constantemente, recibe alertas y asegura la trazabilidad
                                            de
                                            tus operaciones ganaderas, permitiéndote prevenir pérdidas y detectar
                                            irregularidades para mantener la seguridad de tu hato.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Why Choose us -->

        <!-- Start Services
    ============================================= -->
        <div class="services-style-one-area bg-gray default-padding bg-gray half-bg-theme">
            <div class="shape-extra">
                <img src="assets/moolish/img/shape/18.png" alt="Image Not Found">
            </div>
            <div class="container">
                <div class="heading-left">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="left-info">
                                <h5 class="sub-title">Quiénes somos</h5>
                                <h2 class="title">Tienes el poder de transformar el futuro del sector</h2>
                            </div>
                        </div>
                        <div class="col-lg-6 offset-lg-1">
                            <div class="right-info">
                                <p>
                                    Moollish es una plataforma digital para la gestión ganadera, que permite controlar
                                    tu
                                    finca en tiempo real, mejorar la productividad, garantizar trazabilidad y cumplir
                                    con
                                    normativas colombianas. Ofrece acceso remoto, soporte continuo y una excelente
                                    relación
                                    calidad-precio para transformar tu operación ganadera hacia un modelo más eficiente
                                    y
                                    sostenible
                                </p>
                                <!-- <a class="btn btn-theme btn-md radius animation" href="#">Discover More</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Sin carrusel, pero manteniendo las clases -->
                        <div class="services-style-one-carousel bg-gray d-flex justify-content-between flex-wrap gap-4">

                            <!-- Single Item -->
                            <div class="swiper-slide flex-fill" style="min-width:300px; max-width: 32%;">
                                <div class="services-style-one text-center h-100 p-3">
                                    <div class="thumb mb-3">
                                        <img src="assets/moolish/img/moolish/riesgos.png" alt="Image Not Found"
                                            class="img-fluid">
                                    </div>
                                    <h5>Mejora de los impactos y reducción de riesgos</h5>
                                    <p>
                                        Optimiza la gestión ganadera mediante análisis estratégicos que reducen riesgos
                                        sanitarios y operativos, facilitando decisiones eficientes y sostenibles.
                                    </p>
                                </div>
                            </div>

                            <!-- Single Item -->
                            <div class="swiper-slide flex-fill" style="min-width:300px; max-width: 32%;">
                                <div class="services-style-one text-center h-100 p-3">
                                    <div class="thumb mb-3">
                                        <img src="assets/moolish/img/moolish/vaca.png" alt="Image Not Found"
                                            class="img-fluid">
                                    </div>
                                    <h5>Fortalezca su marca y la participación de sus miembros</h5>
                                    <p>
                                        Impulsa tu marca ganadera promoviendo transparencia y eficiencia, fortaleciendo
                                        la confianza del equipo y consolidando tu identidad en el sector.
                                    </p>
                                </div>
                            </div>

                            <!-- Single Item -->
                            <div class="swiper-slide flex-fill" style="min-width:300px; max-width: 32%;">
                                <div class="services-style-one text-center h-100 p-3">
                                    <div class="thumb mb-3">
                                        <img src="assets/moolish/img/moolish/cumplimiento.png" alt="Image Not Found"
                                            class="img-fluid">
                                    </div>
                                    <h5>Respuesta a las nuevas normativas</h5>
                                    <p>
                                        Alinea tu ganadería con las normativas colombianas mediante un sistema
                                        inteligente que facilita el cumplimiento verificable de estándares como los del
                                        ICA, el Ministerio de Agricultura y las BPG.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End Services -->
    
     @include('layouts.partials.download-section', [
    'appName' => 'Moollish',
    'googlePlayLink' => 'https://play.google.com/store/apps/details?id=com.orlando76.MollishApp&hl=es_CO',
    'appStoreLink' => 'https://apps.apple.com/app/id0987654321',
    'description' => 'Administra tu negocio fácilmente con Moollish, la app que simplifica tu día a día.'
])


    </main>

@endsection

</html>
