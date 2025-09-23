@extends('layouts')

@section('title')
    Predios
@endsection

@section('styles')
    <style>
        /* Estilos base */
        .dashboard-container {
            padding: 20px;
        }

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

        /* Encabezado y acciones */
        .card-header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 23px;
            font-weight: 700;
            color: #292929;
            margin: 0;
        }

        .card-subtitle {
            font-size: 14px;
            color: #767676;
            margin-top: 5px;
        }

        .actions-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        /* Búsqueda */
        .search-wrapper {
            flex: 1;
            max-width: 450px;
        }

        .search-input {
            display: flex;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .search-input input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            outline: none;
            font-size: 14px;
        }

        .search-input .search-icon {
            display: flex;
            align-items: center;
            padding: 0 15px;
            background-color: #f8f9fa;
            color: #E49B39;
        }

        /* Botones */
        .btn-action {
            padding: 10px 20px;
            background-color: #E49B39;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action:hover {
            background-color: #C97917;
            transform: translateY(-2px);
        }

        .btn-warning {
            background-color: transparent !important;
            color: #E49B39 !important;
            border: 1px solid #E49B39;
        }

        .btn-warning:hover {
            background-color: #FFF8F0;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Tabla y acordeón */
        .table-container {
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            padding: 14px 16px;
            text-align: left;
        border-bottom: 2px solid #E5E7EB;
            font-weight: 600;
            color: #4B5563;
            background-color: #F9FAFB;
        }

        .custom-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #E5E7EB;
            color: #111827;
        }

        .custom-table tr:hover {
            background-color: #FFF8F0;
        }

        /* Acordeón */
        .predio-details {
            background: #ffffff;
            padding: 0;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease-out;
        }

        .btn-action:hover {
            color: white !important;
        }

        .predio-details.active {
            max-height: 1000px;
            padding: 20px;
            margin-top: 10px;
            border-top: 1px solid #E5E7EB;
        }

        .predio-icon {
            width: 120px;
            height: 120px;
            background-color: #FFF8F0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
        }

        .predio-icon i {
            font-size: 48px;
            color: #E49B39;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-card {
            background-color: white;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #E5E7EB;
        }

        .info-card-title {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 8px;
        }

        .info-card-value {
            font-size: 16px;
            color: #111827;
            font-weight: 500;
        }

        .toggle-details {
            background: none;
            border: none;
            color: #E49B39;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .toggle-details:hover {
            background-color: #FFF8F0;
        }

        .toggle-details i {
            transition: transform 0.3s;
        }

        .toggle-details.active i {
            transform: rotate(180deg);
        }

        /* Badges */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background-color: #D1FAE5;
            color: #059669;
        }

        .badge-warning {
            background-color: #FEF3C7;
            color: #D97706;
        }

        /* Usuario cards */
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            border-radius: 6px;
            background-color: #F9FAFB;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4B5563;
            font-weight: 600;
            font-size: 14px;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 500;
            color: #111827;
        }

        .user-role {
            font-size: 12px;
            color: #6B7280;
        }
    </style>
@endsection

