{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Buscar Producto')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')

@endsection

{{-- Sección mensaje --}}
@section('content')
    <div class="avisos">
    @if ($msj1 != "")  
        <div class="alert alert-{{$msj1_tipo}} alert-dismissible fade show" role="alert">
            {{$msj1}}
            <span type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></span>
        </div>
    @endif
    </div>

<div class="opciones">
    <form method="POST" action="" name="formBusqueda">
        <div class="divVolver">
            <button class ="volver" name="volverTrasteros">Volver</button>
        </div>
        <h3 class="inicial titulo">{{$miTrastero->getNombre()  }}</h3>
        <div  class="bpCabecera row">
            <div class="col-12">
                <div>
                <label>Buscar por palabra: </label>
                &emsp;<input type="text" name="palabraBuscar" placeholder="Producto"><br/>
            </div>
            <div>
                <button type="submit" name="buscarProducto">Buscar producto</button>
            </div>
            </div>
            
            <div>
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
            </div>
        </div>
    </form>
        
       
        @if (isset ($productos))
            @if ($productos != "")
            <div class="bpresult">
                <h3>Resultado</h3>
            </div>      
                
 
                <form action="" method="POST" id='formEliminarProducto'>
                    <table id="bpTable" class="row">
                        <tr class="bpTrCabecera">
                            <th></th>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th class="col-3">Descripción</th>
                            <th class="col-4">Ubicación</th>
                            <th class="col-1">   </th>
                        </tr>
                    @foreach ($productos as $valor)    
                    <tr class="bpTr">
                        <td><input type="checkbox" name="IdsProductos[]" value="{{$valor->getId()}}"></td>
                        <td class="tdCenter"> {{$valor->getFechaIngreso()  }}</td> 
                        <td class="tdCenter"> {{$valor->getNombre()  }}</td> 
                        <td class="col-3"> {{$valor->getDescripcion()  }}</td>
                        <td class="col-4"><b> *E:</b> 
                                @if ($valor->getIdEstanteria() == null)
                                    Sin asignar
                                @else
                                    {{$valor->obtenerNombreEstanteria($bd)  }}
                                @endif
                                 <b>*B:</b> 
                                @if ($valor->obtenerNombreBalda($bd) == null)
                                    Sin asignar
                                @else
                                    {{$valor->obtenerNombreBalda($bd)  }}
                                @endif
                                   <b>*C:</b> 
                                @if ($valor->obtenerNombreCaja($bd) == null)
                                    Sin asignar
                                @else
                                    {{$valor->obtenerNombreCaja($bd)  }} 
                                @endif
                                </td>
                        <td  class="col-1">
                            <form method="POST" action="" id='produModificar'>
                                <input type='hidden' name='id' value='{{$valor->getId()}}'>
                                <button type="submit" name="modificarProducto" style="margin: 0px;padding: 7px;" id="modificarProducto">Modificar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </table>
                    <span class="anotacion">*<sub>1</sub> Estanteria *<sub>2</sub> Balda *<sub>3</sub> Caja</span>
                    <div class="bpbtnEliminar">
                         <button type="submit" name="eliminarProducto" id='eleminarProducto'>Eliminar Seleccionados</button>
                    </div>
                    
                </form>
            @endif
        @endif
</div>
@endsection

