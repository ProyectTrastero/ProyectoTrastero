{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
 
<!--<form action="{{ $_SERVER["PHP_SELF"] }}">
<!--<form action="{{ $_SERVER["PHP_SELF"] }}">
  <div class="nav-item dropdown">
    <div class="nav-link dropdown-toggle " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      {{$usuario->getNombre()}} <i class="fa-solid fa-user fa-2xl"></i>
    </div>
    <ul class="dropdown-menu dropdown-menu-end ">
      <li><button class="dropdown" type="submit" name="perfilUsuario">Perfil usuario</button></li>
      <li><button class="dropdown" type="submit" name="cerrarSesion">Cerrar sesión</button></li>
    </ul>
  </div>
</form>-->

@endsection


{{-- Sección mensaje --}}
@section('content')
<div class="container">
        <div class="cabecera">
            <form method="POST" action="" id='formañadirtratero'>
                <span>Diseña tu trastero: </span>
            <button type="submit" name="añadirTrastero" id="añadirTrastero"><span>Añadir Trastero</span></button>
            </form>
        </div>
        
        
        
        @if ($trasteros != "")
        <!--<div class="container">--> 
            <div class="acceso">
                <h3>Mis trasteros</h3> 
                <br>
                <table class="row">
                    @foreach ($trasteros as $valor)
                    <tr>
                        <td class="col-6 inicial"> {{$valor->getNombre()  }}</td>
                        <td  class="col-2">
                            <form method="POST" action="" id='formacceder'>
                                <input type='hidden' name='id' value='{{$valor->getId()}}'>
                                <button type="submit" name="acceder" id='acceder'><span>Acceder</span></button>
                            </form>
                        </td>
                        <td  class="col-2">
                            <form method="POST" action="" id='formmodificar'>
                                <input type='hidden' name='id' value='{{$valor->getId()}}'>
                                <button type="submit" name="modificar" id='modificar'<span>Modificar</span></button>
                            </form>
                        </td>
                        <td  class="col-2">
                            <form method="POST" action="" id='formeliminar'>
                                <input type='hidden' name='id' value='{{$valor->getId()}}'>
                                <button type="submit" name="eliminar" id='eliminar'<span>Eliminar</span></button>
                            </form>
                        </td>
                    </tr>
                    
                    @endforeach 
                </table>
                   
            </div>   
        <!--</div>-->
        @else
            <div class="acceso">           
            <div class="acceso">           
            <form method="POST" action="" id='formtrasteros'>
                <h3>Mis trasteros</h3><!-- comment -->
                <h2>Usted aun no  tiene trasteros</h2>
                <br/><br/><br/>
            </form>     
            </div>
        @endif
</div>

@endsection
