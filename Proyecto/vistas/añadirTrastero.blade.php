{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Añadir Trastero')

{{-- Sección mensaje --}}
@section('content')

<script src="https://kit.fontawesome.com/12104efb99.js" crossorigin="anonymous"></script>
<script src="asset/js/añadirTrastero.js"></script>
<div class="container" disabled="true">
    <div>
        <p>{{$mensaje}}</p>
    </div>
    <div>
        <form action="" method="POST">
            <fieldset>
                <label for="nombre">NOMBRE:</label>
                <input type="text" name="nombre" id="nombre">
                <input type="submit" name="añadirEstanteria" value="AÑADIR ESTANTERÍA">
                <input type="submit" name="añadirCaja" value="AÑADIR CAJA">
            </fieldset>
                <input type="submit" name="volverAcceso" value="VOLVER">
                @if($datosTrastero['tipo']=="guardar")
                <input type="submit" name="guardar" value="GUARDAR">
                @else
                <input type="submit" name="modificar" value="MODIFICAR">
                @endif
            
        </form>
    </div>
    
    <div class="container">
        <div class="row">
            @foreach ($datosTrastero['estanterias'] as $claveEstanteria => $baldas)
            @php
                $indice=0
            @endphp
            <div class="col-4">
                <ul>
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="nombreEstanteria" value="{{$datosTrastero['almacenEstanterias'][$claveEstanteria]->getNombre()}}">
                            <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}"> 
                            <input type="hidden" name="idEstanteria" value="{{$datosTrastero['almacenEstanterias'][$claveEstanteria]->getId()}}">
                            <span class="papeleraOculta" contenteditable="false">{{$datosTrastero['almacenEstanterias'][$claveEstanteria]->getNombre()}}</span>
                        </form>
                    </li>
                @php
                    $baldasRecuperadas=array()
                @endphp
                @foreach ($baldas as $claveBalda =>$valorBalda)  
                @foreach ($datosTrastero['almacenBaldas'] as $clave=>$balda)
                    @if($balda->getIdEstanteria()==$datosTrastero['almacenEstanterias'][$claveEstanteria]->getId())
                        @php
                        $baldasRecuperadas[]= $datosTrastero['almacenBaldas'][$clave]
                        @endphp
                    @endif
                @endforeach
             
                    <ul> 
                        <li>
                            <form action="" method="POST">
                                <input type="hidden" name="idEstanteria" value="{{$datosTrastero['almacenEstanterias'][$claveEstanteria]->getId()}}">
                                <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                                <input type="hidden" name="nombreEstanteria" value="{{$datosTrastero['almacenEstanterias'][$claveEstanteria]->getNombre()}}">
                                <input type="hidden" name="numeroBalda" value="{{$claveBalda}}">
                                <input type="hidden" name="idBalda" value="{{$baldasRecuperadas[$indice]->getId()}}">
                                <input type="hidden" name="nombreBalda" value="{{$baldasRecuperadas[$indice]->getNombre()}}">
                                <span class="papeleraOculta" contenteditable="false">{{$baldasRecuperadas[$indice]->getNombre()}}</span>
                            </form>
                        </li> 
                        <ul>
                         
                            @foreach($datosTrastero['almacenCajas'] as $claveCaja=>$valorCaja)
                                @if(($valorCaja->getIdEstanteria()==$datosTrastero['almacenEstanterias'][$claveEstanteria]->getId())&&($valorCaja->getIdBalda()==$baldasRecuperadas[$indice]->getId()))
                                <li>
                                    <form action="" method="POST">
                                        <input type="hidden" name="idEstanteria" value="{{$datosTrastero['almacenEstanterias'][$claveEstanteria]->getId()}}">
                                        <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                                        <input type="hidden" name="numeroBalda" value="{{$claveBalda}}">
                                        <input type="hidden" name="nombreBalda" value="{{$baldasRecuperadas[$indice]->getNombre()}}">
                                        <input type="hidden" name="idBalda" value="{{$baldasRecuperadas[$indice]->getId()}}">
                                        <input type="hidden" name="nombreCaja" value="{{$valorCaja->getNombre()}}">
                                        <input type="hidden" name="idCaja" value="{{$valorCaja->getId()}}">
                                        <span class="papeleraOculta" contenteditable="false">{{$valorCaja->getNombre()}}</span>
                                    </form>
                                </li>
                                @endif
                            @endforeach
                            @php
                                $indice++
                            @endphp
                        </ul>
                    </ul>
                @endforeach
                </ul>
                <form action="" method="POST">
                    <input type="submit" name="añadirBalda" value="AÑADIR BALDA">
                    <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                    <input type="hidden" name="nombreEstanteria" value="{{$datosTrastero['almacenEstanterias'][$claveEstanteria]->getNombre()}}">
                </form>
            </div>
            @endforeach
        </div>
    </div>
    <div>
        <ul>
            @foreach ($datosTrastero['almacenCajas'] as $claveCaja=>$valorCaja)
                @if(($valorCaja->getIdBalda()=="")&&($valorCaja->getIdEstanteria()==""))
                    <li>
                        <form action="" method="POST">
                        <fieldset disabled>    
                            <input type="hidden" name="nombreCaja" value="{{$valorCaja->getNombre()}}">
                            <input type="hidden" name="idCaja" value="{{$valorCaja->getId()}}">
                            <span class="papeleraOculta" contenteditable="false">{{$valorCaja->getNombre()}}</span>
                            </fieldset>
                        </form>
                    </li> 
                @endif
            @endforeach   
        </ul>
    </div>
    
    
</div>

@endsection