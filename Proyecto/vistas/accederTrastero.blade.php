{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
 
<!--<form action="{{$_SERVER['PHP_SELF']}}">
  <div class="nav-item dropdown">
    <div class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      {{$usuario->getNombre()}} <i class="fa-solid fa-user fa-2xl"></i>
    </div>
    <ul class="dropdown-menu">
      <li><button class="dropdown" type="submit" name="perfilUsuario">Perfil usuario</button></li>
      <li><button class="dropdown" type="submit" name="cerrarSesion">Cerrar sesión</button></li>
    </ul>
  </div>
</form>-->
@endsection


{{-- Sección mensaje --}}
@section('content')

<div  class="opciones">
    <form method="POST" action="">
        <div class="vtVolver opcionesdiv">
            <span class="inicial titulo">Opciones para el {{$miTrastero->getNombre()}}</span>
            <button class ="volver" name="volverTodosTrasteros">Volver</button>
        </div>
        <div class="opcionesdiv">
            <button class="col-3" name="verTrastero" id="verTrastero"><span>Ver trastero</span></button>
            <button class="col-3" name="añadirProducto" id="añadirProducto"><span>Añadir Producto</span></button>
            <button class="col-3" name="buscarProducto" id="buscarProducto"><span>Buscar Producto</span></button>
        </div>
        
        
    </form>
</div>
@endsection