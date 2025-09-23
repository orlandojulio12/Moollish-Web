<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="nombre_area" class="form-label">{{ __('Nombre Area') }}</label>
            <input type="text" name="nombre_area" class="form-control @error('nombre_area') is-invalid @enderror" value="{{ old('nombre_area', $area?->nombre_area) }}" id="nombre_area" placeholder="Nombre Area">
            {!! $errors->first('nombre_area', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar cambios') }}</button>
    </div>
</div>
