@extends('layouts')

@section('template_title')
    Dashboard Individual - Servicios Ambientales
@endsection
@section('styles')
<style>
    .selector-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .score-card {
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(11, 163, 96, 0.4);
    }
    
    .score-value {
        font-size: 4rem;
        font-weight: bold;
        line-height: 1;
    }
    
    .score-label {
        font-size: 1rem;
        opacity: 0.95;
        margin-top: 10px;
    }
    
    .score-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 15px;
        background: rgba(255,255,255,0.2);
    }
    
    .section-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 15px;
        border-left: 4px solid #0ba360;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
    }
    
    .section-score {
        font-size: 1.8rem;
        font-weight: bold;
    }
    
    .comparison-bar {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    
    .comparison-box {
        flex: 1;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }
    
    .mi-predio {
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: white;
    }
    
    .promedio {
        background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);
        color: white;
    }
    
    .comparison-value {
        font-size: 2rem;
        font-weight: bold;
    }
    
    .comparison-label {
        font-size: 0.8rem;
        opacity: 0.9;
        margin-top: 5px;
    }
    
    .recomendacion-card {
        background: #fff3cd;
        border-left: 4px solid #E49B39;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    
    .recomendacion-area {
        font-weight: 600;
        color: #E49B39;
        font-size: 0.85rem;
        margin-bottom: 5px;
    }
    
    .recomendacion-texto {
        color: #333;
        font-size: 0.9rem;
    }
    
    .fortaleza-card {
        background: #d4edda;
        border-left: 4px solid #0ba360;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        text-align: center;
    }
    
    .fortaleza-texto {
        color: #0ba360;
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .service-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 12px;
        border-left: 3px solid #0ba360;
    }
    
    .service-item-no {
        background: #fff;
        border-left: 3px solid #ddd;
        opacity: 0.7;
    }
    
    .service-name {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    
    .service-details {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
    }
    
    .service-my-value {
        color: #0ba360;
        font-weight: bold;
    }
    
    .service-avg-value {
        color: #666;
    }
    
    .explanation-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }
</style>
@endsection


@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('dashboard.servicios.ambientales.consolidado') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Individual - Servicios Ambientales</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')


<div class="container-fluid">
    <!-- Selector de Predio -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="selector-card">
                <h5><i class="bi bi-geo-alt-fill"></i> Seleccionar Predio</h5>
                <p class="text-muted">Selecciona un predio para ver su evaluación de servicios ambientales</p>
                
                <form method="GET" action="{{ route('dashboard.servicios.ambientales.individual') }}">
                    <div class="row">
                        <div class="col-md-8">
                            <select name="predio" class="form-select" onchange="this.form.submit()" required>
                                <option value="">-- Selecciona un predio --</option>
                                @foreach($todosPredios as $p)
                                    <option value="{{ $p->id }}" {{ $predioId == $p->id ? 'selected' : '' }}>
                                        {{ $p->nombre_predio }} ({{ $p->municipio }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('dashboard.servicios.ambientales.consolidado') }}" class="btn btn-outline-primary">
                                <i class="bi bi-bar-chart"></i> Ver Dashboard Regional
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @if($predioId && isset($predio))
        <!-- Título del Predio -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <h4 class="mb-0">
                        <i class="bi bi-building"></i> {{ $predio->nombre_predio }}
                        <span class="badge bg-secondary ms-2">{{ $predio->municipio }}</span>
                    </h4>
                </div>
            </div>
        </div>
        
        <!-- Scores Principales -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="score-card">
                    <div class="score-value">{{ $scoreGeneral }}</div>
                    <div class="score-label">SCORE DE SOSTENIBILIDAD</div>
                    @php
                        if($scoreGeneral >= 70) { $texto = '⭐ EXCELENTE'; }
                        elseif($scoreGeneral >= 50) { $texto = '✓ BUENO'; }
                        elseif($scoreGeneral >= 30) { $texto = '⚡ MODERADO'; }
                        else { $texto = '⚠ BAJO'; }
                    @endphp
                    <div class="score-badge">{{ $texto }}</div>
                    <div style="margin-top: 15px; font-size: 0.85rem;">
                        0 = Sin servicios | 100 = Máxima sostenibilidad
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="explanation-box">
                    <strong><i class="bi bi-info-circle"></i> ¿Qué mide este score?</strong>
                    <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                        El <strong>Score de Sostenibilidad</strong> evalúa tu compromiso con el medio ambiente basado en 3 factores:
                    </p>
                    <ul style="margin: 10px 0 0 20px; font-size: 0.85rem;">
                        <li><strong>Diversidad de servicios (40%):</strong> Cuántos tipos diferentes implementas (de 9 posibles)</li>
                        <li><strong>Extensión dedicada (40%):</strong> Cuántas hectáreas destinas a conservación/restauración</li>
                        <li><strong>Comparación regional (20%):</strong> Si estás por encima o debajo del promedio municipal</li>
                    </ul>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                            <i class="bi bi-list-check" style="font-size: 2rem; color: #0ba360;"></i>
                            <div style="font-size: 2.5rem; font-weight: bold; color: #333; margin: 10px 0;">{{ $cantidadServicios }}</div>
                            <div style="font-size: 0.85rem; color: #666;">Servicios Implementados</div>
                            <small style="color: #999; font-size: 0.75rem;">De 9 posibles</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                            <i class="bi bi-map" style="font-size: 2rem; color: #667eea;"></i>
                            <div style="font-size: 2.5rem; font-weight: bold; color: #333; margin: 10px 0;">{{ number_format($misHectareasTotales, 1) }}</div>
                            <div style="font-size: 0.85rem; color: #666;">Hectáreas Totales</div>
                            <small style="color: #999; font-size: 0.75rem;">Destinadas a servicios</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                            <i class="bi bi-{{ $misHectareasTotales >= $promedioHectareasMunicipal ? 'arrow-up-circle' : 'arrow-down-circle' }}" 
                               style="font-size: 2rem; color: {{ $misHectareasTotales >= $promedioHectareasMunicipal ? '#0ba360' : '#f5576c' }};"></i>
                            <div style="font-size: 2.5rem; font-weight: bold; color: {{ $misHectareasTotales >= $promedioHectareasMunicipal ? '#0ba360' : '#f5576c' }}; margin: 10px 0;">
                                {{ $misHectareasTotales >= $promedioHectareasMunicipal ? '+' : '' }}{{ number_format($misHectareasTotales - $promedioHectareasMunicipal, 1) }}
                            </div>
                            <div style="font-size: 0.85rem; color: #666;">
                                {{ $misHectareasTotales >= $promedioHectareasMunicipal ? 'Por encima' : 'Por debajo' }} del promedio
                            </div>
                            <small style="color: #999; font-size: 0.75rem;">Promedio: {{ number_format($promedioHectareasMunicipal, 1) }} ha</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Fortalezas y Recomendaciones -->
        <div class="row mb-4">
            @if(count($fortalezas) > 0)
            <div class="col-md-6">
                <h5 style="color: #0ba360; margin-bottom: 15px;"><i class="bi bi-trophy-fill"></i> FORTALEZAS AMBIENTALES</h5>
                @foreach($fortalezas as $fortaleza)
                    <div class="fortaleza-card">
                        <div class="fortaleza-texto">✓ {{ $fortaleza }}</div>
                    </div>
                @endforeach
            </div>
            @endif
            
            @if(count($recomendaciones) > 0)
            <div class="col-md-{{ count($fortalezas) > 0 ? '6' : '12' }}">
                <h5 style="color: #E49B39; margin-bottom: 15px;"><i class="bi bi-lightbulb-fill"></i> RECOMENDACIONES</h5>
                @foreach(collect($recomendaciones)->take(4) as $rec)
                    <div class="recomendacion-card">
                        <div class="recomendacion-area">{{ $rec['area'] }}</div>
                        <div class="recomendacion-texto">{{ $rec['texto'] }}</div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Resumen Visual -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-card">
                    <div class="section-title">
                        <i class="bi bi-pie-chart-fill"></i> Resumen de Servicios Ambientales
                    </div>
                    <div class="explanation-box" style="border-left-color: #0ba360; margin-top: 15px;">
                        <strong>📊 Interpretación de tus datos:</strong>
                        <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                            @if($cantidadServicios == 0)
                                ⚠ <strong>No tienes servicios ambientales registrados.</strong> 
                                Los servicios ambientales son prácticas que protegen la naturaleza mientras produces. 
                                Ejemplos: conservar bosques, plantar árboles, proteger fuentes de agua.
                            @elseif($cantidadServicios <= 2)
                                ⚡ Tienes <strong>{{ $cantidadServicios }} {{ $cantidadServicios == 1 ? 'servicio' : 'servicios' }}</strong> implementados, 
                                lo cual es un buen inicio. Ampliar a más servicios aumenta la resiliencia ambiental del predio.
                            @elseif($cantidadServicios <= 5)
                                ✓ Tienes <strong>{{ $cantidadServicios }} servicios</strong> implementados. 
                                Esto demuestra un compromiso activo con la sostenibilidad ambiental.
                            @else
                                ⭐ <strong>¡Excelente!</strong> Tienes {{ $cantidadServicios }} servicios diferentes implementados. 
                                Esto demuestra un alto compromiso con la conservación y el uso sostenible de los recursos naturales.
                            @endif
                        </p>
                        <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                            <strong>Extensión total:</strong> {{ number_format($misHectareasTotales, 1) }} hectáreas destinadas a servicios ambientales.
                            @if($misHectareasTotales >= $promedioHectareasMunicipal)
                                Esto está <strong style="color: #0ba360;">{{ number_format($misHectareasTotales - $promedioHectareasMunicipal, 1) }} ha por encima</strong> del promedio municipal ({{ number_format($promedioHectareasMunicipal, 1) }} ha). ✓
                            @elseif($misHectareasTotales > 0)
                                Esto está <strong style="color: #E49B39;">{{ number_format($promedioHectareasMunicipal - $misHectareasTotales, 1) }} ha por debajo</strong> del promedio municipal ({{ number_format($promedioHectareasMunicipal, 1) }} ha).
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Comparación Detallada -->
        <div class="row">
            <div class="col-12">
                <h5 style="color: #0ba360; margin-bottom: 20px;">
                    <i class="bi bi-list-ul"></i> COMPARACIÓN DETALLADA POR TIPO DE SERVICIO
                </h5>
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 20px;">
                    Lista completa de los 9 servicios ambientales posibles. Los que tienes aparecen en verde, los que no en gris.
                </p>
            </div>
        </div>
        
        @foreach($comparacionServicios as $servicio)
        <div class="row mb-3">
            <div class="col-12">
                <div class="service-item {{ $servicio['tengo_servicio'] ? '' : 'service-item-no' }}">
                    <div class="service-name">
                        {{ $servicio['tengo_servicio'] ? '✓' : '✗' }} {{ $servicio['nombre'] }}
                    </div>
                    
                    @if($servicio['tengo_servicio'])
                        <div class="service-details">
                            <div>
                                <span class="service-my-value">
                                    {{ number_format($servicio['mis_hectareas'], 1) }} hectáreas
                                </span>
                                en mi predio
                                @if($servicio['materiales'])
                                    <br><small style="color: #666;">Materiales: {{ $servicio['materiales'] }}</small>
                                @endif
                            </div>
                            <div class="service-avg-value">
                                Promedio municipal: {{ number_format($servicio['promedio_hectareas'], 1) }} ha
                            </div>
                        </div>
                        
                        @if($servicio['mis_hectareas'] > 0)
                        <div style="margin-top: 10px;">
                            @php
                                $porcentajeComparacion = $servicio['promedio_hectareas'] > 0 
                                    ? min(($servicio['mis_hectareas'] / $servicio['promedio_hectareas']) * 100, 150) 
                                    : 100;
                            @endphp
                            <div style="background: #f0f0f0; height: 8px; border-radius: 10px; overflow: hidden;">
                                <div style="width: {{ $porcentajeComparacion }}%; height: 100%; background: {{ $servicio['mis_hectareas'] >= $servicio['promedio_hectareas'] ? '#0ba360' : '#E49B39' }}; transition: width 0.3s;"></div>
                            </div>
                            <small style="color: #999; font-size: 0.75rem;">
                                @if($servicio['mis_hectareas'] >= $servicio['promedio_hectareas'])
                                    ✓ Por encima del promedio municipal
                                @else
                                    {{ number_format((($servicio['mis_hectareas'] / $servicio['promedio_hectareas']) * 100), 0) }}% del promedio municipal
                                @endif
                            </small>
                        </div>
                        @endif
                    @else
                        <div style="font-size: 0.85rem; color: #999;">
                            No implementado en este predio. 
                            @if($servicio['promedio_hectareas'] > 0)
                                Promedio en {{ $predio->municipio }}: {{ number_format($servicio['promedio_hectareas'], 1) }} ha
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        
    @else
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                    <h4>Selecciona un predio para ver su análisis</h4>
                    <p>Usa el selector de arriba para elegir el predio que deseas analizar</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection