@extends('layouts')

@section('template_title')
    Dashboard Individual - Bovinos
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
        background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(228, 155, 57, 0.3);
    }
    
    .kpi-promedio {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .kpi-comparacion {
        background: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        border: 3px solid #E49B39;
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
        color: #E49B39;
        font-weight: 600;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('dashboard.censo.bovinos') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Individual - Censo Bovinos</h5>
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
                <p class="text-muted">Selecciona un predio para ver su análisis individual y compararlo con el promedio de su municipio</p>
                
                <form method="GET" action="{{ route('dashboard.individual.bovinos') }}">
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
                            <a href="{{ route('dashboard.comparativo.bovinos') }}" class="btn btn-outline-primary">
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
            <!-- Mi Predio -->
            <div class="col-md-4">
                <div class="kpi-predio">
                    <div class="kpi-title"><i class="bi bi-house-fill"></i> MI PREDIO</div>
                    <div class="kpi-value">{{ number_format($datosPredio['total']) }}</div>
                    <div class="kpi-subtitle">Total Bovinos</div>
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    <div class="row mt-3">
                        <div class="col-6">
                            <strong>{{ number_format($datosPredio['hembras']) }}</strong>
                            <br><small>Hembras</small>
                        </div>
                        <div class="col-6">
                            <strong>{{ number_format($datosPredio['machos']) }}</strong>
                            <br><small>Machos</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Promedio Municipal -->
            <div class="col-md-4">
                <div class="kpi-promedio">
                    <div class="kpi-title"><i class="bi bi-graph-up"></i> PROMEDIO MUNICIPAL</div>
                    <div class="kpi-value">{{ number_format($promedioMunicipal->avg_total, 0) }}</div>
                    <div class="kpi-subtitle">Promedio en {{ $datosPredio['municipio'] }}</div>
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    <div class="row mt-3">
                        <div class="col-6">
                            <strong>{{ number_format($promedioMunicipal->avg_hembras, 0) }}</strong>
                            <br><small>Hembras</small>
                        </div>
                        <div class="col-6">
                            <strong>{{ number_format($promedioMunicipal->avg_machos, 0) }}</strong>
                            <br><small>Machos</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Comparación -->
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
                        <strong>{{ $comparacion['total_diff'] >= 0 ? '+' : '' }}{{ number_format($comparacion['total_diff']) }}</strong> bovinos
                        <br><small>vs promedio municipal</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráfico Comparativo -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <h5><i class="bi bi-bar-chart-fill"></i> Mi Predio vs Promedio Municipal</h5>
                    <canvas id="chartComparativo" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Detalles Comparativos -->
        <div class="row">
            <div class="col-12">
                <div class="chart-card">
                    <h5><i class="bi bi-table"></i> Análisis Detallado</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead style="background: #E49B39; color: white;">
                                <tr>
                                    <th>Categoría</th>
                                    <th class="text-end">Mi Predio</th>
                                    <th class="text-end">Promedio Municipal</th>
                                    <th class="text-end">Diferencia</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Total Bovinos</strong></td>
                                    <td class="text-end">{{ number_format($datosPredio['total']) }}</td>
                                    <td class="text-end">{{ number_format($promedioMunicipal->avg_total, 0) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $comparacion['total_diff'] >= 0 ? 'success' : 'danger' }}">
                                            {{ $comparacion['total_diff'] >= 0 ? '+' : '' }}{{ number_format($comparacion['total_diff']) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($comparacion['total_percent'] > 20)
                                            <span class="badge bg-success">⭐ Excelente</span>
                                        @elseif($comparacion['total_percent'] > 0)
                                            <span class="badge bg-info">✓ Arriba del promedio</span>
                                        @elseif($comparacion['total_percent'] == 0)
                                            <span class="badge bg-secondary">= Promedio</span>
                                        @else
                                            <span class="badge bg-warning">⚠ Abajo del promedio</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Hembras</strong></td>
                                    <td class="text-end">{{ number_format($datosPredio['hembras']) }}</td>
                                    <td class="text-end">{{ number_format($promedioMunicipal->avg_hembras, 0) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $comparacion['hembras_diff'] >= 0 ? 'success' : 'danger' }}">
                                            {{ $comparacion['hembras_diff'] >= 0 ? '+' : '' }}{{ number_format($comparacion['hembras_diff']) }}
                                        </span>
                                    </td>
                                    <td class="text-center">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Machos</strong></td>
                                    <td class="text-end">{{ number_format($datosPredio['machos']) }}</td>
                                    <td class="text-end">{{ number_format($promedioMunicipal->avg_machos, 0) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $comparacion['machos_diff'] >= 0 ? 'success' : 'danger' }}">
                                            {{ $comparacion['machos_diff'] >= 0 ? '+' : '' }}{{ number_format($comparacion['machos_diff']) }}
                                        </span>
                                    </td>
                                    <td class="text-center">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
// Verificar que Chart.js esté disponible
    if (typeof Chart === 'undefined') {
        console.error('Chart.js no está cargado!');
        return;
    }
    const ctx = document.getElementById('chartComparativo');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Bovinos', 'Hembras', 'Machos'],
                datasets: [
                    {
                        label: 'Mi Predio',
                        data: [{{ $datosPredio['total'] }}, {{ $datosPredio['hembras'] }}, {{ $datosPredio['machos'] }}],
                        backgroundColor: '#E49B39',
                        borderRadius: 8
                    },
                    {
                        label: 'Promedio Municipal',
                        data: [{{ $promedioMunicipal->avg_total }}, {{ $promedioMunicipal->avg_hembras }}, {{ $promedioMunicipal->avg_machos }}],
                        backgroundColor: '#667eea',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(value) { return value.toLocaleString(); } }
                    }
                }
            }
        });

});
</script>
@endsection

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

