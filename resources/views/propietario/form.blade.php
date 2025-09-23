<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="nombre_completo" class="form-label">{{ __('Nombre Completo') }}</label>

            @if(Auth::check() && Auth::user()->role->name == 'admin')
            <!-- Si el rol es admin, mostrar un select con todos los usuarios propietarios -->
            <select name="nombre_completo" class="form-control @error('nombre_completo') is-invalid @enderror" id="nombre_completo">
                <option value="">Seleccione un propietario</option>

                @foreach($propietarios as $user)
                    <!-- Mostrar el nombre en el select -->
                    <option value="{{ $user->name }}" data-id="{{ $user->id }}" {{ old('nombre_completo') == $user->name ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @elseif(Auth::check() && Auth::user()->role->name == 'propietario')
                <!-- Si el rol es propietario, mostrar un input de texto no editable -->
                <input type="text" name="nombre_completo" class="form-control @error('nombre_completo') is-invalid @enderror" value="{{ auth()->user()->name }}" readonly>
                <input type="hidden" name="id_user" value="{{ auth()->user()->id }}"> <!-- ID del usuario autenticado -->
            @else
                <!-- Si no es admin ni propietario, mostrar un input de texto normal (si lo necesitas) -->
                <input type="text" name="nombre_completo" class="form-control @error('nombre_completo') is-invalid @enderror" value="{{ old('nombre_completo', $propietario?->nombre_completo) }}" id="nombre_completo" placeholder="Nombre Completo">
            @endif

            {!! $errors->first(
                'nombre_completo',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>


        <div class="form-group mb-2 mb20">
            <label for="tipo_doc" class="form-label">{{ __('Tipos de Documento') }}</label>
            <select name="tipo_doc" class="form-control @error('tipo_doc') is-invalid @enderror" id="tipo_doc">
                <option value="">Seleccione</option>
                <option value="CC">CC</option>
                <option value="CE">CE</option>
                <option value="NIT">NIT</option>
                <option value="OTRO">OTRO</option>
            </select>
            {!! $errors->first('tipo_doc', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="num_doc" class="form-label">{{ __('Num Doc') }}</label>
            <input type="number" name="num_doc" class="form-control @error('num_doc') is-invalid @enderror"
                value="{{ old('num_doc', $propietario?->num_doc) }}" id="num_doc" placeholder="Num Doc">
            {!! $errors->first('num_doc', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="genero" class="form-label">{{ __('Genero') }}</label>
            <select name="genero" class="form-control @error('genero') is-invalid @enderror" id="genero">
                <option value="">Seleccione</option>
                <option value="M">M</option>
                <option value="F">F</option>
                <option value="OTRO">OTRO</option>
            </select>
            {!! $errors->first('genero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="correo_electronico" class="form-label">{{ __('Correo Electronico') }}</label>
            <input type="email" name="correo_electronico"
                class="form-control @error('correo_electronico') is-invalid @enderror"
                value="{{ old('correo_electronico', $propietario?->correo_electronico) }}" id="correo_electronico"
                placeholder="Correo Electronico">
            {!! $errors->first(
                'correo_electronico',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="telefono" class="form-label">{{ __('Telefono') }}</label>
            <input type="number" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                value="{{ old('telefono', $propietario?->telefono) }}" id="telefono" placeholder="Telefono">
            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPropietario = document.getElementById('nombre_completo');
        const hiddenUserId = document.getElementById('id_user_hidden');

        // Detectar cambios en el select y actualizar el campo hidden con el ID del usuario seleccionado
        if (selectPropietario) {
            selectPropietario.addEventListener('change', function() {
                // Obtener el valor del nombre seleccionado
                const selectedOption = selectPropietario.options[selectPropietario.selectedIndex];

                // Obtener el atributo data-id que contiene el ID del usuario seleccionado
                const selectedUserId = selectedOption.getAttribute('data-id');

                // Asignar el ID al campo hidden
                hiddenUserId.value = selectedUserId;
            });
        }
    });
</script>


