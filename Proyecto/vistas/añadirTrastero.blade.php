{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Añadir Trastero')

{{-- Sección mensaje --}}
@section('content')

<script src="https://kit.fontawesome.com/12104efb99.js" crossorigin="anonymous"></script>
<script src="asset/js/añadirTrastero.js"></script>

    <form action="" method="POST">
        <div>
            <label for="nombre">NOMBRE:</label>
            <input type="text" name="nombre" id="nombre">
            <input type="submit" name="añadirEstanteria" value="AÑADIR ESTANTERÍA">
            <input type="submit" name="añadirCaja" value="AÑADIR CAJA">

        </div>
    </form>

        <div class="container">
            <div class="row">
                @foreach ($estanterias as $claveEstanteria => $baldas)
                @php
                $estanteriaRecuperada = $almacenEstanterias[$claveEstanteria]
                @endphp
                <div class="col-4">
                    <ul>
                        <li>
                            <form action="" method="POST">
                                <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                                <span class="papeleraOculta" contenteditable="false">{{$estanteriaRecuperada->getNombre()}}</span>

                            </form>
                        </li>
                    @foreach ($baldas as $claveBalda =>$valorBalda)
                        @php
                            $nombreBalda=""
                        @endphp
                        
                    @foreach ($almacenBaldas as $clave=>$balda)
                        @if(($balda->getIdEstanteria()==$claveEstanteria+1)&&($balda->getNombre()=="Balda ".($claveBalda+1)))
                            @php
                            $nombreBalda = $balda->getNombre()
                            @endphp
                        @endif
                    
                    @endforeach
                    
                        <ul> 
                            <li>
                                <form action="" method="POST">
                                    <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                                    <input type="hidden" name="numeroBalda" value="{{$claveBalda}}">
                                    <input type="hidden" name="nombreBalda" value="{{$nombreBalda}}">
                                    <span class="papeleraOculta" contenteditable="false">{{$nombreBalda}}</span>

                                </form>
                            </li> 
                            <ul>
                                @foreach($almacenCajas as $claveCaja=>$valorCaja)
                                    @if((intVal($valorCaja->getIdEstanteria())==$claveEstanteria+1)&&(intVal($valorCaja->getIdBalda())==$claveBalda+1))
                                    <li>
                                        <form action="" method="POST">
                                            <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                                            <input type="hidden" name="numeroBalda" value="{{$claveBalda}}">
                                            <input type="hidden" name="nombreBalda" value="{{$nombreBalda}}">
                                            <input type="hidden" name="nombreCaja" value="{{$valorCaja->getNombre()}}">
                                            <span class="papeleraOculta" contenteditable="false">{{$valorCaja->getNombre()}}</span>
                                        </form>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </ul>
                    @endforeach
                    </ul>
                    <form action="" method="POST">
                        <input type="submit" name="añadirBalda" value="AÑADIR BALDA">
                        <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        <div>
        <ul>
            @foreach ($almacenCajas as $claveCaja=>$valorCaja)
                @if(($valorCaja->getIdBalda()=="")&&($valorCaja->getIdEstanteria()==""))
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="nombreCaja" value="{{$valorCaja->getNombre()}}">
                            <span class="papeleraOculta" contenteditable="false">{{$valorCaja->getNombre()}}</span>
                        </form>
                    </li> 
                @endif
            @endforeach   
        </ul>
        </div>
        <form action="" method="POST">
        <div>

                <input type="submit" name="volverAcceso" value="VOLVER">
                <input type="submit" name="guardar" value="GUARDAR">
        </div>
        </form>

@endsection