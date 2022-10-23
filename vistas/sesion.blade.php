{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Inicio de Sesion')

{{-- Sección mensaje --}}
@section('content')
        <div class="container">
                <h2>Iniciar sesion</h2>
                @if (isset($error))
                <div class="alert alert-danger" role="alert">Error Credenciales</div>
                @endif 
                        <form method="POST" action="index.php" id='formsesion'>
                                <input id="inputNombre" type="text" placeholder="Nombre" name="nombre" class="nombre">
                                <input id="inputPassword" type="password" placeholder="Contraseña" name="clave" class="clave">
                                <a href="#" class="link" name="forPassword"> ¿Has olvidado tu contraseña?</a>
                        </br>
                            <button type="submit" id="procsesion" name="procsesion"><span> Login </span> </button>
                        <br/><br/><br/>
                        
                        <a>¿Aún no te has regitrado?</a><br/>
                        <div>   
                            <button type="submit" id="registro" name="botonregistro"><span> Registrarse </span></button>
                        </div>
                        </form>

        </div>                
@endsection