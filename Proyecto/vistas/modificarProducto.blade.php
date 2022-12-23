{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')



{{-- Sección content --}}
@section('content')

<div class="container">
  {{-- alerts desde js --}}
  <div  id="alerts"></div>
  {{-- alerts desde php --}}
  @if (@isset($msj['msj-content']))
  <div class="alert alert-{{$msj['msj-type']}} alert-dismissible fade show" role="alert"">
    {{$msj['msj-content']}}

    <?php $msj=array(); ?>

     <span type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></span>
  </div>
@endif


  <form action="{{$_SERVER["PHP_SELF"]}}" method="POST">

    <div class="row mt-3">
      <section class="col-lg-6">
        <h1>Producto</h1>
        <div class="inputsForm">
          <label for="nombreProducto">Nombre: </label>
          <input type="text" name="nombreProducto" class="form-control" value="@isset($producto) {{$producto->getNombre()}} @endisset">
          @if(isset($errores) && in_array("nombreInvalido", $errores)) 
            <div></div>
            <div class="textError form-text p-1 text-start">
              Ingresa un nombre al producto.
            </div>
          @endif
  
          <label for="descripcionProducto">Descripción: </label>
          <input type="text" name="descripcionProducto" class="form-control" value="@isset($producto) {{$producto->getDescripcion()}} @endisset">
        </div>
  
      </section>
      <section class="col-lg-6 mt-2">
        <h2>Ubicación</h2>
        <div class="d-flex flex-wrap">
          <div>
            <label for="radioUbicacionEstanteria">Ubicar en estanteria</label>
            <input class="me-3" type="radio" name="ubicacion" id="radioUbicacionEstanteria" value="ubicacionEstanteria" @if (!is_null($estanterias))
                checked
            @endif>
          </div>
          <div>
            <label for="radioCajasSinAsignar">Ubicar en caja sin ubicación</label>
            <input class="me-3" type="radio" name="ubicacion" id="radioCajasSinAsignar" value="ubicacionCajasSinAsignar" @if (is_null($estanterias) && !is_null($cajas))
                checked
            @endif>
          </div>
          <div>
            <label for="radioSinAsignar">No asignar ubicación</label>
            <input type="radio" name="ubicacion" id="radioSinAsignar" value="ubicacionSinAsignar" @if (is_null($estanterias) && is_null($cajas))
                checked
            @endif>
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
              <option value="{{$estanteria->getId()}}" @if ($estanteria->getId() == $producto->getIdEstanteria()) selected @endif>{{$estanteria->getNombre()}}</option>
            @endforeach
          </select>

          <label for="selectBaldas">Balda: </label>
          <select name="balda" id="selectBaldas" disabled>
            @foreach ($baldas as $balda)
              <option value="{{$balda->getId()}}" @if ($balda->getId() == $producto->getIdBalda()) selected @endif>{{$balda->getNombre()}}</option>
            @endforeach
          </select>

          <label for="selectCaja">Caja: </label>
          <select name="caja" id="selectCaja" disabled>
            @foreach ($cajas as $caja)
              <option value="{{$caja->getId()}}" @if ($caja->getId() == $producto->getIdCaja()) selected @endif>{{$caja->getNombre()}}</option>
            @endforeach
          </select>
        </div>
  
        <div id="idUbicacionCajasSinAsignar" class="hide inputsSelect mt-2">
          <label for="selectCajasSinAsignar">Caja: </label>
          <select name="cajasSinAsignar" id="selectCajasSinAsignar" disabled>
            @foreach ($cajasSinUbicar as $caja)
              <option value="{{$caja->getId()}}" @if ($caja->getId() == $producto->getIdCaja()) selected @endif>{{$caja->getNombre()}}</option>
            @endforeach
          </select>
        </div>
  
      </section>
    </div>
    <section class="containerEtiquetas mt-2">
      <h2>Etiquetas</h2>
      <div id="inputEtiquetas"></div>
      <div id="etiquetasProducto">
        @foreach ($etiquetasProducto as $etiquetaProducto)
            <span class="etiqueta d-inline-flex align-items-center mb-1"> 
              {{$etiquetaProducto['nombreEtiqueta']}} 
              <span class="btn-close close-etiqueta" type="button"></span>
            </span>
        @endforeach
      </div>

      <div>
        <label for="">Seleccione etiqueta: </label>
        <select name="etiquetas" id="selectEtiquetas">
          @foreach ($etiquetasUsuario as $etiquetaUsuario)
              <option value="{{$etiquetaUsuario->getId()}}">{{$etiquetaUsuario->getNombre()}}</option>
          @endforeach
        </select>
        <div class="d-inline-block mt-1">
          <button type="button" class="btn btn-secondary" name="añadirEtiqueta" id="añadirEtiqueta">Añadir etiqueta</button>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearEtiquetaModal">Crear Etiqueta</button>
        </div>
      
      </div>
    </section>

    <div class="text-end mt-3">
      <button type="submit" class="btn btn-secondary " name="volver">Volver</button>
      <button type="submit" class="btn btn-primary " name="añadir">Añadir</button>
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
                   
                    <label for="nombreEtiqueta">Nombre de la etiqueta: </label>
                    <input type="text" name="nombreEtiqueta" id="nombreEtiqueta">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" name="volverModal" data-bs-dismiss="modal">Volver</button>
                    <!--<button type="submit" name="añadirUbicacion" id="botonAñadir" class="btn btn-secondary" data-bs-dismiss="modal">AÑADIR</button>-->
                    <button type="button"  class="btn btn-secondary" name="crearEtiqueta" id="crearEtiqueta" data-bs-dismiss="modal">Crear</button>
                    <!--<input type="submit" name="crearEtiqueta" value="Crear">-->
                </div>
            </div>
        </div>
    </div>
</form>




@endsection



<script src="asset/js/modificarProducto.js"> </script>


