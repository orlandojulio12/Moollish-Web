@extends('layouts')

@section('template_title')
    Análisis Comparativo
@endsection
@section('styles')
<style>
    .comparativo-header {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 8px 20px rgba(255, 215, 0, 0.3);
        text-align: center;
    }

    .module-selector {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .module-btn {
        flex: 1;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        background: white;
        transition: all 0.3s;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        color: #333;
        display: block;
    }

    .module-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: #333;
    }

    .module-btn.active {
        border-color: #ffd700;
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        font-weight: 600;
    }

    .ranking-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .ranking-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 12px;
        transition: all 0.3s;
    }

    .ranking-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .ranking-position {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        margin-right: 15px;
    }

    .ranking-1 {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        color: #333;
    }

    .ranking-2 {
        background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%);
        color: #333;
    }

    .ranking-3 {
        background: linear-gradient(135deg, #cd7f32 0%, #e59a5a 100%);
        color: white;
    }

    .ranking-other {
        background: #f0f0f0;
        color: #666;
    }

    .ranking-municipio {
        flex: 1;
        font-weight: 600;
        font-size: 1rem;
    }

    .ranking-valor {
        font-size: 1.5rem;
        font-weight: bold;
        color: #0ba360;
        margin-right: 10px;
    }

    .ranking-metrica {
        font-size: 0.8rem;
        color: #666;
    }

    .comparison-table {
        width: 100%;
        margin-top: 20px;
    }

    .comparison-table th {
        background: #f8f9fa;
        padding: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        border-bottom: 2px solid #dee2e6;
    }

    .comparison-table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
    }

    .comparison-table tr:hover {
        background: #f8f9fa;
    }

    .best-indicator {
        background: #d4edda;
        color: #0ba360;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .worst-indicator {
        background: #ffe6e6;
        color: #f5576c;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
    }
    
    .explanation-box {
        background: #e7f3ff;
        border-left: 4px solid #667eea;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .explanation-box h6 {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .explanation-box p {
        margin: 0;
        font-size: 0.9rem;
        color: #555;
    }
    
    .metric-help {
        position: relative;
        display: inline-block;
        margin-left: 5px;
        cursor: help;
    }
    
    .metric-help-icon {
        width: 16px;
        height: 16px;
        background: #667eea;
        color: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
    }
</style>
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboards.index') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active">Análisis Comparativo</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')


