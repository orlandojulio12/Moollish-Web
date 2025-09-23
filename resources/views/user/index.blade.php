@extends('layouts')

@section('title')
    Gestión de Usuarios
@endsection

@section('styles')
    <style>
        /* Estilos generales (adaptados de Predios y ConsultaInsumos) */
        .card-custom {
            border-radius: 8px;
            background: white;
            padding: 20px 30px !important;
            border: 1px solid #eaebef;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /*  .bread {
            font-size: 28px !important;
            color: black;
            vertical-align: middle;
        } */

        /*   .cumb {
            margin: 0px !important;
            align-content: center;
            font-size: 22px;
            line-height: 28px;
            font-weight: 500;
        }
     */

        .no-active-tab:hover {
            color: #dc7a00;
            cursor: pointer;
            /* text-decoration: underline; */
            /* Opcional */
        }

        hr {
            border-top: 1px solid #91a1b6;

            /*    margin-top: 0;  */
            /* Pegado al breadcrumb */
            margin-bottom: 25px;
            /* Espacio después del hr */
        }

        /* Encabezado y acciones */
        .actions-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            /* Para responsiveness */
            gap: 15px;
        }

        /* Búsqueda */
        .search-wrapper {
            flex: 1;
            min-width: 250px;
            /* Ancho mínimo */
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

        .search-input input::placeholder {
            color: #9ca3af;
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
            color: white !important;
            /* Forzar color */
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            /* Evitar que el texto se rompa */
        }

        .btn-action:hover {
            background-color: #C97917;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-action .material-symbols-outlined {
            font-size: 20px;
            vertical-align: middle;
        }

        /* Botones específicos tabla */
        .btn-action.btn-sm {
            padding: 6px 12px;
            font-size: 13px;
            gap: 5px;
        }

        .btn-action.btn-sm .material-symbols-outlined {
            font-size: 18px;
        }

        .btn-warning {
            background-color: transparent !important;
            color: #E49B39 !important;
            border: 1px solid #E49B39;
        }

        .btn-warning:hover {
            background-color: #FFF8F0 !important;
            color: #C97917 !important;
        }

        .btn-danger {
            background-color: #e74c3c !important;
            /* Rojo más suave */
            color: white !important;
            border: 1px solid #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b !important;
            border-color: #c0392b;
        }

        /* Tabla */
        .table-container {
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        .custom-table th {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 2px solid #E5E7EB;
            /* Línea más gruesa para header */
            font-weight: 600;
            font-size: 13px;
            /* Ligeramente más pequeño */
            color: #4B5563;
            background-color: #F9FAFB;

            letter-spacing: 0.5px;
        }

        .custom-table td {
            padding: 14px 16px;
        /*     border-bottom: 1px solid #E5E7EB; */
            color: #374151;
            /* Color de texto más oscuro */
            font-size: 14px;
            vertical-align: middle;
        }

        .custom-table tbody tr:hover {
            background-color: #FFF8F0;
            /* Hover suave */
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .custom-table .material-symbols-outlined {
            font-size: 20px;
            vertical-align: middle;
        }

        /* Estilo para foto de perfil en tabla */
        .profile-pic-cell div {
            width: 40px;
            /* Más pequeño */
            height: 40px;
            background-size: cover;
            background-position: center;
            border-radius: 50%;
            background-color: #e2e6ea;
            /* Placeholder color */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 20px;
            /* Tamaño icono placeholder */
            overflow: hidden;
            /* Asegurar que la imagen no se salga */
        }

        .profile-pic-cell .material-symbols-outlined {
            font-size: 24px;
        }

        /* Badges para predios */
        .badge {
            padding: 4px 10px;
            /* Más padding horizontal */
            border-radius: 12px;
            /* Más redondeado */
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            line-height: 1.3;
        }

        .badge-secondary {
            /* Estilo base para predios */
            background-color: #e5e7eb;
            color: #4b5563;
        }

        /* Contenedor para predios si hay muchos */
        .predios-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            max-width: 250px;
            /* Limitar ancho para evitar celdas muy anchas */
        }


        /* Estilos Modal (si aplican, asegúrate que sean consistentes) */
        .modal-header .material-symbols-outlined {
            cursor: pointer;
            opacity: 0.7;
        }

        .modal-header .material-symbols-outlined:hover {
            opacity: 1;
        }

        /* Estilos mensajes de error/éxito */
        .alert-container {
            margin-bottom: 20px;
        }

        .error-container,
        .success-container {
            padding: 12px 15px;
            /* Más padding */
            border-radius: 6px;
            /* Bordes más suaves */
            display: flex;
            align-items: center;
            /* Alinear icono y texto */
            font-size: 14px;
            font-weight: 500;
            gap: 10px;
            /* Espacio entre icono y texto */
        }

        .error-container {
            border: 1px solid #f8b4b4;
            background: #fde8e8;
            color: #9a0202;
        }

        .success-container {
            border: 1px solid #bcf0c0;
            background: #e8fdef;
            color: #1c8b00;
        }

        .error-container .material-symbols-outlined,
        .success-container .material-symbols-outlined {
            font-size: 20px;
        }

        /* DataTable styles (para que no rompa el diseño) */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 15px 0;
            /* Añadir padding */
            font-size: 14px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 6px 12px;
            margin-left: 3px;
            border-radius: 6px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #E49B39 !important;
            border-color: #E49B39 !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
            border-radius: 6px;
            padding: 6px 10px;
            border: 1px solid #E5E7EB;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 6px;
            padding: 6px 10px;
            border: 1px solid #E5E7EB;
        }

        /* Estilos para el acordeón de detalles del usuario */
        .user-details {
            padding: 0 15px; /* Padding inicial reducido */
            background-color: #f0f2f542;
            border-top: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
            overflow: hidden;
            max-height: 0; /* Oculto por defecto, colapsa altura */
            transition: max-height 0.35s linear; /* Animación */
        }

        /* Expandir el div INTERNO cuando la FILA TR tiene la clase activa */
        .details-row.details-row-active .user-details {
            max-height: 1000px; /* Suficientemente grande para cualquier contenido */
            padding: 15px; /* Padding completo cuando está activo */
        }

        .toggle-user-details {
            background: none;
            border: none;
            color: #E49B39;
            cursor: pointer;
            padding: 4px;
            border-radius: 50%;
            transition: all 0.2s;
            display: inline-flex;
            /* Para alinear icono */
            align-items: center;
            justify-content: center;
        }

        form {
            margin: 0px;
        }

        .toggle-user-details:hover {
            background-color: #FFF8F0;
        }

        .toggle-user-details .material-symbols-outlined {
            transition: transform 0.3s;
            font-size: 22px;
            /* Ajustar tamaño icono */
        }

        .toggle-user-details.active .material-symbols-outlined {
            transform: rotate(180deg);
        }

        /* Estilos para el contenido del acordeón */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .details-section {
            background-color: white;
            border-radius: 6px;
            padding: 15px;
            border: 1px solid #e5e7eb;
        }

        .details-section-title {
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 12px;
            font-size: 15px;
            border-bottom: 1px solid #eee;
            /* Separador sutil */
            padding-bottom: 8px;
        }

        .details-section-content p,
        .details-section-content .badge {
            margin-bottom: 8px;
        }

        .details-section-content .predios-list-details {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .details-section-content .badge {
            margin-bottom: 0;
            /* Quitar margen inferior extra a los badges */
        }


        /* Ajustar padding de la primera celda para el botón */
        .custom-table td:first-child,
        .custom-table th:first-child {
            padding-left: 10px;
            padding-right: 5px;
            text-align: center;
        }

        .custom-table td:nth-child(2),
        /* Foto */
        .custom-table th:nth-child(2) {
            padding-left: 5px;
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

        /* --- ESTILOS PARA MODAL NATIVO --- */
        .native-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            /* Fondo oscuro semitransparente */
            z-index: 1040;
            /* Debajo del modal, encima del resto */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .native-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1050;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            /* Altura máxima total */
            overflow: hidden;
            /* El contenedor principal NO debe hacer scroll */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
            display: flex;
            /* CLAVE: Activar Flexbox */
            flex-direction: column;
            /* CLAVE: Apilar verticalmente */
        }

        #nativeUserModal,
        #nativeEditUserModal {
            overflow: auto;

        }

        .native-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            flex-shrink: 0;
            /* CLAVE: No encoger el header */
        }

        .native-modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .native-modal-close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            line-height: 1;
            opacity: 0.7;
        }

        .native-modal-close-btn:hover {
            opacity: 1;
        }

        .native-modal-body {
            padding: 1.5rem;
            overflow-y: auto;
            /* CLAVE: Permitir scroll SÓLO en el body */
            flex: 1 1 auto;
            /* CLAVE: Hacer que el body ocupe el espacio disponible */
            min-height: 0;
            /* Ayuda al cálculo de flex en algunos casos con overflow */
        }

        .native-modal-footer {
            display: flex;
            justify-content: flex-end;
            padding: 1rem 1.5rem;
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
            gap: 0.5rem;
            flex-shrink: 0;
            /* CLAVE: No encoger el footer */
        }

        /* Estado visible */
        .native-modal-overlay.show,
        .native-modal.show {
            opacity: 1;
            visibility: visible;
        }

        .native-modal.show {
            transform: translate(-50%, -50%) scale(1);
        }

        /* Clases del formulario dentro del modal nativo */
        .native-modal-body .row {
            margin-bottom: 1rem;
        }

        .native-modal-body .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        /* ... otros estilos necesarios para el form ... */

        /* Estilos para predios seleccionados en modal */
        #selected-predios-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
            min-height: 50px;
        }

        .selected-predio-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            background-color: #e5e7eb;
            /* Mismo color que badges en tabla */
            color: #4b5563;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.3;
        }

        .remove-predio-btn {
            margin-left: 8px;
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            font-size: 16px;
            /* Tamaño del icono 'x' */
            font-weight: bold;
            opacity: 0.7;
        }

        .remove-predio-btn:hover {
            color: #dc3545;
            /* Rojo al pasar el ratón */
            opacity: 1;
        }

        #no-predios-selected {
            font-size: 0.9em;
            color: #6c757d;
        }

        /* --- FIN ESTILOS PREDIOS MODAL --- */
    </style>
