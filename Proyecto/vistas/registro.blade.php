

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

    
    <div class="signUp">
        <h2 class="mb-5 text-center">Registro usuario</h2>
        <form action="{{$_SERVER["PHP_SELF"]}}" method="post">

            <div class="form-floating mb-3">
                <input id="inputAlias" type="text" name="alias" placeholder="Alias" 
                class="form-control @if(in_array('usuarioInvalido', $error) || in_array('aliasExiste',$error) ) is-invalid  @endif
                                    @if (!in_array('usuarioInvalido', $error) && !in_array('aliasExiste',$error) && $submited == true ) is-valid @endif" 
                 value="{{$datos['alias']}}" required>
                <label for="inputAlias" class="form-label">Alias</label>
                @if(in_array("usuarioInvalido", $error)) 
                    <div id="invalidAlias" class=" form-text p-1 invalid-feedback">
                        Escribe un alias, solo pueden contener letras, números, guiones y guiones bajos.
                    </div>  
                @endif
                @if(in_array("aliasExiste", $error)) 
                    <div class="form-text p-1 invalid-feedback" >
                        Este alias ya existe
                    </div>  
                @endif
                
                
            </div>
            
            <div class="form-floating mb-3">
                <input id="inputNombre" type="text" name="nombre" placeholder="Nombre" 
                class="form-control @if(in_array('nombreInvalido', $error)) is-invalid  @endif
                                    @if (!in_array('nombreInvalido', $error) && $submited == true ) is-valid @endif"
                value="{{$datos['nombre']}}" required>
                <label for="inputNombre">Nombre</label>
                @if(in_array("nombreInvalido", $error)) 
                    
                    <div class="invalid-feedback form-text p-1" role="alert">
                        Escribe un nombre, solo se admiten letras y espacios en blanco.
                    </div>  
                @endif
            </div>
           

            <div class="form-floating mb-3">
                <input id="inputApellidos" type="text" name="apellidos" placeholder="Apellidos" 
                class="form-control @if(in_array('apellidoInvalido', $error)) is-invalid  @endif
                                    @if (!in_array('apellidoInvalido', $error) && $submited == true ) is-valid @endif" 
                value="{{$datos['apellidos']}}" required>
                <label for="inputApellidos">Apellidos</label>
                @if(in_array("apellidoInvalido", $error)) 
                    
                    <div class="invalid-feedback form-text p-1" role="alert">
                        Debe comenzar por una letra, solo se admiten letras y espacios en blanco.
                    </div>  
                @endif
            </div>
            

            <div class="form-floating mb-3">
                <input id="inputCorreo" type="email" name="correo" placeholder="Correo" 
                class="form-control @if(in_array('correoInvalido', $error) || in_array('correoExiste',$error) ) is-invalid  @endif
                                    @if (!in_array('correoInvalido', $error) && !in_array('correoExiste',$error) && $submited == true ) is-valid @endif"
                value="{{$datos['correo']}}" required>
                <label for="inputCorreo">Correo</label>
                @if(in_array("correoInvalido", $error)) 
                    
                    <div class="invalid-feedback form-text p-1" role="alert">
                        Correo invalido
                    </div>  
                @endif
                @if(in_array("correoExiste", $error)) 
                    
                    <div class="invalid-feedback form-text p-1" role="alert">
                        Este correo ya existe
                    </div>  
                @endif
            </div>
            

            <div class="form-floating mb-3">
                <input id="inputPassword" type="password" name="clave" placeholder="Contraseña" 
                class="form-control @if(in_array('claveInvalida', $error)) is-invalid  @endif
                                    @if (!in_array('claveInvalida', $error) && $submited == true ) is-valid @endif"
                value="{{$datos['clave']}}" required>
                <label for="inputPassword">Contraseña</label>
                @if(in_array("claveInvalida", $error)) 
                    
                    <div class="invalid-feedback form-text p-1">
                        Debe contener minimo 8 caracteres, una mayuscula una miniscula y un número. 
                    </div>  
                @endif
            </div>
            

            <div class="form-floating mb-3">
                <input id="inputPasswordRepeat" type="password" name="claveRepeat" placeholder="Repita la contraseña" 
                class="form-control @if(in_array('clavesNoIguales', $error) || in_array('camposVacios',$error) ) is-invalid  @endif
                                    @if (!in_array('clavesNoIguales', $error) && !in_array('camposVacios',$error) && $submited == true ) is-valid @endif"
                value="{{$datos['claveRepeat']}}" required>
                <label for="inputPasswordRepeat">Repita la contraseña</label>
                @if(in_array("clavesNoIguales", $error)) 
                    
                    <div class="invalid-feedback form-text p-1" role="alert">
                        Las contraseñas no coinciden
                    </div>  
                @endif
                @if(in_array("camposVacios", $error)) 
                    
                    <div class="invalid-feedback form-text p-1" role="alert">
                        Rellene todos los campos
                    </div>  
                @endif
            </div>
           
            
           

            <div class="text-end">
                <a class="btn btn-secondary" href="index.php" role="button">Volver</a>
                <button type="submit" name="registrarse" class="btn btn-primary" value="registrarse">Registrarse</button>
            </div>

        </form>
    </div>

</div>                


@endsection