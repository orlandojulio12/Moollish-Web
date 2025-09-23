@extends('layouts')

@section('title')
    Moollish - Gestión de Insumos
@endsection

@section('styles')
<style>
    .card-custom {
        border-radius: 8px;
        background: white;
        padding: 30px;
        border: 1px solid #eaebef;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .bread {
        font-size: 28px;
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

    .grid-playground {
        display: grid;
        gap: 25px;
        padding: 15px;
        /* Configuración responsive por defecto (mobile) */
        grid-template-columns: repeat(1, 1fr);
    }

    /* Tablet - 2 columnas */
    @media (min-width: 768px) {
        .grid-playground {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Laptop - 3 columnas */
    @media (min-width: 1024px) {
        .grid-playground {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .grid-card-end {
        /* width: 100%;
        min-height: 200px;
        border: 1px solid #eaebef;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        background: white;
        color: #333;
        cursor: pointer;
        transition: 200ms cubic-bezier(0.075, 0.82, 0.165, 1);
        justify-content: center;
        text-align: center;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); */

        width: 100%;
    margin: 0;
    border: 1px solid #eaebef;
    border-radius: 8px;
    padding: 10px;
    display: flex
;
    flex-direction: column;
    background: white;
    color: rgba(228, 155, 57, 1);
    cursor: pointer;
    transition: 200ms cubic-bezier(0.075, 0.82, 0.165, 1);
    justify-content: center;
    text-align: center;
    align-items: center;

    }

    .grid-card-end:hover {
        transform: scale(1.03);
      /*   box-shadow: 0 5px 15px rgba(0,0,0,0.1); */
     /*    border-color: #e49b39; */
    }

    .title-card-end {
        font-size: 18px;
        font-weight: 700;
        color: #e49b39;
    }

    .light-text {
        font-size: 12px;
        font-weight: 300;
        color: #e49b39;
    }

    .description {
        color: #666;
        font-weight: 400;
        line-height: 1.5;
        margin: 10px 0 0;
    }

    .insumo-icon {
/*         background-image: url('../../img/inicio/inventarioOrange.png'); */
        background-position: center;
        background-size: contain;
        width: 80px;
        height: 80px;
        background-repeat: no-repeat;
    }

    .registro-insumo-icon {
 /*        background-image: url('../../img/inicio/registroOrange.png'); */
        background-position: center;
        background-size: contain;
        width: 80px;
        height: 80px;
        background-repeat: no-repeat;
    }

    .movimiento-icon {
    /*     background-image: url('../../img/inicio/movimientoOrange.png'); */
        background-position: center;
        background-size: contain;
        width: 80px;
        height: 80px;
        background-repeat: no-repeat;
    }

    .insumoEntrada-icon{
        background-image: url('../../img/inicio/insumoEntradaOrange.png');
        background-position: center;
        background-size: contain;
        width: 80px;
        height: 80px;
        background-repeat: no-repeat;
    }

    .insumoSalida-icon{
        background-image: url('../../img/inicio/insumoSalidaOrange.png');
        background-position: center;
        background-size: contain;
        width: 80px;
        height: 80px;
        background-repeat: no-repeat;
    }

    /* Icono genérico en caso de no tener los específicos */
    .inventario-orange {
    /*     background-image: url('../../img/inicio/inventarioOrange.png'); */
        background-position: center;
        background-size: contain;
        width: 80px;
        height: 80px;
        background-repeat: no-repeat;
    }
    .insumoRegistroIcon {
        background-image: url('../../img/inicio/insumosRegistroOrange.png');
        background-position: center;
        background-size: contain;
        width: 80px;
        height: 80px;
        background-repeat: no-repeat;


    }
    .listadoInsumoIcon {
        background-image: url('../../img/inicio/listadoInsumoOrange.png');
        background-position: center;
        background-size: contain;
        width: 80px;
        height: 80px;
        background-repeat: no-repeat;
    }
</style>
@endsection

@section('content')
    <div class="card-custom">
        <div class="header-grid">
            <div class="breadcrumb">
                <a href="{{ route('inicio') }}">
                    <h3 class="cumb no-active-tab">
                        Inicio
                    </h3>
                </a>

                <span class="material-symbols-outlined bread">
                    chevron_forward
                </span>
                <h3 class="cumb active-tab">Gestión de Insumos</h3>
            </div>
            <hr>
        </div>

        <div class="row">
            <div class="col-12">

                <p class="text-muted mb-4">Administra todos los insumos de tu finca</p>
            </div>
        </div>

        <div class="grid-playground">
            <a href="{{ route('insumos.registroForm') }}" style="text-decoration: none;">
                <div class="grid-card-end">
                    <span class="light-text">Insumos</span>
                    <span class="title-card-end">Registrar Insumo</span>
                    <div class="insumoRegistroIcon"></div>
                    <span class="description">
                        Registra un nuevo insumo agrícola o veterinario con todas sus características.
                    </span>
                </div>
            </a>


            <a href="{{ route('insumos.entrada') }}" style="text-decoration: none;">
                <div class="grid-card-end">
                    <span class="light-text">Insumos</span>
                    <span class="title-card-end">Entrada de Insumos</span>
                    <div class="insumoEntrada-icon"></div>
                    <span class="description">
                        Registra entradas de insumos para mantener un control preciso de tus insumos.
                    </span>
                </div>
            </a>
            <a href="{{ route('insumos.salida') }}" style="text-decoration: none;">
                <div class="grid-card-end">
                    <span class="light-text">Insumos</span>
                    <span class="title-card-end">Salida de Insumos</span>
                    <div class="insumoSalida-icon"></div>
                    <span class="description">
                        Registra salidas de insumos para mantener un control preciso de tus insumos.
                    </span>
                </div>
            </a>
            <a href="{{route('insumos.consulta')}}" style="text-decoration: none;">
                <div class="grid-card-end">
                    <span class="light-text">Insumos</span>
                    <span class="title-card-end">Inventario de Insumos</span>
                    <div class="listadoInsumoIcon"></div>
                    <span class="description">
                        Visualiza el inventario actual de insumos, consulta disponibilidad y reportes.
                    </span>
                </div>
            </a>
        </div>

        @if (session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
