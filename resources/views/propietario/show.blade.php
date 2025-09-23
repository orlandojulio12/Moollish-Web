@extends('layouts')

@section('template_title')
    {{ $propietario->name ?? __('Show') . " " . __('Propietario') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Propietario</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('propietarios.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Completo:</strong>
                                    {{ $propietario->nombre_completo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo Doc:</strong>
                                    {{ $propietario->tipo_doc }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Num Doc:</strong>
                                    {{ $propietario->num_doc }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Genero:</strong>
                                    {{ $propietario->genero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Correo Electronico:</strong>
                                    {{ $propietario->correo_electronico }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $propietario->telefono }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
