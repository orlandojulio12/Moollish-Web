<div class="dashboard-container">
    <div class="dashboard-row">
        <!-- Gráfico de barras -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">Tipos de Explotación </h3>
                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} aumento de ganado este mes
                    </p>
                </div>
            </div>

            <div class="chart-container">
                <div id="grafico-explotacion"
                    style="display: flex; align-items: flex-end; height: 85%; gap: 15px; justify-content: space-between;">
                </div>
                <div id="etiquetas-explotacion"
                    style="display: flex; margin-top: 10px; font-size: 10px; color: #8a8a8a; justify-content: space-between;">
                </div>
            </div>

            <br>
            <div class="dashboard-card-header">
                <h3 class="dashboard-card-subtitle">Diversidad de Especies</h3>
                <div id="diversidad-especies"
                    style="display: flex; gap: 30px; margin-left: 10px; margin-bottom: 5px; justify-content: space-between;">
                </div>
            </div>
        </div>

        @php
            $maximos = collect($resultados)->map(fn($valores) => $valores->max());
        @endphp

        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">INFORMACION EPIDEMOLOGICA</h3>

                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} mejoras este mes
                    </p>
                </div>
            </div>

            <div class="chart-container">
                <div id="grafico-epidemiologia"
                    style="display: flex; align-items: flex-end; height: 85%; gap: 15px; justify-content: space-between;">
                </div>
                <div id="etiquetas-epidemiologia"
                    style="display: flex; margin-top: 10px; font-size: 10px; color: #8a8a8a; justify-content: space-between;">
                </div>
                <br>
                <div class="tabs-container">
                    <div class="tabs-scroll">
                        @foreach ($resultados as $categoria => $valores)
                            <button onclick="mostrarGraficoEpidemiologia('{{ $categoria }}')" class="tab-button-epi">
                                {{ $categoria }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>


        <!-- Gráficos tipo torta -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">INFORMACION EPIDEMOLOGICA</h3>

                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} mejoras este mes
                    </p>
                </div>
            </div>

            <div class="chart-container">
                <div id="grafico-riesgo"
                    style="display: flex; align-items: flex-end; height: 85%; gap: 15px; justify-content: space-between;">
                </div>
                <div id="etiquetas-riesgo"
                    style="display: flex; margin-top: 10px; font-size: 10px; color: #8a8a8a; justify-content: space-between;">
                </div>
                <br>
                <div class="tabs-container">
                    <div class="tabs-scroll">
                        @foreach ($resultadosRiesgo as $categoria => $valores)
                            <button onclick="mostrarGraficoRiesgo('{{ $categoria }}')" class="tab-button-riesgo">
                                {{ $categoria }}
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
        <!-- Gráfico de barras -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">Tipos de Explotación </h3>
                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} aumento de ganado este mes
                    </p>
                </div>
            </div>

            <div class="chart-container">
                <div id="grafico-barras-servicios"
                    style="display: flex; align-items: flex-end; height: 85%; gap: 15px; justify-content: space-between; ">
                    {{-- Se llena desde JS --}}
                </div>
                <div id="etiquetas-categorias-servicios"
                    style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 10px; color: #8a8a8a; justify-content: space-between;">
                    {{-- Se llena desde JS --}}
                </div>
            </div>
            <br><br><br>
            <div class="tabs-container">
                <div class="tabs-scroll">
                    @foreach ($resultadosServiciosAmbientales as $categoria => $valores)
                        <button onclick="mostrarGraficoServicios('{{ $categoria }}')"
                            class="tab-button-servicios {{ $loop->first ? 'active' : '' }}">
                            {{ $categoria }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h3 class="dashboard-card-subtitle">Visitas a Predios de Riesgo</h3>

                    <p class="trend-up">
                        <span class="material-symbols-outlined">arrow_upward</span>
                        +{{ rand(5, 20) }} mejoras este mes
                    </p>
                </div>
            </div>

            <div class="chart-container">
                <div id="grafico-visita"
                    style="display: flex; align-items: flex-end; height: 85%; gap: 15px; justify-content: space-between;">
                </div>
                <div id="etiquetas-visita"
                    style="display: flex; margin-top: 10px; font-size: 10px; color: #8a8a8a; justify-content: space-between;">
                </div>
                <br>
                <div class="tabs-container">
                    <div class="tabs-scroll">
                        <button onclick="mostrarGraficoVisitas('Visitas a Predios')" class="tab-button-vis">
                            Visitas a Predios
                        </button>
                        <button onclick="mostrarGraficoVisitas('Frecuencia de Visitas')" class="tab-button-vis">
                            Frecuencia de Visitas
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function activarTab(selectorClase, botonActivo) {
        document.querySelectorAll(selectorClase).forEach(btn => btn.classList.remove('active'));
        botonActivo.classList.add('active');
    }

    const valoresExplotacion = @json($valoresExplotacion);
    const diversidadEspecies = @json($diversidad);
    const datosEpidemiologia = @json($resultados);
    const maximosEpidemiologia = @json($maximos);
    const datosRiesgo = @json($resultadosRiesgo);
    const maximosRiesgo = @json($maximos);
    const datosVisita = {
        'Visitas a Predios': @json($visitas),
        'Frecuencia de Visitas': @json($frecuencias),
    };
    const datosServicios = @json($resultadosServiciosAmbientales);

    function renderGraficosExplotacion() {
        const maxValor = Math.max(...Object.values(valoresExplotacion));
        const grafico = document.getElementById('grafico-explotacion');
        const etiquetas = document.getElementById('etiquetas-explotacion');
        grafico.innerHTML = '';
        etiquetas.innerHTML = '';

        Object.entries(valoresExplotacion).forEach(([nombre, cantidad]) => {
            const altura = maxValor > 0 ? (cantidad / maxValor) * 100 : 0;
            crearBarra(grafico, etiquetas, nombre, cantidad, altura);
        });

        const diversidadContenedor = document.getElementById('diversidad-especies');
        diversidadContenedor.innerHTML = '';
        diversidadEspecies.forEach(item => {
            const div = document.createElement('div');
            div.innerHTML = `<strong>${item.diversidad_especies}:</strong> ${item.cantidad}`;
            diversidadContenedor.appendChild(div);
        });
    }

    function mostrarGraficoEpidemiologia(categoria) {
        const boton = document.querySelector(`.tab-button-epi[onclick*="${categoria}"]`);
        activarTab('.tab-button-epi', boton);
        const valores = datosEpidemiologia[categoria];
        const maxValor = maximosEpidemiologia[categoria];
        const grafico = document.getElementById('grafico-epidemiologia');
        const etiquetas = document.getElementById('etiquetas-epidemiologia');
        renderBarras(valores, maxValor, grafico, etiquetas);
    }

    function mostrarGraficoRiesgo(categoria) {
        const boton = document.querySelector(`.tab-button-riesgo[onclick*="${categoria}"]`);
        activarTab('.tab-button-riesgo', boton);
        const valores = datosRiesgo[categoria];
        const maxValor = maximosRiesgo[categoria];
        const grafico = document.getElementById('grafico-riesgo');
        const etiquetas = document.getElementById('etiquetas-riesgo');
        renderBarras(valores, maxValor, grafico, etiquetas);
    }

    function mostrarGraficoVisitas(categoria) {
        const boton = document.querySelector(`.tab-button-vis[onclick*="${categoria}"]`);
        activarTab('.tab-button-vis', boton);
        const valores = datosVisita[categoria];
        const maxValor = Math.max(...Object.values(valores));
        const grafico = document.getElementById('grafico-visita');
        const etiquetas = document.getElementById('etiquetas-visita');
        renderBarras(valores, maxValor, grafico, etiquetas);
    }

    function mostrarGraficoServicios(categoria) {
        const boton = document.querySelector(`.tab-button-servicios[onclick*="${categoria}"]`);
        activarTab('.tab-button-servicios', boton);
        const valores = datosServicios[categoria];
        const maxValor = Math.max(...Object.values(valores));
        const grafico = document.getElementById('grafico-barras-servicios');
        const etiquetas = document.getElementById('etiquetas-categorias-servicios');
        renderBarras(valores, maxValor, grafico, etiquetas);
    }

    function renderBarras(valores, maxValor, contenedorBarras, contenedorEtiquetas) {
        contenedorBarras.innerHTML = '';
        contenedorEtiquetas.innerHTML = '';
        Object.entries(valores).forEach(([nombre, cantidad]) => {
            const altura = maxValor > 0 ? (cantidad / maxValor) * 100 : 0;
            crearBarra(contenedorBarras, contenedorEtiquetas, nombre, cantidad, altura);
        });
    }

    function crearBarra(contenedorBarras, contenedorEtiquetas, nombre, cantidad, altura) {
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
        contenedorBarras.appendChild(barContainer);

        const label = document.createElement('div');
        label.style.width = '12%';
        label.style.textAlign = 'center';
        label.textContent = nombre;
        contenedorEtiquetas.appendChild(label);
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderGraficosExplotacion();

        const firstTabEpi = document.querySelector('.tab-button-epi');
        if (firstTabEpi) mostrarGraficoEpidemiologia(firstTabEpi.textContent.trim());

        const firstTabRiesgo = document.querySelector('.tab-button-riesgo');
        if (firstTabRiesgo) mostrarGraficoRiesgo(firstTabRiesgo.textContent.trim());

        const firstTabVis = document.querySelector('.tab-button-vis');
        if (firstTabVis) mostrarGraficoVisitas(firstTabVis.textContent.trim());

        const firstTabServicios = document.querySelector('.tab-button-servicios');
        if (firstTabServicios) mostrarGraficoServicios(firstTabServicios.textContent.trim());
    });
</script>
