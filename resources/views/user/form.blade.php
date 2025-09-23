<div class="row padding-1 p-1">
    <div class="col-md-12">
        <!-- Campo de selección del Rol -->
        <div class="form-group mb-2 mb20">
            <label for="id_rol" class="form-label">{{ __('Rol') }}</label>
            <select name="id_rol" class="form-control @error('id_rol') is-invalid @enderror" id="id_rol">
                <option value="" disabled selected>{{ __('Seleccione el Rol') }}</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ old('id_rol', $user?->id_rol) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('id_rol', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Campo del nombre -->
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Campo del correo -->
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <select name="estado" class="form-control @error('estado') is-invalid @enderror" id="estado" required>
                <option value="Activo" {{ old('estado', $user?->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('estado', $user?->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <!-- Campo de contraseña -->
        <div class="form-group mb-2 mb20">
            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
            <div class="input-group">
                <input type="password" name="password" class="form-control" va id="password" maxlength="12"
                    placeholder="Contraseña" oninput="validatePasswordStrength()">
                <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                    <i class="fas fa-eye" id="toggleIconPassword"></i>
                </span>
            </div>
            <small id="password-strength-text" class="form-text text-muted d-none">Fortaleza de la contraseña: <span
                    id="strength-level">Débil</span></small>
            <div id="password-strength-error" class="invalid-feedback d-none" role="alert">
                <!-- Mensaje de error para longitud insuficiente -->
            </div>
        </div>

        <!-- Campo de confirmación de contraseña -->
        <div class="form-group mb-2 mb20">
            <label for="password_confirmation" class="form-label">{{ __('Confirmar Contraseña') }}</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password_confirmation" maxlength="12"
                    placeholder="Confirmar Contraseña" oninput="validatePasswords()">
                <span class="input-group-text" id="togglePasswordConfirm" style="cursor: pointer;">
                    <i class="fas fa-eye" id="toggleIconPasswordConfirm"></i>
                </span>
            </div>
            <div id="password-match-error" class="invalid-feedback d-none" role="alert">
                <!-- Mensaje de error para contraseñas que no coinciden -->
            </div>
        </div>


        <div class="col-md-12 mt20 mt-2">
            <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
        </div>

    </div>
</div>




@section('scripts')

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
    <!-- Incluye el ícono de FontAwesome -->
    <script src="https://kit.fontawesome.com/d5ecd207a8.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    // Alternar visibilidad del campo de contraseña
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#password');
    const toggleIconPassword = document.querySelector('#toggleIconPassword');

    togglePassword.addEventListener('click', function () {
        // Cambiar el tipo de campo de contraseña entre password y text
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // Cambiar el ícono del ojo al ícono de ojo tachado cuando se muestra la contraseña
        toggleIconPassword.classList.toggle('fa-eye-slash');
    });

    // Alternar visibilidad del campo de confirmación de contraseña
    const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');
    const passwordConfirmField = document.querySelector('#password_confirmation');
    const toggleIconPasswordConfirm = document.querySelector('#toggleIconPasswordConfirm');

    togglePasswordConfirm.addEventListener('click', function () {
        // Cambiar el tipo de campo de confirmación de contraseña entre password y text
        const type = passwordConfirmField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmField.setAttribute('type', type);

        // Cambiar el ícono del ojo al ícono de ojo tachado cuando se muestra la contraseña
        toggleIconPasswordConfirm.classList.toggle('fa-eye-slash');
    });
</script>

    <script>
        // Validación de la fortaleza de la contraseña y restricción de caracteres
        function validatePasswordStrength() {
            const passwordInput = document.getElementById('password');
            const password = passwordInput ? passwordInput.value : ''; // Obtiene el valor de la contraseña
            const errorElement = document.getElementById('password-strength-error');
            const strengthText = document.getElementById('password-strength-text');
            const strengthLevel = document.getElementById('strength-level');

            // Si los elementos necesarios no existen, detener la ejecución
            if (!errorElement || !strengthText || !strengthLevel) {
                return;
            }

            // Mostrar mensaje si se excede el límite de 12 caracteres
            if (password.length > 12) {
                errorElement.classList.remove('d-none');
                errorElement.classList.add('d-block');
                errorElement.innerHTML = '<strong>La contraseña no puede tener más de 12 caracteres.</strong>';
                strengthText.classList.add('d-none');
                return;
            } else {
                errorElement.classList.add('d-none');
            }

            // Verificación de longitud mínima de 8 caracteres
            if (password.length < 8) {
                errorElement.classList.remove('d-none');
                errorElement.classList.add('d-block');
                errorElement.innerHTML = '<strong>La contraseña debe tener al menos 8 caracteres.</strong>';
                strengthText.classList.add(
                'd-none'); // Esconde la información de la fortaleza si no cumple con la longitud mínima
                return;
            } else {
                errorElement.classList.add('d-none');
            }

            // Expresión regular más flexible
            const weakPasswordRegex = /^[A-Za-z\d@$!%*?&]{8,}$/; // Cualquier combinación de letras, números, o símbolos
            const mediumPasswordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/; // Al menos una letra y un número
            const strongPasswordRegex =
            /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/; // Al menos una letra, un número y un carácter especial

            // Validar fortaleza y mostrar un texto descriptivo
            if (strongPasswordRegex.test(password)) {
                strengthText.classList.remove('d-none');
                strengthLevel.innerText = 'Fuerte';
                strengthLevel.style.color = 'green';
                errorElement.classList.add('d-none');
            } else if (mediumPasswordRegex.test(password)) {
                strengthText.classList.remove('d-none');
                strengthLevel.innerText = 'Media';
                strengthLevel.style.color = 'orange';
                errorElement.classList.add('d-none');
            } else if (weakPasswordRegex.test(password)) {
                strengthText.classList.remove('d-none');
                strengthLevel.innerText = 'Débil';
                strengthLevel.style.color = 'red';
                errorElement.classList.remove('d-none');
                errorElement.innerHTML = '<strong>La contraseña es débil. Se recomienda agregar más complejidad.</strong>';
            } else {
                strengthText.classList.add('d-none');
                errorElement.classList.remove('d-none');
                errorElement.innerHTML = '<strong>La contraseña no cumple con los requisitos mínimos.</strong>';
            }
        }

        // Validación para comparar si las contraseñas coinciden
        function validatePasswords() {
            const password = document.getElementById('password') ? document.getElementById('password').value : '';
            const confirmPassword = document.getElementById('password_confirmation') ? document.getElementById(
                'password_confirmation').value : '';
            const matchError = document.getElementById('password-match-error');

            // Asegurarse de que los elementos existan
            if (!matchError) {
                return;
            }

            // Mostrar error si las contraseñas no coinciden
            if (password !== confirmPassword && confirmPassword.length > 0) {
                matchError.classList.remove('d-none');
                matchError.classList.add('d-block');
                matchError.innerHTML = '<strong>Las contraseñas no coinciden.</strong>';
            } else {
                matchError.classList.add('d-none');
            }
        }
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('user-form');

        // Verifica si el formulario existe antes de agregar el event listener
        if (form) {
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;

                // Si no hay nada digitado en los campos de contraseña, no hacemos la validación
                if (password === "" && confirmPassword === "") {
                    return; // Permite el envío del formulario sin validación de contraseñas
                }

                // Expresión regular para la fortaleza de la contraseña
                const weakPasswordRegex = /^[A-Za-z\d@$!%*?&]{8,}$/; // Débil: solo necesita 8 caracteres
                const mediumPasswordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/; // Media: necesita al menos una letra y un número
                const strongPasswordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/; // Fuerte: necesita al menos una letra, un número, y un carácter especial

                // Verificar si la contraseña tiene al menos 8 caracteres
                if (password.length < 8) {
                    e.preventDefault(); // Evitar el envío del formulario
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La contraseña debe tener al menos 8 caracteres.',
                    });
                    return;
                }

                // Verificar si la contraseña es fuerte o media
                if (!strongPasswordRegex.test(password) && !mediumPasswordRegex.test(password)) {
                    // La contraseña es débil
                    e.preventDefault(); // Evitar el envío del formulario si la contraseña es débil
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'La contraseña es demasiado débil. Se recomienda agregar más complejidad.',
                    });
                    return;
                }

                // Verificar si las contraseñas coinciden
                if (password !== confirmPassword) {
                    e.preventDefault(); // Evitar el envío del formulario
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las contraseñas no coinciden.',
                    });
                    return;
                }
            });
        } else {
            console.error("El formulario con id 'user-form' no fue encontrado.");
        }
    });
</script>

@endsection
