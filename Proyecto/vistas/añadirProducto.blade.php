{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
 
<form action="{{ $_SERVER["PHP_SELF"] }}" method="POST">
  <div class="nav-item dropdown">
    <div class="nav-link dropdown-toggle " href="#"  data-bs-toggle="dropdown" aria-expanded="false">
      {{$usuario->getNombre()}} <i class="fa-solid fa-user fa-2xl"></i>
    </div>
    <ul class="dropdown-menu dropdown-menu-end ">
      <li><button class="dropdown" type="submit" name="perfilUsuario">Perfil usuario</button></li>
      <li><button class="dropdown" type="submit" name="cerrarSesion">Cerrar sesión</button></li>
    </ul>
  </div>
</form>
@endsection

{{-- Sección content --}}
@section('content')

<div class="container">
  <form action="{{$_SERVER["PHP_SELF"]}}">

    <div>

      <div>
        <h1>Producto</h1>
        <div class="inputsForm">
          <label for="nombreProducto">Nombre: </label>
          <input type="text" name="nombreProducto" class="form-control">
  
          <label for="descripcionProducto">Descripción: </label>
          <input type="text" name="descripcionProducto" class="form-control">
        </div>

      </div>
      <div>
        <h2>Ubicación</h2>
        <div class="inputsForm">
          <label for="idEstanteria">Estanteria</label>
          <select name="estanteria" id="idEstanteria">
            <option value="1">1</option>
            <option value="2">2</option>
          </select>

        </div>
      </div>
    </div>
  
    <div class="mb-3 inputsForm">
      <label for="inputAlias" class="form-label text-end">Alias: </label>
      <input id="inputAlias" type="text" name="alias" placeholder="Alias" class="form-control" value="{{$datos['alias']}}">
      @if(isset($error) && in_array("usuarioInvalido", $error)) 
          <div></div>
          <div class=" form-text textError p-1" >
              Los alias solo pueden contener letras, números, guiones y guiones bajos.
          </div>  
      @endif
      @if(isset($error) && in_array("aliasExiste", $error)) 
          <div></div>
          <div class="textError form-text p-1" >
              Este alias ya existe
          </div>  
      @endif
  </div>
  
  </form>
</div>

@endsection

