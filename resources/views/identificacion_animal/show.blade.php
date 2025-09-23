@extends('layouts')

@section('template_title')
    Manejo de Pastos, Potreros y Cercas
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion6', $predioId) }}">Censo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">IDENTIFICACIÓN ANIMAL</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <style>
        #content-container:before {
            content: '';
            display: block;
            height: 165px;
            width: 100%;
            position: absolute;
            background-color: #C29F77 !important;
            z-index: 0;
        }
    </style>




    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">
                @if ($IdentificacionAnimal)

                <form action="{{ route('identificacionAnimal.update', $identificacionAnimal->id) }}" method="POST" id="identificacionAnimalForm">
                    @csrf
                    @method('PUT') <!-- Método PUT para actualizar -->

                    <!-- Campo oculto para el ID del Predio -->
                    <input type="hidden" name="id_predio" id="id_predio" value="{{ $identificacionAnimal->id_predio }}">

                    <!-- Porcinos -->
                    <div>
                        <label for="porcinos_con">Porcinos con identificación</label>
                        <input type="number" class="form-control" name="porcinos_con" id="porcinos_con" value="{{ $identificacionAnimal->porcinos_con }}">
                    </div>

                    <div>
                        <label for="porcinos_sin">Porcinos sin identificación</label>
                        <input type="number" class="form-control" name="porcinos_sin" id="porcinos_sin" value="{{ $identificacionAnimal->porcinos_sin }}">
                    </div>

                    <div>
                        <label for="total_porcinos">Total porcinos</label>
                        <input type="number" class="form-control" name="total_porcinos" id="total_porcinos" value="{{ $totalPorcinos }}" readonly>
                    </div>

                    <!-- Bovinos -->
                    <div>
                        <label for="bovinos_con">Bovinos con identificación</label>
                        <input type="number" class="form-control" name="bovinos_con" id="bovinos_con" value="{{ $identificacionAnimal->bovinos_con }}">
                    </div>

                    <div>
                        <label for="bovinos_sin">Bovinos sin identificación</label>
                        <input type="number" class="form-control" name="bovinos_sin" id="bovinos_sin" value="{{ $identificacionAnimal->bovinos_sin }}">
                    </div>

                    <div>
                        <label for="total_bovinos">Total bovinos</label>
                        <input type="number" class="form-control" name="total_bovinos" id="total_bovinos" value="{{ $totalBovinos }}" readonly>
                    </div>

                    <!-- Bufalinos -->
                    <div>
                        <label for="bufalinos_con">Bufalinos con identificación</label>
                        <input type="number" class="form-control" name="bufalinos_con" id="bufalinos_con" value="{{ $identificacionAnimal->bufalinos_con }}">
                    </div>

                    <div>
                        <label for="bufalinos_sin">Bufalinos sin identificación</label>
                        <input type="number" class="form-control" name="bufalinos_sin" id="bufalinos_sin" value="{{ $identificacionAnimal->bufalinos_sin }}">
                    </div>

                    <div>
                        <label for="total_bufalinos">Total bufalinos</label>
                        <input type="number" class="form-control" name="total_bufalinos" id="total_bufalinos" value="{{ $totalBufalinos }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Actualizar</button>
                </form>


                @else
                <form action="{{ route('identificacionAnimal.store') }}" method="POST" id="identificacionAnimalForm">
                    @csrf
                    <!-- Campo oculto para el ID del Predio -->
                    <input type="hidden" name="id_predio" id="id_predio" value="{{ $predioId }}">

                    <!-- Porcinos -->
                    <div>
                        <label for="porcinos_con">Porcinos con identificación</label>
                        <input type="number" class="form-control" name="porcinos_con" id="porcinos_con" value="{{ old('porcinos_con') }}">
                    </div>

                    <div>
                        <label for="porcinos_sin">Porcinos sin identificación</label>
                        <input type="number" class="form-control" name="porcinos_sin" id="porcinos_sin" value="{{ old('porcinos_sin') }}">
                    </div>

                    <div>
                        <label for="total_porcinos">Total porcinos</label>
                        <input type="number" class="form-control" name="total_porcinos" id="total_porcinos" value="{{ $totalPorcinos }}" readonly>
                    </div>

                    <!-- Bovinos -->
                    <div>
                        <label for="bovinos_con">Bovinos con identificación</label>
                        <input type="number" class="form-control" name="bovinos_con" id="bovinos_con" value="{{ old('bovinos_con') }}">
                    </div>

                    <div>
                        <label for="bovinos_sin">Bovinos sin identificación</label>
                        <input type="number" class="form-control" name="bovinos_sin" id="bovinos_sin" value="{{ old('bovinos_sin') }}">
                    </div>

                    <div>
                        <label for="total_bovinos">Total bovinos</label>
                        <input type="number" class="form-control" name="total_bovinos" id="total_bovinos" value="{{ $totalBovinos }}" readonly>
                    </div>

                    <!-- Bufalinos -->
                    <div>
                        <label for="bufalinos_con">Bufalinos con identificación</label>
                        <input type="number" class="form-control" name="bufalinos_con" id="bufalinos_con" value="{{ old('bufalinos_con') }}">
                    </div>

                    <div>
                        <label for="bufalinos_sin">Bufalinos sin identificación</label>
                        <input type="number" class="form-control" name="bufalinos_sin" id="bufalinos_sin" value="{{ old('bufalinos_sin') }}">
                    </div>

                    <div>
                        <label for="total_bufalinos">Total bufalinos</label>
                        <input type="number" class="form-control" name="total_bufalinos" id="total_bufalinos" value="{{ $totalBufalinos }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Guardar</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('identificacionAnimalForm').addEventListener('submit', function(event) {
            // Porcinos
            var porcinosCon = parseInt(document.getElementById('porcinos_con').value) || 0;
            var porcinosSin = parseInt(document.getElementById('porcinos_sin').value) || 0;
            var totalPorcinos = parseInt(document.getElementById('total_porcinos').value) || 0;
            var sumaPorcinos = porcinosCon + porcinosSin;

            // Bovinos
            var bovinosCon = parseInt(document.getElementById('bovinos_con').value) || 0;
            var bovinosSin = parseInt(document.getElementById('bovinos_sin').value) || 0;
            var totalBovinos = parseInt(document.getElementById('total_bovinos').value) || 0;
            var sumaBovinos = bovinosCon + bovinosSin;

            // Bufalinos
            var bufalinosCon = parseInt(document.getElementById('bufalinos_con').value) || 0;
            var bufalinosSin = parseInt(document.getElementById('bufalinos_sin').value) || 0;
            var totalBufalinos = parseInt(document.getElementById('total_bufalinos').value) || 0;
            var sumaBufalinos = bufalinosCon + bufalinosSin;

            // Validar si las sumas coinciden con los totales
            if (sumaPorcinos !== totalPorcinos) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La suma de Porcinos con identificación y Porcinos sin identificación no coincide con el total.',
                });
                return;
            }

            if (sumaBovinos !== totalBovinos) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La suma de Bovinos con identificación y Bovinos sin identificación no coincide con el total.',
                });
                return;
            }

            if (sumaBufalinos !== totalBufalinos) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La suma de Bufalinos con identificación y Bufalinos sin identificación no coincide con el total.',
                });
                return;
            }
        });
    </script>



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
@endsection
