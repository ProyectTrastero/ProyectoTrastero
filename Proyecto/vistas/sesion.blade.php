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
                <form method="POST" action="" id='formsesion'>
                                <input id="inputNombre" type="text" placeholder="Nombre" name="nombre" class="nombre">
                                <input id="inputPassword" type="password" placeholder="Contraseña" name="clave" class="clave">
                                <a href="#" class="link" name="recuperarcontrasena"> ¿Has olvidado tu contraseña?</a>
                        </br>
                            <button type="submit" id="procsesion" name="procsesion"><span> Login </span> </button>
                        <br/><br/><br/>
               
                
                        <a>¿Aún no te has regitrado?</a><br/>
                        <div>   
                            <button type="button" id="registro" name="botonregistro"><a href="registro.php">Registrarse</a></button>
                        </div>
                 </form>        

        </div>                
@endsection
