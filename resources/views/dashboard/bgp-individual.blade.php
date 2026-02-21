@extends('layouts')

@section('template_title')
    Dashboard Individual - BPG
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
    
    .practica-no-cumple {
        background: #fff0f2;
        border-left: 3px solid #f5576c;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 8px;
        font-size: 0.85rem;
    }
    
    .status-breakdown {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }
    
    .status-mini {
        flex: 1;
        padding: 8px;
        border-radius: 6px;
        text-align: center;
        font-size: 0.75rem;
    }
    
    .status-si { background: #d4edda; color: #0ba360; font-weight: 600; }
    .status-no { background: #fff0f2; color: #f5576c; font-weight: 600; }
    .status-na { background: #e7f3ff; color: #667eea; font-weight: 600; }
</style>
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('dashboard.bgp.consolidado') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Individual - Buenas Prácticas Ganaderas (BPG)</h5>
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
                <p class="text-muted">Selecciona un predio para ver su análisis de BPG</p>
                
                <form method="GET" action="{{ route('dashboard.bgp.individual') }}">
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
                            <a href="{{ route('dashboard.bgp.consolidado') }}" class="btn btn-outline-primary">
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
                    <div class="score-value">{{ $scoreGeneral }}%</div>
                    <div class="score-label">MI CUMPLIMIENTO BPG</div>
                    @php
                        if($scoreGeneral >= 80) { $texto = '⭐ EXCELENTE'; }
                        elseif($scoreGeneral >= 60) { $texto = '✓ BUENO'; }
                        elseif($scoreGeneral >= 40) { $texto = '⚠ REGULAR'; }
                        else { $texto = '⚡ NECESITA MEJORA'; }
                    @endphp
                    <div class="score-badge">{{ $texto }}</div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                            <i class="bi bi-graph-up" style="font-size: 2rem; color: #E49B39;"></i>
                            <div style="font-size: 2.5rem; font-weight: bold; color: #333; margin: 10px 0;">{{ $promedioGeneral }}%</div>
                            <div style="font-size: 0.85rem; color: #666;">Promedio {{ $predio->municipio }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                            <i class="bi bi-{{ $scoreGeneral >= $promedioGeneral ? 'arrow-up-circle' : 'arrow-down-circle' }}" 
                               style="font-size: 2rem; color: {{ $scoreGeneral >= $promedioGeneral ? '#0ba360' : '#f5576c' }};"></i>
                            <div style="font-size: 2.5rem; font-weight: bold; color: {{ $scoreGeneral >= $promedioGeneral ? '#0ba360' : '#f5576c' }}; margin: 10px 0;">
                                {{ $scoreGeneral >= $promedioGeneral ? '+' : '' }}{{ $scoreGeneral - $promedioGeneral }}%
                            </div>
                            <div style="font-size: 0.85rem; color: #666;">
                                {{ $scoreGeneral >= $promedioGeneral ? 'Arriba' : 'Abajo' }} del promedio
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Fortalezas y Recomendaciones -->
        <div class="row mb-4">
            @if(count($fortalezas) > 0)
            <div class="col-md-6">
                <h5 style="color: #0ba360; margin-bottom: 15px;"><i class="bi bi-star-fill"></i> FORTALEZAS</h5>
                @foreach($fortalezas as $fortaleza)
                    <div class="fortaleza-card">
                        <div class="fortaleza-texto">✓ {{ $fortaleza }}</div>
                    </div>
                @endforeach
            </div>
            @endif
            
            @if(count($recomendaciones) > 0)
            <div class="col-md-{{ count($fortalezas) > 0 ? '6' : '12' }}">
                <h5 style="color: #E49B39; margin-bottom: 15px;"><i class="bi bi-lightbulb-fill"></i> ÁREAS DE MEJORA</h5>
                @foreach(collect($recomendaciones)->take(6) as $rec)
                    <div class="recomendacion-card">
                        <div class="recomendacion-area">{{ $rec['area'] }}</div>
                        <div class="recomendacion-texto">{{ $rec['texto'] }}</div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Comparación por Sección -->
        <div class="row">
            <div class="col-12">
                <h5 style="color: #0ba360; margin-bottom: 20px;">
                    <i class="bi bi-bar-chart-fill"></i> COMPARACIÓN POR SECCIÓN vs PROMEDIO MUNICIPAL
                </h5>
            </div>
        </div>
        
        @foreach($datosPorSeccion as $index => $seccion)
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card" style="border-left-color: {{ ['#0ba360', '#4facfe', '#E49B39', '#f5576c', '#667eea', '#764ba2', '#3cba92', '#f093fb'][$index % 8] }};">
                    <div class="section-header">
                        <div>
                            <div class="section-title">{{ $index + 1 }}. {{ $seccion['nombre'] }}</div>
                            <small class="text-muted">{{ $seccion['total_practicas'] }} prácticas</small>
                        </div>
                        <div class="section-score" style="color: {{ $seccion['mi_porcentaje'] >= $seccion['promedio_porcentaje'] ? '#0ba360' : '#f5576c' }};">
                            {{ $seccion['mi_porcentaje'] }}%
                        </div>
                    </div>
                    
                    <div class="comparison-bar">
                        <div class="comparison-box mi-predio">
                            <div class="comparison-value">{{ $seccion['mi_porcentaje'] }}%</div>
                            <div class="comparison-label">MI PREDIO</div>
                        </div>
                        <div class="comparison-box promedio">
                            <div class="comparison-value">{{ $seccion['promedio_porcentaje'] }}%</div>
                            <div class="comparison-label">PROMEDIO {{ strtoupper($predio->municipio) }}</div>
                        </div>
                    </div>
                    
                    <div class="status-breakdown">
                        <div class="status-mini status-si">
                            ✓ {{ $seccion['mis_si'] }} SÍ CUMPLE
                        </div>
                        <div class="status-mini status-no">
                            ✗ {{ $seccion['mis_no'] }} NO CUMPLE
                        </div>
                        <div class="status-mini status-na">
                            ━ {{ $seccion['mis_na'] }} NO APLICA
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        <!-- Prácticas que NO cumple (detalle) -->
        @if(count($practicasNoCumple) > 0)
        <div class="row mt-4">
            <div class="col-12">
                <h5 style="color: #f5576c; margin-bottom: 15px;">
                    <i class="bi bi-x-circle"></i> PRÁCTICAS QUE NECESITAS IMPLEMENTAR
                </h5>
                <div class="section-card" style="border-left-color: #f5576c;">
                    @foreach($practicasNoCumple as $practica)
                        <div class="practica-no-cumple">
                            <i class="bi bi-exclamation-circle"></i> {{ $practica->nombre }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
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
