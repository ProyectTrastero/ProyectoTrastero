
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
           
           <span type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> x </span>
        </div>
      @endif
      <h1 class="ml-4 mb-5">Perfil {{$datos['nombre']}}</h1>
        <form method="POST" action="{{$_SERVER["PHP_SELF"]}}">
            
            <div class="row mb-3">
                <label for="nombre" class="col-sm-2 col-lg-1 col-form-label form-text">Nombre: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="nombre" id="nombre" value="{{$datos['nombre']}}">
                </div>
                
            </div>
            @if(isset($errores) && in_array("nombreInvalido", $errores)) 
            <div class="row mb-3">
              <div class="alert alert-danger form-text p-1 col-11 col-md-8 col-lg-7" role="alert">
                  Solo se admiten letras y espacios en blanco.
              </div>
            </div>
            @endif
            
            <div class="row mb-3">
              <label for="apellidos" class="col-sm-2 col-lg-1 col-form-label form-text">Apellidos: </label>
              <div class="col-10 col-sm-8 col-md-5">
                  <input class="form-control" type="text" name="apellidos" id="apellidos" value="{{$datos['apellidos']}}">
              </div>
              
            </div>
            @if(isset($errores) && in_array("apellidoInvalido", $errores)) 
              <div class="row">
                <div class="alert alert-danger form-text p-1 col-11 col-md-8 col-lg-7" role="alert">
                    Solo se admiten letras y espacios en blanco.
                </div>  
              </div>
            @endif
            <div class="row mb-3">
                <label for="alias" class="col-sm-2 col-lg-1 col-form-label form-text">Alias: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="alias" id="alias" value="{{$datos['alias']}}">
                </div>
                
            </div>
            @if(isset($errores) && in_array("aliasInvalido", $errores)) 
              <div class="row">
                <div class="alert alert-danger form-text p-1 col-11 col-md-8 col-lg-7" role="alert">
                  Los alias solo pueden contener letras, números, guiones y guiones bajos.
                </div> 
              </div> 
            @endif
            @if(isset($errores) && in_array("aliasExiste", $errores)) 
              <div class="row">
                <div class="alert alert-danger form-text p-1 col-11 col-md-8 col-lg-7" role="alert">Este alias ya existe</div>
              </div>  
            @endif
            <div class="row mb-3">
              <label for="clave" class="col-sm-2 col-lg-1 col-form-label form-text">Clave: </label>
              <div class="col-10 col-sm-8 col-md-5">
                  <input class="form-control" type="text" name="clave" id="clave" value="{{$datos['clave']}}">
              </div>
              
            </div>
            @if(isset($errores) && in_array("claveInvalida", $errores)) 
              <div class="row">
                <div class="alert alert-danger form-text p-1 col-11 col-md-8 col-lg-7" role="alert">
                    Debe contener minimo 8 caracteres, una mayuscula una miniscula y un número. 
                </div>  
              </div>
            @endif

            <div class="row mb-3">
              <label for="correo" class="col-sm-2 col-lg-1 col-form-label form-text">Correo: </label>
              <div class="col-10 col-sm-8 col-md-5">
                  <input class="form-control" type="email" name="correo" id="Correo" value="{{$datos['correo']}}">
              </div>
              
            </div>
            @if(isset($errores) && in_array("correoInvalido", $errores)) 
              <div class="row">
                <div class="alert alert-danger form-text p-1 col-11 col-md-8 col-lg-7" role="alert">Correo invalido</div>  
              </div>
            @endif
            @if(isset($errores) && in_array("correoExiste", $errores)) 
              <div class="row">
                <div class="alert alert-danger form-text p-1 col-11 col-md-8 col-lg-7" role="alert">Este correo ya existe</div>  
              </div>
            @endif
            @if(isset($errores) && in_array("camposVacios", $errores)) 
              <div class="row">
                <div class="alert alert-danger form-text p-1 col-11 col-md-8 col-lg-7" role="alert">Rellene todos los campos</div>  
              </div>
            @endif
              
            <div class="row">
              <button type="submit" class="btn btn-primary col-4 col-lg-3" name="volver" value="volver">Volver</button>
              <button type="submit" class="btn btn-primary col-4 col-lg-3 ml-2" name="guardar" value="guardar" >Guardar</button>
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
