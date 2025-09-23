@extends('layouts')

@section('title', 'Membresías')

@section('styles')
    <style>
        .btn-success {
            background-color: #e49b39 !important;
            border: 1px solid #e49b39 !important;
            padding: 10px !important;
        }

        .btn-success:hover {
            background-color: #b47625 !important;
            border: 1px solid #b47625 !important;
        }

        .card-custom {
            border-radius: 8px;
            background: #fff;
            padding: 30px;
            border: 1px solid #eaebef;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .grid-playground {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: flex-start;
            flex-direction: row;
        }

        .grid-card,
        .grid-card-end {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            background-color: white;
            text-align: start;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            min-width: 150px;
            width: 23%;
        }

        .grid-card:hover,
        .grid-card-end:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .light-text {
            font-size: 0.9em;
            color: #777;
        }

        .title-card,
        .title-card-end {
            font-size: 2EM;
            font-weight: 900;
            margin-bottom: 10px;
            color: #343a40;
        }

        .description {
            font-size: 0.9em;
            color: #555;
        }

        .admin-table {
            font-size: 0.9em;
        }

        .admin-table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e49b39;
            color: #343a40;
            font-weight: 600;
        }

        .admin-table tbody tr:hover {
            /*  background-color: rgba(228, 155, 57, 0.05); */
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e49b39;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
        }

        .badge.bg-warning {
            background-color: #e49b3942 !important;
            color: #a64f00 !important;
            padding: 10px;
        }

        .badge.bg-success {
            color: #28a745 !important;
            padding: 10px;
            background-color: #28a74533 !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
            padding: 10px !important;
        }

        .btn-danger {
            padding: 10px !important;

        }

        .badge.bg-secondary {
            background-color: #6c757d66 !important;
            color: gray !important;
            padding: 10px;
        }

        .btn-primary {
            background-color: #e49b39;
            border-color: #e49b39;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #b47625;
            border-color: #b47625;
        }

        .collapse .card {
            border: 1px solid #e49b39;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(228, 155, 57, 0.1);
        }

        .collapse .card-header {
            background-color: rgba(228, 155, 57, 0.05);
            border-bottom: 1px solid #e49b39;
        }

        .table-sm td,
        .table-sm th {
            padding: 0.5rem;
        }

        .btn-outline-dark {
            border: 1px solid #e29324;
            color: #e29323;
        }

        .btn-outline-dark:hover {
            background-color: #e29324;
            color: white;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-pending {
            background-color: #e49b39;
        }

        .status-active {
            background-color: #28a745;
        }

        .status-inactive {
            background-color: #6c757d;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-name {
            font-weight: 600;
            color: #343a40;
        }

        .user-email {
            font-size: 0.85em;
            color: #6c757d;
        }

        .details-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: flex-end;
        }

        .CustomBtn {
            padding: 5px 15px;
            border-radius: 6px;
            background: white;
            border: 1px solid #e3e3e3;
            margin-right: 8px;
        }

        .CustomBtn:hover {
            background: #e49b39;
            color: white;
            border: 1px solid #e49b39;

        }

        .rango-btn.active {
            background-color: #e49b39;
            color: white;
            border: 1px solid #e49b39;
        }

        /*  tr {
                min-height: 165px;
             } */
             .alert-success {
    --bs-alert-color: #e49b39;
    --bs-alert-bg: #ffa21d21;
    --bs-alert-border-color: #e49b39;
}

        .plan-activo {
            border: 2px solid #e49b39;
            box-shadow: 0 0 10px rgba(228, 155, 57, 0.3);
            position: relative;
        }

        .plan-activo-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #e49b39;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
@endsection

