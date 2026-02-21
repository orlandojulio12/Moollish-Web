@extends('layouts')

@section('template_title')
    Dashboard - Riesgo Epidemiológico
@endsection
@section('styles')
<style>
    .risk-card {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
        color: white;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(245, 87, 108, 0.4);
    }
    
    .risk-value {
        font-size: 5rem;
        font-weight: bold;
        line-height: 1;
    }
    
    .risk-label {
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
        border-left: 4px solid #f5576c;
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
    
    .metric-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s;
    }
    
    .metric-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .metric-value {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
    }
    
    .metric-percentage {
        font-size: 1rem;
        color: #666;
        font-weight: 600;
        margin-top: 5px;
    }
    
    .metric-label {
        font-size: 0.85rem;
        color: #666;
        margin-top: 8px;
    }
    
    .metric-context {
        font-size: 0.75rem;
        color: #999;
        margin-top: 5px;
    }
    
    .animal-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-top: 20px;
    }
    
    .animal-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 18px;
        border-radius: 10px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .animal-count {
        font-size: 2rem;
        font-weight: bold;
    }
    
    .animal-percentage {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 3px;
    }
    
    .animal-name {
        font-size: 0.8rem;
        opacity: 0.85;
        margin-top: 8px;
    }
    
    .alert-box {
        background: #fff3cd;
        border-left: 4px solid #E49B39;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .risk-indicator {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 12px;
    }
    
    .risk-alto {
        background: #ffe6e6;
        border-left: 4px solid #f5576c;
    }
    
    .risk-medio {
        background: #fff3cd;
        border-left: 4px solid #E49B39;
    }
    
    .risk-bajo {
        background: #d4edda;
        border-left: 4px solid #0ba360;
    }
    
    .risk-icon {
        font-size: 2rem;
        margin-right: 15px;
    }
    
    .risk-content {
        flex: 1;
    }
    
    .risk-title {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 3px;
    }
    
    .risk-description {
        font-size: 0.85rem;
        color: #666;
    }
    
    .comparison-bar {
        background: #f0f0f0;
        height: 30px;
        border-radius: 15px;
        overflow: hidden;
        margin-top: 10px;
        position: relative;
    }
    
    .comparison-fill {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        transition: width 0.5s;
    }
</style>
@endsection


