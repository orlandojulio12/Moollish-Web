@extends('layouts')

@section('title')
    Caracterización
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
            min-height: 200px;

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

        .usuarios-orange {
            background-image: url('../../img/inicio/propietariosOrange.png');
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

        .predios-orange {
            background-image: url('../../img/inicio/prediosOrange.png');
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

        .muerte-orange {
            background-image: url('../../img/inicio/muerteOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        /* ficha_animalOrange.png */

        .ficha-animal-orange {
            background-image: url('../../img/inicio/ficha_animalOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        /* pesajes_lecheOrange.png */

        .pesaje-leche-orange {
            background-image: url('../../img/inicio/pesajes_lecheOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }
        .pesaje-orange {
            background-image: url('../../img/inicio/pesajeOrange.png');
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
                    <a href="{{ route('registros') }}">
                        <h3 class="cumb no-active-tab">
                            Registros
                        </h3>
                    </a>
                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <h3 class="cumb active-tab"> Produccion animal </h3>
                </div>
            <hr>
        </div>
        {{-- Grid completo cuando ya se han registrado todos los datos básicos --}}
        <div class="grid-end">
            <span>Maximiza la productividad y rentabilidad de tu explotación ganadera.</span>
            <div class="grid-playground">
                <a href="{{route('registro_peso')}}">

                    <div class="grid-card-end">
                        <span class="light-text">Produccion animal</span>
                        <span class="title-card-end">Pesaje</span>
                        <div class="pesaje-orange"></div>
                        <span class="description">
                            Registra y analiza el peso de tu ganado para optimizar su crecimiento y alimentación.
                        </span>
                    </div>
                </a>

                <a href="{{route('pesajeLeche.index')}}">
                    <div class="grid-card-end">
                        <span class="light-text">Produccion animal</span>
                        <span class="title-card-end">Pesaje de leche</span>
                        <div  class="pesaje-leche-orange"></div>
                        <span class="description">
                            Monitorea y revisa la producción de leche de cada vaca en tus predios.
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
