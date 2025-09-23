@extends('layouts')
@section('title', 'Dashboard')
@section('styles')
    @include('Graficos.modal')
    <style>
        .modal-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;
            display: flex;
            justify-content: flex-end;
        }

        /* Estilo base de los labels */
        .export-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Estilo cuando está seleccionado */
        .export-label input[type="checkbox"]:checked+.custom-check {
            background-color: #e49b39;
            /* Salmón */
            color: #fff;
            /* Blanco para la flecha */
        }

        /* Diseño de la flecha (puedes ajustar según tu iconografía) */
        .custom-check {
            width: 20px;
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 4px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            transition: background 0.3s ease, color 0.3s ease;
        }


        .tabs-container {
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            background-color: #fff;
            padding: 10px 0;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .tabs {
            list-style: none;
            display: flex;
            flex-direction: row;
            gap: 12px;
            padding: 0;
            margin: 0;
        }

        .tab {
            padding: 10px 20px;
            background-color: #f9f9f9;
            color: #333;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid transparent;
            margin-bottom: 10px;
        }


        .tabs-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;

        }

        .tabs-scroll {
            display: flex;
            gap: 10px;
            min-width: max-content;
        }

        .tab-button,
        .tab-button-datos,
        .tab-button-pastos,
        .tab-button-ma,
        .tab-button-gi,
        .tab-button-epi,
        .tab-button-riesgo,
        .tab-button-vis,
        .tab-button-servicios {
            padding: 6px 6px;
            border: none;
            background: #f0f0f0;
            cursor: pointer;
            white-space: nowrap;
            border-radius: 8px;
            font-size: 12px;
            transition: background 0.3s, color 0.3s;
        }

        .tab-button.active,
        .tab-button-datos.active,
        .tab-button-pastos,
        .tab-button-ma,
        .tab-button-gi,
        .tab-button-epi,
        .tab-button-riesgo,
        .tab-button-vis,
        .tab-button-servicios.active {
            background: #0d6efd;
            color: white;
        }


        .tab:hover {
            background-color: #fff;
            border-color: #e49a39a2;
            color: #e49b39;
        }

        .tab.active {
            background-color: #e49b39;
            color: #fff;
            border-color: #e49b39;
            box-shadow: #e49a395b
        }

        .tab-content {
            animation: fadeIn 0.3s ease-in-out;
        }

        .chart-box {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .tabs-grid {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .tabs-row {
            display: flex;
            gap: 2px;
        }


        .tabs-row a.nav-link {
            flex: 1;
            text-align: center;
            padding: 2px 4px;
            /* Menos padding */
            font-size: 9px;
            /* Tamaño de letra más pequeño */
            background: #f0f0f0;
            border-radius: 6px;
            min-height: 24px;
            /* Opcional, para uniformidad */
        }

        .tabs-row a.active {
            background: #0d6efd;
            color: white;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .chart-pair-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .chart-box {
            text-align: center;
            flex: 1;
            min-width: 20px;
            max-width: 200px
        }

        .chart-title {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .export-button {
            margin-top: 20px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .export-button:hover {
            background-color: #0056b3;
        }


        .dashboard-row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }

        .dashboard-cardHorizontal {
            padding: 15px 25px 8px;
            flex: 1;
            min-width: 250px;
            background: white;
            border-radius: 6px;
            border: 1px solid #E5E7EB;
            margin: 10px 10px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .dashboard-cardHorizontal:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card {
            flex: 1;
            min-width: 250px;
            background: white;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            margin: 10px;
            padding: 22px 22px;

            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .dashboard-card-header {
            display: flex;
            align-items: center;
        }

        .dashboard-card-icon {
            width: 60px;
            height: 60px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-card-icon .material-symbols-outlined {
            font-size: 58px;
        }

        .dashboard-card-title {
            font-size: 23px;
            font-weight: 700;
            color: #292929;
            margin: 0;
        }

        .dashboard-card-subtitle {
            font-size: 14px;
            color: #767676;
            margin-top: -3px;
            font-weight: 300;
        }

        .dashboard-card-number {
            font-size: 38px;
            font-weight: 800;
            color: #E49B39;
            line-height: 130%;
        }

        .dashboard-large-card {
            flex: 2;
            min-width: 500px;
        }

        .chart-container {
            height: 200px;
            margin-top: 0px;
        }

        .chart-container2 {
            height: 200px;
            margin-top: 0px;
        }

        .dashboard-column {
            display: flex;
            flex-direction: column;
            width: 33%;
        }

        .suscripciones-icon {
            color: #E49B39;
        }

        .usuarios-icon {
            color: #E49B39;
        }

        .predios-icon {
            color: #E49B39;
        }

        .animales-icon {
            color: #E49B39;
        }

        .money-icon {
            color: #E49B39;
        }

        .trend-up {
            color: #28a745;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .trend-up .material-symbols-outlined {
            font-size: 16px;
            margin-right: 4px;
        }

        .table-container {
            margin-top: 15px;
            overflow-x: auto;
            border-radius: 6px;
            border: 1px solid #E5E7EB;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eaebef;
            font-weight: 600;
            color: #5c5c5c;
        }

        .custom-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eaebef;
        }

        a:hover {
            text-decoration: none;
            color: #E49B39;
        }

        .custom-table tr:hover {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .options-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #5c5c5c;
        }

        .search-container {
            display: flex;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #eaebef;
            border-radius: 8px;
            margin-right: 10px;
        }

        .order-select {
            padding: 10px 15px;
            border: 1px solid #eaebef;
            border-radius: 8px;
            background: white;
        }

        /*     .nxl-container .nxl-content .main-content {

                    padding: 0px !important;
                } */

        .bar-chart-container {
            position: relative;
            width: 10%;
            min-width: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            height: 100%;
        }

        .bar-chart {
            background-color: #E49B39;
            border-radius: 4px;
            transition: background-color 0.2s;
            width: 100%;
            min-height: 10px;
        }

        .bar-chart-container:hover .bar-chart {
            background-color: #C97917;
        }

        .tooltip-suscripciones {
            /* position: absolute; */
            /* top: -28px; */
            background-color: #ffffff;
            color: #E49B39;
            border: 1px solid #E49B39;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s, transform 0.3s;
            z-index: 10;
            margin: 0px 0px 10px 0px;
        }

        .tooltip-suscripciones::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #E49B39 transparent transparent transparent;
        }

        .suscritospadding {
            padding: 30px 20px;
        }

        .bar-chart-container:hover .tooltip-suscripciones {
            opacity: 1;
            transform: translateY(0);
        }

        /* Estilos para el modal de recaudación */
        #recaudoDetalleModal .modal-content {
            border: none;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        #recaudoDetalleModal .modal-header {
            padding: 15px 20px;
        }

        #recaudoDetalleModal .modal-title {
            color: #2e2e2e;
            font-weight: 600;
            font-size: 1.3em;
        }

        #recaudoDetalleModal .btn-close:focus {
            box-shadow: none;
            outline: none;
        }

        #recaudoDetalleModal .modal-body {
            padding: 20px;
        }

        #recaudoDetalleModal .nav-tabs {
            border-bottom: 1px solid #e5e7eb;
        }

        #recaudoDetalleModal .nav-tabs .nav-link {
            color: #767676;
            border: 1px solid transparent;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        #recaudoDetalleModal .nav-tabs .nav-link:hover {
            border-color: #f8f9fa #f8f9fa #E49B39;
            color: #E49B39;
        }

        #recaudoDetalleModal .nav-tabs .nav-link.active {
            color: #E49B39;
            border-bottom: 3px solid #E49B39;
        }

        #recaudoDetalleModal .tab-content {
            padding: 20px 0px;
            background-color: #fff;
        }

        #recaudoDetalleModal .table {
            margin-bottom: 0;
        }

        #recaudoDetalleModal .table thead th {
            background-color: #f8f9fa;
            color: #5c5c5c;
            font-weight: 600;

        }

        #recaudoDetalleModal .table tbody tr:hover {
            background-color: #FFF8F0;
        }

        #recaudoDetalleModal .badge.bg-success {
            background-color: #28a74533 !important;
            color: #28a745 !important;
            font-weight: 600;
        }

        #recaudoDetalleModal .text-end {
            text-align: right;
        }

        #recaudoDetalleModal .modal-footer {
            border-top: 1px solid #f8f9fa;
            padding: 15px 20px;
        }

        #recaudoDetalleModal .btn-secondary {
            background-color: #E49B39;
            border-color: #E49B39;
            padding: 12px 28px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 12px;
        }

        #recaudoDetalleModal .btn-secondary:hover {
            background-color: #C97917;
            border-color: #C97917;
        }

        /* Estilos para los nuevos elementos del modal */
        #recaudoDetalleModal .recaudo-summary {
            display: flex;
            justify-content: space-between;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        #recaudoDetalleModal .recaudo-total h4,
        #recaudoDetalleModal .recaudo-count h4 {
            font-size: 0.9em;
            color: #767676;
            margin-bottom: 5px;
            font-weight: 500;
        }

        #recaudoDetalleModal .recaudo-amount {
            font-size: 1.8em;
            font-weight: 700;
            color: #E49B39;
        }

        #recaudoDetalleModal .recaudo-count .badge {
            font-size: 1.2em;
            padding: 8px 12px;
        }

        #recaudoDetalleModal .plan-badge {
            background-color: #FFF8F0;
            color: #E49B39;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-block;
        }

        #recaudoDetalleModal .precio-column {
            font-weight: 600;
            color: #E49B39;
        }

        #recaudoDetalleModal .empty-state {
            padding: 30px 0;
            text-align: center;
        }

        #recaudoDetalleModal .empty-state .material-symbols-outlined {
            font-size: 3em;
            color: #E5E7EB;
            margin-bottom: 10px;
        }

        #recaudoDetalleModal .empty-state p {
            color: #767676;
            margin: 0;
        }

        @media (width < 768px) {
            .dashboard-column {
                display: flex;

                flex-direction: column;
                width: 100%;
            }

            .dashboard-large-card {
                flex: 2;
                min-width: 0px;
                width: 100%;
            }

            .order-select {
                padding: 10px 15px;
                border: 1px solid #eaebef;
                border-radius: 8px;
                background: white;
                width: 32%;
            }

            .suscritospadding {
                padding: 40px 20px;
            }

            .dashboard-cardHorizontal {
                margin: 10px 10px 10px;
            }

            #recaudoDetalleModal .modal-dialog {
                margin: 10px;
                width: auto;
            }

            #recaudoDetalleModal .nav-tabs .nav-link {
                padding: 8px 12px;
                font-size: 0.9em;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <!-- Alertas -->
        <div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 1200; display: none;">
            <div class="alert"
                style="padding: 15px 25px; border-radius: 8px; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <span class="material-symbols-outlined" style="font-size: 24px;"></span>
                <p style="margin: 0; font-weight: 500;"></p>
            </div>
        </div>

        <!-- Fila de tarjetas de estadísticas -->
        <div class="dashboard-row">
            <!-- Tarjeta de Usuarios Activos -->
            <a href="{{ route('users.index') }}" class="dashboard-cardHorizontal">
                <div class="dashboard-card-header">
                    <div class="dashboard-card-icon usuarios-icon">
                        <span class="material-symbols-outlined">
                            group
                        </span>
                    </div>
                    <div>
                        <h3 class="dashboard-card-title">Usuarios activos</h3>
                        <p class="dashboard-card-subtitle">Gestiona los usuarios activos</p>
                    </div>
                </div>
                <div class="dashboard-card-number">{{ number_format($usuariosActivos, 0, ',', '.') }}</div>
            </a>

            <!-- Tarjeta de Predios -->
            <a href="{{ route('predios.index') }}" class="dashboard-cardHorizontal">
                <div class="dashboard-card-header">
                    <div class="dashboard-card-icon predios-icon">
                        <span class="material-symbols-outlined">villa</span>
                    </div>
                    <div>
                        <h3 class="dashboard-card-title">Predios</h3>
                        <p class="dashboard-card-subtitle">Gestiona los predios de usuarios</p>
                    </div>
                </div>
                <div class="dashboard-card-number">{{ number_format($prediosCount, 0, ',', '.') }}</div>
            </a>

            <!-- Tarjeta de Animales -->
            <a href="{{ route('inventario.general') }}" class="dashboard-cardHorizontal">
                <div class="dashboard-card-header">
                    <div class="dashboard-card-icon animales-icon">
                        <img src="{{ asset('img/vaca.png') }}" alt="Animales" style="width: 58px; height: 58px;">
                    </div>
                    <div>
                        <h3 class="dashboard-card-title">Animales</h3>
                        <p class="dashboard-card-subtitle">Gestiona los animales en Moollish</p>
                    </div>
                </div>
                <div class="dashboard-card-number">{{ number_format($animalesCount, 0, ',', '.') }}</div>
            </a>
        </div>

        <!-- Fila de gráficos y tablas -->
        <div class="dashboard-row">
            <!-- Tarjeta de Suscripciones -->
            <div class="dashboard-card dashboard-large-card">
                <div class="dashboard-card-header" style="    margin: 0px 0px 20px;">
                    <div class="dashboard-card-icon suscripciones-icon">
                        <span class="material-symbols-outlined">diamond</span>
                    </div>
                    <div>
                        <a href="{{ route('membresias') }}" class="dashboard-card-title">Suscripciones</a>
                        <p class="dashboard-card-subtitle">Gestiona las nuevas solicitudes de suscripciones entrantes</p>
                    </div>
                </div>

                <!-- Buscador y selector de orden -->
                <div class="search-container">
                    <input type="text" placeholder="Filtrar por correo, plan o fecha" class="search-input">
                    <select class="order-select">
                        <option value="">Ordenar</option>
                        <option value="fecha_asc">Fecha (más antigua)</option>
                        <option value="fecha_desc">Fecha (más reciente)</option>
                        <option value="plan_asc">Plan (menor a mayor)</option>
                        <option value="plan_desc">Plan (mayor a menor)</option>
                    </select>
                </div>

                <!-- Tabla de suscripciones -->
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Suscripción</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suscripciones as $index => $suscripcion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $suscripcion->user->email }}</td>
                                    <td>{{ $suscripcion->membershipPlan->nombre }}</td>
                                    <td>{{ \Carbon\Carbon::parse($suscripcion->created_at)->format('d/m/Y') }}</td>
                                    <td class="text-right">
                                        <button class="options-btn gestionar-suscripcion" data-id="{{ $suscripcion->id }}"
                                            data-email="{{ $suscripcion->user->email }}"
                                            data-name="{{ $suscripcion->user->name }}"
                                            data-celular="{{ $suscripcion->user->celular ?? 'No especificado' }}"
                                            data-verified="{{ $suscripcion->user->email_verified_at ? 'true' : 'false' }}"
                                            data-avatar="{{ $suscripcion->user->profile_photo_path ? asset('storage/' . $suscripcion->user->profile_photo_path) : null }}"
                                            data-predios="{{ $suscripcion->user->predios->count() }}"
                                            data-animales="{{ $suscripcion->user->animalsCount() }}"
                                            data-member-since="{{ $suscripcion->user->created_at->format('d/m/Y') }}"
                                            data-plan="{{ $suscripcion->membershipPlan->nombre }}"
                                            data-fecha="{{ \Carbon\Carbon::parse($suscripcion->created_at)->format('d/m/Y') }}"
                                            data-duracion="{{ $suscripcion->membershipPlan->duracion_dias }}"
                                            data-precio="{{ number_format($suscripcion->membershipPlan->precio, 0, ',', '.') }}">
                                            <span class="material-symbols-outlined">more_vert</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            @if (count($suscripciones) === 0)
                                <tr>
                                    <td colspan="5" class="text-center">No hay solicitudes de suscripción pendientes</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tarjeta de Estadísticas de Suscripciones -->
            <div class="dashboard-column">
                <div class="dashboard-card suscritospadding">
                    <div class="dashboard-card-header">
                        <div>
                            <h3 class="dashboard-card-subtitle">Suscritos</h3>
                            <div class="dashboard-card-title">{{ number_format($totalSuscriptores, 0, ',', '.') }}</div>
                            <p class="trend-up">
                                <span class="material-symbols-outlined">arrow_upward</span>
                                +{{ $nuevosSuscriptores }} Suscripciones este mes
                            </p>
                        </div>
                    </div>

                    <!-- Gráfico de barras de suscripciones -->
                    <div class="chart-container">
                        <div
                            style="display: flex; align-items: flex-end; height: 100%; justify-content: space-between; position: relative;">
                            @foreach ($suscripcionesPorMes as $mes => $cantidad)
                                @php
                                    $maxValue = max($suscripcionesPorMes);
                                    $height = $maxValue > 0 ? ($cantidad / $maxValue) * 100 : 0;
                                    // Asegurar un valor mínimo para que siempre sea visible
                                    $height = max($height, 10);
                                @endphp
                                <div class="bar-chart-container">
                                    <div class="tooltip-suscripciones">{{ $cantidad }} suscriptores</div>
                                    <div class="bar-chart" style="height: {{ $height }}%;"
                                        data-cantidad="{{ $cantidad }}" data-mes="{{ $mes }}"></div>
                                </div>
                            @endforeach
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; color: #8a8a8a;">
                            @foreach ($suscripcionesPorMes as $mes => $cantidad)
                                <div style="width: 10%; text-align: center; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $mes }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="dashboard-card" style="padding: 20px 20px;    max-height: 150px; cursor: pointer;"
                    id="recaudoCard">
                    <div class="dashboard-card-header"
                        style="flex-direction: row-reverse;
        justify-content: space-between;">
                        <div class="dashboard-card-icon money-icon">
                            <span class="material-symbols-outlined">payments</span>

                        </div>
                        <div>
                            <h3 class="dashboard-card-subtitle">Recaudado</h3>
                            <div class="dashboard-card-title">{{ number_format($totalRecaudado, 0, ',', '.') }}</div>
                            <p class="trend-up">

                                <span class="material-symbols-outlined">arrow_upward</span>
                                +{{ number_format($recaudadoUltimoMes, 0, ',', '.') }} en el último mes
                            </p>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Tercera fila: ingresos totales -->
        <div class="dashboard-row">

        </div>

        <!-- Modal para gestionar suscripciones -->
        <div id="suscripcionModal" class="modal"
            style="display: none; position: fixed; z-index: 1100; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
            <div class="modal-content"
                style="background-color: #fefefe; margin: 2% auto; padding: 30px; border: none; width: 90%; max-width: 600px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div class="modal-header"
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0px; border-bottom: 1px solid #E5E7EB; padding-bottom: 15px;">
                    <h2 style="margin: 0; color: #292929; font-size: 24px; font-weight: 600;">Gestionar Suscripción</h2>
                    <span class="close-modal" style="cursor: pointer; font-size: 24px; color: #767676;">&times;</span>
                </div>
                <div class="modal-body">
                    <!-- Información del usuario -->
                    <div class="user-info"
                        style="display: flex; gap: 20px; margin-bottom: 20px; padding: 20px; background-color: #F9FAFB; border-radius: 8px;">
                        <div class="user-avatar"
                            style="    width: 80px;
                            height: 80px;
                            border-radius: 50%;
                            overflow: hidden;
                            background-color: #E5E7EB;
                            display: flex
                        ;
                            align-items: center;
                            justify-content: center;">
                            <img id="modal-avatar" src="" alt="Avatar"
                                style="width: 100%; height: 100%; object-fit: cover;">
                            <span id="modal-avatar-icon" class="material-symbols-outlined"
                                style="font-size: 40px; color: #767676; display: none;">account_circle</span>
                        </div>
                        <div class="user-details" style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <h3 id="modal-name" style="margin: 0; color: #292929; font-size: 18px; font-weight: 600;">
                                </h3>
                                <span id="modal-verified" class="material-symbols-outlined"
                                    style="color: #28a745; font-size: 18px; display: none;">verified</span>
                            </div>
                            <p id="modal-email" style="margin: 0 0 8px 0; color: #767676; font-size: 14px;"></p>
                            <p id="modal-celular" style="margin: 0; color: #767676; font-size: 14px;"></p>
                        </div>
                    </div>
                    <!-- Información de la suscripción -->

                    <!-- Estadísticas del usuario -->
                    <div class="user-stats"
                        style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
                        <div style="background-color: #F9FAFB; padding: 15px; border-radius: 8px; text-align: center;">
                            <p style="margin: 0 0 5px 0; color: #767676; font-size: 14px;">Predios</p>
                            <p id="modal-predios" style="margin: 0; color: #E49B39; font-size: 20px; font-weight: 600;">0
                            </p>
                        </div>
                        <div style="background-color: #F9FAFB; padding: 15px; border-radius: 8px; text-align: center;">
                            <p style="margin: 0 0 5px 0; color: #767676; font-size: 14px;">Animales</p>
                            <p id="modal-animales" style="margin: 0; color: #E49B39; font-size: 20px; font-weight: 600;">0
                            </p>
                        </div>
                        <div style="background-color: #F9FAFB; padding: 15px; border-radius: 8px; text-align: center;">
                            <p style="margin: 0 0 5px 0; color: #767676; font-size: 14px;">Miembro desde</p>
                            <p id="modal-member-since"
                                style="margin: 0; color: #E49B39; font-size: 14px; font-weight: 500;"></p>
                        </div>
                    </div>

                    <div class="subscription-info"
                        style="background-color: #FFF8F0; border: 1px solid #E49B39; border-radius: 8px; padding: 20px; margin-bottom: 0px;">
                        <h4 style="margin: 0 0 15px 0; color: #E49B39; font-size: 16px; font-weight: 600;">Detalles de la
                            Suscripción</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                            <div>
                                <p style="margin: 0 0 5px 0; color: #767676; font-size: 14px;">Plan</p>
                                <p id="modal-plan" style="margin: 0; color: #292929; font-size: 16px; font-weight: 500;">
                                </p>
                            </div>
                            <div>
                                <p style="margin: 0 0 5px 0; color: #767676; font-size: 14px;">Precio</p>
                                <p id="modal-precio"
                                    style="margin: 0; color: #292929; font-size: 16px; font-weight: 500;"></p>
                            </div>
                            <div>
                                <p style="margin: 0 0 5px 0; color: #767676; font-size: 14px;">Fecha de solicitud</p>
                                <p id="modal-fecha" style="margin: 0; color: #292929; font-size: 16px; font-weight: 500;">
                                </p>
                            </div>
                            <div>
                                <p style="margin: 0 0 5px 0; color: #767676; font-size: 14px;">Fecha de vencimiento</p>
                                <p id="modal-fecha-vencimiento"
                                    style="margin: 0; color: #292929; font-size: 16px; font-weight: 500;"></p>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer"
                    style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 0px; border-top: 1px solid #E5E7EB; padding-top: 20px;">
                    <form id="rechazarForm" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-rechazar"
                            style="padding: 10px 20px; background-color: #FEE2E2; color: #DC2626; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.2s;">Rechazar</button>
                    </form>
                    <form id="aceptarForm" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="estado" value="activo">
                        <input type="hidden" id="fecha_expiracion_aceptar" name="fecha_expiracion" value="">
                        <button type="submit" class="btn-aceptar"
                            style="padding: 10px 20px; background-color: #E49B39; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.2s;">Aceptar</button>
                    </form>
                </div>
            </div>
        </div>


    </div>

    <br>
    <!-- Tabs de navegación -->
    <div class="tabs-container" style="display: flex; justify-content: space-between; align-items: center;">
        <ul class="tabs" style="margin: 0;">
            <li class="tab active" onclick="mostrarTab('predios')">INFORMACIÓN DE PREDIOS</li>
            {{--  <li class="tab" onclick="mostrarTab('BPG')">INFORMACIÓN BPG</li> --}}
            <li class="tab" onclick="mostrarTab('riesgo')">RIESGO EPIDEMIOLÓGICO</li>
        </ul>

        <div style="margin-left: auto; margin-right: 20px;">
            <button data-bs-toggle="modal" data-bs-target="#filtroModal" class="tab active">
                Filtrar
            </button>
        </div>
        <!-- Botón Exportar alineado a la derecha -->
        <div style=" margin-right: 20px;">
            <button onclick="document.getElementById('exportSidebar').style.display='flex'" class="tab active">
                Exportar
            </button>
        </div>

    </div>


    <!-- Contenido de cada tab -->
    <div class="tab-content" id="tab-predios">
        @include('Graficos.predios')
    </div>
    {{-- <div class="tab-content" id="tab-BPG" style="display: none;">
        @include('Graficos.BPG')
    </div> --}}
    <div class="tab-content" id="tab-riesgo" style="display: none;">
        @include('Graficos.riesgo')
    </div>

    <div id="exportSidebar" style="display: none;">
        <div class="modal-sidebar">
            <div
                style="width: 420px; max-width: 100%; background: white; padding: 30px;
                border-radius: 8px 0 0 8px; overflow-y: auto; margin-top: 60px;">
                <h2 style="margin-bottom: 20px; margin-top: 20px;">Seleccionar módulos para exportar</h2>

                <form id="formExportar">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                        @foreach (['INFORMACION DE AREAS', 'INFORMACION SOBRE TIERRAS Y AGUAS', 'MANEJO DE PASTOS Y POTREROS', 'MANEJO DEL GANADO', 'INFORMACION DE ASPECTOS M.A', 'TIPOS DE EQUIPOS', 'GESTION DE INFORMACION', 'SANIDAD ANIMAL', 'IDENTIFICACION', 'BIOSEGURIDAD', 'REQUISITOS BPMV', 'REQUISITOS BPAA', 'REQUISITOS DE SANEAMIENTO', 'REQUISITOS DE BIENESTAR ANIMAL', 'PERSONAL', 'TIPOS DE EXPLOTACION', 'INFORMACION EPIDEMIOLOGICA', 'CARACTERISTICAS DE RIESGOS', 'VISITAS A PREDIOS DE RIESGOS', 'SERVICIOS AMBIENTALES', 'CENSO'] as $titulo)
                            <label class="export-label">
                                {{ $titulo }}
                                <input type="checkbox" name="modulos[]" value="{{ $titulo }}"
                                    style="display: none;"
                                    onchange="this.nextElementSibling.classList.toggle('checked', this.checked)">
                                <span class="custom-check">&#10003;</span> {{-- ✔️ Flecha, puede ser ícono o símbolo --}}
                            </label>
                        @endforeach
                    </div>

                    <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" onclick="cerrarSidebar()" class="tab">
                            Cancelar
                        </button>
                        <button type="submit" class="tab active">Confirmar exportación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
