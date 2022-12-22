<!DOCTYPE html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> --}}
        {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> --}}
        
        <link rel="stylesheet" href="asset/css/bootstrap.min.css">
        {{-- este es el font awesome para los iconos --}}
        <link rel="stylesheet" href="asset/css/all.css">
        <link rel="stylesheet" type="text/css" href="asset/css/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <script src="https://kit.fontawesome.com/12104efb99.js" crossorigin="anonymous"></script>
    </head>
    @if(!empty($_SESSION['usuario']))
    <body class="ejecutando">
    @else
    <body class="inicio">
    @endif
        @if(!empty($_SESSION['usuario']))
        <nav class="navbar">
        @else
        <nav>
        @endif
            <div class="container-fluid" >
                <a class="navbar-brand">
                    <img src="asset/img/icono2.PNG" alt="" class="rounded-circle" width="80" height="80">
                    VirtualBoxRoom
                </a>
                @if(!empty($_SESSION['usuario']))
                @php
                @$usuario=$_SESSION['usuario']
                @endphp
<!--                <div class="navegador">
                    <div>Inicio</div>
                    <div>Mis Trasteros</div>
                    <div>TrasteroEmma</div>
                </div>-->
                
                <form action="{{ $_SERVER["PHP_SELF"] }}">
                  <div class="nav-item dropdown">
                    <div class="nav-link dropdown-toggle " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      {{$usuario->getNombre()}} <i class="fa-solid fa-user fa-2xl"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end ">
                      <li><button class="dropdown" type="submit" name="perfilUsuario">Perfil usuario</button></li>
                      <li><button class="dropdown" type="submit" name="cerrarSesion">Cerrar sesión</button></li>
                    </ul>
                  </div>
                </form>
                @endif
               @yield('navbar')
            </div>
        </nav>
<!--        {{-- <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="800px" height="600px" viewBox="0 0 800 600" enable-background="new 0 0 800 600" xml:space="preserve">
                <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="174.7899" y1="186.34" x2="330.1259" y2="186.34" gradientTransform="matrix(0.8538 0.5206 -0.5206 0.8538 147.9521 -79.1468)">
                        <stop  offset="0" style="stop-color:#FFC035"/>
                        <stop  offset="0.221" style="stop-color:#F9A639"/>
                        <stop  offset="1" style="stop-color:#E64F48"/>
                </linearGradient>
        <circle fill="url(#SVGID_1_)" cx="266.498" cy="211.378" r="77.668"/>
       
                <linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="290.551" y1="282.9592" x2="485.449" y2="282.9592">
                        <stop  offset="0" style="stop-color:#FFA33A"/>
                        <stop  offset="0.0992" style="stop-color:#E4A544"/>
                        <stop  offset="0.9624" style="stop-color:#00B59C"/>
                </linearGradient>
        <circle fill="url(#SVGID_2_)" cx="388" cy="282.959" r="97.449"/>
               
                <linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="180.3469" y1="362.2723" x2="249.7487" y2="362.2723">
                        <stop  offset="0" style="stop-color:#12B3D6"/>
                        <stop  offset="1" style="stop-color:#7853A8"/>
                </linearGradient>
        <circle fill="url(#SVGID_3_)" cx="215.048" cy="362.272" r="34.701"/>
               
                <linearGradient id="SVGID_4_" gradientUnits="userSpaceOnUse" x1="367.3469" y1="375.3673" x2="596.9388" y2="375.3673">
                        <stop  offset="0" style="stop-color:#12B3D6"/>
                        <stop  offset="1" style="stop-color:#7853A8"/>
                </linearGradient>
        <circle fill="url(#SVGID_4_)" cx="482.143" cy="375.367" r="114.796"/>
       
                <linearGradient id="SVGID_5_" gradientUnits="userSpaceOnUse" x1="365.4405" y1="172.8044" x2="492.4478" y2="172.8044" gradientTransform="matrix(0.8954 0.4453 -0.4453 0.8954 127.9825 -160.7537)">
                        <stop  offset="0" style="stop-color:#FFA33A"/>
                        <stop  offset="1" style="stop-color:#DF3D8E"/>
                </linearGradient>
        <circle fill="url(#SVGID_5_)" cx="435.095" cy="184.986" r="63.504"/>
        </svg> --}}-->
        
      
        @yield('content')   
    </body>
        <!-- Scripts -->
        <script src="asset/js/bootstrap/bootstrap.min.js"></script>
        <script src="asset/js/jquery/jquery-3.6.0.min.js"></script>
        <script src="asset/js/modal.js"></script>
        
   
 