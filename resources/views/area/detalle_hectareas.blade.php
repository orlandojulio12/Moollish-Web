@extends('layouts')

@section('template_title')
    Registrar Servicio Ambiental
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title d-flex align-items-center">
                <a href="{{ route('dashboard') }}"><i class="bi bi-arrow-left"
                        style="margin-right: 10px; font-size: 24px; color: black;"></i></a>
                <h5>HECTÁREAS POR PREDIO</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')
<style>
    #pieChartHectareas {
        max-width: 800px; /* Ajusta esto según necesidades */
        height: 400px; /* Establece una altura fija menor */
        margin: auto; /* Centra el gráfico si es más pequeño que el espacio disponible */
    }
    @media (max-width: 768px) {
        #pieChartHectareas {
            max-width: 100%; /* En dispositivos pequeños, permite que el gráfico se expanda completamente */
            height: 300px; /* Altura más pequeña para dispositivos móviles */
        }
    }
</style>

<div class="container">
    <h2>Detalle de Áreas para {{ $predio }} (Hectáreas)</h2>

    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tipo de Área</th>
                        <th>Medida (ha)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($areas as $area)
                        <tr>
                            <td>{{ $area->TiposAreas->nombre_area ?? 'N/A' }}</td>
                            <td>{{ number_format($area->medidas, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>            
        </div>

        <div class="col-md-6">
            <h5 class="text-center">Distribución de Medidas en Hectáreas</h5>
            <canvas id="medidasHectareasChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos para la gráfica circular de hectáreas
        var medidasData = {!! json_encode($areas->pluck('medidas')) !!};
        var medidasLabels = {!! json_encode($areas->map(fn($area) => $area->TiposAreas->nombre_area ?? 'N/A')) !!};
    
        var ctx = document.getElementById('medidasHectareasChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: medidasLabels,
                datasets: [{
                    label: 'Distribución de Medidas (ha)',
                    data: medidasData,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' ha';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
