{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Añadir Trastero')

{{-- Sección mensaje --}}
@section('content')

<script src="https://kit.fontawesome.com/12104efb99.js" crossorigin="anonymous"></script>
<script src="asset/js/añadirTrastero.js"></script>

<div class="container">
    <form action="" method="POST">
        <div>
            <label for="nombre">NOMBRE:</label>
            <input type="text" name="nombre" id="nombre">
        </div>
        <div>
            <input type="submit" name="añadirEstanteria" value="AÑADIR ESTANTERÍA">
            <input type="submit" name="añadirCaja" value="AÑADIR CAJA">
            
           
        </div>
    
    <div class="container">
        <div class="row">
            @foreach ($estanterias as $claveEstanteria => $baldas)
            
            <div class="col-4">
                <ul>
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                            <span class="papeleraOculta">Estanteria {{$claveEstanteria+1}}</span>
                        </form>
                    </li>

                @foreach ($baldas as $claveBalda =>$valorBalda)
                    <ul> 
                        <li>
                            <form action="" method="POST">
                                <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                                <input type="hidden" name="numeroBalda" value="{{$claveBalda}}">
                                <span class="papeleraOculta">Balda {{$claveBalda+1}}</span>
                            </form>
                        </li> 
                        <ul>
                            @foreach($cajas as $claveCaja=>$valorCaja)
                                @if(($valorCaja['numeroEstanteria']==$claveEstanteria+1)&&($valorCaja['numeroBalda']==$claveBalda+1))
                                <li>
                                    <form action="" method="POST">
                                        <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
                                        <input type="hidden" name="numeroBalda" value="{{$claveBalda}}">
                                        <input type="hidden" name="numeroCaja" value="{{$claveCaja}}">
                                        <span class="papeleraOculta">Caja {{$claveCaja+1}}</span>
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
    <ul>
        @foreach ($cajas as $claveCaja=>$valorCaja)
            @if(($valorCaja['numeroBalda']=="")&&($valorCaja['numeroEstanteria']==""))
            
                <li>
                    <form action="" method="POST">
                        <input type="hidden" name="numeroCaja" value="{{$claveCaja}})">
                        <span class="papeleraOculta">Caja {{$claveCaja+1}}</span>
                    </form>
                </li> 
            @endif
        @endforeach   
    </ul>
    
    <div>
        <!--<form action="" method="POST">-->
            <input type="submit" name="volverAcceso" value="VOLVER">
            <input type="submit" name="guardar" value="GUARDAR">
        </form>
    </div>

   
    
</div>

@endsection