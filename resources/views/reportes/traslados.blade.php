@extends('layouts')

@section('title', 'Reporte de Traslados')

@section('page-header')
    <div class="page-header">
            <div class="breadcrumb">
                    <a href="{{ route('inicio') }}">
                        <h3 class="cumb no-active-tab">
                            Inicio
                        </h3>
                    </a>

                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <a href="{{ route('listados') }}">
                        <h3 class="cumb no-active-tab">
                            Listados
                        </h3>
                    </a>

                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                    <h3 class="cumb active-tab">Traslados</h3>
                </div>
    </div>
@endsection

@section('styles')
<style>
    .badge {
        color: black;
    }
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
    {{-- Mensajes de sesión --}}
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    {{-- Formulario de Filtros --}}
    <div class="card-custom">
        <h5>Filtrar Animales</h5>
        <form action="{{ route('reportes.traslados.listado') }}" method="GET" class="row">
            {{-- Filtro por Predio --}}
            <div class="col-md-3 mb-3">
                <label for="predio_id">Predio:</label>
                <select name="predio_id" id="predio_id" class="form-control">
                    <option value="">-- Todos --</option>
                    @foreach($predios as $predio)
                        <option value="{{ $predio->id }}"
                            {{ request('predio_id') == $predio->id ? 'selected' : '' }}>
                            {{ $predio->nombre_predio }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro por Lote --}}
            <div class="col-md-3 mb-3">
                <label for="lote_id">Lote:</label>
                <select name="lote_id" id="lote_id" class="form-control">
                    <option value="">-- Todos --</option>
                    @foreach($lotes as $lote)
                        <option value="{{ $lote->id }}"
                            {{ request('lote_id') == $lote->id ? 'selected' : '' }}>
                            {{ $lote->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro por Potrero --}}
            <div class="col-md-3 mb-3">
                <label for="potrero_id">Potrero:</label>
                <select name="potrero_id" id="potrero_id" class="form-control">
                    <option value="">-- Todos --</option>
                    @foreach($potreros as $potrero)
                        <option value="{{ $potrero->id }}"
                            {{ request('potrero_id') == $potrero->id ? 'selected' : '' }}>
                            {{ $potrero->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro por Animal --}}
            <div class="col-md-3 mb-3">
                <label for="animal_id">Animal:</label>
                <select name="animal_id" id="animal_id" class="form-control">
                    <option value="">-- Todos --</option>
                    @foreach($animalesFiltro as $animalFiltro)
                        <option value="{{ $animalFiltro->id_animal }}"
                            {{ request('animal_id') == $animalFiltro->id_animal ? 'selected' : '' }}>
                            {{ $animalFiltro->nombre ?? 'ID '.$animalFiltro->id_animal }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Botón de Filtrar --}}
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
<br>
    {{-- Tabla de Animales --}}
    <div class="card-custom ">
        <h5>Listado de Animales</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tabla-animales">
                <thead>
                    <tr>
                        <th>Nombre / Código</th>
                        <th>Sexo</th>
                        <th>Raza</th>
                        <th>Edad</th>
                        <th>Estado Productivo</th>
                        <th>Estado Reproductivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($animales as $animal)
                        <tr>
                            <td>
                                {{ $animal->nombre ?? 'ID '.$animal->id_animal }}
                                ({{ $animal->codigo ?? 's/cod' }})
                            </td>
                            <td>{{ $animal->sexo }}</td>
                            <td>{{ $animal->raza }}</td>
                            <td>
                                @php
                                    // Calcular edad en años
                                    if($animal->fecha_nacimiento) {
                                        $edad = \Carbon\Carbon::parse($animal->fecha_nacimiento)->age;
                                        echo $edad . ' años';
                                    } else {
                                        echo 'Desconocida';
                                    }
                                @endphp
                            </td>
                            <td>
                                {{-- Estado productivo actual --}}
                                {{ $animal->estadoProductivo->nombre ?? 'N/A' }}
                            </td>
                            <td>
                                {{-- Estado reproductivo actual --}}
                                {{ $animal->estadoReproductivo->nombre ?? 'N/A' }}
                            </td>
                            <td>
                                <button
                                    class="btn btn-sm btn-info btn-traslados"
                                    data-animal-id="{{ $animal->id_animal }}"
                                >
                                    <i class="fas fa-exchange-alt"></i> Traslados
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No se encontraron animales con los filtros seleccionados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


@endsection

