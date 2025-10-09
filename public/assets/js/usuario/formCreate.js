    $(document).ready(function() {
        // Abrir el modal al hacer clic en el botón "Crear Usuario"
        $('#openCreateUserModal').click(function() {
            $('#createUserModal').modal('show');
        });

        // Limpiar el formulario al cerrar el modal
        $('#createUserModal').on('hidden.bs.modal', function () {
            $('#user-form')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#password-strength-text').addClass('d-none');
            $('#password-strength-text').text('');
        });

        // Mostrar/Ocultar contraseña
        $('#togglePassword').click(function() {
            let passwordField = $('#password');
            let icon = $('#toggleIconPassword');
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $('#togglePasswordConfirm').click(function() {
            let passwordConfirmField = $('#password_confirmation');
            let icon = $('#toggleIconPasswordConfirm');
            if (passwordConfirmField.attr('type') === 'password') {
                passwordConfirmField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordConfirmField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Función para validar la fortaleza de la contraseña
        window.validatePasswordStrength = function() {
            let password = $('#password').val();
            let strengthLevel = $('#strength-level');
            let strengthText = $('#password-strength-text');

            let strength = 0;

            if (password.length >= 8) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[\W]/)) strength += 1;

            switch(strength) {
                case 0:
                case 1:
                case 2:
                    strengthLevel.text('Débil');
                    strengthText.removeClass('d-none').removeClass('text-warning').removeClass('text-success').addClass('text-danger');
                    break;
                case 3:
                case 4:
                    strengthLevel.text('Media');
                    strengthText.removeClass('d-none').removeClass('text-danger').removeClass('text-success').addClass('text-warning');
                    break;
                case 5:
                    strengthLevel.text('Fuerte');
                    strengthText.removeClass('d-none').removeClass('text-danger').removeClass('text-warning').addClass('text-success');
                    break;
            }

            if (strength < 3) {
                $('#password').addClass('is-invalid');
                if ($('#password').next('.invalid-feedback').length === 0) {
                    $('#password').after('<div class="invalid-feedback"><strong>La contraseña es demasiado débil.</strong></div>');
                }
            } else {
                $('#password').removeClass('is-invalid');
                $('#password').next('.invalid-feedback').remove();
            }
        };

        // Función para validar que las contraseñas coincidan
        window.validatePasswords = function() {
            let password = $('#password').val();
            let passwordConfirm = $('#password_confirmation').val();

            if (password !== passwordConfirm) {
                $('#password_confirmation').addClass('is-invalid');
                if ($('#password_confirmation').next('.invalid-feedback').length === 0) {
                    $('#password_confirmation').after('<div class="invalid-feedback"><strong>Las contraseñas no coinciden.</strong></div>');
                }
            } else {
                $('#password_confirmation').removeClass('is-invalid');
                $('#password_confirmation').next('.invalid-feedback').remove();
            }
        };
    });
