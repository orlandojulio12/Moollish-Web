@extends('layouts.landing')

@section('title', 'Inicio | Moollish')

@section('content')
    <!-- Contenido principal -->
    <main>
        <!-- Preloader Start -->
    <div class="se-pre-con"></div>
    <!-- Preloader Ends -->

    <!-- Start Breadcrumb 
    ============================================= -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Sobre Sat2Farm</h1>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Start What is Sat2Farm 
    ============================================= -->
    <div class="what-is-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center mb-5">
                        <h5 class="sub-title">Tecnología Satelital</h5>
                        <h2 class="title">¿Qué es Sat2Farm?</h2>
                        <div class="devider"></div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="what-is-text">
                        <p style="font-size: 20px; line-height: 1.8;">
                            <strong>Sat2Farm</strong> es una plataforma móvil y basada en la web que utiliza <strong>teledetección satelital, análisis de IA/ML y big data</strong> para recopilar e interpretar datos agrícolas (incluida la salud del suelo, el desarrollo de los cultivos, la humedad, las necesidades de riego, el clima y los riesgos de plagas) y ofrece información útil directamente a tu dispositivo.
                        </p>
                        <div class="mt-4">
                            <a class="btn btn-theme secondary btn-md radius animation" href="#caracteristicas">Explorar Capacidades</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="what-is-image">
                        <img src="{{ asset('assets/moolish/img/moolish/que_es.jpg') }}"  alt="Sat2Farm Platform" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End What is Sat2Farm -->

    <!-- Start Welcome Section 
    ============================================= -->
    <div class="welcome-section">
        <div class="container">
            <div class="welcome-badge">Agricultura de Precisión</div>
            <h2>Bienvenido a Agricultura de Precisión y<br>Decisiones Inteligentes, impulsado por Sat2Farm</h2>
            <p>
                Sat2Farm es un avance tecnológico en la agricultura de <a href="https://satyukt.com" target="_blank" style="color: var(--moollish-primary); font-weight: 600;">Satyukt</a> que permite una gestión agrícola científica y escalable para agricultores a gran escala, cooperativas y aseguradoras agrícolas.
            </p>
        </div>
    </div>
    <!-- End Welcome Section -->

    <!-- Start Capabilities 
    ============================================= -->
    <div class="capabilities-section" id="caracteristicas">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center mb-5">
                        <h5 class="sub-title">Capacidades Principales</h5>
                        <h2 class="title">Explora las Capacidades de Sat2Farm</h2>
                        <div class="devider"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Capability 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card">
                        <div class="capability-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h4>Asesoramiento de Imágenes con IA y ML</h4>
                        <p>Algoritmos de vanguardia analizan imágenes satelitales para la detección temprana del estrés de los cultivos, enfermedades y deficiencias de nutrientes.</p>
                    </div>
                </div>

                <!-- Capability 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card">
                        <div class="capability-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h4>Análisis de pH y Nutrición del Suelo</h4>
                        <p>¡La tecnología patentada estima instantáneamente los niveles de nutrientes del suelo, carbono orgánico, pH y más, todo sin necesidad de tomar muestras de suelo ni visitar el laboratorio!</p>
                    </div>
                </div>

                <!-- Capability 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card">
                        <div class="capability-icon">
                            <i class="fas fa-tint"></i>
                        </div>
                        <h4>Monitoreo de Humedad del Suelo</h4>
                        <p>Realice un seguimiento de las tendencias de humedad en tiempo real y reciba recomendaciones de riego oportunas para ahorrar agua y optimizar los rendimientos.</p>
                    </div>
                </div>

                <!-- Capability 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card">
                        <div class="capability-icon">
                            <i class="fas fa-cloud-sun-rain"></i>
                        </div>
                        <h4>Pronósticos Meteorológicos de 15 Días</h4>
                        <p>Manténgase a la vanguardia de la variabilidad climática con pronósticos confiables para planificar el riego, la siembra y la cosecha.</p>
                    </div>
                </div>

                <!-- Capability 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card">
                        <div class="capability-icon">
                            <i class="fas fa-bug"></i>
                        </div>
                        <h4>Advertencia sobre Plagas y Enfermedades</h4>
                        <p>Las alertas predictivas le ayudan a contrarrestar las amenazas antes de que aparezcan los síntomas, lo que reduce las pérdidas y el uso de pesticidas.</p>
                    </div>
                </div>

                <!-- Capability 6 -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card">
                        <div class="capability-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h4>Calendario y Gestión de Cultivos</h4>
                        <p>Las sugerencias automatizadas alinean actividades como la siembra y la fertilización con datos en tiempo real, mejorando la productividad.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Capabilities -->

    <!-- Start Benefits List
    ============================================= -->
    <div class="benefits-list-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center mb-5">
                        <h5 class="sub-title">Ventajas Clave</h5>
                        <h2>¿Qué Beneficios Ofrece Sat2Farm?</h2>
                        <div class="devider"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="benefit-content">
                            <h5>Precisión</h5>
                            <p>Información práctica para aumentar el rendimiento y reducir costos mediante análisis detallados de cada sector de tu finca.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="benefit-content">
                            <h5>Eficiencia</h5>
                            <p>Ahorre hasta un <strong>30% en el uso de agua</strong> y <strong>50% en insumos</strong> gracias a recomendaciones basadas en datos satelitales en tiempo real.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div class="benefit-content">
                            <h5>Sostenibilidad</h5>
                            <p>Las prácticas basadas en datos reducen el desperdicio y garantizan un crecimiento ecológico, protegiendo el medio ambiente para las futuras generaciones.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="benefit-content">
                            <h5>Accesibilidad</h5>
                            <p>¡No se necesita equipo especial, solo un teléfono inteligente e Internet! Accede a toda la tecnología satelital desde tu móvil.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Benefits List -->

    <!-- Start How it Works
    ============================================= -->
    <div class="how-it-works-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center mb-5">
                        <h5 class="sub-title">Proceso Simple</h5>
                        <h2 class="title">¿Cómo Funciona Sat2Farm?</h2>
                        <div class="devider"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <h5>Traza tus Campos en la App</h5>
                        <p>Define los límites de tu finca en la aplicación móvil o web de manera simple y rápida.</p>
                    </div>

                    <div class="step-item">
                        <div class="step-number">2</div>
                        <h5>Datos Satelitales de Redes Globales</h5>
                        <p>Los datos satelitales se obtienen automáticamente de las redes globales de Satyukt en tiempo real.</p>
                    </div>

                    <div class="step-item">
                        <div class="step-number">3</div>
                        <h5>Análisis Profundo con IA/ML</h5>
                        <p>Los modelos de inteligencia artificial y machine learning integran datos meteorológicos, IoT, UAVs y datos históricos para un análisis profundo y preciso.</p>
                    </div>

                    <div class="step-item">
                        <div class="step-number">4</div>
                        <h5>Información Accionable en tu Dashboard</h5>
                        <p>El panel de control y las alertas brindan información clave: salud de los cultivos, tendencias del suelo, señales de riego y amenazas previstas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End How it Works -->

    <!-- Start Impact Section
    ============================================= -->
    <div class="impact-section">
        <div class="container">
            <h2>El Impacto de Sat2Farm es Real</h2>
            <p>Los usuarios experimentan mejoras significativas en productividad y eficiencia operativa</p>
            
            <div class="impact-stats">
                <div class="impact-stat">
                    <div class="number">+30%</div>
                    <div class="label">Aumento en Productividad de Agua</div>
                </div>
                <div class="impact-stat">
                    <div class="number">5-10%</div>
                    <div class="label">Reducción en Costos de Insumos</div>
                </div>
                <div class="impact-stat">
                    <div class="number">50%</div>
                    <div class="label">Ahorro en Fertilizantes</div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Impact Section -->

    <!-- Start Global Section
    ============================================= -->
    <div class="about-style-two-area default-padding" style="background-image: url(assets/moolish/img/shape/33.png);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="thumb">
                        <img src="assets/moolish/img/Global.jpg" alt="Global Coverage">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="info">
                        <h5 class="sub-title">Cobertura Global</h5>
                        <h2 class="title">Global pero Local</h2>
                        <p>
                            Sat2Farm opera globalmente, soportando múltiples idiomas y cultivos. Es especialmente útil en regiones donde la infraestructura tradicional de análisis de suelo es limitada o inexistente.
                        </p>
                        <p>
                            <strong>Sat2Farm es reconocido por sus capacidades distintivas en nutrientes del suelo y su modelo directo al agricultor (SaaS)</strong>, permitiendo acceso inmediato a tecnología de punta sin inversiones iniciales costosas.
                        </p>
                        <a class="btn btn-theme mt-30 secondary btn-md radius animation" href="index.html">Volver al Inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Global Section -->

    <!-- Start CTA with Moollish
    ============================================= -->
    <div class="process-area default-padding" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h5 class="sub-title">Moollish + Sat2Farm</h5>
                    <h2 class="title">La Combinación Perfecta para tu Ganadería</h2>
                    <p style="font-size: 18px; line-height: 1.8; margin-bottom: 30px;">
                        Integramos la potencia de Sat2Farm con la gestión ganadera de Moollish para ofrecerte una solución completa. Monitorea tus pasturas con tecnología satelital mientras gestionas tu ganado de forma inteligente.
                    </p>
                    <a class="btn btn-theme secondary btn-md radius animation" href="/">Descubre Moollish</a>
                </div>
                <div class="col-lg-6">
                    <img src="assets/moolish/img/moolish/moollish-sat2farm.jpg" alt="Moollish Sat2Farm" class="img-fluid" style="border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
                </div>
            </div>
        </div>
    </div>
    <!-- End CTA -->

 @include('layouts.partials.download-section', [
    'appName' => 'Sat2Farm',
    'googlePlayLink' => 'https://play.google.com/store/apps/details?id=com.satyukt.myfarmapp&hl=es_CO',
    'appStoreLink' => 'https://apps.apple.com/us/app/sat2farm/id6473137667',
    'description' => 'Gestiona tu finca con tecnología satelital avanzada y toma decisiones inteligentes.'
])

@endsection

</html>
