@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('propietarios.index') }}">Propietarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Propietario</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Propietario</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('propietarios.update', $propietario->id) }}" role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('propietario.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
