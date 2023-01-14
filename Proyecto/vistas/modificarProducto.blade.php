{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Modificar producto')



{{-- Sección content --}}
@section('content')

{{-- alerts --}}
<div class="avisos">
    <div  id="alerts"></div>
</div>
<div class="vtVolver opcionesdiv">
    <span class="inicial titulo">Trastero {{$miTrastero->getNombre()}}</span>
</div>
<div class="container contenedorModificarProducto">
  
<form action="{{$_SERVER["PHP_SELF"]}}" method="POST" id="formProducto">
    <div class="vtVolver opcionesdiv">
        <span class="inicial titulo">MODIFICAR PRODUCTO</span>
    </div>
    <div class="row mt-3">
      <section class="col-lg-6">
        <h3>Producto</h3>
        <div class="inputsForm">

          <label for="nombreProducto">Nombre</label>
          <input type="text" name="nombreProducto" class="form-control" value="@isset($producto) {{$producto->getNombre()}} @endisset">
          
          <div></div>
          <div id="nombreInvalido" class="my-2"> </div>
          
  
          <label for="descripcionProducto">Descripción</label>
          <input type="text" name="descripcionProducto" class="form-control" value="@isset($producto) {{$producto->getDescripcion()}} @endisset">
        </div>
  
      </section>
      <section class="col-lg-6 mt-2">
        <h3>Ubicación</h3>
        <div class="d-flex flex-wrap">
          <div class="form-check me-4">
            <input class="form-check-input" type="radio" name="ubicacion" id="radioUbicacionEstanteria" value="ubicacionEstanteria" @if (!is_null($producto->getIdEstanteria()))
                checked
            @endif>
            <label class="form-check-label" for="radioUbicacionEstanteria">Ubicar en estanteria</label>
          </div>
          <div class="form-check me-4">
            <input class="form-check-input" type="radio" name="ubicacion" id="radioCajasSinAsignar" value="ubicacionCajasSinAsignar" @if (is_null($producto->getIdEstanteria()) && !is_null($producto->getIdCaja()))
              checked
            @endif>
            <label class="form-check-label" for="radioCajasSinAsignar">Ubicar en caja sin ubicación</label>
          </div>
          <div class="form-check ">
            <input class="form-check-input" type="radio" name="ubicacion" id="radioSinAsignar" value="ubicacionSinAsignar" @if (is_null($producto->getIdEstanteria()) && is_null($producto->getIdCaja()))
                checked
            @endif>
            <label class="form-check-label" for="radioSinAsignar">No asignar ubicación</label>
          </div>

        </div>
        
        <div id="idUbicacionEstanteria" class="hide inputsSelect mt-2">
          <label for="selectEstanterias">Estanteria: </label>
          <select name="estanteria" id="selectEstanterias" class="form-select" disabled>
            @if (count($estanterias)==0)
              <option value="0">No hay estanterias</option>
            @endif
            @foreach ($estanterias as $estanteria)
              <option value="{{$estanteria->getId()}}" @if ($estanteria->getId() == $producto->getIdEstanteria()) selected @endif>{{$estanteria->getNombre()}}</option>
            @endforeach
          </select>

          <label for="selectBaldas">Balda: </label>
          <select name="balda" id="selectBaldas" class="form-select" disabled>
            @if (count($baldas)==0)
              <option value="0">No hay baldas</option>
            @endif
            @foreach ($baldas as $balda)
              <option value="{{$balda->getId()}}" @if ($balda->getId() == $producto->getIdBalda()) selected @endif>{{$balda->getNombre()}}</option>
            @endforeach
          </select>

          <label for="selectCaja">Caja: </label>
          <select name="caja" id="selectCaja" class="form-select" disabled>
            @if (count($cajas)==0)
                <option value="0">No hay cajas</option>
            @else
              <option value="0">No ubicar en caja</option>
              @foreach ($cajas as $caja)
              <option value="{{$caja->getId()}}" @if ($caja->getId() == $producto->getIdCaja()) selected @endif>{{$caja->getNombre()}}</option>
              @endforeach

            @endif
            
          </select>
        </div>
  
        <div id="idUbicacionCajasSinUbicar" class="hide inputsSelect mt-2">
          <label for="selectCajasSinUbicar">Caja: </label>
          <select name="cajasSinUbicar" id="selectCajasSinUbicar" class="form-select" disabled>
            @if (count($cajasSinUbicar)==0)
              <option value="0">No hay cajas</option>
            @endif
            @foreach ($cajasSinUbicar as $caja)
              <option value="{{$caja->getId()}}" @if ($caja->getId() == $producto->getIdCaja()) selected @endif>{{$caja->getNombre()}}</option>
            @endforeach
           
          </select>
        </div>
  
      </section>
    </div>
    <section class="containerEtiquetas mt-2">
      <h2>Etiquetas</h2>
      {{-- div en donde tendremos los id's de las etiquetas que añadiremos al producto --}}
      <div id="inputEtiquetas"></div>
      {{-- div donde se generan las etiquetas --}}
      <div id="etiquetasProducto">
        @foreach ($etiquetasProducto as $etiquetaProducto)
            <span class="etiqueta d-inline-flex align-items-center mb-1" id="{{$etiquetaProducto['idEtiqueta']}}"> 
              {{$etiquetaProducto['nombreEtiqueta']}} 
              <span class="btn-close close-etiqueta" type="button"></span>
            </span>
        @endforeach
      </div>

      <label for="">Seleccione etiqueta: </label>
      <select name="etiquetas" id="selectEtiquetas" class="form-select mb-2">
        @foreach ($etiquetasUsuario as $etiquetaUsuario)
            <option value="{{$etiquetaUsuario->getId()}}">{{$etiquetaUsuario->getNombre()}}</option>
        @endforeach
      </select>
      <div class="d-inline-block mb-2">
        <button type="button" class="mb-2" name="añadirEtiqueta" id="añadirEtiqueta">Añadir etiqueta</button>
        <button type="button" class="mb-2" data-bs-toggle="modal" data-bs-target="#crearEtiquetaModal">Nueva Etiqueta</button>
        <button type="button" class="mb-2" data-bs-toggle="modal" data-bs-target="#eliminarEtiquetaModal" id="openEliminarEtiquetaModal">Eliminar etiqueta</button>
      </div>
      
      
    </section>

    <div class="text-end mt-3">
      <a href="buscarProducto.php" class="volver btn">Volver</a>
      <button type="submit" name="modificarProducto" id="modificarProducto" value="{{$producto->getId()}}">Modificar</button>
    </div>

  </form>
</div>

<!-- Modal crear Etiqueta-->
<form id="formCrearEtiqueta">
    <div class="modal fade" id="crearEtiquetaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="false" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Crear etiqueta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body inputsForm">
                   
                    <label for="nombreEtiqueta">Nombre de la etiqueta: </label>
                    <input class="ms-1" type="text" name="nombreEtiqueta" id="nombreEtiqueta">
                </div>
                <div class="modal-footer">
                    <button type="button" class="volver" name="volverModal" data-bs-dismiss="modal">Volver</button>
                    <!--<button type="submit" name="añadirUbicacion" id="botonAñadir" class="btn btn-secondary" data-bs-dismiss="modal">AÑADIR</button>-->
                    <button type="submit" name="crearEtiqueta" id="crearEtiqueta" data-bs-dismiss="modal">Crear</button>
                    <!--<input type="submit" name="crearEtiqueta" value="Crear">-->
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal eliminal Etiqueta-->
<form id="formEliminarEtiqueta">
  <div class="modal fade" id="eliminarEtiquetaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="false" >
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5">Eliminar etiqueta</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <p>Desea eliminar la etiqueta: <b><label id="nombreEtiquetaSelect"></label></b> </p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="volver" name="volverModal" data-bs-dismiss="modal">Volver</button>
                  <!--<button type="submit" name="añadirUbicacion" id="botonAñadir" class="btn btn-secondary" data-bs-dismiss="modal">AÑADIR</button>-->
                  <button type="submit" name="eliminarEtiqueta" id="eliminarEtiqueta" data-bs-dismiss="modal">Eliminar</button>
                  <!--<input type="submit" name="crearEtiqueta" value="Crear">-->
              </div>
          </div>
      </div>
  </div>
</form>


@endsection



<script src="asset/js/modificarProducto.js"> </script>


