@extends('layouts')

@section('template_title')
    Manejo de Pastos, Potreros y Cercas
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5>Exporte</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area-body">
    <div class="card mb-0">
        <div class="card-body">
            <form id="export-form" method="GET" action="{{ route('movimientos.export', $predioId) }}">
                <div class="row mb-3">
                    <div class="col-6 d-flex align-items-center">
                        <label for="start_date" class="me-2">Fecha Inicio:</label>
                        <input type="date" id="start_date" class="form-control" name="start_date" required>
                    </div>
                    
                    <div class="col-6 d-flex align-items-center">
                        <label for="end_date" class="me-2">Fecha Fin:</label>
                        <input type="date" id="end_date" class="form-control" name="end_date" required>
                    </div>
                </div>

                <!-- Contenedor flex para los botones -->
                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary me-2">Exportar a Excel</button>
                    <button type="button" id="generate-chart" class="btn btn-success">Generar Gráfico</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Contenedor de la gráfica, inicialmente oculto -->
<div class="content-area-body" style="margin-top: 12px; display: none;" id="chart-container">
    <div class="card mb-0">
        <div class="card-body">
            <div style="display: flex; justify-content: center; align-items: center;">
                <canvas id="movimientosChart" width="400" height="600"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Inicializar la variable del gráfico
    let movimientosChart = null;

    // Capturar el evento de envío del formulario para la exportación
    document.getElementById('export-form').addEventListener('submit', function(event) {
        // Obtener las fechas seleccionadas
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        // Validar que las fechas estén seleccionadas
        if (!startDate || !endDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Fechas no seleccionadas',
                text: 'Por favor, selecciona ambas fechas antes de exportar.'
            });
            event.preventDefault(); // Prevenir el envío del formulario
            return;
        }

        // Realizar la solicitud AJAX para verificar si hay datos
        event.preventDefault(); // Prevenir el envío del formulario temporalmente
        fetch(`/movimientos/data/{{ $predioId }}?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                // Verificar si los datos están vacíos o son todos ceros
                if (Object.keys(data).length === 0 || Object.values(data).every(value => value === 0)) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin datos',
                        text: 'No se encontraron datos para las fechas seleccionadas. No se puede exportar a Excel.'
                    });
                    return; // Detener la ejecución si no hay datos
                }

                // Si hay datos, proceder con la exportación del formulario
                document.getElementById('export-form').submit();
            })
            .catch(error => {
                console.error('Error al verificar los datos:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al verificar los datos.'
                });
            });
    });

    // Generar gráfico al hacer clic en "Generar Gráfico"
    document.getElementById('generate-chart').addEventListener('click', function() {
        // Obtener las fechas seleccionadas
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        // Validar que las fechas estén seleccionadas
        if (!startDate || !endDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Fechas no seleccionadas',
                text: 'Por favor, selecciona ambas fechas antes de generar el gráfico.'
            });
            return; // Detener la ejecución si no se seleccionan las fechas
        }

        // Realizar la solicitud AJAX para obtener los datos
        fetch(`/movimientos/data/{{ $predioId }}?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                // Verificar si los datos están vacíos
                if (Object.keys(data).length === 0 || Object.values(data).every(value => value === 0)) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin datos',
                        text: 'No se encontraron datos para las fechas seleccionadas.'
                    });
                    return; // Detener la ejecución si no hay datos
                }

                // Mostrar el contenedor de la gráfica
                document.getElementById('chart-container').style.display = 'block';

                // Crear los datos para el gráfico
                const labels = Object.keys(data);
                const values = Object.values(data);

                // Configurar el gráfico
                const chartData = {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad',
                        data: values,
                        backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                        borderWidth: 1
                    }]
                };

                // Destruir el gráfico existente si lo hay
                if (movimientosChart) {
                    movimientosChart.destroy();
                }

                // Renderizar el gráfico
                const ctx = document.getElementById('movimientosChart').getContext('2d');
                movimientosChart = new Chart(ctx, {
                    type: 'bar',
                    data: chartData,
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error al obtener los datos:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al obtener los datos.'
                });
            });
    });
</script>


@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
