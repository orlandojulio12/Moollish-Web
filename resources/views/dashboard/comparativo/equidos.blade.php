@extends('layouts')

@section('template_title')
    Dashboard Comparativo - Équidos
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
    
    .predio-checkbox {
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .predio-checkbox:hover {
        border-color: #4facfe;
        background: #f0f9ff;
    }
    
    .predio-checkbox.selected {
        border-color: #4facfe;
        background: #f0f9ff;
    }
    
    .chart-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .chart-card h5 {
        color: #4facfe;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .predio-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-right: 8px;
    }
</style>
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('dashboard.censo.equidos') }}"><i class="bi bi-arrow-left" style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>Dashboard Comparativo - Censo Équidos</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')


<div class="container-fluid">
    <!-- Selector de Predios -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="selector-card">
                <h5><i class="bi bi-funnel-fill"></i> Seleccionar Predios para Comparar (2-5 predios)</h5>
                <p class="text-muted">Selecciona entre 2 y 5 predios para comparar sus censos de équidos</p>
                
                <form id="formComparativo" method="GET" action="{{ route('dashboard.comparativo.equidos') }}">
                    <div class="row">
                        @foreach($todosPredios as $predio)
                        <div class="col-md-4 col-lg-3">
                            <label class="predio-checkbox {{ in_array($predio->id, $prediosIds) ? 'selected' : '' }}">
                                <input type="checkbox" name="predios[]" value="{{ $predio->id }}" 
                                       {{ in_array($predio->id, $prediosIds) ? 'checked' : '' }}
                                       onchange="document.getElementById('formComparativo').submit()">
                                <strong>{{ $predio->nombre_predio }}</strong>
                                <br>
                                <small class="text-muted">{{ $predio->municipio }}</small>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @if(count($comparacion) > 0)
        <!-- Predios Seleccionados -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Comparando <strong>{{ count($comparacion) }}</strong> predios:
                    @foreach($comparacion as $index => $item)
                        <span class="predio-badge" style="background: {{ ['#4facfe', '#E49B39', '#667eea', '#f5576c', '#0ba360'][$index % 5] }}; color: white;">
                            {{ $item['nombre'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Gráfico Comparativo Total -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <h5><i class="bi bi-bar-chart-fill"></i> Comparación de Total Équidos</h5>
                    <canvas id="chartComparativoTotal" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico por Tipo -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <h5><i class="bi bi-bar-chart-fill"></i> Équidos por Tipo (Caballar, Mular, Asnal)</h5>
                    <canvas id="chartTipos" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Tabla Comparativa -->
        <div class="row">
            <div class="col-12">
                <div class="chart-card">
                    <h5><i class="bi bi-table"></i> Tabla Comparativa Detallada</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead style="background: #4facfe; color: white;">
                                <tr>
                                    <th>Predio</th>
                                    <th>Municipio</th>
                                    <th class="text-end">Total Équidos</th>
                                    <th class="text-end">Caballar</th>
                                    <th class="text-end">Mular</th>
                                    <th class="text-end">Asnal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comparacion as $item)
                                <tr>
                                    <td><strong>{{ $item['nombre'] }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ $item['municipio'] }}</span></td>
                                    <td class="text-end fw-bold">{{ number_format($item['total']) }}</td>
                                    <td class="text-end">{{ number_format($item['caballar']) }}</td>
                                    <td class="text-end">{{ number_format($item['mular']) }}</td>
                                    <td class="text-end">{{ number_format($item['asnal']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                    <h4>Selecciona al menos 2 predios para comparar</h4>
                    <p>Usa los checkboxes de arriba para seleccionar los predios que deseas comparar</p>
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
@if(count($comparacion) > 0)

const predios = @json(array_column($comparacion, 'nombre'));
const totales = @json(array_column($comparacion, 'total'));
const caballar = @json(array_column($comparacion, 'caballar'));
const mular = @json(array_column($comparacion, 'mular'));
const asnal = @json(array_column($comparacion, 'asnal'));

const colores = ['#4facfe', '#E49B39', '#667eea', '#f5576c', '#0ba360'];

const ctx1 = document.getElementById('chartComparativoTotal');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: predios,
        datasets: [{
            label: 'Total Équidos',
            data: totales,
            backgroundColor: colores,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: function(value) { return value.toLocaleString(); } }
            }
        }
    }
});

const ctx2 = document.getElementById('chartTipos');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: predios,
        datasets: [
            { label: 'Caballar', data: caballar, backgroundColor: '#4facfe', borderRadius: 6 },
            { label: 'Mular', data: mular, backgroundColor: '#E49B39', borderRadius: 6 },
            { label: 'Asnal', data: asnal, backgroundColor: '#667eea', borderRadius: 6 }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { position: 'bottom' } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: function(value) { return value.toLocaleString(); } }
            }
        }
    }
});

@endif
});
</script>
@endsection