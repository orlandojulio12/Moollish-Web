


@section('popup')


    <div id="selectorAnimalesPopup_{{ isset($nombre) ? $nombre : 'id_animal' }}" class="popup-animal"
        data-input="{{ isset($nombre) ? $nombre : 'id_animal' }}"
        data-text="{{ isset($nombre) ? $nombre . '_text' : 'animalSeleccionado' }}"
        style="display: none; position: fixed; top: 10%; left: 50%; overflow: auto; max-height: -webkit-fill-available; transform: translateX(-50%); z-index: 1050; background: #fff; border: 1px solid #ccc; width: 80%; max-width: 800px; padding: 20px; border-radius: 5px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h5>Seleccione un Animal</h5>
            <button type="button" class="cerrar-popup"
                style="background: none; border: none; font-size: 1.5rem;">&times;</button>
        </div>
        <hr>
        <div class="mb-3">
            <label for="predioSelector" class="form-label">Predio</label>
            <select class="form-select predio-selector">
                <option value="">Seleccione un predio</option>
                @foreach ($predios as $predio)
                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control animal-search" placeholder="Filtrar animales...">
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>ID Electrónica</th>
                        <th>Nombre</th>
                        <th>Estado Productivo</th>
                        <th>Estado Reproductivo</th>
                        <th>Edad</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        function initPopup() {
            $('.buton-dinamico-animal').on('click', function() {
                let target = $(this).data('popup');
                $(`#selectorAnimalesPopup_${target}`).fadeIn();
            });

            $('.cerrar-popup').on('click', function() {
                $(this).closest('.popup-animal').fadeOut();
            });

            $('.predio-selector').on('change', function() {
                let popup = $(this).closest('.popup-animal');
                cargarAnimales(popup);
                popup.find('.animal-search').val('');
            });

            $('.animal-search').on('input', function() {
                let searchTerm = $(this).val().toLowerCase();
                let popup = $(this).closest('.popup-animal');

                popup.find('tbody tr').each(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) !== -1);
                });
            });

            $(document).on('click', '.animal-row', function() {
                let popup = $(this).closest('.popup-animal');
                let inputHidden = popup.attr('data-input');
                let texto = popup.attr('data-text');

                let animalId = $(this).data('animal-id');
                let codigo = $(this).find('td:eq(0)').text();
                let ide = $(this).find('td:eq(1)').text();

                // Actualiza el input y el contenedor de texto
                $(`#${inputHidden}`).val(animalId);
                $(`#${texto}`).text(`CODIGO: ${codigo} - IDELECTRONICA: ${ide}`);
                popup.fadeOut();

                // Si no se encuentra el elemento con el ID de data-input, usa "id_animal" como fallback
                let inputElem = document.getElementById(inputHidden) || document.getElementById(
                    'id_animal');
                if (inputElem) {
                    inputElem.dispatchEvent(new Event('change'));
                } else {
                    console.warn(
                        `No se encontró el elemento con ID: ${inputHidden} ni con ID "id_animal"`);
                }
            });
        }
        initPopup();
        document.querySelectorAll('.buton-dinamico-animal[onclick]').forEach(btn => {
            let onclickAttr = btn.getAttribute('onclick');
            if (onclickAttr && onclickAttr.includes('mostrarPopup')) {
                let popupId = onclickAttr.match(/mostrarPopup\(['"]?([^'"]+)['"]?\)/);
                if (popupId && popupId[1] && popupId[1] !== 'undefined') {
                    btn.setAttribute('data-popup', popupId[1]);
                    btn.removeAttribute('onclick');
                }
                if (!popupId && btn.dataset.popup) {
                    btn.setAttribute('data-popup', btn.dataset.popup);
                }
            }
        });
    });
</script>
<script>
    var animalesGlobal = @json($animales); // 🔥 GLOBAL, no se toca

    function computeAge(fechaNacimiento) {
        var birthDate = new Date(fechaNacimiento);
        var ageDifMs = Date.now() - birthDate.getTime();
        var ageDate = new Date(ageDifMs);
        return Math.abs(ageDate.getUTCFullYear() - 1970);
    }

    function cargarAnimales(popup) {
        let predioId = popup.find('.predio-selector').val();
        let $tbody = popup.find('tbody');
        $tbody.empty();

        if (predioId) {
            let animalesFiltrados = animalesGlobal.filter(function(animal) {
                return animal.id_predio == predioId;
            });

            if (animalesFiltrados.length > 0) {
                animalesFiltrados.forEach(function(animal) {
                    let edad = animal.fecha_nacimiento ? computeAge(animal.fecha_nacimiento) : '';
                    let row = `
                <tr class="animal-row" data-animal-id="${animal.id_animal}">
                    <td>${animal.codigo}</td>
                    <td>${animal.identificacion_electronica || 'N/A'}</td>
                    <td>${animal.nombre || 'N/A'}</td>
                    <td>${animal.estado_productivo_objeto}</td>
                    <td>${animal.estado_reproductivo_objeto}</td>
                    <td>${edad} años</td>
                </tr>`;
                    $tbody.append(row);
                });
            } else {
                $tbody.append('<tr><td colspan="6" class="text-center">No hay animales disponibles.</td></tr>');
            }
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        $('.predio-selector').on('change', function() {
            let popup = $(this).closest('.popup-animal');
            cargarAnimales(popup);
            popup.find('.animal-search').val('');
        });

        $('.animal-search').on('input', function() {
            let searchTerm = $(this).val().toLowerCase();
            let popup = $(this).closest('.popup-animal');

            popup.find('tbody tr').each(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) !== -1);
            });
        });

        $(document).on('click', '.animal-row', function() {
            let popup = $(this).closest('.popup-animal');
            let inputHidden = popup.attr('data-input');
            console.log(inputHidden);
            let texto = popup.attr('data-text');
            console.log(texto);
            let animalId = $(this).data('animal-id');
            let codigo = $(this).find('td:eq(0)').text();
            let ide = $(this).find('td:eq(1)').text();

            $(`#${inputHidden}`).val(animalId);
            $(`#${texto}`).text(`CODIGO: ${codigo} - IDELECTRONICA: ${ide}`);
            popup.fadeOut();

            document.getElementById(inputHidden).dispatchEvent(new Event('change'));
        });

        $('.cerrar-popup').on('click', function() {
            $(this).closest('.popup-animal').fadeOut();
        });

        window.mostrarPopup = function(popupId) {
            let popup = $(`#selectorAnimalesPopup_${popupId}`);

            if (popup.length > 0) {
                popup.fadeIn();
            } else {
                console.error(`No se encontró el popup con ID: ${popupId}`);
            }
        };
    });
</script>


