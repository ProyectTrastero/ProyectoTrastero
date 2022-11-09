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
    <div class="container">lugar de las estanterías 
        @foreach ($estanterias as $clave => $valor)
        <form action="" method="POST">
            <input type="submit" name="añadirBalda" value="AÑADIR BALDA">
            <input type="hidden" name="numeroEstanteria" value="{{$clave}}">
        </form>
        <div class="container">
            <ul>
                <li>
                    <form action="" method="POST">
                        <input type="hidden" name="numeroEstanteria" value="{{$clave}}">
                        <h6 class="papeleraOculta">Estanteria {{$clave}}</h6>
                    </form>
                </li>
            
            @foreach ($baldas as $clave =>$valor)
                <ul> 
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="numeroBalda" value="{{$clave}}">
                            <h6 class="papeleraOculta">Balda {{$clave}}</h6>
                        </form>
                    </li> 
                    <ul>
                @foreach ($cajas as $clave =>$valor)
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="numeroCaja" value="{{$clave}}">
                            <h6 class="papeleraOculta">Caja {{$clave}}</h6>
                        </form>
                    </li>   
                @endforeach   
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
