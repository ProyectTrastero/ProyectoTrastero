{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
<div class="dropdown">
    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{$usuario->getNombre()}}</button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#" name="botonperfil">Perfil</a>
        <a class="dropdown-item" href="#" name="botonlogout">Cerrar sesión</a>
        
    </div>
</div>
@endsection

{{-- Sección mensaje --}}
@section('content')

<div class="container">
        <div>
            <h3>Diseña tu trastero</h3>
            <button name="añadirTrastero" id="añadirTrastero"><span>Diseñar</span></button>
        </div>
        <br/><br/><br/><br/>
        <div>
            <form method="POST" action="trasteros.php" id='formtrasteros'>
                <h3>Mis trasteros</h3>
        @foreach ($trasteros as $valor)    
                <table class="row">
                    <td class="col-4"> {{$valor->getNombre()  }}</td>
                    <br/><br/>   
                    <td  class="col-1"><button type="submit" name="acceder" id='acceder'> Acceder</button></td>
                    <td class="col-1"><button type="submit" name="modificar" id='modificar'> Modificar</button></td>
                </table>
        @endforeach    
                <br/><br/><br/>
        
            </form>
                
        </div>
</div>

@endsection
