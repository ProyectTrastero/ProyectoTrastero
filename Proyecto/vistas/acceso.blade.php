{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
 
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

<div>
        <div class="acceso">
            <form method="POST" action="" id='formañadirtratero'>
            <h3>Diseña tu trastero</h3>
            <button type="submit" name="añadirTrastero" id="añadirTrastero"><span>Añadir Trastero</span></button>
            </form>
        </div>
        
        
        @if ($trasteros != "")
        <div class="container contenido acceso">             
                <h3>Mis trasteros</h3>
                @foreach ($trasteros as $valor)    
                <table class="row">
                    <td class="col-6"> {{$valor->getNombre()  }}</td> <br/>
                    <td  class="col-2">
                        <form method="POST" action="" id='formacceder'>
                            <input type='hidden' name='id' value='{{$valor->getId()}}'>
                            <button type="submit" name="acceder" id='acceder'><span>Acceder</span></button>
                        </form>
                    </td> <br/>
                    <td  class="col-2">
                        <form method="POST" action="" id='formmodificar'>
                            <input type='hidden' name='id' value='{{$valor->getId()}}'>
                            <button type="submit" name="modificar" id='modificar'<span>Modificar</span></button>
                        </form>
                    </td> <br/>
                    <td  class="col-2">
                        <form method="POST" action="" id='formeliminar'>
                            <input type='hidden' name='id' value='{{$valor->getId()}}'>
                            <button type="submit" name="eliminar" id='eliminar'<span>Eliminar</span></button>
                        </form>
                    </td>
                </table>
                @endforeach    
                <br/><br/><br/>
               
        </div>
        @else
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
