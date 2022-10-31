{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Ubicacion Caja')

{{-- Sección mensaje --}}
@section('content')
<form action="" method="POST">     
    <div class="container">
        <div>Seleccione la ubicación de la caja:</div>
        <div>
            <label for="estanteria">Estantería</label>
            <select name="estanteria">
                <option>1</option>
                <option>2</option>
            </select>
        </div>
        <div>
            <label for="balda">Balda</label>
            <select name="balda">
                <option>1</option>
                <option>2</option>
               
            </select>  
        </div>
        <div>
            <label><input type="checkbox" name="sinAsignar">Sin asignar</label>
        </div>
        <div>
            <input class="btn btn-info" type="submit" name="volver" value="Volver">
            <input class="btn btn-info" type="submit" name="añadirUbicacion" value="Añadir">
        </div>
    </div>
</form>
@endsection