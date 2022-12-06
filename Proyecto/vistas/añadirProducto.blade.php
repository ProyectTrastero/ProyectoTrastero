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
  @if (@isset($msj['msj-content']))
  <div class="alert alert-{{$msj['msj-type']}} alert-dismissible fade show" role="alert"">
    {{$msj['msj-content']}}

    <?php $msj=array(); ?>

     <span type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></span>
  </div>
@endif


  <form action="{{$_SERVER["PHP_SELF"]}}" method="POST">

    

      <div>
        <h1>Producto</h1>
        <div class="inputsForm">
          <label for="nombreProducto">Nombre: </label>
          <input type="text" name="nombreProducto" class="form-control">
          @if(isset($errores) && in_array("nombreInvalido", $errores)) 
            <div></div>
            <div class="textError form-text p-1 text-start">
              Ingresa un nombre al producto.
            </div>
          @endif

          <label for="descripcionProducto">Descripción: </label>
          <input type="text" name="descripcionProducto" class="form-control">
        </div>

      </div>
      <div>
        <h2>Ubicación</h2>

        <div>
          <label for="selectEstanterias">Estanteria</label>
          <select name="estanteria" id="selectEstanterias">
            @foreach ($estanterias as $estanteria)
              <option value="{{$estanteria->getId()}}">{{$estanteria->getNombre()}}</option>
            @endforeach
          </select>

        </div>
        <div>
          <label for="selectBaldas">Balda</label>
          <select name="balda" id="selectBaldas"></select>

        </div>

        <div>
          <label for="selectCaja">Caja</label>
          <select name="caja" id="selectCaja"></select>

        </div>

        <div>
          <label for="idSinAsignar">Sin asignar</label>
          <input type="checkbox" name="sinAsignar" id="idSinAsignar">

        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-secondary " name="volver">Volver</button>
        <button type="submit" class="btn btn-primary " name="añadir">Añadir</button>
      </div>
    
  
    
  </form>
</div>

@endsection

<script src="asset/js/añadirProducto.js"></script>



