
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
            
            <div class="mb-3 inputsForm">
              <label for="nombre" class="text-end">Nombre: </label>
              <input class="form-control" type="text" name="nombre" id="nombre" value="{{$datos['nombre']}}">
              @if(isset($errores) && in_array("nombreInvalido", $errores)) 
                <div></div>
                <div class="textError form-text p-1">
                  Solo se admiten letras y espacios en blanco.
                </div>
              @endif
            </div>
            
            
            <div class="inputsForm mb-3">
              <label for="apellidos" class="text-end">Apellidos: </label>
              <input class="form-control" type="text" name="apellidos" id="apellidos" value="{{$datos['apellidos']}}">
              @if(isset($errores) && in_array("apellidoInvalido", $errores)) 
                <div></div>
                <div class="textError form-text p-1">
                    Solo se admiten letras y espacios en blanco.
                </div>  
              @endif
            </div>
            
            <div class="inputsForm mb-3">
                <label for="alias" class="text-end">Alias: </label>
                <input class="form-control" type="text" name="alias" id="alias" value="{{$datos['alias']}}">
                @if(isset($errores) && in_array("aliasInvalido", $errores)) 
                  <div></div>
                  <div class="textError form-text p-1">
                    Los alias solo pueden contener letras, números, guiones y guiones bajos.
                  </div> 
                @endif
                @if(isset($errores) && in_array("aliasExiste", $errores)) 
                  <div></div>
                  <div class="textError form-text p-1">Este alias ya existe</div>
                 @endif
            </div>

            <div class="inputsForm mb-3">
              <label for="clave" class="text-end">Clave: </label>
              <input class="form-control" type="text" name="clave" id="clave" value="{{$datos['clave']}}">
              @if(isset($errores) && in_array("claveInvalida", $errores)) 
                <div></div>
                <div class="textError form-text p-1">
                    Debe contener minimo 8 caracteres, una mayuscula una miniscula y un número. 
                </div>  
              @endif
            </div>

            <div class="inputsForm mb-3">
              <label for="correo" class="text-end">Correo: </label>
              <input class="form-control" type="email" name="correo" id="Correo" value="{{$datos['correo']}}">
              @if(isset($errores) && in_array("correoInvalido", $errores))
                <div></div> 
                <div class="textError form-text p-1">
                  Correo invalido
                </div>  
                
              @endif
              @if(isset($errores) && in_array("correoExiste", $errores))
                <div></div> 
                <div class="textError form-text p-1">
                  Este correo ya existe
                </div>  
                
              @endif
              @if(isset($errores) && in_array("camposVacios", $errores)) 
                <div></div>
                <div class="textError form-text p-1">
                  Rellene todos los campos
                </div>  
              @endif
              
            </div>
              
            <div class="text-end">
              <button type="submit" class="btn btn-secondary " name="volver">Volver</button>
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
