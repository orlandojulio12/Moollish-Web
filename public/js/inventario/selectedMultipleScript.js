$(document).ready(function() {
    function updateNoSelectedMessage() {
        if ($('.selected-animal').length > 0) {
            $('.no-selected').hide(); 
        } else {
            $('.no-selected').show(); 
        }
    }

    $('#codigo_animal_').on('change', function() {
        var selectedOption = $(this).find('option:selected'); 
        var id = selectedOption.val(); 
        var text = selectedOption.text(); 
        if (!selectedOption.is(':disabled')) { 
            $('#ubicacionForm').append('<input type="hidden" name="id_animal[]" value="' + id +
                '" id="animal-input-' + id + '">');

            $('.selected-animals-container').append(
                '<div class="selected-animal" data-id="' + id + '">' +
                text +
                ' <span class="material-symbols-outlined icon-close icon-size-custom" style="cursor: pointer; font-size:14px;">close</span>' +
                '</div>'
            );
            selectedOption.prop('disabled', true);
            $(this).val(''); 

            updateNoSelectedMessage();
        }
    });

    $('.selected-animals-container').on('click', '.icon-close', function() {
        var parentDiv = $(this).closest('.selected-animal');
        var id = parentDiv.data('id'); 
        $('#animal-input-' + id).remove();
        $('#codigo_animal_ option[value="' + id + '"]').prop('disabled',
            false); 
        parentDiv.remove(); 
        updateNoSelectedMessage();
    });

    updateNoSelectedMessage();
});