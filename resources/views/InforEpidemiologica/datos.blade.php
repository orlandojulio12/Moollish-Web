@extends('layouts')

@section('title', 'Dashboard')

@section('content')

    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Dashboard - Resumen de Información Epidemiológica</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Información Epidemiológica</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="row">
                <!-- Gráfico Circular para Proporción de Predios con y sin Enfermedades -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Proporción de Predios con y sin Enfermedades</h5>
                            <canvas id="enfermedadesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfico Circular para Proporción de Predios con y sin Toma de Muestra -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Proporción de Predios con y sin Toma de Muestra</h5>
                            <canvas id="muestrasChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Barras para Tipos de Muestras Más Comunes -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tipos de Muestras Más Comunes</h5>
                            <canvas id="tiposMuestrasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Incluir Chart.js desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Enfermedades
        var prediosConEnfermedad = {{ $resultEnfermedades->predios_con_enfermedad }};
        var prediosSinEnfermedad = {{ $resultEnfermedades->predios_sin_enfermedad }};
        var ctxEnfermedades = document.getElementById('enfermedadesChart').getContext('2d');
        new Chart(ctxEnfermedades, {
            type: 'pie',
            data: {
                labels: ['Con Enfermedad', 'Sin Enfermedad'],
                datasets: [{
                    data: [prediosConEnfermedad, prediosSinEnfermedad],
                    backgroundColor: ['#FF6384', '#36A2EB'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label + ' predios';
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de Toma de Muestra
        var prediosConMuestra = {{ $resultMuestras->predios_con_muestra }};
        var prediosSinMuestra = {{ $resultMuestras->predios_sin_muestra }};
        var ctxMuestras = document.getElementById('muestrasChart').getContext('2d');
        new Chart(ctxMuestras, {
            type: 'pie',
            data: {
                labels: ['Con Muestra', 'Sin Muestra'],
                datasets: [{
                    data: [prediosConMuestra, prediosSinMuestra],
                    backgroundColor: ['#4BC0C0', '#FFCE56'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label + ' predios';
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de Barras para Tipos de Muestras Más Comunes
        var tiposMuestrasLabels = {!! json_encode($tiposMuestras->pluck('toma_muestra_tipos')) !!};
        var tiposMuestrasData = {!! json_encode($tiposMuestras->pluck('frecuencia')) !!};
        var ctxTiposMuestras = document.getElementById('tiposMuestrasChart').getContext('2d');
        new Chart(ctxTiposMuestras, {
            type: 'bar',
            data: {
                labels: tiposMuestrasLabels,
                datasets: [{
                    label: 'Frecuencia',
                    data: tiposMuestrasData,
                    backgroundColor: '#FF9F40',
                    borderColor: '#FF9F40',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y', // Para que sea un gráfico de barras horizontal
                scales: {
                    x: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' predios';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
