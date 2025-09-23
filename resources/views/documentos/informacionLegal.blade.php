@extends('layouts')

@section('title')
    Información Legal - Moollish
@endsection

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<style>
    /* Reutilizamos estilos similares a politicaUso.blade.php */
    .dashboard-container { }
    .dashboard-card {
        background: white;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 0; /* Sin padding principal */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        overflow: hidden; /* Asegurar que el banner no desborde */
    }
    .card-header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        border-bottom: 1px solid #E5E7EB;
        position: relative; /* Para z-index si es necesario */
        z-index: 10;
        background: white; /* Asegurar que el título esté sobre el banner */
    }
    .card-title {
        font-size: 23px;
        font-weight: 700;
        color: #292929;
        margin: 0;
    }

    /* Estilos del Banner (Copiados de politicaUso) */
    .policy-banner-container {
        width: 100%;
        height: 200px; /* Altura del banner, ajusta según necesites */
        overflow: hidden; /* Para contener la imagen */
        margin-bottom: 0; /* Sin margen inferior para pegar con el contenido */
        line-height: 0; /* Evitar espacio extra debajo de la imagen */
    }

    .policy-banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Cubrir el área manteniendo la relación de aspecto */
        object-position: center; /* Centrar la imagen */
        border-radius: 0px;
    }

    .legal-content {
        padding: 25px;
        font-size: 15px;
        color: #4B5563;
        line-height: 1.7;
    }
    .legal-content h3 {
        font-size: 18px;
        font-weight: 600;
        color: #E49B39; /* Color de acento */
        margin-top: 25px;
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
    }
    .legal-content h3:first-of-type {
        margin-top: 0;
    }
    .legal-content p {
        margin-bottom: 1em;
    }
    .legal-content ul {
        margin-bottom: 1em;
        padding-left: 20px;
    }
     .legal-content li {
        margin-bottom: 0.5em;
    }
    .important-notice {
        /* margin-top: 30px; <- Quitar margen superior ya que va al inicio */
        margin-bottom: 30px; /* Añadir margen inferior para separar del resto */
        padding: 15px;
        background-color: #FFF8F0; /* Fondo suave */
        border: 1px solid #E49B39;
        border-radius: 8px;
        font-size: 14px;
        color: #C97917;
    }
    .important-notice strong {
        color: #E49B39;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="dashboard-card">

        <!-- Título -->
        <div class="card-header-container">
            <div>
                <h2 class="card-title">Información Legal de Moollish</h2>
            </div>
        </div>

        <!-- Banner -->
        <div class="policy-banner-container">
            {{-- Reemplaza la URL con la ruta a tu imagen de banner --}}
            <img src="{{ asset('img/56199.jpg') }}"
                 alt="Banner Información Legal Moollish"
                 class="policy-banner-image">
        </div>

        <!-- Contenido Legal -->
        <div class="legal-content">

            <div class="important-notice">
                <strong>Aviso Importante:</strong> La información proporcionada en esta página tiene carácter meramente informativo y no constituye asesoramiento legal. Le recomendamos encarecidamente que consulte con un profesional legal calificado para obtener asesoramiento específico adaptado a su situación y para asegurar el cumplimiento de todas las normativas aplicables. Moollish S.A.S no se hace responsable de las decisiones tomadas basadas únicamente en esta información.
            </div>

            <h3>Términos y Condiciones Generales</h3>
            <p>Bienvenido a Moollish. Al acceder y utilizar nuestra plataforma de software como servicio (SaaS), usted acepta estar sujeto a los siguientes términos y condiciones. Si no está de acuerdo con alguna parte de estos términos, no debe utilizar nuestros servicios.</p>
            <p>Moollish proporciona herramientas para la gestión ganadera y agrícola. El uso de la plataforma está destinado a usuarios registrados y activos según el plan de suscripción contratado.</p>

            <h3>Licencia de Uso del Software</h3>
            <p>Moollish le otorga una licencia limitada, no exclusiva, intransferible y revocable para utilizar el software Moollish únicamente para sus fines internos de gestión agropecuaria, de acuerdo con su plan de suscripción y estos términos.</p>
            <p>Usted se compromete a no:</p>
            <ul>
                <li>Modificar, adaptar, traducir, realizar ingeniería inversa, descompilar o desensamblar el software.</li>
                <li>Sublicenciar, alquilar, arrendar o transferir sus derechos de uso del software a terceros.</li>
                <li>Utilizar el software para fines ilegales o no autorizados.</li>
                <li>Eliminar o alterar cualquier aviso de derechos de autor, marca registrada u otros avisos de propiedad.</li>
            </ul>

            <h3>Propiedad Intelectual</h3>
            <p>Todo el software, código fuente, código objeto, diseño visual, logotipos, nombres comerciales, documentación y contenido relacionado con Moollish (en adelante, "Propiedad Intelectual") son propiedad exclusiva de Moollish S.A.S o sus licenciantes y están protegidos por las leyes de derechos de autor, marcas registradas y otras leyes de propiedad intelectual aplicables.</p>
            <p>La licencia otorgada no le transfiere ningún derecho de propiedad sobre la Propiedad Intelectual de Moollish.</p>

            <h3>Contenido y Datos del Usuario</h3>
            <p>Usted es el único responsable de la exactitud, calidad, integridad, legalidad, fiabilidad y adecuación de todos los datos e información que ingrese o cargue en Moollish ("Contenido del Usuario"). Usted declara y garantiza que tiene todos los derechos necesarios para ingresar dicho Contenido del Usuario.</p>
            <p>Usted conserva la propiedad de su Contenido del Usuario. Sin embargo, otorga a Moollish una licencia mundial, libre de regalías, para usar, reproducir, modificar (con fines técnicos como la optimización) y mostrar su Contenido del Usuario únicamente en la medida necesaria para proporcionar y mejorar los servicios de Moollish.</p>
            <p>Moollish implementa medidas de seguridad razonables para proteger su Contenido del Usuario, pero no garantiza una seguridad absoluta. Consulte nuestra Política de Privacidad para obtener más detalles sobre cómo manejamos sus datos.</p>

            <h3>Limitación de Responsabilidad</h3>
            <p>Moollish se proporciona "tal cual" y "según disponibilidad". Moollish S.A.S no ofrece garantías de ningún tipo, expresas o implícitas, sobre la operatividad del servicio, la precisión de la información generada, la ausencia de errores o interrupciones.</p>
            <p>En la máxima medida permitida por la ley, Moollish S.A.S no será responsable por ningún daño directo, indirecto, incidental, especial, consecuente o punitivo, incluyendo, entre otros, pérdida de beneficios, datos, uso, fondo de comercio u otras pérdidas intangibles, resultantes de:</p>
            <ul>
                <li>Su acceso o uso o incapacidad de acceder o usar el servicio.</li>
                <li>Cualquier conducta o contenido de terceros en el servicio.</li>
                <li>Cualquier contenido obtenido del servicio.</li>
                <li>Acceso no autorizado, uso o alteración de sus transmisiones o contenido.</li>
            </ul>
            <p>La responsabilidad total de Moollish S.A.S por cualquier reclamación derivada de estos términos o del uso del servicio no excederá el monto total pagado por usted a Moollish durante los seis (6) meses anteriores al evento que dio lugar a la reclamación.</p>

            <h3>Modificaciones a los Términos</h3>
            <p>Moollish S.A.S se reserva el derecho de modificar estos términos y condiciones en cualquier momento. Le notificaremos sobre cambios significativos a través de la plataforma o por correo electrónico. El uso continuado del servicio después de la publicación de los cambios constituirá su aceptación de los nuevos términos.</p>

            <h3>Ley Aplicable y Jurisdicción</h3>
            <p>Estos términos se regirán e interpretarán de acuerdo con las leyes de Colombia, sin tener en cuenta sus disposiciones sobre conflicto de leyes.</p>
            <p>Cualquier disputa que surja de o en relación con estos términos o el uso de Moollish se someterá a la jurisdicción exclusiva de los tribunales de Atlantico/Colombia.</p>

            <h3>Contacto</h3>
            <p>Si tiene alguna pregunta sobre esta Información Legal, puede contactarnos en: ceo@moollish.com, moollishcorreo@gmail.com o a través de nuestra página de <a href="{{ route('contactanos') ?? '#' }}">Contacto</a>.</p>

        </div> <!-- Fin .legal-content -->

    </div>
</div>
@endsection

@section('scripts')
{{-- No se necesitan scripts específicos para esta vista --}}
@endsection
