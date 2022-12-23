{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
<script src="asset/js/añadirProducto.js"></script>

@if($mensaje!=""))
<input type="hidden" id="mostrarModal" value="si">
@else
<input type="hidden" id="mostrarModal" value="no">
@endif
    

<!--<form action="{{ $_SERVER["PHP_SELF"] }}" method="POST">
  <div class="nav-item dropdown">
    <div class="nav-link dropdown-toggle " href="#"  data-bs-toggle="dropdown" aria-expanded="false">
      {{$usuario->getNombre()}} <i class="fa-solid fa-user fa-2xl"></i>
    </div>
    <ul class="dropdown-menu dropdown-menu-end ">
      <li><button class="dropdown" type="submit" name="perfilUsuario">Perfil usuario</button></li>
      <li><button class="dropdown" type="submit" name="cerrarSesion">Cerrar sesión</button></li>
    </ul>
  </div>
</form>-->
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
      <h2>Ubicación</h2>
      <div>
           <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Crear Etiqueta</button>
      </div>
      
      <div>
        <label for="radioUbicacionEstanteria">Ubicar en estanteria</label>
        <input class="me-3" type="radio" name="ubicacion" id="radioUbicacionEstanteria" value="ubicacionEstanteria">
        <label for="radioCajasSinAsignar">Ubicar en caja sin asignar</label>
        <input class="me-3" type="radio" name="ubicacion" id="radioCajasSinAsignar" value="ubicacionCajasSinAsignar">
        <label for="radioSinAsignar">No asignar ubicación</label>
        <input type="radio" name="ubicacion" id="radioSinAsignar" value="ubicacionSinAsignar">
        @if(isset($errores) && in_array("sinUbicacion", $errores)) 
            <div class="textError form-text p-1 text-start">
              Selecciona una ubicación.
            </div>
          @endif
      </div>
      
      <div id="idUbicacionEstanteria" class="hide">
        <div>
          <label for="selectEstanterias">Estanteria: </label>
          <select name="estanteria" id="selectEstanterias">
            @foreach ($estanterias as $estanteria)
              <option value="{{$estanteria->getId()}}">{{$estanteria->getNombre()}}</option>
            @endforeach
          </select>

        </div>
        <div>
          <label for="selectBaldas">Balda: </label>
          <select name="balda" id="selectBaldas"></select>

        </div>

        <div>
          <label for="selectCaja">Caja: </label>
          <select name="caja" id="selectCaja"></select>
        </div>

      </div>

      <div id="idUbicacionCajasSinAsignar" class="hide">
        <label for="selectCajasSinAsignar">Caja: </label>
        <select name="cajasSinAsignar" id="selectCajasSinAsignar"></select>
      </div>

      <h2>Etiquetas</h2>
      <div>
        <label for="">Seleccione etiqueta: </label>
        <select name="etiquetas" id="selectEtiquetas">
          @foreach ($etiquetas as $etiqueta)
              <option value="{{$etiqueta->getId()}}">{{$etiqueta->getNombre()}}</option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-secondary" name="añadirEtiqueta">Añadir etiqueta</button>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-secondary " name="volver">Volver</button>
        <button type="submit" class="btn btn-primary " name="añadir">Añadir</button>
      </div>

  </form>
</div>

<!-- Modal crear Etiqueta-->
<form action="" method="POST">
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="false" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Crear etiqueta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{$mensaje}}</p>
                    <label for="nombreEtiqueta">Nombre de la etiqueta: </label>
                    <input type="text" name="nombreEtiqueta">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" name="volverModal" data-bs-dismiss="modal">Volver</button>
                    <button type="submit" class="btn btn-secondary" name="crearEtiqueta" data-bs-dismiss="modal">Crear</button>
                   
                </div>
            </div>
        </div>
    </div>
</form>

@endsection





