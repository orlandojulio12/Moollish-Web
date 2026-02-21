@extends('layouts')

@section('template_title')
    Dashboard Individual - Información de Predios
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        position: relative;
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
    }
    
    .badge-excelente { background: #0ba360; }
    .badge-bueno { background: #4facfe; }
    .badge-regular { background: #E49B39; }
    .badge-bajo { background: #f5576c; }
    
    .info-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255,255,255,0.2);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .info-icon:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }
    
    .explicacion-card {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .explicacion-title {
        font-weight: 600;
        color: #667eea;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }
    
    .categoria-explicacion {
        background: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        border-left: 3px solid #667eea;
    }
    
    .categoria-nombre {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .practica-item {
        font-size: 0.85rem;
        color: #666;
        padding: 3px 0;
        padding-left: 15px;
        position: relative;
    }
    
    .practica-item:before {
        content: "•";
        position: absolute;
        left: 0;
        color: #667eea;
    }
    
    .formula-box {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 2px dashed #667eea;
        text-align: center;
        margin: 15px 0;
    }
    
    .section-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 15px;
        border-left: 4px solid #667eea;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    
    .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px;
    }
    
    .status-si { background: #0ba360; }
    .status-no { background: #f5576c; }
    
    .mini-score-card {
        background: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        position: relative;
    }
    
    .mini-score-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
        margin: 5px 0;
    }
    
    .mini-score-label {
        font-size: 0.75rem;
        color: #666;
    }
    
    .mini-score-detail {
        font-size: 0.7rem;
        color: #999;
        margin-top: 5px;
    }
</style>
@endsection


@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('dashboard.info.predios.consolidado') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Individual - Información de Predios</h5>
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
                <p class="text-muted">Selecciona un predio para ver su análisis completo</p>
                
                <form method="GET" action="{{ route('dashboard.info.predios.individual') }}">
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
                            <a href="{{ route('dashboard.info.predios.consolidado') }}" class="btn btn-outline-primary">
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
        
        <!-- Score General -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="score-card">
                    <div class="info-icon" data-bs-toggle="collapse" data-bs-target="#explicacionScores">
                        <i class="bi bi-info-lg"></i>
                    </div>
                    <div class="score-value">{{ $scoreGeneral }}</div>
                    <div class="score-label">SCORE GENERAL</div>
                    @php
                        if($scoreGeneral >= 75) { $badge = 'excelente'; $texto = '⭐ EXCELENTE'; }
                        elseif($scoreGeneral >= 60) { $badge = 'bueno'; $texto = '✓ BUENO'; }
                        elseif($scoreGeneral >= 40) { $badge = 'regular'; $texto = '⚠ REGULAR'; }
                        else { $badge = 'bajo'; $texto = '⚡ NECESITA MEJORA'; }
                    @endphp
                    <div class="score-badge badge-{{ $badge }}">{{ $texto }}</div>
                    <div style="margin-top: 15px; font-size: 0.85rem; opacity: 0.9;">
                        Toca el <i class="bi bi-info-circle"></i> para ver cómo se calcula
                    </div>
                </div>
            </div>
            
            <!-- Mini Scores por Categoría -->
            <div class="col-md-8">
                <div class="row">
                    @php
                        $categorias = [
                            ['nombre' => 'Tierras y Aguas', 'score' => $miScoreTierra, 'icon' => 'droplet', 'practicas' => '4 prácticas'],
                            ['nombre' => 'Pastos', 'score' => $miScorePastos, 'icon' => 'flower2', 'practicas' => '5 prácticas'],
                            ['nombre' => 'Ganado', 'score' => $miScoreGanado, 'icon' => 'heart-pulse', 'practicas' => '5 prácticas'],
                            ['nombre' => 'Ambiental', 'score' => $miScoreAmbiental, 'icon' => 'tree', 'practicas' => '4 prácticas'],
                            ['nombre' => 'Gestión', 'score' => $miScoreGestion, 'icon' => 'file-earmark-text', 'practicas' => '4 prácticas'],
                        ];
                    @endphp
                    @foreach($categorias as $cat)
                        <div class="col-md-4 mb-3">
                            <div class="mini-score-card">
                                <i class="bi bi-{{ $cat['icon'] }}" style="font-size: 1.5rem; color: #667eea;"></i>
                                <div class="mini-score-value">{{ round($cat['score']) }}</div>
                                <div class="mini-score-label">{{ $cat['nombre'] }}</div>
                                <div class="mini-score-detail">{{ $cat['practicas'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Explicación de Cálculo de Scores (Colapsable) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="collapse" id="explicacionScores">
                    <div class="explicacion-card">
                        <div class="explicacion-title">
                            <i class="bi bi-calculator"></i> ¿Cómo se calculan los scores?
                        </div>
                        
                        <div class="formula-box">
                            <strong>SCORE GENERAL = Promedio de las 5 categorías</strong>
                            <div style="font-size: 0.85rem; color: #666; margin-top: 5px;">
                                (Tierras + Pastos + Ganado + Ambiental + Gestión) ÷ 5
                            </div>
                        </div>
                        
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">
                            Cada categoría se evalúa de 0 a 100 según las prácticas implementadas. 
                            Cada práctica tiene un peso igual dentro de su categoría:
                        </p>
                        
                        <div class="row">
                            <!-- Tierras y Aguas -->
                            <div class="col-md-6 mb-3">
                                <div class="categoria-explicacion">
                                    <div class="categoria-nombre">
                                        <i class="bi bi-droplet"></i> Tierras y Aguas (4 prácticas = 100%)
                                    </div>
                                    <div class="practica-item">✓ Info de Suelos: <strong>25%</strong></div>
                                    <div class="practica-item">✓ Sistema de Drenaje: <strong>25%</strong></div>
                                    <div class="practica-item">✓ Fuente de Agua: <strong>25%</strong></div>
                                    <div class="practica-item">✓ Manejo de Cuencas: <strong>25%</strong></div>
                                    <div style="margin-top: 8px; font-size: 0.8rem; color: #999;">
                                        Ejemplo: Si tienes 3 de 4 = 75%
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pastos -->
                            <div class="col-md-6 mb-3">
                                <div class="categoria-explicacion">
                                    <div class="categoria-nombre">
                                        <i class="bi bi-flower2"></i> Manejo de Pastos (5 prácticas = 100%)
                                    </div>
                                    <div class="practica-item">✓ Fertilización: <strong>20%</strong></div>
                                    <div class="practica-item">✓ Control de Plagas: <strong>20%</strong></div>
                                    <div class="practica-item">✓ Control de Maleza: <strong>20%</strong></div>
                                    <div class="practica-item">✓ División de Potreros: <strong>20%</strong></div>
                                    <div class="practica-item">✓ Cercas: <strong>20%</strong></div>
                                    <div style="margin-top: 8px; font-size: 0.8rem; color: #999;">
                                        Ejemplo: Si tienes 1 de 5 = 20%
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ganado -->
                            <div class="col-md-6 mb-3">
                                <div class="categoria-explicacion">
                                    <div class="categoria-nombre">
                                        <i class="bi bi-heart-pulse"></i> Manejo del Ganado (5 prácticas = 100%)
                                    </div>
                                    <div class="practica-item">✓ Identificación: <strong>20%</strong></div>
                                    <div class="practica-item">✓ Sistema de Cría: <strong>20%</strong></div>
                                    <div class="practica-item">✓ Control de Parásitos: <strong>20%</strong></div>
                                    <div class="practica-item">✓ Pesaje de Animales: <strong>20%</strong></div>
                                    <div class="practica-item">✓ Suministra Sal: <strong>20%</strong></div>
                                    <div style="margin-top: 8px; font-size: 0.8rem; color: #999;">
                                        Ejemplo: Si tienes 5 de 5 = 100%
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ambiental -->
                            <div class="col-md-6 mb-3">
                                <div class="categoria-explicacion">
                                    <div class="categoria-nombre">
                                        <i class="bi bi-tree"></i> Gestión Ambiental (4 prácticas = 100%)
                                    </div>
                                    <div class="practica-item">✓ Manejo Aguas Servidas: <strong>25%</strong></div>
                                    <div class="practica-item">✓ Manejo Excrementos: <strong>25%</strong></div>
                                    <div class="practica-item">✓ Manejo de Basuras: <strong>25%</strong></div>
                                    <div class="practica-item">✓ Manejo de Químicos: <strong>25%</strong></div>
                                    <div style="margin-top: 8px; font-size: 0.8rem; color: #999;">
                                        Ejemplo: Si tienes 4 de 4 = 100%
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Gestión -->
                            <div class="col-md-12 mb-3">
                                <div class="categoria-explicacion">
                                    <div class="categoria-nombre">
                                        <i class="bi bi-file-earmark-text"></i> Gestión de Información (4 prácticas = 100%)
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="practica-item">✓ Lleva Registros: <strong>25%</strong></div>
                                            <div class="practica-item">✓ Calcula Indicadores: <strong>25%</strong></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="practica-item">✓ Usa la Información: <strong>25%</strong></div>
                                            <div class="practica-item">✓ Registra Info Finca: <strong>25%</strong></div>
                                        </div>
                                    </div>
                                    <div style="margin-top: 8px; font-size: 0.8rem; color: #999;">
                                        Ejemplo: Si tienes 4 de 4 = 100%
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info" style="margin-top: 20px;">
                            <strong><i class="bi bi-lightbulb-fill"></i> Interpretación:</strong>
                            <ul style="margin: 10px 0 0 20px; font-size: 0.9rem;">
                                <li><strong>75-100%:</strong> Excelente adopción de prácticas</li>
                                <li><strong>60-74%:</strong> Buena implementación</li>
                                <li><strong>40-59%:</strong> Nivel regular, hay oportunidades de mejora</li>
                                <li><strong>0-39%:</strong> Necesita implementar más prácticas</li>
                            </ul>
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
                <h5 style="color: #E49B39; margin-bottom: 15px;"><i class="bi bi-lightbulb-fill"></i> RECOMENDACIONES PRIORITARIAS</h5>
                @foreach(collect($recomendaciones)->take(5) as $rec)
                    <div class="recomendacion-card">
                        <div class="recomendacion-area">{{ $rec['area'] }}</div>
                        <div class="recomendacion-texto">{{ $rec['texto'] }}</div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Comparaciones Detalladas -->
        <div class="row">
            <div class="col-12">
                <h5 style="color: #667eea; margin-bottom: 20px;"><i class="bi bi-bar-chart-fill"></i> COMPARACIÓN DETALLADA vs PROMEDIO MUNICIPAL</h5>
            </div>
        </div>
        
        <!-- 1. Tierras y Aguas -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card">
                    <div class="section-header">
                        <div>
                            <div class="section-title"><i class="bi bi-droplet"></i> Tierras y Aguas</div>
                        </div>
                        <div class="section-score" style="color: {{ $miScoreTierra >= $promedioScoreTierra ? '#0ba360' : '#f5576c' }};">
                            {{ round($miScoreTierra) }}%
                        </div>
                    </div>
                    
                    <div class="comparison-bar">
                        <div class="comparison-box mi-predio">
                            <div class="comparison-value">{{ round($miScoreTierra) }}%</div>
                            <div class="comparison-label">MI PREDIO</div>
                        </div>
                        <div class="comparison-box promedio">
                            <div class="comparison-value">{{ round($promedioScoreTierra) }}%</div>
                            <div class="comparison-label">PROMEDIO {{ strtoupper($predio->municipio) }}</div>
                        </div>
                    </div>
                    
                    <hr style="margin: 15px 0;">
                    
                    <div class="row">
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miTierraAgua->tiene_suelos > 0 ? 'si' : 'no' }}"></span>
                                Info de Suelos
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miTierraAgua->tiene_drenaje > 0 ? 'si' : 'no' }}"></span>
                                Drenaje
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miTierraAgua->tiene_agua > 0 ? 'si' : 'no' }}"></span>
                                Fuente de Agua
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miTierraAgua->tiene_cuencas > 0 ? 'si' : 'no' }}"></span>
                                Manejo de Cuencas
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 2. Pastos -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card" style="border-left-color: #0ba360;">
                    <div class="section-header">
                        <div>
                            <div class="section-title"><i class="bi bi-flower2"></i> Manejo de Pastos</div>
                        </div>
                        <div class="section-score" style="color: {{ $miScorePastos >= $promedioScorePastos ? '#0ba360' : '#f5576c' }};">
                            {{ round($miScorePastos) }}%
                        </div>
                    </div>
                    
                    <div class="comparison-bar">
                        <div class="comparison-box mi-predio">
                            <div class="comparison-value">{{ round($miScorePastos) }}%</div>
                            <div class="comparison-label">MI PREDIO</div>
                        </div>
                        <div class="comparison-box promedio">
                            <div class="comparison-value">{{ round($promedioScorePastos) }}%</div>
                            <div class="comparison-label">PROMEDIO {{ strtoupper($predio->municipio) }}</div>
                        </div>
                    </div>
                    
                    <hr style="margin: 15px 0;">
                    
                    <div class="row">
                        <div class="col-4">
                            <small>
                                <span class="status-indicator status-{{ $miPastos->fertiliza > 0 ? 'si' : 'no' }}"></span>
                                Fertilización
                            </small>
                        </div>
                        <div class="col-4">
                            <small>
                                <span class="status-indicator status-{{ $miPastos->plagas > 0 ? 'si' : 'no' }}"></span>
                                Control Plagas
                            </small>
                        </div>
                        <div class="col-4">
                            <small>
                                <span class="status-indicator status-{{ $miPastos->maleza > 0 ? 'si' : 'no' }}"></span>
                                Control Maleza
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miPastos->division > 0 ? 'si' : 'no' }}"></span>
                                División Potreros
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miPastos->cercas > 0 ? 'si' : 'no' }}"></span>
                                Cercas
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 3. Ganado -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card" style="border-left-color: #4facfe;">
                    <div class="section-header">
                        <div>
                            <div class="section-title"><i class="bi bi-heart-pulse"></i> Manejo del Ganado</div>
                        </div>
                        <div class="section-score" style="color: {{ $miScoreGanado >= $promedioScoreGanado ? '#0ba360' : '#f5576c' }};">
                            {{ round($miScoreGanado) }}%
                        </div>
                    </div>
                    
                    <div class="comparison-bar">
                        <div class="comparison-box mi-predio">
                            <div class="comparison-value">{{ round($miScoreGanado) }}%</div>
                            <div class="comparison-label">MI PREDIO</div>
                        </div>
                        <div class="comparison-box promedio">
                            <div class="comparison-value">{{ round($promedioScoreGanado) }}%</div>
                            <div class="comparison-label">PROMEDIO {{ strtoupper($predio->municipio) }}</div>
                        </div>
                    </div>
                    
                    <hr style="margin: 15px 0;">
                    
                    <div class="row">
                        <div class="col-4">
                            <small>
                                <span class="status-indicator status-{{ $miGanado->identifica > 0 ? 'si' : 'no' }}"></span>
                                Identificación
                            </small>
                        </div>
                        <div class="col-4">
                            <small>
                                <span class="status-indicator status-{{ $miGanado->cria > 0 ? 'si' : 'no' }}"></span>
                                Sistema Cría
                            </small>
                        </div>
                        <div class="col-4">
                            <small>
                                <span class="status-indicator status-{{ $miGanado->parasitos > 0 ? 'si' : 'no' }}"></span>
                                Control Parásitos
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miGanado->pesaje > 0 ? 'si' : 'no' }}"></span>
                                Pesaje
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miGanado->sal > 0 ? 'si' : 'no' }}"></span>
                                Suministra Sal
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 4. Ambiental -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card" style="border-left-color: #0ba360;">
                    <div class="section-header">
                        <div>
                            <div class="section-title"><i class="bi bi-tree"></i> Gestión Ambiental</div>
                        </div>
                        <div class="section-score" style="color: {{ $miScoreAmbiental >= $promedioScoreAmbiental ? '#0ba360' : '#f5576c' }};">
                            {{ round($miScoreAmbiental) }}%
                        </div>
                    </div>
                    
                    <div class="comparison-bar">
                        <div class="comparison-box mi-predio">
                            <div class="comparison-value">{{ round($miScoreAmbiental) }}%</div>
                            <div class="comparison-label">MI PREDIO</div>
                        </div>
                        <div class="comparison-box promedio">
                            <div class="comparison-value">{{ round($promedioScoreAmbiental) }}%</div>
                            <div class="comparison-label">PROMEDIO {{ strtoupper($predio->municipio) }}</div>
                        </div>
                    </div>
                    
                    <hr style="margin: 15px 0;">
                    
                    <div class="row">
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miAmbiental->aguas > 0 ? 'si' : 'no' }}"></span>
                                Aguas Servidas
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miAmbiental->excrementos > 0 ? 'si' : 'no' }}"></span>
                                Excrementos
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miAmbiental->basuras > 0 ? 'si' : 'no' }}"></span>
                                Basuras
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miAmbiental->quimicos > 0 ? 'si' : 'no' }}"></span>
                                Químicos
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 5. Gestión -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card" style="border-left-color: #E49B39;">
                    <div class="section-header">
                        <div>
                            <div class="section-title"><i class="bi bi-file-earmark-text"></i> Gestión de Información</div>
                        </div>
                        <div class="section-score" style="color: {{ $miScoreGestion >= $promedioScoreGestion ? '#0ba360' : '#f5576c' }};">
                            {{ round($miScoreGestion) }}%
                        </div>
                    </div>
                    
                    <div class="comparison-bar">
                        <div class="comparison-box mi-predio">
                            <div class="comparison-value">{{ round($miScoreGestion) }}%</div>
                            <div class="comparison-label">MI PREDIO</div>
                        </div>
                        <div class="comparison-box promedio">
                            <div class="comparison-value">{{ round($promedioScoreGestion) }}%</div>
                            <div class="comparison-label">PROMEDIO {{ strtoupper($predio->municipio) }}</div>
                        </div>
                    </div>
                    
                    <hr style="margin: 15px 0;">
                    
                    <div class="row">
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miGestion->registros > 0 ? 'si' : 'no' }}"></span>
                                Lleva Registros
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miGestion->indicadores > 0 ? 'si' : 'no' }}"></span>
                                Calcula Indicadores
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miGestion->usa_info > 0 ? 'si' : 'no' }}"></span>
                                Usa Información
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <span class="status-indicator status-{{ $miGestion->registra > 0 ? 'si' : 'no' }}"></span>
                                Registra Info Finca
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
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