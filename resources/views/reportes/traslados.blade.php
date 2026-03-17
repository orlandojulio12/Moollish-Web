@extends('layouts')

@section('title', 'Reporte de Traslados')

@section('page-header')
<div class="page-header">
    <div class="breadcrumb">
        <a href="{{ route('inicio') }}">
            <h3 class="cumb no-active-tab">Inicio</h3>
        </a>
        <span class="material-symbols-outlined bread">chevron_forward</span>
        <a href="{{ route('listados') }}">
            <h3 class="cumb no-active-tab">Listados</h3>
        </a>
        <span class="material-symbols-outlined bread">chevron_forward</span>
        <h3 class="cumb active-tab">Traslados</h3>
    </div>
</div>
@endsection

@section('styles')
<style>
    :root {
        --naranja:       #E8792A;
        --naranja-claro: #F4956A;
        --gris-oscuro:   #1E2025;
        --gris-medio:    #2C3038;
        --gris-claro:    #3A3F4A;
        --texto-claro:   #E8E8E8;
        --texto-muted:   #9AA0AB;
        --borde:         #3F4550;
        --exito:         #27AE60;
        --error:         #E74C3C;
        --azul:          #3498DB;
        --amarillo:      #F39C12;
    }

    /* ─── Breadcrumb ─── */
    .bread         { font-size: 28px; color: black; }
    .cumb          { margin: 0 !important; align-content: center; }
    .breadcrumb    { display: flex; align-items: center; gap: 4px; }
    .active-tab    { color: #dc7a00; }
    .no-active-tab:hover { color: #dc7a00; cursor: pointer; text-decoration: underline; }

    /* ─── Wrapper ─── */
    .rep-wrapper   { padding: 20px; }

    /* ─── Estadísticas ─── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--gris-medio); border: 1px solid var(--borde);
        border-radius: 12px; padding: 16px 18px;
        display: flex; flex-direction: column; gap: 4px;
    }
    .stat-card .sc-valor  { font-size: 1.7rem; font-weight: 800; color: var(--naranja); line-height: 1; }
    .stat-card .sc-label  { font-size: .75rem; color: var(--texto-muted); text-transform: uppercase; letter-spacing: .5px; font-weight: 600; }
    .stat-card .sc-icon   { font-size: 20px; color: var(--naranja); margin-bottom: 4px; opacity: .7; }
    .stat-card.azul    .sc-valor  { color: var(--azul); }
    .stat-card.azul    .sc-icon   { color: var(--azul); }
    .stat-card.verde   .sc-valor  { color: var(--exito); }
    .stat-card.verde   .sc-icon   { color: var(--exito); }
    .stat-card.amarillo .sc-valor { color: var(--amarillo); }
    .stat-card.amarillo .sc-icon  { color: var(--amarillo); }
    .stat-card.rojo    .sc-valor  { color: var(--error); }
    .stat-card.rojo    .sc-icon   { color: var(--error); }

    /* ─── Card general ─── */
    .card-rep {
        background: var(--gris-medio); border: 1px solid var(--borde);
        border-radius: 14px; padding: 20px; margin-bottom: 20px;
    }
    .card-rep-titulo {
        font-size: .95rem; font-weight: 700; color: var(--texto-claro);
        margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    }

    /* ─── Filtros ─── */
    .filtros-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 14px;
        align-items: end;
    }
    .fg-group label {
        display: block; font-size: .78rem; font-weight: 700; color: var(--texto-muted);
        text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px;
    }
    .fg-control {
        width: 100%; background: var(--gris-oscuro); border: 1.5px solid var(--borde);
        border-radius: 8px; padding: 8px 12px; color: var(--texto-claro);
        font-size: .88rem; transition: border-color .2s;
    }
    select.fg-control {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239AA0AB' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center; padding-right: 30px;
    }
    select.fg-control option { background: var(--gris-oscuro); color: var(--texto-claro); }
    .fg-control:focus { outline: none; border-color: var(--naranja); }
    .btn-filtrar {
        padding: 9px 20px; background: linear-gradient(135deg,var(--naranja),var(--naranja-claro));
        color: #fff; border: none; border-radius: 8px; font-weight: 700; font-size: .88rem;
        cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all .2s; white-space: nowrap;
    }
    .btn-filtrar:hover { opacity: .88; transform: translateY(-1px); }
    .btn-limpiar {
        padding: 9px 16px; background: var(--gris-claro); color: var(--texto-muted);
        border: 1.5px solid var(--borde); border-radius: 8px; font-weight: 700; font-size: .88rem;
        cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all .2s;
    }
    .btn-limpiar:hover { color: var(--texto-claro); border-color: var(--texto-muted); }

    /* ─── Tabla ─── */
    .tabla-rep { width: 100%; border-collapse: collapse; font-size: .84rem; }
    .tabla-rep thead th {
        padding: 10px 14px; text-align: left; font-size: .75rem; font-weight: 700;
        color: var(--texto-muted); text-transform: uppercase; letter-spacing: .5px;
        border-bottom: 2px solid var(--borde); background: rgba(0,0,0,.15);
        white-space: nowrap;
    }
    .tabla-rep tbody td {
        padding: 10px 14px; color: var(--texto-claro); border-bottom: 1px solid rgba(63,69,80,.35);
        vertical-align: middle;
    }
    .tabla-rep tbody tr:hover { background: rgba(232,121,42,.04); }
    .tabla-rep tbody tr:last-child td { border-bottom: none; }

    /* ─── Ruta de traslado (origen → destino) ─── */
    .ruta-traslado {
        display: flex; align-items: flex-start; gap: 6px; flex-wrap: wrap;
        font-size: .82rem; line-height: 1.4;
    }
    .ruta-lado { display: flex; flex-direction: column; gap: 2px; }
    .ruta-lado .rl-titulo { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; opacity: .6; }
    .ruta-lado .rl-predio { font-weight: 700; color: var(--texto-claro); }
    .ruta-lado .rl-sub    { color: var(--texto-muted); font-size: .78rem; }
    .ruta-flecha { color: var(--naranja); font-size: 18px; align-self: center; flex-shrink: 0; }

    /* ─── Badges ─── */
    .badge-rep   { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
    .b-masivo    { background: rgba(232,121,42,.15); color: var(--naranja); border: 1px solid rgba(232,121,42,.3); }
    .b-individual{ background: rgba(52,152,219,.15);  color: #5DADE2;      border: 1px solid rgba(52,152,219,.3); }
    .b-macho     { background: rgba(52,152,219,.15);  color: #5DADE2; }
    .b-hembra    { background: rgba(231,76,60,.12);   color: #E07070; }
    .b-lote-nuevo{ background: rgba(39,174,96,.15);   color: #2ECC71;      border: 1px solid rgba(39,174,96,.3); font-size: .68rem; }
    .b-nd        { background: rgba(160,160,160,.1);  color: var(--texto-muted); }

    /* ─── Botón ver detalle ─── */
    .btn-detalle {
        padding: 5px 12px; border-radius: 6px; background: var(--gris-claro);
        border: 1.5px solid var(--borde); color: var(--texto-muted); font-size: .78rem;
        font-weight: 700; cursor: pointer; transition: all .2s; white-space: nowrap;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-detalle:hover { border-color: var(--naranja); color: var(--naranja); }

    /* ─── Paginación ─── */
    .paginacion-wrap {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px; padding-top: 16px; margin-top: 8px;
        border-top: 1px solid var(--borde);
    }
    .paginacion-wrap .page-info { font-size: .82rem; color: var(--texto-muted); }
    .paginacion-links { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
    .paginacion-links a,
    .paginacion-links span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 8px;
        border-radius: 6px; font-size: .82rem; font-weight: 700; text-decoration: none;
        border: 1.5px solid var(--borde); color: var(--texto-muted); background: transparent;
        transition: all .15s;
    }
    .paginacion-links a:hover { border-color: var(--naranja); color: var(--naranja); }
    .paginacion-links .active { border-color: var(--naranja); background: var(--naranja); color: #fff; pointer-events: none; }
    .paginacion-links .disabled { opacity: .35; pointer-events: none; }
    .per-page-sel {
        background: var(--gris-oscuro); border: 1.5px solid var(--borde); border-radius: 6px;
        color: var(--texto-claro); font-size: .78rem; padding: 4px 8px; cursor: pointer;
    }

    /* ─── Sin resultados ─── */
    .sin-resultados-rep {
        text-align: center; padding: 40px 20px; color: var(--texto-muted);
    }
    .sin-resultados-rep .icono { font-size: 44px; opacity: .3; display: block; margin-bottom: 10px; }

    /* ─── Modal detalle ─── */
    .modal-det .modal-content { background: var(--gris-medio); border: 1px solid var(--borde); border-radius: 16px; }
    .modal-det .modal-header  { border-bottom: 1px solid var(--borde); padding: 18px 22px; }
    .modal-det .modal-body    { padding: 20px 22px; }
    .modal-det .modal-footer  { border-top: 1px solid var(--borde); padding: 14px 22px; }
    .det-seccion   { margin-bottom: 20px; }
    .det-sec-titulo { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--texto-muted); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .det-grid      { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .det-item      { background: var(--gris-oscuro); border: 1px solid var(--borde); border-radius: 8px; padding: 10px 14px; }
    .det-item .di-label { font-size: .72rem; color: var(--texto-muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 3px; }
    .det-item .di-valor { font-size: .9rem; font-weight: 600; color: var(--texto-claro); }
    .det-item.full  { grid-column: 1 / -1; }
    .det-ruta {
        background: var(--gris-oscuro); border: 1px solid var(--borde); border-radius: 10px; padding: 16px;
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
    }
    .det-ruta-lado  { flex: 1; min-width: 120px; }
    .det-ruta-lado .drl-titulo { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: var(--texto-muted); margin-bottom: 6px; }
    .det-ruta-lado .drl-predio { font-size: 1rem; font-weight: 700; color: var(--texto-claro); }
    .det-ruta-lado .drl-sub    { font-size: .82rem; color: var(--texto-muted); margin-top: 2px; }
    .det-ruta .flecha-det { color: var(--naranja); font-size: 24px; flex-shrink: 0; }

    /* Tabla animales del evento */
    .tabla-evento { width: 100%; border-collapse: collapse; font-size: .82rem; }
    .tabla-evento th { padding: 8px 12px; background: rgba(0,0,0,.2); color: var(--texto-muted); font-size: .72rem; text-transform: uppercase; letter-spacing: .4px; border-bottom: 1px solid var(--borde); }
    .tabla-evento td { padding: 7px 12px; border-bottom: 1px solid rgba(63,69,80,.3); color: var(--texto-claro); }
    .tabla-evento tr:last-child td { border-bottom: none; }
    .tabla-evento.excluidos td { color: rgba(231,76,60,.8); }

    .btn-sm-det  { padding: 8px 18px; border-radius: 7px; font-size: .85rem; font-weight: 700; cursor: pointer; border: none; transition: all .2s; }
    .btn-sm-det.sec { background: var(--gris-claro); color: var(--texto-claro); }
    .btn-sm-det:hover { opacity: .85; }

    /* ─── Loading spinner mini ─── */
    .spinner-mini { width: 22px; height: 22px; border: 3px solid rgba(255,255,255,.15); border-top-color: var(--naranja); border-radius: 50%; animation: spin .7s linear infinite; display: inline-block; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ─── Responsive ─── */
    @media (max-width: 768px) {
        .stats-grid    { grid-template-columns: repeat(2,1fr); }
        .filtros-grid  { grid-template-columns: 1fr; }
        .det-grid      { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="rep-wrapper">

    {{-- ══ ESTADÍSTICAS ══ --}}
    <div class="stats-grid">
        <div class="stat-card">
            <span class="material-symbols-outlined sc-icon">swap_horiz</span>
            <div class="sc-valor">{{ number_format($stats->total ?? 0) }}</div>
            <div class="sc-label">Total movimientos</div>
        </div>
        <div class="stat-card azul">
            <span class="material-symbols-outlined sc-icon">move_group</span>
            <div class="sc-valor">{{ number_format($stats->total_masivos ?? 0) }}</div>
            <div class="sc-label">Traslados masivos</div>
        </div>
        <div class="stat-card" style="--naranja:#9AA0AB">
            <span class="material-symbols-outlined sc-icon">pets</span>
            <div class="sc-valor" style="color:var(--texto-muted)">{{ number_format($stats->total_individuales ?? 0) }}</div>
            <div class="sc-label">Traslados individuales</div>
        </div>
        <div class="stat-card verde">
            <span class="material-symbols-outlined sc-icon">group_work</span>
            <div class="sc-valor">{{ number_format($stats->total_animales_movidos ?? 0) }}</div>
            <div class="sc-label">Animales movidos</div>
        </div>
        <div class="stat-card amarillo">
            <span class="material-symbols-outlined sc-icon">sell</span>
            <div class="sc-valor">{{ number_format($stats->lotes_creados ?? 0) }}</div>
            <div class="sc-label">Lotes creados</div>
        </div>
        <div class="stat-card rojo">
            <span class="material-symbols-outlined sc-icon">block</span>
            <div class="sc-valor">{{ number_format($totalExcluidos ?? 0) }}</div>
            <div class="sc-label">Animales excluidos</div>
        </div>
    </div>

    {{-- ══ FILTROS ══ --}}
    <div class="card-rep">
        <div class="card-rep-titulo">
            <span class="material-symbols-outlined" style="color:var(--naranja);font-size:18px">tune</span>
            Filtros
        </div>
        <form action="{{ route('reportes.traslados.listado') }}" method="GET">
            <div class="filtros-grid">

                <div class="fg-group">
                    <label>Desde</label>
                    <input type="date" name="fecha_desde" class="fg-control"
                           value="{{ request('fecha_desde') }}" max="{{ date('Y-m-d') }}">
                </div>

                <div class="fg-group">
                    <label>Hasta</label>
                    <input type="date" name="fecha_hasta" class="fg-control"
                           value="{{ request('fecha_hasta') }}" max="{{ date('Y-m-d') }}">
                </div>

                <div class="fg-group">
                    <label>Predio</label>
                    <select name="predio_id" class="fg-control">
                        <option value="">— Todos —</option>
                        @foreach($predios as $p)
                            <option value="{{ $p->id }}" {{ request('predio_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre_predio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="fg-group">
                    <label>Lote (origen o destino)</label>
                    <select name="lote_id" class="fg-control">
                        <option value="">— Todos —</option>
                        @foreach($lotes as $l)
                            <option value="{{ $l->id }}" {{ request('lote_id') == $l->id ? 'selected' : '' }}>
                                {{ $l->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="fg-group">
                    <label>Potrero (origen o destino)</label>
                    <select name="potrero_id" class="fg-control">
                        <option value="">— Todos —</option>
                        @foreach($potreros as $pot)
                            <option value="{{ $pot->id }}" {{ request('potrero_id') == $pot->id ? 'selected' : '' }}>
                                {{ $pot->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="fg-group">
                    <label>Tipo de traslado</label>
                    <select name="tipo_traslado" class="fg-control">
                        <option value="">— Todos —</option>
                        <option value="masivo"     {{ request('tipo_traslado') === 'masivo'     ? 'selected' : '' }}>Masivo</option>
                        <option value="individual" {{ request('tipo_traslado') === 'individual' ? 'selected' : '' }}>Individual</option>
                    </select>
                </div>

                <div class="fg-group">
                    <label>Buscar animal</label>
                    <input type="text" name="animal_busq" class="fg-control"
                           placeholder="Código o nombre..."
                           value="{{ request('animal_busq') }}">
                </div>

                <div class="fg-group">
                    <label>Por página</label>
                    <select name="per_page" class="fg-control">
                        @foreach([20, 50, 100] as $pp)
                            <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }} filas</option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones --}}
                <div class="fg-group" style="display:flex;align-items:flex-end;gap:8px;">
                    <button type="submit" class="btn-filtrar">
                        <span class="material-symbols-outlined" style="font-size:16px">search</span>
                        Filtrar
                    </button>
                    <a href="{{ route('reportes.traslados.listado') }}" class="btn-limpiar">
                        <span class="material-symbols-outlined" style="font-size:16px">refresh</span>
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- ══ TABLA DE MOVIMIENTOS ══ --}}
    <div class="card-rep">
        <div class="card-rep-titulo" style="justify-content:space-between;flex-wrap:wrap;gap:8px;">
            <span style="display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined" style="color:var(--naranja);font-size:18px">list_alt</span>
                Historial de movimientos
            </span>
            <span style="font-size:.8rem;color:var(--texto-muted);font-weight:400;">
                {{ $movimientos->total() }} registro{{ $movimientos->total() != 1 ? 's' : '' }}
                @if(request()->hasAny(['fecha_desde','fecha_hasta','predio_id','lote_id','potrero_id','tipo_traslado','animal_busq']))
                    <span style="color:var(--naranja)">· filtrado</span>
                @endif
            </span>
        </div>

        <div style="overflow-x:auto;">
            <table class="tabla-rep">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Animal</th>
                        <th>Tipo</th>
                        <th style="min-width:320px">Origen → Destino</th>
                        <th>Motivo</th>
                        <th>Lote creado</th>
                        <th>Ref. evento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $mov)
                    <tr>

                        {{-- Fecha --}}
                        <td style="white-space:nowrap;color:var(--texto-muted);font-size:.82rem;">
                            {{ \Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y') }}
                        </td>

                        {{-- Animal --}}
                        <td>
                            <div style="font-weight:700;color:var(--texto-claro);">{{ $mov->animal_codigo }}</div>
                            <div style="font-size:.78rem;color:var(--texto-muted);">{{ $mov->animal_nombre ?? '—' }}</div>
                            <span class="badge-rep {{ strtolower($mov->animal_sexo ?? '') === 'macho' ? 'b-macho' : 'b-hembra' }}" style="margin-top:2px;">
                                {{ $mov->animal_sexo ?? '—' }}
                            </span>
                        </td>

                        {{-- Tipo --}}
                        <td>
                            @if($mov->tipo_traslado === 'masivo')
                                <span class="badge-rep b-masivo">
                                    <span class="material-symbols-outlined" style="font-size:12px">move_group</span>
                                    Masivo
                                </span>
                            @else
                                <span class="badge-rep b-individual">
                                    <span class="material-symbols-outlined" style="font-size:12px">pets</span>
                                    Individual
                                </span>
                            @endif
                        </td>

                        {{-- Origen → Destino --}}
                        <td>
                            <div class="ruta-traslado">
                                {{-- ORIGEN --}}
                                <div class="ruta-lado">
                                    <span class="rl-titulo">Origen</span>
                                    @if($mov->origen_predio)
                                        <span class="rl-predio">{{ $mov->origen_predio }}</span>
                                        <span class="rl-sub">
                                            @if($mov->origen_lote)    <span>↳ L: {{ $mov->origen_lote }}</span><br>@endif
                                            @if($mov->origen_potrero) <span>↳ P: {{ $mov->origen_potrero }}</span>@endif
                                            @if(!$mov->origen_lote && !$mov->origen_potrero) Solo predio @endif
                                        </span>
                                    @else
                                        <span style="color:var(--texto-muted);font-style:italic;font-size:.78rem;">Sin registro</span>
                                    @endif
                                </div>

                                <span class="material-symbols-outlined ruta-flecha">arrow_forward</span>

                                {{-- DESTINO --}}
                                <div class="ruta-lado">
                                    <span class="rl-titulo">Destino</span>
                                    <span class="rl-predio">{{ $mov->destino_predio ?? '—' }}</span>
                                    <span class="rl-sub">
                                        @if($mov->destino_lote)    <span>↳ L: {{ $mov->destino_lote }}</span><br>@endif
                                        @if($mov->destino_potrero) <span>↳ P: {{ $mov->destino_potrero }}</span>@endif
                                        @if(!$mov->destino_lote && !$mov->destino_potrero) Solo predio @endif
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Motivo --}}
                        <td style="max-width:180px;">
                            <span style="font-size:.82rem;color:var(--texto-muted);">
                                {{ $mov->motivo ? Str::limit($mov->motivo, 60) : '—' }}
                            </span>
                        </td>

                        {{-- Lote nuevo --}}
                        <td>
                            @if($mov->lote_nuevo_nombre)
                                <span class="badge-rep b-lote-nuevo">
                                    <span class="material-symbols-outlined" style="font-size:11px">add_circle</span>
                                    {{ Str::limit($mov->lote_nuevo_nombre, 22) }}
                                </span>
                            @else
                                <span style="color:var(--texto-muted);font-size:.78rem;">—</span>
                            @endif
                        </td>

                        {{-- Ref. evento --}}
                        <td>
                           
                           
                                <span style="color:var(--texto-muted);font-size:.78rem;">—</span>
                           
                        </td>

                        {{-- Acciones --}}
                        <td>
                            <button class="btn-detalle" onclick="verDetalle({{ $mov->id }})">
                                <span class="material-symbols-outlined" style="font-size:14px">open_in_new</span>
                                Detalle
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="sin-resultados-rep">
                                <span class="material-symbols-outlined icono">swap_horiz</span>
                                No se encontraron movimientos con los filtros seleccionados.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($movimientos->hasPages())
        <div class="paginacion-wrap">
            <span class="page-info">
                Mostrando {{ $movimientos->firstItem() }}–{{ $movimientos->lastItem() }}
                de {{ $movimientos->total() }} registros
            </span>
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:.78rem;color:var(--texto-muted)">Por página:</span>
                <select class="per-page-sel" onchange="cambiarPerPage(this.value)">
                    @foreach([20,50,100] as $pp)
                        <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                    @endforeach
                </select>
                <div class="paginacion-links">
                    {{-- Primera / Anterior --}}
                    @if($movimientos->onFirstPage())
                        <span class="disabled">‹</span>
                    @else
                        <a href="{{ $movimientos->previousPageUrl() }}">‹</a>
                    @endif

                    {{-- Páginas --}}
                    @foreach($movimientos->getUrlRange(max(1,$movimientos->currentPage()-2), min($movimientos->lastPage(),$movimientos->currentPage()+2)) as $page => $url)
                        @if($page == $movimientos->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Siguiente --}}
                    @if($movimientos->hasMorePages())
                        <a href="{{ $movimientos->nextPageUrl() }}">›</a>
                    @else
                        <span class="disabled">›</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

</div>{{-- /rep-wrapper --}}



@endsection
{{-- ══ MODAL DETALLE ══ --}}
<div class="modal fade modal-det" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    style="color:var(--texto-claro);font-weight:700;display:flex;align-items:center;gap:8px;">
                    <span class="material-symbols-outlined" style="color:var(--naranja)">swap_horiz</span>
                    Detalle del movimiento
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal"
                        style="color:var(--texto-muted);background:none;border:none;font-size:1.4rem;">&times;</button>
            </div>
            <div class="modal-body" id="detalle-body">
                <div style="text-align:center;padding:30px;">
                    <div class="spinner-mini" style="width:36px;height:36px;margin:0 auto 12px;"></div>
                    <div style="color:var(--texto-muted)">Cargando...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-sm-det sec" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ── Cambiar per_page conservando filtros ──────────────────────────────────────
function cambiarPerPage(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', val);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

// ── Ver detalle de un movimiento ─────────────────────────────────────────────
function verDetalle(id) {
    document.getElementById('detalle-body').innerHTML = `
        <div style="text-align:center;padding:30px;">
            <div class="spinner-mini" style="width:36px;height:36px;margin:0 auto 12px;"></div>
            <div style="color:var(--texto-muted)">Cargando...</div>
        </div>`;
    $('#modalDetalle').modal('show');

    fetch(`{{ route('reportes.traslados.detalle', '') }}/${id}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            document.getElementById('detalle-body').innerHTML = `<p style="color:var(--error);">${data.message}</p>`;
            return;
        }
        document.getElementById('detalle-body').innerHTML = buildDetalleHTML(data);
    })
    .catch(() => {
        document.getElementById('detalle-body').innerHTML = `<p style="color:var(--error);">Error de conexión.</p>`;
    });
}

function buildDetalleHTML(data) {
    const m   = data.movimiento;
    const fmt = d => d ? new Date(d + 'T00:00:00').toLocaleDateString('es-CO', { day:'2-digit', month:'short', year:'numeric' }) : '—';
    const nd  = v => v || '<span style="color:var(--texto-muted);font-style:italic">No registrado</span>';

    // Badge tipo
    const tipoBadge = m.tipo_traslado === 'masivo'
        ? `<span class="badge-rep b-masivo"><span class="material-symbols-outlined" style="font-size:12px">move_group</span> Masivo</span>`
        : `<span class="badge-rep b-individual"><span class="material-symbols-outlined" style="font-size:12px">pets</span> Individual</span>`;

    // Lote nuevo
    const loteNuevo = m.lote_nuevo_nombre
        ? `<span class="badge-rep b-lote-nuevo"><span class="material-symbols-outlined" style="font-size:11px">add_circle</span> ${m.lote_nuevo_nombre}</span>`
        : '—';

    let html = `
    <!-- Animal y fecha -->
    <div class="det-seccion">
        <div class="det-sec-titulo">
            <span class="material-symbols-outlined" style="font-size:15px">pets</span> Animal
        </div>
        <div class="det-grid">
            <div class="det-item">
                <div class="di-label">Código</div>
                <div class="di-valor">${nd(m.animal_codigo)}</div>
            </div>
            <div class="det-item">
                <div class="di-label">Nombre</div>
                <div class="di-valor">${nd(m.animal_nombre)}</div>
            </div>
            <div class="det-item">
                <div class="di-label">Sexo</div>
                <div class="di-valor">${nd(m.animal_sexo)}</div>
            </div>
            <div class="det-item">
                <div class="di-label">Fecha del traslado</div>
                <div class="di-valor">${fmt(m.fecha_movimiento)}</div>
            </div>
            <div class="det-item">
                <div class="di-label">Tipo</div>
                <div class="di-valor">${tipoBadge}</div>
            </div>
            <div class="det-item">
                <div class="di-label">Lote creado automáticamente</div>
                <div class="di-valor">${loteNuevo}</div>
            </div>
            <div class="det-item full">
                <div class="di-label">Motivo</div>
                <div class="di-valor">${nd(m.motivo)}</div>
            </div>
            ${m.traslado_ref ? `
            <div class="det-item full">
                <div class="di-label">Referencia del evento</div>
                <div class="di-valor" style="font-family:monospace;font-size:.82rem;">${m.traslado_ref}</div>
            </div>` : ''}
        </div>
    </div>

    <!-- Ruta Origen → Destino -->
    <div class="det-seccion">
        <div class="det-sec-titulo">
            <span class="material-symbols-outlined" style="font-size:15px">route</span> Ruta del traslado
        </div>
        <div class="det-ruta">
            <div class="det-ruta-lado">
                <div class="drl-titulo">📍 Origen</div>
                <div class="drl-predio">${m.origen_predio || '<span style="font-style:italic;opacity:.5;font-size:.82rem">Sin registro</span>'}</div>
                <div class="drl-sub">
                    ${m.origen_lote    ? `↳ Lote: ${m.origen_lote}<br>` : ''}
                    ${m.origen_potrero ? `↳ Potrero: ${m.origen_potrero}` : ''}
                    ${!m.origen_lote && !m.origen_potrero && m.origen_predio ? 'Solo predio' : ''}
                </div>
            </div>
            <span class="material-symbols-outlined flecha-det">arrow_forward</span>
            <div class="det-ruta-lado">
                <div class="drl-titulo">🏁 Destino</div>
                <div class="drl-predio">${nd(m.destino_predio)}</div>
                <div class="drl-sub">
                    ${m.destino_lote    ? `↳ Lote: ${m.destino_lote}<br>` : ''}
                    ${m.destino_potrero ? `↳ Potrero: ${m.destino_potrero}` : ''}
                    ${!m.destino_lote && !m.destino_potrero ? 'Solo predio' : ''}
                </div>
            </div>
        </div>
    </div>`;

    // ── Animales del mismo evento (traslado masivo) ──
    if (data.grupo && data.grupo.length > 0) {
        html += `
        <div class="det-seccion">
            <div class="det-sec-titulo">
                <span class="material-symbols-outlined" style="font-size:15px">move_group</span>
                Animales del mismo evento (${data.grupo.length} trasladados)
            </div>
            <div style="max-height:200px;overflow-y:auto;border-radius:8px;border:1px solid var(--borde);">
                <table class="tabla-evento">
                    <thead><tr><th>#</th><th>Código</th><th>Nombre</th><th>Sexo</th></tr></thead>
                    <tbody>
                        ${data.grupo.map((a, i) => `
                            <tr>
                                <td style="color:var(--texto-muted)">${i+1}</td>
                                <td><strong>${a.codigo}</strong></td>
                                <td>${a.nombre || '—'}</td>
                                <td>${a.sexo || '—'}</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            </div>
        </div>`;
    }

    // ── Animales excluidos del evento ──
    if (data.excluidos && data.excluidos.length > 0) {
        html += `
        <div class="det-seccion">
            <div class="det-sec-titulo" style="color:var(--error)">
                <span class="material-symbols-outlined" style="font-size:15px">block</span>
                Animales que NO fueron trasladados (${data.excluidos.length})
            </div>
            <div style="max-height:180px;overflow-y:auto;border-radius:8px;border:1px solid rgba(231,76,60,.3);">
                <table class="tabla-evento excluidos">
                    <thead><tr><th>Código</th><th>Nombre</th><th>Sexo</th><th>Motivo de exclusión</th></tr></thead>
                    <tbody>
                        ${data.excluidos.map(a => `
                            <tr>
                                <td><strong>${a.codigo}</strong></td>
                                <td>${a.nombre || '—'}</td>
                                <td>${a.sexo || '—'}</td>
                                <td style="font-style:italic;font-size:.8rem;">${a.motivo || '—'}</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            </div>
        </div>`;
    }

    return html;
}
</script>
@endsection