@section( 'modal'
)
   {{-- Modal para mostrar Movimientos de un Animal --}}
   <div class="modal fade" id="modalMovimientos" tabindex="-1" aria-labelledby="movimientosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="movimientosLabel">Historial de Traslados</h5>
          <bua type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true" >&times;</span>
          </bua>
        </div>
        <div class="modal-body">
          {{-- Contenedor para los movimientos por tipo --}}
          <div id="movimientos-container">
              {{-- Secciones por tipo se llenarán vía AJAX --}}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- Bootstrap JS --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    {{-- FontAwesome --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    {{-- SweetAlert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function(){
            // Al hacer clic en el botón "Traslados"
            $('.btn-traslados').click(function() {
                let animalId = $(this).data('animal-id');

                // Petición AJAX para obtener movimientos
                $.ajax({
                    url: '{{ route("reportes.traslados.movimientosPorAnimal") }}',
                    method: 'GET',
                    data: { animal_id: animalId },
                    beforeSend: function() {
                        // Mostrar modal y un spinner de carga
                        $('#movimientos-container').html('<p>Cargando movimientos...</p>');
                        $('#modalMovimientos').modal('show');
                    },
                    success: function(response) {
                        if(response.success) {
                            // Construir HTML de las secciones por tipo
                            let html = buildMovimientosPorTipoHTML(response.movimientos);
                            $('#movimientos-container').html(html);
                        } else {
                            $('#movimientos-container').html('<p>' + response.message + '</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        $('#movimientos-container').html('<p>Error al cargar los movimientos.</p>');
                        Swal.fire('Error', 'No se pudo cargar el historial de traslados.', 'error');
                    }
                });
            });

            // Función que construye el HTML de movimientos por tipo
            function buildMovimientosPorTipoHTML(movimientosPorTipo) {
                let html = '';

                // Iterar sobre cada tipo de movimiento
                for (let tipo in movimientosPorTipo) {
                    if (movimientosPorTipo.hasOwnProperty(tipo)) {
                        let movs = movimientosPorTipo[tipo];
                        if (movs.length > 0) {
                            // Título por tipo
                            let tipoTitulo = capitalizeFirstLetter(tipo);
                            html += `<h5>${tipoTitulo}s</h5>`;

                            // Empezar la tabla
                            html += `
                                <table class="table table-bordered table-hover mb-4">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Traslado</th>
                                            <th>Motivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;

                            // Iterar sobre los movimientos
                            movs.forEach(mov => {
                                // Crear badges para origen y destino
                                let origenHtml = buildUbicacionBadge(mov.origen);
                                let destinoHtml = buildUbicacionBadge(mov.destino);

                                // Icono de flecha
                                let flecha = '<i class="fas fa-arrow-right mx-2"></i>';

                                // Combinar origen y destino con flecha
                                let trasladoHtml = origenHtml + flecha + destinoHtml;

                                // Formatear fecha
                                let fecha = mov.fecha_movimiento ? new Date(mov.fecha_movimiento).toLocaleDateString() : 'N/A';

                                html += `
                                    <tr>
                                        <td>${fecha}</td>
                                        <td>${trasladoHtml}</td>
                                        <td>${mov.motivo ?? 'N/A'}</td>
                                    </tr>
                                `;
                            });

                            html += `</tbody></table>`;
                        }
                    }
                }

                // Si no hay movimientos en ningún tipo
                if (html === '') {
                    html = '<p>No se encontraron movimientos para este animal.</p>';
                }

                return html;
            }

            // Función para poner un badge con color según el tipo de ubicación
            function buildUbicacionBadge(ubicacion) {
                if(!ubicacion) {
                    return '<span class="badge badge-secondary">No definida</span>';
                }

                let tipo = ubicacion.tipo;  // "predio", "lote", "potrero", "no_definida"
                let nombre = ubicacion.nombre;
                let badgeClass = 'badge-secondary';

                switch(tipo) {
                    case 'predio':
                        badgeClass = 'badge-primary';
                        break;
                    case 'lote':
                        badgeClass = 'badge-success';
                        break;
                    case 'potrero':
                        badgeClass = 'badge-warning';
                        break;
                    case 'no_definida':
                        badgeClass = 'badge-secondary';
                        break;
                    default:
                        badgeClass = 'badge-secondary';
                }

                return `<span class="badge ${badgeClass}">${nombre}</span>`;
            }

            // Función para capitalizar la primera letra
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>
@endsection