<div class="container-fluid">
    <!-- Header -->
    <div class="comparativo-header">
        <i class="bi bi-lightning-charge" style="font-size: 3rem; color: #333;"></i>
        <h3 style="margin: 15px 0 10px 0; color: #333;">Análisis Comparativo entre Municipios</h3>
        <p style="margin: 0; color: #555;">
            Compara indicadores clave, rankings y desempeño entre diferentes municipios de la región
        </p>
    </div>

    <!-- Selector de Módulo -->
    <div class="module-selector">
        <h5 class="mb-3"><i class="bi bi-funnel"></i> Selecciona el módulo a comparar:</h5>
        <div class="row g-3">
            <div class="col-md-2">
                <a href="{{ route('dashboard.analisis.comparativo', ['modulo' => 'info_predios']) }}"
                    class="module-btn {{ $modulo == 'info_predios' ? 'active' : '' }}">
                    <i class="bi bi-map" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    <strong>Info Predios</strong>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('dashboard.analisis.comparativo', ['modulo' => 'bgp']) }}"
                    class="module-btn {{ $modulo == 'bgp' ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    <strong>BPG</strong>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('dashboard.analisis.comparativo', ['modulo' => 'riesgo']) }}"
                    class="module-btn {{ $modulo == 'riesgo' ? 'active' : '' }}">
                    <i class="bi bi-shield-exclamation" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    <strong>Riesgo Epidem.</strong>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('dashboard.analisis.comparativo', ['modulo' => 'servicios']) }}"
                    class="module-btn {{ $modulo == 'servicios' ? 'active' : '' }}">
                    <i class="bi bi-tree-fill" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    <strong>Servicios Amb.</strong>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('dashboard.analisis.comparativo', ['modulo' => 'censo']) }}"
                    class="module-btn {{ $modulo == 'censo' ? 'active' : '' }}">
                    <i class="bi bi-clipboard-data" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    <strong>Censo Animal</strong>
                </a>
            </div>
            <div class="col-md-2">
                <div style="padding: 15px; text-align: center; color: #999;">
                    <i class="bi bi-plus-circle" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    <small>Más próximamente</small>
                </div>
            </div>
        </div>
    </div>

    @if($datosComparativos->count() > 0)
        <!-- Explicación del Módulo Seleccionado -->
        <div class="explanation-box">
            @if($modulo == 'info_predios')
                <h6><i class="bi bi-info-circle"></i> ¿Qué estás viendo? - Información de Predios</h6>
                <p>
                    Este análisis compara las <strong>características físicas y productivas</strong> de los predios entre municipios. 
                    Incluye: área total disponible, manejo de suelos y agua, prácticas en pastos, manejo de ganado, gestión ambiental y registros administrativos. 
                    Los indicadores muestran qué tan bien cada municipio gestiona sus recursos naturales y productivos.
                </p>
            @elseif($modulo == 'bgp')
                <h6><i class="bi bi-info-circle"></i> ¿Qué estás viendo? - Buenas Prácticas Ganaderas (BPG)</h6>
                <p>
                    Este análisis compara el <strong>cumplimiento de Buenas Prácticas Ganaderas</strong> entre municipios. 
                    El Score BPG mide qué porcentaje de las prácticas obligatorias se están cumpliendo (sanidad, bioseguridad, medicamentos, alimentación, bienestar animal, etc.). 
                    Un score alto (70%+) indica que el municipio tiene buenas condiciones sanitarias y productivas. Un score bajo (<40%) requiere intervención urgente.
                </p>
            @elseif($modulo == 'riesgo')
                <h6><i class="bi bi-info-circle"></i> ¿Qué estás viendo? - Riesgo Epidemiológico</h6>
                <p>
                    Este análisis compara el <strong>nivel de riesgo sanitario</strong> entre municipios. 
                    El Índice de Riesgo mide factores como: proximidad a establecimientos peligrosos, sacrificio en predio, movilidad laboral y falta de asistencia técnica. 
                    Un índice BAJO (<30%) significa mejor control sanitario. Un índice ALTO (60%+) indica que el municipio necesita reforzar vigilancia y bioseguridad urgentemente.
                </p>
            @elseif($modulo == 'servicios')
                <h6><i class="bi bi-info-circle"></i> ¿Qué estás viendo? - Servicios Ambientales</h6>
                <p>
                    Este análisis compara el <strong>compromiso ambiental</strong> de cada municipio. 
                    Muestra cuántas hectáreas están destinadas a conservación, restauración o producción sostenible (bosques, agroforestería, cercas vivas, etc.). 
                    Un municipio con alta cobertura (70%+) demuestra responsabilidad ambiental. Baja cobertura (<30%) indica necesidad de promover prácticas sostenibles.
                </p>
            @elseif($modulo == 'censo')
                <h6><i class="bi bi-info-circle"></i> ¿Qué estás viendo? - Censo Animal</h6>
                <p>
                    Este análisis compara las <strong>poblaciones animales</strong> entre municipios. 
                    Muestra el total de animales por especie (bovinos, porcinos, équidos, aves, peces, etc.) y cuál es la especie dominante en cada región. 
                    Los datos de población ayudan a planificar programas sanitarios, de alimentación y comercialización según las necesidades de cada municipio.
                </p>
            @endif
        </div>
        
        <!-- Rankings -->
        <div class="row">
            @if($modulo == 'info_predios')
                <!-- RANKING 1: Área Total -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Mayor Área Total
                            <span class="metric-help" title="Suma de todas las hectáreas registradas en el municipio">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios con más hectáreas productivas registradas
                        </small>
                        @foreach($datosComparativos->sortByDesc('area_total')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor">{{ number_format($dato['area_total'], 1) }}</div>
                                    <div class="ranking-metrica">hectáreas</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- RANKING 2: Mejor Fertilización -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Mejor Fertilización de Pastos
                            <span class="metric-help" title="Porcentaje de predios que fertilizan sus potreros">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios donde más predios fertilizan para mejorar productividad
                        </small>
                        @foreach($datosComparativos->sortByDesc('porcentaje_fertiliza')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor">{{ $dato['porcentaje_fertiliza'] }}%</div>
                                    <div class="ranking-metrica">de predios</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
            @elseif($modulo == 'bgp')
                <!-- RANKING 1: Score BPG -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Mejor Score BPG
                            <span class="metric-help" title="Porcentaje de prácticas ganaderas que se cumplen">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios con mayor cumplimiento de Buenas Prácticas Ganaderas
                        </small>
                        @foreach($datosComparativos->sortByDesc('score_bgp')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor">{{ $dato['score_bgp'] }}%</div>
                                    <div class="ranking-metrica">cumplimiento</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- RANKING 2: Cobertura BPG -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Mayor Cobertura BPG
                            <span class="metric-help" title="Porcentaje de predios que implementan BPG">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios donde más predios han adoptado Buenas Prácticas
                        </small>
                        @foreach($datosComparativos->sortByDesc('cobertura_bgp')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor">{{ $dato['cobertura_bgp'] }}%</div>
                                    <div class="ranking-metrica">predios con BPG</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
            @elseif($modulo == 'riesgo')
                <!-- RANKING 1: Menor Riesgo -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Menor Riesgo Epidemiológico
                            <span class="metric-help" title="Índice que mide factores de riesgo sanitario (0-100). Menor es mejor.">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios con mejor control sanitario y menos factores de riesgo
                        </small>
                        @foreach($datosComparativos->sortBy('indice_riesgo')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor" style="color: {{ $dato['indice_riesgo'] < 30 ? '#0ba360' : ($dato['indice_riesgo'] < 60 ? '#E49B39' : '#f5576c') }};">
                                        {{ $dato['indice_riesgo'] }}%
                                    </div>
                                    <div class="ranking-metrica">índice de riesgo</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- RANKING 2: Mayor Asistencia -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Mayor Asistencia Técnica
                            <span class="metric-help" title="Porcentaje de predios con acompañamiento veterinario">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios donde más predios reciben asistencia técnica veterinaria
                        </small>
                        @foreach($datosComparativos->sortByDesc('porcentaje_con_asistencia')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor">{{ $dato['porcentaje_con_asistencia'] }}%</div>
                                    <div class="ranking-metrica">predios con asistencia</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
            @elseif($modulo == 'servicios')
                <!-- RANKING 1: Mayor Área -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Mayor Área de Servicios Ambientales
                            <span class="metric-help" title="Hectáreas destinadas a conservación y prácticas sostenibles">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios que destinan más hectáreas a conservación ambiental
                        </small>
                        @foreach($datosComparativos->sortByDesc('hectareas_totales')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor">{{ number_format($dato['hectareas_totales'], 1) }}</div>
                                    <div class="ranking-metrica">hectáreas</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- RANKING 2: Mayor Cobertura -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Mayor Cobertura de Servicios
                            <span class="metric-help" title="Porcentaje de predios que implementan servicios ambientales">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios donde más predios implementan prácticas sostenibles
                        </small>
                        @foreach($datosComparativos->sortByDesc('cobertura_servicios')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor">{{ $dato['cobertura_servicios'] }}%</div>
                                    <div class="ranking-metrica">predios con servicios</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
            @elseif($modulo == 'censo')
                <!-- RANKING 1: Mayor Población Total -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Mayor Población Animal Total
                            <span class="metric-help" title="Suma de todos los animales censados en el municipio">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios con mayor inventario total de animales de producción
                        </small>
                        @foreach($datosComparativos->sortByDesc('total_animales')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor">{{ number_format($dato['total_animales']) }}</div>
                                    <div class="ranking-metrica">animales</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- RANKING 2: Mayor Población Bovina -->
                <div class="col-md-6">
                    <div class="ranking-card">
                        <h5 class="mb-4">
                            <i class="bi bi-trophy"></i> Ranking: Mayor Población Bovina
                            <span class="metric-help" title="Total de ganado bovino censado">
                                <span class="metric-help-icon">?</span>
                            </span>
                        </h5>
                        <small class="text-muted" style="display: block; margin-bottom: 15px;">
                            Municipios con mayor inventario bovino (vacas, toros, terneros)
                        </small>
                        @foreach($datosComparativos->sortByDesc('total_bovinos')->take(5) as $index => $dato)
                            <div class="ranking-item">
                                <div class="ranking-position {{ $index == 0 ? 'ranking-1' : ($index == 1 ? 'ranking-2' : ($index == 2 ? 'ranking-3' : 'ranking-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ranking-municipio">{{ $dato['municipio'] }}</div>
                                <div style="text-align: right;">
                                    <div class="ranking-valor">{{ number_format($dato['total_bovinos']) }}</div>
                                    <div class="ranking-metrica">bovinos</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Tabla Comparativa Completa -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="ranking-card">
                    <h5 class="mb-4"><i class="bi bi-table"></i> Tabla Comparativa Completa - Todos los Municipios</h5>
                    <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 20px;">
                        <strong>Cómo leer la tabla:</strong> 
                        <span class="best-indicator" style="margin: 0 5px;">Verde</span> = Buen desempeño (70%+) | 
                        <span class="worst-indicator" style="margin: 0 5px;">Rojo</span> = Necesita mejora (<30%) | 
                        Sin color = Desempeño moderado (30-69%)
                    </p>
                    <div class="table-responsive">
                        <table class="comparison-table table">
                            <thead>
                                <tr>
                                    <th>Municipio</th>
                                    <th>Total Predios</th>
                                    @if($modulo == 'info_predios')
                                        <th>Área Total (ha)
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Suma total</div>
                                        </th>
                                        <th>% Info Suelos
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Conocen sus suelos</div>
                                        </th>
                                        <th>% Drenaje
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Tienen sistema</div>
                                        </th>
                                        <th>% Fuente Agua
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Acceso identificado</div>
                                        </th>
                                        <th>% Fertiliza
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Fertiliza potreros</div>
                                        </th>
                                        <th>% Control Plagas
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Maneja plagas</div>
                                        </th>
                                        <th>% División Potreros
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Tiene rotación</div>
                                        </th>
                                        <th>% Identifica Ganado
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Marca animales</div>
                                        </th>
                                        <th>% Maneja Aguas
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Trata residuos</div>
                                        </th>
                                        <th>% Lleva Registros
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Registra info</div>
                                        </th>
                                    @elseif($modulo == 'bgp')
                                        <th>Predios con BPG
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Implementan BPG</div>
                                        </th>
                                        <th>Score BPG
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">% cumplimiento</div>
                                        </th>
                                        <th>Prácticas ✓
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Sí cumplen</div>
                                        </th>
                                        <th>Prácticas ✗
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">No cumplen</div>
                                        </th>
                                        <th>% Cobertura
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Predios con BPG</div>
                                        </th>
                                    @elseif($modulo == 'riesgo')
                                        <th>Predios Enfermos
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Con casos</div>
                                        </th>
                                        <th>Animales Enfermos
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Total afectados</div>
                                        </th>
                                        <th>% Diagnóstico
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Toman muestras</div>
                                        </th>
                                        <th>Índice Riesgo
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">0=mejor 100=peor</div>
                                        </th>
                                        <th>% con Asistencia
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Apoyo técnico</div>
                                        </th>
                                        <th>% Vigilancia
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Predios visitados</div>
                                        </th>
                                    @elseif($modulo == 'servicios')
                                        <th>Predios Activos
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Con servicios</div>
                                        </th>
                                        <th>Hectáreas
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Área total</div>
                                        </th>
                                        <th>Tipos Servicios
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Diversidad</div>
                                        </th>
                                        <th>% Cobertura
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Predios activos</div>
                                        </th>
                                        <th>Ha/Predio
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Promedio</div>
                                        </th>
                                        <th>Servicio Principal
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Más usado</div>
                                        </th>
                                    @elseif($modulo == 'censo')
                                        <th>Total Animales
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Todas especies</div>
                                        </th>
                                        <th>Anim/Predio
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Promedio</div>
                                        </th>
                                        <th>Especie Dominante
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Principal</div>
                                        </th>
                                        <th>Bovinos
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Vacas/toros</div>
                                        </th>
                                        <th>Porcinos
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Cerdos</div>
                                        </th>
                                        <th>Équidos
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Caballos/mulas</div>
                                        </th>
                                        <th>Ovinos/Caprinos
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Ovejas/cabras</div>
                                        </th>
                                        <th>Aves
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Todas</div>
                                        </th>
                                        <th>Peces
                                            <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.7;">Acuicultura</div>
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datosComparativos->sortByDesc(function($item) use ($modulo) {
                                    if($modulo == 'info_predios') return $item['area_total'];
                                    if($modulo == 'bgp') return $item['score_bgp'];
                                    if($modulo == 'riesgo') return -$item['indice_riesgo'];
                                    if($modulo == 'servicios') return $item['hectareas_totales'];
                                    if($modulo == 'censo') return $item['total_animales'];
                                    return 0;
                                }) as $dato)
                                    <tr>
                                        <td><strong>{{ $dato['municipio'] }}</strong></td>
                                        <td>{{ $dato['total_predios'] }}</td>
                                        @if($modulo == 'info_predios')
                                            <td>{{ number_format($dato['area_total'], 1) }}</td>
                                            <td><span class="{{ $dato['porcentaje_info_suelos'] >= 70 ? 'best-indicator' : ($dato['porcentaje_info_suelos'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_info_suelos'] }}%</span></td>
                                            <td><span class="{{ $dato['porcentaje_drenaje'] >= 70 ? 'best-indicator' : ($dato['porcentaje_drenaje'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_drenaje'] }}%</span></td>
                                            <td><span class="{{ $dato['porcentaje_fuente_agua'] >= 70 ? 'best-indicator' : ($dato['porcentaje_fuente_agua'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_fuente_agua'] }}%</span></td>
                                            <td><span class="{{ $dato['porcentaje_fertiliza'] >= 70 ? 'best-indicator' : ($dato['porcentaje_fertiliza'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_fertiliza'] }}%</span></td>
                                            <td><span class="{{ $dato['porcentaje_control_plagas'] >= 70 ? 'best-indicator' : ($dato['porcentaje_control_plagas'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_control_plagas'] }}%</span></td>
                                            <td><span class="{{ $dato['porcentaje_division_potreros'] >= 70 ? 'best-indicator' : ($dato['porcentaje_division_potreros'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_division_potreros'] }}%</span></td>
                                            <td><span class="{{ $dato['porcentaje_identifica'] >= 70 ? 'best-indicator' : ($dato['porcentaje_identifica'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_identifica'] }}%</span></td>
                                            <td><span class="{{ $dato['porcentaje_maneja_aguas'] >= 70 ? 'best-indicator' : ($dato['porcentaje_maneja_aguas'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_maneja_aguas'] }}%</span></td>
                                            <td><span class="{{ $dato['porcentaje_lleva_registros'] >= 70 ? 'best-indicator' : ($dato['porcentaje_lleva_registros'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_lleva_registros'] }}%</span></td>
                                        @elseif($modulo == 'bgp')
                                            <td>{{ $dato['predios_con_bgp'] }}</td>
                                            <td><span class="{{ $dato['score_bgp'] >= 70 ? 'best-indicator' : ($dato['score_bgp'] < 40 ? 'worst-indicator' : '') }}">{{ $dato['score_bgp'] }}%</span></td>
                                            <td>{{ $dato['practicas_cumplidas'] }}</td>
                                            <td>{{ $dato['practicas_no_cumplidas'] }}</td>
                                            <td><span class="{{ $dato['cobertura_bgp'] >= 70 ? 'best-indicator' : ($dato['cobertura_bgp'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['cobertura_bgp'] }}%</span></td>
                                        @elseif($modulo == 'riesgo')
                                            <td>{{ $dato['predios_con_enfermos'] }}</td>
                                            <td>{{ $dato['total_animales_enfermos'] }}</td>
                                            <td><span class="{{ $dato['tasa_diagnostico'] >= 70 ? 'best-indicator' : ($dato['tasa_diagnostico'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['tasa_diagnostico'] }}%</span></td>
                                            <td><span class="{{ $dato['indice_riesgo'] < 30 ? 'best-indicator' : ($dato['indice_riesgo'] >= 60 ? 'worst-indicator' : '') }}">{{ $dato['indice_riesgo'] }}%</span></td>
                                            <td><span class="{{ $dato['porcentaje_con_asistencia'] >= 70 ? 'best-indicator' : ($dato['porcentaje_con_asistencia'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['porcentaje_con_asistencia'] }}%</span></td>
                                            <td><span class="{{ $dato['cobertura_vigilancia'] >= 70 ? 'best-indicator' : ($dato['cobertura_vigilancia'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['cobertura_vigilancia'] }}%</span></td>
                                        @elseif($modulo == 'servicios')
                                            <td>{{ $dato['predios_con_servicios'] }}</td>
                                            <td>{{ number_format($dato['hectareas_totales'], 1) }}</td>
                                            <td>{{ $dato['tipos_servicios'] }}</td>
                                            <td><span class="{{ $dato['cobertura_servicios'] >= 70 ? 'best-indicator' : ($dato['cobertura_servicios'] < 30 ? 'worst-indicator' : '') }}">{{ $dato['cobertura_servicios'] }}%</span></td>
                                            <td>{{ number_format($dato['hectareas_promedio_predio'], 1) }}</td>
                                            <td>{{ $dato['servicio_principal'] }}</td>
                                        @elseif($modulo == 'censo')
                                            <td><strong>{{ number_format($dato['total_animales']) }}</strong></td>
                                            <td>{{ number_format($dato['animales_por_predio'], 1) }}</td>
                                            <td><span class="best-indicator">{{ $dato['especie_dominante'] }}</span></td>
                                            <td>{{ number_format($dato['total_bovinos']) }}</td>
                                            <td>{{ number_format($dato['total_porcinos']) }}</td>
                                            <td>{{ number_format($dato['total_equidos']) }}</td>
                                            <td>{{ number_format($dato['total_ovinos'] + $dato['total_caprinos']) }}</td>
                                            <td>{{ number_format($dato['total_aves']) }}</td>
                                            <td>{{ number_format($dato['total_peces']) }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
            <h4>No hay datos disponibles para comparar</h4>
            <p>Asegúrate de que haya datos registrados en el módulo seleccionado</p>
        </div>
    @endif
</div>
@endsection