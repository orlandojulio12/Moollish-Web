$(document).ready(function () {
    function checkInputValue(input) {
        if ($(input).val()) {
            $(input).addClass('filled');
        } else {
            $(input).removeClass('filled');
        }
    }

    $('input, select').each(function () {
        checkInputValue(this);
    });

    const calcularEdad = () => {
        const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
        const anosInput = document.getElementById('anos');
        const mesesInput = document.getElementById('meses');
        const diasInput = document.getElementById('dias');

        const fechaNacimiento = new Date(fechaNacimientoInput.value);
        const hoy = new Date();
        let edadAnios = hoy.getFullYear() - fechaNacimiento.getFullYear();
        let edadMeses = hoy.getMonth() - fechaNacimiento.getMonth();
        let edadDias = hoy.getDate() - fechaNacimiento.getDate();

        if (edadDias < 0) {
            edadDias += new Date(hoy.getFullYear(), hoy.getMonth(), 0).getDate();
            edadMeses--;
        }
        if (edadMeses < 0) {
            edadMeses += 12;
            edadAnios--;
        }

        anosInput.value = edadAnios;
        mesesInput.value = edadMeses;
        diasInput.value = edadDias;
    };



    $('#id_animal').change(function () {
        var codigo = $(this).val();
        if (codigo) {
            $.ajax({
                url: '/api/animal/' + codigo,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log(response);
                        $('#nombre_animal').val(response.animal.nombre);
                        $('#fecha_nacimiento').val(response.animal.fecha_nacimiento);
                        $('#sexo').val(response.animal.sexo);
                        $('#raza').val(response.animal.raza);
                        $('#color').val(response.animal.color);
                        $('#hierro').val(response.animal.hierro);
                        $('#id_predio').val(response.animal.predio ? response.animal.predio.nombre_predio : '');
                        $('#codigo').val(response.animal.codigo);
                        $('#identificacion_electronica').val(response.animal.identificacion_electronica);
                        $('#id_sinigan').val(response.animal.id_sinigan);
                        $('#fecha_ingreso_hato').val(response.animal.fecha_ingreso_hato);
                        $('#padre').val(response.animal.padre);
                        $('#madre').val(response.animal.madre);
                        $('#raza_madre').val(response.animal.raza_madre);
                        $('#raza_padre').val(response.animal.raza_padre);

                        var esCria = false;
                        if (response.estadoProductivo && response.estadoProductivo.nombre) {
                            var estadoProductivoNombre = response.estadoProductivo.nombre.toLowerCase();
                            if (estadoProductivoNombre.includes('cría') || estadoProductivoNombre.includes('cria')) {
                                esCria = true;
                            }
                        }

                        if (esCria) {
                            var loteActual = response.animal.lote ? response.animal.lote.nombre : 'Ninguno';
                            var potreroActual = response.animal.potrero ? response.animal.potrero.nombre : 'Ninguno';
                            var predioActual = response.animal.predio ? response.animal.predio.nombre_predio : 'Ninguno';
                            $('#lote').val(loteActual);
                            $('#potrero').val(potreroActual);
                            $('#id_predio').val(predioActual);
                            $('#fecha_ubicacion_lote').val(response.animal.fecha_nacimiento);
                            $('#fecha_ubicacion_potrero').val(response.animal.fecha_nacimiento);
                            $('#ultimo_peso_cantidad').val(response.animal.fecha_nacimiento);

                        } else {
                            if (response.movimientos && response.movimientos.length > 0) {
                                var ultimoMovimientoConLote = response.movimientos
                                    .filter(mov => mov.lote)
                                    .reduce((latest, current) => {
                                        return new Date(current.fecha_movimiento) > new Date(latest.fecha_movimiento) ? current : latest;
                                    }, { fecha_movimiento: '1970-01-01T00:00:00Z' });
                                var ultimoMovimientoConPotrero = response.movimientos
                                    .filter(mov => mov.potrero)
                                    .reduce((latest, current) => {
                                        return new Date(current.fecha_movimiento) > new Date(latest.fecha_movimiento) ? current : latest;
                                    }, { fecha_movimiento: '1970-01-01T00:00:00Z' });
                                var loteActual = ultimoMovimientoConLote.lote ? ultimoMovimientoConLote.lote.nombre : 'Ninguno';
                                var potreroActual = ultimoMovimientoConPotrero.potrero ? ultimoMovimientoConPotrero.potrero.nombre : 'Ninguno';
                                var fechaUbicacionLote = ultimoMovimientoConLote.fecha_movimiento || '';
                                var fechaUbicacionPotrero = ultimoMovimientoConPotrero.fecha_movimiento || '';
                                $('#lote').val(loteActual);
                                $('#fecha_ubicacion_lote').val(fechaUbicacionLote);
                                $('#potrero').val(potreroActual);
                                $('#fecha_ubicacion_potrero').val(fechaUbicacionPotrero);
                            } else {
                                $('#lote').val('');
                                $('#fecha_ubicacion_lote').val('');
                                $('#potrero').val('');
                                $('#fecha_ubicacion_potrero').val('');
                            }
                            var predioActual = response.animal.predio ? response.animal.predio.nombre_predio : 'Ninguno';
                            $('#id_predio').val(predioActual);
                        }

                        if (response.pesajes && response.pesajes.length > 0) {
                            var ultimoPesaje = response.pesajes.reduce((latest, current) => {
                                return new Date(current.fecha) > new Date(latest.fecha) ? current : latest;
                            });
                            $('#ultimo_peso_fecha').val(ultimoPesaje.fecha);
                            $('#ultimo_peso_cantidad').val(ultimoPesaje.peso);
                        } else {
                            $('#ultimo_peso_fecha').val('');
                            $('#ultimo_peso_cantidad').val('');
                        }

                        if (response.estadoReproductivo) {
                            $('#estado_reproductivo').val(response.estadoReproductivo.nombre);
                            $('#ultimo_parto').val(response.ultimo_parto);
                        } else {
                            $('#estado_reproductivo').val('');
                        }
                        if (response.estadoProductivo) {
                            $('#estado_productivo').val(response.estadoProductivo.nombre);
                        } else {
                            $('#estado_productivo').val('');
                        }
                        checkInputValue('#nombre_animal');
                        checkInputValue('#fecha_nacimiento');
                        checkInputValue('#sexo');
                        checkInputValue('#raza');
                        checkInputValue('#color');
                        checkInputValue('#potrero');
                        checkInputValue('#fecha_ubicacion');
                        checkInputValue('#raza_padre');
                        checkInputValue('#raza_madre');
                        checkInputValue('#madre');
                        checkInputValue('#padre');
                        checkInputValue('#fecha_ingreso_hato');
                        checkInputValue('#id_sinigan');
                        checkInputValue('#identificacion_electronica');
                        checkInputValue('#codigo');
                        checkInputValue('#id_predio');
                        checkInputValue('#hierro');
                        checkInputValue('#lote');
                        checkInputValue('#ultimo_parto');
                        checkInputValue('#ultimo_peso_fecha');
                        checkInputValue('#ultimo_peso_cantidad');
                        checkInputValue('#estado_productivo');
                        checkInputValue('#estado_reproductivo');
                        calcularEdad();
                    } else {
                        alert(response.message);
                        limpiarCampos();
                    }
                },
                error: function () {
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
        $('#lote').val('');
        $('#potrero').val('');
        $('#fecha_ubicacion_lote').val('');
        $('#fecha_ubicacion_potrero').val('');
        $('#ultimo_peso_fecha').val('');
        $('#ultimo_peso_cantidad').val('');
        $('#estado_productivo').val('');
        $('#estado_reproductivo').val('');
    }

    $('input, select').on('input change', function () {
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

    fechaNacimientoInput.addEventListener('change', calcularEdad);
    anosInput.addEventListener('input', calcularFechaNacimiento);
    mesesInput.addEventListener('input', calcularFechaNacimiento);
    diasInput.addEventListener('input', calcularFechaNacimiento);
});
