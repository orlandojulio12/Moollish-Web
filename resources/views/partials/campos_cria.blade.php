<!-- partials/campos_cria.blade.php -->
<div class="cria">
    <div class="d-flex space-b">
        <!-- Código de la Cría -->
        <div class="form-group two-column-custom">
            <label for="codigo_cria_{{ $numero }}">Código <span style="color: red">*</span>:</label>
            <input class="form-control" type="number" name="crias[{{ $numero }}][codigo_cria]" id="codigo_cria_{{ $numero }}">
        </div>
        <!-- ID SINIGAN de la Cría -->
        <div class="form-group two-column-custom">
            <label for="id_sinigan_cria_{{ $numero }}">ID SINIGAN:</label>
            <input class="form-control" type="text" name="crias[{{ $numero }}][id_sinigan_cria]" id="id_sinigan_cria_{{ $numero }}">
        </div>
        <!-- Nombre de la Cría -->
        <div class="form-group four-column-custom">
            <label for="nombre_cria_{{ $numero }}">Nombre:</label>
            <input class="form-control" type="text" name="crias[{{ $numero }}][nombre_cria]" id="nombre_cria_{{ $numero }}">
        </div>
        <!-- Identificación Electrónica de la Cría -->
        <div class="form-group four-column-custom">
            <label for="identificacion_electronica_cria_{{ $numero }}">ID Electrónica:</label>
            <input class="form-control" type="text" name="crias[{{ $numero }}][identificacion_electronica_cria]" id="identificacion_electronica_cria_{{ $numero }}">
        </div>
    </div>
    <div class="d-flex space-b">
        <!-- Color de la Cría -->
        <div class="form-group five-column-custom">
            <label for="color_cria_{{ $numero }}">Color:</label>
            <input class="form-control" type="text" name="crias[{{ $numero }}][color_cria]" id="color_cria_{{ $numero }}">
        </div>
        <!-- Hierro de la Cría -->
        <div class="form-group five-column-custom">
            <label for="hierro_cria_{{ $numero }}">Hierro:</label>
            <input class="form-control" type="text" name="crias[{{ $numero }}][hierro_cria]" id="hierro_cria_{{ $numero }}">
        </div>
        <!-- Peso al Nacer -->
        <div class="form-group five-column-custom">
            <label for="peso_al_nacer_{{ $numero }}">Peso al Nacer:</label>
            <input class="form-control" type="number" step="0.01" name="crias[{{ $numero }}][peso_al_nacer]" id="peso_al_nacer_{{ $numero }}">
        </div>
        <!-- Raza de la Cría -->
        <div class="form-group five-column-custom">
            <label for="raza_cria_{{ $numero }}">Raza<span style="color: red">*</span>:</label>
            <select class="form-control" name="crias[{{ $numero }}][raza_cria]" id="raza_cria_{{ $numero }}">
                <option value="" disabled selected> Selecciona</option>
@foreach ($razasGanado as $raza)
                <option value="{{ $raza->id }}">{{ $raza->nombre_razas }}</option>
@endforeach

            </select>
{{--             <input class="form-control" type="text"  >
 --}}        </div>
        <!-- Sexo de la Cría -->
        <div class="form-group five-column-custom">
            <label for="sexo_cria_{{ $numero }}">Sexo<span style="color: red">*</span>:</label>
            <select class="form-control" name="crias[{{ $numero }}][sexo_cria]" id="sexo_cria_{{ $numero }}">
                <option value="" disabled selected>Selecciona</option>
                <option value="macho">macho</option>
                <option value="hembra">hembra</option>
            </select>
        </div>
    </div>
    <!-- Ubicación de la Cría -->
    <div class="title-modal-custom">
        <h3>Ubicación</h3>
    </div>
    <div class="d-flex space-b">
        <!-- Predio -->
        <div class="form-group three-column-custom">
            <label for="predio_id_cria_{{ $numero }}">Predio:</label>
            <select class="form-control" name="crias[{{ $numero }}][predio_id_cria]" id="predio_id_cria_{{ $numero }}">
                <option value="" disabled selected>Selecciona</option>
                @foreach ($predios as $predio)
                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                @endforeach
            </select>
        </div>
        <!-- Lote -->
        <div class="form-group three-column-custom">
            <label for="lote_id_cria_{{ $numero }}">Lote:</label>
            <select class="form-control" name="crias[{{ $numero }}][lote_id_cria]" id="lote_id_cria_{{ $numero }}">
                <option value="" disabled selected>Selecciona</option>
                @foreach ($lotes as $lote)
                    <option value="{{ $lote->id }}">{{ $lote->nombre }}</option>
                @endforeach
            </select>
        </div>
        <!-- Potrero -->
        <div class="form-group three-column-custom">
            <label for="potrero_id_cria_{{ $numero }}">Potrero:</label>
            <select class="form-control" name="crias[{{ $numero }}][potrero_id_cria]" id="potrero_id_cria_{{ $numero }}">
                <option value="" disabled selected>Selecciona</option>
                @foreach ($potreros as $potrero)
                    <option value="{{ $potrero->id }}">{{ $potrero->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
