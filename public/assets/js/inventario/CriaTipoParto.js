    document.getElementById('tipo_parto').addEventListener('change', function() {
        var tipoParto = this.value;
        // Ocultar todas las crías
        document.getElementById('cria-1').style.display = 'none';
        document.getElementById('cria-2').style.display = 'none';
        document.getElementById('cria-3').style.display = 'none';

        if (tipoParto === 'Parto') {
            // Mostrar una cría
            document.getElementById('cria-1').style.display = 'block';
        } else if (tipoParto === 'Gemelar') {
            // Mostrar dos crías
            document.getElementById('cria-1').style.display = 'block';
            document.getElementById('cria-2').style.display = 'block';
        } else if (tipoParto === 'Trillizo') {
            // Mostrar tres crías
            document.getElementById('cria-1').style.display = 'block';
            document.getElementById('cria-2').style.display = 'block';
            document.getElementById('cria-3').style.display = 'block';
        } 
        // Para 'Muerte Fetal' y 'Aborto', no se muestra ninguna cría
    });
