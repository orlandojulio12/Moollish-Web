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
                <h5>ÁREA EN HECTÁREAS</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container">
    <h2>Detalle de Áreas para {{ $predio }} (Metros Cuadrados)</h2>

    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tipo de Área</th>
                        <th>Medida (m²)</th>
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
            <h5 class="text-center">Distribución de Medidas en Metros Cuadrados</h5>
            <canvas id="medidasMetrosChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos para la gráfica circular de metros cuadrados
        var medidasData = {!! json_encode($areas->pluck('medidas')) !!};
        var medidasLabels = {!! json_encode($areas->map(fn($area) => $area->TiposAreas->nombre_area ?? 'N/A')) !!};
    
        var ctx = document.getElementById('medidasMetrosChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: medidasLabels,
                datasets: [{
                    label: 'Distribución de Medidas (m²)',
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
                                return context.raw + ' m²';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
