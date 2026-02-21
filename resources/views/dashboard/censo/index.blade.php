@extends('layouts')

@section('template_title')
    Dashboards de Censo Animal
@endsection
@section('styles')
<style>
    #content-container:before {
        content: '';
        display: block;
        height: 165px;
        width: 100%;
        position: absolute;
        background-color: #667eea !important;
        z-index: 0;
    }
    
    .censo-card {
        position: relative;
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
    }
    
    .censo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .censo-card-body {
        padding: 20px 15px;
        text-align: center;
    }
    
    .censo-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 15px;
    }
    
    .censo-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        min-height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .censo-buttons {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-censo {
        width: 100%;
        padding: 8px 12px;
        font-size: 0.8rem;
        border-radius: 6px;
        transition: all 0.3s;
        border: none;
    }
    
    .btn-regional {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-regional:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: scale(1.05);
    }
    
    .btn-comparativo {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .btn-comparativo:hover {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
        transform: scale(1.05);
    }
    
    .btn-individual {
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: white;
    }
    
    .btn-individual:hover {
        background: linear-gradient(135deg, #3cba92 0%, #0ba360 100%);
        transform: scale(1.05);
    }
    
    .btn-consolidado {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        color: #333;
        font-weight: 600;
    }
    
    .btn-consolidado:hover {
        background: linear-gradient(135deg, #ffed4e 0%, #ffd700 100%);
        transform: scale(1.05);
    }
    
    .info-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
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
        font-size: 0.9rem;
    }
    
    .consolidado-card {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        border: none;
        box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
    }
</style>
@endsection


@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboards.index') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Censo Animal</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')


<div class="container-fluid">
    <!-- Banner Informativo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-banner">
                <h4><i class="bi bi-clipboard-data"></i> Dashboards de Censo Animal</h4>
                <p>
                    <strong>Analiza las poblaciones animales</strong> por especie. 
                    Cada tipo de animal tiene <strong>3 vistas de análisis:</strong> 
                    <strong>Regional</strong> (datos consolidados), 
                    <strong>Comparativo</strong> (comparación entre municipios), e 
                    <strong>Individual</strong> (análisis de un predio específico).
                </p>
            </div>
        </div>
    </div>
    
    <!-- Cards de Especies -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="hstack justify-content-between mb-4">
                        <div>
                            <h5 class="mb-1">ANÁLISIS POR ESPECIE</h5>
                            <small class="text-muted">Selecciona una especie para ver sus dashboards</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- BOVINOS -->
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card censo-card border border-2">
                                <div class="censo-card-body">
                                    <img src="{{ asset('img/sesion/bocinos.png') }}" class="censo-icon" alt="Bovinos">
                                    <div class="censo-title">BOVINOS</div>
                                    <div class="censo-buttons">
                                        <a href="{{ route('dashboard.censo.bovinos') }}" class="btn btn-regional btn-censo">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.comparativo.bovinos') }}" class="btn btn-comparativo btn-censo">
                                            <i class="bi bi-diagram-3"></i> Comparativo
                                        </a>
                                        <a href="{{ route('dashboard.individual.bovinos') }}" class="btn btn-individual btn-censo">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- BUFALINOS -->
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card censo-card border border-2">
                                <div class="censo-card-body">
                                    <img src="{{ asset('img/sesion/bufalinos.png') }}" class="censo-icon" alt="Bufalinos">
                                    <div class="censo-title">BUFALINOS</div>
                                    <div class="censo-buttons">
                                        <a href="{{ route('dashboard.censo.bufalinos') }}" class="btn btn-regional btn-censo">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.comparativo.bufalinos') }}" class="btn btn-comparativo btn-censo">
                                            <i class="bi bi-diagram-3"></i> Comparativo
                                        </a>
                                        <a href="{{ route('dashboard.individual.bufalinos') }}" class="btn btn-individual btn-censo">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- PORCINOS -->
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card censo-card border border-2">
                                <div class="censo-card-body">
                                    <img src="{{ asset('img/sesion/porcinos.png') }}" class="censo-icon" alt="Porcinos">
                                    <div class="censo-title">PORCINOS</div>
                                    <div class="censo-buttons">
                                        <a href="{{ route('dashboard.censo.porcinos') }}" class="btn btn-regional btn-censo">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.comparativo.porcinos') }}" class="btn btn-comparativo btn-censo">
                                            <i class="bi bi-diagram-3"></i> Comparativo
                                        </a>
                                        <a href="{{ route('dashboard.individual.porcinos') }}" class="btn btn-individual btn-censo">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ÉQUIDOS -->
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card censo-card border border-2">
                                <div class="censo-card-body">
                                    <img src="{{ asset('img/sesion/equidos.png') }}" class="censo-icon" alt="Équidos">
                                    <div class="censo-title">ÉQUIDOS</div>
                                    <div class="censo-buttons">
                                        <a href="{{ route('dashboard.censo.equidos') }}" class="btn btn-regional btn-censo">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.comparativo.equidos') }}" class="btn btn-comparativo btn-censo">
                                            <i class="bi bi-diagram-3"></i> Comparativo
                                        </a>
                                        <a href="{{ route('dashboard.individual.equidos') }}" class="btn btn-individual btn-censo">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- OVINOS Y CAPRINOS -->
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card censo-card border border-2">
                                <div class="censo-card-body">
                                    <img src="{{ asset('img/sesion/ovinos y caprios.png') }}" class="censo-icon" alt="Ovinos y Caprinos" style="width: 90px;">
                                    <div class="censo-title">OVINOS Y CAPRINOS</div>
                                    <div class="censo-buttons">
                                        <a href="{{ route('dashboard.censo.ovino.caprino') }}" class="btn btn-regional btn-censo">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.comparativo.ovino.caprino') }}" class="btn btn-comparativo btn-censo">
                                            <i class="bi bi-diagram-3"></i> Comparativo
                                        </a>
                                        <a href="{{ route('dashboard.individual.ovino.caprino') }}" class="btn btn-individual btn-censo">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- PECES -->
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card censo-card border border-2">
                                <div class="censo-card-body">
                                    <img src="{{ asset('img/sesion/peces.png') }}" class="censo-icon" alt="Peces">
                                    <div class="censo-title">PECES</div>
                                    <div class="censo-buttons">
                                        <a href="{{ route('dashboard.censo.peces') }}" class="btn btn-regional btn-censo">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <a href="{{ route('dashboard.comparativo.peces') }}" class="btn btn-comparativo btn-censo">
                                            <i class="bi bi-diagram-3"></i> Comparativo
                                        </a>
                                        <a href="{{ route('dashboard.individual.peces') }}" class="btn btn-individual btn-censo">
                                            <i class="bi bi-building"></i> Individual
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- CRUSTÁCEOS -->
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card censo-card border border-2">
                                <div class="censo-card-body">
                                    <img src="{{ asset('img/sesion/crustaceos.png') }}" class="censo-icon" alt="Crustáceos">
                                    <div class="censo-title">CRUSTÁCEOS</div>
                                    <div class="censo-buttons">
                                        <a href="{{ route('dashboard.censo.crustaceos') }}" class="btn btn-regional btn-censo">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <button class="btn btn-censo" style="background: #ddd; color: #666;" disabled>
                                            <i class="bi bi-diagram-3"></i> Comparativo
                                        </button>
                                        <button class="btn btn-censo" style="background: #ddd; color: #666;" disabled>
                                            <i class="bi bi-building"></i> Individual
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- AVES COMERCIALES -->
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card censo-card border border-2">
                                <div class="censo-card-body">
                                    <img src="{{ asset('img/sesion/aves comerciales .png') }}" class="censo-icon" alt="Aves Comerciales">
                                    <div class="censo-title">AVES COMERCIALES</div>
                                    <div class="censo-buttons">
                                        <a href="{{ route('dashboard.censo.aves.comerciales') }}" class="btn btn-regional btn-censo">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <button class="btn btn-censo" style="background: #ddd; color: #666;" disabled>
                                            <i class="bi bi-diagram-3"></i> Comparativo
                                        </button>
                                        <button class="btn btn-censo" style="background: #ddd; color: #666;" disabled>
                                            <i class="bi bi-building"></i> Individual
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- AVES TRASPATIO -->
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card censo-card border border-2">
                                <div class="censo-card-body">
                                    <img src="{{ asset('img/sesion/otras aves.png') }}" class="censo-icon" alt="Otras Aves">
                                    <div class="censo-title">OTRAS AVES (TRASPATIO)</div>
                                    <div class="censo-buttons">
                                        <a href="{{ route('dashboard.censo.aves.traspatio') }}" class="btn btn-regional btn-censo">
                                            <i class="bi bi-bar-chart"></i> Regional
                                        </a>
                                        <button class="btn btn-censo" style="background: #ddd; color: #666;" disabled>
                                            <i class="bi bi-diagram-3"></i> Comparativo
                                        </button>
                                        <button class="btn btn-censo" style="background: #ddd; color: #666;" disabled>
                                            <i class="bi bi-building"></i> Individual
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Dashboard Consolidado -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card consolidado-card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-pie-chart-fill" style="font-size: 3rem; color: #333;"></i>
                    <h4 class="mt-3 mb-2" style="color: #333;">Dashboard Consolidado de Censo</h4>
                    <p class="mb-3" style="color: #555;">Vista general de todas las especies en la región</p>
                    <a href="{{ route('dashboard.censo.consolidado') }}" class="btn btn-consolidado btn-lg">
                        <i class="bi bi-grid-3x3"></i> Ver Dashboard Consolidado
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Cards -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card" style="background: #e7f3ff; border-left: 4px solid #667eea;">
                <div class="card-body">
                    <h6 class="mb-2"><i class="bi bi-bar-chart"></i> Regional</h6>
                    <p class="mb-0" style="font-size: 0.85rem;">
                        Datos consolidados de la especie en <strong>toda la región</strong> o municipio seleccionado.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="background: #ffe6ef; border-left: 4px solid #f5576c;">
                <div class="card-body">
                    <h6 class="mb-2"><i class="bi bi-diagram-3"></i> Comparativo</h6>
                    <p class="mb-0" style="font-size: 0.85rem;">
                        Compara datos de la especie entre <strong>diferentes municipios</strong>.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="background: #d4edda; border-left: 4px solid #0ba360;">
                <div class="card-body">
                    <h6 class="mb-2"><i class="bi bi-building"></i> Individual</h6>
                    <p class="mb-0" style="font-size: 0.85rem;">
                        Analiza un <strong>predio específico</strong> y compáralo con el promedio.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection