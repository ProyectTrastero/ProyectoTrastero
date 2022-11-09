{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Ubicacion Caja')

{{-- Sección mensaje --}}
@section('content')
<script src="asset/js/ubicacionCaja.js"></script>
<form action="" method="POST">     
    <div class="container">
        <div>Seleccione la ubicación de la caja:</div>
        <div>
            <label for="estanteria">Estantería</label>
            <select id="seleccionEstanteria" name="estanteria">
                @foreach($estanterias as $clave=>$valor)
                <option>{{$clave}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="balda">Balda</label>
            <select id="seleccionBalda" name="balda">
                
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