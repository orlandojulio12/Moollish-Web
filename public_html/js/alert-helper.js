/**
 * Script para mostrar alertas y notificaciones
 */

(function() {
    // Variable para el contenedor de alertas
    let alertContainer = null;

    // Función para mostrar alertas (ahora asegura una sola alerta a la vez)
    function showAlert(type, message, duration = 5000) {
        // Crear o encontrar el contenedor de alertas
        if (!alertContainer) {
            alertContainer = $('<div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>');
            $('body').append(alertContainer);
        }

        // Eliminar cualquier alerta existente en el contenedor
        alertContainer.empty();

        // Colores de alerta de Bootstrap según el tipo
        const alertClasses = {
            'success': 'alert-success',
            'warning': 'alert-warning',
            'error': 'alert-danger',
            'info': 'alert-info'
        };

        // Crear el nuevo elemento de alerta (usando clases de Bootstrap)
        const alertElement = $(`
            <div class="alert ${alertClasses[type] || 'alert-info'} alert-dismissible fade show m-0" role="alert" style="box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);

        // Añadir la nueva alerta al contenedor
        alertContainer.append(alertElement);

        // Configurar la eliminación automática después del tiempo especificado
        setTimeout(function() {
            alertElement.fadeOut(500, function() {
                $(this).remove();
                // Si el contenedor queda vacío, podríamos ocultarlo o eliminarlo si se prefiere
                // if (alertContainer.is(':empty')) {
                //     alertContainer.remove();
                //     alertContainer = null;
                // }
            });
        }, duration);

   /*      console.log(`[${type.toUpperCase()}] ${message}`); */

        // Ya no necesitamos interactuar con las alertas estáticas
        /*
        const alertMessages = { ... };
        const alertContainers = { ... };
        if (type in alertMessages) { ... }
        */
    }

    // Exponer la función al ámbito global
    window.showAlert = showAlert;
})();
