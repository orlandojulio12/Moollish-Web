@extends('layouts')

@section('template_title')
    {{ __('Create') }} Propietario
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('propietarios.index') }}">Propietarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crear Propietario</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
<style>
    #content-container:before {
        content: '';
        display: block;
        height: 165px;
        width: 100%;
        position: absolute;
        background-color: #C29F77  !important;
        z-index: 0;
    }
</style>

<div class="content-area-body">
    <div class="card mb-0">
        <div class="card-body">
                        <form method="POST" action="{{ route('propietarios.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('propietario.form')

                        </form>
                    </div>
                </div>
            </div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('need_propietario'))
        <script>
            console.log('Script de SweetAlert ejecutándose');
            Swal.fire({
                icon: 'warning',
                title: 'Necesitas crear un propietario',
                text: 'Antes de registrar un predio, debes crear tu información de propietario.',
                confirmButtonText: 'Entendido',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        </script>
    @endif
@endsection
