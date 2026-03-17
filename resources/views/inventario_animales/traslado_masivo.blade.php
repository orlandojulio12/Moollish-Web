@extends('layouts')

@section('title', 'Traslado Masivo de Grupo')

@section('styles')
<style>
    :root {
        --naranja:       #E8792A;
        --naranja-claro: #F4956A;
        --verde:         #2D7D46;
        --verde-claro:   #3EA15C;
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

    .traslado-wrapper { padding: 24px; max-width: 1100px; margin: 0 auto; }

    /* ─── Header ─── */
    .page-header { display: flex; align-items: center; gap: 14px; margin-bottom: 32px; }
    .page-header .icon-box {
        width: 48px; height: 48px; flex-shrink: 0; border-radius: 12px;
        background: linear-gradient(135deg, var(--naranja), var(--naranja-claro));
        display: flex; align-items: center; justify-content: center;
    }
    .page-header h1 { font-size: 1.6rem; font-weight: 700; color: var(--naranja); margin: 0; }
    .page-header p  { font-size: .9rem; color: var(--texto-muted); margin: 2px 0 0; }

    /* ─── Pasos ─── */
    .steps-bar  { display: flex; align-items: center; margin-bottom: 28px; }
    .step-item  { display: flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: .85rem; font-weight: 600; color: var(--texto-muted); }
    .step-item.active { color: var(--naranja); }
    .step-item.done   { color: var(--exito); }
    .step-circle {
        width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
        border: 2px solid var(--borde); display: flex; align-items: center;
        justify-content: center; font-size: .8rem; font-weight: 700; transition: all .25s;
    }
    .step-item.active .step-circle { border-color: var(--naranja); background: var(--naranja); color: #fff; }
    .step-item.done   .step-circle { border-color: var(--exito);   background: var(--exito);   color: #fff; }
    .step-divider { flex: 1; height: 2px; background: var(--borde); min-width: 24px; }

    /* ─── Cards ─── */
    .card-traslado { background: var(--gris-medio); border: 1px solid var(--borde); border-radius: 14px; padding: 24px; margin-bottom: 20px; }
    .card-titulo   { font-size: 1rem; font-weight: 700; color: var(--texto-claro); margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
    .badge-paso    { font-size: .7rem; padding: 3px 10px; border-radius: 20px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; background: var(--naranja); color: #fff; }

    /* ─── Toggles ─── */
    .tipo-toggle { display: flex; gap: 10px; margin-bottom: 16px; }
    .tipo-btn {
        flex: 1; padding: 14px; border-radius: 10px; border: 2px solid var(--borde);
        background: transparent; color: var(--texto-muted); font-weight: 600;
        font-size: .9rem; cursor: pointer; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .tipo-btn:hover    { border-color: var(--naranja-claro); color: var(--texto-claro); }
    .tipo-btn.selected { border-color: var(--naranja); background: rgba(232,121,42,.12); color: var(--naranja); }

    /* ─── Form ─── */
    .form-group-tm { margin-bottom: 16px; }
    .form-group-tm label { display: block; font-size: .83rem; font-weight: 600; color: var(--texto-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .5px; }
    .form-control-tm {
        width: 100%; background: var(--gris-oscuro); border: 1.5px solid var(--borde);
        border-radius: 8px; padding: 10px 14px; color: var(--texto-claro);
        font-size: .92rem; transition: border-color .2s;
    }
    select.form-control-tm {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239AA0AB' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;
    }
    select.form-control-tm option { background: var(--gris-oscuro); color: var(--texto-claro); }
    .form-control-tm:focus    { outline: none; border-color: var(--naranja); }
    .form-control-tm:disabled { opacity: .45; cursor: not-allowed; }
    textarea.form-control-tm  { resize: vertical; min-height: 80px; }

    /* ─── Preview box ─── */
    .preview-box { background: var(--gris-oscuro); border: 1px solid var(--borde); border-radius: 10px; overflow: hidden; margin-top: 16px; display: none; }

    /* ─── Barra superior del preview ─── */
    .preview-header {
        padding: 12px 16px; background: rgba(232,121,42,.08); border-bottom: 1px solid var(--borde);
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
    }
    .preview-header .titulo { font-size: .88rem; font-weight: 700; color: var(--naranja); display: flex; align-items: center; gap: 6px; }
    .seleccion-bar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .contador-sel  { background: var(--naranja); color: #fff; border-radius: 20px; padding: 3px 14px; font-size: .82rem; font-weight: 700; transition: background .2s; }
    .contador-sel.parcial { background: var(--amarillo); }
    .contador-sel.cero    { background: var(--error); }
    .btn-sel-todo {
        padding: 4px 12px; border-radius: 6px; font-size: .78rem; font-weight: 700;
        cursor: pointer; border: 1.5px solid var(--borde); background: transparent;
        color: var(--texto-muted); transition: all .2s;
    }
    .btn-sel-todo:hover { border-color: var(--naranja); color: var(--naranja); }

    /* ─── Buscador de animales ─── */
    .buscador-wrap {
        padding: 10px 16px; border-bottom: 1px solid var(--borde);
        background: rgba(0,0,0,.1); display: flex; align-items: center; gap: 8px;
    }
    .buscador-input {
        flex: 1; background: var(--gris-medio); border: 1.5px solid var(--borde);
        border-radius: 8px; padding: 8px 12px; color: var(--texto-claro);
        font-size: .85rem; transition: border-color .2s;
    }
    .buscador-input:focus { outline: none; border-color: var(--naranja); }
    .buscador-input::placeholder { color: var(--texto-muted); }
    .buscador-limpiar {
        background: none; border: none; cursor: pointer; color: var(--texto-muted);
        font-size: 18px; padding: 0 4px; transition: color .2s; display: none;
    }
    .buscador-limpiar:hover { color: var(--naranja); }
    .buscador-resultado {
        font-size: .78rem; color: var(--texto-claro); white-space: nowrap;
    }

    /* ─── Tabla ─── */
    .preview-tabla { width: 100%; border-collapse: collapse; font-size: .84rem; }
    .preview-tabla th {
        padding: 10px 14px; text-align: left; font-weight: 700; color: var(--texto-muted);
        text-transform: uppercase; font-size: .75rem; letter-spacing: .5px;
        border-bottom: 1px solid var(--borde); background: rgba(0,0,0,.15); position: sticky; top: 0;
    }
    .preview-tabla td { padding: 9px 14px; color: var(--texto-claro); border-bottom: 1px solid rgba(63,69,80,.4); }
    .preview-tabla tr:last-child td { border-bottom: none; }
    .preview-tabla tr.deseleccionado td { opacity: .45; text-decoration: line-through; }
    .preview-tabla tr.deseleccionado    { background: rgba(231,76,60,.04); }
    .preview-tabla tr.oculto            { display: none; }
    .cb-animal { width: 18px; height: 18px; accent-color: var(--naranja); cursor: pointer; }
    .badge-sexo        { padding: 2px 10px; border-radius: 20px; font-size: .75rem; font-weight: 700; }
    .badge-sexo.macho  { background: rgba(52,152,219,.2); color: #3498DB; }
    .badge-sexo.hembra { background: rgba(231,76,60,.15);  color: #E74C3C; }
    .sin-resultados    { text-align: center; padding: 24px; color: var(--texto-muted); font-size: .88rem; }

    /* ─── Paginación ─── */
    .paginacion-wrap {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 16px; border-top: 1px solid var(--borde);
        background: rgba(0,0,0,.1); flex-wrap: wrap; gap: 8px;
    }
    .paginacion-info { font-size: .78rem; color: var(--texto-muted); }
    .paginacion-btns { display: flex; align-items: center; gap: 4px; }
    .btn-pag {
        min-width: 32px; height: 32px; border-radius: 6px; border: 1.5px solid var(--borde);
        background: transparent; color: var(--texto-muted); font-size: .8rem;
        font-weight: 700; cursor: pointer; transition: all .15s; padding: 0 8px;
    }
    .btn-pag:hover     { border-color: var(--naranja); color: var(--naranja); }
    .btn-pag.activo    { border-color: var(--naranja); background: var(--naranja); color: #fff; }
    .btn-pag:disabled  { opacity: .35; cursor: not-allowed; }
    .pag-por-pagina {
        background: var(--gris-oscuro); border: 1.5px solid var(--borde); border-radius: 6px;
        color: var(--texto-claro); font-size: .78rem; padding: 4px 8px; cursor: pointer;
    }

    /* ─── Panel excluidos ─── */
    .panel-excluidos {
        background: rgba(231,76,60,.06); border: 1.5px solid rgba(231,76,60,.3);
        border-radius: 10px; padding: 16px; margin-top: 14px; display: none;
    }
    .panel-excluidos .titulo-panel { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: .9rem; font-weight: 700; color: #E74C3C; }
    .lista-excluidos { margin-bottom: 12px; }
    .tag-excluido {
        display: inline-flex; align-items: center; gap: 5px; margin: 2px;
        background: rgba(231,76,60,.12); border: 1px solid rgba(231,76,60,.25);
        border-radius: 20px; padding: 3px 10px; font-size: .78rem; color: #E74C3C; font-weight: 600;
    }

    /* ─── Banners escenario ─── */
    .banner-escenario { border-radius: 10px; padding: 14px 16px; margin-bottom: 12px; display: none; align-items: flex-start; gap: 12px; border: 1px solid; }
    .banner-escenario .icono   { font-size: 22px; flex-shrink: 0; margin-top: 1px; }
    .banner-escenario .b-titulo { font-weight: 700; font-size: .92rem; margin-bottom: 3px; }
    .banner-escenario .b-desc   { font-size: .83rem; opacity: .85; line-height: 1.5; }
    .banner-esc1 { background: rgba(52,152,219,.08);  border-color: rgba(52,152,219,.35);  color: #5DADE2; }
    .banner-esc2 { background: rgba(241,196,15,.08);  border-color: rgba(241,196,15,.35);  color: #F4D03F; }
    .banner-esc3 { background: rgba(39,174,96,.08);   border-color: rgba(39,174,96,.35);   color: #2ECC71; }

    /* ─── Campo lote nuevo ─── */
    .campo-lote-nuevo { background: rgba(39,174,96,.06); border: 1.5px solid rgba(39,174,96,.35); border-radius: 10px; padding: 16px; margin-top: 8px; display: none; }
    .campo-lote-nuevo label            { color: #2ECC71 !important; }
    .campo-lote-nuevo .form-control-tm { border-color: rgba(39,174,96,.4); }
    .campo-lote-nuevo .form-control-tm:focus { border-color: #2ECC71; }
    .campo-lote-nuevo .hint { font-size: .78rem; color: rgba(46,204,113,.7); margin-top: 5px; display: flex; align-items: center; gap: 4px; }

    /* ─── Resumen destino visual ─── */
    .resumen-destino-visual {
        background: rgba(232,121,42,.06); border: 1px solid rgba(232,121,42,.25);
        border-radius: 10px; padding: 12px 16px; margin-top: 14px;
        font-size: .86rem; color: var(--texto-claro);
        display: none; align-items: center; gap: 10px; flex-wrap: wrap;
    }

    /* ─── Grid / Flecha ─── */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .flecha-traslado { display: flex; align-items: center; justify-content: center; padding: 6px 0; color: var(--naranja); }
    .flecha-traslado span {
        background: rgba(232,121,42,.12); border: 2px solid var(--naranja);
        border-radius: 50%; width: 44px; height: 44px;
        display: flex; align-items: center; justify-content: center;
    }

    /* ─── Botón ejecutar ─── */
    .btn-ejecutar {
        width: 100%; padding: 15px; border: none; border-radius: 10px;
        background: linear-gradient(135deg, var(--naranja), var(--naranja-claro));
        color: #fff; font-size: 1rem; font-weight: 700; cursor: pointer;
        transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 8px;
    }
    .btn-ejecutar:hover    { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(232,121,42,.35); }
    .btn-ejecutar:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }

    /* ─── Alertas ─── */
    .alerta-tm { padding: 12px 16px; border-radius: 8px; font-size: .88rem; font-weight: 600; margin-bottom: 12px; display: none; align-items: center; gap: 8px; }
    .alerta-tm.info    { background: rgba(52,152,219,.1);  border: 1px solid rgba(52,152,219,.4);  color: #5DADE2; }
    .alerta-tm.warning { background: rgba(241,196,15,.1);  border: 1px solid rgba(241,196,15,.4);  color: #F4D03F; }
    .alerta-tm.error   { background: rgba(231,76,60,.1);   border: 1px solid rgba(231,76,60,.4);   color: #E74C3C; }
    .alerta-tm.success { background: rgba(39,174,96,.1);   border: 1px solid rgba(39,174,96,.4);   color: #2ECC71; }

    /* ─── Modal confirmación ─── */
    .modal-confirmar .modal-content { background: var(--gris-medio); border: 1px solid var(--borde); border-radius: 16px; }
    .modal-confirmar .modal-header  { border-bottom: 1px solid var(--borde); padding: 18px 22px; }
    .modal-confirmar .modal-body    { padding: 20px 22px; }
    .modal-confirmar .modal-footer  { border-top: 1px solid var(--borde); padding: 14px 22px; }
    .resumen-box { background: var(--gris-oscuro); border: 1px solid var(--borde); border-radius: 10px; padding: 16px; }
    .resumen-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 7px 0; font-size: .88rem; }
    .resumen-row:not(:last-child) { border-bottom: 1px solid rgba(63,69,80,.4); }
    .resumen-row .label  { color: var(--texto-muted); flex-shrink: 0; margin-right: 12px; }
    .resumen-row .valor  { color: var(--texto-claro); font-weight: 600; text-align: right; }
    .resumen-row .naranja { color: var(--naranja); font-size: 1rem; }
    .resumen-row .verde   { color: var(--verde-claro); }
    .resumen-row .rojo    { color: #E74C3C; }
    .badge-escenario  { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: .75rem; font-weight: 700; letter-spacing: .4px; }
    .badge-esc1 { background: rgba(52,152,219,.2);  color: #5DADE2; }
    .badge-esc2 { background: rgba(241,196,15,.2);  color: #F4D03F; }
    .badge-esc3 { background: rgba(39,174,96,.2);   color: #2ECC71; }
    .btn-sm-tm          { padding: 9px 20px; border-radius: 8px; font-size: .88rem; font-weight: 700; cursor: pointer; border: none; transition: all .2s; }
    .btn-sm-tm.secundario { background: var(--gris-claro); color: var(--texto-claro); }
    .btn-sm-tm.primario   { background: linear-gradient(135deg, var(--naranja), var(--naranja-claro)); color: #fff; }
    .btn-sm-tm:hover    { opacity: .85; }

    /* ─── Loading ─── */
    .loading-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.65); z-index: 9999; display: none; align-items: center; justify-content: center; flex-direction: column; gap: 16px; }
    .loading-overlay .spinner { width: 52px; height: 52px; border: 4px solid rgba(255,255,255,.15); border-top-color: var(--naranja); border-radius: 50%; animation: spin .7s linear infinite; }
    .loading-overlay p { color: #fff; font-weight: 600; font-size: 1rem; }
    @keyframes spin { to { transform: rotate(360deg); } }

    @media (max-width: 768px) {
        .grid-2       { grid-template-columns: 1fr; }
        .steps-bar    { flex-wrap: wrap; gap: 6px; }
        .step-divider { display: none; }
    }
</style>
@endsection

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- CONTENT                                                                --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
@section('content')

<div class="loading-overlay" id="loading-overlay">
    <div class="spinner"></div>
    <p id="loading-msg">Trasladando animales...</p>
</div>

<div class="traslado-wrapper">

    {{-- Header --}}
    <div class="page-header">
        <div class="icon-box">
            <span class="material-symbols-outlined" style="color:#fff;font-size:24px">move_group</span>
        </div>
        <div>
            <h1>Traslado Masivo de Grupo</h1>
            <p>Mueve todo un lote o potrero a otro destino. Puedes excluir animales individuales si es necesario.</p>
        </div>
    </div>

    {{-- Indicador de pasos --}}
    <div class="steps-bar">
        <div class="step-item active" id="paso-1-indicator"><div class="step-circle">1</div><span>Origen</span></div>
        <div class="step-divider"></div>
        <div class="step-item" id="paso-2-indicator"><div class="step-circle">2</div><span>Selección</span></div>
        <div class="step-divider"></div>
        <div class="step-item" id="paso-3-indicator"><div class="step-circle">3</div><span>Destino</span></div>
        <div class="step-divider"></div>
        <div class="step-item" id="paso-4-indicator"><div class="step-circle">4</div><span>Confirmar</span></div>
    </div>

    <div class="alerta-tm" id="alerta-general"></div>

    {{-- ══ PASO 1 — ORIGEN ══ --}}
    <div class="card-traslado">
        <div class="card-titulo"><span class="badge-paso">Paso 1</span> ¿Desde dónde vas a trasladar?</div>

        <div class="form-group-tm">
            <label>Tipo de origen</label>
            <div class="tipo-toggle">
                <button type="button" class="tipo-btn selected" id="btn-origen-lote" onclick="seleccionarOrigenTipo('lote')">
                    <span class="material-symbols-outlined">sell</span> Lote
                </button>
                <button type="button" class="tipo-btn" id="btn-origen-potrero" onclick="seleccionarOrigenTipo('potrero')">
                    <span class="material-symbols-outlined">fence</span> Potrero
                </button>
            </div>
        </div>

        <div class="form-group-tm">
            <label id="label-origen">Seleccionar lote de origen</label>
            <select class="form-control-tm" id="select-origen" onchange="cargarPreviewAnimales()">
                <option value="">— Elige un lote —</option>
                @foreach($lotes as $lote)
                    <option value="{{ $lote->id }}"
                            data-predio-id="{{ $lote->predio_id }}"
                            data-nombre="{{ $lote->nombre }}">
                        {{ $lote->nombre }} ({{ $lote->predio->nombre_predio ?? 'N/A' }}) — {{ $lote->animales_count }} animales
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Preview con buscador + checkboxes + paginación --}}
        <div class="preview-box" id="preview-box">

            {{-- Barra superior: título + contadores + botones selección --}}
            <div class="preview-header">
                <div class="titulo">
                    <span class="material-symbols-outlined" style="font-size:16px">pets</span>
                    Animales del grupo
                </div>
                <div class="seleccion-bar">
                    <button class="btn-sel-todo" onclick="seleccionarTodos(true)">✓ Todos</button>
                    <button class="btn-sel-todo" onclick="seleccionarTodos(false)">✗ Ninguno</button>
                    <span class="contador-sel" id="contador-sel">0 seleccionados</span>
                </div>
            </div>

            {{-- Buscador de animales --}}
            <div class="buscador-wrap">
                <span class="material-symbols-outlined" style="color:var(--texto-muted);font-size:18px;flex-shrink:0">search</span>
                <input type="text" class="buscador-input" id="buscador-animales"
                       placeholder="Buscar por código, nombre o sexo..."
                       oninput="onBuscar(this.value)">
                <button class="buscador-limpiar" id="btn-limpiar-busqueda"
                        onclick="limpiarBusqueda()" title="Limpiar">
                    <span class="material-symbols-outlined" style="font-size:18px">close</span>
                </button>
                <span class="buscador-resultado" id="buscador-resultado"></span>
            </div>

            {{-- Tabla --}}
            <div style="overflow-x:auto;">
                <table class="preview-tabla">
                    <thead>
                        <tr>
                            <th style="width:36px">
                                <input type="checkbox" class="cb-animal" id="cb-todos"
                                       onchange="seleccionarTodos(this.checked)" checked>
                            </th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Sexo</th>
                            <th>Predio actual</th>
                            <th>Lote</th>
                            <th>Potrero</th>
                        </tr>
                    </thead>
                    <tbody id="preview-tbody"></tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="paginacion-wrap" id="paginacion-wrap">
                <div class="paginacion-info" id="paginacion-info"></div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:.78rem;color:var(--texto-muted)">Por página:</span>
                    <select class="pag-por-pagina" id="pag-por-pagina" onchange="cambiarPorPagina(this.value)">
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="0">Todos</option>
                    </select>
                    <div class="paginacion-btns" id="paginacion-btns"></div>
                </div>
            </div>

            {{-- Panel de excluidos --}}
            <div class="panel-excluidos" id="panel-excluidos">
                <div class="titulo-panel">
                    <span class="material-symbols-outlined" style="font-size:18px">warning</span>
                    Animales que NO serán trasladados
                    <span id="badge-excluidos"
                          style="background:rgba(231,76,60,.2);color:#E74C3C;border-radius:20px;padding:2px 10px;font-size:.78rem;font-weight:700;"></span>
                </div>
                <div class="lista-excluidos" id="lista-excluidos-tags"></div>
                <div class="form-group-tm" style="margin-bottom:0">
                    <label style="color:#E74C3C !important;">
                        Motivo por el que NO se trasladan <span style="color:#E74C3C">*</span>
                    </label>
                    <textarea class="form-control-tm" id="motivo-exclusion"
                              placeholder="Ej: Presentan síntomas de enfermedad, quedan en observación..."
                              style="border-color:rgba(231,76,60,.4);"></textarea>
                    <div style="font-size:.77rem;color:rgba(231,76,60,.6);margin-top:4px;display:flex;align-items:center;gap:4px;">
                        <span class="material-symbols-outlined" style="font-size:13px">info</span>
                        Este motivo queda en la trazabilidad de cada animal excluido.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flecha-traslado">
        <span class="material-symbols-outlined">arrow_downward</span>
    </div>

    {{-- ══ PASO 2 — DESTINO ══ --}}
    <div class="card-traslado">
        <div class="card-titulo"><span class="badge-paso">Paso 2</span> ¿A dónde los vas a llevar?</div>

        <div class="form-group-tm">
            <label>Predio destino</label>
            <select class="form-control-tm" id="select-destino-predio" onchange="onChangePredioDestino()">
                <option value="">— Elige el predio destino —</option>
                @foreach($predios as $predio)
                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group-tm" id="grupo-destino-lote" style="display:none">
            <label>
                Lote destino
                <span style="font-size:.75rem;font-weight:400;color:var(--texto-muted);margin-left:6px;">(opcional)</span>
            </label>
            <select class="form-control-tm" id="select-destino-lote" onchange="onChangeLoteDestino()">
                <option value="">— Sin lote —</option>
            </select>
        </div>

        <div class="form-group-tm" id="grupo-destino-potrero" style="display:none">
            <label>
                Potrero destino
                <span style="font-size:.75rem;font-weight:400;color:var(--texto-muted);margin-left:6px;">(opcional)</span>
            </label>
            <select class="form-control-tm" id="select-destino-potrero" onchange="onChangePotreroDestino()">
                <option value="">— Sin potrero —</option>
            </select>
        </div>

        <div class="banner-escenario banner-esc1" id="banner-esc1">
            <span class="icono material-symbols-outlined">swap_horiz</span>
            <div><div class="b-titulo">Traslado dentro del mismo predio</div>
            <div class="b-desc">Los animales cambian de lote y/o potrero dentro del mismo predio. Puedes asignar ambos, uno solo, o ninguno. El origen queda vacío con historial intacto.</div></div>
        </div>

        <div class="banner-escenario banner-esc2" id="banner-esc2">
            <span class="icono material-symbols-outlined">moving</span>
            <div><div class="b-titulo">Traslado a otro predio — sin lote</div>
            <div class="b-desc">Los animales llegarán a <span id="esc2-desc-ubic">ese predio</span> sin pertenecer a ningún lote. Activa la opción de abajo si quieres crear un lote en el destino.</div></div>
        </div>

        <div class="banner-escenario banner-esc3" id="banner-esc3">
            <span class="icono material-symbols-outlined">add_circle</span>
            <div><div class="b-titulo">Traslado a otro predio — con lote nuevo</div>
            <div class="b-desc">El sistema <strong>creará automáticamente un lote nuevo</strong> en el predio destino. El lote origen quedará vacío con historial intacto.</div></div>
        </div>

        <div id="toggle-lote-nuevo-wrap" style="display:none;margin-bottom:12px;">
            <button type="button" class="tipo-btn" id="btn-activar-lote-nuevo" onclick="activarLoteNuevo()"
                    style="justify-content:flex-start;gap:10px;padding:12px 16px;font-size:.85rem;">
                <span class="material-symbols-outlined">add_circle</span>
                Quiero crear un lote nuevo en el predio destino
            </button>
        </div>

        <div class="campo-lote-nuevo" id="campo-lote-nuevo">
            <div class="form-group-tm" style="margin-bottom:0">
                <label>
                    <span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle">add_circle</span>
                    Nombre del nuevo lote en el predio destino
                </label>
                <input type="text" class="form-control-tm" id="nombre-lote-nuevo"
                       placeholder="Ej: Vacas preñadas — La montañita">
                <div class="hint">
                    <span class="material-symbols-outlined" style="font-size:13px">info</span>
                    Nombre sugerido automáticamente. Puedes editarlo.
                </div>
            </div>
        </div>

        <div class="resumen-destino-visual" id="resumen-destino-visual">
            <span class="material-symbols-outlined" style="color:var(--naranja);font-size:18px">location_on</span>
            <span>Los animales llegarán a:</span>
            <strong id="rdv-predio" style="color:var(--naranja)"></strong>
            <span id="rdv-lote-wrap"    style="display:none"> › Lote: <strong id="rdv-lote"></strong></span>
            <span id="rdv-potrero-wrap" style="display:none"> › Potrero: <strong id="rdv-potrero"></strong></span>
        </div>
    </div>

    {{-- ══ PASO 3 — DETALLES ══ --}}
    <div class="card-traslado">
        <div class="card-titulo"><span class="badge-paso">Paso 3</span> Detalles del traslado</div>
        <div class="grid-2">
            <div class="form-group-tm">
                <label>Fecha del traslado</label>
                <input type="date" class="form-control-tm" id="fecha-traslado"
                       value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"
                       onchange="actualizarSugerenciaLote()">
            </div>
            <div class="form-group-tm">
                <label>Motivo del traslado (opcional)</label>
                <input type="text" class="form-control-tm" id="motivo-traslado"
                       placeholder="Ej: Cambio por saturación del potrero">
            </div>
        </div>
    </div>

    <button class="btn-ejecutar" onclick="abrirModalConfirmacion()">
        <span class="material-symbols-outlined">move_group</span>
        Revisar y ejecutar traslado
    </button>

</div>{{-- /traslado-wrapper --}}

@endsection
{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL — FUERA del @section('content') para que se renderice en el DOM  --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade modal-confirmar" id="modalConfirmar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    style="color:var(--texto-claro);font-weight:700;display:flex;align-items:center;gap:8px;">
                    <span class="material-symbols-outlined" style="color:var(--naranja)">warning</span>
                    Confirmar traslado masivo
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal"
                        style="color:var(--texto-muted);background:none;border:none;font-size:1.4rem;">&times;</button>
            </div>
            <div class="modal-body">
                <div class="resumen-box">
                    <div class="resumen-row">
                        <span class="label">Tipo de operación</span>
                        <span class="valor" id="res-escenario">—</span>
                    </div>
                    <div class="resumen-row">
                        <span class="label">Origen</span>
                        <span class="valor" id="res-origen">—</span>
                    </div>
                    <div class="resumen-row">
                        <span class="label">Destino</span>
                        <span class="valor" id="res-destino">—</span>
                    </div>
                    <div class="resumen-row" id="res-row-lote-nuevo" style="display:none">
                        <span class="label">Lote a crear</span>
                        <span class="valor verde" id="res-lote-nuevo">—</span>
                    </div>
                    <div class="resumen-row">
                        <span class="label">Fecha</span>
                        <span class="valor" id="res-fecha">—</span>
                    </div>
                    <div class="resumen-row">
                        <span class="label">Motivo</span>
                        <span class="valor" id="res-motivo">—</span>
                    </div>
                    <div class="resumen-row">
                        <span class="label" style="font-weight:700">Animales a trasladar</span>
                        <span class="valor naranja" id="res-total">—</span>
                    </div>
                    <div class="resumen-row" id="res-row-excluidos" style="display:none">
                        <span class="label" style="font-weight:700">Quedan en origen</span>
                        <span class="valor rojo" id="res-excluidos">—</span>
                    </div>
                    <div class="resumen-row" id="res-row-motivo-excl" style="display:none">
                        <span class="label">Motivo exclusión</span>
                        <span class="valor" id="res-motivo-excl" style="font-style:italic;opacity:.8">—</span>
                    </div>
                </div>
                <p style="margin-top:14px;font-size:.83rem;color:var(--texto-muted);line-height:1.5">
                    Esta acción registrará el movimiento en el historial de cada animal.
                    <strong style="color:var(--texto-claro)">No se puede deshacer.</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-sm-tm secundario" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn-sm-tm primario" onclick="ejecutarTraslado()">✅ Confirmar traslado</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SCRIPTS                                                                --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ════════════════════════════════════════════════════════════════
// DATOS BLADE → JS
// ════════════════════════════════════════════════════════════════
const LOTES_DATA    = @json($lotes);
const POTREROS_DATA = @json($potreros);

// ════════════════════════════════════════════════════════════════
// ESTADO GLOBAL
// ════════════════════════════════════════════════════════════════
const E = {
    origenTipo:           'lote',
    origenId:             null,
    origenNombre:         '',
    origenPredioId:       null,
    destinoPredioId:      null,
    destinoPredioNombre:  '',
    destinoLoteId:        null,
    destinoLoteNombre:    '',
    destinoPotreroId:     null,
    destinoPotreroNombre: '',
    escenario:            null,
    todosAnimales:        [],
    animalesSelIds:       new Set(),
};

// ════════════════════════════════════════════════════════════════
// PAGINACIÓN Y BÚSQUEDA
// ════════════════════════════════════════════════════════════════
let PAG = {
    paginaActual:  1,
    porPagina:     20,      // 0 = todos
    terminoBusq:   '',
    animalesFiltrados: [],  // subset tras la búsqueda
};

function onBuscar(termino) {
    PAG.terminoBusq  = termino.toLowerCase().trim();
    PAG.paginaActual = 1;
    document.getElementById('btn-limpiar-busqueda').style.display = termino ? 'block' : 'none';
    aplicarFiltroYPaginar();
}

function limpiarBusqueda() {
    document.getElementById('buscador-animales').value = '';
    document.getElementById('btn-limpiar-busqueda').style.display = 'none';
    PAG.terminoBusq  = '';
    PAG.paginaActual = 1;
    aplicarFiltroYPaginar();
}

function cambiarPorPagina(val) {
    PAG.porPagina    = parseInt(val);
    PAG.paginaActual = 1;
    aplicarFiltroYPaginar();
}

function aplicarFiltroYPaginar() {
    const t = PAG.terminoBusq;

    // Filtrar
    PAG.animalesFiltrados = t
        ? E.todosAnimales.filter(a =>
            (a.codigo  || '').toLowerCase().includes(t) ||
            (a.nombre  || '').toLowerCase().includes(t) ||
            (a.sexo    || '').toLowerCase().includes(t) ||
            (a.lote    || '').toLowerCase().includes(t) ||
            (a.potrero || '').toLowerCase().includes(t)
          )
        : [...E.todosAnimales];

    // Info de resultado de búsqueda
    const resEl = document.getElementById('buscador-resultado');
    if (t) {
        resEl.textContent = `${PAG.animalesFiltrados.length} resultado${PAG.animalesFiltrados.length !== 1 ? 's' : ''}`;
    } else {
        resEl.textContent = '';
    }

    renderPagina();
}

function renderPagina() {
    const total    = PAG.animalesFiltrados.length;
    const pp       = PAG.porPagina === 0 ? total : PAG.porPagina;
    const totalPag = pp > 0 ? Math.ceil(total / pp) : 1;

    // Clampar página actual
    if (PAG.paginaActual > totalPag) PAG.paginaActual = totalPag || 1;

    const inicio = (PAG.paginaActual - 1) * pp;
    const fin    = pp === 0 ? total : Math.min(inicio + pp, total);
    const slice  = PAG.animalesFiltrados.slice(inicio, fin);

    // Renderizar filas
    let html = '';
    if (slice.length === 0) {
        html = `<tr><td colspan="7" class="sin-resultados">
            <span class="material-symbols-outlined" style="font-size:28px;display:block;margin-bottom:6px;opacity:.4">search_off</span>
            No se encontraron animales con ese criterio.
        </td></tr>`;
    } else {
        slice.forEach(a => {
            const sel      = E.animalesSelIds.has(a.id_animal);
            const sexoCls  = (a.sexo || '').toLowerCase() === 'macho' ? 'macho' : 'hembra';
            const filaCls  = sel ? '' : 'deseleccionado';
            html += `<tr class="${filaCls}" id="fila-${a.id_animal}">
                <td><input type="checkbox" class="cb-animal" value="${a.id_animal}"
                           ${sel ? 'checked' : ''}
                           onchange="toggleAnimal(${a.id_animal}, this.checked)"></td>
                <td><strong>${a.codigo}</strong></td>
                <td>${a.nombre}</td>
                <td><span class="badge-sexo ${sexoCls}">${a.sexo || '—'}</span></td>
                <td>${a.predio}</td>
                <td>${a.lote    || '—'}</td>
                <td>${a.potrero || '—'}</td>
            </tr>`;
        });
    }
    document.getElementById('preview-tbody').innerHTML = html;

    // Info paginación
    document.getElementById('paginacion-info').textContent =
        total === 0 ? '' : `Mostrando ${inicio + 1}–${fin} de ${total} animales`;

    // Botones paginación
    renderBotonesPag(totalPag);
}

function renderBotonesPag(totalPag) {
    const wrap = document.getElementById('paginacion-btns');
    if (totalPag <= 1) { wrap.innerHTML = ''; return; }

    const actual = PAG.paginaActual;
    let html = '';

    html += `<button class="btn-pag" onclick="irPagina(${actual - 1})" ${actual === 1 ? 'disabled' : ''}>‹</button>`;

    // Rango de páginas a mostrar: hasta 5 alrededor de la actual
    let desde = Math.max(1, actual - 2);
    let hasta  = Math.min(totalPag, actual + 2);
    if (actual <= 3)                   hasta  = Math.min(5, totalPag);
    if (actual >= totalPag - 2)        desde  = Math.max(1, totalPag - 4);

    if (desde > 1) html += `<button class="btn-pag" onclick="irPagina(1)">1</button>`;
    if (desde > 2) html += `<span style="color:var(--texto-muted);padding:0 4px">…</span>`;

    for (let i = desde; i <= hasta; i++) {
        html += `<button class="btn-pag ${i === actual ? 'activo' : ''}" onclick="irPagina(${i})">${i}</button>`;
    }

    if (hasta < totalPag - 1) html += `<span style="color:var(--texto-muted);padding:0 4px">…</span>`;
    if (hasta < totalPag)     html += `<button class="btn-pag" onclick="irPagina(${totalPag})">${totalPag}</button>`;

    html += `<button class="btn-pag" onclick="irPagina(${actual + 1})" ${actual === totalPag ? 'disabled' : ''}>›</button>`;

    wrap.innerHTML = html;
}

function irPagina(n) {
    const total = PAG.animalesFiltrados.length;
    const pp    = PAG.porPagina === 0 ? total : PAG.porPagina;
    const max   = pp > 0 ? Math.ceil(total / pp) : 1;
    PAG.paginaActual = Math.max(1, Math.min(n, max));
    renderPagina();
}

// ════════════════════════════════════════════════════════════════
// PASO 1 — ORIGEN
// ════════════════════════════════════════════════════════════════
function seleccionarOrigenTipo(tipo) {
    E.origenTipo = tipo; E.origenId = null;
    document.getElementById('btn-origen-lote').classList.toggle('selected', tipo === 'lote');
    document.getElementById('btn-origen-potrero').classList.toggle('selected', tipo === 'potrero');

    const sel   = document.getElementById('select-origen');
    const datos = tipo === 'lote' ? LOTES_DATA : POTREROS_DATA;
    sel.innerHTML = `<option value="">— Elige un ${tipo} —</option>`;
    datos.forEach(item => {
        const p = item.predio?.nombre_predio || 'N/A';
        sel.innerHTML += `<option value="${item.id}" data-predio-id="${item.predio_id}" data-nombre="${item.nombre}">
            ${item.nombre} (${p}) — ${item.animales_count} animales</option>`;
    });
    document.getElementById('label-origen').textContent = `Seleccionar ${tipo} de origen`;
    ocultarPreview();
    resetDestino();
}

function cargarPreviewAnimales() {
    const sel = document.getElementById('select-origen');
    const opt = sel.options[sel.selectedIndex];
    E.origenId       = sel.value || null;
    E.origenNombre   = opt?.dataset?.nombre   || '';
    E.origenPredioId = opt?.dataset?.predioId || null;

    if (!E.origenId) { ocultarPreview(); return; }

    fetch(`{{ route('traslado.masivo.preview') }}?tipo=${E.origenTipo}&id=${E.origenId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success || data.total === 0) {
            ocultarPreview();
            mostrarAlerta('warning', `No hay animales vivos en este ${E.origenTipo}.`);
            return;
        }
        E.todosAnimales  = data.animales;
        E.animalesSelIds = new Set(data.animales.map(a => a.id_animal));

        // Resetear paginación/búsqueda al cargar nuevo origen
        PAG.paginaActual  = 1;
        PAG.terminoBusq   = '';
        document.getElementById('buscador-animales').value = '';
        document.getElementById('btn-limpiar-busqueda').style.display = 'none';
        document.getElementById('buscador-resultado').textContent = '';

        aplicarFiltroYPaginar();
        actualizarContador();
        document.getElementById('preview-box').style.display = 'block';
        actualizarPaso(2);
        ocultarAlerta();
        calcularEscenario();
    })
    .catch(() => mostrarAlerta('error', 'Error al cargar los animales del origen.'));
}

function toggleAnimal(id, checked) {
    if (checked) E.animalesSelIds.add(id);
    else         E.animalesSelIds.delete(id);
    const fila = document.getElementById(`fila-${id}`);
    if (fila) fila.className = checked ? '' : 'deseleccionado';
    actualizarContador();
    actualizarCheckboxMaestro();
    actualizarPanelExcluidos();
}

function seleccionarTodos(checked) {
    // Actúa sobre los animales FILTRADOS actualmente
    const alcance = PAG.terminoBusq ? PAG.animalesFiltrados : E.todosAnimales;
    alcance.forEach(a => {
        if (checked) E.animalesSelIds.add(a.id_animal);
        else         E.animalesSelIds.delete(a.id_animal);
    });
    document.getElementById('cb-todos').checked = checked;
    aplicarFiltroYPaginar();     // re-render con nuevos estados
    actualizarContador();
    actualizarPanelExcluidos();
}

function actualizarCheckboxMaestro() {
    const cbTodos = document.getElementById('cb-todos');
    if (!cbTodos) return;
    const total = E.todosAnimales.length;
    const sel   = E.animalesSelIds.size;
    cbTodos.checked       = sel === total;
    cbTodos.indeterminate = sel > 0 && sel < total;
}

function actualizarContador() {
    const total = E.todosAnimales.length;
    const sel   = E.animalesSelIds.size;
    const el    = document.getElementById('contador-sel');
    el.textContent = `${sel} de ${total} seleccionados`;
    el.className   = 'contador-sel' + (sel === 0 ? ' cero' : sel < total ? ' parcial' : '');
}

function actualizarPanelExcluidos() {
    const excluidos = E.todosAnimales.filter(a => !E.animalesSelIds.has(a.id_animal));
    const panel     = document.getElementById('panel-excluidos');
    if (excluidos.length === 0) { panel.style.display = 'none'; return; }
    panel.style.display = 'block';
    document.getElementById('badge-excluidos').textContent = `${excluidos.length} animales`;
    document.getElementById('lista-excluidos-tags').innerHTML = excluidos.map(a =>
        `<span class="tag-excluido">
            <span class="material-symbols-outlined" style="font-size:13px">close</span>
            ${a.codigo} — ${a.nombre}
        </span>`).join('');
}

function ocultarPreview() {
    document.getElementById('preview-box').style.display = 'none';
    E.todosAnimales  = []; E.animalesSelIds = new Set();
    PAG.animalesFiltrados = [];
}

// ════════════════════════════════════════════════════════════════
// PASO 2 — DESTINO
// ════════════════════════════════════════════════════════════════
function onChangePredioDestino() {
    const sel = document.getElementById('select-destino-predio');
    E.destinoPredioId    = sel.value || null;
    E.destinoPredioNombre = sel.options[sel.selectedIndex]?.text || '';
    E.destinoLoteId = null; E.destinoLoteNombre = '';
    E.destinoPotreroId = null; E.destinoPotreroNombre = '';

    ocultarBanners();
    document.getElementById('campo-lote-nuevo').style.display       = 'none';
    document.getElementById('toggle-lote-nuevo-wrap').style.display = 'none';
    document.getElementById('btn-activar-lote-nuevo').classList.remove('selected');
    actualizarResumenDestino();

    if (!E.destinoPredioId) {
        document.getElementById('grupo-destino-lote').style.display    = 'none';
        document.getElementById('grupo-destino-potrero').style.display = 'none';
        return;
    }

    const lotesP    = LOTES_DATA.filter(l => l.predio_id == E.destinoPredioId);
    const potrerosP = POTREROS_DATA.filter(p => p.predio_id == E.destinoPredioId);

    const selL = document.getElementById('select-destino-lote');
    selL.innerHTML = '<option value="">— Sin lote —</option>';
    lotesP.length === 0
        ? selL.innerHTML += '<option value="" disabled style="opacity:.5">No hay lotes en este predio</option>'
        : lotesP.forEach(l => selL.innerHTML += `<option value="${l.id}" data-nombre="${l.nombre}">${l.nombre}</option>`);

    const selP = document.getElementById('select-destino-potrero');
    selP.innerHTML = '<option value="">— Sin potrero —</option>';
    potrerosP.length === 0
        ? selP.innerHTML += '<option value="" disabled style="opacity:.5">No hay potreros en este predio</option>'
        : potrerosP.forEach(p => selP.innerHTML += `<option value="${p.id}" data-nombre="${p.nombre}">${p.nombre}${p.area ? ' ('+p.area+' ha)' : ''}</option>`);

    document.getElementById('grupo-destino-lote').style.display    = 'block';
    document.getElementById('grupo-destino-potrero').style.display = 'block';
    calcularEscenario();
}

function onChangeLoteDestino() {
    const sel = document.getElementById('select-destino-lote');
    E.destinoLoteId    = sel.value || null;
    E.destinoLoteNombre = sel.options[sel.selectedIndex]?.dataset?.nombre || '';
    calcularEscenario(); actualizarResumenDestino();
}

function onChangePotreroDestino() {
    const sel = document.getElementById('select-destino-potrero');
    E.destinoPotreroId    = sel.value || null;
    E.destinoPotreroNombre = sel.options[sel.selectedIndex]?.dataset?.nombre || '';
    calcularEscenario(); actualizarResumenDestino();
}

function activarLoteNuevo() {
    const btn   = document.getElementById('btn-activar-lote-nuevo');
    const activo = btn.classList.toggle('selected');
    document.getElementById('campo-lote-nuevo').style.display = activo ? 'block' : 'none';
    if (activo) {
        E.escenario = 3; actualizarSugerenciaLote();
        ocultarBanners(); document.getElementById('banner-esc3').style.display = 'flex';
    } else {
        E.escenario = 2;
        ocultarBanners(); document.getElementById('banner-esc2').style.display = 'flex';
        actualizarDescBannerEsc2();
    }
    actualizarPaso(3);
}

function actualizarResumenDestino() {
    const el = document.getElementById('resumen-destino-visual');
    if (!E.destinoPredioId) { el.style.display = 'none'; return; }
    document.getElementById('rdv-predio').textContent = E.destinoPredioNombre;
    const lw = document.getElementById('rdv-lote-wrap');
    const pw = document.getElementById('rdv-potrero-wrap');
    if (E.destinoLoteId)    { document.getElementById('rdv-lote').textContent    = E.destinoLoteNombre;    lw.style.display = 'inline'; } else { lw.style.display = 'none'; }
    if (E.destinoPotreroId) { document.getElementById('rdv-potrero').textContent = E.destinoPotreroNombre; pw.style.display = 'inline'; } else { pw.style.display = 'none'; }
    el.style.display = 'flex';
}

// ════════════════════════════════════════════════════════════════
// ESCENARIOS
// ════════════════════════════════════════════════════════════════
function calcularEscenario() {
    if (!E.origenId || !E.destinoPredioId) {
        ocultarBanners();
        document.getElementById('campo-lote-nuevo').style.display       = 'none';
        document.getElementById('toggle-lote-nuevo-wrap').style.display = 'none';
        return;
    }
    const mismoPredio = parseInt(E.origenPredioId) === parseInt(E.destinoPredioId);
    ocultarBanners();
    document.getElementById('campo-lote-nuevo').style.display       = 'none';
    document.getElementById('toggle-lote-nuevo-wrap').style.display = 'none';
    document.getElementById('btn-activar-lote-nuevo').classList.remove('selected');

    if (mismoPredio) {
        E.escenario = 1;
        document.getElementById('banner-esc1').style.display = 'flex';
    } else {
        E.escenario = 2;
        document.getElementById('banner-esc2').style.display = 'flex';
        actualizarDescBannerEsc2();
        document.getElementById('toggle-lote-nuevo-wrap').style.display = 'block';
    }
    actualizarPaso(3);
}

function actualizarDescBannerEsc2() {
    let ubic = 'ese predio';
    if (E.destinoPotreroId) ubic = `el potrero <strong>${E.destinoPotreroNombre}</strong>`;
    document.getElementById('esc2-desc-ubic').innerHTML = ubic;
}

function actualizarSugerenciaLote() {
    const input  = document.getElementById('nombre-lote-nuevo');
    const fecha  = document.getElementById('fecha-traslado').value;
    const p      = fecha.split('-');
    const fFmt   = p.length === 3 ? `${p[2]}/${p[1]}/${p[0]}` : fecha;
    const sug    = `${E.origenNombre} - traslado ${fFmt}`;
    if (!input.value || input.dataset.sugerido === input.value) {
        input.value = sug; input.dataset.sugerido = sug;
    }
}

function ocultarBanners() {
    ['banner-esc1','banner-esc2','banner-esc3'].forEach(id =>
        document.getElementById(id).style.display = 'none');
}

function resetDestino() {
    E.destinoPredioId = null; E.destinoPredioNombre = '';
    E.destinoLoteId   = null; E.destinoLoteNombre   = '';
    E.destinoPotreroId = null; E.destinoPotreroNombre = '';
    E.escenario = null;
    document.getElementById('select-destino-predio').value           = '';
    document.getElementById('grupo-destino-lote').style.display      = 'none';
    document.getElementById('grupo-destino-potrero').style.display   = 'none';
    document.getElementById('campo-lote-nuevo').style.display        = 'none';
    document.getElementById('toggle-lote-nuevo-wrap').style.display  = 'none';
    document.getElementById('resumen-destino-visual').style.display  = 'none';
    ocultarBanners();
}

// ════════════════════════════════════════════════════════════════
// PASOS / ALERTAS
// ════════════════════════════════════════════════════════════════
function actualizarPaso(paso) {
    for (let i = 1; i <= 4; i++) {
        const el = document.getElementById(`paso-${i}-indicator`);
        if (!el) continue;
        el.classList.remove('active','done');
        if (i < paso) el.classList.add('done');
        else if (i === paso) el.classList.add('active');
    }
}
function mostrarAlerta(tipo, msg) {
    const el = document.getElementById('alerta-general');
    el.className = `alerta-tm ${tipo}`;
    el.innerHTML = `<span class="material-symbols-outlined" style="font-size:16px">info</span> ${msg}`;
    el.style.display = 'flex';
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
function ocultarAlerta() { document.getElementById('alerta-general').style.display = 'none'; }

// ════════════════════════════════════════════════════════════════
// MODAL CONFIRMACIÓN
// ════════════════════════════════════════════════════════════════
function abrirModalConfirmacion() {
    if (!E.origenId)                { mostrarAlerta('warning', 'Selecciona el origen.'); return; }
    if (E.animalesSelIds.size === 0){ mostrarAlerta('warning', 'Selecciona al menos un animal para trasladar.'); return; }
    if (!E.destinoPredioId)         { mostrarAlerta('warning', 'Selecciona el predio destino.'); return; }
    if (E.escenario === 3 && !document.getElementById('nombre-lote-nuevo').value.trim()) {
        mostrarAlerta('warning', 'Escribe el nombre del nuevo lote en el destino.'); return;
    }
    const excluidos = E.todosAnimales.filter(a => !E.animalesSelIds.has(a.id_animal));
    if (excluidos.length > 0 && !document.getElementById('motivo-exclusion').value.trim()) {
        mostrarAlerta('warning', 'Escribe el motivo por el que los animales desmarcados NO se trasladarán.'); return;
    }
    ocultarAlerta();

    const etiquetaEsc = {
        1: '<span class="badge-escenario badge-esc1">Mismo predio</span>',
        2: '<span class="badge-escenario badge-esc2">Otro predio — sin lote</span>',
        3: '<span class="badge-escenario badge-esc3">Otro predio — lote nuevo</span>',
    };
    let destinoTexto = E.destinoPredioNombre;
    if (E.destinoLoteId)    destinoTexto += ` › Lote: ${E.destinoLoteNombre}`;
    if (E.destinoPotreroId) destinoTexto += ` › Potrero: ${E.destinoPotreroNombre}`;
    if (!E.destinoLoteId && !E.destinoPotreroId) destinoTexto += ' (solo predio)';

    const selOrigen = document.getElementById('select-origen');
    document.getElementById('res-escenario').innerHTML  = etiquetaEsc[E.escenario];
    document.getElementById('res-origen').textContent   = selOrigen.options[selOrigen.selectedIndex]?.text || '—';
    document.getElementById('res-destino').textContent  = destinoTexto;
    document.getElementById('res-fecha').textContent    = document.getElementById('fecha-traslado').value;
    document.getElementById('res-motivo').textContent   = document.getElementById('motivo-traslado').value || '(sin motivo)';
    document.getElementById('res-total').textContent    = `${E.animalesSelIds.size} animales`;

    const rowLote = document.getElementById('res-row-lote-nuevo');
    if (E.escenario === 3) {
        document.getElementById('res-lote-nuevo').textContent = `"${document.getElementById('nombre-lote-nuevo').value.trim()}"`;
        rowLote.style.display = 'flex';
    } else { rowLote.style.display = 'none'; }

    if (excluidos.length > 0) {
        document.getElementById('res-excluidos').textContent   = `${excluidos.length} animales (quedan en origen)`;
        document.getElementById('res-motivo-excl').textContent = document.getElementById('motivo-exclusion').value.trim();
        document.getElementById('res-row-excluidos').style.display    = 'flex';
        document.getElementById('res-row-motivo-excl').style.display  = 'flex';
    } else {
        document.getElementById('res-row-excluidos').style.display    = 'none';
        document.getElementById('res-row-motivo-excl').style.display  = 'none';
    }

    $('#modalConfirmar').modal('show');
}

// ════════════════════════════════════════════════════════════════
// EJECUTAR
// ════════════════════════════════════════════════════════════════
function ejecutarTraslado() {
    $('#modalConfirmar').modal('hide');
    const excluidos = E.todosAnimales.filter(a => !E.animalesSelIds.has(a.id_animal));
    document.getElementById('loading-msg').textContent = E.escenario === 3
        ? 'Creando lote nuevo y trasladando animales...'
        : 'Trasladando animales...';
    document.getElementById('loading-overlay').style.display = 'flex';

    const payload = {
        _token:             '{{ csrf_token() }}',
        origen_tipo:        E.origenTipo,
        origen_id:          E.origenId,
        animales_ids:       Array.from(E.animalesSelIds),
        animales_excluidos: excluidos.map(a => a.id_animal),
        motivo_exclusion:   document.getElementById('motivo-exclusion').value.trim(),
        destino_predio_id:  E.destinoPredioId,
        destino_lote_id:    E.destinoLoteId    || '',
        destino_potrero_id: E.destinoPotreroId || '',
        nombre_lote_nuevo:  E.escenario === 3 ? document.getElementById('nombre-lote-nuevo').value.trim() : '',
        fecha_movimiento:   document.getElementById('fecha-traslado').value,
        motivo:             document.getElementById('motivo-traslado').value,
    };

    fetch('{{ route('traslado.masivo.store') }}', {
        method:  'POST',
        headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
        body:    JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('loading-overlay').style.display = 'none';
        if (data.success) {
            let extraHtml = '';
            if (data.escenario === 3 && data.lote_nuevo)
                extraHtml += `<p style="font-size:.85rem;color:#9AA0AB;margin-top:6px;">Lote creado: <strong style="color:#2ECC71">"${data.lote_nuevo.nombre}"</strong></p>`;
            if (data.excluidos > 0)
                extraHtml += `<p style="font-size:.85rem;color:#E74C3C;margin-top:4px;">${data.excluidos} animal(es) quedaron en origen con motivo registrado.</p>`;

            Swal.fire({
                icon: 'success', title: '¡Traslado exitoso!',
                html: `<p>${data.message}</p>${extraHtml}`,
                confirmButtonColor: '#E8792A', confirmButtonText: 'Ver historial de traslados',
                showCancelButton: true, cancelButtonText: 'Nuevo traslado', cancelButtonColor: '#3A3F4A',
            }).then(result => {
                window.location.href = result.isConfirmed
                    ? '{{ route('reportes.traslados.listado') }}'
                    : window.location.reload();
            });
        } else {
            mostrarAlerta('error', data.message || 'Error al realizar el traslado.');
        }
    })
    .catch(() => {
        document.getElementById('loading-overlay').style.display = 'none';
        mostrarAlerta('error', 'Error de conexión. Intenta de nuevo.');
    });
}

document.addEventListener('DOMContentLoaded', () => actualizarPaso(1));
</script>
@endsection