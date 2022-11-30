{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@if($datosTrastero['tipo']=="guardar")
@section('title', 'Añadir Trastero')
@else
@section('title', 'Modificar Trastero')
@endif
{{-- Sección mensaje --}}
@section('content')

<script src="https://kit.fontawesome.com/12104efb99.js" crossorigin="anonymous"></script>
<script src="asset/js/añadirTrastero.js"></script>
<div class="container">
    <div>
        <p>{{$mensaje}}</p>
        @if($datosTrastero['guardado'])
        <span id="guardadoModificado" value="true" ></span>
        @else
        <span id="guardadoModificado" value="false" ></span>
        @endif
    </div>
    <div>
        <form action="" method="POST">
            <div>
                @if($datosTrastero['tipo']=="guardar")
                <label for="nombre">NOMBRE:</label>
                <input type="text" name="nombre" id="nombre">
                @endif
               
                <input type="submit" name="añadirEstanteria" value="AÑADIR ESTANTERÍA">
                <input type="submit" name="añadirCaja" value="AÑADIR CAJA"> 
<!--            </div>
            <div>-->
                @if($datosTrastero['tipo']=="guardar")
                <input type="submit" name="guardar" value="GUARDAR">
                @else
                <input type="submit" name="modificar" value="MODIFICAR">
                @endif
                <input type="submit" name="volverAcceso" value="VOLVER">
            </div>
        </form>
    </div>
    
    <div class="container">
        <div class="row">
            @foreach ($datosTrastero['almacenEstanterias'] as $estanteria)
<!--            @php
                $indice=0
            @endphp-->
            <div class="col-4">
                <ul>
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="nombreEstanteria" value="{{$estanteria->getNombre()}}">
                            <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}">
                            <span class="papeleraOculta" contenteditable="false">{{$estanteria->getNombre()}}</span>
                        </form>
                    </li>
                @php
                    $baldasRecuperadas=array()
                @endphp
                 
                @foreach ($datosTrastero['almacenBaldas'] as $balda)
                    @if($balda->getIdEstanteria()==$estanteria->getId())
                        @php
                        $baldasRecuperadas[]= $balda;
                        @endphp
                    @endif
                @endforeach
                @foreach ($baldasRecuperadas as $balda)
             
                    <ul> 
                        <li>
                            <form action="" method="POST">
                                <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}">
                                <input type="hidden" name="idBalda" value="{{$balda->getId()}}">
                                <span class="papeleraOculta" contenteditable="false">{{$balda->getNombre()}}</span>
                            </form>
                        </li> 
                        <ul>
                         
                            @foreach($datosTrastero['almacenCajas'] as $caja)
                                @if(($caja->getIdEstanteria()==$estanteria->getId())&&($caja->getIdBalda()==$balda->getId()))
                                <li>
                                    <form action="" method="POST">
                                        <input type="hidden" name="idBalda" value="{{$balda->getId()}}"> 
                                        <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}">
                                        <input type="hidden" name="idCaja" value="{{$caja->getId()}}">
                                        <span class="papeleraOculta" contenteditable="false">{{$caja->getNombre()}}</span>
                                    </form>
                                </li>
                                @endif
                            @endforeach
<!--                            @php
                                $indice++
                            @endphp-->
                        </ul>
                    </ul>
                @endforeach
                </ul>
                <form action="" method="POST">
                    <input type="submit" name="añadirBalda" value="AÑADIR BALDA">
                    <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}"
                    
                    <input type="hidden" name="nombreEstanteria" value="{{$estanteria->getNombre()}}">
                </form>
            </div>
            @endforeach
        </div>
    </div>
    <div>
        <ul>
            @foreach ($datosTrastero['almacenCajas'] as $caja)
                @if(($caja->getIdBalda()==null)&&($caja->getIdEstanteria()==null))
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="idCaja" value="{{$caja->getId()}}">
                            <span class="papeleraOculta" contenteditable="false">{{$caja->getNombre()}}</span>
                        </form>
                    </li> 
                @endif
            @endforeach   
        </ul>
    </div>
    <div>
        
    </div>
    
</div>

@endsection