@section('content')
    {{-- Mensajes de sesión --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card-custom">
        <div class="header-grid text-center">
            @if ($user->role->name === 'propietario')
                <h3>Suscripciones</h3>
                <hr>
            @elseif ($user->role->name === 'admin')
                <h3>Administra los suscriptores </h3>
                <hr>
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#asignarMembresiaModal">
                    Asignar Membresía
                </button>
                <div class="mb-3 mt-3">
                    <input type="text" id="filtroUsuarios" class="form-control" placeholder="Buscar por nombre o correo...">
                </div>
            @endif

            {{-- Fin campo de filtro --}}

            @if ($user->role->name === 'admin')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped admin-table">
                        <thead>
                            <tr>
                                <th><span class="material-symbols-outlined" style="font-size: 1.2em;">
                                    tag
                                    </span></th>
                                <th>Usuario</th>
                                <th>Estado Actual</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                               @foreach ($memberships->sortBy(function ($membership) {
                   $order = [
                       'pendiente' => 0,
                       'activo' => 1,
                       'expirado' => 2,
                       'rechazado' => 3,
                   ];
                   return $order[$membership->estado] ?? 999; // Valor alto por si hay estados no previstos
               })->groupBy('user_id') as $userMemberships)
                                   @php
                                       $latestMembership = $userMemberships->first();
                                       $membershipUser = $latestMembership->user;
                                       $hasActive = $userMemberships->contains('estado', 'activo');
                                       $hasPending = $userMemberships->contains('estado', 'pendiente');
                                   @endphp
                                <tr>
                                    <td style="padding: 20px;">
                                        <span>
                                            {{ $loop->iteration }}

                                        </span>
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                @if ($membershipUser->profile_photo_path)
                                                    <img src="{{ Storage::url($membershipUser->profile_photo_path) }}"
                                                        alt="Foto de perfil" class="rounded-circle"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    {{ substr($membershipUser->name, 0, 1) }}
                                                @endif
                                            </div>
                                            <div
                                                style="    display: flex
;
    flex-direction: column;
    align-items: start;
">
                                                <div class="user-name">{{ $membershipUser->name }}
                                                </div>
                                                <div>
                                                    @if ($membershipUser->role->name === 'propietario')
                                                        <span class="">Propietario</span>
                                                    @elseif ($membershipUser->role->name === 'admin')
                                                        <span class="">Administrador</span>
                                                    @elseif ($membershipUser->role->name === 'veterinario')
                                                        <span class="">Veterinario</span>
                                                    @elseif ($membershipUser->role->name === 'encuestador')
                                                        <span class="">Encuestador</span>
                                                    @endif
                                                </div>
                                                <div class="user-email">{{ $membershipUser->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($hasPending)
                                            <span class="badge bg-warning">Solicitud de membresía</span>
                                        @elseif($hasActive)
                                            <span class="badge bg-success">Membresía Activa</span>
                                        @else
                                            <span class="badge bg-secondary">Sin membresía activa</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $updated = $latestMembership->updated_at;
                                            $now = \Carbon\Carbon::now();
                                            $diffInMinutes = (int) $updated->diffInMinutes($now);
                                            $diffInHours = (int) $updated->diffInHours($now);
                                            $diffInDays = (int) $updated->diffInDays($now);
                                            $diffInMonths = (int) $updated->diffInMonths($now);
                                            $diffInYears = (int) $updated->diffInYears($now);

                                            if ($diffInMinutes < 60) {
                                                echo 'hace ' .
                                                    $diffInMinutes .
                                                    ' ' .
                                                    ($diffInMinutes == 1 ? 'minuto' : 'minutos');
                                            } elseif ($diffInHours < 24) {
                                                echo 'hace ' .
                                                    $diffInHours .
                                                    ' ' .
                                                    ($diffInHours == 1 ? 'hora' : 'horas');
                                            } elseif ($diffInDays < 30) {
                                                echo 'hace ' . $diffInDays . ' ' . ($diffInDays == 1 ? 'día' : 'días');
                                            } elseif ($diffInMonths < 12) {
                                                echo 'hace ' .
                                                    $diffInMonths .
                                                    ' ' .
                                                    ($diffInMonths == 1 ? 'mes' : 'meses');
                                            } else {
                                                echo 'hace más de 12 meses';
                                            }
                                        @endphp
                                    </td>
                                    <td style="justify-items: center;">
                                         {{-- Botón sin atributos data-bs-* --}}
                                        <button type="button" class="btn btn-primary btn-sm toggle-details">
                                            Ver Detalles
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                     {{-- Contenedor sin clase collapse y con display: none --}}
                                    <td colspan="5" style="paring: 0; border-top: none;">
                                        <div class="collapse-container" style="display: none;">
                                            <div class="details-section">
                                                <h6 class="mb-3" style="color: #e49b39; font-weight: 600;">Historial de
                                                    Membresías</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Plan</th>
                                                                <th>Tipo</th>
                                                                <th>Precio</th>
                                                                <th>Fecha Inicio</th>
                                                                <th>Fecha Expiración</th>
                                                                <th>Estado</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($userMemberships as $membership)
                                                                <tr >
                                                                     <td>{{ $membership->membershipPlan->nombre }}</td>
                                                                    <td>{{ $membership->membershipPlan->isFreeTrial ? 'Gratuito' : 'Pago' }}
                                                                    </td>
                                                                    <td>${{ number_format($membership->membershipPlan->precio, 0, '.', ',') }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($membership->fecha_inicio)->format('d/m/Y') }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($membership->fecha_expiracion)->format('d/m/Y') }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($membership->estado === 'pendiente')
                                                                            <span class="badge bg-warning">Pendiente</span>
                                                                        @elseif($membership->estado === 'activo')
                                                                            <span class="badge bg-success">Activo</span>
                                                                        @elseif($membership->estado === 'rechazado')
                                                                            <span class="badge bg-danger">Rechazado</span>
                                                                        @elseif($membership->estado === 'expirado')
                                                                            <span class="badge bg-secondary">Expirado</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="action-buttons">
                                                                        @if ($membership->estado === 'pendiente')
                                                                            <form
                                                                                action="{{ route('membresias.update', $membership->id) }}"
                                                                                method="POST" class="d-inline">
                                                                                @csrf
                                                                                <input type="hidden" name="estado"
                                                                                    value="activo">
                                                                                <input type="hidden"
                                                                                    name="fecha_expiracion"
                                                                                    value="{{ \Carbon\Carbon::parse($membership->fecha_inicio)->addDays($membership->membershipPlan->duracion_dias)->format('Y-m-d') }}">
                                                                                <button type="submit"
                                                                                    class="btn btn-success btn-sm">
                                                                                    Aceptar
                                                                                </button>
                                                                            </form>
                                                                            <form
                                                                                action="{{ route('membresias.update', $membership->id) }}"
                                                                                method="POST" class="d-inline">
                                                                                @csrf
                                                                                <input type="hidden" name="estado"
                                                                                    value="rechazado">
                                                                                <input type="hidden"
                                                                                    name="fecha_expiracion"
                                                                                    value="{{ $membership->fecha_expiracion }}">
                                                                                <button type="submit"
                                                                                    class="btn btn-danger btn-sm">
                                                                                    Rechazar
                                                                                </button>
                                                                            </form>
                                                                        @else
                                                                            <button type="button"
                                                                                class="btn btn-primary btn-sm edit-membership-btn"
                                                                                data-membership="{{ $membership->id }}"
                                                                                data-fecha_expiracion="{{ \Carbon\Carbon::parse($membership->fecha_expiracion)->format('Y-m-d') }}"
                                                                                data-estado="{{ $membership->estado }}">
                                                                                Editar
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if ($user->role->name === 'propietario')
            {{-- Sección para propietarios --}}
            <div class="mb-4">
                @if ($user->membership && $user->membership->isActive())
                    <div class="alert alert-success mb-4">
                        <h4 class="mb-1">Membresía Activa</h4>
                        <p class="mb-0">
                            <strong>Plan:</strong>
                            {{ $user->membership->membershipPlan->nombre ?? 'No tienes un plan activo' }}<br>
                            <strong>Vigencia:</strong>
                            {{ \Carbon\Carbon::parse($user->membership->fecha_inicio)->format('d/m/Y') }} a
                            {{ \Carbon\Carbon::parse($user->membership->fecha_expiracion)->format('d/m/Y') }}
                        </p>
                    </div>
                @endif

                <!-- Botones para elegir cantidad de animales -->
                <h3>¿Cuantos animales tienes?</h3>
                <br>
                <div class="mb-3">
                    <div id="rangoButtons" class="btn-group" role="group" aria-label="Rango de animales">
                        <button type="button" class="CustomBtn rango-btn" data-range="1-50">1-50 Animales</button>
                        <button type="button" class="CustomBtn rango-btn" data-range="51-200">51-200 Animales</button>
                        <button type="button" class="CustomBtn rango-btn" data-range="201-500">201-500 Animales</button>
                        <button type="button" class="CustomBtn rango-btn" data-range="500-1000">500-1000 Animales</button>
                        <button type="button" class="CustomBtn rango-btn" data-range="+1000">+1000 Animales</button>
                    </div>
                </div>
                <div class="grid-playground">
                    @foreach (['Mensual', 'Trimestral', 'Semestral', 'Anual'] as $planName)
                        <div class="grid-card {{ ($user->membership && $user->membership->isActive() && $user->membership->membershipPlan->nombre == $planName) ? 'plan-activo' : '' }}" data-plan="{{ $planName }}">
                            @if ($user->membership && $user->membership->isActive() && $user->membership->membershipPlan->nombre == $planName)
                                <div class="plan-activo-badge">Tu plan actual</div>
                            @endif

                            <span class="light-text">
                                @if ($planName == 'Mensual')
                                    <p>1 Mes</p>
                                @elseif ($planName == 'Trimestral')
                                    <p>3 Meses</p>
                                @elseif ($planName == 'Semestral')
                                    <p>6 Meses</p>
                                @elseif ($planName == 'Anual')
                                    <p>12 Meses</p>
                                @endif
                            </span>

                            <h5 class="title-card">{{ $planName }}</h5>
                            <p class="precio-plan">
                                $<span class="precio">0.00</span> COP
                            </p>

                            @if ($planName == 'Mensual')
                                <p>Sin descuento</p>
                            @elseif ($planName == 'Trimestral')
                                <p>Descuento: 7%</p>
                            @elseif ($planName == 'Semestral')
                                <p>Descuento: 10%</p>
                            @elseif ($planName == 'Anual')
                                <p>Descuento: 15%</p>
                            @endif

                            <p>Reportes, Roles y App movil</p>

                            <form action="{{ route('solicitar.membresia') }}" method="POST">
                                @csrf
                                <input type="hidden" name="membership_plan_id" class="membership_plan_id"
                                    value="">
                                <button type="submit" class="btn btn-success btn-md">Solicitar Membresía</button>
                            </form>
                        </div>
                    @endforeach
                    <p class="mb-0">
                        ¿Aun tienes dudas?
                    </p>
                    <button class="col-12 btn btn-outline-dark">
                        Solicitar mas información
                    </button>
                </div>
            </div>
        @endif
    </div>
@endsection
    <!-- Modal para editar la fecha de expiración y estado -->
    <div class="modal fade" id="editMembershipModal" tabindex="-1" role="dialog"
        aria-labelledby="editMembershipModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('membresias.update', 0) }}" method="POST" id="editMembershipForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMembershipModalLabel">Editar Membresía</h5>

                    </div>
                    <div class="modal-body">
                        <!-- Campo oculto para el id de la membresía -->
                        <input type="hidden" name="membership_id" id="membership_id">
                        <div class="form-group">
                            <label for="fecha_expiracion">Nueva Fecha de Expiración</label>
                            <input type="date" name="fecha_expiracion" id="fecha_expiracion" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="activo">Activo</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="rechazado">Rechazado</option>
                                <option value="expirado">Expirado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Membresía</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal para asignar --}}

    <div class="modal fade" id="asignarMembresiaModal" tabindex="-1" aria-labelledby="asignarMembresiaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('membresias.asignar') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="asignarMembresiaModalLabel">Asignar Membresía a Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Usuario</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="membership_plan_id" class="form-label">Plan de Membresía</label>
                            <select name="membership_plan_id" id="membership_plan_id" class="form-select" required>
                                <option value="">Seleccione un plan</option>
                                @foreach ($planes as $plan)
                                    <option value="{{ $plan->id }}" data-duracion="{{ $plan->duracion_dias }}">
                                        {{ $plan->nombre }} ({{ $plan->duracion_dias }} días)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                                value="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_expiracion" class="form-label">Fecha de Expiración</label>
                            <input type="date" name="fecha_expiracion" id="fecha_expiracionCreate"
                                class="form-control" required>
                        </div>

                        {{-- Puedes agregar más campos según sea necesario --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Asignar Membresía</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Toggle de detalles - con garantía de que muestra la tabla interna
            $('.toggle-details').off('click').on('click', function() {
                console.log("Toggle button clicked");
                var $button = $(this);
                var $currentRow = $button.closest('tr');
                var $detailsRow = $currentRow.next('tr');
                var $targetContainer = $detailsRow.find('.collapse-container');

                if ($targetContainer.length === 0) {
                    console.error("Error: No se encontró .collapse-container.");
                    return;
                }

                // Alternar visibilidad
                if ($targetContainer.is(':visible')) {
                    // Ocultar el contenedor
                    $targetContainer.hide();
                    $button.text('Ver Detalles');
                } else {
                    // Mostrar el contenedor Y asegurar que la tabla interna sea visible
                    $targetContainer.show();

                    // IMPORTANTE: Forzar que la tabla interna y sus filas sean visibles
                    var $internalTable = $targetContainer.find('.table-sm');
                    var $tableRows = $internalTable.find('tbody tr');

                    // Asegurar que la tabla tenga display:table
                    $internalTable.css('display', 'table');

                    // Asegurar que todas las filas de la tabla tengan display:table-row
                    $tableRows.css('display', 'table-row');

                    console.log("Forzando visibilidad de tabla interna:", $internalTable.length, "filas:", $tableRows.length);

                    $button.text('Ocultar Detalles');
                }
            });

            // FILTRO DE USUARIOS CORREGIDO
            // Solo filtra las filas principales, NO afecta al historial
            $('#filtroUsuarios').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                // Guardar todas las filas principales (las que tienen botón toggle)
                var $userRows = $('.admin-table > tbody > tr').has('.toggle-details');

                // Para cada fila principal (fila de usuario)
                $userRows.each(function() {
                    var $userRow = $(this);
                    var $detailsRow = $userRow.next('tr'); // Fila siguiente (contenedor de detalles)
                    var userName = $userRow.find('.user-name').text().toLowerCase();
                    var userEmail = $userRow.find('.user-email').text().toLowerCase();

                    // Verificar si coincide con la búsqueda
                    if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                        // Mostrar este usuario
                        $userRow.show();
                        $detailsRow.show();

                        // IMPORTANTE: Si la fila de detalles está expandida, asegurar que la tabla interna sea visible
                        var $container = $detailsRow.find('.collapse-container');
                        if ($container.is(':visible')) {
                            $container.find('.table-sm').css('display', 'table');
                            $container.find('.table-sm tbody tr').css('display', 'table-row');
                        }
                    } else {
                        // Ocultar este usuario
                        $userRow.hide();
                        $detailsRow.hide();
                    }
                });

                // EXTRA: Asegurar que las tablas visibles (si hay alguna) no se oculten
                $('.collapse-container:visible .table-sm').css('display', 'table');
                $('.collapse-container:visible .table-sm tbody tr').css('display', 'table-row');
            });

            // Resto de scripts (modal, etc.)
            $('.edit-membership-btn').click(function() {
                var membershipId = $(this).data('membership');
                var fechaExpiracion = $(this).data('fecha_expiracion');
                var estado = $(this).data('estado');
                $('#membership_id').val(membershipId);
                $('#fecha_expiracion').val(fechaExpiracion);
                $('#estado').val(estado);
                var form = $('#editMembershipForm');
                var actionUrl = form.attr('action').replace(/\/\d+$/, '/' + membershipId);
                form.attr('action', actionUrl);
                $('#editMembershipModal').modal('show');
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Mapeo de plan de membresía a su id real según la nueva estructura en SQL
            const membershipMapping = {
                'Mensual': {
                    '1-50': 13,
                    '51-200': 17,
                    '201-500': 21,
                    '500-1000': 25,
                    '+1000': 29
                },
                'Trimestral': {
                    '1-50': 14,
                    '51-200': 18,
                    '201-500': 22,
                    '500-1000': 26,
                    '+1000': 30
                },
                'Semestral': {
                    '1-50': 15,
                    '51-200': 19,
                    '201-500': 23,
                    '500-1000': 27,
                    '+1000': 31
                },
                'Anual': {
                    '1-50': 16,
                    '51-200': 20,
                    '201-500': 24,
                    '500-1000': 28,
                    '+1000': 32
                }
            };

            // Función para animar un número hasta el valor objetivo sin decimales
            function animateNumber(el, target, duration) {
                var current = parseFloat(el.text()) || 0;
                $({
                    numberValue: current
                }).animate({
                    numberValue: target
                }, {
                    duration: duration,
                    easing: 'swing',
                    step: function(now) {
                        el.text(parseInt(now));
                    },
                    complete: function() {
                        el.text(parseInt(target));
                    }
                });
            }

            const pricing = {
                'Mensual': {
                    '1-50': 23000.00,
                    '51-200': 33000.00,
                    '201-500': 47000.00,
                    '500-1000': 51000.00,
                    '+1000': 63000.00
                },
                'Trimestral': {
                    '1-50': 59500.00,
                    '51-200': 89250.00,
                    '201-500': 133875.00,
                    '500-1000': 144288.00,
                    '+1000': 157000.00
                },
                'Semestral': {
                    '1-50': 119000.00,
                    '51-200': 178500.00,
                    '201-500': 267750.00,
                    '500-1000': 288575.00,
                    '+1000': 314000.00
                },
                'Anual': {
                    '1-50': 238000.00,
                    '51-200': 357000.00,
                    '201-500': 535500.00,
                    '500-1000': 577150.00,
                    '+1000': 714000.00
                }
            };

            // Función para actualizar los precios con animación incremental
            function actualizarPrecios(rango) {
                $('.grid-card').each(function() {
                    const planName = $(this).data('plan'); // Ej: "Mensual", "Trimestral", etc.
                    const precio = pricing[planName][rango];
                    // Animar el cambio del número en el elemento con clase "precio"
                    animateNumber($(this).find('.precio'), precio, 600);
                    // Actualizar el hidden con el id real del plan usando el mapeo
                    var planId = membershipMapping[planName][rango];
                    $(this).find('.membership_plan_id').val(planId);
                });
            }

            // Cuando se haga clic en un botón de rango
            $('.rango-btn').on('click', function() {
                $('.rango-btn').removeClass('active');
                $(this).addClass('active');
                const rango = $(this).data('range');
                actualizarPrecios(rango);
            });

            // Seleccionar el primer botón por defecto y actualizar
            $('.rango-btn').first().addClass('active');
            actualizarPrecios($('.rango-btn.active').data('range'));
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const membershipPlanSelect = document.getElementById('membership_plan_id');
            const fechaInicioInput = document.getElementById('fecha_inicio');
            const fechaExpiracionInput = document.getElementById('fecha_expiracionCreate');

            // Función para formatear la fecha (yyyy-mm-dd)
            function formatDate(date) {
                let year = date.getFullYear();
                let month = String(date.getMonth() + 1).padStart(2, '0');
                let day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Función para actualizar la fecha de expiración
            function actualizarFechaExpiracion() {
                // Obtener la opción seleccionada y la duración
                const selectedOption = membershipPlanSelect.options[membershipPlanSelect.selectedIndex];
                const duracion = selectedOption.getAttribute('data-duracion');

                console.log("Plan seleccionado:", selectedOption.value);
                console.log("Duración obtenida:", duracion);

                // Si no se encontró duración, vaciar y salir
                if (!duracion) {
                    fechaExpiracionInput.value = '';
                    return;
                }

                // Obtener la fecha de inicio
                const fechaInicioValue = fechaInicioInput.value;
                console.log("Fecha de inicio:", fechaInicioValue);
                if (!fechaInicioValue) return;

                let fechaInicio = new Date(fechaInicioValue);
                if (isNaN(fechaInicio.getTime())) {
                    console.error("La fecha de inicio no es válida");
                    return;
                }

                // Sumar la duración a la fecha de inicio
                const diasASumar = parseInt(duracion);
                console.log("Sumando días:", diasASumar);
                fechaInicio.setDate(fechaInicio.getDate() + diasASumar);

                // Actualizar el input de fecha de expiración
                const nuevaFechaExpiracion = formatDate(fechaInicio);
                console.log("Fecha de expiración calculada:", nuevaFechaExpiracion);
                fechaExpiracionInput.value = nuevaFechaExpiracion;
            }

            // Escuchar el cambio del select de plan
            membershipPlanSelect.addEventListener('change', actualizarFechaExpiracion);
            // Escuchar cambios en la fecha de inicio
            fechaInicioInput.addEventListener('change', actualizarFechaExpiracion);

            // Inicializar la fecha de expiración al cargar el modal (si hay un plan seleccionado)
            actualizarFechaExpiracion();
        });
    </script>

    {{-- Script para filtrar tabla --}}
    <script>
        $(document).ready(function() {
            $('#filtroUsuarios').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.admin-table tbody tr').each(function(index) {
                    // Saltar las filas de detalles (asumiendo que siempre siguen a una fila de datos)
                    if ($(this).find('.collapse-container').length > 0) {
                        return; // Saltar esta iteración
                    }

                    var userNameElement = $(this).find('.user-name');
                    var userEmailElement = $(this).find('.user-email');
                    var userName = userNameElement.text().toLowerCase();
                    var userEmail = userEmailElement.text().toLowerCase();
                    var $row = $(this);
                    var $detailsRow = $row.next('tr'); // La fila siguiente debería ser la de detalles

                    if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                        $row.show();
                        $detailsRow.show(); // Mostrar ambas filas
                    } else {
                        $row.hide();
                        $detailsRow.hide(); // Ocultar ambas filas si no coincide
                    }
                });
            });
        });
    </script>
    {{-- Fin script para filtrar tabla --}}
@endsection
