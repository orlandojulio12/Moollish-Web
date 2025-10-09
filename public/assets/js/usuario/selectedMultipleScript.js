    $(document).ready(function() {
        // Función para actualizar el mensaje de no selección
        function updateNoSelectedMessage() {
            if ($('.predios-seleccionados .predio').length > 0) {
                $('.predios-seleccionados .no-selected').hide(); 
            } else {
                $('.predios-seleccionados .no-selected').show(); 
            }
        }

        // Manejar el cambio en el select de predios
        $('#predios').on('change', function() {
            var selectedOption = $(this).find('option:selected'); 
            var id = selectedOption.val(); 
            var text = selectedOption.text(); 
            
            if (id && !selectedOption.is(':disabled')) { 
                // Agregar el predio al contenedor de predios seleccionados
                $('.predios-seleccionados').append(
                    '<span class="predio" data-id="' + id + '" style="cursor: pointer;">' +
                    text +
                    '</span>'
                );

                // Agregar un input oculto para enviar el predio en el formulario
                $('<input>').attr({
                    type: 'hidden',
                    name: 'predios[]',
                    value: id,
                    id: 'predio-input-' + id
                }).appendTo('#user-form');

                // Deshabilitar la opción seleccionada para evitar duplicados
                selectedOption.prop('disabled', true);
                
                // Resetear el select para permitir nuevas selecciones
                $(this).val(''); 

                updateNoSelectedMessage();
            }
        });

        // Manejar la eliminación de un predio seleccionado al hacer clic en él
        $('.predios-seleccionados').on('click', '.predio', function() {
            var parentSpan = $(this);
            var id = parentSpan.data('id'); 

            // Remover el input oculto correspondiente
            $('#predio-input-' + id).remove();

            // Habilitar de nuevo la opción en el select
            $('#predios option[value="' + id + '"]').prop('disabled', false); 

            // Remover el predio del contenedor
            parentSpan.remove(); 

            updateNoSelectedMessage();
        });

        // Inicializar el mensaje de no selección al cargar la página
        updateNoSelectedMessage();
    });