@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('inicio') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Regional - Riesgo Epidemiológico</h5>
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
    
    <!-- Indicador de Riesgo -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="risk-card">
                <div class="risk-value">{{ $prediosConRiesgo }}%</div>
                <div class="risk-label">PREDIOS CON FACTORES DE RIESGO</div>
                @php
                    if($prediosConRiesgo >= 50) { $texto = '⚠ ALTO RIESGO'; }
                    elseif($prediosConRiesgo >= 25) { $texto = '⚡ RIESGO MODERADO'; }
                    else { $texto = '✓ BAJO RIESGO'; }
                @endphp
                <div style="margin-top: 15px; font-size: 1rem;">{{ $texto }}</div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="alert-box">
                <h5><i class="bi bi-info-circle"></i> ¿Qué evalúa este dashboard?</h5>
                <p style="margin-bottom: 10px; font-size: 0.9rem;">
                    Analiza 4 componentes clave del riesgo epidemiológico en predios ganaderos:
                </p>
                <ul style="font-size: 0.85rem; margin-bottom: 0;">
                    <li><strong>Diversidad de especies:</strong> Qué tipos de animales se manejan</li>
                    <li><strong>Situación sanitaria:</strong> Presencia de enfermedades y diagnóstico</li>
                    <li><strong>Factores de riesgo:</strong> Condiciones que facilitan contagios</li>
                    <li><strong>Vigilancia activa:</strong> Seguimiento e inspecciones realizadas</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 1: TIPOS DE EXPLOTACIÓN -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-grid-3x3"></i> 1. Diversidad de Especies por Predio
                </div>
                <div class="section-description">
                    📊 Número y porcentaje de predios que manejan cada tipo de animal (de {{ $totalPredios }} predios totales)
                </div>
                
                <div class="animal-grid">
                    @php
                        $especies = [
                            ['nombre' => 'Bovinos', 'count' => $tiposExplotacion->con_bovinos ?? 0],
                            ['nombre' => 'Bufalinos', 'count' => $tiposExplotacion->con_bufalinos ?? 0],
                            ['nombre' => 'Porcinos', 'count' => $tiposExplotacion->con_porcinos ?? 0],
                            ['nombre' => 'Equinos', 'count' => $tiposExplotacion->con_equinos ?? 0],
                            ['nombre' => 'Ovinos', 'count' => $tiposExplotacion->con_ovinos ?? 0],
                            ['nombre' => 'Caprinos', 'count' => $tiposExplotacion->con_caprinos ?? 0],
                            ['nombre' => 'Aves Corral', 'count' => $tiposExplotacion->con_aves_corral ?? 0],
                            ['nombre' => 'Peces', 'count' => $tiposExplotacion->con_peces ?? 0],
                            ['nombre' => 'Apícola', 'count' => $tiposExplotacion->con_apicolas ?? 0],
                        ];
                    @endphp
                    
                    @foreach($especies as $especie)
                        @php
                            $porcentaje = $totalPredios > 0 ? round(($especie['count'] / $totalPredios) * 100) : 0;
                        @endphp
                        <div class="animal-box">
                            <div class="animal-count">{{ $especie['count'] }}</div>
                            <div class="animal-percentage">{{ $porcentaje }}% de predios</div>
                            <div class="animal-name">{{ $especie['nombre'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 2: INFORMACIÓN EPIDEMIOLÓGICA -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card" style="border-left-color: #E49B39;">
                <div class="section-title">
                    <i class="bi bi-virus"></i> 2. Situación Sanitaria y Diagnóstico
                </div>
                <div class="section-description">
                    🔬 Detección de enfermedades y actividades diagnósticas realizadas
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="metric-box" style="border-color: #f5576c;">
                            <div class="metric-value" style="color: #f5576c;">{{ $infoEpidemiologica->con_animales_enfermos ?? 0 }}</div>
                            <div class="metric-percentage">
                                {{ $totalPredios > 0 ? round((($infoEpidemiologica->con_animales_enfermos ?? 0) / $totalPredios) * 100) : 0 }}% de los predios
                            </div>
                            <div class="metric-label">Predios con Signología de Enfermedades</div>
                            <div class="metric-context">
                                <strong>{{ $infoEpidemiologica->total_animales_enfermos ?? 0 }}</strong> animales con signos clínicos sospechosos
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="metric-box" style="border-color: #667eea;">
                            <div class="metric-value" style="color: #667eea;">{{ $infoEpidemiologica->con_toma_muestras ?? 0 }}</div>
                            <div class="metric-percentage">
                                {{ $totalPredios > 0 ? round((($infoEpidemiologica->con_toma_muestras ?? 0) / $totalPredios) * 100) : 0 }}% de los predios
                            </div>
                            <div class="metric-label">Predios con Muestreo Diagnóstico</div>
                            <div class="metric-context">
                                <strong>{{ $infoEpidemiologica->total_muestras ?? 0 }}</strong> muestras tomadas para análisis de laboratorio
                            </div>
                        </div>
                    </div>
                </div>
                
                @php
                    $prediosEnfermos = $infoEpidemiologica->con_animales_enfermos ?? 0;
                    $prediosMuestreados = $infoEpidemiologica->con_toma_muestras ?? 0;
                    $tasaDiagnostico = $prediosEnfermos > 0 ? round(($prediosMuestreados / $prediosEnfermos) * 100) : 0;
                @endphp
                
                <div class="alert-box" style="margin-top: 15px;">
                    <strong><i class="bi bi-clipboard-data"></i> Interpretación:</strong>
                    <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                        De los <strong>{{ $prediosEnfermos }}</strong> predios con animales enfermos, 
                        se tomaron muestras en <strong>{{ $prediosMuestreados }}</strong> ({{ $tasaDiagnostico }}% de cobertura diagnóstica).
                        {{ $tasaDiagnostico < 50 ? '⚠ Se recomienda aumentar la toma de muestras para confirmar diagnósticos.' : '✓ Buena cobertura de diagnóstico.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 3: FACTORES DE RIESGO -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card" style="border-left-color: #f5576c;">
                <div class="section-title">
                    <i class="bi bi-shield-exclamation"></i> 3. Factores de Riesgo Identificados
                </div>
                <div class="section-description">
                    ⚠️ Condiciones que aumentan la probabilidad de aparición o propagación de enfermedades
                </div>
                
                @php
                    $factores = [
                        [
                            'nombre' => 'Proximidad a Focos de Riesgo',
                            'descripcion' => 'Predios que colindan con ferias, mataderos, acopios, basureros o curtiembres',
                            'count' => $caracterizacionRiesgo->colinda_riesgo ?? 0,
                            'icon' => '🏭',
                            'nivel' => 'alto'
                        ],
                        [
                            'nombre' => 'Sacrificio en Predio',
                            'descripcion' => 'Predios que realizan sacrificio de animales sin control sanitario',
                            'count' => $caracterizacionRiesgo->sacrifica_animales ?? 0,
                            'icon' => '🔪',
                            'nivel' => 'alto'
                        ],
                        [
                            'nombre' => 'Movilidad Laboral',
                            'descripcion' => 'Trabajadores que laboran simultáneamente en otras explotaciones',
                            'count' => $caracterizacionRiesgo->trabajadores_otras_explotaciones ?? 0,
                            'icon' => '👷',
                            'nivel' => 'medio'
                        ],
                        [
                            'nombre' => 'Sin Asistencia Técnica',
                            'descripcion' => 'Predios que NO reciben asesoría técnica veterinaria',
                            'count' => $totalPredios - ($caracterizacionRiesgo->con_asistencia_tecnica ?? 0),
                            'icon' => '📋',
                            'nivel' => 'medio'
                        ],
                    ];
                @endphp
                
                @foreach($factores as $factor)
                    @php
                        $porcentaje = $totalPredios > 0 ? round(($factor['count'] / $totalPredios) * 100) : 0;
                        $riesgoClass = $porcentaje >= 50 ? 'risk-alto' : ($porcentaje >= 25 ? 'risk-medio' : 'risk-bajo');
                    @endphp
                    
                    <div class="risk-indicator {{ $riesgoClass }}">
                        <div class="risk-icon">{{ $factor['icon'] }}</div>
                        <div class="risk-content">
                            <div class="risk-title">{{ $factor['nombre'] }}</div>
                            <div class="risk-description">{{ $factor['descripcion'] }}</div>
                            <div style="display: flex; align-items: center; margin-top: 8px;">
                                <strong style="font-size: 1.3rem; margin-right: 10px;">{{ $factor['count'] }} predios</strong>
                                <span style="color: #666;">({{ $porcentaje }}% del total)</span>
                            </div>
                            <div class="comparison-bar">
                                <div class="comparison-fill" style="width: {{ $porcentaje }}%; background: {{ $porcentaje >= 50 ? '#f5576c' : ($porcentaje >= 25 ? '#E49B39' : '#0ba360') }};">
                                    {{ $porcentaje }}%
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="alert-box" style="margin-top: 20px;">
                    <strong><i class="bi bi-lightbulb"></i> Recomendaciones:</strong>
                    <ul style="margin: 10px 0 0 20px; font-size: 0.9rem;">
                        @if($caracterizacionRiesgo->colinda_riesgo > 0)
                            <li>Implementar medidas de bioseguridad en predios cercanos a establecimientos de riesgo</li>
                        @endif
                        @if($caracterizacionRiesgo->sacrifica_animales > 0)
                            <li>Capacitar sobre prácticas sanitarias de sacrificio y disposición de residuos</li>
                        @endif
                        @if(($totalPredios - $caracterizacionRiesgo->con_asistencia_tecnica) > ($totalPredios * 0.5))
                            <li>Aumentar cobertura de asistencia técnica veterinaria en la zona</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 4: VIGILANCIA ACTIVA -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card" style="border-left-color: #4facfe;">
                <div class="section-title">
                    <i class="bi bi-clipboard-check"></i> 4. Vigilancia Epidemiológica Activa
                </div>
                <div class="section-description">
                    🔍 Inspecciones y seguimiento sanitario realizado por autoridades competentes
                </div>
                
                @php
                    $prediosVisitados = $visitaPredios->predios_visitados ?? 0;
                    $cobertura = $totalPredios > 0 ? round(($prediosVisitados / $totalPredios) * 100) : 0;
                    $visitasConMuestras = $visitaPredios->con_toma_muestras ?? 0;
                    $efectividad = $prediosVisitados > 0 ? round(($visitasConMuestras / $prediosVisitados) * 100) : 0;
                @endphp
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="metric-box" style="border-color: #4facfe;">
                            <div class="metric-value" style="color: #4facfe;">{{ $prediosVisitados }}</div>
                            <div class="metric-percentage">{{ $cobertura }}% del total</div>
                            <div class="metric-label">Predios Inspeccionados</div>
                            <div class="metric-context">
                                Cobertura: {{ $cobertura >= 75 ? '✓ Alta' : ($cobertura >= 50 ? '⚡ Media' : '⚠ Baja') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="metric-box" style="border-color: #667eea;">
                            <div class="metric-value" style="color: #667eea;">{{ $visitasConMuestras }}</div>
                            <div class="metric-percentage">{{ $efectividad }}% de las visitas</div>
                            <div class="metric-label">Visitas con Muestreo</div>
                            <div class="metric-context">
                                Efectividad: {{ $efectividad >= 50 ? '✓ Buena' : '⚠ Mejorable' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="metric-box" style="border-color: #0ba360;">
                            <div class="metric-value" style="color: #0ba360;">{{ $visitaPredios->total_muestras_visita ?? 0 }}</div>
                            <div class="metric-percentage">
                                {{ $prediosVisitados > 0 ? round(($visitaPredios->total_muestras_visita ?? 0) / $prediosVisitados, 1) : 0 }} por predio
                            </div>
                            <div class="metric-label">Muestras Totales Tomadas</div>
                            <div class="metric-context">
                                Promedio de muestras por visita
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert-box" style="margin-top: 20px; background: {{ $cobertura >= 50 ? '#d4edda' : '#fff3cd' }}; border-color: {{ $cobertura >= 50 ? '#0ba360' : '#E49B39' }};">
                    <strong><i class="bi bi-graph-up"></i> Estado de la Vigilancia:</strong>
                    <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                        @if($cobertura >= 75)
                            ✓ <strong>Excelente cobertura</strong> - Se han inspeccionado {{ $prediosVisitados }} de {{ $totalPredios }} predios ({{ $cobertura }}%).
                        @elseif($cobertura >= 50)
                            ⚡ <strong>Cobertura aceptable</strong> - Se han inspeccionado {{ $prediosVisitados }} de {{ $totalPredios }} predios ({{ $cobertura }}%). 
                            Se recomienda ampliar la vigilancia.
                        @else
                            ⚠ <strong>Cobertura insuficiente</strong> - Solo se han inspeccionado {{ $prediosVisitados }} de {{ $totalPredios }} predios ({{ $cobertura }}%). 
                            Se requiere intensificar las visitas de vigilancia.
                        @endif
                        
                        @if($prediosEnfermos > 0)
                            <br><br>
                            <strong>Prioridad:</strong> Hay {{ $prediosEnfermos }} predios con animales enfermos reportados. 
                            Se recomienda priorizar visitas de seguimiento en estos predios.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filtrarMunicipio() {
    const municipio = document.getElementById('municipio_filter').value;
    window.location.href = '{{ route("dashboard.riesgo.epidemiologico.consolidado") }}?municipio=' + municipio;
}
</script>
@endsection