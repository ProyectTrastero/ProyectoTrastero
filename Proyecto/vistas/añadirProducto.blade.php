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
          <select name="balda" id="selectBaldas">
            <option value="1">1</option>
            <option value="2">2</option>
          </select>

        </div>

        <div>
          <label for="idCaja">Caja</label>
          <select name="caja" id="idCaja">
            <option value="1">1</option>
            <option value="2">2</option>
          </select>

        </div>

        <div>
          <label for="idSinAsignar">Sin asignar</label>
          <input type="checkbox" name="sinAsignar" id="idSinAsignar">

        </div>
      </div>
    </div>
  
    
  </form>
</div>

@endsection

<script src="asset/js/añadirProducto.js"></script>



