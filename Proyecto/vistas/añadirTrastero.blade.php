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
    </form>
    <div class="container">
        @foreach ($estanterias as $claveEstanteria => $baldas)
        <form action="" method="POST">
            <input type="submit" name="añadirBalda" value="AÑADIR BALDA">
            <input type="hidden" name="numeroEstanteria" value="{{$claveEstanteria}}">
            
        </form>
        <div class="container">
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
<!--                @foreach ($cajas as $claveCaja =>$valorCaja)
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="numeroCaja" value="{{$claveCaja}}">
                            <h6 class="papeleraOculta">Caja {{$claveCaja+1}}</h6>
                        </form>
                    </li>   
                @endforeach   -->
                    </ul>
                </ul>
            @endforeach
            </ul>
        </div>
        @endforeach

    </div>
    <form action="" method="POST">
    <div>
        <input type="submit" name="volverAcceso" value="VOLVER">
        <input type="submit" name="guardar" value="GUARDAR">
    </div>

    </form>
    
</div>

@endsection