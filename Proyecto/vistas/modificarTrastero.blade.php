{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Modificar Trastero')

{{-- Sección mensaje --}}
@section('content')
<div class="container">
    <form action="" method="POST">
        <div>
        <label for="nombre">NOMBRE:</label>
        <input type="text" name="nombre" id="nombre">
    </div>


    <div>
        <input type="submit" name="añadirEstantería" value="AÑADIR ESTANTERÏA">
        <input type="submit" name="añadirCaja" value="AÑADIR CAJA">
    </div>
<div>lugar de las estanterías </div>

    <div>
        <input type="submit" name="volverAcceso" value="VOLVER">
        <input type="submit" name="guardar" value="MODIFICAR">
    </div>

    </form>
    
</div>

@endsection