
{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección mensaje --}}
@section('content')
    @if (isset($_SESSION ['usuario']))
        <div class="container">
            <div>
                <h3>Bienvenido {$usuario->getNombre()} a la mejor solucion para tu trastero, podras añadir, modificar y ordenar tu trastero desde tu casa</h3>
            </div>
            <br/>
            <div>
                <a type="button" href="index.php?botonlistar" name="botonlistar" id="listar">
                    <span class="bi bi-list">   Ver mis trasteros</span></a>
                <a type="button" href="index.php?botoncrear" name="botoncrear" id="crear">
                    <span class="bi bi-plus-circle">   Crear nuevo trastero</span></a>
            </div>
        </div>
    @else
        <div class="container">
            <div>
                <h3>Bienvenido a la mejor solucion para tu trastero, podras añadir, modificar y ordenar tu trastero desde tu casa</h3>
            </div>
            <br/>
            <div>
                <a type="button" href="index.php?botonsesion" name="botonsesion" id="sesion">
                    <span class="bi bi-person">   Iniciar Sesion</span></a>
                <a type="button" href="index.php?botonregistro" name="botonregistro" id="registro">
                    <span class="bi bi-person-plus-fill">   Registrar Usuario</span></a>
            </div>
        </div>
    @endif

@endsection
