{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Trastero Seleccionado')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
<div class="menu">
<form method="POST" action="acceso.php" id="formnav">
    <input type="submit" name="nameusuario" value="{{$usuario->getNombre()}}">
    <input type="submit" name="perfilusuario" value="Perfil">      
    <input type="submit" name="salir" value="Cerrar Sesion">
</form>
</div>
@endsection

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