@extends('layouts')

@section('template_title')
    Dashboard Censo Consolidado
@endsection
@section('styles')
<style>
    .dashboard-kpi {
        background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(228, 155, 57, 0.3);
        margin-bottom: 15px;
    }
    
    .dashboard-kpi h3 {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
    }
    
    .dashboard-kpi p {
        margin: 5px 0 0 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .kpi-main {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
    }
    
    .kpi-main h2 {
        font-size: 3rem;
        font-weight: bold;
        margin: 0;
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
    
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .species-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 20px;
        margin: 5px;
        font-weight: 600;
        color: white;
    }
</style>
@endsection


@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('inicio') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Regional - Censo Consolidado Atlántico</h5>
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
                            <option value="all" {{ $municipio == 'all' ? 'selected' : '' }}>Todos los municipios - Atlántico</option>
                            @foreach($municipiosUnicos as $mun)
                                <option value="{{ $mun }}" {{ $municipio == $mun ? 'selected' : '' }}>{{ $mun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 text-end">
                        <a href="{{ route('dashboard.censo.bovinos') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul"></i> Ver Censos Individuales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- KPI Principal -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="dashboard-kpi kpi-main">
                <h2>{{ number_format($totalGeneral) }}</h2>
                <p><i class="bi bi-globe"></i> INVENTARIO PECUARIO TOTAL</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-kpi kpi-main" style="background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);">
                <h2>{{ number_format($totalPredios) }}</h2>
                <p><i class="bi bi-geo-alt"></i> PREDIOS CARACTERIZADOS</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-kpi kpi-main" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h2>10</h2>
                <p><i class="bi bi-grid-3x3"></i> ESPECIES CENSADAS</p>
            </div>
        </div>
    </div>
    
    <!-- KPIs por Especie -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #E49B39 0%, #D68A28 100%);">
                <h3>{{ number_format($totalBovinos) }}</h3>
                <p><i class="bi bi-circle-fill"></i> Bovinos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3>{{ number_format($totalBuffalinos) }}</h3>
                <p><i class="bi bi-circle-fill"></i> Bufalinos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);">
                <h3>{{ number_format($totalPorcinos) }}</h3>
                <p><i class="bi bi-circle-fill"></i> Porcinos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h3>{{ number_format($totalEquidos) }}</h3>
                <p><i class="bi bi-circle-fill"></i> Équidos</p>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <h3>{{ number_format($totalAvesComerciales) }}</h3>
                <p><i class="bi bi-circle-fill"></i> Aves Comerciales</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                <h3>{{ number_format($totalAvesTraspatio) }}</h3>
                <p><i class="bi bi-circle-fill"></i> Aves Traspatio</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;">
                <h3>{{ number_format($totalOvinos + $totalCaprinos) }}</h3>
                <p><i class="bi bi-circle-fill"></i> Ovinos y Caprinos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);">
                <h3>{{ number_format($totalPeces) }}</h3>
                <p><i class="bi bi-circle-fill"></i> Peces</p>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="dashboard-kpi" style="background: linear-gradient(135deg, #ff6a00 0%, #ee0979 100%);">
                <h3>{{ number_format($totalCrustaceos) }}</h3>
                <p><i class="bi bi-circle-fill"></i> Crustáceos</p>
            </div>
        </div>
    </div>
    
    <!-- Gráfico de Barras -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card">
                <h5><i class="bi bi-bar-chart-fill"></i> Inventario Pecuario por Especie</h5>
                <canvas id="chartConsolidado" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Gráfico de Torta -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card">
                <h5><i class="bi bi-pie-chart-fill"></i> Distribución Porcentual del Inventario</h5>
                <canvas id="chartDistribucion" height="60"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Tabla Resumen -->
    <div class="row">
        <div class="col-12">
            <div class="chart-card">
                <h5><i class="bi bi-table"></i> Resumen General</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead style="background: #E49B39; color: white;">
                            <tr>
                                <th>Especie</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">% del Inventario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="species-badge" style="background: #E49B39;">Bovinos</span></td>
                                <td class="text-end fw-bold">{{ number_format($totalBovinos) }}</td>
                                <td class="text-end">{{ $totalGeneral > 0 ? number_format(($totalBovinos / $totalGeneral) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="species-badge" style="background: #667eea;">Bufalinos</span></td>
                                <td class="text-end fw-bold">{{ number_format($totalBuffalinos) }}</td>
                                <td class="text-end">{{ $totalGeneral > 0 ? number_format(($totalBuffalinos / $totalGeneral) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="species-badge" style="background: #f5576c;">Porcinos</span></td>
                                <td class="text-end fw-bold">{{ number_format($totalPorcinos) }}</td>
                                <td class="text-end">{{ $totalGeneral > 0 ? number_format(($totalPorcinos / $totalGeneral) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="species-badge" style="background: #4facfe;">Équidos</span></td>
                                <td class="text-end fw-bold">{{ number_format($totalEquidos) }}</td>
                                <td class="text-end">{{ $totalGeneral > 0 ? number_format(($totalEquidos / $totalGeneral) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="species-badge" style="background: #fa709a;">Aves Comerciales</span></td>
                                <td class="text-end fw-bold">{{ number_format($totalAvesComerciales) }}</td>
                                <td class="text-end">{{ $totalGeneral > 0 ? number_format(($totalAvesComerciales / $totalGeneral) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="species-badge" style="background: #30cfd0;">Aves Traspatio</span></td>
                                <td class="text-end fw-bold">{{ number_format($totalAvesTraspatio) }}</td>
                                <td class="text-end">{{ $totalGeneral > 0 ? number_format(($totalAvesTraspatio / $totalGeneral) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="species-badge" style="background: #a8edea; color: #333;">Ovinos y Caprinos</span></td>
                                <td class="text-end fw-bold">{{ number_format($totalOvinos + $totalCaprinos) }}</td>
                                <td class="text-end">{{ $totalGeneral > 0 ? number_format((($totalOvinos + $totalCaprinos) / $totalGeneral) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="species-badge" style="background: #0ba360;">Peces</span></td>
                                <td class="text-end fw-bold">{{ number_format($totalPeces) }}</td>
                                <td class="text-end">{{ $totalGeneral > 0 ? number_format(($totalPeces / $totalGeneral) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="species-badge" style="background: #ff6a00;">Crustáceos</span></td>
                                <td class="text-end fw-bold">{{ number_format($totalCrustaceos) }}</td>
                                <td class="text-end">{{ $totalGeneral > 0 ? number_format(($totalCrustaceos / $totalGeneral) * 100, 1) : 0 }}%</td>
                            </tr>
                        </tbody>
                        <tfoot style="background: #f8f9fa; font-weight: bold;">
                            <tr>
                                <td>TOTAL INVENTARIO</td>
                                <td class="text-end">{{ number_format($totalGeneral) }}</td>
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
    // Gráfico de barras
const ctx1 = document.getElementById('chartConsolidado');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ['Bovinos', 'Bufalinos', 'Porcinos', 'Équidos', 'Aves Com.', 'Aves Trasp.', 'Ov/Cap', 'Peces', 'Crust.'],
        datasets: [{
            label: 'Total Animales',
            data: [
                {{ $totalBovinos }},
                {{ $totalBuffalinos }},
                {{ $totalPorcinos }},
                {{ $totalEquidos }},
                {{ $totalAvesComerciales }},
                {{ $totalAvesTraspatio }},
                {{ $totalOvinos + $totalCaprinos }},
                {{ $totalPeces }},
                {{ $totalCrustaceos }}
            ],
            backgroundColor: [
                '#E49B39',
                '#667eea',
                '#f5576c',
                '#4facfe',
                '#fa709a',
                '#30cfd0',
                '#a8edea',
                '#0ba360',
                '#ff6a00'
            ],
            borderRadius: 8,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
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

// Gráfico de torta
const ctx2 = document.getElementById('chartDistribucion');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Bovinos', 'Bufalinos', 'Porcinos', 'Équidos', 'Aves Com.', 'Aves Trasp.', 'Ov/Cap', 'Peces', 'Crust.'],
        datasets: [{
            data: [
                {{ $totalBovinos }},
                {{ $totalBuffalinos }},
                {{ $totalPorcinos }},
                {{ $totalEquidos }},
                {{ $totalAvesComerciales }},
                {{ $totalAvesTraspatio }},
                {{ $totalOvinos + $totalCaprinos }},
                {{ $totalPeces }},
                {{ $totalCrustaceos }}
            ],
            backgroundColor: [
                '#E49B39',
                '#667eea',
                '#f5576c',
                '#4facfe',
                '#fa709a',
                '#30cfd0',
                '#a8edea',
                '#0ba360',
                '#ff6a00'
            ],
            borderWidth: 3,
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
    window.location.href = '{{ route("dashboard.censo.consolidado") }}?municipio=' + municipio;
}

});

</script>
@endsection