@php
    $primerTipo = $datos->keys()->first();
    $valores = $datos[$primerTipo];
@endphp

@php

    $categoriasEquipos = ['Tractores', 'Silos', 'Bebederos', 'Cercas eléctricas', 'Tanques'];
    $valoresEquipos = collect($categoriasEquipos)->mapWithKeys(fn($cat) => [$cat => rand(10, 100)]);
    $maxValorEquipos = $valoresEquipos->max();

@endphp


<div class="dashboard-container">
    <div class="dashboard-row">
        <!-- Gráfico de barras -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">MANEJO DEL GANADO</h3>
                    <div class="dashboard-card-title">{{ $valores->sum() }}</div>
                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} aumento de ganado este mes
                    </p>
                </div>
            </div>

            {{-- Gráfico --}}
            <div class="chart-container">
                <div id="grafico-barras"
                    style="display: flex; align-items: flex-end; height: 85%; gap: 15px; justify-content: space-between;">

                    @php
                        $primerTipo = $datos->keys()->first(); // ✅ Funciona con colecciones
                        $primerValores = $datos[$primerTipo];
                        $maxValor = collect($primerValores)->max(); // Asegura que sea colección para usar max()
                    @endphp


                    @foreach ($primerValores as $categoria => $valor)
                        @php
                            $altura = $maxValor > 0 ? ($valor / $maxValor) * 100 : 0;
                            $altura = max($altura, 10);
                        @endphp
                        <div class="bar-chart-container">
                            <div class="tooltip-suscripciones">{{ $valor }} registros</div>
                            <div class="bar-chart" style="height: {{ $altura }}%;"></div>
                        </div>
                    @endforeach
                </div>
                <div id="etiquetas-categorias"
                    style="display: flex; margin-top: 9px; font-size: 10px; color: #8a8a8a; justify-content: space-between;">
                    @foreach ($valores as $categoria => $valor)
                        <div style="width: 15%; text-align: center;">{{ $categoria }}</div>
                    @endforeach
                </div>
            </div>
            {{-- Tabs --}}
            <br><br>
            <div class="tabs-container">
                <div class="tabs-scroll">
                    @foreach ($datos as $tipo => $valoresTipo)
                        <button class="tab-button-datos {{ $loop->first ? 'active' : '' }}"
                            onclick="mostrarGrafico('{{ $tipo }}')">
                            {{ $tipo }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Gráficos tipo torta áreas y agua -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h3 class="dashboard-card-subtitle">INFORMACIÓN DE ÁREAS Y TIERRAS</h3>
            </div>
            <div class="chart-pair-container">
                <!-- Gráfico: INFORMACION DE AREAS -->

                <div class="chart-box">
                    <h4 class="chart-title">INFORMACIÓN SOBRE LAS ÁREAS</h4>
                    <div class="dashboard-card-title" id="totalAreas"></div>
                    <canvas id="pieChartAreas" width="300" height="300"></canvas>
                    <br><br>
                    <div class="tabs-grid">
                        <div class="tabs-row">
                            <a class="nav-link active" data-type="regional" href="#">Regional</a>
                            <a class="nav-link" data-type="todos" href="#">Todos</a>
                        </div>
                        <div class="tabs-row">
                            <a class="nav-link" data-type="predio" href="#">Predio</a>
                            <a class="nav-link" data-type="top5" href="#">Top 5</a>
                        </div>
                    </div>
                </div>


                <!-- Gráfico: INFORMACION SOBRE TIERRAS Y AGUAS -->

                <div class="chart-box">
                    <h4 class="chart-title">INFORMACIÓN SOBRE TIERRAS Y AGUAS</h4>
                    <div class="dashboard-card-title" id="totalTierras"></div>
                    <canvas id="pieChartTierras" width="300" height="300"></canvas>
                    <br>
                    <div class="tabs-grid">
                        <div class="tabs-row">
                            <a class="nav-link active" data-type="fuentes_agua" href="#">Fuentes de Agua</a>
                            <a class="nav-link" data-type="tipo_suelo" href="#">Tipo de Suelo</a>
                        </div>
                        <div class="tabs-row">
                            <a class="nav-link" data-type="drenaje" href="#">Drenaje</a>
                            <a class="nav-link" data-type="manejo_cuencas" href="#">Manejo de Cuencas</a>
                        </div>
                        <div class="tabs-row">
                            <a class="nav-link" data-type="otro" href="#">Otro</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- Gráficos tipo torta -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">MANEJO DE PASTOS Y POTREROS</h3>
                    <div class="dashboard-card-title" id="totalPastos"></div>
                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} mejoras este mes
                    </p>
                </div>
            </div>

            <div class="chart-container">
                <div id="grafico-barras-pastos"
                    style="display: flex; align-items: flex-end; height: 85%; gap: 20px; justify-content: space-between;">
                    {{-- Aquí se genera con JS --}}
                </div>
                <div id="etiquetas-categorias-pastos"
                    style="display: flex; margin-top: 10px; font-size: 10px; color: #8a8a8a; justify-content: space-between;">
                    {{-- Aquí se genera con JS --}}
                </div>
                <br>
                <div class="tabs-container">
                    <div class="tabs-scroll">
                        @foreach ($pastosCategorias as $nombreCategoria => $campo)
                            <button class="tab-button-pastos {{ $loop->first ? 'active' : '' }}"
                                onclick="mostrarGraficoPastos('{{ $nombreCategoria }}')">
                                {{ $nombreCategoria }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="dashboard-container">
    <div class="dashboard-row">
        {{-- INFORMACION DE ASPECTOS M.A --}}
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">INFORMACION DE ASPECTOS M.A</h3>
                    <div id="totalMA" class="dashboard-card-title"></div>
                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} nuevos equipos este mes
                    </p>
                </div>
            </div>

            <div class="chart-container">
                <div id="grafico-barras-ma" style="display: flex; align-items: flex-end; height: 85%; gap: 20px;">
                    {{-- Se carga dinámico con JS --}}
                </div>
                <div id="etiquetas-categorias-ma"
                    style="display: flex; margin-top: 10px; font-size: 10px; color: #8a8a8a;">
                    {{-- Se carga dinámico con JS --}}
                </div>

                <div class="tabs-container">
                    <div class="tabs-scroll">
                        @foreach ($categoriasMA as $nombreCategoria => $campo)
                            <button class="tab-button-ma {{ $loop->first ? 'active' : '' }}"
                                onclick="mostrarGraficoMA('{{ $nombreCategoria }}')">
                                {{ $nombreCategoria }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        {{-- TIPOS DE EQUIPOS --}}
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">TIPOS DE EQUIPOS</h3>
                    <div class="dashboard-card-title">{{ $valoresEquipos->sum() }} equipos registrados</div>
                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} nuevos equipos este mes
                    </p>
                </div>
            </div>

            <div class="chart-container">
                <div style="display: flex; align-items: flex-end; height: 85%; justify-content: space-between;">
                    @foreach ($valoresEquipos as $categoria => $valor)
                        @php
                            $altura = $maxValorEquipos > 0 ? ($valor / $maxValorEquipos) * 100 : 0;
                            $altura = max($altura, 10);
                        @endphp
                        <div class="bar-chart-container">
                            <div class="tooltip-suscripciones">{{ $valor }} unidades</div>
                            <div class="bar-chart" style="height: {{ $altura }}%;"></div>
                        </div>
                    @endforeach
                </div>
                <div
                    style="display: flex; justify-content: space-between; margin-top: 12px; font-size: 12px; color: #8a8a8a;">
                    @foreach ($valoresEquipos as $categoria => $valor)
                        <div style="width: 10%; text-align: center;">{{ $categoria }}</div>
                    @endforeach
                </div>
            </div>


        </div>
        {{-- GESTIÓN DE INFORMACIÓN --}}
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">TIPOS DE EQUIPOS</h3>
                    <div class="dashboard-card-title">{{ $valoresEquipos->sum() }} equipos registrados</div>
                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} nuevos equipos este mes
                    </p>
                </div>
            </div>

            {{-- Contenedor del gráfico --}}
            <div class="chart-container">
                <div id="grafico-barras-gi"
                    style="display: flex; align-items: flex-end; height: 85%; gap: 15px; justify-content: space-between;">
                </div>
                <div id="etiquetas-categorias-gi"
                    style="display: flex; margin-top: 10px; font-size: 12px; color: #8a8a8a; justify-content: space-between;">
                </div>
            </div>

            {{-- Botones de Tabs --}}
            <div class="tabs-container">
                <div class="tabs-scroll">
                    @foreach ($valoresGI as $nombreCategoria => $items)
                        <button class="tab-button-gi {{ $loop->first ? 'active' : '' }}"
                            onclick="mostrarGraficoGI('{{ $nombreCategoria }}')">
                            {{ $nombreCategoria }}
                        </button>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxAreas = document.getElementById('pieChartAreas').getContext('2d');
        let chartAreas;

        const datosInicialesAreas = @json($datosAreas);

        function renderChartAreas(datos) {
            const activeTab = document.querySelector('.chart-box:nth-of-type(1) .tabs-grid .nav-link.active');
            let datosFiltrados = [...datos];

            if (activeTab && activeTab.dataset.type === 'top5') {
                datosFiltrados = datosFiltrados.sort((a, b) => b.area_total - a.area_total).slice(0, 5);
            }

            if (chartAreas) chartAreas.destroy();

            const labels = datosFiltrados.map(d => d.tipo_area);
            const values = datosFiltrados.map(d => d.area_total);

            chartAreas = new Chart(ctxAreas, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#4CAF50', '#2196F3', '#FFC107', '#FF5722', '#9C27B0',
                            '#00BCD4', '#8BC34A', '#FF9800', '#E91E63', '#3F51B5'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            document.getElementById('totalAreas').innerText = values.reduce((a, b) => a + b, 0).toFixed(2);

            window.categoriasAreas = labels;
            window.valoresAreas = values;
        }

        renderChartAreas(datosInicialesAreas);

        document.querySelectorAll('.chart-box:nth-of-type(1) .tabs-grid .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                document.querySelectorAll('.chart-box:nth-of-type(1) .tabs-grid .nav-link')
                    .forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                renderChartAreas(datosInicialesAreas);
            });
        });
        // ========== TIERRAS Y AGUAS ==========
        const ctxTierras = document.getElementById('pieChartTierras').getContext('2d');
        let chartTierras;
        const dataInfoTierraAgua = @json($dataInfoTierraAgua);

        function renderChartTierras(type) {
            const dataset = dataInfoTierraAgua[type] || [];
            const labels = dataset.map(d => Object.values(d)[0]);
            const values = dataset.map(d => d.cantidad_predios);

            if (chartTierras) chartTierras.destroy();

            chartTierras = new Chart(ctxTierras, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                            '#858796', '#20c997', '#fd7e14', '#6f42c1', '#17a2b8'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            document.getElementById('totalTierras').innerText = values.reduce((a, b) => a + b, 0);

            // Guardamos para exportar
            window.categoriasTierras = labels;
            window.valoresTierras = values;
        }

        renderChartTierras('fuentes_agua');

        document.querySelectorAll('.chart-box:nth-of-type(2) .tabs-grid .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.chart-box:nth-of-type(2) .tabs-grid .nav-link')
                    .forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                renderChartTierras(this.dataset.type);
            });
        });


        // ========== GRÁFICO BARRAS ==========
        const datos = @json($datos);
        window.mostrarGrafico = function(tipo) {
            document.querySelectorAll('.tab-button-datos').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`.tab-button-datos[onclick*="${tipo}"]`)?.classList.add('active');

            const valores = datos[tipo];
            const max = Math.max(...Object.values(valores));
            const grafico = document.getElementById('grafico-barras');
            const etiquetas = document.getElementById('etiquetas-categorias');

            grafico.innerHTML = '';
            etiquetas.innerHTML = '';

            Object.entries(valores).forEach(([categoria, cantidad]) => {
                const altura = max > 0 ? (cantidad / max) * 100 : 0;

                const barContainer = document.createElement('div');
                barContainer.classList.add('bar-chart-container');

                const tooltip = document.createElement('div');
                tooltip.classList.add('tooltip-suscripciones');
                tooltip.textContent = `${cantidad} registros`;

                const bar = document.createElement('div');
                bar.classList.add('bar-chart');
                bar.style.height = `${Math.max(altura, 10)}%`;

                barContainer.appendChild(tooltip);
                barContainer.appendChild(bar);
                grafico.appendChild(barContainer);

                const label = document.createElement('div');
                label.style.width = '10%';
                label.style.textAlign = 'center';
                label.textContent = categoria;
                etiquetas.appendChild(label);
            });
        };

        // Todo el contenido, incluyendo mostrarGraficoPastos
        const valoresPastos = @json($valoresPastos);
        window.mostrarGraficoPastos = function(nombreCategoria) {
            document.querySelectorAll('.tab-button-pastos').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`.tab-button-pastos[onclick*="${nombreCategoria}"]`)?.classList.add(
                'active');

            const datos = valoresPastos[nombreCategoria] || {};
            const max = Math.max(...Object.values(datos));
            const grafico = document.getElementById('grafico-barras-pastos');
            const etiquetas = document.getElementById('etiquetas-categorias-pastos');

            grafico.innerHTML = '';
            etiquetas.innerHTML = '';

            Object.entries(datos).forEach(([categoria, cantidad]) => {
                const altura = max > 0 ? (cantidad / max) * 100 : 0;

                const barContainer = document.createElement('div');
                barContainer.classList.add('bar-chart-container');

                const tooltip = document.createElement('div');
                tooltip.classList.add('tooltip-suscripciones');
                tooltip.textContent = `${cantidad} registros`;

                const bar = document.createElement('div');
                bar.classList.add('bar-chart');
                bar.style.height = `${Math.max(altura, 10)}%`;

                barContainer.appendChild(tooltip);
                barContainer.appendChild(bar);
                grafico.appendChild(barContainer);

                const label = document.createElement('div');
                label.style.width = '15%';
                label.style.textAlign = 'center';
                label.textContent = categoria;
                etiquetas.appendChild(label);
            });

            document.getElementById('totalPastos').innerText = Object.values(datos).reduce((a, b) => a + b,
                0);
        };

        // Inicial
        mostrarGraficoPastos(Object.keys(valoresPastos)[0]);
    });

    const valoresMA = @json($valoresMA);

    function mostrarGraficoMA(nombreCategoria) {
        document.querySelectorAll('.tab-button-ma').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.tab-button-ma[onclick*="${nombreCategoria}"]`)?.classList.add('active');

        const datos = valoresMA[nombreCategoria] || {};
        const max = Math.max(...Object.values(datos));
        const grafico = document.getElementById('grafico-barras-ma');
        const etiquetas = document.getElementById('etiquetas-categorias-ma');

        grafico.innerHTML = '';
        etiquetas.innerHTML = '';

        Object.entries(datos).forEach(([categoria, cantidad]) => {
            const altura = max > 0 ? (cantidad / max) * 100 : 0;

            const barContainer = document.createElement('div');
            barContainer.classList.add('bar-chart-container');

            const tooltip = document.createElement('div');
            tooltip.classList.add('tooltip-suscripciones');
            tooltip.textContent = `${cantidad} registros`;

            const bar = document.createElement('div');
            bar.classList.add('bar-chart');
            bar.style.height = `${Math.max(altura, 10)}%`;

            barContainer.appendChild(tooltip);
            barContainer.appendChild(bar);
            grafico.appendChild(barContainer);

            const label = document.createElement('div');
            label.style.width = '15%';
            label.style.textAlign = 'center';
            label.textContent = categoria;
            etiquetas.appendChild(label);
        });

        document.getElementById('totalMA').innerText = Object.values(datos).reduce((a, b) => a + b, 0) + ' registros';
    }

    mostrarGraficoMA(Object.keys(valoresMA)[0]);

    const valoresGI = @json($valoresGI);

    function mostrarGraficoGI(nombreCategoria) {
        /* console.log('Mostrando gráfico para:', nombreCategoria);
        
        console.log("Contenido de valoresGI:", valoresGI);
        Object.entries(valoresGI).forEach(([categoria, datos]) => {
            console.log(`Categoría: ${categoria}`);
            console.table(datos);
        }); */

        document.querySelectorAll('.tab-button-gi').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.tab-button-gi[onclick*="${nombreCategoria}"]`)?.classList.add('active');

        const datos = valoresGI[nombreCategoria];

        const max = Math.max(...datos.map(d => d.cantidad));
        const grafico = document.getElementById('grafico-barras-gi');
        const etiquetas = document.getElementById('etiquetas-categorias-gi');

        grafico.innerHTML = '';
        etiquetas.innerHTML = '';

        datos.forEach(item => {
            const altura = max > 0 ? (item.cantidad / max) * 100 : 0;

            const barContainer = document.createElement('div');
            barContainer.classList.add('bar-chart-container');

            const tooltip = document.createElement('div');
            tooltip.classList.add('tooltip-suscripciones');
            tooltip.textContent = `${item.cantidad} registros`;

            const bar = document.createElement('div');
            bar.classList.add('bar-chart');
            bar.style.height = `${Math.max(altura, 10)}%`;

            barContainer.appendChild(tooltip);
            barContainer.appendChild(bar);
            grafico.appendChild(barContainer);

            const label = document.createElement('div');
            label.style.width = '10%';
            label.style.textAlign = 'center';
            label.textContent = item.categoria;
            etiquetas.appendChild(label);
        });
    }
    mostrarGraficoGI(Object.keys(valoresGI)[0]);
</script>
