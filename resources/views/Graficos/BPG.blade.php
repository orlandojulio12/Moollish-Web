@php
    $categoriasPrincipales = [
        'SANIDAD ANIMAL',
        'IDENTIFICACION',
        'BIOSEGURIDAD',
        'REQUISITOS BPMV',
        'REQUISITOS BPAA',
        'REQUISITOS DE SANEAMIENTO',
        'REQUISITOS DE BIENESTAR ANIMAL',
        'PERSONAL'
    ];

    $subcategorias = ['Evaluación', 'Implementación', 'Seguimiento', 'Capacitación'];

    $valoresPorCategoria = collect($categoriasPrincipales)->mapWithKeys(function ($categoria) use ($subcategorias) {
        $valores = collect($subcategorias)->mapWithKeys(fn($sub) => [$sub => rand(10, 100)]);
        return [$categoria => $valores];
    });

    $maximosPorCategoria = $valoresPorCategoria->map(fn($valores) => $valores->max());
@endphp

@php
    // Agrupar en bloques: primero 4, luego 3
    $bloques = $valoresPorCategoria->chunk(4);
    if ($bloques->count() > 1) {
        $bloques[1] = $bloques[1]->chunk(3)->first(); // Asegura que el segundo bloque tenga solo 3
    }
@endphp

@foreach ($bloques as $fila)
    <div class="dashboard-row">
        @foreach ($fila as $categoria => $valores)
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div>
                        <h3 class="dashboard-card-subtitle">{{ $categoria }}</h3>
                        <div class="dashboard-card-title">{{ $valores->sum() }} registros</div>
                        <p class="trend-up">
                            <span class="material-symbols-outlined">arrow_upward</span>
                            +{{ rand(5, 20) }} mejoras este mes
                        </p>
                    </div>
                </div>

                <div class="chart-container">
                    <div style="display: flex; align-items: flex-end; height: 85%; justify-content: space-between;">
                        @foreach ($valores as $subcategoria => $valor)
                            @php
                                $altura = $maximosPorCategoria[$categoria] > 0 ? ($valor / $maximosPorCategoria[$categoria]) * 100 : 0;
                                $altura = max($altura, 10);
                            @endphp
                            <div class="bar-chart-container">
                                <div class="tooltip-suscripciones">{{ $valor }} registros</div>
                                <div class="bar-chart" style="height: {{ $altura }}%;"></div>
                            </div>
                        @endforeach
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 14px; font-size: 10px; color: #8a8a8a;">
                        @foreach ($valores as $subcategoria => $valor)
                            <div style="width: 20%; text-align: center;">{{ $subcategoria }}</div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endforeach
    </div>
@endforeach



<script>
    const datosCategorias = @json($valoresPorCategoria);

    function exportarExcelCategoria(nombreCategoria) {
        const valores = datosCategorias[nombreCategoria];
        const data = [['Subcategoría', 'Valor']];
        for (const sub in valores) {
            data.push([sub, valores[sub]]);
        }

        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, nombreCategoria);
        XLSX.writeFile(wb, `${nombreCategoria.replace(/\\s+/g, '_').toLowerCase()}.xlsx`);
    }
</script>
