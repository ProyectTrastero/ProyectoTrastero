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
    <ul class="dropdown-menu">
      <li><button class="dropdown" type="submit" name="perfilUsuario">Perfil usuario</button></li>
      <li><button class="dropdown" type="submit" name="cerrarSesion">Cerrar sesión</button></li>
    </ul>
  </div>
</form>-->
@endsection

{{-- Sección mensaje --}}
@section('content')

    @if ($msj1 != "")  
        <div class="alert alert-{{$msj1_tipo}} alert-dismissible fade show" role="alert"">
            {{$msj1}}
            <span type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></span>
        </div>
    @endif


<div  class='opciones'>
 
        <h3 class="inicial titulo">{{$miTrastero->getNombre()  }}</h3>

        <form method="POST" action="" name="formBusqueda">
            <label>Buscar por palabra: </label>
            &emsp;<input type="text" name="palabraBuscar" placeholder="Producto"><br/>
            @if (isset ($etiquetas))
                <label for="etiquetas">Buscar por mis etiqueta: </label><br/>
                    @if ($etiquetas != "")      
                            @foreach ($etiquetas as $valor)
                            <div class='etiqueta'>    
                                <input type="checkbox" name="IdsEtiquetas[]" value="{{$valor->getId()  }}">
                                &ensp; {{$valor->getNombre()  }}
                            </div>
                            @endforeach   
                    @endif
            @endif
            <div>
            <br/>
            <button type="submit" name="buscarProducto">Buscar producto</button>
            <button class ="volver" name="volverTrasteros">Volver</button>
            
            </div>
        </form><br/>
       
        @if (isset ($productos))
            @if ($productos != "")
                       
                <h3>Resultado</h3>
 
                <form action="" method="POST" id='formEliminarProducto'>
                    <table class="row">
                        <tr>
                        <th class="col-3">Seleccionar</th>
                        
                        <th class="col-3">Producto</th>
                        <th class="col-3">Descripción</th>
                        <th class="col-3">Ubicación</th>
                        <th class="col-3">   </th>
                        </tr>
                    @foreach ($productos as $valor)    
                    <tr>
                        <td><input type="checkbox" name="IdsProductos[]" value="{{$valor->getId()  }}"></td>  
                      
                        <td class="col-3"> {{$valor->getNombre()  }}</td> 
                        <td class="col-3"> {{$valor->getDescripcion()  }}</td>
                        <td class="col-3"> Estanteria: 
                                @if ($valor->getIdEstanteria() == null)
                                    no asignada
                                @else
                                    {{$valor->getIdEstanteria()  }}
                                @endif
                            , Balda: 
                                @if ($valor->getIdBalda() == null)
                                    no asignada
                                @else
                                    {{$valor->getIdBalda()  }}
                                @endif
                            , Caja: 
                                @if ($valor->getIdCaja() == null)
                                    no asignada
                                @else
                                    {{$valor->getIdCaja()  }} 
                                @endif
                                </td>
                        <td  class="col-3">
                            <form method="POST" action="" id='produModificar'>
                                <input type='hidden' name='id' value='{{$valor->getId()}}'>
                                <button type="submit" name="modificarProducto" id="modificarProducto">Modificar Producto</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </table>
                    <br/><button type="submit" name="eliminarProducto" id='eleminarProducto'>Eliminar Seleccionados</button>
                </form>
            @endif
        @endif
</div>
@endsection

