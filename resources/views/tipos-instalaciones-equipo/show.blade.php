@extends('layouts')

@section('template_title')
    {{ $tiposInstalacionesEquipo->name ?? __('Show') . " " . __('Tipos Instalaciones Equipo') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Tipos Instalaciones Equipo</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('tipos-instalaciones-equipos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Tipo:</strong>
                                    {{ $tiposInstalacionesEquipo->nombre_tipo }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
