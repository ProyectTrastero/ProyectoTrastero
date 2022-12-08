{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Ver Trastero')

{{-- Sección mensaje --}}
@section('content')
<link rel="stylesheet" href="asset/css/verTrastero.css">
<script src="asset/js/mostrarProductos.js"></script>

<div class="container">
    
    <div class="row" >
        <div class="col-5">
            <ul id="menu">
                @foreach($datosTrastero['estanterias'] as $estanteria)
                <li>
                    <input type="checkbox" name="list" id="{{$estanteria->getId()}}">
                    <label for="{{$estanteria->getId()}}">{{$estanteria->getNombre()}}</label>
                    <i class="productos fa-regular fa-eye" name="{{$estanteria->getId()}}" style="color: blue; border: white"></i>                    
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
                        <li>
                            <a id="{{$balda->getId()}}" href="#r">{{$balda->getNombre()}}</a>
                            <i class="productos fa-regular fa-eye" name="{{$balda->getId()}}" style="color: blue; border: white"></i> 
         
                        </li>
                        @else
                        <li>
                            <input type="checkbox" name="list" id="{{$balda->getId()}}">
                            <label for="{{$balda->getId()}}">{{$balda->getNombre()}}</label>
                            <i class="productos fa-regular fa-eye" name="{{$balda->getId()}}" style="color: blue; border: white"></i> 
                             
                            <ul class="interior">
                                <li>
                                    <ul>
                                        @foreach ($cajasRecuperadas as $caja)
                                       
                                        <li>
                                            <a id ="{{$caja->getId()}}">{{$caja->getNombre()}}</a>
                                            <i class="productos fa-regular fa-eye" name="{{$caja->getId()}}" style="color: blue; border: white"></i> 
                                        </li>
                                       
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
                    <div class="col-3">
                        <li>
                            <a id ="{{$caja->getId()}}">{{$caja->getNombre()}}</a>
                            <i class="productos fa-regular fa-eye" name="{{$caja->getId()}}" style="color: blue; border: white"></i> 
                        </li>
                    </div>
                    
                    @endif
                    @endforeach
                </div>
            </ul>
        </div>
        <div class="col-7">
            <table id="cabecera" style="border: black; width: 100%">
                <tr  border>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                </tr>
            </table>

        </div>

    </div>
    
</div>

<a href="accederTrastero.php"><input type="button" value="VOLVER"></a>
@endsection