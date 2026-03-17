@extends('layouts')

@section('title')
    Preñeces
@endsection

@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
        }

        .bread {
            font-size: 28px !important;
            color: black;
        }

        .cumb {
            margin: 0px !important;
            align-content: center;
        }

        .breadcrumb {
            display: flex;
        }

        .active-tab {
            color: #dc7a00;
        }

        .no-active-tab:hover {
            color: #dc7a00;
            cursor: pointer;
            text-decoration: underline;
        }

        .col-sm-12 {
            overflow: auto;
        }

        .resumen-card {
            background: linear-gradient(135deg, #f8f9fa, #fff3e0);
            border: 1px solid #e49b39;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .resumen-card .valor {
            font-size: 2rem;
            font-weight: 800;
            color: #dc7a00;
        }

        .resumen-card .etiqueta {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }

        #loading-spinner {
            display: none;
            text-align: center;
            padding: 30px;
        }
    </style>
@endsection

@section('content')
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end',
                    showConfirmButton: false, timer: 3000, timerProgressBar: true,
                });
                Toast.fire({ icon: 'error', title: 'Error', text: '{{ session('error') }}' });
            });
        </script>
    @endif

    <div class="card-custom">
        <div class="header-grid">
            <div class="breadcrumb">
                <a href="{{ route('inicio') }}">
                    <h3 class="cumb no-active-tab">Inicio</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <a href="{{ route('listados') }}">
                    <h3 class="cumb no-active-tab">Listados</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <h3 class="cumb active-tab">Preñeces</h3>
            </div>
            <hr>
        </div>

        {{-- Filtros --}}
        <div class="row mb-4 g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Predio</label>
                <select id="select-predio" class="form-select">
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
                <select id="select-lote" class="form-select" disabled>
                    <option value="">Todos los lotes</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-warning text-white" onclick="consultarPreneces()">
                    <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;">search</span>
                    Consultar
                </button>
                <a id="btn-exportar" href="#" class="btn btn-success" style="display:none;" target="_blank">
                    <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;">download</span>
                    Exportar Excel
                </a>
            </div>
        </div>

        {{-- Resumen --}}
        <div id="resumen-section" style="display:none;" class="row mb-4 g-3">
            <div class="col-6 col-md-3">
                <div class="resumen-card">
                    <div class="valor" id="r-total">0</div>
                    <div class="etiqueta">Total Preñadas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="resumen-card">
                    <div class="valor" id="r-proximas">0</div>
                    <div class="etiqueta">Próximas 30 días</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="resumen-card">
                    <div class="valor" id="r-promedio">-</div>
                    <div class="etiqueta">Promedio días gestación</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="resumen-card">
                    <div class="valor" id="r-config">-</div>
                    <div class="etiqueta">Días gestación configurados</div>
                </div>
            </div>
        </div>

        {{-- Spinner --}}
        <div id="loading-spinner">
            <div class="spinner-border text-warning" role="status"></div>
            <p class="mt-2 text-muted">Cargando datos...</p>
        </div>

        {{-- Tabla --}}
        <div id="tabla-section" style="display:none;">
            <div class="table-responsive">
                <table id="tabla-preneces" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Lote</th>
                            <th>Tipo Concepción</th>
                            <th>Fecha Concepción</th>
                            <th>Días Transcurridos</th>
                            <th>% Gestación</th>
                            <th>Parto Proyectado</th>
                            <th>Días para Parto</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-preneces"></tbody>
                </table>
            </div>
        </div>

        <div id="sin-datos" style="display:none;" class="text-center py-5 text-muted">
            No se encontraron animales preñadas con los filtros indicados.
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let dtPreneces = null;

        document.getElementById('select-predio').addEventListener('change', function () {
            const option = this.options[this.selectedIndex];
            const loteSelect = document.getElementById('select-lote');
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

            document.getElementById('resumen-section').style.display = 'none';
            document.getElementById('tabla-section').style.display = 'none';
            document.getElementById('sin-datos').style.display = 'none';
            document.getElementById('btn-exportar').style.display = 'none';
        });

        function consultarPreneces() {
            const predioId = document.getElementById('select-predio').value;
            if (!predioId) {
                Swal.fire('Atención', 'Seleccione un predio para consultar.', 'warning');
                return;
            }
            const loteId = document.getElementById('select-lote').value;

            document.getElementById('loading-spinner').style.display = 'block';
            document.getElementById('tabla-section').style.display = 'none';
            document.getElementById('resumen-section').style.display = 'none';
            document.getElementById('sin-datos').style.display = 'none';
            document.getElementById('btn-exportar').style.display = 'none';

            let url = `/api/reportes/preneces?predio_id=${predioId}`;
            if (loteId) url += `&lote_id=${loteId}`;

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
                }
            })
                .then(r => r.json())
                .then(resp => {
                    document.getElementById('loading-spinner').style.display = 'none';

                    if (!resp.success) {
                        Swal.fire('Error', resp.message || 'Error al cargar datos', 'error');
                        return;
                    }

                    const resumen = resp.resumen;
                    document.getElementById('r-total').textContent    = resumen.total_prenadas;
                    document.getElementById('r-proximas').textContent  = resumen.proximas_a_parir_30_dias;
                    document.getElementById('r-promedio').textContent  = resumen.promedio_dias_gestacion ?? '-';
                    document.getElementById('r-config').textContent    = resumen.dias_gestacion_configurados;
                    document.getElementById('resumen-section').style.display = '';

                    if (resp.data.length === 0) {
                        document.getElementById('sin-datos').style.display = 'block';
                        return;
                    }

                    if (dtPreneces) {
                        dtPreneces.destroy();
                        dtPreneces = null;
                    }

                    const tbody = document.getElementById('tbody-preneces');
                    tbody.innerHTML = '';

                    resp.data.forEach(function (a) {
                        const diasParto = a.dias_para_parto;
                        let classDias = '';
                        if (diasParto !== null && diasParto >= 0 && diasParto <= 30)  classDias = 'text-danger fw-bold';
                        else if (diasParto !== null && diasParto >= 0 && diasParto <= 60) classDias = 'text-warning fw-bold';

                        const pct = a.porcentaje_gestacion ?? 0;
                        const barColor = pct >= 90 ? 'bg-danger' : (pct >= 70 ? 'bg-warning' : 'bg-success');

                        tbody.innerHTML += `<tr>
                            <td>${a.codigo}</td>
                            <td>${a.nombre}</td>
                            <td>${a.lote ?? '-'}</td>
                            <td>${a.tipo_concepcion ?? '-'}</td>
                            <td>${a.fecha_concepcion ?? '-'}</td>
                            <td>${a.dias_gestacion_transcurridos ?? '-'}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:8px;min-width:60px;">
                                        <div class="progress-bar ${barColor}" style="width:${Math.min(pct, 100)}%"></div>
                                    </div>
                                    <span style="font-size:12px;white-space:nowrap;">${pct ?? '-'}%</span>
                                </div>
                            </td>
                            <td>${a.parto_proyectado ?? '-'}</td>
                            <td class="${classDias}">${diasParto !== null ? diasParto + ' días' : '-'}</td>
                        </tr>`;
                    });

                    document.getElementById('tabla-section').style.display = 'block';

                    dtPreneces = $('#tabla-preneces').DataTable({
                        language: {
                            lengthMenu: 'Mostrar _MENU_ entradas',
                            emptyTable: 'Sin resultados',
                            info: 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                            infoEmpty: 'Mostrando 0 a 0 de 0 entradas',
                            infoFiltered: '(filtrado de _MAX_ total)',
                            search: 'Buscar:',
                            zeroRecords: 'Sin resultados encontrados',
                            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
                        }
                    });

                    const exportUrl = `/reportes/preneces/export?predio_id=${predioId}` + (loteId ? `&lote_id=${loteId}` : '');
                    const btnExportar = document.getElementById('btn-exportar');
                    btnExportar.href = exportUrl;
                    btnExportar.style.display = 'inline-flex';
                    btnExportar.style.alignItems = 'center';
                    btnExportar.style.gap = '4px';
                })
                .catch(function () {
                    document.getElementById('loading-spinner').style.display = 'none';
                    Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                });
        }
    </script>
@endsection