@section('content')


                <div class="card-custom">
                    <!-- Breadcrumb -->
                    <div class="header-grid">

                        @if ($user->role->name === 'propietario')
                            <div class="breadcrumb">
                                <a href="{{ route('inicio') }}">
                                    <h3 class="cumb no-active-tab">
                                        Inicio
                                    </h3>
                                </a>

                                <span class="material-symbols-outlined bread">
                                    chevron_forward
                                </span>
                            <a href="{{ route('caracterizacion') }}">
                                <h3 class="cumb no-active-tab"> Caracterización </h3>
                            </a>

                                <span class="material-symbols-outlined bread">
                                    chevron_forward
                                </span>
                                <h3 class="cumb active-tab"> Predios</h3>

                            </div>
                        @elseif ($user->role->name === 'admin')
                            <h3>Administrar caracterizaciones</h3>
                        @endif
                        <hr>

                    </div>

                    <!-- Actions Container -->
                    <div class="actions-container">
                        <div class="search-wrapper">
                            <div class="search-input">
                                <input type="text" id="searchInput" placeholder="Buscar por nombre, código o ubicación...">
                                <div class="search-icon">
                                    <span class="material-symbols-outlined">search</span>
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->role->name === 'admin' || auth()->user()->role->name === 'propietario')
                            <a href="{{ route('predios.create') }}" class="btn-action">
                                <span class="material-symbols-outlined">add_circle</span>
                                Crear Nuevo Predio
                            </a>
                        @endif
                    </div>

                    <!-- Table -->
                    <div class="table-container">
                        <table id="prediosList" class="custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">Info</th>
                                    <th>Código</th>
                                    <th>Nombre del Predio</th>
                                    <th>Ubicación</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; $currentUser = auth()->user(); @endphp
                                @foreach ($predios as $predio)
                                    <tr>
                                        <td>
                                            <button class="toggle-details" onclick="toggleDetails({{ $predio->id }})">
                                                <i class="material-symbols-outlined">expand_more</i>
                                            </button>
                                        </td>
                                        <td>{{ $predio->cod_predio ?? 'Sin código' }}</td>
                                        <td>{{ $predio->nombre_predio }}</td>
                                        <td>{{ $predio->municipio }}, {{ $predio->departamento }}</td>
                                        <td>
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('Secciones', $predio->id) }}" class="btn-action  ">
                                                    Secciones
                                                </a>

                                                @if ($currentUser->role->name === 'admin' || $currentUser->role->name === 'propietario')
                                                    <a href="{{ route('predios.edit', $predio->id) }}" class="btn-action mx-2">
                                                     Editar
                                                    </a>

                                                    @if ($currentUser->role->name === 'admin')
                                                        <form action="{{ route('predios.destroy', $predio->id) }}" method="POST"
                                                              id="formDelete{{ $predio->id }}" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn-action btn-danger btn-sm"
                                                                    onclick="confirmDelete({{ $predio->id }})">
                                                                <span class="material-symbols-outlined">delete</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="p-0">
                                            <div id="details-{{ $predio->id }}" class="predio-details">
                                                <div class="d-flex">
                                                    <div class="predio-icon">
                                                        <span class="material-symbols-outlined">agriculture</span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h4>{{ $predio->nombre_predio }}</h4>
                                                        <p class="text-muted">Codigo:{{ $predio->cod_predio }}</p>
                                                        <p class="text-muted">{{ $predio->departamento }} - {{ $predio->municipio }}</p>
                                                        <p class="text-muted">Creador: {{ $predio->createdBy->name }}</p>

                                                    </div>
                                                </div>

                                                <div class="info-grid">
                                                    <!-- Información General -->
                                                    <div class="info-card">
                                                        <div class="info-card-title">Información General</div>
                                                        <div class="info-card-value">
                                                            <p><strong>Creado:</strong> {{ $predio->created_at->format('d/m/Y') ?? 'Predio creado por aplicación' }}</p>
                                                           
<p><strong>Última actualización:</strong>
    {{ $predio->updated_at ? $predio->updated_at->format('d/m/Y') : 'Sin información de actualización' }}
</p>

                                                            <p><strong>Área total:</strong> {{ $predio->area_total ?? 'No especificada' }}</p>
                                                        </div>
                                                    </div>

                                                    <!-- Usuarios con Acceso -->
                                                    <div class="info-card">
                                                        <div class="info-card-title">Usuarios con Acceso</div>
                                                        <div class="info-card-value">
                                                            @foreach($predio->usuarios as $usuario)
                                                                <div class="user-card mb-2">
                                                                    <div class="user-avatar">
                                                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                                                    </div>
                                                                    <div class="user-info">
                                                                        <div class="user-name">{{ $usuario->name }}</div>
                                                                        <div class="user-role">{{ $usuario->role->name }}</div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <!-- Estadísticas -->
                                                    <div class="info-card">
                                                        <div class="info-card-title">Estadísticas</div>
                                                        <div class="info-card-value">
                                                            <p><strong>Total Animales:</strong> {{ $predio->total_animales ?? '0' }}</p>
                                                            <p><strong>Potreros:</strong> {{ $predio->total_potreros ?? '0' }}</p>
                                                            <p><strong>lotes:</strong> {{ $predio->total_lotes ?? '0' }}</p>
                                                        </div>
                                                    </div>

                                                    <!-- Estado y Certificaciones -->
                                                    <div class="info-card">
                                                        <div class="info-card-title">Estado y Certificaciones</div>
                                                        <div class="info-card-value">
                                                            <span class="badge badge-success">Certificado ICA</span>
                                                            <span class="badge badge-warning">En Proceso BPG</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Función para alternar los detalles
        function toggleDetails(predioId) {
            const detailsDiv = document.getElementById(`details-${predioId}`);
            const button = event.currentTarget;

            detailsDiv.classList.toggle('active');
            button.classList.toggle('active');
        }

        // Función para confirmar eliminación
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E49B39',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`formDelete${id}`).submit();
                }
            });
        }



        // Notificaciones Toast
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif
    </script>
@endsection