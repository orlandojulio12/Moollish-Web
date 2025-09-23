@extends('layouts')

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Usuario</li>
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
            <form method="POST" action="{{ route('users.update', $user->id) }}" role="form" id="user-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    @include('user.form')

                </form>
            </div>
        </div>
    </div>
@endsection


