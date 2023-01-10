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
                            <th class="col-1">Seleccionar</th>
                            <th class="col-2">Producto</th>
                            <th class="col-3">Descripción</th>
                            <th class="col-4">Ubicación</th>
                            <th class="col-2">   </th>
                        </tr>
                    @foreach ($productos as $valor)    
                    <tr class="bpTr">
                        <td><input type="checkbox" name="IdsProductos[]" value="{{$valor->getId()}}"></td>  
                        <td class="col-2"> {{$valor->getNombre()  }}</td> 
                        <td class="col-3"> {{$valor->getDescripcion()  }}</td>
                        <td class="col-4"> Estanteria: 
                                @if ($valor->getIdEstanteria() == null)
                                    no asignada
                                @else
                                    {{$valor->obtenerNumeroEstanteria($bd)  }}
                                @endif
                            , Balda: 
                                @if ($valor->obtenerNumeroBalda($bd) == null)
                                    no asignada
                                @else
                                    {{$valor->obtenerNumeroBalda($bd)  }}
                                @endif
                            , Caja: 
                                @if ($valor->obtenerNumeroCaja($bd) == null)
                                    no asignada
                                @else
                                    {{$valor->obtenerNumeroCaja($bd)  }} 
                                @endif
                                </td>
                        <td  class="col-2">
                            <form method="POST" action="" id='produModificar'>
                                <input type='hidden' name='id' value='{{$valor->getId()}}'>
                                <button type="submit" name="modificarProducto" id="modificarProducto">Modificar Producto</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </table>
                    <div class="bpbtnEliminar">
                         <button type="submit" name="eliminarProducto" id='eleminarProducto'>Eliminar Seleccionados</button>
                    </div>
                   
                </form>
            @endif
        @endif
</div>
@endsection

