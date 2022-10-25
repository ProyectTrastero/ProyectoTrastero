{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
<div class="">
    <input type="button" name ="perfil" value="{{$usuario->getNombre()}}">
    <input type="button" name="perfilusuario" value="Perfil">       
    <input type="button" name="salir" value="Cerrar Sesion">
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
        @if (isset($_SESSION ['trasteros']))
        <div>
            <form method="POST" action="" id='formtrasteros'>
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
        @endif
</div>

@endsection
