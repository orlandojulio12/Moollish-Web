@extends('layouts')

@section('template_title')
    Informacion Bgps
@endsection

@section('content')
<style>
    #content-container:before {
        content: '';
        display: block;
        height: 165px;
        width: 100%;
        position: absolute;
        background-color: #C29F77 !important;
        z-index: 0;
    }
</style>

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="page-head">

    <!--Page Title-->
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div id="page-title">
        <h1 class="page-header text-center">SECCION 3: INFORMACION PARA BPG</h1>
    </div>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--End page title-->


    <!--Breadcrumb-->
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <ol class="breadcrumb">
        <li><a href="/dashboard"><i class="demo-pli-home"></i></a></li>
        <li class="active"><a href="/informacion_bgp">informacion_bgp</a></li>
    </ol>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--End breadcrumb-->

</div>


<!--Page content-->
<!--===================================================-->
<div id="page-content">

    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="panel">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="panel-title">Lista de informacion_bgp</h3>
                </div>
                <div class="col-md-6 text-right"
                    style="
                padding-right: 20px;
                margin-top: 9px;">
                    <a href="{{ route('informacion-bgps.create') }}" class="btn btn-info">
                        {{ __('Crear informacion_bgp') }}
                    </a>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="panel-body">
            <table id="tabla-informacion_bgp" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
									<th >Predio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($informacionBgps as $informacionBgp)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $informacionBgp->id_predio }}</td>
                                            <td>
                                                <form action="{{ route('informacion-bgps.destroy', $informacionBgp->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('informacion-bgps.show', $informacionBgp->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('informacion-bgps.edit', $informacionBgp->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
@endsection
@section('scripts')
    <script>
        jQuery(document).ready(function($) {
            $('#tabla-informacion_bgp').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ informacion_bgp",
                    "infoEmpty": "Mostrando 0 a 0 de 0 informacion_bgp",
                    "infoFiltered": "(Filtrado de _MAX_ total de informacion_bgp)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ informacion_bgp",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
@endsection
