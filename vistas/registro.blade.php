{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Registro Usuario')

{{-- Sección mensaje --}}
@section('content')
<div class="container position-absolute top-0 start-0">
    
    <div class="signup">
        <h2>Registro usuario</h2>
        <form class="" action="../includes/Registro.inc.php" method="post">

            <div class="mb-3"><input id="inputUsuario" type="text" name="usuario" placeholder="Usuario" class="usuario form-control" value="{{$datos['usuario']}}"></div>
            @if(isset($error) && in_array("usuarioInvalido", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Usuario invalido</div>  
            @endif
            @if(isset($error) && in_array("usuarioExiste", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Este nombre de usuario o email ya existe</div>  
            @endif

            <div class="mb-3"><input id="inputNombre" type="text" name="nombre" placeholder="Nombre" class="nombre form-control" value="{{$datos['nombre']}}"></div>
            @if(isset($error) && in_array("nombreInvalido", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Nombre invalido</div>  
            @endif

            <div class="mb-3"><input id="inputApellidos" type="text" name="apellido" placeholder="Apellido" class="apellidos form-control" value="{{$datos['apellido']}}"></div>
            @if(isset($error) && in_array("apellidoInvalido", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Apellido invalido</div>  
            @endif

            <div class="mb-3"><input id="inputTelefono" type="number" name="telefono" placeholder="Telefono" class="telefono form-control" value="{{$datos['telefono']}}"></div>
            @if(isset($error) && in_array("telefonoInvalido", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Telefono invalido</div>  
            @endif

            <div class="mb-3"><input id="inputEmail" type="email" name="email" placeholder="E-mail" class="email form-control" value="{{$datos['email']}}"></div>
            @if(isset($error) && in_array("emailInvalido", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Email invalido</div>  
            @endif

            <div class="mb-3"><input id="inputPassword" type="password" name="contrasena" placeholder="Contraseña" class="pwd form-control" value="{{$datos['contrasena']}}"></div>
            @if(isset($error) && in_array("contrasenaInvalida", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Contraseña invalido</div>  
            @endif

            <div class="mb-3"><input id="inputPasswordRepeat" type="password" name="contrasenaRepeat" placeholder="Repite la contraseña" class="pwdRepeat form-control" value="{{$datos['contrasenaRepeat']}}"></div>
            @if(isset($error) && in_array("contrasenasNoIguales", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Las contraseñas no coinciden</div>  
            @endif
            
            @if(isset($error) && in_array("camposVacios", $error)) 
            <div class="alert alert-danger form-text p-1" role="alert">Rellene todos los campos</div>  
            @endif

            <button type="submit" name="submit" class="btn btn-primary">Registrarse</button>

        </form>
    </div>


    <!-- comment 
            <form method="POST" action="index.php" id='formregistro'>
                    <input id="inputUsuario" type="text" placeholder="Usuario" class="usuario">
                    <br/>
                    <input id="inputNombre" type="text" placeholder="Nombre" class="nombre">
                    <br/>
                    <input id="inputApellidos" type="text" placeholder="Apellidos" class="apellidos">
                    <br/>
                    <input id="inputEmail" type="text" placeholder="Email" class="email">
                    <br/>
                    <input id="inputPassword" type="password" placeholder="Contraseña" class="pwd">
            </form>
            </br></br>                
    <button class="registro" href="index.php?botonproregistro" id="proregistro">
            <span>Confirmar</span>
    </button>
    @if (isset($error))
            <div class="alert alert-danger" role="alert">Error al crear Usuario</div>
    @endif
    
    -->
</div>                
@endsection