<!-- Modal para desglose de recaudación -->
<div class="modal fade" id="recaudoDetalleModal" tabindex="-1" aria-labelledby="recaudoDetalleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recaudoDetalleModalLabel">

                    Desglose de Recaudación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="recaudoTab" role="tablist">
                    @foreach ($recaudacionDetallada as $index => $recaudo)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                id="mes-{{ $index }}-tab" data-bs-toggle="tab"
                                data-bs-target="#mes-{{ $index }}" type="button" role="tab"
                                aria-controls="mes-{{ $index }}"
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                {{ $recaudo['mes'] }}
                                <span class="badge bg-success" style="margin-left: 5px;">
                                    ${{ number_format($recaudo['total'], 0, ',', '.') }}
                                </span>
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="recaudoTabContent">
                    @foreach ($recaudacionDetallada as $index => $recaudo)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                            id="mes-{{ $index }}" role="tabpanel"
                            aria-labelledby="mes-{{ $index }}-tab">
                            <div class="mt-3 mb-3">
                                <div class="recaudo-summary">
                                    <div class="recaudo-total">
                                        <h4>Total recaudado</h4>
                                        <span
                                            class="recaudo-amount">${{ number_format($recaudo['total'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="recaudo-count">
                                        <h4>Suscripciones</h4>
                                        <span
                                            class="badge rounded-pill bg-success">{{ count($recaudo['suscripciones']) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Email</th>
                                            <th>Plan</th>
                                            <th>Precio</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recaudo['suscripciones'] as $suscripcion)
                                            <tr>
                                                <td>{{ $suscripcion->usuario }}</td>
                                                <td>{{ $suscripcion->email }}</td>
                                                <td><span class="plan-badge">{{ $suscripcion->plan }}</span></td>
                                                <td class="precio-column">
                                                    ${{ number_format($suscripcion->precio, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($suscripcion->created_at)->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="empty-state">
                                                        <span class="material-symbols-outlined">payments_off</span>
                                                        <p>No hay recaudaciones en este mes</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total:</th>
                                            <th class="precio-column">
                                                ${{ number_format($recaudo['total'], 0, ',', '.') }}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('suscripcionModal');
            const closeBtn = document.querySelector('.close-modal');
            const gestionarBtns = document.querySelectorAll('.gestionar-suscripcion');
            const aceptarForm = document.getElementById('aceptarForm');
            const rechazarForm = document.getElementById('rechazarForm');

            // Abrir modal
            gestionarBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const email = this.dataset.email;
                    const name = this.dataset.name;
                    const celular = this.dataset.celular;
                    const isVerified = this.dataset.verified === 'true';
                    const avatar = this.dataset.avatar;
                    const predios = this.dataset.predios;
                    const animales = this.dataset.animales;
                    const memberSince = this.dataset.memberSince;
                    const plan = this.dataset.plan;
                    const fecha = this.dataset.fecha;
                    const duracion = parseInt(this.dataset.duracion);
                    const precio = this.dataset.precio;

                    // Calcular fecha de vencimiento
                    const fechaCreacion = new Date(fecha.split('/').reverse().join('-'));
                    const fechaVencimiento = new Date(fechaCreacion);
                    fechaVencimiento.setDate(fechaVencimiento.getDate() + duracion);

                    // Formatear fecha de vencimiento para mostrar
                    const fechaVencimientoFormateada = fechaVencimiento.toLocaleDateString(
                        'es-ES', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });

                    // Formatear fecha para el input hidden (YYYY-MM-DD)
                    const fechaVencimientoInput = fechaVencimiento.toISOString().split('T')[0];
                    document.getElementById('fecha_expiracion_aceptar').value =
                        fechaVencimientoInput;

                    // Actualizar información del usuario
                    document.getElementById('modal-name').textContent = name;
                    document.getElementById('modal-email').textContent = email;
                    document.getElementById('modal-celular').textContent = celular;
                    document.getElementById('modal-fecha-vencimiento').textContent =
                        fechaVencimientoFormateada;
                    document.getElementById('modal-predios').textContent = predios;
                    document.getElementById('modal-animales').textContent = animales;
                    document.getElementById('modal-member-since').textContent = memberSince;

                    // Manejar avatar
                    const avatarImg = document.getElementById('modal-avatar');
                    const avatarIcon = document.getElementById('modal-avatar-icon');
                    if (avatar) {
                        avatarImg.src = avatar;
                        avatarImg.style.display = 'block';
                        avatarIcon.style.display = 'none';
                    } else {
                        avatarImg.style.display = 'none';
                        avatarIcon.style.display = 'block';
                    }

                    // Manejar verificación
                    const verifiedIcon = document.getElementById('modal-verified');
                    verifiedIcon.style.display = isVerified ? 'block' : 'none';

                    // Actualizar información de la suscripción
                    document.getElementById('modal-plan').textContent = plan;
                    document.getElementById('modal-fecha').textContent = fecha;
                    document.getElementById('modal-precio').textContent = precio;

                    // Actualizar formularios con la ruta correcta
                    aceptarForm.action = `/membresias/actualizar/${id}`;
                    rechazarForm.action = `/membresias/actualizar/${id}`;

                    // Añadir campos ocultos para el estado
                    aceptarForm.innerHTML += '<input type="hidden" name="estado" value="activo">';
                    rechazarForm.innerHTML +=
                        '<input type="hidden" name="estado" value="rechazado">';

                    modal.style.display = 'block';
                });
            });

            // Cerrar modal
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });

            // Función para mostrar alertas
            function showAlert(message, type = 'success') {
                const alertContainer = document.getElementById('alert-container');
                const alert = alertContainer.querySelector('.alert');
                const icon = alert.querySelector('.material-symbols-outlined');
                const text = alert.querySelector('p');

                // Configurar estilos según el tipo
                if (type === 'success') {
                    alert.style.backgroundColor = '#D1FAE5';
                    alert.style.border = '1px solid #34D399';
                    icon.textContent = 'check_circle';
                    icon.style.color = '#059669';
                    text.style.color = '#065F46';
                } else {
                    alert.style.backgroundColor = '#FEE2E2';
                    alert.style.border = '1px solid #F87171';
                    icon.textContent = 'error';
                    icon.style.color = '#DC2626';
                    text.style.color = '#991B1B';
                }

                text.textContent = message;
                alertContainer.style.display = 'block';

                // Ocultar después de 5 segundos
                setTimeout(() => {
                    alertContainer.style.display = 'none';
                }, 5000);
            }

            // Manejar envío de formularios
            aceptarForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('¿Estás seguro de que deseas aceptar esta suscripción?')) {
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    formData.append('estado', 'activo');
                    formData.append('fecha_expiracion', document.getElementById('fecha_expiracion_aceptar')
                        .value);

                    fetch(this.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            showAlert('Suscripción aceptada exitosamente');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        })
                        .catch(error => {
                            showAlert('Error al procesar la solicitud', 'error');
                        });
                }
            });

            rechazarForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('¿Estás seguro de que deseas rechazar esta suscripción?')) {
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    formData.append('estado', 'rechazado');
                    formData.append('fecha_expiracion', new Date().toISOString().split('T')[0]);

                    fetch(this.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            showAlert('Suscripción rechazada exitosamente');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        })
                        .catch(error => {
                            showAlert('Error al procesar la solicitud', 'error');
                        });
                }
            });

            // Manejar click en tarjeta de recaudación
            const recaudoCard = document.getElementById('recaudoCard');
            if (recaudoCard) {
                recaudoCard.addEventListener('click', function() {
                    const recaudoModal = new bootstrap.Modal(document.getElementById(
                        'recaudoDetalleModal'));
                    recaudoModal.show();
                });
            }
        });

        function mostrarTab(tabId) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');

            document.querySelector(`#tab-${tabId}`).style.display = 'block';
            document.querySelector(`.tab[onclick="mostrarTab('${tabId}')"]`).classList.add('active');
        }

        function abrirSidebar() {
            document.getElementById('exportSidebar').style.display = 'block';
            document.getElementById('btnExportar').classList.add('active');
        }

        function cerrarSidebar() {
            document.getElementById('exportSidebar').style.display = 'none';
            document.getElementById('btnExportar').classList.remove('active');
        }
    </script>

    <script>
        document.getElementById('formDescarga').submit();
    </script>
@endsection
