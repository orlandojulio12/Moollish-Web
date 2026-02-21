@extends('layouts')

@section('template_title')
    Dashboard - Servicios Ambientales
@endsection
@section('styles')
<style>
    .eco-card {
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: white;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(11, 163, 96, 0.4);
    }
    
    .eco-value {
        font-size: 5rem;
        font-weight: bold;
        line-height: 1;
    }
    
    .eco-label {
        font-size: 1.2rem;
        opacity: 0.95;
        margin-top: 10px;
    }
    
    .section-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        border-left: 4px solid #0ba360;
    }
    
    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    
    .section-description {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 20px;
        font-style: italic;
    }
    
    .service-bar {
        margin-bottom: 15px;
        padding: 12px 0;
    }
    
    .service-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .service-name {
        font-size: 0.9rem;
        color: #333;
        font-weight: 500;
        flex: 1;
    }
    
    .service-stats {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .service-hectares {
        font-size: 1.1rem;
        font-weight: bold;
        color: #0ba360;
    }
    
    .service-predios {
        font-size: 0.85rem;
        color: #666;
    }
    
    .progress-bar-custom {
        height: 10px;
        border-radius: 10px;
        background: #f0f0f0;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        transition: width 0.5s;
    }
    
    .metric-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        border: 2px solid #e9ecef;
    }
    
    .metric-value {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
    }
    
    .metric-label {
        font-size: 0.85rem;
        color: #666;
        margin-top: 8px;
    }
    
    .alert-box {
        background: #e7f3ff;
        border-left: 4px solid #667eea;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .top-service {
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 12px;
    }
    
    .top-service-rank {
        font-size: 2rem;
        font-weight: bold;
        opacity: 0.3;
        margin-right: 15px;
    }
    
    .low-service {
        background: #fff3cd;
        border-left: 4px solid #E49B39;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
</style>
@endsection


@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('inicio') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Regional - Servicios Ambientales</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')


<div class="container-fluid">
    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-md-4">
            <select id="municipio_filter" class="form-select" onchange="filtrarMunicipio()">
                <option value="all" {{ $municipio == 'all' ? 'selected' : '' }}>Todos los municipios</option>
                @foreach($municipiosUnicos as $mun)
                    <option value="{{ $mun }}" {{ $municipio == $mun ? 'selected' : '' }}>{{ $mun }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-8 text-end">
            <span class="badge bg-info" style="font-size: 0.9rem; padding: 8px 12px;">
                <i class="bi bi-building"></i> {{ number_format($totalPredios) }} Predios Totales
            </span>
        </div>
    </div>
    
    <!-- Indicador Principal -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="eco-card">
                <div class="eco-value">{{ number_format($totalHectareasServicios, 1) }}</div>
                <div class="eco-label">HECTÁREAS CON SERVICIOS AMBIENTALES</div>
                <div style="margin-top: 15px; font-size: 1rem;">
                    {{ $porcentajeCobertura }}% de predios implementan
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="alert-box">
                <h5><i class="bi bi-tree-fill"></i> ¿Qué son los Servicios Ambientales?</h5>
                <p style="margin-bottom: 10px; font-size: 0.9rem;">
                    Son las prácticas y sistemas productivos que los predios implementan para conservar, 
                    proteger y restaurar los recursos naturales mientras desarrollan actividades agropecuarias.
                </p>
                <ul style="font-size: 0.85rem; margin-bottom: 0;">
                    <li><strong>Conservación de bosques:</strong> Proteger ecosistemas naturales</li>
                    <li><strong>Sistemas agroforestales:</strong> Combinar árboles con cultivos o ganadería</li>
                    <li><strong>Restauración:</strong> Recuperar suelos y áreas degradadas</li>
                    <li><strong>Conectividad:</strong> Cercas vivas y corredores biológicos</li>
                </ul>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="metric-box">
                        <div class="metric-value" style="color: #0ba360;">{{ $prediosConServicios }}</div>
                        <div class="metric-label">Predios con Servicios Ambientales</div>
                        <small style="color: #999; font-size: 0.75rem;">{{ $porcentajeCobertura }}% del total</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="metric-box">
                        <div class="metric-value" style="color: #667eea;">{{ $serviciosPorTipo->count() }}</div>
                        <div class="metric-label">Tipos de Servicios Implementados</div>
                        <small style="color: #999; font-size: 0.75rem;">De 9 servicios posibles</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Distribución por Tipo de Servicio -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-bar-chart-fill"></i> Distribución de Hectáreas por Tipo de Servicio Ambiental
                </div>
                <div class="section-description">
                    📊 Extensión y adopción de cada práctica ambiental en la región
                </div>
                
                @foreach($serviciosPorTipo as $servicio)
                    @php
                        $porcentajePredios = $totalPredios > 0 ? round(($servicio->predios_con_servicio / $totalPredios) * 100) : 0;
                        $porcentajeHectareas = $totalHectareasServicios > 0 ? round(($servicio->hectareas_totales / $totalHectareasServicios) * 100) : 0;
                    @endphp
                    
                    <div class="service-bar">
                        <div class="service-header">
                            <div class="service-name">{{ $servicio->nombre }}</div>
                            <div class="service-stats">
                                <div class="service-hectares">{{ number_format($servicio->hectareas_totales, 1) }} ha</div>
                                <div class="service-predios">
                                    <i class="bi bi-building"></i> {{ $servicio->predios_con_servicio }} predios ({{ $porcentajePredios }}%)
                                </div>
                            </div>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="progress-fill" style="width: {{ $porcentajeHectareas }}%;"></div>
                        </div>
                        <small style="color: #999; font-size: 0.75rem;">
                            {{ $porcentajeHectareas }}% del total de hectáreas con servicios ambientales
                            • Promedio: {{ number_format($servicio->hectareas_promedio, 1) }} ha/predio
                        </small>
                    </div>
                @endforeach
                
                @if($serviciosPorTipo->count() == 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        No se encontraron registros de servicios ambientales en {{ $municipio == 'all' ? 'la región' : $municipio }}.
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Top 3 Servicios Más Implementados -->
    @if($topServicios->count() > 0)
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="section-card">
                <div class="section-title" style="color: #0ba360;">
                    <i class="bi bi-trophy-fill"></i> Top 3 - Servicios Más Implementados
                </div>
                <div class="section-description">
                    🏆 Prácticas ambientales con mayor adopción en la región
                </div>
                
                @foreach($topServicios as $index => $servicio)
                    <div class="top-service" style="display: flex; align-items: center;">
                        <div class="top-service-rank">#{{ $index + 1 }}</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.95rem;">{{ $servicio->nombre }}</div>
                            <div style="font-size: 0.85rem; opacity: 0.9; margin-top: 5px;">
                                <strong>{{ number_format($servicio->hectareas_totales, 1) }} hectáreas</strong>
                                en {{ $servicio->predios_con_servicio }} predios
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Servicios con Menor Implementación -->
        <div class="col-md-6">
            <div class="section-card">
                <div class="section-title" style="color: #E49B39;">
                    <i class="bi bi-exclamation-triangle"></i> Oportunidades de Mejora
                </div>
                <div class="section-description">
                    ⚡ Servicios con baja adopción que podrían ampliarse
                </div>
                
                @foreach($serviciosBajos as $servicio)
                    <div class="low-service">
                        <div style="font-weight: 600; font-size: 0.9rem; color: #E49B39;">{{ $servicio->nombre }}</div>
                        <div style="font-size: 0.85rem; margin-top: 5px;">
                            Solo <strong>{{ $servicio->predios_con_servicio }}</strong> 
                            {{ $servicio->predios_con_servicio == 1 ? 'predio implementa' : 'predios implementan' }} este servicio
                            ({{ number_format($servicio->hectareas_totales, 1) }} ha)
                        </div>
                        @php
                            $porcentaje = $totalPredios > 0 ? round(($servicio->predios_con_servicio / $totalPredios) * 100) : 0;
                        @endphp
                        <div style="margin-top: 8px; font-size: 0.8rem; color: #999;">
                            Cobertura: {{ $porcentaje }}% de predios
                        </div>
                    </div>
                @endforeach
                
                <div style="background: #e7f3ff; padding: 12px; border-radius: 6px; margin-top: 15px; font-size: 0.85rem;">
                    <strong><i class="bi bi-lightbulb"></i> Recomendación:</strong>
                    Promover la adopción de estos servicios mediante programas de incentivos, 
                    capacitación técnica y acompañamiento para mejorar la cobertura ambiental.
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function filtrarMunicipio() {
    const municipio = document.getElementById('municipio_filter').value;
    window.location.href = '{{ route("dashboard.servicios.ambientales.consolidado") }}?municipio=' + municipio;
}
</script>
@endsection