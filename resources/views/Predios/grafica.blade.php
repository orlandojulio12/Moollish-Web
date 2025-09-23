@extends('layouts')

@section('template_title')
    Registrar Servicio Ambiental
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Predios por Municipio</li>
                </ol>
            </nav>
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

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-center card shadow-sm p-3 mb-5 bg-white rounded">
                <div class="me-4">
                    <canvas id="graficaMunicipios" style="max-height: 500px; width: 500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const municipios = @json($prediosPorMunicipio->pluck('municipio'));
            const totalPredios = @json($prediosPorMunicipio->pluck('total'));

            const ctx = document.getElementById('graficaMunicipios').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: municipios,
                    datasets: [{
                        data: totalPredios,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'left',
                            align: 'center',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Cantidad de Predios por Municipio',
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
