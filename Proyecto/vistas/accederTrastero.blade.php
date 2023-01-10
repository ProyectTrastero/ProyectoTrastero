{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Acceso trastero')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
 

@endsection


{{-- Sección mensaje --}}
@section('content')
<div class="avisos"></div>
<div  class="opciones">
    <form method="POST" action="">
        <div class="divVolver"> 
             <button class ="volver" name="volverTodosTrasteros">Volver</button>
        </div>
        <div class="vtVolver opcionesdiv">
            <span class="inicial titulo">Opciones para el trastero {{$miTrastero->getNombre()}}</span>
        </div>
        <div class="opcionesdiv">
            <button class="col-3" name="verTrastero" id="verTrastero"><span>Ver trastero</span></button>
            <button class="col-3" name="añadirProducto" id="añadirProducto"><span>Añadir Producto</span></button>
            <button class="col-3" name="buscarProducto" id="buscarProducto"><span>Buscar Producto</span></button>
        </div>
        
        
    </form>
</div>
@endsection