@endsection

@section('content')

    <div class="card-custom">
        {{-- Notificaciones --}}
        <div class="alert-container">
            @if (session('error'))
                <div class="error-container">
                    <span class="material-symbols-outlined">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="success-container">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="error-container">
                    <span class="material-symbols-outlined">error</span>
                    <div>
                        <span>Por favor corrige los siguientes errores:</span>
                        <ul style="margin: 5px 0 0 15px; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        {{-- Breadcrumb --}}
        @php $currentUser = auth()->user(); @endphp

        {{--     <div class="breadcrumb">
                <a href="{{ route('inicio') }}" class="no-active-tab">Inicio</a>
                <span class="material-symbols-outlined bread">chevron_right</span>
                <a href="{{ route('caracterizacion') }}" class="no-active-tab">Caracterización</a>
                <span class="material-symbols-outlined bread">chevron_right</span>
                <span class="cumb active-tab">Usuarios</span>

        </div> --}}

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
            <h3 class="cumb active-tab"> Usuarios</h3>

        </div>
        <hr>

        {{-- Título (Opcional, ya cubierto por breadcrumb) --}}
        {{-- <h2 class="card-title mb-3">Gestión de Usuarios</h2> --}}

        {{-- Acciones --}}
        <div class="actions-container">
            <div class="search-wrapper">
                <div class="search-input">
                    <input type="text" id="searchInput" placeholder="Buscar por nombre, correo, rol o documento...">
                    <div class="search-icon">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                </div>
            </div>
            {{-- Botón Crear Usuario (restaurado) --}}
            <button type="button" class="btn-action" id="openCreateUserModal">
                <span class="material-symbols-outlined">add_circle</span>
                {{ __('Crear Usuario') }}
            </button>
            {{-- Fin Botón Crear Usuario --}}
        </div>

        {{-- Tabla de Usuarios --}}
        <div class="table-container">
            <table id="usersTable" class="custom-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th> {{-- Columna para botón toggle --}}
                        <th style="width: 60px;">Foto</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Identificación</th>
                        {{-- <th>Predios Asignados</th> --}} {{-- Ocultamos la columna de predios aquí --}}
                        <th style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        {{-- Fila principal del usuario --}}
                        <tr>
                            <td> {{-- Celda para botón toggle --}}
                                <button class="toggle-user-details" data-target="details-row-{{ $user->id }}">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </button>
                            </td>
                            <td class="profile-pic-cell">
                                <div>
                                    @if ($user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                            alt="Foto de {{ $user->name }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span class="material-symbols-outlined">person</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->name ?? 'N/A' }}</td>
                            <td>
                                {{ $user->tipo_documento ? ucfirst($user->tipo_documento) . ':' : '' }}
                                {{ $user->documento ?? 'N/A' }}
                            </td>
                            {{-- <td style="max-width: 200px; white-space: normal;"> {{-- Ocultada, se muestra en detalles --}}
                            {{--  <div class="predios-list">
                                    @forelse ($user->predios as $predio)
                                        <span class="badge badge-secondary">{{ $predio->nombre_predio }}</span>
                                    @empty
                                        <span class="text-muted" style="font-size: 13px;">Ninguno</span>
                                    @endforelse
                                </div>
                            </td> --}}
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    {{-- Editar: Si soy yo MISMO o si YO CREÉ a este usuario --}}
                                    @if (auth()->id() === $user->id || auth()->id() === $user->created_by)
                                        {{-- Convertido a botón para modal AJAX --}}
                                        <button type="button" class="btn-action btn-sm btn-warning edit-user-btn"
                                            title="Editar Usuario" data-user-id="{{ $user->id }}">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                    @endif

                                    {{-- Eliminar: Si YO CREÉ a este usuario Y NO soy yo mismo --}}
                                    @if (auth()->id() === $user->created_by && auth()->id() !== $user->id)
                                        {{-- Añadida clase delete-user-btn y quitado onclick --}}
                                        <button type="button" class="btn-action btn-sm btn-danger delete-user-btn"
                                            title="Borrar Usuario" data-user-id="{{ $user->id }}">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                        {{-- Formulario oculto para el borrado --}}
                                        <form id="delete-form-{{ $user->id }}"
                                            action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        {{-- Fila oculta para los detalles --}}
                         {{-- Añadido ID y clase a la fila, quitado style del div --}}
                        <tr id="details-row-{{ $user->id }}" class="details-row">
                            <td colspan="7" class="p-0">
                                {{-- ID del div ya no necesita controlar display --}}
                                <div id="details-{{ $user->id }}" class="user-details">
                                    <div class="details-grid">
                                        {{-- Sección Información Básica --}}
                                        <div class="details-section">
                                            <div class="details-section-title">Información Básica</div>
                                            <div class="details-section-content">
                                                <p><strong>Nombre:</strong> {{ $user->name }}</p>
                                                <p><strong>Correo:</strong> {{ $user->email }}</p>
                                                <p><strong>Rol:</strong> {{ $user->role->name ?? 'N/A' }}</p>
                                                <p><strong>
                                                        {{ $user->tipo_documento ? ucfirst($user->tipo_documento) . ':' : '' }}</strong>
                                                    {{ $user->documento ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        {{-- Sección Predios Asignados --}}
                                        <div class="details-section">
                                            <div class="details-section-title">Predios Asignados</div>
                                            <div class="details-section-content">
                                                <div class="predios-list-details">
                                                    @forelse ($user->predios as $predio)
                                                        <span class="badge badge-secondary">{{ $predio->cod_predio }} -
                                                            {{ $predio->nombre_predio }}</span>
                                                    @empty
                                                        <span class="text-muted" style="font-size: 13px;">No tiene predios
                                                            asignados.</span>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>

                                        {{--  <div class="details-section">
                                            <div class="details-section-title">Membresía</div>
                                            <div class="details-section-content">
                                                <p class="text-muted">Información de membresía no disponible.</p>
                                            </div>
                                        </div> --}}

                                        {{-- Sección Detalles Adicionales --}}
                                       <div class="details-section">
                                            <div class="details-section-title">Detalles Adicionales</div>
                                            <div class="details-section-content">
                                                <p><strong>Usuario creado el:</strong>
                                                    {{ $user->created_at?->format('d/m/Y H:i') ?? 'Fecha no disponible' }}
                                                </p>
                                                <p><strong>Última actualización:</strong>
                                                    {{ $user->updated_at?->format('d/m/Y H:i') ?? 'Fecha no disponible' }}
                                                </p>
                                                {{-- <p><strong>Último login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</p> --}}
                                                @if ($user->created_by)
                                                    @php
                                                        $creator = App\Models\User::find($user->created_by);
                                                    @endphp
                                                    <p><strong>Creado por:</strong>
                                                        {{ $creator?->name ?? 'Usuario desconocido' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px;">
                                <span class="material-symbols-outlined"
                                    style="font-size: 48px; color: #ccc;">group_off</span> {{-- Icono diferente --}}
                                <p style="color: #6c757d; margin-top: 10px;">No se encontraron usuarios.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



@endsection
{{-- Include del Modal (restaurado) --}}
@include('user.create_modal')
@include('user.edit_modal')


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script>
        console.log("User index script executing..."); // <-- LOG 1

        // --- FUNCIONES GLOBALES (MOVIDAS FUERA DE READY) ---
        console.log("Defining global helper functions..."); // <-- LOG 2

        // Funciones auxiliares para manejo de errores de validación
        function clearValidationErrors(form) {
            if (!form) return;
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback, .text-danger[role="alert"]').forEach(el => {
                el.innerHTML = '';
                el.style.display = 'none';
            });
            // Limpiar error específico de foto si existe
            const photoError = form.querySelector('.edit-profile-photo-error');
            if (photoError) photoError.style.display = 'none';
        }

        function displayValidationErrors(errors, form) {
            if (!form) return;
            clearValidationErrors(form); // Limpiar primero
            let firstErrorField = null; // Para hacer focus en el primer campo con error
            for (const field in errors) {
                if (!firstErrorField) firstErrorField = field; // Guarda el nombre del primer campo
                const fieldName = field;
                const inputSelector = `#${form.id} [name="${fieldName}"], #${form.id} [name="${fieldName}[]"]`;
                const input = form.querySelector(inputSelector);

                if (input) {
                    input.classList.add('is-invalid');
                    let errorContainer = null;
                    errorContainer = input.closest('.mb-3, .col-md-6, .input-group')?.querySelector('.invalid-feedback');

                    if (!errorContainer && (fieldName === 'password' || fieldName === 'password_confirmation')) {
                        const parentGroup = input.closest('.input-group');
                        errorContainer = parentGroup?.nextElementSibling;
                        if (!(errorContainer?.classList.contains('invalid-feedback') || errorContainer?.getAttribute(
                                'role') === 'alert')) {
                            errorContainer = parentGroup?.querySelector('.invalid-feedback');
                        }
                    } else if (fieldName === 'predios' || fieldName.startsWith('predios.')) {
                        errorContainer = form.querySelector(
                        '.edit-predios-error, .text-danger[role="alert"]'); // Buscar ambos posibles contenedores
                        const predioSelect = form.querySelector('#edit-predio-selector, #predio-selector');
                        if (predioSelect) predioSelect.classList.add('is-invalid');
                    } else if (fieldName === 'profile_photo') {
                        errorContainer = form.querySelector('.edit-profile-photo-error');
                    }

                    if (errorContainer) {
                        errorContainer.innerHTML = `<strong>${errors[field][0]}</strong>`;
                        errorContainer.style.display = 'block';
                    } else {
                        console.warn(
                            `Contenedor de error no encontrado para el campo: ${field} (selector: ${inputSelector})`);
                        // Considerar añadir un contenedor general de errores visible si esto ocurre
                    }
                } else {
                    console.warn(`Input no encontrado para el campo con error: ${field} (selector: ${inputSelector})`);
                    // Mostrar error no asociado a un campo específico (si existe un contenedor general)
                    // Ejemplo: añadir al principio del modal un <div class="general-form-errors text-danger"></div>
                    const generalErrorContainer = form.querySelector('.general-form-errors');
                    if (generalErrorContainer) {
                        generalErrorContainer.innerHTML += `<p><strong>${field}:</strong> ${errors[field][0]}</p>`;
                        generalErrorContainer.style.display = 'block';
                    }
                }
            }
            // Hacer focus en el primer campo con error si se encontró
            if (firstErrorField) {
                const firstInput = form.querySelector(`#${form.id} [name="${firstErrorField}"]`);
                firstInput?.focus();
            }
        }

        // --- FIN FUNCIONES GLOBALES ---

        // Funciones reutilizables para modals nativos
        const modalOverlay = document.getElementById('nativeModalOverlay');
        const createModal = document.getElementById('nativeUserModal');
        const editModal = document.getElementById('nativeEditUserModal');

        function openModal(modalElement) {
            console.log("openModal called for:", modalElement);
            if (!modalElement || !modalOverlay) {
                console.error("Modal or Overlay element not found in openModal");
                return;
            }
            try {
                clearValidationErrors(modalElement.querySelector('form'));
            } catch (e) {
                console.error("Error calling clearValidationErrors in openModal:", e);
            }
            // CORRECCIÓN: Añadir clases .show para activar CSS
            modalOverlay.style.display = 'block'; // Asegurar display block antes de la transición
            modalElement.style.display = 'block';
            // Pequeño delay para asegurar que display:block se aplique antes de la clase show
            setTimeout(() => {
                modalOverlay.classList.add('show');
                modalElement.classList.add('show');
            }, 10); // 10ms suele ser suficiente

            modalElement.setAttribute('aria-hidden', 'false');
            /*    document.body.classList.add('modal-open'); */
            const modalBody = modalElement.querySelector('.native-modal-body');
            if (modalBody) modalBody.scrollTop = 0;
        }

        function closeModal(modalElement) {
            console.log("closeModal called for:", modalElement);
            if (!modalElement || !modalOverlay) {
                console.error("Modal or Overlay element not found in closeModal");
                return;
            }
            // CORRECCIÓN: Quitar clases .show para desactivar CSS
            modalOverlay.classList.remove('show');
            modalElement.classList.remove('show');

            // Esperar a que termine la transición antes de ocultar con display:none
            // (La duración debe coincidir con la transición CSS, ej: 0.3s = 300ms)
            setTimeout(() => {
                if (!modalOverlay.classList.contains('show')) {
                    modalOverlay.style.display = 'none';
                }
                if (!modalElement.classList.contains('show')) {
                    modalElement.style.display = 'none';
                    modalElement.setAttribute('aria-hidden', 'true');
                }
                // Quitar clase del body solo si no hay otros modals visibles
                if (!document.querySelector('.native-modal.show')) {
                    /*     document.body.classList.remove('modal-open'); */
                }
            }, 300); // Ajustar a la duración de tu transición CSS
        }

        // --- Event listeners para cierre ---
        document.querySelectorAll('.native-modal-close').forEach(button => {
            button.addEventListener('click', () => {
                console.log("Modal close button clicked."); // <-- LOG 5
                const modal = button.closest('.native-modal');
                closeModal(modal);
            });
        });
        if (modalOverlay) {
            modalOverlay.addEventListener('click', () => {
                console.log("Modal overlay clicked."); // <-- LOG 6
                // Cerrar CUALQUIER modal nativo abierto
                document.querySelectorAll('.native-modal[aria-hidden="false"]').forEach(modal => closeModal(modal));
            });
        }

        // === CÓDIGO PARA MODAL DE CREACIÓN (EXISTENTE) ===
        $(document).ready(function() {
            console.log("Document ready handler executing...");

            // --- Elementos Comunes ---
            // POR FAVOR, CONFIRMA ESTA RUTA:
            const defaultAvatar = "{{ asset('/images/placeholder_avatar.png') }}"; // O la ruta correcta

            // --- Elementos Modal Creación ---
            const openModalBtn = document.getElementById('openCreateUserModal');
            const createForm = document.getElementById('user-form');
            const profilePhotoInput = document.getElementById('profile_photo');
            const profilePhotoPreview = document.getElementById('profile_photo_preview');
            const eliminarFotoBtn = document.getElementById('eliminar_foto');
            const predioSelector = document.getElementById('predio-selector');
            const addPredioBtn = document.getElementById('add-predio-btn');
            const selectedContainer = document.getElementById('selected-predios-container');
            const hiddenInputsContainer = document.getElementById('hidden-predios-inputs');
            const noPrediosMsg = document.getElementById('no-predios-selected');

            // --- Elementos Modal Edición (Definidos aquí para accesibilidad) ---
            const editForm = document.getElementById('edit-user-form');
            const editProfilePhotoInput = document.getElementById('edit_profile_photo');
            const editProfilePhotoPreview = document.getElementById('edit_profile_photo_preview');
            const editEliminarFotoBtn = document.getElementById('edit_eliminar_foto');
            const editRemovePhotoInput = document.getElementById('edit_remove_profile_photo');
            const editPredioSelector = document.getElementById('edit-predio-selector');
            const editAddPredioBtn = document.getElementById('edit-add-predio-btn');
            const editSelectedContainer = document.getElementById('edit-selected-predios-container');
            const editHiddenInputsContainer = document.getElementById('edit-hidden-predios-inputs');
            const editNoPrediosMsg = document.getElementById('edit-no-predios-selected');

            // --- Botón Abrir Modal Creación ---
            if (openModalBtn && createModal) {
                console.log("Attaching listener to #openCreateUserModal");
                openModalBtn.addEventListener('click', () => {
                    console.log("#openCreateUserModal clicked!");
                    if (createForm) {
                        createForm.reset();
                        try {
                            const img = new Image();
                            img.onerror = () => console.error(
                                `Placeholder image not found at: ${defaultAvatar}`);
                            img.src = defaultAvatar;
                            if (profilePhotoPreview) profilePhotoPreview.src = defaultAvatar;
                        } catch (e) {
                            console.error("Error setting placeholder src:", e);
                        }

                        if (selectedContainer) selectedContainer.innerHTML = noPrediosMsg ? noPrediosMsg
                            .outerHTML :
                            '<span class="text-muted" style="font-size: 0.9em;">Ningún predio seleccionado.</span>';
                        if (noPrediosMsg && selectedContainer) noPrediosMsg.style.display = 'inline';
                        if (hiddenInputsContainer) hiddenInputsContainer.innerHTML = '';
                    }
                    openModal(createModal);
                });
            } else {
                console.error("#openCreateUserModal button OR #nativeUserModal not found!");
            }

            // --- Lógica Foto Perfil (Creación) ---
            if (eliminarFotoBtn && profilePhotoPreview) {
                eliminarFotoBtn.addEventListener('click', () => {
                    profilePhotoPreview.src = defaultAvatar;
                    if (profilePhotoInput) profilePhotoInput.value = '';
                });
            }
            if (profilePhotoInput && profilePhotoPreview) {
                profilePhotoInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            profilePhotoPreview.src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // --- Lógica Toggle Password (Común) ---
            function setupPasswordToggle(toggleId, passwordId, iconId) {
                const toggleButton = document.getElementById(toggleId);
                const passwordInput = document.getElementById(passwordId);
                const icon = document.getElementById(iconId);
                if (toggleButton && passwordInput && icon) {
                    toggleButton.addEventListener('click', () => {
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                        passwordInput.setAttribute('type', type);
                        icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
                    });
                }
            }
            // Creación
            setupPasswordToggle('togglePassword', 'password', 'toggleIconPassword');
            setupPasswordToggle('togglePasswordConfirm', 'password_confirmation', 'toggleIconPasswordConfirm');
            // Edición
            setupPasswordToggle('editTogglePassword', 'edit_password', 'editToggleIconPassword');
            setupPasswordToggle('editTogglePasswordConfirm', 'edit_password_confirmation',
                'editToggleIconPasswordConfirm');

            // --- MANEJO SELECCIÓN PREDIOS (Funciones Comunes) ---
            function addPredio(predioId, predioNombre, targetContainer, targetHiddenContainer,
            noPrediosMsgElement) {
                if (!predioId || !predioNombre || !targetContainer || !targetHiddenContainer) return;
                const existingInput = targetHiddenContainer.querySelector(
                    `input[name="predios[]"][value="${predioId}"]`);
                if (existingInput) return;
                if (noPrediosMsgElement) noPrediosMsgElement.style.display = 'none';
                const badge = document.createElement('span');
                badge.classList.add('selected-predio-badge');
                badge.dataset.predioId = predioId;
                badge.innerHTML =
                    `${predioNombre} <button type="button" class="remove-predio-btn" aria-label="Eliminar">&times;</button>`;
                badge.querySelector('.remove-predio-btn').addEventListener('click', () => removePredio(badge,
                    predioId, targetHiddenContainer, noPrediosMsgElement));
                targetContainer.appendChild(badge);
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'predios[]';
                hiddenInput.value = predioId;
                targetHiddenContainer.appendChild(hiddenInput);
            }

            function removePredio(badgeElement, predioId, targetHiddenContainer, noPrediosMsgElement) {
                if (!badgeElement || !targetHiddenContainer) return;
                badgeElement.remove();
                const hiddenInput = targetHiddenContainer.querySelector(
                    `input[name="predios[]"][value="${predioId}"]`);
                if (hiddenInput) hiddenInput.remove();
                if (targetHiddenContainer.children.length === 0 && noPrediosMsgElement) {
                    noPrediosMsgElement.style.display = 'inline';
                }
            }

            // Listener Añadir Predio (Creación)
            if (addPredioBtn && predioSelector) {
                addPredioBtn.addEventListener('click', () => {
                    const selectedOption = predioSelector.options[predioSelector.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        addPredio(selectedOption.value, selectedOption.dataset.nombre, selectedContainer,
                            hiddenInputsContainer, noPrediosMsg);
                        predioSelector.value = '';
                    }
                });
            }
            // Listener Añadir Predio (Edición)
            if (editAddPredioBtn && editPredioSelector) {
                editAddPredioBtn.addEventListener('click', () => {
                    const selectedOption = editPredioSelector.options[editPredioSelector.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        addPredio(selectedOption.value, selectedOption.dataset.nombre,
                            editSelectedContainer, editHiddenInputsContainer, editNoPrediosMsg);
                        editPredioSelector.value = '';
                    }
                });
            }

            // --- ENVÍO FORMULARIO AJAX (CREACIÓN) ---
            if (createForm) {
                const submitButton = createForm.querySelector('button[type="submit"]');
                const originalButtonContent = submitButton ? submitButton.innerHTML : '';
                createForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    if (!submitButton) return;
                    submitButton.disabled = true;
                    submitButton.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...`;
                    clearValidationErrors(createForm);
                    const formData = new FormData(createForm);
                    fetch(createForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': formData.get('_token'),
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => {
                            if (response.ok && response.status === 201) return response.json();
                            if (response.status === 422) return response.json().then(errors => Promise
                                .reject({
                                    status: 422,
                                    errors
                                }));
                            return response.text().then(text => Promise.reject({
                                status: response.status,
                                message: text
                            }));
                        })
                        .then(data => {
                            closeModal(createModal);
                            alert('Usuario creado correctamente');
                            setTimeout(() => location.reload(), 500);
                        })
                        .catch(errorInfo => {
                            console.error('Error AJAX Creación:', errorInfo);
                            if (errorInfo.status === 422) {
                                displayValidationErrors(errorInfo.errors, createForm);
                                alert('Por favor corrige los errores indicados en el formulario.');
                            } else {
                                alert(
                                    `Error (${errorInfo.status || '?'}). No se pudo crear el usuario. Intenta de nuevo.`);
                            }
                        })
                        .finally(() => {
                            if (submitButton) {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalButtonContent;
                            }
                        });
                });
            }

            // === CÓDIGO PARA MODAL DE EDICIÓN ===

            // --- Lógica Foto Perfil (Edición) ---
            if (editEliminarFotoBtn && editProfilePhotoPreview) {
                editEliminarFotoBtn.addEventListener('click', () => {
                    editProfilePhotoPreview.src = defaultAvatar;
                    if (editProfilePhotoInput) editProfilePhotoInput.value = '';
                    if (editRemovePhotoInput) editRemovePhotoInput.value = '1';
                });
            }
            if (editProfilePhotoInput && editProfilePhotoPreview) {
                editProfilePhotoInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            editProfilePhotoPreview.src = e.target.result;
                            if (editRemovePhotoInput) editRemovePhotoInput.value =
                            '0'; // Si carga nueva, no eliminar
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // --- Abrir y Poblar Modal Edición ---
            const editButtons = document.querySelectorAll('.edit-user-btn');
            console.log(`Found ${editButtons.length} edit buttons.`);
            if (!editModal) {
                console.error("#nativeEditUserModal (edit modal) not found!");
            }

            editButtons.forEach(button => {
                console.log("Attaching listener to edit button:", button);
                button.addEventListener('click', async () => {
                    const userId = button.dataset.userId;
                    console.log(`.edit-user-btn clicked for user ID: ${userId}`);
                    const getDataUrl = `{{ url('users/get-data') }}/${userId}`;
                    const updateUrl = `{{ url('users/update-ajax') }}/${userId}`;

                    // Usar constantes definidas arriba
                    if (!editForm || !editModal) {
                        console.error("Edit form or edit modal element not found in handler");
                        return;
                    }

                    try {
                        // Resetear form edición
                        editForm.reset();
                        // Limpiar contenedores predios (usando constantes)
                        if (editSelectedContainer) editSelectedContainer.innerHTML = '';
                        if (editHiddenInputsContainer) editHiddenInputsContainer.innerHTML = '';
                        if (editNoPrediosMsg) editNoPrediosMsg.style.display =
                        'inline'; // Mostrar "ninguno" por defecto

                        try {
                            const img = new Image();
                            img.onerror = () => console.error(
                                `Placeholder image not found at: ${defaultAvatar} (Edit Modal)`
                                );
                            img.src = defaultAvatar;
                            if (editProfilePhotoPreview) editProfilePhotoPreview.src =
                                defaultAvatar;
                        } catch (e) {
                            console.error("Error setting placeholder src (Edit Modal):", e);
                        }
                        if (editRemovePhotoInput) editRemovePhotoInput.value = '0';

                        console.log(`Fetching data for user ${userId} from ${getDataUrl}`);
                        const response = await fetch(getDataUrl);

                        if (!response.ok) {
                            const errorText = await response.text();
                            console.error(`Error ${response.status} fetching user data:`,
                                errorText);
                            throw new Error(`Error ${response.status}`);
                        }

                        let userData;
                        try {
                            userData = await response.json();
                            console.log("User data received and parsed:", userData);
                        } catch (jsonError) {
                            console.error("Error parsing JSON response:", jsonError);
                            console.error("Response text:", await response.text());
                            throw new Error("Invalid JSON received from server.");
                        }

                        // Poblar formulario
                        if (userData) {
                            editForm.action = updateUrl;
                            editForm.querySelector('#edit_user_id').value = userData.id;
                            editForm.querySelector('#edit_name').value = userData.name || '';
                            editForm.querySelector('#edit_email').value = userData.email || '';
                            editForm.querySelector('#edit_id_rol').value = userData.id_rol ||
                            '';
                            editForm.querySelector('#edit_tipo_documento').value = userData
                                .tipo_documento ? userData.tipo_documento.toLowerCase() : '';
                            editForm.querySelector('#edit_documento').value = userData
                                .documento || '';
                            editForm.querySelector('#edit_estado').value = userData.estado ?
                                userData.estado.toLowerCase() : 'activo';
                            editForm.querySelector('#edit_password').value = '';
                            editForm.querySelector('#edit_password_confirmation').value = '';

                            if (userData.profile_photo_path && editProfilePhotoPreview) {
                                editProfilePhotoPreview.src =
                                    `/storage/${userData.profile_photo_path}`;
                            } else if (editProfilePhotoPreview) {
                                editProfilePhotoPreview.src = defaultAvatar;
                            }
                            if (editRemovePhotoInput) editRemovePhotoInput.value = '0';

                            // Poblar predios seleccionados (usando constantes)
                            if (userData.predios && userData.predios.length > 0) {
                                if (editNoPrediosMsg) editNoPrediosMsg.style.display = 'none';
                                userData.predios.forEach(predio => {
                                    const predioNombre =
                                        `${predio.nombre_predio} (${predio.cod_predio || 'S/C'})`;
                                    addPredio(predio.id, predioNombre,
                                        editSelectedContainer,
                                        editHiddenInputsContainer, editNoPrediosMsg);
                                });
                            } else {
                                if (editNoPrediosMsg) editNoPrediosMsg.style.display = 'inline';
                                if (editSelectedContainer) editSelectedContainer.innerHTML =
                                    editNoPrediosMsg ? editNoPrediosMsg.outerHTML :
                                    '<span class="text-muted" style="font-size: 0.9em;">Ningún predio seleccionado.</span>';
                            }
                        } else {
                            throw new Error("User data is null or undefined after parsing.");
                        }

                        openModal(editModal);

                    } catch (error) {
                        console.error('Full error object in catch block:', error);
                        alert(
                            'Error: No se pudieron cargar los datos del usuario para editar. Revisa la consola para más detalles.');
                    }
                });
            });

            // --- ENVÍO FORMULARIO AJAX (EDICIÓN) ---
            if (editForm) {
                const editSubmitButton = editForm.querySelector('button[type="submit"]');
                const editOriginalButtonContent = editSubmitButton ? editSubmitButton.innerHTML : '';
                editForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    if (!editSubmitButton) return;
                    editSubmitButton.disabled = true;
                    editSubmitButton.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Actualizando...`;
                    clearValidationErrors(editForm);
                    const formData = new FormData(editForm);
                    if (!formData.has('_method')) {
                        formData.append('_method', 'PUT');
                    }
                    if (editRemovePhotoInput) formData.set('remove_profile_photo', editRemovePhotoInput
                        .value);

                    fetch(editForm.action, {
                            method: 'POST', // Siempre POST con _method
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => {
                            if (response.ok && response.status === 200) return response.json();
                            if (response.status === 422) return response.json().then(errors => Promise
                                .reject({
                                    status: 422,
                                    errors
                                }));
                            return response.text().then(text => Promise.reject({
                                status: response.status,
                                message: text
                            }));
                        })
                        .then(data => {
                            closeModal(editModal);
                            alert('Usuario actualizado correctamente');
                            setTimeout(() => location.reload(), 500);
                        })
                        .catch(errorInfo => {
                            console.error('Error AJAX Edición:', errorInfo);
                            if (errorInfo.status === 422) {
                                displayValidationErrors(errorInfo.errors, editForm);
                                alert('Por favor corrige los errores indicados en el formulario.');
                            } else {
                                alert(
                                    `Error (${errorInfo.status || '?'}). No se pudo actualizar el usuario. Intenta de nuevo.`);
                            }
                        })
                        .finally(() => {
                            if (editSubmitButton) {
                                editSubmitButton.disabled = false;
                                editSubmitButton.innerHTML = editOriginalButtonContent;
                            }
                        });
                });
            }

            // --- Función para Toggle Detalles Usuario (Revisada para fila) ---
            const toggleButtons = document.querySelectorAll('.toggle-user-details');
            console.log(`Found ${toggleButtons.length} toggle detail buttons.`);
            toggleButtons.forEach(button => {
                console.log("Attaching listener to toggle button:", button);
                button.addEventListener('click', function() {
                    // Ahora el target es la FILA
                    const targetId = this.getAttribute('data-target');
                    console.log("Toggle button clicked for target row:", targetId);
                    const targetRow = document.getElementById(targetId); // Obtener la fila <tr>
                    const icon = this.querySelector('span');

                    if (targetRow && icon) {
                        // Toggle la clase en la FILA <tr>
                        const isActive = targetRow.classList.toggle('details-row-active');
                        // Toggle clase en el botón para posible estilo
                        this.classList.toggle('active', isActive);
                        // Cambiar icono basado en el estado activo
                        icon.textContent = isActive ? 'keyboard_arrow_up' : 'expand_more';
                    } else {
                        console.error("Target row or icon not found for toggle:", targetId);
                    }
                });
            });

            // --- Función Confirmación Borrado (CORREGIDO) ---
            const deleteButtons = document.querySelectorAll('.delete-user-btn');
            console.log(`Found ${deleteButtons.length} delete buttons.`);
            deleteButtons.forEach(button => {
                console.log("Attaching listener to delete button:", button);
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    console.log(`Delete button clicked for user ID: ${userId}`);
                    const deleteForm = document.getElementById(`delete-form-${userId}`);
                    if (!deleteForm) {
                        console.error(`Delete form not found for user ID: ${userId}`);
                        return;
                    }
                    if (confirm(
                        '¿Estás seguro de eliminar este usuario? ¡No podrás revertir esto!')) {
                        deleteForm.submit();
                    }
                });
            });

        }); // Fin $(document).ready
    </script>
@endsection
