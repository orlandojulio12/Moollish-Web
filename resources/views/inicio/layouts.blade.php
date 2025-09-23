@extends('layouts')

@section('title')
    Layout
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

        .grid-playground {
            display: flex;
            flex-wrap: wrap;
        }

        .title-card {
            font-size: 16px;
            font-weight: 800;
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

        /* End styles */

        .title-card-end {
            font-size: 16px;
            font-weight: 800;
        }

        .grid-card-end {
            border: 1px solid #eaebef;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            background: white;
            color: rgba(228, 155, 57, 1);
            width: 225px;
            margin: 15px 15px 0px 0px;
            cursor: pointer;
            transition: 200ms cubic-bezier(0.075, 0.82, 0.165, 1);
            justify-content: center;
            text-align: center;
            align-items: center;
        }

        .grid-card-end:hover {
            transform: scale(1.05);
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

        .description {
            color: gray;
            font-weight: 400;
            line-height: 110%;
            margin: 10px 0px 0px;

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
            @if ($user->role->name === 'propietario')
                <h3>Layout</h3>
            @elseif ($user->role->name === 'admin')
                <h3>Bienvenido, {{ $user->name }}. Iniciaste sesión como administrador</h3>
            @endif
            <hr>
        </div>
        {{-- Grid completo cuando ya se han registrado todos los datos básicos --}}
        <div class="grid-end">
            <span>Administra tu finca, al completo y sin restricciones!</span>
            <div class="grid-playground">
                <a href="{{ route('inventario.index') }}">
                    <div class="grid-card-end">
                        <span class="light-text">Inicio</span>
                        <span class="title-card-end">Caracterización</span>
                        <div class="caracterizacion-orange"></div>
                        <span class="description">
                            Caracteriza tus predios y configura tu entorno virtual
                        </span>
                    </div>
                </a>

                <div class="grid-card-end">
                    <span class="light-text">Inicio</span>
                    <span class="title-card-end">Economia</span>
                    <div class="economia-orange"></div>
                    <span class="description">
                        Mantén un control de tus gastos en tiempo real
                    </span>
                </div>

                <div class="grid-card-end">
                    <span class="light-text">Inicio</span>
                    <span class="title-card-end">Registros</span>
                    <div class="inventario-orange"></div>
                    <span class="description">
                        Registra todos los eventos de tus animales
                    </span>
                </div>

                <div class="grid-card-end">
                    <span class="light-text">Inicio</span>
                    <span class="title-card-end">Listados</span>
                    <div class="listados-orange"></div>
                    <span class="description">
                        Revisa tus listas, históricos y demás.
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
