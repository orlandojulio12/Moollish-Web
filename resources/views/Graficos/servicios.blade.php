@php
    $categoriasServicios = ['Forestal', 'Hídrico', 'Paisaje', 'Biodiversidad'];
    $valoresServicios = collect($categoriasServicios)->mapWithKeys(fn($cat) => [$cat => rand(10, 100)]);
    $maxValorServicios = $valoresServicios->max();

    $categoriasCenso = ['Productores', 'Predios', 'Animales', 'Cultivos'];
    $valoresCenso = collect($categoriasCenso)->map(fn($cat) => rand(10, 100));
@endphp

<div class="dashboard-row">
    {{-- SERVICIOS AMBIENTALES --}}
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <div>
                <h3 class="dashboard-card-subtitle">SERVICIOS AMBIENTALES</h3>
                <div class="dashboard-card-title">{{ $valoresServicios->sum() }} registros</div>
                <p class="trend-up">
                    <span class="material-symbols-outlined">arrow_upward</span>
                    +{{ rand(5, 20) }} mejoras este mes
                </p>
            </div>
        </div>

        <div class="chart-container">
            <div style="display: flex; align-items: flex-end; height: 85%; justify-content: space-between;">
                @foreach ($valoresServicios as $categoria => $valor)
                    @php
                        $altura = $maxValorServicios > 0 ? ($valor / $maxValorServicios) * 100 : 0;
                        $altura = max($altura, 10);
                    @endphp
                    <div class="bar-chart-container">
                        <div class="tooltip-suscripciones">{{ $valor }} registros</div>
                        <div class="bar-chart" style="height: {{ $altura }}%;"></div>
                    </div>
                @endforeach
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; color: #8a8a8a;">
                @foreach ($valoresServicios as $categoria => $valor)
                    <div style="width: 10%; text-align: center;">{{ $categoria }}</div>
                @endforeach
            </div>
        </div>

        <button class="export-button" onclick="exportarExcelServicios()">Exportar a Excel</button>
    </div>

    {{-- CENSO --}}
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <div>
                <h3 class="dashboard-card-subtitle">CENSO</h3>
                <div class="dashboard-card-title">{{ $valoresCenso->sum() }} registros</div>
                <p class="trend-up">
                    <span class="material-symbols-outlined">arrow_upward</span>
                    +{{ rand(5, 20) }} registros nuevos
                </p>
            </div>
        </div>

        <div class="chart-container2" style="padding: 10px;">
            <canvas id="pieChartCenso" width="500" height="500"></canvas>
        </div>

       
    </div>

    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <div>
                <h3 class="dashboard-card-subtitle">SERVICIOS AMBIENTALES</h3>
                <div class="dashboard-card-title">{{ $valoresServicios->sum() }} registros</div>
                <p class="trend-up">
                    <span class="material-symbols-outlined">arrow_upward</span>
                    +{{ rand(5, 20) }} mejoras este mes
                </p>
            </div>
        </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoriasCenso = @json($categoriasCenso);
        const valoresCenso = @json($valoresCenso->values());

        new Chart(document.getElementById('pieChartCenso').getContext('2d'), {
            type: 'pie',
            data: {
                labels: categoriasCenso,
                datasets: [{
                    data: valoresCenso,
                    backgroundColor: ['#4CAF50', '#2196F3', '#FFC107', '#FF5722']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });

    // Exportar a Excel
    function exportarExcelServicios() {
        const categorias = @json($categoriasServicios);
        const valores = @json($valoresServicios->values());

        const data = [['Categoría', 'Valor']];
        categorias.forEach((cat, i) => data.push([cat, valores[i]]));

        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Servicios Ambientales");
        XLSX.writeFile(wb, "servicios_ambientales.xlsx");
    }

    function exportarExcelCenso() {
        const data = [['Categoría', 'Valor']];
        categoriasCenso.forEach((cat, i) => data.push([cat, valoresCenso[i]]));

        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Censo");
        XLSX.writeFile(wb, "censo.xlsx");
    }
</script>
