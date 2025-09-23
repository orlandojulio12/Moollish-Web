<div class="row padding-1 p-1">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('SANIDAD ANIMAL') }}</th>
                    <th>{{ __('Estado') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tiposBgp as $tipoBgp)
                    <tr>
                        <td>
                            <input type="hidden" name="id_predio" value="{{ $id }}">
                            <input type="hidden" name="id_tipos_bgp[]" value="{{ $tipoBgp->id }}">
                            {{ $tipoBgp->nombre }}
                        </td>
                        <td>
                            <select name="estado[]" class="form-control @error('estado') is-invalid @enderror">
                                <option value="">Seleccione</option>
                                <option value="Si" {{ old('estado') == 'Si' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('estado') == 'No' ? 'selected' : '' }}>No</option>
                                <option value="NA" {{ old('estado') == 'NA' ? 'selected' : '' }}>N/A</option>
                            </select>
                            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </td>
                    </tr>
                    @if($tipoBgp->id === 6)
                        <tr>
                            <td colspan="3">
                                <table class="table" style="margin-bottom: 0; border-color: #e5e7eb00;">
                                    <thead>
                                        <tr>
                                            <th style="width: 86%;">{{ __('IDENTIFICACIÓN') }}</th>
                                            <th>{{ __('Estado') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </td>
                        </tr>
                    @endif
                    @if($tipoBgp->id === 8)
                        <tr>
                            <td colspan="3">
                                <table class="table" style="margin-bottom: 0; border-color: #e5e7eb00;">
                                    <thead>
                                        <tr>
                                            <th style="width: 86%;">{{ __('BIOSEGURIDAD') }}</th>
                                            <th>{{ __('Estado') }}</th>
                                        </tr>
                                    </thead>
                                    
                                </table>
                            </td>
                        </tr>
                    @endif
                    @if($tipoBgp->id === 13)
                        <tr>
                            <td colspan="3">
                                <table class="table" style="margin-bottom: 0; border-color: #e5e7eb00;">
                                    <thead>
                                        <tr>
                                            <th style="width: 86%;">{{ __('REQUISITOS DE BUENAS PRÁCTICAS PARA EL USO DE MEDICAMENTOS VETERINARIOS –BPMV.') }}</th>
                                            <th>{{ __('Estado') }}</th>
                                        </tr>
                                    </thead>
                                    
                                </table>
                            </td>
                        </tr>
                    @endif
                    @if($tipoBgp->id === 25)
                        <tr>
                            <td colspan="3">
                                <table class="table" style="margin-bottom: 0; border-color: #e5e7eb00;">
                                    <thead>
                                        <tr>
                                            <th style="width: 86%;">{{ __('REQUISITOS DE BUENAS PRÁCTICAS PARA LA ALIMENTACIÓN ANIMAL –BPAA') }}</th>
                                            <th>{{ __('Estado') }}</th>
                                        </tr>
                                    </thead>
                                    
                                </table>
                            </td>
                        </tr>
                    @endif
                    @if($tipoBgp->id === 32)
                        <tr>
                            <td colspan="3">
                                <table class="table" style="margin-bottom: 0; border-color: #e5e7eb00;">
                                    <thead>
                                        <tr>
                                            <th style="width: 86%;">{{ __('REQUISITOS DE SANEAMIENTO') }}</th>
                                            <th>{{ __('Estado') }}</th>
                                        </tr>
                                    </thead>
                                    
                                </table>
                            </td>
                        </tr>
                    @endif
                    @if($tipoBgp->id === 39)
                        <tr>
                            <td colspan="3">
                                <table class="table" style="margin-bottom: 0; border-color: #e5e7eb00;">
                                    <thead>
                                        <tr>
                                            <th style="width: 86%;">{{ __('REQUISITOS DE BIENESTAR ANIMAL') }}</th>
                                            <th>{{ __('Estado') }}</th>
                                        </tr>
                                    </thead>
                                    
                                </table>
                            </td>
                        </tr>
                    @endif
                    @if($tipoBgp->id === 48)
                        <tr>
                            <td colspan="3">
                                <table class="table" style="margin-bottom: 0; border-color: #e5e7eb00;">
                                    <thead>
                                        <tr>
                                            <th style="width: 86%;">{{ __('REQUISITOS DE  PERSONAL') }}</th>
                                            <th>{{ __('Estado') }}</th>
                                        </tr>
                                    </thead>
                                    
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-12 mt20 mt-2 ">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>
