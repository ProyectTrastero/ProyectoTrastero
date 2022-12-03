{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Ver Trastero')

{{-- Sección mensaje --}}
@section('content')
<link rel="stylesheet" href="asset/css/verTrastero.css">
<script src="asset/js/mostrarProductos.js"></script>

<div class="container"">
    
    <div class="row" >
        <div class="col-4">
            <ul id="menu">
                @foreach($datosTrastero['estanterias'] as $estanteria)
               <li><input class="productos" type="checkbox" name="list" id="{{$estanteria->getId()}}"><label for="{{$estanteria->getId()}}">{{$estanteria->getNombre()}}</label>
               <ul class="interior">
                   @php 
                   $baldasRecuperadas = array()
                   @endphp
                   @foreach($datosTrastero['baldas'] as $balda)
                   @if($balda->getIdEstanteria() == $estanteria->getId())
                       @php
                       $baldasRecuperadas[]=$balda
                       @endphp
                       @endif
                   @endforeach
                   @foreach($baldasRecuperadas as $balda)
                       @php
                       $cajasRecuperadas = array()
                       @endphp
                       @foreach($datosTrastero['cajas'] as $caja)
                           @if($caja->getIdBalda()==$balda->getId())
                           @php
                           $cajasRecuperadas[]=$caja
                           @endphp
                           @endif
                       @endforeach
                       @if(empty($cajasRecuperadas))
                        <li><a class="productos" id="{{$balda->getId()}}" href="#r">{{$balda->getNombre()}}</a></li>
                       @else
                        <li><input  class="productos" type="checkbox" name="list" id="{{$balda->getId()}}"><label for="{{$balda->getId()}}">{{$balda->getNombre()}}</label>
                            <ul class="interior">
                                <li>
                                    <ul>
                                        @foreach ($cajasRecuperadas as $caja)
                                      
                                            
                                            <li id ="{{$caja->getId()}}" class="productos">{{$caja->getNombre()}}</li>
                                    
                                        
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @endforeach

                    </ul>
                </li>
               @endforeach
                <div class="row">
                    @foreach ($datosTrastero['cajas'] as $caja)
                    @if(is_null($caja->getIdBalda())&&is_null($caja->getIdEstanteria()))
                    <div class="col-3 productos" id="{{$caja->getId()}}">{{$caja->getNombre()}}</div>
                    @endif
                    @endforeach
                </div>
            </ul>
        </div>
        <div class="col-8">
            <table id="cabecera" style="border: black; width: 100%">
                <tr  border>
                    <th colspan="2">Fecha de Ingreso</th>
                    <th colspan="3">Nombre</th>
                    <th colspan="3">Ubicación</th>
                    <th colspan="4">Descripcion</th>
                </tr>
                <tr>
                    
                    <td colspan="2">27/12/2021</td>
                    <td colspan="3">prueba</td>
                    <td colspan="3">111</td>
                    <td colspan="4">222</td>
               
                </tr>

              
            </table>

        </div>

    </div>
    
</div>

<a href="accederTrastero.php"><input type="button" value="VOLVER"></a>
@endsection