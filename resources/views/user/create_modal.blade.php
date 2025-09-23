{{-- Overlay para el fondo oscuro --}}
<div class="native-modal-overlay" id="nativeModalOverlay"></div>

{{-- Contenedor principal del modal nativo --}}
{{-- Añadido style="display: none;" para evitar flash inicial --}}
<div class="native-modal" id="nativeUserModal" role="dialog" aria-labelledby="nativeUserModalLabel" aria-hidden="true" style="display: none;">

    <form id="user-form" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="native-modal-header">
            <h5 class="native-modal-title" id="nativeUserModalLabel">{{ __('Crear Nuevo Usuario') }}</h5>
            {{-- Botón de cierre nativo --}}
            <button type="button" class="native-modal-close-btn native-modal-close" aria-label="Close">&times;</button>
        </div>

        <div class="native-modal-body">
            <div class="row g-3"> {{-- Usar clases de Bootstrap para layout si Bootstrap CSS está cargado --}}

                 {{-- Columna Izquierda: Foto y Predios --}}
                <div class="col-md-5">
                    {{-- Foto de Perfil --}}
                    <div class="mb-3 text-center">
                        <label class="form-label d-block">{{ __('Foto de Perfil') }}</label>
                        <img id="profile_photo_preview"
                             src="{{ asset('assets/images/placeholder_avatar.png') }}"
                             alt="Previsualización"
                             class="img-thumbnail rounded-circle mb-2"
                             style="width: 120px; height: 120px; object-fit: cover; border-color: #e5e7eb;">
                        <div class="d-flex justify-content-center gap-2">
                             <label for="profile_photo" class="btn btn-sm btn-outline-secondary">
                                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: text-bottom;">upload</span> Cargar
                             </label>
                             <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="d-none">
                             <button type="button" id="eliminar_foto" class="btn btn-sm btn-outline-danger">
                                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: text-bottom;">delete</span> Quitar
                            </button>
                        </div>
                         @error('profile_photo')
                            <div class="text-danger mt-1" style="font-size: 0.875em;"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                    <hr>
                    {{-- Predios --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('Asignar Predios') }}</label>
                        {{-- Selector Simple y Botón Añadir --}}
                        <div class="input-group mb-2">
                            <select id="predio-selector" class="form-select @error('predios') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Selecciona un predio...') }}</option>
                                @if(isset($predios) && $predios->count() > 0)
                                    @foreach ($predios as $predio)
                                        <option value="{{ $predio->id }}" data-nombre="{{ $predio->nombre_predio }} ({{$predio->cod_predio ?? 'S/C'}})">
                                            {{ $predio->nombre_predio }} ({{$predio->cod_predio ?? 'S/C'}})
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>No hay predios disponibles</option>
                                @endif
                            </select>
                            <button type="button" id="add-predio-btn" class="btn btn-outline-secondary">Añadir</button>
                        </div>
                         @error('predios') {{-- Mensaje de error general para la selección --}}
                            <div class="text-danger mt-1 d-block" role="alert" style="font-size: 0.875em;"><strong>{{ $message }}</strong></div>
                         @enderror
                        {{-- Contenedor para mostrar predios seleccionados --}}
                        <div id="selected-predios-container" class="mt-2" style="min-height: 50px; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 10px; background-color: #f8f9fa;">
                            {{-- Aquí se añadirán los badges de los predios seleccionados --}}
                            <span class="text-muted" id="no-predios-selected" style="font-size: 0.9em;">Ningún predio seleccionado.</span>
                        </div>
                        {{-- Div oculto para almacenar los inputs hidden --}}
                        <div id="hidden-predios-inputs">
                            {{-- Aquí se añadirán los <input type="hidden" name="predios[]"> --}}
                             {{-- Re-poblar inputs ocultos si hay errores de validación y old('predios') existe --}}
                            @if(is_array(old('predios')))
                                @foreach(old('predios') as $oldPredioId)
                                    @php
                                        $oldPredio = $predios->firstWhere('id', $oldPredioId);
                                    @endphp
                                    @if($oldPredio)
                                        <input type="hidden" name="predios[]" value="{{ $oldPredio->id }}">
                                    @endif
                                @endforeach
                             @endif
                        </div>
                        {{-- <small class="form-text text-muted">{{ __('Mantén Ctrl/Cmd para seleccionar varios.') }}</small> --}} {{-- Instrucción eliminada --}}
                    </div>
                </div>

                 {{-- Columna Derecha: Datos del Usuario --}}
                <div class="col-md-7">
                    {{-- Rol --}}
                    <div class="mb-3">
                        <label for="id_rol" class="form-label">{{ __('Rol') }} <span class="text-danger">*</span></label>
                        <select name="id_rol" class="form-select @error('id_rol') is-invalid @enderror" id="id_rol" required>
                            <option value="" disabled selected>{{ __('Seleccione...') }}</option>
                             @php
                                $rolesPermitidos = isset($roles) ? $roles->whereIn('name', ['tecnico', 'propietario','encuestador']) : collect([ (object)['id' => 5, 'name' => 'tecnico'] ]);
                            @endphp
                            @foreach ($rolesPermitidos as $rol)
                                <option value="{{ $rol->id }}" {{ old('id_rol') == $rol->id ? 'selected' : '' }}>
                                    {{ ucfirst($rol->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_rol')
                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Nombre Completo') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" id="name" placeholder="{{ __('Ingrese el nombre') }}" required>
                        @error('name')
                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                     {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Correo Electrónico') }} <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" id="email" placeholder="{{ __('usuario@ejemplo.com') }}" required>
                        @error('email')
                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                     <div class="row g-3">
                        {{-- Tipo Documento --}}
                        <div class="col-md-6">
                            <label for="tipo_documento" class="form-label">{{ __('Tipo Documento') }} <span class="text-danger">*</span></label>
                            <select name="tipo_documento" id="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror" required>
                                <option value="" disabled {{ old('tipo_documento') ? '' : 'selected' }}>{{ __('Seleccione...') }}</option>
                                <option value="nit" {{ old('tipo_documento') == 'nit' ? 'selected' : '' }}>Nit</option>
                                <option value="cedula" {{ old('tipo_documento') == 'cedula' ? 'selected' : '' }}>Cédula Ciudadanía</option>
                                <option value="cedula_extranjeria" {{ old('tipo_documento') == 'cedula_extranjeria' ? 'selected' : '' }}>Cédula Extranjería</option>
                                <option value="pasaporte" {{ old('tipo_documento') == 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                            </select>
                            @error('tipo_documento')
                                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        {{-- Documento --}}
                        <div class="col-md-6">
                            <label for="documento" class="form-label">{{ __('Número Documento') }} <span class="text-danger">*</span></label>
                            <input type="number" name="documento" class="form-control @error('documento') is-invalid @enderror" value="{{ old('documento') }}" id="documento" placeholder="{{ __('Ingrese número') }}" required>
                            @error('documento')
                                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>
                     {{-- Contraseña --}}
                    <div class="mt-3 mb-3">
                        <label for="password" class="form-label">{{ __('Contraseña') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                             <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="{{ __('Mínimo 8 caracteres') }}" required autocomplete="new-password">
                             <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-left: 0;">
                                <span class="material-symbols-outlined" id="toggleIconPassword" style="font-size: 18px; vertical-align: middle;">visibility</span>
                             </button>
                             @error('password')
                                <div class="invalid-feedback d-block w-100" role="alert"><strong>{{ $message }}</strong></div>
                             @enderror
                        </div>
                        <small class="form-text text-muted">Debe tener al menos 8 caracteres.</small>
                    </div>
                     {{-- Confirmar Contraseña --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirmar Contraseña') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                             <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" placeholder="{{ __('Repetir contraseña') }}" required autocomplete="new-password">
                             <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm" style="border-left: 0;">
                                <span class="material-symbols-outlined" id="toggleIconPasswordConfirm" style="font-size: 18px; vertical-align: middle;">visibility</span>
                             </button>
                              @error('password_confirmation')
                                  <div class="invalid-feedback d-block w-100" role="alert"><strong>{{ $message }}</strong></div>
                             @enderror
                        </div>
                    </div>

                    {{-- Input oculto para el estado --}}
                    <input type="hidden" name="estado" value="activo">
                </div>
            </div>
        </div>

        <div class="native-modal-footer">
            <button type="button" class="btn btn-secondary native-modal-close">{{ __('Cerrar') }}</button>
            <button type="submit" class="btn-action"> {{-- Estilo naranja --}}
                 <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">save</span> {{ __('Guardar Usuario') }}
             </button>
        </div>
    </form>
</div>
