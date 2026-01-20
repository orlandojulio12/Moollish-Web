@extends('layouts')

@section('title')
    Productivos
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

        .light-text {
            font-size: 11px;
            font-weight: 300;
        }

        .grid-playground {
            display: grid;
            gap: 15px;
            padding: 15px;
            grid-template-columns: repeat(2, 1fr);
        }

        @media (min-width: 768px) {
            .grid-playground {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .grid-playground {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .grid-card-end {
            width: 100%;
            border: 1px solid #eaebef;
            border-radius: 8px;
            min-height: 200px;
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

        .title-card-end {
            font-size: 16px;
            font-weight: 800;
        }

        .description {
            color: gray;
            font-weight: 400;
            line-height: 110%;
            margin-top: 10px;
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

        /* === iconos === */
        .animal-orange {
            background-image: url('../../img/inicio/vacasOrange.png');
            background-position: center;
            background-size: contain;
            width: 80px;
            height: 80px;
            background-repeat: no-repeat;
        }

        /* === selector predio === */
        .predio-selector {
            max-width: 350px;
            margin: 15px 0 25px 0;
        }

        .predio-selector select {
            width: 100%;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #eaebef;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')

    {{-- Toast errores --}}
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        </script>
    @endif

    {{-- Toast success --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        </script>
    @endif

    <div class="card-custom">

        {{-- Header / breadcrumb --}}
        <div class="header-grid">
            @if ($user->role->name === 'propietario')
                <div class="breadcrumb">
                    <a href="{{ route('inicio') }}">
                        <h3 class="cumb no-active-tab">Inicio</h3>
                    </a>
                    <span class="material-symbols-outlined bread">
                        chevron_forward
                    </span>
                     <a href="{{ route('listados') }}">
            <h3 class="cumb no-active-tab">Listados</h3>
        </a>
        <span class="material-symbols-outlined bread">chevron_forward</span>
                    <h3 class="cumb active-tab">Productivos</h3>
                </div>
            @elseif ($user->role->name === 'admin')
                <h3>Administrar Productivos</h3>
            @endif
            <hr>
        </div>

        {{-- Descripción --}}
        <span>Consulta y administra los grupos productivos de tu predio</span>

        {{-- Selector de predio --}}
        <div class="predio-selector">
            <form method="GET" action="{{ route('productivos.index') }}">
                <select name="predio_id" onchange="this.form.submit()">
                    <option value="">Seleccione un predio</option>

                    @foreach ($predios as $predio)
                        <option value="{{ $predio->id }}"
                            {{ $predioId == $predio->id ? 'selected' : '' }}>
                            {{ $predio->nombre_predio }} ({{ $predio->animales_count }} animales)
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- GRID PRODUCTIVOS --}}
        <div class="grid-playground">

            {{-- CRÍAS --}}
            <a href="{{ $predioId ? route('productivos.crias', ['predio_id' => $predioId]) : '#' }}"
               style="{{ !$predioId ? 'pointer-events:none;opacity:.5;' : '' }}">
                <div class="grid-card-end">
                    <span class="light-text">Productivos</span>
                    <span class="title-card-end">
                        Crías ({{ $contadores['crias'] ?? 0 }})
                    </span>
                    <div class="animal-orange"></div>
                    <span class="description">Animales en etapa de cría</span>
                </div>
            </a>

            {{-- LEVANTES MACHOS --}}
            <a href="{{ $predioId ? route('productivos.levantes.machos', ['predio_id' => $predioId]) : '#' }}"
               style="{{ !$predioId ? 'pointer-events:none;opacity:.5;' : '' }}">
                <div class="grid-card-end">
                    <span class="light-text">Productivos</span>
                    <span class="title-card-end">
                        Levantes M ({{ $contadores['levantes_m'] ?? 0 }})
                    </span>
                    <div class="animal-orange"></div>
                    <span class="description">Levantes machos</span>
                </div>
            </a>

            {{-- LEVANTES HEMBRAS --}}
            <a href="{{ $predioId ? route('productivos.levantes.hembras', ['predio_id' => $predioId]) : '#' }}"
               style="{{ !$predioId ? 'pointer-events:none;opacity:.5;' : '' }}">
                <div class="grid-card-end">
                    <span class="light-text">Productivos</span>
                    <span class="title-card-end">
                        Levantes H ({{ $contadores['levantes_h'] ?? 0 }})
                    </span>
                    <div class="animal-orange"></div>
                    <span class="description">Levantes hembras</span>
                </div>
            </a>

            {{-- NOVILLAS --}}
            <a href="{{ $predioId ? route('productivos.novillas.vientre', ['predio_id' => $predioId]) : '#' }}"
               style="{{ !$predioId ? 'pointer-events:none;opacity:.5;' : '' }}">
                <div class="grid-card-end">
                    <span class="light-text">Productivos</span>
                    <span class="title-card-end">
                        Novillas de vientre ({{ $contadores['novillas_vientre'] ?? 0 }})
                    </span>
                    <div class="animal-orange"></div>
                    <span class="description">Novillas reproductivas</span>
                </div>
            </a>

            {{-- TOROS --}}
            <a href="{{ $predioId ? route('productivos.toros', ['predio_id' => $predioId]) : '#' }}"
               style="{{ !$predioId ? 'pointer-events:none;opacity:.5;' : '' }}">
                <div class="grid-card-end">
                    <span class="light-text">Productivos</span>
                    <span class="title-card-end">
                        Toros ({{ $contadores['toros'] ?? 0 }})
                    </span>
                    <div class="animal-orange"></div>
                    <span class="description">Toros registrados</span>
                </div>
            </a>

            {{-- TORETES --}}
            <a href="{{ $predioId ? route('productivos.toretes.ceba', ['predio_id' => $predioId]) : '#' }}"
               style="{{ !$predioId ? 'pointer-events:none;opacity:.5;' : '' }}">
                <div class="grid-card-end">
                    <span class="light-text">Productivos</span>
                    <span class="title-card-end">
                        Toretes de ceba ({{ $contadores['toretes_ceba'] ?? 0 }})
                    </span>
                    <div class="animal-orange"></div>
                    <span class="description">Animales en ceba</span>
                </div>
            </a>

            {{-- VACAS PARIDAS --}}
            <a href="{{ $predioId ? route('productivos.vacas.paridas', ['predio_id' => $predioId]) : '#' }}"
               style="{{ !$predioId ? 'pointer-events:none;opacity:.5;' : '' }}">
                <div class="grid-card-end">
                    <span class="light-text">Productivos</span>
                    <span class="title-card-end">
                        Vacas paridas ({{ $contadores['vacas_paridas'] ?? 0 }})
                    </span>
                    <div class="animal-orange"></div>
                    <span class="description">Vacas con cría</span>
                </div>
            </a>

            {{-- VACAS SECAS --}}
            <a href="{{ $predioId ? route('productivos.vacas.secas', ['predio_id' => $predioId]) : '#' }}"
               style="{{ !$predioId ? 'pointer-events:none;opacity:.5;' : '' }}">
                <div class="grid-card-end">
                    <span class="light-text">Productivos</span>
                    <span class="title-card-end">
                        Vacas secas ({{ $contadores['vacas_secas'] ?? 0 }})
                    </span>
                    <div class="animal-orange"></div>
                    <span class="description">Vacas en descanso</span>
                </div>
            </a>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
