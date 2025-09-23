{{-- Overlay para el fondo oscuro (podría ser el mismo overlay que el de creación si solo se muestra un modal a la vez) --}}
{{-- <div class="native-modal-overlay" id="nativeEditModalOverlay"></div> --}}

{{-- Contenedor principal del modal nativo para Edición --}}
{{-- Añadido style="display: none;" para evitar flash inicial --}}
<div class="native-modal" id="nativeEditUserModal" role="dialog" aria-labelledby="nativeEditUserModalLabel" aria-hidden="true" style="display: none;">

    {{-- La acción se establecerá dinámicamente con JS --}}
    <form id="edit-user-form" method="POST" action="" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT') {{-- Método HTTP para la actualización --}}
        <input type="hidden" id="edit_user_id" name="user_id"> {{-- Campo oculto para el ID del usuario --}}
        <input type="hidden" id="edit_remove_profile_photo" name="remove_profile_photo" value="0"> {{-- Campo para indicar si se debe eliminar la foto --}}

        <div class="native-modal-header">
            <h5 class="native-modal-title" id="nativeEditUserModalLabel">{{ __('Editar Usuario') }}</h5>
            {{-- Botón de cierre nativo --}}
            <button type="button" class="native-modal-close-btn native-modal-close" aria-label="Close">&times;</button>
        </div>

        <div class="native-modal-body">
            <div class="row g-3">

                 {{-- Columna Izquierda: Foto y Predios --}}
                <div class="col-md-5">
                    {{-- Foto de Perfil (Edición) --}}
                    <div class="mb-3 text-center">
                        <label class="form-label d-block">{{ __('Foto de Perfil') }}</label>
                        <img id="edit_profile_photo_preview"
                             src="{{ asset('assets/images/placeholder_avatar.png') }}" {{-- Se actualizará con JS --}}
                             alt="Previsualización"
                             class="img-thumbnail rounded-circle mb-2"
                             style="width: 120px; height: 120px; object-fit: cover; border-color: #e5e7eb;">
                        <div class="d-flex justify-content-center gap-2">
                             <label for="edit_profile_photo" class="btn btn-sm btn-outline-secondary">
                                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: text-bottom;">upload</span> Cambiar
                             </label>
                             <input type="file" name="profile_photo" id="edit_profile_photo" accept="image/*" class="d-none">
                             <button type="button" id="edit_eliminar_foto" class="btn btn-sm btn-outline-danger">
                                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: text-bottom;">delete</span> Quitar
                            </button>
                        </div>
                         {{-- No usamos @error aquí, los errores se mostrarán vía JS --}}
                         <div class="text-danger mt-1 edit-profile-photo-error" style="font-size: 0.875em; display: none;" role="alert"></div>
                    </div>
                    <hr>
                    {{-- Predios (Edición) --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('Asignar Predios') }}</label>
                        <div class="input-group mb-2">
                             {{-- Las opciones del selector se poblarán/usarán desde la vista principal --}}
                            <select id="edit-predio-selector" class="form-select">
                                <option value="" selected disabled>{{ __('Selecciona un predio..') }}</option>
                                {{-- Se asume que la variable $predios está disponible globalmente o se pasa al JS --}}
                                @if(isset($predios) && $predios->count() > 0)
                                    @foreach ($predios as $predio)
                                        <option value="{{ $predio->id }}" data-nombre="{{ $predio->nombre_predio }} ({{$predio->cod_predio ?? 'S/C'}})">
                                            {{ $predio->nombre_predio }} ({{$predio->cod_predio ?? 'S/C'}})
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>No hay predios disponibles para asignar</option>
                                @endif
                            </select>
                            <button type="button" id="edit-add-predio-btn" class="btn btn-outline-secondary">Añadir</button>
                        </div>
                         {{-- Contenedor para errores generales de predios (si es necesario) --}}
                         <div class="text-danger mt-1 d-block edit-predios-error" role="alert" style="font-size: 0.875em; display: none;"></div>

                        <div id="edit-selected-predios-container" class="mt-2" style="min-height: 50px; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 10px; background-color: #f8f9fa;">
                            {{-- Los badges se añadirán/quitarán con JS --}}
                            <span class="text-muted" id="edit-no-predios-selected" style="font-size: 0.9em;">Ningún predio seleccionado.</span>
                        </div>
                        {{-- Div oculto para almacenar los inputs hidden de predios (Edición) --}}
                        <div id="edit-hidden-predios-inputs">
                            {{-- Los inputs <input type="hidden" name="predios[]"> se añadirán/quitarán con JS --}}
                        </div>
                    </div>
                </div>

                 {{-- Columna Derecha: Datos del Usuario (Edición) --}}
                <div class="col-md-7">
                    {{-- Rol (Edición) --}}
                    <div class="mb-3">
                        <label for="edit_id_rol" class="form-label">{{ __('Rol') }} <span class="text-danger">*</span></label>
                         {{-- Se asume que $roles está disponible --}}
                        <select name="id_rol" class="form-select" id="edit_id_rol" required>
                            <option value="" disabled>{{ __('Seleccione...') }}</option>
                             @php
                                $rolesPermitidos = isset($roles) ? $roles->whereIn('name', ['tecnico', 'propietario','encuestador']) : collect();
                            @endphp
                            @foreach ($rolesPermitidos as $rol)
                                <option value="{{ $rol->id }}">
                                    {{ ucfirst($rol->name) }}
                                </option>
                            @endforeach
                        </select>
                         <div class="invalid-feedback" role="alert"></div>
                    </div>
                    {{-- Nombre (Edición) --}}
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">{{ __('Nombre Completo') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="edit_name" placeholder="{{ __('Ingrese el nombre') }}" required>
                         <div class="invalid-feedback" role="alert"></div>
                    </div>
                     {{-- Email (Edición) --}}
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">{{ __('Correo Electrónico') }} <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" id="edit_email" placeholder="{{ __('usuario@ejemplo.com') }}" required>
                         <div class="invalid-feedback" role="alert"></div>
                    </div>
                     <div class="row g-3">
                        {{-- Tipo Documento (Edición) --}}
                        <div class="col-md-6">
                            <label for="edit_tipo_documento" class="form-label">{{ __('Tipo Documento') }} <span class="text-danger">*</span></label>
                            <select name="tipo_documento" id="edit_tipo_documento" class="form-select" required>
                                <option value="" disabled>{{ __('Seleccione...') }}</option>
                                <option value="nit">Nit</option>
                                <option value="cedula">Cédula Ciudadanía</option>
                                <option value="cedula_extranjeria">Cédula Extranjería</option>
                                <option value="pasaporte">Pasaporte</option>
                            </select>
                             <div class="invalid-feedback" role="alert"></div>
                        </div>
                        {{-- Documento (Edición) --}}
                        <div class="col-md-6">
                            <label for="edit_documento" class="form-label">{{ __('Número Documento') }} <span class="text-danger">*</span></label>
                            <input type="number" name="documento" class="form-control" id="edit_documento" placeholder="{{ __('Ingrese número') }}" required>
                             <div class="invalid-feedback" role="alert"></div>
                        </div>
                    </div>
                    {{-- Estado (Edición) --}}
                     <div class="mb-3 mt-3">
                        <label for="edit_estado" class="form-label">{{ __('Estado') }} <span class="text-danger">*</span></label>
                        <select name="estado" id="edit_estado" class="form-select" required>
                             <option value="activo">Activo</option>
                             <option value="expirado">Expirado</option> {{-- O los estados que manejes --}}
                        </select>
                         <div class="invalid-feedback" role="alert"></div>
                    </div>
                    <hr>
                     {{-- Contraseña (Edición - Opcional) --}}
                    <div class="mt-3 mb-3">
                        <label for="edit_password" class="form-label">{{ __('Nueva Contraseña') }}</label>
                        <div class="input-group">
                             <input type="password" name="password" class="form-control" id="edit_password" placeholder="{{ __('Dejar en blanco para no cambiar') }}" autocomplete="new-password">
                             <button class="btn btn-outline-secondary" type="button" id="editTogglePassword" style="border-left: 0;">
                                <span class="material-symbols-outlined" id="editToggleIconPassword" style="font-size: 18px; vertical-align: middle;">visibility</span>
                             </button>
                             <div class="invalid-feedback d-block w-100" role="alert"></div> {{-- Para error de contraseña (min, etc.) --}}
                        </div>
                         <small class="form-text text-muted">Mínimo 8 caracteres si se ingresa una nueva.</small>
                    </div>
                     {{-- Confirmar Contraseña (Edición - Opcional) --}}
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">{{ __('Confirmar Nueva Contraseña') }}</label>
                        <div class="input-group">
                             <input type="password" class="form-control" name="password_confirmation" id="edit_password_confirmation" placeholder="{{ __('Repetir nueva contraseña') }}" autocomplete="new-password">
                             <button class="btn btn-outline-secondary" type="button" id="editTogglePasswordConfirm" style="border-left: 0;">
                                <span class="material-symbols-outlined" id="editToggleIconPasswordConfirm" style="font-size: 18px; vertical-align: middle;">visibility</span>
                             </button>
                              <div class="invalid-feedback d-block w-100" role="alert"></div> {{-- Para error de confirmación --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="native-modal-footer">
            <button type="button" class="btn btn-secondary native-modal-close">{{ __('Cerrar') }}</button>
            <button type="submit" class="btn-action"> {{-- Mismo estilo naranja --}}
                 <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">update</span> {{ __('Actualizar Usuario') }}
             </button>
        </div>
    </form>
</div>
