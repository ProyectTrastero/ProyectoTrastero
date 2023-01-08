
@extends('app')

@section('title', 'Perfil Usuario')

@section('content')
    <div class="container">

      @if (@isset($_SESSION['msj']))
        <div class="alert alert-{{$_SESSION['msj-type']}} alert-dismissible fade show" role="alert"">
          {{$_SESSION['msj']}}
          {{-- {{unset($_SESSION['msj'])}} --}}
          <?php
           unset($_SESSION['msj']); 
           unset($_SESSION['msj-type']);
           ?>
           
           <span type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></span>
        </div>
      @endif

      <h1 class="text-center mt-5">Perfil {{$usuario->getNombre()}}</h1>
        <form class="formEditPerfil" method="POST" action="{{$_SERVER["PHP_SELF"]}}">
            
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
              
              <a href="acceso.php" class="btn btn-secondary " name="volver">Volver</a>
              <button type="submit" class="btn btn-primary " name="guardar">Guardar</button>
            </div>


        </form>
    </div>
    {{-- modal --}}
    <div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="perfilModalLabel"></h1>
              
            </div>
            <div class="modal-body">
              <form>
                <div class="mb-3">
                  <label id="lblCampo" for="inputCampo" class="col-form-label"></label>
                  <input id="inputCampo" type="text" class="form-control" disabled>
                </div>
                <div class="mb-3">
                  <label id="lblNewCampo" for="inputNewCampo" class="col-form-label"></label>
                  <input id="inputNewCampo" class="form-control" ></input>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button id="btnClose" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Guardar</button>
            </div>
          </div>
        </div>
      </div>
@endsection
