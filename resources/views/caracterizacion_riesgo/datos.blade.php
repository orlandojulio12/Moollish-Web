@extends('layouts')

@section('title', 'Dashboard - Caracterización de Riesgo')

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Dashboard - Resumen de Caracterización de Riesgo</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">Caracterización de Riesgo</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <!-- Gráfico de predios que colindan con establecimientos de riesgo -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Predios que Colindan con Establecimientos de Riesgo</h5>
                        <canvas id="colindaEstablecimChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Frecuencia de Ubicación en Vía -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ubicación en Vía</h5>
                        <canvas id="ubicacionViaChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Otros gráficos similares aquí para cada métrica cuantitativa -->
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuración de gráficos para cada indicador

        // Gráfico circular para predios que colindan con establecimientos de riesgo
        new Chart(document.getElementById('colindaEstablecimChart'), {
            type: 'pie',
            data: {
                labels: ['Colinda', 'No Colinda'],
                datasets: [{
                    data: [{{ $colindaEstablecim->colinda }}, {{ $colindaEstablecim->no_colinda }}],
                    backgroundColor: ['#FF6384', '#36A2EB']
                }]
            }
        });

        // Gráfico de barras para ubicación en vía
        new Chart(document.getElementById('ubicacionViaChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($ubicacionVia->pluck('ubica_en_via')) !!},
                datasets: [{
                    label: 'Frecuencia',
                    data: {!! json_encode($ubicacionVia->pluck('frecuencia')) !!},
                    backgroundColor: '#FFCE56'
                }]
            },
            options: {
                indexAxis: 'y'
            }
        });

        // Añade otros gráficos según los datos disponibles
    </script>
@endsection
