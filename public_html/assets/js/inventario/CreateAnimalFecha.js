    document.addEventListener('DOMContentLoaded', function() {
        const fechaNacimientoInput = document.getElementById('fecha_nacimiento_edit');
        const anosInput = document.getElementById('anos_edit');
        const mesesInput = document.getElementById('meses_edit');
        const diasInput = document.getElementById('dias_edit');

        let isUpdating = false;

        const calcularEdad = () => {
            if (isUpdating) return;
            isUpdating = true;

            const fechaNacimientoValue = fechaNacimientoInput.value;
            if (!fechaNacimientoValue) {
                anosInput.value = '';
                mesesInput.value = '';
                diasInput.value = '';
                isUpdating = false;
                return;
            }

            const fechaNacimiento = new Date(fechaNacimientoValue);
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

            isUpdating = false;
        };

        const calcularFechaNacimiento = () => {
            if (isUpdating) return;
            isUpdating = true;

            const edadAnios = parseInt(anosInput.value) || 0;
            const edadMeses = parseInt(mesesInput.value) || 0;
            const edadDias = parseInt(diasInput.value) || 0;

            const hoy = new Date();
            let fechaNacimiento = new Date(hoy);

            fechaNacimiento.setFullYear(hoy.getFullYear() - edadAnios);
            fechaNacimiento.setMonth(hoy.getMonth() - edadMeses);
            fechaNacimiento.setDate(hoy.getDate() - edadDias);

            fechaNacimientoInput.value = fechaNacimiento.toISOString().split('T')[0];

            isUpdating = false;
        };

        fechaNacimientoInput.addEventListener('change', calcularEdad);

        anosInput.addEventListener('input', calcularFechaNacimiento);
        mesesInput.addEventListener('input', calcularFechaNacimiento);
        diasInput.addEventListener('input', calcularFechaNacimiento);
    });
