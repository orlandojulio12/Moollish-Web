@extends('layouts')

@section('template_title')
    Dashboard Censo Ovinos y Caprinos
@endsection

@section('styles')
<style>
    .dashboard-kpi {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(168, 237, 234, 0.3);
    }
    
    .dashboard-kpi h2 {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0;
    }
    
    .dashboard-kpi p {
        margin: 5px 0 0 0;
        font-size: 1rem;
        opacity: 0.8;
    }
    
    .chart-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .chart-card h5 {
        color: #a8edea;
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
                <h5>Dashboard Regional - Censo Ovinos y Caprinos</h5>
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
                        <a href="{{ route('dashboard.censo.aves.traspatio') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Aves Traspatio
                        </a>
                        <a href="{{ route('dashboard.censo.peces') }}" class="btn btn-outline-secondary">
                            Peces <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- KPIs -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="dashboard-kpi">
                <h2>{{ number_format($totalPredios) }}</h2>
                <p><i class="bi bi-geo-alt"></i> Predios</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%); color: white;">
                <h2>{{ number_format($totalAnimales) }}</h2>
                <p><i class="bi bi-activity"></i> Total Animales</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h2>{{ number_format($totalOvinos) }}</h2>
                <p><i class="bi bi-circle-fill"></i> Total Ovinos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <h2>{{ number_format($totalCaprinos) }}</h2>
                <p><i class="bi bi-circle-fill"></i> Total Caprinos</p>
            </div>
        </div>
    </div>
    
    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="chart-card">
                <h5><i class="bi bi-bar-chart-fill"></i> Ovinos y Caprinos por Municipio</h5>
                <canvas id="chartCensoOC" height="80"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="chart-card">
                <h5><i class="bi bi-pie-chart-fill"></i> Distribución Total</h5>
                <canvas id="chartDistribucion" height="80"></canvas>
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
                        <thead style="background: #a8edea; color: #333;">
                            <tr>
                                <th>Municipio</th>
                                <th class="text-end">Ovinos</th>
                                <th class="text-end">Caprinos</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">% del Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($censoMunicipal as $censo)
                            <tr>
                                <td><i class="bi bi-geo-alt-fill" style="color: #a8edea;"></i> {{ $censo->municipio }}</td>
                                <td class="text-end">{{ number_format($censo->total_ovinos) }}</td>
                                <td class="text-end">{{ number_format($censo->total_caprinos) }}</td>
                                <td class="text-end fw-bold">{{ number_format($censo->total) }}</td>
                                <td class="text-end">{{ $totalAnimales > 0 ? number_format(($censo->total / $totalAnimales) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: #f8f9fa; font-weight: bold;">
                            <tr>
                                <td>TOTAL</td>
                                <td class="text-end">{{ number_format($totalOvinos) }}</td>
                                <td class="text-end">{{ number_format($totalCaprinos) }}</td>
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
// Esperar a que Chart.js esté cargado
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que Chart.js esté disponible
    if (typeof Chart === 'undefined') {
        console.error('Chart.js no está cargado!');
        return;
    }

    const municipios = @json($censoMunicipal->pluck('municipio'));
    const ovinos = @json($censoMunicipal->pluck('total_ovinos'));
    const caprinos = @json($censoMunicipal->pluck('total_caprinos'));

    // Gráfico por municipio
    const ctx1 = document.getElementById('chartCensoOC');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: municipios,
                datasets: [
                    {
                        label: 'Ovinos',
                        data: ovinos,
                        backgroundColor: '#667eea',
                        borderRadius: 6
                    },
                    {
                        label: 'Caprinos',
                        data: caprinos,
                        backgroundColor: '#f5576c',
                        borderRadius: 6
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

    // Gráfico torta distribución
    const ctx2 = document.getElementById('chartDistribucion');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Ovinos', 'Caprinos'],
                datasets: [{
                    data: [{{ $totalOvinos }}, {{ $totalCaprinos }}],
                    backgroundColor: ['#667eea', '#f5576c'],
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
    }
});

function filtrarMunicipio() {
    const municipio = document.getElementById('municipio_filter').value;
    window.location.href = '{{ route("dashboard.censo.ovino.caprino") }}?municipio=' + municipio;
}
</script>
@endsection