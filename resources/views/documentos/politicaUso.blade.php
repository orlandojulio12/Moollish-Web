@extends('layouts')

@section('title')
    Política de Uso - Moollish
@endsection

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<style>
    /* Estilos generales */
    .dashboard-container {
        /* padding: 20px; */
    }

    .dashboard-card {
        background: white;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 0; /* Quitar padding principal para controlar secciones */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        overflow: hidden; /* Asegurar que el banner no desborde */
    }

    .card-header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px; /* Añadir padding horizontal */
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

    /* Estilos del Banner */
    .policy-banner-container {
        width: 100%;
        padding: 0px;
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

    .policy-content {
        padding: 25px; /* Padding para el contenido principal */
    }

    /* Estilos para el iframe dentro del modal */
    .pdf-modal-body {
        height: 75vh; /* Altura del cuerpo del modal */
        padding: 0; /* Remover padding si el iframe ocupa todo */
        overflow: hidden; /* Evitar doble scrollbar */
    }

    .pdf-iframe-modal {
        width: 100%;
        height: 100%; /* Ocupar toda la altura del modal body */
        border: none;
    }

    /* Botones de Acción */
    .action-buttons-container {
        margin-top: 15px;
        margin-bottom: 25px;
        display: flex;
        gap: 15px; /* Espacio entre botones */
        flex-wrap: wrap; /* Para que los botones se ajusten en pantallas pequeñas */
    }

    .btn-action-policy {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        font-size: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .btn-download {
        background-color: #E49B39;
        color: white;
    }

    .btn-download:hover {
        background-color: #C97917;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
        color: white;
    }

    .btn-view-online {
        background-color: #f0f0f0;
        color: #333;
        border: 1px solid #dcdcdc;
    }

    .btn-view-online:hover {
        background-color: #e5e5e5;
        border-color: #c8c8c8;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
        color: #333;
    }

    .btn-action-policy .material-symbols-outlined {
        margin-right: 8px;
        font-size: 18px;
    }

    /* Estilo para la descripción */
    .policy-description {
        font-size: 15px;
        color: #4B5563; /* Color de texto más suave */
        line-height: 1.7;
        margin-bottom: 20px;
        background-color: transparent; /* Fondo transparente */
        padding: 0; /* Quitar padding extra */
        border: none; /* Quitar borde */
        border-radius: 0;
    }
    .policy-description p {
        margin-bottom: 1em; /* Espacio entre párrafos */
    }

    /* Ajustes para el modal */
    #politicaPdfModal .modal-dialog {
        max-width: 90%; /* Modal más ancho */
    }
    #politicaPdfModal .modal-header {
         background-color: #f8f9fa;
         border-bottom: 1px solid #dee2e6;
    }
     #politicaPdfModal .modal-title {
        font-weight: 600;
        color: #495057;
    }
    #politicaPdfModal .modal-footer {
        background-color: #f8f9fa;
         border-top: 1px solid #dee2e6;
    }

</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="dashboard-card">

        <!-- Título -->
        <div class="card-header-container">
            <div>
                <h2 class="card-title">Política de Uso de Moollish</h2>
            </div>
        </div>

        <!-- Banner -->
        <div class="policy-banner-container">
            {{-- Reemplaza la URL con la ruta a tu imagen de banner --}}
            <img src="{{ asset('img/16971.jpg') }}"
                 alt="Banner Política de Uso Moollish"
                 class="policy-banner-image">
        </div>

        <!-- Contenido principal de la política -->
        <div class="policy-content">

            <!-- Descripción de la Política de Uso -->
            <div class="policy-description">
                <p>Bienvenido a Moollish. Nuestra Política de Uso establece los términos y condiciones bajo los cuales puedes utilizar nuestra plataforma y servicios. Este documento detalla tus derechos y responsabilidades como usuario, el uso aceptable de la plataforma, la propiedad intelectual, la privacidad de tus datos y las limitaciones de responsabilidad.</p>
                <p>Te recomendamos leer atentamente este documento para comprender cómo operar dentro de Moollish y asegurar una experiencia positiva y segura para toda nuestra comunidad.</p>
            </div>

            <!-- Botones de Acción -->
            <div class="action-buttons-container">
                <a href="{{ asset('pdf/politicadeusomoollish.pdf') }}" download="Politica_de_Uso_Moollish.pdf" class="btn-action-policy btn-download">
                    <span class="material-symbols-outlined">download</span>
                    Descargar (PDF)
                </a>
                <button type="button" class="btn-action-policy btn-view-online" data-bs-toggle="modal" data-bs-target="#politicaPdfModal">
                    <span class="material-symbols-outlined">visibility</span>
                    Visualizar en Línea
                </button>
            </div>

            {{-- El contenedor del PDF ya no se muestra aquí directamente --}}

        </div> <!-- Fin .policy-content -->

    </div>
</div>

<!-- Modal para visualizar PDF -->

@endsection
<div class="modal fade" id="politicaPdfModal" tabindex="-1" aria-labelledby="politicaPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> {{-- Usar modal-xl para más espacio --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="politicaPdfModalLabel">Política de Uso - Moollish</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pdf-modal-body">
                {{-- El iframe se carga aquí dinámicamente --}}
                <iframe id="pdfModalIframe" data-src="{{ asset('pdf/politicadeusomoollish.pdf') }}#toolbar=0&navpanes=0&scrollbar=0" class="pdf-iframe-modal" title="Visor de Política de Uso Moollish">
                    Tu navegador no soporta iframes.
                </iframe>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                 {{-- Aquí podrías añadir un botón de pantalla completa si implementas PDF.js --}}
                 {{-- <button type="button" class="btn btn-primary" id="fullscreenBtn">Pantalla Completa</button> --}}
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pdfModalElement = document.getElementById('politicaPdfModal');
        const pdfIframe = document.getElementById('pdfModalIframe');

        if (pdfModalElement && pdfIframe) {
            // Evento que se dispara JUSTO ANTES de que el modal se muestre
            pdfModalElement.addEventListener('show.bs.modal', function () {
                // Carga el src desde data-src solo si no está ya cargado
                if (!pdfIframe.getAttribute('src')) {
                    pdfIframe.setAttribute('src', pdfIframe.getAttribute('data-src'));
                }
            });

            // Opcional: Limpiar el src cuando el modal se oculta para liberar memoria
            pdfModalElement.addEventListener('hidden.bs.modal', function () {
                // Descomentar la siguiente línea si quieres que el PDF se descargue de memoria al cerrar
                 //pdfIframe.setAttribute('src', '');
            });
        }
    });
</script>
@endsection
