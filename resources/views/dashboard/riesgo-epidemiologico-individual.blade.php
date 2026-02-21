@extends('layouts')

@section('template_title')
Dashboard Individual - Riesgo Epidemiológico
@endsection

@section('styles')
<style>
    .selector-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .score-card {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(245, 87, 108, 0.4);
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
        background: rgba(255, 255, 255, 0.2);
    }

    .section-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
        border-left: 4px solid #f5576c;
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
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
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

    .factor-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 8px;
        background: #f8f9fa;
    }

    .factor-icon {
        font-size: 1.5rem;
        margin-right: 12px;
    }

    .factor-presente {
        background: #ffe6e6;
        border-left: 3px solid #f5576c;
    }

    .factor-ausente {
        background: #d4edda;
        border-left: 3px solid #0ba360;
    }

    .species-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 15px;
    }

    .species-item {
        padding: 10px;
        border-radius: 6px;
        text-align: center;
        font-size: 0.85rem;
    }

    .species-si {
        background: #d4edda;
        color: #0ba360;
        font-weight: 600;
    }

    .species-no {
        background: #f8f9fa;
        color: #999;
    }
</style>
@endsection

@section('page-header')
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title d-flex align-items-center">
            <a href="{{ route('dashboard.riesgo.epidemiologico.consolidado') }}"><i class="bi bi-arrow-left"
                    style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
            <h5>Dashboard Individual - Riesgo Epidemiológico</h5>
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
                <p class="text-muted">Selecciona un predio para ver su evaluación de riesgo epidemiológico</p>

                <form method="GET" action="{{ route('dashboard.riesgo.epidemiologico.individual') }}">
                    <div class="row">
                        <div class="col-md-8">
                            <select name="predio" class="form-select" onchange="this.form.submit()" required>
                                <option value="">-- Selecciona un predio --</option>
                                @foreach($todosPredios as $p)
                                <option value="{{ $p->id }}" {{ $predioId==$p->id ? 'selected' : '' }}>
                                    {{ $p->nombre_predio }} ({{ $p->municipio }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('dashboard.riesgo.epidemiologico.consolidado') }}"
                                class="btn btn-outline-primary">
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
                <div class="score-label">ÍNDICE DE RIESGO</div>
                @php
                if($scoreGeneral >= 70) { $texto = '⚠ RIESGO ALTO'; }
                elseif($scoreGeneral >= 40) { $texto = '⚡ RIESGO MODERADO'; }
                else { $texto = '✓ RIESGO BAJO'; }
                @endphp
                <div class="score-badge">{{ $texto }}</div>
                <div style="margin-top: 15px; font-size: 0.85rem;">
                    0 = Sin riesgo | 100 = Riesgo máximo
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div
                        style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                        <i class="bi bi-graph-up" style="font-size: 2rem; color: #E49B39;"></i>
                        <div style="font-size: 2.5rem; font-weight: bold; color: #333; margin: 10px 0;">{{
                            $scorePromedioMunicipal }}</div>
                        <div style="font-size: 0.85rem; color: #666;">Riesgo Promedio {{ $predio->municipio }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div
                        style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                        <i class="bi bi-{{ $scoreGeneral <= $scorePromedioMunicipal ? 'arrow-down-circle' : 'arrow-up-circle' }}"
                            style="font-size: 2rem; color: {{ $scoreGeneral <= $scorePromedioMunicipal ? '#0ba360' : '#f5576c' }};"></i>
                        <div
                            style="font-size: 2.5rem; font-weight: bold; color: {{ $scoreGeneral <= $scorePromedioMunicipal ? '#0ba360' : '#f5576c' }}; margin: 10px 0;">
                            {{ abs($scoreGeneral - $scorePromedioMunicipal) }} pts
                        </div>
                        <div style="font-size: 0.85rem; color: #666;">
                            {{ $scoreGeneral <= $scorePromedioMunicipal ? '✓ Mejor que' : '⚠ Por encima de' }} promedio
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
                <h5 style="color: #0ba360; margin-bottom: 15px;"><i class="bi bi-shield-check"></i> FORTALEZAS</h5>
                @foreach($fortalezas as $fortaleza)
                <div class="fortaleza-card">
                    <div class="fortaleza-texto">✓ {{ $fortaleza }}</div>
                </div>
                @endforeach
            </div>
            @endif

            @if(count($recomendaciones) > 0)
            <div class="col-md-{{ count($fortalezas) > 0 ? '6' : '12' }}">
                <h5 style="color: #E49B39; margin-bottom: 15px;"><i class="bi bi-exclamation-triangle"></i>
                    RECOMENDACIONES PRIORITARIAS</h5>
                @foreach(collect($recomendaciones)->take(5) as $rec)
                <div class="recomendacion-card">
                    <div class="recomendacion-area">{{ $rec['area'] }}</div>
                    <div class="recomendacion-texto">{{ $rec['texto'] }}</div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Análisis Detallado -->
        <div class="row">
            <div class="col-12">
                <h5 style="color: #f5576c; margin-bottom: 20px;">
                    <i class="bi bi-clipboard-data"></i> ANÁLISIS DETALLADO DE RIESGO
                </h5>
            </div>
        </div>


        <!-- 1. Diversidad de Especies -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card">
                    <div class="section-header">
                        <div>
                            <div class="section-title">1. Diversidad de Especies en el Predio</div>
                            <small class="text-muted">Tipos de animales que se manejan en este predio</small>
                        </div>
                        <div class="section-score"
                            style="color: {{ $miDiversidad <= 3 ? '#0ba360' : ($miDiversidad <= 5 ? '#E49B39' : '#f5576c') }};">
                            {{ $miDiversidad }} {{ $miDiversidad == 1 ? 'especie' : 'especies' }}
                        </div>
                    </div>

                    <!-- Explicación Clara -->
                    <div
                        style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #667eea;">
                        <strong><i class="bi bi-info-circle"></i> ¿Qué significa esto?</strong>
                        <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                            Este predio maneja <strong>{{ $miDiversidad }} {{ $miDiversidad == 1 ? 'tipo' : 'tipos' }}
                                diferentes de animales</strong> (de 9 posibles: bovinos, bufalinos, porcinos, equinos,
                            ovinos, caprinos, aves, peces, apícola).
                        </p>
                        <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                            <strong>Impacto en el riesgo:</strong>
                            @if($miDiversidad <= 2) ✓ <span style="color: #0ba360; font-weight: 600;">BAJA
                                COMPLEJIDAD</span> - Con pocas especies es más fácil controlar la sanidad animal y
                                prevenir enfermedades.
                                @elseif($miDiversidad <= 4) ⚡ <span style="color: #E49B39; font-weight: 600;">
                                    COMPLEJIDAD MODERADA</span> - Manejar varias especies requiere más atención
                                    sanitaria, ya que cada una tiene sus propias enfermedades y necesidades de manejo.
                                    @else
                                    ⚠ <span style="color: #f5576c; font-weight: 600;">ALTA COMPLEJIDAD</span> - Manejar
                                    muchas especies diferentes aumenta el riesgo epidemiológico porque:
                                    <ul style="margin: 5px 0 0 20px; font-size: 0.85rem;">
                                        <li>Cada especie puede transmitir enfermedades a otras (zoonosis)</li>
                                        <li>Se requieren múltiples programas sanitarios y de bioseguridad</li>
                                        <li>Mayor dificultad para aislar animales enfermos</li>
                                        <li>Más recursos humanos y técnicos necesarios</li>
                                    </ul>
                                    @endif
                        </p>
                    </div>

                    <div class="comparison-bar">
                        <div class="comparison-box mi-predio">
                            <div class="comparison-value">{{ $miDiversidad }}</div>
                            <div class="comparison-label">ESPECIES EN MI PREDIO</div>
                        </div>
                        <div class="comparison-box promedio">
                            <div class="comparison-value">{{ round($promedioDiversidad, 1) }}</div>
                            <div class="comparison-label">PROMEDIO {{ strtoupper($predio->municipio) }}</div>
                        </div>
                    </div>

                    <!-- Detalle de Especies -->
                    <div style="margin-top: 20px;">
                        <strong style="font-size: 0.9rem; color: #666;">Especies presentes en este predio:</strong>
                        <div class="species-grid" style="margin-top: 10px;">
                            <div
                                class="species-item {{ $miTipoExplotacion->tiene_bovinos > 0 ? 'species-si' : 'species-no' }}">
                                {{ $miTipoExplotacion->tiene_bovinos > 0 ? '✓' : '✗' }} Bovinos
                            </div>
                            <div
                                class="species-item {{ $miTipoExplotacion->tiene_bufalinos > 0 ? 'species-si' : 'species-no' }}">
                                {{ $miTipoExplotacion->tiene_bufalinos > 0 ? '✓' : '✗' }} Bufalinos
                            </div>
                            <div
                                class="species-item {{ $miTipoExplotacion->tiene_porcinos > 0 ? 'species-si' : 'species-no' }}">
                                {{ $miTipoExplotacion->tiene_porcinos > 0 ? '✓' : '✗' }} Porcinos
                            </div>
                            <div
                                class="species-item {{ $miTipoExplotacion->tiene_equinos > 0 ? 'species-si' : 'species-no' }}">
                                {{ $miTipoExplotacion->tiene_equinos > 0 ? '✓' : '✗' }} Equinos
                            </div>
                            <div
                                class="species-item {{ $miTipoExplotacion->tiene_ovinos > 0 ? 'species-si' : 'species-no' }}">
                                {{ $miTipoExplotacion->tiene_ovinos > 0 ? '✓' : '✗' }} Ovinos
                            </div>
                            <div
                                class="species-item {{ $miTipoExplotacion->tiene_caprinos > 0 ? 'species-si' : 'species-no' }}">
                                {{ $miTipoExplotacion->tiene_caprinos > 0 ? '✓' : '✗' }} Caprinos
                            </div>
                            <div
                                class="species-item {{ $miTipoExplotacion->tiene_aves_corral > 0 ? 'species-si' : 'species-no' }}">
                                {{ $miTipoExplotacion->tiene_aves_corral > 0 ? '✓' : '✗' }} Aves de Corral
                            </div>
                            <div
                                class="species-item {{ $miTipoExplotacion->tiene_peces > 0 ? 'species-si' : 'species-no' }}">
                                {{ $miTipoExplotacion->tiene_peces > 0 ? '✓' : '✗' }} Peces
                            </div>
                            <div
                                class="species-item {{ $miTipoExplotacion->tiene_apicolas > 0 ? 'species-si' : 'species-no' }}">
                                {{ $miTipoExplotacion->tiene_apicolas > 0 ? '✓' : '✗' }} Apícola (Abejas)
                            </div>
                        </div>
                    </div>

                    <!-- Interpretación Final -->
                    <div
                        style="background: {{ $miDiversidad <= 3 ? '#d4edda' : ($miDiversidad <= 5 ? '#fff3cd' : '#ffe6e6') }}; padding: 12px; border-radius: 8px; margin-top: 15px; border-left: 4px solid {{ $miDiversidad <= 3 ? '#0ba360' : ($miDiversidad <= 5 ? '#E49B39' : '#f5576c') }};">
                        <strong style="font-size: 0.85rem;">
                            <i
                                class="bi bi-{{ $miDiversidad <= 3 ? 'check-circle' : ($miDiversidad <= 5 ? 'exclamation-circle' : 'x-circle') }}"></i>
                            Interpretación:
                        </strong>
                        <p style="margin: 5px 0 0 0; font-size: 0.85rem;">
                            @if($miDiversidad <= 2) Tu predio tiene <strong>baja diversidad</strong> ({{ $miDiversidad
                                }} especies). Esto facilita el control sanitario y reduce el riesgo de transmisión de
                                enfermedades entre especies.
                                @elseif($miDiversidad <= 4) Tu predio tiene <strong>diversidad moderada</strong> ({{
                                    $miDiversidad }} especies). Se recomienda tener programas sanitarios específicos
                                    para cada especie y mantener buenas prácticas de bioseguridad.
                                    @else
                                    Tu predio tiene <strong>alta diversidad</strong> ({{ $miDiversidad }} especies). Es
                                    fundamental implementar:
                                    • Protocolos de bioseguridad estrictos
                                    • Separación física entre especies
                                    • Programas sanitarios diferenciados
                                    • Asistencia técnica veterinaria regular
                                    @endif

                                    @if($miDiversidad > round($promedioDiversidad))
                                    <br><br>
                                    <strong>Nota:</strong> Tu predio tiene {{ $miDiversidad - round($promedioDiversidad)
                                    }} {{ ($miDiversidad - round($promedioDiversidad)) == 1 ? 'especie más' : 'especies
                                    más' }} que el promedio municipal ({{ round($promedioDiversidad, 1) }}), lo que
                                    requiere mayor atención sanitaria.
                                    @elseif($miDiversidad < round($promedioDiversidad)) <br><br>
                                        <strong>Nota:</strong> Tu predio tiene {{ round($promedioDiversidad) -
                                        $miDiversidad }} {{ (round($promedioDiversidad) - $miDiversidad) == 1 ? 'especie
                                        menos' : 'especies menos' }} que el promedio municipal ({{
                                        round($promedioDiversidad, 1) }}), lo que facilita el manejo sanitario.
                                        @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Situación Sanitaria -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card" style="border-left-color: #E49B39;">
                    <div class="section-header">
                        <div>
                            <div class="section-title">2. Situación Sanitaria</div>
                            <small class="text-muted">Presencia de enfermedades y diagnóstico</small>
                        </div>
                        <div class="section-score"
                            style="color: {{ $miInfoEpidemiologica->tiene_enfermos > 0 ? '#f5576c' : '#0ba360' }};">
                            {{ $miInfoEpidemiologica->tiene_enfermos > 0 ? 'Con casos' : 'Sin casos' }}
                        </div>
                    </div>

                    @if($miInfoEpidemiologica->tiene_enfermos > 0)
                    <div
                        style="background: #ffe6e6; padding: 15px; border-radius: 8px; border-left: 4px solid #f5576c;">
                        <strong style="color: #f5576c;">⚠ ALERTA SANITARIA</strong>
                        <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                            Se detectaron <strong>{{ $miInfoEpidemiologica->total_enfermos }}</strong> animales con
                            signología compatible con enfermedades de control oficial.
                            @if($miInfoEpidemiologica->tiene_muestras > 0)
                            Se tomaron muestras para diagnóstico confirmatorio.
                            @else
                            <br><strong>Acción requerida:</strong> Tomar muestras para diagnóstico confirmatorio.
                            @endif
                        </p>
                    </div>
                    @else
                    <div
                        style="background: #d4edda; padding: 15px; border-radius: 8px; border-left: 4px solid #0ba360;">
                        <strong style="color: #0ba360;">✓ ESTADO FAVORABLE</strong>
                        <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                            No se reportan animales con signología de enfermedades de control oficial.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3. Factores de Riesgo -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card" style="border-left-color: #f5576c;">
                    <div class="section-header">
                        <div>
                            <div class="section-title">3. Factores de Riesgo Presentes</div>
                            <small class="text-muted">{{ $misFactoresRiesgo }} de 4 factores de riesgo
                                identificados</small>
                        </div>
                        <div class="section-score"
                            style="color: {{ $misFactoresRiesgo >= 3 ? '#f5576c' : ($misFactoresRiesgo >= 2 ? '#E49B39' : '#0ba360') }};">
                            {{ $misFactoresRiesgo }}/4
                        </div>
                    </div>

                    <div class="comparison-bar">
                        <div class="comparison-box mi-predio">
                            <div class="comparison-value">{{ $misFactoresRiesgo }}</div>
                            <div class="comparison-label">MI PREDIO</div>
                        </div>
                        <div class="comparison-box promedio">
                            <div class="comparison-value">{{ round($promedioFactoresRiesgo->promedio ?? 0, 1) }}</div>
                            <div class="comparison-label">PROMEDIO {{ strtoupper($predio->municipio) }}</div>
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
                        <div
                            class="factor-item {{ $miCaracterizacionRiesgo->colinda_riesgo > 0 ? 'factor-presente' : 'factor-ausente' }}">
                            <div class="factor-icon">{{ $miCaracterizacionRiesgo->colinda_riesgo > 0 ? '⚠' : '✓' }}
                            </div>
                            <div>
                                <strong>Proximidad a Establecimientos de Riesgo</strong><br>
                                <small>{{ $miCaracterizacionRiesgo->colinda_riesgo > 0 ? 'SÍ colinda con ferias,
                                    mataderos, acopios o basureros' : 'NO colinda con establecimientos de riesgo'
                                    }}</small>
                            </div>
                        </div>

                        <div
                            class="factor-item {{ $miCaracterizacionRiesgo->sacrifica > 0 ? 'factor-presente' : 'factor-ausente' }}">
                            <div class="factor-icon">{{ $miCaracterizacionRiesgo->sacrifica > 0 ? '⚠' : '✓' }}</div>
                            <div>
                                <strong>Sacrificio en Predio</strong><br>
                                <small>{{ $miCaracterizacionRiesgo->sacrifica > 0 ? 'SÍ realiza sacrificio de animales
                                    en el predio' : 'NO realiza sacrificio en el predio' }}</small>
                            </div>
                        </div>

                        <div
                            class="factor-item {{ $miCaracterizacionRiesgo->trabajadores_otras > 0 ? 'factor-presente' : 'factor-ausente' }}">
                            <div class="factor-icon">{{ $miCaracterizacionRiesgo->trabajadores_otras > 0 ? '⚠' : '✓' }}
                            </div>
                            <div>
                                <strong>Movilidad Laboral</strong><br>
                                <small>{{ $miCaracterizacionRiesgo->trabajadores_otras > 0 ? 'Trabajadores laboran en
                                    otras explotaciones (riesgo de transmisión)' : 'Trabajadores NO laboran en otras
                                    explotaciones' }}</small>
                            </div>
                        </div>

                        <div
                            class="factor-item {{ $miCaracterizacionRiesgo->tiene_asistencia == 0 ? 'factor-presente' : 'factor-ausente' }}">
                            <div class="factor-icon">{{ $miCaracterizacionRiesgo->tiene_asistencia == 0 ? '⚠' : '✓' }}
                            </div>
                            <div>
                                <strong>Asistencia Técnica</strong><br>
                                <small>{{ $miCaracterizacionRiesgo->tiene_asistencia > 0 ? 'SÍ recibe asistencia técnica
                                    veterinaria regular' : 'NO recibe asistencia técnica (aumenta riesgo sanitario)'
                                    }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Vigilancia -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-card" style="border-left-color: #4facfe;">
                    <div class="section-header">
                        <div>
                            <div class="section-title">4. Estado de Vigilancia</div>
                            <small class="text-muted">Inspecciones y seguimiento sanitario oficial</small>
                        </div>
                        <div class="section-score"
                            style="color: {{ $miVisita->fue_visitado > 0 ? '#0ba360' : '#f5576c' }};">
                            {{ $miVisita->fue_visitado > 0 ? 'Visitado' : 'No visitado' }}
                        </div>
                    </div>

                    @if($miVisita->fue_visitado > 0)
                    <div
                        style="background: #d4edda; padding: 15px; border-radius: 8px; border-left: 4px solid #0ba360;">
                        <strong style="color: #0ba360;">✓ PREDIO INSPECCIONADO</strong>
                        <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                            Este predio ha sido visitado por la autoridad sanitaria.
                            @if($miVisita->con_muestras > 0)
                            Se realizó toma de muestras durante la inspección.
                            @endif
                        </p>
                    </div>
                    @else
                    <div
                        style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #E49B39;">
                        <strong style="color: #E49B39;">⚡ SIN INSPECCIÓN REGISTRADA</strong>
                        <p style="margin: 10px 0 0 0; font-size: 0.9rem;">
                            Este predio no ha sido visitado por la autoridad sanitaria para vigilancia epidemiológica.
                            <br><strong>Recomendación:</strong> Solicitar inspección sanitaria oficial.
                        </p>
                    </div>
                    @endif
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