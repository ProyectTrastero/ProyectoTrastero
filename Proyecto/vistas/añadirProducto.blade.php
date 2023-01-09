{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')



{{-- Sección content --}}
@section('content')

<div class="container contenedorAñadir">
  {{-- alerts desde js --}}
  <div  id="alerts"></div>
  {{-- alerts desde php --}}
  @if (@isset($msj['msj-content']))
  <div id="alertsPhp" class="alert alert-{{$msj['msj-type']}} alert-dismissible fade show" role="alert"">
    {{$msj['msj-content']}}

    <?php $msj=array(); ?>

  </div>
@endif


  <form action="{{$_SERVER["PHP_SELF"]}}" method="POST">

    <div class=" row mt-3">
      <section class="col-lg-6">
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
  
      </section>
      <section class="col-lg-6 mt-2">
        <h2>Ubicación</h2>
        <div class="d-flex flex-wrap">
          <div>
            <label for="radioUbicacionEstanteria">Ubicar en estanteria</label>
            <input class="me-3" type="radio" name="ubicacion" id="radioUbicacionEstanteria" value="ubicacionEstanteria">
          </div>
          <div>
            <label for="radioCajasSinAsignar">Ubicar en caja sin ubicación</label>
            <input class="me-3" type="radio" name="ubicacion" id="radioCajasSinAsignar" value="ubicacionCajasSinAsignar">
          </div>
          <div>
            <label for="radioSinAsignar">No asignar ubicación</label>
            <input type="radio" name="ubicacion" id="radioSinAsignar" value="ubicacionSinAsignar" checked>
          </div>

          @if(isset($errores) && in_array("sinUbicacion", $errores)) 
            <div class="textError form-text p-1 text-start">
              Selecciona una ubicación.
            </div>
          @endif
        </div>
        
        <div id="idUbicacionEstanteria" class="hide inputsSelect mt-2">
          <label for="selectEstanterias">Estanteria: </label>
          <select name="estanteria" id="selectEstanterias" disabled>
            @foreach ($estanterias as $estanteria)
              <option value="{{$estanteria->getId()}}">{{$estanteria->getNombre()}}</option>
            @endforeach
          </select>

          <label for="selectBaldas">Balda: </label>
          <select name="balda" id="selectBaldas" disabled></select>

          <label for="selectCaja">Caja: </label>
          <select name="caja" id="selectCaja" disabled></select>
        </div>

        
  
        <div id="idUbicacionCajasSinAsignar" class="hide inputsSelect mt-2">
          <label for="selectCajasSinAsignar">Caja: </label>
          <select name="cajasSinAsignar" id="selectCajasSinAsignar" disabled></select>
        </div>
  
      </section>
    </div>
    <section class="containerEtiquetas mt-2">
      <h2>Etiquetas</h2>
      <div>
        <div id="inputEtiquetas"></div>
        <div id="etiquetasProducto"></div>
        <label for="">Seleccione etiqueta: </label>
        <select name="etiquetas" id="selectEtiquetas">
          @foreach ($etiquetas as $etiqueta)
              <option value="{{$etiqueta->getId()}}">{{$etiqueta->getNombre()}}</option>
          @endforeach
        </select>
        <div class="d-inline-block mt-1">
          <button type="button" name="añadirEtiqueta" id="añadirEtiqueta">Añadir etiqueta</button>
          <button type="submit" name="eliminarEtiqueta" id="eliminarEtiqueta">EliminarEtiqueta</button>
          <button type="button" data-bs-toggle="modal" data-bs-target="#crearEtiquetaModal">Crear Etiqueta</button>
        </div>
       
      
      </div>
    </section>

    <div class="text-end mt-3">
      <button type="submit" class="volver" name="volver">Volver</button>
      <button type="submit"  name="añadir" id="añadirProducto">Añadir</button>
    </div>

  </form>
</div>

<!-- Modal crear Etiqueta-->
<form>
    <div class="modal fade" id="crearEtiquetaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="false" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Crear etiqueta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{$mensaje}}</p>
                    <label for="nombreEtiqueta">Nombre de la etiqueta: </label>
                    <input type="text" name="nombreEtiqueta" id="nombreEtiqueta">
                </div>
                <div class="modal-footer">
                    <button type="button" class="volver" name="volverModal" data-bs-dismiss="modal">Volver</button>
                    <!--<button type="submit" name="añadirUbicacion" id="botonAñadir" class="btn btn-secondary" data-bs-dismiss="modal">AÑADIR</button>-->
                    <button type="button"  name="crearEtiqueta" id="crearEtiqueta" data-bs-dismiss="modal">Crear</button>
                    <!--<input type="submit" name="crearEtiqueta" value="Crear">-->
                </div>
            </div>
        </div>
    </div>
</form>




@endsection



<script src="asset/js/añadirProducto.js"></script>


