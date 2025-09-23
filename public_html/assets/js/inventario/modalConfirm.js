$(document).ready(function () {
    const confirmModal = $('#confirmModal');
    const confirmMessage = $('#confirmMessage');
    const confirmAction = $('#confirmAction');
    const cancelAction = $('#cancelAction');
    const form = $('#mainForm');

    // Manejar el clic en el botón de envío
    $('#formSubmitButton').click(function (event) {
        event.preventDefault();

        const vacaNombre = $('#id_animal option:selected').text();
        const criaNombre = $('#id_cria_animal option:selected').text() || 'Ninguna';
        const marcarVacaSeca = $('#marcarvacaseca').is(':checked');
        const pasarCriaLevante = $('#pasarcrialevante').is(':checked');

        let message = '';

        if (marcarVacaSeca && !pasarCriaLevante) {
            message = `¿Estás seguro de que quieres marcar como seca a la vaca "${vacaNombre}" y no destetar a ninguna cría?`;
        } else if (!marcarVacaSeca && pasarCriaLevante) {
            message = `¿Estás seguro de que quieres destetar a la cría "${criaNombre}" y no marcar como seca a la vaca "${vacaNombre}"?`;
        } else if (marcarVacaSeca && pasarCriaLevante) {
            message = `¿Estás seguro de que quieres marcar como seca a la vaca "${vacaNombre}" y destetar a la cría "${criaNombre}"?`;
        } else {
            alert('No seleccionaste ninguna acción. Por favor, selecciona al menos una.');
            return;
        }

        confirmMessage.text(
            `${message} Al aceptar, ten en cuenta que los cambios no se pueden revertir y tus animales cambiarán de estado.`
        );
        confirmModal.show(); // Mostrar el modal
    });

    // Confirmar la acción y enviar el formulario
    confirmAction.click(function () {
        confirmModal.hide(); // Ocultar el modal
        form.submit(); // Enviar el formulario
    });

    // Cancelar y ocultar el modal
    cancelAction.click(function () {
        confirmModal.hide();
    });

    // Ocultar modal al hacer clic fuera de él
    $(window).click(function (event) {
        if ($(event.target).is(confirmModal)) {
            confirmModal.hide();
        }
    });
});
