$(document).ready(function () {
    // Seleccionar elementos
    const $padreSelect = $('#padre');
    const $otroPadreContainer = $('#otro-padre-container');
    const $otroPadreInput = $('#otro_padre');

    // Evento de cambio en el select
    $padreSelect.change(function () {
        if ($(this).val() === 'otro') {
            $otroPadreContainer.show(); // Mostrar el campo de texto
            $otroPadreInput.prop('required', true); // Marcar como requerido
        } else {
            $otroPadreContainer.hide(); // Ocultar el campo de texto
            $otroPadreInput.val(''); // Limpiar el valor del campo
            $otroPadreInput.prop('required', false); // Eliminar el requisito
        }
    });
})