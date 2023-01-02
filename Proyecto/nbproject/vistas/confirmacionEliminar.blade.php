{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Confirmacion Eliminar')

{{-- Sección mensaje --}}
@section('content')
<link rel="stylesheet" href="asset/css/verTrastero.css">
<script src="asset/js/mostrarProductos.js"></script>

<div class="container">
    <p>Se perderán todas las ubicaciones de los productos guardados en esta ubicación</p>
    <p>¿Desea continuar?</p>
    <form action="" method="POST">
        <input type="submit" name="aceptar" value="SI">
        <input type="submit" name="cancelar" value="NO">
    </form>
</div>
@endsection