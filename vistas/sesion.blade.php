{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Inicio de Sesion')

{{-- Sección mensaje --}}
@section('mensaje')
        <div class="container">
                <h2>Iniciar sesion</h2>
                        <form method="POST" action="index.php" id='formsesion'>
                                <input id="inputNombre" type="text" placeholder="Nombre" class="nombre">
                                <br/>
                                <input id="inputPassword" type="password" placeholder="Contraseña" class="pwd">
                        </form>
                                <a href="#" class="link"> ¿Has olvidado tu contraseña?</a>
                        </br>
               
                        <button type="button" href="index.php?prosesion" id="prosesion">
                                <span>Login</span>
                        </button>
                        <button type="button" href="index.php?botonregistro" id="registro">
                                <span> Registrarse</span>
                        </button>
        @if (isset($error))
                <div class="alert alert-danger" role="alert">Error Credenciales</div>
        @endif        
        </div>                
@endsection
