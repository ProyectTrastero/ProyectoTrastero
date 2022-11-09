{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Trastero Seleccionado')

{{-- Sección mensaje --}}
@section('content')

<div class="container">
    <form method="POST" action="">
        <h3>{{$miTrastero->getNombre()}}</h3>
        <button name="verTrastero" id="verTrastero"><span>Ver trastero</span></button>
        <button name="añadirProducto" id="añadirProducto"><span>Añadir Producto</span></button>
        <button name="buscarProducto" id="buscarProducto"><span>Buscar Producto</span></button>
        <br/><br/><br/>        
        <button class ="info" name="volverTodosTrasteros">Volver</button>
    </form>
</div>