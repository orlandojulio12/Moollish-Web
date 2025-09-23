@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $id_predio) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">NFORMACION PARA BUENAS PRACTICAS GANADERAS - BPG</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
<div class="content-area-body">
    <div class="card mb-0">
        <div class="card-body">
    <h1>Informacion</h1>

    @if($informacionBgp->isEmpty())
        <p>No records found for this Predio.</p>
    @else


    <table class="table">
        <thead>
            <tr>
                <th>{{ __('SANIDAD ANIMAL') }}</th>
                <th>{{ __('Tipo') }}</th>
                <th>{{ __('Estado') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiposBgp as $index => $tipoBgp)
            <form method="POST" action="{{ route('informacionBgp.update', $tipoBgp->id) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                @csrf
                @php
                    $currentInfo = $informacionBgp->firstWhere('id_tipos_bgp', $tipoBgp->id);
                @endphp
                <tr>
                    <td>
                        <input type="hidden" name="id_tipos_bgp[]" value="{{ $tipoBgp->id }}">
                        <input type="hidden" name="id_predio[]" value="{{ $id_predio }}">
                        {{ $tipoBgp->nombre }}
                    </td>
                    <td>
                        <select name="tipo[]" class="form-control @error('tipo.' . $index) is-invalid @enderror">
                            <option value="">Seleccione</option>
                            <option value="F" @selected(old('tipo.' . $index, optional($currentInfo)->tipo) == 'F')>F</option>
                            <option value="My" @selected(old('tipo.' . $index, optional($currentInfo)->tipo) == 'My')>My</option>
                            <option value="Mn" @selected(old('tipo.' . $index, optional($currentInfo)->tipo) == 'Mn')>Mn</option>
                        </select>
                        {!! $errors->first('tipo.' . $index, '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </td>
                    <td>
                        <select name="estado[]" class="form-control @error('estado.' . $index) is-invalid @enderror">
                            <option value="">Seleccione</option>
                            <option value="Si" @selected(old('estado.' . $index, optional($currentInfo)->estado) == 'Si')>Sí</option>
                            <option value="No" @selected(old('estado.' . $index, optional($currentInfo)->estado) == 'No')>No</option>
                            <option value="NA" @selected(old('estado.' . $index, optional($currentInfo)->estado) == 'NA')>N/A</option>
                        </select>
                        {!! $errors->first('estado.' . $index, '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </td>
                 </tr>
                @if($tipoBgp->id === 6)
                    <tr>
                        <td colspan="3">
                            <table class="table" style="margin-bottom: 0; border-color: #e5e7eb00;">
                                <thead>
                                    <tr>
                                        <th style="width: 77%">{{ __('IDENTIFICACIÓN') }}</th>
                                        <th>{{ __('Tipo') }}</th>
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
                                        <th style="width: 77%">{{ __('BIOSEGURIDAD') }}</th>
                                        <th>{{ __('Tipo') }}</th>
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
                                        <th style="width: 77%">{{ __('REQUISITOS DE BUENAS PRÁCTICAS PARA EL USO DE MEDICAMENTOS VETERINARIOS –BPMV.') }}</th>
                                        <th>{{ __('Tipo') }}</th>
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
                                        <th style="width: 77%">{{ __('REQUISITOS DE BUENAS PRÁCTICAS PARA LA ALIMENTACIÓN ANIMAL –BPAA') }}</th>
                                        <th>{{ __('Tipo') }}</th>
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
                                        <th style="width: 77%">{{ __('REQUISITOS DE SANEAMIENTO') }}</th>
                                        <th>{{ __('Tipo') }}</th>
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
                                        <th style="width: 77%">{{ __('REQUISITOS DE BIENESTAR ANIMAL') }}</th>
                                        <th>{{ __('Tipo') }}</th>
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
                                        <th style="width: 77%">{{ __('REQUISITOS DE  PERSONAL') }}</th>
                                        <th>{{ __('Tipo') }}</th>
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

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>

    @endif
</div>
    </div>
</div>
@endsection
