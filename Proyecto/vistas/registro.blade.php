

{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Registro Usuario')

{{-- Sección content --}}
@section('content')
<div class="container position-absolute top-0 start-0">
    
    <div class="signup">
        <h2>Registro usuario</h2>
        <form class="" action="registro.php" method="post">

            <div class="mb-3"><input id="inputUsuario" type="text" name="alias" placeholder="Usuario" class="usuario form-control" value="{{$datos['alias']}}"></div>
            @if(isset($error) && in_array("usuarioInvalido", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">
               Los alias solo pueden contener letras, números, guiones y guiones bajos.
            </div>  
            @endif
            @if(isset($error) && in_array("aliasExiste", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Este alias ya existe</div>  
            @endif

            <div class="mb-3"><input id="inputNombre" type="text" name="nombre" placeholder="Nombre" class="nombre form-control" value="{{$datos['nombre']}}"></div>
            @if(isset($error) && in_array("nombreInvalido", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">
                Debe comenzar por una letra, solo se admiten letras y espacios en blanco.
            </div>  
            @endif

            <div class="mb-3"><input id="inputApellidos" type="text" name="apellidos" placeholder="Apellidos" class="apellidos form-control" value="{{$datos['apellidos']}}"></div>
            @if(isset($error) && in_array("apellidoInvalido", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">
                Debe comenzar por una letra, solo se admiten letras y espacios en blanco.
            </div>  
            @endif

            <div class="mb-3"><input id="inputEmail" type="email" name="correo" placeholder="E-mail" class="email form-control" value="{{$datos['correo']}}"></div>
            @if(isset($error) && in_array("emailInvalido", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Correo invalido</div>  
            @endif
            @if(isset($error) && in_array("correoExiste", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Este correo ya existe</div>  
            @endif

            <div class="mb-3"><input id="inputPassword" type="password" name="clave" placeholder="Contraseña" class="pwd form-control" value="{{$datos['clave']}}"></div>
            @if(isset($error) && in_array("contrasenaInvalida", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">
                Debe contener minimo 8 caracteres, una mayuscula una miniscula y un número. 
            </div>  
            @endif

            <div class="mb-3"><input id="inputPasswordRepeat" type="password" name="claveRepeat" placeholder="Repite la contraseña" class="pwdRepeat form-control" value="{{$datos['claveRepeat']}}"></div>
            @if(isset($error) && in_array("contrasenasNoIguales", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Las contraseñas no coinciden</div>  
            @endif
            
            @if(isset($error) && in_array("camposVacios", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Rellene todos los campos</div>  
            @endif

            <button type="submit" name="submit"><span>Registrarse</span></button>

        </form>
    </div>

</div>                


@endsection