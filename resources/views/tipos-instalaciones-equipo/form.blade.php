<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="nombre_tipo" class="form-label">{{ __('Nombre Tipo') }}</label>
            <input type="text" name="nombre_tipo" class="form-control @error('nombre_tipo') is-invalid @enderror" value="{{ old('nombre_tipo', $tiposInstalacionesEquipo?->nombre_tipo) }}" id="nombre_tipo" placeholder="Nombre Tipo">
            {!! $errors->first('nombre_tipo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar cambios') }}</button>
    </div>
</div>
