@extends('layouts')

@section('template_title')
Dashboards de Análisis
@endsection

@section('styles')

<style>
    #content-container:before {
        content: '';
        display: block;
        height: 165px;
        width: 100%;
        position: absolute;
        background-color: #0ba360 !important;
        z-index: 0;
    }

    .dashboard-card {
        position: relative;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .dashboard-card-body {
        padding: 30px 20px;
        text-align: center;
        position: relative;
    }

    .dashboard-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
    }

    .dashboard-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }

    .dashboard-description {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 20px;
    }

    .dashboard-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-dashboard {
        flex: 1;
        padding: 10px 15px;
        font-size: 0.85rem;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-regional {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .btn-regional:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: scale(1.05);
    }

    .btn-individual {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
    }

    .btn-individual:hover {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
        transform: scale(1.05);
    }

    .card-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #0ba360;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .info-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .info-banner h4 {
        margin-bottom: 10px;
        font-weight: 600;
    }

    .info-banner p {
        margin: 0;
        opacity: 0.95;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('page-header')
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5>Dashboards de Análisis - Caracterización Ganadera</h5>
        </div>
    </div>
</div>
@endsection

@section('content')



<div class="container-fluid">
    <!-- Banner Informativo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-banner">
                <h4><i class="bi bi-graph-up-arrow"></i> Sistema de Dashboards de Análisis</h4>
                <p>
                    <strong>Visualiza y analiza</strong> los datos de caracterización ganadera de manera integral.
                    Cada módulo ofrece dos vistas: <strong>Regional</strong> (datos consolidados de todos los predios)
                    e <strong>Individual</strong> (análisis específico de un predio con comparaciones).
                </p>
            </div>
        </div>
    </div>

    <!-- Cards de Dashboards -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="hstack justify-content-between mb-4">
                        <div>
                            <h5 class="mb-1">MÓDULOS DE ANÁLISIS</h5>
                            <small class="text-muted">Selecciona un módulo para ver sus dashboards</small>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <!-- 1. INFORMACIÓN DE PREDIOS -->
                        <div class="col-xxl-4 col-lg-6 col-md-6 mb-4">
                            <div class="card dashboard-card border border-2">
                                <div class="card-badge">Módulo 1</div>
                                <div class="dashboard-card-body">
                                    <img src="{{ asset('img/caracterizacion/inofrmacion de predios.png') }}"
                                        class="dashboard-icon" alt="Info Predios">
                                    <div class="dashboard-title">Información de Predios</div>
                                    <div class="dashboard-description">
                                        Áreas, tierras, aguas, pastos, ganado, aspectos ambientales, equipos y gestión
                                    </div>
                                    <div class="dashboard-buttons">
                                        <a href="{{ route('dashboard.info.predios.consolidado') }}"
                                            class="btn btn-regional btn-dashboard">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.info.predios.individual') }}"
                                            class="btn btn-individual btn-dashboard">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. BPG -->
                        <div class="col-xxl-4 col-lg-6 col-md-6 mb-4">
                            <div class="card dashboard-card border border-2">
                                <div class="card-badge">Módulo 2</div>
                                <div class="dashboard-card-body">
                                    <img src="{{ asset('img/caracterizacion/inoformacion para bpg.png') }}"
                                        class="dashboard-icon" alt="BPG">
                                    <div class="dashboard-title">Buenas Prácticas Ganaderas (BPG)</div>
                                    <div class="dashboard-description">
                                        Sanidad, identificación, bioseguridad, medicamentos, alimentación, bienestar
                                        animal
                                    </div>
                                    <div class="dashboard-buttons">
                                        <a href="{{ route('dashboard.bgp.consolidado') }}"
                                            class="btn btn-regional btn-dashboard">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.bgp.individual') }}"
                                            class="btn btn-individual btn-dashboard">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. RIESGO EPIDEMIOLÓGICO -->
                        <div class="col-xxl-4 col-lg-6 col-md-6 mb-4">
                            <div class="card dashboard-card border border-2">
                                <div class="card-badge">Módulo 3</div>
                                <div class="dashboard-card-body">
                                    <img src="{{ asset('img/caracterizacion/riesgo epidemiologio.png') }}"
                                        class="dashboard-icon" alt="Riesgo">
                                    <div class="dashboard-title">Riesgo Epidemiológico</div>
                                    <div class="dashboard-description">
                                        Tipo de explotación, información epidemiológica, factores de riesgo, vigilancia
                                    </div>
                                    <div class="dashboard-buttons">
                                        <a href="{{ route('dashboard.riesgo.epidemiologico.consolidado') }}"
                                            class="btn btn-regional btn-dashboard">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.riesgo.epidemiologico.individual') }}"
                                            class="btn btn-individual btn-dashboard">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. SERVICIOS AMBIENTALES -->
                        <div class="col-xxl-4 col-lg-6 col-md-6 mb-4">
                            <div class="card dashboard-card border border-2">
                                <div class="card-badge">Módulo 4</div>
                                <div class="dashboard-card-body">
                                    <img src="{{ asset('img/caracterizacion/servicios ambientales.png') }}"
                                        class="dashboard-icon" alt="Servicios Ambientales">
                                    <div class="dashboard-title">Servicios Ambientales</div>
                                    <div class="dashboard-description">
                                        Bosques, sistemas agroforestales, cercas vivas, conservación de suelos
                                    </div>
                                    <div class="dashboard-buttons">
                                        <a href="{{ route('dashboard.servicios.ambientales.consolidado') }}"
                                            class="btn btn-regional btn-dashboard">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.servicios.ambientales.individual') }}"
                                            class="btn btn-individual btn-dashboard">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. CENSO (Ejemplo - ajusta según tus necesidades) -->
                        <div class="col-xxl-4 col-lg-6 col-md-6 mb-4">
                            <div class="card dashboard-card border border-2">
                                <div class="card-badge">Módulo 5</div>
                                <div class="dashboard-card-body">
                                    <img src="{{ asset('img/caracterizacion/censo.png') }}" class="dashboard-icon"
                                        alt="Censo">
                                    <div class="dashboard-title">Censo Animal</div>
                                    <div class="dashboard-description">
                                        Bovinos, bufalinos, porcinos, equinos, ovinos, caprinos, aves, peces, abejas
                                    </div>
                                    <div class="dashboard-buttons">
                                        <a href="{{ route('dashboards.censo.index') }}"
                                            class="btn btn-dashboard"
                                            style="background: linear-gradient(135deg, #cf7a2b 0%, #f18b06 100%); color: #333; font-weight: 600;">
                                            <i class="bi bi-lightning-charge"></i> Ver Análisis Censo
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. ANÁLISIS COMPARATIVO -->
                        <div class="col-xxl-4 col-lg-6 col-md-6 mb-4">
                            <div class="card dashboard-card border border-2" style="border-color: #ffd700 !important;">
                                <div class="card-badge" style="background: #ffd700; color: #333;">¡Activo!</div>
                                <div class="dashboard-card-body">
                                    <i class="bi bi-lightning-charge" style="font-size: 4rem; color: #ffd700;"></i>
                                    <div class="dashboard-title">Análisis Comparativo</div>
                                    <div class="dashboard-description">
                                        Comparaciones entre municipios, rankings, tendencias y análisis cruzado
                                    </div>
                                    <div class="dashboard-buttons">
                                        <a href="{{ route('dashboard.analisis.comparativo') }}"
                                            class="btn btn-dashboard"
                                            style="background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%); color: #333; font-weight: 600;">
                                            <i class="bi bi-lightning-charge"></i> Ver Análisis Comparativo
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card" style="background: #e7f3ff; border-left: 4px solid #667eea;">
                <div class="card-body">
                    <h6 class="mb-2"><i class="bi bi-bar-chart"></i> Dashboard Regional</h6>
                    <p class="mb-0" style="font-size: 0.85rem;">
                        Visualiza datos consolidados de <strong>todos los predios</strong> del municipio o región
                        seleccionada.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="background: #ffe6ef; border-left: 4px solid #f5576c;">
                <div class="card-body">
                    <h6 class="mb-2"><i class="bi bi-building"></i> Dashboard Individual</h6>
                    <p class="mb-0" style="font-size: 0.85rem;">
                        Analiza un <strong>predio específico</strong> y compáralo con el promedio municipal.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="background: #d4edda; border-left: 4px solid #0ba360;">
                <div class="card-body">
                    <h6 class="mb-2"><i class="bi bi-funnel"></i> Filtros Disponibles</h6>
                    <p class="mb-0" style="font-size: 0.85rem;">
                        Cada dashboard permite filtrar por <strong>municipio</strong> para análisis específicos.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection