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
                    <li class="breadcrumb-item active" aria-current="page">ABEJAS</li>
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
                @if ($censoExistente)
                <form action="{{ route('censo_abejas.update', $predioId) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de Abejas</th>
                                <th scope="col">Número de Apiarios</th>
                                <th scope="col">Número de Colmenas</th>
                                <th scope="col">Población Estimada</th>
                                <th scope="col">Realiza Trashumancia</th>
                                <th scope="col">Nombre Establecimiento Destino</th>
                                <th scope="col">Departamento</th>
                                <th scope="col">Municipio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($CensoAbejas as $index => $censo)
                                <tr>
                                    <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                    <td>
                                        <select name="id_tipo_abejas[]" class="form-control">
                                            @foreach($TipoAbejas as $tipo)
                                                <option value="{{ $tipo->id }}" {{ $tipo->id == $censo->id_tipo_abejas ? 'selected' : '' }}>
                                                    {{ $tipo->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control" name="num_apiarios[]" value="{{ $censo->num_apiarios }}" placeholder="0"></td>
                                    <td><input type="number" class="form-control" name="num_colmenas[]" value="{{ $censo->num_colmenas }}" placeholder="0"></td>
                                    <td><input type="text" class="form-control" name="poblacion_estimada[]" value="{{ $censo->poblacion_estimada }}" placeholder="Ingrese población"></td>
                                    <td><input type="text" class="form-control" name="realiz_trashumancia[]" value="{{ $censo->realiz_trashumancia }}" placeholder="Ingrese sí/no"></td>
                                    <td><input type="text" class="form-control" name="nom_estable_destino[]" value="{{ $censo->nom_estable_destino }}" placeholder="Nombre del destino"></td>
                                    <td>
                                        <select name="departamento[]" class="form-control">
                                            <option value="">Seleccione</option>
                                            <option value="Departamento 1" {{ $censo->departamento == 'Departamento 1' ? 'selected' : '' }}>Departamento 1</option>
                                            <option value="Departamento 2" {{ $censo->departamento == 'Departamento 2' ? 'selected' : '' }}>Departamento 2</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="municipio[]" class="form-control">
                                            <option value="">Seleccione</option>
                                            <option value="Municipio 1" {{ $censo->municipio == 'Municipio 1' ? 'selected' : '' }}>Municipio 1</option>
                                            <option value="Municipio 2" {{ $censo->municipio == 'Municipio 2' ? 'selected' : '' }}>Municipio 2</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>

               @else
                <form action="{{ route('censo_abejas.store') }}" method="POST">
                    @csrf

                    <table class="table">
                        <thead>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Tipo de Abejas</th>
                                        <th scope="col">Número de Apiarios</th>
                                        <th scope="col">Número de Colmenas</th>
                                        <th scope="col">Población Estimada</th>
                                        <th scope="col">Realiza Trashumancia</th>
                                        <th scope="col">Nombre Establecimiento Destino</th>
                                        <th scope="col">Departamento</th>
                                        <th scope="col">Municipio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <input type="hidden" name="id_predio" value="{{ $predioId }}">
                                        <td>
                                            <select name="id_tipo_abejas[]" class="form-control">
                                                <option value="">Seleccione</option>
                                                @foreach($TipoAbejas as $tipo)
                                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control num_apiarios" name="num_apiarios[]" placeholder="0"></td>
                                        <td><input type="number" class="form-control num_colmenas" name="num_colmenas[]" placeholder="0"></td>
                                        <td><input type="text" class="form-control poblacion_estimada" name="poblacion_estimada[]" placeholder="Ingrese población"></td>
                                        <td><input type="text" class="form-control realiz_trashumancia" name="realiz_trashumancia[]" placeholder="Ingrese sí/no"></td>
                                        <td><input type="text" class="form-control nom_estable_destino" name="nom_estable_destino[]" placeholder="Nombre del destino"></td>
                                        <td>
                                            <select id="departamento" class="form-control" name="departamento[]">
                                                <option value="" selected>Seleccione Departamento...</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="municipio" class="form-control" name="municipio[]">
                                                <option value="" selected>Seleccione Municipio...</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        fetch('https://api-colombia.com/api/v1/Department')
        .then(response => response.json())
        .then(data => {
            let selectDepartamento = document.getElementById('departamento');
            let selectCiudad = document.getElementById('municipio');

            data.forEach(item => {
                let option = document.createElement('option');
                option.text = item.name;
                option.value = item.name;
                option.dataset.id = item.id; // Usamos dataset para almacenar datos personalizados
                selectDepartamento.add(option);
            });

            selectDepartamento.addEventListener('change', function() {
                selectCiudad.innerHTML = '';
                let selectedOption = this.options[this.selectedIndex]; // Accedemos al option seleccionado
                let selectedDepId = selectedOption.dataset.id; // Accedemos a los datos personalizados

                fetch(
                        `https://api-colombia.com/api/v1/Department/${selectedDepId}/cities`
                    ) // Usamos el id seleccionado en la URL
                    .then(response => response.json())
                    .then(city => {
                        city.forEach(city => {
                            let option = document.createElement('option');
                            option.text = city.name;
                            option.value = city.name;
                            selectCiudad.add(option);
                        });
                    });
            });
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
