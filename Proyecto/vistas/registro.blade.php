

{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Registro Usuario')

{{-- Sección content --}}
@section('content')
<div class="container">
    @if (isset($msj))
        <div class="alert alert-{{$msj['msjType']}} alert-dismissible fade show" role="alert">
            {{$msj['msj']}}
            <?php
                $msj = null;
            ?>
            <span type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></span>
        </div>
        
    @endif

    <h2 class="mb-5 text-center">Registro usuario</h2>
    <div class="signUp  divContainer">
        
        <form action="{{$_SERVER["PHP_SELF"]}}" method="post">

            <div class="mb-3 inputsForm">
                <label for="inputAlias" class="form-label">Alias: </label>
                <input id="inputAlias" type="text" name="alias" placeholder="Alias" class="form-control" value="{{$datos['alias']}}">
                @if(isset($error) && in_array("usuarioInvalido", $error)) 
                    <div></div>
                    <div class=" form-text textError p-1" >
                        Los alias solo pueden contener letras, números, guiones y guiones bajos.
                    </div>  
                @endif
                @if(isset($error) && in_array("aliasExiste", $error)) 
                    <div></div>
                    <div class="textError form-text p-1" >
                        Este alias ya existe
                    </div>  
                @endif
            
            
            
                <label for="inputNombre">Nombre: </label>
                <input id="inputNombre" type="text" name="nombre" placeholder="Nombre" class="form-control" value="{{$datos['nombre']}}">
                @if(isset($error) && in_array("nombreInvalido", $error)) 
                    <div></div>
                    <div class="textError form-text p-1" role="alert">
                        Solo se admiten letras y espacios en blanco.
                    </div>  
                @endif
            
           

            
                <label for="inputApellidos">Apellidos: </label>
                <input id="inputApellidos" type="text" name="apellidos" placeholder="Apellidos" class="form-control" value="{{$datos['apellidos']}}">
                @if(isset($error) && in_array("apellidoInvalido", $error)) 
                    <div></div>
                    <div class="textError form-text p-1" role="alert">
                        Debe comenzar por una letra, solo se admiten letras y espacios en blanco.
                    </div>  
                @endif
            
            

            
                <label for="inputCorreo">Correo: </label>
                <input id="inputCorreo" type="email" name="correo" placeholder="Correo" class="form-control" value="{{$datos['correo']}}">
                @if(isset($error) && in_array("correoInvalido", $error)) 
                    <div></div>
                    <div class="textError form-text p-1" role="alert">
                        Correo invalido
                    </div>  
                @endif
                @if(isset($error) && in_array("correoExiste", $error)) 
                    <div></div>
                    <div class="textError form-text p-1" role="alert">
                        Este correo ya existe
                    </div>  
                @endif
            
            

            
                <label for="inputPassword">Contraseña: </label>
                <input id="inputPassword" type="password" name="clave" placeholder="Contraseña" class="form-control" value="{{$datos['clave']}}">
                @if(isset($error) && in_array("claveInvalida", $error)) 
                    <div></div>
                    <div class="textError form-text p-1">
                        Debe contener minimo 8 caracteres, una mayuscula una miniscula y un número. 
                    </div>  
                @endif
            
            

            
                <label for="inputPasswordRepeat">Repita la contraseña: </label>
                <input id="inputPasswordRepeat" type="password" name="claveRepeat" placeholder="Repita la contraseña" class="form-control" value="{{$datos['claveRepeat']}}">
                @if(isset($error) && in_array("clavesNoIguales", $error)) 
                    <div></div>
                    <div class="textError form-text p-1" role="alert">
                        Las contraseñas no coinciden
                    </div>  
                @endif
                @if(isset($error) && in_array("camposVacios", $error)) 
                    <div></div>
                    <div class="textError form-text p-1" role="alert">
                        Rellene todos los campos
                    </div>  
                @endif
            </div>

            <div class="text-end">
                <button type="submit" class="volver" name="volver"  value="volver">Volver</button>
                <button type="submit" name="registrarse" value="registrarse">Registrarse</button>
            </div>
        </form>
    </div>

</div>                


@endsection