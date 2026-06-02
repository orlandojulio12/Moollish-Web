  // jQuerys para abrir y cerrar modales
  $(document).ready(function() {
/* Create modal en inventario */

    $('#openCreateAnimalModal').on('click', function() {
        $('#createAnimalModal').modal('show');

    });

    $('.closeCreateAnimalModal').on('click', function() {
        $('#createAnimalModal').modal('hide'); // Cierra el modal
    });
/* Ubicacion modal en inventario */

    $('#openCreateUbicacionModal').on('click', function() {
        $('#CreateUbicacionModal').modal('show');
    });

    $('.closeCreateUbicacionModal').on('click', function() {
        $('#CreateUbicacionModal').modal('hide'); // Cierra el modal
    });
/* Peso modal en inventario */

    $('#openCreatePesoModal').on('click', function() {
        $('#CreatePesoModal').modal('show');
    });

    $('.closeCreatePesoModal').on('click', function() {
        $('#CreatePesoModal').modal('hide');
    });

/* Estado modal en inventario */
    $('#openCreateEstadoModal').on('click', function() {
        $('#CreateEstadoModal').modal('show');
    });

    $('.closeCreateEstadoModal').on('click', function() {
        $('#CreateEstadoModal').modal('hide');
    });

    /* Ubicacion modal en ubicacion */
    $('#openCreateEstadoModal').on('click', function() {
        $('#CreateEstadoModal').modal('show');
    });

    $('.closeCreateEstadoModal').on('click', function() {
        $('#CreateEstadoModal').modal('hide');
    });

    /* closeModalPotreros */
    $('.closeModalPotreros').on('click', function() {
        $('#modal-potreros').css('display', 'none');
    });

    $('.closeModalLotes').on('click', function() {
        $('#modal-lotes').css('display', 'none');
    });

    
    
});