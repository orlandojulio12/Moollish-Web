@extends('layouts')

@section('title')
    Registros
@endsection

@section('styles')
    <style>
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 30px;
            border: 1px solid #eaebef;
        }

        .light-text {
            font-size: 11px;
            font-weight: 300;
        }

        .grid-card {
            border: 2px solid #ffffff;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            background: linear-gradient(297deg, rgb(201 121 23) 0%, rgba(228, 155, 57, 1) 100%);
            color: white;
            width: 225px;
            margin: 15px 15px 0px 0px;
            cursor: pointer;
            transition: 200ms cubic-bezier(0.075, 0.82, 0.165, 1);
            justify-content: center;
            text-align: center;
            align-items: center;
        }


        .grid-card:hover {
            transform: scale(1.05);
            box-shadow: 1px 1px 1px #0003;
        }



        .title-card {
            font-size: 16px;
            font-weight: 800;
        }


        /* End styles */
        .grid-playground {
            display: grid;
            gap: 15px;
            padding: 15px;
            /* Configuración responsive por defecto (mobile) */
            grid-template-columns: repeat(2, 1fr);
        }

        /* Tablet - 3 columnas */
        @media (min-width: 768px) {
            .grid-playground {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Laptop - 4 columnas */
        @media (min-width: 1024px) {
            .grid-playground {
                grid-template-columns: repeat(4, 1fr);
            }
        }


        .title-card-end {
            font-size: 16px;
            font-weight: 800;
        }

        .description {
            color: gray;
            font-weight: 400;
            line-height: 110%;
            margin: 10px 0px 0px;

        }

        .grid-card-end {
            /* Elimina el ancho fijo y márgenes */
            width: 100%;
            margin: 0;
            min-height: 200px;

            /* Mantén el resto de estilos */
            border: 1px solid #eaebef;
            border-radius: 8px;
            padding: 10px;
            display: flex;
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
            transform: scale(1.05);
        }


        .novilla {
            background-image: url('../../img/inicio/novilla.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .potrero {
            background-image: url('../../img/inicio/potrero.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .veterinario {
            background-image: url('../../img/inicio/veterinario.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }


        .novilla-orange {
            background-image: url('../../img/inicio/novillaOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .potrero-orange {
            background-image: url('../../img/inicio/potreroOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .veterinario-orange {
            background-image: url('../../img/inicio/veterinarioOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .caracterizacion-orange {
            background-image: url('../../img/inicio/caracterizacionOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .economia-orange {
            background-image: url('../../img/inicio/economiaOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .listados-orange {
            background-image: url('../../img/inicio/listadosOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .inventario-orange {
            background-image: url('../../img/inicio/inventario_animalOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        /*  */

        .ubicacion-animal-orange {
            background-image: url('../../img/inicio/ubicacion_animalOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .animal-orange {
            background-image: url('../../img/inicio/vacasOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .reproduccion-animal-orange {
            background-image: url('../../img/inicio/reproduccion_animalOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .produccion-animal-orange {
            background-image: url('../../img/inicio/produccion_animalOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .inventario-fisico-orange {
            background-image: url('../../img/inicio/inventarioOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        .medicacion-orange {
            background-image: url('../../img/inicio/medicacionOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }


        .transacciones-orange {
            background-image: url('../../img/inicio/transaccionesOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
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
    </style>
@endsection
@section('content')
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}'
                });
            });
        </script>
    @endif
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
                    <h3 class="cumb active-tab">Registros</h3>
                </div>
            <hr>
        </div>
        {{-- Grid completo cuando ya se han registrado todos los datos básicos --}}
        <div class="grid-end">
            <span>Administra tu finca, al completo y sin restricciones!</span>
            <div class="grid-playground">
                <a href="{{ route('ubicacion.index') }}">
                    <div class="grid-card-end">
                        <span class="light-text">Registros</span>
                        <span class="title-card-end">Ubicación animal</span>
                        <div class="ubicacion-animal-orange"></div>
                        <span class="description">
                            Registra y consulta las ubicaciones de tus animales, desde predios y lotes hasta potreros.
                        </span>
                    </div>
                </a>
                <a href="{{ route('animales') }}">
                    <div class="grid-card-end">
                        <span class="light-text">Registros</span>
                        <span class="title-card-end">Animales</span>
                        <div class="animal-orange"></div>
                        <span class="description">
                            Gestiona el perfil individual de cada animal: raza, edad, peso, historial de salud y más.
                        </span>
                    </div>
                </a>
                <a href="{{ route('produccionAnimal') }}">

                    <div class="grid-card-end">
                        <span class="light-text">Registros</span>
                        <span class="title-card-end">Produccion animal</span>
                        <div class="produccion-animal-orange"></div>
                        <span class="description">
                            Controla y analiza la producción de leche, carne u otros productos derivados de tus animales.
                        </span>
                    </div>
                </a>
                <a href="{{ route('reproduccionAnimal') }}">

                    <div class="grid-card-end">
                        <span class="light-text">Registros</span>
                        <span class="title-card-end">Reproduccion animal</span>
                        <div class="reproduccion-animal-orange"></div>
                        <span class="description">
                            Lleva un registro detallado de los ciclos reproductivos, partos, inseminaciones y otros eventos
                            clave.
                        </span>
                    </div>
                </a>
                <a href="{{ route('gastos.show') }}">
                    <div class="grid-card-end">
                        <span class="light-text">Registros</span>
                        <span class="title-card-end">Transacciones</span>
                        <div class="transacciones-orange"></div>
                        <span class="description">
                            Registra todas las compras, ventas y movimientos de tu ganado.
                        </span>
                    </div>
                </a>
{{-- <a href="{{route('registro_peso')}}">
    <div class="grid-card-end">
        <span class="light-text">Registros</span>
        <span class="title-card-end">Peso animal</span>
        <div class="transacciones-orange"></div>
        <span class="description">
            Registra todas las compras, ventas y movimientos de tu ganado.
        </span>
    </div>
</a> --}}
              {{--   <a href="{{ route('insumos.registrar') }}">
                    <div class="grid-card-end">
                        <span class="light-text">Registros</span>
                        <span class="title-card-end">Gestión de Insumos</span>
                        <div class="inventario-orange"></div>
                        <span class="description">
                            Administra insumos agrícolas y veterinarios, controla inventario y registra aplicaciones.
                        </span>
                    </div>
                </a> --}}

                <a href="{{ route('inventarioFisico.index') }}">
                    <div class="grid-card-end">
                        <span class="light-text">Registros</span>
                        <span class="title-card-end">Inventario fisico</span>
                        <div class="inventario-fisico-orange"></div>
                        <span class="description">
                            Gestiona y registra tus inventarios fisicos,
                        </span>
                    </div>
                </a>


                <a href="{{ route('medicacion.index') }}">
                    <div class="grid-card-end">
                        <span class="light-text">Registros</span>
                        <span class="title-card-end">Medicacion</span>
                        <div class="medicacion-orange"></div>
                        <span class="description">
                            Gestiona y registra los medicamentos, dosis y programas de vacunación.
                        </span>
                    </div>
                </a>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
