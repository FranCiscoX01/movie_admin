<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','MovieShop')</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />

    <script src="https://kit.fontawesome.com/5b962538fe.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/jquery/jquery.min.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nprogress.css') }}" rel="stylesheet">
    <link id="sleek-css" rel="stylesheet" href="{{ asset('css/sleek.css') }}" />
</head>
<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
  <div id="toaster"></div> <!-- sirver para responsivo quitar menu despegable -->
  <div class="wrapper">
    <!--
    ====================================
    ——— LEFT SIDEBAR WITH FOOTER
    =====================================
    -->
    <aside class="left-sidebar bg-sidebar">
    <div id="sidebar" class="sidebar">
      <!-- Aplication Brand -->
      <div class="app-brand">
        <a href="/" title="Sicorent Dashboard">
        <span class="brand-name text-truncate">Karma</span>
        </a>
      </div>
      <!-- begin sidebar scrollbar -->
      <div class="sidebar-scrollbar">
        <!-- sidebar menu -->
        <ul class="nav sidebar-inner" id="sidebar-menu" >

            <li  class="has-sub {{ request()->is('/') ? 'active' : '' }}  " >
              <a class="sidenav-item-link" aria-expanded="false" aria-controls="Inicio" href="/">
                <i class="mdi mdi-home"></i>
                <span class="nav-text">Inicio</span>
              </a>
            </li>

            <li  class="has-sub {{ request()->is('film*') ? 'active' : '' }}  " >
              <a class="sidenav-item-link" aria-expanded="false" aria-controls="Peliculas" href="{{ route('films') }}">
                <i class="mdi mdi-movie"></i>
                <span class="nav-text">Peliculas</span>
              </a>
            </li>

            <li  class="has-sub {{ request()->is('category*') ? 'active' : '' }}  " >
              <a class="sidenav-item-link" aria-expanded="false" aria-controls="Peliculas" href="{{ route('category') }}">
                <i class="mdi mdi-movie-roll"></i>
                <span class="nav-text">Categorias</span>
              </a>
            </li>

            <!--<li  class="has-sub {{ request()->is('users*') ? 'active' : '' }}   " >
              <a class="sidenav-item-link " aria-expanded="false" aria-controls="user" href="#user" data-toggle="collapse">
                <i class="mdi mdi-account"></i>
                <span class="nav-text">Usuarios</span> <b class="caret"></b>
              </a>
              <ul  class="collapse"  id="user">
                <div class="sub-menu">
                    <li >
                      <a class="sidenav-item-link" href="">
                        <span class="nav-text">Permisos</span>
                      </a>
                    </li>
                    <li >
                      <a class="sidenav-item-link" href="">
                        <span class="nav-text">Roles</span>
                      </a>
                    </li>
                    <li >
                      <a class="sidenav-item-link" href="">
                        <span class="nav-text">Administrar Usuarios</span>
                      </a>
                    </li>
                    <li >
                      <a class="sidenav-item-link" href="">
                        <span class="nav-text">Crear Usuarios</span>
                      </a>
                    </li>
                  </div>
                </ul>
            </li>-->


        </ul>
      </div>
    </div>
    </aside>
    <div class="page-wrapper">
          <!-- Header -->
          <header class="main-header " id="header">
            <nav class="navbar navbar-static-top navbar-expand-lg">
              <!-- Sidebar toggle button -->
              <button id="sidebar-toggler" class="sidebar-toggle">
                <span class="sr-only">Toggle navigation</span>
              </button>
              <!-- search form -->
              <div class="search-form d-none d-lg-inline-block">
                <div class="input-group">
                </div>
                <div id="search-results-container">
                </div>
              </div>
              <div class="navbar-right ">
                <ul class="nav navbar-nav">
                  <li class="dropdown notifications-menu">
                    <button class="dropdown-toggle" data-toggle="dropdown">
                      <i class="mdi mdi-bell-outline"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <li class="dropdown-header">You have 5 notifications</li>
                      <li>
                        <a href="#">
                          <i class="mdi mdi-account-plus"></i> New user registered
                          <span class=" font-size-12 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> 10 AM</span>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="mdi mdi-account-remove"></i> User deleted
                          <span class=" font-size-12 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> 07 AM</span>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="mdi mdi-chart-areaspline"></i> Sales report is ready
                          <span class=" font-size-12 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> 12 PM</span>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="mdi mdi-account-supervisor"></i> New client
                          <span class=" font-size-12 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> 10 AM</span>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="mdi mdi-server-network-off"></i> Server overloaded
                          <span class=" font-size-12 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> 05 AM</span>
                        </a>
                      </li>
                      <li class="dropdown-footer">
                        <a class="text-center" href="#"> View All </a>
                      </li>
                    </ul>
                  </li>
                  <!-- User Account -->
                  <li class="dropdown user-menu">
                    <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                      <span class="d-none d-lg-inline-block">Nombre</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <!-- User image -->
                      <li class="dropdown-header">
                        <div class="d-inline-block">
                          Nombre <small class="pt-1">Email</small>
                        </div>
                      </li>
                      <li>
                        <a href="">
                          <i class="mdi mdi-account"></i> Mi Perfil
                        </a>
                      </li>
                      <!--<li>
                        <a href="#">
                          <i class="mdi mdi-email"></i> Mensajes
                        </a>
                      </li>
                      <li class="right-sidebar-in">
                        <a href="javascript:0"> <i class="mdi mdi-settings"></i> Configuración </a>
                      </li>-->
                      <li class="dropdown-footer">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                          <i class="mdi mdi-logout"></i>
                          Cerrar Sesión
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                          </form>
                        </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
            </nav>
          </header>

      <!-- Contenedor -->
      <div class="content-wrapper" style="background-color: #F9F9F9 !important">
         <div class="content">
           <!-- Aqui va todo -->
           <div id="app">
             <!-- TERMINO DE QUITAR -->
               <main class="py-4">
                   @yield('content')
               </main>
           </div>
         </div>

        <footer class="footer mt-auto" style="height: 95px !important">
            <div class="copyright bg-white">
              <p>
                &copy; <span id="copy-year">2019</span> Copyright Karma Dashboard Bootstrap Template by
                <a
                  class="text-primary"
                  target="_blank"
                  >Francisco Pérez</a
                >.
              </p>
            </div>
            <script>
                var d = new Date();
                var year = d.getFullYear();
                document.getElementById("copy-year").innerHTML = year;
            </script>
          </footer>

        </div>
      </div>
  </div>


    <!-- Scripts -->

    <!-- <script src="{{ asset('js/jquery/jquery.min.js') }}" ></script> -->
    <script src="{{ asset('js/slimscrollbar/jquery.slimscroll.min.js') }}" defer></script>
    <!-- <script src="{{ asset('js/jekyll-search.min.js') }}" defer></script> -->
    <script src="{{ asset('js/sleek.bundle.js') }}" defer></script>
    <script src="{{ asset('js/toastr/toastr.min.js') }}" defer></script>
    <script src="{{ asset('js/nprogress/nprogress.js') }}" defer></script>
    <!-- SOLO PARA DEMOSTRACION -->
    <!-- <script src="{{ asset('js/charts/Chart.min.js') }}" defer></script> -->

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>
