<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="row padding-1 p-1">
    <div class="col-md-12">
        <h3>Información del Predio</h3>
        <hr>
        <div class="col-md-12">
            <div class="form-group">
                <label for="propietario_">Propietarios:</label>
                <select class="form-control" id="propietario_" name="propietario_selector">
                    <option disabled selected value="">Seleccione un propietario</option>
                    @foreach ($propietarios as $propietario)
                        <option value="{{ $propietario->id }}"
                            {{ (isset($Predios) && $Predios->usuarios->contains('id', $propietario->id)) ? 'disabled' : '' }}>
                            {{ $propietario->name }}
                        </option>
                    @endforeach
                </select>


                <div class="selected-propietarios-container">
                    @if(isset($Predios) && $Predios->usuarios->isNotEmpty())
                        @foreach($Predios->usuarios as $usuario)
                            <div class="selected-propietario" data-id="{{ $usuario->id }}">
                                {{ $usuario->name }}
                                <span class="material-symbols-outlined icon-close icon-size-custom" style="cursor:pointer; font-size:14px;">close</span>
                            </div>
                            <input type="hidden" name="id_usuario[]" value="{{ $usuario->id }}" id="propietario-input-{{ $usuario->id }}">
                        @endforeach
                    @else
                        <span class="no-selected">Selecciona un propietario</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <div class="form-group mb-2 mb20">
                    <label for="cod_predio" class="form-label">Código del Predio*</label>
                    <input type="text" name="cod_predio"
                        class="form-control @error('cod_predio') is-invalid @enderror"
                        value="{{ old('cod_predio', $Predios?->cod_predio) }}" id="cod_predio"
                        placeholder="Código del Predio">
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group mb-2 mb20">
                    <label for="nombre_predio" class="form-label">Nombre del Predio</label>
                    <input type="text" name="nombre_predio"
                        class="form-control @error('nombre_predio') is-invalid @enderror"
                        value="{{ old('nombre_predio', $Predios?->nombre_predio) }}" id="nombre_predio"
                        placeholder="Nombre del Predio">
                </div>
            </div>
        </div>

        <br>
        <!-- Resto del formulario de Ubicación y demás campos -->
        <div class="row">
            <h3>Ubicación</h3>
            <hr>
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="departamento" class="form-label">Departamento*</label>
                    <select id="departamento" class="form-control @error('departamento') is-invalid @enderror" name="departamento" required>
                        <option value="" selected>Seleccione Departamento...</option>
                        <option value="{{ old('departamento') }}" selected hidden>{{ old('departamento') }}</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="municipio" class="form-label">Municipio*</label>
                    <select id="municipio" class="form-control @error('municipio') is-invalid @enderror" name="municipio" required>
                        <option value="" selected>Seleccione Municipio...</option>
                        <option value="{{ old('municipio') }}" selected hidden>{{ old('municipio') }}</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="vereda" class="form-label">Vereda</label>
                    <input type="text" name="vereda" class="form-control @error('vereda') is-invalid @enderror"
                        value="{{ old('vereda', $Predios?->vereda) }}" id="vereda" placeholder="Vereda">
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group mb-2 mb20">
                <label for="forma_de_llegar" class="form-label">Forma de Llegar</label>
                <input type="text" name="forma_de_llegar"
                    class="form-control @error('forma_de_llegar') is-invalid @enderror"
                    value="{{ old('forma_de_llegar', $Predios?->forma_de_llegar) }}" id="forma_de_llegar"
                    placeholder="Forma de Llegar">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="latitud" class="form-label">Latitud</label>
                    <input type="number" name="latitud" class="form-control @error('latitud') is-invalid @enderror"
                        value="{{ old('latitud', $Predios?->latitud) }}" id="latitud" placeholder="Ejemplo: 3.4516">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="longitud" class="form-label">Longitud</label>
                    <input type="number" name="longitud" class="form-control @error('longitud') is-invalid @enderror"
                        value="{{ old('longitud', $Predios?->longitud) }}" id="longitud"
                        placeholder="Ejemplo: 123.4567">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Valores seleccionados por defecto (para edición)
    let selectedDepartamento = "{{ old('departamento', $Predios?->departamento) }}";
    let selectedMunicipio = "{{ old('municipio', $Predios?->municipio) }}";

    // Cargar departamentos
    fetch('https://api-colombia.com/api/v1/Department')
        .then(response => response.json())
        .then(data => {
            let selectDepartamento = document.getElementById('departamento');
            let selectMunicipio = document.getElementById('municipio');

            // Agregar departamentos al select
            data.forEach(item => {
                let option = document.createElement('option');
                option.text = item.name;
                option.value = item.name;
                option.dataset.id = item.id; // Guardamos el ID del departamento para usarlo después
                selectDepartamento.add(option);

                if (item.name === selectedDepartamento) {
                    option.selected = true;
                }
            });

            selectDepartamento.addEventListener('change', function() {
                selectMunicipio.innerHTML =
                    '<option value="" selected>Seleccione Municipio...</option>'; // Limpiar los municipios
                let selectedOption = this.options[this.selectedIndex];
                let selectedDepId = selectedOption.dataset
                    .id; // Obtener el ID del departamento seleccionado

                if (selectedDepId) {
                    // Cargar municipios del departamento seleccionado
                    fetch(`https://api-colombia.com/api/v1/Department/${selectedDepId}/cities`)
                        .then(response => response.json())
                        .then(cityData => {
                            cityData.forEach(city => {
                                let option = document.createElement('option');
                                option.text = city.name;
                                option.value = city.name;
                                selectMunicipio.add(option);

                                // Si el municipio seleccionado coincide con el que está en la base de datos, seleccionarlo
                                if (city.name === selectedMunicipio) {
                                    option.selected = true;
                                }
                            });
                        });
                }
            });
            // Si hay un departamento seleccionado, cargar las ciudades correspondientes
            if (selectedDepartamento) {
                selectDepartamento.dispatchEvent(new Event('change'));
            }
        });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const codPredioInput = document.getElementById('cod_predio');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Asegúrate de incluir esta meta en tu layout
        const feedbackDiv = document.createElement('div');
        feedbackDiv.className = 'form-text mt-1';

        codPredioInput.addEventListener('input', function () {
            const codPredio = this.value;

            // Si el campo está vacío, eliminar mensajes y clases
            if (codPredio.trim() === '') {
                codPredioInput.classList.remove('is-invalid', 'is-valid');
                feedbackDiv.textContent = '';
                return;
            }

            fetch('{{ route("predios.verificarCodigo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ cod_predio: codPredio })
            })
            .then(response => response.json())
            .then(data => {
                if (!codPredioInput.nextElementSibling || codPredioInput.nextElementSibling !== feedbackDiv) {
                    codPredioInput.insertAdjacentElement('afterend', feedbackDiv);
                }

                if (data.exists) {
                    // Mensaje para código ya en uso
                    feedbackDiv.textContent = "Este código ya está en uso. Por favor, elige otro.";
                    feedbackDiv.style.color = 'red';
                    codPredioInput.classList.add('is-invalid');
                    codPredioInput.classList.remove('is-valid');
                } else {
                    // Mensaje para código disponible
                    feedbackDiv.textContent = "El código está disponible para uso.";
                    feedbackDiv.style.color = 'green';
                    codPredioInput.classList.add('is-valid');
                    codPredioInput.classList.remove('is-invalid');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                feedbackDiv.textContent = "Hubo un error al verificar el código. Inténtalo más tarde.";
                feedbackDiv.style.color = 'orange';
            });
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Función para actualizar el mensaje "no seleccionado"
    function updateNoSelectedPropietarioMessage() {
        if ($('.selected-propietario').length > 0) {
            $('.selected-propietarios-container .no-selected').hide();
        } else {
            $('.selected-propietarios-container .no-selected').show();
        }
    }

    // Al seleccionar un propietario del select
    $('#propietario_').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var id = selectedOption.val();
        var text = selectedOption.text();
        if (!selectedOption.is(':disabled')) {
            // Agregar un input hidden para enviar el propietario seleccionado
            $('#predioForm').append('<input type="hidden" name="id_usuario[]" value="' + id + '" id="propietario-input-' + id + '">');

            // Mostrar visualmente el propietario seleccionado
            $('.selected-propietarios-container').append(
                '<div class="selected-propietario" data-id="' + id + '">' +
                    text +
                    ' <span class="material-symbols-outlined icon-close icon-size-custom" style="cursor:pointer; font-size:14px;">close</span>' +
                '</div>'
            );
            // Deshabilitar la opción ya seleccionada para evitar duplicados
            selectedOption.prop('disabled', true);
            $(this).val(''); // Reinicia el select

            updateNoSelectedPropietarioMessage();
        }
    });

    // Al hacer clic en el ícono de cierre para remover un propietario seleccionado
    $('.selected-propietarios-container').on('click', '.icon-close', function() {
        var parentDiv = $(this).closest('.selected-propietario');
        var id = parentDiv.data('id');
        // Remover el input hidden correspondiente
        $('#propietario-input-' + id).remove();
        // Habilitar nuevamente la opción en el select
        $('#propietario_ option[value="' + id + '"]').prop('disabled', false);
        parentDiv.remove();
        updateNoSelectedPropietarioMessage();
    });

    // Validar que se haya seleccionado al menos un propietario antes de enviar el formulario
    $('#predioForm').on('submit', function(e) {
        if ($('input[name="id_usuario[]"]').length === 0) {
            alert('Debes seleccionar al menos un propietario.');
            e.preventDefault();
        }
    });

    updateNoSelectedPropietarioMessage();
});
</script>

