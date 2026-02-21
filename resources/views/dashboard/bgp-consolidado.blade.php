@extends('layouts')

@section('template_title')
    Dashboard - Buenas Prácticas Ganaderas (BPG)
@endsection
@section('styles')
<style>
    .score-general-card {
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: white;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(11, 163, 96, 0.4);
    }
    
    .score-general-value {
        font-size: 5rem;
        font-weight: bold;
        line-height: 1;
    }
    
    .score-general-label {
        font-size: 1.2rem;
        opacity: 0.95;
        margin-top: 10px;
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
        font-size: 2rem;
        font-weight: bold;
    }
    
    .status-breakdown {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    
    .status-box {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
    }
    
    .status-si {
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: white;
    }
    
    .status-no {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
        color: white;
    }
    
    .status-na {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .status-value {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .status-label {
        font-size: 0.75rem;
        opacity: 0.9;
    }
    
    .critica-card {
        background: #fff3cd;
        border-left: 4px solid #E49B39;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    
    .critica-nombre {
        font-size: 0.85rem;
        color: #333;
        margin-bottom: 5px;
    }
    
    .critica-porcentaje {
        font-weight: 600;
        color: #f5576c;
    }
    
    .progress-bar-custom {
        height: 8px;
        border-radius: 10px;
        background: #f0f0f0;
        margin-top: 5px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: #0ba360;
        transition: width 0.3s;
    }
</style>
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('inicio') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Regional - Buenas Prácticas Ganaderas (BPG)</h5>
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
    
    <!-- Score General -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="score-general-card">
                <div class="score-general-value">{{ $scoreGeneral }}%</div>
                <div class="score-general-label">CUMPLIMIENTO GENERAL BPG</div>
                @php
                    if($scoreGeneral >= 75) { $texto = '⭐ EXCELENTE'; }
                    elseif($scoreGeneral >= 60) { $texto = '✓ BUENO'; }
                    elseif($scoreGeneral >= 40) { $texto = '⚠ REGULAR'; }
                    else { $texto = '⚡ BAJO'; }
                @endphp
                <div style="margin-top: 15px; font-size: 1rem;">{{ $texto }}</div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="alert alert-info">
                <h5><i class="bi bi-info-circle"></i> ¿Qué son las Buenas Prácticas Ganaderas (BPG)?</h5>
                <p style="margin-bottom: 0; font-size: 0.9rem;">
                    Son un conjunto de normas y procedimientos que garantizan la calidad e inocuidad de los productos de origen animal, 
                    protegen la salud animal y humana, y promueven el bienestar animal y la sostenibilidad ambiental. 
                    Este dashboard evalúa el cumplimiento de <strong>{{ collect($datosPorSeccion)->sum('total_practicas') }} prácticas</strong> 
                    organizadas en <strong>8 secciones</strong>.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Secciones de BPG -->
    <div class="row">
        <div class="col-12">
            <h5 style="color: #0ba360; margin-bottom: 20px;">
                <i class="bi bi-clipboard-check"></i> Nivel de Cumplimiento por Sección
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
                            <small class="text-muted">{{ $seccion['total_practicas'] }} prácticas evaluadas</small>
                        </div>
                        <div class="section-score" style="color: {{ $seccion['porcentaje_cumplimiento'] >= 60 ? '#0ba360' : ($seccion['porcentaje_cumplimiento'] >= 40 ? '#E49B39' : '#f5576c') }};">
                            {{ $seccion['porcentaje_cumplimiento'] }}%
                        </div>
                    </div>
                    
                    <div class="status-breakdown">
                        <div class="status-box status-si">
                            <div class="status-value">{{ $seccion['total_si'] }}</div>
                            <div class="status-label">SÍ CUMPLE</div>
                            <div style="font-size: 0.7rem; margin-top: 3px;">
                                {{ $seccion['total_respuestas'] > 0 ? round(($seccion['total_si'] / $seccion['total_respuestas']) * 100) : 0 }}%
                            </div>
                        </div>
                        <div class="status-box status-no">
                            <div class="status-value">{{ $seccion['total_no'] }}</div>
                            <div class="status-label">NO CUMPLE</div>
                            <div style="font-size: 0.7rem; margin-top: 3px;">
                                {{ $seccion['total_respuestas'] > 0 ? round(($seccion['total_no'] / $seccion['total_respuestas']) * 100) : 0 }}%
                            </div>
                        </div>
                        <div class="status-box status-na">
                            <div class="status-value">{{ $seccion['total_na'] }}</div>
                            <div class="status-label">NO APLICA</div>
                            <div style="font-size: 0.7rem; margin-top: 3px;">
                                {{ $seccion['total_respuestas'] > 0 ? round(($seccion['total_na'] / $seccion['total_respuestas']) * 100) : 0 }}%
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 12px;">
                        <small class="text-muted">
                            <i class="bi bi-people"></i> {{ $seccion['predios_respondieron'] }} predios evaluados
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    
    <!-- Prácticas Críticas -->
    @if(count($practicasCriticas) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <h5 style="color: #E49B39; margin-bottom: 20px;">
                <i class="bi bi-exclamation-triangle"></i> Prácticas Críticas (Menor cumplimiento)
            </h5>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="section-card" style="border-left-color: #E49B39;">
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 15px;">
                    Estas son las prácticas con mayor incumplimiento (menos del 50% de respuestas "Sí")
                </p>
                @foreach($practicasCriticas as $critica)
                    <div class="critica-card">
                        <div class="critica-nombre">{{ $critica->nombre }}</div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <small>
                                <strong>{{ $critica->total_si }}</strong> SÍ / 
                                <strong>{{ $critica->total_no }}</strong> NO
                            </small>
                            <span class="critica-porcentaje">{{ $critica->porcentaje_si }}% cumplimiento</span>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="progress-fill" style="width: {{ $critica->porcentaje_si }}%; background: {{ $critica->porcentaje_si < 30 ? '#f5576c' : '#E49B39' }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function filtrarMunicipio() {
    const municipio = document.getElementById('municipio_filter').value;
    window.location.href = '{{ route("dashboard.bgp.consolidado") }}?municipio=' + municipio;
}
</script>
@endsection