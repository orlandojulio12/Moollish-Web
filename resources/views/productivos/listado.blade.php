@extends('layouts')

@section('title')
    {{ $titulo }}
@endsection

@section('styles')
<style>
    /* === estilos base del sistema (NO TOCAR) === */
    .card-custom {
        border-radius: 8px;
        background: white;
        padding: 30px;
        border: 1px solid #eaebef;
    }

    .bread {
        font-size: 28px;
        color: black;
    }

    .cumb {
        margin: 0 !important;
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

    table {
        font-size: 14px;
    }

    .table thead th {
        border-bottom: 1px solid #eaebef;
        font-weight: 600;
    }

    /* === MODAL === */
    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-header {
        background: #f9fafb;
        border-bottom: 1px solid #eaebef;
    }

    .modal-title {
        font-weight: 700;
        color: #dc7a00;
    }

    .modal-body table th {
        width: 40%;
        color: #555;
        font-weight: 600;
    }

    .modal-body table td {
        color: #111;
    }

    .spinner-border {
        color: #dc7a00;
    }

    /* ========================================================== */
    /*                  ANTI-BLUR EXTREMO                        */
    /*  Sobrescribe TODO lo que pueda estar causando el blur     */
    /* ========================================================== */

    /* Fondo del modal (backdrop) → gris simple, SIN blur */
    .modal-backdrop,
    .modal-backdrop.show {
        background-color: rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        filter: none !important;
        opacity: 1 !important;
    }

    /* El modal en sí → sin blur */
    .modal,
    .modal.show,
    .modal.fade .modal-dialog {
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        filter: none !important;
    }

    /* Evita blur en el body y contenedores principales cuando modal está abierto */
    body.modal-open,
    body.modal-open *,
    body:has(.modal.show),
    body:has(.modal.show) *,
    .content,
    main,
    .main-content,
    .app,
    .wrapper,
    .container,
    .card-custom {
        filter: none !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        transform: none !important;
    }

    /* Ultra-sobrescritura final (casi imposible que pase por alto) */
    body.modal-open {
        filter: none !important;
        -webkit-filter: none !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
    }

    /* ========================================================== */
</style>
@endsection

@section('content')

<div class="card-custom">

    {{-- Breadcrumb --}}
    <div class="breadcrumb mb-3">
        <a href="{{ route('inicio') }}">
            <h3 class="cumb no-active-tab">Inicio</h3>
        </a>
        <span class="material-symbols-outlined bread">chevron_forward</span>
         <a href="{{ route('listados') }}">
            <h3 class="cumb no-active-tab">Listados</h3>
        </a>
        <span class="material-symbols-outlined bread">chevron_forward</span>
        <a href="{{ route('productivos.index') }}">
            <h3 class="cumb no-active-tab">Productivos</h3>
        </a>
        <span class="material-symbols-outlined bread">chevron_forward</span>
        <h3 class="cumb active-tab">{{ $titulo }}</h3>
    </div>

    <span class="mb-3 d-block">
        Listado de animales 
    </span>

    @if ($animales->count())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Sexo</th>
                    <th>Raza</th>
                    <th>Fecha nacimiento</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($animales as $animal)
                    <tr>
                        <td>{{ $animal->codigo }}</td>
                        <td>{{ $animal->nombre ?? '-' }}</td>
                        <td>{{ $animal->sexo }}</td>
                        <td>{{ $animal->raza ?? '-' }}</td>
                        <td>
                            {{ $animal->fecha_nacimiento
                                ? \Carbon\Carbon::parse($animal->fecha_nacimiento)->format('d/m/Y')
                                : '-' }}
                        </td>
                        <td class="text-end">
                            <button
                                class="btn btn-sm btn-outline-primary btn-ver-animal"
                                data-id="{{ $animal->id_animal }}"
                                data-predio="{{ request('predio_id') }}">
                                Ver
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $animales->withQueryString()->links() }}
    @else
        <div class="text-center py-5 text-muted">
            No hay registros
        </div>
    @endif
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalAnimal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detalle del animal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="animal-loading" class="text-center py-5">
                    <div class="spinner-border"></div>
                </div>

                <div id="animal-content" style="display:none;">
                    <table class="table table-sm">
                        <tr><th>Código</th><td id="a_codigo"></td></tr>
                        <tr><th>Nombre</th><td id="a_nombre"></td></tr>
                        <tr><th>Sexo</th><td id="a_sexo"></td></tr>
                        <tr><th>Fecha nacimiento</th><td id="a_fecha"></td></tr>
                        <tr><th>Lote</th><td id="a_lote"></td></tr>
                        <tr><th>Potrero</th><td id="a_potrero"></td></tr>
                        <tr><th>Estado productivo</th><td id="a_estado_prod"></td></tr>
                        <tr><th>Estado reproductivo</th><td id="a_estado_repr"></td></tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('click', function (e) {
    if (!e.target.closest('.btn-ver-animal')) return;

    e.preventDefault();

    const btn = e.target.closest('.btn-ver-animal');
    const id = btn.dataset.id;
    const predio = btn.dataset.predio;

    const modalEl = document.getElementById('modalAnimal');
    const modal = new bootstrap.Modal(modalEl);

    document.getElementById('animal-content').style.display = 'none';
    document.getElementById('animal-loading').style.display = 'block';

    modal.show();

    fetch(`/productivos/animal/${id}?predio_id=${predio}`)
        .then(res => res.json())
        .then(resp => { 
            const a = resp.data;

            document.getElementById('a_codigo').innerText = a.codigo;
            document.getElementById('a_nombre').innerText = a.nombre ?? '-';
            document.getElementById('a_sexo').innerText = a.sexo;
            document.getElementById('a_fecha').innerText = a.fecha_nacimiento ?? '-';
            document.getElementById('a_lote').innerText = a.lote ?? '-';
            document.getElementById('a_potrero').innerText = a.potrero ?? '-';
            document.getElementById('a_estado_prod').innerText = a.estado_productivo;
            document.getElementById('a_estado_repr').innerText = a.estado_reproductivo ?? '-';

            document.getElementById('animal-loading').style.display = 'none';
            document.getElementById('animal-content').style.display = 'block';
        })
        .catch(() => {
            modal.hide();
            Swal.fire('Error', 'No se pudo cargar la información', 'error');
        });
});
</script>
@endsection