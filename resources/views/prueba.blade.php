<!-- resources/views/layout.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="/img/logo4.png" />
    <title>@yield('title', 'Moollish')</title>

    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Open Sans Font [ OPTIONAL ]-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css" />

    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />

    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/nifty.min.css') }}" rel="stylesheet" />

    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="{{ asset('css/demo/nifty-demo-icons.min.css') }}" rel="stylesheet" />

    <!--=================================================-->

    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="{{ asset('plugins/pace/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('plugins/pace/pace.min.js') }}"></script>

    <!--Demo [ DEMONSTRATION ]-->
    <link href="{{ asset('css/demo/nifty-demo.min.css') }}" rel="stylesheet" />
    <link href="plugins\datatables\media\css\dataTables.bootstrap.css" rel="stylesheet">
    <link href="plugins\datatables\extensions\Responsive\css\responsive.dataTables.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />



</head>

<body>
    <div id="container" class="effect aside-float aside-bright mainnav-lg">
        <!--NAVBAR-->
        <!--===================================================-->
        <header id="navbar">
            <div id="navbar-container" class="boxed">
                <!--Brand logo & name-->
                <!--================================-->
                <div class="navbar-header">
                    <a href="index.html" class="navbar-brand">
                        <img src="{{ asset('img/logo3.png') }}" alt="Nifty Logo" class="brand-icon"
                            style="height: 100%; wheith:100% " />
                        <div class="brand-title">
                            <span class="brand-text">Moollish</span>
                        </div>
                    </a>
                </div>
                <!--================================-->
                <!--End brand logo & name-->

                <!--Navbar Dropdown-->
                <!--================================-->
                <div class="navbar-content">
                    <ul class="nav navbar-top-links">
                        <!--Navigation toggle button-->
                        <li class="tgl-menu-btn">
                            <a class="mainnav-toggle" href="#">
                                <i class="demo-pli-list-view"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-top-links">
                        <!--Notification dropdown-->
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                <i class="demo-pli-bell"></i>
                                <span class="badge badge-header badge-danger"></span>
                            </a>

                            <!--Notification dropdown menu-->
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                <div class="nano scrollable">
                                    <div class="nano-content">
                                        <ul class="head-list">
                                            <!-- Add your notification items here -->
                                        </ul>
                                    </div>
                                </div>

                                <!--Dropdown footer-->
                                <div class="pad-all bord-top">
                                    <a href="#" class="btn-link text-main box-block">
                                        <i class="pci-chevron chevron-right pull-right"></i>Show All Notifications
                                    </a>
                                </div>
                            </div>
                        </li>
                        <!--End notifications dropdown-->

                        <!--User dropdown-->
                        <li id="dropdown-user" class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="ic-user pull-right">
                                    <i class="demo-pli-male"></i>
                                </span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                                <ul class="head-list">
                                    <li><a href="{{ route('profile.show') }}"><i
                                                class="demo-pli-male icon-lg icon-fw"></i> Profile</a></li>
                                    <li><a href="#"><i class="demo-pli-mail icon-lg icon-fw"></i> Messages</a>
                                    </li>
                                    <li><a href="#"><i class="demo-pli-gear icon-lg icon-fw"></i> Settings</a>
                                    </li>
                                    <li><a href="#"><i class="demo-pli-computer-secure icon-lg icon-fw"></i> Lock
                                            screen</a></li>
                                    <li><a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                                class="demo-pli-unlock icon-lg icon-fw"></i> Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--End user dropdown-->
                    </ul>
                </div>
                <!--End Navbar Dropdown-->
            </div>
        </header>
        <!--END NAVBAR-->

        <div class="boxed">
            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                @yield('content')
            </div>
            <!--END CONTENT CONTAINER-->

            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <nav id="mainnav-container">
                <div id="mainnav">
                    <div class="mainnav-brand">
                        <a href="index.html" class="brand">
                            <img src="{{ asset('img/logo.png') }}" alt="Nifty Logo" class="brand-icon">
                            <span class="brand-text">Nifty</span>
                        </a>
                        <a href="#" class="mainnav-toggle"><i class="pci-cross pci-circle icon-lg"></i></a>
                    </div>

                    <div id="mainnav-menu-wrap">
                        <div class="nano">
                            <div class="nano-content">
                                <!--Profile Widget-->
                                <ul id="mainnav-menu" class="list-group">
                                    <li class="list-header">Navegacion</li>
                                    <li class="{{ Request::is('dashboard') ? 'active-sub' : '' }}">
                                        <a href="/dashboar">
                                            <i class="demo-pli-home"></i>
                                            <span class="menu-title">Dashboard</span>
                                        </a>
                                    </li>


                                    <li class="{{ Request::is('users*') ? 'active-sub' : '' }}">
                                        <a href="{{ route('users.index') }}">
                                            <i class="demo-pli-male"></i>
                                            <span class="menu-title">Usuarios</span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::is('roles*') ? 'active-sub' : '' }}">
                                        <a href="{{ route('roles.index') }}">
                                            <i class="demo-pli-folder"></i>
                                            <span class="menu-title">Roles</span>
                                        </a>
                                    </li>
                                    @if (Auth::check() && Auth::user()->id_rol === 1)
                                        <li class="list-divider"></li>
                                        <li class="list-header">Administrador</li>
                                        <li>
                                            <a href="#">
                                                <i class="demo-pli-gear"></i>
                                                <span class="menu-title">GESTIONAR</span>
                                                <i class="arrow"></i>
                                            </a>
                                            <ul class="collapse">
                                                <li class="{{ Request::is('razas-ganado*') ? 'active-sub' : '' }}">
                                                    <a href="{{ route('razas-ganados.index') }}">Razas de ganados</a>
                                                </li>
                                                <li class="{{ Request::is('areas*') ? 'active-sub' : '' }}"><a
                                                        href="{{ route('areas.index') }}">Tipos de Areas</a></li>
                                                <li
                                                    class="{{ Request::is('tipos-instalaciones-equipos*') ? 'active-sub' : '' }}">
                                                    <a
                                                        href="{{ route('tipos-instalaciones-equipos.index') }}">Infraectructuras</a>
                                                </li>

                                            </ul>
                                        </li>
                                    @endif
                                    <li class="list-divider"></li>
                                    <li class="list-header">CARACTERIZACIONES</li>
                                    <li>
                                        <a href="#">
                                            <i class="demo-pli-boot-2"></i>
                                            <span class="menu-title">PREDIOS PECUARIOS</span>
                                            <i class="arrow"></i>
                                        </a>
                                        <ul class="collapse">
                                            <li class="{{ Request::is('predios*') ? 'active-sub' : '' }}"><a
                                                    href="{{ route('predios.index') }}">Predios</a></li>
                                            <li class="{{ Request::is('propietarios*') ? 'active-sub' : '' }}"><a
                                                    href="{{ route('propietarios.index') }}">Propietarios</a></li>
                                        </ul>
                                    </li>
                                    <li class="list-divider"></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="demo-pli-unlock icon-lg icon-fw"></i>
                                            <span class="menu-title">Salir</span>

                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <script src="{{ asset('js/jquery.min.js') }}"></script>


            <!--END MAIN NAVIGATION-->
        </div>
    </div>

    <!--SCROLL PAGE BUTTON-->
    <!--===================================================-->
    <button class="scroll-top btn">
        <i class="pci-chevron chevron-up"></i>
    </button>

    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="{{ asset('js/nifty.min.js') }}"></script>
    <script src="plugins\datatables\media\js\jquery.dataTables.js"></script>
    <script src="plugins\datatables\media\js\dataTables.bootstrap.js"></script>
    <script src="plugins\datatables\extensions\Responsive\js\dataTables.responsive.min.js"></script>


    <!--DataTables Sample [ SAMPLE ]-->
    <script src="js\demo\tables-datatables.js"></script>
    <script>
        $(document).ready(function() {
            $('#mainnav-menu li').on('click', function() {
                // Remover la clase activa de todos los elementos del menú
                $('#mainnav-menu li').removeClass('active-sub');
                // Agregar la clase activa al elemento clickeado
                $(this).addClass('active-sub');
            });
        });
    </script>

    @yield('scripts')

</body>

</html>

<div class="panel">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h3 class="panel-title">Usuarios</h3>
            </div>
            <div class="col-md-6 text-right"
                style="
                    padding-right: 20px;
                    margin-top: 9px;">
                <a href="{{ route('users.create') }}" class="btn btn-info">
                    {{ __('Crear Usuario') }}
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
        <table id="tabla-users" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->name }}</td>
                        <td>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                <a class="btn btn-sm btn-success" href="{{ route('users.edit', $user->id) }}"><i
                                        class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar este usuario?') ? this.closest('form').submit() : false;"><i
                                        class="fa fa-fw fa-trash"></i> {{ __('Borrar') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
