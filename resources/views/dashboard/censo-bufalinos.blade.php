@extends('layouts')

@section('template_title')
    Dashboard Censo Bufalinos
@endsection

@section('styles')
<style>
    .dashboard-kpi {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
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
        color: #667eea;
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
                <h5>Dashboard Regional - Censo Bufalinos</h5>
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
                        <a href="{{ route('dashboard.censo.bovinos') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Bovinos
                        </a>
                        <a href="{{ route('dashboard.censo.porcinos') }}" class="btn btn-outline-secondary">
                            Porcinos <i class="bi bi-arrow-right"></i>
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
                <p><i class="bi bi-geo-alt"></i> Predios con Búfalos</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h2>{{ number_format($totalAnimales) }}</h2>
                <p><i class="bi bi-activity"></i> Total Bufalinos</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);">
                <h2>{{ $totalPredios > 0 ? number_format($totalAnimales / $totalPredios, 0) : 0 }}</h2>
                <p><i class="bi bi-calculator"></i> Promedio por Predio</p>
            </div>
        </div>
    </div>
    
    <!-- Gráfico -->
    <div class="row">
        <div class="col-12">
            <div class="chart-card">
                <h5><i class="bi bi-bar-chart-fill"></i> Censo Bufalino por Municipio</h5>
                <canvas id="chartCensoBuffalinos" height="80"></canvas>
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
                        <thead style="background: #667eea; color: white;">
                            <tr>
                                <th>Municipio</th>
                                <th class="text-end">Total Bufalinos</th>
                                <th class="text-end">Hembras</th>
                                <th class="text-end">Machos</th>
                                <th class="text-end">% del Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($censoMunicipal as $censo)
                            <tr>
                                <td><i class="bi bi-geo-alt-fill text-primary"></i> {{ $censo->municipio }}</td>
                                <td class="text-end fw-bold">{{ number_format($censo->total) }}</td>
                                <td class="text-end">{{ number_format($censo->total_hembras) }}</td>
                                <td class="text-end">{{ number_format($censo->total_machos) }}</td>
                                <td class="text-end">{{ $totalAnimales > 0 ? number_format(($censo->total / $totalAnimales) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: #f8f9fa; font-weight: bold;">
                            <tr>
                                <td>TOTAL</td>
                                <td class="text-end">{{ number_format($totalAnimales) }}</td>
                                <td class="text-end">{{ number_format($censoMunicipal->sum('total_hembras')) }}</td>
                                <td class="text-end">{{ number_format($censoMunicipal->sum('total_machos')) }}</td>
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

const ctx = document.getElementById('chartCensoBuffalinos');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: municipios,
        datasets: [{
            label: 'Total Bufalinos',
            data: totales,
            backgroundColor: '#667eea',
            borderColor: '#764ba2',
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: '#764ba2'
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
                        return 'Total: ' + context.parsed.y.toLocaleString() + ' búfalos';
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

function filtrarMunicipio() {
    const municipio = document.getElementById('municipio_filter').value;
    window.location.href = '{{ route("dashboard.censo.bufalinos") }}?municipio=' + municipio;
}
});
</script>
@endsection