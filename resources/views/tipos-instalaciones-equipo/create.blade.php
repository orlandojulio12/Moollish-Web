@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('tipos-instalaciones-equipos.index') }}">Infraestructuras</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crear Infraestructuras</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Crear Infraestructuras</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('tipos-instalaciones-equipos.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('tipos-instalaciones-equipo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
