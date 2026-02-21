@extends('layouts')

@section('template_title')
    Dashboard Censo Peces
@endsection
@section('styles')
<style>
    .dashboard-kpi {
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(11, 163, 96, 0.3);
    }
    
    .dashboard-kpi h2 {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0;
    }
    
    .dashboard-kpi p {
        margin: 5px 0 0 0;
        font-size: 1rem;
        opacity: 0.9;
    }
    
    .chart-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .chart-card h5 {
        color: #0ba360;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
</style>
@endsection


@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('inicio') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Regional - Censo Peces</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')


<div class="container-fluid">
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-section">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label class="fw-bold mb-2">
                            <i class="bi bi-funnel"></i> Filtrar por Municipio
                        </label>
                        <select id="municipio_filter" class="form-select" onchange="filtrarMunicipio()">
                            <option value="all" {{ $municipio == 'all' ? 'selected' : '' }}>Todos los municipios</option>
                            @foreach($municipiosUnicos as $mun)
                                <option value="{{ $mun }}" {{ $municipio == $mun ? 'selected' : '' }}>{{ $mun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 text-end">
                        <a href="{{ route('dashboard.censo.ovino.caprino') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Ovinos/Caprinos
                        </a>
                        <a href="{{ route('dashboard.censo.bovinos') }}" class="btn btn-outline-secondary">
                            Bovinos <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- KPIs -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="dashboard-kpi">
                <h2>{{ number_format($totalPredios) }}</h2>
                <p><i class="bi bi-geo-alt"></i> Predios con Piscicultura</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);">
                <h2>{{ number_format($totalAnimales) }}</h2>
                <p><i class="bi bi-activity"></i> Total Peces</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h2>{{ $totalPredios > 0 ? number_format($totalAnimales / $totalPredios, 0) : 0 }}</h2>
                <p><i class="bi bi-calculator"></i> Promedio por Predio</p>
            </div>
        </div>
    </div>
    
    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card">
                <h5><i class="bi bi-bar-chart-fill"></i> Censo de Peces por Municipio</h5>
                <canvas id="chartCensoPeces" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card">
                <h5><i class="bi bi-pie-chart-fill"></i> Distribución por Especie</h5>
                <canvas id="chartEspecie" height="60"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Tabla de datos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="chart-card">
                <h5><i class="bi bi-table"></i> Detalle por Municipio</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead style="background: #0ba360; color: white;">
                            <tr>
                                <th>Municipio</th>
                                <th class="text-end">Total Peces</th>
                                <th class="text-end">% del Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($censoMunicipal as $censo)
                            <tr>
                                <td><i class="bi bi-geo-alt-fill" style="color: #0ba360;"></i> {{ $censo->municipio }}</td>
                                <td class="text-end fw-bold">{{ number_format($censo->total) }}</td>
                                <td class="text-end">{{ $totalAnimales > 0 ? number_format(($censo->total / $totalAnimales) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: #f8f9fa; font-weight: bold;">
                            <tr>
                                <td>TOTAL</td>
                                <td class="text-end">{{ number_format($totalAnimales) }}</td>
                                <td class="text-end">100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
const municipios = @json($censoMunicipal->pluck('municipio'));
const totales = @json($censoMunicipal->pluck('total'));

const ctx1 = document.getElementById('chartCensoPeces');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: municipios,
        datasets: [{
            label: 'Total Peces',
            data: totales,
            backgroundColor: '#0ba360',
            borderColor: '#3cba92',
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: '#3cba92'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                callbacks: {
                    label: function(context) {
                        return 'Total: ' + context.parsed.y.toLocaleString() + ' peces';
                    }
                }
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

// Gráfico por especie
const especies = @json($censoEspecie->pluck('especie'));
const totalesEspecie = @json($censoEspecie->pluck('total'));

const ctx2 = document.getElementById('chartEspecie');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: especies,
        datasets: [{
            data: totalesEspecie,
            backgroundColor: ['#0ba360', '#3cba92', '#E49B39', '#667eea', '#f5576c', '#4facfe'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

function filtrarMunicipio() {
    const municipio = document.getElementById('municipio_filter').value;
    window.location.href = '{{ route("dashboard.censo.peces") }}?municipio=' + municipio;
}
});
</script>
@endsection