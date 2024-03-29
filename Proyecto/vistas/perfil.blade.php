
@extends('app')

@section('title', 'Perfil Usuario')

@section('content')
    <div class="avisos">
      @if (count($mensaje) > 0)
      <div class="alert alert-{{$mensaje['msj-type']}} alert-dismissible fade show" id="alert" role="alert"">
        {{$mensaje['msj']}}           
        <span type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></span>
      </div>
      @endif
    </div>
    <div class="container">

      <h1 class="text-center mt-5">Perfil {{$usuario->getNombre()}}</h1>
        <form class="formEditPerfil" method="POST" action="{{$_SERVER["PHP_SELF"]}}">
        <div class="divContainer">    
            <div class="form-floating mb-3">
              <input type="text" name="nombre" id="nombre" placeholder="Nombre" value="{{$datos['nombre']}}" required
                class="form-control @if(in_array('nombreInvalido', $errores)) is-invalid  @endif
                                    @if (!in_array('nombreInvalido', $errores) && $submited == true ) is-valid @endif" >
              <label class="form-label" for="nombre">Nombre</label>
              @if(isset($errores) && in_array("nombreInvalido", $errores)) 
                <div class="invalid-feedback form-text p-1">
                  Solo se admiten letras y espacios en blanco.
                </div>
              @endif
            </div>

            
            
            
            <div class="form-floating mb-3">
              <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" value="{{$datos['apellidos']}}" required
                class="form-control @if(in_array('apellidoInvalido', $errores)) is-invalid  @endif
                                    @if (!in_array('apellidoInvalido', $errores) && $submited == true ) is-valid @endif">
              <label class="form-label" for="apellidos">Apellidos</label>
              @if(isset($errores) && in_array("apellidoInvalido", $errores)) 
                <div class="invalid-feedback form-text p-1">
                    Solo se admiten letras y espacios en blanco.
                </div>  
              @endif

            </div>
            
            
            <div class="form-floating mb-3">
              <input type="text" name="alias" id="alias" placeholder="Alias" value="{{$datos['alias']}}" required
                class="form-control @if(in_array('aliasInvalido', $errores) || in_array('aliasExiste',$errores) ) is-invalid  @endif
                                    @if (!in_array('aliasInvalido', $errores) && !in_array('aliasExiste',$errores) && $submited == true ) is-valid @endif" >
              <label for="alias">Alias</label>
              @if(isset($errores) && in_array("aliasInvalido", $errores)) 
                <div class="invalid-feedback form-text p-1">
                  Los alias solo pueden contener letras, números, guiones y guiones bajos.
                </div> 
              @endif
              @if(isset($errores) && in_array("aliasExiste", $errores)) 
                <div class="invalid-feedback form-text p-1">Este alias ya existe</div>
               @endif

            </div>
            

            <div class="form-floating mb-3">
              <input type="text" name="clave" id="clave" placeholder="Clave" value="{{$datos['clave']}}" required
                class="form-control @if(in_array('claveInvalida', $errores)) is-invalid  @endif
                                    @if (!in_array('claveInvalida', $errores) && $submited == true ) is-valid @endif">
              <label for="clave">Clave</label>
              @if(isset($errores) && in_array("claveInvalida", $errores)) 
                <div class="invalid-feedback form-text p-1">
                    Debe contener minimo 8 caracteres, una mayuscula una miniscula y un número. 
                </div>  
              @endif

            </div>
            

            <div class="form-floating mb-3">
              <input type="email" name="correo" id="Correo" placeholder="Correo" value="{{$datos['correo']}}" required
                class="form-control @if(in_array('correoInvalido', $errores) || in_array('correoExiste',$errores) ) is-invalid  @endif
                                    @if (!in_array('correoInvalido', $errores) && !in_array('correoExiste',$errores) && $submited == true ) is-valid @endif">
              <label for="correo">Correo</label>
              @if(isset($errores) && in_array("correoInvalido", $errores))
                <div class="invalid-feedback form-text p-1">
                  Correo invalido
                </div>  
                
              @endif
              @if(isset($errores) && in_array("correoExiste", $errores))
                <div class="invalid-feedback form-text p-1">
                  Este correo ya existe
                </div>  
                
              @endif
              @if(isset($errores) && in_array("camposVacios", $errores)) 
                <div class="invalid-feedback form-text p-1">
                  Rellene todos los campos
                </div>  
              @endif

            </div>
              
            
              
            <div class="text-end">
              
              <a href="acceso.php" class="btn volver">Volver</a>
              <button type="submit" name="guardar">Guardar</button>
            </div>
        </div>
        </form>
    </div>
   
@endsection


<script src="asset/js/eliminarAlert.js"></script>