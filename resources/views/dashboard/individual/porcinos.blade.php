@extends('layouts')

@section('template_title')
    Dashboard Individual - Porcinos
@endsection

@section('styles')
<style>
    .selector-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .kpi-predio {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
    }
    
    .kpi-promedio {
        background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(228, 155, 57, 0.3);
    }
    
    .kpi-comparacion {
        background: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        border: 3px solid #f5576c;
    }
    
    .kpi-comparacion.positive {
        border-color: #0ba360;
        background: linear-gradient(135deg, #f0fff8 0%, #e8fff4 100%);
    }
    
    .kpi-comparacion.negative {
        border-color: #f5576c;
        background: linear-gradient(135deg, #fff0f2 0%, #ffe8eb 100%);
    }
    
    .kpi-title {
        font-size: 0.9rem;
        font-weight: 600;
        opacity: 0.9;
        margin-bottom: 10px;
    }
    
    .kpi-value {
        font-size: 3rem;
        font-weight: bold;
        margin: 10px 0;
    }
    
    .kpi-subtitle {
        font-size: 0.85rem;
        opacity: 0.8;
    }
    
    .chart-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .chart-card h5 {
        color: #f5576c;
        font-weight: 600;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('dashboard.censo.porcinos') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Individual - Censo Porcinos</h5>
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
                <p class="text-muted">Selecciona un predio para ver su análisis individual de porcinos</p>
                
                <form method="GET" action="{{ route('dashboard.individual.porcinos') }}">
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
                            <a href="{{ route('dashboard.comparativo.porcinos') }}" class="btn btn-outline-primary">
                                <i class="bi bi-bar-chart"></i> Ver Comparativo
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @if($datosPredio)
        <!-- Título del Predio -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <h4 class="mb-0">
                        <i class="bi bi-building"></i> {{ $datosPredio['nombre'] }}
                        <span class="badge bg-secondary ms-2">{{ $datosPredio['municipio'] }}</span>
                    </h4>
                </div>
            </div>
        </div>
        
        <!-- KPIs Principales -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="kpi-predio">
                    <div class="kpi-title"><i class="bi bi-house-fill"></i> MI PREDIO</div>
                    <div class="kpi-value">{{ number_format($datosPredio['total']) }}</div>
                    <div class="kpi-subtitle">Total Porcinos</div>
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    <div class="row mt-3">
                        <div class="col-4">
                            <strong>{{ number_format($datosPredio['lactancia']) }}</strong>
                            <br><small>Lactancia</small>
                        </div>
                        <div class="col-4">
                            <strong>{{ number_format($datosPredio['precebo']) }}</strong>
                            <br><small>Precebo</small>
                        </div>
                        <div class="col-4">
                            <strong>{{ number_format($datosPredio['levante']) }}</strong>
                            <br><small>Levante</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="kpi-promedio">
                    <div class="kpi-title"><i class="bi bi-graph-up"></i> PROMEDIO MUNICIPAL</div>
                    <div class="kpi-value">{{ number_format($promedioMunicipal->avg_total, 0) }}</div>
                    <div class="kpi-subtitle">Promedio en {{ $datosPredio['municipio'] }}</div>
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    <div class="row mt-3">
                        <div class="col-4">
                            <strong>{{ number_format($promedioMunicipal->avg_lactancia, 0) }}</strong>
                            <br><small>Lactancia</small>
                        </div>
                        <div class="col-4">
                            <strong>{{ number_format($promedioMunicipal->avg_precebo, 0) }}</strong>
                            <br><small>Precebo</small>
                        </div>
                        <div class="col-4">
                            <strong>{{ number_format($promedioMunicipal->avg_levante, 0) }}</strong>
                            <br><small>Levante</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="kpi-comparacion {{ $comparacion['total_percent'] >= 0 ? 'positive' : 'negative' }}">
                    <div class="kpi-title" style="color: #333;">
                        <i class="bi bi-{{ $comparacion['total_percent'] >= 0 ? 'arrow-up-circle-fill' : 'arrow-down-circle-fill' }}"></i> 
                        COMPARACIÓN
                    </div>
                    <div class="kpi-value" style="color: {{ $comparacion['total_percent'] >= 0 ? '#0ba360' : '#f5576c' }};">
                        {{ $comparacion['total_percent'] >= 0 ? '+' : '' }}{{ $comparacion['total_percent'] }}%
                    </div>
                    <div class="kpi-subtitle" style="color: #333;">
                        @if($comparacion['total_percent'] > 0)
                            ⬆️ Estás <strong>{{ abs($comparacion['total_percent']) }}%</strong> ARRIBA del promedio
                        @elseif($comparacion['total_percent'] < 0)
                            ⬇️ Estás <strong>{{ abs($comparacion['total_percent']) }}%</strong> ABAJO del promedio
                        @else
                            ➡️ Estás en el PROMEDIO
                        @endif
                    </div>
                    <hr>
                    <div style="color: #333;">
                        <strong>{{ $comparacion['total_diff'] >= 0 ? '+' : '' }}{{ number_format($comparacion['total_diff']) }}</strong> porcinos
                        <br><small>vs promedio municipal</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráfico Comparativo por Etapas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <h5><i class="bi bi-bar-chart-fill"></i> Mi Predio vs Promedio Municipal por Etapa</h5>
                    <canvas id="chartComparativo" height="80"></canvas>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                    <h4>Selecciona un predio para ver su análisis</h4>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')

<script>

document.addEventListener('DOMContentLoaded', function() {
    // Verificar que Chart.js esté disponible
    if (typeof Chart === 'undefined') {
        console.error('Chart.js no está cargado!');
        return;
    }

    const canvas = document.getElementById('chartComparativo');
    if (canvas) {
        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: ['Total Porcinos', 'Lactancia', 'Precebo', 'Levante/Ceba'],
                datasets: [
                    {
                        label: 'Mi Predio',
                        data: [{{ $datosPredio['total'] ?? 0 }}, {{ $datosPredio['lactancia'] ?? 0 }}, {{ $datosPredio['precebo'] ?? 0 }}, {{ $datosPredio['levante'] ?? 0 }}],
                        backgroundColor: '#f5576c',
                        borderRadius: 8
                    },
                    {
                        label: 'Promedio Municipal',
                        data: [{{ $promedioMunicipal->avg_total ?? 0 }}, {{ $promedioMunicipal->avg_lactancia ?? 0 }}, {{ $promedioMunicipal->avg_precebo ?? 0 }}, {{ $promedioMunicipal->avg_levante ?? 0 }}],
                        backgroundColor: '#E49B39',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection