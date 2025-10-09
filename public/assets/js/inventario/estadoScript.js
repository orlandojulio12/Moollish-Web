$(document).ready(function() {
    $('#id_animal').change(function() {
        var animalId = $(this).val();

        if (animalId) {
            $.ajax({
                url: 'api/animal/' + animalId + '/estados',
                type: 'GET',
                success: function(response) {
                    console.log("Respuesta de la API:", response);

                    // Limpiar los selects antes de agregar opciones
                    $('#estado_productivo_id').html(
                        '<option value="">Seleccionar estado productivo</option>'
                    );
                    $('#estado_reproductivo_id').html(
                        '<option value="">Seleccionar estado reproductivo</option>'
                    ).prop('disabled', true); // Inicialmente deshabilitado

                    // Cargar los estados productivos
                    if (response.estadosProductivos.length > 0) {
                        $.each(response.estadosProductivos, function(index, estado) {
                            $('#estado_productivo_id').append(
                                '<option value="' + estado.id + '">' + estado.nombre + '</option>'
                            );
                        });
                    }

                    // Mostrar siempre el estado productivo
                    $('#estado_productivo_wrapper').show();
                },
                error: function() {
                    alert("Error al cargar los estados.");
                }
            });
        } else {
            // Si no hay animal seleccionado, limpiar y deshabilitar los selects
            $('#estado_productivo_id').html(
                '<option value="">Seleccionar estado productivo</option>'
            );
            $('#estado_reproductivo_id').html(
                '<option value="">Seleccionar estado reproductivo</option>'
            ).prop('disabled', true);

            $('#estado_productivo_wrapper').show(); // Siempre mostrar el estado productivo
            $('#estado_reproductivo_wrapper').hide();
        }
    });

    $('#estado_productivo_id').change(function() {
        var estadoProductivoId = $(this).val();
        var animalId = $('#id_animal').val();

        if (estadoProductivoId) {
            $.ajax({
                url: 'api/animal/' + animalId + '/estados/' + estadoProductivoId + '/reproductivo',
                type: 'GET',
                success: function(response) {
                    console.log("Estados reproductivos según el estado productivo:", response);

                    // Limpiar y cargar los estados reproductivos según la selección
                    $('#estado_reproductivo_id').html(
                        '<option value="">Seleccionar estado reproductivo</option>'
                    );

                    if (response && response.length > 0) {
                        $.each(response, function(index, estado) {
                            $('#estado_reproductivo_id').append(
                                '<option value="' + estado.id + '">' + estado.nombre + '</option>'
                            );
                        });
                        $('#estado_reproductivo_id').prop('disabled', false); // Habilitar reproductivo
                        $('#estado_reproductivo_wrapper').show();
                    } else {
                        $('#estado_reproductivo_id').html(
                            '<option value="">No hay estados reproductivos disponibles</option>'
                        ).prop('disabled', true);
                        $('#estado_reproductivo_wrapper').show(); // Mostrar aunque no haya opciones
                    }
                },
                error: function() {
                    console.log("Error al cargar los estados reproductivos.");
                }
            });
        } else {
            // Si no hay estado productivo seleccionado, deshabilitar reproductivo
            $('#estado_reproductivo_id').html(
                '<option value="">Seleccionar estado reproductivo</option>'
            ).prop('disabled', true);
            $('#estado_reproductivo_wrapper').hide();
        }
    });

    // Siempre mostrar el estado productivo por defecto, deshabilitar reproductivo
    $('#estado_productivo_wrapper').show();
    $('#estado_reproductivo_id').prop('disabled', true);
    $('#estado_reproductivo_wrapper').hide();
});
