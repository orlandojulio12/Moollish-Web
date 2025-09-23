<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="nombre_razas" class="form-label">{{ __('Nombre Razas') }}</label>
            <input type="text" name="nombre_razas" class="form-control @error('nombre_razas') is-invalid @enderror" value="{{ old('nombre_razas', $razasGanado?->nombre_razas) }}" id="nombre_razas" placeholder="Nombre Razas">
            {!! $errors->first('nombre_razas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar cambios') }}</button>
    </div>
</div>
