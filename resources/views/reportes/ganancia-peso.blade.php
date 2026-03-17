@extends('layouts')

@section('title')
    Ganancia de Peso
@endsection

@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
        }

        .bread { font-size: 28px !important; color: black; }
        .cumb { margin: 0px !important; align-content: center; }
        .breadcrumb { display: flex; }
        .active-tab { color: #dc7a00; }
        .no-active-tab:hover { color: #dc7a00; cursor: pointer; text-decoration: underline; }
        .col-sm-12 { overflow: auto; }

        .resumen-card {
            background: linear-gradient(135deg, #f8f9fa, #fff3e0);
            border: 1px solid #e49b39;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .resumen-card .valor { font-size: 1.8rem; font-weight: 800; color: #dc7a00; }
        .resumen-card .etiqueta { font-size: 12px; color: #888; margin-top: 4px; }

        .loading-spinner-box { display: none; text-align: center; padding: 30px; }

        .nav-tabs .nav-link.active {
            color: #dc7a00;
            border-bottom: 2px solid #dc7a00;
            font-weight: 600;
        }
        .nav-tabs .nav-link { color: #555; }

        /* Chip del animal seleccionado */
        #gp-ind-animal-chip {
            display: none;
            align-items: center;
            gap: 8px;
            background: #fff3e0;
            border: 1px solid #e49b39;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 14px;
            color: #7a4000;
            width: fit-content;
        }
        #gp-ind-animal-chip .chip-close {
            cursor: pointer;
            font-size: 16px;
            color: #dc7a00;
            line-height: 1;
        }
        #gp-ind-animal-chip .chip-close:hover { color: #a05000; }

        /* Modal lista animales */
        #modal-animales-lista {
            max-height: 380px;
            overflow-y: auto;
        }
        #modal-animales-lista .animal-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 12px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 150ms;
        }
        #modal-animales-lista .animal-row:hover { background: #fff3e0; }
        #modal-animales-lista .animal-row .animal-info { font-size: 14px; }
        #modal-animales-lista .animal-row .animal-codigo { font-weight: 700; color: #333; }
        #modal-animales-lista .animal-row .animal-meta { font-size: 12px; color: #888; }
        #modal-animales-lista .animal-row .btn-sel {
            flex-shrink: 0;
            font-size: 12px;
            padding: 4px 12px;
        }
        .modal-spinner { display: none; text-align: center; padding: 30px; }
        .sin-animales { display: none; text-align: center; padding: 30px; color: #999; }
    </style>
@endsection

@section('content')
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 })
                    .fire({ icon: 'error', title: 'Error', text: '{{ session('error') }}' });
            });
        </script>
    @endif

    <div class="card-custom">
        <div class="header-grid">
            <div class="breadcrumb">
                <a href="{{ route('inicio') }}"><h3 class="cumb no-active-tab">Inicio</h3></a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <a href="{{ route('listados') }}"><h3 class="cumb no-active-tab">Listados</h3></a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <h3 class="cumb active-tab">Ganancia de Peso</h3>
            </div>
            <hr>
        </div>

        {{-- ── TABS ──────────────────────────────────────────────────── --}}
        <ul class="nav nav-tabs mb-4" id="gpTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-lote" type="button" role="tab">
                    Por Lote / Predio
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-individual" type="button" role="tab">
                    Individual
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ══════════════════════════════════════════════════════════
                 TAB 1 — POR LOTE
            ══════════════════════════════════════════════════════════ --}}
            <div class="tab-pane fade show active" id="tab-lote" role="tabpanel">

                <div class="row mb-4 g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Predio</label>
                        <select id="gp-select-predio" class="form-select">
                            <option value="">Seleccione un predio</option>
                            @foreach ($predios as $predio)
                                <option value="{{ $predio->id }}"
                                    data-lotes="{{ $predio->lotes->map(fn($l) => ['id' => $l->id, 'nombre' => $l->nombre])->toJson() }}">
                                    {{ $predio->nombre_predio }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Lote <span class="text-muted fw-normal">(opcional)</span></label>
                        <select id="gp-select-lote" class="form-select" disabled>
                            <option value="">Todos los lotes</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Período</label>
                        <select id="gp-select-meses" class="form-select">
                            <option value="3">3 meses</option>
                            <option value="6" selected>6 meses</option>
                            <option value="12">12 meses</option>
                            <option value="24">24 meses</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-warning text-white" onclick="consultarGPLote()">
                            <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;">search</span>
                            Consultar
                        </button>
                        <a id="gp-btn-exportar" href="#" class="btn btn-success" style="display:none;" target="_blank">
                            <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;">download</span>
                            Exportar Excel
                        </a>
                    </div>
                </div>

                {{-- Resumen --}}
                <div id="gp-resumen-lote" style="display:none;" class="row mb-4 g-3">
                    <div class="col-6 col-md-3">
                        <div class="resumen-card">
                            <div class="valor" id="gp-r-animales">-</div>
                            <div class="etiqueta">Total animales</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="resumen-card">
                            <div class="valor" id="gp-r-ganancia">-</div>
                            <div class="etiqueta">Ganancia total período (kg)</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="resumen-card">
                            <div class="valor" id="gp-r-meses">-</div>
                            <div class="etiqueta">Meses analizados</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="resumen-card">
                            <div class="valor" id="gp-r-desde">-</div>
                            <div class="etiqueta">Desde</div>
                        </div>
                    </div>
                </div>

                <div class="loading-spinner-box" id="gp-lote-spinner">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="mt-2 text-muted">Cargando datos...</p>
                </div>

                <div id="gp-lote-tabla" style="display:none;">
                    <div class="table-responsive">
                        <table id="tabla-gp-lote" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Lote</th>
                                    <th>Período</th>
                                    <th>Animales</th>
                                    <th>Peso Prom. (kg)</th>
                                    <th>Ganancia Prom. (kg)</th>
                                    <th>Ganancia Total Lote (kg)</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-gp-lote"></tbody>
                        </table>
                    </div>
                </div>

                <div id="gp-lote-sin-datos" style="display:none;" class="text-center py-5 text-muted">
                    No se encontraron datos de pesaje con los filtros indicados.
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════
                 TAB 2 — INDIVIDUAL
            ══════════════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-individual" role="tabpanel">

                <div class="row mb-4 g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Predio</label>
                        <select id="gp-ind-select-predio" class="form-select" onchange="onIndPredioChange()">
                            <option value="">Seleccione un predio</option>
                            @foreach ($predios as $predio)
                                <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Animal seleccionado</label>
                        <div>
                            <button type="button" class="btn btn-outline-warning" id="gp-ind-btn-modal"
                                onclick="abrirModalAnimales()" disabled>
                                <span class="material-symbols-outlined" style="font-size:17px;vertical-align:middle;">pets</span>
                                Seleccionar animal
                            </button>
                        </div>
                        {{-- Chip del animal seleccionado --}}
                        <div id="gp-ind-animal-chip" class="mt-2">
                            <span class="material-symbols-outlined" style="font-size:16px;">pets</span>
                            <span id="gp-ind-chip-texto"></span>
                            <span class="chip-close" onclick="limpiarAnimalSeleccionado()" title="Quitar">✕</span>
                        </div>
                        <input type="hidden" id="gp-animal-id">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Período</label>
                        <select id="gp-ind-meses" class="form-select">
                            <option value="3">3 meses</option>
                            <option value="6" selected>6 meses</option>
                            <option value="12">12 meses</option>
                            <option value="24">24 meses</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-warning text-white" onclick="consultarGPIndividual()">
                            <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;">search</span>
                            Consultar
                        </button>
                        <a id="gp-ind-exportar" href="#" class="btn btn-success" style="display:none;" target="_blank">
                            <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;">download</span>
                            Exportar Excel
                        </a>
                    </div>
                </div>

                {{-- Resumen individual --}}
                <div id="gp-ind-resumen" style="display:none;" class="row mb-4 g-3">
                    <div class="col-12 mb-1">
                        <div class="alert alert-warning py-2 mb-0" id="gp-ind-animal-info"></div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="resumen-card">
                            <div class="valor" id="gp-ind-r-inicial">-</div>
                            <div class="etiqueta">Peso inicial (kg)</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="resumen-card">
                            <div class="valor" id="gp-ind-r-final">-</div>
                            <div class="etiqueta">Peso final (kg)</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="resumen-card">
                            <div class="valor" id="gp-ind-r-total">-</div>
                            <div class="etiqueta">Ganancia total (kg)</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="resumen-card">
                            <div class="valor" id="gp-ind-r-diaria">-</div>
                            <div class="etiqueta">Ganancia diaria prom. (kg)</div>
                        </div>
                    </div>
                </div>

                <div class="loading-spinner-box" id="gp-ind-spinner">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="mt-2 text-muted">Cargando datos...</p>
                </div>

                <div id="gp-ind-tabla" style="display:none;">
                    <div class="table-responsive">
                        <table id="tabla-gp-ind" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Peso (kg)</th>
                                    <th>Ganancia (kg)</th>
                                    <th>Días entre pesajes</th>
                                    <th>Ganancia diaria (kg/día)</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-gp-ind"></tbody>
                        </table>
                    </div>
                </div>

                <div id="gp-ind-sin-datos" style="display:none;" class="text-center py-5 text-muted">
                    No se encontraron pesajes registrados en el período indicado.
                </div>
            </div>

        </div>{{-- /tab-content --}}
    </div>{{-- /card-custom --}}

    

@endsection
{{-- ══════════════════════════════════════════════════════════════
         MODAL — SELECCIONAR ANIMAL
    ══════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalSeleccionarAnimal" tabindex="-1" aria-labelledby="modalAnimalesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAnimalesLabel">
                        <span class="material-symbols-outlined" style="vertical-align:middle;">pets</span>
                        Seleccionar Animal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    {{-- Buscador --}}
                    <div class="p-3 border-bottom">
                        <input type="text" id="modal-buscar-animal" class="form-control"
                            placeholder="Buscar por código o nombre..."
                            oninput="filtrarAnimalesModal(this.value)">
                    </div>

                    {{-- Spinner --}}
                    <div class="modal-spinner" id="modal-animal-spinner">
                        <div class="spinner-border text-warning" role="status"></div>
                        <p class="mt-2 text-muted">Cargando animales...</p>
                    </div>

                    {{-- Sin animales --}}
                    <div class="sin-animales" id="modal-sin-animales">
                        <span class="material-symbols-outlined" style="font-size:40px;color:#ccc;">pets</span>
                        <p class="mt-2">No se encontraron animales.</p>
                    </div>

                    {{-- Lista --}}
                    <div id="modal-animales-lista"></div>
                </div>
                <div class="modal-footer">
                    <small class="text-muted me-auto" id="modal-animal-count"></small>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let dtGPLote = null;
        let dtGPInd  = null;
        let todosLosAnimales = []; // cache para filtrar sin nueva petición

        // ══════════════════════════════════════════════════════════
        // TAB LOTE
        // ══════════════════════════════════════════════════════════

        document.getElementById('gp-select-predio').addEventListener('change', function () {
            const option     = this.options[this.selectedIndex];
            const loteSelect = document.getElementById('gp-select-lote');
            loteSelect.innerHTML = '<option value="">Todos los lotes</option>';

            if (this.value) {
                const lotes = JSON.parse(option.dataset.lotes || '[]');
                lotes.forEach(function (lote) {
                    loteSelect.innerHTML += `<option value="${lote.id}">${lote.nombre}</option>`;
                });
                loteSelect.disabled = false;
            } else {
                loteSelect.disabled = true;
            }
            resetLoteUI();
        });

        function resetLoteUI() {
            document.getElementById('gp-resumen-lote').style.display   = 'none';
            document.getElementById('gp-lote-tabla').style.display     = 'none';
            document.getElementById('gp-lote-sin-datos').style.display = 'none';
            document.getElementById('gp-btn-exportar').style.display   = 'none';
        }

        function consultarGPLote() {
            const predioId = document.getElementById('gp-select-predio').value;
            if (!predioId) {
                Swal.fire('Atención', 'Seleccione un predio para consultar.', 'warning');
                return;
            }
            const loteId = document.getElementById('gp-select-lote').value;
            const meses  = document.getElementById('gp-select-meses').value;

            resetLoteUI();
            document.getElementById('gp-lote-spinner').style.display = 'block';

            let url = `/api/reportes/ganancia-peso?predio_id=${predioId}&meses=${meses}`;
            if (loteId) url += `&lote_id=${loteId}`;

            fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(resp => {
                    document.getElementById('gp-lote-spinner').style.display = 'none';

                    if (!resp.success) {
                        Swal.fire('Error', resp.message || 'Error al cargar datos', 'error');
                        return;
                    }

                    const resumen = resp.resumen ?? {};
                    document.getElementById('gp-r-animales').textContent = resumen.total_animales ?? '-';
                    document.getElementById('gp-r-ganancia').textContent = resumen.ganancia_total_periodo_kg ?? '-';
                    document.getElementById('gp-r-meses').textContent    = resumen.periodo_analizado_meses ?? meses;
                    document.getElementById('gp-r-desde').textContent    = resumen.fecha_desde ?? '-';
                    document.getElementById('gp-resumen-lote').style.display = '';

                    if (!resp.data || resp.data.length === 0) {
                        document.getElementById('gp-lote-sin-datos').style.display = 'block';
                        return;
                    }

                    if (dtGPLote) { dtGPLote.destroy(); dtGPLote = null; }

                    const tbody = document.getElementById('tbody-gp-lote');
                    tbody.innerHTML = '';
                    resp.data.forEach(function (lote) {
                        (lote.meses ?? []).forEach(function (mes) {
                            tbody.innerHTML += `<tr>
                                <td>${lote.lote_nombre}</td>
                                <td>${mes.periodo}</td>
                                <td>${mes.cantidad_animales}</td>
                                <td>${mes.peso_promedio_mes ?? '-'}</td>
                                <td>${mes.ganancia_promedio_kg ?? '-'}</td>
                                <td>${mes.ganancia_total_lote_kg ?? '-'}</td>
                            </tr>`;
                        });
                    });

                    document.getElementById('gp-lote-tabla').style.display = 'block';
                    dtGPLote = $('#tabla-gp-lote').DataTable({
                        language: {
                            lengthMenu: 'Mostrar _MENU_ entradas', emptyTable: 'Sin resultados',
                            info: 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                            infoEmpty: 'Mostrando 0 a 0 de 0 entradas',
                            infoFiltered: '(filtrado de _MAX_ total)', search: 'Buscar:',
                            zeroRecords: 'Sin resultados encontrados',
                            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
                        }
                    });

                    const exportUrl = `/reportes/ganancia-peso/export?predio_id=${predioId}&meses=${meses}` + (loteId ? `&lote_id=${loteId}` : '');
                    const btn = document.getElementById('gp-btn-exportar');
                    btn.href = exportUrl;
                    btn.style.cssText = 'display:inline-flex;align-items:center;gap:4px;';
                })
                .catch(function () {
                    document.getElementById('gp-lote-spinner').style.display = 'none';
                    Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                });
        }

        // ══════════════════════════════════════════════════════════
        // TAB INDIVIDUAL — predio change
        // ══════════════════════════════════════════════════════════

        function onIndPredioChange() {
            limpiarAnimalSeleccionado();
            const predioId = document.getElementById('gp-ind-select-predio').value;
            const btn = document.getElementById('gp-ind-btn-modal');
            btn.disabled = !predioId;
            todosLosAnimales = [];

            // Limpiar resultados anteriores
            document.getElementById('gp-ind-resumen').style.display   = 'none';
            document.getElementById('gp-ind-tabla').style.display     = 'none';
            document.getElementById('gp-ind-sin-datos').style.display = 'none';
            document.getElementById('gp-ind-exportar').style.display  = 'none';
        }

        function limpiarAnimalSeleccionado() {
            document.getElementById('gp-animal-id').value          = '';
            document.getElementById('gp-ind-chip-texto').textContent = '';
            document.getElementById('gp-ind-animal-chip').style.display = 'none';
        }

        // ══════════════════════════════════════════════════════════
        // MODAL — abrir y cargar animales
        // ══════════════════════════════════════════════════════════

        function abrirModalAnimales() {
            const predioId = document.getElementById('gp-ind-select-predio').value;
            if (!predioId) return;

            // Limpiar búsqueda anterior
            document.getElementById('modal-buscar-animal').value = '';
            document.getElementById('modal-animales-lista').innerHTML = '';
            document.getElementById('modal-sin-animales').style.display  = 'none';
            document.getElementById('modal-animal-count').textContent    = '';

            const modal = new bootstrap.Modal(document.getElementById('modalSeleccionarAnimal'));
            modal.show();

            // Si ya tenemos los animales en caché del mismo predio, solo renderizar
            if (todosLosAnimales.length > 0) {
                renderAnimalesModal(todosLosAnimales);
                return;
            }

            document.getElementById('modal-animal-spinner').style.display = 'block';

            fetch(`/reportes/animales-por-predio?predio_id=${predioId}`, {
                headers: { 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(resp => {
                    document.getElementById('modal-animal-spinner').style.display = 'none';
                    todosLosAnimales = resp.animales ?? [];
                    renderAnimalesModal(todosLosAnimales);
                })
                .catch(function () {
                    document.getElementById('modal-animal-spinner').style.display = 'none';
                    Swal.fire('Error', 'No se pudieron cargar los animales', 'error');
                });
        }

        function renderAnimalesModal(lista) {
            const container = document.getElementById('modal-animales-lista');
            const sinAnim   = document.getElementById('modal-sin-animales');
            const count     = document.getElementById('modal-animal-count');
            container.innerHTML = '';

            if (lista.length === 0) {
                sinAnim.style.display = 'block';
                count.textContent = '';
                return;
            }

            sinAnim.style.display = 'none';
            count.textContent     = lista.length + ' animal(es)';

            lista.forEach(function (a) {
                const nombre = a.nombre ? ' — ' + a.nombre : '';
                const meta   = [a.raza, a.sexo, a.lote_nombre ? 'Lote: ' + a.lote_nombre : ''].filter(Boolean).join(' · ');

                const row = document.createElement('div');
                row.className = 'animal-row';
                row.innerHTML = `
                    <div class="animal-info">
                        <div class="animal-codigo">${a.codigo}${nombre}</div>
                        ${meta ? `<div class="animal-meta">${meta}</div>` : ''}
                    </div>
                    <button class="btn btn-sm btn-warning text-white btn-sel">Seleccionar</button>`;

                row.querySelector('.btn-sel').addEventListener('click', function () {
                    seleccionarAnimal(a.id_animal, `${a.codigo}${nombre}`);
                });

                container.appendChild(row);
            });
        }

        function filtrarAnimalesModal(texto) {
            if (todosLosAnimales.length === 0) return;
            const q = texto.toLowerCase().trim();
            const filtrados = q
                ? todosLosAnimales.filter(a =>
                    a.codigo.toLowerCase().includes(q) ||
                    (a.nombre && a.nombre.toLowerCase().includes(q))
                  )
                : todosLosAnimales;
            renderAnimalesModal(filtrados);
        }

        function seleccionarAnimal(id, etiqueta) {
            document.getElementById('gp-animal-id').value           = id;
            document.getElementById('gp-ind-chip-texto').textContent = etiqueta;
            document.getElementById('gp-ind-animal-chip').style.cssText = 'display:flex;';

            bootstrap.Modal.getInstance(document.getElementById('modalSeleccionarAnimal')).hide();
        }

        // ══════════════════════════════════════════════════════════
        // CONSULTAR INDIVIDUAL
        // ══════════════════════════════════════════════════════════

        function consultarGPIndividual() {
            const animalId = document.getElementById('gp-animal-id').value;
            if (!animalId) {
                Swal.fire('Atención', 'Seleccione un animal usando el botón "Seleccionar animal".', 'warning');
                return;
            }
            const predioId = document.getElementById('gp-ind-select-predio').value;
            const meses    = document.getElementById('gp-ind-meses').value;

            document.getElementById('gp-ind-resumen').style.display   = 'none';
            document.getElementById('gp-ind-tabla').style.display     = 'none';
            document.getElementById('gp-ind-sin-datos').style.display = 'none';
            document.getElementById('gp-ind-exportar').style.display  = 'none';
            document.getElementById('gp-ind-spinner').style.display   = 'block';

            fetch(`/api/reportes/ganancia-peso?id_animal=${animalId}&meses=${meses}&predio_id=${predioId || 0}`, {
                headers: { 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(resp => {
                    document.getElementById('gp-ind-spinner').style.display = 'none';

                    if (!resp.success) {
                        Swal.fire('Error', resp.message || 'Error al cargar datos', 'error');
                        return;
                    }

                    const animal  = resp.animal  ?? {};
                    const resumen = resp.resumen ?? {};

                    document.getElementById('gp-ind-animal-info').innerHTML =
                        `<strong>${animal.codigo ?? ''}</strong>` +
                        (animal.nombre ? ` — ${animal.nombre}` : '') +
                        (animal.raza   ? ` &nbsp;|&nbsp; ${animal.raza}` : '') +
                        (animal.lote   ? ` &nbsp;|&nbsp; Lote: ${animal.lote}` : '');

                    document.getElementById('gp-ind-r-inicial').textContent = resumen.peso_inicial  ?? '-';
                    document.getElementById('gp-ind-r-final').textContent   = resumen.peso_final    ?? '-';
                    document.getElementById('gp-ind-r-total').textContent   = resumen.ganancia_total_kg ?? '-';
                    document.getElementById('gp-ind-r-diaria').textContent  = resumen.ganancia_diaria_promedio_kg ?? '-';
                    document.getElementById('gp-ind-resumen').style.display = '';

                    if (!resp.data || resp.data.length === 0) {
                        document.getElementById('gp-ind-sin-datos').style.display = 'block';
                        return;
                    }

                    if (dtGPInd) { dtGPInd.destroy(); dtGPInd = null; }

                    const tbody = document.getElementById('tbody-gp-ind');
                    tbody.innerHTML = '';
                    resp.data.forEach(function (row) {
                        const g   = row.ganancia_kg;
                        const cls = g !== null ? (g >= 0 ? 'text-success' : 'text-danger') : '';
                        tbody.innerHTML += `<tr>
                            <td>${row.fecha_pesaje}</td>
                            <td>${row.peso} kg</td>
                            <td class="${cls}">${g !== null ? (g >= 0 ? '+' : '') + g + ' kg' : '-'}</td>
                            <td>${row.dias_entre_pesajes ?? '-'}</td>
                            <td>${row.ganancia_diaria_kg !== null ? row.ganancia_diaria_kg + ' kg/día' : '-'}</td>
                        </tr>`;
                    });

                    document.getElementById('gp-ind-tabla').style.display = 'block';
                    dtGPInd = $('#tabla-gp-ind').DataTable({
                        language: {
                            lengthMenu: 'Mostrar _MENU_ entradas', emptyTable: 'Sin resultados',
                            info: 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                            infoEmpty: 'Mostrando 0 a 0 de 0 entradas',
                            infoFiltered: '(filtrado de _MAX_ total)', search: 'Buscar:',
                            zeroRecords: 'Sin resultados encontrados',
                            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
                        }
                    });

                    const exportUrl = `/reportes/ganancia-peso/export?id_animal=${animalId}&meses=${meses}&predio_id=${predioId || 0}`;
                    const btn = document.getElementById('gp-ind-exportar');
                    btn.href = exportUrl;
                    btn.style.cssText = 'display:inline-flex;align-items:center;gap:4px;';
                })
                .catch(function () {
                    document.getElementById('gp-ind-spinner').style.display = 'none';
                    Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                });
        }
    </script>
@endsection
