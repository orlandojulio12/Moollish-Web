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

        if (!fechaNacimientoInput.value) return;

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
        const codigo = $(this).val();

        if (!codigo) {
            limpiarCampos();
            return;
        }

        $.ajax({
            url: '/api/animal/' + codigo,
            type: 'GET',
            dataType: 'json',
            success: function (response) {

                if (!response.success) {
                    alert(response.message);
                    limpiarCampos();
                    return;
                }

                console.log(response);

                $('#nombre_animal').val(response.animal.nombre);
                $('#fecha_nacimiento').val(response.animal.fecha_nacimiento);
                $('#sexo').val(response.animal.sexo);
                $('#raza').val(response.animal.raza);
                $('#color').val(response.animal.color);
                $('#hierro').val(response.animal.hierro);
                $('#codigo').val(response.animal.codigo);
                $('#identificacion_electronica').val(response.animal.identificacion_electronica);
                $('#id_sinigan').val(response.animal.id_sinigan);
                $('#fecha_ingreso_hato').val(response.animal.fecha_ingreso_hato);
                $('#padre').val(response.animal.padre);
              $('#madre').val(response.madre_texto ?? '');

                $('#raza_madre').val(response.animal.raza_madre);
                $('#raza_padre').val(response.animal.raza_padre);

                const predioActual = response.animal.predio
                    ? response.animal.predio.nombre_predio
                    : 'Ninguno';
                $('#id_predio').val(predioActual);

                let esCria = false;
                if (response.estadoProductivo?.nombre) {
                    const nombre = response.estadoProductivo.nombre.toLowerCase();
                    esCria = nombre.includes('cría') || nombre.includes('cria');
                }

                if (esCria) {
                    $('#lote').val(response.animal.lote?.nombre || 'Ninguno');
                    $('#potrero').val(response.animal.potrero?.nombre || 'Ninguno');
                    $('#fecha_ubicacion_lote').val(response.animal.fecha_nacimiento);
                    $('#fecha_ubicacion_potrero').val(response.animal.fecha_nacimiento);
                } else if (response.movimientos?.length > 0) {

                    const ultimoLote = response.movimientos
                        .filter(m => m.lote)
                        .sort((a, b) => new Date(b.fecha_movimiento) - new Date(a.fecha_movimiento))[0];

                    const ultimoPotrero = response.movimientos
                        .filter(m => m.potrero)
                        .sort((a, b) => new Date(b.fecha_movimiento) - new Date(a.fecha_movimiento))[0];

                    $('#lote').val(ultimoLote?.lote?.nombre || '');
                    $('#fecha_ubicacion_lote').val(ultimoLote?.fecha_movimiento || '');
                    $('#potrero').val(ultimoPotrero?.potrero?.nombre || '');
                    $('#fecha_ubicacion_potrero').val(ultimoPotrero?.fecha_movimiento || '');

                } else {
                    $('#lote, #potrero, #fecha_ubicacion_lote, #fecha_ubicacion_potrero').val('');
                }

                if (response.pesajes?.length > 0) {
                    const ultimoPesaje = response.pesajes
                        .sort((a, b) => new Date(b.fecha) - new Date(a.fecha))[0];
                    $('#ultimo_peso_fecha').val(ultimoPesaje.fecha);
                    $('#ultimo_peso_cantidad').val(ultimoPesaje.peso);
                } else {
                    $('#ultimo_peso_fecha, #ultimo_peso_cantidad').val('');
                }

                // 🔥 ESTADOS CORREGIDOS
                $('#estado_productivo_texto').val(response.estadoProductivo?.nombre || '');
                $('#estado_reproductivo').val(response.estadoReproductivo?.nombre || '');
                $('#ultimo_parto').val(response.ultimo_parto || '');

                // Visual
                [
                    '#nombre_animal', '#fecha_nacimiento', '#sexo', '#raza', '#color',
                    '#potrero', '#raza_padre', '#raza_madre', '#madre', '#padre',
                    '#fecha_ingreso_hato', '#id_sinigan', '#identificacion_electronica',
                    '#codigo', '#id_predio', '#hierro', '#lote',
                    '#ultimo_parto', '#ultimo_peso_fecha', '#ultimo_peso_cantidad',
                    '#estado_productivo_texto', '#estado_reproductivo'
                ].forEach(checkInputValue);

                calcularEdad();
            },
            error: function () {
                alert('Error al obtener los datos del animal.');
                limpiarCampos();
            }
        });
    });

    function limpiarCampos() {
        [
            '#nombre_animal', '#fecha_nacimiento', '#sexo', '#raza', '#color',
            '#hierro', '#id_predio', '#codigo', '#identificacion_electronica',
            '#id_sinigan', '#fecha_ingreso_hato', '#raza_madre', '#raza_padre',
            '#padre', '#madre', '#lote', '#potrero',
            '#fecha_ubicacion_lote', '#fecha_ubicacion_potrero',
            '#ultimo_peso_fecha', '#ultimo_peso_cantidad',
            '#estado_productivo_texto', '#estado_reproductivo', '#ultimo_parto'
        ].forEach(id => $(id).val(''));
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
        const fecha = new Date(hoy);
        fecha.setFullYear(hoy.getFullYear() - (+anosInput.value || 0));
        fecha.setMonth(hoy.getMonth() - (+mesesInput.value || 0));
        fecha.setDate(hoy.getDate() - (+diasInput.value || 0));
        fechaNacimientoInput.value = fecha.toISOString().split('T')[0];
    };

    fechaNacimientoInput.addEventListener('change', calcularEdad);
    anosInput.addEventListener('input', calcularFechaNacimiento);
    mesesInput.addEventListener('input', calcularFechaNacimiento);
    diasInput.addEventListener('input', calcularFechaNacimiento);
});
