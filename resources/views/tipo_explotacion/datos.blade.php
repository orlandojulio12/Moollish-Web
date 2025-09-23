@extends('layouts')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Estilos para las tarjetas */
    .custom-card-style {
        margin: 15px 0; /* Espacio vertical entre tarjetas individuales */
        border-radius: 8px; /* Esquinas redondeadas */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
        border: 1px solid #e0e0e0; /* Borde gris claro */
        transition: transform 0.2s; /* Transición para hover */
    }

    .custom-card-style:hover {
        transform: scale(1.02); /* Efecto de agrandamiento al pasar el mouse */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Sombra más profunda en hover */
    }

    /* Espacio adicional dentro de las tarjetas */
    .custom-card-style .card-body {
        padding: 20px;
    }

    /* Ajustes de título */
    .page-header-title h5 {
        color: #333;
        font-weight: bold;
    }

    /* Separación entre columnas */
    .col-xxl-3, .col-md-6 {
        padding-left: 15px;
        padding-right: 15px;
    }

    /* Separación entre filas de tarjetas */
    .row {
        margin-left: -15px; /* Ajuste para evitar que se peguen a los bordes */
        margin-right: -15px;
    }
    .col-xxl-3{
        padding-bottom:10px;
    }
    .category-image {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 40px;
        height: 50px;
        border-radius: 50%; /* Opcional: si quieres una imagen circular */
        object-fit: cover;
    }
</style>

<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Dashboard - Resumen de Explotación</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">Tipo Explotación</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <!-- Verificar si hay datos en el resumen -->
            @if(count($summary) == 0)
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No hay datos disponibles para mostrar.
                    </div>
                </div>
            @else
                <!-- Mostrar las tarjetas solo si hay datos -->
                @foreach($summary as $category => $info)
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch custom-card-style">
                            <div class="card-body">
                                <!-- Imagen en la esquina superior derecha -->
                                <img src="{{ asset('img/sesion/' . ($categoryImages[$category] ?? 'default.png')) }}" alt="{{ ucfirst($category) }}" class="category-image">
                                
                                <h3 class="fs-13 fw-semibold text-truncate-1-line">{{ ucfirst($category) }}</h3>
                                <p>Total de Predios: <strong>{{ $info['total'] }}</strong></p>
                                <p>Tipo Más Común: <strong>{{ $info['most_common'] }}</strong> ({{ $info['common_count'] }} predios)</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    
@endsection
