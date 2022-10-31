{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Recuperar Contraseña')

{{-- Sección mensaje --}}
@section('content')
<form action="" method="POST">     
    <div class="container">
        <div>
            <label for="correo">Correo electrónico</label>
            <input type="text" name="correo" placeholder="Correo">
        </div>
        <div>
            <p>{{$mensaje}}</p>
        </div>
        <div>
            <input class="btn btn-info" type="submit" name="enviar" value="Enviar">
            <input class="btn btn-info" type="submit" name="volver" value="Volver">
        </div>
    </div>
</form>
@endsection
