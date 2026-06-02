$(document).ready(function() {
    function checkInputValue(input) {
        if ($(input).val()) {
            $(input).addClass('filled');
        } else {
            $(input).removeClass('filled');
        }
    }
    $('input, select').each(function() {
        checkInputValue(this);
    });

    $('#id_animal').change(function() {
        var id_animal = $(this).val(); // Obtener el valor seleccionado
        if (id_animal) {
            $.ajax({
                url: '/api/animal/parto/' + id_animal,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        $('#id_predio').val(response.animal.id_predio);
                        $('#identificacion_electronica').val(response.animal
                            .identificacion_electronica);
                        $('#id_sinigan').val(response.animal.id_sinigan);
                        $('#fecha_ingreso_hato').val(response.animal
                            .fecha_ingreso_hato);
                        $('#padre').val(response.animal.padre);
                        $('#madre').val(response.animal.madre);
                        $('#raza_madre').val(response.animal.raza_madre);
                        $('#raza_padre').val(response.animal.raza_padre);
                        if (response.ultimoParto == null) {
                            $('#fecha_ultimo_parto').val('');
                        }
                        else
                        {
                            $('#fecha_ultimo_parto').val(response.ultimoParto.fecha_parto);
                        }
                        $('#fecha_ultimo_tacto').val(response.ultimoTacto.fecha);
                        $('#iep').val(response.IEP);


                        if (response.movimientos && response.movimientos.length > 0) {
                            // 1. Encontrar el último movimiento general
                            var ultimoMovimiento = response.movimientos.reduce((latest, current) => {
                                return new Date(current.fecha_movimiento) > new Date(latest.fecha_movimiento) ? current : latest;
                            });

                            // 2. Encontrar el último movimiento en el que lote no sea null
                            var ultimoMovimientoConLote = response.movimientos
                                .filter(mov => mov.lote) // Filtrar solo los movimientos con lote no nulo
                                .reduce((latest, current) => {
                                    return new Date(current.fecha_movimiento) > new Date(latest.fecha_movimiento) ? current : latest;
                                }, { fecha_movimiento: '1970-01-01T00:00:00Z' }); // Fecha inicial muy antigua

                            // 3. Encontrar el último movimiento en el que potrero no sea null
                            var ultimoMovimientoConPotrero = response.movimientos
                                .filter(mov => mov.potrero) // Filtrar solo los movimientos con potrero no nulo
                                .reduce((latest, current) => {
                                    return new Date(current.fecha_movimiento) > new Date(latest.fecha_movimiento) ? current : latest;
                                }, { fecha_movimiento: '1970-01-01T00:00:00Z' }); // Fecha inicial muy antigua

                            // Asignar los valores para el movimiento más reciente de cada campo
                            var loteActual = ultimoMovimientoConLote.lote ? ultimoMovimientoConLote.lote.nombre : 'Ninguno';
                            var potreroActual = ultimoMovimientoConPotrero.potrero ? ultimoMovimientoConPotrero.potrero.nombre : 'Ninguno';
                            var fechaUbicacionLote = ultimoMovimientoConLote.fecha_movimiento || '';
                            var fechaUbicacionPotrero = ultimoMovimientoConPotrero.fecha_movimiento || '';

                            // Asignar los valores a los campos de entrada en el HTML
                            $('#lote').val(loteActual);
                            $('#fecha_ubicacion_lote').val(fechaUbicacionLote);
                            $('#potrero').val(potreroActual);
                            $('#fecha_ubicacion_potrero').val(fechaUbicacionPotrero);
                        } else {
                            // Limpiar los campos si no hay movimientos
                            $('#lote').val('');
                            $('#fecha_ubicacion_lote').val('');
                            $('#potrero').val('');
                            $('#fecha_ubicacion_potrero').val('');
                        }


                        if (response.pesajes && response.pesajes.length > 0) {
                            var pesajes = response.pesajes[0];
                            $('#ultimo_peso_fecha').val(pesajes.fecha_pesaje);
                            $('#peso_madre').val(pesajes.peso);
                        } else {
                            $('#ultimo_peso_fecha').val('');
                            $('#peso_madre').val(response.animal.peso);
                        }

                        checkInputValue('#fecha_ingreso_hato');
                        checkInputValue('#id_sinigan');
                        checkInputValue('#identificacion_electronica');
                        checkInputValue('#peso_madre');
                        checkInputValue('#id_predio');
                        checkInputValue('#hierro');
                        checkInputValue('#lote');
                        checkInputValue('#ultimo_peso_fecha');
                        checkInputValue('#ultimo_peso_cantidad');
                        checkInputValue('#fecha_inicio');
                        checkInputValue('#fecha_fin');
                        checkInputValue('#estado_productivo');
                        checkInputValue('#estado_reproductivo');
                        checkInputValue('#fecha_ultimo_parto');
                        checkInputValue('#fecha_ultimo_tacto');

                    } else {
                        alert(response.message);
                        limpiarCampos();
                    }
                },
                error: function() {
                    alert('Error al obtener los datos del animal.');
                    limpiarCampos();
                }
            });
        } else {
            limpiarCampos();
        }
    });

    function limpiarCampos() {
        $('#nombre_animal').val('');
        $('#fecha_nacimiento').val('');
        $('#sexo').val('');
        $('#raza').val('');
        $('#color').val('');
        $('#hierro').val('');
        $('#id_predio').val('');
        $('#codigo').val('');
        $('#identificacion_electronica').val('');
        $('#id_sinigan').val('');
        $('#fecha_ingreso_hato').val('');
        $('#raza_madre').val('');
        $('#raza_padre').val('');
        $('#padre').val('');
        $('#madre').val('');
        $('#fecha_ultimo_parto').val('');
        $('#fecha_ultimo_tacto').val('');
        $('#lote').val('');
        $('#potrero').val('');
        $('#fecha_ubicacion').val('');
    }
    $('input, select').on('input change', function() {
        checkInputValue(this);
    });

    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    const anosInput = document.getElementById('anos');
    const mesesInput = document.getElementById('meses');
    const diasInput = document.getElementById('dias');

    const calcularFechaNacimiento = () => {
        const hoy = new Date();
        const edadAnios = parseInt(anosInput.value) || 0;
        const edadMeses = parseInt(mesesInput.value) || 0;
        const edadDias = parseInt(diasInput.value) || 0;

        let fechaNacimiento = new Date(hoy);
        fechaNacimiento.setFullYear(hoy.getFullYear() - edadAnios);
        fechaNacimiento.setMonth(hoy.getMonth() - edadMeses);
        fechaNacimiento.setDate(hoy.getDate() - edadDias);

        fechaNacimientoInput.value = fechaNacimiento.toISOString().split('T')[0];
    };


    anosInput.addEventListener('input', calcularFechaNacimiento);
    mesesInput.addEventListener('input', calcularFechaNacimiento);
    diasInput.addEventListener('input', calcularFechaNacimiento);
});
