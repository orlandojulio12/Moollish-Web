@extends('layouts')

@section('template_title')
    Dashboard - Información de Predios
@endsection
@section('styles')
<style>
    .section-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .section-title {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 1rem;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 8px;
    }
    
    .adoption-bar {
        margin-bottom: 12px;
        padding: 8px 0;
    }
    
    .adoption-label {
        font-size: 0.85rem;
        color: #333;
        margin-bottom: 4px;
        display: flex;
        justify-content: space-between;
    }
    
    .adoption-progress {
        height: 24px;
        background: #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }
    
    .adoption-fill {
        height: 100%;
        display: flex;
        align-items: center;
        padding: 0 10px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        transition: width 0.3s;
    }
    
    .status-badge {
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .status-excelente { background: #0ba360; color: white; }
    .status-bueno { background: #4facfe; color: white; }
    .status-regular { background: #E49B39; color: white; }
    .status-bajo { background: #f5576c; color: white; }
    
    .vs-comparison {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .vs-box {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
    }
    
    .vs-si {
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: white;
    }
    
    .vs-no {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
        color: white;
    }
    
    .vs-value {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .vs-label {
        font-size: 0.75rem;
        opacity: 0.9;
    }
</style>
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('inicio') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Regional - Información de Predios</h5>
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
    
    <!-- SECCIÓN 1: ÁREAS -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-map"></i> 1. Distribución de Áreas ({{ $totalPrediosConAreas }} predios)
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 15px;">
                            📊 Distribución de hectáreas por tipo de uso del suelo
                        </p>
                        @php
                            $totalHectareas = $areasPorTipo->sum('hectareas_totales');
                        @endphp
                        @foreach($areasPorTipo->take(5) as $area)
                            @php
                                $porcentaje = $totalHectareas > 0 ? ($area->hectareas_totales / $totalHectareas) * 100 : 0;
                                $color = ['#667eea', '#E49B39', '#4facfe', '#0ba360', '#f5576c'][$loop->index % 5];
                            @endphp
                            <div class="adoption-bar">
                                <div class="adoption-label">
                                    <span>{{ $area->nombre_area }}</span>
                                    <span><strong>{{ number_format($area->hectareas_totales, 1) }} ha</strong> ({{ number_format($porcentaje, 1) }}%)</span>
                                </div>
                                <div class="adoption-progress">
                                    <div class="adoption-fill" style="width: {{ $porcentaje }}%; background: {{ $color }};">
                                        {{ $area->predios }} predios
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 2: TIERRAS Y AGUAS -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-droplet"></i> 2. Gestión de Tierras y Aguas ({{ $totalPrediosTierraAgua }} predios)
                </div>
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 15px;">
                    💧 Nivel de implementación de prácticas de gestión de recursos hídricos y suelos
                </p>
                <div class="row">
                    @php
                        $practicas = [
                            ['nombre' => 'Info de Suelos', 'valor' => $practicasAgua->con_info_suelos ?? 0],
                            ['nombre' => 'Drenaje', 'valor' => $practicasAgua->con_drenaje ?? 0],
                            ['nombre' => 'Fuente Agua', 'valor' => $practicasAgua->con_fuente_agua ?? 0],
                            ['nombre' => 'Manejo Cuencas', 'valor' => $practicasAgua->maneja_cuencas ?? 0],
                        ];
                    @endphp
                    @foreach($practicas as $index => $practica)
                        @php
                            $percent = $totalPrediosTierraAgua > 0 ? ($practica['valor'] / $totalPrediosTierraAgua) * 100 : 0;
                            $noImplementa = $totalPrediosTierraAgua - $practica['valor'];
                            
                            if($percent >= 70) { $status = 'excelente'; $color = '#0ba360'; }
                            elseif($percent >= 50) { $status = 'bueno'; $color = '#4facfe'; }
                            elseif($percent >= 30) { $status = 'regular'; $color = '#E49B39'; }
                            else { $status = 'bajo'; $color = '#f5576c'; }
                        @endphp
                        <div class="col-md-6 mb-3">
                            <strong style="font-size: 0.85rem;">{{ $practica['nombre'] }}</strong>
                            <span class="status-badge status-{{ $status }}" style="float: right;">
                                {{ number_format($percent, 0) }}%
                            </span>
                            <div class="vs-comparison" style="margin-top: 8px;">
                                <div class="vs-box vs-si">
                                    <div class="vs-value">{{ $practica['valor'] }}</div>
                                    <div class="vs-label">SÍ implementa</div>
                                </div>
                                <div class="vs-box vs-no">
                                    <div class="vs-value">{{ $noImplementa }}</div>
                                    <div class="vs-label">NO implementa</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 3: MANEJO DE PASTOS -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-flower2"></i> 3. Manejo de Pastos y Potreros ({{ $totalPrediosPastos }} predios)
                </div>
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 15px;">
                    🌱 Adopción de Buenas Prácticas Ganaderas en manejo de pastos
                </p>
                @php
                    $practicasPastos = [
                        ['nombre' => 'Fertilización de Potreros', 'valor' => $manejoPasstos->fertiliza_potreros ?? 0],
                        ['nombre' => 'Control de Plagas', 'valor' => $manejoPasstos->controla_plagas ?? 0],
                        ['nombre' => 'Control de Maleza', 'valor' => $manejoPasstos->controla_maleza ?? 0],
                        ['nombre' => 'División de Potreros', 'valor' => $manejoPasstos->tiene_division_potreros ?? 0],
                        ['nombre' => 'Tiene Cercas', 'valor' => $manejoPasstos->tiene_cercas ?? 0],
                    ];
                @endphp
                @foreach($practicasPastos as $practica)
                    @php
                        $percent = $totalPrediosPastos > 0 ? ($practica['valor'] / $totalPrediosPastos) * 100 : 0;
                        
                        if($percent >= 70) { $status = 'excelente'; $color = '#0ba360'; }
                        elseif($percent >= 50) { $status = 'bueno'; $color = '#4facfe'; }
                        elseif($percent >= 30) { $status = 'regular'; $color = '#E49B39'; }
                        else { $status = 'bajo'; $color = '#f5576c'; }
                    @endphp
                    <div class="adoption-bar">
                        <div class="adoption-label">
                            <span>{{ $practica['nombre'] }}</span>
                            <span>
                                <span class="status-badge status-{{ $status }}">{{ number_format($percent, 0) }}%</span>
                                <strong>{{ $practica['valor'] }}</strong> de {{ $totalPrediosPastos }}
                            </span>
                        </div>
                        <div class="adoption-progress">
                            <div class="adoption-fill" style="width: {{ $percent }}%; background: {{ $color }};">
                                {{ $practica['valor'] }} predios SÍ implementan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 4: MANEJO DE GANADO -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-heart-pulse"></i> 4. Manejo del Ganado ({{ $totalPrediosGanado }} predios)
                </div>
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 15px;">
                    🐄 Prácticas de manejo sanitario y productivo del hato ganadero
                </p>
                @php
                    $practicasGanado = [
                        ['nombre' => 'Identifica Animales', 'valor' => $manejoGanado->identifica_animales ?? 0],
                        ['nombre' => 'Sistema de Cría', 'valor' => $manejoGanado->sistema_cria ?? 0],
                        ['nombre' => 'Control Parásitos', 'valor' => $manejoGanado->control_parasitos ?? 0],
                        ['nombre' => 'Pesaje de Animales', 'valor' => $manejoGanado->hace_pesaje ?? 0],
                        ['nombre' => 'Suministra Sal', 'valor' => $manejoGanado->suministra_sal ?? 0],
                    ];
                @endphp
                @foreach($practicasGanado as $practica)
                    @php
                        $percent = $totalPrediosGanado > 0 ? ($practica['valor'] / $totalPrediosGanado) * 100 : 0;
                        
                        if($percent >= 70) { $status = 'excelente'; $color = '#0ba360'; }
                        elseif($percent >= 50) { $status = 'bueno'; $color = '#4facfe'; }
                        elseif($percent >= 30) { $status = 'regular'; $color = '#E49B39'; }
                        else { $status = 'bajo'; $color = '#f5576c'; }
                    @endphp
                    <div class="adoption-bar">
                        <div class="adoption-label">
                            <span>{{ $practica['nombre'] }}</span>
                            <span>
                                <span class="status-badge status-{{ $status }}">{{ number_format($percent, 0) }}%</span>
                                <strong>{{ $practica['valor'] }}</strong> de {{ $totalPrediosGanado }}
                            </span>
                        </div>
                        <div class="adoption-progress">
                            <div class="adoption-fill" style="width: {{ $percent }}%; background: {{ $color }};">
                                {{ $practica['valor'] }} predios implementan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 5: ASPECTOS AMBIENTALES -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-tree"></i> 5. Gestión Ambiental ({{ $totalPrediosAmbiental }} predios)
                </div>
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 15px;">
                    🌳 Manejo responsable de residuos y prácticas ambientales
                </p>
                <div class="row">
                    @php
                        $practicasAmb = [
                            ['nombre' => 'Aguas Servidas', 'valor' => $aspectosAmbientales->maneja_aguas ?? 0],
                            ['nombre' => 'Excrementos', 'valor' => $aspectosAmbientales->maneja_excrementos ?? 0],
                            ['nombre' => 'Basuras', 'valor' => $aspectosAmbientales->maneja_basuras ?? 0],
                            ['nombre' => 'Químicos', 'valor' => $aspectosAmbientales->maneja_quimicos ?? 0],
                        ];
                    @endphp
                    @foreach($practicasAmb as $practica)
                        @php
                            $percent = $totalPrediosAmbiental > 0 ? ($practica['valor'] / $totalPrediosAmbiental) * 100 : 0;
                            $noImplementa = $totalPrediosAmbiental - $practica['valor'];
                            
                            if($percent >= 70) { $status = 'excelente'; }
                            elseif($percent >= 50) { $status = 'bueno'; }
                            elseif($percent >= 30) { $status = 'regular'; }
                            else { $status = 'bajo'; }
                        @endphp
                        <div class="col-md-6 mb-3">
                            <strong style="font-size: 0.85rem;">{{ $practica['nombre'] }}</strong>
                            <span class="status-badge status-{{ $status }}" style="float: right;">
                                {{ number_format($percent, 0) }}%
                            </span>
                            <div class="vs-comparison" style="margin-top: 8px;">
                                <div class="vs-box vs-si">
                                    <div class="vs-value">{{ $practica['valor'] }}</div>
                                    <div class="vs-label">SÍ maneja</div>
                                </div>
                                <div class="vs-box vs-no">
                                    <div class="vs-value">{{ $noImplementa }}</div>
                                    <div class="vs-label">NO maneja</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 6: EQUIPOS -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-tools"></i> 6. Equipos e Instalaciones ({{ $totalPrediosEquipos }} predios)
                </div>
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 15px;">
                    🔧 Nivel de mecanización e infraestructura disponible
                </p>
                <div class="row">
                    @foreach($equiposInstalaciones->take(6) as $equipo)
                        @php
                            $percent = $totalPredios > 0 ? ($equipo->predios_con_equipo / $totalPredios) * 100 : 0;
                        @endphp
                        <div class="col-md-6">
                            <div class="adoption-bar">
                                <div class="adoption-label">
                                    <span style="font-size: 0.8rem;">{{ $equipo->nombre_tipo }}</span>
                                    <strong>{{ $equipo->predios_con_equipo }}</strong>
                                </div>
                                <div class="adoption-progress">
                                    <div class="adoption-fill" style="width: {{ $percent }}%; background: #667eea;">
                                        {{ number_format($percent, 0) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 7: GESTIÓN INFO -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-file-earmark-text"></i> 7. Gestión de Información ({{ $totalPrediosGestion }} predios)
                </div>
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 15px;">
                    📋 Nivel de formalización en registro y uso de información productiva
                </p>
                @php
                    $practicasGestion = [
                        ['nombre' => 'Lleva Registros', 'valor' => $gestionInfo->lleva_registros ?? 0],
                        ['nombre' => 'Calcula Indicadores', 'valor' => $gestionInfo->calcula_indicadores ?? 0],
                        ['nombre' => 'Usa la Información', 'valor' => $gestionInfo->usa_informacion ?? 0],
                        ['nombre' => 'Registra Info Finca', 'valor' => $gestionInfo->registra_info ?? 0],
                    ];
                @endphp
                @foreach($practicasGestion as $practica)
                    @php
                        $percent = $totalPrediosGestion > 0 ? ($practica['valor'] / $totalPrediosGestion) * 100 : 0;
                        
                        if($percent >= 70) { $status = 'excelente'; $color = '#0ba360'; }
                        elseif($percent >= 50) { $status = 'bueno'; $color = '#4facfe'; }
                        elseif($percent >= 30) { $status = 'regular'; $color = '#E49B39'; }
                        else { $status = 'bajo'; $color = '#f5576c'; }
                    @endphp
                    <div class="adoption-bar">
                        <div class="adoption-label">
                            <span>{{ $practica['nombre'] }}</span>
                            <span>
                                <span class="status-badge status-{{ $status }}">{{ number_format($percent, 0) }}%</span>
                                <strong>{{ $practica['valor'] }}</strong> de {{ $totalPrediosGestion }}
                            </span>
                        </div>
                        <div class="adoption-progress">
                            <div class="adoption-fill" style="width: {{ $percent }}%; background: {{ $color }};">
                                {{ $practica['valor'] }} predios
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function filtrarMunicipio() {
    const municipio = document.getElementById('municipio_filter').value;
    window.location.href = '{{ route("dashboard.info.predios.consolidado") }}?municipio=' + municipio;
}
</script>
@endsection