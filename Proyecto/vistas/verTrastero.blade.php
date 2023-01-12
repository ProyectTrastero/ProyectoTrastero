{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Ver Trastero')

{{-- Sección content --}}
@section('content')
<link rel="stylesheet" href="asset/css/verTrastero.css">
<script src="asset/js/mostrarProductos.js"></script>

<div class="vtContainer">
    <div id="vtVolver">
        <a href="accederTrastero.php"><button class="volver" name="volver">Volver</button></a>
    </div>
    
    <div id="vtnombre">
        <span>{{$datosTrastero['nombre']}}</span>
    </div>
    
    <div class="row">
        <div class="col-3">
            <ul id="menu">
                <span class="ocultos titulo">Trastero {{$datosTrastero['nombre']}}</span>
                <i id="trastero{{$datosTrastero['nombre']}}" class="productos  fa-solid fa-magnifying-glass"  name="{{$datosTrastero['nombre']}}" style="color: rgb(255,255,255,0)"></i>
                @foreach($datosTrastero['estanterias'] as $estanteria)
                <li>
                    <input type="checkbox" name="list" id="{{$estanteria->getId()}}">
                    <label class="ocultos" for="{{$estanteria->getId()}}">{{$estanteria->getNombre()}}</label>
                    <i id="estanteria{{$estanteria->getId()}}" class="productos  fa-solid fa-magnifying-glass" name="{{$estanteria->getId()}}" style="color: rgb(255,255,255,0)"></i>                    
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
                            <a class="ocultos" id="{{$balda->getId()}}" href="#r">{{$balda->getNombre()}}</a>
                            <i id="balda{{$balda->getId()}}"class="productos  fa-solid fa-magnifying-glass"  name="{{$balda->getId()}}" style="color: rgb(255,255,255,0)"></i> 
         
                        </li>
                        @else
                        <li>
                            <input type="checkbox" name="list" id="{{$balda->getId()}}">
                            <label class="ocultos" for="{{$balda->getId()}}">{{$balda->getNombre()}}</label>
                            <i id="balda{{$balda->getId()}}" class="productos  fa-solid fa-magnifying-glass"  name="{{$balda->getId()}}"  style="color: rgb(255,255,255,0)"></i> 
                             
                            <ul class="interior">
                                <li>
                                    <ul>
                                        @foreach ($cajasRecuperadas as $caja)
                                       
                                        <li>
                                            <a class ="ocultos" id ="{{$caja->getId()}}">{{$caja->getNombre()}}</a>
                                            <i id="caja{{$caja->getId()}}" class="productos  fa-solid fa-magnifying-glass"  name="{{$caja->getId()}}"  style="color: rgb(255,255,255,0)"></i> 
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
                <div class="row" id="vtSinUbicar">
                    <span><b>Sin ubicar:</b> </span>
                    @foreach ($datosTrastero['cajas'] as $caja)
                    @if(is_null($caja->getIdBalda())&&is_null($caja->getIdEstanteria()))
                    <div class="col-6">
                        <li>
                            <a class="ocultos" id ="{{$caja->getId()}}">{{$caja->getNombre()}}</a>
                            <i id="caja{{$caja->getId()}}" class="productos  fa-solid fa-magnifying-glass"  name="{{$caja->getId()}}"  style="color: rgb(255,255,255,0)"></i> 
                        </li>
                    </div>
                    
                    @endif
                    @endforeach
                </div>
            </ul>
        </div>
        <div class="col-9">
            <table id="vtTable">
                <tr class="vtTrCabecera">
                    <th class="vtTdFecha">Fecha</th>
                    <th class="vtTdNombre">Nombre</th>
                    <th class="vtTdDescripcion">Descripcion</th>
                    <th class="vtTdUbicacion">Ubicación</th>    
                </tr>
            </table>
            <span id="anotacion" class="anotacion"></span>
        </div>
    </div>
</div>

@endsection