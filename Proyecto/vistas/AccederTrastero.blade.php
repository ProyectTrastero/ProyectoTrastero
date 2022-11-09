{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Trastero')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
<div class="btn-group">
    <input type="button" class="btn btn-default dropdown-toggle"
          data-toggle="dropdown"> {{$usuario->getNombre()}} <span class="caret"></span>
    <input type="button" name="perfilusuario" value="Perfil">       
    <input type="button" name="salir" value="Cerrar Sesion">
</div>
@endsection

{{-- Sección mensaje --}}
@section('content')
<div class="container">
    <div>
        <h3>{{$miTrastero->getNombre()}}</h3>
        <button name="verTrastero" id="verTrastero"><span>Ver trastero</span></button>
        <button name="añadirProducto" id="añadirProducto"><span>Añadir Producto</span></button>
        <button name="buscraProducto" id="buscarProducto"><span>Buscar Producto</span></button>
    </div>
</div>